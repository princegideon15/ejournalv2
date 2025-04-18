<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

/**
 * File Name: Feedback.php
 * ----------------------------------------------------------------------------------------------------
 * Purpose of this file:
 * To manage CDF UI/UX/ARTA
 * ----------------------------------------------------------------------------------------------------
 * System Name: Online Research Journal System
 * ----------------------------------------------------------------------------------------------------
 * Author: GPDB
 * ----------------------------------------------------------------------------------------------------
 * Date created: 11-26-2024
 * ----------------------------------------------------------------------------------------------------
 * Copyright Notice:
 * Copyright (C) 2018 by the Department of Science and Technology - National Research Council of the Philiipines
 */

class Feedback extends EJ_Controller {

	public function __construct() {
		parent::__construct();
		
		/**
		 * Helpers, Library, Security headers are all in EJ_controller.php
		 */
		
		$this->load->model('Client_journal_model');
		$this->load->model('Library_model');
		$this->load->model('Login_model');
		$this->load->model('Search_model');
		$this->load->model('CSF_model');
		$this->load->model('Oprs/User_model');
		$this->load->model('Admin/Journal_model');
		$this->load->model('Admin/Email_model');
	}

    public function submit_csf_ui_ux(){
        $post = [
            'csf_user_id' => $this->session->userdata('user_id'),
            'csf_rate_ui' => $this->input->post('ui', TRUE),
            'csf_ui_suggestions' => $this->input->post('ui_sug', TRUE),
            'csf_rate_ux' => $this->input->post('ux', TRUE),
            'csf_ux_suggestions' => $this->input->post('ux_sug', TRUE),
            'csf_system' => $this->input->post('csf_system', TRUE),
            'csf_created_at' => date('Y-m-d H:i:s')
        ];
        
        $output = $this->CSF_model->save_csf_ui_ux($post);

        echo $output;
    }

}
/* End of file Feedback.php */

