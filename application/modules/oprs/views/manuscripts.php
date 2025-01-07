<?php $role = $this->session->userdata('_oprs_type_num');?>

<div id="layoutSidenav_content">
    <main>
		<div class="container-fluid pt-3">
			<!-- Breadcrumbs-->
			<!-- <ol class="breadcrumb">
				<li class="breadcrumb-item">
					<a href="javascript:void(0);">Manuscripts</a>
				</li>
				<li class="breadcrumb-item active"><?php echo $this->session->userdata('_oprs_type'); ?>
					(<?php echo $this->session->userdata('_oprs_username'); ?>)</li>
			</ol> -->

			<!-- Manuscript Table -->
			<div class="card mb-3 border border-dark">
				<!-- <div class="card-header fw-bold">
					<i class="fa fa-table"></i> List of Manuscripts
				</div> -->
				<div class="card-body">
				<h3 class="fw-bold">Manuscripts</h3>
					<div class="table-responsive">
						<!-- REVIEWER -->
						<?php if (_UserRoleFromSession() == 5) {?>
						<table class="table table-hover" id="dataTable_rev" width="100%" cellspacing="0">
							<thead>
								<tr>
									<th>#</th>
									<th>Title</th>
									<th>Date Submitted</th>
									<th>Date Reviewed</th>
									<th>Action</th>
									<th>Upload NDA</th>
								</tr>
							</thead>
							<tbody>
								<?php $c = 1;foreach ($manus as $m): ?>
								<?php $drev = ($m->date_reviewed == null) ? '-' : $m->date_reviewed?>
								<?php $mantitle = rawurlencode($m->man_title); ?>
								<?php $action = (($m->scr_status == '4') ? '<span class="badge rounded-pill badge-success">Recommended as submitted</span>' 
									: ((($m->scr_status == '5') ? '<span class="badge rounded-pill badge-warning">Recommended with minor revisions</span>' 
									: ((($m->scr_status == '6') ? '<span class="badge rounded-pill badge-warning">Recommended with major revisions</span>'  
									: ((($m->scr_status == '7') ? '<span class="badge rounded-pill badge-danger">Not recommended</span>' 
									: '<button type="button" class="btn btn-light text-success btn-sm"  data-bs-toggle="modal" rel="tooltip" data-bs-placement="top" title="View Tracking" data-bs-target="#startReviewModal" onclick="start_review(\'' . $m->man_file . '\',\'' . $m->row_id . '\',\'' . $mantitle . '\',\'' . $m->man_author . '\',\'' . $m->rev_hide_auth . '\')"><span class="fa fa-chevron-circle-right" ></span> Start Review</button>'))))))
										);?>
								<?php $i = $m->man_issue;
										$issue = (($i == 5) ? 'Special Issue No. 1' 
												: (($i == 6) ? 'Special Issue No. 2' 
												: (($i == 7) ? 'Special Issue No. 3' 
												: (($i == 8) ? 'Special Issue No. 4' 
												: 'Issue ' . $i))));
										?>
								<tr>
									<td></td>
									<td>
										<a href="javascript:void(0);"
											onclick="view_manus(<?php echo $m->row_id; ?>,<?php echo $m->rev_hide_auth; ?>);"
											class="text-dark "><i class="fa fa-plus-circle text-primary" rel="tooltip"
												data-bs-placement="top" title="Click for more details"></i></a>
										<?php echo $m->man_title; ?>
									</td>
									<td><?php echo date_format(new DateTime($m->date_created), 'F j, Y, g:i a'); ?></td>
									<td><?php echo $drev; ?></td>
									<td><?php echo $action; ?></td>
									<td>
										<?php if($m->scr_nda == NULL){ ?>
										<form id="submit_nda" method="POST" enctype="multipart/form-data">
											<input type="hidden" id="scr_man_id" name="scr_man_id"
												value="<?php echo $m->row_id;?>">
											<div class="input-group is-invalid">
												<div class="custom-file">
													<input type="file" class="custom-file-input " id="scr_nda" name="scr_nda"
														required>
													<label class="custom-file-label scr_nda" for="scr_nda">Choose
														file...</label>
												</div>
												<div class="input-group-append">
													<button class="btn btn-outline-secondary" type="submit"
														accept=".doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/pdf">Submit</button>
												</div>
											</div>
										</form>
										<?php }else{ echo $m->scr_nda;} ?>
									</td>
								</tr>
								<?php endforeach;?>

							</tbody>
						</table>
						<!-- EDITOR -->
						<?php } else if(_UserRoleFromSession() == 12) {?>
						<table class="table table-hover" id="editorial_reviews_table" width="100%" cellspacing="0">
							<thead>
								<tr>
									<th>#</th>
									<th>Title</th>
									<th>Status</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								<?php $c = 1;foreach ($manus as $m): ?>
								<?php $mantitle = rawurlencode($m->man_title); ?>
								<?php $i = $m->man_issue;
								$issue = (($i == 5) ? 'Special Issue No. 1' 
								: (($i == 6) ? 'Special Issue No. 2' 
								: (($i == 7) ? 'Special Issue No. 3' 
								: (($i == 8) ? 'Special Issue No. 4' 
								: 'Issue ' . $i))));
								?>

								<?php $status =  (($m->man_status == '4') ? 'Pending' 
								: (($m->man_status == '5') ? 'To submit final manusript'
								: 'For Publication'));?>

								<?php $class = (($m->man_status == '1') ? 'warning' 
								: ((($m->man_status == '2') ? 'primary' 
								: ((($m->man_status == '3') ? 'info' 
								: ((($m->man_status == '4') ? 'danger' 
								: 'success')))))));?>
								<tr>
									<td></td>
									<td width="60%"><?php echo $m->man_title; ?></br>
										<span class="badge rounded-pill text-bg-secondary"><?php echo $issue;?></span>
										<span class="badge rounded-pill text-bg-secondary">Volume <?php echo $m->man_volume;?></span>
										<span class="badge rounded-pill text-bg-secondary"><?php echo $m->man_year;?></span></td>
									<td><span class="badge text-bg-<?php echo $class;?>"><?php echo $status;?></span></td>
									<td>

										<?php if($m->man_status == 4){ ?>
										<button type="button" class="btn btn-light btn-sm"
											onclick="view_reviews('<?php echo $m->row_id; ?>','<?php echo $mantitle; ?>')"
											data-bs-toggle="modal" data-bs-target="#reviewsModal" rel="tooltip" data-bs-placement="top"
											title="View Reviews"><span class="fa fa-eye"></span> View Reviews
										</button>
										<button type="button" class="btn btn-light btn-sm"
											onclick="submit_editorial_review('<?php echo $m->row_id; ?>','<?php echo $mantitle; ?>')"
											data-bs-toggle="modal" data-bs-target="#editorialModal" rel="tooltip" data-bs-placement="top"
											title="Submit Editorial Review"><span class="fa fa-chevron-circle-right"></span>
											Submit Editorial Review
										</button>
										<?php }else if($m->man_status == 6){ ?>
										<a type="button" class="btn btn-light btn-sm"
											href="<?php echo base_url('/assets/oprs/uploads/revised_manuscripts_word/'.$m->man_word); ?>"
											download><span class="fa fa-chevron-circle-right"></span> Download Final Manuscript
										</a>
										<button type="button" class="btn btn-light btn-sm"
											onclick="for_publication('<?php echo $m->row_id; ?>')" data-bs-toggle="modal"
											data-bs-target="#publicationModal" rel="tooltip" data-bs-placement="top"
											title="Submit to Layout Manager"><span class="fa fa-chevron-circle-right"></span>
											Submit to Layout Manager
										</button>
										<?php } ?>

									</td>
								</tr>
								<?php endforeach;?>

							</tbody>
						</table>
						<!-- LAYOUT -->
						<?php } else if(_UserRoleFromSession() == 13) {?>
						<table class="table table-hover" id="layout_table" width="100%" cellspacing="0">
							<thead>
								<tr>
									<th>#</th>
									<th>Title</th>
									<th>Status</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								<?php $c = 1;foreach ($manus as $m): ?>
								<?php $mantitle = rawurlencode($m->man_title); ?>
								<?php $i = $m->man_issue;
								$issue = (($i == 5) ? 'Special Issue No. 1' 
								: (($i == 6) ? 'Special Issue No. 2' 
								: (($i == 7) ? 'Special Issue No. 3' 
								: (($i == 8) ? 'Special Issue No. 4' 
								: 'Issue ' . $i))));
								?>

								<?php $status = 'For Layout'; ?>

								<?php $class = 'success';?>
								<tr>
									<td></td>
									<td width="60%"><?php echo $m->man_title; ?></br>
										<span class="badge rounded-pill text-bg-secondary"><?php echo $issue;?></span>
										<span class="badge rounded-pill text-bg-secondary">Volume <?php echo $m->man_volume;?></span>
										<span class="badge rounded-pill text-bg-secondary"><?php echo $m->man_year;?></span></td>
									<td><span class="badge text-bg-<?php echo $class;?>"><?php echo $status;?></span></td>
									<td>

										<?php if($m->man_status == 4){ ?>
										<button type="button" class="btn btn-light btn-sm"
											onclick="view_reviews('<?php echo $m->row_id; ?>','<?php echo $mantitle; ?>')"
											data-bs-toggle="modal" data-bs-target="#reviewsModal" rel="tooltip" data-bs-placement="top"
											title="View Reviews"><span class="fa fa-eye"></span> View Reviews
										</button>
										<button type="button" class="btn btn-light btn-sm"
											onclick="submit_editorial_review('<?php echo $m->row_id; ?>','<?php echo $mantitle; ?>')"
											data-bs-toggle="modal" data-bs-target="#editorialModal" rel="tooltip" data-bs-placement="top"
											title="Submit Editorial Review"><span class="fa fa-chevron-circle-right"></span>
											Submit Editorial Review
										</button>
										<?php }else if($m->man_status == 7){ ?>
										<a type="button" class="btn btn-light btn-sm"
											href="<?php echo base_url('/assets/oprs/uploads/revised_abstracts_word/'.$m->man_abs); ?>"><span
												class="fa fa-chevron-circle-right" download></span> Download Abtract
										</a>
										<a type="button" class="btn btn-light btn-sm"
											href="<?php echo base_url('/assets/oprs/uploads/revised_manuscripts_word/'.$m->man_word); ?>"
											download><span class="fa fa-chevron-circle-right"></span> Download Manuscript
										</a>
										<button type="button" class="btn btn-light btn-sm"
											onclick="submit_publishable('<?php echo $m->row_id; ?>','<?php echo $mantitle; ?>')"
											data-bs-toggle="modal" data-bs-target="#publishableModal" rel="tooltip"
											data-bs-placement="top" title="Submit Publishable Manuscript"><span
												class="fa fa-chevron-circle-right"></span> Submit as Publishable
										</button>
										<?php } ?>

									</td>
								</tr>
								<?php endforeach;?>

							</tbody>
						</table>
						<?php } else {?>

							<?php if(_UserRoleFromSession() == 1){ ?>
								<div class="alert alert-primary " role="alert">
									<p class="fw-bold"><span class="fas fa-info-circle me-2"></span>Instructions to Author</p>
									<hr>
									<ol>
										<li>Submission of a paper to the NRCP Research Journal implies that the work has not been published, and is not under consideration for publication in other journals. If the manuscript is accepted for publication, the authors agree that the article will not be published elsewhere.</li>
										<li>The manuscript/article (PDF format) to be submitted MUST NOT contain any traces of identity of author (i.e. Name of author, co-authors, email address and affiliation). The information, however, should be inputted in the upload forms (Word format).</li>
										<li>Submission Process:
											<ol type="a" class="ms-3 mt-1">
												<li>Corresponding Author → check from SKMS database of the membership status (if member or non-member) → show the membership status.</li>
												<li>Provide the corresponding author with radio-button option [O Main Author | O Co-Author</li>
												<li>If the corresponding author is the main author → type/input the name(s) of the co-author(s). Each name should be checked with the SKMS DB for the status of membership. Display the membership status after the name, e.g. Dela Cruz, Juan A. (Non-Member), Manalo, Jose (Member)</li>
												<li>If the corresponding author is a co-author → type/input the name of the primary author. Check the name with the SKMS DB for the status of membership. Display the membership status after the name, e.g. San Juan, Pedro (Member)</li>
											</ol>
										</li>
									</ol>
									<!-- The manuscript/article to be submitted must not contain any traces of identity of author (i.e. Name
									of author, co-authors, email address and affiliation). <br />
									The information, however, should be inputted in the upload forms. <br /> -->
									<button type="button" class="btn btn-primary mt-2" data-bs-toggle="modal"
										data-bs-target="#uploadModal" onclick="show_hidden_manus()"><i class="fas fa-upload"></i> Upload Manuscript
									</button>
								</div>

								<table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
									<thead>
										<tr>
											<th>#</th>
											<th>Title, Author</th>
											<th>Type of Publication</th>
											<th>Date Submitted</th>
											<th>Tracking No.</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
										<?php $c = 1;foreach ($manus as $m): ?>
										<?php $mem_type = count($this->Manuscript_model->check_member($m->man_user_id)); ?>

										<?php $mem_type = ($mem_type > 0) ? '<span class="badge rounded-pill text-bg-secondary">Non-member</span>' : ''; ?>

										<?php $stat = $m->man_status;
											if($stat > 1 && $stat != 99){
												$i = $m->man_issue;
												$issue = (($i == 5) ? 'Special Issue No. 1' 
												: (($i == 6) ? 'Special Issue No. 2' 
												: (($i == 7) ? 'Special Issue No. 3' 
												: (($i == 8) ? 'Special Issue No. 4' 
												: 'Issue ' . $i))));
											}
										?>

										<?php $acoa = (empty($this->Coauthor_model->get_author_coauthors($m->row_id))) ? '' : ', ' . $this->Coauthor_model->get_author_coauthors($m->row_id);?>
										<?php $title = $m->man_title . ', ' . $m->man_author . $acoa; ?>
										<tr>
											<td></td>
											<td width="50%"><a href="javascript:void(0);" onclick="view_manus(<?php echo $m->row_id; ?>);"
													class="text-dark text-decoration-none mb-1"><?php echo $title; ?></a>
												<?php if($stat > 1 && $stat != 99){ ?>
												</br>
												<span class="badge rounded-pill text-bg-secondary"><?php echo $issue;?></span>
												<span class="badge rounded-pill text-bg-secondary">Volume <?php echo $m->man_volume;?></span>
												<span class="badge rounded-pill text-bg-secondary"><?php echo $m->man_year;?></span>
												<?php } ?>
												<?php echo $mem_type;?>
											</td>
											<td><?php echo $m->publication_desc;?></td>
											<td><?php echo date_format(new DateTime($m->date_created), 'F j, Y, g:i a'); ?></td>
											<td class="text-center"><a href=""><?php echo $m->man_trk_no;?></a></td>
											<td>
												<div class="btn-group d-flex gap-2" role="group" aria-label="Basic example">
													<button type="button" class="btn btn-primary">View</button>
													<button type="button" class="btn btn-primary">Upload</button>
													<button type="button" class="btn btn-primary">Revision</button>
												</div>
											</td>
										</tr>
										<?php endforeach; ?>
									</tbody>
								</table>

							<?php } else { ?>
								<table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
									<thead>
										<tr>
											<th>#</th>
											<th>Title</th>
											<th>Date Submitted</th>
											<th>Status</th>
											<th>Actions</th>
											<th>Remarks</th>
										</tr>
									</thead>
									<tbody>
										<?php $c = 1;foreach ($manus as $m): ?>
										<?php $role = $this->session->userdata('_oprs_type_num');?>
										<?php $rev_cnt = count($this->Manuscript_model->get_reviewers_display($m->row_id));?>
										<?php $rev_total = count($this->Manuscript_model->get_reviewers_display($m->row_id, 999));?>
										<?php $rev_act = count($this->Manuscript_model->get_reviewers_w_score($m->row_id));?>
										<?php $scr = count($this->Review_model->get_review(_UserRoleFromSession(), $m->row_id));?>
										<?php $mem_type = count($this->Manuscript_model->check_member($m->man_user_id)); ?>

										<?php $mem_type = ($mem_type > 0) ? '<span class="badge rounded-pill text-bg-secondary">Non-member</span>' : ''; ?>
										<!-- get total reveiewers with scores -->
										<?php $status = (($m->man_status == '1') ? 'New' 
										: ((($m->man_status == '2') ? 'On-review (0/' . $rev_cnt . ')' 
										: ((($m->man_status == '3') ? 'Reviewed (' . $rev_act . '/' . $rev_cnt . ')'
										: ((($m->man_status == '4') ? 'Editorial Review - Pending' 
										: ((($m->man_status == '5') ? 'To submit final manucsript'
										: ((($m->man_status == '6') ? 'For Publication' 
										: ((($m->man_status == '7') ? 'For Layout' 
										: ((($m->man_status == '8') ? 'Publishable' 
										: ((($m->man_status == '9') ? 'Published' 
										:	'Published to other journal platform')))))))))))))))));?>

										<?php $stat = $m->man_status;
											if($stat > 1 && $stat != 99){
												$i = $m->man_issue;
												$issue = (($i == 5) ? 'Special Issue No. 1' 
												: (($i == 6) ? 'Special Issue No. 2' 
												: (($i == 7) ? 'Special Issue No. 3' 
												: (($i == 8) ? 'Special Issue No. 4' 
												: 'Issue ' . $i))));
											}
										?>

										<?php $class = (($m->man_status == '1') ? 'warning' 
										: ((($m->man_status == '2') ? 'primary' 
										: ((($m->man_status == '3') ? 'info' 
										: ((($m->man_status == '4') ? 'danger' 
										: ((($m->man_status == '5') ? 'danger' 
										: ((($m->man_status == '99') ? 'secondary' 
										: 'success')))))))))));?>

										<?php $acoa = (empty($this->Coauthor_model->get_author_coauthors($m->row_id))) ? '' : ', ' . $this->Coauthor_model->get_author_coauthors($m->row_id);?>
										<?php $title = $m->man_title . ', ' . $m->man_author . $acoa; ?>
										<tr>
											<td></td>
											<td width="50%"><a href="javascript:void(0);" onclick="view_manus(<?php echo $m->row_id; ?>);"
													class="text-dark text-decoration-none mb-1"><?php echo $title; ?></a>
												<?php if($stat > 1 && $stat != 99){ ?>
												</br>
												<span class="badge rounded-pill text-bg-secondary"><?php echo $issue;?></span>
												<span class="badge rounded-pill text-bg-secondary">Volume <?php echo $m->man_volume;?></span>
												<span class="badge rounded-pill text-bg-secondary"><?php echo $m->man_year;?></span>
												<?php } ?>
												<?php echo $mem_type;?>
											</td>
											<td><?php echo date_format(new DateTime($m->date_created), 'F j, Y, g:i a'); ?></td>
											<td class="text-center"><span 
													class="badge rounded-pill text-bg-<?php echo $m->status_class;?> text-center" data-bs-toggle="modal"
													rel="tooltip" data-bs-placement="top" title="View Tracking"
													data-bs-target="#trackingModal"
													onclick="tracking('<?php echo $m->row_id; ?>','<?php echo $this->session->userdata('_oprs_type_num');?>','<?php echo rawurlencode($title) ?>','<?php echo $m->man_status ?>')"><?php echo $m->status_desc;?></span>
											</td>
											<td>
												<div class="btn-group" role="group">
													<!-- MANAGING EDITOR -->
													<?php if (_UserRoleFromSession() == 3) { 
														if($m->man_status == 1){ ?>
													<!-- process manuscript -->
													<button type="button" class="btn border border-1 btn-light text-success"
														onclick="process_man(<?php echo $m->row_id; ?>,<?php echo $m->man_status; ?>)"
														data-bs-toggle="modal" data-bs-target="#processModal" rel="tooltip"
														data-bs-placement="top" title="Add Reviewers"><span
															class="fas fa-user-plus"></span></button>
													<!-- add remarks -->
													<button type="button" class="btn border border-1 btn-light text-primary"
														onclick="add_remarks('<?php echo $m->row_id; ?>')" data-bs-toggle="modal"
														data-bs-target="#remarksModal" rel="tooltip" data-bs-placement="top"
														title="Add Remarks"><span class="far fa-edit"></span></button>

													<?php }else if($m->man_status <= 3){ ?>
													<!-- process manuscript -->
													<button type="button" class="btn border border-1 btn-light text-success"
														onclick="process_man(<?php echo $m->row_id; ?>,<?php echo $m->man_status; ?>)"
														data-bs-toggle="modal" data-bs-target="#processModal" rel="tooltip"
														data-bs-placement="top" title="Add Reviewers"><span
															class="fas fa-user-plus"></span></button>
													<!-- view reviewers -->
													<button type="button" class="btn border border-1 btn-light text-info"
														onclick="view_reviewers('<?php echo $m->row_id; ?>','0','<?php echo rawurlencode($title); ?>','<?php echo $m->man_status; ?>')"
														data-bs-toggle="modal" data-bs-target="#reviewerModal" rel="tooltip"
														data-bs-placement="top" title="View Reviewers"><span
															class="fas fa-users"></span></button>
													<?php }else if($m->man_status == 8){ ?>
													<!-- publish to ejournal -->
													<button type="button" class="btn border border-1 btn-light text-success"
														onclick="publish_to_ejournal('<?php echo $m->row_id; ?>')"
														data-bs-toggle="modal" data-bs-target="#publishModal" rel="tooltip"
														data-bs-placement="top" title="Publish to eJournal"><span
															class="fas fa-paper-plane"></span></button>
													<?php } ?>
													<!-- view abstract, full text manuscript, word -->
													<button type="button" class="btn border border-1 btn-light text-dark"
														onclick="manus_view('<?php echo $m->man_abs; ?>', 'abs')"
														data-bs-toggle="modal" data-bs-target="#manusModal" rel="tooltip"
														data-bs-placement="top" title="View Abstract"><span
														class="far fa-file-alt"></span> </button>
													<button type="button" class="btn border border-1 btn-light text-dark"
														onclick="manus_view('<?php echo $m->man_file; ?>', 'full')"
														data-bs-toggle="modal" data-bs-target="#manusModal" rel="tooltip"
														data-bs-placement="top" title="View Full Manuscript"><span
														class="far fa-file-pdf"></span> </button>
													<a type="button" class="btn border border-1 btn-light text-dark"
														href="<?php echo base_url('/assets/oprs/uploads/\initial_manuscripts_word/'.$m->man_word); ?>" rel="tooltip"
														data-bs-placement="top" title="Download Full Text Word" download><span
														class="far fa-file-alt"></span> WORD</a>

											
													<?php } ?>
													<!-- SUPERADMIN -->
													<?php if (_UserRoleFromSession() == 8 ) { ?>
													<!-- view reviewers -->
													<button type="button" class="btn border border-1  btn-light text-info"
														onclick="view_reviewers('<?php echo $m->row_id; ?>','0','<?php echo rawurlencode($title); ?>','<?php echo $m->man_status; ?>')"
														data-bs-toggle="modal" data-bs-target="#reviewerModal" rel="tooltip"
														data-bs-placement="top" title="View Reviewers"><span
															class="fas fa-users"></span></button>
													<!-- view abstract and full text manuscript -->
													<button type="button" class="btn border border-1 btn-light text-dark"
														onclick="manus_view('<?php echo $m->man_abs; ?>', 'abs')"
														data-bs-toggle="modal" data-bs-target="#manusModal" rel="tooltip"
														data-bs-placement="top" title="View Abstract"><span
															class="far fa-file-alt"></span> </button>
													<button type="button" class="btn border border-1 btn-light text-dark"
														onclick="manus_view('<?php echo $m->man_file; ?>', 'full')"
														data-bs-toggle="modal" data-bs-target="#manusModal" rel="tooltip"
														data-bs-placement="top" title="View Full Manuscript"><span
															class="far fa-file-pdf"></span> </button>
													<!-- delete manuscript -->
													<button type="button" class="btn border border-1 btn-light text-danger"
														rel="tooltip" data-bs-placement="top" title="Delete"
														onclick="remove_manus('<?php echo $m->row_id; ?>')"><span
															class="fa fa-trash"></span></button>
													<?php } ?>

													<!-- approve manuscript -->
													<?php if (_UserRoleFromSession() == 9 && $rev_act >= 3 ) { ?>
													<!-- <button type="button" class="btn btn-light text-success btn" rel="tooltip"
														data-bs-placement="top" title="Final Review"
														onclick="final_review('<?php echo $m->row_id; ?>')"><span
															class="	fa fa-gavel"></span> Final Review</button> -->
													<?php } ?>
												</div>
											</td>
											<td><em><?php echo ($m->man_remarks == NULL) ? '-' : $m->man_remarks;?></em></td>
										</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							<?php } ?>
						<?php }?>
					</div>
				</div>
				<!-- <div class="card-footer small text-muted">
					<?php if (_UserRoleFromSession() == 3) { ?>
					<button type="button" data-bs-toggle="collapse" data-bs-target="#publishables"
						class="btn btn-outline-primary">View Publishables <span
							class="badge badge-primary"><?php echo count($publish);?></span></button>
					<?php } ?>
				</div> -->
			</div>

			<div id="publishables" class="collapse">
				<table class="table table-striped" id="table-publishables">
					<thead>
						<tr>
							<th scope="col">#</th>
							<th scope="col">Volume</th>
							<th scope="col">Issue</th>
							<th scope="col">Year</th>
							<th scope="col">Approved</th>
							<th scope="col">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php $r=1; $c=0; foreach($u_man_jor as $row):?>
						<?php $i = $m->man_issue;
							$issue = (($i == 5) ? 'Special Issue No. 1' 
									: (($i == 6) ? 'Special Issue No. 2' 
									: (($i == 7) ? 'Special Issue No. 3' 
									: (($i == 8) ? 'Special Issue No. 4' 
									: 'Issue ' . $i))));
							$articles = $this->Manuscript_model->get_publishable_manus($row->journal); $c++;
						
							?>

						<tr>
							<td><?php echo $r++;?></td>
							<td><?php echo 'Volume ' . $row->man_volume;?></td>
							<td><?php echo $issue;?></td>
							<td><?php echo $row->man_year;?></td>
							<td><?php echo $row->articles;?></td>
							<td><button type="button" class="btn btn-sm btn-outline-success" rel="tooltip" data-bs-placement="top"
									title="Publish selected items" onclick="publish_articles(<?php echo $c++;?>)"><span
										class="fa fa-globe"></span> Publish to eJournal</button></td>
						</tr>
						<tr>
							<td></td>
							<td colspan="4">
								<?php  foreach($articles as $a):?>
								<div class="form-group">
									<div class="custom-control custom-checkbox">
										<input type="checkbox" class="custom-control-input" value="<?php echo $a->row_id;?>"
											name="publish_title" id="publish_title<?php echo $a->row_id;?>">
										<label class="custom-control-label pt-1"
											for="publish_title<?php echo $a->row_id;?>"><?php echo $a->man_title;?></label>
									</div>
								</div>
								<?php endforeach;?>
							</td>
						</tr>
						<?php endforeach;?>
					</tbody>
				</table>
			</div>
		</div>
	</main>

<!-- /#wrapper -->
<!-- Scroll to Top Button-->
<!-- <a class="scroll-to-top rounded" href="#page-top">
	<i class="fas fa-angle-up"></i>
</a> -->

