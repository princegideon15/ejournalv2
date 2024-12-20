
	<footer class="py-4 bg-light mt-auto">
      <div class="container-fluid px-4">
          <div class="d-flex align-items-center justify-content-between small">
              <div class="text-muted">Copyright &copy; 2018 NRCP Online Research Journal (eJournal), Online Peer Review System (eReview) All Rights Reserved</div>
              <div class="text-muted">
                Currently v2.1.85
                  <!-- <a href="javascript:void(0);">Privacy Policy</a>
                  &middot;
                  <a href="javascript:void(0);">Terms &amp; Conditions</a> -->
              </div>
          </div>
      </div>
    </footer>
  </div>
</div>


<!-- CLEAR LOGS MODAL -->
<div class="modal" id="clear_log_modal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Clear Activity Logs</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to clear all activity logs? This action is irreversible. A backup file (Excel) will be downloaded before clearing the logs.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" onclick="confirmClearLogs(this)">Confirm</button>
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
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<p class="lead">Article Information</p>
				<form id="article_modal_form">
					<input type="hidden" id="art_id" name="art_id">
					<div class="form-row">
						<div class="form-group col-md-3">
							<label for="art_year">Year</label>
							<select class="form-select" id="art_year" name="art_year" >
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
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
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
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
							<select class="form-select" id="jor_issue" name="jor_issue" disabled>
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
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
											<select class="form-select" id="edt_year" name="edt_year">
												<option value="">Select Year</option>
												<?php for($i=date('Y'); $i>='1993';$i--){ ?>
												<?php echo '<option value=' . $i . '>' . $i . '</option>'; ?>
												<?php }?>
											</select>
										</div>
										<div class="form-group col-md-4">
											<label for="edt_volume">Volume</label>
											<select class="form-select" id="edt_volume" name="edt_volume" placeholder="ex. X">
												<option value='' class="text-dark">Select Volume</option>
												<?php foreach ($u_journal as $j): ?>
												<?php echo '<option value=' . $j->jor_volume . ' class="text-dark text-uppercase">' . $j->jor_volume . '</option>'; ?>
												<?php endforeach;?>
											</select>
										</div>
										<div class="form-group col-md-4">
											<label for="edt_issue">Issue</label>
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
											<select class="form-select" id="edt_sex" name="edt_sex">
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
											<select class="form-select" id="edt_country" name="edt_country">
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
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
						<select class="form-select" id="acc_type" name="acc_type" >
							<option value="">Select User Type</option>
							<option value='0'>Super Admin</option>
							<option value='1'>Administrator</option>
							<option value='2'>Manager</option>
						</select>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
				<button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
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
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<ul class="list-group list-group-flush registry">
				</ul>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
				<button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">Click "Logout" below if you are ready to end your current session.</div>
			<div class="modal-footer">
				<button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
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
        <p><span class="modal-title fw-bold h3">Your feedback</span><br/>
        <small>We would like your feedback to improve our system.</small></p>
        <!-- <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button> -->
      </div>
      <div class="modal-body p-4">
        <form id="feedback_form">
            <div class="feedback text-center">
                <p class="fw-bold h4 text-center">User Interface</p>
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

                <p class="fw-bold h4 text-center">User Experience</p>
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
                    <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Later</button>
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
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
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
        <button type="submit" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
        <h5 class="modal-title fw-bold"></h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="email_content_form" name="email_content_form">
        <input type="hidden" id="enc_process_id" name="enc_process_id">
          <div class="row">
            <div class="col-5">
              <div class="form-group">
                <label class="fw-bold"  for="enc_subject">Email subject</label>
                <input type="text" class="form-control" id="enc_subject" name="enc_subject" required>
              </div>
              <div class="form-group">
                <label class="fw-bold"  for="enc_description">Notification trigger</label>
                <input type="text" class="form-control" id="enc_description" name="enc_description" required>
              </div>
              <div class="form-group">
                <label class="fw-bold" for="enc_cc">CC <small class="text-muted">(optional)</small></label>
                <input type="text" class="form-control" id="enc_cc" name="enc_cc" placeholder="juandelacruz@gmail.com,mariadelacruz@gmail.com">
                <small class="text-muted pt-2">Please separate emails by comma (,)</small>
              </div>
              <div class="form-group">
                <label class="fw-bold" for="enc_bcc">BCC <small class="text-muted">(optional)</small></label>
                <input type="text" class="form-control" id="enc_bcc" name="enc_bcc" placeholder="juandelacruz@gmail.com,mariadelacruz@gmail.com">
                <small class="text-muted pt-2">Please separate emails by comma (,)</small>
              </div>
              <div class="form-group enc_user_group">
                <label class="fw-bold" for="enc_user_group">User group
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
              <label class="fw-bold" for="enc_content">Email content</label>
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
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" id="update_email_content_btn" class="btn btn-primary">Save changes</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!--/. EMAIL LIBRARY MODAL -->

<script type="text/javascript" >
var base_url = '<?php echo base_url(); ?>';
var prv_add = <?php echo (!empty($this->session->userdata('_prv_add'))) ? $this->session->userdata('_prv_add') : '0'; ?>;
var prv_edt = <?php echo (!empty($this->session->userdata('_prv_edt'))) ? $this->session->userdata('_prv_edt') : '0'; ?>;
var prv_del = <?php echo (!empty($this->session->userdata('_prv_del'))) ? $this->session->userdata('_prv_del') : '0'; ?>;
var prv_view = <?php echo (!empty($this->session->userdata('_prv_view'))) ? $this->session->userdata('_prv_view') : '0'; ?>;
var prv_exp = <?php echo (!empty($this->session->userdata('_prv_exp'))) ? $this->session->userdata('_prv_exp') : '0'; ?>;
</script>


<!-- chart -->
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<script src="<?php echo base_url("assets/js/sweetalert2@11.js");?>"></script>
<script src="<?php echo base_url("assets/oprs/js/chart.js");?>"></script>
<script src="<?php echo base_url("assets/oprs/js/jquery.min.js");?>"></script>


<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>

<!-- <script src="<?php echo base_url("assets/oprs/js/datatables.js");?>"></script> -->
<script src="<?php echo base_url("assets/oprs/js/dataTables.buttons.min.js"); ?>"></script>
<!-- <script src="<?php echo base_url("assets/oprs/js/bootstrap.bundle.min.js");?>"></script> -->
<!-- Main jquery-->
<script src="<?php echo base_url("assets/js/journal.js"); ?>"></script>
<!-- Jquery Validate-->
<script src="<?php echo base_url("assets/oprs/js/jquery.validate.min.js");?>"></script>
<!-- Jquery Validate Additional-->
<script src="<?php echo base_url("assets/oprs/js/additional-methods.min.js");?>"></script>
<!-- Jquery Validate File-->
<script src="<?php echo base_url("assets/oprs/js/jquery.validate.file.js");?>"></script>
<!-- Editable dropdown-->
<script src="<?php echo base_url("assets/oprs/js/jquery-editable-select.min.js");?>"></script>
<!-- Bootstrap notify-->
<script src="<?php echo base_url("assets/oprs/js/bootstrap-notify.js");?>"></script>
<!-- Bootstrap datepicker-->
<script src="<?php echo base_url("assets/oprs/js/bootstrap-datetimepicker.min.js");?>"></script>
<!-- Autocomplete-->
<script src="<?php echo base_url("assets/oprs/js/sh-autocomplete.min.js");?>"></script>
<!-- Moment-->
<script src="<?php echo base_url("assets/oprs/js/moment.min.js");?>">moment().format();moment().tz("Asia/Manila").format();</script>
<!-- Text Editor TinyMCE -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.9.2/tinymce.min.js"></script>
<!-- <script src="<?php echo base_url("assets/oprs/js/tinymce.min.js");?>"></script> -->
<!-- Loading Screen -->
<script src="<?php echo base_url("assets/oprs/js/jquery.loading.admin.js"); ?>"></script>

<!-- Datatable buttons -->
<script src="<?php echo base_url("assets/oprs/js/buttons.flash.min.js"); ?>"></script>
<script src="<?php echo base_url("assets/oprs/js/jszip.min.js"); ?>"></script>
<script src="<?php echo base_url("assets/oprs/js/pdfmake.min.js"); ?>"></script>
<script src="<?php echo base_url("assets/oprs/js/vfs_fonts.js"); ?>"></script>
<script src="<?php echo base_url("assets/oprs/js/buttons.html5.min.js"); ?>"></script>
<script src="<?php echo base_url("assets/oprs/js/buttons.print.min.js"); ?>"></script>

<script>
    window.addEventListener('DOMContentLoaded', event => {

// Toggle the side navigation
const sidebarToggle = document.body.querySelector('#sidebarToggle');
if (sidebarToggle) {
    // Uncomment Below to persist sidebar toggle between refreshes
    // if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
    //     document.body.classList.toggle('sb-sidenav-toggled');
    // }
    sidebarToggle.addEventListener('click', event => {
        event.preventDefault();
        document.body.classList.toggle('sb-sidenav-toggled');
        localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
    });
}

});

</script>
</body>
</html>