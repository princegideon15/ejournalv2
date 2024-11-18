<!-- <body style="background:linear-gradient(0deg,rgb(0, 0, 0,0.8),rgb(0, 0, 0,0.8)),url('<?php echo base_url(); ?>assets/oprs/img/login-3.jpg');"> -->
<body>
  <div class="container" style="margin-top: 10%">
    <div class="row justify-content-center ">
      <div class="col-9 shadow rounded bg-white border" style="min-height:350px;background-image:url('<?php echo base_url(); ?>assets/oprs/img/login-2.jpg');background-size:cover; background-repeat:no-repeat">
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
              <h6>Provide the management for online journals and reviewing of manuscripts.</h6>
            <hr> 
            <small><a href="https://nrcp.dost.gov.ph/">https://nrcp.dost.gov.ph/</a></small></br>   
            <small><a href="https://skms.nrcp.dost.gov.ph/">https://skms.nrcp.dost.gov.ph/</a></small></br>      
            <small><a href="http://researchjournal.nrcp.dost.gov.ph/">http://researchjournal.nrcp.dost.gov.ph/</a></small></br>      
          </div>
          <div class="col-6 p-3 pt-5" >

          <?php if ($this->session->flashdata('_oprs_login_msg')) {
                $msg = $this->session->flashdata('_oprs_login_msg');
                $message = $msg['msg'];
                $class = $msg['class'];
                $icon = $msg['icon'];?>
                      <div class="text-left mb-0 alert border border-0 <?php echo $class; ?>" role="alert" style="font-size:14px">
                        <span class="oi <?php echo $icon; ?>"></span> <?php echo $message; ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        </button>
                      </div>
                      <?php
              }?>
                      <?php if ($this->session->flashdata('_oprs_sess_expire_msg')) {
                $msg = $this->session->flashdata('_oprs_sess_expire_msg');
                $message = $msg['msg'];
                $class = $msg['class'];
                $icon = $msg['icon'];?>
                      <div class="alert mb-0 alert border border-0 <?php echo $class; ?>" role="alert">
                        <strong><span class="oi <?php echo $icon; ?>"></span> <?php echo $message; ?></strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        </button>
                      </div>
                      <?php
              }?>

              
            <?php if ($this->session->flashdata('success')) { ?>
                <?php echo $this->session->flashdata('success'); ?>
            <?php } ?>

            <?php echo form_open('oprs/login/authenticate'); ?>
              <div class="form-group mt-3 login">
                <div class="input-group mb-2">
                  <div class="input-group-prepend">
                    <div class="input-group-text"><span class="fa fa-user"></span></div>
                  </div>
                  <input class="form-control form-control-lg" id="usr_username" name="usr_username" type="text" placeholder="Username" value="<?php if (isset($_COOKIE['oprs_cookie_user'])) {echo $_COOKIE['oprs_cookie_user'];}?>" required>
                </div> 
              </div>
              <div class="form-group">
                <div class="input-group mb-2">
                  <div class="input-group-prepend">
                    <div class="input-group-text"><span class="fa fa-lock"></span></div>
                  </div>
                  <input class="form-control form-control-lg" id="usr_password" name="usr_password" type="password" placeholder="Password" value="<?php if (isset($_COOKIE['oprs_cookie_pass'])) {echo $_COOKIE['oprs_cookie_pass'];}?>" required>
                </div>
              </div>
              <div class="form-group text-left">
                <div class="custom-control custom-checkbox">
                  <input type="checkbox" class="custom-control-input" value="1" name="oprs_remember" id="oprs_remember" 
                  <?php if (isset($_COOKIE['oprs_remember_me'])) {
                    echo 'checked';
                  }
                  ?> >
                  <label class="custom-control-label pt-1 text-mute small" for="oprs_remember">Remember me</label>
                </div>
              </div>
           
              <div class="form-group text-left" id="user_option">
              </div>
              <button type="submit" name="admin_login" value="admin_login" class="btn btn-primary btn-lg w-100 font-weight-bold">Log In</button>
              <div class="pt-5 text-center" ><a class="text-secondary font-weight-bold small" href="../support/forgot">Forgot Password?</a></div>
              <?php echo form_close(); ?>
            </div>
        </div>
      </div>
    </div>
  </div>

