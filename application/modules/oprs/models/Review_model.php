<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Review_model extends CI_Model {
	// oprs
	private $criterias = 'tblcriterias';
	private $scores = 'tblscores';
	private $manus = 'tblmanuscripts';
	private $user = 'tblusers';
	private $reviewers = 'tblreviewers';
	private $non = 'tblnonmembers';
	// skms
	private $business = 'tblbusiness_address';
	private $specs = 'tblmembership_profiles';
	private $titles = 'tbltitles';
	private $profiles = 'tblpersonal_profiles';
	private $employments = 'tblemployments';
	public function __construct() {
		parent::__construct();
		$this->load->database(ENVIRONMENT);
	}

	/**
	 * Retrieve criterias for reviewer
	 *
	 * @return void
	 */
	public function get_criterias() {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('*');
		$oprs->from($this->criterias);
		$query = $oprs->get();
		return $query->result();
	}
	
	/**
	 * Update review
	 *
	 * @param [array] $post
	 * @param [array] $where
	 * @param [int] $flag
	 * @return void
	 */
	public function update_review($post, $where, $flag) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->update($this->scores, $post, $where);

		if($flag != 'nda'){
			save_log_oprs(_UserIdFromSession(), 'reviewed', $flag, 5);
		}else if($flag == 'ecert'){
			save_log_oprs(_UserIdFromSession(), 'sent eCertification', $flag, 3);
		}

	}

	public function update_score_lapse($post, $where) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->update($this->scores, $post, $where);
	}

	/**
	 * Save review of the reviewer
	 *
	 * @param [array] $data
	 * @return void
	 */
	public function save_review($data) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->insert($this->scores, $data);
		// save_log_oprs(_UserIdFromSession(),'reviewed',$oprs->insert_id());
	}

	/**
	 * Retrieve review
	 *
	 * @param [int] $id		scr_man_rev_id
	 * @param [int] $man_id		scr_man_id
	 * @return void
	 */
	public function get_review($id, $man_id) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('s.*, m.man_author, m.man_title');
		$oprs->from($this->scores . ' s');
		$oprs->join($this->manus . ' m', 's.scr_man_id = m.row_id');
		$oprs->where('scr_man_rev_id', $id);
		$oprs->where('scr_man_id', $man_id);
		$query = $oprs->get();
		return $query->result();
	}

	/**
	 * Retrieve reviewer info
	 *
	 * @param [int] $id		rev_id, non_usr_id, pp_usr_id
	 * @return void
	 */
	public function get_rev_info($id) {
		$oprs = $this->load->database('dboprs', TRUE);

		// $oprs->select('*');
		// $oprs->from($this->reviewers);
		// $oprs->where('rev_id', $id);
		// $query = $oprs->get();

		$members = $this->load->database('members', TRUE);
			$members->select('p.*, s.mpr_gen_specialization, e.*, t.title_name');
			$members->from($this->profiles . ' p');
			$members->join($this->specs . ' s', 's.mpr_usr_id = p.pp_usr_id');
			$members->join($this->employments . ' e', 'e.emp_usr_id = p.pp_usr_id');
			$members->join($this->titles . ' t', 't.title_id = p.pp_title');
			$members->where('p.pp_usr_id', $id);
			$query = $members->get();

		// if (strpos($id, 'R') !== false) {
		// 	$oprs->select('*');
		// 	$oprs->from($this->reviewers);
		// 	$oprs->where('rev_id', $id);
		// 	$query = $oprs->get();
		// } else if (strpos($id, 'NM') !== false) {
		// 	$oprs->select('*');
		// 	$oprs->from($this->non);
		// 	$oprs->where('non_usr_id', $id);
		// 	$query = $oprs->get();
		// } else {
		// 	$members = $this->load->database('members', TRUE);
		// 	$members->select('p.*, s.mpr_gen_specialization, e.*, t.title_name');
		// 	$members->from($this->profiles . ' p');
		// 	$members->join($this->specs . ' s', 's.mpr_usr_id = p.pp_usr_id');
		// 	$members->join($this->employments . ' e', 'e.emp_usr_id = p.pp_usr_id');
		// 	$members->join($this->titles . ' t', 't.title_id = p.pp_title');
		// 	$members->where('p.pp_usr_id', $id);
		// 	$query = $members->get();
		// }
		return $query->result();
	}

	/**
	 * Retrieve non member data (UNUSED)
	 *
	 * @param [type] $id
	 * @return void
	 */
	public function get_non_member_info($id) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('*');
		$oprs->from($this->non);
		$oprs->where('non_usr_id', $id);
		$query = $oprs->get();
		return $query->result();
	}

	/**
	 * Retrieve review status
	 *
	 * @param [int] $rev_id		scr_man_rev_id
	 * @param [int] $man_id		scr_man_id
	 * @return void
	 */
	public function get_rev_status($rev_id, $man_id) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('scr_status');
		$oprs->from($this->scores);
		$oprs->where('scr_man_rev_id', $rev_id);
		$oprs->where('scr_man_id', $man_id);
		$query = $oprs->get();
		$result = $query->result_array();
		$rows = $query->num_rows();
		if ($rows > 0) {
			return $result[0]['scr_status'];
		} else {
			return 0;
		}
	}

	/**
	 * Check if reviewer email exists already in a manuscript
	 *
	 * @param [string] $email
	 * @param [int] $id
	 * @return void
	 */
	public function verify_reviewer_email($email, $id) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('*');
		$oprs->from($this->reviewers);
		$oprs->where('rev_email', $email);
		$oprs->where('rev_man_id', $id);
		$query = $oprs->get();
		return $query->result();
	}

	/**
	 * Retrieve manuscript's author data
	 *
	 * @param [int] $id		row_id
	 * @return void
	 */
	public function get_manus_author_info($id) {

		// $dboprs = $this->load->database("dboprs", TRUE);
		// $dbskms = $this->load->database("members", TRUE);

		// $DB->select('x.name as name1, y.name as name2');
		// $DB->from('x');
		// $DB->join($db->database.'.y','x.y_id = y.id');
		// $res = $DB->get();

		// $skms = 


		$oprs = $this->load->database('dboprs', TRUE);
		// $oprs->select('m.man_title, m.man_email, m.man_author, r.rev_name, r.rev_hide_rev, m.man_author_title');
		$oprs->select('*');
		// $oprs->from($this->manus . ' m');
		$oprs->from($this->manus);
		// $oprs->join($this->reviewers . ' r', 'm.row_id = r.rev_man_id');
		// $oprs->where('r.rev_id', _UserIdFromSession());
		// $oprs->where('m.row_id', $id);
		$oprs->where('row_id', $id);
		$query = $oprs->get();
		return $query->result();
		// return $oprs->last_query();
	}

	/**
	 * Check if reviewer exists
	 *
	 * @param [string] $email		rev_email
	 * @return void
	 */
	public function check_reviewer($email) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('rev_id');
		$oprs->from($this->reviewers);
		$oprs->where('rev_email', $email);
		$query = $oprs->get();
		$result = $query->result_array();
		$rows = $query->num_rows();
		if ($rows > 0) {
			return $result[0]['rev_id'];
		} else {
			return '0';
		}
	}
}

/* End of file Review_model.php */