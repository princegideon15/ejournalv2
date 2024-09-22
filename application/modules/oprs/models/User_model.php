<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class User_model extends CI_Model {
	// oprs
	private $users = 'tblusers';
	private $nonmembers = 'tblnonmembers';
	private $reviewers = 'tblreviewers';
	private $privileges = 'tblprivileges';
	private $roles = 'tblroles';
	// skms
	private $members = 'tblusers';
	private $personal = 'tblpersonal_profiles';
	public function __construct() {
		parent::__construct();
		$this->load->database(ENVIRONMENT);
	}

	/**
	 * Add new user
	 *
	 * @param [array] $data
	 * @return void
	 */
	public function add_user($data) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->insert($this->users, $data);
		if (_UserRoleFromSession() == 3) {
			save_log_oprs(_UserIdFromSession(), 'added user', $oprs->insert_id(), _UserRoleFromSession());
		}
		return $oprs->affected_rows();
	}

	/**
	 * Add default user privilege
	 *
	 * @param [type] $data
	 * @return void
	 */
	public function add_privilege($data) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->insert($this->privileges, $data);
		return $oprs->affected_rows();
	}

	/**
	 * Retrieve user role
	 *
	 * @param [int] $id		id
	 * @return void
	 */
	public function get_role($id) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('*');
		$oprs->from($this->roles);
		$oprs->where('role_id', $id);
		$query = $oprs->get();
		$result = $query->result_array();
		return $result[0]['role_name'];
	}

	/**
	 * Retrieve user privelege
	 *
	 * @param [int] $id		prv_usr_id
	 * @return void
	 */
	public function get_privilege($id) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('*');
		$oprs->from($this->privileges);
		$oprs->where('prv_usr_id', $id);
		$query = $oprs->get();
		return $query->result();
	}

	/**
	 * Retrieve users
	 *
	 * @param [int] $id		row_id
	 * @return void
	 */
	public function get_user($id) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('usr_status, role_name, usr_sys_acc, usr_username, usr_id, usr_logout_time, usr_role');
		$oprs->from($this->users . ' a');
		$oprs->join($this->roles . ' r', 'a.usr_role = r.role_id');
		$oprs->where_not_in('a.row_id', $id);
		$query = $oprs->get();
		return $query->result();

		
		$oprs->from($this->users . ' a');
		$oprs->join($this->privileges . ' p', 'a.usr_id = p.prv_usr_id');
	}

	/**
	 * Retrieve username only
	 *
	 * @param [int] $id		usr_id
	 * @return void
	 */
	public function get_user_name($id) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('usr_username');
		$oprs->from($this->users);
		$oprs->where('usr_id', $id);
		$query = $oprs->get();
		$result = $query->result_array();
		$row = $query->num_rows();
		if ($row > 0) {
			return $result[0]['usr_username'];
		} else {
			$members = $this->load->database('members', true);
			$members->select('usr_name');
			$members->from($this->members);
			$members->where('usr_id', $id);
			$query = $members->get();
			$result = $query->result_array();
			return $result[0]['usr_name'];
		}
	}

	/**
	 * Retrieve user data by id
	 *
	 * @param [int] $id		usr_id
	 * @return void
	 */
	public function get_user_info($id) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('*');
		$oprs->from($this->users);
		$oprs->where('usr_id', $id);
		$query = $oprs->get();
		return $query->result();
	}

	/**
	 * Save temporary user account of the reviewer
	 *
	 * @param [array] $data
	 * @return void
	 */
	public function create_temp_reviewer($data) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->insert($this->users, $data);
		return $oprs->affected_rows();
	}

	/**
	 * Save new non-member account (UNUSED)
	 *
	 * @param [array] $data
	 * @return void
	 */
	public function sign_up($data) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->insert($this->nonmembers, $data);
		// save_log_oprs(_UserIdFromSession(), 'created an account', $oprs->insert_id());
		return $oprs->affected_rows();
	}

	/**
	 * Verify member/non-member email
	 *
	 * @param [string] $email
	 * @return void
	 */
	public function verify_email($email) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('*');
		$oprs->from($this->nonmembers);
		$oprs->where('non_email', $email);
		$query = $oprs->get();
		$rows = $query->num_rows();
		if ($rows > 0) {
			return 'false';
		} else {
			$members = $this->load->database('members', true);
			$members->select('*');
			$members->from($this->members);
			$members->where('usr_name', $email);
			$query2 = $members->get();
			$rows2 = $query2->num_rows();
			if ($rows2 > 0) {
				return 'false';
			} else {
				return 'true';
			}
		}
	}

	/**
	 * Verify admin user email
	 *
	 * @param [string] $email	usr_username
	 * @param [int] $role
	 * @param [string] $sys
	 * @return void
	 */
	public function verify_user_email($email, $role, $sys) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('*');
		$oprs->from($this->users);
		$oprs->where('usr_username', $email);
		$query = $oprs->get();
		$rows = $query->num_rows();
		if ($rows > 0) {
			return 'false';
		} else {
			return 'true';
		}
	}

	/**
	 * Disable reviewer account
	 *
	 * @param [darray] $post
	 * @param [darray] $where
	 * @return void
	 */
	public function disable_reviewer($post, $where) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->update($this->users, $post, $where);
	}

	/**
	 * Update user data
	 *
	 * @param [array] $post
	 * @param [array] $where
	 * @return void
	 */
	public function update_user($post, $where) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->update($this->users, $post, $where);
		save_log_oprs(_UserIdFromSession(), 'updated an account', $where['usr_id'], _UserRoleFromSession());
	}

	/**
	 * Activate/deactivate user
	 *
	 * @param [array] $post
	 * @param [array] $where
	 * @return void
	 */
	public function activate_deactivate_account($post, $where) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->update($this->users, $post, $where);
		$action = ($post['usr_status'] == 2) ? 'deactivated user' : 'activated user';
		save_log_oprs(_UserIdFromSession(), $action, $where['usr_id'], _UserRoleFromSession());
	}

	/**
	 * Reset password
	 *
	 * @param [array] $data
	 * @param [array] $where
	 * @return void
	 */
	public function reset_password($data, $where) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->update($this->users, $data, $where);
		return $oprs->affected_rows();
	}

	/**
	 * Change user type/role
	 *
	 * @param [array] $data
	 * @param [array] $where
	 * @return void
	 */
	public function change_user_type($data, $where) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->update($this->users, $data, $where);
		return $oprs->affected_rows();
	}

	/**
	 * Delete user 
	 *
	 * @param [array] $where
	 * @return void
	 */
	public function remove_user($where) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->delete($this->users, $where);
		return $oprs->affected_rows();
	}

	/**
	 * Change password
	 *
	 * @param [array] $data
	 * @param [array] $where
	 * @return void
	 */
	public function change_password($data, $where) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->update($this->users, $data, $where);
		return $oprs->affected_rows();
	}

	/**
	 * Save uploaded displayp picture 
	 *
	 * @param [array] $data
	 * @param [array] $where
	 * @return void
	 */
	public function upload_dp($data, $where) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->update($this->users, $data, $where);
		return $oprs->affected_rows();
	}

	/**
	 * Verify user old password
	 *
	 * @param [array] $data
	 * @return void
	 */
	public function verify_old_password($data) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('usr_password');
		$oprs->from($this->users);
		$oprs->where('usr_id', _UserIdFromSession());
		$oprs->where('usr_role', _UserRoleFromSession());
		$query = $oprs->get();
		$result = $query->result_array();
		if (password_verify($data, $result[0]['usr_password'])) {
			return 'true';
		} else {
			return 'false';
		}
	}

	/**
	 * Retrieve member personal profile by id
	 *
	 * @param [int] $id		pp_usr_id
	 * @return void
	 */
	public function get_member($id) {
		$members = $this->load->database('members', true);
		$members->select('*');
		$members->from($this->personal);
		$members->where('pp_usr_id', $id);
		$query = $members->get();
		return $query->result();
	}

	/**
	 * Retrieve manuscript processor
	 *
	 * @param [type] $id
	 * @param [type] $src
	 * @return void
	 */
	public function get_processor($id, $src) {
		$oprs = $this->load->database('dboprs', TRUE);
		if (strpos($id, 'R') !== false || $src == '_sk_r') {
			$oprs->select('*');
			$oprs->from($this->reviewers);
			$oprs->where('rev_id', $id);
			$query = $oprs->get();
		} else if (strpos($id, 'NM') !== false) {
			$oprs->select('*');
			$oprs->from($this->nonmembers);
			$oprs->where('non_usr_id', $id);
			$query = $oprs->get();
		} else {
			if ($src == '_sk') {
				$members = $this->load->database('members', true);
				$members->select('*');
				$members->from($this->members . ' m');
				$members->join($this->personal . ' p', 'm.usr_id = p.pp_usr_id');
				$members->where('m.usr_id', $id);
				$query = $members->get();
			} else {
				$oprs->select('*');
				$oprs->from($this->users);
				$oprs->where('usr_id', $id);
				$query = $oprs->get();
			}
		}
		return $query->result();
	}

	/**
	 * Retrieve user by role
	 *
	 * @param [int] $role		usr_role
	 * @return void
	 */
	public function get_user_by_role($role) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('p.*, a.usr_id, a.usr_username, a.usr_sys_acc');
		$oprs->from($this->users . ' a');
		$oprs->join($this->privileges . ' p', 'a.usr_id = p.prv_usr_id');
		$oprs->where_not_in('a.row_id', _UserIdFromSession());
		$oprs->where('a.usr_role', $role);
		$query = $oprs->get();
		return $query->result();
	}

	/**
	 * Set user privilege
	 *
	 * @param [type] $data
	 * @param [type] $where
	 * @return void
	 */
	public function set_privilege($data, $where) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->update($this->privileges, $data, $where);
		return $oprs->affected_rows();
	}

	/**
	 * Count users
	 *
	 * @return void
	 */
	public function count_user() {
		
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('*');
		$oprs->from($this->users);
		$query = $oprs->get();
		return $query->num_rows();
	}
}

/* End of file User_model.php */