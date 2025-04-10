<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Report_model extends CI_Model {
	private $manus = 'tblmanuscripts';
	private $publication = 'tblpublication_types';
	private $status = 'tblstatus_types';
	public function __construct() {
		parent::__construct();
		$this->load->database(ENVIRONMENT);
	}
	public function get_list_manus() {

		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('m.*, p.publication_desc, status_desc as status, status_class');
		$oprs->from($this->manus . ' m');
		$oprs->join($this->publication . ' p', 'm.man_type = p.id', 'left');
		$oprs->join($this->status . ' s', 'm.man_status = s.status_id', 'left');
		$oprs->order_by('date_created', 'desc');
		$query = $oprs->get();
		return $query->result();
	}
}
?>