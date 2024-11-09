
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
          <a class="nav-link text-dark" id="nav_home" href="<?php echo base_url();?>">Home</a>
        </li>
        <!-- <li class="nav-item">
          <a class="nav-link text-dark" href="<?php echo base_url('/client/ejournal/about');?>">About<span class="sr-only">(current)</span></a>
        </li> -->
        <li class="nav-item">
          <a class="nav-link text-dark" href="<?php echo base_url('/client/ejournal/guidelines');?>">Guidelines</a>
        </li>
        <!-- <li class="nav-item">
          <a class="nav-link text-dark" href="<?php echo base_url('/client/ejournal/articles');?>">Articles</a>
        </li> -->
        <li class="nav-item">
          <a class="nav-link text-dark" href="<?php echo base_url('/client/ejournal/submission');?>">Submit manuscript</a>
          <!-- <a class="nav-link text-dark" href="https://skms.nrcp.dost.gov.ph/main/login" target="_blank">Submit manuscript</a> -->
        </li>
        <?php if($logged_in) {
          echo '<li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    '.$this->session->userdata('email').'
                  </a>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="'.base_url('/client/login/profile').'">My Profile</a></li>
                    <li><a class="dropdown-item" href="'.base_url('/client/login/logout').'">Logout</a></li>
                  </ul>
                </li>';
        }else{
          echo '<li class="nav-item">
                  <a class="nav-link text-dark" href="'.base_url('/client/ejournal/login').'">Login</a>
                </li>';
        } ?>
        
      </ul>
       
      <span class="navbar-text">
        <span class="badge badge-issn rounded-0 fs-6">ISSN: 2980 - 4728</span>
      </span>
    </div>
  </div>

  
  


  <!-- <a class="navbar-brand ml-2 font-weight-bold" href="https://researchjournal.nrcp.dost.gov.ph/">eJournal</a> -->

    <!-- <form method="post" class="dropdown form-inline my-2 my-lg-0 ">
       <select class="form-control bg-dark text-white font-weight-bold" id="search_filter">
      <option value="1">Title</option>
      <option value="2">Author</option>
      <option value="3" selected> Keywords</option>
    </select>
    <div class="right-inner-addon">
        <i class="oi oi-magnifying-glass  "></i>
        <input type="text"
               class="form-control border-primary text-primary" 
               placeholder="Search" id="search_ejournal" />
    </div>
    </form> -->
  </div>
</nav>

