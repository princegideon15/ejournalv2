

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

    .no-focus-border:focus {
        outline: none; /* Removes outline */
        box-shadow: none; /* Removes shadow */
        border-color: transparent; /* Optional: Makes the border color invisible */
    }
    #search_result_list .list-group-item:hover {
    background-color:#007bff;
    color: white;
    }


    #search_result_list .list-group-item {
    position: relative;
    }

    #search_result_list .list-group-item:hover::after {
    content: "⮨"; /* Unicode for the arrow icon */
    position: absolute;
    right: 15px;
    top:10px;
    font-size:20px;
    color: white; /* Adjust arrow color */
    }

    #search_result_list:hover{
    overflow: auto !important;
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
            <a class="nav-link active fw-bolder text-decoration-uppercase" aria-current="page" href="#!">Online Peer Review System (eReview)</a>
        </li>
    </ul>
    <button class="btn btn-dark text-start border border-1" style="width:20%" onclick="toggleSearch()"><span class="fas fa-search"></span> Search</button>
    <!-- Navbar Search-->
    <!-- <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
        <div class="input-group">
            <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
            <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
        </div>
    </form> -->
    <!-- Navbar-->
    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
        <li class="nav-item dropdown no-arrow mx-1">
            <?php if (_UserRoleFromSession() == 3 || _UserRoleFromSession() == 20) { ?>
            <a class="nav-link dropdown-toggle" href="javascript:void(0)" id="alertsDropdown" role="button" data-bs-toggle="dropdown">
                <i class="fas fa-bell fa-fw oprs_notif">
                <!-- <?php  if(count($logs) > 0){ ?>
                    <span class="badge badge-danger font-weight-bold notif_count" style="font-size:11px;position:fixed; margin-left:-5px;margin-top:2px">          
                    <?php echo count($logs); ?>
                    </span>
                <?php }?> -->
                </i>
            </a>
            <?php } ?>
            <div class="dropdown-menu dropdown-menu-end p-0 oprs_notif_list shadow-lg bg-white rounded" style="width:400px; max-width:400px; max-height:600px; overflow:auto">
            
            </div>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <?php echo $this->session->userdata('_oprs_username'); ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li><h6 class="dropdown-header">Last visit: <?php echo $this->session->userdata('_oprs_last_visit'); ?></h6></li>
                <li><h6 class="dropdown-header">Account: <?php echo $this->session->userdata('_oprs_type'); ?></h6></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#accountSettingModal">
                    <span class="fas fa-user-circle me-2"></span>Account Setting</a></li>
                <li><a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#changePassModal">
                    <span class="fa fa-lock me-2"></span>Change Password</a></li>
                <li><a class="dropdown-item" href="javascript:void(0);" id="logout_oprs" onclick="logout()">
                    <span class="fa fa-sign-out me-2"></span>Logout</a></li>
            </ul>
        </li>
    </ul>
</nav>

<!-- Modal -->
<div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content bg-light">
      <!-- <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div> -->
      <div class="modal-body">
        <div class="mb-3">
          <form class="row" id="search_form">
            <div class="input-group input-group-lg mb-2">
                <span class="input-group-text bg-white border border-dark border-2 border-end-0" id="basic-addon1"><i class="fas fa-search"></i></span>
                <input class="form-control bg-white border border-dark border-2 border-start-0 no-focus-border ps-0" id="search" type="text" placeholder="Search here..." aria-label="Search here..." aria-describedby="search"/>
            </div>
            <div class="">
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="search_filter" id="filter_keyword" value="1" checked>
                <label class="form-check-label mt-2" for="filter_keyword">Keyword</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="search_filter" id="filter_author" value="2">
                <label class="form-check-label mt-2" for="filter_author">Author</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="search_filter" id="filter_title" value="3">
                <label class="form-check-label mt-2" for="filter_title">Title</label>
              </div>
            </div>
          </form>   
        </div>
        <div id="search_result"></div>
        <div class="alert alert-secondary d-flex gap-1 align-items-center d-none" role="alert">
        </div>
      </div>
      <div class="modal-footer py-1 d-flex gap-2 justify-content-start align-items-center" style="font-size:12px">
        <!-- <button class="btn btn-outline-secondary">Close</button> -->
        <div><span class="badge fw-light bg-secondary">Enter</span> to search</div>
        <div><span class="badge fw-light bg-secondary">Esc</span> to close</div>
        
      </div>
    </div>
  </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePassModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="user_modal">Change Password</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="form_change_pass">
                <div class="mb-3">
                    <label class="form-label fw-bold" for="old_password">Current Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control form-control-lg" id="old_password" name="old_password" placeholder="Enter old password" >
                        <span class="input-group-text bg-white text-muted rounded-end" id="inputGroupPrepend3"><a class="text-muted cursor-pointer" href="javascript:void(0);" onclick="togglePassword('#old_password', '#old_password_icon')"><i class="fa fa-eye-slash" id="old_password_icon"></i></a></span>             
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold" for="usr_password">New Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control form-control-lg" id="usr_password" name="usr_password" placeholder="Enter new password" >
                        <span class="input-group-text bg-white text-muted rounded-end" id="inputGroupPrepend3"><a class="text-muted cursor-pointer" href="javascript:void(0);" onclick="togglePassword('#usr_password', '#new_passsword_icon', '#repeat_password')"><i class="fa fa-eye-slash" id="new_passsword_icon"></i></a></span>             
                    </div>
                </div>
                <div class="card mb-3 d-none" id="change_password_strength_container">
                    <div class="card-body text-secondary">
                        <div><span class="me-1 fs-6">Password strength:</span><span class="fw-bold" id="change-password-strength"></span></div>
                        <div class="progress mt-1" style="height: .5rem;">
                            <div class="progress-bar" role="progressbar"  id="change-password-strength-bar" aria-label="Success example" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <ul class="mt-3 small text-muted ps-3">
                            <li>8-20 characters long.</li>
                            <li>At least 1 letter.</li>
                            <li>At lestt 1 number.</li>
                            <li>At least 1 special character.</li>
                        </ul>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold" for="repeat_password">Confirm Password</label>
                    <input type="password" class="form-control form-control-lg" name="repeat_password" id="repeat_password" placeholder="Repeat password" >
                </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>
        </div>
    </div>
</div>
<!-- /.Change Password Modal -->

<!-- Account Setting Modal -->
<div class="modal fade" id="accountSettingModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="user_modal">Account Setting</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="form_update_account">
                <input type="hidden" name="usr_id" id="usr_id">
                <div class="mb-3">
                    <label class="form-label" for="usr_full_name">Account Name</label>
                    <input type="text" class="form-control form-control-lg" id="usr_full_name" name="usr_full_name" placeholder="First Name, Last Name" >
                </div>
                <div class="mb-3">
                    <label class="form-label" for="usr_username">Email</label>
                    <input type="email" class="form-control form-control-lg" id="usr_username" name="usr_username" placeholder="Enter a valid email address" >
                </div>
                <div class="mb-3">
                    <label class="form-label" for="usr_sex">Sex</label>
                    <select id="usr_sex" name="usr_sex" class="form-select form-control-lg">
                      <option value="" selected>Select Sex</option>
                      <option value='1'>Male</option>
                      <option value='2'>Female</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="usr_contact">Contact</label>
                    <input type="text" class="form-control form-control-lg" name="usr_contact" id="usr_contact" placeholder="Enter 11-digit number" >
                </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>
        </div>
    </div>
</div>
<!-- /.Account Setting Modal -->

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
                <div class="nav pt-3 oprs-nav">
                    <div class="sb-sidenav-menu-heading">Main</div>
                    <?php if ($role == 19 || $role == 20 || $role == 3 || $role == 5 || $role == 6) {?>
                        
                        <a class="nav-link" href="dashboard">
                        <i class="fas fa-fw fa-tachometer-alt me-2"></i>
                        <span>Dashboard</span>
                        </a>
                    
                    <?php }?>

                    
                    <?php if ($role == 1) {?>
                        <a class="nav-link" href="manuscripts">
                        <i class="fas fa-scroll me-2"></i>
                        My Submissions
                        <span class="ms-2 badge text bg-danger"><?php echo $man_all_count; ?></span>
                        </a> 
                    <?php }else{ ?>
                        <a class="nav-link" href="manuscripts">
                        <i class="fas fa-scroll me-2"></i>
                        Manuscripts
                        <span class="ms-2 badge text bg-danger"><?php echo $man_all_count; ?></span>
                        </a> 
                    <?php } ?>


                    
                    <?php if ($role == 19 || $role == 20) {?>
                    
                    <div class="sb-sidenav-menu-heading">User Management</div>
                    
                        <a class="nav-link" href="user">
                        <i class="fas fa-fw fa-user me-2"></i>
                        Users<span class="ms-2 badge text bg-danger"><?php echo $usr_count; ?></span>
                        </a>

                    
                    
                    <?php }?>
                    <?php if ($role == 20 || $role == 3 || $role == 19 || $role == 5 || $role == 6) {?>
                        
                        <div class="sb-sidenav-menu-heading">Reports and Statisttics</div>

                        <a class="nav-link" href="reports">
                        <i class="fas fa-fw fa-chart-bar me-2"></i>
                        <span>Reports</span>
                        </a>
                        <a class="nav-link" href="statistics">
                        <i class="fas fa-fw fa-chart-bar me-2"></i>
                        <span>Statistics</span>
                        </a>
                    
                    <?php }?>

                    <?php if ($role == 20 || $role == 5) {?>
                    
                    
                        <div class="sb-sidenav-menu-heading">Library</div>

                        <a class="nav-link" href="roles">
                        <i class="fas fa-fw fa-user-cog me-2"></i>
                        User Types</span>
                        </a>

                        <a class="nav-link" href="status">
                        <i class="fas fa-bullhorn me-2"></i>
                        Status Types
                        </a>

                        <a class="nav-link" href="<?php echo base_url('oprs/criterion?type=1'); ?>">
                        <i class="fas fa-pencil-square me-2"></i>
                        Technical Review Criterion
                        </a>

                        <a class="nav-link" href="<?php echo base_url('oprs/criterion?type=2'); ?>">
                        <i class="fas fa-pencil-square me-2"></i>
                        Peer Review Criterion
                        </a>

                        <a class="nav-link" href="publication_types">
                        <i class="fas fa-book me-2"></i>
                        Publication Types
                        </a>

                        <a class="nav-link" href="emails">
                        <i class="fas fa-envelope-open me-2"></i>
                        Email Notificatiions
                        </a>

                        <a class="nav-link" href="process">
                        <i class="fas fa-clock me-2"></i>
                        Process Time Duration
                        </a>

                        <!-- <a class="nav-link" href="emails">
                        <i class="fas fa-envelope-open me-2"></i>
                        Publication Committee
                        </a> -->

                    <?php } ?>

                    <?php if ($role == 20) {?>
                        
                        <div class="sb-sidenav-menu-heading">Settings</div>

                        <a class="nav-link" href="controls">
                        <i class="fas fa-fw fa-cogs me-2"></i>
                        <span>Menu Access Control Panel</span>
                        </a>

                        <a class="nav-link" href="backup">
                        <i class="fas fa-database me-2"></i>
                        <span>Database</span>
                        </a>

                    <?php } ?>
                        
                        
                    <?php if ($role == 20 || $role == 19 || $role == 5) {?>

                        <div class="sb-sidenav-menu-heading">Feedback</div>
                    
                        <a class="nav-link" href="feedbacks">
                        <i class="fas fa-star me-2"></i>
                        CSF UI/UX<span class="ms-2 badge text bg-danger"><?php echo $feed_count; ?></span>
                        </a>

                        <a class="nav-link" href="arta">
                        <i class="fas fa-edit me-2"></i>
                        CSF ARTA<span class="ms-2 badge text bg-danger"><?php echo $arta_count; ?></span>
                        </a>

                        
                        <div class="sb-sidenav-menu-heading">Logs</div>
                    
                        <a class="nav-link" href="logs">
                        <i class="fas fa-fw fa-clipboard-list me-2"></i>
                        <span>Activity Logs</span>
                        </a>
                    
                    <?php }?>
                </div>
            </div>
            <div class="sb-sidenav-footer">
                <?php if ($role == 20 || $role == 3) {?>
                    <a class="btn btn-info w-100" href="<?php echo base_url('../../admin/dashboard'); ?>"><i class="fa fa-sync me-2"></i>eJournal Admin</a>
                    <hr class="text-light h-10 fw-bold">
                <?php }?>
                <div class="small">Logged in as:</div>
                <?php echo $this->session->userdata('_oprs_type'); ?>
            </div>
        </nav>
    </div>

