<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
class Notifications extends OPRS_Controller {
	public function __construct() {
		parent::__construct();
		if (!$this->session->userdata('_oprs_logged_in')) {
			redirect('oprs/login');
		}
        $this->load->model('Log_model');
        $this->load->model('Manuscript_model');
        $this->load->model('User_model');
	}
	
	/**
	 * Display notifications
	 *
	 * @return void
	 */
	public function index() {
		if ($this->session->userdata('_oprs_logged_in')) {
			if($this->session->userdata('sys_acc') == 2 || $this->session->userdata('sys_acc') == 3 ){
				if (_UserRoleFromSession() == 3 || _UserRoleFromSession() == 8) {
					$data['main_title'] = "OPRS";
					$data['main_content'] = "oprs/notifications";
					$data['all_logs'] = $this->Log_model->get_logs('0');
					$data['logs'] = $this->Log_model->count_logs();
					$this->_LoadPage('common/body', $data);
				}else if(_UserRoleFromSession() == 5 || _UserRoleFromSession() == 12 || _UserRoleFromSession() == 6){
					redirect('oprs/manuscripts');
				}else {
					redirect('oprs/dashboard');
				}
			} else {
				redirect('admin/dashboard');
			}
		}
	}

	/**
	 * Retreieve notifications
	 *
	 * @return void
	 */
	public function notif_tracker(){
		$output = $this->Manuscript_model->notif_tracker();
		echo json_encode($output);
	}
	
}

/* End of file Notifications.php */