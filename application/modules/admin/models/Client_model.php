<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File Name: Client_model.php
 * ----------------------------------------------------------------------------------------------------
 * Purpose of this file:
 * To manage client functions
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

class Client_model extends CI_Model {
	private $clients = 'tblclients';
	private $articles = 'tblarticles';
	public function __construct() {
		parent::__construct();
		$this->load->database(ENVIRONMENT);
	}

	/**
	 * this function save client information
	 *
	 * @param   array  $post  client data
	 *
	 * @return  int         affected rows
	 */
	public function save_client($post) {
		$this->db->insert($this->clients, $post);
		return $this->db->affected_rows();
	}

	/**
	 * this function retreive all client information
	 *
	 * @return  array  all client information
	 */
	public function get_clients() {
		$this->db->select('*');
		$this->db->from($this->clients . ' a');
		$this->db->join($this->articles . ' b', 'a.clt_journal_downloaded_id = b.art_id');
		$this->db->order_by('clt_download_date_time', 'desc');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_clients_graph(){
		$this->db->select('(SELECT sex_name FROM tblsex WHERE sex_id LIKE clt_sex) as sex, count(*) as total');
		$this->db->from($this->clients . ' a');
		$this->db->join($this->articles . ' b', 'a.clt_journal_downloaded_id = b.art_id');
		$this->db->where('a.clt_sex > 0');
		$this->db->group_by('a.clt_sex');
		$query = $this->db->get();
		return $query->result();
		// return $this->db->last_query();
	}

	public function get_clients_line_graph(){

		$result_array = array();

		$query = $this->db->get('tblsex');

		foreach ($query->result() as $row)
		{
			$result_array[] = array($row->sex_id => array($row->sex_name => $this->get_line_sex($row->sex_id)));
			// return $this->get_line_sex($row->sex_id);
		}

		return $result_array;
	}

	public function get_clients_monthly_line_graph(){

		$result_array = array();

		$query = $this->db->get('tblsex');

		foreach ($query->result() as $row)
		{
			$result_array[] = array($row->sex_id => array($row->sex_name => $this->get_line_monthly_sex($row->sex_id)));
			// return $this->get_line_sex($row->sex_id);
		}

		return $result_array;
	}

	/**
	 * this function count total clients
	 *
	 * @return  int  totla count of clients
	 */
	public function client_count() {
		$this->db->select('*');
		$this->db->from($this->clients);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function get_line_sex($id){
		$select = '';
		$i = 1;
		$union = '';

		$this->db->select('YEAR(clt_download_date_time) as year');
		$this->db->from($this->clients);
		$this->db->group_by('year');
		$query = $this->db->get();
		$years = $query->result();
		$year_count = $query->num_rows();

		foreach($years as $row){

			if($i < $year_count){
				$union = 'UNION';
			}else{
				$union = '';
			}

			$select .= 'SELECT count(*) as total, IFNULL(YEAR(clt_download_date_time),'. $row->year .') AS label '.  
			'FROM tblclients '. 
			'WHERE clt_sex LIKE '. $id . 
			' AND YEAR(clt_download_date_time) = '. $row->year . ' ' . $union . ' ';

			$i++;	 

		}
		
		$query = $this->db->query($select);
			return $query->result_array();
               
    }

	public function get_line_monthly_sex($id){
		$select = '';
		$union = '';

		for($i=1; $i<=12;$i++){
			if($i < 12){
				$union = 'UNION';
			}else{
				$union = '';
			}

			$select .= 'SELECT count(*) as total, IFNULL(MONTH(clt_download_date_time),'.$i.') AS label '.  
			'FROM tblclients WHERE clt_sex LIKE '. $id . 
			' AND YEAR(clt_download_date_time) LIKE "%' . date('Y') .'%"'.
			' AND  MONTH(clt_download_date_time) = '. $i . ' ' . $union . ' ';
		}  
		
		$query = $this->db->query($select);
		return $query->result_array();
		// return $this->db->last_query();
               
    }
}
?>