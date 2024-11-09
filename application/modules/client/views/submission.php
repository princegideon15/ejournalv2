<?php error_reporting(0);?>
<?php $logged_in = $this->session->userdata('user_id'); ?>

<div class="container-fluid">
    <div class="row pt-3">
        <div class="col col-3 p-3">
            <a class="btn btn-link main-link" href="<?=base_url('/client/ejournal/articles')?>">View all articles</a>
		</div>
		<div class="col col-7 p-3">
					<div class="border p-5 mb-5 bg-white rounded">
						<!-- <div class="row mt-5">
							<div class="col"> -->
                                
                        <h3>Submit Manuscript</h3>
                        <div class="mt-3">
                            <h6 class="fw-bold">NRCP Non-Member</h6>
                            <p>To submit manuscript, and to check the status of your submission,
                                you need to have an account with the eJournal.
                            </p>

                            <?php if(!$logged_in) { ?>
                                <p>Don't have an account? <a class="fw-bold main-link text-decoration-underline" href="<?php echo base_url('/client/ejournal/login/create_account');?>" target="_blank">Sign up here.</a></p>
                            <?php } ?>

                            <!-- <a role="button" href="http://researchjournal.nrcp.dost.gov.ph/oprs/login" target="_blank" class="btn main-btn">Start Submission</a> -->
                            <a role="button" href="<?php echo base_url('/oprs/login');?>" target="_blank" class="btn main-btn">Start Submission</a>
                        </div>
                        <hr class="my-3">
                        <div>
                            <h6 class="fw-bold">NRCP Member</h6>
                            <p>To submit manuscript, and to check the status of your submission,
                                you need to have an account with the eJournal.
                            </p>

                            <p>Don't have an account? <a class="fw-bold main-link text-decoration-underline" href="https://skms.nrcp.dost.gov.ph/main/register" target="_blank">Sign up here.</a></p>

                            <a role="button" href="https://skms.nrcp.dost.gov.ph/main/login" target="_blank" class="btn btn-dark">Start Submission</a>
                        </div>

            
							<!-- </div>
						</div> -->

						<div class="mt-5">
							<a rel="license" href="http://creativecommons.org/licenses/by/4.0/" target="_blank"><img
									alt="Creative Commons License" style="border-width:0"
									src="https://i.creativecommons.org/l/by/4.0/88x31.png" /></a><br />This work is
							licensed
							under a <a rel="license" href="http://creativecommons.org/licenses/by/4.0/"
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
