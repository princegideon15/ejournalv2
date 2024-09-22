<?php $role = $this->session->userdata('_oprs_type_num');?>
<nav class="navbar navbar-expand navbar-dark bg-dark fixed-top">

  <a class="navbar-brand mr-1" href="dashboard">
    <img src="<?php echo base_url("assets/oprs/img/nrcp.png"); ?>" height="40" width="40">
    <img src="<?php echo base_url("assets/images/skms.png"); ?>" height="40" width="80">
    <img src="<?php echo base_url("assets/oprs/img/ejicon-07.png"); ?>" height="40" width="40">
  eReview</a>
  <!-- <button class="btn btn-link btn-sm text-white order-1 order-sm-0" id="sidebarToggle" href="#">
  <i class="fas fa-bars"></i>
  </button> -->
  <!-- Navbar Search -->
  <!-- <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
    <div class="input-group">
      <input type="text" class="form-control" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
      <div class="input-group-append">
        <button class="btn btn-primary" type="button">
        <i class="fas fa-search"></i>
        </button>
      </div>
    </div>
  </form> -->
  <!-- Navbar -->
  <!-- <ul class="navbar-nav ml-auto ml-md-0 w-100"> -->
  <ul class="navbar-nav ml-auto">

  <li class="nav-item dropdown no-arrow mx-1">
        <?php if (_UserRoleFromSession() == 3 || _UserRoleFromSession() == 8) { ?>
          <a class="nav-link dropdown-toggle" href="dashboard" id="alertsDropdown" role="button" data-toggle="dropdown">
            <i class="fas fa-bell fa-fw oprs_notif">
              <!-- <?php  if(count($logs) > 0){ ?>
                <span class="badge badge-danger font-weight-bold notif_count" style="font-size:11px;position:fixed; margin-left:-5px;margin-top:2px">          
                  <?php echo count($logs); ?>
                </span>
              <?php }?> -->
            </i>
          </a>
        <?php } ?>
        <div class="dropdown-menu dropdown-menu-right p-0 oprs_notif_list shadow-lg bg-white rounded" style="width:400px; max-width:400px; max-height:600px; overflow:auto">
        
        </div>
      </li>
    <li class="nav-item dropdown no-arrow">
      <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown">
      <i class="fas fa-user-circle fa-fw"></i> <?php echo $this->session->userdata('_oprs_username'); ?> 
      </a>
      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
        <a class="dropdown-item" href="javascript:void(0);" data-toggle="modal" data-target="#changePassModal">Change Password</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="javascript:void(0);" data-toggle="modal" data-target="#logoutModal">Logout</a>
      </div>
    </li>
  </ul>
</nav>
<div id="wrapper">
  <div class="sidebar">
    <ul class="navbar-nav" style="padding-top:5em;position:sticky;top:0em;height:100vh">
      <?php if ($role == 7 || $role == 8 || $role == 3) {?>
      <li class="nav-item">
        <a class="nav-link" href="dashboard">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span>
        </a>
      </li>
      <?php }?>
      <li class="nav-item ">
        <a class="nav-link" href="manuscripts">
          <i class="fas fa-fw fa-clipboard-list"></i>
          Manuscripts <sup><span class="badge badge-dark" style="font-size:11px"><?php echo count($manus); ?></span></sup>
        </a> 
      </li>
      <?php if ($role == 7 || $role == 8) {?>
      <li class="nav-item">
        <a class="nav-link" href="user">
          <i class="fas fa-fw fa-user"></i>
          Users  <sup><span class="badge badge-dark" style="font-size:11px"><?php echo $usr_count; ?></span></sup>
        </a>
      </li>
      <?php }?>
      <?php if ($role == 8 || $role == 3) {?>
      <li class="nav-item ">
        <a class="nav-link" href="reports">
          <i class="fas fa-fw fa-chart-bar"></i>
          <span>Reports</span>
        </a>
      </li>
      <?php }?>
      <?php if ($role == 8) {?>
      <li class="nav-item ">
        <a class="nav-link" href="logs">
          <i class="fas fa-fw fa-clipboard-list"></i>
          <span>Activity Logs</span>
        </a>
      </li>
      <li class="nav-item ">
        <a class="nav-link" href="controls">
          <i class="fas fa-fw fa-cogs"></i>
          <span>Control Panel</span>
        </a>
      </li>
      <li class="nav-item ">
        <a class="nav-link" href="feedbacks">
          <i class="fas fa-edit"></i>
          Feedbacks  <sup><span class="badge badge-dark" style="font-size:11px"><?php echo $feed_count; ?></span></sup>
        </a>
      </li>
      <li class="nav-item ">
        <a class="nav-link" href="emails">
          <i class="fas fa-envelope-open"></i>
          Email Notifcatiions
        </a>
      </li>
      <?php }?>
      <?php if ($role == 8) {?>
      <li class="nav-item ">
        <a class="nav-link" href="backup">
          <i class="fas fa-database"></i>
          <span>Backup/Restore Database</span>
        </a>
      </li>
      <?php }?>
      <?php if ($role == 8 || $role == 3) {?>
      <li class="nav-item ">
        <a class="nav-link text-info font-weight-bold" href="<?php echo base_url('../../admin/dashboard'); ?>">
        <!-- <a class="nav-link" href="<?php echo base_url('../../admin/dashboard'); ?>"> -->
          <i class="fas fa-fw fa fa-book"></i>
          <span>eJournal</span>
        </a>
      </li>
      <?php }?>
    </ul>
  </div>
  <!-- CHANGE PASSWORD -->
  <div class="modal fade" id="changePassModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="user_modal"><span class="oi oi-shield"></span> Change Password</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="form_change_pass">
            <div class="form-group">
              <label for="old_password">Old Password</label>
              <input type="password" class="form-control" id="old_password" name="old_password" placeholder="Enter old password" >
            </div>
            <div class="form-group">
              <label for="usr_password">New Password</label>
              <input type="password" class="form-control" id="usr_password" name="usr_password" placeholder="Enter new password" >
            </div>
            <div class="form-group">
              <label for="repeat_password">Repeat Password</label>
              <input type="password" class="form-control" name="repeat_password" id="repeat_password" placeholder="Repeat password" >
              <p id="match" class="mt-2"></p>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- /.CHANGE PASSWORD -->
  <div id="content-wrapper">

  <!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
      <button class="close" type="button" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">×</span>
      </button>
    </div>
    <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
    <div class="modal-footer">
      <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
      <a class="btn btn-primary" href="javascript:void(0);" onclick="verify_feedback();">Logout</a>
    </div>
  </div>
</div>
</div>

  <!-- feedback modal -->
<div class="modal fade" id="feedbackModal" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header pb-0">
        <p><span class="modal-title font-weight-bold h3">Your feedback</span><br/>
        <small>We would like your feedback to improve our system.</small></p>
        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button> -->
      </div>
      <div class="modal-body p-4">
        <form id="feedback_form">
            <div class="feedback text-center">
                <p class="font-weight-bold h4 text-center">User Interface</p>
                <div class="feedback-container ui-container">
                    <div class="feedback-item">
                        <label for="ui-1" data-toggle="tooltip" data-placement="bottom" title="Sad">
                            <input class="radio" type="radio" name="fb_rate_ui" id="ui-1" value="1" >
                            <span >🙁</span>
                        </label>
                    </div>

                    <div class="feedback-item">
                        <label for="ui-2" data-toggle="tooltip" data-placement="bottom" title="Neutral">
                            <input class="radio" type="radio" name="fb_rate_ui" id="ui-2" value="2">
                            <span>😶</span>
                        </label>
                    </div>

                    <div class="feedback-item">
                        <label for="ui-3" data-toggle="tooltip" data-placement="bottom" title="Happy">
                            <input class="radio" type="radio" name="fb_rate_ui" id="ui-3" value="3">
                            <span>🙂</span>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="fb_suggest_ui"></label>
                    <textarea class="form-control" name="fb_suggest_ui" id="fb_suggest_ui" rows="3" placeholder="Type your suggestions here"></textarea>
                </div>

                <hr/>

                <p class="font-weight-bold h4 text-center">User Experience</p>
                <div class="feedback-container ux-container">
                    <div class="feedback-item">
                        <label for="ux-1" data-toggle="tooltip" data-placement="bottom" title="Sad">
                            <input class="radio" type="radio" name="fb_rate_ux" id="ux-1" value="1">
                            <span>🙁</span>
                        </label>
                    </div>

                    <div class="feedback-item">
                        <label for="ux-2" data-toggle="tooltip" data-placement="bottom" title="Nuetral">
                            <input class="radio" type="radio" name="fb_rate_ux" id="ux-2" value="2">
                            <span>😶</span>
                        </label>
                    </div>

                    <div class="feedback-item">
                        <label for="ux-3" data-toggle="tooltip" data-placement="bottom" title="Happy">
                            <input class="radio" type="radio" name="fb_rate_ux" id="ux-3" value="3">
                            <span>🙂</span>
                        </label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="fb_suggest_ux"></label>
                    <textarea class="form-control" name="fb_suggest_ux" id="fb_suggest_ux" rows="3" placeholder="Type your suggestions here"></textarea>
                </div>

                <div class="btn-group pull-right" role="group">
                    <button class="btn btn-outline-secondary" type="button" data-dismiss="modal">Later</button>
                    <button type="submit" class="btn btn-primary">Submit Feedback</button>
                </div>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- /.feedback modal -->
<span class="hidden cookie_id"><?php echo _UserIdFromSession(); ?></span>