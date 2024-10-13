<?php

/**
 * File Name: Client_journal_model.php
 * ----------------------------------------------------------------------------------------------------
 * Purpose of this file:
 * To manage client data input and system output
 * ----------------------------------------------------------------------------------------------------
 * System Name: Online Research Journal System
 * ----------------------------------------------------------------------------------------------------
 * Author: -
 * ----------------------------------------------------------------------------------------------------
 * Date of revision: -
 * ----------------------------------------------------------------------------------------------------
 * Copyright Notice:
 * Copyright (C) 2018 by the Department of Science and Technology - National Research Council of the Philiipines
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model {

	private $clients = 'tblclients';
	private $users = 'tblusers';
	private $sex = 'tblsex';
	private $attempts = 'tbllogin_attempts';
    private $profile = 'tbluser_profiles';

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
    public function validate_otp($otp, $ref){
        $this->db->select('*');
        $this->db->from($this->users);
        $this->db->where('otp', $otp);
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

    public function delete_otp($id){
		$this->db->update($this->users, ['otp' => null, 'otp_date' => null, 'otp_ref_code' => null], ['id' => $id]);
    }

	public function save_otp($data, $where){
		$this->db->update($this->users, $data, $where);
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
        $this->db->select('otp_date, email');
        $this->db->from($this->users);
        $this->db->where('otp_ref_code', $refCode);
        $query = $this->db->get();
        return $query->result();
    }

    public function create_user_profile($data){
		$this->db->insert($this->profile, $data);
    }

    public function create_user_account($data){
		$this->db->insert($this->users, $data);
    }
    


}