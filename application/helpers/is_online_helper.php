<?php

defined('BASEPATH') OR exit('No direct script access allowed');

function is_online($id)
{
   $data = array('usr_status' => 1,
                     'usr_login_time' => date('Y-m-d H:i:s'));

   $where = array('row_id' => $id);

   $CI =& get_instance();
   $oprs = $CI->load->database('dboprs', TRUE);
   $oprs->update('tblusers',$data,$where);
}

function is_offline($id)
{
   $data = array('usr_status' => 0,
                  'usr_logout_time' => date('Y-m-d H:i:s'));

   $where = array('row_id' => $id);

   $CI =& get_instance();
   $oprs = $CI->load->database('dboprs', TRUE);
   $oprs->update('tblusers',$data,$where);

}

function logout_on_session_expire()
{
   session_unset();
   redirect('/admin/login');
}

?>
