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
                <li><a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#database_modal"><span class="oi oi-hard-drive"></span> Backup/Restore Database</a></li>
                <li><a class="dropdown-item" href="javascript:void(0);" onclick="logout()"> <span class="oi oi-account-logout "></span> Logout</a></li>
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

<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark pt-5" id="sidenavAccordion">
            <div class="sb-sidenav-menu overflow-hidden">
                <div class="nav flex-column nav-pills pt-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    
                    <div class="sb-sidenav-menu-heading">Main</div>
                    <button class="nav-link active" id="v-pills-dashboard-tab" data-bs-toggle="pill" data-bs-target="#v-pills-dashboard" type="button" role="tab" aria-controls="v-pills-dashboard" aria-selected="true"><i class="oi oi-shield me-2" title="oi-shield" aria-hidden="true"></i>Dashboard</button>

                    
                    <div class="sb-sidenav-menu-heading">Journal Management</div>
                    <?php if ($this->session->userdata('_prv_add') == 1) {?>
                        <button class="nav-link" id="v-pills-create-journal-tab" data-bs-toggle="pill" data-bs-target="#v-pills-create-journal" type="button" role="tab" aria-controls="v-pills-create-journal" aria-selected="false"><i class="oi oi-plus me-2" title="oi-plus" aria-hidden="true"></i>Create Journal</button>
                    <?php }?>
                    <button class="nav-link" id="v-pills-journal-list-tab" data-bs-toggle="pill" data-bs-target="#v-pills-journal-list" type="button" role="tab" aria-controls="v-pills-journal-list" aria-selected="false"><i class="oi oi-eye me-2" title="oi-eye" aria-hidden="true"></i>View Journals<span class="ms-2 badge bg-primary ms-auto"><?php echo $jor_count; ?></span></button>

                    
                    <div class="sb-sidenav-menu-heading">Article Management</div>
                    <?php if ($this->session->userdata('_prv_add') == 1) {?>
                        <button class="nav-link" id="v-pills-add-article-tab" data-bs-toggle="pill" data-bs-target="#v-pills-add-article" type="button" role="tab" aria-controls="v-pills-add-article" aria-selected="false"><i class="oi oi-book me-2" title="oi-book" aria-hidden="true"></i>Add Article</button>
                    <?php } ?>
                    <button class="nav-link" id="v-pills-article-list-tab" data-bs-toggle="pill" data-bs-target="#v-pills-article-list" type="button" role="tab" aria-controls="v-pills-article-list" aria-selected="false"><i class="oi oi-eye me-2" title="oi-eye" aria-hidden="true"></i>View Articles<span class="ms-2 badge bg-primary ms-auto"><?php echo $art_count; ?></span></button>
                    <button class="nav-link" id="v-pills-client-list-tab" data-bs-toggle="pill" data-bs-target="#v-pills-client-list" type="button" role="tab" aria-controls="v-pills-client-list" aria-selected="false"><i class="oi oi-eye me-2" title="oi-eye" aria-hidden="true"></i>View Clients<span class="ms-2 badge bg-primary ms-auto"><?php echo $client_count; ?></span></button>
                    <button class="nav-link" id="v-pills-citees-list-tab" data-bs-toggle="pill" data-bs-target="#v-pills-citees-list" type="button" role="tab" aria-controls="v-pills-citees-list" aria-selected="false"><i class="oi oi-eye me-2" title="oi-eye" aria-hidden="true"></i>View Citees<span class="ms-2 badge bg-primary ms-auto"><?php echo $cite_count; ?></span></button>
                    <button class="nav-link" id="v-pills-viewers-list-tab" data-bs-toggle="pill" data-bs-target="#v-pills-viewers-list" type="button" role="tab" aria-controls="v-pills-viewers-list" aria-selected="false"><i class="oi oi-eye me-2" title="oi-eye" aria-hidden="true"></i>Abstract Hits<span class="ms-2 badge bg-primary ms-auto"><?php echo $hit_count; ?></span></button>

                    
                    <div class="sb-sidenav-menu-heading">Editorial Management</div>
                    <?php if ($this->session->userdata('_prv_add') == 1) {?>
                        <button class="nav-link" id="v-pills-add-editorial-tab" data-bs-toggle="pill" data-bs-target="#v-pills-add-editorial" type="button" role="tab" aria-controls="v-pills-add-editorial" aria-selected="false"><i class="oi oi-people me-2" title="oi-people" aria-hidden="true" ></i>Add Editorial</button>
                        <button class="nav-link" id="v-pills-editorial-list-tab" data-bs-toggle="pill" data-bs-target="#v-pills-editorial-list" type="button" role="tab" aria-controls="v-pills-editorial-list" aria-selected="false"><i class="oi oi-eye me-2" title="oi-eye" aria-hidden="true"></i>Editorial Board</button>
                    <?php }?>
                    
                    <div class="sb-sidenav-menu-heading">Library</div>
                    
                    <button class="nav-link" id="v-pills-mail-tab" data-bs-toggle="pill" data-bs-target="#v-pills-mail" type="button" role="tab" aria-controls="v-pills-mail" aria-selected="false"><i class="oi oi-envelope-open me-2" title="oi-mail" aria-hidden="true"></i>Email Notifications</button>      
                    <button class="nav-link" id="v-pills-policy-tab" data-bs-toggle="pill" data-bs-target="#v-pills-policy" type="button" role="tab" aria-controls="v-pills-policy" aria-selected="false"><i class="oi oi-task me-2" title="oi-task" aria-hidden="true"></i>Editorial Policy</button>      
                    <button class="nav-link" id="v-pills-home-tab" data-bs-toggle="pill" data-bs-target="#v-pills-home" type="button" role="tab" aria-controls="v-pills-home" aria-selected="false"><i class="oi oi-home me-2" title="oi-home" aria-hidden="true"></i>About</button>
                    <button class="nav-link" id="v-pills-guidelines-tab" data-bs-toggle="pill" data-bs-target="#v-pills-guidelines" type="button" role="tab" aria-controls="v-pills-guidelines" aria-selected="false"><i class="oi oi-task me-2" title="oi-task" aria-hidden="true"></i>Guidelines</button>

                    
                    <div class="sb-sidenav-menu-heading">Logs</div>

                    <button class="nav-link" id="v-pills-logs-tab" data-bs-toggle="pill" data-bs-target="#v-pills-logs" type="button" role="tab" aria-controls="v-pills-logs" aria-selected="false"><i class="oi oi-envelope-open me-2" title="oi-mail" aria-hidden="true"></i>Activity Logs</button>
                </div>
            </div>
            <div class="sb-sidenav-footer">
                <?php if ($this->session->userdata('_oprs_type_num') == 8 || $this->session->userdata('_oprs_type_num') == 8 || $this->session->userdata('_oprs_type_num') == 3) {?>
                    <a class="btn btn-secondary w-100" href="<?php echo base_url('oprs/dashboard'); ?>"><i class="fa fa-sync me-2"></i>eReview Admin</a>
                    <hr class="text-light h-10 fw-bold">
                <?php }?>
                <div class="small">Logged in as:</div>
                <?php echo $this->session->userdata('_oprs_type'); ?>
            </div>
        </nav>
    </div>

