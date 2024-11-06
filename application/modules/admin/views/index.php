
<!-- NAVIGATION -->
	<nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-primary">
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarTogglerDemo01">
			<a class="navbar-brand ml-0 pl-0" href="javascript:void(0);">
				<img src="<?php echo base_url("assets/oprs/img/nrcp.png"); ?>" height="40" width="40">
				<img src="<?php echo base_url("assets/images/skms.png"); ?>" height="40" width="80">
				<img src="<?php echo base_url("assets/oprs/img/ejicon-07.png"); ?>" height="40" width="40">
			eJournal Admin</a>
			
			<ul class="navbar-nav bd-navbar-nav flex-row">
				<li class="nav-item">
					<a class="nav-item nav-link" href="http://researchjournal.nrcp.dost.gov.ph/" target="_blank"><span class="oi oi-globe"></span> Visit Client Page</a>
				</li>
			</ul>
			<ul class="navbar-nav flex-row ml-md-auto d-none d-md-flex">
				<!-- <li class="nav-item dropdown">
					<div class="icon-wrapper  float-right">
						<a class="nav-link p-3 dropdown-toggle" data-toggle="dropdown"><span class="oi oi-envelope-closed"></span>
						<span class="badge badge-danger badge-nav"></span>
					</a>
					<span id="new_msg"></span>
				</div>
			</li> -->
			<li class="nav-item">
				<a class="nav-link p-3"><span class="oi oi-bell"></span></a>
			</li>
			<?php if ($this->session->userdata('_oprs_logged_in')) {?>
			<li class="nav-item dropdown">
				<a href="javascript:void(0);" class="nav-item nav-link dropdown-toggle mr-md-2" id="user_dropdown" data-toggle="dropdown">
					<?php if ($this->session->userdata('_oprs_user_dp') != '') {?>
					<img class="rounded-circle" width="20px" height="19px" src="<?php echo base_url("assets/uploads/dp/" . $this->session->userdata('user_dp') . ""); ?>">
					<?php } else {?>
					<span class="oi oi-person mr-1"></span>
					<?php }?>
					<?php echo $this->session->userdata('_oprs_username'); ?>
				</a>
				<div class="dropdown-menu dropdown-menu-right">
					<!-- <a class="dropdown-item " href="javascript:void(0);" data-toggle="modal" data-target="#set_dp"><span class="oi oi-camera-slr"></span> Set Display Picture</a> -->
					<a class="dropdown-item " href="javascript:void(0);" data-toggle="modal" data-target="#change_pass"><span class="oi oi-shield"></span> Change Password</a>
					
					<a class="btn btn-link drop<span classdown-item" data-toggle="modal" data-target="#database_modal"><span class="oi oi-hard-drive" style="width:20px"></span> Backup/Restore Database</a>
					<a href="javascript:void(0);" class="dropdown-item" data-toggle="modal" data-target="#logoutModal"> <span class="oi oi-account-logout "></span> Logout</a>
				</div>
			</li>
			<?php }?>
		</ul>
		</div>
	</nav>
<!-- /.NAVIGATION -->

<div class="container-fluid " style="padding-top:70px;">
	<div class="row ">
		<!-- SIDE NAVIGATION -->
		<div class="col-2">
			<div class="list-group list-group-flush sticky" id="list-tab" role="tablist" >
				<a class="list-group-item list-group-item-action active" id="dashboard" data-toggle="tab" href="#dashboard-tab" role="tab" aria-controls="dashboard"><i class="oi oi-shield" title="oi-shield" aria-hidden="true"></i> Dashboard</a>
				<?php if ($this->session->userdata('_prv_add') == 1) {?>
				<a class="list-group-item list-group-item-action" id="create-journal" data-toggle="tab" href="#create-journal-tab" role="tab" aria-controls="home"><i class="oi oi-plus" title="oi-plus" aria-hidden="true"></i> Create Journal</a>
				<a class="list-group-item list-group-item-action " id="add-article" data-toggle="tab" href="#add-article-tab" role="tab" aria-controls="home"><i class="oi oi-book" title="oi-book" aria-hidden="true"></i> Add Article</a>
				<?php }?>
				<a class="list-group-item list-group-item-action " id="article-list" data-toggle="tab" href="#all_articles" role="tab" aria-controls="home">
				<div class="d-flex justify-content-between"><i class="oi oi-eye mr-1" title="oi-eye" aria-hidden="true"></i> View Articles 
			    <span class="badge badge-primary ml-auto"><?php echo $art_count; ?></span></div></a>
				<a class="list-group-item list-group-item-action " id="journal-list" data-toggle="tab" href="#journals" role="tab" aria-controls="home">
				<div class="d-flex justify-content-between"><i class="oi oi-eye mr-1" title="oi-eye" aria-hidden="true"></i> View Journals
			    <span class="badge badge-primary ml-auto"><?php echo $jor_count; ?></span></div></a>
				<a class="list-group-item list-group-item-action " id="client-list" data-toggle="tab" href="#clients" role="tab" aria-controls="home">
				<div class="d-flex justify-content-between"><i class="oi oi-eye mr-1" title="oi-eye" aria-hidden="true"></i> View Clients
			    <span class="badge badge-primary ml-auto"><?php echo $client_count; ?></span></div></a>
				<a class="list-group-item list-group-item-action " id="viewers-list" data-toggle="tab" href="#viewers" role="tab" aria-controls="home">
				<div class="d-flex justify-content-between"><i class="oi oi-eye mr-1" title="oi-eye" aria-hidden="true"></i> View Abstract Hits
			    <span class="badge badge-primary ml-auto"><?php echo $hit_count; ?></span></div></a>
				<a class="list-group-item list-group-item-action " id="citees-list" data-toggle="tab" href="#citees" role="tab" aria-controls="home">
				<div class="d-flex justify-content-between"><i class="oi oi-eye mr-1" title="oi-eye" aria-hidden="true"></i> View Citees
			    <span class="badge badge-primary ml-auto"><?php echo $cite_count; ?></span></div></a>
				<a class="list-group-item list-group-item-action " data-parent="#manage-editorials" data-toggle="collapse" href="#manage-editorials" role="tab" aria-controls="home"><i class="oi oi-grid-two-up" title="oi-grid-two-up" aria-hidden="true"></i> Manage Editorials </a>
				<div class="collapse" id="manage-editorials" class="list-group">
					<?php if ($this->session->userdata('_prv_add') == 1) {?>
					<a data-toggle="tab" data-parent="#manage-editorials" id="add-editorial"  href="#add-editorial-tab" role="tab" class="list-group-item list-group-item-action  sub-item "><i class="oi oi-people" title="oi-people" aria-hidden="true" ></i> Add Editorial</a>
					<?php }?>
					<a data-toggle="tab" data-parent="#manage-editorials" id="editorial-list"  href="#editorials" role="tab" class="list-group-item list-group-item-action  sub-item"><i class="oi oi-eye" title="oi-eye" aria-hidden="true"></i> Editorial Boards</a>
				</div>
				<a class="list-group-item list-group-item-action" data-toggle="tab" href="#guidelines" role="tab" aria-controls="home"><i class="oi oi-task" title="oi-task" aria-hidden="true"></i> Manage Guidelines</a>
				<a class="list-group-item list-group-item-action" data-toggle="tab" href="#home" role="tab" aria-controls="home"><i class="oi oi-home" title="oi-home" aria-hidden="true"></i> Manage Home</a>
				<a class="list-group-item list-group-item-action" data-toggle="tab" href="#mail" role="tab" aria-controls="mail"><i class="oi oi-envelope-open" title="oi-mail" aria-hidden="true"></i> Manage Email Notifications</a>
				<a class="list-group-item list-group-item-action" data-toggle="tab" href="#logs" role="tab" aria-controls="logs"><i class="oi oi-envelope-open" title="oi-mail" aria-hidden="true"></i> Activity Logs</a>
				<?php if ($this->session->userdata('_oprs_type_num') == 8 || $this->session->userdata('_oprs_type_num') == 8 || $this->session->userdata('_oprs_type_num') == 3) {?>
				<a class="list-group-item list-group-item-action text-info font-weight-bold" href="<?php echo base_url('oprs/dashboard'); ?>"><i class="oi oi-pencil" title="oi-pencil" aria-hidden="true"></i> eReview</a>
				<?php }?>
			</div>
		</div>
		<!-- /.SIDE NAVIGATION -->

		<div class="col-10">
			<div class="tab-content" id="nav-tabContent">

				<!-- HOME -->
				<div class="tab-pane fade" id="home" role="tabpanel" aria-labelledby="home">
					<div class="jumbotron text-white">
						<div class="row">
							<?php if ($this->session->userdata('_prv_edt') == 1) {?>
							<div class="col-6">
								<p class="h3">Manage Home</p>
								<p class="lead"></p>
								<form id="form_home">
									<div class="form-group">
										<label for="upload_guidelines">Description</label>
										<textarea rows="20" class="form-control" id="home_description" name="home_description"><?php echo file_get_contents('./assets/uploads/DO_NOT_DELETE_description.txt'); ?></textarea>
									</div>
									<p>Upload Call for Papers <small class="text-danger">(Select one option only)</small></p>
									<p class="pt-2 text-danger">
										<span class="badge badge-primary"><span class="oi oi-info text-danger"></span></span> PDF - 20 MB file size limit
										<br/><span class="badge badge-primary"><span class="oi oi-info text-danger"></span></span> IMAGE - 2 MB file size limit
									</p>
									<div class="form-group">
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
									</div>
									<button type="submit" class="btn btn-primary" id="btn_save_home" name="btn_save_home"><span class="oi oi-check"></span> Save</button>
								</form>
							</div>
							<?php }?>
							<div class="col-6" style="height:600px">
								<p class="lead">Uploaded File</p>
								<?php
								$filename = 'assets/uploads/';
								if (file_exists($filename . 'DO_NOT_DELETE_callforpapers.pdf')) {?>
								<embed WMODE="transparent" class="border border-secondary" id="embed_cfp" src="<?php echo base_url('assets/uploads/DO_NOT_DELETE_callforpapers.pdf#toolbar=0&navpanes=0&scrollbar=0'); ?>"  style="overflow: hidden; height: 100%;
								width: 100%; position: absolute;" type="application/pdf">
								<?php } else {?>
								<img class="border border-secondary" id="embed_cfp" src="<?php echo base_url('assets/uploads/DO_NOT_DELETE_callforpapers.jpg'); ?>" width="100%" height="auto" >
								<?php }?>
							</div>
						</div>
					</div>
				</div>
				<!-- /.HOME -->

				<!-- GUIDELINES -->
				<div class="tab-pane fade" id="guidelines" role="tabpanel" aria-labelledby="guidelines">
					<div class="jumbotron text-whtie">
						<div class="row">
							<?php if ($this->session->userdata('_prv_edt') == 1) {?>
							<div class="col-6">
								<p class="h3">Manage Guidelines</p>
								<p class="pt-2 text-danger">
									<span class="badge badge-primary"><span class="oi oi-info text-danger"></span></span> PDF - 20 MB file size limit
								</p>
								<p class="lead"></p>
								<div class="form-group">
									<form id="form_guidelines">
										<label for="upload_guidelines">Upload <span class="badge badge-danger">PDF</span> file only</label>
										<input type="file" class="form-control-file" id="upload_guidelines" name="upload_guidelines" accept="application/pdf">
										<br/><button type="submit" class="btn btn-primary" id="btn_upload_guidelines" name="btn_upload_guidelines"><span class="oi oi-check"></span> Upload</button>
									</form>
								</div>
							</div>
							<?php }?>
							<div class="col-6">
								<embed class="border border-secondary" WMODE="transparent" id="embed_guidelines" src="<?php echo base_url('assets/uploads/DO_NOT_DELETE_guidelines.pdf#toolbar=0&navpanes=0&scrollbar=0'); ?>" width="100%" height="700px" type="application/pdf">
							</div>
						</div>
					</div>
				</div>
				<!-- /.GUIDELINES -->

				<!-- DASHBOARD -->
				<div class="tab-pane fade show active" id="dashboard-tab" role="tabpanel" aria-labelledby="dashboard">
					<div class="row">
						<div class="col-3">
							<div class="card text-white">
								<div class="card-body bg-primary">
									<h3 class="card-title lead text-center">Journals</h3>
									<div class="row">
										<div class="col text-center">
											<h5 class="card-title" style="font-size:60px">
											<span class="oi oi-book text-danger" style="font-size:60px"></span>
											<?php if ($jor_count > 0) {echo $jor_count;} else {echo 0;}?></h5>
											<p class="text-muted"><?php echo $art_count; ?> Articles</p>
										</div>
									</div>
								</div>
								<div class="card-footer bg-primary">
									<a href="javascript:void(0);" id="view_journals" class="text-white">View details <span class="oi oi-caret-right float-right"></span></a>
								</div>
							</div>
						</div>
						<div class="col-3">
							<div class="card text-white">
								<div class="card-body bg-primary">
									<h3 class="card-title lead text-center">Editorial Boards</h3>
									<div class="row">
										<div class="col text-center">
											<h5 class="card-title" style="font-size:60px">
											<span class="oi oi-pencil text-success" style="font-size:60px"></span>
											<?php if ($edt_count > 0) {echo $edt_count;} else {echo 0;}?></h5>
											<p class="text-muted">Review submitted manuscripts</p>
										</div>
									</div>
								</div>
								<div class="card-footer bg-primary">
									<a href="javascript:void(0);" id="view_editorials" class="text-white">View details <span class="oi oi-caret-right float-right"></span></a>
								</div>
							</div>
						</div>
						<div class="col-3">
							<div class="card text-white">
								<div class="card-body bg-primary">
									<h3 class="card-title lead text-center">Client Information</h3>
									<div class="row">
										<div class="col text-center">
											<h5 class="card-title" style="font-size:60px">
											<span class="oi oi-people text-warning" style="font-size:60px"></span>
											<?php if ($client_count > 0) {echo $client_count;} else {echo 0;}?></h5>
											<p class="text-muted">Full text pdf requests</p>
										</div>
									</div>
								</div>
								<div class="card-footer bg-primary">
									<a href="javascript:void(0);" id="view_clients" class="text-white">View details <span class="oi oi-caret-right float-right"></span></a>
								</div>
							</div>
						</div>
						<div class="col-3">
							<div class="card text-white">
								<div class="card-body bg-primary">
									<h3 class="card-title lead text-center">Visitors Today</h3>
									<div class="row">
										<div class="col text-center">
											<h5 class="card-title" style="font-size:60px">
											<span class="oi oi-eye text-info" style="font-size:60px"></span>
											<?php if ($vis_count > 0) {echo $vis_count;} else {echo 0;}?></h5>
											<p class="text-muted"><?php echo $vis_all; ?> Visited</p>
										</div>
									</div>
								</div>
								<div class="card-footer bg-primary">
									<a href="javascript:void(0);" class="text-white" onclick="get_visitors()">View details <span class="oi oi-caret-right float-right"></span></a>
								</div>
							</div>
						</div>
					</div>
					<div class="row mt-4">
						<div class="col-8">
							<!-- POPULAR ARTICLE -->
							<div class="card">
								<div class="card-header text-light">
									<span class="oi oi-star"></span> Popular articles
								</div>
								<div class="mt-3 mb-3">
									<?php if ($popular != null) {?>
									<table class="table table-dark table-hover" id="table-popular">
										<thead class="thead-dark">
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
											<?php $abs_class = ($row['abs'] > 0 ) ? 'text-info' : 'text-muted';?>
											<?php $pdf_class = ($row['count'] > 0 ) ? 'text-info' : 'text-muted';?>
											<?php $cite_class = ($row['cite'] > 0 ) ? 'text-info' : 'text-muted';?>
											<tr>
												<td></td>
												<td><a href="javascript:void(0);"  onclick="edit_article(<?php echo $row['id']; ?>)"><?php echo '<strong>' . $row['title'] . '</strong>, ' . $row['coa']; ?></a></td>
												<td><a href="javascript:void(0);"  class="<?php echo $abs_class;?> font-weight-bold" onclick="get_hits_info(<?php echo $row['id']; ?>)"><?php echo $row['abs']; ?></a></td>
												<td><a href="javascript:void(0);"  class="<?php echo $pdf_class;?> font-weight-bold" onclick="get_client_info(<?php echo $row['id']; ?>)"><?php echo $row['count']; ?></a></td>
												<td><a href="javascript:void(0);"  class="<?php echo $cite_class;?> font-weight-bold" onclick="get_citees(<?php echo $row['id']; ?>)"><?php echo $row['cite']; ?></a></td>
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
									<div class="dropdown float-right" data-toggle="tooltip" data-placement="top" title="Generate Report">
										<a href=javascript:void(0); class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown"><span class="oi oi-data-transfer-download"></span></a>
										<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
											<a class="dropdown-item" href="<?php echo base_url('admin/export_excel/export_popular_excel'); ?>"><oi class="fa fa-file-excel-o"></oi> Excel File</a>
											<a class="dropdown-item" href="<?php echo base_url('admin/export_excel/export_popular_pdf'); ?>"><oi class="fa fa-file-pdf-o"></oi> PDF File</a>
										</div>
									</div>
								</div>
							</div>
							<!-- SEARCH AUTHOR -->
							<div class="card mt-4">
								<div class="card-header text-light">
									<span class="oi oi-bookmark"></span> Authors Registry  <span class="float-right">Press <span class="badge badge-warning">ENTER</span> to search</span>
								</div>
								<input type="text" class="form-control" id="authors_reg" placeholder="Type here to search Author/Coauthor">
								<div class="mt-3 mb-3">
									<table class="table" id="table-registry">
										<thead class="thead-dark">
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
								<div class="card">
									<div class="card-header text-light">
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
									<div class="card-footer text-muted">
										<small><a href="javascript:void(0);" data-toggle="modal" data-target="#activities_modal"><span class="oi oi-eye"></span> View all activity</a></small>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- /.DASHBOARD -->

				<!-- CREATE JOURNAL	 -->
				<div class="tab-pane fade " id="create-journal-tab" role="tabpanel" aria-labelledby="create-journal-tab">
					<div class="jumbotron  text-white">
						<form id="form_create_journal">
							<h1 class="h3">Create Journal</h1>
							<!-- <hr class="my-4" style="border:.5px solid white"> -->
							<p class="pt-2 text-danger"><span class="badge badge-primary"><span class="oi oi-info text-danger"></span></span> Upload Cover - 2 MB file size limit</p>
							<p class="lead">Journal Information</p>
							<div class="form-row">
								<div class="form-group col-md-2">
									<label for="jor_volume">Volume No.</label>
									<select class="form-control text-uppercase" id="jor_volume" name="jor_volume" placeholder="ex. X">
										<option class="text-dark" disabled>Volume</option>
										<?php foreach ($u_journal as $j): ?>
										<?php echo '<option value=' . $j->jor_volume . ' class="text-dark">' . $j->jor_volume . '</option>'; ?>
										<?php endforeach;?>
									</select>
								</div>
								<div class="form-group col-md-2">
									<label for="jor_issue">Issue No.</label>
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
								<div class="form-group col-md-4">
									<label for="jor_month">Month <small class="text-warning">(optional)</small></label>
									<input type="text" class="form-control" id="jor_month" name="jor_month" placeholder="ex. Jan-Dec">
								</div>
								<div class="form-group col-md-2">
									<label for="jor_year">Year</label>
									<input type="text" class="form-control" id="jor_year" name="jor_year" placeholder="ex. 2018" maxlength="4">
								</div>
								<div class="form-group col-md-2">
									<label for="jor_issn">ISSN</label>
									<input type="text" class="form-control" id="jor_issn" name="jor_issn" value="0117-3294" readonly>
								</div>
							</div>
							<div class="form-row">
								<div class="form-group col-md-2">
									<label for="cover_photo">Preview </label><br/>
									<img class="mr-3 img-thumbnail" id="cover_photo" src="<?php echo base_url('assets/images/unavailable.jpg'); ?>" style="width:150px; height:210px;">
								</div>
								<div class="form-group col-md-4">
									<label for="jor_cover">Upload Photo <small class="text-warning">(optional)</small> <small class="text-success">(JPG only)</small></label>
									<input type="file" class="form-control" id="jor_cover" name="jor_cover" accept="image/jpeg" >
									<small class="text-warning">(MAX: 20MB)</small>
								</div>
								<div class="form-group col-md-6">
									<label for="jor_description">Description <small class="text-warning">(optional)</small></label>
									<textarea rows="6" class="form-control" id="jor_description" name="jor_description" placeholder="Type description here" maxlength="500"></textarea>
								</div>
							</div>
							<button type="submit" class="btn btn-primary" name="submit_journal" id="submit_journal"><span class="oi oi-check"></span> Save</button>
						</form>
					</div>
				</div>
				<!-- /.CREATE JOURNAL -->

				<!-- ADD ARTICLE -->
				<div class="tab-pane fade " id="add-article-tab" role="tabpanel" aria-labelledby="add-article-tab">
					<div class="jumbotron text-white">
						<h1 class="h3">Add Article</h1>
						<!-- <hr class="my-4"> -->
						<form id="form_add_article">
							<p class="pt-2 text-danger">
								<span class="badge badge-primary"><span class="oi oi-info text-danger"></span></span> PDF - 20 MB file size limit
								<br/>
								<span class="badge badge-primary"><span class="oi oi-info text-danger"></span></span> File Naming Format - Volume_issue_yyyy_PrimaryAuthorLastNameFirstName_concatenatedArticleTitle
								<br/>
								<span class="badge badge-primary"><span class="oi oi-info text-danger"></span></span> Please do not include special characters in file name except underscore (_)
							</p>
							<p class="lead">Article Information</p>
							<div class="form-row">
								<div class="form-group col-md-3">
									<label for="art_year">Year</label>
									<select class="form-control" id="art_year" name="art_year">
										<option value="">Select year</option>
										<?php foreach ($u_year as $j): ?>
										<?php echo '<option value=' . $j->jor_year . '>' . $j->jor_year . '</option>'; ?>
										<?php endforeach;?>
									</select>
								</div>
								<div class="form-group col-md-3">
									<label for="art_jor_id">Volume, Issue </label>
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
							</div>
							<div class="form-group">
								<label for="art_title">Title of Article</label>
								<div class="bg-white">
								<textarea class="form-control" id="art_title" name="art_title"></textarea></div>
							</div>
							<div class="form-group">
								<label for="art_keywords">Keywords</label>
								<input type="text" class="form-control" id="art_keywords" placeholder="ex. Keyword 1,Keyword 2,Keyword 3" name="art_keywords">
							</div>
							<div class="form-row">
								<div class="form-group col-md-2">
									<label for="art_page">Page Nos</label>
									<input type="text" class="form-control" id="art_page" placeholder="ex. 100-200" name="art_page">
								</div>
								<div class="form-group col-md-5">
									<label for="art_abstract_file">Abstract</label>
									<span class="badge badge-warning" id="badge_pdf">PDF only</span>
									<input type="file" class="form-control" id="art_abstract_file" name="art_abstract_file" accept="application/pdf" >
									<small class="text-warning">(MAX: 20MB)</small>
								</div>
								<div class="form-group col-md-5">
									<label for="art_full_text_pdf">Full Text PDF</label>
									<span class="badge badge-warning" id="badge_text">PDF only</span>
									<input type="file" class="form-control" id="art_full_text_pdf" name="art_full_text_pdf" accept="application/pdf" >
									<small class="text-warning">(MAX: 20MB)</small>
								</div>
							</div>
							<div class="form-row">
								<div class="form-group autocomplete col-md-4" >
									<label for="art_author">Author</label>
									<input type="text" class="form-control" id="art_author_p" name="art_author"  placeholder="Search by name or specialization">
								</div>
								<div class="form-group col-md-4">
									<label for="art_affiliation">Affiliation</label>
									<input type="text" class="form-control" id="art_affiliation_p" name="art_affiliation">
								</div>
								<div class="form-group col-md-4">
									<label for="art_email">Email Address <small class="text-warning">(optional)</small></label>
									<input type="text" class="form-control" id="art_email_p" name="art_email" placeholder="Enter a valid email">
								</div>
							</div>
							<span id="coauthors"></span>
							<button type="button" id="btn-add-coauthor" class="btn btn-secondary"><span class="oi oi-people"></span> Add Co-Author</button>
							<button type="submit" class="btn btn-primary" name="submit_article" id="submit_article"><span class="oi oi-check"></span> Save</button>
						</form>
					</div>
				</div>
				<!-- /.ADD ARTICLE -->

				<!-- ADD EDITORIAL -->
				<div class="tab-pane fade" id="add-editorial-tab" role="tabpanel" aria-labelledby="add-editorial-tab">
					<div class="jumbotron text-white">
						<h1 class="h3">Add Editorial Boards and Staff</h1>
						<hr class="my-4">
						<form id="add_editorial_form">
							<div class="form-row">
								<div class="form-group col-md-4">
									<label for="editorial_photo">Preview </label><br/>
									<img class="mr-3 img-thumbnail" id="editorial_photo" src="<?php echo base_url('assets/images/unavailable.jpg'); ?>" style="width:300px; height:300px;">
								</div>
								<div class="form-group col-md-8">
									<div class="form-row">
										<div class="form-group col-md">
											<label for="edt_photo">Upload Photo <small class="text-success">(JPG only)</small></label>
											<input type="file" class="form-control" id="edt_photo" name="edt_photo" accept="image/jpeg" >
											<small class="text-warning">(MAX: 20MB)</small>
										</div>
									</div>
									<div class="form-row">
										<div class="form-group col-md-4">
											<label for="edt_year">Year</label>
											<select class="form-control" id="edt_year" name="edt_year">
												<option value="">Select Year</option>
												<?php for($i=date('Y'); $i>='1993';$i--){ ?>
												<?php echo '<option value=' . $i . '>' . $i . '</option>'; ?>
												<?php }?>
											</select>
										</div>
										<div class="form-group col-md-4">
											<label for="edt_volume">Volume</label>
											<select class="form-control" id="edt_volume" name="edt_volume" placeholder="ex. X">
												<option value='' class="text-dark">Select Volume</option>
												<?php foreach ($u_journal as $j): ?>
												<?php echo '<option value=' . $j->jor_volume . ' class="text-dark text-uppercase">' . $j->jor_volume . '</option>'; ?>
												<?php endforeach;?>
											</select>
										</div>
										<div class="form-group col-md-4">
											<label for="edt_issue">Issue</label>
											<select class="form-control" id="edt_issue" name="edt_issue">
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
									<div class="form-row">
										<div class="form-group col-md-6">
											<label for="edt_name">Name</label>
											<input type="text" class="form-control" id="edt_name" name="edt_name" placeholder="First name, Middle name, Last name">
										</div>
										<div class="form-group col-md-4">
											<label for="edt_email">Email Address</label>
											<input type="text" class="form-control" id="edt_email" name="edt_email" placeholder="Enter a valid email">
										</div>
										<div class="form-group col-md-2">
											<label for="edt_sex">Sex</label>
											<select class="form-control" id="edt_sex" name="edt_sex">
												<option value="">Sex</option>
												<?php foreach ($sex as $s): ?>
												<?php echo '<option value=' . $s->sex_id . '>' . $s->sex_name . '</option>'; ?>
												<?php endforeach;?>
											</select>
										</div>
									</div>
									<div class="form-row">
										<div class="form-group col-md-4">
											<label for="edt_position">Editorial Position</label>
											<input type="text" class="form-control" id="edt_position" name="edt_position" placeholder="ex. Editor-in-Chief">
										</div>	
										<div class="form-group col-md-4">
											<label for="edt_position_affiliation">Employment Position</label>
											<input type="text" class="form-control" id="edt_position_affiliation" name="edt_position_affiliation" placeholder="ex. Professor">
										</div>	
									</div>
									<div class="form-row">
										<div class="form-group col-md">
											<label for="edt_affiliation">Affiliation</label>
											<input type="text" class="form-control" id="edt_affiliation" name="edt_affiliation" placeholder="ex. UP Manila">
										</div>
									</div>
									<div class="form-row">
										<div class="form-group col-md-8">
											<label for="edt_address">Address</label>
											<input type="text" class="form-control" id="edt_address" name="edt_address" placeholder="ex. Juan Dela Cruz Street, Manila">
										</div>
										<div class="form-group col-md-4">
											<label for="edt_country">Country</label>
											<select class="form-control" id="edt_country" name="edt_country" placeholder="Select Country">
												<option value="">Select Country</option>
												<?php foreach ($country as $c): ?>
												<?php $field = 'edt_country';
												echo '<option value=' . $c->country_id  . '>' . $c->country_name . '</option>';?>
												<?php endforeach;?>
											</select>
										</div>
									</div>
									<div class="form-row">
										<div class="form-group col-md">
											<label for="edt_affiliation">Specialization</label>
											<span class="badge badge-secondary">Separate in comma</span>
											<input type="text" class="form-control" id="edt_specialization" name="edt_specialization" placeholder="ex. Specialization 1, Specialization 2">
										</div>
									</div>
									<div class="form-row">
										<div class="form-group">
											<button type="submit" class="btn btn-primary ml-1" name="submit_editorial"><span class="oi oi-check"></span> Save</button>	
										</div>
									</div>
								</div>
							</div>
							
						</form>
					</div>
				</div>
				<!-- /.ADD EDITORIAL -->

				<!-- CLIENTS -->
				<div class="tab-pane fade " id="clients" role="tabpanel" aria-labelledby="clients" >
					<div class="jumbotron text-white">
						<h1 class="h3">Client Information</h1>
						<ul class="nav nav-tabs" id="myTab" role="tablist">
							<li class="nav-item" role="presentation">
								<a class="nav-link active" id="client-tab" data-toggle="tab" href="#client_info" role="tab" aria-controls="home" aria-selected="true">Details</a>
							</li>
							<li class="nav-item" role="presentation">
								<a class="nav-link" onclick="generate_sex_chart()" id="client-grraph-tab" data-toggle="tab" href="#client_graph" role="tab" aria-controls="profile" aria-selected="false">Graph</a>
							</li>
						</ul>
						<div class="tab-content" id="clientTabContent">
						<div class="tab-pane fade show active" id="client_info" role="tabpanel"">
						<div class="no-margin table-responsive">
													<table id="table-clients" class="table table-hover" style="font-size:14px;table-layout: auto">
														<thead class="thead-dark">
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
												</div></div>
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
				<!-- /.CLIENTS -->

				<!-- ABSTRACT HITS -->
				<div class="tab-pane fade " id="viewers" role="tabpanel" aria-labelledby="viewers" >
					<div class="jumbotron text-white">
						<h1 class="h3">Abstract Hits</h1>
						<div class="no-margin table-responsive">
							<table id="table-viewers" class="table table-hover" style="font-size:14px;table-layout: auto">
								<thead class="thead-dark">
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
				<!-- /.ABSTRACT HITS -->

				<!-- CITEES -->
				<div class="tab-pane fade " id="citees" role="tabpanel">
					<div class="jumbotron text-white">
						<h1 class="h3">All Citees</h1>
						<div class="no-margin table-responsive">
							<table id="table-citees" class="table table-hover" style="font-size:14px;table-layout: auto">
								<thead class="thead-dark">
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
										<td><?php echo $i++;?></td>
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
				<!-- /.CITEES -->

				<!-- ARTICLES -->
				<div class="tab-pane fade" id="all_articles" role="tabpanel" aria-labelledby="articles">
					<div class="jumbotron text-white">
						<h1 class="h3">All Articles</h1>
						<div class='no-margin table-responsive'>
							<table id="table-all-articles" class="table table-dark table-hover w-100" style="font-size:14px;">
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
										$abs_class = ($abs > 0 ) ? 'text-info' : 'text-muted';
										$pdf_class = ($pdf > 0 ) ? 'text-info' : 'text-muted';
										$cite_class = ($cite > 0 ) ? 'text-info' : 'text-muted';?>
							
									<tr>
										<td><?php echo $c++; ?></td>
										<td><a href="javascript:void(0);" onclick="edit_article(<?php echo $a->art_id; ?>);"><?php echo $a->art_title; ?></a></td>
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

												echo '<a href="' . $href . $a->art_full_text_pdf . '" class="' . $color . '" target="_blank">' . $size .'</a>';?> 
										</td>
										<td><a href="javascript:void(0);" class="<?php echo $abs_class;?> font-weight-bold" onclick="get_hits_info(<?php echo $a->art_id; ?>)"><?php echo $abs; ?></a></td>
										<td><a href="javascript:void(0);" class="<?php echo $pdf_class;?> font-weight-bold" onclick="get_client_info(<?php echo $a->art_id; ?>)"><?php echo $pdf; ?></a></td>
										<td><a href="javascript:void(0);" class="<?php echo $cite_class;?> font-weight-bold" onclick="get_citees(<?php echo $a->art_id; ?>)"><?php echo $cite; ?></a></td>
												
									</tr>
									<?php endforeach;?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<!-- /.ARTICLES -->

				<!-- JOURNALS -->
				<div class="tab-pane fade" id="journals" role="tabpanel" aria-labelledby="journals">
					<div class="jumbotron text-white">
						<h1 class="h3">All Journals</h1>
						<div class='no-margin table-responsive'>
							<table id="table-journals" class="table table-dark table-hover w-100" style="font-size:14px;">
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
						echo '<a href="javascript:void(0);"  class="font-weight-bold text-info" role="button" onclick="view_articles(\'' . $j->jor_id . '\',\'' . $j->jor_volume . '\',\'' . $j->jor_issue . '\',\'' . $j->jor_month . '\',\'' . $j->jor_year . '\')">' . $this->Article_model->count_article_by_journal($j->jor_id) . ' Article(s)</a>';
					} else {echo '0';}
					?>
										</td>
										<td><?php echo $j->date_created; ?></td>
										<td class='w-25'>
											<?php if ($this->session->userdata('_prv_edt') == 1) {?>
											<button type="button" data-toggle="tooltip" data-placement="top" title="Edit Journal" class="btn btn-info btn-sm" onclick="edit_journal('<?php echo $j->jor_id; ?>','<?php echo $this->Article_model->count_article_by_journal($j->jor_id); ?>')"><span class="oi oi-pencil"></span></button>
											<?php }?>
											<?php if ($this->session->userdata('_prv_add') == 1) {?>
											<button type="button" data-toggle="tooltip" data-placement="top" title="Add Article" class="btn btn-primary btn-sm" onclick="add_article('<?php echo $j->jor_year; ?>','<?php echo $j->jor_id; ?>')" ><span class="oi oi-plus"></span></button>
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
							<div class="no-margin table-responsive">
								<table class="table table-hover table-dark" id="table-articles"  style="table-layout: auto">
									<thead class="thead-dark">
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
				<!-- /.JOURNALS -->

				<!-- EDITORIALS -->
				<div class="tab-pane fade" id="editorials" role="tabpanel" aria-labelledby="editorials">
					<div class="jumbotron text-white">
						<h1 class="h3">Editorial Boards</h1>
						<div class='no-margin table-responsive'>
							<table id="table-editorials" class="table table-hover" style="font-size:14px">
								<thead class="thead-dark">
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
										<td><?php echo $c++; ?></td>
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
											<button type="button" data-toggle="tooltip" data-placement="top" title="Edit" class="btn btn-success btn-sm w-100" onclick="edit_editorial('<?php echo $e->edt_id; ?>')"><span class="oi oi-pencil" ></span> Edit</button>
											<!-- <button type="button" data-toggle="tooltip" data-placement="top" title="Delete" class="btn btn-danger btn-sm" onclick="_remove('delete-editorial-out')"><span class="oi oi-trash"></span></button> -->
										</td>
									</tr>
									<?php endforeach;?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<!-- /.EDITORIALS -->

				<!-- EMAIL -->
				<div class="tab-pane fade" id="mail" role="tabpanel" aria-labelledby="articles">
					<div class="jumbotron text-white">
						<h1 class="h3">Email Notifications</h1>
						<div class='no-margin table-responsive'>
							<table id="table-all-articles" class="table table-dark table-hover w-100" style="font-size:14px;">
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
									<td  class="font-weight-bold"><?php echo $e->enc_subject; ?></td>
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
									<!-- <td><?php echo $e->enc_user_group; ?></td> -->
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
				<!-- /.EMAIL -->

				<!-- LOGS -->
				<div class="tab-pane fade" id="logs" role="tabpanel" aria-labelledby="articles">
					<div class="jumbotron text-white">
						<h1 class="h3">Activity Logs</h1>
						<div class="mt-4 mb-3">
							<label for="dateFrom">Date From:</label>
							<input type="date" id="dateFrom">
							<label for="dateTo">Date To:</label>
							<input type="date" id="dateTo">
						</div>
						<div class='no-margin table-responsive'>
							<table id="activityLogsTable" class="table table-hover" style="font-size:14px">
								<thead class="thead-dark">
									<tr>
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
						<div>
							<button class="btn btn-danger" data-toggle="modal" data-target="#clear_log_modal">Clear Logs</button>
						</div>
					</div>
				</div>
				<!-- /.LOGD  -->
			</div>
		</div>
		
	</div>

		<!-- Copyright -->
		<div class="footer-copyright text-center mt-3 mb-3" >&copy; 2018 Copyright ResearchJournal, All Rights Reserved
			<small class="text-muted d-block">Currently v2.1.85</small>
		</div>
		<!-- Copyright -->
</div>

<!-- CLEAR LOGS MODAL -->
<div class="modal" id="clear_log_modal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Clear Activity Logs</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to clear all activity logs? This action is irreversible. A backup file (Excel) will be downloaded before clearing the logs.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" onclick="confirmClearLogs()">Confirm</button>
      </div>
    </div>
  </div>
</div>
<!-- /.CLEAR LOGS MODAL -->

<!-- ARTICLE MODAL -->
<div class="modal fade" id="article_modal" tabindex="-1" role="dialog" aria-labelledby="article_modal" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"><span class="oi oi-pencil"></span> Edit Article</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<p class="lead">Article Information</p>
				<form id="article_modal_form">
					<input type="hidden" id="art_id" name="art_id">
					<div class="form-row">
						<div class="form-group col-md-3">
							<label for="art_year">Year</label>
							<select class="form-control" id="art_year" name="art_year" >
								<option value="">Select year</option>
								<?php foreach ($u_year as $j): ?>
								<?php echo '<option value=' . $j->jor_year . '>' . $j->jor_year . '</option>'; ?>
								<?php endforeach;?>
							</select>
						</div>
						<div class="form-group col-md-3">
							<label for="art_jor_id">Volume, Issue</label>
							<select class="form-control " id="art_jor_id" name="art_jor_id">
								<option value="">Select Volume, Issue</option>
								<?php foreach ($journal as $j): ?>
								<?php $issue = (
									($j->jor_issue == 5) ? 'Special Issue No. 1' :
									(($j->jor_issue == 6) ? 'Special Issue No. 2' :
										(($j->jor_issue == 7) ? 'Special Issue No. 3' :
											(($j->jor_issue == 8) ? 'Special Issue No. 4' : 'Issue ' . $j->jor_issue)))
								);?>
								<?php echo '<option value=' . $j->jor_id . '>' . 'Vol. ' . $j->jor_volume . ', ' . $issue . '</option>'; ?>
								<?php endforeach;?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="art_title">Title of Article</label>
						<textarea class="form-control  bg-white" id="art_title" name="art_title"></textarea>
					</div>
					<div class="form-row">
						<div class="form-group col-md-9">
							<label for="art_keywords">Keywords</label>
							<span class="badge badge-secondary">Separate in comma</span>
							<input type="text" class="form-control" id="art_keywords" placeholder="ex. Keyword 1,Keyword 2,Keyword 3" name="art_keywords">
						</div>
						<div class="form-group col-md-3">
							<label for="art_page">Page Nos</label>
							<input type="text" class="form-control" id="art_page" placeholder="ex. 100-200" name="art_page">
						</div>
					</div>
					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="art_abstract_file">Abstract</label>
							<div class="input-group">
								<input type="text" class="form-control" id="art_abstract_file" name="art_abstract_file" readonly>
								<div class="input-group-append">
									<a class="btn btn-outline-secondary" target="_blank" id="view_abstract"><span class="oi oi-eye"></span> View</a>
								</div>
							</div>
						</div>
						<div class="form-group col-md-6">
							<label for="art_abstract_file">Upload New Abstract</label>
							<span class="badge badge-warning">PDF only</span>
							<input type="file" class="form-control" name="art_abstract_file_new" accept="application/pdf" >
							<small class="text-warning">(MAX: 20MB)</small>
						</div>
					</div>
					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="art_full_text_pdf">Full Text PDF</label>
							<div class="input-group">
								<input type="text" class="form-control" id="art_full_text_pdf" name="art_full_text_pdf" readonly>
								<div class="input-group-append">
									<a class="btn btn-outline-secondary" target="_blank" id="view_pdf"><span class="oi oi-eye"></span> View</a>
								</div>
							</div>
						</div>
						<div class="form-group col-md-6">
							<label for="art_full_text_pdf">Upload New Full Text PDF</label>
							<span class="badge badge-warning">PDF only</span>
							<input type="file" class="form-control" name="art_full_text_pdf_new" accept="application/pdf" >
							<small class="text-warning">(MAX: 20MB)</small>
						</div>
					</div>
					<div class="form-row">
						<div class="form-group col-md-4">
							<label for="art_author">Author</label>
							<input class="form-control" id="art_author" name="art_author" placeholder="Search by name or specialization" required>	
						</div>
						<div class="form-group col-md-4">
							<label for="art_affiliation">Affiliation</label>
							<input type="text" class="form-control" id="art_affiliation" name="art_affiliation">
						</div>
						<div class="form-group col-md-4">
							<label for="art_email">Email Address</label>
							<input type="text" class="form-control" id="art_email" name="art_email" placeholder="Enter a valid email">
						</div>
					</div>
					<span id="coa_list"></span>
				</div>
				<div class="modal-footer">
					<?php if ($this->session->userdata('_prv_edt') == 1) {?>
					<button type="button" id="btn-add-coauthor" class="btn btn-secondary"><span class="oi oi-people"></span> Add Co-Author</button>
					<?php }?>
					<?php if ($this->session->userdata('_prv_del') == 1) {?>
					<button type="button" class="btn btn-danger mr-auto" onclick="_remove('delete-article')">Remove Article</button>
					<?php }?>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<?php if ($this->session->userdata('_prv_edt') == 1) {?>
					<button type="submit" class="btn btn-primary" name="update-article">Save changes</button>
					<?php }?>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- /.ARTICLE MODAL -->

<!-- REMOVE MODAL -->
<div class="modal fade bd-example-modal-sm" id="remove_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="z-index:9999 !important">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Remove</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-danger" id="btn-remove">Remove</button>
			</div>
		</div>
	</div>
</div>
<!-- /.REMOVE MODAL -->

<!-- JOURNAL MODAL -->
<div class="modal fade" id="journal_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"><span class="oi oi-pencil"></span> Edit Journal</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<!-- <?php echo form_open_multipart('admin/journal/journal/update', array('id' => 'journal_modal_form')); ?> -->
				<form id="journal_modal_form">
					<input type="hidden" id="jor_id" name="jor_id">
					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="jor_volume">Volume No.</label>
							<input type="text" class="form-control" id="jor_volume" name="jor_volume">
						</div>
						<div class="form-group col-md-6">
							<label for="jor_issue">Issue No.</label>
							<select class="form-control" id="jor_issue" name="jor_issue" disabled>
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
					<div class="form-row">
						<div class="form-group col-md-4">
							<label for="jor_month">Month <small class="text-warning">(optional)</small></label>
							<input type="text" class="form-control" id="jor_month" name="jor_month" placeholder="ex. Jan-Dec">
						</div>
						<div class="form-group col-md-4">
							<label for="jor_year">Year</label>
							<input type="text" class="form-control" id="jor_year" name="jor_year" placeholder="ex. 2018" maxlength="4">
							<small class="text-danger"><?php echo form_error('jor_year'); ?></small>
						</div>
						<div class="form-group col-md-4">
							<label for="jor_issn">ISSN</label>
							<input type="text" class="form-control" id="jor_issn" name="jor_issn" value="0117-3294" readonly>
						</div>
					</div>
					<div class="form-row">
						<div class="form-group col-md-4">
							<label for="">Preview</label>
							<img class="mr-3 img-thumbnail" id="cover_photo" src="<?php echo base_url('assets/images/unavailable.jpg'); ?>" style="width: auto; max-width:150px; height: auto;" alt="Generic placeholder image">
						</div>
						<div class="form-group col-md-8">
							<label for="">Upload Photo <small class="text-warning">(optional)</small></label>
							<input type="file" class="form-control" id="jor_cover" name="jor_cover" accept="image/*">
							<small class="text-warning">(MAX: 20MB)</small>
							<label for="jor_description">Description</label>
							<textarea rows="6" class="form-control" id="jor_description" name="jor_description" placeholder="Type description here" maxlength="500"></textarea>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger mr-auto" onclick="_remove('delete-journal')">Remove Journal</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">Save changes</button>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- /.JOURNAL MODAL -->

<!-- EDITORIAL MODAL -->
<div class="modal fade" id="editorial_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"><span class="oi oi-pencil"></span> Edit Editorial Board and Staff</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="editorial_modal_form">
							<input type="hidden" id="edt_id" name="edt_id">
							<input type="hidden" id="edt_photo_exist" name="edt_photo_exist">
							<div class="form-row">
								<div class="form-group col-md-3">
									<label for="editorial_photo">Preview </label><br/>
									<img class="mr-3 img-thumbnail" id="editorial_photo"  style="width:200px; height:200px;">
									<!-- src="<?php echo base_url('assets/images/unavailable.jpg'); ?>" -->
								</div>
								<div class="form-group col-md-9">
									<div class="form-row">
										<div class="form-group col-md">
											<label for="edt_photo">Upload Photo <small class="text-success">(JPG only)</small></label>
											<input type="file" class="form-control" id="edt_photo" name="edt_photo" accept="image/jpeg" >
										</div>
									</div>
									<div class="form-row">
										<div class="form-group col-md-4">
											<label for="edt_year">Year</label>
											<select class="form-control" id="edt_year" name="edt_year">
												<option value="">Select Year</option>
												<?php for($i=date('Y'); $i>='1993';$i--){ ?>
												<?php echo '<option value=' . $i . '>' . $i . '</option>'; ?>
												<?php }?>
											</select>
										</div>
										<div class="form-group col-md-4">
											<label for="edt_volume">Volume</label>
											<select class="form-control" id="edt_volume" name="edt_volume" placeholder="ex. X">
												<option value='' class="text-dark">Select Volume</option>
												<?php foreach ($u_journal as $j): ?>
												<?php echo '<option value=' . $j->jor_volume . ' class="text-dark text-uppercase">' . $j->jor_volume . '</option>'; ?>
												<?php endforeach;?>
											</select>
										</div>
										<div class="form-group col-md-4">
											<label for="edt_issue">Issue</label>
											<select class="form-control" id="edt_issue" name="edt_issue">
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
									<div class="form-row">
										<div class="form-group col-md-6">
											<label for="edt_name">Name</label>
											<input type="text" class="form-control" id="edt_name" name="edt_name" placeholder="First name, Middle name, Last name">
										</div>
										<div class="form-group col-md-4">
											<label for="edt_email">Email Address</label>
											<input type="text" class="form-control" id="edt_email" name="edt_email" placeholder="Enter a valid email">
										</div>
										<div class="form-group col-md-2">
											<label for="edt_sex">Sex</label>
											<select class="form-control" id="edt_sex" name="edt_sex">
												<option value="">Sex</option>
												<?php foreach ($sex as $s): ?>
												<?php echo '<option value=' . $s->sex_id . '>' . $s->sex_name . '</option>'; ?>
												<?php endforeach;?>
											</select>
										</div>
									</div>
									<div class="form-row">
										<div class="form-group col-md-6">
											<label for="edt_position">Editorial Position</label>
											<input type="text" class="form-control" id="edt_position" name="edt_position" placeholder="ex. Editor-in-Chief">
										</div>	
										<div class="form-group col-md-6">
											<label for="edt_position_affiliation">Employment Position</label>
											<input type="text" class="form-control" id="edt_position_affiliation" name="edt_position_affiliation" placeholder="ex. Professor">
										</div>	
									</div>
									<div class="form-row">
										<div class="form-group col-md">
											<label for="edt_affiliation">Affiliation</label>
											<input type="text" class="form-control" id="edt_affiliation" name="edt_affiliation" placeholder="ex. UP Manila">
										</div>
									</div>
									<div class="form-row">
										<div class="form-group col-md-8">
											<label for="edt_address">Address</label>
											<input type="text" class="form-control" id="edt_address" name="edt_address" placeholder="ex. Juan Dela Cruz Street, Manila">
										</div>
										<div class="form-group col-md-4">
											<label for="edt_country">Country</label>
											<select class="form-control" id="edt_country" name="edt_country">
												<option value="">Select Country</option>
												<?php foreach ($country as $c): ?>
												<?php echo '<option value=' . $c->country_id . '>' . $c->country_name . '</option>';?>
												<?php endforeach;?>
											</select>
										</div>
									</div>
									<div class="form-row">
										<div class="form-group col-md">
											<label for="edt_affiliation">Specialization</label>
											<span class="badge badge-secondary">Separate in comma</span>
											<input type="text" class="form-control" id="edt_specialization" name="edt_specialization" placeholder="ex. Specialization 1, Specialization 2">
										</div>
									</div>
								</div>
							</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger mr-auto" onclick="_remove('delete-editorial')">Delete</button>
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Save changes</button>
					</form>
				</div>
		</div>
	</div>
</div>
<!-- EDITORIAL MODAL -->

<!-- VISITOR DETAILS -->
<div class="modal fade" id="visitor_modal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document" >
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><span class="oi oi-eye"></span> Visitor Information</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class='no-margin table-responsive'>
					<table id="table-visitors" class="table table-hover" style="font-size:14px">
						<thead class="thead-dark">
							<tr>
								<th scope="col"></th>
								<th scope="col">IP Address</th>
								<th scope="col">Location</th>
								<th scope="col">Browser(User Agent)</th>
								<th scope="col">Visit Date&Time</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- /.VISITOR DETAILS -->

<!-- ADD USER -->
<div class="modal fade" id="user_modal" tabindex="-1" role="dialog" aria-labelledby="user_modal" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="user_modal"><span class="oi oi-person"></span> Add User Account</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="form_add_user">
					<div class="form-group">
						<label for="acc_username">Username</label>
						<input type="text" class="form-control" id="acc_username" name="acc_username" aria-describedby="acc_username" placeholder="Enter username" >
					</div>
					<div class="form-group">
						<label for="acc_password">Password</label>
						<input type="password" class="form-control" id="acc_password" name="acc_password" placeholder="Password" >
					</div>
					<div class="form-group">
						<label for="repeat_password">Repeat Password</label>
						<input type="password" class="form-control" name="repeat_password" id="repeat_password" placeholder="Repeat Password" >
						<p id="match" class="mt-2"></p>
					</div>
					<div class="form-group">
						<label for="acc_type">User Type</label>
						<select class="form-control" id="acc_type" name="acc_type" >
							<option value="">Select User Type</option>
							<option value='0'>Super Admin</option>
							<option value='1'>Administrator</option>
							<option value='2'>Manager</option>
						</select>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">Save</button>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- /.ADD USER -->

<!-- MANAGE USER -->
<div class="modal fade" id="manage_user_modal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><span class="oi oi-cog"></span> Manage User Account</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="manage_user_content">
					<div class="input-group mb-3">
						<div class="input-group-prepend">
							<span class="input-group-text" id="basic-addon1"><span class="oi oi-person"></span></span>
						</div>
						<input readonly type="text" id="manage_username" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1">
					</div>
					<div class="list-group">
						<a href="javascript:void(0);" class="list-group-item list-group-item-action reset-pass"><span class="oi oi-reload"></span> Reset Password</a>
						<a href="javascript:void(0);" class="list-group-item list-group-item-action dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<span class="oi oi-shield"></span> Change User Type
						</a>
						<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
							<a class="dropdown-item super" href="javascript:void(0);">Super Admin</a>
							<a class="dropdown-item admin" href="javascript:void(0);">Administrator</a>
							<a class="dropdown-item man" href="javascript:void(0);">Manager</a>
						</div>
						<a href="javascript:void(0);" class="list-group-item list-group-item-action remove-user"><span class="oi oi-trash"></span> Remove User</a>
					</div>
				</div>
				<div class="text-center" id="offline_user">
					<span class="oi oi-ban text-danger" style="font-size:80px; " ></span>
					<p>Not allowed to make changes while the user is <strong class="text-success">online</strong>.</p>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- /.MANAGE USER -->

<!-- SET DP -->
<!-- <div class="modal fade" id="set_dp" tabindex="-1" role="dialog" aria-labelledby="set_dp" aria-hidden="true">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="user_modal"><span class="oi oi-camera-slr"></span> Set Display Picture</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body text-center">
				<?php echo form_open_multipart('admin/dashboard/upload_display_picture', array('id' => 'form_set_dp')); ?>
					<?php if ($this->session->userdata('_oprs_user_dp') != '') {?>
					<img class="rounded-0" id="img_dp" width="260px" height="250px" src="<?php echo base_url("assets/uploads/dp/" . $this->session->userdata('_oprs_user_dp') . ""); ?>">
					<?php } else {?>
					<img class="rounded-0" id="img_dp" width="260px" height="250px" src="<?php echo base_url('assets/images/img_avatar.png'); ?>">
					<?php }?>
					<input type="file" class="form-control" id="set_d_p" name="acc_dp" accept='image/*' required>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning mr-auto" id="browse_dp">Browse</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary">Save</button>
			</form>
		</div>
	</div>
</div> -->
<!-- /.SET DP -->

<!-- CHANGE PASSWORD -->
<div class="modal fade" id="change_pass" tabindex="-1" role="dialog" aria-labelledby="change_pass" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="user_modal"><span class="oi oi-shield"></span> Change Password</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="form_change_pass">
				<div class="form-group">
					<label for="acc_password">New Password</label>
					<input type="password" class="form-control" id="new_password" name="acc_password" placeholder="Enter new password" >
				</div>
				<div class="form-group">
					<label for="repeat_password">Repeat Password</label>
					<input type="password" class="form-control" name="repeat_password" id="repeat_password" placeholder="Repeat password" >
					<p id="match" class="mt-2"></p>
				</div>
				<div class="alert alert-danger" role="alert">
					<span class="oi oi-warning"></span> You will be redirected to login page after saving.
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary">Save</button>
			</form>
			</div>
		</div>
	</div>
</div>
<!-- /.CHANGE PASSWORD -->

<!-- LATEST ACTIVITIES -->
<div class="modal fade" id="activities_modal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document" >
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><span class="oi oi-bell"></span> Activity</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class='no-margin table-responsive'>
					<table id="table-activities" class="table table-hover" style="font-size:14px">
						<thead class="thead-dark">
							<tr>
								<th scope="col">Activity</th>
								<th scope="col">Date/Time</th>
							</tr>
						</thead>
						<tbody>
							<?php if ($all_logs != null) {?>
							<?php $c = 1;foreach ($all_logs as $row): ?>
							<tr>
								<td>
									<strong  class="text-uppercase"><?php echo $this->Login_model->get_username_for_logs($row->log_user_id); ?></strong>
									<?php echo $row->log_action; ?>
									<td><?php echo $row->date_created; ?></td>
								</tr>
								<?php endforeach;?>
								<?php }?>
						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- /.LATEST ACTIVITIES -->

<!-- RELATED ARTICLES -->
<div class="modal fade" id="registry_modal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document" >
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><span class="oi oi-list"></span> Related Articles</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<ul class="list-group list-group-flush registry">
				</ul>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- /.RELATED ARTICLES -->

<!-- LOGOUT -->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModal" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
				<button class="close" type="button" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">Click "Logout" below if you are ready to end your current session.</div>
			<div class="modal-footer">
				<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
				<a class="btn btn-primary" href="javascript:void(0);" onclick="verify_feedback();">Logout</a>
				<!-- <a class="btn btn-primary" href="<?php echo base_url('oprs/login/logout'); ?>">Logout</a> -->
			</div>
		</div>
	</div>
</div>
<!-- /.LOGOUT -->

<!-- FEEDBACK MODAL -->
<div class="modal fade" id="feedbackModal" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header pb-0">
        <p><span class="modal-title font-weight-bold h3">Your feedback</span><br/>
        <small>We would like your feedback to improve our system.</small></p>
        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button> -->
      </div>
      <div class="modal-body p-4">
        <form id="feedback_form">
            <div class="feedback text-center">
                <p class="font-weight-bold h4 text-center">User Interface</p>
                <div class="feedback-container ui-container">
                    <div class="feedback-item">
                        <label for="ui-1" data-toggle="tooltip" data-placement="bottom" title="Sad">
                            <input class="radio" type="radio" name="fb_rate_ui" id="ui-1" value="1" >
                            <span >🙁</span>
                        </label>
                    </div>

                    <div class="feedback-item">
                        <label for="ui-2" data-toggle="tooltip" data-placement="bottom" title="Neutral">
                            <input class="radio" type="radio" name="fb_rate_ui" id="ui-2" value="2">
                            <span>😶</span>
                        </label>
                    </div>

                    <div class="feedback-item">
                        <label for="ui-3" data-toggle="tooltip" data-placement="bottom" title="Happy">
                            <input class="radio" type="radio" name="fb_rate_ui" id="ui-3" value="3">
                            <span>🙂</span>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="fb_suggest_ui"></label>
                    <textarea class="form-control" name="fb_suggest_ui" id="fb_suggest_ui" rows="3" placeholder="Type your suggestions here"></textarea>
                </div>

                <hr/>

                <p class="font-weight-bold h4 text-center">User Experience</p>
                <div class="feedback-container ux-container">
                    <div class="feedback-item">
                        <label for="ux-1" data-toggle="tooltip" data-placement="bottom" title="Sad">
                            <input class="radio" type="radio" name="fb_rate_ux" id="ux-1" value="1">
                            <span>🙁</span>
                        </label>
                    </div>

                    <div class="feedback-item">
                        <label for="ux-2" data-toggle="tooltip" data-placement="bottom" title="Nuetral">
                            <input class="radio" type="radio" name="fb_rate_ux" id="ux-2" value="2">
                            <span>😶</span>
                        </label>
                    </div>

                    <div class="feedback-item">
                        <label for="ux-3" data-toggle="tooltip" data-placement="bottom" title="Happy">
                            <input class="radio" type="radio" name="fb_rate_ux" id="ux-3" value="3">
                            <span>🙂</span>
                        </label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="fb_suggest_ux"></label>
                    <textarea class="form-control" name="fb_suggest_ux" id="fb_suggest_ux" rows="3" placeholder="Type your suggestions here"></textarea>
                </div>

                <div class="btn-group pull-right" role="group">
                    <button class="btn btn-outline-secondary" type="button" data-dismiss="modal">Later</button>
                    <button type="submit" class="btn btn-primary">Submit Feedback</button>
                </div>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- /FEEDBACK MODAL -->

<!-- DATBASE MODAL -->
<div class="modal fade" id="database_modal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><span class="oi oi-hard-drive" style="width:20px"></span> Backup/Restore Database </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="card">
            <div class="card-header">
                Backup Database
            </div>
            <div class="card-body">
                <form id="export_db_form" action="<?php echo site_url('admin/backup/export');?>" method="POST">
                
                <strong>Export method:</strong>
                <hr/>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="export_method" id="quick_export" value="1" checked>
                    <label class="form-check-label" for="quick_export">
                        Quick - Create backup of the database
                    </label>
                </div>
                <div class="form-check mt-1 mb-3">
                    <input class="form-check-input" type="radio" name="export_method" id="custom_export" value="2">
                    <label class="form-check-label" for="custom_export">
                        Custom - Select specific table to backup
                    </label>
                </div>
                <!-- <strong>Format:</strong>
                <hr/>
                <select class="form-control w-25 form-control-sm" id="export_format" name="export_format">
                    <option value="sql">SQL</option>
                    <option value="csv">CSV</option>
                </select> -->
                <table id="sd_table" class="table table-striped mt-3 table-sm table-bordered">
                        <thead>
                            <tr>
                            <th scope="col">Table</th>
                            <th scope="col">Structure</th>
                            <th scope="col">Data</th>
                            </tr>
                        </thead>
                        <tbody>
                        <tr class="table-warning"><td>Select all</td>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" id="select_all_structure" name="select_all_structure">
                                    <label class="form-check-label" for="defaultCheck1">
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" id="select_all_data" name="select_all_data">
                                    <label class="form-check-label" for="defaultCheck1">
                                    </label>
                                </div>
                            </td>
                        </tr>
                    <?php  foreach($tables as $table){

                    echo '<tr> 
                              <td>' . $table . '</td> 
                              <td>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="table_structure[]" value="'. $table .'" id="defaultCheck1">
                                    <label class="form-check-label" for="defaultCheck1">
                                    </label>
                                </div>
                              </td> 
                              <td>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="table_data[]" value="'. $table .'" id="defaultCheck1">
                                    <label class="form-check-label" for="defaultCheck1">
                                    </label>
                                </div>
                              </td> 
                         </tr>';
                      }?>
                        </tbody>
                    </table>
                    <div class="mt-3">
                      <button type="submit" id="export_button" class="btn btn-dark">Go</button>
                    </div>
                    </form>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-header">
                Import Backup
            </div>
            <div class="card-body">
                <form id="import_db_form" method="POST" enctype="multipart/form-data">
                    <div class="input-group is-invalid">
                        <div class="custom-file">
                        <input type="file" class="custom-file-input" id="import_file" name="import_file"  >
                        <label class="custom-file-label" for="import_file">Choose file...</label>
                        </div>
                        <div class="input-group-append">
                        <button type="submit" class="btn btn-dark">Go</button>
                        </div>
                    </div>
                    <div class="invalid-feedback">
                    </div>
      			</form>

				<div id="success_import" class="mt-3"></div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- /.DATBASE MODAL -->

<!-- EMAIL LIBRARY MODAL -->
<div class="modal fade text-white" id="emailContentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title font-weight-bold"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="email_content_form" name="email_content_form">
        <input type="hidden" id="enc_process_id" name="enc_process_id">
          <div class="row">
            <div class="col-5">
              <div class="form-group">
                <label class="font-weight-bold"  for="enc_subject">Email subject</label>
                <input type="text" class="form-control" id="enc_subject" name="enc_subject" required>
              </div>
              <div class="form-group">
                <label class="font-weight-bold"  for="enc_description">Notification trigger</label>
                <input type="text" class="form-control" id="enc_description" name="enc_description" required>
              </div>
              <div class="form-group">
                <label class="font-weight-bold" for="enc_cc">CC <small class="text-muted">(optional)</small></label>
                <input type="text" class="form-control" id="enc_cc" name="enc_cc" placeholder="juandelacruz@gmail.com,mariadelacruz@gmail.com">
                <small class="text-muted pt-2">Please separate emails by comma (,)</small>
              </div>
              <div class="form-group">
                <label class="font-weight-bold" for="enc_bcc">BCC <small class="text-muted">(optional)</small></label>
                <input type="text" class="form-control" id="enc_bcc" name="enc_bcc" placeholder="juandelacruz@gmail.com,mariadelacruz@gmail.com">
                <small class="text-muted pt-2">Please separate emails by comma (,)</small>
              </div>
              <div class="form-group enc_user_group">
                <label class="font-weight-bold" for="enc_user_group">User group
                <br/><small class="text-muted">Following user roles will also receive this email notification.</small>
                </label>
               
                <?php foreach($user_roles as $row): ?>
                  <div class="form-check"> 
                    <input class="form-check-input" id="enc_user_group<?php echo $row->role_id;?>" name="enc_user_group[]" value="<?php echo $row->role_id;?>" type="checkbox" > 
                    <label class="form-check-label" for="<?php echo $row->role_id;?>"> 
                     <?php echo $row->role_name;?> 
                    </label> 
                  </div>
                  <?php endforeach ?>
              </div>
            </div>
            <div class="col-7">
              <div class="form-group">
              <label class="font-weight-bold" for="enc_content">Email content</label>
                <div class="alert alert-warning" role="alert">
                  <span class="oi oi-warning"></span> Do not change or remove words with square brackets. [EXAMPLE]
                </div>
                <textarea type="text" id="enc_content" class="form-control"></textarea>
              </div>
              <div class="alert alert-warning pt-3" role="alert">
                <span class="oi oi-warning"></span> Do not change or remove words with square brackets. [EXAMPLE]
              </div>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" id="update_email_content_btn" class="btn btn-primary">Save changes</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!--/. EMAIL LIBRARY MODAL -->

