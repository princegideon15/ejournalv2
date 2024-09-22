<body style="background-repeat: no-repeat;background-position: center center;background-size: cover;background:linear-gradient(0deg,rgb(0, 0, 0,0.8),rgb(0, 0, 0,0.8)),url('<?php echo base_url();?>assets/oprs/img/login-3.jpg');">
  890
  <div class="container">
    <div class="card card-login mx-auto mt-5">
      <div class="card-header">Reset Password</div>
      <div class="card-body">
        <div class="text-center mb-4">
          <h4>Forgot your password?</h4>
          <p>Enter your email address and we will send you instructions on how to reset your password.</p>
        </div>
        <form id="form_forgot">
          <div class="form-group">
            <input type="email" id="get_email" name="get_email" class="form-control" placeholder="Enter email address" autofocus="autofocus">
          </div>
          <div class="form-group" id="user_option">
          </div>
          <button class="btn btn-primary btn-block" type="submit">Reset Password</button>
        </form>
        <div class="text-center">
          <a class="d-block small mt-3" href="../oprs/login">Back to Login Page</a>
        </div>
      </div>
    </div>
  </div>