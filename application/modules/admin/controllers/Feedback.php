<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

/**
 * File Name: Dashboard.php
 * ----------------------------------------------------------------------------------------------------
 * Purpose of this file:
 * To manage data to display in admin dashboard page
 * ----------------------------------------------------------------------------------------------------
 * System Name: Online Research Journal System
 * ----------------------------------------------------------------------------------------------------
 * Author: Gerard Paul D. Balde
 * ----------------------------------------------------------------------------------------------------
 * Date of revision: Sep 26, 2019
 * ----------------------------------------------------------------------------------------------------
 * Copyright Notice:
 * Copyright (C) 2018 by the Department of Science and Technology - National Research Council of the Philiipines
 */



class Feedback extends EJ_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model('Feedback_model');
		$this->load->model('Log_model');
		$this->load->model('User_model');
		$this->load->helper('is_online_helper');
        $this->load->library('form_validation');
        error_reporting(0);
    }

    /**
     * Verify if feedback was submitted already
     *
     * @param [type] $id
     * @return void
     */
    public function verify($id){
        $output = $this->Feedback_model->verify($id);
        echo $output;
    }

    /**
     * Submit feedback
     *
     * @param [type] $sys
     * @return void
     */
    public function submit($sys){

        $CI =& get_instance();
        $oprs = $CI->load->database('dboprs', TRUE);

        $tableName = 'tblfeedbacks';
		$result = $oprs->list_fields($tableName);
		$post = array();

		foreach ($result as $i => $field) {
            if($field == 'fb_usr_id'){
                $post[$field] = (empty(_UserIdFromSession())) ? $this->input->post($field, TRUE) : _UserIdFromSession();
                // $id = (empty(_UserIdFromSession())) ? $this->input->post($field, TRUE) : _UserIdFromSession();
            }else{
                $post[$field] = $this->input->post($field, TRUE);
            }
            $this->form_validation->set_rules($post[$field], $field, 'trim|regex_match[/^([a-zA-Z]|\s)+$/]|max_length[300]');
        }

        $this->form_validation->set_rules('fb_suggest_ui', 'Special characters not allowed, ', 'trim|regex_match[/^([a-zA-Z]|\s)+$/]|max_length[300]');
        $this->form_validation->set_rules('fb_suggest_ux', 'Special characters not allowed,', 'trim|regex_match[/^([a-zA-Z]|\s)+$/]|max_length[300]');
        $this->form_validation->set_rules('fb_rate_ui', 'User Interface rating (UI) is required', 'required');
        $this->form_validation->set_rules('fb_rate_ux', 'User Experience (UX) rating is required', 'required');
        if ($this->form_validation->run() == FALSE)
		{
			//$this->load->view('myform');
			//$error = 400;
			if(validation_errors()):
				echo "<div class='alert alert-danger px-1 py-1 font-weight-bold'>".validation_errors()."</div>";
			endif;
		}
		else
		{
            $post['fb_system'] = $sys; // 1-ejournal 2-oprs 3-client
            $post['fb_source'] = $this->input->post('fb_source', TRUE); // 1-full text 2-citation 3-internal
            $post['date_created'] = date('Y-m-d H:i:s');
            $saved =  $this->Feedback_model->save_feedback(array_filter($post));
            //save_log_ej($id, 'submitted UI/UX feedback.',  $this->db->insert_id());
            //if($saved){
            echo 1;
           // }
        }
    }
}

/* End of file Feedback.php */