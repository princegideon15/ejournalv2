<?php $this->load->view('common/header');?>
<?php if($this->session->userdata('_oprs_user_id') != '')$this->load->view('common/navbar'); ?>
<?php $this->load->view($main_content);?>
<?php $this->load->view('common/footer');?>
