<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

/**
 * File Name: Journal.php
 * ----------------------------------------------------------------------------------------------------
 * Purpose of this file:
 * To manage journals,issue and articles
 * ----------------------------------------------------------------------------------------------------
 * System Name: Online Research Journal System
 * ----------------------------------------------------------------------------------------------------
 * Author: Gerard Paul D. Balde
 * ----------------------------------------------------------------------------------------------------
 * Date of revision: Sep 27, 2019
 * ----------------------------------------------------------------------------------------------------
 * Copyright Notice:
 * Copyright (C) 2018 by the Department of Science and Technology - National Research Council of the Philiipines
 */

class Journal extends EJ_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model('Article_model');
		$this->load->model('Editorial_model');
		$this->load->model('Library_model');
		$this->load->model('Client_model');
		$this->load->model('Coauthor_model');
		$this->load->model('Journal_model');
	}
	
	/**
	 * this function get, save, update and remove an article
	 *
	 * @param   string  $method  action
	 * @param   int  $id	journal id
	 *
	 * @return  void
	 */
	public function article($method = null, $id = null) {
		$this->session->unset_userdata('artc_message');

		if ($method == 'get') {
			$output = $this->Article_model->get_articles($id);
			echo json_encode($output);
		} else if ($method == 'view') {
			$output = $this->Article_model->get_article($id);
			echo json_encode($output);
		} else if ($method == 'update') {
			$tableName = 'tblarticles';
			$result = $this->db->list_fields($tableName);
			$post = array();
			$jor_id;

			foreach ($result as $i => $field) {
				$jor_id = $this->input->post('art_jor_id', TRUE);
				$post[$field] = $this->input->post($field, TRUE);
				$old_abstract = $this->input->post('art_abstract_file', TRUE);
				$old_pdf = $this->input->post('art_full_text_pdf', TRUE);
			}

			if (!empty($_FILES['art_abstract_file_new']['name'])) {
				$filepath = "assets/uploads/abstract/";
				unlink($filepath . $old_abstract);
				$post['art_abstract_file'] = date('YmdHis') . '_' . str_replace(" ", "_", $_FILES['art_abstract_file_new']['name']);

				/** UPLOAD ABSTRACT */
				$config_abstract['upload_path'] = './assets/uploads/abstract/';
				$config_abstract['allowed_types'] = 'pdf';
				$config_abstract['file_name'] = date('YmdHis') . '_' . str_replace(" ", "_", $_FILES['art_abstract_file_new']['name']);

				$this->load->library('upload', $config_abstract);
				$this->upload->initialize($config_abstract);

				if (!$this->upload->do_upload('art_abstract_file_new')) {
					$error = $this->upload->display_errors();
				} else {
					$data = $this->upload->data();
				}
			}

			if (!empty($_FILES['art_full_text_pdf_new']['name'])) {
				$filepath = "assets/uploads/pdf/";
				unlink($filepath . $old_pdf);
				$post['art_full_text_pdf'] = date('YmdHis') . '_' . str_replace(" ", "_", $_FILES['art_full_text_pdf_new']['name']);

				// upload abstract
				$config_pdf['upload_path'] = './assets/uploads/pdf/';
				$config_pdf['allowed_types'] = 'pdf';
				$config_pdf['file_name'] = date('YmdHis') . '_' . str_replace(" ", "_", $_FILES['art_full_text_pdf_new']['name']);
				$this->load->library('upload', $config_pdf);
				$this->upload->initialize($config_pdf);

				if (!$this->upload->do_upload('art_full_text_pdf_new')) {
					$error = $this->upload->display_errors();
				} else {
					$data = $this->upload->data();
				}
			}

			$post['last_updated'] = date('Y-m-d H:i:s');
			$where['art_id'] = $this->input->post('art_id', TRUE);
			$output = $this->Article_model->update_article(array_filter($post), $where);

			// save added coauthors
			$coauthors = $this->input->post('coa_name', TRUE);
			$affiliations = $this->input->post('coa_affiliation', TRUE);
			$emails = $this->input->post('coa_email', TRUE);
			$coa = array();

			if (!empty($coauthors)) {
				for ($i = 0; $i < count($coauthors); $i++) {
					$coa['coa_name'] = $coauthors[$i];
					$coa['coa_affiliation'] = $affiliations[$i];
					$coa['coa_email'] = $emails[$i];
					$coa['coa_art_id'] = $this->input->post('art_id', TRUE);
					$coa['date_created'] = date('Y-m-d H:i:s');
					$this->Article_model->save_coauthors($coa);
				}
			}

			// update coauthors
			$id_u = $this->input->post('coa_id', TRUE);
			$coauthors_u = $this->input->post('coa_name_update', TRUE);
			$affiliations_u = $this->input->post('coa_affiliation_update', TRUE);
			$emails_u = $this->input->post('coa_email_update', TRUE);
			$coa_u = array();

			if (!empty($coauthors_u)) {
				for ($i = 0; $i < count($coauthors_u); $i++) {
					$coa_u['coa_name'] = $coauthors_u[$i];
					$coa_u['coa_affiliation'] = $affiliations_u[$i];
					$coa_u['coa_email'] = $emails_u[$i];
					$coa_u['coa_art_id'] = $this->input->post('art_id', TRUE);
					$coa_u['last_updated'] = date('Y-m-d H:i:s');
					$where_u['coa_id'] = $id_u[$i];
					$this->Article_model->update_coauthors(array_filter($coa_u), $where_u);
				}
			}
		} else {
			$output = $this->Article_model->get_article($id);
			foreach ($output as $key) {
				foreach ($key as $value) {
					$abstract = $key->art_abstract_file;
					$pdf = $key->art_full_text_pdf;
				}
			}

			$filepath1 = "assets/uploads/abstract/";
			unlink($filepath1 . $abstract);

			$filepath2 = "assets/uploads/pdf/";
			unlink($filepath2 . $pdf);

			$where['art_id'] = $id;
			$this->Article_model->delete_article($where);

			$where2['coa_art_id'] = $id;
			$this->Coauthor_model->delete_coauthor($where2);

		}
	}

	/**
	 * this function get and remove a coauthor
	 *
	 * @param   string  $method  action
	 * @param   int  $id      co-author id
	 *
	 * @return  array           json 
	 */
	public function coauthor($method = null, $id = null) {
		if ($method == 'get') {
			$output = $this->Coauthor_model->get_coauthor($id);
			echo json_encode($output);
		} else {
			$where['coa_id'] = $id;
			$output = $this->Coauthor_model->delete_coauthor($where);
		}
	}

	/**
	 * this function get, save, update and remove an journal
	 *
	 * @param   string  $method  action
	 * @param   int  $id      journal id
	 *
	 * @return  array           json
	 */
	public function journal($method = null, $id = null) {
		$this->session->unset_userdata('artc_message');

		if ($method == 'view') {
			$output = $this->Journal_model->get_journal($id);
			echo json_encode($output);
		} else if ($method == 'update') {
			$tableName = 'tbljournals';
			$result = $this->db->list_fields($tableName);
			$post = array();

			foreach ($result as $i => $field) {
				if ($field != 'jor_cover') {
					$post[$field] = $this->input->post($field, TRUE);
				}
			}

			if ($_FILES['jor_cover']['name'] != '') {
				$post['jor_cover'] = date('YmdHis') . '_' . str_replace(" ", "_", $_FILES['jor_cover']['name']);

				// remove old file
				$filepath = "assets/uploads/cover/";
				$old_cover = $this->Journal_model->get_cover($this->input->post('jor_id'));

				if ($old_cover != '') {unlink($filepath . $old_cover);}

				// upload cover
				$config_abstract['upload_path'] = './assets/uploads/cover/';
				$config_abstract['allowed_types'] = 'jpg|png|jpeg';
				$config_abstract['file_name'] = $post['jor_cover'];

				$this->load->library('upload', $config_abstract);
				$this->upload->initialize($config_abstract);

				if (!$this->upload->do_upload('jor_cover')) {
					$error = $this->upload->display_errors();
				} else {
					$data = $this->upload->data();
				}
			}

			$post['last_updated'] = date('Y-m-d H:i:s');
			$where['jor_id'] = $this->input->post('jor_id', TRUE);
			$output = $this->Journal_model->update_journal(array_filter($post), $where);

		} else {
			$old_cover = $this->Journal_model->get_cover($id);
			$filepath = "assets/uploads/cover/";
			unlink($filepath . $old_cover);
			$where['jor_id'] = $id;
			$output = $this->Journal_model->delete_journal($where);
		}
	}

	/**
	 * this function get, save, update and remove an editorial boards
	 *
	 * @param   string  $method  action
	 * @param   int  $id      editorial id
	 *
	 * @return  array           json
	 */
	public function editorial($method = null, $id = null) {

		$this->session->unset_userdata('edtr_message');

		if ($method == 'view') {
			$output = $this->Editorial_model->get_editorial($id);
			echo json_encode($output);
		} else if ($method == 'update') {
			$tableName = 'tbleditorials';
			$result = $this->db->list_fields($tableName);
			$post = array();

			foreach ($result as $i => $field) {
				if ($field != 'edt_id') {
					$post[$field] = $this->input->post($field, TRUE);
				}

				// if($this->input->post('edt_photo_exist', TRUE) == ''){
					if ($_FILES['edt_photo']['name'] != '') {
						$file_string = str_replace(" ", "_", $_FILES['edt_photo']['name']);
						$file_no_ext = preg_replace("/\.[^.]+$/", "", $file_string);
						$clean_file = preg_replace('/[^A-Za-z0-9\-]/', '_', $file_no_ext);
						$post['edt_photo'] = date('YmdHis') . '_' . $clean_file . '.jpg';
						$upload_file = $post['edt_photo'];
					}

						
				// }else{
				// 	$post['edt_photo'] = $this->input->post('edt_photo_exist', TRUE);
				// }
			}

			// if($this->input->post('edt_photo_exist', TRUE) == ''){
				if ($_FILES['edt_photo']['name'] != '') {
					// upload cover
					$config_abstract['upload_path'] = './assets/uploads/editorial/';
					$config_abstract['allowed_types'] = 'jpg';
					$config_abstract['file_name'] = $upload_file;
	
					$this->load->library('upload', $config_abstract);
					$this->upload->initialize($config_abstract);
	
					if (!$this->upload->do_upload('edt_photo')) {
						$error = $this->upload->display_errors();
					} else {
						$data = $this->upload->data();
					}
				}
			// }

			$post['last_updated'] = date('Y-m-d H:i:s');
			$where['edt_id'] = $this->input->post('edt_id', TRUE);
			$output = $this->Editorial_model->update_editorial(array_filter($post), $where);
			$array_msg = array('icon' => 'oi-check', 'class' => 'alert-success', 'msg' => 'Editorial Updated.');
			$this->session->set_flashdata('edtr_message', $array_msg);
			redirect('admin/dashboard');
		} else if ($method == 'add') {
			$tableName = 'tbleditorials';
			$result = $this->db->list_fields($tableName);
			$post = array();

			foreach ($result as $i => $field) {
				if ($field != 'edt_id') {
					if (!empty($this->input->post($field, TRUE))) {
						$post[$field] = $this->input->post($field, TRUE);
					}
				}

				if ($_FILES['edt_photo']['name'] != '') {
					//journal cover
					$file_string = str_replace(" ", "_", $_FILES['edt_photo']['name']);
					$file_no_ext = preg_replace("/\.[^.]+$/", "", $file_string);
					$clean_file = preg_replace('/[^A-Za-z0-9\-]/', '_', $file_no_ext);
					$post['edt_photo'] = date('YmdHis') . '_' . $clean_file . '.jpg';
					$upload_file = $post['edt_photo'];
				} else {
					$post['edt_photo'] = 'unavailable.jpg';
				}

			}

			$post['date_created'] = date('Y-m-d H:i:s');

			if ($_FILES['edt_photo']['name'] != '') {
				// upload cover
				$config_abstract['upload_path'] = './assets/uploads/editorial/';
				$config_abstract['allowed_types'] = 'jpg|png|jpeg';
				$config_abstract['file_name'] = $upload_file;

				$this->load->library('upload', $config_abstract);
				$this->upload->initialize($config_abstract);

				if (!$this->upload->do_upload('edt_photo')) {
					$error = $this->upload->display_errors();
				} else {
					$data = $this->upload->data();
				}
			}

			$output = $this->Editorial_model->save_editorial($post);
			$array_msg = array('icon' => 'oi-check', 'class' => 'alert-success', 'msg' => 'Editorial Added.');
			$this->session->set_flashdata('edtr_message', $array_msg);
			redirect('admin/dashboard');
		} else{
			$where['edt_id'] = $id;
			$output = $this->Editorial_model->delete_editorial($where);
			$array_msg = array('icon' => 'oi-check', 'class' => 'alert-success', 'msg' => 'Editorial Deleted.');
			$this->session->set_flashdata('edtr_message', $array_msg);
		}
	}

	/**
	 * this function get journals according to year
	 *
	 * @param   string  $value  year
	 *
	 * @return  array          json
	 */
	public function journals_by_year($value) {
		$output = $this->Journal_model->get_journal_by_year($value);
		echo json_encode($output);
	}

	/**
	 * this function get total number of abstract viewers
	 *
	 * @param   int  $id  article id
	 *
	 * @return  int       count of abstract hits
	 */
	public function count_abstract($id) {
		$output = $this->Article_model->count_abstract($id);
		echo $output;
	}

	/**
	 * this function get total number of pdf downloads
	 *
	 * @param   int  $id  article id
	 *
	 * @return  int       count of pdf downloaded
	 */
	public function count_pdf($id) {
		$output = $this->Article_model->count_pdf($id);
		echo $output;
	}

}
