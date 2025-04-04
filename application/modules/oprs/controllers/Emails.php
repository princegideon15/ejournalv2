<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
class Emails extends OPRS_Controller {
	public function __construct() {
		parent::__construct();
		if (!$this->session->userdata('_oprs_logged_in')) {
			redirect('oprs/login');
		}
		$this->load->model('Log_model');
		$this->load->model('User_model');
		$this->load->model('Email_model');
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

					$module_access_session = $this->session->userdata('_' . _UserIdFromSession() . '_acc_lib');
					if($module_access_session == 1){
						$data['main_title'] = "OPRS";
						$data['main_content'] = "oprs/emails";
						$data['user_roles'] = $this->Email_model->get_email_user_roles();
						$data['emails'] = $this->Email_model->get_contents();
						$data['man_all'] = $this->Manuscript_model->get_manus(_UserRoleFromSession());
						$data['man_all_count'] = count($data['man_all']);
						$data['man_onreview'] = $this->Manuscript_model->get_manuscripts(2);
						$data['man_reviewed'] = $this->Manuscript_model->get_manuscripts(3);
						$data['man_final'] = $this->Manuscript_model->get_manuscripts(4);
						$data['man_for_p'] = $this->Manuscript_model->get_manuscripts(5);
						$data['man_pub'] = $this->Manuscript_model->get_manuscripts(6);	
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
	 * Get email notification content info
	 *
	 * @param [type] $id
	 * @return void
	 */
	public function get_email_content($id){
		$output = $this->Email_model->get_email_content($id);
		echo json_encode($output);
	}

	/**
	 * update email notification content by process id
	 *
	 * @param [type] $id
	 * @return void
	 */
	public function update_email_content(){
		$post = array();
		$user_groups = $this->input->post('enc_user_group', true);
		$post['enc_user_group'] = implode(",", $user_groups);
		$post['enc_content'] = $this->input->post('enc_content', true);
		$post['enc_subject'] = $this->input->post('enc_subject', true);
		$post['enc_description'] = $this->input->post('enc_description', true);
		$post['enc_cc'] = $this->input->post('enc_cc', true);
		$post['enc_bcc'] = $this->input->post('enc_bcc', true);

		$post['last_updated'] = date('Y-m-d H:i:s');
		$where['enc_process_id'] = $this->input->post('enc_process_id', true);
		$output = $this->Email_model->update_email_content(array_filter($post), $where);
		return $output;
	}

	public function update_process_time_duration(){
		$post = array();
		$post['enc_process_duration'] = $this->input->post('days', true);
		$where['enc_process_id'] = $this->input->post('id', true);
		$output = $this->Email_model->update_email_content(array_filter($post), $where);
		save_log_oprs(_UserIdFromSession(), 'Updated Process time duration', 0, _UserRoleFromSession());
		return $output;
	}


}