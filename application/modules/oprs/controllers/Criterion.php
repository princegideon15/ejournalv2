<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
class Criterion extends OPRS_Controller {
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
	}

	public function index(){
		$criterion = $this->input->get('type', TRUE);

		if ($this->session->userdata('_oprs_logged_in')) {
			if($this->session->userdata('sys_acc') == 2 || $this->session->userdata('sys_acc') == 3 ){
				if (_UserRoleFromSession() == 8) {
					$data['criteria'] = $this->Library_model->get_criteria(null, $criterion);
					$data['crit_cat'] = $criterion;
					$data['crit_name'] = $criterion == 1 ? 'Technical Review Criterion' : 'Peer Review Criterion';
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
					$data['user_types'] = $this->User_model->get_user_types();
					$data['main_title'] = "OPRS";
					$data['main_content'] = "oprs/criteria";
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

    public function check_unique_criteria_code($criteria){

        $name = $this->input->post('code', TRUE);
        $id = $this->input->post('id');
    
        $output = $this->Library_model->check_unique_criteria_code($name, $id, $criteria);
        echo $output;
    }

    public function check_unique_criteria_desc($criteria){

        $name = $this->input->post('desc', TRUE);
        $id = $this->input->post('id');
    
        $output = $this->Library_model->check_unique_criteria_desc($name, $id, $criteria);
        echo $output;
    }
    
	public function get_criteria($id, $criteria){
		$output = $this->Library_model->get_criteria($id, $criteria);
		echo json_encode($output);
	}

    public function update($criteria){
        if($criteria == 1){
            $post['crt_code'] = $this->input->post('crt_code', TRUE);
            $post['crt_desc'] = $this->input->post('crt_desc', TRUE);
            $post['last_updated'] = date('Y-m-d H:i:s');
            $where['crt_id'] = $this->input->post('crt_id', TRUE);
        }else{
            $post['pcrt_code'] = $this->input->post('pcrt_code', TRUE);
            $post['pcrt_desc'] = $this->input->post('pcrt_desc', TRUE);
            $post['pcrt_score'] = $this->input->post('pcrt_score', TRUE);
            $post['last_updated'] = date('Y-m-d H:i:s');
            $where['pcrt_id'] = $this->input->post('pcrt_id', TRUE);
        }

		$this->Library_model->update_critera($post, $where, $criteria);
    }


}