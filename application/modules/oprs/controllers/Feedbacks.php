<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

/**
 * File Name: Feedbacks.php
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



class Feedbacks extends OPRS_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model('Feedback_model');
		$this->load->model('Log_model');
		$this->load->model('User_model');
		$this->load->model('Manuscript_model');
		$this->load->model('client/CSF_model');
		$this->load->helper('is_online_helper');
		$this->load->model('client/Library_model');
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
				if (_UserRoleFromSession() == 3 || _UserRoleFromSession() == 17) {
					$data['main_title'] = "OPRS";
					$data['main_content'] = "oprs/feedbacks";
					$data['logs'] = $this->Log_model->count_logs();
					$data['uiux'] = $this->Feedback_model->get_uiux();
					// echo json_encode($this->Feedback_model->get_uiux());exit;
					$data['uiux_sex'] = $this->Feedback_model->get_uiux_sex();
					// $data['csf_feedbacks'] = $this->Feedback_model->get_csf_feedbacks();
					$data['questions'] = $this->Library_model->get_csf_questions();
					$data['manus'] = $this->Manuscript_model->get_manus($this->session->userdata('_oprs_srce'), $this->session->userdata('_oprs_username'));
					// $data['man_onreview'] = $this->Manuscript_model->get_manuscripts(2);
					// $data['man_reviewed'] = $this->Manuscript_model->get_manuscripts(3);
					// $data['man_final'] = $this->Manuscript_model->get_manuscripts(4);
					// $data['man_for_p'] = $this->Manuscript_model->get_manuscripts(5);
					// $data['man_pub'] = $this->Manuscript_model->get_manuscripts(6);	
					$data['usr_count'] = $this->User_model->count_user();
					$data['feed_count'] = $this->Feedback_model->count_feedbacks();
					$data['arta_count'] = count($this->Arta_model->get_arta());
					
					$this->update_feedbacks();
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
	
	/**
	 * Update feedback
	 *
	 * @return void
	 */
	public function update_feedbacks(){
		$post['fb_notif'] = 1;
		$where['fb_notif'] = 0;
		$output = $this->Feedback_model->update_feedbacks($post, $where);
	}

	public function get_csf_feedback_by_ref($id){

		$output = $this->Feedback_model->get_csf_feedback_by_ref($id);
		echo json_encode($output);
	}

	public function get_ui_graph(){
		$output = $this->Feedback_model->ui_graph();
		echo json_encode($output);
	}

	public function get_ux_graph(){
		$output = $this->Feedback_model->ux_graph();
		echo json_encode($output);
	}

	public function get_csf_graph($id){
		$output = $this->Feedback_model->csf_graph($id);
		echo json_encode($output);
	}

	public function submit_csf_ui_ux(){
		
        $post = [
            'csf_user_id' => $this->session->userdata('_oprs_user_id'),
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

	public function filter_uiux(){
        $from = $this->input->post('from', TRUE);
        $to = $this->input->post('to', TRUE);
        $output = $this->Feedback_model->get_uiux($from, $to);
        echo json_encode($output);
	}

	public function filter_uiux_sex(){
        $from = $this->input->post('from', TRUE);
        $to = $this->input->post('to', TRUE);
        $output = $this->Feedback_model->get_uiux_sex($from, $to);
        echo json_encode($output);
	}
}


/* End of file Feedbacks.php */