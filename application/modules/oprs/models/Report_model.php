<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Report_model extends CI_Model {
	private $manus = 'tblmanuscripts';
	public function __construct() {
		parent::__construct();
		$this->load->database(ENVIRONMENT);
	}
	public function get_list_manus() {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('*');
		$oprs->from($this->manus);
		$oprs->order_by('date_created', 'desc');
		$query = $oprs->get();
		return $query->result();
	}
}
?>