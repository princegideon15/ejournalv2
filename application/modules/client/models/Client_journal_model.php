<?php

/**
 * File Name: Client_journal_model.php
 * ----------------------------------------------------------------------------------------------------
 * Purpose of this file:
 * To manage client data input and system output
 * ----------------------------------------------------------------------------------------------------
 * System Name: Online Research Journal System
 * ----------------------------------------------------------------------------------------------------
 * Author: GPDB
 * ----------------------------------------------------------------------------------------------------
 * Date of revision: 10-16-2024
 * ----------------------------------------------------------------------------------------------------
 * Copyright Notice:
 * Copyright (C) 2018 by the Department of Science and Technology - National Research Council of the Philiipines
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Client_journal_model extends CI_Model {

	// ejournal tables
	private $journals = 'tbljournals';
	private $articles = 'tblarticles';
	private $coauthors = 'tblcoauthors';
	private $clients = 'tblclients';
	private $abstracts = 'tblhits_abstract';
	private $editorials = 'tbleditorials';
	private $citations = 'tblcitations';
	private $divisions = 'tbldivisions';
	private $profiles = 'tbluser_profiles';
	private $users = 'tblusers';
	private $educations = 'tbleducational_attainment';
	private $downloads = 'tbldownloads';
	
	// oprs tables
	private $titles = 'tbltitles';

	public function __construct() {
		parent::__construct();
		$this->load->database(ENVIRONMENT);
	}

	public function get_editorials_by_volume_year($vol, $year, $iss){
		$this->db->select('*');
		$this->db->from($this->editorials);
		$this->db->where('edt_volume', $vol);
		$this->db->where('edt_year', $year);
		$this->db->where('edt_issue', $iss);
		// $this->db->where('date_created', 'desc');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_unique_editorials(){
		$this->db->select('CONCAT("Volume ", edt_volume, ", ", edt_year) as volume, edt_volume, edt_year, edt_issue');
		$this->db->from($this->editorials);
		$this->db->group_by('CONCAT(edt_volume, edt_year, edt_issue)');
		$this->db->order_by('edt_id', 'desc');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_citation($id)
	{
		$citation_arr = array();
		$this->db->select('art_author');
		$this->db->from($this->articles);
		$this->db->where('art_id', $id);
		$query = $this->db->get();
		$result = $query->result_array();

		//get author name
		$name = $result[0]['art_author'];
		//remove affiliations
		$detect_comma = explode(",", $name);


		if(count($detect_comma) > 1){
			$parts = explode(" ", $detect_comma[0]);
			$lastname = array_pop($parts);
			$a = implode(' ', $parts);
			$b = explode(' ',$a);
			$acro = '';
			foreach($b as $val)
			{
				if(strlen($val) > 2 && strpos($val, '.') !== true)
				{
					if(strpos($val, '-') !== false)
					{
						$x = explode('-',$val);
						foreach($x as $valx){
							$acro .= $valx[0] . '.';
						}
					}else{
						$acro .= $val[0] . '.';
					}
				}
			}
		}else{
			$parts = explode(" ", $name);
	
			$lastname = array_pop($parts);		
			$a = implode(' ', $parts);
			$b = explode(' ',$a);
			$acro = '';
			foreach($b as $val)
			{
				if(strlen($val) > 2 && strpos($val, '.') !== true)
				{
					if(strpos($val, '-') !== false)
					{
						$x = explode('-',$val);
						foreach($x as $valx){
							$acro .= $valx[0] . '.';
						}
					}else{
						$acro .= $val[0] . '.';
					}
				}

				
			}
		}

		$final = $lastname . ', ' . $acro;
	
		array_push($citation_arr, $final);

		$this->db->select('b.coa_name as coauthor');
		$this->db->from($this->articles . ' a');
		$this->db->join($this->coauthors . ' b', 'a.art_id = b.coa_art_id');
		$this->db->where('coa_art_id', $id);
		$query = $this->db->get();
		$coas = $query->result();

		


		$numItems = count($coas);
		$i = 0;
	  

		foreach ($coas as $row) {

			$name = $row->coauthor;

			$detect_comma = explode(",", $name);

			if(count($detect_comma) > 1){
				$parts = explode(" ", $detect_comma[0]);
				$lastname = array_pop($parts);
				$a = implode(' ', $parts);
				$b = explode(' ',$a);
				$acro = '';
				foreach($b as $val)
				{
					if(strlen($val) > 2 && strpos($val, '.') !== true)
					{
						if(strpos($val, '-') !== false)
						{
							$x = explode('-',$val);
							foreach($x as $valx){
								$acro .= $valx[0] . '.';
							}
						}else{
							$acro .= $val[0] . '.';
						}
					}
				}
			}else{
				$parts = explode(" ", $name);
				$lastname = array_pop($parts);
				$a = implode(' ', $parts);
				$b = explode(' ',$a);
				$acro = '';
				foreach($b as $val)
				{
					if(strlen($val) > 2 && strpos($val, '.') !== true)
					{
						if(strpos($val, '-') !== false)
						{
							$x = explode('-',$val);
							foreach($x as $valx){
								$acro .= $valx[0] . '.';
							}
						}else{
							$acro .= $val[0] . '.';
						}
					}
				}
			}
	

			if(++$i === $numItems) {
				$final = ' & ' . $lastname . ', ' . $acro;
			}else{
				$final = $lastname . ', ' . $acro;
			}
	
			array_push($citation_arr, $final);
		}

		$comma_separated = implode(', ', $citation_arr);
		return $comma_separated;
	
	}
	
	public function get_journals() {
		$this->db->select('jor_volume, jor_id, jor_issue, jor_year');
		$this->db->from($this->journals);
		$this->db->group_by('jor_volume');
		$this->db->order_by('jor_year', 'desc');
		$query = $this->db->get();
		return $query->result();

		// if ($result != null) {
		// 	foreach ($result as $row) {
		// 		$jor[$row->jor_id] = $row->jor_volume;
		// 	}

		// 	foreach ($jor as $j) {
		// 		$iss[$j] = $this->get_issue($j);
		// 	}

		// 	return $iss;
		// }
	}

	/** this function get all editorial boards */
	public function get_editorials() {
		$this->db->select('*');
		$this->db->from($this->editorials);
		$query = $this->db->get();
		$result = $query->result();
		return $result;
	}

	/** this function get all issues under each journals */
	public function get_issue($id) {
		$this->db->select('jor_issue,jor_id');
		$this->db->from($this->journals);
		$this->db->join($this->articles,'jor_id = art_jor_id');
		$this->db->where('jor_volume', $id);
		$this->db->group_by('jor_issue');
		// $this->db->where('jor_id IN (select art_jor_id from tblarticles)');

		$query = $this->db->get();
		return $query->result_array();
	}

	/** this function get all issues under each journals */
	public function get_issues($id) {
		// SELECT jor_id, jor_volume, jor_issue, (select count(*) from tblarticles where art_jor_id like jor_id) from tbljournals where jor_volume = 'III' group by jor_issue
		$this->db->select('*, (select count(*) from tblarticles where art_jor_id like jor_id) as articles');
		$this->db->from($this->journals);
		$this->db->where('jor_volume', $id);
		$query = $this->db->get();
		return $query->result();
	}

	/** this function get all articles */
	public function get_articles($vol,$iss) {
		$this->db->select('a.*, j.jor_volume, j.jor_issue, jor_issn, jor_description, jor_month, jor_year');
		$this->db->from($this->articles.' a');
		$this->db->join($this->journals. ' j','a.art_jor_id = j.jor_id');
		// $this->db->where('a.art_jor_id', $id);
		$this->db->where('j.jor_issue', $iss);
		$this->db->where('j.jor_volume', $vol);
		$this->db->order_by('a.art_title', 'asc');
		$query = $this->db->get();
		return $query->result();
	}

	/** this function get all articles */
	public function get_index($id) {
		$this->db->select('a.*, j.jor_volume, j.jor_issue, jor_issn');
		$this->db->from($this->articles.' a');
		$this->db->join($this->journals. ' j','a.art_jor_id = j.jor_id');

		if($id != null){
		$this->db->LIKE('a.art_title',  $id , 'after');
		}
		$this->db->order_by('a.art_title', 'asc');
		$query = $this->db->get();
		return $query->result();
	}

	/** this function get coauthors */
	public function get_coauthors($id) {
		$this->db->select('*');
		$this->db->from($this->coauthors);
		$this->db->where('coa_art_id', $id);
		$query = $this->db->get();
		return $query->result();
	}

	/** this function save client details upon visiting the website */
	public function save_client($post) {
		// $this->db->insert($this->clients, $post);
		$this->db->insert($this->downloads, $post);
		return $this->db->insert_id();
	}

	/** this function get pdf file to attach in email of client */
	public function get_pdf_to_sent($id) {
		$this->db->select('art_full_text_pdf');
		$this->db->from($this->articles);
		$this->db->where('art_id', $id);
		$query = $this->db->get();
		$data = $query->row_array();
		return $data['art_full_text_pdf'];
	}

	/** this function get article details */
	public function get_article($id) {
		$this->db->select('article.* , jor_volume, jor_issue');
		$this->db->from($this->articles . ' article');
		$this->db->join($this->journals, 'art_jor_id = jor_id');
		$this->db->where('art_id', $id);
		$query = $this->db->get();
		return $query->result();
	}

	/** this function get all journals */
	public function get_journal($id) {
		$this->db->select('*');
		$this->db->from($this->journals);
		$this->db->where('jor_id', $id);
		$query = $this->db->get();
		return $query->result();
	}

	/** this function get total numbers of clients */
	public function client_count($id) {
		$this->db->select('*');
		$this->db->from($this->clients);
		$this->db->where('clt_journal_downloaded_id', $id);
		$query = $this->db->get();
		return $query->num_rows();
	}

	/** this function get total numbers of clients */
	public function all_client() {
		$this->db->select('*');
		$this->db->from($this->clients);
		$query = $this->db->get();
		return $query->num_rows();
	}

	/** this function get total number of abstract viewers */
	public function all_hits() {
		$this->db->select('*');
		$this->db->from($this->abstracts);
		$query = $this->db->get();
		return $query->num_rows();
	}

	/** this function save client details who viewed an abstract  */
	public function save_abstract_hits($post) {
		$this->db->insert($this->abstracts, $post);
		return $this->db->affected_rows();
	}

	/** this function get total number of abstract viewers */
	public function hits_count($id) {
		$this->db->select('*');
		$this->db->from($this->abstracts);
		$this->db->where('hts_art_id', $id);
		$query = $this->db->get();
		return $query->num_rows();
	}

	/** this function file size */
	public function file_size($id) {
		$this->db->select('art_full_text_pdf');
		$this->db->from($this->articles);
		$this->db->where('art_id', $id);
		$query = $this->db->get();
		$result = $query->result_array();
		return $result[0]['art_full_text_pdf'];
	}

	/** this function retreive top 5 most downloaded article */
	public function top_five() {

		$this->db->select('a.*, j.jor_volume, j.jor_issue');
		$this->db->from($this->articles.' a');
		$this->db->join($this->journals. ' j','a.art_jor_id = j.jor_id');
		$this->db->where('a.art_id IN (SELECT clt_journal_downloaded_id from tblclients GROUP BY clt_journal_downloaded_id)');
		$this->db->order_by('a.art_title', 'asc');
		$this->db->limit(5);

		$query = $this->db->get();
		return $query->result();

		// echo $this->db->last_query();
	}

	/** this function retreive latest articles */
	public function latest_journal() {

		$this->db->select('a.*, j.jor_volume, j.jor_issue');
		$this->db->from($this->articles.' a');
		$this->db->join($this->journals. ' j','a.art_jor_id = j.jor_id');
		$this->db->where('jor_volume !=', 'Adv. Publication');
		$this->db->order_by('a.date_created', 'desc');
		$this->db->limit(5);

		$query = $this->db->get();
		return $query->result();
	}

	/**
	 * Get 13 Divisions info for Aim and Scope in landing page
	 *
	 * @return void
	 */
	public function getDivisions(){

		$this->db->select('CONCAT(title, " (", description , ")") as title, content, id');
		$this->db->from($this->divisions);
		$query = $this->db->get();
		return $query->result();
	}

	/** this function get author with coauthors */
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

		$comma_separated = implode(',& ', $a);
		return $comma_separated;

	}

	/** this function count total abstract hits of an article */
	public function count_abstract($id) {
		$this->db->select('*');
		$this->db->from($this->abstracts);
		$this->db->where('hts_art_id', $id);
		$query = $this->db->get();
		return $query->num_rows();
	}

	/** this function count total pdf downloads of an article */
	public function count_pdf($id) {
		$this->db->select('*');
		$this->db->from($this->clients);
		$this->db->where('clt_journal_downloaded_id', $id);
		$query = $this->db->get();
		return $query->num_rows();
	}

	/** this function retreive journal cover filename */
	public function get_cover($id) {
		$this->db->select('jor_cover');
		$this->db->from($this->journals);
		$this->db->where('jor_id', $id);
		$query = $this->db->get();
		$result = $query->result_array();

		if ($result[0]['jor_cover'] == '') {
			return 'unavailable.png';
		} else if (file_exists('./assets/uploads/cover/' . $result[0]['jor_cover'])) {
			return $result[0]['jor_cover'];
		} else {
			return 'unavailable.png';
		}
	}

	public function get_acoa_details($id, $data) {
		$this->db->select('*');
		$this->db->from($this->articles);
		$this->db->where('art_author', $data);
		$this->db->where('art_jor_id', $id);
		$query = $this->db->get();
		$result = $query->result();

		// if($query->result())
		// {
		//     return $query->result();
		// }
		// else
		// {
			$this->db->select('*');
			$this->db->from($this->coauthors);
			// $this->db->from($this->articles . ' a');
			// $this->db->join($this->coauthors . ' c', 'a.art_id = c.coa_art_id');
			// $this->db->where('art_jor_id',$id);
			$this->db->like('coa_name', $data);
			$query2 = $this->db->get();
			$result2 = $query2->result();
			// return $this->db->last_query();

		// 	return $query->result();
		// }

		return array('authors' => $result, 'coas' => $result2);
	}

	public function save_citation($post) {
		$this->db->insert($this->citations, $post);
		return $this->db->insert_id();
	}

	public function count_citation($id)
	{
		$this->db->select('*');
		$this->db->from($this->citations);
		$this->db->where('cite_art_id',$id);
		return $this->db->get()->num_rows();
		
	}
	
	// email for author when article/manuscript is downloaded/cited
	public function get_author_email($id){
		$this->db->select('art_email');
		$this->db->from($this->articles);
		$this->db->where('art_id', $id);
		$data = $this->db->get()->row_array();
		return $data['art_email'];
	}

	public function get_client_info_download($id){

		$this->db->select('clt_name, clt_affiliation, clt_country, clt_purpose, clt_download_date_time, clt_member, art_title, art_author');
		$this->db->from($this->clients);
		$this->db->join($this->articles, 'art_id = clt_journal_downloaded_id');
		$this->db->where('clt_id', $id);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_client_by_id($id){

		$this->db->select('*');
		$this->db->from($this->clients);
		$this->db->where('clt_id', $id);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_client_info_citation($id){
		$this->db->select('cite_name, cite_affiliation, cite_country, cite_email, art_title, cite_member, cite.date_created as cite_date, art_author');
		$this->db->from($this->citations . ' cite');
		$this->db->join($this->articles, 'art_id = cite_art_id');
		$this->db->where('row_id', $id);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_user_info($email){
		$this->db->select('p.*, otp, otp_ref_code');
		$this->db->from($this->profiles . ' p');
		$this->db->join($this->users . ' u', 'p.user_id = u.id');
		$this->db->where('u.email', $email);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_client_info_id($id){
		$this->db->select('p.*, otp, otp_ref_code');
		$this->db->from($this->profiles . ' p');
		$this->db->join($this->users . ' u', 'p.user_id = u.id');
		$this->db->where('u.id', $id);
		$query = $this->db->get();
		return $query->result();
	}

	public function getTitles(){
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('*');
		$oprs->from($this->titles);
		$query = $oprs->get();
		return $query->result();
	}

	public function getEducations(){
		$this->db->select('*');
		$this->db->from($this->educations);
		$query = $this->db->get();
		return $query->result();
	}

	public function advancePublication() {

		$this->db->select('a.*, j.jor_volume, j.jor_issue');
		$this->db->from($this->articles.' a');
		$this->db->join($this->journals. ' j','a.art_jor_id = j.jor_id');
		$this->db->where('jor_volume', 'Adv. Publication');
		$this->db->order_by('a.date_created', 'desc');
		$this->db->limit(5);

		$query = $this->db->get();
		return $query->result();
	}
	
	public function totalCitationsCurrentYear()
	{
		$this->db->select('*');
		$this->db->from($this->citations);
		$this->db->where('YEAR(date_created)',date('Y'));
		return $this->db->get()->num_rows();
		
	}

	public function totalDownloadsCurrentYear()
	{
		$this->db->select('*');
		$this->db->from($this->clients);
		$this->db->where('YEAR(clt_download_date_time)',date('Y'));
		return $this->db->get()->num_rows();
		
	}

	public function get_client_downloads($id){
		$this->db->select('dl_datetime, article.*, jor_volume, jor_issue');
		$this->db->from($this->downloads );
		$this->db->join($this->articles . ' article', 'dl_art_id = art_id');
		$this->db->join($this->journals, 'art_jor_id = jor_id');
		$this->db->where('dl_user_id', $id);
		$this->db->order_by('dl_datetime', 'DESC');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_client_email($id){
		$this->db->select('email');
		$this->db->from($this->users);
		$this->db->where('id', $id);
		$data = $this->db->get()->row_array();
		return $data['email'];
	}

	public function get_article_title_download_by_client($id){
		$this->db->select('art_title');
		$this->db->from($this->articles);
		$this->db->where('art_id', $id);
		$data = $this->db->get()->row_array();
		return $data['art_title'];
	}
}

/* End of file Client_journal_model.php */
?>