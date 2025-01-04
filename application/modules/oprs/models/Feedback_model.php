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
	// oprs
	private $feedbacks = 'tblfeedbacks';
    private $oprs_users = 'tblusers';
    private $clients = 'tblclients';
    private $citee = 'tblcitations';
    private $csf = 'tblservice_feedbacks';
	// ejournal
	private $uiux = 'tblcsf_uiux';
    private $ej_users = 'tblusers';

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
        $oprs = $this->load->database('dboprs', TRUE);

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
	public function get_name($id, $sys){

		if($sys == 'eReview' || $sys == 'eJournal Admin'){
			
			$oprs = $this->load->database('dboprs', TRUE);
			$oprs->select('usr_username');
			$oprs->from($this->oprs_users);
			$oprs->where('usr_id', $id);
			$data = $oprs->get()->row_array();
			return $data['usr_username'];

		}else{
			$this->db->select('email');
			$this->db->from($this->ej_users);
			$this->db->where('id', $id);
			$data = $this->db->get()->row_array();
			return $data['email'];
		}
	}

	/**
	 * Count feedbacks
	 *
	 * @return void
	 */
	public function count_feedbacks(){

		
		$oprs = $this->load->database('dboprs', TRUE);

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
		
        // $oprs = $this->load->database('dboprs', TRUE);

		// $oprs->select('count(*) as total, (CASE WHEN fb_rate_ui = 1 THEN "Sad" WHEN fb_rate_ui = 2 THEN "Neutral" else "Happy" end) as label');
		// $oprs->from($this->feedbacks);
		// $oprs->where($this->feedbacks.'.fb_rate_ui >', '0');
		// $oprs->group_by($this->feedbacks.'.fb_rate_ui');
		// $query = $oprs->get();
		// return $query->result();

		$sql = "
					SELECT stars AS star_count, 
						COUNT(r.csf_rate_ui) AS total_ratings
					FROM (
						SELECT 5 AS stars UNION ALL
						SELECT 4 UNION ALL
						SELECT 3 UNION ALL
						SELECT 2 UNION ALL
						SELECT 1
					) AS star_counts
					LEFT JOIN dbej.tblcsf_uiux r ON star_counts.stars = r.csf_rate_ui
					GROUP BY stars
					ORDER BY stars DESC
				";

		// Execute the query
		$query = $this->db->query($sql);

		// Fetch the result
		return $query->result();
	}

	public function ux_graph(){
		
        // $oprs = $this->load->database('dboprs', TRUE);

		// $oprs->select('count(*) as total, (CASE WHEN fb_rate_ux = 1 THEN "Sad" WHEN fb_rate_ux = 2 THEN "Neutral" else "Happy" end) as label');
		// $oprs->from($this->feedbacks);
		// $oprs->where($this->feedbacks.'.fb_rate_ux >', '0');
		// $oprs->group_by($this->feedbacks.'.fb_rate_ux');
		// $query = $oprs->get();
		// return $query->result();

		
		$sql = "
					SELECT stars AS star_count, 
						COUNT(r.csf_rate_ui) AS total_ratings
					FROM (
						SELECT 5 AS stars UNION ALL
						SELECT 4 UNION ALL
						SELECT 3 UNION ALL
						SELECT 2 UNION ALL
						SELECT 1
					) AS star_counts
					LEFT JOIN dbej.tblcsf_uiux r ON star_counts.stars = r.csf_rate_ux
					GROUP BY stars
					ORDER BY stars DESC 
					LIMIT 100
				";

		// Execute the query
		$query = $this->db->query($sql);

		// Fetch the result
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

	public function get_uiux($from = null, $to = null){

		// Build the main query
		$this->db->select('a.*'); // Replace 'joined_data' with actual column(s) needed from the joined table
		$this->db->from($this->uiux . ' a');
	
		// Apply the date filters if both `$from` and `$to` are provided
		if ($from > 0 && $to > 0) {
			$this->db->where('a.csf_created_at >=', $from);
			$this->db->where('a.csf_created_at <=', $to);
		}
	
		// Perform the join dynamically based on `csf_system`
		$query = $this->db->get();
		$result = $query->result();
	
		// Process each result row to join based on `csf_system`
		foreach ($result as &$row) {
			if ($row->csf_system == 'eReview' || $row->csf_system == 'eJournal Admin') {
				// Use the secondary database for these systems
				$oprs = $this->load->database('dboprs', TRUE);
				$oprs->select('usr_username');
				$oprs->from($this->oprs_users);
				$oprs->where('usr_id', $row->csf_user_id);
				$data = $oprs->get()->row_array();
	
				// Add the joined data to the result row
				$row->email = isset($data['usr_username']) ? $data['usr_username'] : null;
			} else {
				// Use the primary database for other systems
				$this->db->select('email');
				$this->db->from($this->ej_users);
				$this->db->where('id', $row->csf_user_id);
				$data = $this->db->get()->row_array();
	
				// Add the joined data to the result row
				$row->email = isset($data['email']) ? $data['email'] : null;
			}
		}
	
		return $result;

		// $this->db->select('*');
		// $this->db->from($this->uiux);

		// if($from > 0 && $to > 0){
		// 	$this->db->where('csf_created_at >=',$from);
		// 	$this->db->where('csf_created_at <=',$to);
		// }

		// $query = $this->db->get();
		// return $query->result();
	}

	public function get_uiux_sex($from = null, $to = null){
	

	$query = "SELECT 
			sex_label, 
			SUM(total) AS total_count
		FROM (
			SELECT 
				IFNULL(COUNT(csf_user_id), 0) AS total, 
				sex_name AS sex_label
			FROM dbej.tblsex
			LEFT JOIN dbej.tbluser_profiles ON sex_id = sex
			LEFT JOIN dbej.tblcsf_uiux ON user_id = csf_user_id";


		if($from > 0 && $to > 0){
			$query .= " WHERE DATE(csf_created_at) >= " . $from ." DATE(csf_created_at) <= " . $to;
		}
		
		$query .= " GROUP BY sex_id

			UNION ALL

			SELECT 
				IFNULL(COUNT(csf_user_id), 0) AS total, 
				sex AS sex_label
			FROM dboprs.tblsex AS s
			LEFT JOIN dboprs.tblusers ON s.id = usr_sex
			LEFT JOIN dbej.tblcsf_uiux ON usr_id = csf_user_id";

			if($from > 0 && $to > 0){
				$query .= " WHERE DATE(csf_created_at) >= " . $from ." DATE(csf_created_at) <= " . $to;
			}

			$query .= "
			GROUP BY s.id
		) combined
		GROUP BY sex_label";
		
		// Execute the query
		$query = $this->db->query($query);

		// Fetch the result
		return $query->result();
		
	}
}

/* End of file Feedbak_model.php */ 