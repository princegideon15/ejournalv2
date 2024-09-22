<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
class Controls extends OPRS_Controller {
	public function __construct() {
		parent::__construct();
		if (!$this->session->userdata('_oprs_logged_in')) {
			redirect('oprs/login');
		}
		$this->load->model('User_model');
		$this->load->model('Log_model');
		$this->load->model('Manuscript_model');
		$this->load->model('Feedback_model');
	}
	
	public function index() {
		if ($this->session->userdata('_oprs_logged_in')) {
			if($this->session->userdata('sys_acc') == 2 || $this->session->userdata('sys_acc') == 3 ){
				if(_UserRoleFromSession() == 8){
					$id = $this->session->userdata('_oprs_user_id');
					$data['users'] = $this->User_model->get_user($id);
					$data['main_title'] = "OPRS";
					$data['main_content'] = "oprs/controls";
					$data['logs'] = $this->Log_model->count_logs();
					$data['manus'] = $this->Manuscript_model->get_manus($this->session->userdata('_oprs_srce'), $this->session->userdata('_oprs_username'));
					$data['man_new'] = $this->Manuscript_model->get_manuscripts(1);
					$data['man_onreview'] = $this->Manuscript_model->get_manuscripts(2);
					$data['man_reviewed'] = $this->Manuscript_model->get_manuscripts(3);
					$data['man_final'] = $this->Manuscript_model->get_manuscripts(4);
					$data['man_for_p'] = $this->Manuscript_model->get_manuscripts(5);
					$data['man_pub'] = $this->Manuscript_model->get_manuscripts(6);	
					$data['usr_count'] = $this->User_model->count_user();
					$data['feed_count'] = $this->Feedback_model->count_feedbacks();
					$this->_LoadPage('common/body', $data);
					$this->session->unset_userdata('_oprs_usr_message');
				}else if(_UserRoleFromSession() == 5 || _UserRoleFromSession() == 12 || _UserRoleFromSession() == 6){
					redirect('oprs/manuscripts');
				}else{
					redirect('oprs/dashboard');
				}
			} else {
				redirect('admin/dashboard');
			}
		}
	}
}