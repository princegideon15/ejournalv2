<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
class Manuscripts extends OPRS_Controller {
	public function __construct() {
		parent::__construct();
		if (!$this->session->userdata('_oprs_logged_in')) {
			redirect('oprs/login');
		}
		$this->load->model('Manuscript_model');
		$this->load->model('Login_model');
		$this->load->model('Coauthor_model');
		$this->load->model('Review_model');
		$this->load->model('Library_model');
		$this->load->model('Dashboard_model');
		$this->load->model('Log_model');
		$this->load->model('User_model');
		$this->load->model('Feedback_model');
		$this->load->model('Email_model');
		$this->load->model('Arta_model');
		$objMail = $this->my_phpmailer->load();
		$this->check_expired_request();
	}

	/**
	 * Display manuscript
	 *
	 * @return void
	 */
	public function index() {

		if ($this->session->userdata('_oprs_logged_in')) {
			if($this->session->userdata('sys_acc') == 2 || $this->session->userdata('sys_acc') == 3 )
			{
				// $data['manus'] = $this->Manuscript_model->get_manus($this->session->userdata('_oprs_srce'), $this->session->userdata('_oprs_username'));
				// $data['manus'] = $this->Manuscript_model->get_manus(_UserRoleFromSession());
				$data['u_man_jor'] = $this->Manuscript_model->get_oprs_journal();
				$data['publish'] = $this->Dashboard_model->get_publishables();
				$data['u_journal'] = $this->Manuscript_model->get_unique_journal();
				$data['u_year'] = $this->Manuscript_model->get_unique_journal_year();
				$data['criteria'] = $this->Review_model->get_criterias();
				$data['tech_rev_critera'] = $this->Library_model->get_criteria(null,1);
				$data['logs'] = $this->Log_model->count_logs();
				$data['titles'] = $this->Library_model->get_titles();
				$data['publ_types'] = $this->Library_model->get_publication_types(null);

				$data['man_all'] = $this->Manuscript_model->get_manus(_UserRoleFromSession());
				$data['man_new'] = $this->Manuscript_model->get_manuscripts(1);
				$data['man_onreview'] = $this->Manuscript_model->get_manuscripts(2);
				$data['man_rej'] = $this->Manuscript_model->get_manuscripts(14);
				$data['rev_cons'] = $this->Manuscript_model->get_manuscripts(6);
				$data['prf_cop'] = $this->Manuscript_model->get_manuscripts(7);
				$data['fin_rev'] = $this->Manuscript_model->get_manuscripts(8);
				$data['prf_auth'] = $this->Manuscript_model->get_manuscripts(9);
				$data['rev_auth'] = $this->Manuscript_model->get_manuscripts(10);
				$data['lay_art'] = $this->Manuscript_model->get_manuscripts(11);
				$data['fin_app'] = $this->Manuscript_model->get_manuscripts(12);
				$data['publ'] = $this->Manuscript_model->get_manuscripts(16);

				$data['man_all_count'] = count($data['man_all']);
				$data['man_new_count'] = count($data['man_new']);
				$data['man_onreview_count'] = count($data['man_onreview']);
				$data['man_rej_count'] = count($data['man_rej']);
				$data['rev_cons_count'] = count($data['rev_cons']);
				$data['prf_cop_count'] = count($data['prf_cop']);
				$data['fin_rev_count'] = count($data['fin_rev']);
				$data['prf_auth_count'] = count($data['prf_auth']);
				$data['rev_auth_count'] = count($data['rev_auth']);
				$data['lay_art_count'] = count($data['lay_art']);
				$data['fin_app_count'] = count($data['fin_app']);
				$data['publ_count'] = count($data['publ']);

				$data['usr_count'] = $this->User_model->count_user();
				$data['arta_count'] = count($this->Arta_model->get_arta());
				$data['feed_count'] = $this->Feedback_model->count_feedbacks();
				// $data['existing'] = $this->Manuscript_model->get_manuscripts(99);
				$data['author'] = $this->Manuscript_model->get_corresponding_author(_UserIdFromSession());
				$data['associate'] = $this->User_model->get_associate_editors();
				$data['cluster'] = $this->User_model->get_cluster_editors(_UserRoleFromSession());
				$data['main_title'] = "OPRS";
				$data['main_content'] = "oprs/manuscripts";
				$this->_LoadPage('common/body', $data);
			}else{
				redirect('admin/dashboard');
			}
		}
	}


	/**
	 * Retrieve journals with publishable manuscripts
	 *
	 * @param   string  $jor  journal, volume, issue and year
	 *
	 * @return  array	list of manuscripts
	 */
	public function get_publishable_manus($jor)
	{	
		$output = $this->Manuscript_model->get_publishable_manus($jor);
		echo json_encode($output);
	}

	/**
	 * Retrieve all SKMS members
	 *
	 * @return void
	 */
	public function members() {
		$mem = $this->Manuscript_model->get_members();
		echo json_encode($mem);
	}

	// public function non_members() {
	// 	$mem = $this->Manuscript_model->get_non_members();
	// 	echo json_encode($mem);
	// }
	// public function non_member_info($id) {
	// 	$output = $this->Review_model->get_non_member_info($id);
	// 	echo json_encode($output);
	// }

	/**
	 * Retrieve manuscript authors and coauthors
	 *
	 * @param   string  $action  action
	 * @param   int  $id      manuscript id
	 *
	 * @return  array           list of authors
	 */
	public function authors($action = null, $id = null) {
		if ($action == 'get') {
			$output = $this->Coauthor_model->get_manus_acoa($id);
			echo json_encode($output);
		} else {
			$output = $this->Manuscript_model->get_author_coa();
			echo json_encode($output);
		}
	}

	/**
	 * Upload manuscript by Author
	 *
	 * @return  void
	 */
	public function upload() {
		$tableName = 'tblmanuscripts';
		$oprs = $this->load->database('dboprs', true);
		$result = $oprs->list_fields($tableName);
		$post = array();
		$man_author_type = $this->input->post('man_author_type', TRUE);
		$publication_type = "0" . $this->input->post('man_type', TRUE); 
		$currentYear = date("Y"); // Get the current year
		$totalEntries = $this->Manuscript_model->count_manus_by_type($this->input->post('man_type'), $currentYear); // Get the total number of entries for the current year
		$newNumber = $totalEntries + 1; // Increment the count for the new entry

		foreach ($result as $i => $field) {

			$post[$field] = $this->input->post($field, true);
			if($man_author_type == 1){
				$post['man_author'] = $this->input->post('corr_author', true);
				$post['man_affiliation'] = $this->input->post('corr_affiliation', true);
				$post['man_email'] = $this->input->post('corr_email', true);
				$post['man_author_user_id'] = $this->input->post('corr_usr_id', true);
				$user_id = $this->input->post('corr_usr_id', true);
			}else{
				$post['man_author_user_id'] = $this->input->post('man_usr_id', true);
				$user_id = _UserIdFromSession();
			}

			// full manuscript
			$file_string_man = str_replace(" ", "_", $_FILES['man_file']['name']);
			$file_no_ext_man = preg_replace("/\.[^.	]+$/", "", $file_string_man);
			$clean_file_man = preg_replace('/[^A-Za-z0-9\-]/', '_', $file_no_ext_man);

			$filename_man = $_FILES["man_file"]["name"];
			$file_ext_man = pathinfo($filename_man, PATHINFO_EXTENSION);

			$post['man_file'] = date('YmdHis') . '_' . $clean_file_man . '.' . $file_ext_man;
			$upload_file_man = $post['man_file'];

			// abstract
			$file_string_abs = str_replace(" ", "_", $_FILES['man_abs']['name']);
			$file_no_ext_abs = preg_replace("/\.[^.]+$/", "", $file_string_abs);
			$clean_file_abs = preg_replace('/[^A-Za-z0-9\-]/', '_', $file_no_ext_abs);

			$filename_abs = $_FILES["man_abs"]["name"];
			$file_ext_abs = pathinfo($filename_abs, PATHINFO_EXTENSION);

			$post['man_abs'] = date('YmdHis') . '_' . $clean_file_abs . '.' . $file_ext_abs;
			$upload_file_abs = $post['man_abs'];

			// word
			$file_string_word = str_replace(" ", "_", $_FILES['man_word']['name']);
			$file_no_ext_word = preg_replace("/\.[^.]+$/", "", $file_string_word);
			$clean_file_word = preg_replace('/[^A-Za-z0-9\-]/', '_', $file_no_ext_word);

			$filename_word = $_FILES["man_word"]["name"];
			$file_ext_word = pathinfo($filename_word, PATHINFO_EXTENSION);

			$post['man_word'] = date('YmdHis') . '_' . $clean_file_word . '.' . $file_ext_word;
			$upload_file_word = $post['man_word'];

			if($_FILES['man_latex']['name'] != ''){
				// latex
				$file_string_latex = str_replace(" ", "_", $_FILES['man_latex']['name']);
				$file_no_ext_latex = preg_replace("/\.[^.]+$/", "", $file_string_latex);
				$clean_file_latex = preg_replace('/[^A-Za-z0-9\-]/', '_', $file_no_ext_latex);
	
				$filename_latex = $_FILES["man_latex"]["name"];
				$file_ext_latex = pathinfo($filename_latex, PATHINFO_EXTENSION);
	
				$post['man_latex'] = date('YmdHis') . '_' . $clean_file_latex . '.' . $file_ext_latex;
				$upload_file_latex = $post['man_latex'];
			}
		}

		$source = '_op'; // uploaded in oprs

		$author_category = $this->Manuscript_model->get_author_category($user_id);

		if($author_category == 1){
			$user_info = $this->User_model->get_nrcp_member_info_by_id($user_id);
			$post['man_author_title'] = $user_info[0]->title_name;
			$post['man_author_sex'] = $user_info[0]->pp_sex;
		}else{
			$user_info = $this->User_model->get_corresponding_author($user_id);
			$post['man_author_title'] = $user_info[0]->title;
			$post['man_author_sex'] = $user_info[0]->usr_sex;
		}


		$post['man_trk_no'] = sprintf("%s-%s-%05d", $currentYear, $publication_type, $newNumber); 	// Format the tracking number
		$post['date_created'] = date('Y-m-d H:i:s');
		$post['man_user_id'] = _UserIdFromSession();
		$post['man_status'] = 1;
		// $post['man_author_type'] = $man_author_type;
		$post['man_source'] = $source; 

		// local
		$dir_man = $_SERVER['DOCUMENT_ROOT'] . '/ejournal/assets/oprs/uploads/initial_manuscripts_pdf/';
		// server
		// $dir_man = '/var/www/html/ejournal/assets/oprs/uploads/initial_manuscripts_pdf/';
	
		// upload full manuscript
		$config_man['upload_path'] = $dir_man;
		$config_man['allowed_types'] = 'pdf';
		$config_man['file_name'] = $upload_file_man;

		$this->load->library('upload', $config_man);
		$this->upload->initialize($config_man);

		if (!$this->upload->do_upload('man_file')) {
			$error = $this->upload->display_errors();
		} else {
			$data = $this->upload->data();
		}

		// local
		$dir_abs = $_SERVER['DOCUMENT_ROOT'] . '/ejournal/assets/oprs/uploads/initial_abstracts_pdf/';
		// server
		//  $dir_abs = '/var/www/html/ejournal/assets/oprs/uploads/initial_abstracts_pdf/';
	
		// upload full manuscript
		$config_abs['upload_path'] = $dir_abs;
		$config_abs['allowed_types'] = 'pdf';
		$config_abs['file_name'] = $upload_file_abs;

		$this->load->library('upload', $config_abs);
		$this->upload->initialize($config_abs);

		if (!$this->upload->do_upload('man_abs')) {
			$error = $this->upload->display_errors();
		} else {
			$data = $this->upload->data();
		}

		// local
		$dir_word = $_SERVER['DOCUMENT_ROOT'] . '/ejournal/assets/oprs/uploads/initial_manuscripts_word/';
		// server
		//  $dir_word = '/var/www/html/ejournal/assets/oprs/uploads/initial_manuscripts_word/';
	
		// upload full manuscript word
		$config_word['upload_path'] = $dir_word;
		$config_word['allowed_types'] = 'doc|docx';
		$config_word['file_name'] = $upload_file_word;

		$this->load->library('upload', $config_word);
		$this->upload->initialize($config_word);

		if (!$this->upload->do_upload('man_word')) {
			$error = $this->upload->display_errors(); 
		} else {
			$data = $this->upload->data();
		}

		if(isset($upload_file_latex)){
			// local
			$dir_latex = $_SERVER['DOCUMENT_ROOT'] . '/ejournal/assets/oprs/uploads/initial_latex/';
			// server
			//  $dir_latex = '/var/www/html/ejournal/assets/oprs/uploads/initial_latex/';
		
			// upload full manuscript latex
			$config_latex['upload_path'] = $dir_latex;
			$config_latex['allowed_types'] = 'tex';
			$config_latex['file_name'] = $upload_file_latex;
	
			$this->load->library('upload', $config_latex);
			$this->upload->initialize($config_latex);
	
			if (!$this->upload->do_upload('man_latex')) {
				$error = $this->upload->display_errors(); 
			} else {
				$data = $this->upload->data();
			}
		}

		$output = $this->Manuscript_model->save_manuscript(array_filter($post));

		// save tracking
		$track['trk_man_id'] = $output;
		$track['trk_description'] = 'Uploaded new publication.';
		$track['trk_processor'] = _UserIdFromSession();
		$track['trk_process_datetime'] = date('Y-m-d H:i:s');
		$track['trk_source'] = $source;
		$this->Manuscript_model->tracking(array_filter($track));


		if($man_author_type == 1){
			$coauthors = $this->input->post('coa_name', true);
			$affiliations = $this->input->post('coa_affiliation', true);
			$emails = $this->input->post('coa_email', true);
			$coa = array();
	
			if (!empty($coauthors)) {
				for ($i = 0; $i < count($coauthors); $i++) {
					$coa['coa_name'] = $coauthors[$i];
					$coa['coa_affiliation'] = $affiliations[$i];
					$coa['coa_email'] = $emails[$i];
					$coa['coa_man_id'] = $output;
					$coa['date_created'] = date('Y-m-d H:i:s');
					$this->Manuscript_model->save_coauthors($coa);
				}
			}
		}else{
			$coa['coa_name'] = $this->input->post('corr_author', true);
			$coa['coa_affiliation'] = $this->input->post('corr_affiliation', true);
			$coa['coa_email'] = $this->input->post('corr_email', true);
			$coa['coa_man_id'] = $output;
			$coa['date_created'] = date('Y-m-d H:i:s');
			$this->Manuscript_model->save_coauthors($coa);

			$coauthors = $this->input->post('coa_name', true);
			$affiliations = $this->input->post('coa_affiliation', true);
			$emails = $this->input->post('coa_email', true);
			$coa = array();
	
			if (!empty($coauthors)) {
				for ($i = 0; $i < count($coauthors); $i++) {
					$coa['coa_name'] = $coauthors[$i];
					$coa['coa_affiliation'] = $affiliations[$i];
					$coa['coa_email'] = $emails[$i];
					$coa['coa_man_id'] = $output;
					$coa['date_created'] = date('Y-m-d H:i:s');
					$this->Manuscript_model->save_coauthors($coa);
				}
			}
		}

		// send email to author if submit successfull/acknowledgement email
		$this->send_email_author($output);
	}

	/**
	 * Process, add reviewer and send email to the selected reviewers
	 *
	 * @param   int  $id  manuscript id
	 *
	 * @return  void
	 */
	public function process($id) {
		$oprs = $this->load->database('dboprs', TRUE);

		// get manuscript info
		$manus_info = $this->Manuscript_model->get_manus_for_email($id);
		foreach ($manus_info as $key => $row) {
			$title = $row->man_title;
			$author = $row->man_author;
			$affiliation = $row->man_affiliation;
			$abs = $row->man_abs;
			$author_mail = $row->man_email;
			$status = $row->man_status;
			$date_avail = date_format(new DateTime($row->man_date_available), 'F j, Y, g:i a');
			$post['man_status'] = 5;
		}

		// if($status == 1){
		// 	// send email on-review
		// 	$this->notify_author_on_review($id);
		// }

		// update manuscript status
		if ($this->input->post('jor_volume')) {
			$post['man_volume'] = $this->input->post('jor_volume', true);
			$post['man_issue'] = $this->input->post('jor_issue', true);
			$post['man_year'] = $this->input->post('jor_year', true);
		} else if ($this->input->post('art_year')) {
			$split_jor = explode("-", $this->input->post('art_issue', true));
			$post['man_volume'] = $split_jor[0];
			$post['man_issue'] = $split_jor[1];
			$post['man_year'] = $this->input->post('art_year', true);
		}
		$post['last_updated'] = date('Y-m-d H:i:s');
		$where['row_id'] = $id;
		$this->Manuscript_model->process_manuscript(array_filter($post), $where);

		// save tracking
		$tableName = 'tbltracking';
		$result = $oprs->list_fields($tableName);
		$track = array();
		$mails = array();
		foreach ($result as $i => $field) {
			if ($field != 'row_id') {
				$track[$field] = $this->input->post($field, true);
				$remarks = $this->input->post('trk_remarks', true);
				$timeframe = $this->input->post('trk_timeframe', true);
				$rev_timer = $this->input->post('trk_request_timer', true);
				$req_day_week = $this->input->post('trk_req_day_week');
				$rev_day_week = $this->input->post('trk_rev_day_week');
			}
		}

		$track['trk_man_id'] = $id;
		$track['trk_description'] = 'Endorsed to Peer Reviewers';
		$track['trk_remarks'] = $remarks;
		$track['trk_processor'] = _UserIdFromSession();
		$track['trk_process_datetime'] = date('Y-m-d H:i:s');
		$this->Manuscript_model->tracking(array_filter($track));

		// save reviewers
		$trk_title = $this->input->post('trk_title', true);
		$trk_rev = $this->input->post('trk_rev', true);
		$rev_mail = $this->input->post('trk_rev_email', true);
		$rev_num = $this->input->post('trk_rev_num', true);
		$rev_spec = $this->input->post('trk_rev_spec', true);
		$rev_id = $this->input->post('trk_rev_id', true);
		$rev_email = $this->input->post('tiny_mail');

		$revs = array();
		$rev_id_validated = array();

		$request = ($req_day_week == 2) ? $rev_timer * 7 : $rev_timer;
		$review = ($rev_day_week == 2) ? $timeframe * 7 : $timeframe;

		$revs['rev_request_timer'] = $request;
		$revs['rev_timeframe'] = $review;

		if ($trk_rev != '') {
			for ($i = 0; $i < count($trk_rev); $i++) {
				$revs['rev_title'] = $trk_title[$i];
				$revs['rev_name'] = $trk_rev[$i];
				$revs['rev_email'] = $rev_mail[$i];
				$revs['rev_contact'] = $rev_num[$i];
				$revs['rev_specialization'] = $rev_spec[$i];
				$revs['rev_man_id'] = $id;
				// $revs['rev_id'] = ($rev_id[$i] == '') ? 'R' . rand(1, 9999) : $rev_id[$i];
				if ($rev_id[$i] == '') {
					$check = $this->Review_model->check_reviewer($rev_mail[$i]);
					if ($check == '0') {
						$revs['rev_id'] = 'R' . md5(uniqid('', TRUE));
					} else {
						$revs['rev_id'] = $check;
					}
				} else {
					$revs['rev_id'] = $rev_id[$i];
				}
				array_push($rev_id_validated, $revs['rev_id']);
				$revs['rev_status'] = 2;
				$revs['date_created'] = date('Y-m-d H:i:s');
				$revs['rev_hide_auth'] = $this->input->post('rev_hide_auth');
				$revs['rev_hide_rev'] = $this->input->post('rev_hide_rev');
				$revs['rev_man_id'] = $id;
				$this->Manuscript_model->save_reviewers(array_filter($revs), $id);
			}
		}

		// get email notification content
		$email_contents = $this->Email_model->get_email_content(5);

		// add cc/bcc
		foreach($email_contents as $row){
			$email_subject = $row->enc_subject;

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
		$sender = 'eReview';
		$sender_email = 'nrcp.ejournal@gmail.com';
		$password = 'fpzskheyxltsbvtg';
		// server
		$man_abs = '/var/www/html/ejournal/assets/oprs/uploads/initial_abstracts_pdf/' . $abs;
		// $nda = '/var/www/html/ejournal/assets/oprs/uploads/SAMPLE_NDA_NRCP.doc';
		// Localhost
		// $file_to_attach = $_SERVER['DOCUMENT_ROOT'].'/ejournal/assets/oprs/uploads/SAMPLE_NDA_NRCP.doc';
		// $file_to_attach = $_SERVER['DOCUMENT_ROOT'].'/ejournal/assets/oprs/uploads/abstracts/';
		// $nda =  $_SERVER['DOCUMENT_ROOT'].'/ejournal/assets/oprs/uploads/SAMPLE_NDA_NRCP.doc/';
		// $nda = '/var/www/html/ejournal/assets/oprs/uploads/SAMPLE_NDA_NRCP.doc';
		

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

	

		$rev_c = 0;
		foreach ($rev_mail as $m) {
			$mail->AddAddress($m);
			$mail->addAttachment($man_abs);
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
		
			// time to expect accept request
			$date = date('Y-m-d');
			$deadline = date('Y-m-d', strtotime($date. ' + ' . $request . ' days'));
			$format_deadline = new DateTime($deadline);
			$format_deadline = date_format($format_deadline, 'd F Y');
			$mail_time = 'May we get your acceptance on or before <strong>' . $format_deadline . '</strong>?';
			$emailBody = str_replace('[TIME]', $mail_time, $rev_email[$rev_c]);
			
			// review deadline
			$mail_due = 'Your review will be due after ' . $review . ' days of your acceptance. If you are unable to review at the moment, we would greatly appreciate if you can recommend alternate reviewers.';
			$emailBody = str_replace('[DUE]', $mail_due, $emailBody);
			
			// accept or decline
			$acc_dec = "Please click <u><a href='" . $link_to . "/" . $id . "/1/" . $rev_id_validated[$rev_c] . "/" . $review . "' target='_blank' style='color:green;cursor:pointer;'>
						ACCEPT</a></u> or <u><a href='" . $link_to . "/" . $id . "/0/" . $rev_id_validated[$rev_c] . "' style='color:red;cursor:pointer;'>
						DECLINE</a></u>. ";
			$days = "This request will expire in " . $request . " days from the date of this email.
					If you click ACCEPT button, you will be redirected/taken to the Online Peer Review System (eReview)
					wherein you can find your username and password for the login. ";
			$timer = "Subsequently, you will be given " . $review . " days to complete the reveiw task.";
			$accept_decline = $acc_dec . $days . $timer;
			$emailBody = str_replace('[ACCEPT/DECLINE]', $accept_decline, $emailBody);

			if ($this->input->post('rev_hide_auth') == 1) {
				$emailBody = str_replace($author, '<em>Undisclosed</em>', $emailBody);
				$emailBody = str_replace($affiliation, '<em>Undisclosed</em>', $emailBody);
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
			$mail->ClearAllRecipients();
			$rev_c++;
		}
	}

	/**
	 * Process, add editor and send email to the selected editor
	 *
	 * @param   int  $id  manuscript id
	 *
	 * @return  void
	 */
	public function editor($id) { 
		$oprs = $this->load->database('dboprs', TRUE);
		// get manuscript info
		$manus_info = $this->Manuscript_model->get_manus_for_email($id);
		foreach ($manus_info as $key => $row) {
			$title = $row->man_title;
			$author = $row->man_author;
			$affiliation = $row->man_affiliation;
			$abs = $row->man_abs;
			$author_mail = $row->man_email;
			$status = $row->man_status;
			$date_avail = date_format(new DateTime($row->man_date_available), 'F j, Y, g:i a');
		}

		// send email on-review
		$this->notify_author_on_editor_review($id, 12);
		

		// update manuscript status
		$post['man_status'] = 4;
		$post['last_updated'] = date('Y-m-d H:i:s');
		$where['row_id'] = $id;
		$this->Manuscript_model->process_manuscript(array_filter($post), $where);

		// save tracking
		$track = array();
		$track['trk_man_id'] = $id;
		$track['trk_processor'] = _UserIdFromSession();
		$track['trk_description'] = 'EDITOR';
		$track['trk_process_datetime'] = date('Y-m-d H:i:s');
		$track['trk_remarks'] = $this->input->post('editor_remarks', true);
		$this->Manuscript_model->tracking(array_filter($track));

		// save editor
		$titles = $this->input->post('editor_title', true);
		$editors = $this->input->post('editor_rev', true);
		$edit_mail = $this->input->post('editor_rev_email', true);
		$edit_num = $this->input->post('editor_rev_num', true);
		$edit_spec = $this->input->post('editor_rev_spec', true);
		$edit_id = $this->input->post('editor_rev_id', true);
		$editor_email = $this->input->post('editor_tiny_mail');
		
		$timeframe = $this->input->post('editor_timeframe', true);
		// $rev_timer = $this->input->post('editor_request_timer', true);
		// $req_day_week = $this->input->post('editor_req_day_week');
		$rev_day_week = $this->input->post('editor_rev_day_week');

		$edits = array();
		$edit_id_validated = array();

		// $request = ($req_day_week == 2) ? $rev_timer * 7 : $rev_timer;
		$review = ($rev_day_week == 2) ? $timeframe * 7 : $timeframe;

		// $edits['rev_request_timer'] = $request;
		$edits['edit_timeframe'] = $review;

		if ($editors != '') {
			for ($i = 0; $i < count($editors); $i++) {
				$edits['edit_title'] = $titles[$i];
				$edits['edit_name'] = $editors[$i];
				$edits['edit_email'] = $edit_mail[$i];
				$edits['edit_contact'] = $edit_num[$i];
				$edits['edit_specialization'] = $edit_spec[$i];
				$edits['edit_man_id'] = $id;
				if ($edit_id[$i] == '') {
					$check = $this->Review_model->check_reviewer($edit_mail[$i]);
					if ($check == '0') {
						$edits['edit_id'] = 'E' . md5(uniqid('', TRUE));
					} else {
						$edits['edit_id'] = $check;
					}
				} else {
					$edits['edit_id'] = $edit_id[$i];
				}
				array_push($edit_id_validated, $edits['edit_id']);
				$edits['edit_status'] = 2;
				$edits['date_created'] = date('Y-m-d H:i:s');
				// $edits['edit_hide_auth'] = $this->input->post('edit_hide_auth');
				// $edits['edit_hide_edit'] = $this->input->post('edit_hide_edit');
				$edits['edit_man_id'] = $id;
				$this->Manuscript_model->save_editors(array_filter($edits), $id);
			}
		}

		// get email notification content
		$email_contents = $this->Email_model->get_email_content(10);

		// add cc/bcc
		foreach($email_contents as $row){
			$email_subject = $row->enc_subject;

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

		$link_to = base_url() . 'oprs/login/editor';
		$sender = 'eReview';
		$sender_email = 'nrcp.ejournal@gmail.com';
		$password = 'fpzskheyxltsbvtg';
		// server
		// $man_abs = '/var/www/html/ejournal/assets/oprs/uploads/abstracts/' . $abs;
		// $nda = '/var/www/html/ejournal/assets/oprs/uploads/SAMPLE_NDA_NRCP.doc';
		// Localhost
		// $file_to_attach = $_SERVER['DOCUMENT_ROOT'].'/ejournal/assets/oprs/uploads/SAMPLE_NDA_NRCP.doc';
		// $file_to_attach = $_SERVER['DOCUMENT_ROOT'].'/ejournal/assets/oprs/uploads/abstracts/';
		// $nda =  $_SERVER['DOCUMENT_ROOT'].'/ejournal/assets/oprs/uploads/SAMPLE_NDA_NRCP.doc/';
		// $nda = '/var/www/html/ejournal/assets/oprs/uploads/SAMPLE_NDA_NRCP.doc';
		

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

	

		$rev_c = 0;
		foreach ($edit_mail as $m) {
			$mail->AddAddress($m);
			$mail->addAttachment($man_abs);
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
			// review deadline
	
            $emailBody = $editor_email[$rev_c];

			if($review > 0){
				$due = 'Your review will be due after ' . $review . ' days of your acceptance. If you are unable to review at the moment, we would greatly appreciate if you can recommend alternate reviewers.'; 
				$emailBody = str_replace('[DUE]', $due, $emailBody);
			}else{
				$emailBody = str_replace('[DUE]', '', $emailBody);
			}


			// accept or decline
			$link = "Please click <u><a href='" . $link_to . "/" . $id . "/1/" . $edit_id_validated[$rev_c] ."' target='_blank' style='cursor:pointer;'>
						HERE</a></u> to login.";
		
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
			$mail->ClearAllRecipients();
			$rev_c++;
		}
	}

	/**
	 * Save reviewer's review to a manuscript
	 *
	 * @param   int  $id  manuscript id
	 *
	 * @return  void
	 */
	public function review($id) {

		$oprs = $this->load->database('dboprs', TRUE);
		// save scores
		$tableName = 'tblscores';
		$result = $oprs->list_fields($tableName);
		$post = array();
		foreach ($result as $i => $field) {
			if ($field != 'row_id') {
				$post[$field] = $this->input->post($field, true);

				$total_score = $this->input->post('scr_total', true);

				// 4-passed 7-failed
				$post['scr_status'] = ($total_score <= 75) ? 7 : 4;
			}
		}
		
		if($_FILES['scr_file']['name'] != ''){
			// upload edited manuscript word
			$file_string = str_replace(" ", "_", $_FILES['scr_file']['name']);
			$file_no_ext = preg_replace("/\.[^.]+$/", "", $file_string);
			$clean_file = preg_replace('/[^A-Za-z0-9\-]/', '_', $file_no_ext);
			$filename = $_FILES["scr_file"]["name"];
			$file_ext = pathinfo($filename, PATHINFO_EXTENSION);
			$post['scr_file'] = date('YmdHis') . '_' . $clean_file . '.' . $file_ext;
			$upload_file = $post['scr_file'];

			if ($post['scr_file'] != '') {
				$config['upload_path'] = './assets/oprs/uploads/reviewersdoc/';
				$config['allowed_types'] = '*';
				$config['file_name'] = $upload_file;
				$this->load->library('upload', $config);
				$this->upload->initialize($config);
				if (!$this->upload->do_upload('scr_file')) {
					$error = $this->upload->display_errors();
				} else {
					$data = $this->upload->data();
				}
			}
		}

		if($_FILES['scr_nda']['name'] != ''){
			// upload nda
			$file_string_nda = str_replace(" ", "_", $_FILES['scr_nda']['name']);
			$file_no_ext_nda = preg_replace("/\.[^.]+$/", "", $file_string_nda);
			$clean_file_nda = preg_replace('/[^A-Za-z0-9\-]/', '_', $file_no_ext_nda);
			$filename_nda = $_FILES["scr_nda"]["name"];
			$file_ext_nda = pathinfo($filename_nda, PATHINFO_EXTENSION);
			$post['scr_nda'] = date('YmdHis') . '_' . $clean_file_nda . '.' . $file_ext_nda;
			$upload_file_nda = $post['scr_nda'];

			if ($post['scr_nda'] != '') {
				$config['upload_path'] = './assets/oprs/uploads/nda/';
				$config['allowed_types'] = '*';
				$config['file_name'] = $upload_file_nda;
				$this->load->library('upload', $config);
				$this->upload->initialize($config);
				if (!$this->upload->do_upload('scr_nda')) {
					$error = $this->upload->display_errors();
				} else {
					$data = $this->upload->data();
				}
			}
		}

		$post['date_reviewed'] = date('Y-m-d H:i:s');
		$where_rev['scr_man_rev_id'] = _UserIdFromSession();
		$where_rev['scr_man_id'] = $id;
		// $this->Review_model->save_review(array_filter($post));
		$this->Review_model->update_review(array_filter($post), $where_rev, $id);
		
		// save tracking
		$tableName = 'tbltracking';
		$result = $oprs->list_fields($tableName);
		$track = array();
		$track['trk_man_id'] = $id;
		$track['trk_processor'] = _UserIdFromSession();
		$track['trk_remarks'] = $this->input->post('scr_remarks', true);
		$track['trk_description'] = ($total_score <= 75) ? 'Failed' : 'Passed';
		$track['trk_process_datetime'] = date('Y-m-d H:i:s');
		if (strpos(_UserIdFromSession(), 'NM') !== false) {
			$track['trk_source'] = '_op';
		} else if (strpos(_UserIdFromSession(), 'R') !== false) {
			$track['trk_source'] = '_op';
		} else {
			$track['trk_source'] = '_sk_r';
		}
		$this->Manuscript_model->tracking(array_filter($track));
		// $man['man_status'] = 3;
		// $man['last_updated'] = date('Y-m-d H:i:s');
		// $where['row_id'] = $id;
		// $this->Manuscript_model->process_manuscript(array_filter($man), $where);

		// send email to technical desk editor 

		// email config
		// $link_to = "https://researchjournal.nrcp.dost.gov.ph/oprs/login";
		$link_to = base_url() . 'oprs/login';
		$sender = 'eReview';
		$sender_email = 'nrcp.ejournal@gmail.com';
		$password = 'fpzskheyxltsbvtg';

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
		$email_contents = $this->Email_model->get_email_content(11);
			
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

		$output = $this->Review_model->get_manus_author_info($id);

		foreach ($output as $key => $value) {
			$manuscript = $value->man_title;
			$title = $value->man_author_title;
			$author = $value->man_author;
			$email = $value->man_email;
		}

		$link = "<a href='" . $link_to ."' target='_blank' style='cursor:pointer;'>
		https://researchjournal.nrcp.dost.gov.ph</a>";
		$emailBody = str_replace('[LINK]', $link, $email_contents);
		$emailBody = str_replace('[MANUSCRIPT]', $manuscript, $emailBody);

		$next_processor_info = $this->User_model->get_processor_by_role(5);

		foreach ($next_processor_info as $row) {
			$next_processor_email = $row->usr_username;
		}

		$mail->AddAddress($next_processor_email);

		// add exisiting email as cc
		if(count($email_user_group) > 0){
			$user_group_emails = array();
			foreach($email_user_group as $grp){
				$username = $this->Email_model->get_user_group_emails($grp);
				array_push($user_group_emails, $username);
			}
		}


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

		// send certification
		$this->send_certification(_UserIdFromSession(), $id);
	
	}

	public function test_send_cert($x, $y){
		echo $x . ' ' . $y;
	}
	
	public function send_certification($rev_id, $man_id){

		$cert['scr_cert'] = 1;
		$where['scr_man_rev_id'] = $rev_id;
		$where['scr_man_id'] = $man_id;
		$this->Review_model->update_review(array_filter($cert), $where, 'eCert');

		// get manus info
		$info = $this->Manuscript_model->get_manus_info($man_id);
		foreach ($info as $key => $value) {
			$manuscript = $value->man_title;
		}

		$rev_info = $this->Manuscript_model->get_rev_info($rev_id);
		foreach ($rev_info as $key => $val) {
			$email = $val->rev_email;
			$title = $val->rev_title;
			$name = $val->rev_name;
		}


		// get email notification content
		$email_contents = $this->Email_model->get_email_content(21);

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

		
		// $link = "<a href='https://researchjournal.nrcp.dost.gov.ph/' target='_blank'>https://researchjournal.nrcp.dost.gov.ph/</a>";
		// $link = 'http://localhost/ejournal/oprs/certification/generate_cert/'.$rev_id.'/'.$man_id.'';
		$link = "<a href='https://researchjournal.nrcp.dost.gov.ph/oprs/certification/generate_cert/".$rev_id."/".$man_id."'
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
		>Download Certification 
		</a>";
		$sender = 'eReview';
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
		$emailBody = str_replace('[MANUSCRIPT]', $manuscript, $emailBody);
		$emailBody = str_replace('[CERTIFICATION]', $link, $emailBody);
		
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


	public function publishx()
	{
		$files = $_FILES['man_file']['name'];
	
		foreach($files as $id => $value)
		{
			$_FILES['file']['name'] = $_FILES['man_file']['name'][$id];
			$_FILES['file']['type'] = $_FILES['man_file']['type'][$id];
			$_FILES['file']['tmp_name'] = $_FILES['man_file']['tmp_name'][$id];
			$_FILES['file']['error'] = $_FILES['man_file']['error'][$id];
			$_FILES['file']['size'] = $_FILES['man_file']['size'][$id];

			$config_pdf['upload_path'] = './assets/oprs/uploads/manuscripts/'; 
			$config_pdf['allowed_types'] = 'pdf';
			$config_pdf['file_name'] = $_FILES['file']['name'];
			$this->load->library('upload', $config_pdf);
			$this->upload->initialize($config_pdf);
			
			if (!$this->upload->do_upload('file')) {
				$error = $this->upload->display_errors();
			} else {
				$data = $this->upload->data();
			}
	
		}
	
		$pages = $this->input->post('man_page_position');

		foreach($pages as $id => $page)
		{
		
			// get manus info
			$info = $this->Manuscript_model->get_manus_info($id);
			foreach ($info as $key => $value) {
				$volume = $value->man_volume;
				$issue = $value->man_issue;
				$year = $value->man_year;
				$title = $value->man_title;
				$author = $value->man_author;
				$email = $value->man_email;
				$aff = $value->man_affiliation;
				$file = $value->man_file;
				$abs = $value->man_abs;
				$keys = $value->man_keywords;
			}

			$from_abs = '/var/www/html/ejournal/assets/oprs/uploads/abstracts/' . $abs;
			$to_abs = '/var/www/html/ejournal/assets/uploads/abstract/' . $abs;
			if (!copy($from_abs, $to_abs)) {
				echo "failed to copy $from_abs...\n";
			} else {
				echo "copied $from_abs into $to_abs\n";
			}

			$from_pdf = '/var/www/html/ejournal/assets/oprs/uploads/manuscripts/' . $file;
			$to_pdf = '/var/www/html/ejournal/assets/uploads/pdf/' . $file;
			if (!copy($from_pdf, $to_pdf)) {
				echo "failed to copy $from_abs...\n";
			} else {
				echo "copied $from_pdf into $to_pdf\n";
			}

			// get coauthors
			$coas = $this->Coauthor_model->get_manus_acoa($id);

			// check if journal exists
			$jor_id = $this->Manuscript_model->check_journal($volume, $issue);
			if ($jor_id > 0) {
				// if journal exist save as article
				$post_art = array();
				$post_art['art_title'] = $title;
				$post_art['art_author'] = $author;
				$post_art['art_affiliation'] = $aff;
				$post_art['art_email'] = $email;
				$post_art['art_abstract_file'] = $abs;
				$post_art['art_full_text_pdf'] = $file;
				$post_art['art_year'] = $year;
				$post_art['art_page'] = $page;
				$post_art['art_keywords'] = $keys;
				$post_art['art_jor_id'] = $jor_id;
				$post_art['date_created'] = date('Y-m-d H:i:s');		
				$art_id = $this->Manuscript_model->add_article(array_filter($post_art));
			} else {
				// if journal not exist create journal
				$post_jor = array();
				$post_jor['jor_volume'] = $volume;
				$post_jor['jor_issue'] = $issue;
				$post_jor['jor_year'] = $year;
				$post_jor['jor_issn'] = '0117-3294';
				$post_jor['jor_cover'] = 'unavailable.jpg';
				$post_jor['date_created'] = date('Y-m-d H:i:s');
				$jor_new_id = $this->Manuscript_model->create_journal(array_filter($post_jor));
				$post_art = array();
				$post_art['art_title'] = $title;
				$post_art['art_author'] = $author;
				$post_art['art_affiliation'] = $aff;
				$post_art['art_email'] = $email;
				$post_art['art_abstract_file'] = $abs;
				$post_art['art_full_text_pdf'] = $file;
				$post_art['art_keywords'] = $keys;
				$post_art['art_jor_id'] = $jor_new_id;
				$post_art['date_created'] = date('Y-m-d H:i:s');
				$post_art['art_year'] = $year;
				$post_art['art_page'] = $page;		
					// echo json_encode($post_art);exit;
				$art_id = $this->Manuscript_model->add_article(array_filter($post_art));
			}

			// add coauthors if any
			if (!empty($coas)) {
				$coa = array();
				foreach ($coas as $key => $val) {
					$coa['coa_name'] = $val->coa_name;
					$coa['coa_affiliation'] = $val->coa_affiliation;
					$coa['coa_email'] = $val->coa_email;
					$coa['coa_art_id'] = $art_id;
					$coa['date_created'] = date('Y-m-d H:i:s');
					$this->Manuscript_model->save_acoa(array_filter($coa));
				}
			}

			// update manuscript
			$post['man_status'] = 6;
			$post['man_page_position'] = $page;
			$post['man_file'] = $config_pdf['file_name'];
			$post['last_updated'] = date('Y-m-d H:i:s');
			$where['row_id'] = $id;
			$this->Manuscript_model->process_manuscript(array_filter($post), $where, 3);
			
			// save tracking
			$track['trk_man_id'] = $id;
			$track['trk_processor'] = _UserIdFromSession();
			$track['trk_process_datetime'] = date('Y-m-d H:i:s');
			$track['trk_description'] = 'PUBLISHED';
			$issue = (
				($issue == 5) ? 'Special Issue No. 1' :
				(($issue == 6) ? 'Special Issue No. 2' :
					(($issue == 7) ? 'Special Issue No. 3' :
						(($issue == 8) ? 'Special Issue No. 4' : 'Issue ' . $issue)))
			);
			$track['trk_remarks'] = 'Published to eJournal Volume ' . $volume . ', ' . $issue;
			$this->Manuscript_model->tracking(array_filter($track));
		}
	}

		// for publish (for finalization)
		// $articles = $this->input->post('ids');
		
		// foreach($articles as $a)
		// {
		// 	//get manus info
		// 	$output = $this->Manuscript_model->get_manus_info($a);
		// 	foreach ($output as $key => $value) {
		// 		$volume = $value->man_volume;
		// 		$issue = $value->man_issue;
		// 		$year = $value->man_year;
		// 		$title = $value->man_title;
		// 		$author = $value->man_author;
		// 		$email = $value->man_email;
		// 		$aff = $value->man_affiliation;
		// 		$file = $value->man_file;
		// 		$abs = $value->man_abs;
		// 		$keys = $value->man_keywords;
		// 	}
	
		// 	//get coauthors
		// 	$coas = $this->Coauthor_model->get_manus_acoa($a);
		// 	//check if journal exists
		// 	$jor_id = $this->Manuscript_model->check_journal($volume, $issue);
		// 	if ($jor_id > 0) {
		// 		//if journal exist save as article
		// 		$post_art = array();
		// 		$post_art['art_title'] = $title;
		// 		$post_art['art_author'] = $author;
		// 		$post_art['art_affiliation'] = $aff;
		// 		$post_art['art_email'] = $email;
		// 		$post_art['art_abstract_file'] = $abs;
		// 		$post_art['art_full_text_pdf'] = $file;
		// 		$post_art['art_year'] = $year;
		// 		$post_art['art_page'] = $pages;
		// 		$post_art['art_keywords'] = $keys;
		// 		$post_art['art_jor_id'] = $jor_id;
		// 		$post_art['date_created'] = date('Y-m-d H:i:s');
		// 		$art_id = $this->Manuscript_model->add_article(array_filter($post_art));
		// 	} else {
		// 		//if journal not exist create journal
		// 		$post_jor = array();
		// 		$post_jor['jor_volume'] = $volume;
		// 		$post_jor['jor_issue'] = $issue;
		// 		$post_jor['jor_year'] = $year;
		// 		$post_jor['jor_issn'] = '0117-3294';
		// 		$post_jor['jor_cover'] = 'unavailable.jpg';
		// 		$post_jor['date_created'] = date('Y-m-d H:i:s');
		// 		$jor_new_id = $this->Manuscript_model->create_journal(array_filter($post_jor));
		// 		$post_art = array();
		// 		$post_art['art_title'] = $title;
		// 		$post_art['art_author'] = $author;
		// 		$post_art['art_affiliation'] = $aff;
		// 		$post_art['art_email'] = $email;
		// 		$post_art['art_abstract_file'] = $abs;
		// 		$post_art['art_full_text_pdf'] = $file;
		// 		$post_art['art_keywords'] = $keys;
		// 		$post_art['art_jor_id'] = $jor_new_id;
		// 		$post_art['date_created'] = date('Y-m-d H:i:s');
		// 		$post_art['art_year'] = $year;
		// 		$post_art['art_page'] = $pages;
		// 		$art_id = $this->Manuscript_model->add_article(array_filter($post_art));
		// 	}
		// 	//add coauthors if any
		// 	if (!empty($coas)) {
		// 		$coa = array();
		// 		foreach ($coas as $key => $val) {
		// 			$coa['coa_name'] = $val->coa_name;
		// 			$coa['coa_affiliation'] = $val->coa_affiliation;
		// 			$coa['coa_email'] = $val->coa_email;
		// 			$coa['coa_art_id'] = $art_id;
		// 			$coa['date_created'] = date('Y-m-d H:i:s');
		// 			$this->Manuscript_model->save_acoa(array_filter($coa));
		// 		}
		// 	}
		// 	//copy file to another directory
		// 	//local
		// 	// $from = $_SERVER['DOCUMENT_ROOT'].'/oprs/assets/uploads/manuscripts/'.$file;
		// 	// $to = $_SERVER['DOCUMENT_ROOT'].'/ejournal/assets/uploads/pdf/'.$file;
		// 	//server manuscript
		// 	$from = '/var/www/html/ejournal/assets/oprs/uploads/manuscripts/' . $file;
		// 	$to = '/var/www/html/ejournal/assets/uploads/pdf/' . $file;
		// 	if (!copy($from, $to)) {
		// 		echo "failed to copy $from...\n";
		// 	} else {
		// 		echo "copied $from into $to\n";
		// 	}
		// 	//server abstract
		// 	$from2 = '/var/www/html/ejournal/assets/oprs/uploads/abstracts/' . $abs;
		// 	$to2 = '/var/www/html/ejournal/assets/uploads/abstract/' . $abs;
		// 	if (!copy($from2, $to2)) {
		// 		echo "failed to copy $from...\n";
		// 	} else {
		// 		echo "copied $from into $to\n";
		// 	}
		// 	//update manuscript
		// 	$post['man_status'] = 6;
		// 	$post['man_page_position'] = $pages;
		// 	$post['last_updated'] = date('Y-m-d H:i:s');
		// 	$where['row_id'] = $a;
		// 	$this->Manuscript_model->process_manuscript(array_filter($post), $where, 4);
		// 	//save tracking
		// 	$track['trk_man_id'] = $a;
		// 	$track['trk_processor'] = _UserIdFromSession();
		// 	$track['trk_process_datetime'] = date('Y-m-d H:i:s');
		// 	$track['trk_description'] = 'PUBLISHED';
		// 	$issue = (
		// 		($issue == 5) ? 'Special Issue No. 1' :
		// 		(($issue == 6) ? 'Special Issue No. 2' :
		// 			(($issue == 7) ? 'Special Issue No. 3' :
		// 				(($issue == 8) ? 'Special Issue No. 4' : 'Issue ' . $issue)))
		// 	);
		// 	$track['trk_remarks'] = 'Published to eJournal Volume ' . $volume . ', ' . $issue;
		// 	$this->Manuscript_model->tracking(array_filter($track));
		// }
	// }
	
	
	// public function approve($id, $pages) {
	// 	//update manuscript
	// 	$post['man_status'] = 5;
	// 	$post['man_page_position'] = $pages;
	// 	$post['last_updated'] = date('Y-m-d H:i:s');
	// 	$where['row_id'] = $id;
	// 	$this->Manuscript_model->process_manuscript(array_filter($post), $where, 3);
	// 	//save tracking
	// 	$track['trk_man_id'] = $id;
	// 	$track['trk_processor'] = _UserIdFromSession();
	// 	$track['trk_process_datetime'] = date('Y-m-d H:i:s');
	// 	$track['trk_description'] = 'APPROVED';
	// 	// $issue = (
	// 	// 	($issue == 5) ? 'Special Issue No. 1' :
	// 	// 	(($issue == 6) ? 'Special Issue No. 2' :
	// 	// 		(($issue == 7) ? 'Special Issue No. 3' :
	// 	// 			(($issue == 8) ? 'Special Issue No. 4' : 'Issue ' . $issue)))
	// 	// );
	// 	// $track['trk_remarks'] = 'Manuscript approved and published to eJournal Volume ' . $volume . ', ' . $issue;
	// 	$track['trk_remarks'] = 'Manuscript approved.';
	// 	$this->Manuscript_model->tracking(array_filter($track));
	// }

	/**
	 * Check if request lapsed
	 *
	 * @return  void
	 */
	public function check_expired_request() {
		$output = $this->Manuscript_model->get_reviewer_status();
		foreach ($output as $key => $val) {
			$future_date = date('Y-m-d H:i:s', strtotime($val->date_created . " +$val->rev_request_timer days"));
			$future = strtotime($future_date);
			$timeleft = $future - strtotime(date('Y-m-d H:i:s'));
			$daysleft = ((($timeleft / 24) / 60) / 60);
			if ($daysleft <= 0) {
				// update reviewer
				$post['rev_status'] = 3;
				$where['row_id'] = $val->row_id;
				$this->Manuscript_model->update_reviewer(array_filter($post), $where);
			} else {
				// counting
			}
		}
	}

	/**
	 * Retrieve tracking by manuscript id
	 *
	 * @param   int  $id  manuscript id
	 *
	 * @return  array       tracking
	 */
	public function tracker($id) {
		$output = $this->Manuscript_model->tracker($id);
		echo json_encode($output);
	}

	/**
	 * Retrieve manuscript data by id
	 *
	 * @param   int  $id  manuscript id
	 *
	 * @return  array       manuscript data
	 */
	public function get_manuscript_by_id($id)
	{
		$output = $this->Manuscript_model->get_manus_info($id);
		echo json_encode($output);
	}

	public function parking_spot($time, $driver){
		if($time == "7 AM" && $driver == "LEW"){
			return "PARKING NA MAY BUBONG";
		}else{
			return "IYAK";
		}
	}

	/**
	 * Retrieve manuscript title only by manuscript id
	 *
	 * @param   int  $id  manuscript id
	 *
	 * @return  string       manuscript title
	 */
	public function get_manuscript_title($id)
	{
		echo $this->Manuscript_model->get_manus_title($id);
		
	}


	/**
	 * Save final review of editorial board/publication committee
	 *
	 * @return void
	 */
	// public function final_review(){

	// 	$manus_id = $this->input->post('com_man_id', true);

	// 	$post['com_man_id'] = $manus_id; 
	// 	$post['com_review'] = $this->input->post('com_rev', true);
	// 	$post['com_remarks'] = $this->input->post('com_remarks', true);
	// 	$post['com_usr_id'] = _UserIdFromSession();
	// 	$post['date_created'] = date('Y-m-d H:i:s');

	// 	$this->Manuscript_model->final_review(array_filter($post));

	// 	$man['man_status'] = 4;
	// 	$man['last_updated'] = date('Y-m-d H:i:s');
	// 	$where['row_id'] = $manus_id;

	// 	$this->Manuscript_model->process_manuscript(array_filter($man), $where);

	// 	$track['trk_man_id'] = $manus_id;
	// 	$track['trk_processor'] = _UserIdFromSession();
	// 	$track['trk_process_datetime'] = date('Y-m-d H:i:s');
	// 	$track['trk_description'] = 'FINAL';
		
	// 	$this->Manuscript_model->tracking(array_filter($track));

		
	// 	// email
	// 	$this->notify_author_publication($manus_id);
		

	// }

	/**
	 * Technical desk editor submits consolidation to EIC if no revision, to author if for revision
	 *
	 * @return void
	 */
	public function consolidation_review(){
			
		$man_id = $this->input->post('cons_man_id', true);
		$status = $this->input->post('cons_revise', TRUE);

		$post['cons_man_id'] = $man_id; 
		$post['cons_usr_id'] = _UserIdFromSession(); 
		$post['cons_remarks'] = $this->input->post('cons_remarks', true);
		$post['cons_status'] = $status;
		$post['date_created'] = date('Y-m-d H:i:s');

		$file_string = str_replace(" ", "_", $_FILES['cons_file']['name']);
		$file_no_ext = preg_replace("/\.[^.]+$/", "", $file_string);
		$clean_file = preg_replace('/[^A-Za-z0-9\-]/', '_', $file_no_ext);
		$filename = $_FILES["cons_file"]["name"];
		$file_ext = pathinfo($filename, PATHINFO_EXTENSION);
		$post['cons_file'] = date('YmdHis') . '_' . $clean_file . '.' . $file_ext;
		$upload_file = $post['cons_file'];

		$config['upload_path'] = './assets/oprs/uploads/consolidations/';
		$config['allowed_types'] = '*';
		$config['file_name'] = $upload_file;
		$this->load->library('upload', $config);
		$this->upload->initialize($config);
		if (!$this->upload->do_upload('cons_file')) {
			$error = $this->upload->display_errors();
		} else {
			$data = $this->upload->data();
		}


		$this->Review_model->save_consolidations(array_filter($post));

		// email config
		// $link_to = "https://researchjournal.nrcp.dost.gov.ph/oprs/login";
		$link_to = base_url() . 'oprs/login';
		$sender = 'eReview';
		$sender_email = 'nrcp.ejournal@gmail.com';
		$password = 'fpzskheyxltsbvtg';

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
		
		// get manuscript info
		$output = $this->Review_model->get_manus_author_info($man_id);

		foreach ($output as $key => $value) {
			$manuscript = $value->man_title;
			$title = $value->man_author_title;
			$author = $value->man_author;
			$email = $value->man_email;
		}
		
		// email notification condition
		if($status == 1){ // for revision
			$man['man_status'] = 10;
			$track['trk_description'] = 'For Revision';
			$email_contents = $this->Email_model->get_email_content(13);

			$mail->AddAddress($email);
		}else{ // no revision
			$man['man_status'] = 7;
			$track['trk_description'] = 'Endorsed to Copy Editor for Proofreading';
			$email_contents = $this->Email_model->get_email_content(12);

			$next_processor_info = $this->User_model->get_processor_by_role(17);

			foreach ($next_processor_info as $row) {
				$next_processor_user_id = $row->usr_id;
				$next_processor_email = $row->usr_username;
			}

			$mail->AddAddress($next_processor_email);
		}

		// update manuscript status
		$man['last_updated'] = date('Y-m-d H:i:s');
		$where['row_id'] = $man_id;
		$this->Manuscript_model->update_manuscript_status(array_filter($man), $where);

		// save tracking
		$track['trk_man_id'] = $man_id;
		$track['trk_processor'] = _UserIdFromSession();
		$track['trk_process_datetime'] = date('Y-m-d H:i:s');
		$this->Manuscript_model->tracking(array_filter($track));


		// email config
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

		if($status == 1){ // for revision
			$link = "<a href='" . $link_to ."' target='_blank' style='cursor:pointer;'>
			https://researchjournal.nrcp.dost.gov.ph</a>";
			$emailBody = str_replace('[LINK]', $link, $email_contents);
			$emailBody = str_replace('[FULL NAME]', $author, $emailBody);
			$emailBody = str_replace('[TITLE]', $title, $emailBody);
			$emailBody = str_replace('[MANUSCRIPT]', $manuscript, $emailBody);
			

		}else{ // no revision
			$link = "<a href='" . $link_to ."' target='_blank' style='cursor:pointer;'>
			https://researchjournal.nrcp.dost.gov.ph</a>";
			$emailBody = str_replace('[LINK]', $link, $email_contents);
			$emailBody = str_replace('[MANUSCRIPT]', $manuscript, $emailBody);
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
	 * Retrieve all manuscripts
	 *
	 * @param   int  $id      manuscript id
	 * @param   string  $action  action
	 *
	 * @return  array           manuscript data
	 */
	public function manuscript($id, $action = null) {
		if ($id == 999) {
			$output = $this->Manuscript_model->get_all_manus_info();
		} else {
			$output = $this->Manuscript_model->get_manus_info($id);
		}
		echo json_encode($output);
	}

	/**
	 * Retrieve issues by journal id
	 *
	 * @param   string  $jor  journal
	 *
	 * @return  array        journal data
	 */
	public function journal($jor = null) {
		$output = $this->Manuscript_model->get_issues($jor);
		echo json_encode($output);
	}

	/**
	 * Retrieve reviewers by manuscript id
	 *
	 * @param   int  $id    manuscript id
	 * @param   string  $time  time
	 *
	 * @return  array         list of reviewers
	 */
	public function reviewers($id, $time) {
		$output = $this->Manuscript_model->get_reviewers($id, $time);
		echo json_encode($output);
	}

	/**
	 * Retrieve editors by manuscript id
	 *
	 * @param   int  $id    manuscript id
	 * @param   string  $time  time
	 *
	 * @return  array         list of reviewers
	 */
	public function editors($id) {
		$output = $this->Manuscript_model->get_editors($id);
		echo json_encode($output);
	}

	/**
	 * Retrieve reviews per manuscrip by editor id
	 *
	 * @param   int  $id    manuscript id
	 * @param   string  $time  time
	 *
	 * @return  array         list of reviewers
	 */
	public function reviews($id) {
		$output = $this->Manuscript_model->get_reviews($id);
		echo json_encode($output);
	}

	/**
	 * Retrieve review result for tracking
	 *
	 * @param   int  $id      reviewer id
	 * @param   int  $man_id  manuscript id
	 *
	 * @return  array           review result
	 */
	public function tracker_review($id, $man_id) {
		$output = $this->Review_model->get_review($id, $man_id);
		echo json_encode($output);
	}

	/**
	 * Retrieve reviewer info
	 *
	 * @param   int  $id  reviewer id
	 *
	 * @return  array       reviewer data
	 */
	public function reviewer_info($id) {
		$output = $this->Review_model->get_rev_info($id);
		echo json_encode($output);
	}

	/**
	 * Retrieve review status
	 *
	 * @param   int  $rev_id  reviewer id
	 * @param   int  $man_id  manuscript id
	 *
	 * @return  array           review result
	 */
	public function review_status($rev_id, $man_id) {
		$output = $this->Review_model->get_rev_status($rev_id, $man_id);
		echo json_encode($output);
	}

	/**
	 * Retrieve volume and issue
	 *
	 * @param   string  $value  year
	 *
	 * @return  array          journal data
	 */
	public function volume_issue($value) {
		$output = $this->Manuscript_model->get_journal_by_year($value);
		echo json_encode($output);
	}

	/**
	 * Count article by journal
	 *
	 * @param   string  $vol   volume
	 * @param   string  $iss   issue
	 * @param   string  $year  year
	 *
	 * @return  int         number of articles by journal
	 */
	public function get_jor_id($vol, $iss, $year) {
		$id = $this->Manuscript_model->get_jor_id($vol, $iss, $year);
		$output = $this->Manuscript_model->count_article_by_journal($id);
		echo $output;
	}

	/**
	 * Verify reviewer email
	 *
	 * @param   int  $id  reviewer id
	 *
	 * @return	array		reviewer data
	 */
	public function verify_reviewer_email($id) {
		$output = $this->Review_model->verify_reviewer_email($rev_mail = $this->input->post('trk_rev_email1', true), $id);
		echo json_encode($output);
	}

	/**
	 * Hide reviewer
	 *
	 * @param   int  $id    reviewer id
	 * @param   string  $user  user
	 *
	 * @return  array         user data
	 */
	public function hide_rev($id, $user) {
		$output = $this->Manuscript_model->hide_rev($id, $user);
		echo json_encode($output);
	}

	/**
	 * Set default author
	 *
	 * @return  array  author data
	 */
	public function default_auth() {
		$output = $this->Manuscript_model->default_auth(_UserIdFromSession());
		echo json_encode($output);
	}

	/**
	 * Send email to author (for finalization)
	 *
	 * @param   int  $id  author id
	 *
	 * @return  void
	 */
	public function notify_author_publication($id) {
		$output = $this->Review_model->get_manus_author_info($id);

		foreach ($output as $key => $value) {
			$title = $value->man_title;
			$dear = 'Dear ' . $val->man_author_title . ' ' . $value->man_author . '<br/><br/>';
			$email = $value->man_email;
		}

		$nameuser = 'eJournal Admin';
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
		$mail->AddAddress($email);
		$dir = "https://skms.nrcp.dost.gov.ph/user/login";
		$htmlBody = date("F j, Y") . '<br/><br/>' .
			$dear .
			'A publication committee has sent final evaluation on the manuscript <em>' . $title . '</em> which <br/>' .
			'you have submitted for publication to the NRCP Research Journal. <br/><br/>' .
			'Please <u><a href="' . $dir . '" target="_blank" style="color:blue;cursor:pointer;">' .
			'log in</a></u> to your SKMS account.<br/><br/>' .

			'Very truly yours,<br/>'.
			'Managing Editor<br/>'.
			'NRCP Research Journal<br/><br/>'.
			
			'** THIS IS AN AUTOMATED MESSAGE PLEASE DO NOT REPLY **';
	
		// email content
		$mail->Subject = "NRCP Journal Publication : Manuscript Review Result";
		$mail->Body = $htmlBody;
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

	public function notify_author_on_review($id){
		$output = $this->Review_model->get_manus_author_info($id);

		foreach ($output as $key => $value) {
			$manuscript = $value->man_title;
			$title = $value->man_author_title;
			$author = $value->man_author;
			$email = $value->man_email;
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

		$dir = "https://skms.nrcp.dost.gov.ph/user/login";
		$sender = 'eReview';
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
	
		$emailBody = str_replace('[FULL NAME]', $author, $email_contents);
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

	public function notify_author_on_editor_review($id, $content){
		$output = $this->Review_model->get_manus_author_info($id);

		foreach ($output as $key => $value) {
			$manuscript = $value->man_title;
			$title = $value->man_author_title;
			$name = $value->man_author;
			$email = $value->man_email;
		}

		
		// get email notification content
		$email_contents = $this->Email_model->get_email_content($content);

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

		$dir = "https://skms.nrcp.dost.gov.ph/user/login";
		$sender = 'eReview';
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
		if($content == 16){
			$link = "<a href='https://researchjournal.nrcp.dost.gov.ph/' target='_blank'>https://researchjournal.nrcp.dost.gov.ph/</a>";
		}else{
			$link = "Please click <a href='https://skms.nrcp.dost.gov.ph/user/login' target='_blank'>HERE</a> to login your SKMS account.";
		}
		$emailBody = str_replace('[FULL NAME]', $name, $email_contents);
		$emailBody = str_replace('[TITLE]', $title, $emailBody);
		$emailBody = str_replace('[MANUSCRIPT]', $manuscript, $emailBody);
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
	 * Retrieve title (mr., ms., etc)
	 *
	 * @return  array  titles
	 */
	public function get_titles() {
		$output = $this->Library_model->get_titles();
		echo json_encode($output);
	}

	/**
	 * Retrieve editorial board/publication committee final review
	 *
	 * @param [type] $id
	 * @return void
	 */
	public function get_committee_review($id){
		$output = $this->Manuscript_model->get_com_rev($id);
		echo json_encode($output);
	}

	/**
	 * Delete manuscript
	 *
	 * @param [type] $id
	 * @return void
	 */
	public function remove_manus($id){
		$output = $this->Manuscript_model->get_manus_info($id);
		foreach($output as $row){
			$dir_abs = '/var/www/html/ejournal/assets/oprs/uploads/initial_abstracts_pdf/';
			// $dir_abs = $_SERVER['DOCUMENT_ROOT'] . '/ejournal/assets/oprs/uploads/initial_abstracts_pdf/';
			unlink($dir_abs . $row->man_abs);
			
			$dir_file = '/var/www/html/ejournal/assets/oprs/uploads/initial_manuscripts_pdf/';
			// $dir_file = $_SERVER['DOCUMENT_ROOT'] . '/ejournal/assets/oprs/uploads/initial_manuscripts_pdf/';
			unlink($dir_file . $row->man_file);
			
			$dir_word = '/var/www/html/ejournal/assets/oprs/uploads/initial_manuscripts_word/';
			// $dir_word = $_SERVER['DOCUMENT_ROOT'] . '/ejournal/assets/oprs/uploads/initial_manuscripts_word/';
			unlink($dir_word . $row->man_word);
		}
		$where_m['row_id'] = $id;
		$this->Manuscript_model->remove_manus_by_man_id($where_m);
		$where_r['rev_man_id'] = $id;
		$this->Manuscript_model->remove_reviewers_by_man_id($where_r);
		$where_t['trk_man_id'] = $id;
		$this->Manuscript_model->remove_tracking_by_man_id($where_t);
		$where_c['coa_man_id'] = $id;
		$this->Manuscript_model->remove_coa_by_man_id($where_c);
		$where_l['log_insert_id'] = $id;
		$this->Manuscript_model->remove_logs_by_man_id($where_l);
	}

	/**
	 * Send email to author 
	 *
	 * @param [type] $id
	 * @return void
	 */
	public function send_email_author($man_id){

		$output = $this->Review_model->get_manus_author_info($man_id);

		foreach ($output as $key => $value) {
			$manuscript = $value->man_title;
			$title = $value->man_author_title;
			$author = $value->man_author;
			$email = $value->man_email;
		}

		// $user_info = $this->User_model->get_corresponding_author(_UserIdFromSession());
		// foreach ($user_info as $key => $row) {
		// 	$title =  $row->title;
		// 	$name = $row->first_name . ' ' . $row->last_name;
		// 	$email = $row->usr_username;
		// }
		
		// $link_to = "https://researchjournal.nrcp.dost.gov.ph/oprs/login";
		$link_to = base_url() . 'oprs/login';
		$sender = 'eReview';
		$sender_email = 'nrcp.ejournal@gmail.com';
		$password = 'fpzskheyxltsbvtg';

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

		$email_contents = $this->Email_model->get_email_content(1);

		// add cc/bcc
		foreach($email_contents as $row){
			$email_body = $row->enc_content;
			$email_subject = $row->enc_subject;

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
		$link = "<a href='" . $link_to ."' target='_blank' style='cursor:pointer;'>
			https://researchjournal.nrcp.dost.gov.ph/</a>";
		$emailBody = str_replace('[TITLE]', $title, $email_body);
		$emailBody = str_replace('[FULL NAME]', $author, $emailBody);
		$emailBody = str_replace('[LINK]', $link, $emailBody);
		$emailBody = str_replace('[MANUSCRIPT]', $manuscript, $emailBody);

	
		// email content
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
			echo '<br/><br/>Message could not be sent.<br/>';
			echo 'Mailer Error: ' . $mail->ErrorInfo . '<br/>';
			exit;
		}
	}
	
	public function add_remarks(){
		$where['row_id'] = $this->input->post('man_id', TRUE);
		$post['man_remarks'] = $this->input->post('man_remarks', TRUE);
		
		$this->Manuscript_model->update_remarks(array_filter($post), $where);
		
	}

	public function upload_nda(){
		$post = array();
        $file_string = str_replace(" ", "_", $_FILES['scr_nda']['name']);
		$file_no_ext = preg_replace("/\.[^.]+$/", "", $file_string);
		$clean_file = preg_replace('/[^A-Za-z0-9\-]/', '_', $file_no_ext);
		$filename = $_FILES["scr_nda"]["name"];
		$file_ext = pathinfo($filename, PATHINFO_EXTENSION);
		$post['scr_nda'] = date('YmdHis') . '_' . $clean_file . '.' . $file_ext;
		$upload_file = $post['scr_nda'];

		if ($post['scr_nda'] != '') {
			$config['upload_path'] = './assets/oprs/uploads/nda/';
		    $config['allowed_types'] = '*';
			$config['file_name'] = $upload_file;
			$this->load->library('upload', $config);
			$this->upload->initialize($config);
			if (!$this->upload->do_upload('scr_nda')) {
				$error = $this->upload->display_errors();
			} else {
				$data = $this->upload->data();
			}
		}


		$where_rev['scr_man_rev_id'] = _UserIdFromSession();
		$where_rev['scr_man_id'] = $this->input->post('scr_man_id', true);
		$this->Review_model->update_review(array_filter($post), $where_rev, 'nda');
	}

	public function for_publication(){
		$man_id = $this->input->post('man_id', true);

		// update manuscript status
		$post['man_status'] = 7;
		$where['row_id'] = $man_id;
		$output = $this->Manuscript_model->process_manuscript(array_filter($post), $where);

		// save trackingpublish_articles
		$track['trk_man_id'] = $man_id;
		$track['trk_processor'] = _UserIdFromSession();
		$track['trk_process_datetime'] = date('Y-m-d H:i:s');
		$track['trk_remarks'] = $this->input->post('trk_remarks', true);
		$track['trk_description'] = 'LAYOUT';
		$this->Manuscript_model->tracking(array_filter($track));

		// send email for publication
		$this->notify_author_on_editor_review($man_id, 15);

	}

	public function upload_publishable(){
		$man_id = $this->input->post('man_id', true);

		$post = array();

		// final abstract
        $file_string = str_replace(" ", "_", $_FILES['man_abs']['name']);
		$file_no_ext = preg_replace("/\.[^.]+$/", "", $file_string);
		$clean_file = preg_replace('/[^A-Za-z0-9\-]/', '_', $file_no_ext);
		$filename = $_FILES["man_abs"]["name"];
		$file_ext = pathinfo($filename, PATHINFO_EXTENSION);
		$post['man_abs'] = date('YmdHis') . '_' . $clean_file . '.' . $file_ext;
		$upload_file = $post['man_abs'];
		
		// final manuscript
        $file_string = str_replace(" ", "_", $_FILES['man_file']['name']);
		$file_no_ext = preg_replace("/\.[^.]+$/", "", $file_string);
		$clean_file = preg_replace('/[^A-Za-z0-9\-]/', '_', $file_no_ext);
		$filename = $_FILES["man_file"]["name"];
		$file_ext = pathinfo($filename, PATHINFO_EXTENSION);
		$post['man_file'] = date('YmdHis') . '_' . $clean_file . '.' . $file_ext;
		$upload_file = $post['man_file'];

		if ($post['man_abs'] != '') {
			$config['upload_path'] = './assets/oprs/uploads/final_abstracts/';
		    $config['allowed_types'] = '*';
			$config['file_name'] = $upload_file;
			$this->load->library('upload', $config);
			$this->upload->initialize($config);
			if (!$this->upload->do_upload('man_abs')) {
				$error = $this->upload->display_errors();
			} else {
				$data = $this->upload->data();
			}
		}

		if ($post['man_file'] != '') {
			$config['upload_path'] = './assets/oprs/uploads/final_manuscripts/';
		    $config['allowed_types'] = '*';
			$config['file_name'] = $upload_file;
			$this->load->library('upload', $config);
			$this->upload->initialize($config);
			if (!$this->upload->do_upload('man_file')) {
				$error = $this->upload->display_errors();
			} else {
				$data = $this->upload->data();
			}
		}

		$post['last_updated'] = date('Y-m-d H:i:s');
		$post['man_status'] = 8;
		$where['row_id'] = $man_id;
		$this->Manuscript_model->process_manuscript(array_filter($post), $where);

		// save tracking
		$track = array();
		$track['trk_man_id'] = $man_id;
		$track['trk_processor'] = _UserIdFromSession();
		$track['trk_description'] = 'PUBLISHABLE';
		$track['trk_process_datetime'] = date('Y-m-d H:i:s');
		$this->Manuscript_model->tracking(array_filter($track));

		// send email for published articls
		$this->notify_author_on_editor_review($man_id, 16);

	}

	public function change_status($id, $status){

		$post['last_updated'] = date('Y-m-d H:i:s');
		$post['man_status'] = $status;
		$where['row_id'] = $id;
		$this->Manuscript_model->process_manuscript(array_filter($post), $where);

		// save tracking
		$track = array();
		$track['trk_man_id'] = $id;
		$track['trk_processor'] = _UserIdFromSession();
		$track['trk_description'] = 'PUBLISHED TO OTHER JOURNAL PLATFORM';
		$track['trk_process_datetime'] = date('Y-m-d H:i:s');
		$this->Manuscript_model->tracking(array_filter($track));
	

		$manus_info = $this->Manuscript_model->get_manus_info($id);

		foreach($manus_info as $key => $row){
			$man_title = $row->man_title;
			$title = $row->man_author_title;
			$author = $row->man_author;
			$recepient = $row->man_email;
		}

		// get email notification content
		$email_contents = $this->Email_model->get_email_content(19);

		// add cc/bcc
		foreach($email_contents as $row){
			$email_body = $row->enc_content;
			$email_subject = $row->enc_subject;

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

		$dir = "https://skms.nrcp.dost.gov.ph/main/login";
		$link = '<a href="' . $dir . '" target="_blank">skms.nrcp.dost.gov.ph</a>';
		$sender = 'eReview';
		$sender_mail = 'nrcp.ejournal@gmail.com';
		$password = 'fpzskheyxltsbvtg';
		
		// replace reserved words
		$emailBody = str_replace('[TITLE]', $title, $email_body);
		$emailBody = str_replace('[FULL NAME]', $author, $emailBody);
		$emailBody = str_replace('[MANUSCRIPT]', $man_title, $emailBody);
		$emailBody = $emailBody;

		// setup email config
		$mail = new PHPMailer;
		$mail->isSMTP();
		$mail->Host = "smtp.gmail.com";
		// Specify main and backup server
		$mail->SMTPAuth = true;
		$mail->Port = 465;
		// Enable SMTP authentication
		$mail->Username = $sender_mail;
		// SMTP username
		$mail->Password = $password;
		// SMTP password
		$mail->SMTPSecure = 'ssl';
		// Enable encryption, 'ssl' also accepted
		$mail->From = $sender_mail;
		$mail->FromName = $sender;
		$mail->AddAddress($recepient);
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
			return '<br/><br/>Message could not be sent.<br/>
					Mailer Error: ' . $mail->ErrorInfo . '<br/>';
			exit;
		}else{
			return $emailBody;
		}
		
		echo 1;
	}

	public function tester(){
		$value = '3,9';
		if( strpos($value, ',') !== false ) {
			$email_user_group = explode(',', $value);
		}else{
			$email_user_group = array();
			array_push($email_user_group, $value);
		}

		// add exisiting email as cc
		if(count($email_user_group) > 0){
			$user_group_emails = array();
			foreach($email_user_group as $grp){
				$username = $this->Email_model->get_user_group_emails($grp);
				array_push($user_group_emails, $username);
			}
			echo count($user_group_emails);
			print_r($user_group_emails);
		}

		foreach($user_group_emails as $name){
			echo $name->usr_username;
		}
	}

	public function search(){
		$search = $this->input->post('search', TRUE);
		$filter = $this->input->post('filter', TRUE);

		$output = $this->Manuscript_model->search($search, $filter);
		echo json_encode($output);
	}

	public function technical_review_process(){

		$oprs = $this->load->database('dboprs', TRUE);
		$tableName = 'tbltech_rev_score';
		$result = $oprs->list_fields($tableName);
		$post = array(); $where = array();
		foreach ($result as $i => $field) {
			if($field != 'tr_remarks'){
				$post[$field] = $this->input->post($field, true);
			}
		}

		$man_id = $this->input->post('tr_man_id', TRUE);
		$remarks = $this->input->post('tr_remarks', TRUE);
		$output = $this->Review_model->get_tech_rev_score($man_id);

		if(count($output) > 0){
			// update exsiting record
			$where['tr_processor_id'] = _UserIdFromSession();
			$where['tr_man_id'] = $man_id;
			$post['tr_date_reviewed'] = date('Y-m-d H:i:s');
			$post['tr_final'] = 1;
			$post['tr_remarks'] = ($remarks != '') ? $remarks : 'No remarks';

			$this->Review_model->update_tech_rev_score(array_filter($post), $where);
		}else{
			// save criteria score
			$post['tr_processor_id'] = _UserIdFromSession();
			$post['tr_date_reviewed'] = date('Y-m-d H:i:s');
			$post['tr_remarks'] = ($remarks != '') ? $remarks : 'No remarks';

			$this->Review_model->save_tech_rev_score(array_filter($post));
		}


		// email config
		// $link_to = "https://researchjournal.nrcp.dost.gov.ph/oprs/login";
		$link_to = base_url() . 'oprs/login';
		$sender = 'eReview';
		$sender_email = 'nrcp.ejournal@gmail.com';
		$password = 'fpzskheyxltsbvtg';

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
		
		$post = array(); $where = array();

		if($this->input->post('tr_final') == 1){ // passed

			$track['trk_description'] = 'Passed: Endorsed to Editor-in-chief';
			$track['trk_remarks'] = ($remarks != '') ? $remarks : 'No remarks';


			// send email 3
			// update manuscript status passed endorsed to EIC
			$manus['man_status'] = 2;
			$where['row_id'] = $man_id;
			$this->Manuscript_model->update_manuscript_status(array_filter($manus), $where);

			// save/update initial data for EIC review for duration validation
			$next_processor_info = $this->User_model->get_processor_by_role(6);

			foreach ($next_processor_info as $row) {
				$next_processor_user_id = $row->usr_id;
				$next_processor_email = $row->usr_username;
			}

			$output = $this->Review_model->get_last_editors_review($man_id);

			if(count($output) > 0){
				// remove all editors review score
				$this->Review_model->reset_editor_review(['edit_man_id' => $man_id]);
			}

			
			// save editors review data
			$editorial_data['edit_man_id'] = $man_id;
			$editorial_data['edit_usr_id'] = $next_processor_user_id;
			$editorial_data['date_created'] = date('Y-m-d H:i:s');
			$this->Review_model->save_initial_editor_data(array_filter($editorial_data));

			
			
			// send email to next processor
			$mail->AddAddress($next_processor_email);

			$output = $this->Review_model->get_manus_author_info($man_id);

			foreach ($output as $key => $value) {
				$manuscript = $value->man_title;
				$title = $value->man_author_title;
				$author = $value->man_author;
				$email = $value->man_email;
			}

			// get email notification content
			$email_contents = $this->Email_model->get_email_content(3);

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
			
			$link = "<a href='" . $link_to ."' target='_blank' style='cursor:pointer;'>
			https://researchjournal.nrcp.dost.gov.ph</a>";
			$emailBody = str_replace('[LINK]', $link, $email_contents);
			$emailBody = str_replace('[MANUSCRIPT]', $manuscript, $emailBody);


		}else{ // failed

			$track['trk_description'] = 'For Revision: Failure to meet the criteria';
			$track['trk_remarks'] = $remarks;

			// send email 2
			// update manuscript status failed/for revision
			$manus['man_status'] = 10;
			$where['row_id'] = $man_id;
			$this->Manuscript_model->update_manuscript_status(array_filter($manus), $where);
			
			// send email to author
			$output = $this->Review_model->get_manus_author_info($man_id);
			foreach ($output as $key => $value) {
				$manuscript = $value->man_title;
				$title = $value->man_author_title;
				$author = $value->man_author;
				$email = $value->man_email;
			}

			// get email notification content
			$email_contents = $this->Email_model->get_email_content(13);
			
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

			$link = "<a href='" . $link_to ."' target='_blank' style='cursor:pointer;'>
			https://researchjournal.nrcp.dost.gov.ph</a>";
			$emailBody = str_replace('[FULL NAME]', $author, $email_contents);
			$emailBody = str_replace('[TITLE]', $title, $emailBody);
			$emailBody = str_replace('[LINK]', $link, $emailBody);
			$emailBody = str_replace('[MANUSCRIPT]', $manuscript, $emailBody);

			$mail->AddAddress($email);
		}

		// save tracking
		$track['trk_man_id'] = $man_id;
		$track['trk_processor'] = _UserIdFromSession();
		$track['trk_process_datetime'] = date('Y-m-d H:i:s');
		$track['trk_source'] = '_op';
		$this->Manuscript_model->tracking(array_filter($track));

		// add exisiting email as cc
		if(count($email_user_group) > 0){
			$user_group_emails = array();
			foreach($email_user_group as $grp){
				$username = $this->Email_model->get_user_group_emails($grp);
				array_push($user_group_emails, $username);
			}
		}

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

	public function get_tech_rev_score($id){
		$output = $this->Review_model->get_tech_rev_score($id);
		echo json_encode($output);
	}

	public function unique_title() {
		$output = $this->Manuscript_model->unique_title($this->input->post('man_title', true));
		echo $output;
	}


	public function eic_review_process(){
		
		$man_id = $this->input->post('id', TRUE);
		$remarks = $this->input->post('remarks', TRUE);
		$status = $this->input->post('status', TRUE);

		// email config
		// $link_to = "https://researchjournal.nrcp.dost.gov.ph/oprs/login";
		$link_to = base_url() . 'oprs/login';
		$sender = 'eReview';
		$sender_email = 'nrcp.ejournal@gmail.com';
		$password = 'fpzskheyxltsbvtg';

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

		if($status == 14){ // reject

			$track['trk_description'] = 'Rejected';
			$track['trk_remarks'] = $remarks;

			// send email 4
			// update manuscript status 
			$manus['man_status'] = $status;
			$where['row_id'] = $man_id;
			$this->Manuscript_model->update_manuscript_status(array_filter($manus), $where);

				
			$output = $this->Review_model->get_manus_author_info($man_id);

			foreach ($output as $key => $value) {
				$manuscript = $value->man_title;
				$title = $value->man_author_title;
				$author = $value->man_author;
				$email = $value->man_email;
			}

			// get email notification content
			$email_contents = $this->Email_model->get_email_content(4);

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
			
			$emailBody = str_replace('[FULL NAME]', $author, $email_contents);
			$emailBody = str_replace('[TITLE]', $title, $emailBody);
			$emailBody = str_replace('[MANUSCRIPT]', $manuscript, $emailBody);

			$mail->AddAddress($email);

		}else if($status == 10){ // revise

			$track['trk_description'] = 'For Revision';
			$track['trk_remarks'] = $remarks;

			// send email 13
			// update manuscript status
			$manus['man_status'] = $status;
			$where['row_id'] = $man_id;
			$this->Manuscript_model->update_manuscript_status(array_filter($manus), $where);
			
			$output = $this->Review_model->get_manus_author_info($man_id);

			foreach ($output as $key => $value) {
				$manuscript = $value->man_title;
				$title = $value->man_author_title;
				$author = $value->man_author;
				$email = $value->man_email;
			}

			// get email notification content
			$email_contents = $this->Email_model->get_email_content(13);
			
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

			$link = "<a href='" . $link_to ."' target='_blank' style='cursor:pointer;'>
			https://researchjournal.nrcp.dost.gov.ph</a>";
			$emailBody = str_replace('[LINK]', $link, $email_contents);
			$emailBody = str_replace('[FULL NAME]', $author, $emailBody);
			$emailBody = str_replace('[TITLE]', $title, $emailBody);
			$emailBody = str_replace('[MANUSCRIPT]', $manuscript, $emailBody);

			$mail->AddAddress($email);
		}else if($status == 15){ // accepted

			$track['trk_description'] = 'Accepted';
			$track['trk_remarks'] = $remarks;

			// send email 24
			// update manuscript status
			$manus['man_status'] = $status;
			$where['row_id'] = $man_id;
			$this->Manuscript_model->update_manuscript_status(array_filter($manus), $where);
			
			$output = $this->Review_model->get_manus_author_info($man_id);

			foreach ($output as $key => $value) {
				$manuscript = $value->man_title;
				$title = $value->man_author_title;
				$author = $value->man_author;
				$email = $value->man_email;
			}

			// get email notification content
			$email_contents = $this->Email_model->get_email_content(24);
			
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

			$link = "<a href='" . $link_to ."' target='_blank' style='cursor:pointer;'>
			https://researchjournal.nrcp.dost.gov.ph</a>";
			$emailBody = str_replace('[LINK]', $link, $email_contents);
			$emailBody = str_replace('[MANUSCRIPT]', $manuscript, $emailBody);

			$next_processor_info = $this->User_model->get_processor_by_role(5);

			foreach ($next_processor_info as $row) {
				$next_processor_email = $row->usr_username;
			}

			$mail->AddAddress($next_processor_email);
		}else if($status == 3){ // endorsed to associate ditor
			$track['trk_description'] = 'Endorsed to Associate Editor';
			$track['trk_remarks'] = $remarks;

			// send email 22
			// update manuscript status
			$manus['man_status'] = $status;
			$where['row_id'] = $man_id;
			$this->Manuscript_model->update_manuscript_status(array_filter($manus), $where);
			
			$output = $this->Review_model->get_manus_author_info($man_id);

			foreach ($output as $key => $value) {
				$manuscript = $value->man_title;
				$title = $value->man_author_title;
				$author = $value->man_author;
				$email = $value->man_email;
			}

			// get email notification content
			$email_contents = $this->Email_model->get_email_content(22);
			
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

			$link = "<a href='" . $link_to ."' target='_blank' style='cursor:pointer;'>
			https://researchjournal.nrcp.dost.gov.ph</a>";
			$emailBody = str_replace('[LINK]', $link, $email_contents);
			$emailBody = str_replace('[MANUSCRIPT]', $manuscript, $emailBody);


			// save initial data for EIC review for duration validation
			$next_processor_info = $this->User_model->get_processor_by_id($this->input->post('associate_editor', TRUE));

			foreach ($next_processor_info as $row) {
				$next_processor_user_id = $row->usr_id;
				$next_processor_email = $row->usr_username;
			}

			$editorial_data['edit_man_id'] = $man_id;
			$editorial_data['edit_usr_id'] = $next_processor_user_id;
			$editorial_data['edit_remarks'] = $remarks;
			$editorial_data['date_created'] = date('Y-m-d H:i:s');

			$this->Review_model->save_initial_editor_data(array_filter($editorial_data));

			$mail->AddAddress($next_processor_email);

		}else{ // add peer reviwers
			$track['trk_description'] = 'Suggested Peer Reviewers';
			$track['trk_remarks'] = $remarks;

			// send email 23
			// update manuscript status
			$status = 15;
			$manus['man_status'] = $status;
			$where['row_id'] = $man_id;
			$output = $this->Manuscript_model->update_manuscript_status(array_filter($manus), $where);
			
			$output = $this->Review_model->get_manus_author_info($man_id);

			foreach ($output as $key => $value) {
				$manuscript = $value->man_title;
				$title = $value->man_author_title;
				$author = $value->man_author;
				$email = $value->man_email;
			}


			$serializedData = $this->input->post('suggested_peer', true);

			// Parse the serialized form data into an associative array
			$suggestedPeer = [];
			parse_str($serializedData, $suggestedPeer);
			
			$peer_title = $suggestedPeer['suggested_peer_rev_title'];
			$peer_rev = $suggestedPeer['suggested_peer_rev'];
			$peer_email = $suggestedPeer['suggested_peer_rev_email'];
			$peer_num = $suggestedPeer['suggested_peer_rev_num'];
			$peer_spec = $suggestedPeer['suggested_peer_rev_spec'];
			$peer_id = $suggestedPeer['suggested_peer_rev_id'];
	
			$peers = array();
	
			for ($i = 0; $i < count($peer_rev); $i++) {
				$peers['peer_title'] = $peer_title[$i];
				$peers['peer_name'] = $peer_rev[$i];
				$peers['peer_email'] = $peer_email[$i];
				$peers['peer_contact'] = $peer_num[$i];
				$peers['peer_specialization'] = $peer_spec[$i];
				$peers['peer_usr_id'] = $peer_id[$i];
				$peers['peer_type'] = $peer_id[$i] ? 'Member' : 'Non-member';
				$peers['peer_man_id'] = $man_id;
				$peers['peer_clued_usr_id'] = _UserIdFromSession();
				$peers['date_created'] = date('Y-m-d H:i:s');
				$this->Review_model->save_peer_reviewers(array_filter($peers));
			}

			// get email notification content
			$email_contents = $this->Email_model->get_email_content(24);

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
						
			$link = "<a href='" . $link_to ."' target='_blank' style='cursor:pointer;'>
			https://researchjournal.nrcp.dost.gov.ph</a>";
			$emailBody = str_replace('[LINK]', $link, $email_contents);
			$emailBody = str_replace('[MANUSCRIPT]', $manuscript, $emailBody);

			
			$next_processor_info = $this->User_model->get_processor_by_role(5);

			foreach ($next_processor_info as $row) {
				$next_processor_email = $row->usr_username;
			}

			$mail->AddAddress($next_processor_email);
		}

		// update editors review data status
		$where_editorial_data['edit_man_id'] = $man_id;
		$where_editorial_data['edit_usr_id'] = _UserIdFromSession();
		$update_editorial_data['edit_status'] = $status;
		$update_editorial_data['edit_remarks'] = $remarks;
        $update_editorial_data['last_updated'] = date('Y-m-d H:i:s');

		$this->Review_model->update_editor_data($update_editorial_data, $where_editorial_data);

		// save tracking
		$track['trk_man_id'] = $man_id;
		$track['trk_processor'] = _UserIdFromSession();
		$track['trk_process_datetime'] = date('Y-m-d H:i:s');
		$track['trk_source'] = '_op';
		$this->Manuscript_model->tracking(array_filter($track));

		// add exisiting email as cc
		if(count($email_user_group) > 0){
			$user_group_emails = array();
			foreach($email_user_group as $grp){
				$username = $this->Email_model->get_user_group_emails($grp);
				array_push($user_group_emails, $username);
			}
		}


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

	public function assoc_review_process(){
		
		$man_id = $this->input->post('id', TRUE);
		$remarks = $this->input->post('remarks', TRUE);
		$status = $this->input->post('status', TRUE);
		
		// email config
		// $link_to = "https://researchjournal.nrcp.dost.gov.ph/oprs/login";
		$link_to = base_url() . 'oprs/login';
		$sender = 'eReview';
		$sender_email = 'nrcp.ejournal@gmail.com';
		$password = 'fpzskheyxltsbvtg';

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

		if($status == 14){ // reject

			$track['trk_description'] = 'Rejected';
			$track['trk_remarks'] = $remarks;

			// send email 4
			// update manuscript status 
			$manus['man_status'] = $status;
			$where['row_id'] = $man_id;
			$this->Manuscript_model->update_manuscript_status(array_filter($manus), $where);

				
			$output = $this->Review_model->get_manus_author_info($man_id);

			foreach ($output as $key => $value) {
				$manuscript = $value->man_title;
				$title = $value->man_author_title;
				$author = $value->man_author;
				$email = $value->man_email;
			}

			// get email notification content
			$email_contents = $this->Email_model->get_email_content(4);

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
			
			$emailBody = str_replace('[FULL NAME]', $author, $email_contents);
			$emailBody = str_replace('[TITLE]', $title, $emailBody);
			$emailBody = str_replace('[MANUSCRIPT]', $manuscript, $emailBody);

			$mail->AddAddress($email);

		}else if($status == 10){ // revise

			$track['trk_description'] = 'For Revision';
			$track['trk_remarks'] = $remarks;

			// send email 13
			// update manuscript status
			$manus['man_status'] = $status;
			$where['row_id'] = $man_id;
			$this->Manuscript_model->update_manuscript_status(array_filter($manus), $where);
			
			$output = $this->Review_model->get_manus_author_info($man_id);

			foreach ($output as $key => $value) {
				$manuscript = $value->man_title;
				$title = $value->man_author_title;
				$author = $value->man_author;
				$email = $value->man_email;
			}

			// get email notification content
			$email_contents = $this->Email_model->get_email_content(13);
			
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

			$link = "<a href='" . $link_to ."' target='_blank' style='cursor:pointer;'>
			https://researchjournal.nrcp.dost.gov.ph</a>";
			$emailBody = str_replace('[LINK]', $link, $email_contents);
			$emailBody = str_replace('[FULL NAME]', $author, $emailBody);
			$emailBody = str_replace('[TITLE]', $title, $emailBody);
			$emailBody = str_replace('[MANUSCRIPT]', $manuscript, $emailBody);

			$mail->AddAddress($email);
		}else if($status == 15){ // accepted

			$track['trk_description'] = 'Accepted';
			$track['trk_remarks'] = $remarks;

			// send email 24
			// update manuscript status
			$manus['man_status'] = $status;
			$where['row_id'] = $man_id;
			$this->Manuscript_model->update_manuscript_status(array_filter($manus), $where);
			
			$output = $this->Review_model->get_manus_author_info($man_id);

			foreach ($output as $key => $value) {
				$manuscript = $value->man_title;
				$title = $value->man_author_title;
				$author = $value->man_author;
				$email = $value->man_email;
			}

			// get email notification content
			$email_contents = $this->Email_model->get_email_content(24);
			
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

			$link = "<a href='" . $link_to ."' target='_blank' style='cursor:pointer;'>
			https://researchjournal.nrcp.dost.gov.ph</a>";
			$emailBody = str_replace('[LINK]', $link, $email_contents);
			$emailBody = str_replace('[MANUSCRIPT]', $manuscript, $emailBody);

			$next_processor_info = $this->User_model->get_processor_by_role(5);

			foreach ($next_processor_info as $row) {
				$next_processor_email = $row->usr_username;
			}

			$mail->AddAddress($next_processor_email);
		}else if($status == 4){ // endorsed to cluster editor

			$track['trk_description'] = 'Endorsed to Cluster Editors';
			$track['trk_remarks'] = $remarks;

			// send email 23
			// update manuscript status
			$manus['man_status'] = $status;
			$where['row_id'] = $man_id;
			$output = $this->Manuscript_model->update_manuscript_status(array_filter($manus), $where);
			// echo json_encode($output);exit;
			$output = $this->Review_model->get_manus_author_info($man_id);

			foreach ($output as $key => $value) {
				$manuscript = $value->man_title;
				$title = $value->man_author_title;
				$author = $value->man_author;
				$email = $value->man_email;
			}

			
			$cluster_editors = $this->input->post('cluster_editor', TRUE);

			foreach($cluster_editors as $row){
				$next_processor_info = $this->User_model->get_processor_by_id($row);

				foreach ($next_processor_info as $row) {
					$next_processor_user_id = $row->usr_id;
					$next_processor_email = $row->usr_username;
				}

				
				// get email notification content
				$email_contents = $this->Email_model->get_email_content(23);
							
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

				$link = "<a href='" . $link_to ."' target='_blank' style='cursor:pointer;'>
				https://researchjournal.nrcp.dost.gov.ph</a>";
				$emailBody = str_replace('[LINK]', $link, $email_contents);
				$emailBody = str_replace('[MANUSCRIPT]', $manuscript, $emailBody);


				// save initial data for EIC review for duration validation
				$editorial_data['edit_man_id'] = $man_id;
				$editorial_data['edit_usr_id'] = $next_processor_user_id;
				$editorial_data['edit_remarks'] = $remarks;
				$editorial_data['date_created'] = date('Y-m-d H:i:s');

				$this->Review_model->save_initial_editor_data(array_filter($editorial_data));

				$mail->AddAddress($next_processor_email);

				// add exisiting email as cc
				if(count($email_user_group) > 0){
					$user_group_emails = array();
					foreach($email_user_group as $grp){
						$username = $this->Email_model->get_user_group_emails($grp);
						array_push($user_group_emails, $username);
					}
				}
		
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
				$mail->ClearAllRecipients();
			}
		}else{ // add peer reviwers
			$track['trk_description'] = 'Suggested Peer Reviewers';
			$track['trk_remarks'] = $remarks;

			// send email 23
			// update manuscript status
			$status = 15;
			$manus['man_status'] = $status;
			$where['row_id'] = $man_id;
			$output = $this->Manuscript_model->update_manuscript_status(array_filter($manus), $where);
			
			$output = $this->Review_model->get_manus_author_info($man_id);

			foreach ($output as $key => $value) {
				$manuscript = $value->man_title;
				$title = $value->man_author_title;
				$author = $value->man_author;
				$email = $value->man_email;
			}


			$serializedData = $this->input->post('suggested_peer', true);

			// Parse the serialized form data into an associative array
			$suggestedPeer = [];
			parse_str($serializedData, $suggestedPeer);
			
			$peer_title = $suggestedPeer['suggested_peer_rev_title'];
			$peer_rev = $suggestedPeer['suggested_peer_rev'];
			$peer_email = $suggestedPeer['suggested_peer_rev_email'];
			$peer_num = $suggestedPeer['suggested_peer_rev_num'];
			$peer_spec = $suggestedPeer['suggested_peer_rev_spec'];
			$peer_id = $suggestedPeer['suggested_peer_rev_id'];
	
			$peers = array();
	
			for ($i = 0; $i < count($peer_rev); $i++) {
				$peers['peer_title'] = $peer_title[$i];
				$peers['peer_name'] = $peer_rev[$i];
				$peers['peer_email'] = $peer_email[$i];
				$peers['peer_contact'] = $peer_num[$i];
				$peers['peer_specialization'] = $peer_spec[$i];
				$peers['peer_usr_id'] = $peer_id[$i];
				$peers['peer_type'] = $peer_id[$i] ? 'Member' : 'Non-member';
				$peers['peer_man_id'] = $man_id;
				$peers['peer_clued_usr_id'] = _UserIdFromSession();
				$peers['date_created'] = date('Y-m-d H:i:s');
				$this->Review_model->save_peer_reviewers(array_filter($peers));
			}

			// get email notification content
			$email_contents = $this->Email_model->get_email_content(24);

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
						
			$link = "<a href='" . $link_to ."' target='_blank' style='cursor:pointer;'>
			https://researchjournal.nrcp.dost.gov.ph</a>";
			$emailBody = str_replace('[LINK]', $link, $email_contents);
			$emailBody = str_replace('[MANUSCRIPT]', $manuscript, $emailBody);

			
			$next_processor_info = $this->User_model->get_processor_by_role(5);

			foreach ($next_processor_info as $row) {
				$next_processor_email = $row->usr_username;
			}

			$mail->AddAddress($next_processor_email);
		}

		// update editors review data status
		$where_editorial_data['edit_man_id'] = $man_id;
		$where_editorial_data['edit_usr_id'] = _UserIdFromSession();
		$update_editorial_data['edit_status'] = $status;
		$update_editorial_data['edit_remarks'] = $remarks;
        $update_editorial_data['last_updated'] = date('Y-m-d H:i:s');

		$this->Review_model->update_editor_data($update_editorial_data, $where_editorial_data);

		// save tracking
		$track['trk_man_id'] = $man_id;
		$track['trk_processor'] = _UserIdFromSession();
		$track['trk_process_datetime'] = date('Y-m-d H:i:s');
		$track['trk_source'] = '_op';
		$this->Manuscript_model->tracking(array_filter($track));

		if($status > 4){ // accept/reject/revise action
			// add exisiting email as cc
			if(count($email_user_group) > 0){
				$user_group_emails = array();
				foreach($email_user_group as $grp){
					$username = $this->Email_model->get_user_group_emails($grp);
					array_push($user_group_emails, $username);
				}
			}
	
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

	public function cluster_review_process(){
		
		$man_id = $this->input->post('id', TRUE);
		$remarks = $this->input->post('remarks', TRUE);
		$status = $this->input->post('status', TRUE);
		
		// email config
		// $link_to = "https://researchjournal.nrcp.dost.gov.ph/oprs/login";
		$link_to = base_url() . 'oprs/login';
		$sender = 'eReview';
		$sender_email = 'nrcp.ejournal@gmail.com';
		$password = 'fpzskheyxltsbvtg';

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

		if($status == 14){ // reject
			$track['trk_description'] = 'Rejected';
			$track['trk_remarks'] = $remarks;

			// send email 4
			// update manuscript status 
			$manus['man_status'] = $status;
			$where['row_id'] = $man_id;
			$this->Manuscript_model->update_manuscript_status(array_filter($manus), $where);

			// get author info
			$output = $this->Review_model->get_manus_author_info($man_id);
			foreach ($output as $key => $value) {
				$manuscript = $value->man_title;
				$title = $value->man_author_title;
				$author = $value->man_author;
				$email = $value->man_email;
			}

			// get email notification content
			$email_contents = $this->Email_model->get_email_content(4);

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
			
			$emailBody = str_replace('[FULL NAME]', $author, $email_contents);
			$emailBody = str_replace('[TITLE]', $title, $emailBody);
			$emailBody = str_replace('[MANUSCRIPT]', $manuscript, $emailBody);

			$mail->AddAddress($email);

		}else if($status == 10){ // revise

			$track['trk_description'] = 'For Revision';
			$track['trk_remarks'] = $remarks;

			// send email 13
			// update manuscript status
			$manus['man_status'] = $status;
			$where['row_id'] = $man_id;
			$this->Manuscript_model->update_manuscript_status(array_filter($manus), $where);
			
			// get author info
			$output = $this->Review_model->get_manus_author_info($man_id);
			foreach ($output as $key => $value) {
				$manuscript = $value->man_title;
				$title = $value->man_author_title;
				$author = $value->man_author;
				$email = $value->man_email;
			}

			// get email notification content
			$email_contents = $this->Email_model->get_email_content(13);
			
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

			$link = "<a href='" . $link_to ."' target='_blank' style='cursor:pointer;'>
			https://researchjournal.nrcp.dost.gov.ph</a>";
			$emailBody = str_replace('[LINK]', $link, $email_contents);
			$emailBody = str_replace('[FULL NAME]', $author, $emailBody);
			$emailBody = str_replace('[TITLE]', $title, $emailBody);
			$emailBody = str_replace('[MANUSCRIPT]', $manuscript, $emailBody);

			$mail->AddAddress($email);
		}else if($status == 15){ // accepted
			$track['trk_description'] = 'Accepted';
			$track['trk_remarks'] = $remarks;

			// send email 24
			// update manuscript status
			$manus['man_status'] = $status;
			$where['row_id'] = $man_id;
			$this->Manuscript_model->update_manuscript_status(array_filter($manus), $where);
			
			// get author info
			$output = $this->Review_model->get_manus_author_info($man_id);
			foreach ($output as $key => $value) {
				$manuscript = $value->man_title;
				$title = $value->man_author_title;
				$author = $value->man_author;
				$email = $value->man_email;
			}

			// get email notification content
			$email_contents = $this->Email_model->get_email_content(24);
			
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

			$link = "<a href='" . $link_to ."' target='_blank' style='cursor:pointer;'>
			https://researchjournal.nrcp.dost.gov.ph</a>";
			$emailBody = str_replace('[LINK]', $link, $email_contents);
			$emailBody = str_replace('[MANUSCRIPT]', $manuscript, $emailBody);

			$next_processor_info = $this->User_model->get_processor_by_role(5);

			foreach ($next_processor_info as $row) {
				$next_processor_email = $row->usr_username;
			}

			$mail->AddAddress($next_processor_email);
		}else{ // add peer reviwers
			$track['trk_description'] = 'Suggested Peer Reviewers';
			$track['trk_remarks'] = $remarks;

			// send email 23
			// update manuscript status
			$status = 15;
			$manus['man_status'] = $status;
			$where['row_id'] = $man_id;
			$output = $this->Manuscript_model->update_manuscript_status(array_filter($manus), $where);
			
			// get author info
			$output = $this->Review_model->get_manus_author_info($man_id);
			foreach ($output as $key => $value) {
				$manuscript = $value->man_title;
				$title = $value->man_author_title;
				$author = $value->man_author;
				$email = $value->man_email;
			}

			$serializedData = $this->input->post('suggested_peer', true);

			// Parse the serialized form data into an associative array
			$suggestedPeer = [];
			parse_str($serializedData, $suggestedPeer);
			
			$peer_title = $suggestedPeer['suggested_peer_rev_title'];
			$peer_rev = $suggestedPeer['suggested_peer_rev'];
			$peer_email = $suggestedPeer['suggested_peer_rev_email'];
			$peer_num = $suggestedPeer['suggested_peer_rev_num'];
			$peer_spec = $suggestedPeer['suggested_peer_rev_spec'];
			$peer_id = $suggestedPeer['suggested_peer_rev_id'];
	
			$peers = array();
	
			for ($i = 0; $i < count($peer_rev); $i++) {
				$peers['peer_title'] = $peer_title[$i];
				$peers['peer_name'] = $peer_rev[$i];
				$peers['peer_email'] = $peer_email[$i];
				$peers['peer_contact'] = $peer_num[$i];
				$peers['peer_specialization'] = $peer_spec[$i];
				$peers['peer_usr_id'] = $peer_id[$i];
				$peers['peer_type'] = $peer_id[$i] ? 'Member' : 'Non-member';
				$peers['peer_man_id'] = $man_id;
				$peers['peer_clued_usr_id'] = _UserIdFromSession();
				$peers['date_created'] = date('Y-m-d H:i:s');
				$this->Review_model->save_peer_reviewers(array_filter($peers));
			}

			// get email notification content
			$email_contents = $this->Email_model->get_email_content(24);

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
						
			$link = "<a href='" . $link_to ."' target='_blank' style='cursor:pointer;'>
			https://researchjournal.nrcp.dost.gov.ph</a>";
			$emailBody = str_replace('[LINK]', $link, $email_contents);
			$emailBody = str_replace('[MANUSCRIPT]', $manuscript, $emailBody);

			
			$next_processor_info = $this->User_model->get_processor_by_role(5);

			foreach ($next_processor_info as $row) {
				$next_processor_email = $row->usr_username;
			}

			$mail->AddAddress($next_processor_email);
		}

		// update editors review data status
		$where_editorial_data['edit_man_id'] = $man_id;
		$where_editorial_data['edit_usr_id'] = _UserIdFromSession();
		$update_editorial_data['edit_status'] = $status;
		$update_editorial_data['edit_remarks'] = $remarks;
        $update_editorial_data['last_updated'] = date('Y-m-d H:i:s');

		$this->Review_model->update_editor_data($update_editorial_data, $where_editorial_data);

		// save tracking
		$track['trk_man_id'] = $man_id;
		$track['trk_processor'] = _UserIdFromSession();
		$track['trk_process_datetime'] = date('Y-m-d H:i:s');
		$track['trk_source'] = '_op';
		$this->Manuscript_model->tracking(array_filter($track));

		// add exisiting email as cc
		if(count($email_user_group) > 0){
			$user_group_emails = array();
			foreach($email_user_group as $grp){
				$username = $this->Email_model->get_user_group_emails($grp);
				array_push($user_group_emails, $username);
			}
		}

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

	public function get_editors_review($id){
		$output = $this->Review_model->get_editors_review($id);
		echo json_encode($output);
	}

	public function get_last_editors_review($id){
		$output = $this->Review_model->get_last_editors_review($id);
		echo json_encode($output);
	}

	public function get_suggested_peer($id){
		$output = $this->Review_model->get_suggested_peer($id);
		echo json_encode($output);
	}
	
	public function get_consolidation($man_id){
		$output = $this->Review_model->get_consolidation($man_id);
		echo json_encode($output);
	}

	public function author_revision(){
		$man_id = $this->input->post('man_id', TRUE);
		$man_pages = $this->input->post('man_pages', TRUE);
		$revision_status = $this->input->post('revision_status', TRUE);
		$criteria_status = $this->input->post('criteria_status', TRUE);
		$editor_review_status = $this->input->post('editor_review_status', TRUE);

		// udpate manuscript, status
		$post = array();
		$post['man_pages'] = $man_pages;
		$post['man_status'] = ($revision_status == 2) ? 6 : (($criteria_status == 2  || $editor_review_status == 10) ? 1 : 8);
		$post['man_revision_status'] = ($revision_status == 2) ? 1 : (($criteria_status == 2  || $editor_review_status == 10) ? 1 : 0);

		// full manuscript
		$file_string_man = str_replace(" ", "_", $_FILES['man_file']['name']);
		$file_no_ext_man = preg_replace("/\.[^.	]+$/", "", $file_string_man);
		$clean_file_man = preg_replace('/[^A-Za-z0-9\-]/', '_', $file_no_ext_man);

		$filename_man = $_FILES["man_file"]["name"];
		$file_ext_man = pathinfo($filename_man, PATHINFO_EXTENSION);

		$post['man_file'] = date('YmdHis') . '_' . $clean_file_man . '.' . $file_ext_man;
		$upload_file_man = $post['man_file'];

		// abstract
		$file_string_abs = str_replace(" ", "_", $_FILES['man_abs']['name']);
		$file_no_ext_abs = preg_replace("/\.[^.]+$/", "", $file_string_abs);
		$clean_file_abs = preg_replace('/[^A-Za-z0-9\-]/', '_', $file_no_ext_abs);

		$filename_abs = $_FILES["man_abs"]["name"];
		$file_ext_abs = pathinfo($filename_abs, PATHINFO_EXTENSION);

		$post['man_abs'] = date('YmdHis') . '_' . $clean_file_abs . '.' . $file_ext_abs;
		$upload_file_abs = $post['man_abs'];

		// word
		$file_string_word = str_replace(" ", "_", $_FILES['man_word']['name']);
		$file_no_ext_word = preg_replace("/\.[^.]+$/", "", $file_string_word);
		$clean_file_word = preg_replace('/[^A-Za-z0-9\-]/', '_', $file_no_ext_word);

		$filename_word = $_FILES["man_word"]["name"];
		$file_ext_word = pathinfo($filename_word, PATHINFO_EXTENSION);

		$post['man_word'] = date('YmdHis') . '_' . $clean_file_word . '.' . $file_ext_word;
		$upload_file_word = $post['man_word'];

		// latex
		if($_FILES['man_latex']['name'] != ''){
			$file_string_latex = str_replace(" ", "_", $_FILES['man_latex']['name']);
			$file_no_ext_latex = preg_replace("/\.[^.]+$/", "", $file_string_latex);
			$clean_file_latex = preg_replace('/[^A-Za-z0-9\-]/', '_', $file_no_ext_latex);

			$filename_latex = $_FILES["man_latex"]["name"];
			$file_ext_latex = pathinfo($filename_latex, PATHINFO_EXTENSION);

			$post['man_latex'] = date('YmdHis') . '_' . $clean_file_latex . '.' . $file_ext_latex;
			$upload_file_latex = $post['man_latex'];
		}

		$post['last_updated'] = date('Y-m-d H:i:s');
		$where['row_id'] = $man_id;
		$this->Manuscript_model->update_manuscript_status(array_filter($post), $where);


		if($revision_status == 2 || $criteria_status == 2 || $editor_review_status == 10){ // pre-final revision
			$dir_man = $_SERVER['DOCUMENT_ROOT'] . '/ejournal/assets/oprs/uploads/revised_manuscripts_pdf/';
			// server
			// $dir_man = '/var/www/html/ejournal/assets/oprs/uploads/revised_manuscripts_pdf/';
		}else{
			// final revision
			$dir_man = $_SERVER['DOCUMENT_ROOT'] . '/ejournal/assets/oprs/uploads/final_manuscripts_pdf/';
			// server
			// $dir_man = '/var/www/html/ejournal/assets/oprs/uploads/final_manuscripts_pdf/';
		}
	
		// upload full manuscript
		$config_man['upload_path'] = $dir_man;
		$config_man['allowed_types'] = 'pdf';
		$config_man['file_name'] = $upload_file_man;

		$this->load->library('upload', $config_man);
		$this->upload->initialize($config_man);

		if (!$this->upload->do_upload('man_file')) {
			$error = $this->upload->display_errors();
		} else {
			$data = $this->upload->data();
		}

		if($revision_status == 2 || $criteria_status == 2 || $editor_review_status == 10){ // pre-final revision
			$dir_abs = $_SERVER['DOCUMENT_ROOT'] . '/ejournal/assets/oprs/uploads/revised_abstracts_pdf/';
			// server
			// $dir_abs = '/var/www/html/ejournal/assets/oprs/uploads/revised_abstracts_pdf/';
		}else{
			// final revision
			$dir_abs = $_SERVER['DOCUMENT_ROOT'] . '/ejournal/assets/oprs/uploads/final_abstracts_pdf/';
			// server
			// $dir_abs = '/var/www/html/ejournal/assets/oprs/uploads/final_abstracts_pdf/';
		}
	
		// upload full manuscript
		$config_abs['upload_path'] = $dir_abs;
		$config_abs['allowed_types'] = 'pdf';
		$config_abs['file_name'] = $upload_file_abs;

		$this->load->library('upload', $config_abs);
		$this->upload->initialize($config_abs);

		if (!$this->upload->do_upload('man_abs')) {
			$error = $this->upload->display_errors();
		} else {
			$data = $this->upload->data();
		}
		
		if($revision_status == 2 || $criteria_status == 2 || $editor_review_status == 10){ // pre-final revision
			$dir_word = $_SERVER['DOCUMENT_ROOT'] . '/ejournal/assets/oprs/uploads/revised_manuscripts_word/';
			// server
			// $dir_word = '/var/www/html/ejournal/assets/oprs/uploads/revised_manuscripts_word/';
		}else{
			// final revision
			$dir_word = $_SERVER['DOCUMENT_ROOT'] . '/ejournal/assets/oprs/uploads/final_manuscripts_word/';
			// server
			// $dir_word = '/var/www/html/ejournal/assets/oprs/uploads/final_manuscripts_word/';
		}
	
		// upload full manuscript word
		$config_word['upload_path'] = $dir_word;
		$config_word['allowed_types'] = 'doc|docx';
		$config_word['file_name'] = $upload_file_word;

		$this->load->library('upload', $config_word);
		$this->upload->initialize($config_word);

		if (!$this->upload->do_upload('man_word')) {
			$error = $this->upload->display_errors(); 
		} else {
			$data = $this->upload->data();
		}
		
		if($revision_status == 2 || $criteria_status == 2 || $editor_review_status == 10){ // pre-final revision
			$dir_latex = $_SERVER['DOCUMENT_ROOT'] . '/ejournal/assets/oprs/uploads/revised_latex/';
			// server
			// $dir_latex = '/var/www/html/ejournal/assets/oprs/uploads/revised_latex/';
		}else{
			// final revision
			$dir_latex = $_SERVER['DOCUMENT_ROOT'] . '/ejournal/assets/oprs/uploads/final_latex/';
			// server
			// $dir_latex = '/var/www/html/ejournal/assets/oprs/uploads/final_latex/';
		}
	
		if(isset($upload_file_latex)){
			// upload full manuscript latex
			$config_latex['upload_path'] = $dir_latex;
			$config_latex['allowed_types'] = 'tex';
			$config_latex['file_name'] = $upload_file_latex;

			$this->load->library('upload', $config_latex);
			$this->upload->initialize($config_latex);

			if (!$this->upload->do_upload('man_latex')) {
				$error = $this->upload->display_errors(); 
			} else {
				$data = $this->upload->data();
			}
		}

		if($revision_status == 2){
			// add uploaded matrix
			$post = array();
	
			// revision matrix
			$file_string_mtx = str_replace(" ", "_", $_FILES['man_matrix']['name']);
			$file_no_ext_mtx = preg_replace("/\.[^.]+$/", "", $file_string_mtx);
			$clean_file_mtx = preg_replace('/[^A-Za-z0-9\-]/', '_', $file_no_ext_mtx);
	
			$filename_mtx = $_FILES["man_matrix"]["name"];
			$file_ext_mtx = pathinfo($filename_mtx, PATHINFO_EXTENSION);
	
			$post['mtx_file'] = date('YmdHis') . '_' . $clean_file_mtx . '.' . $file_ext_mtx;
			$upload_file_mtx = $post['mtx_file'];
	
			$dir_mtx = $_SERVER['DOCUMENT_ROOT'] . '/ejournal/assets/oprs/uploads/revision_matrix/';
			// server
			// $dir_mtx = '/var/www/html/ejournal/assets/oprs/uploads/revision_matrix/';
		
			// upload full mtxuscript
			$config_mtx['upload_path'] = $dir_mtx;
			$config_mtx['allowed_types'] = 'pdf';
			$config_mtx['file_name'] = $upload_file_mtx;
	
			$this->load->library('upload', $config_mtx);
			$this->upload->initialize($config_mtx);
	
			if (!$this->upload->do_upload('man_matrix')) {
				$error = $this->upload->display_errors();
			} else {
				$data = $this->upload->data();
			}

			$post['mtx_man_id'] = $man_id;
			$post['mtx_usr_id'] = _UserIdFromSession();
			$post['date_created'] = date('Y-m-d H:i:s');
			$this->Review_model->save_revision_matrix(array_filter($post));
		}


		// save tracking
		$track['trk_man_id'] = $man_id;
		$track['trk_description'] = ($revision_status == 2 || $criteria_status == 2 || $editor_review_status == 10) ? 'Uploaded revision' : 'Uploaded final revision';
		$track['trk_processor'] = _UserIdFromSession();
		$track['trk_process_datetime'] = date('Y-m-d H:i:s');
		$this->Manuscript_model->tracking(array_filter($track));

		// email config
		// $link_to = "https://researchjournal.nrcp.dost.gov.ph/oprs/login";
		$link_to = base_url() . 'oprs/login';
		$sender = 'eReview';
		$sender_email = 'nrcp.ejournal@gmail.com';
		$password = 'fpzskheyxltsbvtg';

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

		$output = $this->Review_model->get_manus_author_info($man_id);
	
		foreach ($output as $key => $value) {
			$manuscript = $value->man_title;
			$title = $value->man_author_title;
			$author = $value->man_author;
			$email = $value->man_email;
		}

		if($revision_status == 2 || $criteria_status == 2){
			$next_processor_info = $this->User_model->get_processor_by_role(5);
			// get email notification content
			$email_contents = $this->Email_model->get_email_content(14);
		}else{
			$next_processor_info = $this->User_model->get_processor_by_role(6);
			// get email notification content
			$email_contents = $this->Email_model->get_email_content(15);
		}
	
		foreach ($next_processor_info as $row) {
			$next_processor_email = $row->usr_username;
		}

		$mail->AddAddress($next_processor_email);

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
		
		$link = "<a href='" . $link_to ."' target='_blank' style='cursor:pointer;'>
		https://researchjournal.nrcp.dost.gov.ph</a>";
		$emailBody = str_replace('[LINK]', $link, $email_contents);
		$emailBody = str_replace('[MANUSCRIPT]', $manuscript, $emailBody);

		// add exisiting email as cc
		if(count($email_user_group) > 0){
			$user_group_emails = array();
			foreach($email_user_group as $grp){
				$username = $this->Email_model->get_user_group_emails($grp);
				array_push($user_group_emails, $username);
			}
		}

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

	public function get_revision_matrix($id){
		$output = $this->Review_model->get_revision_matrix($id);
		echo json_encode($output);
	}

	/**
	 * Technical desk editor submits consolidation to EIC if no revision, to author if for revision
	 *
	 * @return void
	 */
	public function check_revision(){
			
		$man_id = $this->input->post('cons_man_id', true);
		$status = $this->input->post('cons_check_revise', TRUE);
		$post = array(); $where = array();

		$where['cons_man_id'] = $man_id; 
		$where['cons_usr_id'] = _UserIdFromSession(); 
		$post['cons_remarks'] = $this->input->post('cons_remarks', true);
		$post['cons_status'] = $status;
		$post['last_updated'] = date('Y-m-d H:i:s');

		$this->Review_model->update_consolidations(array_filter($post), $where);

		// email config
		// $link_to = "https://researchjournal.nrcp.dost.gov.ph/oprs/login";
		$link_to = base_url() . 'oprs/login';
		$sender = 'eReview';
		$sender_email = 'nrcp.ejournal@gmail.com';
		$password = 'fpzskheyxltsbvtg';

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
		
		// get manuscript info
		$output = $this->Review_model->get_manus_author_info($man_id);

		foreach ($output as $key => $value) {
			$manuscript = $value->man_title;
			$title = $value->man_author_title;
			$author = $value->man_author;
			$email = $value->man_email;
		}
		
		$where = array();

		// email notification condition
		if($status == 1){ // for revision
			$man['man_status'] = 10;
			$track['trk_description'] = 'For Revision';
			$email_contents = $this->Email_model->get_email_content(13);

			$mail->AddAddress($email);
		}else{ // no revision
			$man['man_status'] = 7;
			$track['trk_description'] = 'For Proofread';
			$email_contents = $this->Email_model->get_email_content(12);

			$next_processor_info = $this->User_model->get_processor_by_role(17);

			foreach ($next_processor_info as $row) {
				$next_processor_user_id = $row->usr_id;
				$next_processor_email = $row->usr_username;
			}

			$mail->AddAddress($next_processor_email);
		}

		// update manuscript status
		$man['last_updated'] = date('Y-m-d H:i:s');
		$where['row_id'] = $man_id;
		$this->Manuscript_model->update_manuscript_status(array_filter($man), $where);

		// save tracking
		$track['trk_man_id'] = $man_id;
		$track['trk_processor'] = _UserIdFromSession();
		$track['trk_process_datetime'] = date('Y-m-d H:i:s');
		$this->Manuscript_model->tracking(array_filter($track));


		// email config
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

		if($status == 1){ // for revision
			$link = "<a href='" . $link_to ."' target='_blank' style='cursor:pointer;'>
			https://researchjournal.nrcp.dost.gov.ph</a>";
			$emailBody = str_replace('[LINK]', $link, $email_contents);
			$emailBody = str_replace('[FULL NAME]', $author, $emailBody);
			$emailBody = str_replace('[TITLE]', $title, $emailBody);
			$emailBody = str_replace('[MANUSCRIPT]', $manuscript, $emailBody);
			

		}else{ // no revision
			$link = "<a href='" . $link_to ."' target='_blank' style='cursor:pointer;'>
			https://researchjournal.nrcp.dost.gov.ph</a>";
			$emailBody = str_replace('[LINK]', $link, $email_contents);
			$emailBody = str_replace('[MANUSCRIPT]', $manuscript, $emailBody);
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

	public function submit_coped_process(){

		// save process
		$man_id = $this->input->post('coped_man_id', TRUE);
		$remarks = $this->input->post('coped_remarks', TRUE);
		$post = array();

		$post['cons_man_id'] = $man_id; 
		$post['cons_usr_id'] = _UserIdFromSession(); 
		$post['cons_remarks'] = $remarks;
		$post['cons_status'] = 3; // for final revision
		$post['date_created'] = date('Y-m-d H:i:s');

		$file_string = str_replace(" ", "_", $_FILES['coped_file']['name']);
		$file_no_ext = preg_replace("/\.[^.]+$/", "", $file_string);
		$clean_file = preg_replace('/[^A-Za-z0-9\-]/', '_', $file_no_ext);
		$filename = $_FILES["coped_file"]["name"];
		$file_ext = pathinfo($filename, PATHINFO_EXTENSION);
		$post['cons_file'] = date('YmdHis') . '_' . $clean_file . '.' . $file_ext;
		$upload_file = $post['cons_file'];

		$config['upload_path'] = './assets/oprs/uploads/consolidations/';
		$config['allowed_types'] = '*';
		$config['file_name'] = $upload_file;
		$this->load->library('upload', $config);
		$this->upload->initialize($config);
		if (!$this->upload->do_upload('coped_file')) {
			$error = $this->upload->display_errors();
		} else {
			$data = $this->upload->data();
		}
		$this->Review_model->save_consolidations(array_filter($post));

		// update manuscript status
		$post = array(); $where = array();
		$post['man_status'] = 10;
		$where['row_id'] = $man_id;
		$this->Manuscript_model->update_manuscript_status(array_filter($post), $where);

		// save tracking
		$track['trk_man_id'] = $man_id;
		$track['trk_remarks'] = $remarks;
		$track['trk_description'] = 'Endorsed to Author for Proofreading/Revision';
		$track['trk_processor'] = _UserIdFromSession();
		$track['trk_process_datetime'] = date('Y-m-d H:i:s');
		$this->Manuscript_model->tracking(array_filter($track));

		// email config
		// $link_to = "https://researchjournal.nrcp.dost.gov.ph/oprs/login";
		$link_to = base_url() . 'oprs/login';
		$sender = 'eReview';
		$sender_email = 'nrcp.ejournal@gmail.com';
		$password = 'fpzskheyxltsbvtg';

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
		
		// get manuscript info
		$output = $this->Review_model->get_manus_author_info($man_id);

		foreach ($output as $key => $value) {
			$manuscript = $value->man_title;
			$title = $value->man_author_title;
			$author = $value->man_author;
			$email = $value->man_email;
		}
		
		$email_contents = $this->Email_model->get_email_content(13);

		$mail->AddAddress($email);

		// email config
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

		$link = "<a href='" . $link_to ."' target='_blank' style='cursor:pointer;'>
		https://researchjournal.nrcp.dost.gov.ph</a>";
		$emailBody = str_replace('[LINK]', $link, $email_contents);
		$emailBody = str_replace('[FULL NAME]', $author, $emailBody);
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

	public function submit_final_review(){
		// update manuscript status
		$man_id = $this->input->post('final_man_id', TRUE);
		$remarks = $this->input->post('final_remarks', TRUE);
		$post = array(); $where = array();

		$post['man_status'] = 11;
		$post['man_remarks'] = $remarks;
		$where['row_id'] = $man_id;
		$this->Manuscript_model->update_manuscript_status(array_filter($post), $where);

		// save tracking
		$track['trk_man_id'] = $man_id;
		$track['trk_description'] = 'Endorsed to Layout Artist';
		$track['trk_remarks'] = $remarks;
		$track['trk_processor'] = _UserIdFromSession();
		$track['trk_process_datetime'] = date('Y-m-d H:i:s');
		$this->Manuscript_model->tracking(array_filter($track));

		// email config
		// $link_to = "https://researchjournal.nrcp.dost.gov.ph/oprs/login";
		$link_to = base_url() . 'oprs/login';
		$sender = 'eReview';
		$sender_email = 'nrcp.ejournal@gmail.com';
		$password = 'fpzskheyxltsbvtg';

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
		
		// get manuscript info
		$output = $this->Review_model->get_manus_author_info($man_id);

		foreach ($output as $key => $value) {
			$manuscript = $value->man_title;
			$title = $value->man_author_title;
			$author = $value->man_author;
			$email = $value->man_email;
		}
		

		
		$next_processor_info = $this->User_model->get_processor_by_role(15);

		foreach ($next_processor_info as $row) {
			$next_processor_email = $row->usr_username;
		}

		$mail->AddAddress($next_processor_email);
		
		$email_contents = $this->Email_model->get_email_content(17);

		// email config
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

		$link = "<a href='" . $link_to ."' target='_blank' style='cursor:pointer;'>
		https://researchjournal.nrcp.dost.gov.ph</a>";
		$emailBody = str_replace('[TITLE]', $title, $email_contents);
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

	public function submit_layout(){
			
		$man_id = $this->input->post('lay_man_id', true);
		$remarks = $this->input->post('lay_remarks', true);
		$post = array(); $where = array();

		$post['lay_man_id'] = $man_id; 
		$post['lay_usr_id'] = _UserIdFromSession(); 
		$post['lay_remarks'] = $remarks;
		$post['date_created'] = date('Y-m-d H:i:s');

		$file_string = str_replace(" ", "_", $_FILES['lay_file']['name']);
		$file_no_ext = preg_replace("/\.[^.]+$/", "", $file_string);
		$clean_file = preg_replace('/[^A-Za-z0-9\-]/', '_', $file_no_ext);
		$filename = $_FILES["lay_file"]["name"];
		$file_ext = pathinfo($filename, PATHINFO_EXTENSION);
		$post['lay_file'] = date('YmdHis') . '_' . $clean_file . '.' . $file_ext;
		$upload_file = $post['lay_file'];

		$config['upload_path'] = './assets/oprs/uploads/layouts/';
		$config['allowed_types'] = '*';
		$config['file_name'] = $upload_file;
		$this->load->library('upload', $config);
		$this->upload->initialize($config);
		if (!$this->upload->do_upload('lay_file')) {
			$error = $this->upload->display_errors();
		} else {
			$data = $this->upload->data();
		}


		$this->Review_model->save_layouts(array_filter($post));

		// email config
		// $link_to = "https://researchjournal.nrcp.dost.gov.ph/oprs/login";
		$link_to = base_url() . 'oprs/login';
		$sender = 'eReview';
		$sender_email = 'nrcp.ejournal@gmail.com';
		$password = 'fpzskheyxltsbvtg';

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
		
		// get manuscript info
		$output = $this->Review_model->get_manus_author_info($man_id);

		foreach ($output as $key => $value) {
			$manuscript = $value->man_title;
			$title = $value->man_author_title;
			$author = $value->man_author;
			$email = $value->man_email;
		}

		$man['man_status'] = 9;
		$track['trk_description'] = 'For Author Proofread';
		$email_contents = $this->Email_model->get_email_content(16);

		$mail->AddAddress($email);

		// update manuscript status
		$man['last_updated'] = date('Y-m-d H:i:s');
		$where['row_id'] = $man_id;
		$this->Manuscript_model->update_manuscript_status(array_filter($man), $where);

		// save tracking
		$track['trk_man_id'] = $man_id;
		$track['trk_description'] = 'For Author Proofreading'; 
		$track['trk_processor'] = _UserIdFromSession();
		$track['trk_process_datetime'] = date('Y-m-d H:i:s');
		$this->Manuscript_model->tracking(array_filter($track));


		// email config
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

		$link = "<a href='" . $link_to ."' target='_blank' style='cursor:pointer;'>
		https://researchjournal.nrcp.dost.gov.ph</a>";
		$emailBody = str_replace('[LINK]', $link, $email_contents);
		$emailBody = str_replace('[FULL NAME]', $author, $emailBody);
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

	public function get_layout($man_id){
		$output = $this->Review_model->get_layout($man_id);
		echo json_encode($output);
	}

	public function submit_final_revision(){
		$man_id = $this->input->post('man_id', TRUE);
		$man_pages = $this->input->post('man_pages', TRUE);

		// udpate manuscript, status
		$post = array();
		$post['man_pages'] = $man_pages;
		$post['man_revision_status'] = 2;
		$post['man_status'] = 12;

		// full manuscript
		$file_string_man = str_replace(" ", "_", $_FILES['man_file']['name']);
		$file_no_ext_man = preg_replace("/\.[^.	]+$/", "", $file_string_man);
		$clean_file_man = preg_replace('/[^A-Za-z0-9\-]/', '_', $file_no_ext_man);

		$filename_man = $_FILES["man_file"]["name"];
		$file_ext_man = pathinfo($filename_man, PATHINFO_EXTENSION);

		$post['man_file'] = date('YmdHis') . '_' . $clean_file_man . '.' . $file_ext_man;
		$upload_file_man = $post['man_file'];

		// abstract
		$file_string_abs = str_replace(" ", "_", $_FILES['man_abs']['name']);
		$file_no_ext_abs = preg_replace("/\.[^.]+$/", "", $file_string_abs);
		$clean_file_abs = preg_replace('/[^A-Za-z0-9\-]/', '_', $file_no_ext_abs);

		$filename_abs = $_FILES["man_abs"]["name"];
		$file_ext_abs = pathinfo($filename_abs, PATHINFO_EXTENSION);

		$post['man_abs'] = date('YmdHis') . '_' . $clean_file_abs . '.' . $file_ext_abs;
		$upload_file_abs = $post['man_abs'];

		// word
		$file_string_word = str_replace(" ", "_", $_FILES['man_word']['name']);
		$file_no_ext_word = preg_replace("/\.[^.]+$/", "", $file_string_word);
		$clean_file_word = preg_replace('/[^A-Za-z0-9\-]/', '_', $file_no_ext_word);

		$filename_word = $_FILES["man_word"]["name"];
		$file_ext_word = pathinfo($filename_word, PATHINFO_EXTENSION);

		$post['man_word'] = date('YmdHis') . '_' . $clean_file_word . '.' . $file_ext_word;
		$upload_file_word = $post['man_word'];

		// latex
		$file_string_latex = str_replace(" ", "_", $_FILES['man_latex']['name']);
		$file_no_ext_latex = preg_replace("/\.[^.]+$/", "", $file_string_latex);
		$clean_file_latex = preg_replace('/[^A-Za-z0-9\-]/', '_', $file_no_ext_latex);

		$filename_latex = $_FILES["man_latex"]["name"];
		$file_ext_latex = pathinfo($filename_latex, PATHINFO_EXTENSION);

		$post['man_latex'] = date('YmdHis') . '_' . $clean_file_latex . '.' . $file_ext_latex;
		$upload_file_latex = $post['man_latex'];

		$post['last_updated'] = date('Y-m-d H:i:s');
		$where['row_id'] = $man_id;
		$this->Manuscript_model->update_manuscript_status(array_filter($post), $where);


		// final revision
		$dir_man = $_SERVER['DOCUMENT_ROOT'] . '/ejournal/assets/oprs/uploads/final_manuscripts_pdf/';
		// server
		// $dir_man = '/var/www/html/ejournal/assets/oprs/uploads/final_manuscripts_pdf/';
		
	
		// upload full manuscript
		$config_man['upload_path'] = $dir_man;
		$config_man['allowed_types'] = 'pdf';
		$config_man['file_name'] = $upload_file_man;

		$this->load->library('upload', $config_man);
		$this->upload->initialize($config_man);

		if (!$this->upload->do_upload('man_file')) {
			$error = $this->upload->display_errors();
		} else {
			$data = $this->upload->data();
		}

		// final revision
		$dir_abs = $_SERVER['DOCUMENT_ROOT'] . '/ejournal/assets/oprs/uploads/final_abstracts_pdf/';
		// server
		// $dir_abs = '/var/www/html/ejournal/assets/oprs/uploads/final_abstracts_pdf/'
	
		// upload full manuscript
		$config_abs['upload_path'] = $dir_abs;
		$config_abs['allowed_types'] = 'pdf';
		$config_abs['file_name'] = $upload_file_abs;

		$this->load->library('upload', $config_abs);
		$this->upload->initialize($config_abs);

		if (!$this->upload->do_upload('man_abs')) {
			$error = $this->upload->display_errors();
		} else {
			$data = $this->upload->data();
		}
	
		// final revision
		$dir_word = $_SERVER['DOCUMENT_ROOT'] . '/ejournal/assets/oprs/uploads/final_manuscripts_word/';
		// server
		// $dir_word = '/var/www/html/ejournal/assets/oprs/uploads/final_manuscripts_word/';
	
		// upload full manuscript word
		$config_word['upload_path'] = $dir_word;
		$config_word['allowed_types'] = 'doc|docx';
		$config_word['file_name'] = $upload_file_word;

		$this->load->library('upload', $config_word);
		$this->upload->initialize($config_word);

		if (!$this->upload->do_upload('man_word')) {
			$error = $this->upload->display_errors(); 
		} else {
			$data = $this->upload->data();
		}
		
	
		// final revision
		$dir_latex = $_SERVER['DOCUMENT_ROOT'] . '/ejournal/assets/oprs/uploads/final_latex/';
		// server
		// $dir_latex = '/var/www/html/ejournal/assets/oprs/uploads/final_latex/';
		
	
		// upload full manuscript latex
		$config_latex['upload_path'] = $dir_latex;
		$config_latex['allowed_types'] = 'tex';
		$config_latex['file_name'] = $upload_file_latex;

		$this->load->library('upload', $config_latex);
		$this->upload->initialize($config_latex);

		if (!$this->upload->do_upload('man_latex')) {
			$error = $this->upload->display_errors(); 
		} else {
			$data = $this->upload->data();
		}

	

		// save tracking
		$track['trk_man_id'] = $man_id;
		$track['trk_description'] = 'Uploaded final revision';
		$track['trk_processor'] = _UserIdFromSession();
		$track['trk_process_datetime'] = date('Y-m-d H:i:s');
		$this->Manuscript_model->tracking(array_filter($track));

		// email config
		// $link_to = "https://researchjournal.nrcp.dost.gov.ph/oprs/login";
		$link_to = base_url() . 'oprs/login';
		$sender = 'eReview';
		$sender_email = 'nrcp.ejournal@gmail.com';
		$password = 'fpzskheyxltsbvtg';

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

		$output = $this->Review_model->get_manus_author_info($man_id);
	
		foreach ($output as $key => $value) {
			$manuscript = $value->man_title;
			$title = $value->man_author_title;
			$author = $value->man_author;
			$email = $value->man_email;
		}

	
		$next_processor_info = $this->User_model->get_processor_by_role(6);
		
		foreach ($next_processor_info as $row) {
			$next_processor_email = $row->usr_username;
		}

		$mail->AddAddress($next_processor_email);
		
		// get email notification content
		$email_contents = $this->Email_model->get_email_content(15);

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
		
		$link = "<a href='" . $link_to ."' target='_blank' style='cursor:pointer;'>
		https://researchjournal.nrcp.dost.gov.ph</a>";
		$emailBody = str_replace('[LINK]', $link, $email_contents);
		$emailBody = str_replace('[MANUSCRIPT]', $manuscript, $emailBody);

		// add exisiting email as cc
		if(count($email_user_group) > 0){
			$user_group_emails = array();
			foreach($email_user_group as $grp){
				$username = $this->Email_model->get_user_group_emails($grp);
				array_push($user_group_emails, $username);
			}
		}

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
	 * Publish manuscripts
	 *
	 * @return  [type]  [return description]
	 */
	public function publish(){
		$pages = $this->input->post('man_page_position');
		$new_volume = $this->input->post('man_volume');
		$new_issue = $this->input->post('man_issue');
		$new_year = $this->input->post('man_year');
		$man_id = $this->input->post('pub_man_id');

		// get manus info
		$info = $this->Manuscript_model->get_manus_info($man_id);

		foreach ($info as $key => $value) {
			$volume = $value->man_volume;
			$issue = $value->man_issue;
			$year = $value->man_year;
			$title = $value->man_title;
			$author = $value->man_author;
			$email = $value->man_email;
			$aff = $value->man_affiliation;
			$file = $value->man_file;
			$abs = $value->man_abs;
			$keys = $value->man_keywords;
			$user_id = $value->man_user_id;
		}

		// server
		// $from_abs = '/var/www/html/ejournal/assets/oprs/uploads/final_abstracts_pdf/' . $abs;
		// $to_abs = '/var/www/html/ejournal/assets/uploads/abstract/' . $abs;
		// local
		$from_abs = $_SERVER['DOCUMENT_ROOT'] . '/ejournal/assets/oprs/uploads/final_abstracts_pdf/' . $abs;
		$to_abs = $_SERVER['DOCUMENT_ROOT'] . '/ejournal/assets/uploads/abstract/' . $abs;


		if (!copy($from_abs, $to_abs)) {
			echo "failed to copy $from_abs...\n";
		} else {
			echo "copied $from_abs into $to_abs\n";
		}

		// server
		// $from_pdf = '/var/www/html/ejournal/assets/oprs/uploads/final_manuscripts_pdf/' . $file;
		// $to_pdf = '/var/www/html/ejournal/assets/uploads/pdf/' . $file;
		// local
		$from_pdf = $_SERVER['DOCUMENT_ROOT'] . '/ejournal/assets/oprs/uploads/final_manuscripts_pdf/' . $abs;
		$to_pdf = $_SERVER['DOCUMENT_ROOT'] . '/ejournal/assets/uploads/pdf/' . $abs;

		if (!copy($from_pdf, $to_pdf)) {
			echo "failed to copy $from_abs...\n";
		} else {
			echo "copied $from_pdf into $to_pdf\n";
		}

		// get coauthors
		$coas = $this->Coauthor_model->get_manus_acoa($man_id);

		// check if journal exists
		$jor_id = $this->Manuscript_model->check_journal($volume, $issue);
		if ($jor_id > 0) {
			// if journal exist save as article
			$post_art = array();
			$post_art['art_title'] = $title;
			$post_art['art_author'] = $author;
			$post_art['art_affiliation'] = $aff;
			$post_art['art_email'] = $email;
			$post_art['art_abstract_file'] = $abs;
			$post_art['art_full_text_pdf'] = $file;
			$post_art['art_year'] = $new_year;
			$post_art['art_page'] = $pages;
			$post_art['art_keywords'] = $keys;
			$post_art['art_jor_id'] = $jor_id;
			$post_art['art_keywords'] = $keys;
			$post_art['art_usr_id'] = $user_id;
			$post_art['date_created'] = date('Y-m-d H:i:s');		
			$art_id = $this->Manuscript_model->add_article(array_filter($post_art));
		} else {
			// if journal not exist create journal
			$post_jor = array();
			$post_jor['jor_volume'] = $new_volume;
			$post_jor['jor_issue'] = $new_issue;
			$post_jor['jor_year'] = $new_year;
			$post_jor['jor_issn'] = '2980-4728'; //eissn
			$post_jor['jor_cover'] = 'unavailable.jpg';
			$post_jor['date_created'] = date('Y-m-d H:i:s');
			$jor_new_id = $this->Manuscript_model->create_journal(array_filter($post_jor));
			$post_art = array();
			$post_art['art_title'] = $title;
			$post_art['art_author'] = $author;
			$post_art['art_affiliation'] = $aff;
			$post_art['art_email'] = $email;
			$post_art['art_abstract_file'] = $abs;
			$post_art['art_full_text_pdf'] = $file;
			$post_art['art_keywords'] = $keys;
			$post_art['art_jor_id'] = $jor_new_id;
			$post_art['date_created'] = date('Y-m-d H:i:s');
			$post_art['art_year'] = $new_year;
			$post_art['art_page'] = $pages;		
			$post_art['art_usr_id'] = $user_id;
				// echo json_encode($post_art);exit;
			$art_id = $this->Manuscript_model->add_article(array_filter($post_art));
		}

		// add coauthors if any
		if (!empty($coas)) {
			$coa = array();
			foreach ($coas as $key => $val) {
				$coa['coa_name'] = $val->coa_name;
				$coa['coa_affiliation'] = $val->coa_affiliation;
				$coa['coa_email'] = $val->coa_email;
				$coa['coa_art_id'] = $art_id;
				$coa['date_created'] = date('Y-m-d H:i:s');
				$this->Manuscript_model->save_acoa(array_filter($coa));
			}
		}

		// update manuscript
		$post['man_status'] = 16;
		$post['man_page_position'] = $pages;
		$post['man_volume'] = $new_volume;
		$post['man_issue'] = $new_issue;
		$post['man_year'] = $new_year;
		$post['last_updated'] = date('Y-m-d H:i:s');
		$where['row_id'] = $man_id;
		// $this->Manuscript_model->process_manuscript(array_filter($post), $where, 3);
		$this->Manuscript_model->update_manuscript_status(array_filter($post), $where);
		
		// save tracking
		$track['trk_man_id'] = $man_id;
		$track['trk_processor'] = _UserIdFromSession();
		$track['trk_description'] = 'Published to eJournal';
		$track['trk_process_datetime'] = date('Y-m-d H:i:s');
		// $issue = (
		// 	($issue == 5) ? 'Special Issue No. 1' :
		// 	(($issue == 6) ? 'Special Issue No. 2' :
		// 		(($issue == 7) ? 'Special Issue No. 3' :
		// 			(($issue == 8) ? 'Special Issue No. 4' : 'Issue ' . $issue)))
		// );
		// $track['trk_remarks'] = 'Published to eJournal Volume ' . $volume . ', ' . $issue;
		$this->Manuscript_model->tracking(array_filter($track));


		// get manuscript info
		$output = $this->Review_model->get_manus_author_info($man_id);

		foreach ($output as $key => $value) {
			$manuscript = $value->man_title;
			$title = $value->man_author_title;
			$author = $value->man_author;
			$email = $value->man_email;
		}
		
		// send email to author
		$email_contents = $this->Email_model->get_email_content(19);

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

		// email config
		// $link_to = "https://researchjournal.nrcp.dost.gov.ph";
		$link_to = base_url();
		$sender = 'eReview';
		$sender_email = 'nrcp.ejournal@gmail.com';
		$password = 'fpzskheyxltsbvtg';

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
		
		$link = "<a href='" . $link_to ."' target='_blank' style='cursor:pointer;'>
		https://researchjournal.nrcp.dost.gov.ph</a>";
		$emailBody = str_replace('[LINK]', $link, $email_contents);
		$emailBody = str_replace('[FULL NAME]', $author, $emailBody);
		$emailBody = str_replace('[TITLE]', $title, $emailBody);
		$emailBody = str_replace('[MANUSCRIPT]', $manuscript, $emailBody);

		// add exisiting email as cc
		if(count($email_user_group) > 0){
			$user_group_emails = array();
			foreach($email_user_group as $grp){
				$username = $this->Email_model->get_user_group_emails($grp);
				array_push($user_group_emails, $username);
			}
		}


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

	function get_manuscripts_publication_status(){
		
        $from = $this->input->post('from', TRUE);
        $to = $this->input->post('to', TRUE); 
		$pub_id =  $this->input->post('pub_id');
		$man_status = $this->input->post('man_status', TRUE);
		$editor_type = $this->input->post('editor_type', TRUE);

		$output = $this->Manuscript_model->get_manuscripts_publication_status($pub_id, $man_status, $editor_type, $from, $to);
		echo json_encode($output);
	}

	function get_unique_journal(){
		$output = $this->Manuscript_model->get_unique_journal();
		echo json_encode($output);
	}

}

/* End of file Manuscripts.php */