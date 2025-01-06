<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
class Publication_types extends OPRS_Controller {
	public function __construct() {
		parent::__construct();
		if (!$this->session->userdata('_oprs_logged_in')) {
			redirect('oprs/login');
		}
		$this->load->model('Manuscript_model');
		$this->load->model('Login_model');
		$this->load->model('User_model');
		$this->load->model('Feedback_model');
		$this->load->model('Log_model');
		$this->load->model('Library_model');
		$this->load->model('Arta_model');
	}

	public function index(){
		if ($this->session->userdata('_oprs_logged_in')) {
			if($this->session->userdata('sys_acc') == 2 || $this->session->userdata('sys_acc') == 3 ){
				if (_UserRoleFromSession() == 17) {
					$data['publ_types'] = $this->Library_model->get_publication_types(null);
					$id = $this->session->userdata('_oprs_user_id');
					$data['users'] = $this->User_model->get_user($id);
					$data['logs'] = $this->Log_model->count_logs();
					$data['manus'] = $this->Manuscript_model->get_manus($this->session->userdata('_oprs_srce'), $this->session->userdata('_oprs_username'));
					// $data['man_new'] = $this->Manuscript_model->get_manuscripts(1);
					// $data['man_onreview'] = $this->Manuscript_model->get_manuscripts(2);
					// $data['man_reviewed'] = $this->Manuscript_model->get_manuscripts(3);
					// $data['man_final'] = $this->Manuscript_model->get_manuscripts(4);
					// $data['man_for_p'] = $this->Manuscript_model->get_manuscripts(5);
					// $data['man_pub'] = $this->Manuscript_model->get_manuscripts(6);	
					$data['usr_count'] = $this->User_model->count_user();
					$data['feed_count'] = $this->Feedback_model->count_feedbacks();
					$data['arta_count'] = count($this->Arta_model->get_arta());
					$data['feed_count'] = $this->Feedback_model->count_feedbacks();
					$data['main_title'] = "OPRS";
					$data['main_content'] = "oprs/publication";
					$this->_LoadPage('common/body', $data);
					$this->session->unset_userdata('_oprs_usr_message');
				}else if(_UserRoleFromSession() == 5 || _UserRoleFromSession() == 12 || _UserRoleFromSession() == 6){
					redirect('oprs/manuscripts');
				}else {
					redirect('oprs/dashboard');
				}
			} else {
				redirect('admin/dashboard');
			}
		}
	}

    public function check_unique_publication_type(){

        $name = $this->input->post('publication', TRUE);
        $id = $this->input->post('id');
    
        $output = $this->Library_model->check_unique_publication_type($name, $id);
        echo $output;
    }
    
	public function get_publication_types($id = null){
		$output = $this->Library_model->get_publication_types($id);
		echo json_encode($output);
	}

    public function update(){
		$post['publication_desc'] = $this->input->post('publication_desc', TRUE);
		$post['id'] = $this->input->post('id', TRUE);
		$post['publication_status'] = $this->input->post('publication_status', TRUE);
		$post['last_updated'] = date('Y-m-d H:i:s');
		$where['id'] = $this->input->post('id', TRUE);
		$this->Library_model->update_publication_type($post, $where);
    }


}