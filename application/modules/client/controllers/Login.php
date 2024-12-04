<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

/**
 * File Name: Login.php
 * ----------------------------------------------------------------------------------------------------
 * Purpose of this file:
 * To manage login, otp, email.
 * ----------------------------------------------------------------------------------------------------
 * System Name: Online Research Journal System
 * ----------------------------------------------------------------------------------------------------
 * Author: GPDB
 * ----------------------------------------------------------------------------------------------------
 * Date of revision: 10-16-2024
 * ----------------------------------------------------------------------------------------------------
 * Copyright Notice:
 * Copyright (C) 2018 by the Department of Science and Technology - National Research Council of the Philiipines
 */

class Login extends EJ_Controller {

	public function __construct() {

		parent::__construct();
		
		/**
		 * Helpers, Library, Security headers are all in EJ_controller.php
		 */
		
		 $this->load->model('Client_journal_model');
		 $this->load->model('Library_model');
		 $this->load->model('Login_model');
		 $this->load->model('Search_model');
		 $this->load->model('CSF_model');
		 $this->load->model('Oprs/User_model');
		 $this->load->model('Admin/Journal_model');
		 $this->load->model('Admin/Email_model');
	}
    

	/**
	 * Login page
	 *
	 * @return void
	 */
	public function index($flag = null){

		if (!$this->session->userdata('user_id')) {
			$data['country'] = $this->Library_model->get_library('tblcountries', 'members');
			$data['regions'] = $this->Library_model->get_library('tblregions', 'members');
			$data['citations'] = $this->Client_journal_model->totalCitationsCurrentYear();
			$data['downloads'] = $this->Client_journal_model->totalDownloadsCurrentYear();
			$data['titles'] = $this->Client_journal_model->getTitles();
			$data['educations'] = $this->Client_journal_model->getEducations();
			$data['main_title'] = "eJournal";
			$data['main_content'] = "client/login";

			if($flag){
				$this->session->set_flashdata('active_link1', '');
				$this->session->set_flashdata('active_link2', 'active');
				$this->session->set_flashdata('active_tab1', '');
				$this->session->set_flashdata('active_tab2', 'show active');
			}
			$this->_LoadPage('common/body', $data);
		}else{
			redirect('/');
		}
	}

	/**
	 * Authenticate user login
	 *
	 * @return void
	 */
    public function authenticate(){

		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'required|trim');

		if($this->form_validation->run() == FALSE){
			$errors = [];

			if (form_error('email')) {
				$errors['email'] = strip_tags(form_error('email'));
			}
			if (form_error('password')) {
				$errors['password'] = strip_tags(form_error('password'));
			}

			// Set flashdata to pass validation errors and form data to the view
			$this->session->set_flashdata('validation_errors', $errors);
			redirect('client/login');
		}else{
			
			$email = $this->input->post('email', TRUE);
			$password = $this->input->post('password', TRUE);
		
			// Check user credentials using your authentication logic
			$validateUser = $this->Login_model->validate_user($email);

			if ($validateUser) {

				//check if account activated
				if($validateUser[0]->status == 1){
					if (password_verify($password, $validateUser[0]->password)) {
						$this->Login_model->clear_login_attempts($validateUser[0]->email);
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
	}

	/**
	 * Send login otp
	 *
	 * @param string $email
	 * @return void
	 */
	public function send_login_otp($email) {
	
		$user = $this->Client_journal_model->get_user_info($email);
		$name = $user[0]->title . ' ' . $user[0]->first_name . ' ' . $user[0]->last_name;
		$otp = substr(number_format(time() * rand(),0,'',''),0,6);
		$ref_code = random_string('alnum', 16);

		$this->Login_model->save_otp(
			[
				'otp' => password_hash($otp, PASSWORD_BCRYPT),
				// 'otp' => $otp,
				'otp_date' => date('Y-m-d H:i:s'),
				'otp_ref_code' => $ref_code
			],
			['email' => $email]
		);

		$link = base_url() . 'client/login/verify_otp/'.$ref_code;
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
											<div class="alert alert-primary d-flex align-items-center w-50">
												<i class="oi oi-circle-check me-1"></i>Please check your email for the 6-digit code.
											</div>');
		$this->session->set_userdata('otp_ref_code', $ref_code);
		redirect($link);
	}
	
	/**
	 * Send email alert notif to admin on login attempt
	 *
	 * @param string $email
	 * @return void
	 */
	public function send_email_alert($email) {
		
		$user = $this->Client_journal_model->get_user_info($email);

		if($user){
			$name = $user[0]->title . ' ' . $user[0]->first_name . ' ' . $user[0]->last_name;
		}else{
			$attempts = $this->Login_model->get_login_attempts($email);
			$name = '(Unregistered/Invalid Account)';
		}

		$attempts = $this->Login_model->get_login_attempts($email);
		$last_attempt_time = $attempts[0]->attempt_time;



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
	
		// $mail->AddAddress('nrcp.ejournal@gmail.com');
		$mail->AddAddress('gerard_balde@yahoo.com');


		$date = date("F j, Y") . '<br/><br/>';

		$emailBody = 'Dear Admin,
		<br><br>
		There have been multiple unsuccessful login attempts.
		<br><br>
		Name: <strong>'.$name.'</strong>
		<br>
		Email: <strong>'.$email.'</strong>
		<br>
		Attempts: <strong>3</strong>
		<br>
		Timestamp: <strong>'.$last_attempt_time.'</strong>
		<br><br>
		Please investigate this activity to ensure the security of your system.
		<br><br>

		Sincerely,
		<br>
		eJournal System';
		
		// send email
		$mail->Subject = 'Login Attempt Alert';
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
			<div class="alert alert-danger d-flex align-items-center w-50">
				<i class="oi oi-circle-x me-1"></i>Link expired.
			</div>');

			$data['main_title'] = "eJournal";
			$data['main_content'] = "client/login_otp";
			$data['disabled'] = "disabled";
			$this->_LoadPage('common/body', $data);
		}else{ // code expire
			// $otp_date = $isOtpRefExist[0]->otp_date;
			// $current_date = date('Y-m-d H:i:s');

			// check if code expired after 5 minutes
			if ($this->compareDates($otp_date, $current_date) > 4) {
				$this->session->set_flashdata('otp', '
				<div class="alert alert-danger d-flex align-items-center w-50">
					<i class="oi oi-circle-x me-1"></i>Code expired.
				</div>');
	
				$data['ref_code'] = $isOtpRefExist[0]->otp_ref_code;
				$data['disabled'] = "disabled";
				$data['main_title'] = "eJournal";
				$data['main_content'] = "client/login_otp";
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
					$data['main_content'] = "client/login_otp";
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
									<a href="' . base_url() . 'client/ejournal/csf_arta/' . $arta_ref_code . '" class="btn btn-sm btn-warning" target="_blank">View</a>
								</div>
							</div>';
	
							$this->session->set_userdata('csf_arta', $csf_arta);
						}


						redirect('client/ejournal/');
					} else {
						//invalid code
						$this->session->set_flashdata('otp', '
															<div class="alert alert-danger d-flex align-items-center w-50">
																<i class="oi oi-circle-x me-1"></i>Invalid code. Try again.
															</div>');
		
						$data['main_title'] = "eJournal";
						$data['main_content'] = "client/login_otp";
						$this->_LoadPage('common/body', $data);
					}
				}
			}
		}
		
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

	/**
	 * Forgot password page
	 *
	 * @return void
	 */
	public function forgot_password(){
		$data['citations'] = $this->Client_journal_model->totalCitationsCurrentYear();
		$data['downloads'] = $this->Client_journal_model->totalDownloadsCurrentYear();
		$data['main_title'] = "eJournal";
		$data['main_content'] = "client/forgot_password";
		$this->_LoadPage('common/body', $data);
	}

	/**
	 * Vadalite reset password form
	 *
	 * @return void
	 */
	public function reset_password(){

		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|xss_clean');

		if($this->form_validation->run() == FALSE){
			$errors = [];

            if (form_error('email')) {
                $errors['email'] = strip_tags(form_error('email'));
            }

            // Set flashdata to pass validation errors and form data to the view
            $this->session->set_flashdata('validation_errors', $errors);
			redirect('client/login/forgot_password');
		}else{
			
			$email = $this->input->post('email', TRUE);
		
			// Check user credentials using your authentication logic
			$validateUser = $this->Login_model->validate_user($email);

			if ($validateUser) {
				save_log_ej($validateUser[0]->id, 'Temporary password request email sent');
				//send email
				$this->send_temp_password($validateUser[0]->email);
			}else{
				$this->session->set_flashdata('error', 'Email does not exist in our system.');
				redirect('client/login/forgot_password');
			}
		}
	}

	/**
	 * Send email for temporary password after reset password request
	 *
	 * @param [type] $email
	 * @return void
	 */
	public function send_temp_password($email) {
		
		$user = $this->Client_journal_model->get_user_info($email);
		$name = $user[0]->title . ' ' . $user[0]->first_name . ' ' . $user[0]->last_name;
		$temp_pass = random_string('alnum', 8);

		$this->Login_model->update_password(
			[
				'password' => password_hash($temp_pass, PASSWORD_BCRYPT), 
			],
			['email' => $email]
		);

		// $link = base_url() . 'client/login/';
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
		You have requested a temporary password for your eJournal account.
		<br><br>
		Your new temporary password is: 
		<br><br>
		<strong style="font-size:20px">'.$temp_pass.'</strong>
		<br><br>
		Please log in to your account using this temporary password and change it to a more secure one as soon as possible.
		<br><br>
		If you did not request this password reset, please disregard this email.
		<br><br>
		Sincerely,
		<br><br>
		NRCP Research Journal
		<br><br><br>
		<em>This is an automated message. Please do not reply to this email. For assistance, please contact our support team at [Support Email Address]</em>';
		
		// send email
		$mail->Subject = 'Reset Password';
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

		$this->session->set_flashdata('reset_password_success', 'Please check your email for your temporary password.');
		redirect('client/login/forgot_password');
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
		$email = $output[0]->email;
		save_log_ej($output[0]->id, 'Resend login otp code');
		$this->send_login_otp($email);
	}

	/**
	 * Destroy session on idle
	 *
	 * @return void
	 */
	public function destroy_user_session(){
		$id = $this->session->userdata('user_id');
		$token = $this->input->post('user_access_token');
		$output = $this->Login_model->get_access_token($id);
		if($output[0]->tkn_value == $token){
			save_log_ej($id, 'Session expired');
			$this->Login_model->delete_access_token($id);
			$this->session->unset_userdata('user_id');
			$this->session->unset_userdata('email');
			$this->session->sess_destroy();
		}else{
			echo 'Error destroying session.';
		}
	}

	/**
	 * Logout user
	 *
	 * @return void
	 */
	public function logout(){
		$id = $this->session->userdata('user_id');
		$this->Login_model->delete_access_token($id);
		if($id){
			save_log_ej($id, 'Logout successful');
		}
        $this->session->unset_userdata('user_id');
        $this->session->unset_userdata('email');
        $this->session->sess_destroy();
		redirect('client/login');
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
}
