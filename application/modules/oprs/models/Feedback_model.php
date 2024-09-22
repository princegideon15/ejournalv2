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
    private $clients = 'tblclients';
    private $citee = 'tblcitations';
    private $csf = 'tblservice_feedbacks';
	public function __construct() {
		parent::__construct();
		$this->load->database(ENVIRONMENT);
	}

	/**
	 * Retreive feedbacks TODO
	 *
	 * @return void
	 */
	public function get_feedbacks()
	{
		
        $CI =& get_instance();
        $oprs = $CI->load->database('dboprs', TRUE);

        $oprs->select('*');
        $oprs->from($this->feedbacks);
        $oprs->order_by('date_created', 'desc');
		$query = $oprs->get();
		return $query->result();
		
	}
	
	/**
	 * Retreive user names
	 *
	 * @param [int] $id		usr_id | clt_id
	 * @param [int] $sys		
	 * @return void
	 */
	public function get_name($id, $sys, $src){

		if($sys == 1 || $sys == 2){
			$CI =& get_instance();
			$oprs = $CI->load->database('dboprs', TRUE);
	
			$oprs->select('usr_username');
			$oprs->from('dboprs.tblusers');
			$oprs->where('usr_id', $id);
			$data = $oprs->get()->row_array();
			return $data['usr_username'];

		}else{
			if($src == 1){

				$this->db->select('clt_name');
				$this->db->from($this->clients);
				$this->db->where('clt_id', $id);
				$data = $this->db->get()->row_array();
				$return = (empty($data['clt_name'])) ? '-' : $data['clt_name'];
	
				return $return;

			}else if($src == 2){

				$this->db->select('cite_name');
				$this->db->from($this->citee);
				$this->db->where('row_id', $id);
				$data = $this->db->get()->row_array();
				$return = (empty($data['cite_name'])) ? '-' : $data['cite_name'];

				return $return;
			}

		}
	}

	/**
	 * Count feedbacks
	 *
	 * @return void
	 */
	public function count_feedbacks(){

		$CI =& get_instance();
		$oprs = $CI->load->database('dboprs', TRUE);

		$oprs->select('*');
		$oprs->from('tblfeedbacks');

		return $oprs->get()->num_rows();
	}

	/**
	 * Update feedback
	 *
	 * @param [type] $data
	 * @param [type] $where
	 * @return void
	 */
	public function update_feedbacks($data, $where){

		$CI =& get_instance();
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->update('tblfeedbacks', $data, $where);
	}

	public function get_csf_feedbacks(){
		$this->db->select('*');
		$this->db->from($this->clients . ' a');
		$this->db->join($this->csf . ' b', 'a.clt_id = b.svc_fdbk_usr_id');
		$this->db->group_by('svc_fdbk_ref');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_csf_feedback_by_ref($id){
		$this->db->select('*');
		$this->db->from($this->csf . ' a');
		$this->db->join('new_dbskms.tblservice_feedback_questions b', 'a.svc_fdbk_q_id = b.svc_fdbk_q_id ');
		$this->db->join('new_dbskms.tblservice_feedback_ratings c', 'a.svc_fdbk_q_answer = c.svc_fdbk_rating_id', 'left');
		$this->db->join('new_dbskms.tblnrcp_services d', 'a.svc_fdbk_q_answer = d.nrcp_svc_id', 'left');
		$this->db->join('new_dbskms.tblaffiliation_type e', 'a.svc_fdbk_q_answer = e.aff_type_id ', 'left');
		$this->db->where('svc_fdbk_ref', $id);
		$query = $this->db->get();
		return $query->result();
	}

	public function ui_graph(){
		$CI =& get_instance();
        $oprs = $CI->load->database('dboprs', TRUE);

		$oprs->select('count(*) as total, (CASE WHEN fb_rate_ui = 1 THEN "Sad" WHEN fb_rate_ui = 2 THEN "Neutral" else "Happy" end) as label');
		$oprs->from($this->feedbacks);
		$oprs->where($this->feedbacks.'.fb_rate_ui >', '0');
		$oprs->group_by($this->feedbacks.'.fb_rate_ui');
		$query = $oprs->get();
		return $query->result();
	}

	public function ux_graph(){
		$CI =& get_instance();
        $oprs = $CI->load->database('dboprs', TRUE);

		$oprs->select('count(*) as total, (CASE WHEN fb_rate_ux = 1 THEN "Sad" WHEN fb_rate_ux = 2 THEN "Neutral" else "Happy" end) as label');
		$oprs->from($this->feedbacks);
		$oprs->where($this->feedbacks.'.fb_rate_ux >', '0');
		$oprs->group_by($this->feedbacks.'.fb_rate_ux');
		$query = $oprs->get();
		return $query->result();
	}

	public function csf_graph($id){

		if($id == 1){ //affiliation
			$this->db->select('count(*) as total, aff_type as label , aff_type_id as id ');
			$this->db->join('new_dbskms.tblaffiliation_type b', 'a.svc_fdbk_q_answer = b.aff_type_id');
		}else if($id == 2){ //service
			$this->db->select('count(*) as total, nrcp_svc as label, nrcp_svc_id as id');
			$this->db->join('new_dbskms.tblnrcp_services b', 'a.svc_fdbk_q_answer = b.nrcp_svc_id');
		}else{
			$this->db->select('count(*) as total, svc_fdbk_rating as label, svc_fdbk_rating_id as id');
			$this->db->join('new_dbskms.tblservice_feedback_ratings b', 'a.svc_fdbk_q_answer = b.svc_fdbk_rating_id');
		}

		$this->db->from($this->csf. ' a');

		if($id > 0){
			$this->db->where('svc_fdbk_q_id', $id);
		}

		if($id == 1){ //affiliation
			$this->db->group_by('aff_type_id');
		}else if($id == 2){ //service
			$this->db->group_by('nrcp_svc_id');
		}else{
			$this->db->group_by('svc_fdbk_rating_id');
		}


		$query = $this->db->get();
		return $query->result();
	}
}

/* End of file Feedbak_model.php */ 