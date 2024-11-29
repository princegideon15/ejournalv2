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
 * Author: GPDB
 * ----------------------------------------------------------------------------------------------------
 * Date of revision: 10-16-2024
 * ----------------------------------------------------------------------------------------------------
 * Copyright Notice:
 * Copyright (C) 2018 by the Department of Science and Technology - National Research Council of the Philiipines
 */

class Ejournal extends EJ_Controller {

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
	 * Display landing page
	 *
	 * @return void
	 */
	public function index() {

		// store visitor information
		ip_info(); 
		
		$journals = $this->Client_journal_model->get_journals();

		//data to display
		$data['volumes'] = $journals;
		$data['journals'] = $this->Client_journal_model->get_journals();
		$data['popular'] = $this->Client_journal_model->top_five();
		$data['client_count'] = $this->Client_journal_model->all_client();
		$data['hits_count'] = $this->Client_journal_model->all_hits();
		$data['latest'] = $this->Client_journal_model->latest_journal();
		$data['adv_publication'] = $this->Client_journal_model->advancePublication();
		$data['divisions'] = $this->Client_journal_model->getDivisions();
		$data['citations'] = $this->Client_journal_model->totalCitationsCurrentYear();
		$data['downloads'] = $this->Client_journal_model->totalDownloadsCurrentYear();
		$data['main_title'] = "eJournal";
		$data['main_content'] = "client/journal";
		$this->_LoadPage('common/body', $data);
	}

	/**
	 * Guidelines page
	 *
	 * @return void
	 */
	public function guidelines() {
		$data['main_title'] = "eJournal";
		$data['main_content'] = "client/guidelines";
		$data['citations'] = $this->Client_journal_model->totalCitationsCurrentYear();
		$data['downloads'] = $this->Client_journal_model->totalDownloadsCurrentYear();
		$data['journals'] = $this->Client_journal_model->get_journals();
		$this->_LoadPage('common/body', $data);
	}

	/**
	 * Editorial policy page
	 *
	 * @return void
	 */
	public function policy() {
		$data['main_title'] = "eJournal";
		$data['main_content'] = "client/policy";
		$data['citations'] = $this->Client_journal_model->totalCitationsCurrentYear();
		$data['downloads'] = $this->Client_journal_model->totalDownloadsCurrentYear();
		$data['journals'] = $this->Client_journal_model->get_journals();
		$this->_LoadPage('common/body', $data);
	}

	/**
	 * Editorial board page
	 *
	 * @return void
	 */
	public function editorial() {
		$data['main_title'] = "eJournal";
		$data['main_content'] = "client/editorial";
		$data['citations'] = $this->Client_journal_model->totalCitationsCurrentYear();
		$data['downloads'] = $this->Client_journal_model->totalDownloadsCurrentYear();
		$data['editorials_vol_year'] = $this->Client_journal_model->get_unique_editorials();
		$data['journals'] = $this->Client_journal_model->get_journals();
		$this->_LoadPage('common/body', $data);
	}

	/**
	 * Retrieve authors/coauthors by article id
	 *
	 * @param int $id
	 * @return void
	 */
	public function get_coauthors($id) {
		$output = $this->Client_journal_model->get_coauthors($id);
		echo json_encode($output);
	}
	
	/**
	 * Volume with issue page and volume list
	 *
	 * @param int $vol
	 * @param int $iss
	 * @return void
	 */
	public function volume($vol,$iss) {
		$volumes = [];
		$issues = [];

		$journals = $this->Client_journal_model->get_journals();

		foreach($journals as $row){
			$issues = $this->Client_journal_model->get_issues($row->jor_volume);
			$jor_issues = [];
			foreach ($issues as $issue) {
				$jor_issues[] = [$issue->jor_issue, $issue->jor_id, $row->jor_volume];
			}
			$volume = $row->jor_volume . ', ' . $row->jor_year;
			$volumes[$volume] = $jor_issues;
		}

		$data['volumes'] = $volumes;
		$data['articles'] = $this->Client_journal_model->get_articles($vol,$iss);
		$data['journals'] = $this->Client_journal_model->get_journals();
		$data['selected_journal'] = $vol;
		$data['citations'] = $this->Client_journal_model->totalCitationsCurrentYear();
		$data['downloads'] = $this->Client_journal_model->totalDownloadsCurrentYear();
		$data['main_title'] = "eJournal";
		$data['main_content'] = "client/articles";
		$this->_LoadPage('common/body', $data);
	}

	/**
	 * Download full text pdf when logged in
	 *
	 * @param int $id
	 * @param string $file
	 * @return void
	 */
	public function download_file($dl_id, $file) {

		// get info of downloader
		$data = [
			'dl_art_id' => $dl_id,
			'dl_user_id' => $this->session->userdata('user_id'),
			'dl_datetime' => date('Y-m-d H:i:s')
		];
		
		$client_id = $this->Client_journal_model->save_client($data);

        $this->load->helper('download');

		//copy file to another directory
		//local
		$file_path = $_SERVER['DOCUMENT_ROOT'].'/ejournal/assets/uploads/pdf/' . $file;
		
		//server manuscript
		// $from = '/var/www/html/ejournal/assets/oprs/uploads/manuscripts/' . $file;
		// $to = '/var/www/html/ejournal/assets/uploads/pdf/' . $file;


		// save blank arta
		$ref_code = random_string('alnum', 16);
		$id = $this->session->userdata('user_id');
		$email = $this->session->userdata('email');
		$post['arta_user_id'] = $id;
		$post['arta_email'] = $email;
		$post['arta_ref_code'] = $ref_code;
		$post['arta_created_at'] = date('Y-m-d H:i:s');

		$this->CSF_model->save_csf_arta($post);

		// send email
		$this->notify_client($id, $dl_id, $ref_code);


        $data = file_get_contents($file_path);
        // $name = basename($file_path);
        force_download($file, $data);
    }

	/**
	 * Send email to author if client requested/downloaded his/her full text pdf
	 *
	 * @param [int] $id		client row id
	 * @param [int] $download_id	author email
	 * @param [int] $flag		determine if download or citation
	 * @return void
	 */
	public function notify_author($id, $download_id, $flag){

		$author_email = $this->Client_journal_model->get_author_email($download_id);
	
		if($author_email == '') { $author_email = 'nrcp.ejournal@gmail.com'; }

		$link = "<a href='https://researchjournal.nrcp.dost.gov.ph/' target='_blank'>https://researchjournal.nrcp.dost.gov.ph/</a>";
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
		$mail->AddAddress($author_email);

		if($flag == 1){ // full text pdf downloaded
			$client_info = $this->Client_journal_model->get_client_info_download($id);

			foreach ($client_info as $key => $row) {
				
				$author = $row->art_author;
				$name = $row->clt_name;
				$affiliation = $row->clt_affiliation;
				$country = $row->clt_country;
				$purpose = $row->clt_purpose;
				$date = $row->clt_download_date_time;
				$member = ($row->clt_member == 1) ? '(NRCP member)' : '';
				$article = $row->art_title;
			}


			// get email notification content
			$email_contents = $this->Email_model->get_email_content(2);

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

			
			$emailBody = str_replace('[FULL NAME]', $author, $email_contents);
			$emailBody = str_replace('[ARTICLE]', $article, $emailBody);
			$emailBody = str_replace('[NAME]', $name, $emailBody);
			$emailBody = str_replace('[MEMBER]', $member, $emailBody);
			$emailBody = str_replace('[AFFILIATION]', $affiliation, $emailBody);
			$emailBody = str_replace('[COUNTRY]', $country, $emailBody);
			$emailBody = str_replace('[PURPOSE]', $purpose, $emailBody);
			$emailBody = str_replace('[LINK]', $link, $emailBody);
			
	
		}else{ // articles cited
			$client_info = $this->Client_journal_model->get_client_info_citation($id);

			foreach ($client_info as $key => $row) {
				
				$author = $row->art_author;
				$name = $row->cite_name;
				$client_email = $row->cite_email;
				$affiliation = $row->cite_affiliation;
				$country = $row->cite_country;
				$date = $row->cite_date;
				$member = ($row->cite_member == 1) ? '(NRCP member)' : '';
				$article = $row->art_title;
			}

			// get email notification content
			$email_contents = $this->Email_model->get_email_content(1);

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

			$link = "<a href='https://researchjournal.nrcp.dost.gov.ph/' target='_blank'>https://researchjournal.nrcp.dost.gov.ph/</a>";
			$emailBody = str_replace('[FULL NAME]', $author, $email_contents);
			$emailBody = str_replace('[ARTICLE]', $article, $emailBody);
			$emailBody = str_replace('[NAME]', $name, $emailBody);
			$emailBody = str_replace('[MEMBER]', $member, $emailBody);
			$emailBody = str_replace('[EMAIL]', $client_email, $emailBody);
			$emailBody = str_replace('[LINK]', $link, $emailBody);
			$emailBody = str_replace('[AFFILIATION]', $affiliation, $emailBody);
			$emailBody = str_replace('[COUNTRY]', $country, $emailBody);
		
		}

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

	/**
	 * Send email with attached full text pdf to the client
	 *
	 * @param [id] $download_id		article id
	 * @param [string] $recipient		client's email
	 * @return void
	 */
	public function download_pdf_continue($client_id, $download_id, $recipient) {

		//Server
		$file_to_attach = '/var/www/html/ejournal/assets/uploads/pdf/';
		//Localhost
		// $file_to_attach = $_SERVER['DOCUMENT_ROOT'].'/ejournal/assets/uploads/pdf/';
		$file_name = $this->Client_journal_model->get_pdf_to_sent($download_id);
		$file_size = filesize($file_to_attach . $file_name);

		$output = $this->Client_journal_model->get_article($download_id);

		foreach ($output as $row) {
			$title = $row->art_title;
			$author = $row->art_author;
			$aff = $row->art_affiliation;
			$aemail = $row->art_email;
			$att = $row->art_full_text_pdf;
		}

		$client = $this->Client_journal_model->get_client_by_id($client_id);

		foreach ($client as $row) {
			$client_title = $row->clt_title;
			$client_name = $row->clt_name;
			$client_aff = $row->clt_affiliation;
			$client_country = $row->clt_country;
		}

		// get email notification content
		$email_contents = $this->Email_model->get_email_content(3);

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

		if ($file_size >= 26214400) {
			$show_file = '*** Requested file exceeds 25MB. Please contact us. ***';
		} else {

			$show_file = $file_name;
			$mail->addAttachment($file_to_attach . $file_name);
		}

		$mail->AddAddress($recipient);

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

		$link = "<a href='https://researchjournal.nrcp.dost.gov.ph/client/ejournal/customer_service' 
		style='box-shadow: 0px 0px 0px 2px #97c4fe;
		background:linear-gradient(to bottom, #3d94f6 5%, #1e62d0 100%);
		background-color:#3d94f6;
		border-radius:42px;
		border:1px solid #337fed;
		display:inline-block;
		cursor:pointer;
		color:#ffffff;
		font-family:Arial;
		font-size:19px;
		padding:10px 21px;
		text-decoration:none;
		text-shadow:0px 1px 50px #1570cd;'
		>&#8594; DOST-NRCP Satisfaction Feedback Form
		</a>";

		$emailBody = str_replace('[CLIENT_TITLE]', $client_title, $email_contents);
		$emailBody = str_replace('[CLIENT_NAME]', $client_name, $emailBody);
		$emailBody = str_replace('[CLIENT_AFFILIATION]', $client_aff, $emailBody);
		$emailBody = str_replace('[CLIENT_COUNTRY]', $client_country, $emailBody);

		$emailBody = str_replace('[TITLE]', $title, $emailBody);
		$emailBody = str_replace('[AUTHOR]', $author, $emailBody);
		$emailBody = str_replace('[AFFILIATION]', $aff, $emailBody);
		$emailBody = str_replace('[EMAIL]', $aemail, $emailBody);
		$emailBody = str_replace('[FILE]', $show_file, $emailBody);
		$emailBody = str_replace('[LINK]', $link, $emailBody);

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
	 * Count total numbers of client downloads
	 *
	 * @param [int] $id		article id
	 * @return void
	 */
	public function client_count($id) {
		$output = $this->Client_journal_model->client_count($id);
		echo $output;
	}

	/**
	 * Save visitor info after abstract hits
	 *
	 * @param [int] $id		article id
	 * @return void
	 */
	public function abstract_hits($id) {
		// whether ip is from share internet
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip_address = $_SERVER['HTTP_CLIENT_IP'];
		}
		// whether ip is from proxy
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		// whether ip is from remote address
		else {
			$ip_address = $_SERVER['REMOTE_ADDR'];
		}

		$post = array();
		$post['hts_ip_address'] = $ip_address;
		$post['hts_art_id'] = $id;
		$post['date_viewed'] = date('Y-m-d H:i:s');
		$this->Client_journal_model->save_abstract_hits($post);
	}

	/**
	 * Count total numbers of citation by article id
	 *
	 * @param [int] $id		article id
	 * @return void
	 */
	public function cite_count($id) {
		$output = $this->Client_journal_model->count_citation($id);
		echo $output;
	}

	/**
	 * Count total numbers of abstract hits
	 *
	 * @param [int] $id		article id
	 * @return void
	 */
	public function hits_count($id) {
		$output = $this->Client_journal_model->hits_count($id);
		echo $output;
	}

	/**
	 * Retrieve full text pdf file size
	 *
	 * @param [int] $id		article id
	 * @return void
	 */
	public function file_size($id) {
		$file = $this->Client_journal_model->file_size($id);

		$server_dir = '/var/www/html/ejournal/assets/uploads/pdf/';
		$get_file = filesize($server_dir . $file);

		// $get_file = filesize($_SERVER['DOCUMENT_ROOT'].'/ejournal/assets/uploads/pdf/'.$file);

		if ($get_file >= 1048576) {
			$output = number_format($get_file / 1048576, 2) . ' MB';
		} elseif ($get_file >= 1024) {
			$output = number_format($get_file / 1024, 2) . ' KB';
		} else {
			$output = round($get_file, 1) . ' bytes';
		}

		echo $output;
	}

	/**
	 * Search matched keywords or view all articles
	 *
	 * @param [int] $filter		category
	 * @param [string] $keyword		keyword
	 * @return void
	 */
	public function articles() {

		// initialize pagination with page, start_index and per page
		$this->load->library('pagination');
		$perPage = 10;
		$page = 0;

		if($this->input->get('per_page')){
			$page =  $this->input->get('per_page', TRUE);
		}

		$start_index = 0;

		if($page != 0){
			$start_index = $perPage * ($page - 1);
		}

		// search query
		if($this->input->get('search') != null){
			$search = $this->input->get('search', TRUE);
			$clean_search = str_replace('%C3%B1','ñ',str_replace('%2C',',',str_replace('+',' ',$search)));
			$output = $this->Search_model->search_ejournal($perPage, $start_index, $clean_search);
			$totalRows = $this->Search_model->search_ejournal(null, null, $clean_search);
		}else{
			$output = $this->Search_model->search_ejournal($perPage, $start_index, null);
			$totalRows = $this->Search_model->search_ejournal(null, null, null);
		}

		// pagination config
		$config['base_url'] = base_url('client/ejournal/articles');
		$config['total_rows'] = count($totalRows);
		$config['per_page'] = $perPage;
		$config['enable_query_strings'] = true;
		$config['use_page_numbers'] = true;
		$config['page_query_string'] = true;
		$config['page_query_segment'] = 'page';
		$config['reuse_query_string'] = true;
		$config['first_link'] = 'First';
		$config['last_link'] = 'Last';
		$config['first_tag_open'] = '<li class="page-item"><span class="page-link main-link">';
		$config['first_tag_close'] = '</span></li>';
		$config['prev_link'] = 'Previous';
		$config['prev_tag_open'] = '<li class="page-item"><span class="page-link main-link">';
		$config['prev_tag_close'] = '</span></li>';
		$config['next_link'] = 'Next';
		$config['next_tag_open'] = '<li class="page-item"><span class="page-link main-link">';
		$config['next_tag_close'] = '</span></li>';
		$config['last_tag_open'] = '<li class="page-item"><span class="page-link main-link">';
		$config['last_tag_close'] = '</span></li>';
		$config['cur_tag_open'] = '<li class="page-item"><span class="page-link text-white main-bg-color">';
		$config['cur_tag_close'] = '</span></li>';
		$config['num_tag_open'] = '<li class="page-item"><span class="page-link main-link">';
		$config['num_tag_close'] = '</span></li>';


		// pagination data to display
		$this->pagination->initialize($config);
		$data['total_rows'] = count($totalRows);
		$data['pagination'] = $this->pagination->create_links();
		$data['page'] = ($page > 0) ? $page : 1;
		$data['start_index'] = $start_index;
		$data['per_page'] = $perPage;

		// search result to display
		$data['result'] = $output;
		$data['search'] = $search;
		$data['journals'] = $this->Client_journal_model->get_journals();
		$data['citations'] = $this->Client_journal_model->totalCitationsCurrentYear();
		$data['downloads'] = $this->Client_journal_model->totalDownloadsCurrentYear();
		$data['country'] = $this->Library_model->get_library('tblcountries', 'members');
		$data['main_title'] = "eJournal";
		$data['main_content'] = "client/search_results";
		$this->_LoadPage('common/body', $data);

	
		// if (strlen($clean_keyword) >= 3) {
		// 	$stop_words = array("a", "an", "the", "in", "of", "on", "are", "be", "if", "into", "which");
		// 	if (in_array($keyword, $stop_words)) {} else {

		// 		// store keywords on search
		// 		$myFile = ($filter == 1) ? './assets/title.txt' :
		// 		(($filter == 2) ? './assets/acoa.txt' :
		// 			'./assets/keywords.txt');

		// 		if ($filter == 2) {
		// 			$res_count = count($output['authors']) + count($output['coas']);
		// 			$arr_count = count($output, COUNT_RECURSIVE);

		// 			if ($arr_count > 2) {
		// 				$message = $filter . '=>' . $keyword . '=>' . $res_count . PHP_EOL;

		// 				if (file_exists($myFile)) {
		// 					$fh = fopen($myFile, 'a');
		// 					fwrite($fh, $message);
		// 				} else {
		// 					$fh = fopen($myFile, 'w');
		// 					fwrite($fh, $message);
		// 				}
		// 				fclose($fh);
		// 			}
		// 		} else {
		// 			$res_count = count($output);
		// 			$arr_count = count($output, COUNT_RECURSIVE);

		// 			if ($arr_count > 0) {
		// 				$message = $filter . '=>' . $keyword . '=>' . $res_count . PHP_EOL;

		// 				if (file_exists($myFile)) {
		// 					$fh = fopen($myFile, 'a');
		// 					fwrite($fh, $message);
		// 				} else {
		// 					$fh = fopen($myFile, 'w');
		// 					fwrite($fh, $message);
		// 				}
		// 				fclose($fh);
		// 			}
		// 		}
		// 	}
		// }

	}

	/**
	 * Advanced search page
	 *
	 * @return void
	 */
	public function advanced(){
		if(count(array_filter($this->input->get('search[]'))) > 0 || $this->input->get('single_search')){
			// where dropdowns
			$searchFields = ['jor_volume', 'jor_issue'];
			$where_journal = [];

			foreach ($searchFields as $field) {
				$value = $this->input->get($field, TRUE);
				if (!empty($value)) {
					$where2[$field] = $value;
				}
			}

			// where year from to
			$where_year = [];

			if($this->input->get('jor_year_from') && $this->input->get('jor_year_to')){
				$where_year = array('jor_year >=' => $this->input->get('jor_year_from', TRUE), 'jor_year <=' => $this->input->get('jor_year_to', TRUE));
			}else if($this->input->get('jor_year_from')){
				$where_year = array('jor_year' => $this->input->get('jor_year_from', TRUE));
			}else if($this->input->get('jor_year_to')){
				$where_year = array('jor_year' => $this->input->get('jor_year_to', TRUE));
			}

			// initialize pagination with page, start_index and per page
			$this->load->library('pagination');
			$perPage = 10;
			$page = 1;
	
			if($this->input->get('per_page')){
				$page =  $this->input->get('per_page', TRUE);
			}
	
			// advance search 
			if($this->input->get('single_search')){
				$search = array('search[]' => $this->input->get('single_search', TRUE));
				$search_filter = array('search_filter[]' => 1);
			}else{
				$search = array_filter($this->input->get('search[]'));
				$search_filter = $this->input->get('search_filter[]', TRUE);
			}
			$output = $this->Search_model->advance_search_ejournal($search, $search_filter, $where_journal, $where_year);
			$totalRows = $this->Search_model->advance_search_ejournal($search, $search_filter, $where_journal, $where_year);

			// pagination config
			$config['base_url'] = base_url('client/ejournal/advanced');
			$config['total_rows'] = count($totalRows);
			$config['per_page'] = $perPage;
			$config['enable_query_strings'] = true;
			$config['use_page_numbers'] = true;
			$config['page_query_string'] = true;
			$config['page_query_segment'] = 'page';
			$config['reuse_query_string'] = true;
			$config['first_link'] = 'First';
			$config['last_link'] = 'Last';
			$config['first_tag_open'] = '<li class="page-item"><span class="page-link main-link">';
			$config['first_tag_close'] = '</span></li>';
			$config['prev_link'] = 'Previous';
			$config['prev_tag_open'] = '<li class="page-item"><span class="page-link main-link">';
			$config['prev_tag_close'] = '</span></li>';
			$config['next_link'] = 'Next';
			$config['next_tag_open'] = '<li class="page-item"><span class="page-link main-link">';
			$config['next_tag_close'] = '</span></li>';
			$config['last_tag_open'] = '<li class="page-item"><span class="page-link main-link">';
			$config['last_tag_close'] = '</span></li>';
			$config['cur_tag_open'] = '<li class="page-item"><span class="page-link text-white main-bg-color">';
			$config['cur_tag_close'] = '</span></li>';
			$config['num_tag_open'] = '<li class="page-item"><span class="page-link main-link">';
			$config['num_tag_close'] = '</span></li>';
	
	
			$this->pagination->initialize($config);
	
			// pagination data to display
			$data['total_rows'] = count($totalRows);
			$data['pagination'] = $this->pagination->create_links();
			$actualPerPage = $perPage * $page;
			$page = ($perPage * $page) - 10;
			$data['per_page'] = $actualPerPage;
			$data['page'] = $page;
			usort($output, function ($a, $b) {
				// sort merged array by article title
				return strnatcasecmp($a->art_title, $b->art_title);
			});
			$data['result'] = array_slice($output, $page, $perPage);
			$data['search'] = $search;
			$data['filter'] = $this->input->get('search_filter', TRUE);
			$data['volume'] = $this->input->get('jor_volume', TRUE);
			$data['issue'] = $this->input->get('jor_issue', TRUE);
			$data['from'] = $this->input->get('jor_year_from', TRUE);
			$data['to'] = $this->input->get('jor_year_to', TRUE);

		}

		$data['journals'] = $this->Client_journal_model->get_journals();
		$data['citations'] = $this->Client_journal_model->totalCitationsCurrentYear();
		$data['downloads'] = $this->Client_journal_model->totalDownloadsCurrentYear();
		$data['years'] = $this->Journal_model->get_unique_journal_year();
		$data['country'] = $this->Library_model->get_library('tblcountries', 'members');
		$data['main_title'] = "eJournal";
		$data['main_content'] = "client/advanced";

		$this->_LoadPage('common/body', $data);
	}

	/**
	 * Retrieve coauthor data
	 *
	 * @param [int] $id		article id
	 * @param [string] $data		author name
	 * @return void
	 */
	public function get_acoa_details($id, $data) {
		$output = $this->Client_journal_model->get_acoa_details($id, str_replace('%C3%B1','ñ',str_replace('+',' ',$data)));
		echo json_encode($output);
	}

	//TODO:save citation on copy to clipboard button ???
	/**
	 * Save info of client after citing article 
	 *
	 * @param [int] $id		article id
	 * @return void
	 */
	public function save_citation($id)
	{

		$post['cite_user_id'] = $this->session->userdata('user_id');
		$post['cite_art_id'] = $id;
		$post['date_created'] = date('Y-m-d H:i:s');
		

		$last_insert_id = $this->Client_journal_model->save_citation(array_filter($post));

		$ref = random_string('alnum', 8) . date('ymdhis');
		$fdbk_sess = array(
			'client_id' => $last_insert_id,
			'fdbk_ref' => $ref,
		);
		$this->session->set_userdata($fdbk_sess);

		echo $last_insert_id;

		// send email to author after citing his/her article
		$this->notify_author($last_insert_id, $id, 2); // 2 - cited article

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

		$mail->AddAddress($recipient);

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

		$link = "<a href='https://researchjournal.nrcp.dost.gov.ph/client/ejournal/customer_service' 
		style='box-shadow: 0px 0px 0px 2px #97c4fe;
		background:linear-gradient(to bottom, #3d94f6 5%, #1e62d0 100%);
		background-color:#3d94f6;
		border-radius:42px;
		border:1px solid #337fed;
		display:inline-block;
		cursor:pointer;
		color:#ffffff;
		font-family:Arial;
		font-size:19px;
		padding:10px 21px;
		text-decoration:none;
		text-shadow:0px 1px 50px #1570cd;'
		>&#8594; DOST-NRCP Satisfaction Feedback Form
		</a>";
		
		
		$emailBody = str_replace('[CLIENT_TITLE]', $client_title, $email_contents);
		$emailBody = str_replace('[CLIENT_NAME]', $client_name, $emailBody);
		$emailBody = str_replace('[CLIENT_AFFILIATION]', $client_aff, $emailBody);
		$emailBody = str_replace('[CLIENT_COUNTRY]', $client_country, $emailBody);

		$emailBody = str_replace('[CITATION]', $citation, $emailBody);
		$emailBody = str_replace('[LINK]', $link, $emailBody);

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
	 * Display customer service feedback form
	 *
	 * @return void
	 */
	public function customer_service() { 
		
		$id = $this->session->userdata('client_id');
		$ref = $this->session->userdata('fdbk_ref');   

		$check_client_id_exists = $this->CSF_model->check_client_id_exists($id);
		$check_if_fdbk_ref_exist = $this->CSF_model->check_fdbk_ref($ref);
		
		if($check_client_id_exists == 1 && $check_if_fdbk_ref_exist == 0){
		// if($check_if_fdbk_ref_exist == 0){
			$data['main_title'] = "eJournal";
			$data['main_content'] = "client/feedback";
			$data['questions'] = $this->Library_model->get_csf_questions();
			$data['affiliations'] = $this->Library_model->get_csf_q_choices(1);
			$data['services'] = $this->Library_model->get_csf_q_choices(2);
			$data['choices'] = $this->Library_model->get_csf_q_choices(3);
			$this->_LoadPage('common/body', $data);
		}else{
			redirect('/');
		}

		// $data['main_title'] = "eJournal";
		// 	$data['main_content'] = "client/feedback";
		// 	$data['questions'] = $this->Library_model->get_csf_questions();
		// 	$data['affiliations'] = $this->Library_model->get_csf_q_choices(1);
		// 	$data['services'] = $this->Library_model->get_csf_q_choices(2);
		// 	$data['choices'] = $this->Library_model->get_csf_q_choices(3);
		// 	$this->_LoadPage('common/body', $data);
			
	}

	/**
	 * Display customer service feedback form for testing only
	 *
	 * @return void
	 */
	public function feedback_form() {
		
			$data['main_title'] = "eJournal";
			$data['main_content'] = "client/feedback";
			//$data['main_content'] = "client/maintenance";
			$data['questions'] = $this->Library_model->get_csf_questions();
			$data['affiliations'] = $this->Library_model->get_csf_q_choices(1);
			$data['services'] = $this->Library_model->get_csf_q_choices(2);
			$data['choices'] = $this->Library_model->get_csf_q_choices(3);
			$this->_LoadPage('common/body', $data);
	}

	public function submit_feedback() {

		$id = $this->session->userdata('client_id');  
		$ref = $this->session->userdata('fdbk_ref');
		
	
		$svc_fdbk_q_id      = $this->input->post('svc_fdbk_q_id[]', TRUE);       
        $svc_fdbk_q_answer  = $this->input->post('svc_fdbk_q_answer[]', TRUE);  
        $svc_fdbk_q_other_answer  = $this->input->post('svc_fdbk_q_other_answer[]', TRUE);  
		//$this->form_validation->set_rules('svc_fdbk_q_other_answer[]', 'Purpose', 'trim|required|regex_match[/^([a-zA-Z]|\s)+$/]|max_length[250]');
		
		if($svc_fdbk_q_id == ''){
			redirect('/');
		}else{
			
			$ids = array_unique($svc_fdbk_q_id);
			$data = array();

			$c=1;
			foreach($ids as $key => $q_id){

				$data= array( 
					'svc_fdbk_q_id'            =>  $q_id,                
					'svc_fdbk_q_answer'        =>  $svc_fdbk_q_answer[$c],
					'svc_fdbk_q_other_answer'  =>  (isset($svc_fdbk_q_other_answer[$c])) ? $svc_fdbk_q_other_answer[$c] : '',
					'svc_fdbk_q_code        '  =>  'CSF-V2022',
					'svc_fdbk_usr_id'          =>  $id,    
					'date_created'             =>  date('Y-m-d H:i:s'),
					'svc_fdbk_ref'             =>  $ref,
					);
				$c++;
				
			$output = $this->CSF_model->save_csf(array_filter($data));
			}

			$this->success_fdbk();
		}

        save_log_oprs($id, 'submitted Client Satisfaction Feedback (CSF)', '', '');
		
	}
		
	public function success_fdbk(){
			
		$data['main_title'] = "eJournal";
		$data['main_content'] = "client/success";
		$this->_LoadPage('common/body', $data);
	}
	
	/**
	 * Display CSF-ARTA page
	 */
	public function csf_arta($ref_code = null){
		
		if($ref_code){

			$is_ref_code_exist = $this->CSF_model->get_csf_arta_ref_code($ref_code);

			if($is_ref_code_exist > 0){
				$data['regions'] = $this->Library_model->get_library('tblregions', 'members');
				$data['client_types'] = $this->Library_model->get_csf_client_types();
				$data['cc1'] = $this->Library_model->get_csf_cc1();
				$data['cc2'] = $this->Library_model->get_csf_cc2();
				$data['cc3'] = $this->Library_model->get_csf_cc3();
				$data['sqd'] = $this->Library_model->get_csf_sqd();
				$data['ref_code'] = $ref_code;
				$data['main_title'] = "eJournal";
				$data['main_content'] = "client/arta";
				$this->_LoadPage('common/body', $data);
			}else{
				redirect('/');
			}
;
		}else{
		}

	}

	public function submit_arta($ref_code){

		$is_ref_code_exist = $this->CSF_model->get_csf_arta_ref_code($ref_code);

		if($is_ref_code_exist > 0){

			$this->form_validation->set_rules('arta_ctype', 'Client type', 'required|integer|trim');
			$this->form_validation->set_rules('arta_sex', 'Sex', 'required|integer|trim');
			$this->form_validation->set_rules('arta_age', 'Age', 'required|integer|trim');
			$this->form_validation->set_rules('arta_region', 'Region of residence', 'required|integer|trim');
			$this->form_validation->set_rules('arta_service', 'Service availed', 'required|trim');
			$this->form_validation->set_rules('arta_cc1', 'CC1', 'required|integer|trim');
			$this->form_validation->set_rules('arta_cc2', 'CC2', 'required|integer|trim');
			$this->form_validation->set_rules('arta_cc3', 'CC3', 'required|integer|trim');
			$this->form_validation->set_rules('arta_sqd1', 'SQD1', 'required|integer|trim');
			$this->form_validation->set_rules('arta_sqd2', 'SQD2', 'required|integer|trim');
			$this->form_validation->set_rules('arta_sqd3', 'SQD3', 'required|integer|trim');
			$this->form_validation->set_rules('arta_sqd4', 'SQD4', 'required|integer|trim');
			$this->form_validation->set_rules('arta_sqd5', 'SQD5', 'required|integer|trim');
			$this->form_validation->set_rules('arta_sqd6', 'SQD6', 'required|integer|trim');
			$this->form_validation->set_rules('arta_sqd7', 'SQD7', 'required|integer|trim');
			$this->form_validation->set_rules('arta_sqd8', 'SQD8', 'required|integer|trim');
	
			
			$validations = [
							'arta_ctype', 
							'arta_sex', 
							'arta_age', 
							'arta_region', 
							'arta_service', 
							'arta_cc1', 
							'arta_cc2', 
							'arta_cc3', 
							'arta_sqd1', 
							'arta_sqd2', 
							'arta_sqd3', 
							'arta_sqd4', 
							'arta_sqd5', 
							'arta_sqd6', 
							'arta_sqd7',
							'arta_sqd8'
						];
	
			if($this->form_validation->run() == FALSE){
				$errors = [];
				foreach($validations as $value){
					//store entered value to display on redirect
					$this->session->set_flashdata($value, $this->input->post($value));
					
					//store errors to display on redirect
					if (form_error($value)) {
						$errors[$value] = strip_tags(form_error($value));
	
					}
				}
	
				// Set flashdata to pass validation errors and form data to the view
				$this->session->set_flashdata('csf_arta_validation_errors', $errors);
				redirect('client/ejournal/csf_arta/' . $ref_code);
	
			}else{
				$tableName = 'tblcsf_arta';
				$result = $this->db->list_fields($tableName);
				$post = array();
		
				foreach ($result as $i => $field) {
					$post[$field] = $this->input->post($field, TRUE);
				}
		
				$post['arta_agency'] = 'NRCP';
				$post['arta_updated_at'] = date('Y-m-d H:i:s');
				$post['arta_ref_code'] = '-'; // remove ref code to prevent link reacces
				$where['arta_user_id'] = $this->session->userdata('user_id');
				$where['arta_ref_code'] = $ref_code;
	
				$this->CSF_model->update_csf_arta(array_filter($post), $where);
	
				$data['main_title'] = "eJournal";
				$data['main_content'] = "client/success";
				$this->_LoadPage('common/body', $data);
	
			}
		}else{
			redirect('/');
		}

	}

	/**
	 * Get provinces by region id
	 *
	 * @param int $region_id
	 * @return void
	 */
	public function get_provinces($region_id){
		$output = $this->Library_model->get_library('tblprovinces', 'members', array('province_region_id' => $region_id));
		echo json_encode($output);
	}

	/**
	 * Get cities by province id
	 *
	 * @param int $province_id
	 * @return void
	 */
	public function get_city($province_id){
		$output = $this->Library_model->get_library('tblcities', 'members', array('city_province_id' => $province_id));
		echo json_encode($output);
	}

	/**
	 * Display submission page with create author account link
	 *
	 * @param string $create_author_account
	 * @return void
	 */
	public function submission($create_author_account = null){

		$journals = $this->Client_journal_model->get_journals();

		// data to display
		$data['volumes'] = $journals;
		$data['journals'] = $this->Client_journal_model->get_journals();
		$data['popular'] = $this->Client_journal_model->top_five();
		$data['client_count'] = $this->Client_journal_model->all_client();
		$data['hits_count'] = $this->Client_journal_model->all_hits();
		$data['latest'] = $this->Client_journal_model->latest_journal();
		$data['adv_publication'] = $this->Client_journal_model->advancePublication();
		$data['divisions'] = $this->Client_journal_model->getDivisions();
		$data['citations'] = $this->Client_journal_model->totalCitationsCurrentYear();
		$data['downloads'] = $this->Client_journal_model->totalDownloadsCurrentYear();

		if($create_author_account){
			$data['titles'] = $this->Client_journal_model->getTitles();
			$data['educations'] = $this->Client_journal_model->getEducations();
			$data['regions'] = $this->Library_model->get_library('tblregions', 'members');
			$data['country'] = $this->Library_model->get_library('tblcountries', 'members');
			$data['main_content'] = "client/create_author_account";
		}else{
			$data['main_content'] = "client/submission";
		}
		
		$data['main_title'] = "eJournal";
		$this->_LoadPage('common/body', $data);

	}

	public function article($id){
		// data to display
		$data['article'] = $this->Client_journal_model->get_article($id);
		$data['volumes'] = $journals;
		$data['journals'] = $this->Client_journal_model->get_journals();
		$data['popular'] = $this->Client_journal_model->top_five();
		$data['client_count'] = $this->Client_journal_model->all_client();
		$data['hits_count'] = $this->Client_journal_model->all_hits();
		$data['latest'] = $this->Client_journal_model->latest_journal();
		$data['adv_publication'] = $this->Client_journal_model->advancePublication();
		$data['divisions'] = $this->Client_journal_model->getDivisions();
		$data['citations'] = $this->Client_journal_model->totalCitationsCurrentYear();
		$data['downloads'] = $this->Client_journal_model->totalDownloadsCurrentYear();
		$data['main_title'] = "eJournal";
		$data['main_content'] = "client/article_page";
		$this->_LoadPage('common/body', $data);
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
/* End of file Ejournal.php */


/**
 * Unused/replaced functions
 */

 
	// private function email($recipient, $verification_code){
	// 	$sender = 'eJournal Admin';
	// 	$sender_email = 'nrcp.ejournal@gmail.com';
	// 	$password = 'fpzskheyxltsbvtg';
	// 	// setup email config
	// 	$mail = new PHPMailer;
	// 	$mail->isSMTP();
	// 	$mail->Host = "smtp.gmail.com";
	// 	// Specify main and backup server
	// 	$mail->SMTPAuth = true;
	// 	$mail->Port = 465;
	// 	// Enable SMTP authentication
	// 	$mail->Username = $sender_email;
	// 	// SMTP username
	// 	$mail->Password = $password;
	// 	// SMTP password
	// 	$mail->SMTPSecure = 'ssl';
	// 	// Enable encryption, 'ssl' also accepted
	// 	$mail->From = $sender_email;
	// 	$mail->FromName = $sender;

	// 	$mail->AddAddress($recipient);
	// 	$emailBody = "Your verification code is: <br><div style='font-size:2.5em;font-weight:bold;'>".$verification_code."</div>"; ;
	// 	// send email
	// 	$mail->Subject = "Verification Code";
	// 	$mail->Body = $emailBody;
	// 	$mail->IsHTML(true);
	// 	$mail->smtpConnect([
	// 		'ssl' => [
	// 			'verify_peer' => false,
	// 			'verify_peer_name' => false,
	// 			'allow_self_signed' => true,
	// 		],
	// 	]);

	// 	if (!$mail->Send()) {
	// 		echo '<div class="alert alert-danger font-weight-bold">';
	// 		echo 'Message could not be sent.</br>';
	// 		echo 'Mailer Error: ' . $mail->ErrorInfo . '</br>';
	// 		echo '</div>';
	// 		echo '<div class="btn btn-warning btn-block small font-weight-bold" id="send_verification_code" onclick="send_verification_code()" style="font-size:0.9em; width:100%;" title="Click this button to get the verification code emailed to you."><sup class="text-danger font-weight-bold">*</sup>Click the button to get the verification code emailed to you.</div>';
	// 		exit;
	// 	}else{
	// 		echo "<div class='alert alert-success font-weight-bold'> Verification code was sent to your email.</div>";
	// 	}
	// }

	// public function send_verification_code(){
	// 	$email = $this->input->post('clt_email');
	// 	$verification_code =  $this->session->userdata('verification_code');
	// 	//echo $email;
	// 	//echo $verification_code;
	// 	echo $email_sent =  $this->email($email, $verification_code);
	// 	if($email_sent){
	// 		echo "<div class='alert alert-success font-weight-bold'> The verification code  was sent to your email.</div>";
	// 	}
	// }
 
	/**
	 * Retrieve articles by journal id
	 *
	 * @param [int] $id
	 * @return void
	 */
	// public function get_articles($vol,$id) {

	// 	//get volums for side menu
	// 	$volumes = [];
	// 	$issues = [];

	// 	$journals = $this->Client_journal_model->get_journals();

	// 	foreach($journals as $row){
	// 		$issues = $this->Client_journal_model->get_issues($row->jor_volume);
	// 		$jor_issues = [];
	// 		foreach ($issues as $issue) {
	// 			$jor_issues[] = [$issue->jor_issue, $issue->jor_id];
	// 		}

	// 		$volumes[$row->jor_volume] = $jor_issues;
	// 	}


	// 	$data['volumes'] = $volumes;
	// 	$data['main_title'] = "eJournal";
	// 	$data['main_content'] = "client/articles";
	// 	//$data['main_content'] = "client/maintenance";
	// 	$data['articles'] = $this->Client_journal_model->get_articles($id);
	// 	$data['journals'] = $this->Client_journal_model->get_journals();
	// 	$data['selected_journal'] = $vol;
	// 	$this->_LoadPage('common/body', $data);
	// }
	
	/**
	 * Retrieve articles by journal id
	 *
	 * @param [int] $id
	 * @return void
	 */
	// public function get_issues($id) {
	// 	$data['main_title'] = "eJournal";
	// 	$data['main_content'] = "client/issues";
	// 	//$data['main_content'] = "client/maintenance";
	// 	$data['issues'] = $this->Client_journal_model->get_issues($id);
	// 	$data['journals'] = $this->Client_journal_model->get_journals();
	// 	$data['selected_journal'] = $id;
	// 	$this->_LoadPage('common/body', $data);
	// }
	

	/**
	 * Retrieve articles by journal id
	 *
	 * @param [int] $id
	 * @return void
	 */
	// public function get_index($id = null) {
	// 	$data['main_title'] = "eJournal";
	// 	$data['main_content'] = "client/indexes";
	// 	//$data['main_content'] = "client/maintenance";
	// 	$data['articles'] = $this->Client_journal_model->get_index($id);
	// 	$data['journals'] = $this->Client_journal_model->get_journals();
	// 	$data['article_index'] = $id;
	// 	$this->_LoadPage('common/body', $data);
	// }

	/**
	 * About page
	 *
	 * @return void
	 */
	// public function about() {
	// 	$data['main_title'] = "eJournal";
	// 	$data['main_content'] = "client/about";
	// 	//$data['main_content'] = "client/maintenance";
	// 	$data['journals'] = $this->Client_journal_model->get_journals();
	// 	$this->_LoadPage('common/body', $data);
	// }

    //added by mark zosa
	// private function token(){
    //     // $time =  time();
    //     //  return md5($time."nrcp");
    //     // $time =  time();
    //     $n = 6;
    //     //return md5($time."nrcp_token");
    //     // Taking a generator string that consists of 
    //     // all the numeric digits 
    //     $generator = "1357902468";

    //     // Iterating for n-times and pick a single character 
    //     // from generator and append it to $result 

    //     // Login for generating a random character from generator 
    //     //     ---generate a random number 
    //     //     ---take modulus of same with length of generator (say i) 
    //     //     ---append the character at place (i) from generator to result 

    //     $result = "";

    //     for ($i = 1; $i <= $n; $i++) {
    //         $result .= substr($generator, (rand() % (strlen($generator))), 1);
    //     }

    //     // Returning the result 
    //     return $result;
    // }

	/**
	 * Retrieve articles in a journal by journal id
	 *
	 * @param [type] $id
	 * @return void
	 */
	// public function get_journal($id) {
	// 	$output = $this->Client_journal_model->get_journal($id);
	// 	echo json_encode($output);
	// }

	/**
	 * Save client info after requesting full text pdf (unused/replaced)
	 *
	 * @return void
	 */
	// public function download_pdf() {
	// 	// whether ip is from share internet
	// 	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
	// 		$ip_address = $_SERVER['HTTP_CLIENT_IP'];
	// 	}
	// 	// whether ip is from proxy
	// 	elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	// 		$ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
	// 	}
	// 	// whether ip is from remote address
	// 	else {
	// 		$ip_address = $_SERVER['REMOTE_ADDR'];
	// 	}

	// 	$tableName = 'tblclients';
	// 	$result = $this->db->list_fields($tableName);
	// 	$post = array();

	// 	foreach ($result as $i => $field) {
	// 		if ($field != 'clt_id') {
	// 			$post[$field] = $this->input->post($field, TRUE);
	// 		}
	// 	}

	// 	$client_member = $this->input->post('clt_member');
	// 	$download_id = $post['clt_journal_downloaded_id'];
		
	// 	//$recipient = $post['clt_email'];

	// 	$this->form_validation->set_rules('clt_title', 'Title', 'trim|required|regex_match[/^([a-zA-Z]+\s*)+\.?$/]');
    //     $this->form_validation->set_rules('clt_name', 'Full Name', 'trim|required|regex_match[/^([a-zA-Z]|\s)+$/]|max_length[100]');
	// 	$this->form_validation->set_rules('clt_email', 'Email is required  and should be valid', 'trim|required|valid_email');
	// 	$this->form_validation->set_rules('clt_age', 'Age', 'trim|required|numeric');
	// 	$this->form_validation->set_rules('clt_sex', 'Sex', 'trim|required|numeric');
	// 	$this->form_validation->set_rules('clt_affiliation', 'Affiliation', 'trim|required|regex_match[/^([a-zA-Z]|\s)+$/]|max_length[500]');
	// 	$this->form_validation->set_rules('clt_country', 'Country', 'trim|required|regex_match[/^([a-zA-Z]|\s)+$/]|max_length[100]');
	// 	$this->form_validation->set_rules('clt_purpose', 'Purpose', 'trim|required|regex_match[/^([a-zA-Z]|\s)+$/]|max_length[250]');
	// 	$this->form_validation->set_rules('clt_vcode', 'Verification Code', 'trim|required|regex_match[/^([0-9])+$/]|max_length[6]');

	// 	if ($this->form_validation->run() == FALSE)
	// 	{
	// 		//$this->load->view('myform');
	// 		//$error = 400;
	// 		if(validation_errors()):
	// 			echo "<div class='alert alert-danger px-1 py-1 font-weight-bold'>".validation_errors()."</div>";
	// 		endif;
	// 	}
	// 	else
	// 	{
	// 		//$verification_code = $this->token();
	// 		$vcode  						= $this->security->xss_clean($this->input->post('clt_vcode'));
	// 		//echo $this->session->userdata('verification_code');
	// 		//echo "<br>";
	// 		//echo $vcode;
	// 		if($this->session->userdata('verification_code') == $vcode){
	// 			$post['clt_title']       		= $this->security->xss_clean(ucfirst($this->input->post('clt_title')));
	// 			$post['clt_name']       		= $this->security->xss_clean($this->input->post('clt_name'));
	// 			$post['clt_email']      		= $this->security->xss_clean($this->input->post('clt_email'));
	// 			$post['clt_age']    			= $this->security->xss_clean($this->input->post('clt_age'));
	// 			$post['clt_sex']        		= $this->security->xss_clean($this->input->post('clt_sex'));
	// 			$post['clt_affiliation']        = $this->security->xss_clean($this->input->post('clt_affiliation'));
	// 			$post['clt_country']            = $this->security->xss_clean($this->input->post('clt_country'));
	// 			$post['clt_purpose']            = $this->security->xss_clean($this->input->post('clt_purpose'));
	// 			$post['clt_download_date_time'] = date('Y-m-d H:i:s');
	// 			$post['clt_ip_address'] 		= $ip_address;
	// 			//var_dump($post);
	// 			$client_id = $this->Client_journal_model->save_client(array_filter($post));
	// 			$ref = random_string('alnum', 8) . date('ymdhis');
	// 			$fdbk_sess = array(
	// 				'client_id' => $client_id,
	// 				'fdbk_ref' => $ref,
	// 			);

	// 			$this->session->set_userdata($fdbk_sess);
	// 			// echo $client_id;
	// 			$recipient = $post['clt_email'];
	// 			$this->download_pdf_continue($client_id, $download_id, $recipient);
	// 			//send email to author
	// 			$this->notify_author($client_id, $download_id, 1); // 1 - downloaded article	
	// 			$success = trim("success");
	// 			//echo $success;
	// 			echo 200;
	// 			//echo "<div class='alert alert-success font-weight-bold' role='alert'>";
	// 			//echo "<span class='oi oi-check'></span> Full Text PDF sent! Please check your email.</div><h5 class='text-center'></h5>";
	// 			//echo "<button class='btn btn-light w-100 font-weight-bold' id='btn_feedback'>Close</button>";
				
	// 		}else{
	// 			echo 401;
	// 			//echo "<div class='alert alert-danger font-weight-bold'> Wrong Verification code. Please enter the right verification code.</div>";
	// 			//echo "<button class='btn btn-light w-100 font-weight-bold'  data-dismiss='modal'>Close</button>";
	// 		}
	// 	}
	// }

