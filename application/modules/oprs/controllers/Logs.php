<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
class Logs extends OPRS_Controller {
	public function __construct() {
		parent::__construct();
		if (!$this->session->userdata('_oprs_logged_in')) {
			redirect('oprs/login');
		}
		$this->load->model('Log_model');
		$this->load->model('User_model');
		$this->load->library('Csvreader');
		$this->load->model('Manuscript_model');
		$this->load->model('Feedback_model');
		$this->load->model('Arta_model');
		$this->load->helper('url');
	}

	public function index() {
		if ($this->session->userdata('_oprs_logged_in')) {
			if($this->session->userdata('sys_acc') == 2 || $this->session->userdata('sys_acc') == 3 ){
				if (_UserRoleFromSession() != 1 && _UserRoleFromSession() != 16) { // can access except author and peer reviewers

					$module_access_session = $this->session->userdata('_' . _UserIdFromSession() . '_acc_logs');
					if($module_access_session == 1){
						$data['main_title'] = "OPRS";
						$data['main_content'] = "oprs/logs";
						$data['all_logs'] = $this->Log_model->get_logs(317);
						$data['logs'] = $this->Log_model->count_logs();
						$data['man_all'] = $this->Manuscript_model->get_manus(_UserRoleFromSession());
						$data['man_all_count'] = count($data['man_all']);	
						$data['usr_count'] = $this->User_model->count_user();
						$data['arta_count'] = count($this->Arta_model->get_arta());
						$data['feed_count'] = $this->Feedback_model->count_feedbacks();
						$this->_LoadPage('common/body', $data);
					}else{
						redirect('oprs/manuscripts');
					}
				}else {
					redirect('oprs/dashboard');
				}
			}else{
				redirect('admin/dashboard');
			}
		}
	}

	/**
	 * this function save export action to log
	 *
	 * @param   string  $log  action
	 *
	 * @return  void
	 */
	public function log_export($log) {
		$data['log_user_id'] = _UserIdFromSession();
		$data['log_user_role'] = _UserRoleFromSession();
		$data['log_action'] = rawurldecode($log);
		$data['date_created'] = date('Y-m-d H:i:s');
		$this->Log_model->save_log_export(array_filter($data));
	}

	/**
	 * this function retreive activity logs
	 *
	 * @return  array  logs
	 */
	public function get_logs($flag){
		if($flag == 0){
			$output = $this->Log_model->get_logs(0);
		}else{
			if(_UserRoleFromSession() == 3 || _UserRoleFromSession() == 20)
			$output = $this->Log_model->get_logs(317);
		}
		echo json_encode($output);
	}

	/**
	 * this function retreive unopened logs
	 *
	 * @param   int  $id  row id
	 *
	 * @return  void
	 */
	public function notif_open($id)
	{
		$post['notif_open'] = 1;
		$where['row_id'] = $id;
		$output = $this->Log_model->notif_open($post, $where);
		echo json_encode($output);
	}

	public function import_backup()
	{
		$this->Log_model->clear_logs();

		$data = array();
		$csv =  $_FILES["import_backup"]["tmp_name"];
		$handle = fopen($csv,"r");
		
		while (($row = fgetcsv($handle, 10000, ",")) != FALSE) //get row vales
		{
			
			
			if($row[0] != 'LOG USER ID')
	
			$data['log_user_id'] = $row[0];
			$data['log_action'] = $row[1];
			$data['log_insert_id'] = $row[2];
			$data['log_user_role'] = $row[3];
			$data['date_created'] = $row[4];
			$data['notif_open'] = $row[5];

			$this->Log_model->import_logs($data);

		}

	
	}

	public function export_logs()
	{
		 // file name 
		 $filename = 'Activity_logs_backup_'.date('Ymd').'.csv'; 
		 header("Content-Description: File Transfer"); 
		 header("Content-Disposition: attachment; filename=$filename"); 
		 header("Content-Type: application/csv; ");
		 
		 // get data 
		 $logs = $this->Log_model->get_logs_backup();
		 // file creation 
		 $file = fopen('php://output', 'w');
	   
		 $header = array('LOG USER ID', 'LOG ACTION', 'LOG INSERT ID', 'LOG USER ROLE', 'DATE CREATED', 'NOTIF OPEN'); 
		 fputcsv($file, $header);
		 foreach ($logs as $key=>$line){ 
		   fputcsv($file,$line); 
		 }
		 fclose($file); 
		 exit; 
	}

	public function clear_logs()
	{
		$this->Log_model->clear_logs();
	}
}