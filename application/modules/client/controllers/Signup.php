<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

/**
 * File Name: Signup.php
 * ----------------------------------------------------------------------------------------------------
 * Purpose of this file:
 * To manage data to display in client landing page
 * ----------------------------------------------------------------------------------------------------
 * System Name: Online Research Journal System
 * ----------------------------------------------------------------------------------------------------
 * Author: GPDB
 * ----------------------------------------------------------------------------------------------------
 * Date created: 11-16-2024
 * ----------------------------------------------------------------------------------------------------
 * Copyright Notice:
 * Copyright (C) 2018 by the Department of Science and Technology - National Research Council of the Philiipines
 */

class Signup extends EJ_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model('Client_journal_model');
		$this->load->model('Library_model');
		$this->load->model('Login_model');
		$this->load->model('Search_model');
		$this->load->model('CSF_model');
		$this->load->model('Oprs/User_model');
		$this->load->model('Admin/Journal_model');
		$this->load->model('Admin/Email_model');
		$this->load->library("My_phpmailer");
		$objMail = $this->my_phpmailer->load();
		$this->load->helper('visitors_helper');
		$this->load->helper('string');
        $this->load->helper('form');
        $this->load->library('session'); 
		$this->load->helper('security');
		$this->load->library('form_validation');

		error_reporting(0);

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
	 * Create account
	 *
	 * @return void
	 */
	public function create_account(){
		
		$this->form_validation->set_rules('new_email', 'Email', 'required|trim|valid_email|is_unique[tblusers.email]', array('is_unique' => 'Email already in use. Please use different email.'));
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

		$this->form_validation->set_rules('contact', 'Contact', 'required|trim|numeric|exact_length[11]');
		$this->form_validation->set_rules('new_password', 'Password', 'required|trim|min_length[8]|max_length[20]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/]',
		array('regex_match' => 'Password must contain at least 1 letter, 1 number and 1 special character.'));
		$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|trim|matches[new_password]');

		$validations = ['new_email', 'title', 'first_name', 'last_name', 'extension_name', 'sex', 'educational_attainment', 'affiliation', 'country', 'region', 'province', 'city', 'contact', 'new_password', 'confirm_password'];

		if($this->form_validation->run() == FALSE){
			$errors = [];

			foreach($validations as $value){
				//store entered value to display on redirect
				if($value == 'country'){
					if($this->input->post($value)){
						$this->session->set_flashdata($value, $this->input->post($value));
					}else{
						$this->session->set_flashdata($value, 175);
					}
				}else{
					$this->session->set_flashdata($value, $this->input->post($value));
				}

				//store errors to display on redirect
				if (form_error($value)) {
					$errors[$value] = strip_tags(form_error($value));

				}
			}
	
			//return password data and strenght data
			$password = $this->input->post('new_password');
			
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
			$region = $this->input->post('region');

			if($region > 0){
				$provinces = $this->Library_model->get_library('tblprovinces', 'members', array('province_region_id' => $region));
				$this->session->set_flashdata('provinces', $provinces);
			}

			//return city value and options if city has value
			$province = $this->input->post('province');

			if($province){
				$cities = $this->Library_model->get_library('tblcities', 'members', array('city_province_id' => $province));
				$this->session->set_flashdata('cities', $cities);
			}

            // Set flashdata to pass validation errors and form data to the view
            $this->session->set_flashdata('signup_validation_errors', $errors);
            $this->session->set_flashdata('error', 'Please check the required fields and make corrections.');
            $this->session->set_flashdata('active_link1', '');
            $this->session->set_flashdata('active_link2', 'active');
            $this->session->set_flashdata('active_tab1', '');
            $this->session->set_flashdata('active_tab2', 'show active');
			redirect('client/ejournal/login');
		}else{

			$otp = substr(number_format(time() * rand(),0,'',''),0,6);
			$ref_code = random_string('alnum', 16);
			$email = $this->input->post('new_email', TRUE);
			
			$lastUserID = $this->get_last_user_id();
			$newUserID = $this->generate_user_id('0000', intval($lastUserID) + 1);

			//save user account
			$userAuth = [
				'id' => $newUserID,
				'email' => $email,
				'password' => password_hash($this->input->post('new_password', TRUE), PASSWORD_BCRYPT),
				'status' => 0,
				'otp' => password_hash($otp, PASSWORD_BCRYPT), 
				'otp_date' => date('Y-m-d H:i:s'),
				'otp_ref_code' => $ref_code,
				'created_at' => date('Y-m-d H:i:s')
			];
			
			$this->Login_model->create_user_auth($userAuth);

			//save user profile
			$userProfile = [
				'user_id' => $newUserID,
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
				'created_at' => date('Y-m-d H:i:s')
			];

			$this->Login_model->create_user_profile($userProfile);

			//send email otp for create account
			$this->send_create_account_otp($email, $otp, 'client', null);
		}
		
	}

	/**
	 * Send email with otp code for account creation
	 *
	 * @param string $email
	 * @return void
	 */
	public function send_create_account_otp($email, $otp, $account_type, $nrcp_member) {
		
		if($account_type == 'client'){
			$user = $this->Client_journal_model->get_user_info($email);
			$name = $user[0]->title . ' ' . $user[0]->first_name . ' ' . $user[0]->last_name;
			$ref_code = $user[0]->otp_ref_code;
			$link = base_url() . 'client/signup/new_account_verify_otp/'.$ref_code;
		}else{ // author
			if($nrcp_member == 1){ // skms member
				$user_info = $this->User_model->get_nrcp_member_info($email);
				$name = $user_info['title_name'] . ' ' . $user_info['pp_first_name'] . ' ' . $user_info['pp_last_name'];
				$otp_info = $this->User_model->get_user_info_by_email($email);
				$ref_code = $otp_info[0]->otp_ref_code;
				$link = base_url() . 'client/signup/author_account_verify_otp/'.$ref_code;
			}else{ // oprs member
				$user_info = $this->Client_journal_model->get_user_info($email);
				$name = $user_info[0]->title . ' ' . $user_info[0]->first_name . ' ' . $user_info[0]->last_name;
				
				$otp_info = $this->User_model->get_user_info_by_email($email);
				$ref_code = $otp_info[0]->otp_ref_code;
				$link = base_url() . 'client/signup/author_account_verify_otp/'.$ref_code;
			}	
		}

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
		Please enter this code to verify your new account.
		<br><br>
		<strong style="font-size:20px">'.$otp.'</strong>
		<br><br>
		Or click the link below to redirect in the verification page:
		<br><br>
		'.$link.'
		<br><br>
		Link not working? Copy and paste the link into your browser.
		<br><br><br>
		Sincerely,
		<br><br>
		NRCP Research Journal
		<br><br><br>
		<em>This is an automated message. Please do not reply to this email. For assistance, please contact our support team at [Support Email Address]</em>';
		
		// send email
		$mail->Subject = 'Verify New Account';
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

	/**
	 * Resend new client account otp
	 *
	 * @param string $refCode
	 * @return void
	 */
	public function resend_new_client_account_code($refCode){

		$refCode = $this->security->xss_clean($refCode);
		$output = $this->Login_model->get_current_otp($refCode);
		$email = $output[0]->email;
		$otp = substr(number_format(time() * rand(),0,'',''),0,6);
		$ref_code = random_string('alnum', 16);

		$this->Login_model->save_otp(
			[
				'otp' => password_hash($otp, PASSWORD_BCRYPT),
				'otp_date' => date('Y-m-d H:i:s'),
				'otp_ref_code' => $ref_code
			],
			['email' => $email]
		);
		
		save_log_ej($output[0]->id, 'Resend new client account otp code');
		$this->send_create_account_otp($email, $otp, 'client', null);
	}

	
	public function author_account_verify_otp($ref){
		$ref = $this->security->xss_clean($ref);
		$this->session->set_userdata('otp_ref_code', $ref);
		//check if ref code exist
		$isOtpRefExist = $this->Login_model->get_current_otp_oprs($ref);
		//link expired
		if($isOtpRefExist[0]->otp_ref_code == null){
			$this->session->set_flashdata('otp', '
			<div class="alert alert-danger d-flex align-items-center w-50">
				<i class="oi oi-circle-x me-1"></i>Link expired.
			</div>');

			$data['main_title'] = "eJournal";
			$data['main_content'] = "client/author_account_otp";
			$data['disabled'] = "disabled";
			$this->_LoadPage('common/body', $data);
		}else{
			$otp_date = $isOtpRefExist[0]->otp_date;
			$current_date = date('Y-m-d H:i:s');

			//check if code expired after 5 minutes
			if ($this->compareDates($otp_date, $current_date)  > 4) {
				$this->session->set_flashdata('otp', '
				<div class="alert alert-danger d-flex align-items-center w-50">
					<i class="oi oi-circle-x me-1"></i>Code expired.
				</div>');
	
				$data['main_title'] = "eJournal";
				$data['resend_link'] = base_url() . 'client/signup/resend_author_account_code/' . $ref;
				$data['main_content'] = "client/author_account_otp";
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
					$data['main_content'] = "client/author_account_otp";
					$this->_LoadPage('common/body', $data);
				}else{
					$otp = $this->input->post('otp', TRUE);
					
					// Check user credentials using your authentication logic
					$verifyOTP = $this->Login_model->get_current_otp_oprs($ref_code);

					if (password_verify($otp, $verifyOTP[0]->otp)) {
						$this->session->unset_userdata('otp_ref_code');
						$this->Login_model->activate_account_oprs($verifyOTP[0]->usr_id);
						$this->Login_model->delete_otp_oprs($verifyOTP[0]->usr_id);

						$this->session->set_flashdata('success', '
						<div class="alert alert-success">
							<span class="fa fa-check mr-1"></span>Author account created successfully. You can now login.
						</div>');

						redirect('oprs/login');
					} else {
						//invalid code
						$this->session->set_flashdata('otp', '
															<div class="alert alert-danger d-flex align-items-center w-50">
																<i class="fa fa-circle-x me-1"></i>Invalid code. Try again.
															</div>');
		
						$data['main_title'] = "eJournal";
						$data['main_content'] = "client/author_account_otp";
						$this->_LoadPage('common/body', $data);
					}
				}
			}
		}
	}

	public function resend_author_account_code($refCode){

		$refCode = $this->security->xss_clean($refCode);
		$output = $this->Login_model->get_current_otp_oprs($refCode);
		$email = $output[0]->usr_username;
		$otp = substr(number_format(time() * rand(),0,'',''),0,6);
		$ref_code = random_string('alnum', 16);

		$this->Login_model->save_otp_oprs(
			[
				'otp' => password_hash($otp, PASSWORD_BCRYPT),
				'otp_date' => date('Y-m-d H:i:s'),
				'otp_ref_code' => $ref_code
			],
			['usr_username' => $email]
		);

		save_log_ej($output[0]->usr_id, 'Resend author account otp code');
		
		$this->send_create_account_otp($email, $otp, 'author', 1);
	}

	/**
	 * Password format checker
	 *
	 * @param string $password
	 * @return void
	 */
    public function check_password_strength($password) {
        $regex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/';

        if (!preg_match($regex, $password)) {
            $this->form_validation->set_message('check_password_strength', 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.');
            return FALSE;
        }

        return TRUE;

    }


	/**
	 * Generate user id
	 *
	 * @param integer $start
	 * @param int $end
	 * @return void
	 */
	function generate_user_id($start = 0000, $end) {
		$current_number = $start;
	
		while ($current_number <= $end) {
			// Pad the number with leading zeros to ensure 6 digits
			$formatted_number = str_pad($current_number, 6, '0', STR_PAD_LEFT);
	
			// Do something with the formatted number here
	
			$current_number++;
		}
		
		return 'NRCP-EJ-'. date('Y') .'-'.$formatted_number;
	}

	/**
	 * Get last user id to generate new user id for eJournal/eReview account
	 *
	 * @return void
	 */
	function get_last_user_id(){
		$currentTotalUsers = $this->Library_model->get_library('tblusers', 'default');
		$lastUserID = end($currentTotalUsers);
		$lastUserID = $lastUserID->id;
		$lastUserID = end(explode('-', $lastUserID));
		return $lastUserID;
	}

	/**
	 * Create author account for OPRS/eReview
	 *
	 * @return void
	 */
	function create_author_account(){
		
		$member = $this->input->post('author_type', TRUE);

		if($member == 2){
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
	
			$this->form_validation->set_rules('contact', 'Contact', 'required|trim|numeric|exact_length[11]');
		}
		
		$this->form_validation->set_rules('new_email', 'Email', 'required|trim|valid_email');
		$this->form_validation->set_rules('new_password', 'Password', 'required|trim|min_length[8]|max_length[20]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/]',
		array('regex_match' => 'Password must contain at least 1 letter, 1 number and 1 special character.'));
		$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|trim|matches[new_password]');

		$validations = ['author_type', 'new_email', 'title', 'first_name', 'last_name', 'extension_name', 'sex', 'educational_attainment', 'affiliation', 'country', 'region', 'province', 'city', 'contact', 'new_password', 'confirm_password'];

		if($this->form_validation->run() == FALSE){
			$errors = [];

			foreach($validations as $value){
				//store entered value to display on redirect
				if($value == 'country'){
					if($this->input->post($value)){
						$this->session->set_flashdata($value, $this->input->post($value));
					}else{
						$this->session->set_flashdata($value, 175);
					}
				}else{
					$this->session->set_flashdata($value, $this->input->post($value));
				}

				//store errors to display on redirect
				if (form_error($value)) {
					$errors[$value] = strip_tags(form_error($value));

				}
			}
	
			//return password data and strenght data
			$password = $this->input->post('new_password');
			
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
			$region = $this->input->post('region');

			if($region > 0){
				$provinces = $this->Library_model->get_library('tblprovinces', 'members', array('province_region_id' => $region));
				$this->session->set_flashdata('provinces', $provinces);
			}

			//return city value and options if city has value
			$province = $this->input->post('province');

			if($province){
				$cities = $this->Library_model->get_library('tblcities', 'members', array('city_province_id' => $province));
				$this->session->set_flashdata('cities', $cities);
			}

            // Set flashdata to pass validation errors and form data to the view
            $this->session->set_flashdata('signup_validation_errors', $errors);
            $this->session->set_flashdata('error', 'Please check the required fields and make corrections.');
			redirect('client/ejournal/submission/create_account');
		}else{
			$email = $this->input->post('new_email', TRUE);
			$password = $this->input->post('new_password', TRUE);
			$contact = $this->input->post('contact', TRUE);
			$otp = substr(number_format(time() * rand(),0,'',''),0,6);
			$ref_code = random_string('alnum', 16);
			
			if($member == 1){ // nrcp member

				// get member info
				$result = $this->User_model->get_nrcp_member_info($email);

				// create author account in oprs
				$data = [
					'usr_id' => $result['usr_id'],
					'usr_username' => $email,
					'usr_password' => password_hash($password, PASSWORD_BCRYPT),
					'usr_contact' => $result['pp_contact'],
					'usr_desc' => 'Author',
					'usr_role' => 6,
					'usr_status' => 0,
					'usr_category' => $member,
					'date_created' => date('Y-m-d H:i:s'),
					'usr_sys_acc' => 2,
					'otp' => password_hash($otp, PASSWORD_BCRYPT), 
					'otp_date' => date('Y-m-d H:i:s'),
					'otp_ref_code' => $ref_code
				];
				
				$this->User_model->create_author_account($data);

			}else{ // non-member

				$lastUserID = $this->get_last_user_id();
				$newUserID = $this->generate_user_id('0000', intval($lastUserID) + 1);

				// save in ejournal
				// save user account
				$userAuth = [
					'id' => $newUserID,
					'email' => $email,
					'password' => password_hash($password, PASSWORD_BCRYPT),
					'status' => 0,
					'otp' => password_hash($otp, PASSWORD_BCRYPT), 
					'otp_date' => date('Y-m-d H:i:s'),
					'otp_ref_code' => $ref_code,
					'created_at' => date('Y-m-d H:i:s')
				];
				
				$this->Login_model->create_user_auth($userAuth);

				// save user profile
				$userProfile = [
					'user_id' => $newUserID,
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
					'created_at' => date('Y-m-d H:i:s')
				];

				$this->Login_model->create_user_profile($userProfile);

				// create author account in oprs
				$data = [
					'usr_id' => $newUserID,
					'usr_username' => $email,
					'usr_password' => password_hash($password, PASSWORD_BCRYPT),
					'usr_contact' => $contact,
					'usr_desc' => 'Author',
					'usr_role' => 6,
					'usr_status' => 0,
					'usr_category' => $member,
					'date_created' => date('Y-m-d H:i:s'),
					'usr_sys_acc' => 2,
					'otp' => password_hash($otp, PASSWORD_BCRYPT), 
					'otp_date' => date('Y-m-d H:i:s'),
					'otp_ref_code' => $ref_code
				];
				
				$this->User_model->create_author_account($data);
			}

			//send email otp for create account
			$this->send_create_account_otp($email, $otp, 'author', $member);
		}
	}

    
	/**
	 * Verify create account otp
	 *
	 * @param string $ref
	 * @return void
	 */
	public function new_account_verify_otp($ref){
		$ref = $this->security->xss_clean($ref);
		//check if ref code exist
		$isOtpRefExist = $this->Login_model->validate_otp_ref($ref);

		//link expired
		if($isOtpRefExist[0]->otp_ref_code == null){
			$this->session->set_flashdata('otp', '
			<div class="alert alert-danger d-flex align-items-center w-50">
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
			if ($this->compareDates($otp_date, $current_date)  > 4) {
				$this->session->set_flashdata('otp', '
				<div class="alert alert-danger d-flex align-items-center w-50">
					<i class="oi oi-circle-x me-1"></i>Code expired.
				</div>');
				
				$data['main_title'] = "eJournal";
				$data['resend_link'] = base_url() . 'client/signup/resend_new_client_account_code/' . $ref;
				$data['main_content'] = "client/new_account_otp";
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
					$data['main_content'] = "client/new_account_otp";
					$this->_LoadPage('common/body', $data);
				}else{
					$otp = $this->input->post('otp', TRUE);
					// Check user credentials using your authentication logic
					$verifyOTP = $this->Login_model->validate_otp($ref_code);

					if (password_verify($otp, $verifyOTP[0]->otp)) {
						$this->session->unset_userdata('otp_ref_code');
						$this->Login_model->activate_account($verifyOTP[0]->id);
						$this->Login_model->delete_otp($verifyOTP[0]->id);
						$this->session->set_flashdata('success', '
						<div class="alert alert-success d-flex align-items-center w-50">
							<i class="oi oi-check me-1"></i>Account created successfully. You can now login.
						</div>');
						redirect('client/ejournal/login');
					} else {
						//invalid code
						$this->session->set_flashdata('otp', '
															<div class="alert alert-danger d-flex align-items-center w-50">
																<i class="oi oi-circle-x me-1"></i>Invalid code. Try again.
															</div>');
		
						$data['main_title'] = "eJournal";
						$data['main_content'] = "client/new_account_otp";
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

	public function get_current_otp_oprs($refCode){
		
		$refCode = $this->security->xss_clean($refCode);
		$output = $this->Login_model->get_current_otp_oprs($refCode);
		echo json_encode($output);
	}


}
/* End of file Signup.php */

