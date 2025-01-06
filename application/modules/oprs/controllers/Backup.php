<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

/**
 * File Name: Backup.php
 * ----------------------------------------------------------------------------------------------------
 * Purpose of this file:
 * To backup/restore database and tables
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



class Backup extends OPRS_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model('Feedback_model');
		$this->load->model('Log_model');
		$this->load->model('Library_model');
		$this->load->model('User_model');
        $this->load->model('Manuscript_model');
        $this->load->model('Arta_model');
		$this->load->helper('is_online_helper');
		$this->load->model('client/Library_model');
    }

    public function index(){
      
		if ($this->session->userdata('_oprs_logged_in')) {
			if($this->session->userdata('sys_acc') == 2 || $this->session->userdata('sys_acc') == 3 ){
				if (_UserRoleFromSession() == 17) {
					$data['main_title'] = "OPRS";
					$data['main_content'] = "oprs/database";
					$data['logs'] = $this->Log_model->count_logs();
					$data['tables'] = $this->Library_model->get_tables();
					$data['manus'] = $this->Manuscript_model->get_manus($this->session->userdata('_oprs_srce'), $this->session->userdata('_oprs_username'));
					$data['man_onreview'] = $this->Manuscript_model->get_manuscripts(2);
					$data['man_reviewed'] = $this->Manuscript_model->get_manuscripts(3);
					$data['man_final'] = $this->Manuscript_model->get_manuscripts(4);
					$data['man_for_p'] = $this->Manuscript_model->get_manuscripts(5);
					$data['man_pub'] = $this->Manuscript_model->get_manuscripts(6);	
					$data['usr_count'] = $this->User_model->count_user();
					$data['arta_count'] = count($this->Arta_model->get_arta());
					$data['feed_count'] = $this->Feedback_model->count_feedbacks();
					$this->_LoadPage('common/body', $data);
				}else if(_UserRoleFromSession() == 5 || _UserRoleFromSession() == 12 || _UserRoleFromSession() == 6){
					redirect('oprs/manuscripts');
				}else {
					redirect('oprs/dashboard');
				}
			}else{
				redirect('admin/dashboard');
			}
		}
	}

    public function export(){
        // Database configuration
        $host = 'localhost';
        $username = 'skms';
        $password = '$km$@NRCP@1933';
        $database_name = 'dboprs';

        // Get connection object and set the charset
        $conn = mysqli_connect($host, $username, $password, $database_name);
        $conn->set_charset("utf8");

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
            save_log_oprs(_UserIdFromSession(), 'created backup of OPRS database. ('.$backup_file_name.')', '', _UserRoleFromSession());
        }
    }

    public function import(){

        // Name of the data file
        $filename = $_FILES['import_file']['name'];

        // MySQL host
        $mysqlHost = 'localhost';
        // MySQL username
        $mysqlUser = 'skms';
        // MySQL password
        $mysqlPassword = '$km$@NRCP@1933';
        // Database name
        $mysqlDatabase = 'dboprs';

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
        save_log_oprs(_UserIdFromSession(), 'imported backup of OPRS database. ('.$filename.')', '', _UserRoleFromSession());
    }
}


/* End of file Backup.php */