<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Skms_model extends CI_Model {

	public function __construct() {
		parent::__construct();
		$this->load->database('new_dbskms');
	}   

	// In Skms_model.php
		public function get_user_by_email($email)
		{
			return $this->db->where('usr_name', $email)->get('tblusers')->row();
		}
		
}
