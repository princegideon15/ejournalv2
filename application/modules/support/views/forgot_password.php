
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

              <?php if ($this->session->flashdata('reset_password_success')) { ?>
                    <div class="alert alert-success" role="alert">
                      <h4 class="alert-heading"><span class="fa fa-check me-1"></span>Success!</h4>
                      <hr>
                      <?php echo $this->session->flashdata('reset_password_success'); ?>
                    </div>
              <?php } else { ?>

              

                <?php echo form_open('support/forgot/send_password', ['method' => 'post', 'id' => 'form_forgot']); ?>
                  <?php $accounts = $this->session->userdata('accounts');?>
                  <?php if(isset($accounts) && count($accounts) > 1){ ?>
                    <input type="hidden" name="has_role" value="true">
                    <div class="alert alert-warning" role="alert">
                      <h4 class="alert-heading h6 fw-bold"><span class="fa fa-exclamation-triangle text-warning"></span> Multiple Accounts Found.</h4>
                      <hr>
                      <p class="mb-3">Our system has found multiple accounts linked to (<b><?= set_value('get_email', $this->session->flashdata('get_email')); ?></b>). Please select the account for which you want to reset the password:</p>

                      <?php foreach($accounts as $row){ 
                        $role = ($row->usr_role == 1) ? 'Author' : 'Reviewer';
                        echo '<div class="form-check form-check-inline pe-2">
                                <input type="radio" value="'. $row->usr_id . '" name="user_id" class="form-check-input" id="' . $role . '' . $row->usr_role . '">
                                <label class="form-check-label pt-1 ms-1" for="' . $role . '' . $row->usr_role . '"> ' . $role . '</label>
                              </div>';
                        
                        
                      } ?>
                      <span class="invalid-feedback"><?= $this->session->flashdata('validation_errors')['role'] ?></span>
                    </div>
                  <?php }else{ ?>
                    <h4 class="fw-bold">Forgot your password?</h4>
                    <p>Enter your email address and we will send you instructions on how to reset your password.</p>

                      <div class="mb-3">
                        <input type="email" id="get_email" name="get_email" class="form-control form-control-lg <?php if($this->session->flashdata('validation_errors')['email']){ echo 'is-invalid';} ?>" placeholder="Enter email address" value="<?= set_value('get_email', $this->session->flashdata('get_email')); ?>" autofocus="autofocus">
                        <span class="invalid-feedback"><?= $this->session->flashdata('validation_errors')['email'] ?></span>
                      </div>

                  <?php } ?>

                  <button class="btn btn-lg btn-primary w-100" type="submit" id="reset_password_btn" onclick="disableOnSubmit(this, '#form_forgot', 'reset')">Submit</button>
                <?php echo form_close(); ?>

                

              <?php } ?>
              <div class="pt-5 text-center"><a class="text-secondary text-decoration-none fw-bold text-sm" href="../oprs/login">Back to Login</a></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

