<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
class Forgot extends OPRS_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->library('input');
		$this->load->library("My_phpmailer");
		$objMail = $this->my_phpmailer->load();
		$this->load->model('Forgot_model');
		$this->load->model('client/Client_journal_model');
		$this->load->model('oprs/User_model');
	}

	public function index() {

		$data['main_title'] = "OPRS";
		$data['main_content'] = "support/forgot_password";
		$this->_LoadPage('common/body', $data);
	}

	public function check_email() {
		$this->Forgot_model->check_email($this->input->post('get_email', true));
		// echo $output;
	}

	public function check_multiple_account($email) {
		$output = $this->Forgot_model->check_multiple_account($email);
		echo json_encode($output);
	}

	public function send_password() {
		$this->form_validation->set_rules('get_email', 'Email', 'required|trim');
		
		// send temp password if there is selected account in multiple account
		if($this->input->post('user_id')){
			$id = $this->input->post('user_id');
			$user_category = $this->User_model->get_user_info_by_id($id);

			if( $user_category[0]->usr_category == 1) { // nrcp member

				$nrcp_member_info = $this->User_model->get_nrcp_member_info_by_id($id);
				$user_id = $nrcp_member_info[0]->usr_id;
				$name = $nrcp_member_info[0]->title_name . ' ' . $nrcp_member_info[0]->pp_first_name . ' ' .  $nrcp_member_info[0]->pp_last_name;
				$email = $user_category[0]->usr_username;

			}else if( $user_category[0]->usr_category == 2) { // ejournal client and oprs non member author 

				$ejournal_client_info = $this->Client_journal_model->get_client_info_id($id);
				$user_id = $ejournal_client_info[0]->user_id;
				$name = $ejournal_client_info[0]->title . ' ' . $ejournal_client_info[0]->first_name . ' ' . $ejournal_client_info[0]->last_name;
				$email = $user_category[0]->usr_username;

			}else{ // oprs user
				if( $user_category[0]->usr_role == 16){
					// reviewer
					$reviewer_info = $this->User_model->get_reviewer_info_by_id($id);
					$user_id = $reviewer_info[0]->rev_id;
					$name = $reviewer_info[0]->rev_title . ' ' . $reviewer_info[0]->rev_name;
					$email = $user_category[0]->usr_username;

				}else{

					// oprs orejournal admin/supderamin
					$user_id = $user_category[0]->usr_id;
					$name = 'User';
					$email = $user_category[0]->usr_username;
				}
			}

			$supp['date_created'] = date('Y-m-d H:i:s');
			$supp['supp_id'] = $user_id;

			$this->Forgot_model->save_support(array_filter($supp));

			$forgot_pass = 'nrcp' . rand(100, 1000);
			$hash_forgot_pass = password_hash($forgot_pass, PASSWORD_BCRYPT);

			$post['usr_password'] = $hash_forgot_pass;
			$where['usr_id'] = $user_id;

			$this->Forgot_model->update_password(array_filter($post), $where);

			//email
			$nameuser = 'eJournal';
			$sender_email = 'nrcp.ejournal@gmail.com';
			$password = 'fpzskheyxltsbvtg';
			$usergmail = 'nrcp.ejournal@gmail.com';
			$password = 'fpzskheyxltsbvtg';
			$mail = new PHPMailer;
			$mail->isSMTP();
			$mail->Host = "smtp.gmail.com";

			// Specify main and backup server
			$mail->SMTPAuth = true;
			$mail->Port = 465;
			// Enable SMTP authentication
			$mail->Username = $usergmail;
			// SMTP username
			$mail->Password = $password;
			// SMTP password
			$mail->SMTPSecure = 'ssl';
			// Enable encryption, 'ssl' also accepted
			$mail->From = $usergmail;
			$mail->FromName = $nameuser;

			// $mail->AddCC('gerardbalde15@gmail.com');
			$mail->AddAddress($email);
			$mail->Subject = "OPRS Reset Password";

			$date = date("F j, Y") . '<br/><br/>';

			$emailBody = 'Dear <strong>'.$name.'</strong>,
			<br><br>
			You have requested a temporary password for your eReview account.
			<br><br>
			Your new temporary password is: 
			<br><br>
			<strong style="font-size:20px">'.$forgot_pass.'</strong>
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
			redirect('support/forgot');
		}

		// validation and other functions for single account
		if($this->form_validation->run() == FALSE){
			$errors = [];

			if (form_error('get_email')) {
				$errors['email'] = strip_tags(form_error('get_email'));
			}

			// Set flashdata to pass validation errors and form data to the view
			$this->session->set_flashdata('validation_errors', $errors);
			redirect('support/forgot');
		}else{

			$email = $this->input->post('get_email', true);
			$is_exist = $this->Forgot_model->check_email($email);
			
			if($is_exist == 'false'){
				$errors = [];

				$errors['email'] = 'The Email Address does not exist.';

				// Set flashdata to pass validation errors and form data to the view
				$this->session->set_flashdata('validation_errors', $errors);
				redirect('support/forgot');
			}else{
				if(isset($email)){
					$user_account = $this->Forgot_model->check_multiple_account($email);

					if(count($user_account) > 1){
							$this->session->set_flashdata('get_email', $email);
							$this->session->set_flashdata('accounts', $user_account);
							$this->session->set_flashdata('disable_reset', 'disabled');
							redirect('support/forgot');
					}else{
						$forgot_pass = 'nrcp' . rand(100, 1000);
						$hash_forgot_pass = password_hash($forgot_pass, PASSWORD_BCRYPT);
		
						$post['usr_password'] = $hash_forgot_pass;
						$where['usr_username'] = $email;
		
						$this->Forgot_model->update_password(array_filter($post), $where);
		
						
						$user_category = $this->User_model->get_user_info_by_email($email);
						if($user_category[0]->usr_category == 1){ // nrcp member
							$nrcp_member_info = $this->User_model->get_nrcp_member_info($email);
							$user_id = $nrcp_member_info['usr_id'];
							$name = $nrcp_member_info['title_name'] . ' ' . $nrcp_member_info['pp_first_name'] . ' ' .  $nrcp_member_info['pp_last_name'];
						}else if($user_category[0]->usr_category == 2){ // ejournal client and oprs non member author 
							$ejournal_client_info = $this->Client_journal_model->get_user_info($email);
							$user_id = $ejournal_client_info[0]->user_id;
							$name = $ejournal_client_info[0]->title . ' ' . $ejournal_client_info[0]->first_name . ' ' . $ejournal_client_info[0]->last_name;
						}else{ // oprs user
							if( $user_category[0]->usr_role == 5 ){
								// reviewer
								$reviewer_info = $this->User_model->get_reviewer_info_by_email($email);
								$user_id = $reviewer_info[0]->rev_id;
								$name = $reviewer_info[0]->rev_title . ' ' . $reviewer_info[0]->rev_name;
								$email = $user_category[0]->usr_username;
			
							}else{
								// oprs orejournal admin/supderamin
								$user_id = $user_category[0]->usr_id;
								$name = 'User';
								$email = $user_category[0]->usr_username;
							}
						}
		
						$supp['date_created'] = date('Y-m-d H:i:s');
						$supp['supp_id'] = $user_id;
		
						$this->Forgot_model->save_support(array_filter($supp));
		
						//email
						$nameuser = 'eJournal';
						$sender_email = 'nrcp.ejournal@gmail.com';
						$password = 'fpzskheyxltsbvtg';
						$usergmail = 'nrcp.ejournal@gmail.com';
						$password = 'fpzskheyxltsbvtg';
						$mail = new PHPMailer;
						$mail->isSMTP();
						$mail->Host = "smtp.gmail.com";
		
						// Specify main and backup server
						$mail->SMTPAuth = true;
						$mail->Port = 465;
						// Enable SMTP authentication
						$mail->Username = $usergmail;
						// SMTP username
						$mail->Password = $password;
						// SMTP password
						$mail->SMTPSecure = 'ssl';
						// Enable encryption, 'ssl' also accepted
						$mail->From = $usergmail;
						$mail->FromName = $nameuser;
		
						// $mail->AddCC('gerardbalde15@gmail.com');
						$mail->AddAddress($email);
						$mail->Subject = "OPRS Reset Password";
		
						$date = date("F j, Y") . '<br/><br/>';
		
						$emailBody = 'Dear <strong>'.$name.'</strong>,
						<br><br>
						You have requested a temporary password for your eReview account.
						<br><br>
						Your new temporary password is: 
						<br><br>
						<strong style="font-size:20px">'.$forgot_pass.'</strong>
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
						redirect('support/forgot');
					}
				}
			}
		}
	}

}
