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
		$this->load->library("My_phpmailer");
		$objMail = $this->my_phpmailer->load();
		$this->load->helper('string');
        $this->load->helper('form');
        $this->load->library('session'); 
		$this->load->library('form_validation');
		error_reporting(0);
	}
    
    public function authenticate(){

		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'required|trim|min_length[8]');

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
			
			$email = $this->input->post('email');
			$password = $this->input->post('password');
		
			// Check user credentials using your authentication logic
			$validateUser = $this->Login_model->validate_user($email);

			if ($validateUser) {

				if (password_verify($password, $validateUser[0]->password)) {
					$this->Login_model->clear_login_attempts($validateUser[0]->email);
					//send otp to email
					$this->send_otp($email);
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

	public function send_otp($email) {
		
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
		<em>THIS IS AN AUTOMATED MESSAGE PLEASE DO NOT REPLY</em>';
		
		// send email
		// $mail->Subject = 'Login Verification';
		// $mail->Body = $emailBody;
		// $mail->IsHTML(true);
		// $mail->smtpConnect([
		// 	'ssl' => [
		// 		'verify_peer' => false,
		// 		'verify_peer_name' => false,
		// 		'allow_self_signed' => true,
		// 	],
		// ]);

		// if (!$mail->Send()) {
		// 	echo '</br></br>Message could not be sent.</br>';
		// 	echo 'Mailer Error: ' . $mail->ErrorInfo . '</br>';
		// 	exit;
		// }

		$this->session->set_flashdata('otp', '
											<div class="alert alert-primary d-flex align-items-center">
												<i class="oi oi-circle-check me-1"></i>Your code was sent to your email.
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
		$isOtpRefExist = $this->Login_model->validate_otp_ref($ref);

		//code expired
		if($isOtpRefExist[0]->otp_ref_code == null){
			$this->session->set_flashdata('otp', '
			<div class="alert alert-danger d-flex align-items-center">
				<i class="oi oi-circle-x me-1"></i>Code expired.
			</div>');

			$data['main_title'] = "eJournal";
			$data['main_content'] = "client/otp";
			$data['disabled'] = "disabled";
			$this->_LoadPage('common/body', $data);
		}else{

			$otp_date = $isOtpRefExist[0]->otp_date;
			$current_date = date('Y-m-d H:i:s');

			//check if code expired after 5 minutes
			if ($this->compareDates($otp_date, $current_date)  > 5) {
				$this->session->set_flashdata('otp', '
				<div class="alert alert-danger d-flex align-items-center">
					<i class="oi oi-circle-x me-1"></i>Code expired.
				</div>');
	
				$data['main_title'] = "eJournal";
				$data['main_content'] = "client/otp";
				$data['disabled'] = "disabled";
				$this->_LoadPage('common/body', $data);
			} else {
			
				$ref_code = $this->input->post('ref');
			
				$this->form_validation->set_rules('otp', 'OTP', 'required|trim|min_length[6]');
			
				if($this->form_validation->run() == FALSE){
					$errors = [];
		
					if (form_error('otp')) {
						$errors['otp'] = strip_tags(form_error('otp'));
					}
		
					// Set flashdata to pass validation errors and form data to the view
					$this->session->set_flashdata('validation_errors', $errors);
					$data['main_title'] = "eJournal";
					$data['main_content'] = "client/otp";
					$this->_LoadPage('common/body', $data);
				}else{
					$otp = $this->input->post('otp');
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
						$data['main_content'] = "client/otp";
						$this->_LoadPage('common/body', $data);
					}
				}
			}
	
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

	public function logout(){
        $this->session->unset_userdata('user_id');
        $this->session->unset_userdata('email');
        $this->session->sess_destroy();
		redirect('client/ejournal/login');

	}
}