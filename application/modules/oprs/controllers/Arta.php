<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

/**
 * File Name: Arta.php
 * ----------------------------------------------------------------------------------------------------
 * Purpose of this file:
 * To manage data to display in admin dashboard page
 * ----------------------------------------------------------------------------------------------------
 * System Name: Online Research Journal System
 * ----------------------------------------------------------------------------------------------------
 * Author: Gerard Paul D. Balde
 * ----------------------------------------------------------------------------------------------------
 * Date of revision: Oct 28, 2020
 * ----------------------------------------------------------------------------------------------------
 * Copyright Notice:
 * Copyright (C) 2018 by the Department of Science and Technology - National Research Council of the Philiipines
 */



class Arta extends OPRS_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model('Feedback_model');
		$this->load->model('Log_model');
		$this->load->model('User_model');
		$this->load->model('Manuscript_model');
		$this->load->model('client/CSF_model');
		$this->load->helper('is_online_helper');
		$this->load->model('oprs/Library_model');
		$this->load->model('Arta_model');
    }

	/**
	 * Display feedbacks
	 *
	 * @return void
	 */
    public function index(){
        
		if ($this->session->userdata('_oprs_logged_in')) {
			if($this->session->userdata('sys_acc') == 2 || $this->session->userdata('sys_acc') == 3 ){
				if (_UserRoleFromSession() == 3 || _UserRoleFromSession() == 8) {
					$data['main_title'] = "OPRS";
					$data['main_content'] = "oprs/arta";
					$data['logs'] = $this->Log_model->count_logs();
					$data['arta'] = $this->Arta_model->get_arta();
					$data['arta_age'] = $this->Arta_model->get_arta_resp_age();
					$data['arta_reg'] = $this->Arta_model->get_arta_region();
					$data['arta_cc'] = $this->Arta_model->get_arta_cc();
					$data['arta_sqd'] = $this->Arta_model->get_arta_sqd();
					$data['manus'] = $this->Manuscript_model->get_manus($this->session->userdata('_oprs_srce'), $this->session->userdata('_oprs_username'));
					$data['usr_count'] = $this->User_model->count_user();
					$data['arta_count'] = count($this->Arta_model->get_arta());
					$data['feed_count'] = $this->Feedback_model->count_feedbacks();
					$data['regions'] = $this->Library_model->get_regions();
					$data['client_type'] = $this->Library_model->get_client_type();
					$this->_LoadPage('common/body', $data);
				}else if(_UserRoleFromSession() == 5 || _UserRoleFromSession() == 12  || _UserRoleFromSession() == 6){
					redirect('oprs/manuscripts');
				}else {
					redirect('oprs/dashboard');
				}
			}else{
				redirect('admin/dashboard');
			}
		}
	}
	
}


/* End of file Arta.php */