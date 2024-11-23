<?php

/**
 * File Name: Login_model.php
 * ----------------------------------------------------------------------------------------------------
 * Purpose of this file:
 * To manage client data input and system output
 * ----------------------------------------------------------------------------------------------------
 * System Name: Online Research Journal System
 * ----------------------------------------------------------------------------------------------------
 * Author: GPDB
 * ----------------------------------------------------------------------------------------------------
 * Date of revision: 10-16-2024
 * ----------------------------------------------------------------------------------------------------
 * Copyright Notice:
 * Copyright (C) 2018 by the Department of Science and Technology - National Research Council of the Philiipines
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model {

  // ejournal
	private $clients = 'tblclients';
	private $users = 'tblusers';
	private $sex = 'tblsex';
	private $attempts = 'tbllogin_attempts';
  private $profile = 'tbluser_profiles';
  private $access_tokens = 'tbluser_access_tokens';

  // oprs
	private $oprs_users = 'tblusers';

	public function __construct() {
		parent::__construct();
		$this->load->database(ENVIRONMENT);
	}

  public function validate_user($email){
      $this->db->select('*');
      $this->db->from($this->users);
      $this->db->where('email', $email);
      $query = $this->db->get();
      // If a matching user is found, return the user object
      if ($query->num_rows() == 1) {
          return $query->result();
          // return $this->db->last_query();
      } else {
          // Return false if no user found
          return false;
      }
  }

  public function validate_otp($ref){
      $this->db->select('id, email, CONCAT(first_name," ",last_name) as name, otp');
      $this->db->from($this->users);
      $this->db->join($this->profile, 'id = user_id');
      $this->db->where('otp_ref_code', $ref);
      $query = $this->db->get();
      // If a matching user is found, return the user object
      if ($query->num_rows() == 1) {
          return $query->result();
          // return $this->db->last_query();
      } else {
          // Return false if no user found
          return false;
      }
  }

  public function validate_otp_ref($ref){
      $this->db->select('*');
      $this->db->from($this->users);
      $this->db->where('otp_ref_code', $ref);
      $query = $this->db->get();
      return $query->result();
  }

  public function activate_account($id){
    $this->db->update($this->users, ['status' => 1], ['id' => $id]);
  }

  public function activate_account_oprs($id){
		$oprs = $this->load->database('dboprs', TRUE);
    $oprs->update($this->oprs_users, ['usr_status' => 0], ['usr_id' => $id]);
  }

  public function delete_otp($id){
    $this->db->update($this->users, ['otp' => null, 'otp_date' => null, 'otp_ref_code' => null], ['id' => $id]);
  }

  public function delete_otp_oprs($id){
		$oprs = $this->load->database('dboprs', TRUE);
    $oprs->update($this->oprs_users, ['otp' => null, 'otp_date' => null, 'otp_ref_code' => null], ['usr_id' => $id]);
  }

  public function save_otp($data, $where){
    $this->db->update($this->users, $data, $where);
  }

  public function save_otp_oprs($data, $where){
		$oprs = $this->load->database('dboprs', TRUE);
    $oprs->update($this->oprs_users, $data, $where);
  }

  public function store_login_attempts($data){
    $this->db->insert($this->attempts, $data);
  }

  public function get_login_attempts($email){
      $this->db->select('*');
      $this->db->from($this->attempts);
      $this->db->where('user_email', $email);
      $this->db->order_by('attempt_time', 'desc');
      $query = $this->db->get();
      return $query->result();
  }
  
  public function clear_login_attempts($id){
      $this->db->delete($this->attempts, ['user_id' => $id]);
  }

  public function update_password($data, $where){
   $this->db->update($this->users, $data, $where);
  }

  public function get_current_otp($refCode){
      $this->db->select('otp_date, id, email');
      $this->db->from($this->users);
      $this->db->where('otp_ref_code', $refCode);
      $query = $this->db->get();
      return $query->result();
  }

  public function get_current_otp_oprs($refCode){
		$oprs = $this->load->database('dboprs', TRUE);
    $oprs->select('otp, otp_ref_code, otp_date, usr_id, usr_username');
    $oprs->from($this->oprs_users);
    $oprs->where('otp_ref_code', $refCode);
    $query = $oprs->get();
    return $query->result();
  }

  public function create_user_profile($data){
    $this->db->insert($this->profile, $data);
  }

  public function create_user_auth($data){
    
    // use escape_str to prevent sql injections
    foreach ($data as $key => $value) {
        $data[$key] = $this->db->escape_str($value);
    }

    $this->db->insert($this->users, $data);
  }

  public function get_user_profile($id){
    $this->db->select('p.*, email');
    $this->db->from($this->users . ' u');
    $this->db->join($this->profile . ' p', 'u.id = p.user_id');
    $this->db->where('u.id', $id);
    $query = $this->db->get();
    return $query->result();
  }
  
  public function check_exist_email($user_id, $email){
    $this->db->select('*');
    $this->db->from($this->users);
    $this->db->where('email !=', $email);
    $this->db->where('id', $user_id);
    $query = $this->db->get();
    // If a matching user is found, return the user object
    if ($query->num_rows() == 1) {
        return $query->result();
        // return $this->db->last_query();
    } else {
        // Return false if no user found
        return false;
    }
  }

  public function update_user_auth($data, $where){
    $this->db->update($this->users, $data, $where);
  }

  public function update_user_profile($data, $where){
    $this->db->update($this->profile, $data, $where);
  }

  public function activate_oprs_account($data, $where){
    $this->db->update($this->oprs_users, $data, $where);
  }

  public function create_user_access_token($data){
    $this->db->insert($this->access_tokens, $data);
  }
  
  public function delete_access_token($id){
    $this->db->delete($this->access_tokens, ['tkn_user_id' => $id]);
  }

  public function get_access_token($id){
    
    $this->db->select('tkn_value');
    $this->db->from($this->access_tokens);
    $this->db->where('tkn_user_id', $id);
    $query = $this->db->get();
    return $query->result();
  }

  public function get_last_visit_date($id){
    $this->db->select('date_created');
    $this->db->from('tbllogs');
    $this->db->where('log_user_id', $id);
    $this->db->order_by('date_created', 'DESC');
    $this->db->limit(1, 1);
    $query = $this->db->get();
    return $query->result();
  }
}

/* End of file Login_model.php */
?>