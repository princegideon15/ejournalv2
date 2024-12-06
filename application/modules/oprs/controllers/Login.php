<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
class Login extends OPRS_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('Login_model');
		$this->load->model('User_model');
		$this->load->model('Manuscript_model');
		$this->load->model('Review_model');
		$this->load->model('Library_model');
		$this->load->model('Email_model');
		$this->load->helper('string');
        $this->load->helper('form');
        $this->load->helper('security');
        $this->load->library('session'); 
		$this->load->library('form_validation');

		$objMail = $this->my_phpmailer->load();

			//security headers
			$this->output->set_header("Content-Security-Policy: 
			default-src 'self' https://*.google.com https://*.gstatic.com https://*.googleapis.com; 
			script-src 'self' https://*.google.com https://*.gstatic.com https://*.googleapis.com 'unsafe-inline'; 
			style-src 'self' https://*.google.com https://*.gstatic.com https://*.googleapis.com 'unsafe-inline'; 
			font-src 'self' https://*.gstatic.com;
			img-src 'self' https://*.google.com https://*.gstatic.com https://*.googleapis.com data:; 
			frame-src 'self' https://*.google.com;"
		);

		$this->output->set_header('X-Frame-Options: SAMEORIGIN');
		$this->output->set_header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
		$this->output->set_header('X-XSS-Protection: 1; mode=block');
		$this->output->set_header('X-Content-Type-Options: nosniff');
	}
	/**
	 * Display login page
	 *
	 * @return void
	 */
	public function index() {

		if ($this->session->userdata('_oprs_logged_in')) {
			if ($this->session->userdata('sys_acc') == 1 || $this->session->userdata('sys_acc') == 3) {
				redirect('admin/dashboard');
			} else {
					redirect('oprs/dashboard');
			}
		}

		$data['main_title'] = "OPRS";
		$data['titles'] = $this->Library_model->get_titles();
		$data['main_content'] = "oprs/login";
		$this->_LoadPage('common/body', $data);

		// $this->session->unset_userdata('_oprs_login_msg');
		// $this->session->unset_userdata('_oprs_sign_up_msg');
	}

	/**
	 * Authenticate login credentials
	 *
	 * @return  void
	 */
	public function authenticate() {

		$this->form_validation->set_rules('usr_username', 'Email', 'required|trim');
		$this->form_validation->set_rules('usr_password', 'Password', 'required|trim');

		if($this->form_validation->run() == FALSE){
			$errors = [];

			if (form_error('usr_username')) {
				$errors['email'] = strip_tags(form_error('usr_username'));
			}
			if (form_error('usr_password')) {
				$errors['password'] = strip_tags(form_error('usr_password'));
			}

			// Set flashdata to pass validation errors and form data to the view
			$this->session->set_flashdata('validation_errors', $errors);
			redirect('oprs/login');
		}else{

			$login = $this->input->post('admin_login', TRUE);
			$remember = $this->input->post('oprs_remember', TRUE);
			$email = $this->input->post('usr_username', TRUE);
			$password = $this->input->post('usr_password', TRUE);
	
			// Check user credentials using your authentication logic
			$validateUser = $this->Login_model->validate_user($email);
	
			if ($validateUser) {
	
				//check if account activated
				if($validateUser[0]->usr_status == 1){
					if (password_verify($password, $validateUser[0]->usr_password)) {
						$this->Login_model->clear_login_attempts($validateUser[0]->usr_username);
						//send otp to email
						$this->send_login_otp($email);
					}else{
						$count_attempt = count($this->Login_model->get_login_attempts($validateUser[0]->email));
	
						if($count_attempt == 3){
	
							$last_attempt_time = $this->Login_model->get_login_attempts($validateUser[0]->email);
							$last_attempt_time = $last_attempt_time[0]->attempt_time;
							$current_date = date('Y-m-d H:i:s');
							$time_remaining = $this->compareDates($last_attempt_time, $current_date);
	
							$this->send_email_alert($email);
							
							if ($time_remaining  > 30) {
								$this->Login_model->clear_login_attempts($validateUser[0]->email);
								$this->session->set_flashdata('error_login', 'Invalid email or password.');
				
								//store login attempt
								$data = [
									'user_id' => $validateUser[0]->id,
									'user_email' => $validateUser[0]->email,
									'attempt_time' => date('Y-m-d H:i:s')
								];
	
								$this->Login_model->store_login_attempts($data); 
								save_log_ej($validateUser[0]->id, 'Account locked for 30 minutes');
							}
							else{
								$this->session->set_flashdata('error_login', 'Account temporarily locked for&nbsp;<strong>'.(30 - $time_remaining).' minutes</strong>.');
							}
	
							redirect('client/login');
						}else{
							//store login attempt
							$data = [
								'user_id' => $validateUser[0]->id,
								'user_email' => $validateUser[0]->email,
								'attempt_time' => date('Y-m-d H:i:s')
							];
	
							$this->Login_model->store_login_attempts($data);  
						}
	
						$this->session->set_flashdata('error_login', 'Invalid email or password.');
						redirect('client/login'); 
					
	
					}
				}else{
					$this->session->set_flashdata('error_login', 'Account not activated. Please check your email for a create account verification code.');
					redirect('client/login');
				}
			} else {
	
				$count_attempt = count($this->Login_model->get_login_attempts($email));
	
				if($count_attempt == 3){
	
					$last_attempt_time = $this->Login_model->get_login_attempts($email);
					$last_attempt_time = $last_attempt_time[0]->attempt_time;
					$current_date = date('Y-m-d H:i:s');
					// check otp valid 5 minutes
					$time_remaining = $this->compareDates($last_attempt_time, $current_date);
	
					$this->send_email_alert($email);
					
					if ($time_remaining  > 30) {
						$this->Login_model->clear_login_attempts($email);
						$this->session->set_flashdata('error_login', 'Invalid email or password.');
						
						// store login attempt
						$data = [
							'user_email' => $email,
							'attempt_time' => date('Y-m-d H:i:s')
						];
	
						$this->Login_model->store_login_attempts($data); 
						
						save_log_ej(0, 'Unregistered account locked for 30 minutes');
					}
					else{
						$this->session->set_flashdata('error_login', 'Account temporarily locked for&nbsp;<strong>'.(30 - $time_remaining).' minutes</strong>.');
					}
	
					redirect('client/login');
				}else{
					// store login attempt
					$data = [
						'user_email' => $email,
						'attempt_time' => date('Y-m-d H:i:s')
					];
	
					$this->Login_model->store_login_attempts($data); 
				}
	
			
				$this->session->set_flashdata('error_login', 'Invalid email or password.');
				redirect('client/login');
			}
		}

		// $x = 0;
		
		// if (isset($login)) {
		// 	$usr_name = $this->input->post('usr_username', true);
		// 	$usr_password = $this->input->post('usr_password', true);
		// 	$usr_role = $this->input->post('usr_role');

		// 	if ($this->Login_model->authenticate_user($usr_name, $usr_role)) {
		// 		$hash = $this->Login_model->authenticate_user($usr_name, $usr_role);
				
		// 		if($hash[0]->usr_status == 2){
		// 			// incorrect password
		// 			$array_msg = array('icon' => 'fa fa-exclamation-triangle', 'class' => 'alert-danger', 'msg' => 'Account not activated. Please verify your account.');
		// 			$this->session->set_flashdata('_oprs_login_msg', $array_msg);
		// 			redirect('oprs/login');
		// 		}

		// 		$count_hash = count($hash);

		// 		foreach ($hash as $row) {
		// 			$pass = $row->usr_password;
		// 			$type_num = $row->usr_role;
		// 			$type = $row->usr_desc;
		// 			$id = $row->usr_id;
		// 			$dp = $row->usr_dp;
		// 			$sys = $row->usr_sys_acc;
		// 			if (password_verify($usr_password, $pass)) {
		// 				// privilege session
		// 				$priv = $this->User_model->get_privilege($id);
		// 				if($priv){
		// 					foreach ($priv as $row) {
		// 						$priv_sess = array('_prv_add' => $row->prv_add,
		// 							'_prv_edt' => $row->prv_edit,
		// 							'_prv_del' => $row->prv_delete,
		// 							'_prv_view' => $row->prv_view,
		// 							'_prv_exp' => $row->prv_export);
		// 					}
		// 				}
		// 				else{
		// 					$priv_sess = array('_prv_add' => 0,
		// 					'_prv_edt' => 0,
		// 					'_prv_del' => 0,
		// 					'_prv_view' => 0,
		// 					'_prv_exp' => 0);
		// 				}
						
		// 				$this->session->set_userdata($priv_sess);
		// 				if ($sys == 1 || $sys == 3) {
		// 					// login session
		// 					$sess = array('_oprs_logged_in' => true,
		// 						'_oprs_username' => $usr_name,
		// 						'_oprs_type' => $type,
		// 						'_oprs_type_num' => $type_num,
		// 						'_oprs_user_id' => $id,
		// 						'_oprs_user_dp' => $dp,
		// 						'sys_acc' => $sys);
								
		// 					is_online($id);
		// 					$this->session->set_userdata($sess);
		// 					save_log_oprs(_UserIdFromSession(), 'login', 0, _UserRoleFromSession());
							
		// 					redirect('admin/dashboard');
		// 				} else {
							
		// 					// privilege session
		// 					// login session
		// 					$sess = array('_oprs_logged_in' => true,
		// 						'_oprs_username' => $usr_name,
		// 						'_oprs_type' => $type,
		// 						'_oprs_type_num' => $type_num,
		// 						'_oprs_user_id' => $id,
		// 						'_oprs_user_dp' => $dp,
		// 						'_oprs_srce' => '_op',
		// 						'sys_acc' => $sys);
		// 					is_online($id);
		// 					$this->session->set_userdata($sess);
		// 					save_log_oprs(_UserIdFromSession(), 'login', 0,  _UserRoleFromSession());
		// 					if (_UserRoleFromSession() == 3 || _UserRoleFromSession() == 8) {
		// 						redirect('oprs/dashboard');
		// 					} else {
		// 						redirect('oprs/manuscripts');
		// 					}
		// 				}
		// 				// remember me
		// 				$expire = time() + 3600;
		// 				if (!empty($_POST['oprs_remember'])) {
		// 					$this->input->set_cookie('oprs_cookie_user',
		// 						$usr_name,
		// 						3600);
		// 					$this->input->set_cookie('oprs_cookie_pass',
		// 						$usr_password,
		// 						3600);
		// 					$this->input->set_cookie('oprs_remember_me',
		// 						$remember,
		// 						3600);
		// 				} else {
		// 					delete_cookie('oprs_cookie_user');
		// 					delete_cookie('oprs_cookie_pass');
		// 					delete_cookie('oprs_remember_me');
		// 				}
		// 			} else {
		// 				$x++;
		// 			}
		// 		}

		// 		if ($x == 2 && $count_hash == 2) {
		// 			// incorrect password
		// 			$array_msg = array('icon' => 'fa fa-times', 'class' => 'alert-danger', 'msg' => 'Incorrect Password.');
		// 			$this->session->set_flashdata('_oprs_login_msg', $array_msg);
		// 			redirect('oprs/login');
		// 		} else {
		// 			// incorrect password
		// 			$array_msg = array('icon' => 'fa fa-times', 'class' => 'alert-danger', 'msg' => 'Incorrect Password.');
		// 			$this->session->set_flashdata('_oprs_login_msg', $array_msg);
		// 			redirect('oprs/login');
		// 		}

		// 		//redirect to otp page
		// 		//TODO:
		// 	} else {
		// 		if ($this->Login_model->authenticate_member($usr_name)) {
		// 			$hash = $this->Login_model->authenticate_member($usr_name);
		// 			foreach ($hash as $row) {
		// 				$pass = $row->usr_password;
		// 				$type_num = 1;
		// 				$type = 'Author';
		// 				$id = $row->usr_id;
		// 				$dp = '';
		// 			}
		// 			if (password_verify($usr_password, $pass)) {
		// 				$sess = array('_oprs_logged_in' => true,
		// 					'_oprs_username' => $usr_name,
		// 					'_oprs_type' => $type,
		// 					'_oprs_type_num' => $type_num,
		// 					'_oprs_user_id' => $id,
		// 					'_oprs_user_dp' => $dp,
		// 					'_oprs_srce' => '_sk');
		// 				is_online($id);
		// 				$this->session->set_userdata($sess);
		// 				save_log_oprs(_UserIdFromSession(), 'login', 0, _UserRoleFromSession());
		// 				// remember me
		// 				$year = time() + 31536000;
		// 				if (isset($_POST['remember'])) {
		// 					setcookie('oprs_cookie_user', $usr_name, $year);
		// 					setcookie('oprs_cookie_pass', $usr_password, $year);
		// 					setcookie('oprs_remember_me', $remember, $year);
		// 				} else {
		// 					$past = time() - 100;
		// 					setcookie('oprs_remember_me', '', $past);
		// 					setcookie('oprs_cookie_user', '', $past);
		// 					setcookie('oprs_cookie_pass', '', $past);
		// 				}
		// 				redirect('oprs/manuscripts');
		// 			} else {
		// 				// incorrect password
		// 				$array_msg = array('icon' => 'fa fa-times', 'class' => 'alert-danger', 'msg' => 'Incorrect Password.');
		// 				$this->session->set_flashdata('_oprs_login_msg', $array_msg);
		// 				redirect('oprs/login');
		// 			}
		// 		} else {
		// 			// invalid user
		// 			$array_msg = array('icon' => 'fa fa-user-times', 'class' => 'alert-danger', 'msg' => 'User not found.');
		// 			$this->session->set_flashdata('_oprs_login_msg', $array_msg);
		// 			redirect('oprs/login');
		// 		}
		// 	}
		// }
	}

/**
	 * Send login otp
	 *
	 * @param string $email
	 * @return void
	 */
	public function send_login_otp($email) {
	
		$user_info = $this->User_model->get_user_info_by_email($email);

		if($user_info[0]->usr_category == 1){ // nrcp member
			$nrcp_member_info = $this->User_model->get_nrcp_member_info($email);
			$user_id = $nrcp_member_info['usr_id'];
			$name = $nrcp_member_info['title_name'] . ' ' . $nrcp_member_info['pp_first_name'] . ' ' .  $nrcp_member_info['pp_last_name'];
		}else if($user_info[0]->usr_category == 2){ // ejournal client and oprs non member author 
			$ejournal_client_info = $this->Client_journal_model->get_user_info($email);
			$user_id = $ejournal_client_info[0]->user_id;
			$name = $ejournal_client_info[0]->title . ' ' . $ejournal_client_info[0]->first_name . ' ' . $ejournal_client_info[0]->last_name;
		}else{ // oprs user
			if( $user_info[0]->usr_role == 5 ){
				// reviewer
				$reviewer_info = $this->User_model->get_reviewer_info_by_email($email);
				$user_id = $reviewer_info[0]->rev_id;
				$name = $reviewer_info[0]->rev_title . ' ' . $reviewer_info[0]->rev_name;
				$email = $user_info[0]->usr_username;

			}else{
				// oprs orejournal admin/supderamin
				$user_id = $user_info[0]->usr_id;
				$name = 'User';
				$email = $user_info[0]->usr_username;
			}
		}
		
		$otp = substr(number_format(time() * rand(),0,'',''),0,6);
		$ref_code = random_string('alnum', 16);

		$this->Login_model->save_otp(
			[
				'otp' => password_hash($otp, PASSWORD_BCRYPT),
				'otp_date' => date('Y-m-d H:i:s'),
				'otp_ref_code' => $ref_code
			],
			['usr_username' => $email]
		);

		$link = base_url() . 'oprs/login/verify_otp/'.$ref_code;
		$sender = 'eJournal';
		$sender_email = 'nrcp.ejournal@gmail.com';
		$password = 'fpzskheyxltsbvtg';
		
		// setup email config	
		$mail = new PHPMailer;
		$mail->isSMTP();
		$mail->Host = "smtp.gmail.com";
		// Specify main and backup server
		$mail->SMTPAuth = true;
		$mail->Port = 465;
		// Enable SMTP authentication
		$mail->Username = $sender_email;
		// SMTP username
		$mail->Password = $password;
		// SMTP password
		$mail->SMTPSecure = 'ssl';
		// Enable encryption, 'ssl' also accepted
		$mail->From = $sender_email;
		$mail->FromName = $sender;
	
		$mail->AddAddress($email);


		$date = date("F j, Y") . '<br/><br/>';

		$emailBody = 'Dear <strong>'.$name.'</strong>,
		<br><br>
		Please enter this code to verify your log in.
		<br><br>
		<strong style="font-size:20px">'.$otp.'</strong>
		<br><br>
		Or click the link below to redirect in the verification page:
		<br><br>
		'.$link.'
		<br><br>
		Link not working? Copy and paste the link into your browser.
		<br><br>
		This code will only be valid for the next <strong>5 minutes</strong>.
		<br><br><br>
		Sincerely,
		<br><br>
		NRCP Research Journal
		<br><br><br>
		<em>This is an automated message. Please do not reply to this email. For assistance, please contact our support team at [Support Email Address]</em>';
		
		// send email
		$mail->Subject = 'Login Verification';
		$mail->Body = $emailBody;
		$mail->IsHTML(true);
		$mail->smtpConnect([
			'ssl' => [
				'verify_peer' => false,
				'verify_peer_name' => false,
				'allow_self_signed' => true,
			],
		]);

		if (!$mail->Send()) {
			echo '</br></br>Message could not be sent.</br>';
			echo 'Mailer Error: ' . $mail->ErrorInfo . '</br>';
			exit;
		}

		$this->session->set_flashdata('otp', '
											<div class="alert alert-primary d-flex align-items-center">
												<i class="oi oi-circle-check me-1"></i>Please check your email for the 6-digit code.
											</div>');
		$this->session->set_userdata('otp_ref_code', $ref_code);
		redirect($link);
	}

	
	/**
	 * Verify login otp
	 *
	 * @param string $ref
	 * @return void
	 */
	public function verify_otp($ref){
		// check if ref code exist
		$ref = $this->security->xss_clean($ref);
		$isOtpRefExist = $this->Login_model->validate_otp_ref($ref);
		
		$otp_date = $isOtpRefExist[0]->otp_date;
		$current_date = date('Y-m-d H:i:s');
		if($this->compareDates($otp_date, $current_date) > 30){
			// remove otp info if more than 30mins no action
			$this->Login_model->delete_otp($isOtpRefExist[0]->id);
			$isOtpRefExist = $this->Login_model->validate_otp_ref($ref);
		}

		if($isOtpRefExist[0]->otp_ref_code == null){ //link expired
			$this->session->set_flashdata('otp', '
			<div class="alert alert-danger d-flex align-items-center">
				<i class="oi oi-circle-x me-1"></i>Link expired.
			</div>');

			$data['main_title'] = "eJournal";
			$data['main_content'] = "oprs/login_otp";
			$data['disabled'] = "disabled";
			$this->_LoadPage('common/body', $data);
		}else{ // code expire

			// check if code expired after 5 minutes
			if ($this->compareDates($otp_date, $current_date) > 4) {
				$this->session->set_flashdata('otp', '
				<div class="alert alert-danger d-flex align-items-center">
					<i class="oi oi-circle-x me-1"></i>Code expired.
				</div>');
	
				$data['ref_code'] = $isOtpRefExist[0]->otp_ref_code;
				$data['disabled'] = "disabled";
				$data['main_title'] = "eJournal";
				$data['main_content'] = "oprs/login_otp";
				$this->_LoadPage('common/body', $data);
			} else {
			
				$ref_code = $this->input->post('ref', TRUE);
			
				$this->form_validation->set_rules('otp', 'OTP', 'required|trim|min_length[6]|max_length[6]');
			
				if($this->form_validation->run() == FALSE){
					$errors = [];
		
					if (form_error('otp')) {
						$errors['otp'] = strip_tags(form_error('otp'));
					}
		
					// Set flashdata to pass validation errors and form data to the view
					$this->session->set_flashdata('validation_errors', $errors);
					$data['main_title'] = "eJournal";
					$data['main_content'] = "oprs/login_otp";
					$this->_LoadPage('common/body', $data);
				}else{
					$otp = $this->input->post('otp', TRUE);
					// Check user credentials using your authentication logic
					$verifyOTP = $this->Login_model->validate_otp($ref_code);
					
					// if ($verifyOTP) {
					if (password_verify($otp, $verifyOTP[0]->otp)) {

						$id = $verifyOTP[0]->id;

						$last_visit_date = $this->Login_model->get_last_visit_date($id);
						$last_visit_date = new DateTime($last_visit_date[0]->date_created);
						$last_visit_date = $last_visit_date->format('F j, Y');
						

						//set session values
						$this->session->set_userdata('user_id', $id);
						$this->session->set_userdata('email', $verifyOTP[0]->email);
						$this->session->set_userdata('name', $verifyOTP[0]->name);
						$this->session->set_userdata('last_visit_date', $last_visit_date);
						$this->session->unset_userdata('otp_ref_code');
						$this->Login_model->delete_otp($id);
						
						//save log
						save_log_ej($id, 'Login successful');

						//create access token
						$token = uniqid();
						$token = password_hash($token, PASSWORD_BCRYPT);
						$this->session->set_userdata('access_token', $token);

						$expiration_time = time() + 1200; // 20 minutes in seconds
						$expired_at = date('Y-m-d H:i:s', $expiration_time);

						
						$this->Login_model->delete_access_token($id);

						$tokenData = [
							'tkn_user_id' => $id,
							'tkn_value' => $token,
							'tkn_created_at' => date('Y-m-d H:i:s'),
							'tkn_expired_at' => $expired_at
						];
						
						$this->Login_model->create_user_access_token($tokenData);
						
						// check if there is an unaccomplished csf arta
						$arta_ref_code = $this->CSF_model->get_latest_incomplete_csf_arta($id);

						if($arta_ref_code){
							$csf_arta = '<div class="alert alert-warning" role="alert">
								<h4 class="alert-heading h6 fw-bold"><span class="fa fa-exclamation-triangle text-warning"></span> CSF-ARTA</h4>
								<hr>
								<p class="mb-3">The system has detected that you have an unsubmitted CSF-ARTA from your most recent article download.</p>
								
								<div>
									<a href="' . base_url() . 'oprs/ejournal/csf_arta/' . $arta_ref_code . '" class="btn btn-sm btn-warning" target="_blank">View</a>
								</div>
							</div>';
	
							$this->session->set_userdata('csf_arta', $csf_arta);
						}


						// redirect('oprs/ejournal/');
					} else {
						//invalid code
						$this->session->set_flashdata('otp', '
															<div class="alert alert-danger d-flex align-items-center">
																<i class="oi oi-circle-x me-1"></i>Invalid code. Try again.
															</div>');
		
						$data['main_title'] = "eJournal";
						$data['main_content'] = "oprs/login_otp";
						$this->_LoadPage('common/body', $data);
					}
				}
			}
		}
		
	}
	

	/**
	 * Logout
	 *
	 * @return  [type]  [return description]
	 */
	public function logout() {
		save_log_oprs(_UserIdFromSession(), 'logout', 0, _UserRoleFromSession());
		is_offline(_UserIdFromSession());
		session_unset();
		redirect('oprs/login');
	}

	/**
	 * Create temporary reviewer account if manuscript request accepted
	 * 
	 *
	 * @param   int  $man_id  manuscript id
	 * @param   int  $action  accept/decline
	 * @param   int  $id      reviewer id
	 * @param   int  $days    review duration
	 *
	 * @return  void
	 */
	public function reviewer($man_id, $action, $id, $days = null) {
		$output = $this->Login_model->check_reveiwer_status($id, $man_id);
		foreach ($output as $key => $row) {
			$status = $row->rev_status;
			$response = $row->rev_date_respond;
		}
		if ($status == 9 || $status == 1) {
			// determine if reviewer has clicked accept or decline already
			$message = ($status == 1) ? 'accepted' : 'declined';
			$array_msg = array('icon' => 'fa fa-exclamation-triangle', 'class' => 'alert-warning', 'msg' => 'Sorry, you have already ' . $message. ' the request.');
			$this->session->set_flashdata('_oprs_login_msg', $array_msg);
			redirect('oprs/login');
			$this->session->unset_userdata('_oprs_login_msg');
		} else if ($status == 3) {
			$array_msg = array('icon' => 'fa fa-exclamation-triangle', 'class' => 'alert-danger', 'msg' => 'Sorry, the request has been expired.');
			$this->session->set_flashdata('_oprs_login_msg', $array_msg);
			redirect('oprs/login');
			$this->session->unset_userdata('_oprs_login_msg');
		} else {
		
			if ($action == 1) {
				// accept
				// get info reviewer
				$output = $this->Login_model->get_reviewer_info($id);
				foreach($output as $row){
					$rev_username = $row->rev_email;
					$rev_man_id = $row->rev_man_id;
				}
				if ($this->Login_model->authenticate_user($rev_username, 5)) {
					$array_msg = array('icon' => 'fa fa-check-square', 'class' => 'alert-success', 'msg' => 'Thank you for accepting the review request. <br/><br/>
					You already have a temporary account. Please use your
					existing username and password to begin the review. You have <strong>' . $days . ' days</strong> to accomplish and submit the score/evaluation sheet.');
				
					// get info existing reviewer
					$output = $this->User_model->get_user_info($id);
					foreach($output as $row){
						$rev_password = $row->usr_password_copy;
					}
				
				
				} else {
					$rev_password = 'nrcp' . rand(100, 1000);
					$rev_password_hash = password_hash($rev_password, PASSWORD_BCRYPT);
					// create temporary account
					$temp['usr_username'] = $rev_username;
					$temp['usr_password'] = $rev_password_hash;
					$temp['usr_password_copy'] = $rev_password;
					$temp['usr_desc'] = 'Reviewer';
					$temp['usr_role'] = 5;
					$temp['usr_sys_acc'] = 2;
					$temp['date_created'] = date('Y-m-d H:i:s');
					$temp['usr_id'] = $id;
					$this->User_model->create_temp_reviewer(array_filter($temp));
					save_log_oprs($id, 'accepted review request for', $rev_man_id, 5);
					$array_msg = array('icon' => 'fa fa-check-square', 'class' => 'alert-success', 'msg' => 'Thank you for accepting the review request. <br/><br/>
					To begin with review, please login to your temporary account
					with this username and password. <br/></br>
					Username: <strong>' . $rev_username . '</strong><br/>
					Password : <strong>' . $rev_password . '</strong><br/><br/>
					Please check your email for more details. You have <strong>' . $days . ' days</strong> to accomplish and submit the score/evaluation sheet.');
					// $this->send_password_copy($rev_username, $rev_password);
				}

				// Please take note of your username and password for your next login. You have <strong>' . $days . ' days</strong> to accomplish and submit the score/evaluation sheet.');
				
				// update reviewer
				$revs['rev_status'] = $action;
				$revs['rev_notif_status'] = 0;
				$revs['rev_date_respond'] = date('Y-m-d H:i:s');
				$where['rev_id'] = $id;
				$where['rev_man_id'] = $man_id;
				$this->Manuscript_model->update_reviewer(array_filter($revs), $where);

				// send toke for apprection mail to reviewer
				$this->send_appreciation_msg($id, $rev_password, 5);
				// $this->send_appreciation_msg($rev_username);

				// add flag to tblscores
				$post_scr['scr_man_id'] = $man_id;	
				$post_scr['scr_man_rev_id'] = $id;
				$post_scr['scr_status'] = 2;
				$this->Review_model->save_review(array_filter($post_scr));
				$this->session->set_flashdata('_oprs_login_msg', $array_msg);
				redirect('oprs/login');
				$this->session->unset_userdata('_oprs_login_msg');
			} else {
				$rev_username = $this->Login_model->get_reviewer_info($id);
				$this->decline_request($rev_username, $man_id, $id);

				// decline
				// update reviewer
				$revs['rev_status'] = 9;
				$revs['rev_date_respond'] = date('Y-m-d H:i:s');
				$where['rev_id'] = $id;
				$where['rev_man_id'] = $man_id;
				$this->Manuscript_model->update_reviewer(array_filter($revs), $where);
				$array_msg = array('icon' => 'fa fa-times-circle', 'class' => 'alert-warning', 'msg' => 'Request declined.');
				$this->session->set_flashdata('_oprs_login_msg', $array_msg);
				redirect('oprs/login');
				$this->session->unset_userdata('_oprs_login_msg');
			}
		}
	}

	/**
	 * Create temporary editor account if manuscript request accepted
	 * 
	 *
	 * @param   int  $man_id  manuscript id
	 * @param   int  $action  accept/decline
	 * @param   int  $id      reviewer id
	 * @param   int  $days    review duration
	 *
	 * @return  void
	 */
	public function editor($man_id, $action, $id, $days = null) {
		// get editor info
		$output = $this->Login_model->get_editor_info($id);
		foreach($output as $row){
			$edit_username = $row->edit_email;
			$edit_man_id = $row->edit_man_id;
		}
		
		if ($this->Login_model->authenticate_user($edit_username, 12)) {
			// existing account	
		} else {
			$edit_password = 'nrcp' . rand(100, 1000);
			$edit_password_hash = password_hash($edit_password, PASSWORD_BCRYPT);
			// create temporary account
			$temp['usr_username'] = $edit_username;
			$temp['usr_password'] = $edit_password_hash;
			$temp['usr_desc'] = 'Editor-in-Chief';
			$temp['usr_role'] = 12;
			$temp['usr_sys_acc'] = 2;
			$temp['date_created'] = date('Y-m-d H:i:s');
			$temp['usr_id'] = $id;
			$this->User_model->create_temp_reviewer(array_filter($temp));
			save_log_oprs($id, 'accepted review request for', $edit_man_id, 12);
			$array_msg = array('icon' => 'fa fa-check-square', 'class' => 'alert-success', 'msg' => 'Thank you for accepting the review request. <br/><br/>
			To begin with review, please login to your temporary account
			with this username and password. <br/></br>
			Username: <strong>' . $edit_username . '</strong><br/>
			Password : <strong>' . $edit_password . '</strong>');


			// update reviewer
			$edits['edit_status'] = 1;
			$edits['edit_notif_status'] = 0;
			$edits['edit_date_respond'] = date('Y-m-d H:i:s');
			$where['edit_id'] = $id;
			$where['edit_man_id'] = $man_id;
			$this->Manuscript_model->update_editor(array_filter($edits), $where);

			// send toke for apprection mail to reviewer
			$this->send_appreciation_msg($id, $edit_password,12);
			// $this->send_appreciation_msg($rev_username);

			// add flag to tblscores
			// $post_scr['scr_man_id'] = $man_id;	
			// $post_scr['scr_man_rev_id'] = $id;
			// $post_scr['scr_status'] = 2;
			// $this->Review_model->save_review(array_filter($post_scr));

			

		}

		$this->session->set_flashdata('_oprs_login_msg', $array_msg);
			redirect('oprs/login');
			$this->session->unset_userdata('_oprs_login_msg');
	}


	/**
	 * Send email of password copy of reviewer who accepted the request
	 *
	 * @param   string  $email  reviewer email
	 * @param   string  $pass   reviewer password
	 *
	 * @return  void
	 */
	// public function send_password_copy($email, $pass) {
	// 	$nameuser = 'eJournal Admin';
	// 	$usergmail = 'nrcp.ejournal@gmail.com';
	// 	$password = 'fpzskheyxltsbvtg';
	// 	$mail = new PHPMailer;
	// 	$mail->isSMTP();
	// 	$mail->Host = "smtp.gmail.com";
	// 	// Specify main and backup server
	// 	$mail->SMTPAuth = true;
	// 	$mail->Port = 465;
	// 	// Enable SMTP authentication
	// 	$mail->Username = $usergmail;
	// 	// SMTP username
	// 	$mail->Password = $password;
	// 	// SMTP password
	// 	$mail->SMTPSecure = 'ssl';
	// 	// Enable encryption, 'ssl' also accepted
	// 	$mail->From = $usergmail;
	// 	$mail->FromName = $nameuser;
	// 	$mail->AddCC('gerardbalde15@gmail.com');
	// 	// $mail->AddCC('nrcpeditorial2021@gmail.com');
	// 	// $mail->AddCC('oed@nrcp.dost.gov.ph');
	// 	$mail->AddAddress($email);
	// 	$mail->Subject = "OPRS Password Copy";
	// 	$mail->Body = $pass . ' Please change your password after logging in.';
	// 	$mail->IsHTML(true);
	// 	$mail->smtpConnect([
	// 		'ssl' => [
	// 			'verify_peer' => false,
	// 			'verify_peer_name' => false,
	// 			'allow_self_signed' => true,
	// 		],
	// 	]);
	// 	if (!$mail->Send()) {
	// 		echo '</br></br>Message could not be sent.</br>';
	// 		echo 'Mailer Error: ' . $mail->ErrorInfo . '</br>';
	// 		exit;
	// 	}
	// }

	/**
	 * Send email of password copy of reviewer who accepted the request
	 *
	 * @param   string  $email  reviewer email
	 * @param   string  $pass   reviewer password
	 *
	 * @return  void
	 */
	public function send_appreciation_msg($id, $rev_password, $role) {

		$link = 'https://researchjournal.nrcp.dost.gov.ph/oprs/login';
		$sender = 'eJournal Admin';
		$sender_email = 'nrcp.ejournal@gmail.com';
		$password = 'fpzskheyxltsbvtg';
		
		// setup email config	
		$mail = new PHPMailer;
		$mail->isSMTP();
		$mail->Host = "smtp.gmail.com";
		// Specify main and backup server
		$mail->SMTPAuth = true;
		$mail->Port = 465;
		// Enable SMTP authentication
		$mail->Username = $sender_email;
		// SMTP username
		$mail->Password = $password;
		// SMTP password
		$mail->SMTPSecure = 'ssl';
		// Enable encryption, 'ssl' also accepted
		$mail->From = $sender_email;
		$mail->FromName = $sender;

		if($role == 5){
			// reviewer

			$output = $this->Manuscript_model->get_reviewer_by_id($id);

			foreach($output as $row){
				$email = $row->rev_email;
				$name = $row->rev_name;
				$man_id = $row->rev_man_id;
				$title = $row->rev_title;
			}
	
			
			// get email notification content
			$email_contents = $this->Email_model->get_email_content(5);

			// get manuscript info
			$manus_info = $this->Manuscript_model->get_manus_for_email($man_id);
			foreach ($manus_info as $key => $val) {
				$man_pdf = $val->man_file;
				$man_word = $val->man_word;
			}

			$nda = '/var/www/html/ejournal/assets/oprs/uploads/SAMPLE_NDA_NRCP.doc';
			$mail->addAttachment($nda);
			$word = '/var/www/html/ejournal/assets/oprs/uploads/manuscriptsdoc/' . $man_word;
			$mail->addAttachment($word);
			$pdf = '/var/www/html/ejournal/assets/oprs/uploads/manuscripts/' . $man_pdf;
			$mail->addAttachment($pdf);
		}else{
			// editor

			$output = $this->Manuscript_model->get_editor_by_id($id);

			foreach($output as $row){
				$email = $row->edit_email;
				$name = $row->edit_name;
				$title = $row->edit_title;
				$man_id = $row->edit_man_id;
			}
	
			
			// get email notification content
			$email_contents = $this->Email_model->get_email_content(11);
		}

	
		

		// add cc/bcc
		foreach($email_contents as $row){
			$email_subject = $row->enc_subject;
			$email_contents = $row->enc_content;

			if( strpos($row->enc_cc, ',') !== false ) {
				$email_cc = explode(',', $row->enc_cc);
		    }else{
				$email_cc = array();
				array_push($email_cc, $row->enc_cc);
			}

			if( strpos($row->enc_bcc, ',') !== false ) {
				$email_bcc = explode(',', $row->enc_bcc);
		    }else{
				$email_bcc = array();
				array_push($email_bcc, $row->enc_bcc);
			}

			if( strpos($row->enc_user_group, ',') !== false ) {
				$email_user_group = explode(',', $row->enc_user_group);
		    }else{
				$email_user_group = array();
				array_push($email_user_group, $row->enc_user_group);
			}
			
		}

		// add exisiting email as cc
		if(count($email_user_group) > 0){
			$user_group_emails = array();
			foreach($email_user_group as $grp){
				$username = $this->Email_model->get_user_group_emails($grp);
				array_push($user_group_emails, $username);
			}
		}
		
		

		$mail->AddAddress($email);
	
		
		// replace reserved words
		// redirection link

		// add cc if any
		if(count($email_cc) > 0){
			foreach($email_cc as $cc){
				$mail->AddCC($cc);
			}
		}
		// add bcc if any
		if(count($email_bcc) > 0){
			foreach($email_bcc as $bcc){
				$mail->AddBCC($bcc);
			}
		}
		// add existing as cc
		if(count($user_group_emails) > 0){
			foreach($user_group_emails as $grp){
				$mail->AddCC($grp);
			}
		}
		
		$dir = 'Click <a href="' . $link .'" target="_blank">' . $link .'</a> to login.';
		$emailBody = str_replace('[FULL NAME]', $name, $email_contents);
		$emailBody = str_replace('[TITLE]', $title, $emailBody);
		$emailBody = str_replace('[EMAIL]', $email, $emailBody);
		$emailBody = str_replace('[PASSWORD]', $rev_password, $emailBody);
		$emailBody = str_replace('[LINK]', $dir, $emailBody);
		
		// send email
		$mail->Subject = $email_subject;
		$mail->Body = $emailBody;
		$mail->IsHTML(true);
		$mail->smtpConnect([
			'ssl' => [
				'verify_peer' => false,
				'verify_peer_name' => false,
				'allow_self_signed' => true,
			],
		]);
		if (!$mail->Send()) {
			echo '</br></br>Message could not be sent.</br>';
			echo 'Mailer Error: ' . $mail->ErrorInfo . '</br>';
			exit;
		}
	}

	/**
	 * Check multiple account for password recovery
	 *
	 * @param   string  $email  user acount
	 *
	 * @return  int          number of account found
	 */
	public function check_multiple_account($email) {
		$output = $this->Login_model->check_multiple_account($email);
		echo json_encode($output);
	}

	/**
	 * Send email if reviewer decline
	 *
	 * @param   string  $email   reivewer email
	 * @param   int  $id      manuscript id
	 * @param   int  $rev_id  reviewer id
	 *
	 * @return  void
	 */
	public function decline_request($email, $id, $rev_id) {
		// get manuscript info
		$manus_info = $this->Manuscript_model->get_manus_for_email($id);
		foreach ($manus_info as $key => $val) {
			$man_title = $val->man_title;
			$file_name = $val->man_file;
			$man_author = $val->man_author;
			$man_affiliation = $val->man_affiliation;
			$date_avail = date_format(new DateTime($val->man_date_available), 'F j, Y, g:i a');
		}

		// get reviewer information
		$rev_info = $this->Manuscript_model->get_rev_info($rev_id);
		foreach ($rev_info as $key => $val) {
			$timeframe = $val->rev_timeframe;
			$rev_timer = $val->rev_request_timer;
			$rev_email = $val->rev_email;
		}

		$output = $this->Manuscript_model->get_reviewer_by_id($rev_id);

		foreach($output as $row){
			$email = $row->rev_email;
			$name = $row->rev_name;
			$man_id = $row->rev_man_id;
			$title = $row->rev_title;
		}

		// get email notification content
		$email_contents = $this->Email_model->get_email_content(4);

		// add cc/bcc
		foreach($email_contents as $row){
			$email_subject = $row->enc_subject;
			$email_contents = $row->enc_content;

			if( strpos($row->enc_cc, ',') !== false ) {
				$email_cc = explode(',', $row->enc_cc);
		    }else{
				$email_cc = array();
				array_push($email_cc, $row->enc_cc);
			}

			if( strpos($row->enc_bcc, ',') !== false ) {
				$email_bcc = explode(',', $row->enc_bcc);
		    }else{
				$email_bcc = array();
				array_push($email_bcc, $row->enc_bcc);
			}

			if( strpos($row->enc_user_group, ',') !== false ) {
				$email_user_group = explode(',', $row->enc_user_group);
		    }else{
				$email_user_group = array();
				array_push($email_user_group, $row->enc_user_group);
			}
			
		}

		// add exisiting email as cc
		if(count($email_user_group) > 0){
			$user_group_emails = array();
			foreach($email_user_group as $grp){
				$username = $this->Email_model->get_user_group_emails($grp);
				array_push($user_group_emails, $username);
			}
		}

		$sender = 'eJournal Admin';
		$sender_email = 'nrcp.ejournal@gmail.com';
		$password = 'fpzskheyxltsbvtg';

		// setup email config	
		$mail = new PHPMailer;
		$mail->isSMTP();
		$mail->Host = "smtp.gmail.com";
		// Specify main and backup server
		$mail->SMTPAuth = true;
		$mail->Port = 465;
		// Enable SMTP authentication
		$mail->Username = $sender_email;
		// SMTP username
		$mail->Password = $password;
		// SMTP password
		$mail->SMTPSecure = 'ssl';
		// Enable encryption, 'ssl' also accepted
		$mail->From = $sender_email;
		$mail->FromName = $sender;
		$mail->AddAddress($rev_email);

		// add cc if any
		if(count($email_cc) > 0){
			foreach($email_cc as $cc){
				$mail->AddCC($cc);
			}
		}
		// add bcc if any
		if(count($email_bcc) > 0){
			foreach($email_bcc as $bcc){
				$mail->AddBCC($bcc);
			}
		}
		// add existing as cc
		if(count($user_group_emails) > 0){
			foreach($user_group_emails as $grp){
				$mail->AddCC($grp);
			}
		}
		
		// replace reserved words
		$emailBody = str_replace('[FULL NAME]', $name, $email_contents);
		$emailBody = str_replace('[TITLE]', $title, $emailBody);

		// send email
		$mail->Subject = $email_subject;
		$mail->Body = $emailBody;
		$mail->IsHTML(true);
		$mail->smtpConnect([
			'ssl' => [
				'verify_peer' => false,
				'verify_peer_name' => false,
				'allow_self_signed' => true,
			],
		]);
		if (!$mail->Send()) {
			echo '</br></br>Message could not be sent.</br>';
			echo 'Mailer Error: ' . $mail->ErrorInfo . '</br>';
			exit;
		}
	}

	public function forgot_password(){
		$data['main_title'] = "OPRS";
		$data['main_content'] = "oprs/forgot_password";
		$this->_LoadPage('common/body', $data);
	}

	/**
	 * Get minutes for otp 5 mins and locked account 30 mins
	 *
	 * @param datetime $date1
	 * @param datetime $date2
	 * @return void
	 */
	function compareDates($date1, $date2) {
		$date1 = new DateTime($date1);
		$date2 = new DateTime($date2);
	
		$interval = $date1->diff($date2);
		$minutes = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;
		
		return $minutes;
	}

	function get_access_token(){
		$id = $this->session->userdata('user_id');
		if ($id) {
			$accessToken = $this->Login_model->get_access_token($id);
			$token =  $accessToken[0]->tkn_value;
			echo trim($token);
		}else{
			echo 0;
		}
	}

	
	/**
	 * Get existing reference code for resending otp code
	 *
	 * @param string $refCode
	 * @return void
	 */
	public function get_current_otp($refCode){
		$refCode = $this->security->xss_clean($refCode);
		$output = $this->Login_model->get_current_otp($refCode);
		echo json_encode($output);
	}

	/**
	 * Resend login otp
	 *
	 * @param string $refCode
	 * @return void
	 */
	public function resend_login_code($refCode){
		$refCode = $this->security->xss_clean($refCode);
		$output = $this->Login_model->get_current_otp($refCode);
		$email = $output[0]->usr_username;
		save_log_ej($output[0]->usr_id, 'Resend login otp code');
		$this->send_login_otp($email);
	}

	
}

/* End of file Login.php */