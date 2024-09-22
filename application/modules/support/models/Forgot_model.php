<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Forgot_model extends CI_Model {

	private $users = 'tblusers';
	private $supports = 'tblsupports';

	public function __construct() {
		parent::__construct();
		$this->load->database(ENVIRONMENT);
	}

	public function check_email($email) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('*');
		$oprs->from($this->users);
		$oprs->where('usr_username', $email);
		$query = $oprs->get();
		$rows = $query->num_rows();
		if ($rows > 0) {
			return 'true';
		} else {
			return 'false';
		}
	}

	public function update_password($post, $where) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->update($this->users, $post, $where);
	}

	public function check_multiple_account($email) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('*');
		$oprs->from($this->users);
		$oprs->where('usr_username', $email);
		$query = $oprs->get();
		return $query->result();
	}

	public function save_support($post) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->insert($this->supports, $post);
		return $oprs->affected_rows();
	}
}

?>
