
<body>
  <div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="row justify-content-center">
      <div class="col-8 shadow rounded bg-white border" style="min-height:350px;background-image:url('<?php echo base_url(); ?>assets/oprs/img/login-2.jpg');background-size:cover; background-repeat:no-repeat">
        <div class="row">
          <div class="col-6 text-center p-5" style="background-color:white;opacity:.9;min-height:350px">
            <div class="d-flex justify-content-center align-items-center">
              <img src="<?php echo base_url("assets/oprs/img/nrcp.png"); ?>" class="img-fluid" height="25%" width="25%">
              <img src="<?php echo base_url("assets/images/skms.png"); ?>" class="img-fluid" height="40%" width="40%">
              <img src="<?php echo base_url("assets/oprs/img/ejicon-07.png"); ?>" class=" img-fluid" height="25%" width="25%">
            </div>
            <hr>
              <h5>NRCP Research Journal</h5>
              <h5>Online Peer Review Sytem</h5>
              </br>
              <h6>Manage online journals and facilitate the submission and review process of manuscripts.</h6>
            <hr> 
            <small><a href="https://nrcp.dost.gov.ph/">https://nrcp.dost.gov.ph/</a></small></br>   
            <small><a href="https://skms.nrcp.dost.gov.ph/">https://skms.nrcp.dost.gov.ph/</a></small></br>      
            <small><a href="http://researchjournal.nrcp.dost.gov.ph/">http://researchjournal.nrcp.dost.gov.ph/</a></small></br>      
          </div>
          <div class="col-6 d-flex justify-content-center align-items-center">
            <div class="row p-3">
            <div>
                  <?php if ($this->session->flashdata('_oprs_login_msg')) {
                    $msg = $this->session->flashdata('_oprs_login_msg');
                    $message = $msg['msg'];
                    $class = $msg['class'];
                    $icon = $msg['icon'];?>
                          <div class="mb-3 alert <?php echo $class; ?>" role="alert">
                            <span class="oi <?php echo $icon; ?>"></span> <?php echo $message; ?>
                          </div>
                  <?php }?>

                  <?php if ($this->session->flashdata('_oprs_sess_expire_msg')) {
                      $msg = $this->session->flashdata('_oprs_sess_expire_msg');
                      $message = $msg['msg'];
                      $class = $msg['class'];
                      $icon = $msg['icon'];?>
                            <div class="alert mb-3 <?php echo $class; ?>" role="alert">
                              <strong><span class="oi <?php echo $icon; ?>"></span> <?php echo $message; ?></strong>
                            </div>
                  <?php }?>

                  <!-- success author account creation -->
                  <?php if ($this->session->flashdata('success')) { ?>
                      <?php echo $this->session->flashdata('success'); ?>
                  <?php } ?>
                </div>

              <form action="<?php echo base_url('oprs/login/authenticate'); ?>" method="post">
                <div class="mb-3 login">
                  <input class="form-control form-control-lg <?php if($this->session->flashdata('validation_errors')['email']){ echo 'is-invalid';} ?>" id="usr_username" name="usr_username" type="email" placeholder="Email" value="<?php if (isset($_COOKIE['oprs_cookie_user'])) {echo $_COOKIE['oprs_cookie_user'];}?>">
                  <span class="invalid-feedback"><?= $this->session->flashdata('validation_errors')['email'] ?></span>
                </div>
                <div class="input-group mb-3 has-validation">
                    <input type="password" class="form-control form-control-lg <?php if($this->session->flashdata('validation_errors')['password']){ echo 'is-invalid';} ?>"  id="usr_password" name="usr_password" placeholder="Password" value="<?php if (isset($_COOKIE['oprs_cookie_pass'])) {echo $_COOKIE['oprs_cookie_pass'];}?>">
                    <span class="input-group-text bg-white text-muted rounded-end" id="inputGroupPrepend3"><a class="text-muted cursor-pointer" href="javascript:void(0);" onclick="togglePassword('#usr_password', '#password_icon')"><i class="fa fa-eye-slash" id="password_icon"></i></a></span>
                    <span class="invalid-feedback"><?= $this->session->flashdata('validation_errors')['password'] ?></span>
                </div>
                <div class="mb-3 d-flex gap-1 align-items-center">
                  <input type="checkbox" class="form-check-input" value="1" name="oprs_remember" id="oprs_remember" 
                    <?php if (isset($_COOKIE['oprs_remember_me'])) {
                      echo 'checked';
                    }
                    ?> >
                  <label class="form-check-label mt-1" for="oprs_remember">
                    Remember me
                  </label>
                </div>
                <div class="form-group" id="user_option"></div>
                <div><button type="submit" name="admin_login" value="admin_login" class="btn btn-primary btn-lg w-100 font-weight-bold">Log In</button></div>
              </form>
              <div class="pt-5 text-center"><a class="text-secondary text-decoration-none fw-bold text-xs" href="../support/forgot">Forgot Password?</a></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

