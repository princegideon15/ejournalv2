<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File Name: Journal_model.php
 * ----------------------------------------------------------------------------------------------------
 * Purpose of this file:
 * To manage journal functions
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

class Journal_model extends CI_Model {

	private $tblName = 'tbljournals';

	public function __construct() {
		parent::__construct();
		$this->load->database(ENVIRONMENT);
	}

	/**
	 * Retrieve all journals
	 *
	 * @return  array  journals
	 */
	public function get_journals() {
		$this->db->select('*');
		$this->db->from($this->tblName);
		$this->db->order_by('date_created', 'desc');
		$query = $this->db->get();
		return $query->result();
	}

	/**
	 * Retrieve journal details
	 *
	 * @param   int  $id  journal id
	 *
	 * @return  array       journals
	 */
	public function get_journal($id) {
		$this->db->select('*');
		$this->db->from($this->tblName);
		$this->db->where('jor_id', $id);
		$query = $this->db->get();
		return $query->result();
	}

	/**
	 * Retrieve current journal
	 *
	 * @return  array  current journals
	 */
	public function get_journal_max() {
		$this->db->select_max('jor_year');
		$this->db->from($this->tblName);
		$query = $this->db->get();
		$year = $query->result_array();
		$output = $year[0]['jor_year'];

		$this->db->select('*');
		$this->db->from($this->tblName);
		$this->db->where('jor_year', $output);
		$query = $this->db->get();
		return $query->result();

	}

	/**
	 * Retrieve journal by year
	 *
	 * @param   string  $value  year
	 *
	 * @return  array          journals
	 */
	public function get_journal_by_year($value) {
		$this->db->select('*');
		$this->db->from($this->tblName);
		$this->db->where('jor_year', $value);
		$query = $this->db->get();
		return $query->result();
	}

	/**
	 * Save new journal
	 *
	 * @param   array  $post  journal data
	 *
	 * @return  int         affected rows
	 */
	public function save_journal($post) {
		$this->db->insert($this->tblName, $post);
		save_log_ej(_UserIdFromSession(), 'just created a journal.', $this->db->insert_id());
		return $this->db->affected_rows();
	}

	/**
	 * Check if journal volume + issue exists
	 *
	 * @param   string  $volume  journal volume
	 * @param   string  $issue   journal issue
	 *
	 * @return  int           number of results
	 */
	public function check_journal($volume, $issue) {
		$this->db->select('*');
		$this->db->from($this->tblName);
		$this->db->where('jor_volume', $volume);
		$this->db->where('jor_issue', $issue);
		$query = $this->db->get();
		return $query->num_rows();
	}
	
	/**
	 * Count abstract hits
	 *
	 * @return  int  number of journals
	 */
	public function hit_count() {
		$this->db->select('*');
		$this->db->from('tblhits_abstract');
		$query = $this->db->get();
		return $query->num_rows();
	}
	
	/**
	 * Count citees
	 *
	 * @return  int  number of journals
	 */
	public function cite_count() {
		$this->db->select('*');
		$this->db->from('tblcitations');
		$query = $this->db->get();
		return $query->num_rows();
	}

	/**
	 * Count total journals
	 *
	 * @return  int  number of journals
	 */
	public function jor_count() {
		$this->db->select('*');
		$this->db->from($this->tblName);
		$query = $this->db->get();
		return $query->num_rows();
	}

	/**
	 * Retrieve unique journal volume
	 *
	 * @return  string  journal volume
	 */
	public function get_unique_journal() {
		$this->db->select('jor_volume');
		$this->db->distinct();
		$this->db->from($this->tblName);
		$query = $this->db->get();
		return $query->result();
	}

	/**
	 * Retrieve list of journal years
	 *
	 * @return  string  journal year
	 */
	public function get_unique_journal_year() {
		$this->db->select('jor_year');
		$this->db->distinct();
		$this->db->from($this->tblName);
		$this->db->order_by('jor_year', 'desc');
		$query = $this->db->get();
		return $query->result();
	}

	/**
	 * Update journal
	 *
	 * @param   array  $post   journal data
	 * @param   array  $where  condition
	 *
	 * @return  int          affected rows
	 */
	public function update_journal($post, $where) {
		$this->db->update($this->tblName, $post, $where);
		save_log_ej(_UserIdFromSession(), 'just updated a journal.', $this->db->insert_id());
		return $this->db->affected_rows();
	}

	/**
	 * Delete journal
	 *
	 * @param   array  $where  condition
	 *
	 * @return  int          affected rows
	 */
	public function delete_journal($where) {
		$this->db->delete($this->tblName, $where);
		save_log_ej(_UserIdFromSession(), 'just deleted a journal.');
		return $this->db->affected_rows();
	}

	/**
	 * Retrieve journal cover filename
	 *
	 * @param   int  $id  journal id
	 *
	 * @return  string       journal cover image file name
	 */
	public function get_cover($id) {
		$this->db->select('jor_cover');
		$this->db->from($this->tblName);
		$this->db->where('jor_id', $id);
		$query = $this->db->get();
		$result = $query->result_array();
		return $result[0]['jor_cover'];
	}
}

/* End of file Journal_model.php */
