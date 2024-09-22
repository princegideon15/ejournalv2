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
	}

	public function index() {
		$data['main_title'] = "OPRS";
		$data['main_content'] = "support/forgot_password";
		$this->_LoadPage('common/body', $data);
	}

	public function check_email() {
		$output = $this->Forgot_model->check_email($this->input->post('get_email', true));
		echo $output;
	}

	public function check_multiple_account($email) {
		$output = $this->Forgot_model->check_multiple_account($email);
		echo json_encode($output);
	}

	public function send_password() {
		$forgot_pass = 'nrcp' . rand(100, 1000);
		$hash_forgot_pass = password_hash($forgot_pass, PASSWORD_BCRYPT);
		$email = $this->input->post('get_email', TRUE);
		$id = $this->input->post('usr_id', TRUE);

		$post['usr_password'] = $hash_forgot_pass;
		$where['usr_id'] = $id;

		$this->Forgot_model->update_password(array_filter($post), $where);

		$supp['date_created'] = date('Y-m-d H:i:s');
		$supp['supp_id'] = $id;

		$this->Forgot_model->save_support(array_filter($supp));

		//email
		$nameuser = 'eJournal Admin';
		$usergmail = 'nrcp.ejournal@gmail.com';
		$password = '<<NRCP!!ejournal>>';
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

		$mail->AddCC('gerardbalde15@gmail.com');
		$mail->AddAddress($email);
		$mail->Subject = "OPRS Reset Password";
		$mail->Body = $forgot_pass;
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

}
