<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
class Signup extends OPRS_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('User_model');
	}

	public function sign_up() {
		$oprs = $this->load->database('dboprs', TRUE);
		// save as author
		$tableName = 'tblnonmembers';
		$result = $oprs->list_fields($tableName);
		$post = array();
		$non_user_id = 'NM' . md5(uniqid('', TRUE));
		foreach ($result as $i => $field) {
			if ($field != 'non_password') {
				$post[$field] = $this->input->post($field, true);
			}
			$post['non_usr_id'] = $non_user_id;
		}
		$usr_name = $this->input->post('non_email', true);
		$usr_pass = $this->input->post('non_password', true);
		$post['date_created'] = date('Y-m-d H:i:s');
		$this->User_model->sign_up(array_filter($post));
		// create user account
		$post = array();
		$post['usr_username'] = $usr_name;
		$post['usr_password'] = password_hash($usr_pass, PASSWORD_BCRYPT);
		$post['usr_email'] = $usr_name;
		$post['usr_desc'] = 'Author';
		$post['usr_role'] = 1;
		$post['usr_id'] = $non_user_id;
		$post['date_created'] = date('Y-m-d H:i:s');
		$this->User_model->add_user(array_filter($post));
	}

	public function refresh_captcha() {
		$config = array(
			'img_url' => base_url() . 'assets/image_for_captcha/',
			'img_path' => 'assets/image_for_captcha/',
			// 'img_height' => 50,
			'word_length' => 5,
			'img_width' => 150,
			'font_path' => FCPATH . 'captcha/font/verdana.ttf',
			'font_size' => 20,
		);
		$captcha = create_captcha($config);
		$data['image'] = $captcha['image'];
		$data['word'] = $captcha['word'];
		echo json_encode($data);
	}
	
	public function verify_email() {
		$output = $this->User_model->verify_email($this->input->post('non_email', true));
		echo $output;
	}

	
	public function check_author_email(){
		$email = $this->input->post('email', TRUE);
		$member = $this->input->post('member', TRUE);
		
		if($member == 1){ // check if skms member
			$isNrcpMember = $this->User_model->check_author_email_skms($email);

			if($isNrcpMember == 1){ // check if existing in oprs
				$isAuthorAccount = $this->User_model->get_user_info_by_email($email);
				if(count($isAuthorAccount) > 0){
					// existing as author
					echo 2;
				}else{
					// not existing as author
					echo 1;
				}
			}else{ // email is not member
				echo $isNrcpMember;
			}
		}else{
			$output = $this->User_model->check_author_email_ejournal($email);
			echo json_encode($output);
		}

	}

	public function register_author(){
		$email = $this->input->post('email', TRUE);
		$member = $this->input->post('member', TRUE);
		
		if($member == 1){
			// save skms member info to oprs author
			$output = $this->User_model->check_author_email_skms($email);
		}else{
			// save ejournal client info to oprs as author
			$output = $this->User_model->check_author_email_ejournal($email);
		}
	}
}