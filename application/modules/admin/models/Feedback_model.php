<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File Name: Article_model.php
 * ----------------------------------------------------------------------------------------------------
 * Purpose of this file:
 * To manage article functions
 * ----------------------------------------------------------------------------------------------------
 * System Name: Online Research Journal System
 * ----------------------------------------------------------------------------------------------------
 * Author: Gerard Paul D. Balde
 * ----------------------------------------------------------------------------------------------------
 * Date of revision: Sep 30, 2019
 * ----------------------------------------------------------------------------------------------------
 * Copyright Notice:
 * Copyright (C) 2018 by the Department of Science and Technology - National Research Council of the Philiipines
 */

class Feedback_model extends CI_Model {
	private $feedbacks = 'tblfeedbacks';
	private $users = 'tblusers';
	public function __construct() {
		parent::__construct();
		$this->load->database(ENVIRONMENT);
	}

	/**
	 * Verify if feedback was submitted already
	 *
	 * @param [int] $id		user id
	 * @return void
	 */
	public function verify($id = null)
	{
        $CI =& get_instance();
        $oprs = $CI->load->database('dboprs', TRUE);
		
		
		if($id != 999999){ // 999999-code for admin

			//get email
			$this->db->select('clt_email');
			$this->db->from('tblclients');
			$this->db->where('clt_id', $id);
			$data = $this->db->get()->row_array();
			$email = $data['clt_email'];

			$this->db->select('*');
			$this->db->from('dbej.tblclients a');
			$this->db->join('dboprs.tblfeedbacks b', 'a.clt_id = b.fb_usr_id');
			$this->db->where('a.clt_email', $email);
			$query = $this->db->get();

		}else{
			$oprs->select('*');
			$oprs->from($this->feedbacks);	
			$oprs->where('fb_usr_id', _UserIdFromSession());
			$query = $oprs->get();
		}
	
		return $query->num_rows();
	}

	/**
	 * Save feedback datas
	 *
	 * @param [type] $data
	 * @return void
	 */
	public function save_feedback($data){
		
		$CI =& get_instance();
		$oprs = $CI->load->database('dboprs', TRUE);
		
		$oprs->insert($this->feedbacks, $data);
		// save_log_ej(_UserIdFromSession(), 'submitted UI/UX feedback.', $oprs->insert_id());
		return $oprs->affected_rows();

		
	}
}

/* End of file Feedback_model.php */