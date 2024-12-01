
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
              <h4 class="fw-bold">Forgot your password?</h4>
              <p>Enter your email address and we will send you instructions on how to reset your password.</p>

              <!-- <?php echo form_open('support/forgot/send_password'); ?> -->
              <form class="mb-5" id="form_forgot">
                <div class="mb-3">
                  <input type="email" id="get_email" name="get_email" class="form-control form-control-lg" placeholder="Enter email address" autofocus="autofocus">
                </div>
                <div class="form-group" id="user_option">
                </div>
                <button class="btn btn-lg btn-primary w-100" type="submit">Reset Password</button>
              </form>
              <!-- <?php echo form_close(); ?> -->
              <div class="pt-5 text-center"><a class="text-secondary text-decoration-none fw-bold text-sm" href="../oprs/login">Back to Login</a></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

