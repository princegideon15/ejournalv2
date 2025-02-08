
<?php $logged_in = $this->session->userdata('user_id'); ?>

    <?php if($logged_in && $this->session->userdata('csf_arta')){ echo $this->session->userdata('csf_arta'); } ?>
   
    <div class="card bg-light border-0">
        <div class="card-body">
            <h6 class="card-title fw-bold d-block">Journal Information</h6>
            <p class="mb-0"><a href="<?php echo base_url('/client/ejournal/editorial');?>"
            class="text-dark text-decoration-underline">Editorial Board</a></p>
            <!-- <p class="mb-0"><a href="#" class="text-dark text-decoration-underline" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Ongoing Development">International Editorial Board</a></p> -->
            <p class="mb-0"><a href="<?php echo base_url('/client/ejournal/policy');?>" class="text-dark text-decoration-underline">Editorial Policy</a></p>
        </div>
    </div>

    <div class="card bg-light border-0 mt-3 mb-3">
        <div class="card-body">
            <h6 class="card-title fw-bold">Annual Journal Metrics</h6>
            <!-- <h6 class="card-title fw-bold mt-3 mb-0">Citation Impact <?php echo date('Y');?></h6>
            -
            <h6 class="card-title fw-bold mt-2 mb-0">Speed <?php echo date('Y');?></h6>
            - -->
            <h6 class="card-title fw-bold mt-2 mb-0">Usage <?php echo date('Y');?></h6>
            <div>Downloads: <?=number_format($downloads, 0, '', ',')?></div>
            <div>Altmetric mentions: <?=number_format($citations, 0, '', ',')?></div>

        </div>
    </div>

    <?php if(!$logged_in){
            echo '<div class="sticky-top pt-3"><a type="button" class="btn main-btn w-100 login-btn" href="'.base_url('client/login').'" >
            Login to Get Access <span class="oi oi-account-login ms-1" style="font-size:.9rem"></span></a></div>';
    }?>

    
    <img class="mt-4 border rounded" src="<?php echo base_url("assets/oprs/img/logos/agpci.png"); ?>" alt="agpci" width="100%" height="50px">
    <img class="mt-3 border rounded" src="<?php echo base_url("assets/oprs/img/logos/crossref.jfif"); ?>" alt="crossref" width="100%" height="50px">
    <img class="mt-3 border rounded" src="<?php echo base_url("assets/oprs/img/logos/ebscohost.gif"); ?>" alt="ebscohost" width="100%" height="50px">
    <img class="mt-3 border rounded" src="<?php echo base_url("assets/oprs/img/logos/pej.png"); ?>" alt="pej" width="100%" height="50px">
    <img class="mt-3 border rounded" src="<?php echo base_url("assets/oprs/img/logos/tuv_nord_iso.png"); ?>" alt="tub_nord_iso" width="100%" height="200px"> 