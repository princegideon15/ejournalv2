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

	public function __construct() {
		parent::__construct();
		$this->load->database(ENVIRONMENT);
	}

    public function validate_user($email){
        // MD5 hash the entered password before checking with the database


        // Query the database for a matching username and hashed password
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

}