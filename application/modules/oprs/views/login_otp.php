
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

              
              <?php if ($this->session->flashdata('otp')) { ?>
                  <?php echo $this->session->flashdata('otp'); ?>
              <?php } ?>


              <?php $ref_code = ($this->session->userdata('otp_ref_code') ?? $ref_code); ?>
              <?=form_open('oprs/login/verify_otp/' . $ref_code, ['method' => 'post', 'id' => 'verifyOTPForm'])?>
              <!-- <form action="<?php echo base_url('oprs/login/verify_otp/' . $ref_code); ?>" method="post" id="verifyOTPForm"> -->
                
                  <h4 class="fw-bold">Login Verification</h4>
                  <div class="mb-3">
                      <label for="otp" class="form-label">Enter the 6-digit One-Time-Password (OTP) below</label>
                      <input type="otp" class="form-control form-control-lg text-center fw-bold <?php if($this->session->flashdata('validation_errors')['otp']){ echo 'is-invalid';} ?>" id="otp" name="otp" placeholder="xxxxxx" maxlength="6">
                      <span class="invalid-feedback"><?=$this->session->flashdata('validation_errors')['otp']?></span>
                  </div>
                  <?php //$disabled ?>
                  <button type="submit" class="btn btn-primary w-100 mt-1 mb-2" id="verify_code" onclick="disableOnSubmit(this, '#verifyOTPForm', 'verify')">Verify Code</button>
                  <a type="button" class="btn btn-primary w-100 mt-1 disabled" id="resend_code">Resend Code</a>
              <!-- </form> -->
              <?=form_close()?>

              <div class="pt-5 text-center"><a class="text-secondary text-decoration-none fw-bold text-sm" href="<?= base_url('oprs/login') ?>">Back to Login</a></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

