<?php error_reporting(0);?>
<div class="container-fluid" style="margin-top:70px; background-color:#F3F4F6">

	<div class="row">
		<div class="col">
			<img src="<?php echo base_url("assets/images/banner2024.png"); ?>" class="img-fluid w-100"
				alt="Responsive image">
		</div>
	</div>
	<br />
	<div class="row">
		<!-- SIDE NAVIGATION -->
		<div class="col-2 mb-5">
			<div class="list-group sticky" id="list_group_menu">
				<li class="list-group-item font-weight-bold bg-light"><span class="oi oi-book"></span> JOURNALS</li>
				<?php if ($journals != null) {
				$c = 0;foreach ($journals as $row): ?>
				<?php $c++;?>
				<?php echo '<a href="'.base_url('/client/ejournal/get_issues/'.$row->jor_volume.'').'" class="list-group-item  list-group-item-action">Volume ' . $row->jor_volume . ', ' . $row->jor_year . '<span class="oi oi-chevron-right float-right mt-1" style="font-size:10px"></span></a>'; ?>
				<?php endforeach;}?>
			</div>
		</div>
		<div class="col-10">
			<div class="tab-content" id="nav-tabContent">

				<!-- ABOUT -->
				<div class="tab-pane fade show active " id="home" role="tabpanel" aria-labelledby="home">
					<div class="jumbotron shadow p-5 mb-5 bg-white rounded">
						<div class="row">
							<div class="col">
								<div class="btn-group" role="group" aria-label="Basic example">
								<a type="button" class="btn btn-dark mr-1 text-white disabled">ARTICLES:</a>
									<a href="<?php echo base_url('/client/ejournal/get_index');?>" type="button" class="btn btn-outline-primary mr-1">INDEX</a>
									 <?php foreach (range('A', 'Z') as $char) {
										echo '<a href="'.base_url('/client/ejournal/get_index/'.$char.'').'" type="button" class="btn btn-outline-primary mr-1">'. $char .'</a>';
										}
									 ?>
								</div>
							</div>
						</div>
						<div class="row mt-5">
							<div class="col">
								<h2>About</h2>
								<pre><?php echo file_get_contents('./assets/uploads/DO_NOT_DELETE_description.txt'); ?></pre>
							</div>
						</div>

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
			</div>
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
