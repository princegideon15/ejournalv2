<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

	private $accounts = 'tblaccounts';
	private $messages = 'tblmessages';

	public function __construct() {
		parent::__construct();
		$this->load->database(ENVIRONMENT);
	}

	/**
	 * Add user
	 *
	 * @param   array  $data  user data
	 *
	 * @return  int         number of added rows
	 */
	public function add_user($data) {
		$this->db->insert($this->accounts, $data);
		save_log_ej(_UserIdFromSession(), 'just added new user account.', $this->db->insert_id());
		return $this->db->affected_rows();
	}

	/**
	 * Retrieve user data by ud
	 *
	 * @param   int  $id  user id
	 *
	 * @return  array       user data
	 */
	public function get_user($id) {
		$this->db->select('*');
		$this->db->from($this->accounts);
		$this->db->where('row_id', $id);
		$query = $this->db->get();
		return $query->result();
	}

	/**
	 * Reset admin user password
	 *
	 * @param   array  $data   password data
	 * @param   array  $where  user id
	 *
	 * @return  int          affected row
	 */
	public function reset_password($data, $where) {
		$this->db->update($this->accounts, $data, $where);
		return $this->db->affected_rows();
	}

	/**
	 * Update user admin type
	 *
	 * @param   array  $data   user data
	 * @param   array  $where  user id
	 *
	 * @return  int          affected row
	 */
	public function change_user_type($data, $where) {
		$this->db->update($this->accounts, $data, $where);
		return $this->db->affected_rows();
	}

	/**
	 * Delete user
	 *
	 * @param   array  $where  user data
	 *
	 * @return  int          affected row
	 */
	public function remove_user($where) {
		$this->db->delete($this->accounts, $where);
		return $this->db->affected_rows();
	}

	/**
	 * Update admin user password
	 *
	 * @param   array  $data   user data
	 * @param   array  $where  user id
	 *
	 * @return  int          affected row
	 */
	public function change_password($data, $where) {
		$this->db->update($this->accounts, $data, $where);
		return $this->db->affected_rows();
	}

	/**
	 * Upload admin user display image
	 *
	 * @param   array  $data   user data
	 * @param   array  $where  user id
	 *
	 * @return  int          affected row
	 */
	public function upload_dp($data, $where) {
		$this->db->update($this->accounts, $data, $where);
		return $this->db->affected_rows();
	}

	/**
	 * [store_message description]
	 *
	 * @param   [type]  $data  [$data description]
	 *
	 * @return  [type]         [return description]
	 */
	// public function store_message($data) {
	// 	$this->db->insert($this->messages, $data);
	// 	return $this->db->affected_rows();
	// }

	// public function get_messages($rcvr) {
	// 	$this->db->select('*');
	// 	$this->db->from($this->messages);
	// 	$this->db->where('msg_sender', _UserIdFromSession());
	// 	$this->db->where('msg_receiver', $rcvr);
	// 	$this->db->or_where('msg_receiver', _UserIdFromSession());
	// 	$this->db->where('msg_sender', $rcvr);
	// 	$query = $this->db->get();

	// 	if ($query->result()) {
	// 		return $query->result();
	// 	} else {
	// 		$this->db->select('*');
	// 		$this->db->from($this->messages);
	// 		$this->db->where('msg_sender', _UserIdFromSession());
	// 		$this->db->where('msg_receiver', $rcvr);
	// 		return $query->result();
	// 	}
	// }

	// public function get_new_messages() {
	// 	$this->db->select('a.msg_content,a.date_created,b.acc_username,a.msg_sender');
	// 	$this->db->from($this->messages . ' a');
	// 	$this->db->join($this->accounts . ' b', 'b.row_id = a.msg_sender');
	// 	$this->db->where('a.msg_receiver', _UserIdFromSession());
	// 	$this->db->where('a.msg_notif', 1);
	// 	$this->db->group_by('a.msg_receiver');
	// 	$this->db->group_by('a.msg_sender');

	// 	$query = $this->db->get();
	// 	return $query->result();
	// 	// return $this->db->last_query();
	// }

	// public function count_new_messages() {
	// 	$this->db->select('a.msg_content,a.date_created,b.acc_username,a.msg_sender');
	// 	$this->db->from($this->messages . ' a');
	// 	$this->db->join($this->accounts . ' b', 'b.row_id = a.msg_sender');
	// 	$this->db->where('a.msg_receiver', _UserIdFromSession());
	// 	$this->db->where('a.msg_notif', 1);
	// 	$this->db->group_by('a.msg_receiver');
	// 	$this->db->group_by('a.msg_sender');

	// 	$query = $this->db->get();
	// 	return $query->num_rows();
	// 	// return $this->db->last_query();
	// }

	// public function update_new_message_counter($data, $where) {
	// 	$this->db->update($this->messages, $data, $where);
	// 	return $this->db->affected_rows();
	// 	// return $this->db->last_query();
	// }
}

/* End of file User_model.php */