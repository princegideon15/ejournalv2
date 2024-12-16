

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


<?php $role = $this->session->userdata('_oprs_type_num');?>
<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark fixed-top">
    <!-- Navbar Brand-->
    <a class="navbar-brand ps-3" href="javascript:void(0);">
        <img src="<?php echo base_url("assets/oprs/img/nrcp.png"); ?>" height="40" width="40">
        <img src="<?php echo base_url("assets/images/skms.png"); ?>" height="40" width="80">
        <img src="<?php echo base_url("assets/oprs/img/ejicon-07.png"); ?>" height="40" width="40">
    </a>
    <!-- Sidebar Toggle-->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#!">eJournal</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="http://researchjournal.nrcp.dost.gov.ph/" target="_blank">Client Landing Page</a>
        </li>
    </ul>
    <!-- Navbar Search-->
    <!-- <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
        <div class="input-group">
            <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
            <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
        </div>
    </form> -->
    <!-- Navbar-->
    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-user fa-fw me-1"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li><h6 class="dropdown-header"><?php echo $this->session->userdata('_oprs_username'); ?></h6></li>
                <li><h6 class="dropdown-header"><?php echo $this->session->userdata('_oprs_type'); ?></h6></li>
                <li><hr class="dropdown-divider"></li>
                <!-- <a class="dropdown-item " href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#set_dp"><span class="oi oi-camera-slr"></span> Set Display Picture</a> -->
                <li><a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#change_pass"><span class="oi oi-shield"></span> Change Password</a></li>
                <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#database_modal"><span class="oi oi-hard-drive"></span> Backup/Restore Database</a></li>
                <li><a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#logoutModal"> <span class="oi oi-account-logout "></span> Logout</a></li>
            </ul>
        </li>
    </ul>
</nav>

<!-- Change Password Modal -->
<div class="modal fade" id="changePassModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="user_modal"><span class="oi oi-shield"></span> Change Password</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="form_change_pass">
                <div class="mb-3">
                    <label class="form-label" for="old_password">Old Password</label>
                    <input type="password" class="form-control form-control-lg" id="old_password" name="old_password" placeholder="Enter old password" >
                </div>
                <div class="mb-3">
                    <label class="form-label" for="usr_password">New Password</label>
                    <input type="password" class="form-control form-control-lg" id="usr_password" name="usr_password" placeholder="Enter new password" >
                </div>
                <div class="mb-3">
                    <label class="form-label" for="repeat_password">Repeat Password</label>
                    <input type="password" class="form-control form-control-lg" name="repeat_password" id="repeat_password" placeholder="Repeat password" >
                    <p id="match" class="mt-2"></p>
                </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>
        </div>
    </div>
</div>
<!-- /.Change Password Modal -->

<!-- FEEDBACK MODAL -->
<div class="modal fade" id="feedbackModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body p-4">
        <h5 class="fw-bold main-link">Thank you for visiting the eJournal/eReview system!</h5>
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
          <button type="button" id="submit_feedback" class="btn btn-primary" disabled>Submit</button>
      </div>
    </div>
  </div>
</div>
<!-- /FEEDBACK MODAL -->


<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark pt-5" id="sidenavAccordion">
            <div class="sb-sidenav-menu overflow-hidden">
					<div class="nav pt-3" id="list-tab" role="tablist" >
						<a class="nav-link" id="dashboard" data-bs-toggle="tab" href="#dashboard-tab" role="tab" aria-controls="dashboard"><i class="oi oi-shield me-2" title="oi-shield" aria-hidden="true"></i> Dashboard</a>
						<?php if ($this->session->userdata('_prv_add') == 1) {?>
                            <a class="nav-link" id="create-journal" data-bs-toggle="tab" href="#create-journal-tab" role="tab" aria-controls="home"><i class="oi oi-plus me-2" title="oi-plus" aria-hidden="true"></i> Create Journal</a>
                            <a class="nav-link" id="add-article" data-bs-toggle="tab" href="#add-article-tab" role="tab" aria-controls="home"><i class="oi oi-book me-2" title="oi-book" aria-hidden="true"></i> Add Article</a>
						<?php }?>
						<a class="nav-link" id="article-list" data-bs-toggle="tab" href="#all_articles" role="tab" aria-controls="home">
						<div class="d-flex justify-content-between"><i class="oi oi-eye me-2" title="oi-eye" aria-hidden="true"></i> View Articles 
						<span class="ms-2 badge bg-primary ml-auto"><?php echo $art_count; ?></span></div></a>
						<a class="nav-link" id="journal-list" data-bs-toggle="tab" href="#journals" role="tab" aria-controls="home">
						<div class="d-flex justify-content-between"><i class="oi oi-eye me-2" title="oi-eye" aria-hidden="true"></i> View Journals
						<span class="ms-2 badge bg-primary ml-auto"><?php echo $jor_count; ?></span></div></a>
						<a class="nav-link" id="client-list" data-bs-toggle="tab" href="#clients" role="tab" aria-controls="home">
						<div class="d-flex justify-content-between"><i class="oi oi-eye me-2" title="oi-eye" aria-hidden="true"></i> View Clients
						<span class="ms-2 badge bg-primary ml-auto"><?php echo $client_count; ?></span></div></a>
						<a class="nav-link" id="viewers-list" data-bs-toggle="tab" href="#viewers" role="tab" aria-controls="home">
						<div class="d-flex justify-content-between"><i class="oi oi-eye me-2" title="oi-eye" aria-hidden="true"></i> Abstract Hits
						<span class="ms-2 badge bg-primary ml-auto"><?php echo $hit_count; ?></span></div></a>
						<a class="nav-link" id="citees-list" data-bs-toggle="tab" href="#citees" role="tab" aria-controls="home">
						<div class="d-flex justify-content-between"><i class="oi oi-eye me-2" title="oi-eye" aria-hidden="true"></i> View Citees
						<span class="ms-2 badge bg-primary ml-auto"><?php echo $cite_count; ?></span></div></a>
						<a class="nav-link" data-parent="#manage-editorials" data-bs-toggle="collapse" href="#manage-editorials" role="tab" aria-controls="home"><i class="oi oi-grid-two-up me-2" title="oi-grid-two-up" aria-hidden="true"></i> Manage Editorials </a>
						<div class="collapse" id="manage-editorials" class="list-group">
							<?php if ($this->session->userdata('_prv_add') == 1) {?>
							<a data-bs-toggle="tab" data-parent="#manage-editorials" id="add-editorial"  href="#add-editorial-tab" role="tab" class="nav-link sub-item "><i class="oi oi-people me-2" title="oi-people" aria-hidden="true" ></i> Add Editorial</a>
							<?php }?>
							<a data-bs-toggle="tab" data-parent="#manage-editorials" id="editorial-list"  href="#editorials" role="tab" class="nav-link sub-item"><i class="oi oi-eye me-2" title="oi-eye" aria-hidden="true"></i> Editorial Boards</a>
						</div>
						<a class="nav-link" data-bs-toggle="tab" href="#guidelines" role="tab" aria-controls="home"><i class="oi oi-task me-2" title="oi-task" aria-hidden="true"></i> Manage Guidelines</a>
						<a class="nav-link" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home"><i class="oi oi-home me-2" title="oi-home" aria-hidden="true"></i> Manage Home</a>
						<a class="nav-link" data-bs-toggle="tab" href="#mail" role="tab" aria-controls="mail"><i class="oi oi-envelope-open me-2" title="oi-mail" aria-hidden="true"></i> Manage Email Notifications</a>
						<a class="nav-link" data-bs-toggle="tab" href="#logs" role="tab" aria-controls="logs"><i class="oi oi-envelope-open me-2" title="oi-mail" aria-hidden="true"></i> Activity Logs</a>
					</div>
            </div>
            <div class="sb-sidenav-footer">
                <?php if ($this->session->userdata('_oprs_type_num') == 8 || $this->session->userdata('_oprs_type_num') == 8 || $this->session->userdata('_oprs_type_num') == 3) {?>
                    <a class="btn btn-primary w-100" href="<?php echo base_url('oprs/dashboard'); ?>"><i class="fa fa-sync me-2"></i>eReview Admin</a>
                    <hr class="text-light h-10 fw-bold">
                <?php }?>
                <div class="small">Logged in as:</div>
                <?php echo $this->session->userdata('_oprs_type'); ?>
            </div>
        </nav>
    </div>

