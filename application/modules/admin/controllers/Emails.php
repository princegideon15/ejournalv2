<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
class Emails extends EJ_Controller {
	public function __construct() {
		parent::__construct();
		if (!$this->session->userdata('_oprs_logged_in')) {
			redirect('oprs/login');
		}
		$this->load->model('Journal_model');
		$this->load->model('Article_model');
		$this->load->model('Editorial_model');
		$this->load->model('Library_model');
		$this->load->model('Client_model');
		$this->load->model('Dashboard_model');
		$this->load->model('Login_model');
		$this->load->model('Log_model');
		$this->load->model('oprs/User_model');
		$this->load->model('Coauthor_model');
		$this->load->model('Email_model');
		$this->load->helper('is_online_helper');
	}

	public function index() {
		if ($this->session->userdata('_oprs_logged_in')) {
			if($this->session->userdata('sys_acc') == 2 || $this->session->userdata('sys_acc') == 3 ){
				if (_UserRoleFromSession() == 3 || _UserRoleFromSession() == 8) {
					$data['main_title'] = "OPRS";
					$data['main_content'] = "oprs/emails";
					$data['user_roles'] = $this->Email_model->get_email_user_roles();
					$data['emails'] = $this->Email_model->get_contents();
					$data['manus'] = $this->Manuscript_model->get_manus($this->session->userdata('_oprs_srce'), $this->session->userdata('_oprs_username'));
					$data['man_onreview'] = $this->Manuscript_model->count_manuscript(2);
					$data['man_reviewed'] = $this->Manuscript_model->count_manuscript(3);
					$data['man_final'] = $this->Manuscript_model->count_manuscript(4);
					$data['man_for_p'] = $this->Manuscript_model->count_manuscript(5);
					$data['man_pub'] = $this->Manuscript_model->count_manuscript(6);	
					$data['usr_count'] = $this->User_model->count_user();
					$data['feed_count'] = $this->Feedback_model->count_feedbacks();
					$this->_LoadPage('common/body', $data);
				}else if(_UserRoleFromSession() == 5 || _UserRoleFromSession() == 9){
					redirect('oprs/manuscripts');
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


}