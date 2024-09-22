<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Export_excel_model extends CI_Model {

	private $journals = 'tbljournals';
	private $articles = 'tblarticles';
	private $clients = 'tblclients';
	private $editorials = 'tbleditorials';
	private $hits = 'tblhits_abstract';

	/**
	 * this function download excel file of journals
	 *
	 * @return  array  [return description]
	 */
	public function fetch_data_journals() {
		$this->db->select('*');
		$this->db->from($this->journals);
		$this->db->order_by('date_created', 'desc');
		$query = $this->db->get();
		return $query->result();
	}

	/** this function download excel file of articles */
	public function fetch_data_articles($id) {
		$this->db->select('*');
		$this->db->from($this->articles);
		$this->db->where('art_jor_id', $id);
		$this->db->order_by('date_created', 'desc');
		$query = $this->db->get();
		return $query->result();
	}

	/** this function download excel file of client information */
	public function fetch_data_clients() {
		$this->db->select('clt_id,art_title,clt_title,clt_name,clt_sex,clt_affiliation,clt_country,clt_email,clt_purpose,clt_ip_address,clt_journal_downloaded_id,clt_download_date_time');
		$this->db->from($this->clients . ' a');
		$this->db->join($this->articles . ' b', 'a.clt_journal_downloaded_id = b.art_id');
		$this->db->order_by('a.clt_download_date_time', 'desc');
		$query = $this->db->get();
		return $query->result();
		// return $this->db->last_query();
	}

	/** this function download excel file of abstract hits information */
	public function fetch_data_abstract() {
		$this->db->select('hts_id,art_title,hts_ip_address,hts_art_id,date_viewed');
		$this->db->from($this->hits . ' a');
		$this->db->join($this->articles . ' b', 'a.hts_art_id = b.art_id');
		$this->db->order_by('date_viewed', 'desc');
		$query = $this->db->get();
		return $query->result();
	}

	/** this function download excel file of editorial boards */
	public function fetch_data_editorials() {
		$this->db->select('*');
		$this->db->from($this->editorials);
		$this->db->order_by('date_created', 'desc');
		$query = $this->db->get();
		return $query->result();
	}

}
