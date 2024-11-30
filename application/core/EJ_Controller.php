<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/* load the MX_Loader class */
//require APPPATH."third_party/MX/Loader.php";

class EJ_Controller extends MX_Controller {


    function __construct() {

        parent::__construct();

        date_default_timezone_set('Asia/Manila');
		
		$this->load->database();

		$this->load->helper('form');
		$this->load->helper('date');
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->helper('security');
		$this->load->helper('file');
		$this->load->helper('download');
		$this->load->helper('visitors_helper');
		$this->load->helper('string');

		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->library('upload');
		$this->load->library('encryption');
		$this->load->library("excel");
		$this->load->library("My_phpmailer");

		$objMail = $this->my_phpmailer->load();

		//security headers
		$this->output->set_header("Content-Security-Policy: 
			default-src 'self' https://*.google.com https://*.gstatic.com https://*.googleapis.com; 
			script-src 'self' https://*.google.com https://*.gstatic.com https://*.googleapis.com 'unsafe-inline'; 
			style-src 'self' https://*.google.com https://*.gstatic.com https://*.googleapis.com 'unsafe-inline'; 
			font-src 'self' https://*.gstatic.com;
			img-src 'self' https://*.google.com https://*.gstatic.com https://*.googleapis.com data:; 
			frame-src 'self' https://*.google.com;"
		);

		$this->output->set_header('X-Frame-Options: SAMEORIGIN');
		$this->output->set_header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
		$this->output->set_header('X-XSS-Protection: 1; mode=block');
		$this->output->set_header('X-Content-Type-Options: nosniff');
		
		error_reporting(0);
    }
     
    public function _LoadPage($page = NULL, $data=NULL){
            $this->load->view($page, $data);
      }

}

?>

