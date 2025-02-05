
<div id="layoutSidenav_content">
    <main>
		<div class="container-fluid pt-3 bg-light">	
			<div class="tab-content" id="v-pills-tabContent">
				<div class="tab-pane fade show active" id="v-pills-dashboard" role="tabpanel" aria-labelledby="v-pills-dashboard-tab" tabindex="0">	
					<div class="row">
						<div class="col-3">
							<div class="card border-dark">
								<div class="card-body text-dark">
									<h3 class="card-title lead text-center">Journals</h3>
									<div class="row">
										<div class="col text-center">
											<h5 class="card-title fw-bold d-flex gap-1 justify-content-center align-items-center" style="font-size:65px">
											<span class="oi oi-book text-danger" style="font-size:50px"></span>
											<?php if ($jor_count > 0) {echo $jor_count;} else {echo 0;}?></h5>
											<p class="text-muted"><?php echo $art_count; ?> Articles</p>
										</div>
									</div>
								</div>
								<div class="card-footer text-end">
									<a href="javascript:void(0);" id="view_journals" class="text-dark text-decoration-none">View details<span class="fa fa-angle-right ms-1"></span></a>
								</div>
							</div>
						</div>
						<div class="col-3">
							<div class="card border-dark">
								<div class="card-body text-dark" >
									<h3 class="card-title lead text-center">Editorial Boards</h3>
									<div class="row mt-3">
										<div class="col text-center">
											<h5 class="card-title fw-bold d-flex gap-1 justify-content-center align-items-center" style="font-size:65px">
											<span class="oi oi-pencil text-success" style="font-size:50px"></span>
											<?php if ($edt_count > 0) {echo $edt_count;} else {echo 0;}?></h5>
											<p class="text-muted">Review submitted manuscripts</p>
										</div>
									</div>
								</div>
								<div class="card-footer text-end">
									<a href="javascript:void(0);" id="view_editorials" class="text-dark text-decoration-none">View details <span class="fa fa-angle-right ms-1"></span></a>
								</div>
							</div>
						</div>
						<div class="col-3">
							<div class="card border-dark">
								<div class="card-body text-dark">
									<h3 class="card-title lead text-center">Client Information</h3>
									<div class="row">
										<div class="col text-center">
											<h5 class="card-title fw-bold d-flex gap-1 justify-content-center align-items-center" style="font-size:65px">
											<span class="oi oi-people text-warning" style="font-size:50px"></span>
											<?php if ($client_count > 0) {echo $client_count;} else {echo 0;}?></h5>
											<p class="text-muted">Full text pdf requests</p>
										</div>
									</div>
								</div>
								<div class="card-footer text-end">
									<a href="javascript:void(0);" id="view_clients" class="text-dark text-decoration-none">View details <span class="fa fa-angle-right ms-1"></span></a>
								</div>
							</div>
						</div>
						<div class="col-3">
							<div class="card border-dark">
								<div class="card-body text-dark">
									<h3 class="card-title lead text-center">Visitors Today</h3>
									<div class="row">
										<div class="col text-center">
											<h5 class="card-title fw-bold d-flex gap-1 justify-content-center align-items-center" style="font-size:65px">
											<span class="oi oi-eye text-info" style="font-size:50px"></span>
											<?php if ($vis_count > 0) {echo $vis_count;} else {echo 0;}?></h5>
											<p class="text-muted"><?php echo $vis_all; ?> Visited</p>
										</div>
									</div>
								</div>
								<div class="card-footer text-end">
									<a href="javascript:void(0);" class="text-dark text-decoration-none" onclick="get_visitors()">View details <span class="fa fa-angle-right ms-1"></span></a>
								</div>
							</div>
						</div>
					</div>
					<div class="row mt-4">
						<div class="col-8">
							<!-- POPULAR ARTICLE -->
							<div class="card border-dark">
								<div class="card-header text-dark">
									<span class="oi oi-star"></span> Popular Articles
								</div>
								<div class="card-body mt-3 mb-3">
									<?php if ($popular != null) {?>
									<table class="table table-hover" id="table-popular">
										<thead>
											<tr>
												<th scope="col">#</th>
												<th scope="col">Title</th>
												<th scope="col">Abstract View</th>
												<th scope="col">Full Text PDF Requests</th>
												<th scope="col">Cited</th>
											</tr>
										</thead>
										<tbody>
											<!-- GET DATA STORE TO ARRAY -->
											<?php $c = 0;foreach ($popular as $row): ?>
											<?php $c++;
											$pop[$c]['title'] = $row->art_title;?>
											<?php $pop[$c]['id'] = $row->art_id;?>
											<?php $pop[$c]['coa'] = $this->Coauthor_model->get_author_coauthors($row->art_id);?>
											<?php $pop[$c]['count'] = $this->Article_model->count_pdf($row->art_id);?>
											<?php $pop[$c]['abs'] = $this->Article_model->count_abstract($row->art_id);?>
											<?php $pop[$c]['cite'] = $this->Article_model->count_citation($row->art_id);?>
											
											<?php endforeach;?>
											<?php $c = 1;foreach ($pop as $row): ?>
											<?php $abs_class = ($row['abs'] > 0 ) ? 'text-primary' : 'text-muted';?>
											<?php $pdf_class = ($row['count'] > 0 ) ? 'text-primary' : 'text-muted';?>
											<?php $cite_class = ($row['cite'] > 0 ) ? 'text-primary' : 'text-muted';?>
											<tr>
												<td></td>
												<td><a href="javascript:void(0);"  class="text-primary text-decoration-none" onclick="edit_article(<?php echo $row['id']; ?>)"><?php echo '<strong>' . $row['title'] . '</strong>, ' . $row['coa']; ?></a></td>
												<td><a href="javascript:void(0);"  class="<?php echo $abs_class;?> fw-bold text-decoration-none" onclick="get_hits_info(<?php echo $row['id']; ?>)"><?php echo $row['abs']; ?></a></td>
												<td><a href="javascript:void(0);"  class="<?php echo $pdf_class;?> fw-bold text-decoration-none" onclick="get_client_info(<?php echo $row['id']; ?>)"><?php echo $row['count']; ?></a></td>
												<td><a href="javascript:void(0);"  class="<?php echo $cite_class;?> fw-bold text-decoration-none" onclick="get_citees(<?php echo $row['id']; ?>)"><?php echo $row['cite']; ?></a></td>
											</tr>
											<?php endforeach;?>
										</tbody>
									</table>
									<?php } else {?>
									<div class="list-group-flush">
										<a href="javascript:void(0);" class="list-group-item text-muted text-center "><span class="oi oi-warning"></span> No data found.</a>
									</div>
									<?php }?>
								</div>
								<div class="card-footer text-muted">
									Updated as of <?php echo date("Y-m-d H:i:s"); ?>
									<!-- <div class="dropdown float-right" data-bs-toggle="tooltip" data-bs-placement="top" title="Generate Report">
										<a href=javascript:void(0); class="dropdown-toggle" id="dropdownMenuButton" data-bs-toggle="dropdown"><span class="oi oi-data-transfer-download"></span></a>
										<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
											<a class="dropdown-item" href="<?php echo base_url('admin/export_excel/export_popular_excel'); ?>"><oi class="fa fa-file-excel-o"></oi> Excel File</a>
											<a class="dropdown-item" href="<?php echo base_url('admin/export_excel/export_popular_pdf'); ?>"><oi class="fa fa-file-pdf-o"></oi> PDF File</a>
										</div>
									</div> -->
								</div>
							</div>
							<!-- SEARCH AUTHOR -->
							<div class="card border-dark mt-4">
								<div class="card-header text-dark">
									<span class="oi oi-bookmark"></span> Authors Registry  <span class="float-right">Press <span class="badge bg-warning text-dark">ENTER</span> to search</span>
								</div>
								<div class="card-body mt-3 mb-3">
									<input type="text" class="form-control border-dark mb-3" id="authors_reg" placeholder="Type here to search Author/Coauthor">
									<table class="table" id="table-registry">
										<thead>
											<tr>
												<th scope="col" width="2%">#</th>
												<th scope="col">Name</th>
												<th scope="col">Afillitaion</th>
												<th scope="col">Email</th>
												<th scope="col">Articles</th>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
							</div>
							<!-- /.SEARCH AUTHOR -->
						</div>
						<div class="col-4">
							<div class="sticky-right-panel">
								<div class="card border-dark">
									<div class="card-header text-dark">
										<span class="oi oi-bell"></span> Activity
									</div>
									<div class="">
										<ul class="list-group list-group-flush">
											<?php if ($logs != null) {
												?>
												<?php foreach ($logs as $row): ?>
												<?php $src = ($row->acc_dp != '') ? base_url('assets/uploads/dp/' . $row->acc_dp . '') : base_url('assets/images/img_avatar.png');?>
												<?php $onclick = (
												(strpos($row->log_action, 'article') !== false) ? 'edit_article(' . $row->log_insert_id . ')' :
												((strpos($row->log_action, 'journal') !== false) ? 'edit_journal(' . $row->log_insert_id . ')' : '')
												);?>
											<a href="javascript:void(0);" onclick="<?php echo $onclick; ?>";? class="list-group-item list-group-item-action">
												<div class="media">
													<img class="rounded-circle mr-1" width="40px" height="40px" src="<?php echo $src; ?>">
													<div class="media-body">
														<h6 class="mt-0 mb-0"> <?php echo $this->Login_model->get_username_for_logs($row->log_user_id); ?> <?php echo $row->log_action; ?></h6>
														<small class="text-muted ml-auto mt-0"><?php echo $row->date_created; ?></small>
													</div>
												</div>
											</a>
											<?php endforeach;?>
											<?php } else {?>
											<li class="list-group-item text-muted text-center"><span class="oi oi-warning"></span> No activity.</li>
											<?php }?>
										</ul>
									</div>
									<div class="card-footer">
										<small><a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#activities_modal" class="text-decoration-none text-dark"><span class="oi oi-eye"></span> View all activity</a></small>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane fade" id="v-pills-create-journal" role="tabpanel" aria-labelledby="v-pills-create-journal-tab" tabindex="0">
					<div class="card border border-dark">
						<div class="card-body">
						<h3>Create Journal</h3>
						<p class="pt-2 text-primary"><span class="fa fa-info-circle text-primary me-1"></span>Upload Cover Photo - 2MB file size limit</p>
						<p class="lead">Journal Information</p>
						<form id="form_create_journal">
							<div class="row">
								<div class="col">
									<div class="mb-3">
										<label for="jor_volume" class="form-label">Volume No.</label>
										<select class="form-select text-uppercase mb-3" id="jor_volume" name="jor_volume" placeholder="ex. X">
											<option class="text-dark" disabled>Volume</option>
											<?php foreach ($u_journal as $j): ?>
											<?php echo '<option value=' . $j->jor_volume . ' class="text-dark">' . $j->jor_volume . '</option>'; ?>
											<?php endforeach;?>
										</select>
									</div>
									<div class="mb-3">
										<label for="jor_issue" class="form-label">Issue No.</label>
										<select class="form-select mb-3" id="jor_issue" name="jor_issue">
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
									<div class="mb-3">
										<label for="jor_month" class="form-label">Month  <span class="badge rounded-pill bg-secondary">Optional</span></label>
										<input type="text" class="form-control mb-3" id="jor_month" name="jor_month" placeholder="ex. Jan-Dec">
									</div>
									<div class="mb-3">
										<label for="jor_year" class="form-label">Year</label>
										<input type="text" class="form-control mb-3" id="jor_year" name="jor_year" placeholder="ex. 2018" maxlength="4">
									</div>
									<div class="mb-3">
										<label for="jor_issn" class="form-label">ISSN</label>
										<input type="text" class="form-control mb-3" id="jor_issn" name="jor_issn" value="0117-3294" readonly>
									</div>
									<div class="mb-3">
										<label for="jor_description" class="form-label">Description <span class="badge rounded-pill bg-secondary">Optional</span></label>
										<textarea rows="6" class="form-control mb-3" id="jor_description" name="jor_description" placeholder="Type description here" maxlength="500"></textarea>
									</div>
								</div>
								<div class="col">
									<div class="mb-3">
										<label for="jor_cover" class="form-label">Upload Cover Photo <span class=" badge rounded-pill bg-secondary">Optional</span> <span class="badge rounded-pill bg-success">JPG</span> <span class="badge rounded-pill bg-warning text-dark">2MB Limit</span></label>
										<input type="file" class="form-control mb-3" id="jor_cover" name="jor_cover" accept="image/jpeg" />
									</div>
									<div class="mb-3">
										<label for="cover_photo" class="form-label">Preview </label><br/>
										<img class="img-thumbnail" id="cover_photo" src="<?php echo base_url('assets/images/unavailable.jpg'); ?>" width="50%" height="50%">
									</div>
								</div>
							</div>
							<button type="submit" class="btn btn-primary" name="submit_journal" id="submit_journal"><span class="oi oi-check"></span> Save</button>
						</form>
						</div>
					</div>
				</div>
				<div class="tab-pane fade" id="v-pills-add-article" role="tabpanel" aria-labelledby="v-add-article-tab" tabindex="0">
					<div class="card border-dark">
						<div class="card-body">
							<h3>Add Article</h3>
							<p class="pt-2 text-primary mb-1"><span class="fa fa-info-circle text-primary me-1 "></span>PDF - 20 MB file size limit</span></p>
							<p class="text-primary mb-1"><span class="fa fa-info-circle text-primary me-1 "></span>File Naming Format - Volume_issue_yyyy_PrimaryAuthorLastNameFirstName_concatenatedArticleTitle</p>
							<p class="text-primary mb-3"><span class="fa fa-info-circle text-primary me-1 "></span>Please do not include special characters in file name except underscore (_)</p>
							<p class="fs-6">Article Information</p>
							<form id="form_add_article">
								<div class="row">
									<div class="col-6">
										<div class="mb-3">
											<label for="art_year" class="form-label">Year</label>
											<select class="form-control" id="art_year" name="art_year">
												<option value="">Select year</option>
												<?php foreach ($u_year as $j): ?>
												<?php echo '<option value=' . $j->jor_year . '>' . $j->jor_year . '</option>'; ?>
												<?php endforeach;?>
											</select>
										</div>
										<div class="mb-3">
											<label for="art_jor_id" class="form-label">Volume, Issue </label>
											<select class="form-control" id="art_jor_id" name="art_jor_id">
												<option value="">Select Volume, Issue </option>
												<?php foreach ($journal_max as $j): ?>
												<?php $issue = (($j->jor_issue == 5) ? 'Special Issue No. 1' 
												: (($j->jor_issue == 6) ? 'Special Issue No. 2' 
												: (($j->jor_issue == 7) ? 'Special Issue No. 3' 
												: (($j->jor_issue == 8) ? 'Special Issue No. 4' 
												: 'Issue ' . $j->jor_issue))));?>
												<?php echo '<option value=' . $j->jor_id . '>' . 'Vol. ' . $j->jor_volume . ', ' . $issue . '</option>'; ?>
												<?php endforeach;?>
											</select>
										</div>
										<div class="mb-3">
											<label for="art_title"  class="form-label">Title of Article</label>
											<div class="bg-white">
											<textarea class="form-control" id="art_title" name="art_title"></textarea></div>
										</div>
										<div class="mb-3">
											<label for="art_keywords"  class="form-label">Keywords</label>
											<input type="text" class="form-control" id="art_keywords" placeholder="ex. Keyword 1,Keyword 2,Keyword 3" name="art_keywords">
										</div>
										<div class="mb-3">
											<label for="art_page"  class="form-label">Page Nos</label>
											<input type="text" class="form-control" id="art_page" placeholder="ex. 100-200" name="art_page">
										</div>
										<div class="mb-3">
											<label for="art_abstract_file"  class="form-label">Abstract <span class="badge rounded-pill bg-success" id="badge_pdf">PDF</span> <span class="badge bg-warning text-dark">20MB Limit</span></label>
											
											<input type="file" class="form-control" id="art_abstract_file" name="art_abstract_file" accept="application/pdf">
										</div>
										<div class="mb-3">
											<label for="art_full_text_pdf" class="form-label">Full Text PDF <span class="badge rounded-pill bg-success" id="badge_text">PDF</span> <span class="badge rounded-pill bg-warning text-dark">20MB Limit</span></label>
											<input type="file" class="form-control" id="art_full_text_pdf" name="art_full_text_pdf" accept="application/pdf" >
										</div>
									
									</div>
								</div>
								<div class="row">
									<div class="col-10">
										<div class="mb-3" >
											<div class="row">
												<div class="col-3 autocomplete">
													<label for="art_author" class="form-label">Author</label>
													<input type="text" class="form-control" id="art_author_p" name="art_author"  placeholder="Search by name or specialization">
												</div>
												<div class="col-3">
													<label for="art_affiliation" class="form-label">Affiliation</label>
													<input type="text" class="form-control" id="art_affiliation_p" name="art_affiliation">
												</div>
												<div class="col-3">
													<label for="art_email" class="form-label">Email Address</label>
													<input type="text" class="form-control" id="art_email_p" name="art_email" placeholder="Enter a valid email">
												</div>
											</div>
										</div>
										<span id="coauthors"></span>
									</div>
								</div>
								<div class="row">
									<div class="col">
										<button type="button" id="btn-add-coauthor" class="btn btn-secondary"><span class="oi oi-people"></span> Add Co-Author</button>
										<button type="submit" class="btn btn-primary" name="submit_article" id="submit_article"><span class="oi oi-check"></span> Save</button>
									</div>
								</div>
								
							</form>
						</div>
					</div>
				</div>
				<div class="tab-pane fade" id="v-pills-article-list" role="tabpanel" aria-labelledby="v-pills-article-list-tab" tabindex="0">
					<div class="card border-dark">
						<div class="card-body">
							<div class="row">
							<h3>All Articles</h3>
							<div class='table-responsive mt-3'>
								<table id="table-all-articles" class="table table-hover w-100">
									<thead>
										<tr>
											<th scope="col">#</th>
											<th scope="col">Title</th>
											<th scope="col">Volume</th>
											<th scope="col">Issue</th>
											<th scope="col">Year</th>
											<th scope="col">Author</th>
											<th scope="col">File</th>
											<th scope="col">Abstract hits</th>
											<th scope="col">PDF requests</th>
											<th scope="col">Cited</th>
										</tr>
									</thead>
									<tbody>
										<?php $c = 1;foreach ($articles as $a): ?>
										
										<?php $issue = (($a->jor_issue == 5) ? 'Special Issue No. 1' 
											: (($a->jor_issue == 6) ? 'Special Issue No. 2' 
											: (($a->jor_issue == 7) ? 'Special Issue No. 3' 
											: (($a->jor_issue == 8) ? 'Special Issue No. 4' : 'Issue ' . $a->jor_issue))));
											$pdf = $this->Article_model->count_pdf($a->art_id);
											$abs = $this->Article_model->count_abstract($a->art_id);
											$cite = $this->Article_model->count_citation($a->art_id);
											$abs_class = ($abs > 0 ) ? 'text-primary' : 'text-muted';
											$pdf_class = ($pdf > 0 ) ? 'text-primary' : 'text-muted';
											$cite_class = ($cite > 0 ) ? 'text-primary' : 'text-muted';?>
								
										<tr>
											<td><?php echo $c++; ?></td>
											<td><a href="javascript:void(0);" class="text-decoration-none" onclick="edit_article(<?php echo $a->art_id; ?>);"><?php echo $a->art_title; ?></a></td>
											<td><?php echo $a->jor_volume; ?></td>
											<td><?php echo $issue; ?></td>
											<td><?php echo $a->jor_year; ?></td>
											<td><?php echo $a->art_author; ?></td>
											<td>
												<?php 
													$href = base_url('assets/uploads/pdf/');
													$server_dir = '/var/www/html/ejournal/assets/uploads/pdf/';
													$get_file = filesize($server_dir . $a->art_full_text_pdf);
													$size = ($get_file >= 1048576) ? round($get_file / 1024 / 1024, 1) . ' MB'
															: (($get_file >= 1024) ? round($get_file / 1024, 1) . ' KB'
															: (round($get_file, 1) . ' bytes'));
													$color = (round($get_file / 1024 / 1024, 1) > 20) ? 'text-danger' : '';

													echo '<a href="' . $href . $a->art_full_text_pdf . '" class="' . $color . ' text-decoration-none" target="_blank">' . $size .'</a>';?> 
											</td>
											<td><a href="javascript:void(0);" class="<?php echo $abs_class;?> fw-bold text-decoration-none" onclick="get_hits_info(<?php echo $a->art_id; ?>)"><?php echo $abs; ?></a></td>
											<td><a href="javascript:void(0);" class="<?php echo $pdf_class;?> fw-bold text-decoration-none" onclick="get_client_info(<?php echo $a->art_id; ?>)"><?php echo $pdf; ?></a></td>
											<td><a href="javascript:void(0);" class="<?php echo $cite_class;?> fw-bold text-decoration-none" onclick="get_citees(<?php echo $a->art_id; ?>)"><?php echo $cite; ?></a></td>
													
										</tr>
										<?php endforeach;?>
									</tbody>
								</table>
							</div>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane fade" id="v-pills-journal-list" role="tabpanel" aria-labelledby="v-pills-journal-list-tab" tabindex="0">
					<div class="card border-dark">
						<div class="card-body">
							<div class="row">
							<h3>All Journals</h3>
								<div class='table-responsive mt-3'>
									<table id="table-journals" class="table table-hover">
										<thead>
											<tr>
												<th scope="col">#</th>
												<th scope="col">Volume</th>
												<th scope="col">Issue</th>
												<th scope="col">Month</th>
												<th scope="col">Year</th>
												<th scope="col">ISSN</th>
												<th scope="col">Articles</th>
												<th scope="col">Date Created</th>
												<th scope="col">Action</th>
											</tr>
										</thead>
										<tbody>
											<?php $c = 1;foreach ($journal as $j): ?>
											<?php $issue = (
												($j->jor_issue == 5) ? 'Special Issue No. 1' :
												(($j->jor_issue == 6) ? 'Special Issue No. 2' :
													(($j->jor_issue == 7) ? 'Special Issue No. 3' :
														(($j->jor_issue == 8) ? 'Special Issue No. 4' : 'Issue ' . $j->jor_issue)))
											);?>
											<tr >
												<td></td>
												<td><?php echo $j->jor_volume; ?></td>
												<td><?php echo $issue; ?></td>
												<td><?php echo $j->jor_month; ?></td>
												<td><?php echo $j->jor_year; ?></td>
												<td><?php echo $j->jor_issn; ?></td>
												<td>
													<?php if ($this->Article_model->count_article_by_journal($j->jor_id) > 0) {
														echo '<a href="javascript:void(0);"  class="fw-bold text-primary text-decoration-none cursor-pointer" role="button" onclick="view_articles(\'' . $j->jor_id . '\',\'' . $j->jor_volume . '\',\'' . $j->jor_issue . '\',\'' . $j->jor_month . '\',\'' . $j->jor_year . '\')">' . $this->Article_model->count_article_by_journal($j->jor_id) . ' Article(s)</a>';
													} else {echo '0';}
													?>
												</td>
												<td><?php echo $j->date_created; ?></td>
												<td>
													<?php if ($this->session->userdata('_prv_edt') == 1) {?>
													<button type="button" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Journal" class="btn btn-light btn-sm" onclick="edit_journal('<?php echo $j->jor_id; ?>','<?php echo $this->Article_model->count_article_by_journal($j->jor_id); ?>')"><span class="oi oi-pencil"></span></button>
													<?php }?>
													<?php if ($this->session->userdata('_prv_add') == 1) {?>
													<button type="button" data-bs-toggle="tooltip" data-bs-placement="top" title="Add Article" class="btn btn-primary btn-sm" onclick="add_article('<?php echo $j->jor_year; ?>','<?php echo $j->jor_id; ?>')" ><span class="oi oi-plus"></span></button>
													<?php }?>
												</td>
											</tr>
											<?php endforeach;?>
										</tbody>
									</table>
								</div>
								<div class="collapse" id="articles">
									<hr class="my-4">
									<p class="h3">Articles</p>
									<div class="table-responsive">
										<table class="table table-hover" id="table-articles"  style="table-layout: auto">
											<thead>
												<tr>
													<th scope="col">Title</th>
													<th scope="col" width="2%">Abstract Hits</th>
													<th scope="col" width="2%">Full Text Requests</th>
													<th scope="col">Date Added</th>
													<th scope="col" >Action</th>
												</tr>
											</thead>
											<tbody>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane fade" id="v-pills-client-list" role="tabpanel" aria-labelledby="v-pills-client-list-tab" tabindex="0">
					<div class="card border-dark">
						<div class="card-body">
						<h3>Client Information</h3>
						<ul class="nav nav-tabs mt-3" id="myTabx" role="tablist">
							<li class="nav-item" role="presentation">
								<a class="nav-link active" id="client-tab" data-bs-toggle="tab" data-bs-target="#client_info" role="tab" aria-controls="home" aria-selected="true" href="#">Details</a>
							</li>
							<li class="nav-item" role="presentation">
								<a class="nav-link" onclick="generate_sex_chart()" id="client-grraph-tab" data-bs-toggle="tab" data-bs-target="#client_graph" role="tab" aria-controls="profile" aria-selected="false" href="#">Graph</a>
							</li>
						</ul>
						<div class="tab-content mt-3" id="clientTabContent">
							<div class="tab-pane fade show active" id="client_info" role="tabpanel">
								<div class="table-responsive">
									<table id="table-clients" class="table table-hover">
										<thead>
											<tr>
												<th scope="col">#</th>
												<th scope="col">Full Text PDF</th>
												<th scope="col">Downloader</th>
												<th scope="col">Sex</th>
												<th scope="col">Affiliation</th>
												<th scope="col">Country</th>
												<th scope="col">Email</th>
												<th scope="col">Purpose</th>
												<th scope="col">Ip Address</th>
												<th scope="col">Date&Time Visited</th>
												<th scope="col">Article Reference ID</th>
											</tr>
										</thead>
										<tbody>
											<?php $i = 1;foreach ($clients as $c): ?>
											<tr>
												<td></td>
												<td><?php echo $c->art_title; ?></td>
												<td><?php echo $c->clt_title . ' ' . $c->clt_name; ?></td>
												<td><?php echo ($c->clt_sex == 1) ? 'Male' : 'Female'; ?></td>
												<td><?php echo $c->clt_affiliation; ?></td>
												<td><?php echo $c->clt_country; ?></td>
												<td><?php echo $c->clt_email; ?></td>
												<td><?php echo $c->clt_purpose; ?></td>
												<td><?php echo $c->clt_ip_address; ?></td>
												<td><?php echo $c->clt_download_date_time; ?></td>
												<td>ID:<?php echo $c->clt_journal_downloaded_id; ?></td>
											</tr>
											<?php endforeach;?>
										</tbody>
									</table>
								</div>
							</div>
							<div class="tab-pane fade" id="client_graph" role="tabpanel">
								<div class="row">
									<div class="col-md-6">
										<div id="client_bar">
										</div>
									</div>
									<div class="col-md-6">
										<div id="client_pie">
										</div>
									</div>
								</div>
								<div class="row mt-4">
									<div class="col">
										<div id="client_monthly_line">
										</div>
									</div>
								</div>
								<div class="row mt-4">
									<div class="col">
										<div id="client_line">
										</div>
									</div>
								</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane fade" id="v-pills-viewers-list" role="tabpanel" aria-labelledby="v-pills-viewers-list-tab" tabindex="0">
					<div class="card border-dark">
						<div class="card-body">
						<h3>Abstract Hits</h3>
						<div class="table-responsive mt-3">
							<table id="table-viewers" class="table table-hover">
								<thead>
									<tr>
										<th scope="col">#</th>
										<th scope="col">Title of Article Viewed</th>
										<th scope="col">IP Address</th>
										<th scope="col">Date&Time Viewed</th>
										<th scope="col">Article Reference ID</th>
									</tr>
								</thead>
								<tbody>
									<?php $i = 1;foreach ($viewers as $v): ?>
									<tr>
										<td></td>
										<td><?php echo $v->art_title; ?></td>
										<td><?php echo $v->hts_ip_address; ?></td>
										<td><?php echo $v->date_viewed; ?></td>
										<td>ID:<?php echo $v->hts_art_id; ?></td>
									</tr>
									<?php endforeach;?>
								</tbody>
							</table>
						</div>
						</div>
					</div>
				</div>
				<div class="tab-pane fade" id="v-pills-citees-list" role="tabpanel" aria-labelledby="v-pills-citees-list-tab" tabindex="0">
					<div class="card border-dark">
						<div class="card-body">
						<h3>All Citees</h3>
						<div class="table-responsive mt-3">
							<table id="table-citees" class="table table-hover" style="font-size:14px;table-layout: auto">
								<thead>
									<tr>
										<th scope="col">#</th>
										<th scope="col">Name</th>
										<th scope="col">Email</th>
										<th scope="col">Article cited</th>
										<th scope="col">Article Reference ID</th>
									</tr>
								</thead>
								<tbody>
									<?php $i = 1;foreach ($citees as $c): ?>
									<tr>
										<td></td>
										<td><?php echo $c->cite_name; ?></td>
										<td><?php echo $c->cite_email; ?></td>
										<td><?php echo $c->art_title;?></td>
										<td>ID:<?php echo $c->art_id; ?></td>
									</tr>
									<?php endforeach;?>
								</tbody>
							</table>
						</div>
						</div>
					</div>
				</div>
				<div class="tab-pane fade" id="v-pills-add-editorial" role="tabpanel" aria-labelledby="v-pills-add-editorial-tab" tabindex="0">
					<div class="card border-dark">
						<div class="card-body">
							<h3>Add Editorial Board and Staff</h3>
							<form id="add_editorial_form">
								<div class="row mt-3">
									<div class="col">
										<div class="mb-3 row">
											<div class="form-group col">
												<label for="edt_year" class="form-label">Year</label>
												<select class="form-select" id="edt_year" name="edt_year">
													<option value="">Select Year</option>
													<?php for($i=date('Y'); $i>='1993';$i--){ ?>
													<?php echo '<option value=' . $i . '>' . $i . '</option>'; ?>
													<?php }?>
												</select>
											</div>
											<div class="form-group col">
												<label for="edt_volume"  class="form-label">Volume</label>
												<select class="form-select" id="edt_volume" name="edt_volume" placeholder="ex. X">
													<option value='' class="text-dark">Select Volume</option>
													<?php foreach ($u_journal as $j): ?>
													<?php echo '<option value=' . $j->jor_volume . ' class="text-dark text-uppercase">' . $j->jor_volume . '</option>'; ?>
													<?php endforeach;?>
												</select>
											</div>
											<div class="form-group col">
												<label for="edt_issue" class="form-label">Issue</label>
												<select class="form-select" id="edt_issue" name="edt_issue">
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
										</div>
										<div class="mb-3">
											<label for="edt_name" class="form-label">Name</label>
											<input type="text" class="form-control" id="edt_name" name="edt_name" placeholder="First name, Middle name, Last name">
										</div>
										<div class="mb-3">
											<label for="edt_email" class="form-label">Email Address</label>
											<input type="text" class="form-control" id="edt_email" name="edt_email" placeholder="Enter a valid email">
										</div>
										<div class="mb-3">
											<label for="edt_sex" class="form-label">Sex</label>
											<select class="form-select w-50" id="edt_sex" name="edt_sex">
												<option value="">Sex</option>
												<?php foreach ($sex as $s): ?>
												<?php echo '<option value=' . $s->sex_id . '>' . $s->sex_name . '</option>'; ?>
												<?php endforeach;?>
											</select>
										</div>
										<div class="mb-3">
											<label for="edt_position" class="form-label">Editorial Position</label>
											<select class="form-select" name="edt_position" id="edt_position">
												<option value="">Select Editorial Position</option>
												<?php foreach($editorial_board_position as $row):?>
													<?php echo '<option value=' . $row->role_name . '>' . $row->role_name . '</option>'; ?>
												<?php endforeach ?>
												<option value="Editorial Staff">Editorial Staff</option>
											</select>
											<!-- <input type="text" class="form-control" id="edt_position" name="edt_position" placeholder="ex. Editor-in-Chief"> -->
										</div>	
										<div class="mb-3">
											<label for="edt_position_affiliation" class="form-label">Employment Position</label>
											<input type="text" class="form-control" id="edt_position_affiliation" name="edt_position_affiliation" placeholder="ex. Professor">
										</div>
										<div class="mb-3">
											<label for="edt_affiliation" class="form-label">Affiliation</label>
											<input type="text" class="form-control" id="edt_affiliation" name="edt_affiliation" placeholder="ex. UP Manila">
										</div>
										<div class="mb-3">
											<label for="edt_address" class="form-label">Address</label>
											<input type="text" class="form-control" id="edt_address" name="edt_address" placeholder="ex. Juan Dela Cruz Street, Manila">
										</div>
										<div class="mb-3">
											<label for="edt_country" class="form-label">Country</label>
											<select class="form-select" id="edt_country" name="edt_country" placeholder="Select Country">
												<option value="">Select Country</option>
												<?php foreach ($country as $c): ?>
												<?php $field = 'edt_country';
												echo '<option value=' . $c->country_id  . '>' . $c->country_name . '</option>';?>
												<?php endforeach;?>
											</select>
										</div>
										<div class="mb-3">
											<label for="edt_specialization" class="form-label">Specialization</label>
											<span class="badge rounded-pill bg-secondary">Separate in comma</span>
											<input type="text" class="form-control" id="edt_specialization" name="edt_specialization" placeholder="ex. Specialization 1, Specialization 2">
										</div>
										<div class="mb-3">
											<button type="submit" class="btn btn-primary me-1" name="submit_editorial"><span class="oi oi-check"></span> Save</button>	
										</div>
									</div>
									<div class="col">
										<div class="mb-3">
											<label for="edt_photo" class="form-label">Upload Photo <span class="badge rounded-pill bg-success">JPG</span> <span class="badge rounded-pill bg-warning text-dark">200 x 250 pixels</span> <span class="badge rounded-pill bg-warning text-dark">2MB Limit</span></label>
											<input type="file" class="form-control" id="edt_photo" name="edt_photo" accept="image/jpeg" >
										</div>
										<div class="mb-3">
											<label for="editorial_photo" class="form-label">Preview </label><br/>
											<img class="me-3 img-thumbnail" id="editorial_photo" src="<?php echo base_url('assets/images/unavailable.jpg'); ?>" style="width: 200px; height: 250px; object-fit: cover;">
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
				<div class="tab-pane fade" id="v-pills-editorial-list" role="tabpanel" aria-labelledby="v-pills-editorial-list-tab" tabindex="0">
					<div class="card border-dark">
						<div class="card-body">
						<h3>Editorial Board</h3>
						<div class='table-responsive'>
							<table id="table-editorials" class="table table-hover">
								<thead>
									<tr>
										<th scope="col"></th>
										<th scope="col">Journal</th>
										<th scope="col">Photo</th>
										<th scope="col">Name</th>
										<th scope="col">Sex</th>
										<th scope="col">Editorial Position</th>
										<th scope="col">Employment Position</th>
										<th scope="col">Affiliation</th>
										<th scope="col">Address</th>
										<th scope="col">Country</th>
										<th scope="col">Specialization</th>
										<th scope="col">Email</th>
										<th scope="col">Date Added</th>
										<th scope="col">
											<?php if ($this->session->userdata('_prv_add') == 1) {?>
											<button type="button" class="w-100 btn btn-sm btn-primary" id="btn-add-editorial"><span class="oi oi-plus"></span> Add Editorial</button>
											<?php }?>
										</th>
									</tr>
								</thead>
								<tbody>
									<?php $c = 1;foreach ($editorials as $e): ?>
									<?php $issue = (
													($e->edt_issue == 5) ? 'Special Issue No. 1, ' :
													(($e->edt_issue == 6) ? 'Special Issue No. 2, ' :
													(($e->edt_issue == 7) ? 'Special Issue No. 3, ' :
													(($e->edt_issue == 8) ? ',Special Issue No. 4, ' :
													(($e->edt_issue == 0) ? '' : 'Issue ' . $e->edt_issue . ', '))))
												); ?>
									<tr>
										<input type="hidden" id="delete_edt_id" value="<?php echo $e->edt_id; ?>">
										<td></td>
										<td><?php echo $issue . 'Volume ' . $e->edt_volume . ', ' . $e->edt_year ?></td>
										<!-- <td><img class="img-thumbnail" src="<?php echo base_url('assets/uploads/editorial/'. $e->edt_photo .''); ?>" style="width:50px;height:50px;"></td> -->
										<td><img class="img-thumbnail" src="" style="width:50px;height:50px;"></td>
										<td><?php echo $e->edt_name; ?></td>
										<td><?php echo ($e->edt_sex == 1) ? 'Male' : 'Female'; ?></td>
										<td><?php echo $e->edt_position; ?></td>
										<td><?php echo $e->edt_position_affiliation; ?></td>
										<td><?php echo $e->edt_affiliation; ?></td>
										<td><?php echo $e->edt_address; ?></td>
										<td><?php echo $e->edt_country; ?></td>
										<td><?php echo $e->edt_specialization; ?></td>
										<td><?php echo $e->edt_email; ?></td>
										<td><?php echo $e->date_created; ?></td>
										<td>
											<button type="button" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit" class="btn btn-light text-primary btn-sm w-100" onclick="edit_editorial('<?php echo $e->edt_id; ?>')"><span class="oi oi-pencil" ></span> Edit</button>
											<!-- <button type="button" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete" class="btn btn-danger btn-sm" onclick="_remove('delete-editorial-out')"><span class="oi oi-trash"></span></button> -->
										</td>
									</tr>
									<?php endforeach;?>
								</tbody>
							</table>
						</div>
						</div>
					</div>
				</div>
				<div class="tab-pane fade" id="v-pills-mail" role="tabpanel" aria-labelledby="v-pills-mail-tab" tabindex="0">
					<div class="card border-dark">
						<div class="card-body">
							<h3>Email Notifications</h3>
							<div class='table-responsive mt-3'>
								<table id="table-email-notifications" class="table table-hover w-100" >
									<thead>
										<tr>
											<th>#</th>
											<th>Email subject</th>
											<th>Notification trigger</th>
											<th>CC</th>
											<th>BCC</th>
											<th>Last updated</th>
											<th>Actions</th>
										</tr>
									</thead>
									<tbody>
									<?php $c = 1;foreach ($emails as $e): ?>
									<tr>
										<td><?php echo $c++; ?></td>
										<td  class="fw-bold"><?php echo $e->enc_subject; ?></td>
										<td><?php echo $e->enc_description; ?></td>
										<td class="small"><?php $cc = explode(",",$e->enc_cc);
											foreach($cc as $email){
											echo $email . '</br>';
											} ?>
										</td>
										<td class="small"><?php $bcc = explode(",",$e->enc_bcc);
											foreach($bcc as $email){
											echo $email . '</br>';
											} ?>
										</td>
										<td><?php echo $e->last_updated; ?></td>
										<td>
										<button type="button" class="btn btn-light text-primary btn-sm w-100"
										onclick="edit_email_content(<?php echo $e->enc_process_id;?>)"><span class="fa fa-edit"></span> Edit</button>
										</td>
									</tr>
									<?php endforeach;?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane fade" id="v-pills-policy" role="tabpanel" aria-labelledby="v-pills-policy-tab" tabindex="0">
					<div class="card border-dark">
						<div class="card-body">
						<p class="h3">Manage Editorial Policy</p>
						<div class="row mt-3">
							<div class="col-6">
								<form id="form_policy">
									<div class="mb-3">
										<!-- <div class="upload_cfp d-none"> -->
											<label for="ep_file" class="form-label">Upload File <span class="badge rounded-pill bg-danger">PDF</span> <span class="badge rounded-pill bg-warning text-dark">20MB Limit</span></label>
											<input type="file" class="form-control" id="ep_file" name="ep_file" accept="application/pdf">
										<!-- </div>	 -->
										<!-- <label for="ep_content" class="form-label">Content</label> -->
										<!-- <textarea rows="20" class="form-control" id="ep_content" name="ep_content"><?php echo $editorial_policy; ?></textarea> -->
									</div>
									
									<div class="mb-5">
									<button type="submit" class="btn btn-primary" id="btn_save_policy" name="btn_save_policy"><span class="oi oi-check"></span> Save</button>
									</div>

									<div class="mb-3">
										<table class="table">
											<thead class="text-muted fs-6">
												<th>Archived File</th>
												<th>Date</th>
											</thead>
											<tbody>
												<?php if($editorial_policy) { ?>
													<?php foreach($editorial_policy as $row): ?>
														<?php if($row->ep_is_archive == 1) { ?>
															<tr>
																<td><a class="text-muted fs-6" href="<?php echo base_url('assets/uploads/editorial_policy/'.$row->ep_file.'.pdf');?>" target="_blank"><?php echo $row->ep_file;?>.pdf</a>
																</td>
																<td class="text-muted fs-6"><?php echo $row->created_at;?></td>
														<?php } else { ?>
															<?php $editorial_policy = $row->ep_file;?>
														<?php } ?>
													<?php endforeach ?>
												<?php } else { ?>
													<tr>
														<td colspan="2">No archived yet.</td>
													</tr>
												<?php } ?>
											</tbody>
										</table>
									</div>
								</form>
							</div>
							<div class="col">
								<embed WMODE="transparent" class="border border-secondary" id="ep_file" src="<?php echo base_url('assets/uploads/editorial_policy/'.$editorial_policy.'.pdf#toolbar=0&navpanes=0&scrollbar=0'); ?>" type="application/pdf" width="100%" height="600px">
							</div>
						</div>
						</div>
					</div>
				</div>
				<div class="tab-pane fade" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab" tabindex="0">
					<div class="card border-dark">
						<div class="card-body">
						<p class="h3">Manage Home</p>
						<div class="row mt-3">
							<?php if ($this->session->userdata('_prv_edt') == 1) {?>
							<div class="col-6">
								<form id="form_home">
									<div class="mb-3">
										<label for="home_description" class="form-label">About Content</label>
										<textarea rows="20" class="form-control" id="home_description" name="home_description"><?php echo file_get_contents('./assets/uploads/DO_NOT_DELETE_description.txt'); ?></textarea>
									</div>
									<!-- <div class="mb-3">
										<label for="upload_call_papers" class="form-label">Upload Call for Papers</label>
										<select name="upload_call_papers" id="upload_call_papers" class="form-select">
											<option value="">Select Type</option>
											<option value="1">Image</option>
											<option value="2">PDF</option>
										</select>
									</div> -->
									<!-- <div class="mb-3"> -->
										<!-- <label class="rd_container float-right"><input type="radio" name="upload_only" id="upload_only"  value="1"><span class="checkmark"></span></label> -->
										<!-- <div class="upload_cfpi d-none">
											<label for="upload_cfpi" class="form-label">Upload File <span class="badge rounded-pill bg-success">PDF</span> <span class="badge rounded-pill bg-warning text-dark">20MB Limit</span></label>
											<input type="file" class="form-control" id="upload_cfpi" name="upload_cfpi" accept="application/pdf">
										</div>

										<div class="upload_cfp d-none">
											<label for="upload_cfp" class="form-label">Upload File <span class="badge rounded-pill bg-success">JPG</span> <span class="badge rounded-pill bg-warning text-dark">2MB Limit</span></label>
											<input type="file" class="form-control" id="upload_cfp" name="upload_cfp" accept="image/jpeg">
										</div>			 -->
										<!-- <label class="rd_container float-right"><input type="radio" class="float-right" name="upload_only" id="upload_only" value="2" ><span class="checkmark"></span></label> -->

									<!-- </div> -->
									<!-- <p class="pt-2 text-danger">
										<span class="badge badge-primary"><span class="fa fa-info-circle text-danger"></span></span> PDF - 20 MB file size limit
										<br/><span class="badge badge-primary"><span class="fa fa-info-circle text-danger"></span></span> IMAGE - 2 MB file size limit
									</p> -->
									<!-- <div class="form-group">
										<div class="row">
											<div class="col">
												<div class="card border-secondary" >
													<div class="card-body">
														<label class="rd_container float-right"><input type="radio" name="upload_only" id="upload_only"  value="1"><span class="checkmark"></span></label>
														<h5 class="card-title">PDF </h5>
														<input type="file" class="form-control-file" id="upload_cfp" name="upload_cfp" accept="application/pdf">
													</div>
												</div>
											</div>
											<div class="col">
												<div class="card border-secondary" >
													<div class="card-body">
														<label class="rd_container float-right"><input type="radio" class="float-right" name="upload_only" id="upload_only" value="2" ><span class="checkmark"></span></label>
														<h5 class="card-title">IMAGE <span class="badge badge-success">JPG</span></h5>
														<input type="file" class="form-control-file" id="upload_cfpi" name="upload_cfpi" accept="image/jpeg">
													</div>
												</div>
											</div>
										</div>
									</div> -->
									<div class="mb-3">
									<button type="submit" class="btn btn-primary" id="btn_save_home" name="btn_save_home"><span class="oi oi-check"></span> Save</button>
									</div>
								</form>
							</div>
							<?php }?>
							<!-- <div class="col-6" style="height:600px">
								<p class="form-label">Uploaded File</p>
								<?php
								$filename = 'assets/uploads/';
								if (file_exists($filename . 'DO_NOT_DELETE_callforpapers.pdf')) {?>
								<embed WMODE="transparent" class="border border-secondary" id="embed_cfp" src="<?php echo base_url('assets/uploads/DO_NOT_DELETE_callforpapers.pdf#toolbar=0&navpanes=0&scrollbar=0'); ?>" type="application/pdf" width="100%" height="100%">
								<?php } else {?>
								<img class="border border-secondary" id="embed_cfp" src="<?php echo base_url('assets/uploads/DO_NOT_DELETE_callforpapers.jpg'); ?>" width="100%" height="auto" >
								<?php }?>
							</div> -->
						</div>
						</div>
					</div>
				</div>
				<div class="tab-pane fade" id="v-pills-guidelines" role="tabpanel" aria-labelledby="v-pills-guidelines-tab" tabindex="0">
					<div class="card border-dark">
						<div class="card-body">
						<h3>Manage Guidelines</h3>
						<div class="row mt-3">
							<?php if ($this->session->userdata('_prv_edt') == 1) {?>
							<div class="col-10">
								<div class="form-group">
									<form id="form_guidelines">
										<div class="mb-3">
											<!-- <label for="gd_content" class="form-label">Content</label> -->
											<textarea rows="20" class="form-control" id="gd_content" name="gd_content"><?php echo $guidelines; ?></textarea>
										</div>
										<div class="mb-3">
											<button type="submit" class="btn btn-primary" id="btn_save_guidelines" name="btn_save_guidelines"><span class="oi oi-check"></span> Save</button>
										</div>
										<!-- <div class="mb-3">
											<label for="upload_guidelines" class="form-label">Upload File <span class="badge rounded-pill bg-success">PDF</span></label>
											<input type="file" class="form-control" id="upload_guidelines" name="upload_guidelines" accept="application/pdf">
										</div>
										<div class="mb-3">
											<button type="submit" class="btn btn-primary" id="btn_upload_guidelines" name="btn_upload_guidelines"><span class="oi oi-check"></span> Upload</button>
										</div> -->
									</form>
								</div>
							</div>
							<?php }?>
							<!-- <div class="col-6">
								<embed class="border border-secondary" WMODE="transparent" id="embed_guidelines" src="<?php echo base_url('assets/uploads/DO_NOT_DELETE_guidelines.pdf#toolbar=0&navpanes=0&scrollbar=0'); ?>" width="100%" height="700px" type="application/pdf">
							</div> -->
						</div>
						</div>
					</div>
				</div>
				<div class="tab-pane fade" id="v-pills-logs" role="tabpanel" aria-labelledby="v-pills-logs-tab" tabindex="0">
					<div class="card border-dark">
						<div class="card-body">
							<h3>Activity Logs</h3>
							<div class="d-flex gap-1 mt-3">
								<div class="mb-3">
									<label for="dateFrom" class="form-label">Start Date</label>
									<input type="date" id="dateFrom" class="form-control">
								</div>
								<div class="mb-3">
									<label for="dateTo" class="form-label">End Date</label>
									<input type="date" id="dateTo" class="form-control">
								</div>
							</div>
							<div class='table-responsive'>
								<table id="activityLogsTable" class="table table-hover">
									<thead>
										<tr>
											<th scope="col">#</th>
											<th scope="col">User name</th>
											<th scope="col">Activity</th>
											<th scope="col">IP Adress/Browser</th>
											<th scope="col">Date</th>
										</tr>
									</thead>
									<tbody>
										<?php if ($all_logs != null) {?>
										<?php $c = 1;foreach ($all_logs as $row): ?>
										<?php 
										$timestamp = strtotime($row->date_created);
										$formattedDate = date('Y-m-d H:i:s A', $timestamp); 
										?>

										<tr>
											<td></td>
											<td><?= $row->email ?? '-'; ?></td>
											<td><?= $row->log_action; ?></td>
											<td><?= $row->log_browser ?? '-'; ?> / <?= $row->log_browser ?? '-'; ?></td>
											<td><?= $formattedDate; ?></td>
										</tr>
											<?php endforeach;?>
											<?php }?>
									</tbody>
								</table>
							</div>
							<?php if($all_logs){ ?>
							<div class="mb-3">
								<button class="btn btn-outline-danger" id="clearLogs" data-bs-toggle="modal" data-bs-target="#clear_log_modal">Clear Logs</button>
							</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</main>
	
<!-- <td><?php echo $e->enc_user_group; ?></td> -->