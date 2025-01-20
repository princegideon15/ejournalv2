

<style>
    .rating-star {
        font-size: 3rem;
        cursor: pointer;
        color: gray;
        padding-top: 0;
        margin-top: 0;
    }
    .rating-star.selected {
        color: gold;
    }
    .character-counter {
            font-size: 0.9rem;
            margin-top: 0.5rem;
    }
    .character-counter.exceeded {
        color: red;
    }
</style>

<script>
  let recaptchaWidgetId_logout;

  // Initialize reCAPTCHA and store the widget ID
  window.onload = function () {
      recaptchaWidgetId_logout = grecaptcha.render('captcha_logout', {
          'sitekey': '6LcTEV8qAAAAACVwToj7gI7BRdsoEEhJCnnFkWC6',
          'callback': onRecaptchaSuccess,
          'expired-callback': onRecaptchaExpired
      });
  };

</script>

<?php $logged_in = $this->session->userdata('user_id'); ?>

<nav class="navbar navbar-expand-lg fixed-topx custom-border">
  <div class="container-fluid p-0">
    
    <div class="d-flex gap-1 align-items-center me-3">
      <a href="https://skms.nrcp.dost.gov.ph/" target="_blank">
        <img src="<?php echo base_url("assets/images/skms.png"); ?>" height="60" width="110">
      </a>
      <a href="https://nrcp.dost.gov.ph/" target="_blank">
        <img src="<?php echo base_url("assets/oprs/img/nrcp.png"); ?>" height="55" width="55">
      </a>
      <div class="d-flex flex-column text-center">
        <div class="display-5 fw-bolder nav-title">NRCP</div>
        <div class="fw-bolder nav-subtitle">RESEARCH JOURNAL</div>
      </div>
    </div>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link text-dark fw-bolder" id="nav_home" href="<?php echo base_url();?>">Home</a>
        </li>
        <!-- <li class="nav-item">
          <a class="nav-link text-dark fw-bolder" href="<?php echo base_url('/client/ejournal/about');?>">About<span class="sr-only">(current)</span></a>
        </li> -->
        <!-- <li class="nav-item">
          <a class="nav-link text-dark fw-bolder" href="<?php echo base_url('/client/ejournal/guidelines');?>">Guidelines</a>
        </li> -->
        <!-- <li class="nav-item">
          <a class="nav-link text-dark" href="<?php echo base_url('/client/ejournal/articles');?>">Articles</a>
        </li> -->
        <li class="nav-item">
          <a class="nav-link text-dark fw-bolder" href="<?php echo base_url('/client/ejournal/submission');?>">Submissions</a>
          <!-- <a class="nav-link text-dark" href="https://skms.nrcp.dost.gov.ph/main/login" target="_blank">Submit manuscript</a> -->
        </li>
        <!-- <li class="nav-item">
          <a class="nav-link text-dark fw-bolder" href="<?php echo base_url('/client/ejournal/submission');?>">Research Integrity</a>
        </li> -->
        <?php if($logged_in) {
          echo '<li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    '.$this->session->userdata('name').'
                  </a>
                  <ul class="dropdown-menu">
                  
                    <li><p class="dropdown-header pb-0">Logged in as <strong>CLIENT</strong></p></li>
                    <li><p class="dropdown-header pt-0">Last visit: ' . $this->session->userdata('last_visit_date') . '</p></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="'.base_url('/client/user/profile').'">My Profile</a></li>
                    <li><a class="dropdown-item" href="'.base_url('/client/user/downloads').'">My Downloads</a></li>
                    <li><a class="dropdown-item" onclick="logout()">Logout</a></li>
                  </ul>
                </li>';
        }else{
          echo '<li class="nav-item">
                  <a class="nav-link text-dark fw-bolder" id="logout" href="'.base_url('/client/login').'">Account</a>
                </li>';
        } ?>

        

        
      </ul>
       
      <span class="navbar-text">
        <span class="badge badge-issn rounded-0 fs-6">ISSN: 2980 - 4728</span>
      </span>
    </div>
  </div>
  </div>
</nav>

<!-- FEEDBACK MODAL -->
<div class="modal fade" id="feedbackModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- <div class="modal-header pb-0">
        <p><span class="modal-title font-weight-bold h3">Your feedback</span><br/>
        <small>We would like your feedback to improve our system.</small></p>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div> -->
      <div class="modal-body p-4">
        <h5 class="fw-bold main-link">Thank you for visiting the eJournal website!</h5>
        <p>To improve the performance of the system, kindly provide us your feedback.</p>
        <hr>
        <form id="feedback_form">
            <h6 class="fw-bold mb-0">User Interface</h6>
            <div class="d-flex gap-3 mb-0">
              <span class="rating-star rate-ui" data-value="1">&#9733;</span>
              <span class="rating-star rate-ui" data-value="2">&#9733;</span>
              <span class="rating-star rate-ui" data-value="3">&#9733;</span>
              <span class="rating-star rate-ui" data-value="4">&#9733;</span>
              <span class="rating-star rate-ui" data-value="5">&#9733;</span>
            </div>
            <textarea class="form-control" id="fb_suggest_ui" rows="3" placeholder="Type your suggestions here..." maxlength="300"></textarea>
            <div id="char_count_ui" class="character-counter text-muted">0 / 300 characters</div>
            <div class="rate-ui-validation text-danger mt-2"></div>

            <hr/>

            <h6 class="fw-bold mb-0">User Experience</h6>
            <div class="d-flex gap-3 mb-0">
              <span class="rating-star rate-ux" data-value="1">&#9733;</span>
              <span class="rating-star rate-ux" data-value="2">&#9733;</span>
              <span class="rating-star rate-ux" data-value="3">&#9733;</span>
              <span class="rating-star rate-ux" data-value="4">&#9733;</span>
              <span class="rating-star rate-ux" data-value="5">&#9733;</span>
            </div>

            <textarea class="form-control" name="fb_suggest_ux" id="fb_suggest_ux" rows="3" placeholder="Type your suggestions here..." maxlength="300"></textarea>
            <div id="char_count_ux" class="character-counter text-muted">0 / 300 characters</div>
            <div class="rate-ux-validation text-danger mt-2"></div>

            <div class="mt-3 mb-0 w-100" id="google_recaptchav2_container">
                <div data-sitekey="6LcTEV8qAAAAACVwToj7gI7BRdsoEEhJCnnFkWC6" id="captcha_logout"></div>
                <p class="text-danger" id="g-recaptcha"></p>
            </div>

        </form>
      </div>
      <div class="modal-footer">
          <button class="btn btn-light" type="button" data-bs-dismiss="modal">Later</button>
          <button type="button" id="submit_feedback" class="btn main-btn" disabled>Submit</button>
      </div>
    </div>
  </div>
</div>
<!-- /FEEDBACK MODAL -->
