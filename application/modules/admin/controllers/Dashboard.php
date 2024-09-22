<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

/**
 * File Name: Dashboard.php
 * ----------------------------------------------------------------------------------------------------
 * Purpose of this file:
 * To manage data to display in admin dashboard page
 * ----------------------------------------------------------------------------------------------------
 * System Name: Online Research Journal System
 * ----------------------------------------------------------------------------------------------------
 * Author: Gerard Paul D. Balde
 * ----------------------------------------------------------------------------------------------------
 * Date of revision: Sep 26, 2019
 * ----------------------------------------------------------------------------------------------------
 * Copyright Notice:
 * Copyright (C) 2018 by the Department of Science and Technology - National Research Council of the Philiipines
 */
class Dashboard extends EJ_Controller {

	public function __construct() {
		parent::__construct();

		if (!$this->session->userdata('_oprs_logged_in')) {
			redirect('oprs/login');
		}

		$this->load->model('Journal_model');
		$this->load->model('Article_model');
		$this->load->model('Editorial_model');
		$this->load->model('Library_model');
		$this->load->model('Client_model');
		$this->load->model('Dashboard_model');
		$this->load->model('Login_model');
		$this->load->model('Log_model');
		$this->load->model('oprs/User_model');
		$this->load->model('Coauthor_model');
		$this->load->model('Email_model');
		$this->load->helper('is_online_helper');
	}

	
	public function index() {

		if ($this->session->userdata('_oprs_logged_in')) {

			//session expiration and timeout
				//Start our session.
				//Expire the session if user is inactive for 15
				//minutes or more.
				$expireAfter = 15;

				//Check to see if our "last action" session
				//variable has been set.
				if (isset($_SESSION['last_action'])) {
					//Figure out how many seconds have passed
					//since the user was last active.
					$secondsInactive = time() - $_SESSION['last_action'];

					//Convert our minutes into seconds.
					$expireAfterSeconds = $expireAfter * 60;

					//Check to see if they have been inactive for too long.
					if ($secondsInactive >= $expireAfterSeconds) {
						//User has been inactive for too long.
						//Kill their session.
						// is_offline(_UserIdFromSession());
						// session_unset();
						$array_msg = array('icon' => 'oi-warning', 'class' => 'alert-info', 'msg' => 'Your session has expired.');
						$this->session->set_flashdata('_oprs_sess_expire_msg', $array_msg);
						redirect('oprs/login/logout');
					}
				}

				// Assign the current timestamp as the user's latest activity
				$_SESSION['last_action'] = time();
				
			
		if($this->session->userdata('sys_acc') == 1 || $this->session->userdata('sys_acc') == 3 ){


				$data['country'] = $this->Library_model->get_library('tblcountries');
				$data['sex'] = $this->Library_model->get_library('tblsex');
				$data['journal'] = $this->Journal_model->get_journals();
				$data['journal_max'] = $this->Journal_model->get_journal_max();
				$data['u_journal'] = $this->Journal_model->get_unique_journal();
				$data['u_year'] = $this->Journal_model->get_unique_journal_year();
				$data['client_count'] = $this->Client_model->client_count();
				$data['art_count'] = $this->Article_model->art_count();
				$data['edt_count'] = $this->Editorial_model->edt_count();
				$data['jor_count'] = $this->Journal_model->jor_count();
				$data['hit_count'] = $this->Journal_model->hit_count();
				$data['cite_count'] = $this->Journal_model->cite_count();
				$data['editorials'] = $this->Editorial_model->get_editorials();
				$data['clients'] = $this->Client_model->get_clients();
				$data['vis_count'] = $this->Dashboard_model->vis_count();
				$data['online'] = $this->Login_model->online_users(_UserIdFromSession());
				$data['popular'] = $this->Article_model->top_five();
				$data['logs'] = $this->Log_model->get_logs();
				$data['all_logs'] = $this->Log_model->get_all_logs();
				$data['vis_all'] = $this->Dashboard_model->vis_count_all();
				$data['viewers'] = $this->Dashboard_model->get_viewers();
				$data['articles'] = $this->Article_model->get_all_articles();
				$data['citees'] = $this->Article_model->get_all_citees();
				$data['tables'] = $this->Library_model->get_tables();
				$data['emails'] = $this->Email_model->get_contents();
				$data['user_roles'] = $this->Email_model->get_email_user_roles();

				$acoa_arr = explode(",& ", $this->Coauthor_model->get_author_coauthors_list());
				sort($acoa_arr, SORT_STRING);
				$data['authors'] = array_unique($acoa_arr);
				$data['main_title'] = "eJournal Administrator";
				$data['main_content'] = "admin/index";

				$this->_LoadPage('common/body', $data);
			} else {
				redirect('../../ejournal/oprs/dashboard');
			}
		}

	}

	/**
	 * Generate client sex chart
	 *
	 * @return void
	 */
	public function sex_chart(){
		$output = $this->Client_model->get_clients_graph();
		echo json_encode($output);
	}

	public function sex_line(){
		$output = $this->Client_model->get_clients_line_graph();
		echo json_encode($output);
	}

	public function sex_monthly_line(){
		$output = $this->Client_model->get_clients_monthly_line_graph();
		echo json_encode($output);
	}

	/**
	 * this function change password
	 *
	 * @return  void
	 */
	public function change_password() {
		$post['usr_password'] = password_hash($this->input->post('acc_password', TRUE), PASSWORD_BCRYPT);
		$post['last_updated'] = date('Y-m-d H:i:s');
		$where['usr_id'] = _UserIdFromSession();
		$this->User_model->change_password(array_filter($post), $where);
	}

	/**
	 * Count total numbers of citation
	 *
	 * @param   int	 $id	article id			
	 *
	 * @return  int       total count
	 */
	public function cite_count($id) {
		$output = $this->Article_model->count_citation($id);
		echo $output;
	}

	/**
	 * Manage notification
	 *
	 * @param   int	 $id	row id
	 *
	 * @return  array	notifications
	 */
	public function notifications($id = null) {
		if ($id > 0) {
			$notification = $this->Log_model->update_log($id);
		} else {
			$notification = $this->Log_model->get_all_logs_today();
			echo json_encode($notification);
		}
	}

	/**
	 * Retrieve journals
	 *
	 * @return  array  journals
	 */
	public function journal() {
		$data['country'] = $this->Library_model->get_library('tblcountries');
		$data['sex'] = $this->Library_model->get_library('tblsex');
		$data['journal'] = $this->Journal_model->get_journals();
		$data['journal_max'] = $this->Journal_model->get_journal_max();
		$data['u_journal'] = $this->Journal_model->get_unique_journal();
		$data['u_year'] = $this->Journal_model->get_unique_journal_year();
		$data['client_count'] = $this->Client_model->client_count();
		$data['art_count'] = $this->Article_model->art_count();
		$data['edt_count'] = $this->Editorial_model->edt_count();
		$data['jor_count'] = $this->Journal_model->jor_count();
		$data['hit_count'] = $this->Journal_model->hit_count();
		$data['cite_count'] = $this->Journal_model->cite_count();
		$data['editorials'] = $this->Editorial_model->get_editorials();
		$data['vis_count'] = $this->Dashboard_model->vis_count();
		$data['online'] = $this->Login_model->online_users(_UserIdFromSession());
		$data['popular'] = $this->Article_model->top_five();
		$data['logs'] = $this->Log_model->get_logs();
		$data['all_logs'] = $this->Log_model->get_all_logs();
		$data['vis_all'] = $this->Dashboard_model->vis_count_all();

		$volume = $this->input->post('jor_volume', TRUE);
		$issue = $this->input->post('jor_issue', TRUE);

		if ($this->Journal_model->check_journal($volume, $issue) > 0) {
			$notif['flag'] = 0;
			$notif['icon'] = 'oi oi-circle-x';
			$notif['msg'] = 'Journal already exists. Click <em>View Journals</em> to verify. ';
			echo json_encode($notif);
		} else {
			$tableName = 'tbljournals';
			$result = $this->db->list_fields($tableName);
			$post = array();

			foreach ($result as $i => $field) {
				if ($field != 'jor_id') {
					$post[$field] = $this->input->post($field, TRUE);

					if ($_FILES['jor_cover']['name'] != '') {
						//journal cover
						$file_string = str_replace(" ", "_", $_FILES['jor_cover']['name']);
						$file_no_ext = preg_replace("/\.[^.]+$/", "", $file_string);
						$clean_file = preg_replace('/[^A-Za-z0-9\-]/', '_', $file_no_ext);
						$post['jor_cover'] = date('YmdHis') . '_' . $clean_file . '.jpg';
						$upload_file = $post['jor_cover'];
					} else {
						$post['jor_cover'] = 'unavailable.jpg';
					}

				}
			}

			$post['date_created'] = date('Y-m-d H:i:s');

			if ($_FILES['jor_cover']['name'] != '') {
				// upload cover
				$config_abstract['upload_path'] = './assets/uploads/cover/';
				$config_abstract['allowed_types'] = 'jpg';
				$config_abstract['file_name'] = $upload_file;

				$this->load->library('upload', $config_abstract);
				$this->upload->initialize($config_abstract);

				if (!$this->upload->do_upload('jor_cover')) {
					$error = $this->upload->display_errors();
				} else {
					$data = $this->upload->data();
				}
			}

			$output = $this->Journal_model->save_journal(array_filter($post));
			$notif['flag'] = 1;
			$notif['icon'] = 'oi oi-circle-check';
			$notif['msg'] = 'Journal saved successfully. Page will reload in 3 seconds.';
			echo json_encode($notif);
		}
	}

	/**
	 * Retrieve articles 
	 *
	 * @return  array	articles
	 */
	public function article() {
		$data['country'] = $this->Library_model->get_library('tblcountries');
		$data['sex'] = $this->Library_model->get_library('tblsex');
		$data['journal'] = $this->Journal_model->get_journals();
		$data['journal_max'] = $this->Journal_model->get_journal_max();
		$data['u_journal'] = $this->Journal_model->get_unique_journal();
		$data['u_year'] = $this->Journal_model->get_unique_journal_year();
		$data['client_count'] = $this->Client_model->client_count();
		$data['art_count'] = $this->Article_model->art_count();
		$data['edt_count'] = $this->Editorial_model->edt_count();
		$data['jor_count'] = $this->Journal_model->jor_count();
		$data['hit_count'] = $this->Journal_model->hit_count();
		$data['cite_count'] = $this->Journal_model->cite_count();
		$data['editorials'] = $this->Editorial_model->get_editorials();
		$data['vis_count'] = $this->Dashboard_model->vis_count();
		$data['online'] = $this->Login_model->online_users(_UserIdFromSession());
		$data['popular'] = $this->Article_model->top_five();
		$data['logs'] = $this->Log_model->get_logs();
		$data['all_logs'] = $this->Log_model->get_all_logs();
		$data['vis_all'] = $this->Dashboard_model->vis_count_all();

		$tableName = 'tblarticles';
		$result = $this->db->list_fields($tableName);
		$post = array();

		foreach ($result as $i => $field) {
			if ($field != 'art_id') {
				$post[$field] = $this->input->post($field, TRUE);

				// abstract
				$file_string = str_replace(" ", "_", $_FILES['art_abstract_file']['name']);
				$file_no_ext = preg_replace("/\.[^.]+$/", "", $file_string);
				$clean_file = preg_replace('/[^A-Za-z0-9\-]/', '_', $file_no_ext);
				$post['art_abstract_file'] = date('YmdHis') . '_' . $clean_file . '.pdf';
				$upload_file = $post['art_abstract_file'];

				// full text pdf
				$file_string2 = str_replace(" ", "_", $_FILES['art_full_text_pdf']['name']);
				$file_no_ext2 = preg_replace("/\.[^.]+$/", "", $file_string2);
				$clean_file2 = preg_replace('/[^A-Za-z0-9\-]/', '_', $file_no_ext2);
				$post['art_full_text_pdf'] = date('YmdHis') . '_' . $clean_file2 . '.pdf';
				$upload_file2 = $post['art_full_text_pdf'];
			}
		}

		$post['date_created'] = date('Y-m-d H:i:s');
		$post['last_updated'] = '';

		// upload abstract
		$config_abstract['upload_path'] = './assets/uploads/abstract/';
		$config_abstract['allowed_types'] = 'pdf';
		$config_abstract['file_name'] = $upload_file;

		$this->load->library('upload', $config_abstract);
		$this->upload->initialize($config_abstract);

		if (!$this->upload->do_upload('art_abstract_file')) {
			$error = $this->upload->display_errors();
		} else {
			$data = $this->upload->data();
		}

		// upload full text pdf
		$config_pdf['upload_path'] = './assets/uploads/pdf/';
		$config_pdf['allowed_types'] = 'pdf';
		$config_pdf['file_name'] = $upload_file2;

		$this->load->library('upload', $config_pdf);
		$this->upload->initialize($config_pdf);

		if (!$this->upload->do_upload('art_full_text_pdf')) {
			$error = $this->upload->display_errors();
		} else {
			$data = $this->upload->data();
		}

		$output = $this->Article_model->save_article(array_filter($post));
		$jor_id = $this->db->insert_id();
		$coauthors = $this->input->post('coa_name', TRUE);
		$affiliations = $this->input->post('coa_affiliation', TRUE);
		$emails = $this->input->post('coa_email', TRUE);
		$coa = array();

		if ($coauthors != '') {
			for ($i = 0; $i < count($coauthors); $i++) {
				$coa['coa_name'] = $coauthors[$i];
				$coa['coa_affiliation'] = $affiliations[$i];
				$coa['coa_email'] = $emails[$i];
				$coa['coa_art_id'] = $output;
				$coa['date_created'] = date('Y-m-d H:i:s');
				$this->Article_model->save_coauthors($coa);
			}
		}

		$notif['icon'] = 'oi oi-circle-check';
		$notif['msg'] = 'Article added successfully. Page will reload in 3 seconds.';
		echo json_encode($notif);
	}

	// /**
	//  * Retrieve editorial boards
	//  *
	//  * @return  array  editorial boards
	//  */
	// public function editorial() {
	// 	// $data['country'] = $this->Library_model->get_library('tblcountries');
	// 	// $data['sex'] = $this->Library_model->get_library('tblsex');
	// 	// $data['journal'] = $this->Journal_model->get_journals();
	// 	// $data['journal_max'] = $this->Journal_model->get_journal_max();
	// 	// $data['u_journal'] = $this->Journal_model->get_unique_journal();
	// 	// $data['u_year'] = $this->Journal_model->get_unique_journal_year();
	// 	// $data['client_count'] = $this->Client_model->client_count();
	// 	// $data['art_count'] = $this->Article_model->art_count();
	// 	// $data['edt_count'] = $this->Editorial_model->edt_count();
	// 	// $data['jor_count'] = $this->Journal_model->jor_count();
	// 	// $data['hit_count'] = $this->Journal_model->hit_count();
	// 	// $data['cite_count'] = $this->Journal_model->cite_count();
	// 	// $data['editorials'] = $this->Editorial_model->get_editorials();
	// 	// $data['vis_count'] = $this->Dashboard_model->vis_count();
	// 	// $data['online'] = $this->Login_model->online_users(_UserIdFromSession());
	// 	// $data['popular'] = $this->Article_model->top_five();
	// 	// $data['logs'] = $this->Log_model->get_logs();
	// 	// $data['all_logs'] = $this->Log_model->get_all_logs();
	// 	// $data['vis_all'] = $this->Dashboard_model->vis_count_all();

		

	// }
	
	 /**
	 *  Retrieve and display total number of visitors
	 *
	 * @return  int	total number of visitors
	 */
	public function visitor() {
		$output = $this->Dashboard_model->get_visitors();
		echo json_encode($output);
	}

	/**
	 * Retrieve authors
	 *
	 * @param   string  $data  author or co-author name
	 *
	 * @return  array	author/co-author 
	 */
	public function authors_articles($data) {
		
		$output = $this->Article_model->get_author_coa($data);
		echo json_encode($output);
	}

	/**
	 * Update uploaded guidelines (pdf)
	 *
	 * @return  void
	 */
	public function update_guidelines() {

		// upload guidelines
		$config_pdf['upload_path'] = './assets/uploads/';
		$config_pdf['allowed_types'] = 'pdf';
		$config_pdf['overwrite'] = TRUE;
		$config_pdf['file_name'] = 'DO_NOT_DELETE_guidelines';

		$this->load->library('upload', $config_pdf);
		$this->upload->initialize($config_pdf);

		if (!$this->upload->do_upload('upload_guidelines')) {
			$error = $this->upload->display_errors();
		} else {
			$data = $this->upload->data();
		}

		save_log_ej(_UserIdFromSession(), 'updated author\'s guidelines.', '0', _UserRoleFromSession());
	}

	/**
	 * Update description of call for papers
	 *
	 * @return  void
	 */
	public function update_home() {
		$data = $this->input->post('home_description', TRUE);
		$filetype = $this->input->post('upload_only', TRUE);

		if (!write_file('./assets/uploads/DO_NOT_DELETE_description.txt', $data, 'wbr+')) {
			echo 'Unable to write the file';
		} else {
			echo 'File written!';
		}

		if ($filetype == 1) {
			$filepath = "assets/uploads/";
			@unlink($filepath . 'DO_NOT_DELETE_callforpapers.jpg');
			$upload = 'upload_cfp';
		} else if ($filetype == 2) {
			$filepath = "assets/uploads/";
			@unlink($filepath . 'DO_NOT_DELETE_callforpapers.pdf');
			$upload = 'upload_cfpi';
		}

		// upload call for papers
		$config_pdf['upload_path'] = './assets/uploads/';
		$config_pdf['allowed_types'] = 'pdf|jpg';
		$config_pdf['overwrite'] = TRUE;
		$config_pdf['file_name'] = 'DO_NOT_DELETE_callforpapers';

		$this->load->library('upload', $config_pdf);
		$this->upload->initialize($config_pdf);

		if (!$this->upload->do_upload($upload)) {
			$error = $this->upload->display_errors();
		} else {
			$data = $this->upload->data();
		}

		save_log_ej(_UserIdFromSession(), 'updated author\'s home description and call for papers.', '0', _UserRoleFromSession());
	}

	/**
	 * Manage admin user display picture
	 *
	 * @return  void
	 */
	public function upload_display_picture() {
		/** UPLOAD DISPLAY PICTURE */
		// $config_pdf['upload_path'] = './assets/uploads/dp';
		// $config_pdf['allowed_types'] = 'gif|jpg|png';
		// $config_pdf['overwrite'] = TRUE;
		// $config_pdf['file_name'] = 'dp_' . $this->session->userdata('user_id') . '_' . $_FILES['acc_dp']['name'];

		// $this->load->library('upload', $config_pdf);
		// $this->upload->initialize($config_pdf);

		// if (!$this->upload->do_upload('acc_dp')) {
		// 	$error = $this->upload->display_errors();
		// } else {
		// 	$data = $this->upload->data();
		// 	$post['acc_dp'] = $config_pdf['file_name'];
		// 	$post['last_updated'] = date('Y-m-d H:i:s');
		// 	$where['row_id'] = $this->session->userdata('user_id');
		// 	$this->User_model->upload_dp(array_filter($post), $where);
		// }

		// $this->session->set_userdata('user_dp', $config_pdf['file_name']);
		// redirect('admin/dashboard');
	}

	/**
	 * get author/co-author articles from registry
	 *
	 * @param   string  $data  author name or keyword
	 * @param   string  $flag  
	 *
	 * @return  array	author/co-author
	 */
	public function reg_list($data, $flag) {
		if ($flag == 'aut') {
			$output = $this->Article_model->get_authors_articles($data);
			echo json_encode($output);
		} else {
			$output = $this->Article_model->get_coauthors_articles($data);
			echo json_encode($output);
		}
	}

	/**
	 * Send and get messages from users
	 *
	 * @param   string  $func  semd or get message
	 * @param   int  $rcvr  receiver user id
	 * @param   string  $msg   message content
	 *
	 * @return  array	message data
	 */
	public function message($func, $rcvr = null, $msg = null) {
		if ($func == 'send') {
			$post['msg_content'] = rawurldecode($msg);
			$post['msg_sender'] = _UserIdFromSession();
			$post['msg_receiver'] = $rcvr;
			$post['date_created'] = date('Y-m-d H:i:s');
			$this->User_model->store_message(array_filter($post));
		} else if ($func == 'get') {
			$output = $this->User_model->get_messages($rcvr);
			echo json_encode($output);

			$data['msg_notif'] = 0;
			$where['msg_sender'] = $rcvr;
			$where['msg_receiver'] = _UserIdFromSession();
			$this->User_model->update_new_message_counter($data, $where);
		}
	}

	/**
	 * Retrieve new messages
	 *
	 * @return  array	message data
	 */
	public function new_messages() {
		$output = $this->User_model->get_new_messages();
		echo json_encode($output);
	}

	/**
	 * Count new messages
	 *
	 * @return  int		number of new messages
	 */
	public function count_new_messages() {
		$output = $this->User_model->count_new_messages();
		echo $output;
	}

	/**
	 * Retrieve list of author and co-authors
	 *
	 * @return  array	author and co-authors
	 */
	public function get_list_author_coa() {
		$acoa_arr = explode(",& ", $this->Coauthor_model->get_author_coauthors_list());
		sort($acoa_arr, SORT_STRING);
		echo json_encode(array_unique($acoa_arr));
	}

	/**
	 *Save log on exporting data
	 *
	 * @param   string  $log  action taken
	 *
	 * @return  void
	 */
	public function log_export($log) {
		$data['log_user_id'] = _UserIdFromSession();
		$data['log_action'] = rawurldecode($log);
		$data['date_created'] = date('Y-m-d H:i:s');
		$this->Log_model->save_log_export(array_filter($data));
	}

}

/* End of file Dashboard.php */