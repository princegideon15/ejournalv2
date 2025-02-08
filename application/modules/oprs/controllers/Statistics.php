<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
class Statistics extends OPRS_Controller {
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
		$this->load->model('Statistics_model');
		$this->load->model('Arta_model');
	}

	public function index(){
		// $type = $this->input->get('type', TRUE);

		if ($this->session->userdata('_oprs_logged_in')) {
			if($this->session->userdata('sys_acc') == 2 || $this->session->userdata('sys_acc') == 3 ){
				if (_UserRoleFromSession() != 1 && _UserRoleFromSession() != 16) { // can access except author and peer reviewers

					$module_access_session = $this->session->userdata('_' . _UserIdFromSession() . '_acc_reports');
					if($module_access_session == 1){
                        $id = $this->session->userdata('_oprs_user_id');
                        $data['users'] = $this->User_model->get_user($id);
                        $data['logs'] = $this->Log_model->count_logs();
                        $data['man_all'] = $this->Manuscript_model->get_manus(_UserRoleFromSession());
                        $data['man_all_count'] = count($data['man_all']);
                        $data['usr_count'] = $this->User_model->count_user();
                        $data['arta_count'] = count($this->Arta_model->get_arta());
                        $data['feed_count'] = $this->Feedback_model->count_feedbacks();
                        $data['user_types'] = $this->User_model->get_user_types();
                        $data['main_title'] = "OPRS";
                        $data['stat_summary'] = $this->Statistics_model->get_submission_summary();
                        // var_dump($data['stat_summary']);exit;
                        $data['stat_submission'] = $this->Statistics_model->get_submission_stats();
                        $data['stat_author_by_sex'] = $this->Statistics_model->get_author_by_sex_stats();
                        $data['main_content'] = "oprs/submission_statistics";

                        $this->_LoadPage('common/body', $data);
                        $this->session->unset_userdata('_oprs_usr_message');
                    }else{
					    redirect('oprs/manuscripts');
                    }
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

    public function filter_sub_sum(){
        $from = $this->input->post('from', TRUE);
        $to = $this->input->post('to', TRUE);
        $output = $this->Statistics_model->get_submission_summary($from, $to);
        echo json_encode($output);
    }

    public function filter_sub_stat(){
        $from = $this->input->post('from', TRUE);
        $to = $this->input->post('to', TRUE);
        $output = $this->Statistics_model->get_submission_stats($from, $to);
        echo json_encode($output);
    }

    public function filter_auth_by_sex(){
        $from = $this->input->post('from', TRUE);
        $to = $this->input->post('to', TRUE);
        $output = $this->Statistics_model->get_author_by_sex_stats($from, $to);
        echo json_encode($output);
    }


}