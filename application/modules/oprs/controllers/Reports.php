<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
class Reports extends OPRS_Controller {
	public function __construct() {
		parent::__construct();
		if (!$this->session->userdata('_oprs_logged_in')) {
			redirect('oprs/login');
		}
		$this->load->model('Report_model');
		$this->load->model('Manuscript_model');
		$this->load->model('Coauthor_model');
		$this->load->model('Review_model');
		$this->load->model('Log_model');
		$this->load->model('Feedback_model');
		$this->load->model('Dashboard_model');
		$this->load->model('Arta_model');
	}
	
	public function index() {
		if ($this->session->userdata('_oprs_logged_in')) {
			if($this->session->userdata('sys_acc') == 2 || $this->session->userdata('sys_acc') == 3 ){
				if (_UserRoleFromSession() != 1 && _UserRoleFromSession() != 16) { // can access except author and peer reviewers

					$module_access_session = $this->session->userdata('_' . _UserIdFromSession() . '_acc_reports');
					if($module_access_session == 1){
						$data['main_title'] = "OPRS";
						$data['main_content'] = "oprs/reports";
						$data['manus'] = $this->Report_model->get_list_manus();
						$data['ndas'] = $this->Manuscript_model->get_ndas();
						$data['reviewers'] = $this->Manuscript_model->get_reviewers_reviewed();
						$data['reviewed'] = $this->Manuscript_model->get_reviewed_manuscript();
						$data['completed'] = $this->Manuscript_model->get_completed_reviews();
						$data['criteria'] = $this->Review_model->get_criterias();
						$data['logs'] = $this->Log_model->count_logs();
						$data['man_all'] = $this->Manuscript_model->get_manus(_UserRoleFromSession());
						$data['man_all_count'] = count($data['man_all']);
						$data['man_new'] = $this->Manuscript_model->get_manuscripts(1);
						$data['man_onreview'] = $this->Manuscript_model->get_manuscripts(2);
						$data['man_reviewed'] = $this->Manuscript_model->get_manuscripts(3);
						$data['man_final'] = $this->Manuscript_model->get_manuscripts(4);
						$data['man_for_p'] = $this->Manuscript_model->get_manuscripts(5);
						$data['man_pub'] = $this->Manuscript_model->get_manuscripts(6);	
						$data['usr_count'] = $this->User_model->count_user();
						$data['arta_count'] = count($this->Arta_model->get_arta());
						$data['feed_count'] = $this->Feedback_model->count_feedbacks();
						$data['lapreq'] = $this->Dashboard_model->get_lap_req();
						$data['decreq'] = $this->Dashboard_model->get_dec_req();
						$data['laprev'] = $this->Dashboard_model->get_lap_rev();
						$this->_LoadPage('common/body', $data);
					}else{
						redirect('oprs/manuscripts');
					}
				}else {
					redirect('oprs/dashboard');
				}
			}else{
				redirect ('admin/dashboard');
			}
		}
	}
}