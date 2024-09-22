<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File Name: Editorial_model.php
 * ----------------------------------------------------------------------------------------------------
 * Purpose of this file:
 * To manage editorial boards functions
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

class Editorial_model extends CI_Model {
	private $tblName = 'tbleditorials';
	public function __construct() {
		parent::__construct();
		$this->load->database(ENVIRONMENT);
	}

	/**
	 * Retrieve all editorial boards
	 *
	 * @return  array  all editorial boards data
	 */
	public function get_editorials() {
		$this->db->select('*');
		$this->db->from($this->tblName);
		$this->db->order_by('date_created', 'desc');
		$query = $this->db->get();
		return $query->result();
	}

	/**
	 * Retrieve editorial board details
	 *
	 * @param   int  $id  editorial board id
	 *
	 * @return  array       editorial board data
	 */
	public function get_editorial($id) {
		$this->db->select('*');
		$this->db->from($this->tblName);
		$this->db->where('edt_id', $id);
		$query = $this->db->get();
		return $query->result();
	}
	
	/**
	 * Save editorial board
	 *
	 * @param   array  $post  editorial board data
	 *
	 * @return  int         affected rows
	 */
	public function save_editorial($post) {
		$this->db->insert($this->tblName, $post);
		save_log_ej(_UserIdFromSession(), 'just added an editorial board.', $this->db->insert_id());
		return $this->db->affected_rows();
	}

	/**
	 * Count total editorial boards
	 *
	 * @return  int  number of editorial board
	 */
	public function edt_count() {
		$this->db->select('*');
		$this->db->from($this->tblName);
		$query = $this->db->get();
		return $query->num_rows();
	}

	/**
	 * Delete editorial board
	 *
	 * @param   array	$where  condition
	 *
	 * @return  int 	number of deleted rows
	 */
	public function delete_editorial($where) {
		$this->db->delete($this->tblName, $where);
		save_log_ej(_UserIdFromSession(), 'just deleted an editorial board.', $this->db->insert_id());
		return $this->db->affected_rows();
	}

	/**
	 * Update editorial board
	 *
	 * @param   array  $post   editorial data
	 * @param   array  $where  editorial id
	 *
	 * @return  int    number of updated rows
	 */
	public function update_editorial($post, $where) {
		$this->db->update($this->tblName, $post, $where);
		save_log_ej(_UserIdFromSession(), 'just updated an editorial board.', $this->db->insert_id());
		return $this->db->affected_rows();
	}
}

/* End of file Editorial_board.php */