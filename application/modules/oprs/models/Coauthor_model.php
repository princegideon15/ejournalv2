<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Coauthor_model extends CI_Model {
	private $coauthors = 'tblcoauthors';
	private $manus = 'tblmanuscripts';
	public function __construct() {
		parent::__construct();
		$this->load->database(ENVIRONMENT);
	}
	/** retreive coauthors */
	public function get_manus_acoa($id) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('*');
		$oprs->from($this->coauthors);
		$oprs->where('coa_man_id', $id);
		$query = $oprs->get();
		return $query->result();
	}
	public function get_author_coauthors($id) {
		$oprs = $this->load->database('dboprs', TRUE);
		$a = array();
		$oprs->select('b.coa_name as coauthor');
		$oprs->from($this->manus . ' a');
		$oprs->join($this->coauthors . ' b', 'a.row_id = b.coa_man_id');
		$oprs->where('coa_man_id', $id);
		$query = $oprs->get();
		$coas = $query->result();
		foreach ($coas as $row) {
			array_push($a, $row->coauthor);
		}
		$comma_separated = implode(', ', $a);
		return $comma_separated;
	}
}
?>