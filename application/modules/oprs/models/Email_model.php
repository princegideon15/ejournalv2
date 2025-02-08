<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Email_model extends CI_Model {
	private $logs = 'tbllogs';
	private $accounts = 'tblaccounts';
	private $manus = 'tblmanuscripts';
	private $users = 'tblusers';
	private $emails = 'tblemail_notif_contents';
	private $roles = 'tblroles';

	public function __construct() {
		parent::__construct();
		$this->load->database(ENVIRONMENT);
	}

	public function get_contents() {
		$oprs = $this->load->database('dboprs', TRUE);
        $oprs->select('*');
        $oprs->from($this->emails);
        $oprs->order_by('row_id', 'asc');
        $query = $oprs->get();
		return $query->result();
	}
	
	public function get_email_content($id) {
		$oprs = $this->load->database('dboprs', TRUE);
        $oprs->select('*');
        $oprs->from($this->emails);
        $oprs->where('enc_process_id', $id);
        $query = $oprs->get();
		return $query->result();
	}

	public function get_email_user_roles() {
		$oprs = $this->load->database('dboprs', TRUE);
        $oprs->select('*');
        $oprs->from($this->roles);
		// $oprs->where('role_access !=', '1');
		// $oprs->where('role_id !=', '5');
		// $oprs->where('role_id !=', '1');
        $query = $oprs->get();
		return $query->result();
	}

	public function get_user_group_emails($id) {
		$oprs = $this->load->database('dboprs', TRUE);
        $oprs->select('usr_username');
        $oprs->from($this->users);
		$oprs->where('usr_role', $id);
		$oprs->where('usr_status !=', '2');
        $query = $oprs->get();
		$result = $query->result_array();
		return $result[0]['usr_username'];
	}

	public function update_email_content($post, $where){
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->update($this->emails, $post, $where);
	}

	public function get_process_time_duration(){
		$oprs = $this->load->database('dboprs', TRUE);
        $oprs->select('*,
		(SELECT IFNULL(role_name,0) FROM tblroles where role_id LIKE enc_process_owner) as processor,
		(SELECT IFNULL(role_name,0) FROM tblroles where role_id LIKE enc_target_user) as target');
        $oprs->from($this->emails);
        $oprs->order_by('row_id', 'asc');
        $query = $oprs->get();
		return $query->result();
	}

}
?>