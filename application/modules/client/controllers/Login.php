<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

/**
 * File Name: Ejournal.php
 * ----------------------------------------------------------------------------------------------------
 * Purpose of this file:
 * To manage data to display in client landing page
 * ----------------------------------------------------------------------------------------------------
 * System Name: Online Research Journal System
 * ----------------------------------------------------------------------------------------------------
 * Author: -
 * ----------------------------------------------------------------------------------------------------
 * Date of revision: -
 * ----------------------------------------------------------------------------------------------------
 * Copyright Notice:
 * Copyright (C) 2018 by the Department of Science and Technology - National Research Council of the Philiipines
 */

class Login extends EJ_Controller {

	public function __construct() {

		parent::__construct();

		$this->load->model('Login_model');
		$this->load->model('Client_journal_model');
		$this->load->model('Library_model');
		$this->load->library("My_phpmailer");
		$objMail = $this->my_phpmailer->load();
		$this->load->helper('string');
        $this->load->helper('form');
        $this->load->helper('security');
        $this->load->library('session'); 
		$this->load->library('form_validation');
		error_reporting(0);
		
	}
    
    public function authenticate(){

		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'required|trim|min_length[8]|max_length[20]');

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
			redirect('client/ejournal/login');
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
								$this->session->set_flashdata('error', 'Invalid email or password.');
				
								//store login attempt
								$data = [
									'user_id' => $validateUser[0]->id,
									'user_email' => $validateUser[0]->email,
									'attempt_time' => date('Y-m-d H:i:s')
								];
	
								$this->Login_model->store_login_attempts($data); 
							}
							else{
								$this->session->set_flashdata('error', 'Account temporarily locked for&nbsp;<strong>'.(30 - $time_remaining).' minutes</strong>.');
							}
	
							redirect('client/ejournal/login');
						}else{
							//TODO:store in system logs
							//store login attempt
							$data = [
								'user_id' => $validateUser[0]->id,
								'user_email' => $validateUser[0]->email,
								'attempt_time' => date('Y-m-d H:i:s')
							];
	
							$this->Login_model->store_login_attempts($data);  
						}
	
						$this->session->set_flashdata('error', 'Invalid email or password.');
						redirect('client/ejournal/login'); 
					
	
					}
				}else{
					$this->session->set_flashdata('error', 'Account not activated. Please check your email for a create account verification code.');
					redirect('client/ejournal/login');
				}
			} else {

				$count_attempt = count($this->Login_model->get_login_attempts($email));

				if($count_attempt == 3){

					$last_attempt_time = $this->Login_model->get_login_attempts($email);
					$last_attempt_time = $last_attempt_time[0]->attempt_time;
					$current_date = date('Y-m-d H:i:s');
					$time_remaining = $this->compareDates($last_attempt_time, $current_date);

					$this->send_email_alert($email);
					
					if ($time_remaining  > 30) {
						$this->Login_model->clear_login_attempts($email);
						$this->session->set_flashdata('error', 'Invalid email or password.');
						
						//store login attempt
						$data = [
							'user_email' => $email,
							'attempt_time' => date('Y-m-d H:i:s')
						];

						$this->Login_model->store_login_attempts($data); 
					}
					else{
						$this->session->set_flashdata('error', 'Account temporarily locked for&nbsp;<strong>'.(30 - $time_remaining).' minutes</strong>.');
					}

					redirect('client/ejournal/login');
				}else{
					//TODO:store in system logs
					//store login attempt
					$data = [
						'user_email' => $email,
						'attempt_time' => date('Y-m-d H:i:s')
					];

					$this->Login_model->store_login_attempts($data); 
				}

			
				$this->session->set_flashdata('error', 'Invalid email or password.');
				redirect('client/ejournal/login');
			}
		}
	}

	public function send_login_otp($email) {
		
		$user = $this->Client_journal_model->get_user_info($email);
		$name = $user[0]->title . ' ' . $user[0]->first_name . ' ' . $user[0]->last_name;
		$otp = substr(number_format(time() * rand(),0,'',''),0,6);
		$ref_code = random_string('alnum', 16);

		$this->Login_model->save_otp(
			[
				'otp' => $otp, 
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
												<i class="oi oi-circle-check me-1"></i>We sent a 6 digit code to your email.
											</div>');
		$this->session->set_userdata('otp_ref_code', $ref_code);
		redirect($link);
	}
	
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
		There have been multiple unsuccessful login attempts/
		<br><br>
		<strong>Details:</strong>
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

	
	public function verify_otp($ref){
		//check if ref code exist
		$ref = $this->security->xss_clean($ref);
		$isOtpRefExist = $this->Login_model->validate_otp_ref($ref);

		//code expired
		if($isOtpRefExist[0]->otp_ref_code == null){
			$this->session->set_flashdata('otp', '
			<div class="alert alert-danger d-flex align-items-center">
				<i class="oi oi-circle-x me-1"></i>Link expired.
			</div>');

			$data['main_title'] = "eJournal";
			$data['main_content'] = "client/login_otp";
			$data['disabled'] = "disabled";
			$this->_LoadPage('common/body', $data);
		}else{
			$otp_date = $isOtpRefExist[0]->otp_date;
			$current_date = date('Y-m-d H:i:s');

			//check if code expired after 5 minutes
			if ($this->compareDates($otp_date, $current_date)  > 4) {
				$this->session->set_flashdata('otp', '
				<div class="alert alert-danger d-flex align-items-center">
					<i class="oi oi-circle-x me-1"></i>Code expired.
				</div>');
	
				$data['main_title'] = "eJournal";
				$data['main_content'] = "client/login_otp";
				$data['disabled'] = "disabled";
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
					$verifyOTP = $this->Login_model->validate_otp($otp, $ref_code);
					if ($verifyOTP) {
						$this->session->set_userdata('user_id', $verifyOTP[0]->id);
						$this->session->set_userdata('email',  $verifyOTP[0]->email);
						$this->session->unset_userdata('otp_ref_code');
						$this->Login_model->delete_otp($verifyOTP[0]->id);
						redirect('client/ejournal/');
					} else {
						$this->session->set_flashdata('otp', '
															<div class="alert alert-danger d-flex align-items-center">
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
	
	public function new_account_verify_otp($ref){
		//check if ref code exist
		$isOtpRefExist = $this->Login_model->validate_otp_ref($ref);

		//code expired
		if($isOtpRefExist[0]->otp_ref_code == null){
			$this->session->set_flashdata('otp', '
			<div class="alert alert-danger d-flex align-items-center">
				<i class="oi oi-circle-x me-1"></i>Link expired.
			</div>');

			$data['main_title'] = "eJournal";
			$data['main_content'] = "client/new_account_otp";
			$data['disabled'] = "disabled";
			$this->_LoadPage('common/body', $data);
		}else{
			$otp_date = $isOtpRefExist[0]->otp_date;
			$current_date = date('Y-m-d H:i:s');

			//check if code expired after 5 minutes
			// if ($this->compareDates($otp_date, $current_date)  > 4) {
			// 	$this->session->set_flashdata('otp', '
			// 	<div class="alert alert-danger d-flex align-items-center">
			// 		<i class="oi oi-circle-x me-1"></i>Code expired.
			// 	</div>');
	
			// 	$data['main_title'] = "eJournal";
			// 	$data['main_content'] = "client/new_account_otp";
			// 	$data['disabled'] = "disabled";
			// 	$this->_LoadPage('common/body', $data);
			// } else {
			
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
					$data['main_content'] = "client/new_account_otp";
					$this->_LoadPage('common/body', $data);
				}else{
					$otp = $this->input->post('otp', TRUE);
					// Check user credentials using your authentication logic
					$verifyOTP = $this->Login_model->validate_otp($otp, $ref_code);
					if ($verifyOTP) {
						$this->session->set_userdata('user_id', $verifyOTP[0]->id);
						$this->session->set_userdata('email',  $verifyOTP[0]->email);
						$this->session->unset_userdata('otp_ref_code');
						$this->Login_model->activateAccount($verifyOTP[0]->id);
						$this->Login_model->delete_otp($verifyOTP[0]->id);
						redirect('client/ejournal/');
					} else {
						$this->session->set_flashdata('otp', '
															<div class="alert alert-danger d-flex align-items-center">
																<i class="oi oi-circle-x me-1"></i>Invalid code. Try again.
															</div>');
		
						$data['main_title'] = "eJournal";
						$data['main_content'] = "client/new_account_otp";
						$this->_LoadPage('common/body', $data);
					}
				}
			// }
		}
	}

	function compareDates($date1, $date2) {
		$date1 = new DateTime($date1);
		$date2 = new DateTime($date2);
	
		$interval = $date1->diff($date2);
		$minutes = $interval->h * 60 + $interval->i;
		
		//otp valid for 5 minutes
		return $minutes;
	}

	public function forgot_password(){
		$data['main_title'] = "eJournal";
		$data['main_content'] = "client/forgot_password";
		$this->_LoadPage('common/body', $data);

	}

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
				//send email
				$this->send_temp_password($validateUser[0]->email);
			}else{
				$this->session->set_flashdata('error', 'Email does not exist in our system.');
				redirect('client/login/forgot_password');
			}
		}
	}

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
		You have requested a new temporary password for your eJournal account.
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

	public function logout(){
        $this->session->unset_userdata('user_id');
        $this->session->unset_userdata('email');
        $this->session->sess_destroy();
		redirect('client/ejournal/login');
	}

	public function get_current_otp($refCode){
		$output = $this->Login_model->get_current_otp($refCode);
		echo json_encode($output);
	}

	public function resend_code($refCode){
		$output = $this->Login_model->get_current_otp($refCode);
		$email = $output[0]->email;
		$this->send_login_otp($email);
	}
	
	function profile(){
		$id = $this->session->userdata('user_id');
		if($id){
			$data['profile'] = $this->Login_model->get_user_profile($id);
			$data['educations'] = $this->Client_journal_model->getEducations();
			$data['country'] = $this->Library_model->get_library('tblcountries', 'members');
			$data['provinces'] = $this->Library_model->get_library('tblprovinces', 'members', array('province_region_id' => $data['profile'][0]->region));
			$data['cities'] = $this->Library_model->get_library('tblcities', 'members', array('city_province_id' => $data['profile'][0]->province));
			$data['titles'] = $this->Client_journal_model->getTitles();
			$data['regions'] = $this->Library_model->get_library('tblregions', 'members');
			$data['main_title'] = "eJournal";
			$data['main_content'] = "client/user_profile";
			$this->_LoadPage('common/body', $data);
		}else{
			redirect('/');
		}
	}

	function update_profile(){
		$id = $this->session->userdata('user_id');

		if($id){
		
			$this->form_validation->set_rules('new_email', 'Email', 'required|trim|valid_email');
			$this->form_validation->set_rules('title', 'Title', 'required|trim');
			$this->form_validation->set_rules('first_name', 'First Name', 'required|trim');
			$this->form_validation->set_rules('last_name', 'Last Name', 'required|trim');
			$this->form_validation->set_rules('middle_name', 'Middle Name', 'trim');
			$this->form_validation->set_rules('extension_name', 'Extension Name', 'trim');
			$this->form_validation->set_rules('sex', 'Sex', 'required|trim');
			$this->form_validation->set_rules('educational_attainment', 'Educational Attainment', 'required|trim');
			$this->form_validation->set_rules('affiliation', 'Affiliation', 'required|trim');
	
			//require region,province,city for philippines
			if($this->input->post('country') == 175){
				$this->form_validation->set_rules('region', 'Region', 'required|trim');
				$this->form_validation->set_rules('province', 'Province', 'required|trim');
				$this->form_validation->set_rules('city', 'City', 'required|trim');
			}
	
			$this->form_validation->set_rules('contact', 'Contact', 'required|trim');
			$this->form_validation->set_rules('new_password', 'Password', 'trim|min_length[8]|max_length[20]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/]',
			array('regex_match' => 'Password must contain at least 1 letter, 1 number and 1 special character.'));
			$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|matches[new_password]');
	
			$validations = ['new_email', 'title', 'first_name', 'last_name', 'extension_name', 'sex', 'educational_attainment', 'affiliation', 'country', 'region', 'province', 'city', 'contact', 'new_password', 'confirm_password'];
	
			if($this->form_validation->run() == FALSE){
				$errors = [];
	
				foreach($validations as $value){
					//store entered value to display on redirect
					if($value == 'country'){
						if($this->input->post($value)){
							$this->session->set_flashdata($value, $this->input->post($value, TRUE));
						}else{
							$this->session->set_flashdata($value, 175);
						}
					}else{
						$this->session->set_flashdata($value, $this->input->post($value, TRUE));
					}
	
					//store errors to display on redirect
					if (form_error($value)) {
						$errors[$value] = strip_tags(form_error($value));
	
					}
				}
		
				//return password data and strenght data
				$password = $this->input->post('new_password', TRUE);
				
				if (strlen($password) >= 8) {
					$strength += 10;
				}
				if (strlen($password) >= 12) {
					$strength += 15;
				}
				if (strlen($password) >= 16) {
					$strength += 20;
				}
			
				if (preg_match('/[A-Z]/', $password)) {
					$strength += 15;
				}
				if (preg_match('/[a-z]/', $password)) {
					$strength += 10;
				}
				if (preg_match('/[0-9]/', $password)) {
					$strength += 15;
				}
				if (preg_match('/[^A-Za-z0-9]/', $password)) {
					$strength += 15;
				}
	
				if ($strength <= 25) {
					$bar_color = 'red';
					$password_strength = 'Weak';
				} else if ($strength <= 50) {
					$bar_color = 'orange';
					$password_strength = 'Good';
				} else if ($strength <= 75) {
					$bar_color = 'yellow';
					$password_strength = 'Fair';
				}else {
					$bar_color = 'green';
					$password_strength = 'Excellent';     
				}
	
				$this->session->set_flashdata('bar_style', 'style="width:'. $strength .'%; background-color:'. $bar_color .'"');
				$this->session->set_flashdata('password_strength', $password_strength);
	
				//return province value and options if province has value
				$region = $this->input->post('region', TRUE);
	
				if($region > 0){
					$provinces = $this->Library_model->get_library('tblprovinces', 'members', array('province_region_id' => $region));
					$this->session->set_flashdata('provinces', $provinces);
				}
	
				//return city value and options if city has value
				$province = $this->input->post('province', TRUE);
	
				if($province){
					$cities = $this->Library_model->get_library('tblcities', 'members', array('city_province_id' => $province));
					$this->session->set_flashdata('cities', $cities);
				}
	
				// Set flashdata to pass validation errors and form data to the view
				$this->session->set_flashdata('message', '
				<div class="alert alert-danger d-flex align-items-center">
					<i class="fa fa-info-circle me-1"></i>Please check the form and make corrections.</div>');
				$this->session->set_flashdata('signup_validation_errors', $errors);
				redirect('client/login/profile');
			}else{
	
				$email = $this->input->post('new_email', TRUE);
				
				//check if email is exisiting
				$isExist = $this->Login_model->check_exist_email($id, $email);
				if($isExist){
					$this->session->set_flashdata('message', '
					<div class="alert alert-danger d-flex align-items-center">
						<i class="fa fa-info-circle me-1"></i>Please check the form and make corrections.</div>');
					$errors['new_email'] = 'Email already in use. Please use different email.';
					$this->session->set_flashdata('new_email', $email);
					$this->session->set_flashdata('signup_validation_errors', $errors);
					redirect('client/login/profile');
				}
	
				$this->session->set_flashdata('message', '
								<div class="alert alert-primary d-flex align-items-center">
									<i class="fa fa-check-circle me-1"></i>Your profile has been update.</div>');
									
				
				//update password
				$new_password = $this->input->post('new_password', TRUE);
				
				$userAuth = [
					'email' => $email,
					'updated_at' => date('Y-m-d H:i:s')
				];

				if (!empty($new_password)) {
					$userAuth['password'] = password_hash($new_password, PASSWORD_BCRYPT);
				}

				$whereAuth = array('id' => $id);

				//save log of change password

				$this->Login_model->update_user_auth(array_filter($userAuth), $whereAuth);


				//update user profile
				$userProfile = [
					'title' => $this->input->post('title', TRUE),
					'first_name' => $this->input->post('first_name', TRUE),
					'last_name' => $this->input->post('last_name', TRUE),
					'middle_name' => $this->input->post('middle_name', TRUE),
					'extension_name' => $this->input->post('extension_name', TRUE),
					'sex' => $this->input->post('sex', TRUE),
					'educational_attainment' => $this->input->post('educational_attainment', TRUE),
					'affiliation' => $this->input->post('affiliation', TRUE),
					'country' => $this->input->post('country', TRUE),
					'region' => $this->input->post('region', TRUE),
					'province' => $this->input->post('province', TRUE),
					'city' => $this->input->post('city', TRUE),
					'contact' => $this->input->post('contact', TRUE),
					'updated_at' => date('Y-m-d H:i:s')
				];

				//save log on update

				$whereProfile = array('user_id' => $id);
	
				$this->Login_model->update_user_profile($userProfile, $whereProfile);

				redirect('client/login/profile');
			}

		}else{
			redirect('/');
		}
	}
}

// add set ruls form validation