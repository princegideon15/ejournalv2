<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File Name: Library_model.php
 * --------------------------------------------------------
 * Purpose of this file:
 * To manage library functions
 * --------------------------------------------------------
 * System Name: Online Research Journal System
 * --------------------------------------------------------
 * Author: Gerard Paul D. Balde
 * ----------------------------------------------------------------------------------------------------
 * Date of revision: Sep 30, 2019
 * ----------------------------------------------------------------------------------------------------
 * Copyright Notice:
 * Copyright (C) 2017 by the Department of Science and Technology-NRCP
 */

class Library_model extends CI_Model {

	public function __construct() {
		parent::__construct();
		$this->load->database(ENVIRONMENT);
	}

	/**
	 * Retrieve library using table name
	 *
	 * @param   string  $tbl  table name
	 *
	 * @return  array        table data
	 */
	public function get_library($tbl) {
		$this->db->select('*');
		$this->db->from($tbl);
		$query = $this->db->get();
		return $query->result();
	}

	

    public function get_tables(){
        return $this->db->list_tables();
    }
}

/* End of file Library_model.php */
