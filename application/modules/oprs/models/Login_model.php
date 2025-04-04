<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Login_model extends CI_Model {
	// oprs
	private $users = 'tblusers';
	private $reviewers = 'tblreviewers';
	private $editors = 'tbleditorials';
	private $nonmembers = 'tblnonmembers';
	private $attempts = 'tbllogin_attempts';
	private $access_tokens = 'tbluser_access_tokens';
	private $logs = 'tbllogs';
	// skms
	private $memberships = 'tblmembership_profiles';
	private $profiles = 'tblpersonal_profiles';
	private $members = 'tblusers';
	public function __construct() {
		parent::__construct();
		$this->load->database(ENVIRONMENT);
	}

	/** authenticate user login */
	public function authenticate_user($user, $role = null) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('*');
		$oprs->from($this->users);
		$oprs->where('usr_username', $user);
		if (isset($role)) {
			$oprs->where('usr_role', $role);
		}
		$query = $oprs->get();
		$data = $query->result();
		return $data;
		// return $oprs->last_query();
	}

	/** retreive online users */
	public function online_users($id) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('*');
		$oprs->from($this->users);
		$oprs->where_not_in('row_id', $id);
		$oprs->where_not_in('acc_type', '0');
		$oprs->order_by('acc_type', 'asc');
		$oprs->order_by('acc_username', 'asc');
		$query = $oprs->get();
		$data = $query->result();
		return $data;
	}
	/** retreive username to display in dashboard */
	public function get_username($id) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('acc_username');
		$oprs->from($this->users);
		$oprs->where_not_in('row_id', $id);
		$query = $oprs->get();
		$result = $query->result_array();
		return $result[0]['acc_username'];
	}
	/** retreive username to display in latest activities */
	public function get_username_for_logs($id) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('acc_username');
		$oprs->from($this->users);
		$oprs->where('row_id', $id);
		$query = $oprs->get();
		$result = $query->result_array();
		return $result[0]['acc_username'];
		// return  (isset($result[0]['acc_username']) ? $result[0]['acc_username'] : 'Deleted User' ;
	}
	public function authenticate_member($user) {
		$members = $this->load->database('members', true);
		$members->select('*');
		$members->from($this->members);
		// $members->from($this->members . ' m');
		// $members->join($this->memberships . ' s', 'm.usr_id = s.mpr_usr_id');
		// $members->where('s.mpr_membership_type <=', 2);
		$members->where('usr_name', $user);
		// $members->where('m.usr_name', $user);
		$query = $members->get();
		$data = $query->result();
		return $data;
	}

	public function get_reviewer_info($id) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('rev_email,rev_man_id');
		$oprs->from($this->reviewers);
		$oprs->where('rev_id', $id);
		$query = $oprs->get();
		$data = $query->result();
		return $data;
	}

	public function get_editor_info($id) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('edit_email,edit_man_id');
		$oprs->from($this->editors);
		$oprs->where('edit_id', $id);
		$query = $oprs->get();
		$data = $query->result();
		return $data;
	}

	public function check_reveiwer_status($id, $man_id) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('*');
		$oprs->from($this->reviewers);
		$oprs->where('rev_id', $id);
		$oprs->where('rev_man_id', $man_id);
		$query = $oprs->get();
		$data = $query->result();
		return $data;
	}

	public function check_multiple_accountx($email) {
		$oprs = $this->load->database('dboprs', TRUE);
		$acc_ctr = 0;
		$oprs->select('*');
		$oprs->from($this->users);
		$oprs->where('usr_username', $email);
		$query = $oprs->get();
		$row = $query->num_rows();
		if ($row > 1) {
			return $query->result();
		} else {
			if ($email != 'superadmin') {
				$acc_ctr++;
				$members = $this->load->database('members', true);
				$members->select('*');
				$members->from($this->members);
				$members->where('usr_name', $email);
				$query2 = $members->get();
				$row2 = $query2->num_rows();
				if ($row2 > 0) {
					$acc_ctr++;
				}
				if ($acc_ctr == 2) {
					$a = array_merge($query->result(), $query2->result());
					return $a;
				}
			}
		}
	}
	
	
	public function validate_user($email){
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('*');
		$oprs->from($this->users);
		$oprs->where('usr_username', $email);
		// $oprs->where('usr_role !=', 5);
		$query = $oprs->get();
		// If a matching user is found, return the user object
		if ($query->num_rows() == 1) {
			return $query->result();
			// return $this->db->last_query();
		} else {
			// Return false if no user found
			return false;
		}
	}

	public function save_otp($data, $where){
		$oprs = $this->load->database('dboprs', TRUE);
	  	$oprs->update($this->users, $data, $where);
	}

	public function validate_otp_ref($ref){
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('*');
		$oprs->from($this->users);
		$oprs->where('otp_ref_code', $ref);
		$query = $oprs->get();
		return $query->result();
	}

	public function delete_otp_oprs($id){
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->update($this->users, ['otp' => null, 'otp_date' => null, 'otp_ref_code' => null], ['usr_id' => $id]);
	}

	public function get_current_otp($refCode){
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('otp_date, usr_id, usr_username, usr_role');
		$oprs->from($this->users);
		$oprs->where('otp_ref_code', $refCode);
		$query = $oprs->get();
		return $query->result();
	}

	public function check_multiple_account($email) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('*');
		$oprs->from($this->users);
		$oprs->where('usr_username', $email);
		$query = $oprs->get();
		return $query->result();
	}

	
	public function store_login_attempts($data){
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->insert($this->attempts, $data);
	}

	public function get_login_attempts($email){
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('*');
		$oprs->from($this->attempts);
		$oprs->where('user_email', $email);
		$oprs->order_by('attempt_time', 'desc');
		$query = $oprs->get();
		return $query->result();
	}

	public function clear_login_attempts($email){
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->delete($this->attempts, ['user_email' => $email]);
	}

	public function create_user_access_token($data){
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->insert($this->access_tokens, $data);
	}
	  
	public function delete_access_token($id){
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->delete($this->access_tokens, ['tkn_user_id' => $id]);
	}

	public function get_access_token($id){
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('tkn_value');
		$oprs->from($this->access_tokens);
		$oprs->where('tkn_user_id', $id);
		$query = $oprs->get();
		return $query->result();
	}

	public function get_last_visit_date($id){
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('date_created');
		$oprs->from($this->logs);
		$oprs->where('log_user_id', $id);
		$oprs->order_by('date_created', 'DESC');
		$oprs->limit(1, 1);
		$query = $oprs->get();
		return $query->result();
	  }

}