<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

/**
 * File Name: Backup.php
 * ----------------------------------------------------------------------------------------------------
 * Purpose of this file:
 * To backup/restore ejournal database and tables
 * ----------------------------------------------------------------------------------------------------
 * System Name: Online Research Journal System
 * ----------------------------------------------------------------------------------------------------
 * Author: Gerard Paul D. Balde
 * ----------------------------------------------------------------------------------------------------
 * Date of revision: Jun 30, 2021
 * ----------------------------------------------------------------------------------------------------
 * Copyright Notice:
 * Copyright (C) 2018 by the Department of Science and Technology - National Research Council of the Philiipines
 */
class Backup extends EJ_Controller {

	public function __construct() {
		parent::__construct();

         // Load the necessary helpers and models
         $this->load->helper('download');
         $this->load->database();
         $this->load->model('Log_model');

		if (!$this->session->userdata('_oprs_logged_in')) {
			redirect('oprs/login');
		}

	}
    
    public function export(){

        // Database configuration
        $host = 'localhost';
        $username = 'skms';
        $password = '$km$@NRCP@1933';
        $database_name = 'dbej';

        // Get connection object and set the charset
        $conn = mysqli_connect($host, $username, $password, $database_name);
        $conn->set_charset('utf8');

        $tables = array();

        $method = $this->input->post('export_method', TRUE);

        // custom data only
        if($method == 2){


            // echo json_encode($request->input('table_structure', TRUE));
            // to be continue july


            $tables = $this->input->post('table_data', TRUE);

        }else{
            // Get All Table Names From the Database
            $sql = "SHOW TABLES";
            $result = mysqli_query($conn, $sql);

            while ($row = mysqli_fetch_row($result)) {
                $tables[] = $row[0];
            }
        }

        

        $sqlScript = "";
        foreach ($tables as $table) {
            
            // Prepare SQLscript for creating table structure
            $query = "SHOW CREATE TABLE $table";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_row($result);
            
            if($method == 1){
                $sqlScript .= "\n\n" . $row[1] . ";\n\n";
            }
            
            
            $query = "SELECT * FROM $table";
            $result = mysqli_query($conn, $query);
            
            $columnCount = mysqli_num_fields($result);
            
            // Prepare SQLscript for dumping data for each table
            for ($i = 0; $i < $columnCount; $i ++) {
                while ($row = mysqli_fetch_row($result)) {
                    $sqlScript .= "INSERT IGNORE INTO $table VALUES(";
                    for ($j = 0; $j < $columnCount; $j ++) {
                        $row[$j] = $row[$j];
                        
                        if (isset($row[$j])) {
                            $sqlScript .= '\'' . addslashes($row[$j]) . '\'';
                        } else {
                            $sqlScript .= '\'\'';
                        }
                        if ($j < ($columnCount - 1)) {
                            $sqlScript .= ',';
                        }
                    }
                    $sqlScript .= ");\n";
                }
            }
            
            $sqlScript .= "\n"; 
        }

        if(!empty($sqlScript))
        {
            // Save the SQL script to a backup file
            $backup_file_name = $database_name . '_backup_' . time() . '.sql';
            $fileHandler = fopen($backup_file_name, 'w+');
            $number_of_lines = fwrite($fileHandler, $sqlScript);
            fclose($fileHandler); 

            // Download the SQL backup file to the browser
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($backup_file_name));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($backup_file_name));
            ob_clean();
            flush();
            readfile($backup_file_name);
            exec('rm ' . $backup_file_name); 
            save_log_ej(_UserIdFromSession(), 'Created backup of EJOURNAL database. ('.$backup_file_name.')', '');
        }
    }

    public function import(){

        // Name of the data file
        $filename = $_FILES['import_file']['name'];
        // $filename = $this->input->post('import_file', TRUE);

        // echo $filename;


        // MySQL host
        $mysqlHost = 'localhost';
        // MySQL username
        $mysqlUser = 'skms';
        // MySQL password
        $mysqlPassword = '$km$@NRCP@1933';
        // Database name
        $mysqlDatabase = 'dbej';

        $conn = mysqli_connect($mysqlHost, $mysqlUser, $mysqlPassword , $mysqlDatabase);

        $query = '';
        $sqlScript = file($filename);
        foreach ($sqlScript as $line)	{

            $startWith = substr(trim($line), 0 ,2);
            $endWith = substr(trim($line), -1 ,1);

            if (empty($line) || $startWith == '--' || $startWith == '/*' || $startWith == '//') {
                continue;
            }

            $query = $query . $line;
            if ($endWith == ';') {
                mysqli_query($conn,$query) or die('<div class="error-response sql-import-response">Problem in executing the SQL query <b>' . $query. '</b></div>');
                $query= '';		
            }
        }
        echo '1';

		save_log_ej(_UserIdFromSession(), 'Imported backup of EJOURNAL database. ('.$filename.')','');
    }

    public function export_clear_log(){
         // Fetch data from the model
         $data = $this->Log_model->get_logs_only(); // Customize this method in your model to fetch required data

         // Convert data to CSV format
         $csv_data = $this->format_csv($data);
 
         // Set CSV file name
         $file_name = 'exported_data_' . date('Y-m-d') . '.csv';

         $data = $this->Log_model->clear_logs(); // Customize this method in your model to fetch required data
 
         // Force download the CSV file
         force_download($file_name, $csv_data);
    }

    private function format_csv($data) {
        $csv = '';
        if (!empty($data)) {
            // Get the header
            $header = array_keys((array) $data[0]);
            $csv .= implode(',', $header) . "\n";

            // Get the data rows
            foreach ($data as $row) {
                $csv .= implode(',', (array) $row) . "\n";
            }
        }
        return $csv;
    }

}

/* End of file Backup.php */