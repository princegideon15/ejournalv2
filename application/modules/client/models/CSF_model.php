<?php

/**
* File Name: CSF_model.php
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

class CSF_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database(ENVIRONMENT);
    }
    
    public function save_csf($post){

      $this->db->insert('tblservice_feedbacks', $post);
      return $this->db->affected_rows();

    }

    public function check_fdbk_ref($ref){
      
      $this->db->select('*');
      $this->db->from('tblservice_feedbacks');
      $this->db->where('svc_fdbk_ref', $ref);
      $query = $this->db->get();
      return $query->num_rows();

    }

    public function check_client_id_exists($id){
      
      $this->db->select('*');
      $this->db->from('tblclients');
      $this->db->where('clt_id', $id);
      $query = $this->db->get();
      return $query->num_rows();

    }
}

/* End of file Library_model.php */