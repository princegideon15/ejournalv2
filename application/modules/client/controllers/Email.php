<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
class Email extends EJ_Controller {
	public function __construct() {
		parent::__construct();
        
		/**
		 * Helpers, Models, Library, Security headers are all in EJ_controller.php
		 */
	}

	/**
	 * this function check unsubmitted csf arta
	 *
	 * @return  void
	 */
	public function check_csf_arta() {


        $csf_arta = $this->CSF_model->get_ubsubmitted_csf_arta();

        if($csf_arta){
            foreach($csf_arta as $row){
                // check if there is an unaccomplished csf arta
                $download_info = $this->CSF_model->get_latest_download_info($row->arta_user_id);
                // send email
                $this->notify_client($row->arta_user_id, $download_info['dl_art_id'], $download_info['arta_ref_code']);
            }
        }else{
            echo 'No unsubmitted CSF ARTA data found.';
        }


	}

    /**
	 * Send email to client after downloading article for csf arta
	 *
	 * @param [int] $id	client user id
	 * @param [int] $download_id downloaded article's id
	 * @param [string] $ref_cde csf arta ref code
	 * @return void
	 */
	public function notify_client($id, $download_id, $ref_code){

		$client_email = $this->Client_journal_model->get_client_email($id);

		$link = "<a href='https://researchjournal.nrcp.dost.gov.ph/client/ejournal/csf_arta/". $ref_code ."' target='_blank'>CSF-ARTA</a>";
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
		$mail->AddAddress($client_email);

		// if($flag == 1){ // full text pdf downloaded
			

			// get email notification content
			$email_contents = $this->Email_model->get_email_content(5);

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

			$title = $this->Client_journal_model->get_article_title_download_by_client($download_id);
			$emailBody = str_replace('[TITLE]', $title, $email_contents);
			$emailBody = str_replace('[LINK]', $link, $emailBody);
			
	
		// }else{ // articles cited
		// 	$client_info = $this->Client_journal_model->get_client_info_citation($id);

		// 	foreach ($client_info as $key => $row) {
				
		// 		$author = $row->art_author;
		// 		$name = $row->cite_name;
		// 		$client_email = $row->cite_email;
		// 		$affiliation = $row->cite_affiliation;
		// 		$country = $row->cite_country;
		// 		$date = $row->cite_date;
		// 		$member = ($row->cite_member == 1) ? '(NRCP member)' : '';
		// 		$article = $row->art_title;
		// 	}

		// 	// get email notification content
		// 	$email_contents = $this->Email_model->get_email_content(1);

		// 	// add cc/bcc
		// 	foreach($email_contents as $row){
		// 		$email_subject = $row->enc_subject;
		// 		$email_contents = $row->enc_content;

		// 		if( strpos($row->enc_cc, ',') !== false ) {
		// 			$email_cc = explode(',', $row->enc_cc);
		// 		}else{
		// 			$email_cc = array();
		// 			array_push($email_cc, $row->enc_cc);
		// 		}

		// 		if( strpos($row->enc_bcc, ',') !== false ) {
		// 			$email_bcc = explode(',', $row->enc_bcc);
		// 		}else{
		// 			$email_bcc = array();
		// 			array_push($email_bcc, $row->enc_bcc);
		// 		}

		// 		if( strpos($row->enc_user_group, ',') !== false ) {
		// 			$email_user_group = explode(',', $row->enc_user_group);
		// 		}else{
		// 			$email_user_group = array();
		// 			array_push($email_user_group, $row->enc_user_group);
		// 		}
		// 	}

		// 	// add exisiting email as cc
		// 	if(count($email_user_group) > 0){
		// 		$user_group_emails = array();
		// 		foreach($email_user_group as $grp){
		// 			$username = $this->Email_model->get_user_group_emails($grp);
		// 			array_push($user_group_emails, $username);
		// 		}
		// 	}

		// 	$link = "<a href='https://researchjournal.nrcp.dost.gov.ph/' target='_blank'>https://researchjournal.nrcp.dost.gov.ph/</a>";
		// 	$emailBody = str_replace('[FULL NAME]', $author, $email_contents);
		// 	$emailBody = str_replace('[ARTICLE]', $article, $emailBody);
		// 	$emailBody = str_replace('[NAME]', $name, $emailBody);
		// 	$emailBody = str_replace('[MEMBER]', $member, $emailBody);
		// 	$emailBody = str_replace('[EMAIL]', $client_email, $emailBody);
		// 	$emailBody = str_replace('[LINK]', $link, $emailBody);
		// 	$emailBody = str_replace('[AFFILIATION]', $affiliation, $emailBody);
		// 	$emailBody = str_replace('[COUNTRY]', $country, $emailBody);
		
		// }

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