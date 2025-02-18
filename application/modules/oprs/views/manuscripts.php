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
			<h3 class="fw-bold">Manuscripts</h3>
			<div class="card mb-3 border border-dark">
				<!-- <div class="card-header">
					<i class="fas fa-scroll me-1"></i>Manuscripts
				</div> -->
				<div class="card-body">
					<div class="table-responsive">
						<!-- AUTHOR -->
						<?php if(_UserRoleFromSession() == 1){ ?>
								<div class="alert alert-primary " role="alert">
									<p class="fw-bold"><span class="fas fa-info-circle me-2"></span>Instructions to Author</p>
									<hr>
									<ol>
										<li>Submission of a paper to the NRCP Research Journal implies that the work has not been published, and is not under consideration for publication in other journals. If the manuscript is accepted for publication, the authors agree that the article will not be published elsewhere.</li>
										<li>The manuscript/article (PDF format) to be submitted MUST NOT contain any traces of identity of author (i.e. Name of author, co-authors, email address and affiliation). The information, however, should be inputted in the upload forms (Word format).</li>
										<!-- <li>Submission Process:
											<ol type="a" class="ms-3 mt-1">
												<li>Corresponding Author → check from SKMS database of the membership status (if member or non-member) → show the membership status.</li>
												<li>Provide the corresponding author with radio-button option [O Main Author | O Co-Author</li>
												<li>If the corresponding author is the main author → type/input the name(s) of the co-author(s). Each name should be checked with the SKMS DB for the status of membership. Display the membership status after the name, e.g. Dela Cruz, Juan A. (Non-Member), Manalo, Jose (Member)</li>
												<li>If the corresponding author is a co-author → type/input the name of the primary author. Check the name with the SKMS DB for the status of membership. Display the membership status after the name, e.g. San Juan, Pedro (Member)</li>
											</ol>
										</li> -->
									</ol>
									<!-- The manuscript/article to be submitted must not contain any traces of identity of author (i.e. Name
									of author, co-authors, email address and affiliation). <br />
									The information, however, should be inputted in the upload forms. <br /> -->
									<button type="button" class="btn btn-primary mt-2" data-bs-toggle="modal"
										data-bs-target="#uploadModal" onclick="show_hidden_manus()"><i class="fas fa-upload"></i> Upload Manuscript
									</button>
								</div>

								<div class="table-responsive">
									<table class="table table-hover" id="author_table" width="100%" cellspacing="0">
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
											<?php $c = 1;foreach ($man_all as $m): ?>
											<?php $mem_type = $this->Manuscript_model->check_member($m->man_author_user_id); ?>
											<?php $mem_type = ($mem_type == 3) ? 'Member' : 'Non-member'; ?>

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
												<td><?php echo $title; ?>
													<?php if($stat > 1 && $stat != 99){ ?>
													</br>
													<span class="badge rounded-pill text-bg-secondary"><?php echo $issue;?></span>
													<span class="badge rounded-pill text-bg-secondary">Volume <?php echo $m->man_volume;?></span>
													<span class="badge rounded-pill text-bg-secondary"><?php echo $m->man_year;?></span>
													<?php } ?>
												</td>
												<td><?php echo $m->publication_desc;?></td>
												<td><?php echo date_format(new DateTime($m->date_created), 'F j, Y'); ?></td>
												<!-- <td class="text-center"><a href="javascript:void(0);" onclick="tracking('<?php echo $m->man_trk_no;?>',<?php echo $this->session->userdata('_oprs_type_num');?>,<?php echo $m->man_status ?>)"><?php echo $m->man_trk_no;?></a></td> -->
												<!-- <td class="text-center"><a href="javascript:void(0);" onclick="tracking(<?php echo $m->row_id;?>,<?php echo $this->session->userdata('_oprs_type_num');?>,'<?php echo rawurlencode($title) ?>',<?php echo $m->man_status ?>)"><?php echo $m->man_trk_no;?></a></td> -->
												<td class="text-centerx"><a href="javascript:void(0);" onclick="tracking(<?php echo $m->row_id;?>)"><?php echo $m->man_trk_no;?></a></td>
												<td>
													<div class="d-flex gap-2" role="group">
														<button type="button" class="btn btn-outline-secondary" rel="tooltip"
														data-bs-placement="top" title="View" onclick="view_manus(<?php echo $m->row_id; ?>);"><span class="fa fa-eye"></span></button>

														<?php if($m->man_status == 10){ ?> 
															<!-- for revision -->
															<button type="button" class="btn btn-outline-primary" rel="tooltip"
															data-bs-placement="top" title="Upload Revision" onclick="upload_revision(<?php echo $m->row_id; ?>)"><span class="fa fa-edit"></span></button>
														<?php } ?>

														<?php if($m->man_status == 9){ ?>
															<!-- for proofread / revision -->
															<button type="button" class="btn btn-outline-primary" rel="tooltip"
															data-bs-placement="top" title="Upload Revision" onclick="upload_proofread_revision(<?php echo $m->row_id; ?>)"><span class="fa fa-edit"></span></button>
														<?php } ?>
														
													</div>
												</td>
											</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
								</div>

						<?php } else{ ?>
							<!-- PROCESSOR -->

							
							<!-- TECHNICAL DESK EDITOR, SUPERADMIN-->
							<?php if (_UserRoleFromSession() == 5 || _UserRoleFromSession() == 20 || _UserRoleFromSession() == 3) { ?>
								<ul class="nav nav-tabs" id="myTab" role="tablist">
									<li class="nav-item" role="presentation">
										<button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all-tab-pane" type="button" role="tab" aria-controls="all-tab-pane" aria-selected="true">All <?php if($man_all_count > 0){?><span class="badge rounded-pill text-bg-secondary"><?php echo $man_all_count; ?></span><?php } ?></button>
									</li>
									<li class="nav-item" role="presentation">
										<button class="nav-link" id="new-tab" data-bs-toggle="tab" data-bs-target="#new-tab-pane" type="button" role="tab" aria-controls="new-tab-pane" aria-selected="false">New <?php if($man_new_count > 0){?><span class="badge rounded-pill text-bg-secondary"><?php echo $man_new_count; ?></span><?php } ?></button>
									</li>
									<li class="nav-item" role="presentation">
										<button class="nav-link" id="onreview-tab" data-bs-toggle="tab" data-bs-target="#onreview-tab-pane" type="button" role="tab" aria-controls="onreview-tab-pane" aria-selected="false">On-review <?php if($man_onreview_count >0){?><span class="badge rounded-pill text-bg-secondary"><?php echo $man_onreview_count; ?></span><?php } ?></button>
									</li>
									<li class="nav-item" role="presentation">
										<button class="nav-link" id="peer-tab" data-bs-toggle="tab" data-bs-target="#peer-tab-pane" type="button" role="tab" aria-controls="peer-tab-pane" aria-selected="false">Endorsement to Peer <?php if($man_peer_count >0){?><span class="badge rounded-pill text-bg-secondary"><?php echo $man_peer_count; ?></span><?php } ?></button>
									</li>
									<!-- <li class="nav-item" role="presentation">
										<button class="nav-link" id="review-consolidated-tab" data-bs-toggle="tab" data-bs-target="#review-consolidated-tab-pane" type="button" role="tab" aria-controls="review-consolidated-tab-pane" aria-selected="false">Review Consolidated <?php if($rev_cons_count > 0){?><span class="badge rounded-pill text-bg-secondary"><?php echo $rev_cons_count; ?></span><?php } ?></button>
									</li> -->
									<!-- <li class="nav-item" role="presentation">
										<button class="nav-link" id="review-consolidated-tab" data-bs-toggle="tab" data-bs-target="#review-consolidated-tab-pane" type="button" role="tab" aria-controls="review-consolidated-tab-pane" aria-selected="false">Revised<?php if($rev_cons_count > 0){?><span class="badge rounded-pill text-bg-secondary"><?php echo $rev_cons_count; ?></span><?php } ?></button>
									</li> -->
									<li class="nav-item" role="presentation">
										<button class="nav-link" id="proofread-coped-tab" data-bs-toggle="tab" data-bs-target="#proofread-coped-tab-pane" type="button" role="tab" aria-controls="proofread-coped-tab-pane" aria-selected="false">Proofread Copy Editor <?php if($prf_cop_count > 0){?><span class="badge rounded-pill text-bg-secondary"><?php echo $prf_cop_count; ?></span><?php } ?></button>
									</li>
									<li class="nav-item" role="presentation">
										<button class="nav-link" id="final-review-tab" data-bs-toggle="tab" data-bs-target="#final-review-tab-pane" type="button" role="tab" aria-controls="final-review-tab-pane" aria-selected="false">Final Review <?php if($fin_rev_count > 0){?><span class="badge rounded-pill text-bg-secondary"><?php echo $fin_rev_count; ?></span><?php } ?></button>
									</li>
									<li class="nav-item" role="presentation">
										<button class="nav-link" id="proofread-author-tab" data-bs-toggle="tab" data-bs-target="#proofread-author-tab-pane" type="button" role="tab" aria-controls="proofread-author-tab-pane" aria-selected="false">Proofread Author <?php if($prf_auth_count > 0){?><span class="badge rounded-pill text-bg-secondary"><?php echo $prf_auth_count; ?></span><?php } ?></button>
									</li>
									<li class="nav-item" role="presentation">
										<button class="nav-link" id="revision-tab" data-bs-toggle="tab" data-bs-target="#revision-tab-pane" type="button" role="tab" aria-controls="revision-tab-pane" aria-selected="false">Revision <?php if($rev_auth_count > 0){?><span class="badge rounded-pill text-bg-secondary"><?php echo $rev_auth_count; ?></span><?php } ?></button>
									</li>
									<li class="nav-item" role="presentation">
										<button class="nav-link" id="layout-tab" data-bs-toggle="tab" data-bs-target="#layout-tab-pane" type="button" role="tab" aria-controls="layout-tab-pane" aria-selected="false">Layout <?php if($lay_art_count > 0){?><span class="badge rounded-pill text-bg-secondary"><?php echo $lay_art_count; ?></span><?php } ?></button>
									</li>
									<li class="nav-item" role="presentation">
										<button class="nav-link" id="final-approval-tab" data-bs-toggle="tab" data-bs-target="#final-approval-tab-pane" type="button" role="tab" aria-controls="final-approval-tab-pane" aria-selected="false">Final Approval <?php if($fin_app_count > 0){?><span class="badge rounded-pill text-bg-secondary"><?php echo $fin_app_count; ?></span><?php } ?></button>
									</li>
									<li class="nav-item" role="presentation">
										<button class="nav-link" id="publication-tab" data-bs-toggle="tab" data-bs-target="#publication-tab-pane" type="button" role="tab" aria-controls="publication-tab-pane" aria-selected="false">Published <?php if($publ_count > 0){?><span class="badge rounded-pill text-bg-secondary"><?php echo $publ_count; ?></span><?php } ?></button>
									</li>
									<li class="nav-item" role="presentation">
										<button class="nav-link" id="rejected-tab" data-bs-toggle="tab" data-bs-target="#rejected-tab-pane" type="button" role="tab" aria-controls="rejected-tab-pane" aria-selected="false">Rejected <?php if($man_rej_count >0){?><span class="badge rounded-pill text-bg-secondary"><?php echo $man_rej_count; ?></span><?php } ?></button>
									</li>
								</ul>
								<div class="tab-content" id="myTabContent">
									<div class="tab-pane fade show active pt-3" id="all-tab-pane" role="tabpanel" aria-labelledby="all-tab" tabindex="0">
										<div class="table-responsive">
											<table class="table table-hover" id="all-manuscript" width="100%" cellspacing="0">
												<thead>
													<tr>
														<th>#</th>
														<th>Title</th>
														<th>Author(s)</th>
														<th>Membership Status</th>
														<th>Date Submitted</th>
														<th>Status</th>
														<th>Tracking No.</th>
														<th>Actions</th>
														<th>Remarks</th>
														<th>Fraction of Process Turnaround</th>
													</tr>
												</thead>
												<tbody>
													<?php $c = 1;foreach ($man_all as $m): ?>
													<?php $role = $this->session->userdata('_oprs_type_num');?>
													<?php $mem_type = $this->Manuscript_model->check_member($m->man_author_user_id); ?>
													<?php $mem_type = ($mem_type == 3) ? 'Member' : 'Non-member'; ?>
													<?php $author_type = ($m->man_author_type == 1) ? 'Main Author' : 'Co-author'; ?>
													<?php $acoa = (empty($this->Coauthor_model->get_author_coauthors($m->row_id))) ? '' : ', ' . $this->Coauthor_model->get_author_coauthors($m->row_id);?>
													<?php $title = $m->man_title; ?>
													<?php $authors = $m->man_author . $acoa; ?>
													<?php $status = '<span class="badge rounded-pill bg-' . $m->status_class . '">' . $m->status . '</span>'; ?>
													<tr>
														<td></td>
														<td width="50%"><a href="javascript:void(0);" onclick="view_manus(<?php echo $m->row_id; ?>);"
																class="text-dark text-decoration-none mb-1"><?php echo $title; ?></a>
															<?php if($status > 1 && $status != 99){ ?>
															</br>
															<span class="badge rounded-pill text-bg-secondary"><?php echo $issue;?></span>
															<span class="badge rounded-pill text-bg-secondary">Volume <?php echo $m->man_volume;?></span>
															<span class="badge rounded-pill text-bg-secondary"><?php echo $m->man_year;?></span>
															<?php } ?>
														</td>
														<td><?php echo $authors;?></td>
														<td><?php echo $author_type;?> - <?php echo $mem_type;?></td>
														<td><?php echo date_format(new DateTime($m->date_created), 'F j, Y, g:i a'); ?></td>
														<td><?php echo $status;?></td>
														<!-- <td class="text-center"><a href="javascript:void(0);" onclick="tracking('<?php echo $m->man_trk_no;?>',<?php echo $this->session->userdata('_oprs_type_num');?>,<?php echo $m->man_status ?>)"><?php echo $m->man_trk_no;?></a></td> -->
														<!-- <td class="text-center"><a href="javascript:void(0);" onclick="tracking('<?php echo $m->man_trk_no;?>',<?php echo $this->session->userdata('_oprs_type_num');?>,'<?php echo rawurlencode($title) ?>',<?php echo $m->man_status ?>)"><?php echo $m->man_trk_no;?></a></td> -->
														<td class="text-center"><a href="javascript:void(0);" onclick="tracking('<?php echo $m->row_id;?>')"><?php echo $m->man_trk_no;?></a></td>
														<td>
															<div class="btn-groupx d-flex gap-1" role="group">
																<!-- TECHNICAL DESK EDITOR -->
																<?php if (_UserRoleFromSession() == 5) { 
																	if($m->man_status == 1){ ?>
																		<!-- process manuscript -->
																		<button type="button" class="btn btn-outline-primary"
																			onclick="tech_rev_criterion(<?php echo $m->row_id; ?>,<?php echo $m->man_status; ?>)" rel="tooltip"
																			data-bs-placement="top" title="Process"><span
																				class="fas fa-gear"></span></button>
																	

																		<?php }else if($m->man_status == 15){ ?>
																		<!-- process manuscript -->
																		<button type="button" class="btn btn-outline-success"
																			onclick="process_man(<?php echo $m->row_id; ?>,<?php echo $m->man_status; ?>)"
																			data-bs-toggle="modal" data-bs-target="#processModal" rel="tooltip"
																			data-bs-placement="top" title="Add Reviewers"><span
																				class="fas fa-user-plus"></span></button>
																		<?php }else if($m->man_status == 5){ ?>
																		<!-- view reviewers -->
																		<button type="button" class="btn border border-1 btn-light text-info"
																			onclick="view_reviewers('<?php echo $m->row_id; ?>','0','<?php echo rawurlencode($title); ?>','<?php echo $m->man_status; ?>')"
																			data-bs-toggle="modal" data-bs-target="#reviewerModal" rel="tooltip"
																			data-bs-placement="top" title="View Reviewers"><span
																				class="fas fa-users"></span></button>
																		<!-- <button type="button" class="btn btn-light btn-sm"
																			onclick="view_reviews('<?php echo $m->row_id; ?>','<?php echo rawurlencode($title); ?>')"
																			data-bs-toggle="modal" data-bs-target="#reviewsModal" rel="tooltip" data-bs-placement="top"
																			title="View Reviews"><span class="fa fa-eye"></span> View Reviews
																		</button> -->
																		<?php }else if($m->man_status == 6){ ?>
																			<button type="button" class="btn btn-outline-primary"
																			onclick="endorse_coped('<?php echo $m->row_id; ?>')"
																			data-bs-toggle="modal" data-bs-target="#checkRevisionModal" rel="tooltip"
																			data-bs-placement="top" title="Endorse to Copy Editor"><span
																				class="fas fa-gear"></span></button>
																		<?php }else if($m->man_status == 8){ ?>
																			<!-- publish to ejournal -->
																			<!-- <button type="button" class="btn border border-1 btn-light text-success"
																			onclick="publish_to_ejournal('<?php echo $m->row_id; ?>')"
																			data-bs-toggle="modal" data-bs-target="#publishModal" rel="tooltip"
																			data-bs-placement="top" title="Publish to eJournal"><span
																				class="fas fa-paper-plane"></span></button> -->
																		<?php } ?>

																	
																		<!-- view manuscript details -->
																		<button type="button" class="btn btn-outline-secondary" rel="tooltip"
																		data-bs-placement="top" title="View" onclick="view_manus(<?php echo $m->row_id; ?>);"><span class="fa fa-eye"></span></button>
																<?php } ?>

																<!-- SUPERADMIN -->
																<?php if (_UserRoleFromSession() == 20 ) { ?>
																	<!-- view reviewers -->
																	<button type="button" class="btn btn-outline-info"
																		onclick="view_reviewers('<?php echo $m->row_id; ?>','0','<?php echo rawurlencode($title); ?>','<?php echo $m->man_status; ?>')"
																		data-bs-toggle="modal" data-bs-target="#reviewerModal" rel="tooltip"
																		data-bs-placement="top" title="View Reviewers"><span
																			class="fas fa-users"></span></button>
																	<!-- view manuscript details -->
																	<button type="button" class="btn btn-outline-secondary" rel="tooltip"
																		data-bs-placement="top" title="View" onclick="view_manus(<?php echo $m->row_id; ?>);"><span class="fa fa-eye"></span></button>
																	<!-- view abstract and full text manuscript -->
																	<!-- <button type="button" class="btn border border-1 btn-light text-dark"
																		onclick="manus_view('<?php echo $m->man_abs; ?>', 'abs')"
																		data-bs-toggle="modal" data-bs-target="#manusModal" rel="tooltip"
																		data-bs-placement="top" title="View Abstract"><span
																			class="far fa-file-alt"></span> </button>
																	<button type="button" class="btn border border-1 btn-light text-dark"
																		onclick="manus_view('<?php echo $m->man_file; ?>', 'full')"
																		data-bs-toggle="modal" data-bs-target="#manusModal" rel="tooltip"
																		data-bs-placement="top" title="View Full Manuscript"><span
																			class="far fa-file-pdf"></span> </button> -->
																	<!-- delete manuscript -->
																	<button type="button" class="btn btn-outline-danger"
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

																
																<!-- view abstract, full text manuscript, word -->
																<!-- <button type="button" class="btn border border-1 btn-light text-dark"
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
																	class="far fa-file-alt"></span></a> -->
																<!-- add remarks -->
																<!-- <button type="button" class="btn border border-1 btn-light"
																	onclick="add_remarks('<?php echo $m->row_id; ?>')" data-bs-toggle="modal"
																	data-bs-target="#remarksModal" rel="tooltip" data-bs-placement="top"
																	title="Add Remarks"><span class="far fa-edit"></span></button> -->
															</div>
														</td>
														<td><em><?php echo ($m->man_remarks == NULL) ? '-' : $m->man_remarks;?></em></td>
														<td><?= $m->man_process_date > 0 ? $fraction : 'N/a' ?></td>
													</tr>
													<?php endforeach; ?>
												</tbody>
											</table>
										</div>
									</div>
									<div class="tab-pane fade" id="new-tab-pane" role="tabpanel" aria-labelledby="new-tab" tabindex="0">
										<div class="table-responsive">
											<table class="table table-hover" id="new-manuscript" width="100%" cellspacing="0">
												<thead>
													<tr>
														<th>#</th>
														<th>Title</th>
														<th>Author(s)</th>
														<th>Membership Status</th>
														<th>Date Submitted</th>
														<th>Status</th>
														<th>Tracking No.</th>
														<th>Actions</th>
														<th>Remarks</th>
													</tr>
												</thead>
												<tbody>
													<?php $c = 1;foreach ($man_new as $m): ?>
													<?php $role = $this->session->userdata('_oprs_type_num');?>
													<?php $mem_type = $this->Manuscript_model->check_member($m->man_author_user_id); ?>
													<?php $mem_type = ($mem_type == 3) ? 'Member' : 'Non-member'; ?>
													<?php $author_type = ($m->man_author_type == 1) ? 'Main Author' : 'Co-author'; ?>
													<?php $acoa = (empty($this->Coauthor_model->get_author_coauthors($m->row_id))) ? '' : ', ' . $this->Coauthor_model->get_author_coauthors($m->row_id);?>
													<?php $title = $m->man_title; ?>
													<?php $authors = $m->man_author . $acoa; ?>
													<?php $status = '<span class="badge rounded-pill bg-' . $m->status_class . '">' . $m->status . '</span>'; ?>
													<tr>
														<td></td>
														<td width="50%"><a href="javascript:void(0);" onclick="view_manus(<?php echo $m->row_id; ?>);"
																class="text-dark text-decoration-none mb-1"><?php echo $title; ?></a>
															<?php if($status > 1 && $status != 99){ ?>
															</br>
															<span class="badge rounded-pill text-bg-secondary"><?php echo $issue;?></span>
															<span class="badge rounded-pill text-bg-secondary">Volume <?php echo $m->man_volume;?></span>
															<span class="badge rounded-pill text-bg-secondary"><?php echo $m->man_year;?></span>
															<?php } ?>
														</td>
														<td><?php echo $authors;?></td>
														<td><?php echo $author_type;?> - <?php echo $mem_type;?></td>
														<td><?php echo date_format(new DateTime($m->date_created), 'F j, Y, g:i a'); ?></td>
														<td><?php echo $status;?></td>
														<!-- <td class="text-center"><a href="javascript:void(0);" onclick="tracking('<?php echo $m->man_trk_no;?>',<?php echo $this->session->userdata('_oprs_type_num');?>,<?php echo $m->man_status ?>)"><?php echo $m->man_trk_no;?></a></td> -->
														<!-- <td class="text-center"><a href="javascript:void(0);" onclick="tracking('<?php echo $m->man_trk_no;?>',<?php echo $this->session->userdata('_oprs_type_num');?>,'<?php echo rawurlencode($title) ?>',<?php echo $m->man_status ?>)"><?php echo $m->man_trk_no;?></a></td> -->
														<td class="text-center"><a href="javascript:void(0);" onclick="tracking('<?php echo $m->row_id;?>')"><?php echo $m->man_trk_no;?></a></td>
														<td>
															<div class="btn-groupx d-flex gap-1" role="group">

																<!-- TECHNICAL DESK EDITOR -->
																<?php if (_UserRoleFromSession() == 5) { 
																	if($m->man_status == 1){ ?>
																		<!-- process manuscript -->
																		<button type="button" class="btn btn-outline-primary"
																			onclick="tech_rev_criterion(<?php echo $m->row_id; ?>,<?php echo $m->man_status; ?>)" rel="tooltip"
																			data-bs-placement="top" title="Process"><span
																				class="fas fa-gear"></span></button>
																	
																		<button type="button" class="btn btn-outline-danger"
																			rel="tooltip" data-bs-placement="top" title="Delete"
																			onclick="remove_manus('<?php echo $m->row_id; ?>')"><span
																				class="fa fa-trash"></span></button>

																		<?php }else if($m->man_status == 15){ ?>
																		<!-- process manuscript -->
																		<button type="button" class="btn btn-outline-success"
																			onclick="process_man(<?php echo $m->row_id; ?>,<?php echo $m->man_status; ?>)"
																			data-bs-toggle="modal" data-bs-target="#processModal" rel="tooltip"
																			data-bs-placement="top" title="Add Reviewers"><span
																				class="fas fa-user-plus"></span></button>
																		<?php }else if($m->man_status == 5){ ?>
																		<!-- view reviewers -->
																		<button type="button" class="btn border border-1 btn-light text-info"
																			onclick="view_reviewers('<?php echo $m->row_id; ?>','0','<?php echo rawurlencode($title); ?>','<?php echo $m->man_status; ?>')"
																			data-bs-toggle="modal" data-bs-target="#reviewerModal" rel="tooltip"
																			data-bs-placement="top" title="View Reviewers"><span
																				class="fas fa-users"></span></button>
																		<?php }else if($m->man_status == 6){ ?>
																			<button type="button" class="btn btn-outline-primary"
																			onclick="endorse_coped('<?php echo $m->row_id; ?>')"
																			data-bs-toggle="modal" data-bs-target="#checkRevisionModal" rel="tooltip"
																			data-bs-placement="top" title="Endorse to Copy Editor"><span
																				class="fas fa-gear"></span></button>
																		<?php } ?>
																	
																		<!-- view manuscript details -->
																		<button type="button" class="btn btn-outline-secondary" rel="tooltip"
																		data-bs-placement="top" title="View" onclick="view_manus(<?php echo $m->row_id; ?>);"><span class="fa fa-eye"></span></button>
																<?php } ?>

																<!-- SUPERADMIN/MANAGING EDITOR-->
																<?php if (_UserRoleFromSession() == 20 || _UserIdFromSession() == 3) { ?>
																	<?php if($m->man_status == 5){ ?>
																	<!-- view reviewers -->
																	<button type="button" class="btn btn-outline-info"
																		onclick="view_reviewers('<?php echo $m->row_id; ?>','0','<?php echo rawurlencode($title); ?>','<?php echo $m->man_status; ?>')"
																		data-bs-toggle="modal" data-bs-target="#reviewerModal" rel="tooltip"
																		data-bs-placement="top" title="View Reviewers"><span
																			class="fas fa-users"></span></button>
																	<?php } ?>
																	<!-- view manuscript details -->
																	<button type="button" class="btn btn-outline-secondary" rel="tooltip"
																		data-bs-placement="top" title="View" onclick="view_manus(<?php echo $m->row_id; ?>);"><span class="fa fa-eye"></span></button>
																	
																	<?php if($m->man_status == 1){ ?>
																		<button type="button" class="btn btn-outline-danger"
																			rel="tooltip" data-bs-placement="top" title="Delete"
																			onclick="remove_manus('<?php echo $m->row_id; ?>')"><span
																				class="fa fa-trash"></span></button>
																		<?php } ?>
																<?php } ?>
															</div>
														</td>
														<td><em><?php echo ($m->man_remarks == NULL) ? '-' : $m->man_remarks;?></em></td>
													</tr>
													<?php endforeach; ?>
												</tbody>
											</table>
										</div>
									</div>
									<div class="tab-pane fade" id="onreview-tab-pane" role="tabpanel" aria-labelledby="onreview-tab" tabindex="0">
										<div class="table-responsive">
											<table class="table table-hover" id="onreview-manuscript" width="100%" cellspacing="0">
												<thead>
													<tr>
														<th>#</th>
														<th>Title</th>
														<th>Author(s)</th>
														<th>Membership Status</th>
														<th>Date Submitted</th>
														<th>Status</th>
														<th>Tracking No.</th>
														<th>Actions</th>
														<th>Remarks</th>
														<th>Fraction of Process Turnaround</th>
													</tr>
												</thead>
												<tbody>
													<?php $c = 1;foreach ($man_onreview as $m): ?>
													<?php $role = $this->session->userdata('_oprs_type_num');?>
													<?php $mem_type = $this->Manuscript_model->check_member($m->man_author_user_id); ?>
													<?php $mem_type = ($mem_type == 3) ? 'Member' : 'Non-member'; ?>
													<?php $author_type = ($m->man_author_type == 1) ? 'Main Author' : 'Co-author'; ?>
													<?php $acoa = (empty($this->Coauthor_model->get_author_coauthors($m->row_id))) ? '' : ', ' . $this->Coauthor_model->get_author_coauthors($m->row_id);?>
													<?php $title = $m->man_title; ?>
													<?php $authors = $m->man_author . $acoa; ?>
													<?php $status = '<span class="badge rounded-pill bg-' . $m->status_class . '">' . $m->status . '</span>'; ?>
													<?php 
														$date1 = new DateTime($m->date_created);
														$date2 = new DateTime($m->man_process_date);

														$interval = $date1->diff($date2);
														$minutes = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;

														
														if ($minutes < 60) {
															$fraction = "< 1 hr";
														} elseif ($minutes < 1440) { // 1440 minutes in a day
															$hours = round($minutes / 60, 1);
															$fraction = $hours . " hrs";
														} else {
															$days = round($minutes / 1440, 1); // Convert to days with 1 decimal place
															$fraction = $days . " days";
														}
													?>
													<tr>
														<td></td>
														<td width="50%"><a href="javascript:void(0);" onclick="view_manus(<?php echo $m->row_id; ?>);"
																class="text-dark text-decoration-none mb-1"><?php echo $title; ?></a>
															<?php if($status > 1 && $status != 99){ ?>
															</br>
															<span class="badge rounded-pill text-bg-secondary"><?php echo $issue;?></span>
															<span class="badge rounded-pill text-bg-secondary">Volume <?php echo $m->man_volume;?></span>
															<span class="badge rounded-pill text-bg-secondary"><?php echo $m->man_year;?></span>
															<?php } ?>
														</td>
														<td><?php echo $authors;?></td>
														<td><?php echo $author_type;?> - <?php echo $mem_type;?></td>
														<td><?php echo date_format(new DateTime($m->date_created), 'F j, Y, g:i a'); ?></td>
														<td><?php echo $status;?></td>
														<!-- <td class="text-center"><a href="javascript:void(0);" onclick="tracking('<?php echo $m->man_trk_no;?>',<?php echo $this->session->userdata('_oprs_type_num');?>,<?php echo $m->man_status ?>)"><?php echo $m->man_trk_no;?></a></td> -->
														<!-- <td class="text-center"><a href="javascript:void(0);" onclick="tracking('<?php echo $m->man_trk_no;?>',<?php echo $this->session->userdata('_oprs_type_num');?>,'<?php echo rawurlencode($title) ?>',<?php echo $m->man_status ?>)"><?php echo $m->man_trk_no;?></a></td> -->
														<td class="text-center"><a href="javascript:void(0);" onclick="tracking('<?php echo $m->row_id;?>')"><?php echo $m->man_trk_no;?></a></td>
														<td>
															<div class="btn-groupx d-flex gap-1" role="group">

															<?php if (_UserRoleFromSession() == 5) { 
																	if($m->man_status == 1){ ?>
																		<!-- process manuscript -->
																		<button type="button" class="btn btn-outline-primary"
																			onclick="tech_rev_criterion(<?php echo $m->row_id; ?>,<?php echo $m->man_status; ?>)" rel="tooltip"
																			data-bs-placement="top" title="Process"><span
																				class="fas fa-gear"></span></button>
																	

																		<?php }else if($m->man_status == 15){ ?>
																		<!-- process manuscript -->
																		<button type="button" class="btn btn-outline-success"
																			onclick="process_man(<?php echo $m->row_id; ?>,<?php echo $m->man_status; ?>)"
																			data-bs-toggle="modal" data-bs-target="#processModal" rel="tooltip"
																			data-bs-placement="top" title="Add Reviewers"><span
																				class="fas fa-user-plus"></span></button>
																		<?php }else if($m->man_status == 5){ ?>
																		<!-- view reviewers -->
																		<button type="button" class="btn border border-1 btn-light text-info"
																			onclick="view_reviewers('<?php echo $m->row_id; ?>','0','<?php echo rawurlencode($title); ?>','<?php echo $m->man_status; ?>')"
																			data-bs-toggle="modal" data-bs-target="#reviewerModal" rel="tooltip"
																			data-bs-placement="top" title="View Reviewers"><span
																				class="fas fa-users"></span></button>
																		<!-- <button type="button" class="btn btn-light btn-sm"
																			onclick="view_reviews('<?php echo $m->row_id; ?>','<?php echo rawurlencode($title); ?>')"
																			data-bs-toggle="modal" data-bs-target="#reviewsModal" rel="tooltip" data-bs-placement="top"
																			title="View Reviews"><span class="fa fa-eye"></span> View Reviews
																		</button> -->
																		<?php }else if($m->man_status == 6){ ?>
																			<button type="button" class="btn btn-outline-primary"
																			onclick="endorse_coped('<?php echo $m->row_id; ?>')"
																			data-bs-toggle="modal" data-bs-target="#checkRevisionModal" rel="tooltip"
																			data-bs-placement="top" title="Endorse to Copy Editor"><span
																				class="fas fa-gear"></span></button>
																		<?php }else if($m->man_status == 8){ ?>
																			<!-- publish to ejournal -->
																			<!-- <button type="button" class="btn border border-1 btn-light text-success"
																			onclick="publish_to_ejournal('<?php echo $m->row_id; ?>')"
																			data-bs-toggle="modal" data-bs-target="#publishModal" rel="tooltip"
																			data-bs-placement="top" title="Publish to eJournal"><span
																				class="fas fa-paper-plane"></span></button> -->
																		<?php } ?>

																	
																		<!-- view manuscript details -->
																		<button type="button" class="btn btn-outline-secondary" rel="tooltip"
																		data-bs-placement="top" title="View" onclick="view_manus(<?php echo $m->row_id; ?>);"><span class="fa fa-eye"></span></button>
																<?php } ?>

																<!-- SUPERADMIN/MANAGING EDITOR-->
																<?php if (_UserRoleFromSession() == 20 || _UserRoleFromSession() == 3) { ?>
																	<!-- view reviewers -->
																	<!-- <button type="button" class="btn btn-outline-info"
																		onclick="view_reviewers('<?php echo $m->row_id; ?>','0','<?php echo rawurlencode($title); ?>','<?php echo $m->man_status; ?>')"
																		data-bs-toggle="modal" data-bs-target="#reviewerModal" rel="tooltip"
																		data-bs-placement="top" title="View Reviewers"><span
																			class="fas fa-users"></span></button> -->
																	<!-- view manuscript details -->
																	<button type="button" class="btn btn-outline-secondary" rel="tooltip"
																		data-bs-placement="top" title="View" onclick="view_manus(<?php echo $m->row_id; ?>);"><span class="fa fa-eye"></span></button>
																<?php } ?>
															</div>
														</td>
														<td><em><?php echo ($m->man_remarks == NULL) ? '-' : $m->man_remarks;?></em></td>
														<td><?= $m->man_process_date > 0 ? $fraction : 'N/a' ?></td>
													</tr>
													<?php endforeach; ?>
												</tbody>
											</table>
										</div>
									</div>
									<div class="tab-pane fade" id="peer-tab-pane" role="tabpanel" aria-labelledby="peer-tab" tabindex="0">
										<div class="table-responsive">
											<table class="table table-hover" id="peer-manuscript" width="100%" cellspacing="0">
												<thead>
													<tr>
														<th>#</th>
														<th>Title</th>
														<th>Author(s)</th>
														<th>Membership Status</th>
														<th>Date Submitted</th>
														<th>Status</th>
														<th>Tracking No.</th>
														<th>Actions</th>
														<th>Remarks</th>
														<th>Fraction of Process Turnaround</th>
													</tr>
												</thead>
												<tbody>
													<?php $c = 1;foreach ($man_peer as $m): ?>
													<?php $role = $this->session->userdata('_oprs_type_num');?>
													<?php $mem_type = $this->Manuscript_model->check_member($m->man_author_user_id); ?>
													<?php $mem_type = ($mem_type == 3) ? 'Member' : 'Non-member'; ?>
													<?php $author_type = ($m->man_author_type == 1) ? 'Main Author' : 'Co-author'; ?>
													<?php $acoa = (empty($this->Coauthor_model->get_author_coauthors($m->row_id))) ? '' : ', ' . $this->Coauthor_model->get_author_coauthors($m->row_id);?>
													<?php $title = $m->man_title; ?>
													<?php $authors = $m->man_author . $acoa; ?>
													<?php $status = '<span class="badge rounded-pill bg-' . $m->status_class . '">' . $m->status . '</span>'; ?>
													<?php 
														$date1 = new DateTime($m->date_created);
														$date2 = new DateTime($m->man_process_date);

														$interval = $date1->diff($date2);
														$minutes = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;

														
														if ($minutes < 60) {
															$fraction = "< 1 hr";
														} elseif ($minutes < 1440) { // 1440 minutes in a day
															$hours = round($minutes / 60, 1);
															$fraction = $hours . " hrs";
														} else {
															$days = round($minutes / 1440, 1); // Convert to days with 1 decimal place
															$fraction = $days . " days";
														}
													?>
													<tr>
														<td></td>
														<td width="50%"><a href="javascript:void(0);" onclick="view_manus(<?php echo $m->row_id; ?>);"
																class="text-dark text-decoration-none mb-1"><?php echo $title; ?></a>
															<?php if($status > 1 && $status != 99){ ?>
															</br>
															<span class="badge rounded-pill text-bg-secondary"><?php echo $issue;?></span>
															<span class="badge rounded-pill text-bg-secondary">Volume <?php echo $m->man_volume;?></span>
															<span class="badge rounded-pill text-bg-secondary"><?php echo $m->man_year;?></span>
															<?php } ?>
														</td>
														<td><?php echo $authors;?></td>
														<td><?php echo $author_type;?> - <?php echo $mem_type;?></td>
														<td><?php echo date_format(new DateTime($m->date_created), 'F j, Y, g:i a'); ?></td>
														<td><?php echo $status;?></td>
														<!-- <td class="text-center"><a href="javascript:void(0);" onclick="tracking('<?php echo $m->man_trk_no;?>',<?php echo $this->session->userdata('_oprs_type_num');?>,<?php echo $m->man_status ?>)"><?php echo $m->man_trk_no;?></a></td> -->
														<!-- <td class="text-center"><a href="javascript:void(0);" onclick="tracking('<?php echo $m->man_trk_no;?>',<?php echo $this->session->userdata('_oprs_type_num');?>,'<?php echo rawurlencode($title) ?>',<?php echo $m->man_status ?>)"><?php echo $m->man_trk_no;?></a></td> -->
														<td class="text-center"><a href="javascript:void(0);" onclick="tracking('<?php echo $m->row_id;?>')"><?php echo $m->man_trk_no;?></a></td>
														<td>
															<div class="btn-groupx d-flex gap-1" role="group">

																<!-- TECHNICAL DESK EDITOR -->
																<?php if (_UserRoleFromSession() == 5) { 
																	if($m->man_status == 1){ ?>
																		<!-- process manuscript -->
																		<button type="button" class="btn btn-outline-primary"
																			onclick="tech_rev_criterion(<?php echo $m->row_id; ?>,<?php echo $m->man_status; ?>)" rel="tooltip"
																			data-bs-placement="top" title="Process"><span
																				class="fas fa-gear"></span></button>
																	
																		<button type="button" class="btn btn-outline-danger"
																			rel="tooltip" data-bs-placement="top" title="Delete"
																			onclick="remove_manus('<?php echo $m->row_id; ?>')"><span
																				class="fa fa-trash"></span></button>

																		<?php }else if($m->man_status == 15){ ?>
																		<!-- process manuscript -->
																		<button type="button" class="btn btn-outline-success"
																			onclick="process_man(<?php echo $m->row_id; ?>,<?php echo $m->man_status; ?>)"
																			data-bs-toggle="modal" data-bs-target="#processModal" rel="tooltip"
																			data-bs-placement="top" title="Add Reviewers"><span
																				class="fas fa-user-plus"></span></button>
																		<?php }else if($m->man_status == 5){ ?>
																		<!-- view reviewers -->
																		<button type="button" class="btn border border-1 btn-light text-info"
																			onclick="view_reviewers('<?php echo $m->row_id; ?>','0','<?php echo rawurlencode($title); ?>','<?php echo $m->man_status; ?>')"
																			data-bs-toggle="modal" data-bs-target="#reviewerModal" rel="tooltip"
																			data-bs-placement="top" title="View Reviewers"><span
																				class="fas fa-users"></span></button>
																		<!-- <button type="button" class="btn btn-light btn-sm"
																			onclick="view_reviews('<?php echo $m->row_id; ?>','<?php echo rawurlencode($title); ?>')"
																			data-bs-toggle="modal" data-bs-target="#reviewsModal" rel="tooltip" data-bs-placement="top"
																			title="View Reviews"><span class="fa fa-eye"></span> View Reviews
																		</button> -->
																		<?php }else if($m->man_status == 6){ ?>
																			<button type="button" class="btn btn-outline-primary"
																			onclick="endorse_coped('<?php echo $m->row_id; ?>')"
																			data-bs-toggle="modal" data-bs-target="#checkRevisionModal" rel="tooltip"
																			data-bs-placement="top" title="Endorse to Copy Editor"><span
																				class="fas fa-gear"></span></button>
																		<?php } ?>

																	
																		<!-- view manuscript details -->
																		<button type="button" class="btn btn-outline-secondary" rel="tooltip"
																		data-bs-placement="top" title="View" onclick="view_manus(<?php echo $m->row_id; ?>);"><span class="fa fa-eye"></span></button>
																<?php } ?>

																<!-- SUPERADMIN -->
																<?php if (_UserRoleFromSession() == 20 || _UserRoleFromSession() == 3) { ?>
																	<?php if($m->man_status == 5){ ?>
																	<!-- view reviewers -->
																	<button type="button" class="btn btn-outline-info"
																		onclick="view_reviewers('<?php echo $m->row_id; ?>','0','<?php echo rawurlencode($title); ?>','<?php echo $m->man_status; ?>')"
																		data-bs-toggle="modal" data-bs-target="#reviewerModal" rel="tooltip"
																		data-bs-placement="top" title="View Reviewers"><span
																			class="fas fa-users"></span></button>
																	<?php } ?>
																	<!-- view manuscript details -->
																	<button type="button" class="btn btn-outline-secondary" rel="tooltip"
																		data-bs-placement="top" title="View" onclick="view_manus(<?php echo $m->row_id; ?>);"><span class="fa fa-eye"></span></button>
																<?php } ?>
															</div>
														</td>
														<td><em><?php echo ($m->man_remarks == NULL) ? '-' : $m->man_remarks;?></em></td>
														<td><?= $m->man_process_date > 0 ? $fraction : 'N/a' ?></td>
													</tr>
													<?php endforeach; ?>
												</tbody>
											</table>
										</div>
									</div>
									<div class="tab-pane fade" id="review-consolidated-tab-pane" role="tabpanel" aria-labelledby="review-consolidated-tab" tabindex="0">
										<div class="table-responsive">
											<table class="table table-hover" id="review-consolidated-manuscript" width="100%" cellspacing="0">
												<thead>
													<tr>
														<th>#</th>
														<th>Title</th>
														<th>Author(s)</th>
														<th>Membership Status</th>
														<th>Date Submitted</th>
														<th>Status</th>
														<th>Tracking No.</th>
														<th>Actions</th>
														<th>Remarks</th>
														<th>Fraction of Process Turnaround</th>
													</tr>
												</thead>
												<tbody>
													<?php $c = 1;foreach ($rev_cons as $m): ?>
													<?php $role = $this->session->userdata('_oprs_type_num');?>
													<?php $mem_type = $this->Manuscript_model->check_member($m->man_author_user_id); ?>
													<?php $mem_type = ($mem_type == 3) ? 'Member' : 'Non-member'; ?>
													<?php $author_type = ($m->man_author_type == 1) ? 'Main Author' : 'Co-author'; ?>
													<?php $acoa = (empty($this->Coauthor_model->get_author_coauthors($m->row_id))) ? '' : ', ' . $this->Coauthor_model->get_author_coauthors($m->row_id);?>
													<?php $title = $m->man_title; ?>
													<?php $authors = $m->man_author . $acoa; ?>
													<?php $status = '<span class="badge rounded-pill bg-' . $m->status_class . '">' . $m->status . '</span>'; ?>
													<?php 
														$date1 = new DateTime($m->date_created);
														$date2 = new DateTime($m->man_process_date);

														$interval = $date1->diff($date2);
														$minutes = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;

														
														if ($minutes < 60) {
															$fraction = "< 1 hr";
														} elseif ($minutes < 1440) { // 1440 minutes in a day
															$hours = round($minutes / 60, 1);
															$fraction = $hours . " hrs";
														} else {
															$days = round($minutes / 1440, 1); // Convert to days with 1 decimal place
															$fraction = $days . " days";
														}
													?>
													<tr>
														<td></td>
														<td width="50%"><a href="javascript:void(0);" onclick="view_manus(<?php echo $m->row_id; ?>);"
																class="text-dark text-decoration-none mb-1"><?php echo $title; ?></a>
															<?php if($status > 1 && $status != 99){ ?>
															</br>
															<span class="badge rounded-pill text-bg-secondary"><?php echo $issue;?></span>
															<span class="badge rounded-pill text-bg-secondary">Volume <?php echo $m->man_volume;?></span>
															<span class="badge rounded-pill text-bg-secondary"><?php echo $m->man_year;?></span>
															<?php } ?>
														</td>
														<td><?php echo $authors;?></td>
														<td><?php echo $author_type;?> - <?php echo $mem_type;?></td>
														<td><?php echo date_format(new DateTime($m->date_created), 'F j, Y, g:i a'); ?></td>
														<td><?php echo $status;?></td>
														<!-- <td class="text-center"><a href="javascript:void(0);" onclick="tracking('<?php echo $m->man_trk_no;?>',<?php echo $this->session->userdata('_oprs_type_num');?>,<?php echo $m->man_status ?>)"><?php echo $m->man_trk_no;?></a></td> -->
														<!-- <td class="text-center"><a href="javascript:void(0);" onclick="tracking('<?php echo $m->man_trk_no;?>',<?php echo $this->session->userdata('_oprs_type_num');?>,'<?php echo rawurlencode($title) ?>',<?php echo $m->man_status ?>)"><?php echo $m->man_trk_no;?></a></td> -->
														<td class="text-center"><a href="javascript:void(0);" onclick="tracking('<?php echo $m->row_id;?>')"><?php echo $m->man_trk_no;?></a></td>
														<td>
															<div class="btn-groupx d-flex gap-1" role="group">
																	<!-- view manuscript details -->
																	<button type="button" class="btn btn-outline-secondary" rel="tooltip"
																		data-bs-placement="top" title="View" onclick="view_manus(<?php echo $m->row_id; ?>);"><span class="fa fa-eye"></span></button>
															</div>
														</td>
														<td><em><?php echo ($m->man_remarks == NULL) ? '-' : $m->man_remarks;?></em></td>
														<td><?= $m->man_process_date > 0 ? $fraction : 'N/a' ?></td>
													</tr>
													<?php endforeach; ?>
												</tbody>
											</table>
										</div>
									</div>
									<div class="tab-pane fade" id="proofread-coped-tab-pane" role="tabpanel" aria-labelledby="proofread-coped-tab" tabindex="0">
										<div class="table-responsive">
											<table class="table table-hover" id="coped-manuscript" width="100%" cellspacing="0">
												<thead>
													<tr>
														<th>#</th>
														<th>Title</th>
														<th>Author(s)</th>
														<th>Membership Status</th>
														<th>Date Submitted</th>
														<th>Status</th>
														<th>Tracking No.</th>
														<th>Actions</th>
														<th>Remarks</th>
														<th>Fraction of Process Turnaround</th>
													</tr>
												</thead>
												<tbody>
													<?php $c = 1;foreach ($prf_cop as $m): ?>
													<?php $role = $this->session->userdata('_oprs_type_num');?>
													<?php $mem_type = $this->Manuscript_model->check_member($m->man_author_user_id); ?>
													<?php $mem_type = ($mem_type == 3) ? 'Member' : 'Non-member'; ?>
													<?php $author_type = ($m->man_author_type == 1) ? 'Main Author' : 'Co-author'; ?>
													<?php $acoa = (empty($this->Coauthor_model->get_author_coauthors($m->row_id))) ? '' : ', ' . $this->Coauthor_model->get_author_coauthors($m->row_id);?>
													<?php $title = $m->man_title; ?>
													<?php $authors = $m->man_author . $acoa; ?>
													<?php $status = '<span class="badge rounded-pill bg-' . $m->status_class . '">' . $m->status . '</span>'; ?>
													<?php 
														$date1 = new DateTime($m->date_created);
														$date2 = new DateTime($m->man_process_date);

														$interval = $date1->diff($date2);
														$minutes = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;

														
														if ($minutes < 60) {
															$fraction = "< 1 hr";
														} elseif ($minutes < 1440) { // 1440 minutes in a day
															$hours = round($minutes / 60, 1);
															$fraction = $hours . " hrs";
														} else {
															$days = round($minutes / 1440, 1); // Convert to days with 1 decimal place
															$fraction = $days . " days";
														}
													?>
													<tr>
														<td></td>
														<td width="50%"><a href="javascript:void(0);" onclick="view_manus(<?php echo $m->row_id; ?>);"
																class="text-dark text-decoration-none mb-1"><?php echo $title; ?></a>
															<?php if($status > 1 && $status != 99){ ?>
															</br>
															<span class="badge rounded-pill text-bg-secondary"><?php echo $issue;?></span>
															<span class="badge rounded-pill text-bg-secondary">Volume <?php echo $m->man_volume;?></span>
															<span class="badge rounded-pill text-bg-secondary"><?php echo $m->man_year;?></span>
															<?php } ?>
														</td>
														<td><?php echo $authors;?></td>
														<td><?php echo $author_type;?> - <?php echo $mem_type;?></td>
														<td><?php echo date_format(new DateTime($m->date_created), 'F j, Y, g:i a'); ?></td>
														<td><?php echo $status;?></td>
														<!-- <td class="text-center"><a href="javascript:void(0);" onclick="tracking('<?php echo $m->man_trk_no;?>',<?php echo $this->session->userdata('_oprs_type_num');?>,<?php echo $m->man_status ?>)"><?php echo $m->man_trk_no;?></a></td> -->
														<!-- <td class="text-center"><a href="javascript:void(0);" onclick="tracking('<?php echo $m->man_trk_no;?>',<?php echo $this->session->userdata('_oprs_type_num');?>,'<?php echo rawurlencode($title) ?>',<?php echo $m->man_status ?>)"><?php echo $m->man_trk_no;?></a></td> -->
														<td class="text-center"><a href="javascript:void(0);" onclick="tracking('<?php echo $m->row_id;?>')"><?php echo $m->man_trk_no;?></a></td>
														<td>
															<div class="btn-groupx d-flex gap-1" role="group">
																	<!-- view manuscript details -->
																	<button type="button" class="btn btn-outline-secondary" rel="tooltip"
																		data-bs-placement="top" title="View" onclick="view_manus(<?php echo $m->row_id; ?>);"><span class="fa fa-eye"></span></button>
															</div>
														</td>
														<td><em><?php echo ($m->man_remarks == NULL) ? '-' : $m->man_remarks;?></em></td>
														<td><?= $m->man_process_date > 0 ? $fraction : 'N/a' ?></td>
													</tr>
													<?php endforeach; ?>
												</tbody>
											</table>
										</div>
									</div>
									<div class="tab-pane fade" id="final-review-tab-pane" role="tabpanel" aria-labelledby="final-reviewd-tab" tabindex="0">
										<div class="table-responsive">
											<table class="table table-hover" id="fin-rev-manuscript" width="100%" cellspacing="0">
												<thead>
													<tr>
														<th>#</th>
														<th>Title</th>
														<th>Author(s)</th>
														<th>Membership Status</th>
														<th>Date Submitted</th>
														<th>Status</th>
														<th>Tracking No.</th>
														<th>Actions</th>
														<th>Remarks</th>
														<th>Fraction of Process Turnaround</th>
													</tr>
												</thead>
												<tbody>
													<?php $c = 1;foreach ($fin_rev as $m): ?>
													<?php $role = $this->session->userdata('_oprs_type_num');?>
													<?php $mem_type = $this->Manuscript_model->check_member($m->man_author_user_id); ?>
													<?php $mem_type = ($mem_type == 3) ? 'Member' : 'Non-member'; ?>
													<?php $author_type = ($m->man_author_type == 1) ? 'Main Author' : 'Co-author'; ?>
													<?php $acoa = (empty($this->Coauthor_model->get_author_coauthors($m->row_id))) ? '' : ', ' . $this->Coauthor_model->get_author_coauthors($m->row_id);?>
													<?php $title = $m->man_title; ?>
													<?php $authors = $m->man_author . $acoa; ?>
													<?php $status = '<span class="badge rounded-pill bg-' . $m->status_class . '">' . $m->status . '</span>'; ?>
													<?php 
														$date1 = new DateTime($m->date_created);
														$date2 = new DateTime($m->man_process_date);

														$interval = $date1->diff($date2);
														$minutes = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;

														
														if ($minutes < 60) {
															$fraction = "< 1 hr";
														} elseif ($minutes < 1440) { // 1440 minutes in a day
															$hours = round($minutes / 60, 1);
															$fraction = $hours . " hrs";
														} else {
															$days = round($minutes / 1440, 1); // Convert to days with 1 decimal place
															$fraction = $days . " days";
														}
													?>
													<tr>
														<td></td>
														<td width="50%"><a href="javascript:void(0);" onclick="view_manus(<?php echo $m->row_id; ?>);"
																class="text-dark text-decoration-none mb-1"><?php echo $title; ?></a>
															<?php if($status > 1 && $status != 99){ ?>
															</br>
															<span class="badge rounded-pill text-bg-secondary"><?php echo $issue;?></span>
															<span class="badge rounded-pill text-bg-secondary">Volume <?php echo $m->man_volume;?></span>
															<span class="badge rounded-pill text-bg-secondary"><?php echo $m->man_year;?></span>
															<?php } ?>
														</td>
														<td><?php echo $authors;?></td>
														<td><?php echo $author_type;?> - <?php echo $mem_type;?></td>
														<td><?php echo date_format(new DateTime($m->date_created), 'F j, Y, g:i a'); ?></td>
														<td><?php echo $status;?></td>
														<!-- <td class="text-center"><a href="javascript:void(0);" onclick="tracking('<?php echo $m->man_trk_no;?>',<?php echo $this->session->userdata('_oprs_type_num');?>,<?php echo $m->man_status ?>)"><?php echo $m->man_trk_no;?></a></td> -->
														<!-- <td class="text-center"><a href="javascript:void(0);" onclick="tracking('<?php echo $m->man_trk_no;?>',<?php echo $this->session->userdata('_oprs_type_num');?>,'<?php echo rawurlencode($title) ?>',<?php echo $m->man_status ?>)"><?php echo $m->man_trk_no;?></a></td> -->
														<td class="text-center"><a href="javascript:void(0);" onclick="tracking('<?php echo $m->row_id;?>')"><?php echo $m->man_trk_no;?></a></td>
														<td>
															<div class="btn-groupx d-flex gap-1" role="group">
																	<!-- view manuscript details -->
																	<button type="button" class="btn btn-outline-secondary" rel="tooltip"
																		data-bs-placement="top" title="View" onclick="view_manus(<?php echo $m->row_id; ?>);"><span class="fa fa-eye"></span></button>
															</div>
														</td>
														<td><em><?php echo ($m->man_remarks == NULL) ? '-' : $m->man_remarks;?></em></td>
														<td><?= $m->man_process_date > 0 ? $fraction : 'N/a' ?></td>
													</tr>
													<?php endforeach; ?>
												</tbody>
											</table>
										</div>
									</div>
									<div class="tab-pane fade" id="proofread-author-tab-pane" role="tabpanel" aria-labelledby="proofread-author-tab" tabindex="0">
										<div class="table-responsive">
											<table class="table table-hover" id="prf-auth-manuscript" width="100%" cellspacing="0">
												<thead>
													<tr>
														<th>#</th>
														<th>Title</th>
														<th>Author(s)</th>
														<th>Membership Status</th>
														<th>Date Submitted</th>
														<th>Status</th>
														<th>Tracking No.</th>
														<th>Actions</th>
														<th>Remarks</th>
														<th>Fraction of Process Turnaround</th>
													</tr>
												</thead>
												<tbody>
													<?php $c = 1;foreach ($prf_auth as $m): ?>
													<?php $role = $this->session->userdata('_oprs_type_num');?>
													<?php $mem_type = $this->Manuscript_model->check_member($m->man_author_user_id); ?>
													<?php $mem_type = ($mem_type == 3) ? 'Member' : 'Non-member'; ?>
													<?php $author_type = ($m->man_author_type == 1) ? 'Main Author' : 'Co-author'; ?>
													<?php $acoa = (empty($this->Coauthor_model->get_author_coauthors($m->row_id))) ? '' : ', ' . $this->Coauthor_model->get_author_coauthors($m->row_id);?>
													<?php $title = $m->man_title; ?>
													<?php $authors = $m->man_author . $acoa; ?>
													<?php $status = '<span class="badge rounded-pill bg-' . $m->status_class . '">' . $m->status . '</span>'; ?>
													<?php 
														$date1 = new DateTime($m->date_created);
														$date2 = new DateTime($m->man_process_date);

														$interval = $date1->diff($date2);
														$minutes = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;

														
														if ($minutes < 60) {
															$fraction = "< 1 hr";
														} elseif ($minutes < 1440) { // 1440 minutes in a day
															$hours = round($minutes / 60, 1);
															$fraction = $hours . " hrs";
														} else {
															$days = round($minutes / 1440, 1); // Convert to days with 1 decimal place
															$fraction = $days . " days";
														}
													?>
													<tr>
														<td></td>
														<td width="50%"><a href="javascript:void(0);" onclick="view_manus(<?php echo $m->row_id; ?>);"
																class="text-dark text-decoration-none mb-1"><?php echo $title; ?></a>
															<?php if($status > 1 && $status != 99){ ?>
															</br>
															<span class="badge rounded-pill text-bg-secondary"><?php echo $issue;?></span>
															<span class="badge rounded-pill text-bg-secondary">Volume <?php echo $m->man_volume;?></span>
															<span class="badge rounded-pill text-bg-secondary"><?php echo $m->man_year;?></span>
															<?php } ?>
														</td>
														<td><?php echo $authors;?></td>
														<td><?php echo $author_type;?> - <?php echo $mem_type;?></td>
														<td><?php echo date_format(new DateTime($m->date_created), 'F j, Y, g:i a'); ?></td>
														<td><?php echo $status;?></td>
														<!-- <td class="text-center"><a href="javascript:void(0);" onclick="tracking('<?php echo $m->man_trk_no;?>',<?php echo $this->session->userdata('_oprs_type_num');?>,<?php echo $m->man_status ?>)"><?php echo $m->man_trk_no;?></a></td> -->
														<!-- <td class="text-center"><a href="javascript:void(0);" onclick="tracking('<?php echo $m->man_trk_no;?>',<?php echo $this->session->userdata('_oprs_type_num');?>,'<?php echo rawurlencode($title) ?>',<?php echo $m->man_status ?>)"><?php echo $m->man_trk_no;?></a></td> -->
														<td class="text-center"><a href="javascript:void(0);" onclick="tracking('<?php echo $m->row_id;?>')"><?php echo $m->man_trk_no;?></a></td>
														<td>
															<div class="btn-groupx d-flex gap-1" role="group">
																	<!-- view manuscript details -->
																	<button type="button" class="btn btn-outline-secondary" rel="tooltip"
																		data-bs-placement="top" title="View" onclick="view_manus(<?php echo $m->row_id; ?>);"><span class="fa fa-eye"></span></button>
															</div>
														</td>
														<td><em><?php echo ($m->man_remarks == NULL) ? '-' : $m->man_remarks;?></em></td>
														<td><?= $m->man_process_date > 0 ? $fraction : 'N/a' ?></td>
													</tr>
													<?php endforeach; ?>
												</tbody>
											</table>
										</div>
									</div>
									<div class="tab-pane fade" id="revision-tab-pane" role="tabpanel" aria-labelledby="revision-tab" tabindex="0">
										<div class="table-responsive">
											<table class="table table-hover" id="revision-manuscript" width="100%" cellspacing="0">
												<thead>
													<tr>
														<th>#</th>
														<th>Title</th>
														<th>Author(s)</th>
														<th>Membership Status</th>
														<th>Date Submitted</th>
														<th>Status</th>
														<th>Tracking No.</th>
														<th>Actions</th>
														<th>Remarks</th>
														<th>Fraction of Process Turnaround</th>
													</tr>
												</thead>
												<tbody>
													<?php $c = 1;foreach ($rev_auth as $m): ?>
													<?php $role = $this->session->userdata('_oprs_type_num');?>
													<?php $mem_type = $this->Manuscript_model->check_member($m->man_author_user_id); ?>
													<?php $mem_type = ($mem_type == 3) ? 'Member' : 'Non-member'; ?>
													<?php $author_type = ($m->man_author_type == 1) ? 'Main Author' : 'Co-author'; ?>
													<?php $acoa = (empty($this->Coauthor_model->get_author_coauthors($m->row_id))) ? '' : ', ' . $this->Coauthor_model->get_author_coauthors($m->row_id);?>
													<?php $title = $m->man_title; ?>
													<?php $authors = $m->man_author . $acoa; ?>
													<?php $status = '<span class="badge rounded-pill bg-' . $m->status_class . '">' . $m->status . '</span>'; ?>
													<?php 
														$date1 = new DateTime($m->date_created);
														$date2 = new DateTime($m->man_process_date);

														$interval = $date1->diff($date2);
														$minutes = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;

														
														if ($minutes < 60) {
															$fraction = "< 1 hr";
														} elseif ($minutes < 1440) { // 1440 minutes in a day
															$hours = round($minutes / 60, 1);
															$fraction = $hours . " hrs";
														} else {
															$days = round($minutes / 1440, 1); // Convert to days with 1 decimal place
															$fraction = $days . " days";
														}
													?>
													<tr>
														<td></td>
														<td width="50%"><a href="javascript:void(0);" onclick="view_manus(<?php echo $m->row_id; ?>);"
																class="text-dark text-decoration-none mb-1"><?php echo $title; ?></a>
															<?php if($status > 1 && $status != 99){ ?>
															</br>
															<span class="badge rounded-pill text-bg-secondary"><?php echo $issue;?></span>
															<span class="badge rounded-pill text-bg-secondary">Volume <?php echo $m->man_volume;?></span>
															<span class="badge rounded-pill text-bg-secondary"><?php echo $m->man_year;?></span>
															<?php } ?>
														</td>
														<td><?php echo $authors;?></td>
														<td><?php echo $author_type;?> - <?php echo $mem_type;?></td>
														<td><?php echo date_format(new DateTime($m->date_created), 'F j, Y, g:i a'); ?></td>
														<td><?php echo $status;?></td>
														<!-- <td class="text-center"><a href="javascript:void(0);" onclick="tracking('<?php echo $m->man_trk_no;?>',<?php echo $this->session->userdata('_oprs_type_num');?>,<?php echo $m->man_status ?>)"><?php echo $m->man_trk_no;?></a></td> -->
														<!-- <td class="text-center"><a href="javascript:void(0);" onclick="tracking('<?php echo $m->man_trk_no;?>',<?php echo $this->session->userdata('_oprs_type_num');?>,'<?php echo rawurlencode($title) ?>',<?php echo $m->man_status ?>)"><?php echo $m->man_trk_no;?></a></td> -->
														<td class="text-center"><a href="javascript:void(0);" onclick="tracking('<?php echo $m->row_id;?>')"><?php echo $m->man_trk_no;?></a></td>
														<td>
															<div class="btn-groupx d-flex gap-1" role="group">
																
																<!-- TECHNICAL DESK EDITOR -->
																<?php if (_UserRoleFromSession() == 5) { 
																	if($m->man_status == 17){ ?>
																		<!-- process manuscript -->
																		<button type="button" class="btn btn-outline-primary"
																			onclick="submit_revision_consolidation(<?php echo $m->row_id; ?>)" rel="tooltip"
																			data-bs-placement="top" title=""><span
																				class="fas fa-gear"></span></button>

																	<?php } ?>
																<?php } ?>

																	<!-- view manuscript details -->
																	<button type="button" class="btn btn-outline-secondary" rel="tooltip"
																		data-bs-placement="top" title="View" onclick="view_manus(<?php echo $m->row_id; ?>);"><span class="fa fa-eye"></span></button>
															</div>
														</td>
														<td><em><?php echo ($m->man_remarks == NULL) ? '-' : $m->man_remarks;?></em></td>
														<td><?= $m->man_process_date > 0 ? $fraction : 'N/a' ?></td>
													</tr>
													<?php endforeach; ?>
												</tbody>
											</table>
										</div>
									</div>
									<div class="tab-pane fade" id="layout-tab-pane" role="tabpanel" aria-labelledby="layout-tab" tabindex="0">
										<div class="table-responsive">
											<table class="table table-hover" id="layout-manuscript" width="100%" cellspacing="0">
												<thead>
													<tr>
														<th>#</th>
														<th>Title</th>
														<th>Author(s)</th>
														<th>Membership Status</th>
														<th>Date Submitted</th>
														<th>Status</th>
														<th>Tracking No.</th>
														<th>Actions</th>
														<th>Remarks</th>
														<th>Fraction of Process Turnaround</th>
													</tr>
												</thead>
												<tbody>
													<?php $c = 1;foreach ($lay_art as $m): ?>
													<?php $role = $this->session->userdata('_oprs_type_num');?>
													<?php $mem_type = $this->Manuscript_model->check_member($m->man_author_user_id); ?>
													<?php $mem_type = ($mem_type == 3) ? 'Member' : 'Non-member'; ?>
													<?php $author_type = ($m->man_author_type == 1) ? 'Main Author' : 'Co-author'; ?>
													<?php $acoa = (empty($this->Coauthor_model->get_author_coauthors($m->row_id))) ? '' : ', ' . $this->Coauthor_model->get_author_coauthors($m->row_id);?>
													<?php $title = $m->man_title; ?>
													<?php $authors = $m->man_author . $acoa; ?>
													<?php $status = '<span class="badge rounded-pill bg-' . $m->status_class . '">' . $m->status . '</span>'; ?>
													<?php 
														$date1 = new DateTime($m->date_created);
														$date2 = new DateTime($m->man_process_date);

														$interval = $date1->diff($date2);
														$minutes = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;

														
														if ($minutes < 60) {
															$fraction = "< 1 hr";
														} elseif ($minutes < 1440) { // 1440 minutes in a day
															$hours = round($minutes / 60, 1);
															$fraction = $hours . " hrs";
														} else {
															$days = round($minutes / 1440, 1); // Convert to days with 1 decimal place
															$fraction = $days . " days";
														}
													?>
													<tr>
														<td></td>
														<td width="50%"><a href="javascript:void(0);" onclick="view_manus(<?php echo $m->row_id; ?>);"
																class="text-dark text-decoration-none mb-1"><?php echo $title; ?></a>
															<?php if($status > 1 && $status != 99){ ?>
															</br>
															<span class="badge rounded-pill text-bg-secondary"><?php echo $issue;?></span>
															<span class="badge rounded-pill text-bg-secondary">Volume <?php echo $m->man_volume;?></span>
															<span class="badge rounded-pill text-bg-secondary"><?php echo $m->man_year;?></span>
															<?php } ?>
														</td>
														<td><?php echo $authors;?></td>
														<td><?php echo $author_type;?> - <?php echo $mem_type;?></td>
														<td><?php echo date_format(new DateTime($m->date_created), 'F j, Y, g:i a'); ?></td>
														<td><?php echo $status;?></td>
														<!-- <td class="text-center"><a href="javascript:void(0);" onclick="tracking('<?php echo $m->man_trk_no;?>',<?php echo $this->session->userdata('_oprs_type_num');?>,<?php echo $m->man_status ?>)"><?php echo $m->man_trk_no;?></a></td> -->
														<!-- <td class="text-center"><a href="javascript:void(0);" onclick="tracking('<?php echo $m->man_trk_no;?>',<?php echo $this->session->userdata('_oprs_type_num');?>,'<?php echo rawurlencode($title) ?>',<?php echo $m->man_status ?>)"><?php echo $m->man_trk_no;?></a></td> -->
														<td class="text-center"><a href="javascript:void(0);" onclick="tracking('<?php echo $m->row_id;?>')"><?php echo $m->man_trk_no;?></a></td>
														<td>
															<div class="btn-groupx d-flex gap-1" role="group">
																	<!-- view manuscript details -->
																	<button type="button" class="btn btn-outline-secondary" rel="tooltip"
																		data-bs-placement="top" title="View" onclick="view_manus(<?php echo $m->row_id; ?>);"><span class="fa fa-eye"></span></button>
															</div>
														</td>
														<td><em><?php echo ($m->man_remarks == NULL) ? '-' : $m->man_remarks;?></em></td>
														<td><?= $m->man_process_date > 0 ? $fraction : 'N/a' ?></td>
													</tr>
													<?php endforeach; ?>
												</tbody>
											</table>
										</div>
									</div>
									<div class="tab-pane fade" id="final-approval-tab-pane" role="tabpanel" aria-labelledby="final-approval-tab" tabindex="0">
										<div class="table-responsive">
											<table class="table table-hover" id="fin-app-manuscript" width="100%" cellspacing="0">
												<thead>
													<tr>
														<th>#</th>
														<th>Title</th>
														<th>Author(s)</th>
														<th>Membership Status</th>
														<th>Date Submitted</th>
														<th>Status</th>
														<th>Tracking No.</th>
														<th>Actions</th>
														<th>Remarks</th>
														<th>Fraction of Process Turnaround</th>
													</tr>
												</thead>
												<tbody>
													<?php $c = 1;foreach ($fin_app as $m): ?>
													<?php $role = $this->session->userdata('_oprs_type_num');?>
													<?php $mem_type = $this->Manuscript_model->check_member($m->man_author_user_id); ?>
													<?php $mem_type = ($mem_type == 3) ? 'Member' : 'Non-member'; ?>
													<?php $author_type = ($m->man_author_type == 1) ? 'Main Author' : 'Co-author'; ?>
													<?php $acoa = (empty($this->Coauthor_model->get_author_coauthors($m->row_id))) ? '' : ', ' . $this->Coauthor_model->get_author_coauthors($m->row_id);?>
													<?php $title = $m->man_title; ?>
													<?php $authors = $m->man_author . $acoa; ?>
													<?php $status = '<span class="badge rounded-pill bg-' . $m->status_class . '">' . $m->status . '</span>'; ?>
													<?php 
														$date1 = new DateTime($m->date_created);
														$date2 = new DateTime($m->man_process_date);

														$interval = $date1->diff($date2);
														$minutes = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;

														
														if ($minutes < 60) {
															$fraction = "< 1 hr";
														} elseif ($minutes < 1440) { // 1440 minutes in a day
															$hours = round($minutes / 60, 1);
															$fraction = $hours . " hrs";
														} else {
															$days = round($minutes / 1440, 1); // Convert to days with 1 decimal place
															$fraction = $days . " days";
														}
													?>
													<tr>
														<td></td>
														<td width="50%"><a href="javascript:void(0);" onclick="view_manus(<?php echo $m->row_id; ?>);"
																class="text-dark text-decoration-none mb-1"><?php echo $title; ?></a>
															<?php if($status > 1 && $status != 99){ ?>
															</br>
															<span class="badge rounded-pill text-bg-secondary"><?php echo $issue;?></span>
															<span class="badge rounded-pill text-bg-secondary">Volume <?php echo $m->man_volume;?></span>
															<span class="badge rounded-pill text-bg-secondary"><?php echo $m->man_year;?></span>
															<?php } ?>
														</td>
														<td><?php echo $authors;?></td>
														<td><?php echo $author_type;?> - <?php echo $mem_type;?></td>
														<td><?php echo date_format(new DateTime($m->date_created), 'F j, Y, g:i a'); ?></td>
														<td><?php echo $status;?></td>
														<!-- <td class="text-center"><a href="javascript:void(0);" onclick="tracking('<?php echo $m->man_trk_no;?>',<?php echo $this->session->userdata('_oprs_type_num');?>,<?php echo $m->man_status ?>)"><?php echo $m->man_trk_no;?></a></td> -->
														<!-- <td class="text-center"><a href="javascript:void(0);" onclick="tracking('<?php echo $m->man_trk_no;?>',<?php echo $this->session->userdata('_oprs_type_num');?>,'<?php echo rawurlencode($title) ?>',<?php echo $m->man_status ?>)"><?php echo $m->man_trk_no;?></a></td> -->
														<td class="text-center"><a href="javascript:void(0);" onclick="tracking('<?php echo $m->row_id;?>')"><?php echo $m->man_trk_no;?></a></td>
														<td>
															<div class="btn-groupx d-flex gap-1" role="group">
																	<!-- view manuscript details -->
																	<button type="button" class="btn btn-outline-secondary" rel="tooltip"
																		data-bs-placement="top" title="View" onclick="view_manus(<?php echo $m->row_id; ?>);"><span class="fa fa-eye"></span></button>
															</div>
														</td>
														<td><em><?php echo ($m->man_remarks == NULL) ? '-' : $m->man_remarks;?></em></td>
														<td><?= $m->man_process_date > 0 ? $fraction : 'N/a' ?></td>
													</tr>
													<?php endforeach; ?>
												</tbody>
											</table>
										</div>
									</div>
									<div class="tab-pane fade" id="publication-tab-pane" role="tabpanel" aria-labelledby="publication-tab" tabindex="0">
										<div class="table-responsive">
											<table class="table table-hover" id="published-manuscript" width="100%" cellspacing="0">
												<thead>
													<tr>
														<th>#</th>
														<th>Title</th>
														<th>Author(s)</th>
														<th>Membership Status</th>
														<th>Date Submitted</th>
														<th>Status</th>
														<th>Tracking No.</th>
														<th>Actions</th>
														<th>Remarks</th>
														<th>Fraction of Process Turnaround</th>
													</tr>
												</thead>
												<tbody>
													<?php $c = 1;foreach ($publ as $m): ?>
													<?php $role = $this->session->userdata('_oprs_type_num');?>
													<?php $mem_type = $this->Manuscript_model->check_member($m->man_author_user_id); ?>
													<?php $mem_type = ($mem_type == 3) ? 'Member' : 'Non-member'; ?>
													<?php $author_type = ($m->man_author_type == 1) ? 'Main Author' : 'Co-author'; ?>
													<?php $acoa = (empty($this->Coauthor_model->get_author_coauthors($m->row_id))) ? '' : ', ' . $this->Coauthor_model->get_author_coauthors($m->row_id);?>
													<?php $title = $m->man_title; ?>
													<?php $authors = $m->man_author . $acoa; ?>
													<?php $status = '<span class="badge rounded-pill bg-' . $m->status_class . '">' . $m->status . '</span>'; ?>
													<?php 
														$date1 = new DateTime($m->date_created);
														$date2 = new DateTime($m->man_process_date);

														$interval = $date1->diff($date2);
														$minutes = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;

														
														if ($minutes < 60) {
															$fraction = "< 1 hr";
														} elseif ($minutes < 1440) { // 1440 minutes in a day
															$hours = round($minutes / 60, 1);
															$fraction = $hours . " hrs";
														} else {
															$days = round($minutes / 1440, 1); // Convert to days with 1 decimal place
															$fraction = $days . " days";
														}
													?>
													<tr>
														<td></td>
														<td width="50%"><a href="javascript:void(0);" onclick="view_manus(<?php echo $m->row_id; ?>);"
																class="text-dark text-decoration-none mb-1"><?php echo $title; ?></a>
															<?php if($status > 1 && $status != 99){ ?>
															</br>
															<span class="badge rounded-pill text-bg-secondary"><?php echo $issue;?></span>
															<span class="badge rounded-pill text-bg-secondary">Volume <?php echo $m->man_volume;?></span>
															<span class="badge rounded-pill text-bg-secondary"><?php echo $m->man_year;?></span>
															<?php } ?>
														</td>
														<td><?php echo $authors;?></td>
														<td><?php echo $author_type;?> - <?php echo $mem_type;?></td>
														<td><?php echo date_format(new DateTime($m->date_created), 'F j, Y, g:i a'); ?></td>
														<td><?php echo $status;?></td>
														<!-- <td class="text-center"><a href="javascript:void(0);" onclick="tracking('<?php echo $m->man_trk_no;?>',<?php echo $this->session->userdata('_oprs_type_num');?>,<?php echo $m->man_status ?>)"><?php echo $m->man_trk_no;?></a></td> -->
														<!-- <td class="text-center"><a href="javascript:void(0);" onclick="tracking('<?php echo $m->man_trk_no;?>',<?php echo $this->session->userdata('_oprs_type_num');?>,'<?php echo rawurlencode($title) ?>',<?php echo $m->man_status ?>)"><?php echo $m->man_trk_no;?></a></td> -->
														<td class="text-center"><a href="javascript:void(0);" onclick="tracking('<?php echo $m->row_id;?>')"><?php echo $m->man_trk_no;?></a></td>
														<td>
															<div class="btn-groupx d-flex gap-1" role="group">
																	<!-- view manuscript details -->
																	<button type="button" class="btn btn-outline-secondary" rel="tooltip"
																		data-bs-placement="top" title="View" onclick="view_manus(<?php echo $m->row_id; ?>);"><span class="fa fa-eye"></span></button>
															</div>
														</td>
														<td><em><?php echo ($m->man_remarks == NULL) ? '-' : $m->man_remarks;?></em></td>
														<td><?= $m->man_process_date > 0 ? $fraction : 'N/a' ?></td>
													</tr>
													<?php endforeach; ?>
												</tbody>
											</table>
										</div>
									</div>
									<div class="tab-pane fade" id="rejected-tab-pane" role="tabpanel" aria-labelledby="rejected-tab" tabindex="0">
										<div class="table-responsive">
											<table class="table table-hover" id="rejected-manuscript" width="100%" cellspacing="0">
												<thead>
													<tr>
														<th>#</th>
														<th>Title</th>
														<th>Author(s)</th>
														<th>Membership Status</th>
														<th>Date Submitted</th>
														<th>Status</th>
														<th>Tracking No.</th>
														<th>Actions</th>
														<th>Remarks</th>
														<th>Fraction of Process Turnaround</th>
													</tr>
												</thead>
												<tbody>
													<?php $c = 1;foreach ($man_rej as $m): ?>
													<?php $role = $this->session->userdata('_oprs_type_num');?>
													<?php $mem_type = $this->Manuscript_model->check_member($m->man_author_user_id); ?>
													<?php $mem_type = ($mem_type == 3) ? 'Member' : 'Non-member'; ?>
													<?php $author_type = ($m->man_author_type == 1) ? 'Main Author' : 'Co-author'; ?>
													<?php $acoa = (empty($this->Coauthor_model->get_author_coauthors($m->row_id))) ? '' : ', ' . $this->Coauthor_model->get_author_coauthors($m->row_id);?>
													<?php $title = $m->man_title; ?>
													<?php $authors = $m->man_author . $acoa; ?>
													<?php $status = '<span class="badge rounded-pill bg-' . $m->status_class . '">' . $m->status . '</span>'; ?>
													<?php 
														$date1 = new DateTime($m->date_created);
														$date2 = new DateTime($m->man_process_date);

														$interval = $date1->diff($date2);
														$minutes = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;

														
														if ($minutes < 60) {
															$fraction = "< 1 hr";
														} elseif ($minutes < 1440) { // 1440 minutes in a day
															$hours = round($minutes / 60, 1);
															$fraction = $hours . " hrs";
														} else {
															$days = round($minutes / 1440, 1); // Convert to days with 1 decimal place
															$fraction = $days . " days";
														}
													?>
													
													<tr>
														<td></td>
														<td width="50%"><a href="javascript:void(0);" onclick="view_manus(<?php echo $m->row_id; ?>);"
																class="text-dark text-decoration-none mb-1"><?php echo $title; ?></a>
															<?php if($status > 1 && $status != 99){ ?>
															</br>
															<span class="badge rounded-pill text-bg-secondary"><?php echo $issue;?></span>
															<span class="badge rounded-pill text-bg-secondary">Volume <?php echo $m->man_volume;?></span>
															<span class="badge rounded-pill text-bg-secondary"><?php echo $m->man_year;?></span>
															<?php } ?>
														</td>
														<td><?php echo $authors;?></td>
														<td><?php echo $author_type;?> - <?php echo $mem_type;?></td>
														<td><?php echo date_format(new DateTime($m->date_created), 'F j, Y, g:i a'); ?></td>
														<td><?php echo $status;?></td>
														<!-- <td class="text-center"><a href="javascript:void(0);" onclick="tracking('<?php echo $m->man_trk_no;?>',<?php echo $this->session->userdata('_oprs_type_num');?>,<?php echo $m->man_status ?>)"><?php echo $m->man_trk_no;?></a></td> -->
														<!-- <td class="text-center"><a href="javascript:void(0);" onclick="tracking('<?php echo $m->man_trk_no;?>',<?php echo $this->session->userdata('_oprs_type_num');?>,'<?php echo rawurlencode($title) ?>',<?php echo $m->man_status ?>)"><?php echo $m->man_trk_no;?></a></td> -->
														<td class="text-center"><a href="javascript:void(0);" onclick="tracking('<?php echo $m->row_id;?>')"><?php echo $m->man_trk_no;?></a></td>
														<td>
															<div class="btn-groupx d-flex gap-1" role="group">
																	<!-- view manuscript details -->
																	<button type="button" class="btn btn-outline-secondary" rel="tooltip"
																		data-bs-placement="top" title="View" onclick="view_manus(<?php echo $m->row_id; ?>);"><span class="fa fa-eye"></span></button>
															</div>
														</td>
														<td><em><?php echo ($m->man_remarks == NULL) ? '-' : $m->man_remarks;?></em></td>
														<td><?= $m->man_process_date > 0 ? $fraction : 'N/a' ?></td>
												
													</tr>
													<?php endforeach; ?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							<?php } else if(_UserRoleFromSession() == 16) { ?> 
							<!-- PEER REVIEWER -->
							<table class="table table-hover" id="dataTable_rev" width="100%" cellspacing="0">
								<thead>
									<tr>
										<th>#</th>
										<th>Title</th>
										<th>Date Submitted</th>
										<th>Date Reviewed</th>
										<th>Status</th>
										<th width="10%">Action</th>
										<th width="25%">Upload NDA</th>
									</tr>
								</thead>
								<tbody>
									<?php $c = 1;foreach ($man_all as $m): ?>
									<?php $drev = ($m->date_reviewed == null) ? '-' : $m->date_reviewed?>
									<?php $mantitle = rawurlencode($m->man_title); ?>
									<?php $action = ($m->scr_status == 4) ? '<span class="badge rounded-pill bg-success">PASSED</span>' : (($m->scr_status == 7) ? '<span class="badge rounded-pill bg-danger">FAILED</span>' : '<span class="badge rounded-pill bg-secondary">PENDING</span>');?>

											
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
											<?php echo $m->man_title; ?>
										</td>
										<td><?php echo date_format(new DateTime($m->date_created), 'F j, Y, g:i a'); ?></td>
										<td><?php echo $drev; ?></td>
										<td><?php echo $action;?></td>
										<td>
										<?php if($m->scr_status == 2){ ?>
											<button type="button" class="btn btn-outline-success"  data-bs-toggle="modal" rel="tooltip" data-bs-placement="top" title="Start Review" data-bs-target="#startReviewModal" onclick="start_review('<?php echo $m->man_file;?>','<?php echo $m->row_id; ?>','<?php echo $mantitle; ?>','<?php echo $m->man_author; ?>','<?php echo $m->rev_hide_auth; ?>')"><span class="fa fa-play-circle" ></span></button>
										<?php } ?>
											<button type="button" class="btn btn-outline-secondary" rel="tooltip"
											data-bs-placement="top" title="View" onclick="view_manus(<?php echo $m->row_id; ?>)"><span class="fa fa-eye"></span></button>
										</td>
										<td>
											<?php if($m->scr_nda == NULL){ ?>
											<form id="submit_nda" method="POST" enctype="multipart/form-data">
												<input type="hidden" id="scr_man_id" name="scr_man_id"
													value="<?php echo $m->row_id;?>">
												<div class="input-group">
													<input type="file" class="form-control" id="scr_nda" name="scr_nda" accept=".doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/pdf" required>
													<button class="btn btn-outline-secondary" type="submit">Submit</button>
												</div>
											</form>
											<?php }else{ echo $m->scr_nda;} ?>
										</td>
									</tr>
									<?php endforeach;?>

								</tbody>
							</table>
							<?php } else { ?>

								<table class="table table-hover" id="all-manuscript" width="100%" cellspacing="0">
									<thead>
										<tr>
											<th>#</th>
											<th>Title</th>
											<th>Author(s)</th>
											<th>Membership Status</th>
											<th>Date Submitted</th>
											<th>Status</th>
											<th>Tracking No.</th>
											<th>Actions</th>
											<th>Remarks</th>
										</tr>
									</thead>
									<tbody>
										<?php $c = 1;foreach ($man_all as $m): ?>
										<?php $role = $this->session->userdata('_oprs_type_num');?>
										<?php $mem_type = $this->Manuscript_model->check_member($m->man_author_user_id); ?>
										<?php $mem_type = ($mem_type == 3) ? 'Member' : 'Non-member'; ?>
										<?php $author_type = ($m->man_author_type == 1) ? 'Main Author' : 'Co-author'; ?>
										<?php $acoa = (empty($this->Coauthor_model->get_author_coauthors($m->row_id))) ? '' : ', ' . $this->Coauthor_model->get_author_coauthors($m->row_id);?>
										<?php $title = $m->man_title; ?>
										<?php $mantitle = rawurlencode($m->man_title); ?>
										<?php $authors = $m->man_author . $acoa; ?>
										<?php $status = '<span class="badge rounded-pill bg-' . $m->status_class . '">' . $m->status . '</span>'; ?>
										<tr>
											<td></td>
											<td width="50%"><a href="javascript:void(0);" onclick="view_manus(<?php echo $m->row_id; ?>);"
													class="text-dark text-decoration-none mb-1"><?php echo $title; ?></a>
												<?php if($status > 1 && $status != 99){ ?>
												</br>
												<span class="badge rounded-pill text-bg-secondary"><?php echo $issue;?></span>
												<span class="badge rounded-pill text-bg-secondary">Volume <?php echo $m->man_volume;?></span>
												<span class="badge rounded-pill text-bg-secondary"><?php echo $m->man_year;?></span>
												<?php } ?>
											</td>
											<td><?php echo $authors;?></td>
											<td><?php echo $author_type;?> - <?php echo $mem_type;?></td>
											<td><?php echo date_format(new DateTime($m->date_created), 'F j, Y, g:i a'); ?></td>
											<td><?php echo $status;?></td>
											<!-- <td class="text-center"><a href="javascript:void(0);" onclick="tracking('<?php echo $m->man_trk_no;?>',<?php echo $this->session->userdata('_oprs_type_num');?>,<?php echo $m->man_status ?>)"><?php echo $m->man_trk_no;?></a></td> -->
											<!-- <td class="text-center"><a href="javascript:void(0);" onclick="tracking('<?php echo $m->man_trk_no;?>',<?php echo $this->session->userdata('_oprs_type_num');?>,'<?php echo rawurlencode($title) ?>',<?php echo $m->man_status ?>)"><?php echo $m->man_trk_no;?></a></td> -->
											<td class="text-center"><a href="javascript:void(0);" onclick="tracking('<?php echo $m->row_id;?>')"><?php echo $m->man_trk_no;?></a></td>
											<td>
												<div class="btn-groupx d-flex gap-1" role="group">
													<!-- EDITOR-IN-CHIEF EDITOR -->
													<?php if (_UserRoleFromSession() == 6) { 
														if($m->man_status == 2){ ?>
															<!-- process manuscript -->
															<button type="button" class="btn btn-outline-primary"
															onclick="eic_process(<?php echo $m->row_id; ?>,'<?php echo $mantitle; ?>', <?php echo $m->man_status; ?>)"
															data-bs-toggle="modal" data-bs-target="#eicProcessModal" rel="tooltip"
															data-bs-placement="top" title="Process"><span
																class="fas fa-gear"></span></button>
															<?php }else if($m->man_status == 8){ ?>
															<!-- process manuscript -->
															<button type="button" class="btn btn-outline-primary"
																onclick="final_review(<?php echo $m->row_id; ?>)"
																data-bs-toggle="modal" data-bs-target="#finalReviewModal" rel="tooltip"
																data-bs-placement="top" title="Process"><span
																	class="fas fa-gear"></span></button>
															<?php }else if($m->man_status == 12){ ?>
															<!-- publish to ejournal -->
															<button type="button" class="btn btn-outline-primary"
																onclick="publish_to_ejournal('<?php echo $m->row_id; ?>')"
																rel="tooltip"
																data-bs-placement="top" title="Publish to eJournal"><span
																	class="fas fa-paper-plane"></span></button>
														<?php } ?>
														
														
														<!-- view manuscript details -->
														<button type="button" class="btn btn-outline-secondary" rel="tooltip"
														data-bs-placement="top" title="View" onclick="view_manus(<?php echo $m->row_id; ?>);"><span class="fa fa-eye"></span></button>
													<?php } ?>

													<!-- ASSOCIATE EDITOR -->
													<?php if (_UserRoleFromSession() >= 7 && _UserRoleFromSession() <= 10) { ?>
															<!-- process manuscript -->
															<button type="button" class="btn btn-outline-primary"
															onclick="assoced_process(<?php echo $m->row_id; ?>,'<?php echo $mantitle; ?>',<?php echo $m->man_status; ?>)"
															data-bs-toggle="modal" data-bs-target="#assocEdProcessModal" rel="tooltip"
															data-bs-placement="top" title="Process"><span
																class="fas fa-gear"></span></button>
														
															<!-- view manuscript details -->
															<button type="button" class="btn btn-outline-secondary" rel="tooltip"
															data-bs-placement="top" title="View" onclick="view_manus(<?php echo $m->row_id; ?>);"><span class="fa fa-eye"></span></button>
													<?php } ?>
													
													<!-- CLUSTER EDITOR -->
													<?php if (_UserRoleFromSession() >= 11 && _UserRoleFromSession() <= 14) { ?>

														<?php if($m->man_status == 4){ ?>
															<!-- process manuscript -->
															<button type="button" class="btn btn-outline-primary"
															onclick="clued_process(<?php echo $m->row_id; ?>,'<?php echo $mantitle; ?>',<?php echo $m->man_status; ?>)"
															data-bs-toggle="modal" data-bs-target="#cluEdProcessModal" rel="tooltip"
															data-bs-placement="top" title="Process"><span
																class="fas fa-gear"></span></button>
														<?php } ?>
														
														<!-- view manuscript details -->
														<button type="button" class="btn btn-outline-secondary" rel="tooltip"
														data-bs-placement="top" title="View" onclick="view_manus(<?php echo $m->row_id; ?>);"><span class="fa fa-eye"></span></button>
													<?php } ?>

													<!-- SUPERADMIN -->
													<?php if (_UserRoleFromSession() == 20 ) { ?>
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

													<!-- COPY EDITOR -->
													<?php if (_UserRoleFromSession() == 17 ) { ?>	<!-- view manuscript details -->
														
														<button type="button" class="btn btn-outline-primary"
															onclick="coped_process(<?php echo $m->row_id; ?>,'<?php echo $mantitle; ?>',<?php echo $m->man_status; ?>)"
															data-bs-toggle="modal" data-bs-target="#copEdProcessModal" rel="tooltip"
															data-bs-placement="top" title="Process"><span
																class="fas fa-gear"></span></button>
														<button type="button" class="btn btn-outline-secondary" rel="tooltip"
															data-bs-placement="top" title="View" onclick="view_manus(<?php echo $m->row_id; ?>,'0','<?php echo $m->file; ?>');"><span class="fa fa-eye"></span></button>
													<?php } ?>

													<!-- LAYOUT ARTIST -->
													<?php if (_UserRoleFromSession() == 15 ) { ?>	
														
														<button type="button" class="btn btn-outline-primary"
															onclick="layout_process(<?php echo $m->row_id; ?>)"
															data-bs-toggle="modal" data-bs-target="#layoutProcessModal" rel="tooltip"
															data-bs-placement="top" title="Process"><span
																class="fas fa-gear"></span></button>
														<button type="button" class="btn btn-outline-secondary" rel="tooltip"
															data-bs-placement="top" title="View" onclick="view_manus(<?php echo $m->row_id; ?>,'0','<?php echo $m->file; ?>');"><span class="fa fa-eye"></span></button>
													<?php } ?>
													
													<!-- view abstract, full text manuscript, word -->
													<!-- <button type="button" class="btn border border-1 btn-light text-dark"
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
														class="far fa-file-alt"></span></a> -->
													<!-- add remarks -->
													<!-- <button type="button" class="btn border border-1 btn-light"
														onclick="add_remarks('<?php echo $m->row_id; ?>')" data-bs-toggle="modal"
														data-bs-target="#remarksModal" rel="tooltip" data-bs-placement="top"
														title="Add Remarks"><span class="far fa-edit"></span></button> -->
												</div>
											</td>
											<td><em><?php echo ($m->man_remarks == NULL) ? '-' : $m->man_remarks;?></em></td>
										</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							<?php } ?>

						<?php } ?>
						
						




						<!-- REVIEWER -->
						<!-- <?php if (_UserRoleFromSession() == 5) {?>
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
								<?php $c = 1;foreach ($man_all as $m): ?>
								<?php $drev = ($m->date_reviewed == null) ? '-' : $m->date_reviewed?>
								<?php $mantitle = rawurlencode($m->man_title); ?>
								<?php $action = (($m->scr_status == '4') ? '<span class="badge rounded-pill bg-success">Recommended as submitted</span>' 
									: ((($m->scr_status == '5') ? '<span class="badge rounded-pill badge-warning">Recommended with minor revisions</span>' 
									: ((($m->scr_status == '6') ? '<span class="badge rounded-pill badge-warning">Recommended with major revisions</span>'  
									: ((($m->scr_status == '7') ? '<span class="badge rounded-pill bg-danger">Not recommended</span>' 
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
						</table> -->
						<!-- EDITOR -->
						<?php } else if(_UserRoleFromSession() == 12) {?>
						<!-- <table class="table table-hover" id="editorial_reviews_table" width="100%" cellspacing="0">
							<thead>
								<tr>
									<th>#</th>
									<th>Title</th>
									<th>Status</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								<?php $c = 1;foreach ($man_all as $m): ?>
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
						</table> -->
						<!-- LAYOUT -->
						<?php } else if(_UserRoleFromSession() == 13) {?>
						<!-- <table class="table table-hover" id="layout_table" width="100%" cellspacing="0">
							<thead>
								<tr>
									<th>#</th>
									<th>Title</th>
									<th>Status</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								<?php $c = 1;foreach ($man_all as $m): ?>
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
						</table> -->
						<?php } ?>
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

