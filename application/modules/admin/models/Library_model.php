<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File Name: Library_model.php
 * --------------------------------------------------------
 * Purpose of this file:
 * To manage library functions
 * --------------------------------------------------------
 * System Name: Online Research Journal System
 * --------------------------------------------------------
 * Author: Gerard Paul D. Balde
 * ----------------------------------------------------------------------------------------------------
 * Date of revision: Sep 30, 2019
 * ----------------------------------------------------------------------------------------------------
 * Copyright Notice:
 * Copyright (C) 2017 by the Department of Science and Technology-NRCP
 */

class Library_model extends CI_Model {

	public function __construct() {
		parent::__construct();
		$this->load->database(ENVIRONMENT);
	}

	/**
	 * Retrieve library using table name
	 *
	 * @param   string  $tbl  table name
	 *
	 * @return  array        table data
	 */
	public function get_library($tbl) {
		$this->db->select('*');
		$this->db->from($tbl);
		$query = $this->db->get();
		return $query->result();
	}

    public function get_tables(){
        return $this->db->list_tables();
    }

	public function get_editorial_board_position(){
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('*');
		$oprs->from('tblroles');
		$oprs->where('role_id >=', 2);
		$oprs->where('role_id <=', 15);
		$query = $oprs->get();
		return $query->result();
	}

	public function archive_editorial_policy($data, $where){
		$this->db->update('tbleditorial_policy', $data, $where);
	}

	public function save_editorial_policy($data){
		$this->db->insert('tbleditorial_policy', $data);
	}

	public function update_editorial_policy($data){
		$this->db->where('id', 1); // specify the condition
		$this->db->update('tbleditorial_policy', $data);
	}

	public function get_editorial_policy_content(){
		$this->db->select("*");
		$this->db->from('tbleditorial_policy');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function update_guidelines($data){
		$this->db->update('tblguidelines', $data);
	}

	public function get_guidelines_content(){
		$this->db->select("gd_content");
		$this->db->from('tblguidelines');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result[0]['gd_content'];
	}
}

/* End of file Library_model.php */
