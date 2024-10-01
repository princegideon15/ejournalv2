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
		$this->form_validation->set_rules('password', 'Password', 'required|trim');

		if($this->form_validation->run() == FALSE){
			redirect('client/ejournal/login');
		}else{
			
			$email = $this->input->post('email');
			$password = $this->input->post('password');
		
			// Check user credentials using your authentication logic
			$validateUser = $this->Login_model->validate_user($email);
			if ($validateUser) {

				if (password_verify($password, $validateUser[0]->password)) {
					//logged in
					// // Set session variables
					// $this->session->set_userdata('user_id', $validateUser['id']);
					// $this->session->set_userdata('email', $validateUser['email']);
		
					// Redirect to dashboard or other protected page
					// redirect('dashboard');
					// redirect('login');
				}else{
					echo 'Invalid email or password.';exit;
				}
			} else {
				echo 'Invalid email or password.';exit;
				// Redirect back to login form with error message
				$this->session->set_flashdata('error', 'Invalid email or password.');
				// redirect('login');
			}
		}
	}
}