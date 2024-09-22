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

class Article_model extends CI_Model {
	
	private $articles = 'tblarticles';
	private $coauthors = 'tblcoauthors';
	private $journals = 'tbljournals';
	private $citations = 'tblcitations';
	public function __construct() {
		parent::__construct();
		$this->load->database(ENVIRONMENT);
	}

	/**
	 * Retrieve list of citees
	 *
	 * @return void
	 */
	public function get_all_citees()
	{
		$this->db->select('*');
		$this->db->from($this->citations.' c');
		$this->db->join($this->articles.' a','a.art_id = c.cite_art_id');
		$query = $this->db->get();
		return $query->result();
	}

	/**
	 * Count citations
	 *
	 * @param [type] $id
	 * @return void
	 */
	public function count_citation($id)
	{
		$this->db->select('*');
		$this->db->from($this->citations);
		$this->db->where('cite_art_id',$id);
		return 	$query = $this->db->get()->num_rows();
		
	}
	
	/**
	 * Retrieve all articles based on journal id
	 *
	 * @param   int  $id  journal id
	 *
	 * @return  array 
	 */
	public function get_all_articles() {
		$this->db->select('*');
		$this->db->from($this->articles.' a');
		$this->db->join($this->journals.' j','a.art_jor_id = j.jor_id');
		$this->db->order_by('a.art_title', 'asc');
		$query = $this->db->get();
		return $query->result();
	}
	
	/**
	 * Retrieve all articles based on journal id
	 *
	 * @param   int  $id  journal id
	 *
	 * @return  array 
	 */
	public function get_articles($id) {
		$this->db->select('*');
		$this->db->from($this->articles);
		$this->db->where('art_jor_id', $id);
		$this->db->order_by('date_created', 'desc');
		$query = $this->db->get();
		return $query->result();
	}

	/**
	 * Retrieve article details
	 *
	 * @param   int  $id  article id
	 *
	 * @return  array
	 */
	public function get_article($id) {
		$this->db->select('*');
		$this->db->from($this->articles);
		$this->db->where('art_id', $id);
		$query = $this->db->get();
		return $query->result();
	}

	/**
	 * Save article
	 *
	 * @param   array  $post  article data
	 *
	 * @return  [type]         [return description]
	 */
	public function save_article($post) {
		$this->db->insert($this->articles, $post);
		return $this->db->insert_id();
		save_log_ej(_UserIdFromSession(), 'just added an article.', $this->db->insert_id());
	}

	/**
	 * Update article
	 *
	 * @param   array  $post   article data
	 * @param   array  $where  condition
	 *
	 * @return  int          affected rows on update
	 */
	public function update_article($post, $where) {
		$this->db->update($this->articles, $post, $where);
		save_log_ej(_UserIdFromSession(), 'just updated an article.', $this->db->insert_id());
		return $this->db->affected_rows();
	}

	/**
	 * Delete article
	 *
	 * @param   array  $where  condition
	 *
	 * @return  int          affected rows
	 */
	public function delete_article($where) {
		$this->db->delete($this->articles, $where);
		save_log_ej(_UserIdFromSession(), 'just deleted an article.');
		return $this->db->affected_rows();
	}

	/**
	 * Save coauthors
	 *
	 * @param   array  $post  coauthors data
	 *
	 * @return  int         affected rows
	 */
	public function save_coauthors($post) {
		$this->db->insert($this->coauthors, $post);
		save_log_ej(_UserIdFromSession(), 'just added coauthor/s.');
		return $this->db->affected_rows();
	}

	/**
	 * Update coauthors
	 *
	 * @param   array  $post   coauthors data
	 * @param   array  $where  condition
	 *
	 * @return  int          affected rows
	 */
	public function update_coauthors($post, $where) {
		$this->db->update($this->coauthors, $post, $where);
		save_log_ej(_UserIdFromSession(), 'just updated a coauthor/s.');
		return $this->db->affected_rows();
	}

	/**
	 * Count total articles
	 *
	 * @return  int  total count of articles
	 */
	public function art_count() {
		$this->db->select('*');
		$this->db->from($this->articles);
		$query = $this->db->get();
		return $query->num_rows();
	}

	/**
	 * Count total articles under a journal
	 *
	 * @param   int  $id  journal id
	 * @return  int       count total articles under a journal
	 */
	public function count_article_by_journal($id) {
		$this->db->select('*');
		$this->db->from($this->articles);
		$this->db->where('art_jor_id', $id);
		$query = $this->db->get();
		return $query->num_rows();
	}

	/**
	 * Count total abstract hits of an article
	 *
	 * @param   int  $id  article id
	 *
	 * @return  int       total abstract hits of an article
	 */
	public function count_abstract($id) {
		$this->db->select('*');
		$this->db->from('tblhits_abstract');
		$this->db->where('hts_art_id', $id);
		$query = $this->db->get();
		return $query->num_rows();
	}

	/**
	 * Count total pdf downloads of an article
	 *
	 * @param   [type]  $id  [$id description]
	 *
	 * @return  [type]       [return description]
	 */
	public function count_pdf($id) {
		$this->db->select('*');
		$this->db->from('tblclients');
		$this->db->where('clt_journal_downloaded_id', $id);
		$query = $this->db->get();
		return $query->num_rows();
	}
	
	/**
	 * Retrieve top most downloaded article
	 *
	 * @return  array  total count of mose download article
	 */
	public function top_five() {
		$this->db->select('art_title, art_id, art_author');
		$this->db->from($this->articles);
		$this->db->order_by('art_title', 'asc');
		$query = $this->db->get();
		return $query->result();
	}

	/**
	 * Retrieve list of authors
	 *
	 * @return  array  list of authors
	 */
	public function get_authors() {
		$this->db->select('art_author');
		$this->db->distinct();
		$this->db->from($this->articles);
		$query = $this->db->get();
		return $query->result();
	}

	/**
	 * Retrieve articles base on selected author
	 *
	 * @param   string  $data  keyword
	 *
	 * @return  array         article titles
	 */
	public function get_authors_articles($data) {
		$this->db->select('*');
		$this->db->from($this->articles);
		$this->db->like('art_author', str_replace("%20", " ", $data), 'both');
		$query = $this->db->get();
		return $query->result();
	}

	/**
	 * Retrieve articles base on selected coauthors
	 *
	 * @param   array  $data  keyword
	 *
	 * @return  array         article titles
	 */
	public function get_coauthors_articles($data) {
		$this->db->select('*');
		$this->db->from($this->articles . ' a');
		$this->db->join($this->coauthors . ' b', 'a.art_id = b.coa_art_id');
		$this->db->like('coa_name', str_replace("%20", " ", $data), 'both');
		$query = $this->db->get();
		return $query->result();
	}

	/**
	 * Retrieve authors and coauthors names
	 *
	 * @param   array  $data  authors and coauthors name
	 *
	 * @return  array         authors and coauthors data
	 */
	public function get_author_coa($data) {
		$this->db->select('*');
		$this->db->from($this->articles);
		$this->db->like('art_author', str_replace("%20", " ", $data), 'both');
		$this->db->group_by('art_author');
		$query = $this->db->get();
		$result = $query->result();
		$this->db->select('*');
		$this->db->from($this->coauthors);
		$this->db->like('coa_name', str_replace("%20", " ", $data), 'both');
		$this->db->group_by('coa_name');
		$query2 = $this->db->get();
		$result2 = $query2->result();
		return array('authors' => $result, 'coas' => $result2);
	}
}

/* End of file Article_model.php */