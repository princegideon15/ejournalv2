<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Dashboard_model extends CI_Model {
	private $manus = 'tblmanuscripts';
	private $track = 'tbltracking';
	private $user = 'tblusers';
	private $coauthors = 'tblcoauthors';
	private $reviewers = 'tblreviewers';
	private $scores = 'tblscores';
	private $non = 'tblnonmembers';
	public function __construct() {
		parent::__construct();
		$this->load->database(ENVIRONMENT);
	}
	public function get_new_manus() {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('*');
		$oprs->from($this->manus);
		$oprs->where('man_status', 1);
		$oprs->order_by('date_created', 'desc');
		$query = $oprs->get();
		return $query->result();
	}
	public function get_lap_req() {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('r.rev_name as revname, m.man_title as mantitle, m.row_id as man_id, r.date_created as date_created');
		$oprs->from($this->reviewers . ' r');
		$oprs->join($this->manus . ' m', 'r.rev_man_id = m.row_id');
		$oprs->where('r.rev_status', 3);
		$oprs->order_by('r.date_created', 'desc');
		$query = $oprs->get();
		return $query->result();
	}
	public function get_dec_req() {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('r.rev_name as revname, m.man_title as mantitle, m.row_id as man_id, r.rev_date_respond as date_declined');
		$oprs->from($this->reviewers . ' r');
		$oprs->join($this->manus . ' m', 'r.rev_man_id = m.row_id');
		$oprs->where('r.rev_status', 0);
		$oprs->order_by('r.date_created', 'desc');
		$query = $oprs->get();
		return $query->result();
	}
	public function get_lap_rev() {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('r.rev_name as revname, m.man_title as mantitle, m.row_id as man_id, r.rev_date_respond as date_respond');
		$oprs->from($this->reviewers . ' r');
		$oprs->join($this->manus . ' m', 'r.rev_man_id = m.row_id');
		$oprs->join($this->scores . ' s', 's.scr_man_rev_id = r.rev_id');
		$oprs->where('s.scr_status', 3);
		$oprs->order_by('r.date_created', 'desc');
		$query = $oprs->get();
		return $query->result();
	}

	public function get_publishables()
	{
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('*');
		$oprs->from($this->manus);
		$oprs->where('man_status', 5);
		$query = $oprs->get();
		return $query->result();
	}
}
?>