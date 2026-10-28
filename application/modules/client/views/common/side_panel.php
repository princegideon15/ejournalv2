
<?php $logged_in = $this->session->userdata('user_id'); ?>


    <?php if($logged_in) { ?>    
        <a role="button" href="<?php echo base_url('/oprs/manuscripts');?>" class="btn main-btn text-decoration-none w-100 mb-3 fw-bold">Submit Manuscript</a>
    <?php } ?>

    <?php if($logged_in && $this->session->userdata('csf_arta')){ echo $this->session->userdata('csf_arta'); } ?>
   
    <div class="card bg-light border-0">
        <div class="card-body">
            <h6 class="card-title fw-bold d-block">Journal Information</h6>
				<!-- <p class="mb-0">
				<a href="<?= base_url('../../../../../assets/admin/International Advisory.pdf') ?>" class="text-dark text-decoration-underline" target="_blank" rel="noopener noreferrer">
					Intl. Advisory Board
				</a>
				</p> -->
				<a href="<?= base_url('../../../../../assets/admin/International Advisory.pdf') ?>" class="text-dark text-decoration-underline" target="_blank" rel="noopener noreferrer">
					Intl. Advisory Board
				</a>
            <p class="mb-0"><a href="<?php echo base_url('/client/ejournal/editorial');?>"
            class="text-dark text-decoration-underline">Editorial Board</a></p>
          
            <p class="mb-0"><a href="<?php echo base_url('/client/ejournal/policy');?>" class="text-dark text-decoration-underline">Editorial Policy</a></p>
        </div>
    </div>

    <div class="card bg-light border-0 mt-3 mb-3">
        <div class="card-body">
            <h6 class="card-title fw-bold mt-2 mb-0">Usage Metrics <?php echo date('Y');?></h6>
            <div>Downloads: <?php echo $downloads; ?></div>
            <div>Abstract Hits: <?php echo $hits_count; ?></div>
			<div>Visitors: <?php echo $vis_count; ?></div>
        </div>
    </div>

	<div class="card bg-light border-0 mt-3 mb-3">
        <div class="card-body">
            <h6 class="card-title fw-bold mt-2 mb-0">Submission To Publication</h6>
            <h6 class="card-title fw-bold mt-2 mb-0">96 Days</h6>

        </div>
    </div>

	<div class="card bg-light border-0 mt-3 mb-3">
        <div class="card-body">
            <h6 class="card-title mt-2 mb-0"><b>Advancing Multidisciplinary Research </b> </h6>
			<p> We accept original research, review, and perspectives across all disciplines - 
				with rigorous editorial stanards and no fixed issue deadlines; rolling submissions </p>
            <!-- <h6 class="card-title fw-bold mt-2 mb-0">96 Days</h6> -->

        </div>
    </div>

    <!-- <?php if(!$logged_in){
            echo '<div class="sticky-top pt-3"><a type="button" class="btn main-btn w-100 login-btn" href="'.base_url('client/login').'" >
            Login to Get Access <span class="oi oi-account-login ms-1" style="font-size:.9rem"></span></a></div>';
    }?> -->

    <img class="mt-3 " src="https://assets.crossref.org/logo/crossref-logo-200.svg" alt="Crossref logo stacked" width="100%" height="70px"/>
	<!-- <img class="mt-3 " src="<?php echo base_url("assets/images/new-images/crossref.png"); ?>" alt="crossref" width="100%" height="70px">	 -->
	<img class="mt-3 " src="<?php echo base_url("assets/images/logos/ebscohost.gif"); ?>" alt="ebscohost" width="100%" height="70px">
	<img class="mt-3 " src="<?php echo base_url("assets/images/new-images/agpci.png"); ?>" alt="agpci" width="100%" height="60px">
	<img class="mt-3 " src="<?php echo base_url("assets/images/new-images/pejlogo.png"); ?>" alt="agpci" width="100%" height="200px">
	<img class="mt-3 " src="<?php echo base_url("assets/images/Ithenticate.jpg"); ?>" alt="ithenticate" width="100%" height="80px">
	<img class="mt-3 " src="<?php echo base_url("assets/images/new-images/issn.png"); ?>" alt="ithenticate" width="100%" height="90px">
	<img class="mt-3 " src="<?php echo base_url("assets/images/new-images/iso.png"); ?>" alt="iso" width="100%" height="200px"> 
	<img class="mt-3 " src="<?php echo base_url("assets/images/dpo_seal.png");?>" width="100%" height="200px">

<div class="card bg-light border-0 mt-3" width="100%">
       <a href="https://info.flagcounter.com/l6yQ"><img src="https://s01.flagcounter.com/count2/l6yQ/bg_FFFFFF/txt_000000/border_CCCCCC/columns_2/maxflags_10/viewers_0/labels_0/pageviews_0/flags_0/percent_0/"
	    alt="Flag Counter" border="0" width="100%"></a>
    </div>

