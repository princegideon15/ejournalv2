<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
    class MY_Form_validation extends CI_Form_validation
    {

    function run($module = '', $group = '')
    {
       (is_object($module)) AND $this->CI = &$module;
        return parent::run($group);
    }

     public function clear_field_data() {

      $_POST = array();
    $this->_field_data = array();
    return $this;
    }

     public function unset_field_data()
    {    
        unset($this->_field_data);    
    }
}
?>