<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File Name: Coauthor_model.php
 * ----------------------------------------------------------------------------------------------------
 * Purpose of this file:
 * To manage coauthor functions
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

class Coauthor_model extends CI_Model {
	private $coauthors = 'tblcoauthors';
	private $articles = 'tblarticles';
	public function __construct() {
		parent::__construct();
		$this->load->database(ENVIRONMENT);
	}

	/**
	 * Retrieve coauthors
	 *
	 * @param  int  $id  coauthor id
	 *
	 * @return  array       coauthor data
	 */
	public function get_coauthor($id) {
		$this->db->select('*');
		$this->db->from($this->coauthors);
		$this->db->where('coa_art_id', $id);
		$query = $this->db->get();
		return $query->result();
	}

	/**
	 * Delete coauthor
	 *
	 * @param   array  $where  condition
	 *
	 * @return  int          affected rows
	 */
	public function delete_coauthor($where) {
		$this->db->delete($this->coauthors, $where);
		save_log_ej(_UserIdFromSession(), 'just deleted coauthor/s.');
		return $this->db->affected_rows();
	}
	
	/**
	 * tRetrieve author with coauthors
	 *
	 * @param   int  $id  article id
	 *
	 * @return  array       author and coauthor
	 */
	public function get_author_coauthors($id) {
		$a = array();
		$this->db->select('art_author');
		$this->db->from($this->articles);
		$this->db->where('art_id', $id);
		$query = $this->db->get();
		$result = $query->result_array();
		array_push($a, $result[0]['art_author']);
		$this->db->select('b.coa_name as coauthor');
		$this->db->from($this->articles . ' a');
		$this->db->join($this->coauthors . ' b', 'a.art_id = b.coa_art_id');
		$this->db->where('coa_art_id', $id);
		$query = $this->db->get();
		$coas = $query->result();
		foreach ($coas as $row) {
			array_push($a, $row->coauthor);
		}
		$comma_separated = implode(', ', $a);
		return $comma_separated;
	}

	/**
	 * Retrieve author with coauthors
	 *
	 * @return  array  list of author with coauthor
	 */
	public function get_author_coauthors_list() {
		$a = array();
		$this->db->select('art_author');
		$this->db->from($this->articles);
		$query = $this->db->get();
		$auth = $query->result();
		foreach ($auth as $row) {
			array_push($a, $row->art_author);
		}
		$this->db->select('coa_name');
		$this->db->from($this->coauthors);
		$query2 = $this->db->get();
		$coa = $query2->result();
		foreach ($coa as $row) {
			array_push($a, $row->coa_name);
		}
		$comma_separated = implode(',& ', $a);
		return $comma_separated;
	}
}

/* End of file Coauthor_model.php */