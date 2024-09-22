<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File Name: Dashboard_model.php
 * ----------------------------------------------------------------------------------------------------
 * Purpose of this file:
 * To manage dashboard functions
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

class Dashboard_model extends CI_Model {

	private $visitors = 'tblvisitor_details';
	private $hits = 'tblhits_abstract';
	private $articles = 'tblarticles';

	public function __construct() {
		parent::__construct();
		$this->load->database(ENVIRONMENT);
	}

	/**
	 * this function retreive all visitor details
	 *
	 * @return  array  visitor data
	 */
	public function get_visitors() {
		$this->db->limit(100);
		$this->db->select('*');
		$this->db->from($this->visitors);
		$this->db->order_by('vis_datetime', 'desc');
		$query = $this->db->get();
		return $query->result();
	}

	/**
	 * this function retreive all abstract viewers details
	 *
	 * @return  array  abstract viewer data
	 */
	public function get_viewers() {
		$this->db->select('*');
		$this->db->from($this->hits . ' a');
		$this->db->join($this->articles . ' b', 'a.hts_art_id = b.art_id');
		$this->db->order_by('date_viewed', 'desc');
		$query = $this->db->get();
		return $query->result();
	}

	/**
	 * this function count todays visitors
	 *
	 * @return  int  number of visitor today
	 */
	public function vis_count() {
		$this->db->select('*');
		$this->db->from($this->visitors);
		$this->db->like('vis_datetime', date('Y-m-d'));
		$query = $this->db->get();
		return $query->num_rows();
	}

	/**
	 * this function count total visitors
	 *
	 * @return  int  number of  visitors
	 */
	public function vis_count_all() {
		$this->db->select('*');
		$this->db->from($this->visitors);
		$query = $this->db->get();
		return $query->num_rows();
	}

}

?>
