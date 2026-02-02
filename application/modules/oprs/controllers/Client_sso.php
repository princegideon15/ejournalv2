<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Client_sso extends CI_Controller {

    public function __construct() {
        parent::__construct();
         $this->load->model('Oprs/User_model', '_oprs_user_model');
		 $this->load->model('Oprs/Login_model', '_oprs_login_model');
        $this->load->library('session');
    }

    public function go_to_client() {
        // Ensure user is logged into OPRS
        if (!$this->session->userdata('_oprs_logged_in')) {
            show_error('Access denied. Please log in first.');
        }

        $user_id = $this->session->userdata('_oprs_user_id');
        $email = $this->session->userdata('email');
        $name = $this->session->userdata('name');

        // 🔁 REVERSE LOGIC: Check eJournal (client) account
        $dbej = $this->_dbej_user_model->get_user_info($user_id);

        if (empty($dbej)) {
            // Auto-create eJournal account
            // 👇 This is the reverse of what you do in authenticate()
            $this->_create_ejournal_account($user_id, $email, $name);
        }

        // Set eJournal session flags (same as in authenticate())
        $this->session->set_userdata('_ej_logged_in', true);
        $this->session->set_userdata('user_id', $user_id);
        $this->session->set_userdata('email', $email);
        $this->session->set_userdata('name', $name);

        // Optional: set last visit, token, logs — just like authenticate()
        $last_visit = $this->db->select('date_created')
            ->where('user_id', $user_id)
            ->order_by('date_created', 'DESC')
            ->limit(1)
            ->get('ej_login_logs') // adjust table name
            ->row();

        $last_visit_date = $last_visit 
            ? (new DateTime($last_visit->date_created))->format('F j, Y')
            : date('F j, Y');

        $this->session->set_userdata('last_visit_date', $last_visit_date);

        // Redirect to client page — now fully authenticated
        // redirect('http://researchjournal.nrcp.dost.gov.ph/client/ejournal/');
		redirect('http://localhost:8080//client/login/');
    }

    // 🔁 Helper: Mirror of register_author_account, but for eJournal
    private function _create_ejournal_account($user_id, $email, $name) {
        // Example structure — adjust to match your eJournal user table
        $data = [
            'user_id' => $user_id,
            'email' => $email,
            'full_name' => $name,
            'status' => 1, // active
            'created_at' => date('Y-m-d H:i:s'),
            'created_from_oprs' => 1
        ];

        // Insert into eJournal user table
        $this->db->insert('ej_users', $data); // adjust table name

        // Optional: log it
        save_log_ej($user_id, 'Account auto-created via OPRS SSO');

        return $this->db->insert_id();
    }
}
