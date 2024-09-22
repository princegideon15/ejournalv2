<nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-primary">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
  <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
    <?php if ($this->session->userdata('_oprs_type_num') != '') {?>
    <a class="navbar-brand" href="#">eJournal <?php echo $this->session->userdata('_oprs_type'); ?></a>
    <?php } else {?>
    <a class="navbar-brand" href="#">eJournal</a>
    <?php }?>
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="http://researchjournal.nrcp.dost.gov.ph/" target="_blank"><span class="oi oi-globe"></span> Visit Client Page</a>
      </li>
    </ul>
    <ul class="navbar-nav ml-auto text-center dropdown" >
      <?php if ($this->session->userdata('_oprs_logged_in')) {?>
      <li class="nav-item active" style="width:185px">
        <span class=" ">
          <a href="#" class="nav-link dropdown-toggle" id="user_dropdown" data-toggle="dropdown">
            <span class="oi oi-person mr-1"></span>
            <?php echo $this->Login_model->get_username_for_logs($this->session->userdata('_oprs_user_id')); ?>
          </a>
          <div class="dropdown-menu" aria-labelledby="user_dropdown">
            <a class="dropdown-item " href="#" data-toggle="modal" data-target="#set_dp"><span class="oi oi-camera-slr"></span> Set Display Picture</a>
            <a class="dropdown-item " href="#" data-toggle="modal" data-target="#change_pass"><span class="oi oi-shield"></span> Change Password</a>
            <a class="dropdown-item" href="<?php echo base_url('/admin/login/logout'); ?>"> <span class="oi oi-account-logout "></span> Logout</a>
            <!-- <a class="dropdown-item" href="javascript:void(0);" onclick="verify_feedback()"> <span class="oi oi-account-logout "></span> Logout</a> -->
          </div>
        </span>
      </li>
      <li class="nav-item">
      </li>
      <?php }?>
    </ul>
  </div>
</nav>