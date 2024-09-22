<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Library_model extends CI_Model {
	private $titles = 'tbltitles';
	public function __construct() {
		parent::__construct();
		$this->load->database(ENVIRONMENT);
	}

	/**
	 * Retreive titles (ex. mr., ms., etc)
	 *
	 * @return void
	 */
	public function get_titles() {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('*');
		$oprs->from($this->titles);
		$oprs->order_by('title_name', 'asc');
		$query = $oprs->get();
		return $query->result();
	}

	public function get_tables(){

		$oprs = $this->load->database('dboprs', TRUE);
        return $oprs->list_tables();
    }
}

/* End of file Library_model.php */