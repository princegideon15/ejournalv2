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

    public function save_csf_ui_ux($post){
      $this->db->insert('tblcsf_uiux', $post);
      return $this->db->affected_rows();
    }

    public function save_csf_arta($post){
      $this->db->insert('tblcsf_arta', $post);
      return $this->db->affected_rows();
    }

    public function update_csf_arta($post, $where){
      $this->db->update('tblcsf_arta', $post, $where);
      return $this->db->affected_rows();
    }

    public function get_csf_arta_ref_code($ref_code){
      $this->db->select('arta_ref_code');
      $this->db->from('tblcsf_arta');
      $this->db->where('arta_ref_code', $ref_code);
      $query = $this->db->get();
      return $query->num_rows();
    }

    public function get_latest_incomplete_csf_arta($id){
      $this->db->select('arta_ref_code');
      $this->db->from('tblcsf_arta');
      $this->db->where('arta_user_id', $id);
      $this->db->where('arta_service', '');
      $this->db->order_by('arta_created_at', 'desc');
      $this->db->limit(1);
      $data = $this->db->get()->row_array();
      return $data['arta_ref_code'];
    }
}

/* End of file Library_model.php */