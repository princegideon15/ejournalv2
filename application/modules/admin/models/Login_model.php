<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model {

	private $accounts = 'tblaccounts';

	public function __construct() {
		parent::__construct();
		$this->load->database(ENVIRONMENT);
	}

	/**
	 * this function authenticate user login
	 *
	 * @param   string  $user  username
	 *
	 * @return  array         user data
	 */
	public function authenticate_user($user) {
		$this->db->select('row_id,acc_password,acc_type,acc_dp');
		$this->db->from($this->accounts);
		$this->db->where('acc_username', $user);
		$query = $this->db->get();
		$data = $query->result();
		return $data;
	}

	/**
	 * this function retreive online users
	 *
	 * @param   int  $id  user id
	 *
	 * @return  array       user data
	 */
	public function online_users($id) {
		$this->db->select('*');
		$this->db->from($this->accounts);
		$this->db->where_not_in('row_id', $id);
		$this->db->where_not_in('acc_type', '0');
		$this->db->order_by('acc_type', 'asc');
		$this->db->order_by('acc_username', 'asc');
		$query = $this->db->get();
		$data = $query->result();
		return $data;
	}

	/**
	 * this function retreive username to display in dashboard
	 *
	 * @param   int  $id  user id
	 *
	 * @return  string	username
	 */
	public function get_username($id) {
		$this->db->select('acc_username');
		$this->db->from($this->accounts);
		$this->db->where_not_in('row_id', $id);
		$query = $this->db->get();
		$result = $query->result_array();
		return $result[0]['acc_username'];
	}

	/**
	 * this function retreive username to display in latest activities
	 *
	 * @param   int  $id  user id
	 *
	 * @return  string       username
	 */
	public function get_username_for_logs($id) {
		$this->db->select('acc_username');
		$this->db->from($this->accounts);
		$this->db->where('row_id', $id);
		$query = $this->db->get();
		$result = $query->result_array();
		return $result[0]['acc_username'];
	}

}

?>
