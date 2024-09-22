<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/* load the MX_Loader class */
//require APPPATH."third_party/MX/Loader.php";

class OPRS_Controller extends MX_Controller {

	function __construct() {

		parent::__construct();

		date_default_timezone_set('Asia/Manila');
		//error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

		$this->load->database();
		$this->load->helper('form');
		$this->load->helper('date');
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->helper('security');
		$this->load->helper('file');
		$this->load->helper('download');
		$this->load->helper('is_online_helper');
		$this->load->helper('log_helper');
		$this->load->helper('captcha');
		$this->load->helper('cookie');

		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->library('upload');
		$this->load->library('encryption');
		$this->load->library("excel");
		$this->load->library('input');
		$this->load->library("My_phpmailer");
	}

	public function _LoadPage($page = NULL, $data = NULL) {
		$this->load->view($page, $data);

	}

}

?>
