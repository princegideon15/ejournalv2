<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Manuscript_model extends CI_Model {
	// oprs
	private $manus = 'tblmanuscripts';
	private $track = 'tbltracking';
	private $user = 'tblusers';
	private $coauthors = 'tblcoauthors';
	private $reviewers = 'tblreviewers';
	private $editors = 'tbleditorials';
	private $editors_review = 'tbleditors_review';
	private $scores = 'tblscores';
	private $non = 'tblnonmembers';
	private $committee = 'tblfinalreviews';
	private $logs = 'tbllogs';
	private $editorials = 'tbleditorials';
	private $status = 'tblstatus_types';
	private $publication = 'tblpublication_types';
	private $roles = 'tblroles';
	private $matrix = 'tblrevision_matrix';
	private $tech_rev_score = 'tbltech_rev_score';
	// skms
	private $skms_mem = 'tblpersonal_profiles';
	private $skms_exp = 'tblmembership_profiles';
	private $skms_aff = 'tblbusiness_address';
	private $skms_tit = 'tbltitles';
	private $skms_usr = 'tblusers';
	// ejournal
	private $articles = 'tblarticles';
	private $acoa = 'tblcoauthors';
	private $journals = 'tbljournals';
	public function __construct() {
		parent::__construct();
		$this->load->database(ENVIRONMENT);
		$this->load->model('User_model');
	}

	public function count_existing(){
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('*');
		$oprs->from($this->manus);
		$oprs->like('man_remarks', 'published', 'both');
		$query = $oprs->get();
		return $query->result();
	}

	/**
	 * Retrieve publishable manuscripts
	 *
	 * @param [int] $jor	journal	
	 * @return void
	 */
	public function get_publishable_manus($jor)
	{
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('*,CONCAT(man_volume, '.', man_issue, '.', man_year) as journal');
		$oprs->from($this->manus);
		$oprs->where('man_status',5);
		$oprs->having('journal', $jor);
		$query = $oprs->get();
		return $query->result();
	}

	/**
	 * Retrieve exisintg journal issues
	 *
	 * @return void
	 */
	public function get_oprs_journal()
	{
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('man_title, CONCAT(man_volume, '.', man_issue, '.', man_year) as journal,row_id, man_volume, man_issue, man_year, count(*) as articles');
		$oprs->where('man_status',5);
		$oprs->group_by('journal');
		$oprs->order_by('man_year', 'desc');
		$oprs->from($this->manus);
		$query = $oprs->get();
		return $query->result();
	}

	/**
	 * Retrieve all manuscripts by user role
	 *
	 * @param [string] $man_source
	 * @param [tystringpe] $email
	 * @return void
	 */
	public function get_manus($role_id) {
		
		$oprs = $this->load->database('dboprs', TRUE);

		if($role_id == 1){ // author
			$oprs->select('m.*, p.publication_desc');
			$oprs->from($this->manus . ' m');
			$oprs->join($this->publication . ' p', 'm.man_type = p.id');
			$oprs->where('man_user_id', _UserIdFromSession());
		}
		// else if ($role_id == 5){ // technical desk editor
		// 	$oprs->select('m.*, p.publication_desc, status_desc as status, status_class');
		// 	$oprs->from($this->manus . ' m');
		// 	$oprs->join($this->publication . ' p', 'm.man_type = p.id');
		// 	$oprs->join($this->status . ' s', 'm.man_status = s.status_id');
		// 	$oprs->where_in('man_status', [1,15]);
		// }
		else if ($role_id == 6){ // editor in chief
			$oprs->select('m.*, p.publication_desc, status_desc as status, status_class');
			$oprs->from($this->manus . ' m');
			$oprs->join($this->publication . ' p', 'm.man_type = p.id');
			$oprs->join($this->status . ' s', 'm.man_status = s.status_id');
			$oprs->join($this->editors_review . ' e', 'm.row_id = e.edit_man_id');
			$oprs->where_in('man_status', [2,8,12,13,16]);
			$oprs->group_by('man_trk_no');
		}else if ($role_id >= 7 && $role_id <= 10 ){ // associate
			$oprs->select('m.*, p.publication_desc, status_desc as status, status_class');
			$oprs->from($this->manus . ' m');
			$oprs->join($this->publication . ' p', 'm.man_type = p.id');
			$oprs->join($this->status . ' s', 'm.man_status = s.status_id');
			$oprs->join($this->editors_review . ' e', 'm.row_id = e.edit_man_id');
			$oprs->where('man_status', 3);
			$oprs->where('edit_usr_id', _UserIdFromSession());
		}else if ($role_id >= 11 && $role_id <= 14 ){ // cluster
			$oprs->select('m.*, p.publication_desc, status_desc as status, status_class');
			$oprs->from($this->manus . ' m');
			$oprs->join($this->publication . ' p', 'm.man_type = p.id');
			$oprs->join($this->status . ' s', 'm.man_status = s.status_id');
			$oprs->join($this->editors_review . ' e', 'm.row_id = e.edit_man_id');
			$oprs->where('edit_status', NULL);
			$oprs->where('edit_usr_id', _UserIdFromSession());
		}else if($role_id == 16){ // peer reviwer
				$oprs->select('m.*,s.scr_status, s.date_reviewed, r.rev_hide_auth, scr_nda');
				$oprs->from($this->scores . ' s');
				$oprs->join($this->reviewers . ' r', 's.scr_man_rev_id = r.rev_id');
				$oprs->join($this->manus . ' m', 'm.row_id = s.scr_man_id');
				$oprs->where('r.rev_id', _UserIdFromSession());
				$oprs->where('r.rev_status', 1);
				$oprs->group_by('m.row_id');
		}else if($role_id == 17){ // copy editor
			$oprs->select('m.*, p.publication_desc, status_desc as status, status_class, IFNULL(x.mtx_file,"") as file');
			$oprs->from($this->manus . ' m');
			$oprs->join($this->matrix . ' x', 'm.row_id = x.mtx_man_id', 'left');
			$oprs->join($this->publication . ' p', 'm.man_type = p.id');
			$oprs->join($this->status . ' s', 'm.man_status = s.status_id');
			$oprs->where('man_status', 7);
		}else if($role_id == 15){ // layout artist
			$oprs->select('m.*, p.publication_desc, status_desc as status, status_class, IFNULL(x.mtx_file,"") as file');
			$oprs->from($this->manus . ' m');
			$oprs->join($this->matrix . ' x', 'm.row_id = x.mtx_man_id', 'left');
			$oprs->join($this->publication . ' p', 'm.man_type = p.id');
			$oprs->join($this->status . ' s', 'm.man_status = s.status_id');
			$oprs->where('man_status', 11);
		}else{ // super admin
				$oprs->select('m.*, p.publication_desc, status_desc as status, status_class');
				$oprs->from($this->manus . ' m');
				$oprs->join($this->publication . ' p', 'm.man_type = p.id', 'left');
				$oprs->join($this->status . ' s', 'm.man_status = s.status_id', 'left');
		}
		$query = $oprs->get();
		return $query->result();
	}

	/**
	 * Retrieve manuscript data by id
	 *
	 * @param [int] $id		row_id
	 * @return void
	 */
	public function get_manus_info($id) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('*');
		$oprs->from($this->manus);
		$oprs->join($this->publication . ' p', 'man_type = p.id', 'left');
		$oprs->where('row_id', $id);
		$query = $oprs->get();
		return $query->result();
	}

	/**
	 * Retrieve manuscript title only
	 *
	 * @param [kint] $id		row_id
	 * @return void
	 */
	public function get_manus_title($id){
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('man_title');
		$oprs->from($this->manus);
		$oprs->where('row_id', $id);
		$row = $oprs->get()->row();
		return $row->man_title;
	}

	/**
	 * Save manuscript data 
	 *
	 * @param [array] $data
	 * @return void
	 */
	public function save_manuscript($data) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->insert($this->manus, $data);
		$output = $oprs->insert_id();
		save_log_oprs(_UserIdFromSession(), 'uploaded', $output, _UserRoleFromSession());
		return $output;
	}

	/**
	 * Save editorial review 
	 *
	 * @param [array] $data
	 * @return void
	 */
	public function save_editorial_review($data) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->insert($this->editors_review, $data);
		$output = $oprs->insert_id();
		save_log_oprs(_UserIdFromSession(), 'editorial review', $output, _UserRoleFromSession());
		return $output;
	}

	

	/**
	 * Save final review of editorial board/publication committee
	 *
	 * @param [array] $data
	 * @return void
	 */
	public function final_review($data) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->insert($this->committee, $data);
		$output = $oprs->insert_id();
		save_log_oprs(_UserIdFromSession(), 'final review', $output, _UserRoleFromSession());
		return $output;
	}

	/**
	 * Update processing of a manuscript
	 *
	 * @param [array] $post
	 * @param [array] $wherefupdate_manuscript_status
	 * @param [int] $flag
	 * @return void
	 */
	public function process_manuscript($post, $where, $flag = null) {
		//to fix
		$action = (($flag == 1) ? 'added reviewers for'
				: ((($flag == 2) ? 'uploaded final'
				// : ((($flag == 3) ? 'approved'
				: 'published')));

		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->update($this->manus, $post, $where);
		if ($flag > 0 && (_UserRoleFromSession() == 3 || _UserRoleFromSession() == 1)) {
			save_log_oprs(_UserIdFromSession(), $action, $where['row_id'], _UserRoleFromSession());
		}
	}

	public function update_manuscript_status($post, $where){
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->update($this->manus, $post, $where);
		// return $oprs->last_query();
	}

	/**
	 * Save tracking
	 *
	 * @param [array] $data
	 * @return void
	 */
	public function tracking($data) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->insert($this->track, $data);
	}

	/**
	 * Retrieve tracking by id
	 *
	 * @param [int] $id		trk_man_id
	 * @return void
	 */
	public function tracker($id) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('t.*, usr_desc as role_name');
		$oprs->from($this->track . ' t');
		// $oprs->join($this->manus . ' m', 'trk_man_id = m.row_id');
		$oprs->join($this->user . ' u', 't.trk_processor = u.usr_id', 'left');
		// $oprs->join($this->roles . ' r', 'u.usr_role = r.role_id', 'left');
		$oprs->where('t.trk_man_id', $id);
		$oprs->order_by('trk_process_datetime', 'desc');
		$query = $oprs->get();
		return $query->result();
	}

	/**
	 * Retrieve tracking to display in notification
	 *
	 * @return void
	 */
	public function notif_tracker(){
		$data = array();
		$x = array();
		$oprs = $this->load->database('dboprs', TRUE);

		$oprs->select('t.*, man_title, usr_username, usr_id');
		$oprs->from($this->track . ' t');
		$oprs->join($this->manus . ' m', 'm.row_id = t.trk_man_id');
		$oprs->join($this->user, 'usr_id = trk_processor', 'left');
		$oprs->order_by('trk_process_datetime', 'desc');
		$oprs->limit(10);
		$query = $oprs->get();
		$output = $query->result();

		foreach($output as $row){
			if($row->trk_source == '_sk' || $row->trk_source == '_sk_r'){
				$output2 = $this->User_model->get_member($row->trk_processor);
				foreach($output2 as $row2){
					$user_name =  $row2->pp_first_name . ' ' . $row2->pp_middle_name . ' ' . $row2->pp_last_name;
				}
			}else{
				$user_name = $row->usr_username;
			}

			array_push($x, $row->trk_process_datetime);
			$data[] = array('trk_process_datetime' => $row->trk_process_datetime, 
						'man_title' => $row->man_title,
						'trk_processor' => $row->trk_processor,
						'row_id' => $row->row_id,
						'trk_remarks' => $row->trk_remarks,
						'trk_description' => $row->trk_description,
						'user_name' => $user_name,
					 	'trk_source' => $row->trk_source);

		}
		return $data;
	}
	
	/**
	 * Retrieve all members
	 *
	 * @return void
	 */
	public function get_members() {
		$members = $this->load->database('members', true);
		$members->select('*, u.usr_grp_id');
		$members->from($this->skms_mem . ' a');
		$members->join($this->skms_exp . ' b', 'a.pp_usr_id = b.mpr_usr_id');
		$members->join($this->skms_aff . ' c', 'a.pp_usr_id = c.bus_usr_id');
		$members->join($this->skms_tit . ' d', 'a.pp_title = d.title_id');
		$members->join($this->skms_usr . ' u', 'a.pp_usr_id = u.usr_id');
		$members->where('mpr_h_index >', '0');
		$members->order_by('a.pp_first_name', 'asc');
		$query = $members->get();
		return $query->result();
	}

	/**
	 * Retrieve non members (UNUSED)
	 *
	 * @return void
	 */
	public function get_non_members() {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('*');
		$oprs->from($this->non);
		$query = $oprs->get();
		return $query->result();
	}

	/**
	 * Retrieve manuscript data for email
	 *
	 * @param [int] $id		row_id
	 * @return void
	 */
	public function get_manus_for_email($id) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('*');
		$oprs->from($this->manus);
		$oprs->where('row_id', $id);
		$query = $oprs->get();
		return $query->result();
	}

	/**
	 * Save coauthors
	 *
	 * @param [type] $data
	 * @return void
	 */
	public function save_coauthors($data) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->insert($this->coauthors, $data);
		return $oprs->affected_rows();
		// save_log_oprs(_UserIdFromSession(), 'added coauthor/s', $data['coa_man_id']);
	}

	/**
	 * Retrieve authors and coauthors
	 *
	 * @return void
	 */
	public function get_author_coa() {
		$oprs = $this->load->database('dboprs', TRUE);
		$a = array();
		$this->db->select('*');
		$this->db->from($this->articles);
		$this->db->group_by('art_author', 'asc');
		$query = $this->db->get();
		$result = $query->result();
		foreach ($result as $row) {
			array_push($a, $row->art_author);
		}
		$this->db->select('*');
		$this->db->from($this->coauthors);
		$this->db->group_by('coa_name', 'asc');
		$query2 = $this->db->get();
		$result2 = $query2->result();
		foreach ($result2 as $row) {
			array_push($a, $row->coa_name);
		}
		$oprs->select('*');
		$oprs->from($this->manus);
		$oprs->group_by('man_author', 'asc');
		$query3 = $oprs->get();
		$result3 = $query3->result();
		foreach ($result3 as $row) {
			array_push($a, $row->man_author);
		}
		$oprs->select('*');
		$oprs->from($this->coauthors);
		$oprs->group_by('coa_name', 'asc');
		$query4 = $oprs->get();
		$result4 = $query4->result();
		foreach ($result4 as $row) {
			array_push($a, $row->coa_name);
		}
		return $a;
	}

	/**
	 * Retrieve unique journal volume
	 *
	 * @return void
	 */
	public function get_unique_journal() {
		$this->db->select('jor_volume');
		$this->db->distinct();
		$this->db->from($this->journals);
		$query = $this->db->get();
		return $query->result();
	}

	/**
	 * Retrieve issues/volume by journal
	 *
	 * @param [string] $jor		jor_volume
	 * @return void
	 */
	public function get_issues($jor) {
		$this->db->select('*');
		$this->db->from($this->journals);
		$this->db->where('jor_volume', $jor);
		$query = $this->db->get();
		return $query->result();
	}
	
	/**
	 * Save reviewer data
	 *
	 * @param [array] $post
	 * @param [int] $id
	 * @return void
	 */
	public function save_reviewers($post, $id) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->insert($this->reviewers, $post);
		save_log_oprs(_UserIdFromSession(), 'added reviewers for', $id, _UserRoleFromSession());
		return $oprs->affected_rows();
	}

	/**
	 * Save reviewer data
	 *
	 * @param [array] $post
	 * @param [int] $id
	 * @return void
	 */
	public function save_editors($post, $id) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->insert($this->editors, $post);
		save_log_oprs(_UserIdFromSession(), 'added editor/s for', $id, _UserRoleFromSession());
		return $oprs->affected_rows();
	}

	/**
	 * Retrieve reviewers by manuscript id
	 *
	 * @param [int] $id
	 * @param [string] $time
	 * @return void
	 */
	public function get_reviewers($id, $time) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('*');
		$oprs->from($this->reviewers);
		$oprs->where('rev_man_id', $id);
		if ($time != 0) {
			$oprs->where('date_created', urldecode($time));
		}
		$query = $oprs->get();
		return $query->result();
	}
	/**
	 * Retrieve editirs by manuscript id
	 *
	 * @param [int] $id
	 * @return void
	 */
	public function get_editors($id) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('*');
		$oprs->from($this->editorials);
		$oprs->where('edit_man_id', $id);
		$query = $oprs->get();
		return $query->result();
	}

		/**
	 * Retrieve reviewers by manuscript id per editor
	 *
	 * @param [int] $id
	 * @param [string] $time
	 * @return void
	 */
	public function get_reviews($id) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('*');
		$oprs->from($this->reviewers);
		// $oprs->join($this->manus . ' m', 'm.row_id = rev_man_id');
		$oprs->join($this->scores, 'scr_man_rev_id = rev_id');
		// $oprs->join($this->editors, 'edit_man_id = m.row_id');
		// $oprs->where('scr_status > ', 3);
		$oprs->where('scr_man_id', $id);
		$oprs->group_by('rev_id');
		// $oprs->where('edit_id', _UserIdFromSession());
		$query = $oprs->get();
		return $query->result();
	}


	/**
	 * Retreive reviewers to count
	 *
	 * @param [type] $id
	 * @param [type] $stat
	 * @return void
	 */
	public function get_reviewers_display($id, $stat = null) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('*');
		$oprs->from($this->reviewers);
		$oprs->where('rev_man_id', $id);
		if (isset($stat)) {
			$oprs->where('rev_status', 1);
			$oprs->or_where('rev_status', 2);
		}
		$query = $oprs->get();
		return $query->result();
	}
	
	/**
	 * Retrieve reviewer data by id
	 *
	 * @param [int] $id		rev_id
	 * @return void
	 */
	public function get_rev_info($id) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('*');
		$oprs->from($this->reviewers);
		$oprs->where('rev_id', $id);
		$query = $oprs->get();
		return $query->result();
	}
	
	/**
	 * Update reivewer data
	 *
	 * @param [array] $post
	 * @param [array] $where
	 * @return void
	 */
	public function update_reviewer($post, $where) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->update($this->reviewers, $post, $where);
	}

	/**
	 * Update reivewer data
	 *
	 * @param [array] $post
	 * @param [array] $where
	 * @return void
	 */
	public function update_editor($post, $where) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->update($this->editors, $post, $where);
	}

	/**
	 * Retrieve reviewer score/review by id
	 *
	 * @param [int] $id		scr_man_id
	 * @return void
	 */
	public function get_reviewers_w_score($id) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('*');
		$oprs->from($this->scores);
		$oprs->where('scr_man_id', $id);
		$oprs->group_start();
		$oprs->where('scr_status', 4);
		$oprs->or_where('scr_status', 5);
		$oprs->or_where('scr_status', 6);
		$oprs->or_where('scr_status', 7);
		$oprs->group_end();
		$query = $oprs->get();
		return $query->result();;
	}

	/**
	 * Retrieve all manuscripts
	 *
	 * @return void
	 */
	public function get_all_manus_info() {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('*');
		$oprs->from($this->manus);
		$query = $oprs->get();
		return $query->result();
	}

	/**
	 * Retrieve member expertise
	 *
	 * @return void
	 */
	public function get_members_expertises() {
		$members = $this->load->database('members', true);
		$members->select('*');
		$members->from($this->skms_mem . ' a');
		$members->join($this->skms_exp . ' b', 'a.pp_usr_id = b.mpr_usr_id');
		$members->order_by('a.pp_first_name', 'asc');
		$query = $members->get();
		return $query->result();
	}

	/**
	 * Retrieve reviewer status
	 *
	 * @return void
	 */
	public function get_reviewer_status() {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('*');
		$oprs->from($this->reviewers);
		$oprs->where('rev_status', 2);
		$query = $oprs->get();
		return $query->result();
	}

	/**
	 * Retrieve review status
	 *
	 * @return void
	 */
	public function get_review_status() {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('r.*');
		$oprs->from($this->reviewers . ' r');
		$oprs->join($this->scores . ' s', 'r.rev_id = s.scr_man_rev_id');
		$oprs->where('r.rev_status', 1);
		$oprs->where('s.scr_status', 2);
		$query = $oprs->get();
		return $query->result();
	}

	/**
	 * Retrieve reviewer data by id
	 *
	 * @param [int] $id		rev_id
	 * @return void
	 */
	public function get_reviewer_by_id($id){
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('*');
		$oprs->from($this->reviewers);
		$oprs->where('rev_id', $id);
		$query = $oprs->get();
		return $query->result();
	}

	/**
	 * Retrieve reviewer data by id
	 *
	 * @param [int] $id		rev_id
	 * @return void
	 */
	public function get_editor_by_id($id){
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('*');
		$oprs->from($this->editors);
		$oprs->where('edit_id', $id);
		$query = $oprs->get();
		return $query->result();
	}

	/**
	 * Retrieve reviewer name only by id
	 *
	 * @param [int] $id		rev_id
	 * @return void
	 */
	public function get_reviewer_name($id) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('rev_name');
		$oprs->from($this->reviewers);
		$oprs->where('rev_id', $id);
		$query = $oprs->get();
		$result = $query->result_array();
		return $result[0]['rev_name'];
	}

	/**
	 * Retrieve manuscript to count
	 *
	 * @param [string] $id
	 * @return void
	 */
	public function count_manus($id) {
		$oprs = $this->load->database('dboprs', TRUE);
		if ($id == 'n') {
			$table = $this->manus;
			$where = $oprs->where('man_status', 1);
		} elseif ($id == 'lr') {
			$table = $this->reviewers;
			$where = $oprs->where('rev_status', 3);
		} elseif ($id == 'dr') {
			$table = $this->reviewers;
			$where = $oprs->where('rev_status', '0');
		} else if($id == 'total') {
			$table = $this->manus;
			$where = $oprs->where('date_created', 'LIKE', '%' . date('Y') . '%');
		} else {
			$table = $this->scores;
			$where = $oprs->where('scr_status', 3);
		}
		$oprs->select('*');
		$oprs->from($table);
		$where;
		$query = $oprs->get();
		return $query->num_rows();
	}

	public function count_manus_by_type($type, $year){
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('*');
		$oprs->from($this->manus);	
		$oprs->where('man_type', $type);
		$oprs->like('date_created', $year, 'both');
		$query =  $oprs->get();
		return $query->num_rows();
	}

	/**
	 * Retrieve reviewer to hide
	 *
	 * @param [type] $id
	 * @param [type] $user
	 * @return void
	 */
	public function hide_rev($id, $user) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('*');
		$oprs->from($this->reviewers);
		$oprs->where('rev_man_id', $id);
		$oprs->where('rev_email', $user);
		$query = $oprs->get();
		return $query->result();
	}
	
	/**
	 * Retrieve journal
	 *
	 * @param [string] $volume		jor_volume
	 * @param [int] $issue		jor_issue
	 * @return void
	 */
	public function check_journal($volume, $issue) {
		$this->db->select('jor_id');
		$this->db->from($this->journals);
		$this->db->where('jor_volume', $volume);
		$this->db->where('jor_issue', $issue);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result['jor_id'];
	}

	/**
	 * Save new journal
	 *
	 * @param [array] $data
	 * @return void
	 */
	public function create_journal($data) {
		$this->db->insert($this->journals, $data);
		return $this->db->insert_id();
	}

	/**
	 * Save new article
	 *
	 * @param [array] $data
	 * @return void
	 */
	public function add_article($data) {
		$this->db->insert($this->articles, $data);
		return $this->db->insert_id();
	}

	/**
	 * Save coauthors
	 *
	 * @param [array] $data
	 * @return void
	 */
	public function save_acoa($data) {
		$this->db->insert($this->acoa, $data);
		return $this->db->affected_rows();
	}
	
	/**
	 * Retreive journal years only
	 *
	 * @return void
	 */
	public function get_unique_journal_year() {
		$this->db->select('jor_year');
		$this->db->distinct();
		$this->db->from($this->journals);
		$this->db->order_by('jor_year', 'desc');
		$query = $this->db->get();
		return $query->result();
	}
	
	/**
	 * Retreive journal by year
	 *
	 * @param [int] $value		jor_year
	 * @return void
	 */
	public function get_journal_by_year($value) {
		$this->db->select('*');
		$this->db->from($this->journals);
		$this->db->where('jor_year', $value);
		$query = $this->db->get();
		return $query->result();
	}

	/**
	 * Count total articles 
	 *
	 * @param [type] $id
	 * @return void
	 */
	public function get_manuscripts($status) {
		
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('m.*, p.publication_desc, status_desc as status, status_class');
		$oprs->from($this->manus . ' m');
		$oprs->join($this->publication . ' p', 'm.man_type = p.id', 'left');
		$oprs->join($this->status . ' s', 'm.man_status = s.status_id', 'left');

		if($status > 0){
			if($status == 2){
				$oprs->where_in('m.man_status', [2,3,4,5]);
			}else if($status == 10){
				$oprs->where_in('m.man_status', [10,17]);
			}else if($status == 9){
				$oprs->where_in('m.man_status', [9,18]);
			}
			else{
				$oprs->where('m.man_status', $status);
			}
		}

		$query = $oprs->get();
		return $query->result();
	}
	
	/**
	 * Count total articles under a journal
	 *
	 * @param [type] $id
	 * @return void
	 */
	public function count_article_by_journal($id) {
		$this->db->select('*');
		$this->db->from($this->articles);
		$this->db->where('art_jor_id', $id);
		$query = $this->db->get();
		return $query->num_rows();
	}
	
	/**
	 * Count total articles under a journal
	 *
	 * @param [string] $vol		jor_volume
	 * @param [int] $iss		jor_issue
	 * @param [int] $year		jor_year
	 * @return void
	 */
	public function get_jor_id($vol, $iss, $year) {
		$this->db->select('jor_id');
		$this->db->from($this->journals);
		$this->db->where('jor_volume', $vol);
		$this->db->where('jor_issue', $iss);
		$this->db->where('jor_year', $year);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result['jor_id'];
	}

	/**
	 * Retrieve reviewers that submitted reviewes
	 *
	 * @return void
	 */
	public function get_reviewers_reviewed() {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('m.*, m.row_id as man_id, s.scr_man_rev_id, scr_status, s.scr_file, s.scr_total, date_reviewed, scr_cert');
		$oprs->from($this->manus . ' m');
		$oprs->join($this->scores . ' s', 's.scr_man_id = m.row_id');
		// $oprs->join($this->reviewers . ' r', 'r.rev_man_id = m.row_id');
		$oprs->where('s.scr_crt_1 >', 0);
		$oprs->order_by('date_reviewed', 'desc');
		$query = $oprs->get();
		return $query->result();
	}

     /**
	 * Retrieve NDAs
	 *
	 * @return void
	 */
	public function get_ndas() {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('m.*, s.scr_man_rev_id, s.scr_status, s.scr_total, s.scr_nda, date_reviewed');
		$oprs->from($this->manus . ' m');
		$oprs->join($this->scores . ' s', 'm.row_id = s.scr_man_id');
		$oprs->where('s.scr_crt_1 >', 0);
		$oprs->where('s.scr_nda !=', '');
		$query = $oprs->get();
		return $query->result();
	}

	/**
	 * Retrieve reviewed manuscriptss
	 *
	 * @return void
	 */
	public function get_reviewed_manuscript() {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('m.*, s.scr_man_rev_id, s.scr_status, s.scr_total, max(date_reviewed) as date_reviewed , m.man_title as mantitle');
		$oprs->from($this->manus . ' m');
		$oprs->join($this->scores . ' s', 'm.row_id = s.scr_man_id');
		$oprs->where('s.scr_crt_1 >', 0);
		$oprs->group_by('m.row_id');
		$oprs->order_by('m.man_title', 'asc');
		$query = $oprs->get();
		return $query->result();
	}

	/**
	 * Retrieve reviewed manuscripts
	 *
	 * @return void
	 */
	public function get_completed_reviews() {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('m.*, s.scr_man_rev_id, s.scr_status, count(scr_status) as score, s.scr_total, max(date_reviewed) as date_reviewed , m.man_title as mantitle');
		$oprs->from($this->manus . ' m');
		$oprs->join($this->scores . ' s', 'm.row_id = s.scr_man_id');
		$oprs->where('s.scr_status >', 3);
		$oprs->where('m.man_status', 3);
		$oprs->group_by('m.row_id');
		$oprs->having('count(score) >', 1);
		$oprs->order_by('m.man_title', 'asc');
		$query = $oprs->get();
		return $query->result();
	}

	/**
	 * Retrieve default author
	 *
	 * @param [int] $id		pp_usr_id
	 * @return void
	 */
	public function default_auth($id) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('*');
		$oprs->from($this->non);
		$oprs->where('non_usr_id', $id);
		$query = $oprs->get();
		$row = $query->num_rows();
		if ($row > 0) {
			return $query->result();
		} else {
			$members = $this->load->database('members', true);
			$members->select('*');
			$members->from($this->skms_mem . ' a');
			$members->join($this->skms_exp . ' b', 'a.pp_usr_id = b.mpr_usr_id');
			$members->join($this->skms_aff . ' c', 'a.pp_usr_id = c.bus_usr_id');
			$members->where('a.pp_usr_id', $id);
			$query = $members->get();
			$row = $query->num_rows();
			if ($row > 0) {
				return $query->result();
			} else {
				$members = $this->load->database('members', true);
				$members->select('*');
				$members->from($this->skms_mem);
				$members->where('pp_usr_id', $id);
				$query = $members->get();
				return $query->result();
			}
		}
	}

	/**
	 * Retrieve final review data
	 *
	 * @param [int] $id		com_man_id
	 * @return void
	 */
	public function get_com_rev($id){
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('*');
		$oprs->from($this->committee);
		$oprs->where('com_man_id', $id);
		$query = $oprs->get();
		return $query->result();
	}

	/**
	 * Delete manuscript by id
	 *
	 * @param [string] $where
	 * @return void
	 */
	public function remove_manus_by_man_id($where){
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->delete($this->manus, $where);
	}

	/**
	 * Delete reviewers by id
	 *
	 * @param [string] $where
	 * @return void
	 */
	public function remove_reviewers_by_man_id($where){
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->delete($this->reviewers, $where);
	}

	/**
	 * Delete tracking by id
	 *
	 * @param [array] $where
	 * @return void
	 */
	public function remove_tracking_by_man_id($where){
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->delete($this->track, $where);
	}


	/**
	 * Delete coauthors by id
	 *
	 * @param [array] $where
	 * @return void
	 */
	public function remove_coa_by_man_id($where){
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->delete($this->coauthors, $where);
	}


	/**
	 * Delete activity log by id
	 *
	 * @param [array] $where
	 * @return void
	 */
	public function remove_logs_by_man_id($where){
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->delete($this->logs, $where);
	}
	

	/**
	 * Retrieve managing editor email
	 *
	 * @param [array] $where
	 * @return void
	 */
	public function get_man_editor_email($id){
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('usr_username');
		$oprs->from($this->user);
		$oprs->join($this->track, 'usr_id = trk_processor');
		$oprs->where('trk_man_id', $id);
		$query = $oprs->get()->row()->usr_username;
		return $query;
	}

	/**
	 * Retreive member personal profile
	 *
	 * @param [int] $id		pp_usr_id
	 * @return void
	 */
	public function get_member_info($id){
		$skms = $this->load->database('members', TRUE);
		$skms->select('CONCAT(pp_first_name, " ", pp_middle_name, " ", pp_last_name) as NAME, pp_email, (select title_name from tbltitles where title_id like pp_title) as TITLE');
		$skms->from($this->skms_mem);
		$skms->where('pp_usr_id', $id);
		$query = $skms->get();
		return $query->result();
	}

	public function update_remarks($post, $where) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->update($this->manus, $post, $where);
	}

	public function check_member($id){
		$skms = $this->load->database('members', TRUE);
		$skms->select('usr_grp_id');
		$skms->from($this->skms_usr);
		$skms->where('usr_id', $id);
		$query = $skms->get();
		$result = $query->result_array();
		return $result[0]['usr_grp_id'] ?? '';
	}

	public function search($search, $filter){
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('m.*, GROUP_CONCAT(c.coa_name SEPARATOR ", ") AS coas');
		$oprs->from($this->manus . ' m');
		$oprs->join($this->coauthors . ' c', 'm.row_id = c.coa_man_id', 'left');

		if($filter == 1){ // keyword
			$oprs->like('man_keywords', $search);
		}else if($filter == 2){ // author or coauthors
			$oprs->like('man_author', $search, 'both');
			$oprs->or_like('coa_name', $search, 'both');
		}else{ // title
			$oprs->like('man_title', $search, 'both');
		}


		$oprs->order_by('man_title', 'asc');
		$oprs->group_by('row_id');
		$query = $oprs->get();
		return $query->result();

	}

	public function get_author_category($id){
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('usr_category');
		$oprs->from($this->user);
		$oprs->where('usr_id',$id);
		$query = $oprs->get();
		$result = $query->result_array();
		$category = $result[0]['usr_category']; 
		return $category;
	}
	
	public function get_corresponding_author($id){
		
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('usr_category');
		$oprs->from($this->user);
		$oprs->where('usr_id',$id);
		$query = $oprs->get();
		$result = $query->result_array();
		$category = $result[0]['usr_category']; 
		$data = array();
		
		if($category == 1){ // nrcp member
			$output = $this->User_model->get_member($id);

			foreach($output as $row){
				$data = [
					    'user_id' => $row->pp_usr_id,
						'name' => $row->pp_first_name . ' ' . $row->pp_last_name,
						'affiliation' => $row->bus_name,
						'email' => $row->pp_email,
						'author_type' => 'Member'
				];
			}

			return $data;
		}else{ // non member
			$output = $this->User_model->get_corresponding_author($id);

			foreach($output as $row){
				$data = [
					    'user_id' => $row->user_id,
						'name' => $row->first_name . ' ' . $row->last_name,
						'affiliation' => $row->affiliation,
						'email' => $row->usr_username,
						'author_type' => 'Non-member'
				];
			}
			
			return $data;
		}

	}

	public function unique_title($title){
		
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('*');
		$oprs->from($this->manus);
		$oprs->where('man_title', $title);
		$query = $oprs->get();
		$rows = $query->num_rows();
		if ($rows > 0) {
			return 'false';
		} else {
			return 'true';
		}
	}

	public function get_published_manus(){
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('m.*, a.art_id');
		$oprs->from($this->manus . ' m');
		$oprs->join('dbej.tblarticles a', 'm.man_title = a.art_title');
		$oprs->where('man_status' , 16);
		$query = $oprs->get();
		return $query->result();
	}

	public function get_manuscripts_publication_status($pub_id, $status, $editor_type, $from = null, $to = null){

		$oprs = $this->load->database('dboprs', TRUE);

		// Check if the ID exists in the table
		$exists = $oprs->select('*')->from($this->publication)->where('id', $pub_id)->get()->num_rows();

		$oprs->select('m.*, p.publication_desc, status_desc as status, status_class');
		$oprs->from($this->manus . ' m');
		$oprs->join($this->publication . ' p', 'm.man_type = p.id', 'left');
		$oprs->join($this->status . ' s', 'm.man_status = s.status_id');

		if($from > 0 && $to > 0){
		    $oprs->where('DATE(m.date_created) >=', $from);
			$oprs->where('DATE(m.date_created) <=', $to);
        }
		
		if($exists > 0){
			$oprs->where('man_type', $pub_id);
		}

		if($editor_type == 'technical'){
			
			$oprs->join($this->tech_rev_score . ' t', 'm.row_id = t.tr_man_id', 'left');
			$oprs->where('t.tr_final', $status);

		}else if($editor_type == 'associate'){

			$oprs->join(
				'(' .
				'SELECT ed.* FROM ' . $this->editors_review . ' ed ' .
				'JOIN (SELECT edit_man_id, MAX(er.row_id) AS last_entry FROM ' . $this->editors_review . ' er JOIN tblusers on edit_usr_id = usr_id where usr_desc LIKE "%associate%" GROUP BY edit_man_id) latest ' .
				'ON ed.row_id = latest.last_entry ' .
				') e',
				'm.row_id = e.edit_man_id',
				'left'
			);
	
			$oprs->where('edit_status', $status);
		}else{
			if($status == 1){
				$oprs->where('m.man_status > ', $status); // editor in chief 1st review
			}else if($status > 1){
				$oprs->where('m.man_status', $status);
			}
		}

		$query = $oprs->get();
		$results = $query->result();

		foreach ($results as $row) {
			$row->coauthors = $this->Coauthor_model->get_author_coauthors($row->row_id);
		}

		return $results;
	}
}


/* End of file Manuscript_model.php */