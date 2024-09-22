<body style="background-repeat: no-repeat;background-position: center center;background-size: cover;background:linear-gradient(0deg,rgb(84, 84, 84,0.9),rgb(84, 84, 84,0.9)),url('<?php echo base_url("assets/images/login-1.jpg"); ?>">
  <div class="container" style="margin-top: 10%">
    <div class="row">
      <div class="col-md-5 mx-auto">
        <div class="mx-auto">
          <?php if ($this->session->flashdata('login_msg')) {
	$msg = $this->session->flashdata('login_msg');
	$message = $msg['msg'];
	$class = $msg['class'];
	$icon = $msg['icon'];?>
          <div class="alert <?php echo $class; ?> alert-dismissible fade show" role="alert">
            <strong><span class="oi <?php echo $icon; ?>"></span> <?php echo $message; ?></strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <?php }?>
          <?php if ($this->session->flashdata('sess_expire_msg')) {
	$msg = $this->session->flashdata('sess_expire_msg');
	$message = $msg['msg'];
	$class = $msg['class'];
	$icon = $msg['icon'];?>
          <div class="alert <?php echo $class; ?> alert-dismissible fade show" role="alert">
            <strong><span class="oi <?php echo $icon; ?>"></span> <?php echo $message; ?></strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <?php }?>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-5 mx-auto">
        <div class="card card-login ">
          <div class="card-header  text-center text-white">
            <div class="row">
              <div class="col-sm  p-0">
                <img src="<?php echo base_url("assets/images/nrcp.png"); ?>" class="img-fluid" height="70%" width="70%">
              </div>
              <div class="col-sm  p-0">
                <img src="<?php echo base_url("assets/images/skms.png"); ?>" height="90px" width="170px">
              </div>
              <div class="col-sm  p-0">
                <img src="<?php echo base_url("assets/images/ejicon-07.png"); ?>" class=" img-fluid" height="70%" width="70%">
              </div>
            </div>
            <ul class="list-unstyled">
              <li>National Research Council of the Philippines</li>
              <li class="text-uppercase font-weight-bold">Scientific Knowledge Management System</li>
              <li>NRCP Research Journal</li>
            </ul>
          </div>
          <div class="card-body ">
            <?php echo form_open('admin/login/authenticate'); ?>
            <div class="form-group">
              <label for="acc_username">USERNAME</label>
              <input class="form-control input-sm" name="acc_username"  type="text" aria-describedby="acc_username" placeholder="Your username"
              value="<?php if (isset($_COOKIE['cookie_user'])) {echo $_COOKIE['cookie_user'];}?>" required>
            </div>
            <div class="form-group">
              <label for="acc_password">PASSWORD</label>
              <input class="form-control" name="acc_password" type="password" placeholder="Your password"
              value="<?php if (isset($_COOKIE['cookie_pass'])) {echo $_COOKIE['cookie_pass'];}?>" required>
            </div>
            <div class="form-group row">
              <div class="col-sm-6">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="1" name="remember" id="remember" <?php if (isset($_COOKIE['remember_me'])) {
	echo 'checked="checked"';
} else {
	echo '';
}
?> >
                  <label class="form-check-label" for="remember">
                    Remember me
                  </label>
                </div>
              </div>
              <div class="col-sm-6 text-right">
              </div>
            </div>
            <button type="submit" name="admin_login" value="admin_login" class="btn bg-primary btn-block text-white" href="index.html">Login</button>
            <?php echo form_close(); ?>
          </div>
          <div class="card-footer text-center">
            <a class="text-default" href="http://researchjournal.nrcp.dost.gov.ph/" target="_blank"><span class="oi oi-globe"></span> Visit Client Page</a>
          </div>
        </div>
      </div>
    </div>
  </div>