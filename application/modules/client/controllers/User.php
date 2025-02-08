<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

/**
 * File Name: User.php
 * ----------------------------------------------------------------------------------------------------
 * Purpose of this file:
 * To manage user profile and downloads.
 * ----------------------------------------------------------------------------------------------------
 * System Name: Online Research Journal System
 * ----------------------------------------------------------------------------------------------------
 * Author: GPDB
 * ----------------------------------------------------------------------------------------------------
 * Date created: 11-23-2024
 * ----------------------------------------------------------------------------------------------------
 * Copyright Notice:
 * Copyright (C) 2018 by the Department of Science and Technology - National Research Council of the Philiipines
 */

class User extends EJ_Controller {

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

	/**
	 * User profile page
	 *
	 * @return void
	 */
	public function profile(){
		$id = $this->session->userdata('user_id');

		if($id){
			$data['profile'] = $this->Login_model->get_user_profile($id);
			$data['educations'] = $this->Client_journal_model->getEducations();
			$data['country'] = $this->Library_model->get_library('tblcountries', 'members');
			$data['provinces'] = $this->Library_model->get_library('tblprovinces', 'members', array('province_region_id' => $data['profile'][0]->region));
			$data['cities'] = $this->Library_model->get_library('tblcities', 'members', array('city_province_id' => $data['profile'][0]->province));
			$data['titles'] = $this->Client_journal_model->getTitles();
			$data['regions'] = $this->Library_model->get_library('tblregions', 'members');
			$data['main_title'] = "eJournal";
			$data['main_content'] = "client/user_profile";
			$this->_LoadPage('common/body', $data);
		}else{
			redirect('/');
		}
	}

	/**
	 * Update profile
	 *
	 * @return void
	 */
	public function update_profile(){
		$id = $this->session->userdata('user_id');

		if($id){
			$this->form_validation->set_rules('new_email', 'Email', 'required|trim|valid_email');
			$this->form_validation->set_rules('title', 'Title', 'required|trim');
			$this->form_validation->set_rules('first_name', 'First Name', 'required|trim');
			$this->form_validation->set_rules('last_name', 'Last Name', 'required|trim');
			$this->form_validation->set_rules('middle_name', 'Middle Name', 'trim');
			$this->form_validation->set_rules('extension_name', 'Extension Name', 'trim');
			$this->form_validation->set_rules('sex', 'Sex', 'required|trim');
			$this->form_validation->set_rules('educational_attainment', 'Educational Attainment', 'required|trim');
			$this->form_validation->set_rules('affiliation', 'Affiliation', 'required|trim');
	
			//require region,province,city for philippines
			if($this->input->post('country') == 175){
				$this->form_validation->set_rules('region', 'Region', 'required|trim');
				$this->form_validation->set_rules('province', 'Province', 'required|trim');
				$this->form_validation->set_rules('city', 'City', 'required|trim');
			}
	
			$this->form_validation->set_rules('contact', 'Contact', 'required|trim|numeric|exact_length[11]');
			$this->form_validation->set_rules('new_password', 'Password', 'trim|min_length[8]|max_length[20]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/]',
			array('regex_match' => 'Password must contain at least 1 letter, 1 number and 1 special character.'));
			$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|matches[new_password]');
	
			$validations = ['new_email', 'title', 'first_name', 'last_name', 'extension_name', 'sex', 'educational_attainment', 'affiliation', 'country', 'region', 'province', 'city', 'contact', 'new_password', 'confirm_password'];
	
			if($this->form_validation->run() == FALSE){
				$errors = [];
	
				foreach($validations as $value){
					//store entered value to display on redirect
					if($value == 'country'){
						if($this->input->post($value)){
							$this->session->set_flashdata($value, $this->input->post($value, TRUE));
						}else{
							$this->session->set_flashdata($value, 175);
						}
					}else{
						$this->session->set_flashdata($value, $this->input->post($value, TRUE));
					}
	
					//store errors to display on redirect
					if (form_error($value)) {
						$errors[$value] = strip_tags(form_error($value));
	
					}
				}
		
				//return password data and strenght data
				$password = $this->input->post('new_password', TRUE);
				$strength = 0;

				if (strlen($password) >= 8) {
					$strength += 10;
				}
				if (strlen($password) >= 12) {
					$strength += 15;
				}
				if (strlen($password) >= 16) {
					$strength += 20;
				}
			
				if (preg_match('/[A-Z]/', $password)) {
					$strength += 15;
				}
				if (preg_match('/[a-z]/', $password)) {
					$strength += 10;
				}
				if (preg_match('/[0-9]/', $password)) {
					$strength += 15;
				}
				if (preg_match('/[^A-Za-z0-9]/', $password)) {
					$strength += 15;
				}
	
				if ($strength <= 25) {
					$bar_color = 'red';
					$password_strength = 'Weak';
				} else if ($strength <= 50) {
					$bar_color = 'orange';
					$password_strength = 'Good';
				} else if ($strength <= 75) {
					$bar_color = 'yellow';
					$password_strength = 'Fair';
				}else {
					$bar_color = 'green';
					$password_strength = 'Excellent';     
				}
	
				$this->session->set_flashdata('bar_style', 'style="width:'. $strength .'%; background-color:'. $bar_color .'"');
				$this->session->set_flashdata('password_strength', $password_strength);
	
				//return province value and options if province has value
				$region = $this->input->post('region', TRUE);
	
				if($region > 0){
					$provinces = $this->Library_model->get_library('tblprovinces', 'members', array('province_region_id' => $region));
					$this->session->set_flashdata('provinces', $provinces);
				}
	
				//return city value and options if city has value
				$province = $this->input->post('province', TRUE);
	
				if($province){
					$cities = $this->Library_model->get_library('tblcities', 'members', array('city_province_id' => $province));
					$this->session->set_flashdata('cities', $cities);
				}
	
				// Set flashdata to pass validation errors and form data to the view
				$this->session->set_flashdata('message', '
				<div class="alert alert-danger d-flex align-items-center">
					<i class="fa fa-info-circle me-1"></i>Please check the form and make corrections.</div>');
				$this->session->set_flashdata('signup_validation_errors', $errors);
				redirect('client/user/profile');
			}else{
	
				$email = $this->input->post('new_email', TRUE);
				
				//check if email is exisiting
				$isExist = $this->Login_model->check_exist_email($id, $email);
				if($isExist){
					$this->session->set_flashdata('message', '
					<div class="alert alert-danger d-flex align-items-center">
						<i class="fa fa-info-circle me-1"></i>Please check the form and make corrections.</div>');
					$errors['new_email'] = 'Email already in use. Please use different email.';
					$this->session->set_flashdata('new_email', $email);
					$this->session->set_flashdata('signup_validation_errors', $errors);
					redirect('client/user/profile');
				}
	
				$this->session->set_flashdata('message', '
								<div class="alert alert-primary d-flex align-items-center">
									<i class="fa fa-check-circle me-1"></i>Your profile has been update.</div>');
									
				
				//update password
				$new_password = $this->input->post('new_password', TRUE);
				
				$userAuth = [
					'email' => $email,
					'updated_at' => date('Y-m-d H:i:s')
				];

				if (!empty($new_password)) {
					$userAuth['password'] = password_hash($new_password, PASSWORD_BCRYPT);
				}

				$whereAuth = array('id' => $id);

				//save log of change password

				$this->Login_model->update_user_auth(array_filter($userAuth), $whereAuth);


				//update user profile
				$userProfile = [
					'title' => $this->input->post('title', TRUE),
					'first_name' => $this->input->post('first_name', TRUE),
					'last_name' => $this->input->post('last_name', TRUE),
					'middle_name' => $this->input->post('middle_name', TRUE),
					'extension_name' => $this->input->post('extension_name', TRUE),
					'sex' => $this->input->post('sex', TRUE),
					'educational_attainment' => $this->input->post('educational_attainment', TRUE),
					'affiliation' => $this->input->post('affiliation', TRUE),
					'country' => $this->input->post('country', TRUE),
					'region' => $this->input->post('region', TRUE),
					'province' => $this->input->post('province', TRUE),
					'city' => $this->input->post('city', TRUE),
					'contact' => $this->input->post('contact', TRUE),
					'updated_at' => date('Y-m-d H:i:s')
				];

				//save log on update
				$whereProfile = array('user_id' => $id);
				$this->Login_model->update_user_profile($userProfile, $whereProfile);

				save_log_ej($id, 'Updated Profile');

				redirect('client/user/profile');
			}

		}else{
			redirect('/');
		}
	}

	/**
	 * View downloaded articles
	 *
	 * @return void
	 */
	public function downloads(){
		$id = $this->session->userdata('user_id');
		$output = $this->Client_journal_model->get_client_downloads($id);
		
		$data['results'] = $output;
		$data['journals'] = $this->Client_journal_model->get_journals();
		$data['popular'] = $this->Client_journal_model->top_five();
		$data['client_count'] = $this->Client_journal_model->all_client();
		$data['hits_count'] = $this->Client_journal_model->all_hits();
		$data['latest'] = $this->Client_journal_model->latest_journal();
		$data['adv_publication'] = $this->Client_journal_model->advancePublication();
		// $data['divisions'] = $this->Client_journal_model->getDivisions();
		$data['citations'] = $this->Client_journal_model->totalCitationsCurrentYear();
		$data['downloads'] = $this->Client_journal_model->totalDownloadsCurrentYear();
		$data['main_title'] = "eJournal";
		$data['main_content'] = "client/downloads";
		$this->_LoadPage('common/body', $data);
	}


}
/* End of file User.php */

