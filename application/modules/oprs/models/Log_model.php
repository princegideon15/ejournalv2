<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Log_model extends CI_Model {
	private $logs = 'tbllogs';
	private $accounts = 'tblaccounts';
	private $manus = 'tblmanuscripts';
	private $users = 'tblusers';

	public function __construct() {
		parent::__construct();
		$this->load->database(ENVIRONMENT);
	}

	/** retreive all activities */
	public function get_logs($data=null) {
		$oprs = $this->load->database('dboprs', TRUE);
		// if(isset($data)){
		// 	$oprs->select('notif_open, man_title, a.row_id as row_id, a.date_created as date_created, usr_username, log_action');
		// 	$oprs->from($this->logs .' a');
		// 	$oprs->join($this->manus . ' b', 'a.log_insert_id = b.row_id');
		// 	$oprs->join($this->users . ' c', 'a.log_user_id = c.usr_id');
		// 	$oprs->where('a.log_insert_id !=',0);
		// 	$oprs->order_by('a.notif_open', 'asc');
		// 	$oprs->order_by('a.date_created', 'desc');
		// }else{
			$oprs->select('*');
			$oprs->from($this->logs);
			$oprs->order_by('date_created', 'desc');
		// }
		
		$oprs->where('log_user_id !=', _UserIdFromSession());
		$query = $oprs->get();
		return $query->result();
	}

	
	public function see_all(){
		$oprs = $this->load->database('dboprs', TRUE);
		
		$data = ['notif_open' => 1];
		
		$oprs->where('log_insert_id !=', 0);
		$oprs->where('notif_open', 0);
		$oprs->update($this->logs, $data);
	}


	public function count_logs() {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('*');
		$oprs->from($this->logs);
		$oprs->where('notif_open',0);
		$oprs->where('log_insert_id !=',0);
		$oprs->where('log_user_id !=', _UserIdFromSession());
		$query = $oprs->get();
		return $query->result();
	}

	public function save_log_export($data) {
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->insert($this->logs, $data);
	}

	public function import_logs($data)
	{
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->insert($this->logs, $data);
	}	

	public function notif_open($data, $where)
	{
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->update($this->logs, $data, $where);
	}

	public function get_logs_backup()
	{
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->select('log_user_id, log_action, log_insert_id, log_user_role, date_created, notif_open');
		$oprs->from($this->logs);
		$query = $oprs->get();
		return $query->result_array();
	}

	public function clear_logs()
	{
		$oprs = $this->load->database('dboprs', TRUE);
		$oprs->empty_table($this->logs);
	}
}
?>