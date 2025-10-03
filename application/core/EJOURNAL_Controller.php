<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/* load the MX_Loader class */
//require APPPATH."third_party/MX/Loader.php";

class EJOURNAL_Controller extends MX_Controller {


    function __construct() {

        parent::__construct();

        date_default_timezone_set('Asia/Hong_Kong');
			//error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
		
		$this->load->database();
		$this->load->helper(array('form'));
		$this->load->helper('date');
		$this->load->helper('url');
		$this->load->helper('html');
		/* Load form validation library */
		$this->load->library('form_validation');

		$this->load->library('session');
		$this->load->library('upload');




    }
     
    public function _LoadPage($page = NULL, $data=NULL){
            $this->load->view($page, $data);
      }

}

?>

