
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
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
					<div class="row mb-3">
						<div class="col">
							<label for="art_year" class="form-label">Year</label>
							<select class="form-select" id="art_year" name="art_year" >
								<option value="">Select year</option>
								<?php foreach ($u_year as $j): ?>
								<?php echo '<option value=' . $j->jor_year . '>' . $j->jor_year . '</option>'; ?>
								<?php endforeach;?>
							</select>
						</div>
						<div class="col">
							<label for="art_jor_id" class="form-label">Volume, Issue</label>
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
					<div class="mb-3">
						<label for="art_title" class="form-label">Title of Article</label>
						<textarea class="form-control  bg-white" id="art_title" name="art_title"></textarea>
					</div>
					<div class="row mb-3">
						<div class="col">
							<label for="art_keywords" class="form-label">Keywords</label>
							<span class="badge rounded-pill bg-secondary">Separate in comma</span>
							<input type="text" class="form-control" id="art_keywords" placeholder="ex. Keyword 1,Keyword 2,Keyword 3" name="art_keywords">
						</div>
						<div class="col">
							<label for="art_page" class="form-label">Page Nos</label>
							<input type="text" class="form-control" id="art_page" placeholder="ex. 100-200" name="art_page">
						</div>
					</div>
					<div class="mb-3">
						<label for="art_abstract_file" class="form-label">Abstract</label>
						<div class="input-group">
							<input type="text" class="form-control" id="art_abstract_file" name="art_abstract_file" readonly>
							<div class="input-group-append">
								<a class="btn btn-outline-secondary" target="_blank" id="view_abstract"><span class="oi oi-eye"></span> View</a>
							</div>
						</div>
					</div>
					<div class="mb-3">
						<label for="art_abstract_file" class="form-label">Upload New Abstract <span class="badge rounded-pill bg-success">PDF Only</span> <span class="badge rounded-pill bg-warning text-dark">20MB Limit</span></label>
						<input type="file" class="form-control" name="art_abstract_file_new" accept="application/pdf" >
					</div>
					<div class="mb-3">
						<label for="art_full_text_pdf" class="form-label">Full Text PDF</label>
						<div class="input-group">
							<input type="text" class="form-control" id="art_full_text_pdf" name="art_full_text_pdf" readonly>
							<div class="input-group-append">
								<a class="btn btn-outline-secondary" target="_blank" id="view_pdf"><span class="oi oi-eye"></span> View</a>
							</div>
						</div>
					</div>
					<div class="mb-3">
						<label for="art_full_text_pdf" class="form-label">Upload New Full Text PDF <span class="badge rounded-pill bg-success">PDF Only</span> <span class="badge rounded-pill bg-warning text-dark">20MB Limit</span></label>
						<input type="file" class="form-control" name="art_full_text_pdf_new" accept="application/pdf" >
					</div>
					<div class="row mb-3"> 
						<div class="col autocomplete">
							<label for="art_author" class="form-label">Author</label>
							<input class="form-control" id="art_author" name="art_author" placeholder="Search by name or specialization" required>	
						</div>
						<div class="col">
							<label for="art_affiliation" class="form-label">Affiliation</label>
							<input type="text" class="form-control" id="art_affiliation" name="art_affiliation">
						</div>
						<div class="col">
							<label for="art_email" class="form-label">Email Address</label>
							<input type="text" class="form-control" id="art_email" name="art_email" placeholder="Enter a valid email">
						</div>
						<div class="col-1"></div>
					</div>
					<span id="coa_list"></span>
				</div>
				<div class="modal-footer">
					<?php if ($this->session->userdata('_prv_edt') == 1) {?>
					<button type="button" id="btn-add-coauthor" class="btn btn-secondary"><span class="oi oi-people"></span> Add Co-Author</button>
					<?php }?>
					<?php if ($this->session->userdata('_prv_del') == 1) {?>
					<button type="button" class="btn btn-danger me-auto" onclick="_remove('delete-article')">Remove Article</button>
					<?php }?>
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					<?php if ($this->session->userdata('_prv_edt') == 1) {?>
					<button type="submit" class="btn btn-primary" name="update-article">Apply Changes</button>
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
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<form id="journal_modal_form">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel"><span class="oi oi-pencil"></span> Edit Journal</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
						<input type="hidden" id="jor_id" name="jor_id">
						<div class="row">
							<div class="col">
								<div class="mb-3">
									<label for="jor_volume" class="form-label">Volume No.</label>
									<input type="text" class="form-control" id="jor_volume" name="jor_volume">
								</div>
								<div class="mb-3">
									<label for="jor_issue" class="form-label">Issue No.</label>
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
								<div class="row mb-3">
									<div class="col">
										<label for="jor_month" class="form-label">Month <span class="badge rounded-pill badge-secondary">Optional</span></label>
										<input type="text" class="form-control" id="jor_month" name="jor_month" placeholder="ex. Jan-Dec">
									</div>
									<div class="col">
										<label for="jor_year" class="form-label">Year</label>
										<input type="text" class="form-control" id="jor_year" name="jor_year" placeholder="ex. 2018" maxlength="4">
										<small class="text-danger"><?php echo form_error('jor_year'); ?></small>
									</div>
									<div class="col">
										<label for="jor_issn" class="form-label">ISSN</label>
										<input type="text" class="form-control" id="jor_issn" name="jor_issn" value="0117-3294" readonly>
									</div>
								</div>
								<div class="mb-3">
									<label for="jor_cover" class="form-label">Upload Photo <span class=" badge rounded-pill bg-secondary">Optional</span> <span class="badge rounded-pill bg-success">JPG</span> <span class="badge rounded-pill bg-warning text-dark">2MB Limit</span></label>
									<input type="file" class="form-control" id="jor_cover" name="jor_cover" accept="image/*">
								</div>
								<div class="mb-3">
									<label for="jor_description" class="form-label">Description</label>
									<textarea rows="6" class="form-control" id="jor_description" name="jor_description" placeholder="Type description here" maxlength="500"></textarea>
								</div>
							</div>
							<div class="col">
								<label for="cover_photo" class="form-label">Preview</label><br>
								<img class="img-thumbnail" id="cover_photo" src="<?php echo base_url('assets/images/unavailable.jpg'); ?>"  style="width: 600px; height: 600px; object-fit: cover;">
							</div>
						</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger me-auto" onclick="_remove('delete-journal')">Remove Journal</button>
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">Apply Changes</button>
				</div>
			</form>
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
			
			<form id="editorial_modal_form">
				<div class="modal-body">
					<input type="hidden" id="edt_id" name="edt_id">
					<input type="hidden" id="edt_photo_exist" name="edt_photo_exist">
					<div class="row">
						<div class="col">
							<div class="mb-3">
								<div class="row">
									<div class="col">
										<label for="edt_year" class="form-label">Year</label>
										<select class="form-select" id="edt_year" name="edt_year">
											<option value="">Select Year</option>
											<?php for($i=date('Y'); $i>='1993';$i--){ ?>
											<?php echo '<option value=' . $i . '>' . $i . '</option>'; ?>
											<?php }?>
										</select>
									</div>
									<div class="col">
										<label for="edt_volume" class="form-label">Volume</label>
										<select class="form-select" id="edt_volume" name="edt_volume" placeholder="ex. X">
											<option value='' class="text-dark">Select Volume</option>
											<?php foreach ($u_journal as $j): ?>
											<?php echo '<option value=' . $j->jor_volume . ' class="text-dark text-uppercase">' . $j->jor_volume . '</option>'; ?>
											<?php endforeach;?>
										</select>
									</div>
									<div class="col">
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
								<select class="form-select" id="edt_sex" name="edt_sex">
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
								<label for="edt_address"  class="form-label">Address</label>
								<input type="text" class="form-control" id="edt_address" name="edt_address" placeholder="ex. Juan Dela Cruz Street, Manila">
							</div>
							<div class="mb-3">
								<label for="edt_country" class="form-label">Country</label>
								<select class="form-select" id="edt_country" name="edt_country">
									<option value="">Select Country</option>
									<?php foreach ($country as $c): ?>
									<?php echo '<option value=' . $c->country_id . '>' . $c->country_name . '</option>';?>
									<?php endforeach;?>
								</select>
							</div>
							<div class="mb-3">
								<label for="edt_affiliation" class="form-label">Specialization</label>
								<span class="badge badge-secondary">Separate in comma</span>
								<input type="text" class="form-control" id="edt_specialization" name="edt_specialization" placeholder="ex. Specialization 1, Specialization 2">
							</div>
						</div>
						<div class="col">
							<div class="mb-3">
								<label for="editorial_photo" class="form-label">Preview </label><br/>
								<img class="img-thumbnail" id="editorial_photo" style="width: 200px; height: 250px; object-fit: cover;">
							</div>
							<div class="mb-3">
								<label for="edt_photo" class="form-label">Upload Photo <span class="badge rounded-pill bg-success">JPG only</span> <span class="badge rounded-pill bg-warning text-dark">200 x 250 pixels</span> <span class="badge rounded-pill bg-warning text-dark">2MB Limit</span></label>
								<input type="file" class="form-control" id="edt_photo" name="edt_photo" accept="image/jpeg" >
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger me-auto" onclick="_remove('delete-editorial')">Delete</button>
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">Apply Changes</button>
				</div>
			</form>
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
				<button type="button" class="btn btn-warning me-auto" id="browse_dp">Browse</button>
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
				<h5 class="modal-title" id="user_modal">Change Password</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="form_change_pass">
                <div class="mb-3">
                    <label class="form-label fw-bold" for="old_password">Current Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control form-control-lg" id="old_password" name="old_password" placeholder="Enter old password" >
                        <span class="input-group-text bg-white text-muted rounded-end" id="inputGroupPrepend3"><a class="text-muted cursor-pointer" href="javascript:void(0);" onclick="togglePassword('#old_password', '#old_password_icon')"><i class="fa fa-eye-slash" id="old_password_icon"></i></a></span>             
                    </div>
                </div>
				<div class="mb-3">
					<label for="acc_password" class="form-label fw-bold">New Password</label>
                    <div class="input-group">
						<input type="password" class="form-control form-control-lg" id="new_password" name="acc_password" placeholder="Enter new password">
                        <span class="input-group-text bg-white text-muted rounded-end">
							<a class="text-muted cursor-pointer" href="javascript:void(0);" onclick="togglePassword('#new_password','#password_icon','#repeat_password')"><i class="fa fa-eye-slash" id="password_icon"></i></a></span>             
                    </div>
				</div>
                <div class="card mb-3 d-none" id="ejournal_password_strength_container">
                    <div class="card-body text-secondary">
                        <div><span class="me-1 fs-6">Password strength:</span><span class="fw-bold" id="ejournal-password-strength"></span></div>
                        <div class="progress mt-1" style="height: .5rem;">
                            <div class="progress-bar" role="progressbar"  id="ejournal-password-strength-bar" aria-label="Success example" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <ul class="mt-3 small text-muted ps-3">
                            <li>8-20 characters long.</li>
                            <li>At least 1 letter.</li>
                            <li>At lestt 1 number.</li>
                            <li>At least 1 special character.</li>
                        </ul>
                    </div>
                </div>
				<div class="mb-3">
					<label for="repeat_password" class="form-label fw-bold">Confirm Password</label>
					<input type="password" class="form-control form-control-lg" name="repeat_password" id="repeat_password" placeholder="Repeat password">
				</div>
				<!-- <div class="alert alert-danger" role="alert">
					<span class="oi oi-warning"></span> You will be redirected to login page after saving.
				</div> -->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-primary" id="change_password">Save</button>
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
<div class="modal fade" id="feedbackModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body p-4">
        <h5 class="fw-bold main-link">Thank you for visiting the eJournal/eReview system!</h5>
        <p>To improve the performance of the system, kindly provide us your feedback.</p>
        <hr>
        <form id="feedback_form">
            <h6 class="fw-bold mb-0">User Interface</h6>
            <div class="d-flex gap-3 mb-0">
              <span class="rating-star rate-ui" data-value="1">&#9733;</span>
              <span class="rating-star rate-ui" data-value="2">&#9733;</span>
              <span class="rating-star rate-ui" data-value="3">&#9733;</span>
              <span class="rating-star rate-ui" data-value="4">&#9733;</span>
              <span class="rating-star rate-ui" data-value="5">&#9733;</span>
            </div>
            <textarea class="form-control" id="fb_suggest_ui" rows="3" placeholder="Type your suggestions here..." maxlength="300"></textarea>
            <div id="char_count_ui" class="character-counter text-muted">0 / 300 characters</div>
            <div class="rate-ui-validation text-danger mt-2"></div>
      
            <hr/>

            <h6 class="fw-bold mb-0">User Experience</h6>
            <div class="d-flex gap-3 mb-0">
              <span class="rating-star rate-ux" data-value="1">&#9733;</span>
              <span class="rating-star rate-ux" data-value="2">&#9733;</span>
              <span class="rating-star rate-ux" data-value="3">&#9733;</span>
              <span class="rating-star rate-ux" data-value="4">&#9733;</span>
              <span class="rating-star rate-ux" data-value="5">&#9733;</span>
            </div>

            <textarea class="form-control" name="fb_suggest_ux" id="fb_suggest_ux" rows="3" placeholder="Type your suggestions here..." maxlength="300"></textarea>
            <div id="char_count_ux" class="character-counter text-muted">0 / 300 characters</div>
            <div class="rate-ux-validation text-danger mt-2"></div>

                  
            <div class="mt-3 mb-0 w-100" id="google_recaptchav2_container">
                <div id="captcha_logout"></div>
                <!-- <div data-sitekey="6LcTEV8qAAAAACVwToj7gI7BRdsoEEhJCnnFkWC6" id="captcha_logout"></div> -->
                <p class="text-danger" id="g-recaptcha"></p>
            </div>

        </form>
      </div>
      <div class="modal-footer">
          <button class="btn btn-light" type="button" data-bs-dismiss="modal">Later</button>
          <button type="button" id="submit_feedback" class="btn btn-primary" disabled>Submit</button>
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
        <h5 class="modal-title" id="exampleModalLabel"><span class="fas fa-database" style="width:20px"></span> Backup/Restore Database </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                    <label class="form-check-label mt-2" for="quick_export">
                        Quick - Create backup of the database
                    </label>
                </div>
                <div class="form-check mt-1 mb-3">
                    <input class="form-check-input" type="radio" name="export_method" id="custom_export" value="2">
                    <label class="form-check-label mt-2" for="custom_export">
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
					<div class="input-group">
					<input type="file" class="form-control" id="import_file" name="import_file"  >
						<button class="btn btn-dark" type="submit" id="inputGroupFileAddon04">Go</button>
					</div>
                    <!-- <div class="input-group is-invalid">
                        <div class="custom-file">
                        <input type="file" class="custom-file-input" id="import_file" name="import_file"  >
                        <label class="custom-file-label" for="import_file">Choose file...</label>
                        </div>
                        <div class="input-group-append">
                        <button type="submit" class="btn btn-dark">Go</button>
                        </div>
                    </div> -->
                    <div class="invalid-feedback">
                    </div>
      			</form>

				<div id="success_import" class="mt-3"></div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>
<!-- /.DATBASE MODAL -->

<!-- EMAIL LIBRARY MODAL -->
<div class="modal fade" id="emailContentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="email_content_form" name="email_content_form">
        <input type="hidden" id="enc_process_id" name="enc_process_id">
          <div class="row">
            <div class="col-5">
              <div class="mb-3">
                <label class="fw-bold form-label"  for="enc_subject">Email subject</label>
                <input type="text" class="form-control" id="enc_subject" name="enc_subject" required>
              </div>
              <div class="mb-3">
                <label class="fw-bold form-label"  for="enc_description">Notification trigger</label>
                <input type="text" class="form-control" id="enc_description" name="enc_description" required>
              </div>
              <div class="mb-3">
                <label class="fw-bold form-label" for="enc_cc">CC <span class="badge rounded-pill bg-secondary">Optional</span></label>
                <input type="text" class="form-control" id="enc_cc" name="enc_cc" placeholder="juandelacruz@gmail.com,mariadelacruz@gmail.com">
                <small class="text-muted pt-2">Please separate emails by comma (,)</small>
              </div>
              <div class="mb-3">
                <label class="fw-bold form-label" for="enc_bcc">BCC <span class="badge rounded-pill bg-secondary">Optional</span></label>
                <input type="text" class="form-control" id="enc_bcc" name="enc_bcc" placeholder="juandelacruz@gmail.com,mariadelacruz@gmail.com">
                <small class="text-muted pt-2">Please separate emails by comma (,)</small>
              </div>
              <div class="mb-3 enc_user_group">
                <label class="fw-bold form-label" for="enc_user_group">User group
                <br/><small class="text-muted">Following user roles will also receive this email notification.</small>
                </label>
               
                <?php foreach($user_roles as $row): ?>
                  <div class="form-check"> 
                    <input class="form-check-input" id="enc_user_group<?php echo $row->role_id;?>" name="enc_user_group[]" value="<?php echo $row->role_id;?>" type="checkbox" > 
                    <label class="form-check-label ms-1 mt-2" for="<?php echo $row->role_id;?>"> 
                     <?php echo $row->role_name;?> 
                    </label> 
                  </div>
                  <?php endforeach ?>
              </div>
            </div>
            <div class="col-7">
              <div class="form-group">
              <label class="fw-bold form-label" for="enc_content">Email content</label>
                <div class="alert alert-warning mb-0" role="alert">
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
        <button type="button" id="update_email_content_btn" class="btn btn-primary">Apply Changes</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!--/. EMAIL LIBRARY MODAL -->

<!-- Account Setting Modal -->
<div class="modal fade" id="accountSettingModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="user_modal">Account Setting</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="form_update_account">
                <input type="hidden" name="usr_id" id="usr_id">
                <div class="mb-3">
                    <label class="form-label" for="usr_full_name">Account Name</label>
                    <input type="text" class="form-control form-control-lg" id="usr_full_name" name="usr_full_name" placeholder="First Name, Last Name" >
                </div>
                <div class="mb-3">
                    <label class="form-label" for="usr_username">Email</label>
                    <input type="email" class="form-control form-control-lg" id="usr_username" name="usr_username" placeholder="Enter a valid email address" >
                </div>
                <div class="mb-3">
                    <label class="form-label" for="usr_sex">Sex</label>
                    <select id="usr_sex" name="usr_sex" class="form-select form-control-lg">
                      <option value="" selected>Select Sex</option>
                      <option value='1'>Male</option>
                      <option value='2'>Female</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="usr_contact">Contact</label>
                    <input type="text" class="form-control form-control-lg" name="usr_contact" id="usr_contact" placeholder="Enter 11-digit number" >
                </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>
        </div>
    </div>
</div>
<!-- /.Account Setting Modal -->

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
<!-- <script src="<?php echo base_url("assets/oprs/js/chart.js");?>"></script> -->
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
<script src="<?php echo base_url("assets/js/jquery.loading.admin.js"); ?>"></script>

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