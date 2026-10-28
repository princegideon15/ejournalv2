<?php error_reporting(0);?>
<?php $logged_in = $this->session->userdata('user_id'); ?>

<div class="container-fluid mt-2 p-4">
    <div class="row">
        <!-- <div class="col col-3 p-3">
            <a class="btn btn-link main-link" href="<?=base_url('/client/ejournal/articles')?>">View all articles</a>
		</div> -->
		<div class="col col-10 p-3">
					<div class="border p-5 mb-5 bg-white rounded">
						<h1>Guidelines</h1>
						
						<?php if(!$logged_in) { ?>    
                        <h3 class="text-uppercase">Submit Manuscript</h3>
                        <div class="mt-3">
                            <!-- <h6 class="fw-bold">NRCP Non-Member</h6> -->
                            <p>
								Please note: Your SKMS account cannot be used for manuscript submission.
								To submit a manuscript and to check the status of your submission, kindly register a separate account on the eJournal’s Online Peer Review System (eReview). 

							</p>							
							<p>To Submit a manuscript and check the status of submission, please create an account <a class="fw-bold main-link text-decoration-underline" href="<?php echo base_url('/client/login#create-account');?>">Create an Account</a></p>
						
						 </div>
						 <?php } ?>
                        <hr>
                        <!-- <div>
                            <h6 class="fw-bold">NRCP Member</h6>
                            <p>To submit manuscript, and to check the status of your submission,
                                you need to have an account with the eJournal.
                            </p>

                            <p>Don't have an account? <a class="fw-bold main-link text-decoration-underline" href="https://skms.nrcp.dost.gov.ph/main/register" target="_blank">Sign up here.</a></p>

                            <a role="button" href="https://skms.nrcp.dost.gov.ph/main/login" target="_blank" class="btn btn-dark">Start Submission</a>
                        </div> -->

            
							<!-- </div>
						</div> -->

						<div class="row">
							<div class="col">
								<?php echo $guidelines;?>
							</div>
						</div>
						
						<div class="mt-5">
						<h6 class="fw-bold">Open Access</h6>   
							<a rel="license" href="https://creativecommons.org/licenses/by-nc/4.0/" target="_blank">
								 <img alt="Creative Commons License" style="width: 10%;" src="<?php echo base_url('assets/images/Creative.png'); ?>" />
							</a><br />This work is
							licensed
							under a <a rel="license" href="https://creativecommons.org/licenses/by-nc/4.0/"
								target="_blank">Creative Commons Attribution 4.0 International License</a>.
						</div>
					</div>
		</div>
		<div class="col col-2 p-3">
			<?php $this->load->view('common/side_panel');?>
		</div>
	</div>
    </div>
</div>


<!-- Submit Feedback Floater -->
<!-- <div class="fixed-bottom text-right pr-5">
	<button type="button" class="btn btn-sm btn-warning font-weight-bold px-3 py-2" data-target="#feedbackModal"
		data-toggle="modal">
		<span class="oi oi-comment-square mr-1"></span>
		Submit Feedback
	</button>
</div> -->
