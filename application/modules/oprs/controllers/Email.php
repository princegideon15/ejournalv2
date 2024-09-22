<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
class Email extends OPRS_Controller {
	public function __construct() {
		parent::__construct();
		$objMail = $this->my_phpmailer->load();
		$this->load->model('Manuscript_model');
		$this->load->model('Review_model');
		$this->load->model('Email_model');
	}

	/**
	 * this function check review request if accepted or rejected
	 *
	 * @return  void
	 */
	public function check_mail_request() {
		$output = $this->Manuscript_model->get_reviewer_status();
		foreach ($output as $key => $val) {
			$future_date = date('Y-m-d H:i:s', strtotime($val->date_created . " + $val->rev_request_timer days"));
			$future = strtotime($future_date);
			$timeleft = $future - strtotime(date('Y-m-d H:i:s'));
			$daysleft = ((($timeleft / 24) / 60) / 60);
			$notif_sent = $val->rev_notif_status;
			$r_days = (int)$daysleft;

			if ($r_days > 1 && $r_days < 7 && $notif_sent == 0) {
				if ($val->rev_request_timer != 1){
					// send notif if 1 day left and notif not sent yet
					$this->email_notification_content($val->rev_man_id, $val->rev_id, $r_days);
				}

				// $post['rev_notif_status'] = 1;
				// $where['row_id'] = $val->row_id;
				// $this->Manuscript_model->update_reviewer(array_filter($post), $where);
			} elseif($r_days == 1 && $notif_sent == 0){
				if ($val->rev_request_timer != 1){
					// send notif if 1 day left and notif not sent yet
					$this->email_notification_content($val->rev_man_id, $val->rev_id, $r_days);
				}

				$post['rev_notif_status'] = 1;
				$where['row_id'] = $val->row_id;
				$this->Manuscript_model->update_reviewer(array_filter($post), $where);
			} elseif ($r_days == 0 && $notif_sent == 1) {
				// expire
				$post['rev_status'] = 3;
				$where['row_id'] = $val->row_id;
				$this->Manuscript_model->update_reviewer(array_filter($post), $where);
				$this->email_lapsed($val->rev_man_id, $val->rev_id,6); // request lapsed
			}
		}
	}
	
	/**
	 * this function check if reviewer completed the review
	 *
	 * @return  void
	 */
	public function check_mail_reviewer() {
		$output = $this->Manuscript_model->get_review_status();
		foreach ($output as $key => $val) {
			$future_date = date('Y-m-d H:i:s', strtotime($val->rev_date_respond . " + $val->rev_timeframe days"));
			$future = strtotime($future_date);
			$timeleft = $future - strtotime(date('Y-m-d H:i:s'));
			$daysleft = ((($timeleft / 24) / 60) / 60);
			$notif_sent = $val->rev_notif_status;
			$r_days = (int)$daysleft + 1;

			if ($r_days > 1 && $r_days < 7 && $notif_sent == 0) {
				// send notif if 5 days left and notif not sent yet
				$this->email_notification_content_review($val->rev_man_id, $val->rev_id, $r_days);
				// $post['rev_notif_status'] = 1;
				// $where['row_id'] = $val->row_id;
				// $this->Manuscript_model->update_reviewer(array_filter($post), $where);
			} elseif($r_days == 1 && $notif_sent == 0){
				// send notif if 1 day left and notif not sent yet
				$this->email_notification_content_review($val->rev_man_id, $val->rev_id, $r_days);
				$post['rev_notif_status'] = 1;
				$where['row_id'] = $val->row_id;
				$this->Manuscript_model->update_reviewer(array_filter($post), $where);
			} elseif ($r_days == 0 && $notif_sent == 1) {
				// disable reviewer account if 0 day left and notif sent already
				$post['usr_status'] = 2;
				$where['row_id'] = $val->row_id;
				$this->User_model->disable_reviewer(array_filter($post), $where);
				$where_lapsed['scr_man_id'] = $val->rev_man_id;
				$where_lapsed['scr_man_rev_id'] = $val->rev_id;
				$post_scr['scr_status'] = 3;
				$this->Review_model->update_score_lapse(array_filter($post_scr), $where_lapsed);
				$this->email_lapsed($val->rev_man_id, $val->rev_id,7); // review lapsed
			} 
		}
	}

	/**
	 * this function sends email to remind request
	 *
	 * @param   string  $email   reviewer's email
	 * @param   int  $id      manuscript's id
	 * @param   int  $rev_id  reviewer's id
	 *
	 * @return  void
	 */
	public function email_notification_content($id, $rev_id, $r_days) {
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
			$title = $val->rev_title;
			$name = $val->rev_name;
		}

		// get email notification content
		$email_contents = $this->Email_model->get_email_content(8);

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

		$link_to = base_url() . 'oprs/login/reviewer';
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
		// Server
		$file_to_attach = '/var/www/html/ejournal/assets/oprs/uploads/manuscripts/';
		// Localhost
		// $file_to_attach = $_SERVER['DOCUMENT_ROOT'].'/oprs/assets/uploads/manuscripts/';
	
		$mail->AddAddress($rev_email);
		$mail->addAttachment($file_to_attach . $file_name);


		// replace reserved words
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

		$date = date("F j, Y") . '<br/><br/>';
		$accept_decline ='<u><a href="' . $link_to . '/' . $id . '/1/' . $rev_id . '/' . $timeframe . '" target="_blank" style="color:green;cursor:pointer;">ACCEPT</a></u> or <u><a href="' . $link_to . '/' . $id . '/0/' . $rev_id . '"' .
			'style="color:red;cursor:pointer;">DECLINE</a></u>';

		$emailBody = str_replace('[FULL NAME]', $name, $email_contents);
		$emailBody = str_replace('[TITLE]', $title, $emailBody);
		$emailBody = str_replace('[DAYS]', $r_days, $emailBody);
		$emailBody = str_replace('[ACCEPT/DECLINE]', $accept_decline, $emailBody);
		$emailBody = str_replace('[MANUSCRIPT]', $man_title, $emailBody);
		
		// send email
		$mail->Subject = $email_subject .' ('.$r_days.' day/s left)';
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
	 * this function sends email to remind review
	 *
	 * @param   string  $email   reviewer's email
	 * @param   int  $id      manuscript's id
	 * @param   int  $rev_id  reviewer's id
	 *
	 * @return  void
	 */
	public function email_notification_content_review($id, $rev_id, $r_days) {
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
			$title = $val->rev_title;
			$name = $val->rev_name;
		}

		
		// get email notification content
		$email_contents = $this->Email_model->get_email_content(9);

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

		$link_to = base_url() . 'oprs/login/reviewer';
		$sender = 'eJournal Admin';
		$sender_email = 'nrcp.ejournal@gmail.com';
		$password = 'fpzskheyxltsbvtg';
		$file_to_attach = '/var/www/html/ejournal/assets/oprs/uploads/manuscripts/';
		// Localhost
		// $file_to_attach = $_SERVER['DOCUMENT_ROOT'].'/oprs/assets/uploads/manuscripts/';
		
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
		$mail->addAttachment($file_to_attach . $file_name);

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
		$emailBody = str_replace('[MANUSCRIPT]', $man_title, $emailBody);
		$emailBody = str_replace('[DAYS]', $r_days, $emailBody);
		
		//send email
		$mail->Subject = $email_subject . ' ('.$r_days.' day/s left)';
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
	 * this function sends email to lapsed requests and reviews
	 *
	 * @param   string  $email   reviewer's email
	 * @param   int  $id      manuscript's id
	 * @param   int  $rev_id  reviewer's id
	 *
	 * @return  void
	 */
	public function email_lapsed($id, $rev_id, $email_notif_id) {
		// get manuscript info
		$manus_info = $this->Manuscript_model->get_manus_for_email($id);
		foreach ($manus_info as $key => $val) {
			$manuscript = $val->man_title;
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
			$title = $val->rev_title;
			$name = $val->rev_name;
		}

		
		// get email notification content
		$email_contents = $this->Email_model->get_email_content($email_notif_id);

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

		$emailBody = str_replace('[FULL NAME]', $name, $email_contents);
		$emailBody = str_replace('[TITLE]', $title, $emailBody);
		$emailBody = str_replace('[MANUSCRIPT]', $manuscript, $emailBody);

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
}