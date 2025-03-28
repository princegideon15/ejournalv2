<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
class Dashboard extends OPRS_Controller {
	public function __construct() {
		parent::__construct();
		if (!$this->session->userdata('_oprs_logged_in')) {
			redirect('oprs/login');
		}
		$this->load->model('Manuscript_model');
		$this->load->model('Login_model');
		$this->load->model('Coauthor_model');
		$this->load->model('Review_model');
		$this->load->model('Dashboard_model');
		$this->load->model('Log_model');
		$this->load->model('Feedback_model');
		$this->load->model('User_model');
		$this->load->model('Arta_model');
	}
	
	public function index() {
		
		if ($this->session->userdata('_oprs_logged_in')) {
			if($this->session->userdata('sys_acc') == 2 || $this->session->userdata('sys_acc') == 3 ){
				if (_UserRoleFromSession() != 1 && _UserRoleFromSession() != 16) { // can access except author and peer reviewers

					$module_access_session = $this->session->userdata('_' . _UserIdFromSession() . '_acc_dashboard');
					if($module_access_session == 1){
						$data['reviewers'] = $this->Manuscript_model->get_reviewers_reviewed();
						$data['reviewed'] = $this->Manuscript_model->get_reviewed_manuscript();
						$data['completed'] = $this->Manuscript_model->get_completed_reviews();
						$data['new'] = $this->Dashboard_model->get_new_manus();
						$data['lapreq'] = $this->Dashboard_model->get_lap_req();
						$data['decreq'] = $this->Dashboard_model->get_dec_req();
						$data['laprev'] = $this->Dashboard_model->get_lap_rev();
						$data['logs'] = $this->Log_model->count_logs();
						$data['count_feedbacks'] = $this->Feedback_model->count_feedbacks();
						$data['man_all'] = $this->Manuscript_model->get_manus(_UserRoleFromSession());
						$data['man_all_count'] = count($data['man_all']);
						$data['man_new'] = $this->Manuscript_model->get_manuscripts(1);
						$data['man_pub'] = $this->Manuscript_model->get_published_manus();
						$data['usr_count'] = $this->User_model->count_user();
						$data['arta_count'] = count($this->Arta_model->get_arta());
						$data['feed_count'] = $this->Feedback_model->count_feedbacks();
						$data['main_title'] = "OPRS";
						$data['main_content'] = "oprs/index";
						$this->_LoadPage('common/body', $data);
					}else{
						redirect('oprs/manuscripts');
					}
				}
			}
		}
	}
}