<?php

defined('BASEPATH') OR exit('No direct script access allowed');

function save_log_oprs($user, $action, $id = 0, $role) {
	
	$data = array('log_user_id' => $user,
		'log_action' => $action,
		'log_insert_id' => $id,
		'log_user_role' => $role,
		'date_created' => date('Y-m-d H:i:s'),
		'notif_open' => '0',
		'log_source' => 'Admin'
	);

	$CI =& get_instance();
	$oprs = $CI->load->database('dboprs', TRUE);
	$oprs->insert('tbllogs', $data);
}

function save_log_ej($user, $action, $id = 0) {
	
	$data = array('log_user_id' => $user,
		'log_action' => $action,
		'log_insert_id' => $id,
		'date_created' => date('Y-m-d H:i:s'),
		'notif_shown' => '0',
		'log_source' =>'Client');

	$CI =& get_instance();
	$ej = $CI->load->database('default', TRUE);
	$ej->insert('tbllogs', $data);
}

function _UserIdFromSession() {
	$CI =& get_instance();
	$user_id = $CI->session->userdata('_oprs_user_id');
	return $user_id;
}

function _UserRoleFromSession() {
	$CI =& get_instance();
	$user_id = $CI->session->userdata('_oprs_type_num');
	return $user_id;
}

?>