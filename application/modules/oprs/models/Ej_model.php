<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ej_model extends CI_Model {

	public function __construct() {
		parent::__construct();
		$this->load->database('dbej');
	}
		// In Ej_model.php
		public function id_exists($id)
		{
			return $this->db->where('id', $id)->count_all_results('tblusers') > 0;
		}

		public function insert_user($data)
		{
			return $this->db->insert('tblusers', $data);
		}

		// Also add this (you use it later):
		public function get_user_info_by_email($email)
		{
			return $this->db->where('email', $email)->get('tblusers')->row();
		}
		
}
