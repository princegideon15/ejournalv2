<?php error_reporting(0);?>
<div class="container-fluid mt-2 p-4">
    <div class="row">
        <!-- <div class="col col-2 p-3">
            <a class="btn btn-link main-link" href="<?=base_url('/client/ejournal/articles')?>">View all articles</a>
		</div> -->
		<div class="col col-10 p-3">
					<div class="border p-5 mb-5 bg-white rounded">
						<div class="row">
							<div class="col">
								<h3>Downloaded Articles</h3>
								<div class="no-margin p-1 table-responsive">
									<!-- <table class="table table-bordered table-striped table-hover" id="my-downloads-table">
										<thead>
											<tr>
												<th class="align-middle">#</th>
												<th class="align-middle">Title</th>
												<th class="align-middle">Primary Author</th>
												<th class="align-middle">Co-authors</th>
												<th class="align-middle">Year Published</th>
												<th class="align-middle">Volume No.</th>
												<th class="align-middle">Issue No.</th>
												<th class="align-middle">Download Date</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach($results as $index => $row): ?>
											<?php   
												$issue = (
														($row->jor_issue == 5) ? 'Special Issue No. 1' :
														(($row->jor_issue == 6) ? 'Special Issue No. 2' :
														(($row->jor_issue == 7) ? 'Special Issue No. 3' :
														(($row->jor_issue == 8) ? 'Special Issue No. 4' : 'Issue ' . $row->jor_issue)))
													);
											
												$coa =  $this->Client_journal_model->get_author_coauthors($row->art_id);
												$coa_arr = (explode(",& ",$coa));

											?>
										
											<tr>
												<td><?= $index + 1 ?></td>
												<td><a href="<?= base_url() . 'client/ejournal/article/' . $row->art_id ?>" class="text-dark" target="_blank"><?= $row->art_title ?></a></td>
												<td><a href="<?= base_url() . 'client/ejournal/articles?search=' . str_replace(' ', '+', $row->art_author) ?>" class="text-dark" target="_blank"><?= $row->art_author ?></a></td>
												<td>
													<?php $i = 0; foreach($coa_arr as $cr):?>
														<?php $cc = $cr; ?>
														<a href="<?= base_url() . 'client/ejournal/articles?search=' . str_replace(' ', '+', $cr) ?>" class="text-dark" target="_blank"><?=$cc;?></a> 
														<?php if($i < (count($coa_arr) - 1)) echo ','; ?>
														<?php $i++; ?>
													<?php endforeach;?>
												</td>
												<td><?= $row->art_year ?></td>
												<td>Volume <?= $row->jor_volume ?></td>
												<td><a class="text-dark" href="<?=base_url('/client/ejournal/volume/'.$row->jor_volume.'/'. $row->jor_issue)?>" class="main-link" target="_blank"><?= $issue ?></a></td>
												<td><?= $row->dl_datetime ?></td>
											</tr>

											<?php endforeach; ?>
										</tbody>
									</table> -->
									<table class="table table-bordered table-striped table-hover" id="my-downloads-table">
										<thead>
											<tr>
												<th class="align-middle">#</th>
												<th class="align-middle">Title</th>
												
												<th class="align-middle">Date Accessed</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach($results as $index => $row): ?>
											<?php   
												$issue = (
														($row->jor_issue == 5) ? 'Special Issue No. 1' :
														(($row->jor_issue == 6) ? 'Special Issue No. 2' :
														(($row->jor_issue == 7) ? 'Special Issue No. 3' :
														(($row->jor_issue == 8) ? 'Special Issue No. 4' : 'Issue ' . $row->jor_issue)))
													);
											
												$coa =  $this->Client_journal_model->get_author_coauthors($row->art_id);
												$coa_arr = (explode(",& ",$coa));
												$date = new DateTime($row->dl_datetime);
												$date = $date->format('Y-m-d');

											?>
										
											<tr>
												<td><?= $index + 1 ?></td>
												<td><a href="<?= base_url() . 'client/ejournal/article/' . $row->art_id ?>" class="text-dark" target="_blank"><?= $row->art_title ?></a>,
												<a href="<?= base_url() . 'client/ejournal/articles?search=' . str_replace(' ', '+', $row->art_author) ?>" class="text-dark" target="_blank"><?= $row->art_author ?></a>,
												
													<?php $i = 0; foreach($coa_arr as $cr):?>
														<?php $cc = $cr; ?>
														<a href="<?= base_url() . 'client/ejournal/articles?search=' . str_replace(' ', '+', $cr) ?>" class="text-dark" target="_blank"><?=$cc;?></a> 
														<?php if($i < (count($coa_arr) - 1)) echo ','; ?>
														<?php $i++; ?>
													<?php endforeach;?>
												,
												Volume <?= $row->jor_volume ?>,
												<a class="text-dark" href="<?=base_url('/client/ejournal/volume/'.$row->jor_volume.'/'. $row->jor_issue)?>" class="main-link" target="_blank"><?= $issue ?> (<?= $row->art_year ?>)</a></td>
												<td><?= $date ?></td>
											</tr>

											<?php endforeach; ?>
										</tbody>
									</table>
								</div>
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
