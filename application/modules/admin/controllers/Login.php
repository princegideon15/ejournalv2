<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

/**
 * File Name: Login.php
 * ----------------------------------------------------------------------------------------------------
 * Purpose of this file:
 * To manage user login
 * ----------------------------------------------------------------------------------------------------
 * System Name: Online Research Journal System
 * ----------------------------------------------------------------------------------------------------
 * Author: Gerard Paul D. Balde
 * ----------------------------------------------------------------------------------------------------
 * Date of revision: Sep 30, 2019
 * ----------------------------------------------------------------------------------------------------
 * Copyright Notice:
 * Copyright (C) 2018 by the Department of Science and Technology - National Research Council of the Philiipines
 */

class Login extends EJ_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model('Login_model');
		$this->load->helper('is_online_helper');
	}

	/**
	 * this function verifies username and password
	 *
	 * @return  void
	 */
	public function authenticate() {
		$login = $this->input->post('admin_login', TRUE);

		if (isset($login)) {
			$usr_name = $this->input->post('acc_username', TRUE);
			$usr_password = $this->input->post('acc_password', TRUE);
			$remember = $this->input->post('remember', TRUE);
			$this->form_validation->set_rules('acc_username', 'Username', 'trim|required|xss_clean');
			$this->form_validation->set_rules('acc_pasword', 'Password', 'trim|required|xss_clean');

			if ($this->form_validation->run($this) == FALSE) {
				if ($this->Login_model->authenticate_user($usr_name)) {
					$hash = $this->Login_model->authenticate_user($usr_name);

					foreach ($hash as $row) {
						$pass = $row->acc_password;
						$type_num = $row->acc_type;
						$type = ($row->acc_type == 2) ? 'Manager' : ($row->acc_type == 1) ? 'Administrator' : 'Superadmin';
						$id = $row->row_id;
						$dp = $row->acc_dp;
					}

					if (password_verify($usr_password, $pass)) {
						$sess = array(
							'_oprs_logged_in' => TRUE,
							'_oprs_username' => $usr_name,
							'_oprs_type' => $type,
							'_oprs_type_num' => $type_num,
							'_oprs_user_id' => $id,
							'_oprs_user_dp' => $dp,
						);

						is_online($id);
						$this->session->set_userdata($sess);

						// remember me
						$year = time() + 31536000;
						if (isset($_POST['remember'])) {
							setcookie('cookie_user', $usr_name, $year);
							setcookie('cookie_pass', $usr_password, $year);
							setcookie('remember_me', $remember, $year);
						} else {
							$past = time() - 100;
							setcookie('remember_me', '', $past);
							setcookie('cookie_user', '', $past);
							setcookie('cookie_pass', '', $past);
						}

						redirect('/admin/dashboard');
					} else {
						$array_msg = array('icon' => 'oi-warning', 'class' => 'alert-danger', 'msg' => 'Incorrect Password.');
						$this->session->set_flashdata('login_msg', $array_msg);
						redirect('oprs/oprs');
					}
				} else {
					$array_msg = array('icon' => 'oi-warning', 'class' => 'alert-danger', 'msg' => 'User not found.');
					$this->session->set_flashdata('login_msg', $array_msg);
					redirect('oprs/oprs');
				}
			}
		}
	}

	/**
	 * this function logouts a user
	 *
	 * @return  void
	 */
	public function logout() {
		is_offline(_UserIdFromSession());
		session_unset();
		redirect('oprs/oprs');
	}

	/**
	 * this function get all online users
	 *
	 * @return  array  online users data
	 */
	public function get_online_users() {
		$output = $this->Login_model->online_users(_UserIdFromSession());
		$output['current_user'] = $this->session->userdata('_oprs_type_num');
		echo json_encode($output);
	}

}
