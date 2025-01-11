<?php

/**
* File Name: Library_model.php
* ----------------------------------------------------------------------------------------------------
* Purpose of this file: 
* To get library items
* ----------------------------------------------------------------------------------------------------
* System Name: Online Research Journal System 
* ----------------------------------------------------------------------------------------------------
* Author: GPDB
* ----------------------------------------------------------------------------------------------------
* Date of revision: 10-16-2025
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
    public function get_library($tbl, $db, $where = null)
    {
        //$db = members (skms), dboprs (oprs), default (ejournal/dbej)
        $db = $this->load->database($db, TRUE);
        $db->select('*');
        $db->from($tbl);

        if($where){
            $db->where($where);
        }

        $query = $db->get();
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

    public function get_csf_client_types(){
        $this->db->select('*');
        $this->db->from('tblcsf_client_type');
        $query = $this->db->get();
        return $query->result_array();
    }
   
    public function get_csf_cc1(){
        $this->db->select('*');
        $this->db->from('tblcsf_cc1');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function get_csf_cc2(){
        $this->db->select('*');
        $this->db->from('tblcsf_cc2');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function get_csf_cc3(){
        $this->db->select('*');
        $this->db->from('tblcsf_cc3');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function get_csf_sqd(){
        $this->db->select('*');
        $this->db->from('tblcsf_sqd');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function get_editorial_policy_content(){
		$this->db->select("ep_file");
		$this->db->from('tbleditorial_policy');
        $this->db->where('ep_is_archive', '0');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result[0]['ep_file'];
	}
}

/* End of file Library_model.php */
?>