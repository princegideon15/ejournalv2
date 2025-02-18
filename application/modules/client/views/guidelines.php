<?php error_reporting(0);?>
<div class="container-fluid mt-2 p-4">
    <div class="row">
        <div class="col col-3 p-3">
            <a class="btn btn-link main-link" href="<?=base_url('/client/ejournal/articles')?>">View all articles</a>
		</div>
		<div class="col col-7 p-3">
					<div class="border p-5 mb-5 bg-white rounded">
						<div class="row">
							<div class="col">
								<?php echo $guidelines;?>
							</div>
						</div>

						<div class="mt-5">
						<h6 class="fw-bold">Open Access</h6>   
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
