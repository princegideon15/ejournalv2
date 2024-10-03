<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

/**
 * File Name: Ejournal.php
 * ----------------------------------------------------------------------------------------------------
 * Purpose of this file:
 * To manage data to display in client landing page
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

class Login extends EJ_Controller {

	public function __construct() {

		parent::__construct();

		$this->load->model('Login_model');
		$this->load->library("My_phpmailer");
		$objMail = $this->my_phpmailer->load();
		$this->load->helper('string');
        $this->load->helper('form');
        $this->load->library('session'); 
		$this->load->library('form_validation');
		error_reporting(0);
	}
    
    public function authenticate(){

		$this->form_validation->set_rules('email', 'Email', 'required|trim');
		$this->form_validation->set_rules('password', 'Password', 'required|trim|min_length[8]');

		if($this->form_validation->run() == FALSE){
			$errors = [];

            if (form_error('email')) {
                $errors['email'] = strip_tags(form_error('email'));
            }
            if (form_error('password')) {
                $errors['password'] = strip_tags(form_error('password'));
            }

            // Set flashdata to pass validation errors and form data to the view
            $this->session->set_flashdata('validation_errors', $errors);
			redirect('client/ejournal/login');
		}else{
			
			$email = $this->input->post('email');
			$password = $this->input->post('password');
		
			// Check user credentials using your authentication logic
			$validateUser = $this->Login_model->validate_user($email);
			if ($validateUser) {

				if (password_verify($password, $validateUser[0]->password)) {
					$this->session->set_userdata('user_id', $validateUser[0]->id);
					$this->session->set_userdata('email',  $validateUser[0]->email);
					redirect('/');
				}else{
					$this->session->set_flashdata('error', 'Invalid email or password.');
					redirect('client/ejournal/login');
				}
			} else {
				$this->session->set_flashdata('error', 'Invalid email or password.');
				redirect('client/ejournal/login');
			}
		}
	}

	public function logout(){
        $this->session->unset_userdata('user_id');
        $this->session->unset_userdata('email');
        $this->session->sess_destroy();
		redirect('client/ejournal/login');

	}
}