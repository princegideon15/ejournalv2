<?php

defined('BASEPATH') OR exit('No direct script access allowed');

function get_ip_address_browser(){

    $useragent = $_SERVER['HTTP_USER_AGENT'];
    $name = 'NA';

    if (preg_match('/MSIE/i', $useragent) && !preg_match('/Opera/i', $useragent)) {
        $name = 'Internet Explorer';
    } elseif (preg_match('/Firefox/i', $useragent)) {
        $name = 'Mozilla Firefox';
    } elseif (preg_match('/Chrome/i', $useragent)) {
        $name = 'Google Chrome';
    } elseif (preg_match('/Safari/i', $useragent)) {
        $name = 'Apple Safari';
    } elseif (preg_match('/Opera/i', $useragent)) {
        $name = 'Opera';
    } elseif (preg_match('/Netscape/i', $useragent)) {
        $name = 'Netscape';
    }

    
    $ip = getenv('HTTP_CLIENT_IP')?:
            getenv('HTTP_X_FORWARDED_FOR')?:
                getenv('HTTP_X_FORWARDED')?:
                    getenv('HTTP_FORWARDED_FOR')?:
                        getenv('HTTP_FORWARDED')?:
                            getenv('REMOTE_ADDR');
    $data = [
        'ip' => $ip,
        'browser' => $name
    ];

    return $data;
                            
}

function save_log_oprs($user, $action, $id = 0, $role = 0) {

	$ip_info = get_ip_address_browser();

	$data = array('log_user_id' => $user,
		'log_action' => $action,
		'log_insert_id' => $id,
		'log_ip' => $ip_info['ip'],
		'log_browser' => $ip_info['browser'],
		'log_user_role' => $role,
		'date_created' => date('Y-m-d H:i:s'),
		'notif_open' => '0'
	);

	$CI =& get_instance();
	$oprs = $CI->load->database('dboprs', TRUE);
	$oprs->insert('tbllogs', $data);
}

function save_log_ej($user, $action, $id = 0) {
	
	$ip_info = get_ip_address_browser();
	
	$data = array('log_user_id' => $user,
		'log_action' => $action,
		'log_insert_id' => $id,
		'log_ip' => $ip_info['ip'],
		'log_browser' => $ip_info['browser'],
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