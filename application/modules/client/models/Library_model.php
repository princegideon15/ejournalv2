<?php

/**
* File Name: Library_model.php
* ----------------------------------------------------------------------------------------------------
* Purpose of this file: 
* To get library items
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

class Library_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database(ENVIRONMENT);
    }

    /**
     * Retrieve library by table name
     *
     * @param [string] $tbl     table name
     * @return void
     */
    public function get_library($tbl)
    {
        $this->db->select('*');
        $this->db->from($tbl);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_csf_questions(){
        
        $skms = $this->load->database('members', TRUE);
        $skms->select('*');
        $skms->from('tblservice_feedback_questions');
        $skms->where('svc_fdbk_q_code', 'CSF-V2022');
		$query = $skms->get();
		return $query->result();
    }

    public function get_csf_q_choices($val){

        $table = (($val == 1) ? 'tblaffiliation_type' : 
                 (($val == 2) ? 'tblnrcp_services' : 'tblservice_feedback_ratings'));
        
        $skms = $this->load->database('members', TRUE);
        $skms->select('*');
        $skms->from($table);  
        
		$query = $skms->get();
		return $query->result();
    }
}

/* End of file Library_model.php */