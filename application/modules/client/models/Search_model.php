<?php

/**
* File Name: Search_model.php
* ----------------------------------------------------------------------------------------------------
* Purpose of this file: 
* To manage client search
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

class Search_model extends CI_Model {

    private $journals   = 'tbljournals'; 
    private $articles   = 'tblarticles'; 
    private $coauthors  = 'tblcoauthors'; 

    public function __construct()
    {
        parent::__construct();
        $this->load->database(ENVIRONMENT);
    }

    /** this function search based on filter and keyword */
    // public function search_ejournal($filter, $keyword)
    public function search_ejournal($perPage, $start_index, $search = null)
    {
        if($perPage != '' && $start_index != ''){
            $this->db->limit($perPage, $start_index);
        }else{
            $this->db->limit($perPage);
        }

        $searchFields = ['art_title', 'art_author', 'coa_name', 'art_keywords', 'art_affiliation'];

        foreach ($searchFields as $field) {
            $this->db->select('a.*, j.jor_volume, j.jor_issue, jor_issn, c.*');
            $this->db->from($this->articles.' a');
            $this->db->join($this->journals.' j','a.art_jor_id = j.jor_id');
            $this->db->join($this->coauthors.' c', 'a.art_id = c.coa_art_id', 'left');

            if($search){
                $search = str_replace("%20", " ", $search);
                $this->db->like($field, $search, 'both');
            }

            $this->db->order_by('art_year', 'desc');
            $this->db->order_by('art_title', 'asc');
            $this->db->group_by('art_id');

    
            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                break; // Stop the loop if a match is found
            }
        }

        return $query->result();

        // if($filter == 1)
        // {
        //     $where = 'art_title';
        // }
        // else if($filter == 2)
        // {
        //     $where = 'art_author';
        // }
        // else
        // {
        //     $where = 'art_keywords';
        // }

        // $this->db->select('a.*, j.jor_volume, j.jor_issue, jor_issn');
        // $this->db->from($this->articles.' a');
        // $this->db->join($this->journals.' j','a.art_jor_id = j.jor_id');

        // if($keyword){
        //     $this->db->like($where, str_replace("%20", " ", $keyword), 'both');
        // }
        
        // $query = $this->db->get();

        // if($filter == 2)
        // {   
        //     $result2 = $query->result();
        //     $this->db->select('a.*, j.jor_volume, j.jor_issue, jor_issn, c.*');
        //     $this->db->from($this->articles.' a');
        //     $this->db->join($this->coauthors.' c', 'a.art_id = c.coa_art_id');
        //     $this->db->join($this->journals.' j','a.art_jor_id = j.jor_id');
        //     $this->db->like('coa_name', str_replace("%20", " ", $keyword), 'both');
        //     $query2 = $this->db->get();
        //     $result3 = $query2->result();
        //     return array('authors' => $result2, 'coas' => $result3);
        // }
        // else
        // {
        //     return $query->result();
        // }
          
    }

       /** this function search based on filter and keyword */
    // public function search_ejournal($filter, $keyword)
    public function advance_search_ejournal($perPage, $start_index, $search = null, $where, $where2)
    {
        if($perPage != '' && $start_index != ''){
            $this->db->limit($perPage, $start_index);
        }else{
            $this->db->limit($perPage);
        }

        $searchFields = $where;
        
       
     
     
        foreach ($searchFields as $field) {
            $this->db->select('a.*, j.jor_volume, j.jor_issue, jor_issn, c.*');
            $this->db->from($this->articles.' a');
            $this->db->join($this->journals.' j','a.art_jor_id = j.jor_id');
            $this->db->join($this->coauthors.' c', 'a.art_id = c.coa_art_id', 'left');
            
            foreach($where2 as $key => $val){
                $this->db->where($key, $val);
            }
    
            $search = str_replace("%20", " ", $search);
            $this->db->like($field, $search, 'both');
           

            $this->db->order_by('art_year', 'desc');
            $this->db->order_by('art_title', 'asc');
            $this->db->group_by('art_id');

    
            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                break; // Stop the loop if a match is found
            }
        }

        return $query->result();
    }

    /** this function get journal and article details */
    public function get_journals()
    {
        $this->db->select('jor_volume,jor_id');
        $this->db->from($this->journals);
        $this->db->join('tblarticles', 'tbljournals.jor_id = tblarticles.art_jor_id');
        $this->db->group_by('jor_volume');
        $query = $this->db->get();
        $result =  $query->result();
        foreach ($result as $row)
        {
          $jor[$row->jor_id] = $row->jor_volume;
        }
   
        foreach($jor as $j)
        {
            $iss[$j] = $this->get_issue($j);
        }
     
        return $iss;
    }

    /** this function get and shows image cover of a journal */
    public function get_cover($id)
    {
        $this->db->select('jor_cover');
        $this->db->from($this->journals);
        $this->db->where('jor_id', $id);
        $query = $this->db->get();
        $data = $query->result_array();
        if(count($data) > 0)
        return $data[0]['jor_cover'];   
    }

    /** this function get issue under a journal */
    public function get_issue($id)
    {
        $this->db->select('jor_issue,jor_id');
        $this->db->from($this->journals);
        $this->db->where('jor_volume', $id);
        $query = $this->db->get();
        return $query->result_array();
    }

    /** this function get all the articles */
    public function get_articles($id)
    {
        $this->db->select('*');
        $this->db->from($this->articles);
        $this->db->where('art_jor_id', $id);
        $query = $this->db->get();
        return $query->result();
    }

    /** this function get all coauthors  */
    public function get_coauthors($id)
    {
       $this->db->select('*');
        $this->db->from($this->coauthors);
        $this->db->where('coa_art_id', $id);
        $query = $this->db->get();
        return $query->result(); 
    }

    /** this function save client details upon visiting the website */
    public function save_client($post)
    {
        $this->db->insert($this->clients, $post);
        return $this->db->affected_rows();
    }

    /** this function get pdf file to attach in email of client */
    public function get_pdf_to_sent($id)
    {
        $this->db->select('art_full_text_pdf');
        $this->db->from($this->articles);
        $this->db->where('art_id', $id);
        $query = $this->db->get();
        $data = $query->result_array();
        return $data[0]['art_full_text_pdf'];
    }

    /** this function get article details */
    public function get_article($id)
    {
        $this->db->select('*');
        $this->db->from($this->articles);
        $this->db->where('art_id', $id);
        $query = $this->db->get();
        return $query->result();
    }

     /** this function get total numbers of clients */
    public function client_count($id)
    {
        $this->db->select('*');
        $this->db->from($this->clients);
        $this->db->where('clt_journal_downloaded_id', $id);
        $query = $this->db->get();
        return $query->num_rows();
    }

     /** this function save client details who viewed an abstract  */
    public function save_abstract_hits($post)
    {
        $this->db->insert($this->abstracts, $post);
        return $this->db->affected_rows();
    }

    /** this function get total number of abstract viewers */
    public function hits_count($id)
    {
        $this->db->select('*');
        $this->db->from($this->abstracts);
        $this->db->where('hts_art_id', $id);
        $query = $this->db->get();
        return $query->num_rows();
    }



    

 

}

?>
