<?php $role = $this->session->userdata('_oprs_type_num');?>

<div class="container-fluid" style="padding-top:3.5em">
	<!-- Breadcrumbs-->
	<ol class="breadcrumb">
		<li class="breadcrumb-item">
			<a href="javascript:void(0);">Manuscripts</a>
		</li>
		<li class="breadcrumb-item active"><?php echo $this->session->userdata('_oprs_type'); ?>
			(<?php echo $this->session->userdata('_oprs_username'); ?>)</li>
	</ol>

	<!-- Manuscript Table -->
	<div class="card mb-3">
		<div class="card-header font-weight-bold">
			<i class="fa fa-table"></i> List of Manuscripts
		</div>
		<div class="card-body">
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
						<?php $action = (($m->scr_status == '4') ? '<span class="badge badge-pill badge-success">Recommended as submitted</span>' 
                              : ((($m->scr_status == '5') ? '<span class="badge badge-pill badge-warning">Recommended with minor revisions</span>' 
                              : ((($m->scr_status == '6') ? '<span class="badge badge-pill badge-warning">Recommended with major revisions</span>'  
                              : ((($m->scr_status == '7') ? '<span class="badge badge-pill badge-danger">Not recommended</span>' 
                              : '<button type="button" class="btn btn-light text-success btn-sm"  data-toggle="modal" rel="tooltip" data-placement="top" title="View Tracking" data-target="#startReviewModal" onclick="start_review(\'' . $m->man_file . '\',\'' . $m->row_id . '\',\'' . $mantitle . '\',\'' . $m->man_author . '\',\'' . $m->rev_hide_auth . '\')"><span class="fa fa-chevron-circle-right" ></span> Start Review</button>'))))))
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
										data-placement="top" title="Click for more details"></i></a>
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
								<span class="badge badge-secondary"><?php echo $issue;?></span>
								<span class="badge badge-secondary">Volume <?php echo $m->man_volume;?></span>
								<span class="badge badge-secondary"><?php echo $m->man_year;?></span></td>
							<td><span class="badge badge-<?php echo $class;?>"><?php echo $status;?></span></td>
							<td>

								<?php if($m->man_status == 4){ ?>
								<button type="button" class="btn btn-light btn-sm"
									onclick="view_reviews('<?php echo $m->row_id; ?>','<?php echo $mantitle; ?>')"
									data-toggle="modal" data-target="#reviewsModal" rel="tooltip" data-placement="top"
									title="View Reviews"><span class="fa fa-eye"></span> View Reviews
								</button>
								<button type="button" class="btn btn-light btn-sm"
									onclick="submit_editorial_review('<?php echo $m->row_id; ?>','<?php echo $mantitle; ?>')"
									data-toggle="modal" data-target="#editorialModal" rel="tooltip" data-placement="top"
									title="Submit Editorial Review"><span class="fa fa-chevron-circle-right"></span>
									Submit Editorial Review
								</button>
								<?php }else if($m->man_status == 6){ ?>
								<a type="button" class="btn btn-light btn-sm"
									href="<?php echo base_url('/assets/oprs/uploads/revised_manuscripts_word/'.$m->man_word); ?>"
									download><span class="fa fa-chevron-circle-right"></span> Download Final Manuscript
								</a>
								<button type="button" class="btn btn-light btn-sm"
									onclick="for_publication('<?php echo $m->row_id; ?>')" data-toggle="modal"
									data-target="#publicationModal" rel="tooltip" data-placement="top"
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
								<span class="badge badge-secondary"><?php echo $issue;?></span>
								<span class="badge badge-secondary">Volume <?php echo $m->man_volume;?></span>
								<span class="badge badge-secondary"><?php echo $m->man_year;?></span></td>
							<td><span class="badge badge-<?php echo $class;?>"><?php echo $status;?></span></td>
							<td>

								<?php if($m->man_status == 4){ ?>
								<button type="button" class="btn btn-light btn-sm"
									onclick="view_reviews('<?php echo $m->row_id; ?>','<?php echo $mantitle; ?>')"
									data-toggle="modal" data-target="#reviewsModal" rel="tooltip" data-placement="top"
									title="View Reviews"><span class="fa fa-eye"></span> View Reviews
								</button>
								<button type="button" class="btn btn-light btn-sm"
									onclick="submit_editorial_review('<?php echo $m->row_id; ?>','<?php echo $mantitle; ?>')"
									data-toggle="modal" data-target="#editorialModal" rel="tooltip" data-placement="top"
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
									data-toggle="modal" data-target="#publishableModal" rel="tooltip"
									data-placement="top" title="Submit Publishable Manuscript"><span
										class="fa fa-chevron-circle-right"></span> Submit as Publishable
								</button>
								<?php } ?>

							</td>
						</tr>
						<?php endforeach;?>

					</tbody>
				</table>
				<?php } else {?>
				<?php if ( _UserRoleFromSession() == 6 || _UserRoleFromSession() == 8 || _UserRoleFromSession() == 7) {?>
					
				<?php } ?>

				<?php if(_UserRoleFromSession() == 3){ ?>
					<div class="alert alert-warning " role="alert">
						<p class="font-weight-bold">Instructions to Managing Editor</p>
						The manuscript/article to be submitted must not contain any traces of identity of author (i.e. Name
						of author, co-authors, email address and affiliation). <br />
						The information, however, should be inputted in the upload forms. <br />
						<button type="button" class="btn btn-sm btn-warning mt-2" data-toggle="modal"
							data-target="#uploadModal" onclick="show_hidden_manus()"><i class="fas fa-upload"></i> Upload
							Manuscript
						</button>
					</div>
				<?php } ?>

				<nav id="man_tab">
					<div class="nav nav-tabs font-weight-bold" id="nav-tab" role="tablist">
						<a class="nav-link  active" id="nav-all-tab" data-toggle="tab" href="#nav-all" role="tab"
							aria-controls="nav-all" aria-selected="true">All
							<span class="badge badge-dark"><?php echo count($man_count);?></span></a>
						<a class="nav-link " id="nav-new-tab" data-toggle="tab" href="#nav-new" role="tab"
							aria-controls="nav-new" aria-selected="true">New
							<span class="badge badge-dark"><?php echo count($man_new);?></span></a>
						<a class="nav-link " id="nav-onreview-tab" data-toggle="tab" href="#nav-onreview" role="tab"
							aria-controls="nav-onreview" aria-selected="false">On-review
							<span class="badge badge-dark"><?php echo count($man_onreview);?></span></a>
						<a class="nav-link " id="nav-reviewed-tab" data-toggle="tab" href="#nav-reviewed" role="tab"
							aria-controls="nav-reviewed" aria-selected="false">Reviewed
							<span class="badge badge-dark"><?php echo count($man_reviewed);?></span></a>
						<a class="nav-link " id="nav-complete-review-tab" data-toggle="tab" href="#nav-complete-review"
							role="tab" aria-controls="nav-complete-review" aria-selected="false">Completed reviews
							<span class="badge badge-dark"><?php echo count($completed);?></span></a>
						<a class="nav-link " id="nav-editorial-review-tab" data-toggle="tab"
							href="#nav-editorial-review" role="tab" aria-controls="nav-editorial-review"
							aria-selected="false">Editorial reviews
							<span class="badge badge-dark"><?php echo count($editorial);?></span></a>
						<a class="nav-link " id="nav-final-tab" data-toggle="tab" href="#nav-final" role="tab"
							aria-controls="nav-final" aria-selected="false">Author's revision
							<span class="badge badge-dark"><?php echo count($man_final);?></span></a>
						<a class="nav-link " id="nav-publication-tab" data-toggle="tab" href="#nav-publication"
							role="tab" aria-controls="nav-publication" aria-selected="false">For
							publication
							<span class="badge badge-dark"><?php echo count($man_for_p);?></span></a>
						<a class="nav-link" id="nav-layout-tab" data-toggle="tab" href="#nav-layout" role="tab"
							aria-controls="nav-layout" aria-selected="false">For Layout
							<span class="badge badge-dark"><?php echo count($man_lay);?></span></a>
						<a class="nav-link" id="nav-publishables-tab" data-toggle="tab" href="#nav-publishables"
							role="tab" aria-controls="nav-publishables" aria-selected="false">Publishables
							<span class="badge badge-dark"><?php echo count($publishables);?></span></a>
						<a class="nav-link" id="nav-published-tab" data-toggle="tab" href="#nav-published" role="tab"
							aria-controls="nav-published" aria-selected="false">Published
							<span class="badge badge-dark"><?php echo count($published);?></span></a>
						<a class="nav-link" id="nav-existing-tab" data-toggle="tab" href="#nav-existing" role="tab"
							aria-controls="nav-existing" aria-selected="false">Published to other journal platforms
							<span class="badge badge-dark"><?php echo count($existing);?></span></a>
					</div>
				</nav>
				<div class="tab-content" id="nav-tabContent">
					<!-- ALL -->
					<div class="tab-pane fade show active p-3" id="nav-all" role="tabpanel"
						aria-labelledby="nav-all-tab">
						<table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
							<thead>
								<tr>
									<th>#</th>
									<th>Title</th>
									<th>Date Submitted</th>
									<th>Status <i class="fa fa-exclamation-circle text-primary" rel="tooltip"
											data-placement="top" title="Click the status to view tracking"></i></th>
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

								<?php $mem_type = ($mem_type > 0) ? '<span class="badge badge-secondary">Non-member</span>' : ''; ?>
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
                                :	'Published to to other journal platform')))))))))))))))));?>

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
									<td><a href="javascript:void(0);" onclick="view_manus(<?php echo $m->row_id; ?>);"
											class="text-dark "><?php echo $title; ?></a>
										<?php if($stat > 1 && $stat != 99){ ?>
										</br>
										<span class="badge badge-secondary"><?php echo $issue;?></span>
										<span class="badge badge-secondary">Volume <?php echo $m->man_volume;?></span>
										<span class="badge badge-secondary"><?php echo $m->man_year;?></span>
										<?php } ?>
										<?php echo $mem_type;?>
									</td>
									<td><?php echo date_format(new DateTime($m->date_created), 'F j, Y, g:i a'); ?></td>
									<td><span style="cursor:pointer"
											class="badge badge-pill badge-<?php echo $class; ?>" data-toggle="modal"
											rel="tooltip" data-placement="top" title="View Tracking"
											data-target="#trackingModal"
											onclick="tracking('<?php echo $m->row_id; ?>','<?php echo $this->session->userdata('_oprs_type_num');?>','<?php echo rawurlencode($title) ?>','<?php echo $m->man_status ?>')"><?php echo $status;?></span>
									</td>
									<td>
										<div class="btn-group" role="group">
											<!-- MANAGING EDITOR -->
											<?php if (_UserRoleFromSession() == 3) { 
                                                if($m->man_status == 1){ ?>
											<!-- process manuscript -->
											<button type="button" class="btn border border-1 btn-light text-success"
												onclick="process_man(<?php echo $m->row_id; ?>,<?php echo $m->man_status; ?>)"
												data-toggle="modal" data-target="#processModal" rel="tooltip"
												data-placement="top" title="Add Reviewers"><span
													class="fas fa-user-plus"></span></button>
											<!-- add remarks -->
											<button type="button" class="btn border border-1 btn-light text-primary"
												onclick="add_remarks('<?php echo $m->row_id; ?>')" data-toggle="modal"
												data-target="#remarksModal" rel="tooltip" data-placement="top"
												title="Add Remarks"><span class="far fa-edit"></span></button>

											<?php }else if($m->man_status <= 3){ ?>
											<!-- process manuscript -->
											<button type="button" class="btn border border-1 btn-light text-success"
												onclick="process_man(<?php echo $m->row_id; ?>,<?php echo $m->man_status; ?>)"
												data-toggle="modal" data-target="#processModal" rel="tooltip"
												data-placement="top" title="Add Reviewers"><span
													class="fas fa-user-plus"></span></button>
											<!-- view reviewers -->
											<button type="button" class="btn border border-1 btn-light text-info"
												onclick="view_reviewers('<?php echo $m->row_id; ?>','0','<?php echo rawurlencode($title); ?>','<?php echo $m->man_status; ?>')"
												data-toggle="modal" data-target="#reviewerModal" rel="tooltip"
												data-placement="top" title="View Reviewers"><span
													class="fas fa-users"></span></button>
											<?php }else if($m->man_status == 8){ ?>
											<!-- publish to ejournal -->
											<button type="button" class="btn border border-1 btn-light text-success"
									            onclick="publish_to_ejournal('<?php echo $m->row_id; ?>')"
												data-toggle="modal" data-target="#publishModal" rel="tooltip"
												data-placement="top" title="Publish to eJournal"><span
													class="fas fa-paper-plane"></span></button>
											<?php } ?>
											<!-- view abstract, full text manuscript, word -->
											<button type="button" class="btn border border-1 btn-light text-dark"
												onclick="manus_view('<?php echo $m->man_abs; ?>', 'abs')"
												data-toggle="modal" data-target="#manusModal" rel="tooltip"
												data-placement="top" title="View Abstract"><span
												class="far fa-file-alt"></span> ABS</button>
											<button type="button" class="btn border border-1 btn-light text-dark"
												onclick="manus_view('<?php echo $m->man_file; ?>', 'full')"
												data-toggle="modal" data-target="#manusModal" rel="tooltip"
												data-placement="top" title="View Full Manuscript"><span
												class="far fa-file-alt"></span> PDF</button>
											<a type="button" class="btn border border-1 btn-light text-dark"
												href="<?php echo base_url('/assets/oprs/uploads/\initial_manuscripts_word/'.$m->man_word); ?>" rel="tooltip"
												data-placement="top" title="Download Full Text Word" download><span
												class="far fa-file-alt"></span> WORD</a>

									
											<?php } ?>
											<!-- SUPERADMIN -->
											<?php if (_UserRoleFromSession() == 8 ) { ?>
											<!-- view reviewers -->
											<button type="button" class="btn border border-1  btn-light text-info"
												onclick="view_reviewers('<?php echo $m->row_id; ?>','0','<?php echo rawurlencode($title); ?>','<?php echo $m->man_status; ?>')"
												data-toggle="modal" data-target="#reviewerModal" rel="tooltip"
												data-placement="top" title="View Reviewers"><span
													class="fas fa-users"></span></button>
											<!-- view abstract and full text manuscript -->
											<button type="button" class="btn border border-1 btn-light text-dark"
												onclick="manus_view('<?php echo $m->man_abs; ?>', 'abs')"
												data-toggle="modal" data-target="#manusModal" rel="tooltip"
												data-placement="top" title="View Abstract"><span
													class="far fa-file-alt"></span> ABS</button>
											<button type="button" class="btn border border-1 btn-light text-dark"
												onclick="manus_view('<?php echo $m->man_file; ?>', 'full')"
												data-toggle="modal" data-target="#manusModal" rel="tooltip"
												data-placement="top" title="View Full Manuscript"><span
													class="far fa-file-alt"></span> PDF</button>
											<!-- delete manuscript -->
											<button type="button" class="btn border border-1 btn-light text-danger"
												rel="tooltip" data-placement="top" title="Delete"
												onclick="remove_manus('<?php echo $m->row_id; ?>')"><span
													class="fa fa-trash"></span></button>
											<?php } ?>

											<!-- approve manuscript -->
											<?php if (_UserRoleFromSession() == 9 && $rev_act >= 3 ) { ?>
											<!-- <button type="button" class="btn btn-light text-success btn" rel="tooltip"
                                                data-placement="top" title="Final Review"
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
					</div>
					<!-- NEW -->
					<div class="tab-pane fade p-3" id="nav-new" role="tabpanel" aria-labelledby="nav-new-tab">
						<table class="table table-hover" id="new_manus_table" width="100%" cellspacing="0">
							<thead>
								<tr>
									<th>#</th>
									<th>Title</th>
									<th>Date Submitted</th>
									<th>Status <i class="fa fa-exclamation-circle text-primary" rel="tooltip"
											data-placement="top" title="Click the status to view tracking"></i></th>
									<th>Actions</th>
									<th>Remarks</th>
								</tr>
							</thead>
							<tbody>
								<?php $c = 1;foreach ($man_new as $m): ?>
								<?php $role = $this->session->userdata('_oprs_type_num');?>
								<?php $status = 'New'; ?>
								<?php $class = 'warning'; ?>
								<?php $acoa = (empty($this->Coauthor_model->get_author_coauthors($m->row_id))) ? '' : ', ' . $this->Coauthor_model->get_author_coauthors($m->row_id);?>
								<?php $title = $m->man_title . ', ' . $m->man_author . $acoa; ?>
								<tr>
									<td></td>
									<td width="50%"><a href="javascript:void(0);"
											onclick="view_manus(<?php echo $m->row_id; ?>);"
											class="text-dark "><?php echo $title; ?></a></td>
									<td><?php echo date_format(new DateTime($m->date_created), 'F j, Y, g:i a'); ?></td>
									<td><span style="cursor:pointer"
											class="badge badge-pill badge-<?php echo $class; ?>" data-toggle="modal"
											rel="tooltip" data-placement="top" title="View Tracking"
											data-target="#trackingModal"
											onclick="tracking('<?php echo $m->row_id; ?>','<?php echo $this->session->userdata('_oprs_type_num');?>','<?php echo $title ?>','<?php echo $m->man_status ?>')"><?php echo $status;?></span>
									</td>
									<td>
										<div class="btn-group" role="group">
											<!-- MANAGING EDITOR -->
											<?php if (_UserRoleFromSession() == 3) { 
                                  if($m->man_status == 1){ ?>
											<!-- process manuscript -->
											<button type="button" class="btn border border-1 btn-light text-success"
												onclick="process_man(<?php echo $m->row_id; ?>,<?php echo $m->man_status; ?>)"
												data-toggle="modal" data-target="#processModal" rel="tooltip"
												data-placement="top" title="Add Reviewers"><span
													class="fas fa-user-plus"></span></button>
											<!-- add remarks -->
											<button type="button" class="btn border border-1 btn-light text-primary"
												onclick="add_remarks('<?php echo $m->row_id; ?>')" data-toggle="modal"
												data-target="#remarksModal" rel="tooltip" data-placement="top"
												title="Add Remarks"><span class="far fa-edit"></span></button>
											<?php } ?>
											<!-- view abstract, full text manuscript, word -->
											<button type="button" class="btn border border-1 btn-light text-dark"
												onclick="manus_view('<?php echo $m->man_abs; ?>', 'abs')"
												data-toggle="modal" data-target="#manusModal" rel="tooltip"
												data-placement="top" title="View Abstract"><span
													class="far fa-file-alt"></span> ABS</button>
											<button type="button" class="btn border border-1 btn-light text-dark"
												onclick="manus_view('<?php echo $m->man_file; ?>', 'full')"
												data-toggle="modal" data-target="#manusModal" rel="tooltip"
												data-placement="top" title="View Full Manuscript"><span
													class="far fa-file-alt"></span> PDF</button>
											<a type="button" class="btn border border-1 btn-light text-dark"
												href="<?php echo base_url('/assets/oprs/uploads/\initial_manuscripts_word/'.$m->man_word); ?>" rel="tooltip"
												data-placement="top" title="Download Full Text Word" download><span
												class="far fa-file-alt"></span> WORD</a>
											<?php } ?>
											<!-- SUPERADMIN -->
											<?php if (_UserRoleFromSession() == 8 ) { ?>
											<!-- view reviewers -->
											<button type="button" class="btn border border-1  btn-light text-info"
												onclick="view_reviewers('<?php echo $m->row_id; ?>','0','<?php echo rawurlencode($title); ?>','<?php echo $m->man_status; ?>')"
												data-toggle="modal" data-target="#reviewerModal" rel="tooltip"
												data-placement="top" title="View Reviewers"><span
													class="fas fa-users"></span></button>
											<!-- view abstract and full text manuscript -->
											<button type="button" class="btn border border-1 btn-light text-dark"
												onclick="manus_view('<?php echo $m->man_abs; ?>', 'abs')"
												data-toggle="modal" data-target="#manusModal" rel="tooltip"
												data-placement="top" title="View Abstract"><span
													class="far fa-file-alt"></span> ABS</button>
											<button type="button" class="btn border border-1 btn-light text-dark"
												onclick="manus_view('<?php echo $m->man_file; ?>', 'full')"
												data-toggle="modal" data-target="#manusModal" rel="tooltip"
												data-placement="top" title="View Full Manuscript"><span
													class="far fa-file-alt"></span> PDF</button>
											<!-- delete manuscript -->
											<button type="button" class="btn border border-1 btn-light text-danger"
												rel="tooltip" data-placement="top" title="Delete"
												onclick="remove_manus('<?php echo $m->row_id; ?>')"><span
													class="fa fa-trash"></span></button>
											<?php } ?>
										</div>
									<td><em><?php echo ($m->man_remarks == NULL) ? '-' : $m->man_remarks;?></em></td>
									</td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
					<!-- ON REVIEW -->
					<div class="tab-pane fade p-3" id="nav-onreview" role="tabpanel" aria-labelledby="nav-onreview-tab">
						<table class="table table-hover" id="onreview_manus_table" width="100%" cellspacing="0">
							<thead>
								<tr>
									<th>#</th>
									<th>Title</th>
									<th>Date Submitted</th>
									<th>Status <i class="fa fa-exclamation-circle text-primary" rel="tooltip"
											data-placement="top" title="Click the status to view tracking"></i></th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								<?php $c = 1;foreach ($man_onreview as $m): ?>
								<?php $role = $this->session->userdata('_oprs_type_num');?>
								<?php $rev_cnt = count($this->Manuscript_model->get_reviewers_display($m->row_id));?>
								<?php $rev_total = count($this->Manuscript_model->get_reviewers_display($m->row_id, 999));?>
								<?php $rev_act = count($this->Manuscript_model->get_reviewers_w_score($m->row_id));?>
								<?php $scr = count($this->Review_model->get_review(_UserRoleFromSession(), $m->row_id));?>
								<?php $i = $m->man_issue;
                                $issue = (($i == 5) ? 'Special Issue No. 1' 
                                        : (($i == 6) ? 'Special Issue No. 2' 
                                        : (($i == 7) ? 'Special Issue No. 3' 
                                        : (($i == 8) ? 'Special Issue No. 4' 
                                        : 'Issue ' . $i))));
                                ?>
								<!-- get total reveiewers with scores -->
								<?php $status = 'On-review'; ?>
								<?php $class = 'primary'; ?>
								<?php $acoa = (empty($this->Coauthor_model->get_author_coauthors($m->row_id))) ? '' : ', ' . $this->Coauthor_model->get_author_coauthors($m->row_id);?>
								<?php $title = $m->man_title . ', ' . $m->man_author . $acoa; ?>
								<tr>
									<td></td>
									<td width="50%"><a href="javascript:void(0);"
											onclick="view_manus(<?php echo $m->row_id; ?>);"
											class="text-dark "><?php echo $title; ?></a>
										</br>
										<span class="badge badge-secondary"><?php echo $issue;?></span>
										<span class="badge badge-secondary">Volume <?php echo $m->man_volume;?></span>
										<span class="badge badge-secondary"><?php echo $m->man_year;?></span>
									</td>
									<td><?php echo date_format(new DateTime($m->date_created), 'F j, Y, g:i a'); ?></td>
									<td><span style="cursor:pointer"
											class="badge badge-pill badge-<?php echo $class; ?>" data-toggle="modal"
											rel="tooltip" data-placement="top" title="View Tracking"
											data-target="#trackingModal"
											onclick="tracking('<?php echo $m->row_id; ?>','<?php echo $this->session->userdata('_oprs_type_num');?>','<?php echo $title ?>','<?php echo $m->man_status ?>')"><?php echo $status;?></span>
									</td>
									<td>
										<div class="btn-group" role="group">
											<!-- MANAGING EDITOR -->
											<?php if (_UserRoleFromSession() == 3) {
                          if($m->man_status != 1){ ?>
											<!-- process manuscript -->
											<button type="button" class="btn border border-1 btn-light text-success"
												onclick="process_man(<?php echo $m->row_id; ?>,<?php echo $m->man_status; ?>)"
												data-toggle="modal" data-target="#processModal" rel="tooltip"
												data-placement="top" title="Add Reviewers"><span
													class="fas fa-user-plus"></span></button>
											<!-- view reviewers -->
											<button type="button" class="btn border border-1 btn-light text-info"
												onclick="view_reviewers('<?php echo $m->row_id; ?>','0','<?php echo rawurlencode($title); ?>','<?php echo $m->man_status; ?>')"
												data-toggle="modal" data-target="#reviewerModal" rel="tooltip"
												data-placement="top" title="View Reviewers"><span
													class="fas fa-users"></span></button>
											<?php } ?>
											<!-- view abstract and full text manuscript -->
											<button type="button" class="btn border border-1 btn-light text-dark"
												onclick="manus_view('<?php echo $m->man_abs; ?>', 'abs')"
												data-toggle="modal" data-target="#manusModal" rel="tooltip"
												data-placement="top" title="View Abstract"><span
													class="far fa-file-alt"></span> ABS</button>
											<button type="button" class="btn border border-1 btn-light text-dark"
												onclick="manus_view('<?php echo $m->man_file; ?>', 'full')"
												data-toggle="modal" data-target="#manusModal" rel="tooltip"
												data-placement="top" title="View Full Manuscript"><span
													class="far fa-file-alt"></span> PDF</button>
											<?php } ?>
											<!-- SUPERADMIN -->
											<?php if (_UserRoleFromSession() == 8 ) { ?>
											<!-- view reviewers -->
											<button type="button" class="btn border border-1  btn-light text-info"
												onclick="view_reviewers('<?php echo $m->row_id; ?>','0','<?php echo rawurlencode($title); ?>','<?php echo $m->man_status; ?>')"
												data-toggle="modal" data-target="#reviewerModal" rel="tooltip"
												data-placement="top" title="View Reviewers"><span
													class="fas fa-users"></span></button>
											<!-- view abstract and full text manuscript -->
											<button type="button" class="btn border border-1 btn-light text-dark"
												onclick="manus_view('<?php echo $m->man_abs; ?>', 'abs')"
												data-toggle="modal" data-target="#manusModal" rel="tooltip"
												data-placement="top" title="View Abstract"><span
													class="far fa-file-alt"></span> ABS</button>
											<button type="button" class="btn border border-1 btn-light text-dark"
												onclick="manus_view('<?php echo $m->man_file; ?>', 'full')"
												data-toggle="modal" data-target="#manusModal" rel="tooltip"
												data-placement="top" title="View Full Manuscript"><span
													class="far fa-file-alt"></span> PDF</button>
											<!-- delete manuscript -->
											<button type="button" class="btn border border-1 btn-light text-danger"
												rel="tooltip" data-placement="top" title="Delete"
												onclick="remove_manus('<?php echo $m->row_id; ?>')"><span
													class="fa fa-trash"></span></button>
											<?php } ?>
										</div>
									</td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
					<!-- REVIEWED -->
					<div class="tab-pane fade p-3" id="nav-reviewed" role="tabpanel" aria-labelledby="nav-reviewed-tab">
						<table class="table table-hover" id="reviewed_manus_table" width="100%" cellspacing="0">
							<thead>
								<tr>
									<th>#</th>
									<th>Title</th>
									<th>Date Submitted</th>
									<th>Status <i class="fa fa-exclamation-circle text-primary" rel="tooltip"
											data-placement="top" title="Click the status to view tracking"></i></th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								<?php $c = 1;foreach ($man_reviewed as $m): ?>
								<?php $role = $this->session->userdata('_oprs_type_num');?>
								<?php $rev_cnt = count($this->Manuscript_model->get_reviewers_display($m->row_id));?>
								<?php $rev_total = count($this->Manuscript_model->get_reviewers_display($m->row_id, 999));?>
								<?php $rev_act = count($this->Manuscript_model->get_reviewers_w_score($m->row_id));?>
								<?php $scr = count($this->Review_model->get_review(_UserRoleFromSession(), $m->row_id));?>
								<!-- get total reveiewers with scores -->
								<?php $status = 'Reviewed (' . $rev_act . '/' . $rev_cnt . ')'; ?>
								<?php $class = 'info'; ?>
								<?php $acoa = (empty($this->Coauthor_model->get_author_coauthors($m->row_id))) ? '' : ', ' . $this->Coauthor_model->get_author_coauthors($m->row_id);?>
								<?php $title = $m->man_title . ', ' . $m->man_author . $acoa; ?>
								<?php $i = $m->man_issue;
                                $issue = (($i == 5) ? 'Special Issue No. 1' 
                                        : (($i == 6) ? 'Special Issue No. 2' 
                                        : (($i == 7) ? 'Special Issue No. 3' 
                                        : (($i == 8) ? 'Special Issue No. 4' 
                                        : 'Issue ' . $i))));
                                ?>
								<tr>
									<td></td>
									<td width="50%"><a href="javascript:void(0);"
											onclick="view_manus(<?php echo $m->row_id; ?>);"
											class="text-dark "><?php echo $title; ?></a>
										</br>
										<span class="badge badge-secondary"><?php echo $issue;?></span>
										<span class="badge badge-secondary">Volume <?php echo $m->man_volume;?></span>
										<span class="badge badge-secondary"><?php echo $m->man_year;?></span></td>
									<td><?php echo date_format(new DateTime($m->date_created), 'F j, Y, g:i a'); ?></td>
									<td><span style="cursor:pointer"
											class="badge badge-pill badge-<?php echo $class; ?>" data-toggle="modal"
											rel="tooltip" data-placement="top" title="View Tracking"
											data-target="#trackingModal"
											onclick="tracking('<?php echo $m->row_id; ?>','<?php echo $this->session->userdata('_oprs_type_num');?>','<?php echo $title ?>','<?php echo $m->man_status ?>')"><?php echo $status;?></span>
									</td>
									<td>
										<div class="btn-group" role="group">
											<!-- MANAGING EDITOR -->
											<?php if (_UserRoleFromSession() == 3) {
                            if($m->man_status != 1){ ?>
											<!-- process manuscript -->
											<button type="button" class="btn border border-1 btn-light text-success"
												onclick="process_man(<?php echo $m->row_id; ?>,<?php echo $m->man_status; ?>)"
												data-toggle="modal" data-target="#processModal" rel="tooltip"
												data-placement="top" title="Add Reviewers"><span
													class="fas fa-user-plus"></span></button>
											<!-- view reviewers -->
											<button type="button" class="btn border border-1 btn-light text-info"
												onclick="view_reviewers('<?php echo $m->row_id; ?>','0','<?php echo rawurlencode($title); ?>','<?php echo $m->man_status; ?>')"
												data-toggle="modal" data-target="#reviewerModal" rel="tooltip"
												data-placement="top" title="View Reviewers"><span
													class="fas fa-users"></span></button>
											<?php } ?>
											<!-- view abstract and full text manuscript -->
											<button type="button" class="btn border border-1 btn-light text-dark"
												onclick="manus_view('<?php echo $m->man_abs; ?>', 'abs')"
												data-toggle="modal" data-target="#manusModal" rel="tooltip"
												data-placement="top" title="View Abstract"><span
													class="far fa-file-alt"></span> ABS</button>
											<button type="button" class="btn border border-1 btn-light text-dark"
												onclick="manus_view('<?php echo $m->man_file; ?>', 'full')"
												data-toggle="modal" data-target="#manusModal" rel="tooltip"
												data-placement="top" title="View Full Manuscript"><span
													class="far fa-file-alt"></span> PDF</button>
											<?php } ?>
											<!-- SUPERADMIN -->
											<?php if (_UserRoleFromSession() == 8 ) { ?>
											<!-- view reviewers -->
											<button type="button" class="btn border border-1  btn-light text-info"
												onclick="view_reviewers('<?php echo $m->row_id; ?>','0','<?php echo rawurlencode($title); ?>','<?php echo $m->man_status; ?>')"
												data-toggle="modal" data-target="#reviewerModal" rel="tooltip"
												data-placement="top" title="View Reviewers"><span
													class="fas fa-users"></span></button>
											<!-- view abstract and full text manuscript -->
											<button type="button" class="btn border border-1 btn-light text-dark"
												onclick="manus_view('<?php echo $m->man_abs; ?>', 'abs')"
												data-toggle="modal" data-target="#manusModal" rel="tooltip"
												data-placement="top" title="View Abstract"><span
													class="far fa-file-alt"></span> ABS</button>
											<button type="button" class="btn border border-1 btn-light text-dark"
												onclick="manus_view('<?php echo $m->man_file; ?>', 'full')"
												data-toggle="modal" data-target="#manusModal" rel="tooltip"
												data-placement="top" title="View Full Manuscript"><span
													class="far fa-file-alt"></span> PDF</button>
											<!-- delete manuscript -->
											<button type="button" class="btn border border-1 btn-light text-danger"
												rel="tooltip" data-placement="top" title="Delete"
												onclick="remove_manus('<?php echo $m->row_id; ?>')"><span
													class="fa fa-trash"></span></button>
											<?php } ?>
										</div>
									</td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
					<!-- COMPLETED REVIEWS -->
					<div class="tab-pane fade p-3" id="nav-complete-review" role="tabpanel"
						aria-labelledby="nav-complete-review-tab">
						<table class="table table-hover" id="completed_manus_table" width="100%" cellspacing="0">
							<thead>
								<tr>
									<th>#</th>
									<th>Title</th>
									<th>Date Submitted</th>
									<th>Status <i class="fa fa-exclamation-circle text-primary" rel="tooltip"
											data-placement="top" title="Click the status to view tracking"></i></th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								<?php $c = 1;foreach ($completed as $row): ?>
								<?php $role = $this->session->userdata('_oprs_type_num');?>
								<?php $rev_cnt = count($this->Manuscript_model->get_reviewers_display($row->row_id));?>
								<?php $rev_total = count($this->Manuscript_model->get_reviewers_display($row->row_id, 999));?>
								<?php $rev_act = count($this->Manuscript_model->get_reviewers_w_score($row->row_id));?>
								<?php $scr = count($this->Review_model->get_review(_UserRoleFromSession(), $row->row_id));?>
								<!-- get total reveiewers with scores -->
								<?php $status = 'Reviewed (' . $rev_act . '/' . $rev_cnt . ')'; ?>
								<?php $rowlass = 'info'; ?>
								<?php $acoa = (empty($this->Coauthor_model->get_author_coauthors($row->row_id))) ? '' : ', ' . $this->Coauthor_model->get_author_coauthors($row->row_id);?>
								<?php $title = $row->man_title . ', ' . $row->man_author . $acoa; ?> <?php $i = $m->man_issue;
                                $issue = (($i == 5) ? 'Special Issue No. 1' 
                                        : (($i == 6) ? 'Special Issue No. 2' 
                                        : (($i == 7) ? 'Special Issue No. 3' 
                                        : (($i == 8) ? 'Special Issue No. 4' 
                                        : 'Issue ' . $i))));
                                ?>
								<tr>
									<td></td>
									<td width="50%"><a href="javascript:void(0);"
											onclick="view_manus(<?php echo $row->row_id; ?>);"
											class="text-dark "><?php echo $title; ?></a> </br>
										<span class="badge badge-secondary"><?php echo $issue;?></span>
										<span class="badge badge-secondary">Volume <?php echo $m->man_volume;?></span>
										<span class="badge badge-secondary"><?php echo $m->man_year;?></span></td>
									<td><?php echo date_format(new DateTime($row->date_created), 'F j, Y, g:i a'); ?>
									</td>
									<td><span style="cursor:pointer"
											class="badge badge-pill badge-<?php echo $rowlass; ?>" data-toggle="modal"
											rel="tooltip" data-placement="top" title="View Tracking"
											data-target="#trackingModal"
											onclick="tracking('<?php echo $row->row_id; ?>','<?php echo $this->session->userdata('_oprs_type_num');?>','<?php echo $title ?>','<?php echo $row->man_status ?>')"><?php echo $status;?></span>
									</td>
									<td>
										<div class="btn-group" role="group">
											<!-- MANAGING EDITOR -->
											<?php if (_UserRoleFromSession() == 3) {
                                            if($row->man_status != 1){ ?>
											<!-- send to editor in chief -->
											<button type="button" class="btn border border-1 btn-light text-warning"
												onclick="edit_man(<?php echo $row->row_id; ?>,<?php echo $row->man_status; ?>)"
												data-toggle="modal" data-target="#editorModal" rel="tooltip"
												data-placement="top" title="Add Editor-in-chief"><span
													class="fas fa-book-reader"></span></button>
											<!-- view reviewers -->
											<button type="button" class="btn border border-1 btn-light text-info"
												onclick="view_reviewers('<?php echo $row->row_id; ?>','0','<?php echo rawurlencode($title); ?>','<?php echo $row->man_status; ?>')"
												data-toggle="modal" data-target="#reviewerModal" rel="tooltip"
												data-placement="top" title="View Reviewers"><span
													class="fas fa-users"></span></button>
											<?php } ?>
											<!-- view abstract and full text manuscript -->
											<button type="button" class="btn border border-1 btn-light text-dark"
												onclick="manus_view('<?php echo $row->man_abs; ?>', 'abs')"
												data-toggle="modal" data-target="#manusModal" rel="tooltip"
												data-placement="top" title="View Abstract"><span
													class="far fa-file-alt"></span> ABS</button>
											<button type="button" class="btn border border-1 btn-light text-dark"
												onclick="manus_view('<?php echo $row->man_file; ?>', 'full')"
												data-toggle="modal" data-target="#manusModal" rel="tooltip"
												data-placement="top" title="View Full Manuscript"><span
													class="far fa-file-alt"></span> PDF</button>
											<?php } ?>
											<!-- SUPERADMIN -->
											<?php if (_UserRoleFromSession() == 8 ) { ?>
											<!-- view reviewers -->
											<button type="button" class="btn border border-1  btn-light text-info"
												onclick="view_reviewers('<?php echo $row->row_id; ?>','0','<?php echo rawurlencode($title); ?>','<?php echo $row->man_status; ?>')"
												data-toggle="modal" data-target="#reviewerModal" rel="tooltip"
												data-placement="top" title="View Reviewers"><span
													class="fas fa-users"></span></button>
											<!-- view abstract and full text manuscript -->
											<button type="button" class="btn border border-1 btn-light text-dark"
												onclick="manus_view('<?php echo $row->man_abs; ?>', 'abs')"
												data-toggle="modal" data-target="#manusModal" rel="tooltip"
												data-placement="top" title="View Abstract"><span
													class="far fa-file-alt"></span> ABS</button>
											<button type="button" class="btn border border-1 btn-light text-dark"
												onclick="manus_view('<?php echo $row->man_file; ?>', 'full')"
												data-toggle="modal" data-target="#manusModal" rel="tooltip"
												data-placement="top" title="View Full Manuscript"><span
													class="far fa-file-alt"></span> PDF</button>
											<!-- delete manuscript -->
											<button type="button" class="btn border border-1 btn-light text-danger"
												rel="tooltip" data-placement="top" title="Delete"
												onclick="remove_manus('<?php echo $row->row_id; ?>')"><span
													class="fa fa-trash"></span></button>
											<?php } ?>
										</div>
									</td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
					<!-- EDITORIAL REVIEWS  -->
					<div class="tab-pane fade p-3" id="nav-editorial-review" role="tabpanel"
						aria-labelledby="nav-editorial-review-tab">
						<table class="table table-hover" id="editorial_reviews_table" width="100%" cellspacing="0">
							<thead>
								<tr>
									<th>#</th>
									<th>Title</th>
									<th>Date Submitted</th>
									<th>Status <i class="fa fa-exclamation-circle text-primary" rel="tooltip"
											data-placement="top" title="Click the status to view tracking"></i></th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								<?php $c = 1;foreach ($editorial as $e): ?>
								<?php $role = $this->session->userdata('_oprs_type_num');?>
								<?php $status = 'Pending'; ?>
								<?php $class = 'danger'; ?>
								<?php $acoa = (empty($this->Coauthor_model->get_author_coauthors($e->row_id))) ? '' : ', ' . $this->Coauthor_model->get_author_coauthors($e->row_id);?>
								<?php $title = $e->man_title . ', ' . $e->man_author . $acoa; ?>
								<?php $i = $m->man_issue;
                                $issue = (($i == 5) ? 'Special Issue No. 1' 
                                        : (($i == 6) ? 'Special Issue No. 2' 
                                        : (($i == 7) ? 'Special Issue No. 3' 
                                        : (($i == 8) ? 'Special Issue No. 4' 
                                        : 'Issue ' . $i))));
                                ?>
								<tr>
									<td></td>
									<td width="50%"><a href="javascript:void(0);"
											onclick="view_manus(<?php echo $e->row_id; ?>);"
											class="text-dark "><?php echo $title; ?></a></br>
										<span class="badge badge-secondary"><?php echo $issue;?></span>
										<span class="badge badge-secondary">Volume <?php echo $m->man_volume;?></span>
										<span class="badge badge-secondary"><?php echo $m->man_year;?></span></td>
									<td><?php echo date_format(new DateTime($e->date_created), 'F j, Y, g:i a'); ?></td>
									<td><span style="cursor:pointer"
											class="badge badge-pill badge-<?php echo $class; ?>" data-toggle="modal"
											rel="tooltip" data-placement="top" title="View Tracking"
											data-target="#trackingModal"
											onclick="tracking('<?php echo $e->row_id; ?>','<?php echo $this->session->userdata('_oprs_type_num');?>','<?php echo $title ?>','<?php echo $e->man_status ?>')"><?php echo $status;?></span>
									</td>
									<td>
										<div class="btn-group" role="group">
											<!-- view editors -->
											<button type="button" class="btn border border-1 btn-light text-warning"
												onclick="view_editors('<?php echo $e->row_id; ?>','<?php echo rawurlencode($title); ?>')"
												data-toggle="modal" data-target="#editorialReviewModal" rel="tooltip"
												data-placement="top" title="View Editors"><span
													class="fas fa-book-reader"></span>
											</button>

											<!-- view reviewers -->
											<button type="button" class="btn border border-1 btn-light text-info"
												onclick="view_reviewers('<?php echo $e->row_id; ?>','0','<?php echo rawurlencode($title); ?>','<?php echo $e->man_status; ?>')"
												data-toggle="modal" data-target="#reviewerModal" rel="tooltip"
												data-placement="top" title="View Reviewers"><span
													class="fas fa-users"></span>
											</button>

											<!-- view abstract and full text manuscript -->
											<button type="button" class="btn border border-1 btn-light text-dark"
												onclick="manus_view('<?php echo $e->man_abs; ?>', 'abs')"
												data-toggle="modal" data-target="#manusModal" rel="tooltip"
												data-placement="top" title="View Abstract"><span
													class="far fa-file-alt"></span> ABS
											</button>
											<button type="button" class="btn border border-1 btn-light text-dark"
												onclick="manus_view('<?php echo $e->man_file; ?>', 'full')"
												data-toggle="modal" data-target="#manusModal" rel="tooltip"
												data-placement="top" title="View Full Manuscript"><span
													class="far fa-file-alt"></span> PDF
											</button>
										</div>
									</td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
					<!-- TO SUBMIT FINAL MANUSCRIPT FROM AUTHOR -->
					<div class="tab-pane fade p-3" id="nav-final" role="tabpanel" aria-labelledby="nav-final-tab">
						<table class="table table-hover" id="final_manus_table" width="100%" cellspacing="0">
							<thead>
								<tr>
									<th>#</th>
									<th>Title</th>
									<th>Date Submitted</th>
									<th>Status <i class="fa fa-exclamation-circle text-primary" rel="tooltip"
											data-placement="top" title="Click the status to view tracking"></i></th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								<?php $c = 1;foreach ($man_final as $m): ?>
								<?php $role = $this->session->userdata('_oprs_type_num');?>
								<?php $status = 'To submit final manuscript'; ?>
								<?php $class = 'danger'; ?>
								<?php $acoa = (empty($this->Coauthor_model->get_author_coauthors($m->row_id))) ? '' : ', ' . $this->Coauthor_model->get_author_coauthors($m->row_id);?>
								<?php $title = $m->man_title . ', ' . $m->man_author . $acoa; ?>
								<?php $i = $m->man_issue;
                                $issue = (($i == 5) ? 'Special Issue No. 1' 
                                        : (($i == 6) ? 'Special Issue No. 2' 
                                        : (($i == 7) ? 'Special Issue No. 3' 
                                        : (($i == 8) ? 'Special Issue No. 4' 
                                        : 'Issue ' . $i))));
                                ?>
								<tr>
									<td></td>
									<td width="50%"><a href="javascript:void(0);"
											onclick="view_manus(<?php echo $m->row_id; ?>);"
											class="text-dark "><?php echo $title; ?></a></br>
										<span class="badge badge-secondary"><?php echo $issue;?></span>
										<span class="badge badge-secondary">Volume <?php echo $m->man_volume;?></span>
										<span class="badge badge-secondary"><?php echo $m->man_year;?></span></td>
									<td><?php echo date_format(new DateTime($m->date_created), 'F j, Y, g:i a'); ?></td>
									<td><span style="cursor:pointer"
											class="badge badge-pill badge-<?php echo $class; ?>" data-toggle="modal"
											rel="tooltip" data-placement="top" title="View Tracking"
											data-target="#trackingModal"
											onclick="tracking('<?php echo $m->row_id; ?>','<?php echo $this->session->userdata('_oprs_type_num');?>','<?php echo $title ?>','<?php echo $m->man_status ?>')"><?php echo $status;?></span>
									</td>
									<td>
										<div class="btn-group" role="group">
											<!-- view reviewers -->
											<button type="button" class="btn border border-1 btn-light text-info"
												onclick="view_reviewers('<?php echo $m->row_id; ?>','0','<?php echo rawurlencode($title); ?>','<?php echo $m->man_status; ?>')"
												data-toggle="modal" data-target="#reviewerModal" rel="tooltip"
												data-placement="top" title="View Reviewers"><span
													class="fas fa-users"></span>
											</button>

											<!-- view abstract and full text manuscript -->
											<button type="button" class="btn border border-1 btn-light text-dark"
												onclick="manus_view('<?php echo $m->man_abs; ?>', 'abs')"
												data-toggle="modal" data-target="#manusModal" rel="tooltip"
												data-placement="top" title="View Abstract"><span
													class="far fa-file-alt"></span> ABS
											</button>
											<button type="button" class="btn border border-1 btn-light text-dark"
												onclick="manus_view('<?php echo $m->man_file; ?>', 'full')"
												data-toggle="modal" data-target="#manusModal" rel="tooltip"
												data-placement="top" title="View Full Manuscript"><span
													class="far fa-file-alt"></span> PDF
											</button>
										</div>
									</td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
					<!-- FOR PUBLICATION -->
					<div class="tab-pane fade p-3" id="nav-publication" role="tabpanel"
						aria-labelledby="nav-publication-tab">
						<table class="table table-hover" id="for_p_manus_table" width="100%" cellspacing="0">
							<thead>
								<tr>
									<th>#</th>
									<th>Title</th>
									<th>Date Submitted</th>
									<th>Status <i class="fa fa-exclamation-circle text-primary" rel="tooltip"
											data-placement="top" title="Click the status to view tracking"></i></th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								<?php $c = 1;foreach ($man_for_p as $m): ?>
								<?php $role = $this->session->userdata('_oprs_type_num');?>
								<?php $status = 'For publication'; ?>
								<?php $class = 'success'; ?>
								<?php $acoa = (empty($this->Coauthor_model->get_author_coauthors($m->row_id))) ? '' : ', ' . $this->Coauthor_model->get_author_coauthors($m->row_id);?>
								<?php $title = $m->man_title . ', ' . $m->man_author . $acoa; ?>
								<?php $i = $m->man_issue;
                                $issue = (($i == 5) ? 'Special Issue No. 1' 
                                        : (($i == 6) ? 'Special Issue No. 2' 
                                        : (($i == 7) ? 'Special Issue No. 3' 
                                        : (($i == 8) ? 'Special Issue No. 4' 
                                        : 'Issue ' . $i))));
                                ?>
								<tr>
									<td></td>
									<td width="50%"><a href="javascript:void(0);"
											onclick="view_manus(<?php echo $m->row_id; ?>);"
											class="text-dark "><?php echo $title; ?></a></br>
										<span class="badge badge-secondary"><?php echo $issue;?></span>
										<span class="badge badge-secondary">Volume <?php echo $m->man_volume;?></span>
										<span class="badge badge-secondary"><?php echo $m->man_year;?></span></td>
									<td><?php echo date_format(new DateTime($m->date_created), 'F j, Y, g:i a'); ?></td>
									<td><span style="cursor:pointer"
											class="badge badge-pill badge-<?php echo $class; ?>" data-toggle="modal"
											rel="tooltip" data-placement="top" title="View Tracking"
											data-target="#trackingModal"
											onclick="tracking('<?php echo $m->row_id; ?>','<?php echo $this->session->userdata('_oprs_type_num');?>','<?php echo $title ?>','<?php echo $m->man_status ?>')"><?php echo $status;?></span>
									</td>
									<td>
										<div class="btn-group" role="group">
											<!-- view reviewers -->
											<button type="button" class="btn border border-1 btn-light text-info"
												onclick="view_reviewers('<?php echo $m->row_id; ?>','0','<?php echo rawurlencode($title); ?>','<?php echo $m->man_status; ?>')"
												data-toggle="modal" data-target="#reviewerModal" rel="tooltip"
												data-placement="top" title="View Reviewers"><span
													class="fas fa-users"></span>
											</button>
											<!-- view abstract and full text manuscript -->
											<button type="button" class="btn border border-1 btn-light text-dark"
												onclick="manus_view('<?php echo $m->man_abs; ?>', 'abs')"
												data-toggle="modal" data-target="#manusModal" rel="tooltip"
												data-placement="top" title="View Abstract"><span
													class="far fa-file-alt"></span> ABS
											</button>
											<button type="button" class="btn border border-1 btn-light text-dark"
												onclick="manus_view('<?php echo $m->man_file; ?>', 'full')"
												data-toggle="modal" data-target="#manusModal" rel="tooltip"
												data-placement="top" title="View Full Manuscript"><span
													class="far fa-file-alt"></span> PDF
											</button>
										</div>
									</td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
					<!-- FOR LAYOUT -->
					<div class="tab-pane fade p-3" id="nav-layout" role="tabpanel" aria-labelledby="nav-layout-tab">
						<table class="table table-hover" id="for_l_manus_table" width="100%" cellspacing="0">
							<thead>
								<tr>
									<th>#</th>
									<th>Title</th>
									<th>Date Submitted</th>
									<th>Status <i class="fa fa-exclamation-circle text-primary" rel="tooltip"
											data-placement="top" title="Click the status to view tracking"></i></th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								<?php $c = 1;foreach ($man_lay as $m): ?>
								<?php $role = $this->session->userdata('_oprs_type_num');?>
								<?php $status = 'For layout'; ?>
								<?php $class = 'success'; ?>
								<?php $acoa = (empty($this->Coauthor_model->get_author_coauthors($m->row_id))) ? '' : ', ' . $this->Coauthor_model->get_author_coauthors($m->row_id);?>
								<?php $title = $m->man_title . ', ' . $m->man_author . $acoa; ?>
								<?php $i = $m->man_issue;
                                $issue = (($i == 5) ? 'Special Issue No. 1' 
                                        : (($i == 6) ? 'Special Issue No. 2' 
                                        : (($i == 7) ? 'Special Issue No. 3' 
                                        : (($i == 8) ? 'Special Issue No. 4' 
                                        : 'Issue ' . $i))));
                                ?>
								<tr>
									<td></td>
									<td width="50%"><a href="javascript:void(0);"
											onclick="view_manus(<?php echo $m->row_id; ?>);"
											class="text-dark "><?php echo $title; ?></a></br>
										<span class="badge badge-secondary"><?php echo $issue;?></span>
										<span class="badge badge-secondary">Volume <?php echo $m->man_volume;?></span>
										<span class="badge badge-secondary"><?php echo $m->man_year;?></span></td>
									<td><?php echo date_format(new DateTime($m->date_created), 'F j, Y, g:i a'); ?></td>
									<td><span style="cursor:pointer"
											class="badge badge-pill badge-<?php echo $class; ?>" data-toggle="modal"
											rel="tooltip" data-placement="top" title="View Tracking"
											data-target="#trackingModal"
											onclick="tracking('<?php echo $m->row_id; ?>','<?php echo $this->session->userdata('_oprs_type_num');?>','<?php echo $title ?>','<?php echo $m->man_status ?>')"><?php echo $status;?></span>
									</td>
									<td>
										<div class="btn-group" role="group">
											<!-- view reviewers -->
											<button type="button" class="btn border border-1 btn-light text-info"
												onclick="view_reviewers('<?php echo $m->row_id; ?>','0','<?php echo rawurlencode($title); ?>','<?php echo $m->man_status; ?>')"
												data-toggle="modal" data-target="#reviewerModal" rel="tooltip"
												data-placement="top" title="View Reviewers"><span
													class="fas fa-users"></span>
											</button>
											<!-- view abstract and full text manuscript -->
											<button type="button" class="btn border border-1 btn-light text-dark"
												onclick="manus_view('<?php echo $m->man_abs; ?>', 'abs')"
												data-toggle="modal" data-target="#manusModal" rel="tooltip"
												data-placement="top" title="View Abstract"><span
													class="far fa-file-alt"></span> ABS
											</button>
											<button type="button" class="btn border border-1 btn-light text-dark"
												onclick="manus_view('<?php echo $m->man_file; ?>', 'full')"
												data-toggle="modal" data-target="#manusModal" rel="tooltip"
												data-placement="top" title="View Full Manuscript"><span
													class="far fa-file-alt"></span> PDF
											</button>
										</div>
									</td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
					<!-- PUBLISHABLES -->
					<div class="tab-pane fade p-3" id="nav-publishables" role="tabpanel"
						aria-labelledby="nav-publishables-tab">
						<table class="table table-hover" id="publishables_table" width="100%" cellspacing="0">
							<thead>
								<tr>
									<th>#</th>
									<th>Title</th>
									<th>Date Submitted</th>
									<th>Status <i class="fa fa-exclamation-circle text-primary" rel="tooltip"
											data-placement="top" title="Click the status to view tracking"></i></th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								<?php $c = 1;foreach ($publishables as $m): ?>
								<?php $role = $this->session->userdata('_oprs_type_num');?>
								<?php $status = 'Ready to publish in eJournal'; ?>
								<?php $class = 'success'; ?>
								<?php $acoa = (empty($this->Coauthor_model->get_author_coauthors($m->row_id))) ? '' : ', ' . $this->Coauthor_model->get_author_coauthors($m->row_id);?>
								<?php $title = $m->man_title . ', ' . $m->man_author . $acoa; ?>
								<?php $i = $m->man_issue;
                                $issue = (($i == 5) ? 'Special Issue No. 1' 
                                        : (($i == 6) ? 'Special Issue No. 2' 
                                        : (($i == 7) ? 'Special Issue No. 3' 
                                        : (($i == 8) ? 'Special Issue No. 4' 
                                        : 'Issue ' . $i))));
                                ?>
								<tr>
									<td></td>
									<td width="50%"><a href="javascript:void(0);"
											onclick="view_manus(<?php echo $m->row_id; ?>);"
											class="text-dark "><?php echo $title; ?></a></br>
										<span class="badge badge-secondary"><?php echo $issue;?></span>
										<span class="badge badge-secondary">Volume <?php echo $m->man_volume;?></span>
										<span class="badge badge-secondary"><?php echo $m->man_year;?></span></td>
									<td><?php echo date_format(new DateTime($m->date_created), 'F j, Y, g:i a'); ?></td>
									<td><span style="cursor:pointer"
											class="badge badge-pill badge-<?php echo $class; ?>" data-toggle="modal"
											rel="tooltip" data-placement="top" title="View Tracking"
											data-target="#trackingModal"
											onclick="tracking('<?php echo $m->row_id; ?>','<?php echo $this->session->userdata('_oprs_type_num');?>','<?php echo $title ?>','<?php echo $m->man_status ?>')"><?php echo $status;?></span>
									</td>
									<td>
										<div class="btn-group" role="group">
											<!-- publish to ejournal -->
											<button type="button" class="btn border border-1 btn-light text-success"
									            onclick="publish_to_ejournal('<?php echo $m->row_id; ?>')"
												data-toggle="modal" data-target="#publishModal" rel="tooltip"
												data-placement="top" title="Publish to eJournal"><span
													class="fas fa-paper-plane"></span></button>
											<!-- view abstract and full text manuscript -->
											<button type="button" class="btn border border-1 btn-light text-dark"
												onclick="manus_view('<?php echo $m->man_abs; ?>', 'final_abs')"
												data-toggle="modal" data-target="#manusModal" rel="tooltip"
												data-placement="top" title="View Abstract"><span
													class="far fa-file-alt"></span> ABS
											</button>
											<button type="button" class="btn border border-1 btn-light text-dark"
												onclick="manus_view('<?php echo $m->man_file; ?>', 'final_full')"
												data-toggle="modal" data-target="#manusModal" rel="tooltip"
												data-placement="top" title="View Full Manuscript"><span
													class="far fa-file-alt"></span> PDF
											</button>
										</div>
									</td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
					<!-- PUBLISHED -->
					<div class="tab-pane fade p-3" id="nav-published" role="tabpanel"
						aria-labelledby="nav-published-tab">
						<table class="table table-hover" id="pub_manus_table" width="100%" cellspacing="0">
							<thead>
								<tr>
									<th>#</th>
									<th>Title</th>
									<th>Date Submitted</th>
									<th>Status <i class="fa fa-exclamation-circle text-primary" rel="tooltip"
											data-placement="top" title="Click the status to view tracking"></i></th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								<?php $c = 1;foreach ($published as $m): ?>
								<?php $role = $this->session->userdata('_oprs_type_num');?>
								<?php $status = 'Published'; ?>
								<?php $class = 'success'; ?>
								<?php $acoa = (empty($this->Coauthor_model->get_author_coauthors($m->row_id))) ? '' : ', ' . $this->Coauthor_model->get_author_coauthors($m->row_id);?>
								<?php $title = $m->man_title . ', ' . $m->man_author . $acoa; ?>
								<?php $i = $m->man_issue;
                                $issue = (($i == 5) ? 'Special Issue No. 1' 
                                        : (($i == 6) ? 'Special Issue No. 2' 
                                        : (($i == 7) ? 'Special Issue No. 3' 
                                        : (($i == 8) ? 'Special Issue No. 4' 
                                        : 'Issue ' . $i))));
                                ?>
								<tr>
									<td></td>
									<td><a href="javascript:void(0);" onclick="view_manus(<?php echo $m->row_id; ?>);"
											class="text-dark "><?php echo $title; ?></a></br>
										<span class="badge badge-secondary"><?php echo $issue;?></span>
										<span class="badge badge-secondary">Volume <?php echo $m->man_volume;?></span>
										<span class="badge badge-secondary"><?php echo $m->man_year;?></span></td>
									<td><?php echo date_format(new DateTime($m->date_created), 'F j, Y, g:i a'); ?></td>
									<td><span style="cursor:pointer"
											class="badge badge-pill badge-<?php echo $class; ?>" data-toggle="modal"
											rel="tooltip" data-placement="top" title="View Tracking"
											data-target="#trackingModal"
											onclick="tracking('<?php echo $m->row_id; ?>','<?php echo $this->session->userdata('_oprs_type_num');?>','<?php echo $title ?>','<?php echo $m->man_status ?>')"><?php echo $status;?></span>
									</td>
									<td>
										<!-- view abstract and full text manuscript -->
										<button type="button" class="btn border border-1 btn-light text-dark"
												onclick="manus_view('<?php echo $m->man_abs; ?>', 'final_abs')"
												data-toggle="modal" data-target="#manusModal" rel="tooltip"
												data-placement="top" title="View Abstract"><span
													class="far fa-file-alt"></span> ABS
											</button>
											<button type="button" class="btn border border-1 btn-light text-dark"
												onclick="manus_view('<?php echo $m->man_file; ?>', 'final_full')"
												data-toggle="modal" data-target="#manusModal" rel="tooltip"
												data-placement="top" title="View Full Manuscript"><span
													class="far fa-file-alt"></span> PDF
										</button>
									</td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
					<!-- NEW BUT PUBLISHED TO OTHER JOURNAL PLATFORMS -->
					<div class="tab-pane fade p-3" id="nav-existing" role="tabpanel" aria-labelledby="nav-existing-tab">
						<table class="table table-hover" id="existing_manus_table" width="100%" cellspacing="0">
							<thead>
								<tr>
									<th>#</th>
									<th>Title</th>
									<th>Date Submitted</th>
									<th>Status <i class="fa fa-exclamation-circle text-primary" rel="tooltip"
											data-placement="top" title="Click the status to view tracking"></i></th>
									<th>Actions</th>
									<th>Remarks</th>
								</tr>
							</thead>
							<tbody>
								<?php $c = 1;foreach ($existing as $m): ?>
								<?php $role = $this->session->userdata('_oprs_type_num');?>
								<?php $status = 'Published to other journal platform'; ?>
								<?php $class = 'secondary'; ?>
								<?php $acoa = (empty($this->Coauthor_model->get_author_coauthors($m->row_id))) ? '' : ', ' . $this->Coauthor_model->get_author_coauthors($m->row_id);?>
								<?php $title = $m->man_title . ', ' . $m->man_author . $acoa; ?>
								<tr>
									<td></td>
									<td width="50%"><a href="javascript:void(0);"
											onclick="view_manus(<?php echo $m->row_id; ?>);"
											class="text-dark "><?php echo $title; ?></a></td>
									<td><?php echo date_format(new DateTime($m->date_created), 'F j, Y, g:i a'); ?></td>
									<td><span style="cursor:pointer"
											class="badge badge-pill badge-<?php echo $class; ?>" data-toggle="modal"
											rel="tooltip" data-placement="top" title="View Tracking"
											data-target="#trackingModal"
											onclick="tracking('<?php echo $m->row_id; ?>','<?php echo $this->session->userdata('_oprs_type_num');?>','<?php echo $title ?>','<?php echo $m->man_status ?>')"><?php echo $status;?></span>
									</td>
									<td>
										<div class="btn-group" role="group">
											<!-- MANAGING EDITOR -->
											<?php if (_UserRoleFromSession() == 3) { 
                                  if($m->man_status == 1){ ?>
											<!-- process manuscript -->
											<button type="button" class="btn border border-1 btn-light text-success"
												onclick="process_man(<?php echo $m->row_id; ?>,<?php echo $m->man_status; ?>)"
												data-toggle="modal" data-target="#processModal" rel="tooltip"
												data-placement="top" title="Add Reviewers"><span
													class="fas fa-user-plus"></span></button>
											<!-- add remarks -->
											<button type="button" class="btn border border-1 btn-light text-primary"
												onclick="add_remarks('<?php echo $m->row_id; ?>')" data-toggle="modal"
												data-target="#remarksModal" rel="tooltip" data-placement="top"
												title="Add Remarks"><span class="far fa-edit"></span></button>
											<?php } ?>
											<!-- view abstract and full text manuscript -->
											<button type="button" class="btn border border-1 btn-light text-dark"
												onclick="manus_view('<?php echo $m->man_abs; ?>', 'abs')"
												data-toggle="modal" data-target="#manusModal" rel="tooltip"
												data-placement="top" title="View Abstract"><span
													class="far fa-file-alt"></span> ABS</button>
											<button type="button" class="btn border border-1 btn-light text-dark"
												onclick="manus_view('<?php echo $m->man_file; ?>', 'full')"
												data-toggle="modal" data-target="#manusModal" rel="tooltip"
												data-placement="top" title="View Full Manuscript"><span
													class="far fa-file-alt"></span> PDF</button>
											<?php } ?>
											<!-- SUPERADMIN -->
											<?php if (_UserRoleFromSession() == 8 ) { ?>
											<!-- view reviewers -->
											<button type="button" class="btn border border-1  btn-light text-info"
												onclick="view_reviewers('<?php echo $m->row_id; ?>','0','<?php echo rawurlencode($title); ?>','<?php echo $m->man_status; ?>')"
												data-toggle="modal" data-target="#reviewerModal" rel="tooltip"
												data-placement="top" title="View Reviewers"><span
													class="fas fa-users"></span></button>
											<!-- view abstract and full text manuscript -->
											<button type="button" class="btn border border-1 btn-light text-dark"
												onclick="manus_view('<?php echo $m->man_abs; ?>', 'abs')"
												data-toggle="modal" data-target="#manusModal" rel="tooltip"
												data-placement="top" title="View Abstract"><span
													class="far fa-file-alt"></span> ABS</button>
											<button type="button" class="btn border border-1 btn-light text-dark"
												onclick="manus_view('<?php echo $m->man_file; ?>', 'full')"
												data-toggle="modal" data-target="#manusModal" rel="tooltip"
												data-placement="top" title="View Full Manuscript"><span
													class="far fa-file-alt"></span> PDF</button>
											<!-- delete manuscript -->
											<button type="button" class="btn border border-1 btn-light text-danger"
												rel="tooltip" data-placement="top" title="Delete"
												onclick="remove_manus('<?php echo $m->row_id; ?>')"><span
													class="fa fa-trash"></span></button>
											<?php } ?>
										</div>
									<td><em><?php echo ($m->man_remarks == NULL) ? '-' : $m->man_remarks;?></em></td>
									</td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
				<?php }?>
			</div>
		</div>
		<!-- <div class="card-footer small text-muted">
			<?php if (_UserRoleFromSession() == 3) { ?>
			<button type="button" data-toggle="collapse" data-target="#publishables"
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
					<td><button type="button" class="btn btn-sm btn-outline-success" rel="tooltip" data-placement="top"
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

<!-- Sticky Footer -->
<footer class="sticky-footer">
	<div class="container my-auto">
		<!-- Copyright -->
		<div class="footer-copyright text-center" >&copy; 2018 Copyright ResearchJournal, All Rights Reserved
		<small class="text-muted d-block mt-1">Currently v2.1.85</small>
		</div>
		<!-- Copyright -->
	</div>
</footer>

</div>
<!-- /.content-wrapper -->
</div>
<!-- /#wrapper -->
<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
	<i class="fas fa-angle-up"></i>
</a>

<!-- Upload Manuscript-->
<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModal" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Manuscript Details</h5>
				<button class="close" type="button" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">x</span>
				</button>
			</div>
			<div class="modal-body">
				<table class="table table-borderless">
					<tbody>
					</tbody>
				</table>
				<form id="manuscript_form">
					<div class="form-group">
						<label class="font-weight-bold" for="man_title">Title</label>
						<textarea class="form-control" id="man_title" name="man_title" placeholder=""></textarea>
					</div>
					<div class="form-group">
						<label class="font-weight-bold mr-1" for="man_title">Member?</label>
						<div class="form-check-inline">
							<label class="form-check-label">
								<input type="radio" class="form-check-input" name="non_member" value="1">No
							</label>
						</div>
						<div class="form-check-inline">
							<label class="form-check-label">
								<input type="radio" class="form-check-input" name="non_member" value="2">Yes
							</label>
						</div>
					</div>
					<div class="form-group autocomplete" style="width:100% !important">
						<label class="font-weight-bold principal" for="man_author">Principal Author</label>
						<input type="text" class="form-control mt-2" id="man_author" name="man_author"
							placeholder="Search/Type by Name/Specialization/Non-member/Non-account">
					</div>
					<div class="form-group">
						<input type="text" class="form-control" placeholder="Affiliation" id="man_affiliation"
							name="man_affiliation">
					</div>
					<div class="form-group">
						<input type="email" class="form-control" placeholder="Email" id="man_email" name="man_email">
					</div>
					<input type="hidden" class="form-control" id="man_usr_id" name="man_usr_id">
					<span id="coauthors"></span>
					<div class="form-group" id="man_abs_div">
						<label class="font-weight-bold" for="man_abs">Upload Abstract</label>
						<span class="badge badge-danger" id="badge_abs">PDF only</span>
						<input type="file" class="form-control" id="man_abs" name="man_abs" accept="application/pdf">
					</div>
					<div class="form-group" id="man_file_div">
						<label class="font-weight-bold" for="man_file">Upload Full Manuscript</label>
						<span class="badge badge-danger" id="badge_pdf">PDF only</span>
						<input type="file" class="form-control" id="man_file" name="man_file" accept="application/pdf">
					</div>
					<div class="form-group" id="man_word_div">
						<label class="font-weight-bold" for="man_word">Upload Full Manuscript</label>
						<span class="badge badge-primary" id="badge_word">WORD only</span>
						<input type="file" class="form-control" id="man_word" name="man_word"
							accept=".doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
					</div>
					<div class="form-group">
						<label class="font-weight-bold" for="man_pages">Number of pages</label>
						<input type="number" class="form-control w-25" placeholder="0" id="man_pages" name="man_pages"
							min="1">
					</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-outline-secondary mr-auto" type="button" id="btn_add_coa"><i
						class="fa fa-plus"></i> Add Co-author</button>
				<button class="btn btn-outline-secondary btn_cancel" type="button" data-dismiss="modal">Cancel</button>
				<button class="btn btn-outline-secondary btn_close" type="button" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary" id="btn_save">Proceed</button>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- /.Upload Manuscript-->

<!-- Confirm Upload Manuscript -->
<div class="modal fade" id="confirmUploadModal" tabindex="-1">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Submit Manuscript</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				Do you want to submit?
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary" id="submit_upload_manuscript">Submit</button>
			</div>
		</div>
	</div>
</div>
<!--/. Confirm Upload Manuscript -->

<!-- Submit Final Manuscript -->
<div class="modal fade" id="finalModal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Submit Final Manuscript</h5>
				<button class="close" type="button" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="final_manuscript_form">
					<!-- <div class="form-group" id="man_file_div">
            <label for="man_file">Upload Final Manuscript</label>
            <span class="badge badge-warning" id="badge_pdf">PDF only</span>
            <input type="file" class="form-control" id="man_file" name="man_file" accept="application/pdf">
          </div> -->
					<div class="form-group" id="man_word_div">
						<label class="font-weight-bold" for="man_word">Upload Final Manuscript</label>
						<span class="badge badge-primary" id="badge_word">WORD</span>
						<input type="file" class="form-control" id="man_word" name="man_word"
							accept=".doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
					</div>
					<div class="form-group" id="man_abs_div">
						<label for="man_abs">Upload Abstract</label>
						<span class="badge badge-danger" id="badge_pdf">PDF</span>
						<input type="file" class="form-control" id="man_abs" name="man_abs" accept="application/pdf">
					</div>
					<div class="form-group" id="man_key_div">
						<label for="man_keywords">Keywords</label>
						<input type="text" class="form-control" id="man_keywords" name="man_keywords"
							placeholder="ex. science, community, etc.">
					</div>
					<div class="form-group">
						<label for="man_remarks">Remarks</label>
						<textarea class="form-control" id="man_remarks" name="man_remarks" placeholder="(optional)"
							maxlength="255"></textarea>
						<small class="text-muted float-right limit"></small>
					</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-primary">Submit</button>
				</form>
			</div>
		</div>
	</div>
</div>
<!--/. Submit Final Manuscript -->

<!-- Submit successfull -->
<div class="modal fade" id="refreshModal" tabindex="-1" role="dialog" aria-labelledby="refreshModal" aria-hidden="true"
	style="z-index:999999">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Message</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<p>
					<i class="fa fa-check"></i> Manuscript uploaded successfully.
				</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary" data-dismiss="modal"
					onclick="refresh_manus();">Close</button>
			</div>
		</div>
	</div>
</div>
<!--/. Submit successful -->

<!-- Process Manuscript-->
<div class="modal fade" id="processModal" tabindex="-1" data-keyboard="false" data-backdrop="static" role="dialog"
	aria-labelledby="processModal" aria-hidden="true" style="z-index:9999">
	<div class="modal-dialog modal-lg" role="document" style="max-width:90%">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Process Manuscript</h5>
				<button class="close" type="button" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="process_manuscript_form" autocomplete="off">
					<div class="form-row">
						<div class="col-6">
							<div class="form-group" id="form_journal">
								<ul class="nav nav-tabs" id="myTab" role="tablist">
									<li class="nav-item">
										<a class="nav-link active" id="new-tab" data-toggle="tab" href="#new" role="tab"
											aria-controls="new" aria-selected="true"><span class="fa fa-book"></span>
											Manuscript</a>
									</li>
									<!-- <li class="nav-item">
                                        <a class="nav-link" id="article-tab" data-toggle="tab" href="#article"
                                            role="tab" aria-controls="article" aria-selected="false"><span
                                                class="fa fa-plus-square"></span> Select Existing Journal/Issue</a>
                                    </li> -->
								</ul>
								<div class="tab-content p-3" id="myTabContent">
									<div class="tab-pane fade show active" id="new" role="tabpanel"
										aria-labelledby="new-tab">
										<div class="form-row">
											<div class="col">
												<label class="font-weight-bold" for="jor_volume">Volume No.</label>
												<select class="form-control text-uppercase" id="jor_volume"
													name="jor_volume" placeholder="ex. X"
													style="background-color:white">
													<?php foreach ($u_journal as $j): ?>
													<?php echo '<option value=' . $j->jor_volume . '>' . $j->jor_volume . '</option>'; ?>
													<?php endforeach;?>
												</select>
											</div>
											<div class="col">
												<label class="font-weight-bold" for="jor_issue">Issue No.</label>
												<select class="form-control" id="jor_issue" name="jor_issue">
													<option value="">Select Issue</option>
													<option value="1">1</option>
													<option value="2">2</option>
													<option value="3">3</option>
													<option value="4">4</option>
													<option value="5">Special Issue No. 1</option>
													<option value="6">Special Issue No. 2</option>
													<option value="7">Special Issue No. 3</option>
													<option value="8">Special Issue No. 4</option>
												</select>
											</div>
											<div class="col">
												<label class="font-weight-bold" for="jor_year">Year</label>
												<input type="number" class="form-control" id="jor_year" name="jor_year"
													max="9999" min="1000">
											</div>
										</div>
									</div>
									<div class="tab-pane fade" id="article" role="tabpanel"
										aria-labelledby="article-tab">
										<div class="form-row">
											<div class="col">
												<div class="col">
													<label for="jor_issue">Year</label>
													<select class="form-control" id="art_year" name="art_year">
														<option value="">Select year</option>
														<?php foreach ($u_year as $j): ?>
														<?php echo '<option value=' . $j->jor_year . '>' . $j->jor_year . '</option>'; ?>
														<?php endforeach;?>
													</select>
												</div>
											</div>
											<div class="col">
												<label for="art_issue">Volume, Issue</label>
												<select class="form-control" id="art_issue" name="art_issue">
													<option value="">Select Volume, Issue</option>
												</select>
											</div>
										</div>
									</div>
								</div>
								<!-- <small class="text-muted">Unselectable options in <span class="text-info">Issue No.</span> means were existing already.</small> -->
							</div>
							<nav>
								<div class="nav nav-tabs" id="nav-tab" role="tablist">
									<a class="nav-item nav-link active" data-toggle="tab" href="#nav-rev" role="tab"
										aria-controls="nav-rev" aria-selected="true" id="btn_add_rev"><button
											class="btn btn-primary btn-sm"><span class="fa fa-plus-square"></span> Add
											Reviewer</button></a>
									<a class="nav-item nav-link disabled" data-toggle="tab" href="#nav-rev" role="tab"
										aria-controls="nav-rev" aria-selected="true" id="btn_add_rev">
										<!-- <small>All reviewer emails will be Cc to <span class="text-info">oed@nrcp.dost.gov.ph</span></small>  -->
									</a>
								</div>
							</nav>
							<div class="tab-content p-3" id="nav-tabContent">
								<div class="tab-pane fade show active" id="nav-rev" role="tabpanel">
									<div class="form-group">
										<div id="rev_acc">
											<div class="card">
												<div class="card-header p-0" id="heading1" data-toggle="collapse"
													data-target="#collapse1">
													<h5 class="mb-0">
														<button class="btn btn-link" type="button">
															<span class="fa fa-address-card"></span> Reviewer 1 : <span
																id="rev_header1"></span>
														</button>
													</h5>
												</div>
												<div id="collapse1" class="collapse show" data-parent="#rev_acc">
													<div class="card-body">
														<div class="form-row mb-2">
															<div class="col-3">
																<select class="form-control" id="trk_title1"
																	name="trk_title[]" placeholder="Title">
																	<?php foreach ($titles as $t): ?>
																	<?php echo '<option value=' . $t->title_name . '>' . $t->title_name . '</option>'; ?>
																	<?php endforeach;?>
																</select>
															</div>
															<div class="col autocomplete">
																<input autofocus type="text" class="form-control "
																	id="trk_rev1" name="trk_rev[]"
																	placeholder="Search by Name/Specialization/Non-member/Non-account">
															</div>
														</div>
														<div class="form-row mb-2">
															<div class="col">
																<input type="text" class="form-control"
																	placeholder="Email" id="trk_rev_email1"
																	name="trk_rev_email[]">
															</div>
															<div class="col">
																<input type="text" class="form-control"
																	placeholder="Contact" id="trk_rev_num1"
																	name="trk_rev_num[]">
															</div>
															<input type="hidden" id="trk_rev_id1" name="trk_rev_id[]">
														</div>
														<div class="form-row">
															<div class="col">
																<input type="text" class="form-control"
																	placeholder="Specialization" id="trk_rev_spec1"
																	name="trk_rev_spec[]" autofocus>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<nav>
								<div class="nav nav-tabs" id="nav-tab" role="tablist">
									<a class="nav-item nav-link active" id="nav-timeframe-tab" data-toggle="tab"
										href="#nav-timeframe" role="tab" aria-controls="nav-timeframe"
										aria-selected="true"><span class="fas fa-stopwatch"></span> Time frames</a>
								</div>
							</nav>
							<div class="tab-content p-3" id="nav-tabContent">
								<div class="tab-pane fade show active" id="nav-timeframe" role="tabpanel"
									aria-labelledby="nav-timeframe-tab">
									<p class="font-weight-bold">Accept Review
										<br /><small>Days/weeks to accept or decline the review request.</small>
									</p>

									<div class="w-50 input-group mb-3">
										<input type="number" style="width:75px !important" placeholder="0"
											id="trk_request_timer" name="trk_request_timer" min="1">
										<div class="input-group-append">
											<select class="custom-select" id="trk_req_day_week" name="trk_req_day_week">
												<option value="1" selected>Days</option>
												<option value="2">Week/s</option>
											</select>
										</div>
									</div>
									<p class="font-weight-bold">Review Request
										<br /><small>Days/weeks to finish the review task</small>
									</p>

									<div class="input-group mb-3">
										<input type="number" placeholder="0" style="width:75px !important"
											id="trk_timeframe" name="trk_timeframe" style="width:50px !important;"
											min="1">
										<div class="input-group-append">
											<select class="custom-select" id="trk_rev_day_week" name="trk_rev_day_week">
												<option value="1" selected>Days</option>
												<option value="2">Week/s</option>
											</select>
										</div>
									</div>
								</div>
							</div>
							<nav>
								<div class="nav nav-tabs" id="nav-tab" role="tablist">
									<a class="nav-item nav-link active" id="nav-timeframe-tab" data-toggle="tab"
										href="#nav-timeframe" role="tab" aria-controls="nav-timeframe"
										aria-selected="true"><span class="fas fa-check-square"></span> Optional</a>
								</div>
							</nav>
							<div class="tab-content p-3" id="nav-tabContent">
								<div class="tab-pane fade show active" id="nav-timeframe" role="tabpanel"
									aria-labelledby="nav-timeframe-tab">
									<div class="form-group text-left">
										<div class="custom-control custom-checkbox">
											<input type="checkbox" class="custom-control-input" value="1"
												id="rev_hide_auth" name="rev_hide_auth">
											<label class="custom-control-label pt-1" for="rev_hide_auth"> Hide Authors
												to Reviewers <small>(Names, affiliations and emails are
													hidden)</small></label>
										</div>
									</div>
									<div class="form-group text-left">
										<div class="custom-control custom-checkbox">
											<input type="checkbox" class="custom-control-input" value="1"
												id="rev_hide_rev" name="rev_hide_rev">
											<label class="custom-control-label pt-1" for="rev_hide_rev"> Hide Reviewers
												to Authors <small>(Names, affiliations and emails are
													hidden)</small></label>
										</div>
									</div>
									<!-- <div class="form-group text-left">
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" value="1" id="rev_cc" name="rev_cc">
                          <label class="custom-control-label pt-1" for="rev_cc"> Additional CC</label>
                        </div>
                        <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
                      </div> -->
									<div class="form-group">
										<label class="font-weight-bold" for="man_remarks">Remarks</label>
										<textarea class="form-control" id="trk_remarks" name="trk_remarks"
											placeholder="Type your remarks here" onkeydown="countChar(this)"></textarea>
										<small class="text-muted float-right limit"></small>
									</div>
								</div>
							</div>
						</div>
						<div class="col-6">
							<div class="accordion" id="rev_acc_mail">
								<h6 class="font-weight-bold">Request for Manuscript Review Email</h6>
								<div class="alert alert-warning" role="alert">
									<span class="fas fa-exclamation-triangle"></span> Do not change or remove words with
									square brackets. [EXAMPLE]
								</div>
								<div class="card">
									<div class="card-header p-0" id="heading1" data-toggle="collapse"
										data-target="#collapse_mail1">
										<h5 class="mb-0">
											<button class="btn btn-link" type="button">
												<span class="fa fa-envelope"></span> Reviewer 1 : <span
													id="rev_header_mail1"></span>
											</button>
										</h5>
									</div>
									<div id="collapse_mail1" class="collapse show" data-parent="#rev_acc_mail">
										<div class="card-body p-0">
											<textarea type="text" id="tiny_mail1" name="tiny_mail[]"
												style="height:500px"></textarea>
										</div>
									</div>
								</div>
							</div>

							<div class="alert alert-warning mt-3" role="alert">
								<span class="fas fa-exclamation-triangle"></span> Do not change or remove words with
								square brackets. [EXAMPLE]
							</div>
						</div>
					</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-primary">Submit</button>
				</form>
			</div>
		</div>
	</div>
</div>
</div>
</div>
<!--/. Process Manuscript-->

<!-- Edit Manuscript-->
<div class="modal fade" id="editorModal" tabindex="-1" data-keyboard="false" data-backdrop="static" role="dialog"
	aria-labelledby="editorModal" aria-hidden="true" style="z-index:9999">
	<div class="modal-dialog modal-lg" role="document" style="max-width:90%">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Process Manuscript</h5>
				<button class="close" type="button" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="edit_manuscript_form" autocomplete="off">
					<div class="form-row">
						<div class="col-6">
							<div class="form-group">
								<ul class="nav nav-tabs" id="myTab" role="tablist">
									<li class="nav-item">
										<a class="nav-link active"><span class="fa fa-book"></span>
											Details</a>
									</li>
								</ul>
								<div class="tab-content p-3" id="myTabContent">
									<div class="tab-pane fade show active" id="new" role="tabpanel"
										aria-labelledby="new-tab">
										<div class="form-row">
											<div class="col">
												<label class="font-weight-bold" for="jor_volume">Volume No.</label>
												<select class="form-control text-uppercase" id="jor_volume"
													name="jor_volume" placeholder="ex. X"
													style="background-color:white">
													<?php foreach ($u_journal as $j): ?>
													<?php echo '<option value=' . $j->jor_volume . '>' . $j->jor_volume . '</option>'; ?>
													<?php endforeach;?>
												</select>
											</div>
											<div class="col">
												<label class="font-weight-bold" for="jor_issue">Issue No.</label>
												<select class="form-control" id="jor_issue" name="jor_issue">
													<option value="">Select Issue</option>
													<option value="1">1</option>
													<option value="2">2</option>
													<option value="3">3</option>
													<option value="4">4</option>
													<option value="5">Special Issue No. 1</option>
													<option value="6">Special Issue No. 2</option>
													<option value="7">Special Issue No. 3</option>
													<option value="8">Special Issue No. 4</option>
												</select>
											</div>
											<div class="col">
												<label class="font-weight-bold" for="jor_year">Year</label>
												<input type="number" class="form-control" id="jor_year" name="jor_year"
													max="9999" min="1000">
											</div>
										</div>
									</div>
								</div>
							</div>
							<nav>
								<div class="nav nav-tabs" id="nav-editor-tab" role="tablist">
									<!-- <a class="nav-item nav-link active" data-toggle="tab" href="#nav-editor" role="tab"
                                        aria-controls="nav-editor" aria-selected="true" id="btn_add_editor"><button
                                            class="btn btn-primary btn-sm"><span class="fa fa-plus-square"></span> Add
                                            Editor-in-chief</button></a>
                                    <a class="nav-item nav-link disabled" data-toggle="tab" href="#nav-editor" role="tab"
                                        aria-controls="nav-editor" aria-selected="true" id="btn_add_editor">
                                    </a> -->
									<li class="nav-item">
										<a class="nav-link active"><span class="fa fa-book"></span>
											Editor-in-chief</a>
									</li>
								</div>
							</nav>
							<div class="tab-content p-3" id="nav-tabContent">
								<div class="tab-pane fade show active" id="nav-editor" role="tabpanel">
									<div class="form-group">
										<div id="editor_acc">
											<div class="card">
												<div class="card-header p-0" id="heading1" data-toggle="collapse"
													data-target="#collapse1">
													<h5 class="mb-0">
														<button class="btn btn-link" type="button">
															<span class="fa fa-address-card"></span> Editor 1 : <span
																id="editor_header1"></span>
														</button>
													</h5>
												</div>
												<div id="collapse1" class="collapse show" data-parent="#editor_acc">
													<div class="card-body">
														<div class="form-row mb-2">
															<div class="col-3">
																<select class="form-control" id="editor_title1"
																	name="editor_title[]" placeholder="Title">
																	<?php foreach ($titles as $t): ?>
																	<?php echo '<option value=' . $t->title_name . '>' . $t->title_name . '</option>'; ?>
																	<?php endforeach;?>
																</select>
															</div>
															<div class="col autocomplete">
																<input autofocus type="text" class="form-control "
																	id="editor_rev1" name="editor_rev[]"
																	placeholder="Search by Name/Specialization/Non-member/Non-account">
															</div>
														</div>
														<div class="form-row mb-2">
															<div class="col">
																<input type="text" class="form-control"
																	placeholder="Email" id="editor_rev_email1"
																	name="editor_rev_email[]">
															</div>
															<div class="col">
																<input type="text" class="form-control"
																	placeholder="Contact" id="editor_rev_num1"
																	name="editor_rev_num[]">
															</div>
															<input type="hidden" id="editor_rev_id1"
																name="editor_rev_id[]">
														</div>
														<div class="form-row">
															<div class="col">
																<input type="text" class="form-control"
																	placeholder="Specialization" id="editor_rev_spec1"
																	name="editor_rev_spec[]" autofocus>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<nav>
								<div class="nav nav-tabs" id="nav-tab" role="tablist">
									<a class="nav-item nav-link active" id="nav-timeframe-tab" data-toggle="tab"
										href="#nav-timeframe" role="tab" aria-controls="nav-timeframe"
										aria-selected="true"><span class="fas fa-stopwatch"></span> Time frames
										<small>(optional)</small></a>
								</div>
							</nav>
							<div class="tab-content p-3" id="nav-tabContent">
								<div class="tab-pane fade show active" id="nav-timeframe" role="tabpanel"
									aria-labelledby="nav-timeframe-tab">

									<p class="font-weight-bold">Review Request
										<br /><small>Days/weeks to finish the review task</small>
									</p>

									<div class="input-group mb-3">
										<input type="number" placeholder="0" style="width:75px !important"
											id="editor_timeframe" name="editor_timeframe" style="width:50px !important;"
											min="1">
										<div class="input-group-append">
											<select class="custom-select" id="editor_rev_day_week"
												name="editor_rev_day_week">
												<option value="1" selected>Days</option>
												<option value="2">Week/s</option>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="font-weight-bold" for="man_remarks">Remarks</label>
								<textarea class="form-control" id="editor_remarks" name="editor_remarks"
									placeholder="Type your remarks here" onkeydown="countChar(this)"></textarea>
								<small class="text-muted float-right limit"></small>
							</div>
						</div>
						<div class="col-6">
							<div class="accordion" id="editor_acc_mail">
								<h6 class="font-weight-bold">Request for Manuscript Review Email</h6>
								<div class="alert alert-warning" role="alert">
									<span class="fas fa-exclamation-triangle"></span> Do not change or remove words with
									square brackets. [EXAMPLE]
								</div>
								<div class="card">
									<div class="card-header p-0" id="heading1" data-toggle="collapse"
										data-target="#collapse_editor_mail1">
										<h5 class="mb-0">
											<button class="btn btn-link" type="button">
												<span class="fa fa-envelope"></span> Editor 1 : <span
													id="editor_header_mail1"></span>
											</button>
										</h5>
									</div>
									<div id="collapse_editor_mail1" class="collapse show"
										data-parent="#editor_acc_mail">
										<div class="card-body p-0">
											<textarea type="text" id="editor_tiny_mail1" name="editor_tiny_mail[]"
												style="height:500px"></textarea>
										</div>
									</div>
								</div>
							</div>

							<div class="alert alert-warning mt-3" role="alert">
								<span class="fas fa-exclamation-triangle"></span> Do not change or remove words with
								square brackets. [EXAMPLE]
							</div>
						</div>
					</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-primary">Submit</button>
				</form>
			</div>
		</div>
	</div>
</div>
</div>
</div>
<!--/. Edit Manuscript-->

<!-- Process Status-->
<div class="modal fade" id="trackingModal" tabindex="-1" role="dialog" aria-labelledby="trackingModal"
	aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Status Tracking</h5>
				<button class="close" type="button" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body m-0 p-0" style="font-size:20px;">

				<div class="list-group w-100" id="track_list">
				</div>
			</div>
			<div class="modal-footer">
				<div class="dropdown">
					<a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						Change status
					</a>

					<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
						<a class="dropdown-item" onclick="change_status('99')">Published to other journal platforms</a>
						<!-- <a class="dropdown-item" onclick="change_status('1')">New</a> -->
					</div>
				</div>
				<button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- /.Process Status-->

<!-- View Manuscript -->
<div class="modal fade" id="manusModal" role="dialog" aria-labelledby="abstract_modal" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<embed id="manus_view" width="100%" height="700px" type="application/pdf">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- /.View Manuscript -->

<!-- Editors -->
<div class="modal fade" id="editorialReviewModal" tabindex="-1" role="dialog" aria-labelledby="processModal"
	aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Editors</h5>
				<button class="close" type="button" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<p class="font-weight-bold"></p>
				<div class="table-responsive">
					<table class="table table-hover" id="table-editors" width="100%" cellspacing="0"
						style="font-size:14px">
						<thead>
							<tr>
								<th>#</th>
								<th>Editor</th>
								<th>Specialization</th>
								<th>Email</th>
								<th>Contact</th>
								<th>Date of Request</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- /.Editors -->

<!-- Reviewers -->
<div class="modal fade" id="reviewerModal" tabindex="-1" role="dialog" aria-labelledby="processModal"
	aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Reviewers</h5>
				<button class="close" type="button" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<p class="font-weight-bold"></p>
				<div class="table-responsive">
					<table class="table table-hover" id="table-reviewers" width="100%" cellspacing="0"
						style="font-size:14px">
						<thead>
							<tr>
								<th>#</th>
								<th>Reviewer</th>
								<th>Email</th>
								<th>Contact</th>
								<th>Request Status</th>
								<th>Review Status</th>
								<th>Date Responded</th>
								<th>Time Remaining</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<div class="mr-auto">
					( <span class="fa fa-user-secret"></span> ) <span class="text-danger">Reviewers hidden to
						Authors</span>
					( <span class="fas fa-user-alt-slash ml-2"></span> ) <span class="text-danger">Authors hidden to
						Reviewers</span>
				</div>
				<?php if(_UserRoleFromSession() != 8) { ?>
				<!-- <a href="javascript:void(0);" id="new_rev" data-toggle="modal" data-target="#processModal"
                    class="btn btn-primary"><span class="fa fa-search"></span> Find new reviewer</a> -->
				<?php } ?>
				<button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- /.Reviewers -->

<!-- Reviews -->
<div class="modal fade" id="reviewsModal" tabindex="-1" role="dialog" aria-labelledby="processModal" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Reviews</h5>
				<button class="close" type="button" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<p class="font-weight-bold"></p>
				<div class="table-responsive">
					<table class="table table-hover" id="reviews_table" width="100%" cellspacing="0"
						style="font-size:14px">
						<thead>
							<tr>
								<th>#</th>
								<th>Reviewer</th>
								<th>Score</th>
								<th>Recommendation</th>
								<th>File</th>
								<th>Remarks</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- /.Reviews -->

<!-- Start Review -->
<div class="modal fade" id="startReviewModal" tabindex="-1" role="dialog" aria-labelledby="startReviewModal"
	aria-hidden="true" style="z-index:1499">
	<div class="modal-dialog modal-lg" role="document" style="max-width:90%">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">NRCP Research Journal - Manuscript Review</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="container-fluid">
					<div class="row">
						<div class="col-6">
							<embed id="manus_review" width="100%" height="700px" type="application/pdf"
								class="border border-dark">
						</div>
						<div class="col-6">
							<form id="submit_review_form">
								<table class="table table-hover table-borderless" style="font-size:14px;">
									<thead>
										<tr>
											<th>TITLE</th>
											<th colspan="4" id="rev_title"></th>
										</tr>
										<tr>
											<th>AUTHOR</th>
											<th colspan="4" id="rev_author"></th>
										</tr>
										<tr>
											<th scope="col">CRITERIA</th>
											<th scope="col">DESCRIPTION</th>
											<th scope="col">WEIGHT</th>
											<th scope="col" width="80px">SCORE</th>
										</tr>
									</thead>
									<tbody>
										<?php $y = 1;
                                        $x = 1;
                                        foreach ($criteria as $key => $c): ?>
										<tr>
											<td><?php echo $c->crt_subject; ?></td>
											<td><?php echo $c->crt_description; ?></td>
											<td><?php echo $c->crt_weight; ?></td>
											<?php if ($c->crt_type == 'text') { ?>
											<td><input type="text" class="form-control border border-danger crt_score"
													maxlength="2" id="scr_crt_<?php echo $x; ?>"
													name="scr_crt_<?php echo $x; ?>"></td>
											<?php $x++;}?>
										</tr>
										<?php if ($c->crt_type == 'text') {?>
										<tr>
											<td colspan="4">
												<textarea class="form-control form-control-sm" cols="2"
													name="scr_rem_<?php echo $y; ?>"
													placeholder="Comments/Remarks (Required)"></textarea>
											</td>
										</tr>
										<tr>
											<?php $y++;}?>
											<?php endforeach;?>
											<td colspan="3" class="font-weight-bold">TOTAL SCORE</td>
											<td><input type="text" id="crt_total" name="scr_total"
													class="form-control border border-dark" readonly=""></td>
										</tr>
										<tr>
											<td colspan="4">
												<div class="form-group">
													<label for="scr_remarks">General Remarks</label>
													<textarea class="form-control form-control-sm" id="scr_remarks"
														name="scr_remarks" placeholder="(Required)"></textarea>
												</div>
											</td>
										</tr>
										<tr>
											<td colspan="4">
												<div class="form-group">
													<label class="font-weight-bold" for="scr_file">You may upload your
														commented manuscript here (If any)</label>
													<span class="badge badge-primary mr-1">WORD</span><span
														class="badge badge-danger">PDF</span>
													<input type="file" class="form-control" id="scr_file"
														name="scr_file" accept=".doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/pdf">
												</div>
											</td>
										</tr>
										<tr>
											<td colspan="4">
												<div class="form-group">
													<label class="font-weight-bold" for="scr_nda">Non-Disclosure
														Agreement</label>
													<span class="badge badge-primary mr-1">WORD</span><span
														class="badge badge-danger">PDF</span>
													<input type="file" class="form-control" id="scr_nda" name="scr_nda"
														accept=".doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/pdf">
												</div>
											</td>
										</tr>
										<tr>
											<td colspan="4">
												<p class="font-weight-bold">Recommendation</p>
												<div class="custom-control custom-radio">
													<input type="radio" id="opt1" value="4" name="scr_status"
														class="custom-control-input">
													<label class="custom-control-label pt-1" for="opt1">Recommended as
														submitted</label>
												</div>
												<div class="custom-control custom-radio">
													<input type="radio" id="opt2" value="5" name="scr_status"
														class="custom-control-input">
													<label class="custom-control-label pt-1" for="opt2">Recommended with
														minor revisions</label>
												</div>
												<div class="custom-control custom-radio">
													<input type="radio" id="opt3" value="6" name="scr_status"
														class="custom-control-input">
													<label class="custom-control-label pt-1" for="opt3">Recommended with
														major revisions</label>
												</div>
												<div class="custom-control custom-radio">
													<input type="radio" id="opt4" value="7" name="scr_status"
														class="custom-control-input">
													<label class="custom-control-label pt-1" for="opt4">Not
														recommended</label>
												</div>
											</td>
										</tr>
									</tbody>
								</table>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
				<div class="btn-group" role="group">
					<button type="submit" class="btn btn-success">Proceed</button>
				</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- /.Start Review -->

<!-- Editorial Review -->
<div class="modal fade" id="editorialModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Editorial Review</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="submit_editorial_review_form" method="POST" enctype="multipart/form-data">
					<div class="form-group">
						<input type="hidden" id="edit_man_id" name="edit_man_id">
						<label class="font-weight-bold">Upload file</label>
						<div class="input-group is-invalid">
							<div class="custom-file">
								<input type="file" class="custom-file-input " id="edit_file" name="edit_file"
									accept=".doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/pdf" required>
								<label class="custom-file-label edit_file" for="edit_file">Choose
									file...</label>
							</div>
						</div>
						<div class="errorTxt"></div>
					</div>
					<div class="form-group pt-3">
						<label class="font-weight-bold" for="edit_remarks">Remarks</label>
						<textarea class="form-control form-control-sm" id="edit_remarks" name="edit_remarks"
							placeholder="(Optional)"></textarea>
					</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary">Submit</button>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- /.Final Review -->

<!-- View Score -->
<div class="modal fade" id="scoreModal" tabindex="-1" role="dialog" aria-labelledby="scoreModal" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Score</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<table class="table table-hover">
					<thead>
						<tr>
							<th>TITLE</th>
							<th colspan="4" id="score_title"></th>
						</tr>
						<tr>
							<th>AUTHOR</th>
							<th colspan="4" id="score_author"></th>
						</tr>
						<tr>
							<th scope="col">CRITERIA</th>
							<th scope="col">DESCRIPTION</th>
							<th scope="col">WEIGHT</th>
							<th scope="col" width="80px">SCORE</th>
							<th scope="col">Remarks</th>
						</tr>
					</thead>
					<tbody>
						<?php $x = 1;foreach ($criteria as $key => $c): ?>
						<tr>
							<td><?php echo $c->crt_subject; ?></td>
							<td><?php echo $c->crt_description; ?></td>
							<td><?php echo $c->crt_weight; ?></td>
							<td id="<?php echo $c->crt_input_name; ?>" class="text-primary"></td>
							<?php if ($c->crt_type == 'text') {?>
							<td id="scr_rem_<?php echo $x;
                            $x++; ?>" class="text-primary"></td>
							<?php $y++;}?>
						</tr>
						<?php endforeach;?>
						<tr>
							<td colspan="3" class="font-weight-bold">TOTAL SCORE</td>
							<td colspan="2" id="scr_total" class="text-primary"></td>
							<td></td>
						</tr>
						<tr>
							<td class="font-weight-bold">GENERAL REMARKS</td>
							<td colspan="4" id="scr_remarks"></td>
						</tr>
						<tr>
							<td class="font-weight-bold">REVIEWER</td>
							<td colspan="4" id="score_reviewer"></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!--./. View Score -->

<!-- Review inputs before process -->
<div class="modal fade" id="processReviewModal" tabindex="-1" role="dialog" aria-labelledby="processReviewModal"
	aria-hidden="true" style="z-index:999999">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Verify Process Manuscript</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<p>Do you want to check information you have entered?, click Cancel.</p>
				<p>Otherwise, click Submit.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary" id="submit_final_process">Submit</button>
			</div>
		</div>
	</div>
</div>
<!--/. Review inputs before process -->

<!-- Review inputs before process -->
<div class="modal fade" id="editorReviewModal" tabindex="-1" role="dialog" aria-labelledby="editorReviewModal"
	aria-hidden="true" style="z-index:999999">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Verify Process Manuscript</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<p>Do you want to check information you have entered?, click Cancel.</p>
				<p>Otherwise, click Submit.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary" id="submit_to_editor">Submit</button>
			</div>
		</div>
	</div>
</div>
<!--/. Review inputs before process -->

<!-- Publish Modal -->
<div class="modal fade" id="publishModal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Publish Manuscript to eJournal</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="pub_to_e_form">
					<table class="table table-borderless" id="pub_to_e_table">
						<tbody>
							<tr>
								<th scope="row">Title</th>
								<td id="man_title"></td>
							</tr>
							<tr>
								<th scope="row">Author</th>
								<td id="man_author"></td>
							</tr>
							<tr>
								<th scope="row">Final Absract</th>
								<td id="man_abs"></td>
							</tr>
							<tr>
								<th scope="row">Final Manuscript</th>
								<td id="man_word"></td>
							</tr>
							<tr>
								<th scope="row">Issue</th>
								<td id="man_issue"></td>
							</tr>
							<tr>
								<th scope="row">Volume</th>
								<td id="man_volume"></td>
							</tr>
							<tr>
								<th scope="row">Year</th>
								<td id="man_year"></td>
							</tr>
							<tr>
								<th scope="row">Page no.</th>
								<td>
									<input type="text" class="form-control" id="man_page_position" name="man_page_position" placeholder="ex. 1-3" required></td>
									<input type="hidden" id="man_id" name="man_id">
							</tr>
						</tbody>
					</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-primary">Save changes</button>
				</form>
			</div>
		</div>
	</div>
</div>
<!--/. Publish Modal -->

<!-- Confirm Submit Review Manuscript -->
<div class="modal fade" id="confirmSubmitReviewModal" tabindex="-1" style="z-index:1500">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Submit Review</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				Do you want to submit?
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary" id="submit_review_manuscript">Submit</button>
			</div>
		</div>
	</div>
</div>
<!--/. Confirm Submit Review Manuscript -->

<!-- Confirm Delete Manuscript -->
<div class="modal fade" id="confirmRemoveModal" tabindex="-1" style="z-index:1500">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Remove Manuscript</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				This action cannot be undo. Do you want to remove anyway?
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-danger" id="remove_manus">Remove</button>
			</div>
		</div>
	</div>
</div>
<!--/. Confirm Delete Manuscript -->

<!-- Remarks -->
<div class="modal fade" id="remarksModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Remarks</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="remarks_form">
					<div class="form-group">
						<textarea class="form-control" id="man_remarks" name="man_remarks"
							placeholder="Type your remarks here..."></textarea>
					</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-primary">Save changes</button>
				</form>
			</div>
		</div>
	</div>
</div>
<!--/. Remarks -->

<!-- For Publication Modal -->
<div class="modal fade" id="publicationModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Manuscript Details</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="publication_form">
					<table class="table table-borderless" id="publication_table">
						<tbody>
							<tr>
								<th scope="row">Title</th>
								<td id="man_title"></td>
							</tr>
							<tr>
								<th scope="row">Author</th>
								<td id="man_author"></td>
							</tr>
							<tr>
								<th scope="row">Final Manuscript</th>
								<td id="man_word"></td>
							</tr>
							<tr>
								<th scope="row">Issue</th>
								<td id="man_issue"></td>
							</tr>
							<tr>
								<th scope="row">Volume</th>
								<td id="man_volume"></td>
							</tr>
							<tr>
								<th scope="row">Year</th>
								<td id="man_year"></td>
							</tr>
							<tr>
								<th scope="row">Remarks</th>
								<td><textarea class="form-control" name="trk_remarks"
										placeholder="Type your remarks here (optional)"></textarea>
									<input type="hidden" id="man_id" name="man_id"></td>
							</tr>
						</tbody>
					</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-primary">Save changes</button>
				</form>
			</div>
		</div>
	</div>
</div>
<!--/. For Publication Modal -->

<!-- For Publishable Modal -->
<div class="modal fade" id="publishableModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Manuscript Details</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="publishable_form" method="POST" enctype="multipart/form-data">
					<table class="table table-borderless" id="publishable_table">
						<tbody>
							<tr>
								<th scope="row">Title</th>
								<td id="man_title"></td>
							</tr>
							<tr>
								<th scope="row">Author</th>
								<td id="man_author"></td>
							</tr>
							<tr>
								<th scope="row">Final Manuscript</th>
								<td id="man_word"></td>
							</tr>
							<tr>
								<th scope="row">Issue</th>
								<td id="man_issue"></td>
							</tr>
							<tr>
								<th scope="row">Volume</th>
								<td id="man_volume"></td>
							</tr>
							<tr>
								<th scope="row">Year</th>
								<td id="man_year"></td>
							</tr>
							<tr>
								<th scope="row">Upload Final Abstract <span class="badge badge-danger">PDF</span></th>
								<td>
									<input type="file" class="form-control-file" id="man_abs" name="man_abs"
										accept="application/pdf" required></td>
							</tr>
							</tr>
							<tr>
								<th scope="row">Upload Final Manuscript <span class="badge badge-danger">PDF</span></th>
								<td>
									<input type="file" class="form-control-file" id="man_file" name="man_file"
										accept="application/pdf" required>
									<input type="hidden" id="man_id" name="man_id"></td>
							</tr>
						</tbody>
					</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-primary">Save changes</button>
				</form>
			</div>
		</div>
	</div>
</div>
<!--/. For Publishable Modal -->