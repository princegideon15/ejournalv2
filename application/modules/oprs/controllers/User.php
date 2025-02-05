<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
class User extends OPRS_Controller {
	public function __construct() {
		parent::__construct();
		if (!$this->session->userdata('_oprs_logged_in')) {
			redirect('oprs/login');
		}
		$this->load->model('Manuscript_model');
		$this->load->model('Login_model');
		$this->load->model('User_model');
		$this->load->model('Feedback_model');
		$this->load->model('Log_model');
		$this->load->model('Arta_model');
	}

	public function index() {
		if ($this->session->userdata('_oprs_logged_in')) {
			if($this->session->userdata('sys_acc') == 2 || $this->session->userdata('sys_acc') == 3 ){
				if (_UserRoleFromSession() == 20) {
					$data['manus'] = $this->Manuscript_model->get_manus($this->session->userdata('_oprs_srce'), $this->session->userdata('_oprs_username'));
					$id = $this->session->userdata('_oprs_user_id');
					$data['users'] = $this->User_model->get_user($id);
					$data['logs'] = $this->Log_model->count_logs();
					$data['man_all'] = $this->Manuscript_model->get_manus(_UserRoleFromSession());
					$data['man_all_count'] = count($data['man_all']);
					// $data['man_new'] = $this->Manuscript_model->get_manuscripts(1);
					// $data['man_onreview'] = $this->Manuscript_model->get_manuscripts(2);
					// $data['man_reviewed'] = $this->Manuscript_model->get_manuscripts(3);
					// $data['man_final'] = $this->Manuscript_model->get_manuscripts(4);
					// $data['man_for_p'] = $this->Manuscript_model->get_manuscripts(5);
					// $data['man_pub'] = $this->Manuscript_model->get_manuscripts(6);	
					$data['usr_count'] = $this->User_model->count_user();
					$data['arta_count'] = count($this->Arta_model->get_arta());
					$data['feed_count'] = $this->Feedback_model->count_feedbacks();
					$data['user_types'] = $this->User_model->get_user_types();
					$data['main_title'] = "OPRS";
					$data['main_content'] = "oprs/user";
					$this->_LoadPage('common/body', $data);
					$this->session->unset_userdata('_oprs_usr_message');
				}else if(_UserRoleFromSession() == 12 || _UserRoleFromSession() == 12 || _UserRoleFromSession() == 6){
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
	 * this function get logs by user
	 *
	 * @param   int  $id  user id
	 *
	 * @return  array       log
	 */
	public function get_user_log($id)
	{
		$output = $this->User_model->get_user_name($id);
		echo json_encode($output);
	}

	/**
	 * this function get user info
	 *
	 * @param   int  $id  user id
	 *
	 * @return  array       user data
	 */
	public function get_info($id) {
		$output = $this->User_model->get_user_info($id);
		echo json_encode($output);
	}

	/**
	 * this function edit, update user
	 *
	 * @param   int  $id  user id
	 *
	 * @return  void
	 */
	public function edit_user($id) {
		$post['usr_username'] = $this->input->post('usr_username', TRUE);
		if ($this->input->post('usr_password', TRUE)) {
			$post['usr_password'] = password_hash($this->input->post('usr_password', true), PASSWORD_BCRYPT);
		}
		if ($this->input->post('usr_contact', TRUE)) {
			$post['usr_contact'] = $this->input->post('usr_contact', TRUE);
		}
		$post['usr_role'] = $this->input->post('usr_role', TRUE);
		$role = $post['usr_role'];
		$post['usr_sys_acc'] = $this->input->post('usr_sys_acc', TRUE);
		$post['usr_sex'] = $this->input->post('usr_sex', TRUE);
		$post['usr_desc'] = $this->User_model->get_role($role);
		$where['usr_id'] = $id;
		$this->User_model->update_user(array_filter($post), $where);
	}

	/**
	 * this function add user
	 *
	 * @return  void
	 */
	public function add_user() {
		$oprs = $this->load->database('dboprs', TRUE);
		$tableName = 'tblusers';
		$result = $oprs->list_fields($tableName);
		$post = array();
		$id = 'SA' . md5(uniqid('', TRUE));
		foreach ($result as $i => $field) {
			if ($field != 'row_id') {
				$role = $this->input->post('usr_role', true);
				$role_info = $this->User_model->get_user_types($role);
				$post[$field] = $this->input->post($field, true);
				$post['usr_password'] = password_hash($this->input->post('usr_password', true), PASSWORD_BCRYPT);
				$post['usr_desc'] = $role_info[0]->role_name;
				$post['usr_id'] = $id;
				$post['usr_sys_acc'] = $role_info[0]->role_access;
				$post['usr_status'] = 1;
			}
		}
		$post['date_created'] = date('Y-m-d H:i:s');
		$this->User_model->add_user(array_filter($post));
		$priv['prv_usr_id'] = $id;
		$priv['prv_add'] = 1;
		$priv['prv_edit'] = 1;
		$priv['prv_delete'] = 1;
		$priv['prv_view'] = 1;
		$priv['prv_export'] = 1;
		$priv['date_created'] = date('Y-m-d H:i:s');
		$this->User_model->add_privilege(array_filter($priv));
		// $array_msg = array('icon' => 'fa fa-check-circle-o', 'class' => 'alert-success', 'msg' => 'User Saved.');
		// $this->session->set_flashdata('_oprs_usr_message', $array_msg);
	}

	/**
	 * this function change password
	 *
	 * @return  void
	 */
	public function change_password() {
		$post['usr_password'] = password_hash($this->input->post('usr_password', TRUE), PASSWORD_BCRYPT);
		$post['last_updated'] = date('Y-m-d H:i:s');
		$where['usr_id'] = _UserIdFromSession();
		$this->User_model->change_password(array_filter($post), $where);
	}

	/**
	 * this fcuntion activate/deactivate user
	 *
	 * @param   int  $status  activate or deactivate
	 * @param   int  $id      user id
	 *
	 * @return  void
	 */
	public function activate_deactivate($status, $id) {
		$post['usr_status'] = $status;
		$post['last_updated'] = date('Y-m-d H:i:s');
		$where['usr_id'] = $id;
		$output = $this->User_model->activate_deactivate_account($post, $where);
	}

	/**
	 * this function verify email
	 *
	 * @return  string  true or false
	 */
	public function verify_email() {
		$output = $this->User_model->verify_user_email($this->input->post('usr_username', true), $this->input->post('role', true), $this->input->post('sys', true));
		echo $output;
	}

	public function verify_email_except_self() {
		$current_email = $this->session->userdata('_oprs_username');
		$entered_email = $this->input->post('usr_username', true);
		$output = $this->User_model->verify_email_except_self($current_email, $entered_email);
		echo $output;
	}

	/**
	 * this function verify old password
	 *
	 * @return  string  true or false
	 */
	public function verify_old_password() {
		$output = $this->User_model->verify_old_password($this->input->post('old_password', true));
		echo $output;
	}

	public function get_member($id) {
		$output = $this->User_model->get_member($id);
		echo json_encode($output);
	}

	/**
	 * this function get processor of the manuscript
	 *
	 * @param   int  $id   user id
	 * @param   string  $src  user source, skms or oprs
	 *
	 * @return  array        processor data
	 */
	public function get_processor($id, $src) {
		$output = $this->User_model->get_processor($id, $src);
		echo json_encode($output);
	}

	/**
	 * this function get user by role
	 *
	 * @param   int  $role  user role
	 *
	 * @return  array         user data
	 */
	public function get_user_by_role($role) {
		$output = $this->User_model->get_user_by_role($role);
		echo json_encode($output);
	}

	/**
	 * this function set user privileges
	 *
	 * @param   string  $priv   priveleges (add,edit,delete,update)
	 * @param   int  $id     user id
	 * @param   int  $value  privilege value (0/1)
	 *
	 * @return  void
	 */
	public function set_privilege($priv, $id, $value) {
		$priv_post = (($priv == 1) ? 'prv_add' :
			((($priv == 2) ? 'prv_edit' :
				((($priv == 3) ? 'prv_delete' :
					((($priv == 4) ? 'prv_view' :
						'prv_export')))))));
		$post[$priv_post] = $value;
		$where['prv_usr_id'] = $id;
		$this->User_model->set_privilege($post, $where);
	}

	public function check_email_oprs($email){
		$output = $this->User_model->get_user_info_by_email($email);
		return $output;
	}

	public function get_user_types($id = null){
		$output = $this->User_model->get_user_types($id);
		echo json_encode($output);
	}

	public function types(){
		if ($this->session->userdata('_oprs_logged_in')) {
			if($this->session->userdata('sys_acc') == 2 || $this->session->userdata('sys_acc') == 3 ){
				if (_UserRoleFromSession() == 20) {
					$data['roles'] = $this->User_model->get_user_types(null);
					$id = $this->session->userdata('_oprs_user_id');
					$data['users'] = $this->User_model->get_user($id);
					$data['logs'] = $this->Log_model->count_logs();
					$data['manus'] = $this->Manuscript_model->get_manus($this->session->userdata('_oprs_srce'), $this->session->userdata('_oprs_username'));
					// $data['man_new'] = $this->Manuscript_model->get_manuscripts(1);
					// $data['man_onreview'] = $this->Manuscript_model->get_manuscripts(2);
					// $data['man_reviewed'] = $this->Manuscript_model->get_manuscripts(3);
					// $data['man_final'] = $this->Manuscript_model->get_manuscripts(4);
					// $data['man_for_p'] = $this->Manuscript_model->get_manuscripts(5);
					// $data['man_pub'] = $this->Manuscript_model->get_manuscripts(6);	
					$data['usr_count'] = $this->User_model->count_user();
					$data['feed_count'] = $this->Feedback_model->count_feedbacks();
					$data['user_types'] = $this->User_model->get_user_types();
					$data['main_title'] = "OPRS";
					$data['main_content'] = "oprs/user_types";
					$this->_LoadPage('common/body', $data);
					$this->session->unset_userdata('_oprs_usr_message');
				}else if(_UserRoleFromSession() == 12 || _UserRoleFromSession() == 12 || _UserRoleFromSession() == 6){
					redirect('oprs/manuscripts');
				}else {
					redirect('oprs/dashboard');
				}
			} else {
				redirect('admin/dashboard');
			}
		}
	}

	public function udpate_account(){
		$post['usr_full_name'] = $this->input->post('usr_full_name', TRUE);
		$post['usr_username'] = $this->input->post('usr_username', TRUE);
		$post['usr_sex'] = $this->input->post('usr_sex', TRUE);
		$post['usr_contact'] = $this->input->post('usr_contact', TRUE);
		$post['last_updated'] = date('Y-m-d H:i:s');
		$where['usr_id'] = _UserIdFromSession();
		$this->User_model->update_account(array_filter($post), $where);
	}

	public function get_account_info(){
		$output = $this->User_model->get_user_info(_UserIdFromSession());
		echo json_encode($output);
	}


}