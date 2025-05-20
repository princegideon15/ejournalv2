
<footer class="py-4 bg-light mt-auto">
      <div class="container-fluid px-4">
          <div class="d-flex align-items-center justify-content-between small">
              <div class="text-muted">Copyright &copy; 2018 NRCP Online Research Journal (eJournal), Online Peer Review System (eReview) All Rights Reserved</div>
			  <div class="text-muted">
				Currently v3.0.0
			  </div>
              <!-- <div>
                  <a href="javascript:void(0);">Privacy Policy</a>
                  &middot;
                  <a href="javascript:void(0);">Terms &amp; Conditions</a>
              </div> -->
          </div>
      </div>
    </footer>
  </div>
</div>



<!-- Manage email notification contents -->
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
                <label class="fw-bold form-label" for="enc_subject">Email subject</label>
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
                </label>
                <p><small class="text-muted">Following user roles will also receive this email notification.</small></p>
               
                <?php foreach($user_roles as $row): ?>
                  <div class="form-check"> 
                    <input class="form-check-input" id="enc_user_group<?php echo $row->role_id;?>" name="enc_user_group[]" value="<?php echo $row->role_id;?>" type="checkbox" > 
                    <label class="form-check-label mt-2" for="<?php echo $row->role_id;?>"> 
                     <?php echo $row->role_name;?> 
                    </label> 
                  </div>
                  <?php endforeach ?>
              </div>
            </div>
            <div class="col-7">
              <div class="mb-3">
              <label class="fw-bold" for="enc_content">Email content</label>
                <div class="alert alert-warning" role="alert">
                  <span class="fas fa-exclamation-triangle"></span> Do not change or remove words with square brackets. [EXAMPLE]
                </div>
                <textarea type="text" id="enc_content" class="form-control"></textarea>
              </div>
              <div class="alert alert-warning pt-3" role="alert">
                <span class="fas fa-exclamation-triangle"></span> Do not change or remove words with square brackets. [EXAMPLE]
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
<!--/. Manage email notification contents -->



<!-- csf feedback -->
<div class="modal fade" id="csf_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Client Satisfaction Feedback</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- /.csf feedback -->



<!-- UPDATE USER -->
<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" >
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="form_edit_user">
          <div class="mb-3">
            <label for="usr_full_name">Full Name</label>
            <input type="text" class="form-control" id="usr_full_name" name="usr_full_name" placeholder="Your username">
          </div>
          <div class="mb-3">
            <label for="usr_username" class="form-label">Email</label>
            <input type="email" class="form-control" id="usr_username" name="usr_username" placeholder="Your email address">
          </div>
          <div class="mb-3">
            <label for="usr_password" class="form-label">New Password (If any)</label>
            <input type="password" class="form-control" id="usr_password" name="usr_password" placeholder="Your password">
          </div>
          <div class="mb-3">
            <label for="usr_rep_password" class="form-label">Repeat New Password</label>
            <input type="password" class="form-control" id="usr_rep_password" name="usr_rep_password" placeholder="Repeat your password">
          </div>
          <div class="mb-3">
            <label for="usr_contact" class="form-label">Contact</label>
            <input type="text" class="form-control" id="usr_contact" name="usr_contact" placeholder="Your contact number">
          </div>
          <div class="mb-3">
            <label for="usr_sex" class="form-label">Sex</label>
            <select id="usr_sex" name="usr_sex" class="form-select">
              <option value="" selected>Select Sex</option>
              <option value='1'>Male</option>
              <option value='2'>Female</option>
            </select>
          </div>
          <!-- <div class="mb-3">
            <label for="usr_sys_acc">System Access</label>
            <select id="usr_sys_acc" name="usr_sys_acc" class="form-select">
              <option value="" selected>Select System Access</option>
              <option value='1'>OPRS</option>
              <option value='2'>eJournal</option>
            </select>
          </div> -->
          <div class="mb-3">
            <label for="usr_role" class="form-label">User Role</label>
            <select id="usr_role" name="usr_role" class="form-select">
				<option value="" selected>Select User Role</option>
				<?php foreach($user_types as $row): ?>
				<option value="<?= $row->role_id ?>"><?= $row->role_name ?></option>
				<?php endforeach ?>
			</select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger me-auto deactivate " style="display:none;"  onclick="act_deact_modal(2);">Deactivate Account</button>
          <button type="button" class="btn btn-success me-auto activate " style="display:none;" onclick="act_deact_modal(0);">Activate Account</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- /.UPDATE USER -->

<!-- REVIEW INPUT -->
<div class="modal fade" id="confirmDeactivationModal" tabindex="-1" role="dialog" aria-hidden="true" style="z-index:999999">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" onclick="activate_deactivate_user();">Confirm</button>
      </div>
    </div>
  </div>
</div>
<!-- /.REVIEW INPUT -->


<!-- Clear Logs Modal -->
<div class="modal fade" id="clearLogsModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Clear Logs</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Please check if you already created backup before proceeding.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="btn_clear_logs" class="btn btn-primary">Proceed</button>
      </div>
    </div>
  </div>
</div>

<!-- Process Status-->
<div class="modal fade" id="trackingModal" tabindex="-1" role="dialog" aria-labelledby="trackingModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Status Tracking</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body m-0 p-0" style="font-size:20px;">
        <div class="list-group w-100" id="track_list">
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<!-- Process Manuscript-->
<div class="modal fade" id="processModal" tabindex="-1" role="dialog" aria-labelledby="processModal">
  <div class="modal-dialog modal-lg" role="document" style="max-width:90%">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Process Manuscript</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="process_manuscript_form" autocomplete="off">
          <div class="row">
            <div class="col-6">
              <div class="form-group" id="form_journal">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="new-tab" data-bs-toggle="tab" href="#new" role="tab" aria-controls="new" aria-selected="true"><span class="fa fa-book"></span> New Journal</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="article-tab" data-bs-toggle="tab" href="#article" role="tab" aria-controls="article" aria-selected="false"><span class="fa fa-plus-square"></span> Add Article</a>
                  </li>
                </ul>
                <div class="tab-content p-3" id="myTabContent">
                  <div class="tab-pane fade show active" id="new" role="tabpanel" aria-labelledby="new-tab">
                    <div class="row">
                      <div class="col mb-3">
                        <label class="fw-bold form-label" for="jor_volume">Volume No.</label>
                        <select class="form-select" id="jor_volume" name="jor_volume" placeholder="Select existing or Type new Volume no." style="background-color:white">
                          <?php foreach ($u_journal as $j): ?>
                          <?php echo '<option value=' . $j->jor_volume . '>' . $j->jor_volume . '</option>'; ?>
                          <?php endforeach;?>
                        </select>
                      </div>
                      <div class="col mb-3">
                        <label class="fw-bold form-label" for="jor_issue">Issue No.</label>
                        <select class="form-select" id="jor_issue" name="jor_issue">
                          <option value="">Select Issue no.</option>
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
                      <div class="col mb-3">
                        <label class="fw-bold form-label" for="jor_year">Year</label>
                        <input type="number" class="form-control" id="jor_year" name="jor_year" max="9999" min="1000" >
                      </div>
                    </div>
                  </div>
                  <div class="tab-pane fade" id="article" role="tabpanel" aria-labelledby="article-tab">
                    <div class="row">
                      <div class="col">
                        <div class="col mb-3">
                          <label for="jor_issue" class="form-label fw-bold">Year</label>
                          <select class="form-select" id="art_year" name="art_year">
                            <option value="">Select year</option>
                            <?php foreach ($u_year as $j): ?>
                            <?php echo '<option value=' . $j->jor_year . '>' . $j->jor_year . '</option>'; ?>
                            <?php endforeach;?>
                          </select>
                        </div>
                      </div>
                      <div class="col mb-3">
                        <label for="art_issue" class="fw-bold form-label">Volume, Issue</label>
                        <select class="form-select" id="art_issue" name="art_issue">
                          <option value="">Select Volume no., Issue no.</option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- <small class="text-muted">Unselectable options in <span class="text-info">Issue No.</span> means were existing already.</small> -->
              </div>
                <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                  <a class="nav-item nav-link active" data-bs-toggle="tab" href="#nav-rev" role="tab" aria-controls="nav-rev" aria-selected="true">Reviewers</a>
                  <a class="nav-item nav-link" data-bs-toggle="tab" href="#nav-suggested-peer" role="tab" aria-controls="nav-suggested-peer" aria-selected="true">Suggested Peer Reviewers
					<span class="badge rounded-pill bg-primary d-none" id="suggested_peer_count"></span>
				  </a>
                </div>
                </nav>
                <div class="tab-content p-3" id="nav-tabContent">
					<div class="tab-pane fade show active" id="nav-rev" role="tabpanel">
						<div class="form-group mb-3">
							<div id="rev_acc">
							<div class="card">
								<div class="card-header p-0" id="heading1"  data-bs-toggle="collapse" data-bs-target="#collapse1">
								<h5 class="mb-0">
								<button class="btn btn-link text-decoration-none" type="button">
								<span class="fa fa-address-card"></span> Reviewer 1 : <span id="rev_header1"></span>
								</button>
								</h5>
								</div>
								<div id="collapse1" class="collapse show" data-parent="#rev_acc">
								<div class="card-body">
									<div class="row mb-3">
										<div class="col-3">
											<select class="form-select" id="trk_title1" name="trk_title[]" placeholder="Title">
												<option value="">Select Title</option>
												<?php foreach ($titles as $t): ?>
												<?php echo '<option value=' . $t->title_name . '>' . $t->title_name . '</option>'; ?>
												<?php endforeach;?>
											</select>
										</div>
										<div class="col autocomplete">
											<input type="text" class="form-control " id="trk_rev1" name="trk_rev[]" placeholder="Search by Name or Specialization">
										</div>
									</div>
									<div class="row">
										<div class="col mb-3">
											<input type="text" class="form-control" placeholder="Email" id="trk_rev_email1" name="trk_rev_email[]">
										</div>
										<div class="col mb-3">
											<input type="text" class="form-control" placeholder="Contact" id="trk_rev_num1" name="trk_rev_num[]">
										</div>
										<input type="hidden" id="trk_rev_id1" name="trk_rev_id[]">
									</div>
									<div class="row">
										<div class="col">
											<input type="text" class="form-control" placeholder="Specialization" id="trk_rev_spec1" name="trk_rev_spec[]">
										</div>
									</div>
								</div>
								</div>
							</div>
							</div>
						</div>
						
						<button class="btn btn-outline-secondary mb-3" id="btn_add_rev" type="button"><span class="fa fa-plus-square"></span> Add Reviewer</button>

						
						<nav>
							<div class="nav nav-tabs" id="nav-tab" role="tablist">
								<a class="nav-item nav-link active" id="nav-timeframe-tab" data-bs-toggle="tab" href="#nav-timeframe" role="tab" aria-controls="nav-timeframe" aria-selected="true"><span class="fas fa-stopwatch"></span> Timeframes</a> 
							</div>
						</nav>
						<div class="tab-content p-3" id="nav-tabContent">
							<div class="tab-pane fade show active" id="nav-timeframe" role="tabpanel" aria-labelledby="nav-timeframe-tab">
								<p class="fw-bold">Accept Review</p>
								<div class="input-group mb-3">
								<input type="number" placeholder="0" id="trk_request_timer" name="trk_request_timer" style="width:70px !important;" min="1" value="5">
								<div class="input-group-append">
									<span class="input-group-text">Days to accept/decline the review request.</span>
								</div>
								</div>
								<p class="fw-bold">Review Request</p>
								<div class="input-group mb-3">
								<input type="number" placeholder="0" id="trk_timeframe" name="trk_timeframe" style="width:70px !important;" min="1" value="30">
								<div class="input-group-append">
									<span class="input-group-text">Days to finish the review task</span>
								</div>
								</div>
							</div>
						</div>

						<nav>
							<div class="nav nav-tabs" id="nav-tab" role="tablist">
								<a class="nav-item nav-link active" id="nav-timeframe-tab" data-bs-toggle="tab" href="#nav-timeframe" role="tab" aria-controls="nav-timeframe" aria-selected="true"><span class="fas fa-check-square"></span> Optional</a>
							</div>
						</nav>
						<div class="tab-content p-3" id="nav-tabContent">
							<div class="tab-pane fade show active" id="nav-timeframe" role="tabpanel" aria-labelledby="nav-timeframe-tab">
								<div class="form-group text-left">
								<div class="form-check">
									<input type="checkbox" class="form-check-input" value="1" id="rev_hide_auth" name="rev_hide_auth">
									<label class="form-check-label pt-1" for="rev_hide_auth"> Hide Authors to Reviewers <small>(Names, affiliations and emails are hidden)</small></label>
								</div>
								</div>
								<div class="form-group mb-3 text-left">
								<div class="form-check">
									<input type="checkbox" class="form-check-input" value="1" id="rev_hide_rev" name="rev_hide_rev">
									<label class="form-check-label pt-1" for="rev_hide_rev"> Hide Reviewers to Authors <small>(Names, affiliations and emails are hidden)</small></label>
								</div>
								</div>
								<div class="form-group">
								<label class="fw-bold form-label" for="trk_remarks">Remarks</label>
								<textarea class="form-control" id="trk_remarks" name="trk_remarks" placeholder="(Type N/A if no remarks)" onkeydown="countChar(this)"></textarea>
								<small class="text-muted float-right limit"></small>
								</div>
							</div>
						</div>
					</div>

					<div class="tab-pane fade" id="nav-suggested-peer" role="tabpanel">
						<div class="overflow-auto" style="max-height:500px" id="suggested_peers">
						</div>
					</div>
					
                </div>
			</div>
            <div class="col-6">
              <div class="accordion" id="rev_acc_mail">
                <h6 class="fw-bold">Request for Manuscript Review Email</h6>
				<div class="alert alert-warning" role="alert">
                  <span class="fas fa-exclamation-triangle"></span> Do not change or remove words with square brackets. <b>[EXAMPLE]</b>
                </div>
                <div class="card">
                  <div class="card-header p-0" id="heading1" data-bs-toggle="collapse" data-bs-target="#collapse_mail1">
                    <h5 class="mb-0">
                    <button class="btn btn-link text-decoration-none" type="button" >
                    <span class="fa fa-envelope"></span> Reviewer 1 : <span id="rev_header_mail1"></span>
                    </button>
                    </h5>
                  </div>
                  <div id="collapse_mail1" class="collapse show" data-parent="#rev_acc_mail">
                    <div class="card-body p-0">
                      <textarea type="text" id="tiny_mail1" name="tiny_mail[]" style="height:500px"></textarea>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
              <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- REVIEW INPUT -->
<div class="modal fade" id="confirmDeactivationModal" tabindex="-1" role="dialog" aria-hidden="true" style="z-index:999999">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" onclick="activate_deactivate_user();">Confirm</button>
      </div>
    </div>
  </div>
</div>
<!-- /.REVIEW INPUT -->

<!-- Upload Manuscript-->
<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModal" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Manuscript Details</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="manuscript_form">
					<h6 class="text-uppercase text-muted fw-bold bg-light border border-2 p-2" style="font-size:14px"><span class="fa fa-info-circle me-1"></span>Upload Manuscript</h6>
					<div class="mb-3">
						<label class="fw-bold form-label" for="man_title">Title</label>
						<textarea class="form-control" id="man_title" name="man_title" placeholder=""></textarea>
					</div>
					<div class="mb-3">
						<label for="man_type" class="fw-bold form-label">Type of Publcation</label>
						<select class="form-select" name="man_type" id="man_type">
							<option value="">Select Type</option>
							<?php foreach($publ_types as $row): ?>
								<option value="<?php echo $row->id;?>"><?php echo $row->publication_desc;?></option>
							<?php endforeach ?>
						</select>
					</div>
					<div class="mb-3">
						<label class="fw-bold form-label" for="man_keywords">Keywords</label>
						<input type="text" class="form-control" id="man_keywords" name="man_keywords" placeholder="ex. science, community, etc.">
					</div>
					<h6 class="text-uppercase text-muted fw-bold bg-light border border-2 p-2" style="font-size:14px"><span class="fa fa-info-circle me-1"></span>Author Details</h6>
					<div class="mb-1">
						<div class="row">
							<p class="fw-bold">Corresponding Author: <span class="text-primary"><?php echo $author['author_type'] ?? ''?></span>
							<input type="hidden" class="form-control" id="corr_usr_id" name="corr_usr_id" value="<?php echo $author['user_id'] ?? ''?>">
							<div class="col">
								<div class="autocomplete" style="width:100% !important">
									<label class="fw-bold principal" for="corr_author">Full Name</label>
									<input type="text" class="form-control mt-2 bg-light" id="corr_author" name="corr_author"
										placeholder="First Name M.I. Last name" value="<?php echo $author['name'] ?? ''?>" readonly>
								</div>
							</div>
							<div class="col">
								<label for="corr_affiliation" class="fw-bold form-label">Affiliation</label>
								<input type="text" class="form-control bg-light" placeholder="Enter affiliation" id="corr_affiliation"
							name="corr_affiliation" value="<?php echo $author['affiliation'] ?? ''?>" readonly>
							</div>
							<div class="col">
								<label for="corr_email" class="fw-bold form-label">Email</label>
								<input type="email" class="form-control bg-light" placeholder="Enter a valid email" id="corr_email" name="corr_email" value="<?php echo $author['email'] ?? ''?>" readonly>
							</div>
						</div>
						<div class="form-check form-check-inline mb-3">
							<input class="form-check-input" type="radio" name="man_author_type" id="status1" value="1">
							<label class="form-check-label mt-2" for="status1">Main Author</label>
						</div>
						<div class="form-check form-check-inline">
							<input class="form-check-input" type="radio" name="man_author_type" id="status2" value="2">
							<label class="form-check-label mt-2" for="status2">Co-author</label>
						</div>
						<div class="form-check form-check-inline text-danger" id="author_type_error"></div>
					</div>
					<div class="row mb-3 d-none" id="add_main_author">
							<p class="fw-bold">Main Author: <span id="author_status" class="text-primary"></span></p>
							<input type="hidden" class="form-control" id="man_usr_id" name="man_usr_id">
							<div class="col">
								<div class="autocomplete" style="width:100% !important">
									<label class="fw-bold principal" for="man_author">Full Name</label>
									<input type="text" class="form-control mt-2 bg-light" id="man_author" name="man_author"
										placeholder="First Name M.I. Last name">
								</div>
							</div>
							<div class="col">
								<label for="man_affiliation" class="fw-bold form-label">Affiliation</label>
								<input type="text" class="form-control bg-light" placeholder="Enter affiliation" id="man_affiliation"
							name="man_affiliation">
							</div>
							<div class="col">
								<label for="man_email" class="fw-bold form-label">Email</label>
								<input type="email" class="form-control bg-light" placeholder="Enter a valid email" id="man_email" name="man_email">
							</div>
						</div>
					<div class="mb-3" id="add_coauthors">
						<span id="coauthors"></span>
						<button	button class="btn btn-outline-secondary mr-auto" type="button" id="btn_add_coa"><i
						class="fa fa-plus"></i> Add Co-author</button>
					</div>


					<h6 class="text-uppercase text-muted fw-bold bg-light border border-2 p-2" style="font-size:14px"><span class="fa fa-info-circle me-1"></span>File Uploads</h6>
					<div class="mb-3" id="man_abs_div">
						<label class="fw-bold form-label" for="man_abs">Abstract</label>
						<span class="badge rounded-pill bg-danger" id="badge_abs">PDF</span>
						<span class="badge rounded-pill bg-warning text-dark">20MB Limit</span>
						<input type="file" class="form-control" id="man_abs" name="man_abs" accept="application/pdf">
					</div>
					<div class="mb-3" id="man_file_div">
						<label class="fw-bold form-label" for="man_file">Full Manuscript</label>
						<span class="badge rounded-pill bg-danger">PDF</span>
						<span class="badge rounded-pill bg-warning text-dark">20MB Limit</span>
						<input type="file" class="form-control" id="man_file" name="man_file" accept="application/pdf">
					</div>
					<div class="mb-3" id="man_word_div">
						<label class="fw-bold form-label" for="man_word">Full Manuscript</label>
						<span class="badge rounded-pill bg-primary">WORD</span>
						<span class="badge rounded-pill bg-warning text-dark">20MB Limit</span>
						<input type="file" class="form-control" id="man_word" name="man_word"
							accept=".doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
					</div>
					<div class="mb-3" id="man_latex_div">
						<label class="fw-bold form-label" for="man_latex">LaTex Format</label>
						<span class="badge rounded-pill bg-warning text-dark">20MB Limit</span>
						<input type="file" class="form-control" id="man_latex" name="man_latex">
					</div>
					<div class="mb-3">
						<label class="fw-bold form-label" for="man_pages">Number of pages</label>
						<input type="number" class="form-control w-25" placeholder="0" id="man_pages" name="man_pages"
							min="1">
					</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-primary">Submit</button>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- /.Upload Manuscript-->

<!-- Manuscript Details-->
<div class="modal fade" id="manuscriptModal" tabindex="-1" role="dialog" aria-labelledby="uploadModal" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Manuscript Details</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<table class="table table-bordered">
					<tbody></tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- /.Manuscript Details-->


<!-- Upload Manuscript Revision-->
<div class="modal fade" id="uploadRevisionModal" tabindex="-1" role="dialog" aria-labelledby="uploadRevisionModal" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Manuscript Revision</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<form id="manuscript_revision_form"  enctype="multipart/form-data">
				<div class="modal-body">
						<input type="hidden" id="man_id2" name="man_id2">
						<input type="hidden" id="revision_status" name="revision_status">
						<input type="hidden" id="criteria_status" name="criteria_status">
						<input type="hidden" id="editor_review_status" name="editor_review_status">
						<h6 class="text-uppercase text-muted fw-bold bg-light border border-2 p-2" style="font-size:14px"><span class="fa fa-info-circle me-1"></span>REVISION DOCUMENTS/REMARKS</h6>
						<table class="table">
							<tbody>
								<tr class="d-none" id="criteria_review_result"><td class="fw-bold bg-light">Criteria Review Result</td><td id="criteria_review_result_value"></td></tr>
								<tr id="revision_consolidations_row"><td class="fw-bold bg-light">Consolidated Review/Remarks</td><td id="revision_consolidations"></td></tr>
								<tr><td class="fw-bold bg-light">Remarks</td><td id="revision_remarks"></td></tr>
								<tr id="revision_matrix_template"><td class="fw-bold bg-light">Revision Matrix Template</td><td><a href="<?php echo base_url("assets/oprs/uploads/REVISION_MATRIX_TEMPLATE.docx");?>" download>Download</a></td></tr>
							</tbody>
						</table>
						<h6 class="text-uppercase text-muted fw-bold border border-2 bg-light p-2" style="font-size:14px"><span class="fa fa-info-circle me-1"></span> Upload Revisions</h6>
						<!-- <span class="badge rounded-pill bg-primary">WORD</span> -->
						<div class="mb-3" id="div_man_matrix">
							<label class="fw-bold form-label" for="man_matrix">Revision Matrix</label>
							<span class="badge rounded-pill bg-danger">PDF</span>
							<span class="badge rounded-pill bg-warning text-dark">20MB Limit</span>
							<input type="file" class="form-control" id="man_matrix" name="man_matrix" accept="application/pdf">
						</div>
						<div class="mb-3">
							<label class="fw-bold form-label" for="man_abs">Abstract</label>
							<span class="badge rounded-pill bg-danger">PDF</span>
							<span class="badge rounded-pill bg-warning text-dark">20MB Limit</span>
							<input type="file" class="form-control" id="man_abs" name="man_abs" accept="application/pdf">
						</div>
						<div class="mb-3" id="man_file_div">
							<label class="fw-bold form-label" for="man_file">Full Manuscript</label>
							<span class="badge rounded-pill bg-danger">PDF</span>
							<span class="badge rounded-pill bg-warning text-dark">20MB Limit</span>
							<input type="file" class="form-control" id="man_file" name="man_file" accept="application/pdf">
						</div>
						<div class="mb-3" id="man_word_div">
							<label class="fw-bold form-label" for="man_word">Full Manuscript</label>
							<span class="badge rounded-pill bg-primary">WORD</span>
							<span class="badge rounded-pill bg-warning text-dark">20MB Limit</span>
							<input type="file" class="form-control" id="man_word" name="man_word"
								accept=".doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
						</div>
						<div class="mb-3" id="man_latex_div">
							<label class="fw-bold form-label" for="man_latex">LaTex Format</label>
							<span class="badge rounded-pill bg-warning text-dark">20MB Limit</span>
							<input type="file" class="form-control" id="man_latex" name="man_latex">
						</div>
						<div class="mb-3">
							<label class="fw-bold form-label" for="man_pages">Number of pages</label>
							<input type="number" class="form-control w-25" placeholder="0" id="man_pages" name="man_pages"
								min="1">
						</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-primary">Submit</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- /.Upload Manuscript Revision-->

<!-- Upload Proofread/Revision Manuscript-->
<div class="modal fade" id="uploadProofreadRevisionModal" tabindex="-1" role="dialog" aria-labelledby="uploadProofreadRevisionModal" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Manuscript Proofread/Revision</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="submit_proofread_revision_form">
					<input type="hidden" id="man_id" name="man_id">
				<h6 class="text-uppercase text-muted fw-bold bg-light border border-2 p-2" style="font-size:14px"><span class="fa fa-info-circle me-1"></span>COPY EDITOR REVIEW/REMARKS</h6>
				<!-- <h6 class="text-uppercase text-muted fw-bold bg-light border border-2 p-2" style="font-size:14px"><span class="fa fa-info-circle me-1"></span>LAYOUT DOCUMENTS/REMARKS</h6> -->
					<table class="table">
						<tbody>
							<tr><td class="fw-bold bg-light">Uploaded Copy Editor Review</td><td id="coped_rev_file"></td></tr>
							<!-- <tr><td class="fw-bold bg-light">Uploaded Layout and Format</td><td id="layout_file"></td></tr> -->
							<tr><td class="fw-bold bg-light">Remarks</td><td id="coped_rev_remarks"></td></tr>
							<!-- <tr><td class="fw-bold bg-light">Remarks</td><td id="layout_remarks"></td></tr> -->
						</tbody>
					</table>
					<h6 class="text-uppercase text-muted fw-bold border border-2 bg-light p-2" style="font-size:14px"><span class="fa fa-info-circle me-1"></span> Upload Revisions</h6>
					<div class="mb-3">
						<label class="fw-bold form-label" for="man_abs">Abstract</label>
						<span class="badge rounded-pill bg-danger">PDF</span>
						<span class="badge rounded-pill bg-warning text-dark">20MB Limit</span>
						<input type="file" class="form-control" id="man_abs" name="man_abs" accept="application/pdf">
					</div>
					<div class="mb-3" id="man_file_div">
						<label class="fw-bold form-label" for="man_file">Full Manuscript</label>
						<span class="badge rounded-pill bg-danger">PDF</span>
						<span class="badge rounded-pill bg-warning text-dark">20MB Limit</span>
						<input type="file" class="form-control" id="man_file" name="man_file" accept="application/pdf">
					</div>
					<div class="mb-3" id="man_word_div">
						<label class="fw-bold form-label" for="man_word">Full Manuscript</label>
						<span class="badge rounded-pill bg-primary">WORD</span>
						<span class="badge rounded-pill bg-warning text-dark">20MB Limit</span>
						<input type="file" class="form-control" id="man_word" name="man_word"
							accept=".doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
					</div>
					<div class="mb-3" id="man_latex_div">
						<label class="fw-bold form-label" for="man_latex">LaTex Format</label>
						<span class="badge rounded-pill bg-warning text-dark">20MB Limit</span>
						<input type="file" class="form-control" id="man_latex" name="man_latex">
					</div>
					<div class="mb-3">
						<label class="fw-bold form-label" for="man_pages">Number of pages</label>
						<input type="number" class="form-control w-25" placeholder="0" id="man_pages" name="man_pages"
							min="1">
					</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-primary">Submit</button>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- /.Upload Manuscript Revision-->

<!-- Upload Final Proofread/Revision Manuscript-->
<div class="modal fade" id="uploadProofreadModal" tabindex="-1" role="dialog" aria-labelledby="uploadProofreadModal" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Manuscript Proofread/Revision</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="submit_proofread_form">
					<input type="hidden" id="man_id" name="man_id">
				<h6 class="text-uppercase text-muted fw-bold bg-light border border-2 p-2" style="font-size:14px"><span class="fa fa-info-circle me-1"></span>LAYOUT DOCUMENTS/REMARKS</h6>
					<table class="table">
						<tbody>
							<tr><td class="fw-bold bg-light">Uploaded Layout and Format</td><td id="layout_file"></td></tr>
							<tr><td class="fw-bold bg-light">Remarks</td><td id="layout_remarks"></td></tr>
						</tbody>
					</table>
					<h6 class="text-uppercase text-muted fw-bold border border-2 bg-light p-2" style="font-size:14px"><span class="fa fa-info-circle me-1"></span> Upload Revisions</h6>
					<div class="mb-3">
						<label class="fw-bold form-label" for="man_abs">Abstract</label>
						<span class="badge rounded-pill bg-danger">PDF</span>
						<span class="badge rounded-pill bg-warning text-dark">20MB Limit</span>
						<input type="file" class="form-control" id="man_abs" name="man_abs" accept="application/pdf">
					</div>
					<div class="mb-3" id="man_file_div">
						<label class="fw-bold form-label" for="man_file">Full Manuscript</label>
						<span class="badge rounded-pill bg-danger">PDF</span>
						<span class="badge rounded-pill bg-warning text-dark">20MB Limit</span>
						<input type="file" class="form-control" id="man_file" name="man_file" accept="application/pdf">
					</div>
					<div class="mb-3" id="man_word_div">
						<label class="fw-bold form-label" for="man_word">Full Manuscript</label>
						<span class="badge rounded-pill bg-primary">WORD</span>
						<span class="badge rounded-pill bg-warning text-dark">20MB Limit</span>
						<input type="file" class="form-control" id="man_word" name="man_word"
							accept=".doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
					</div>
					<div class="mb-3" id="man_latex_div">
						<label class="fw-bold form-label" for="man_latex">LaTex Format</label>
						<span class="badge rounded-pill bg-warning text-dark">20MB Limit</span>
						<input type="file" class="form-control" id="man_latex" name="man_latex">
					</div>
					<div class="mb-3">
						<label class="fw-bold form-label" for="man_pages">Number of pages</label>
						<input type="number" class="form-control w-25" placeholder="0" id="man_pages" name="man_pages"
							min="1">
					</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-primary">Submit</button>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- /.Upload Manuscript Revision-->

<!-- Confirm Upload Manuscript -->
<div class="modal fade" id="confirmUploadModal" tabindex="-1">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Submit Manuscript</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				Do you want to submit?
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
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
				<button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="final_manuscript_form">
					<!-- <div class="mb-3" id="man_file_div">
            <label for="man_file">Upload Final Manuscript</label>
            <span class="badge badge-warning">PDF</span>
            <input type="file" class="form-control" id="man_file" name="man_file" accept="application/pdf">
          </div> -->
					<div class="mb-3" id="man_word_div">
						<label class="fw-bold" for="man_word">Upload Final Manuscript</label>
						<span class="badge bg-primary">WORD</span>
						<input type="file" class="form-control" id="man_word" name="man_word"
							accept=".doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
					</div>
					<div class="mb-3" id="man_abs_div">
						<label for="man_abs">Upload Abstract</label>
						<span class="badge bg-danger">PDF</span>
						<input type="file" class="form-control" id="man_abs" name="man_abs" accept="application/pdf">
					</div>
					<div class="mb-3" id="man_key_div">
						<label for="man_keywords">Keywords</label>
						<input type="text" class="form-control" id="man_keywords" name="man_keywords"
							placeholder="ex. science, community, etc.">
					</div>
					<div class="mb-3">
						<label for="man_remarks">Remarks</label>
						<textarea class="form-control" id="man_remarks" name="man_remarks" placeholder="(optional)"
							maxlength="255"></textarea>
						<small class="text-muted float-right limit"></small>
					</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
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
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<p>
					<i class="fa fa-check"></i> Manuscript uploaded successfully.
				</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"
					onclick="refresh_manus();">Close</button>
			</div>
		</div>
	</div>
</div>
<!--/. Submit successful -->

<!-- Process Status-->
<div class="modal fade" id="trackingModal" tabindex="-1" role="dialog" aria-labelledby="trackingModal"
	aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Status Tracking</h5>
				<button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body m-0 p-0" style="font-size:20px;">

				<div class="list-group w-100" id="track_list">
				</div>
			</div>
			<div class="modal-footer">
				<div class="dropdown">
					<a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						Change status
					</a>

					<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
						<a class="dropdown-item" onclick="change_status('99')">Published to other journal platforms</a>
						<!-- <a class="dropdown-item" onclick="change_status('1')">New</a> -->
					</div>
				</div>
				<button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
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
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<embed id="manus_view" width="100%" height="700px" type="application/pdf">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- /.View Manuscript -->

<!-- Editors -->
<div class="modal fade" id="editorialReviewModal" tabindex="-1" role="dialog" aria-labelledby="editorialReviewModal"
	aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Editors</h5>
				<button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<p class="fw-bold"></p>
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
				<button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- /.Editors -->

<!-- Reviewers -->
<div class="modal fade" id="reviewerModal" tabindex="-1" role="dialog" aria-labelledby="reviewerModal"
	aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Reviewers</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<p class="fw-bold"></p>
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
				<div class="me-auto">
					( <span class="fa fa-user-secret"></span> ) <span class="text-primary">Reviewers hidden to
						Authors</span>
					( <span class="fas fa-user-alt-slash ml-2"></span> ) <span class="text-primary">Authors hidden to
						Reviewers</span>
				</div>
				<?php if(_UserRoleFromSession() == 5) { ?>
					<button class="btn btn-outline-success" type="button" id="add_more_reviewers" data-bs-toggle="modal" data-bs-target="#processModal" rel="tooltip" data-bs-placement="top" title="Add Reviewers">
					<span class="fas fa-user-plus"></span></button>
				<?php } ?>
				<button class="btn btn-primary" type="button" id="view_review_results">Review Results</button>
				<button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- /.Reviewers -->

<!-- Reviews -->
<div class="modal fade" id="reviewsModal" tabindex="-1" role="dialog" aria-labelledby="reviewsModal" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Reviews</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<p class="fw-bold"></p>
				<div class="table-responsive">
					<table class="table table-hover" id="reviews_table" width="100%" cellspacing="0"
						style="font-size:14px">
						<thead>
							<tr>
								<th>#</th>
								<th>Reviewer</th>
								<th>Score</th>
								<th>Review Status</th>
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
				<button class="btn btn-outline-secondary me-auto" type="button" data-bs-toggle="modal" data-bs-target="#reviewerModal">Back to Reviewers</button>
				<button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
				<?php if(_UserRoleFromSession() == 5) { ?>
					<button type="button" class="btn btn-primary" onclick="submit_consolidation()">Submit Consolidation</button>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<!-- /.Reviews -->

<!-- Start Review -->
<div class="modal fade" id="startReviewModal" tabindex="-1" role="dialog" aria-labelledby="startReviewModal"
	aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document" style="max-width:90%">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">NRCP Research Journal - Manuscript Review</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
								
								<table class="table table-borderless mb-1" style="font-size:14px;">
										<thead >
											<tr>
												<th>TITLE</th>
												<th class="fw-light" colspan="4" id="rev_title"></th>
											</tr>
											<tr>
												<th>AUTHOR</th>
												<th class="fw-light" colspan="4" id="rev_author"></th>
											</tr>
								</table>
								<hr>
								<table class="table table-borderless mt-1" style="font-size:14px;">
									<thead class="table-light">
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
											<td class="bg-light"><?php echo $c->crt_subject; ?></td>
											<td><?php echo $c->crt_description; ?></td>
											<td class="text-center"><?php echo $c->crt_weight; ?></td>
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
											<td colspan="3" class="fw-bold text-end align-middle">TOTAL SCORE</td>
											<td><input type="text" id="crt_total" name="scr_total"
													class="form-control border border-dark" readonly=""></td>
										</tr>
										<tr><td class="fw-bold">Result:</td><td id="overall_rating"></td></tr>
										<tr>
											<td colspan="4">
												<div class="mb-3">
													<label for="scr_remarks" class="fw-bold form-label">General Remarks</label>
													<textarea class="form-control form-control-sm" id="scr_remarks"
														name="scr_remarks" placeholder="(Required)"></textarea>
												</div>
											</td>
										</tr>
										<tr>
											<td colspan="4">
												<div class="mb-3">
													<label class="fw-bold form-label" for="scr_file">You may upload your
														commented manuscript here (If any)</label>
													<span class="badge rounded-pill bg-primary me-1">WORD</span><span
														class="badge rounded-pill bg-danger">PDF</span>
													<input type="file" class="form-control" id="scr_file"
														name="scr_file" accept=".doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/pdf">
												</div>
											</td>
										</tr>
										<tr>
											<td colspan="4">
												<div class="mb-3">
													<label class="fw-bold form-label" for="scr_nda">Non-Disclosure
														Agreement</label>
													<span class="badge rounded-pill bg-primary me-1">WORD</span><span
														class="badge rounded-pill bg-danger">PDF</span>
													<input type="file" class="form-control" id="scr_nda" name="scr_nda"
														accept=".doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/pdf">
												</div>
											</td>
										</tr>
										<!-- <tr>
											<td colspan="4">
												<p class="fw-bold">Recommendation</p>
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
										</tr> -->
									</tbody>
								</table>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
				<div class="btn-group" role="group">
					<button type="submit" id="submit_peer_review" class="btn btn-primary">Submit</button>
				</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- /.Start Review -->

<!-- Technicak Desk Editor Consolidation -->
<div class="modal fade" id="consolidationModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Manuscript Reviews Consolidation</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<!-- <form id="submit_consolidation_form" method="POST" enctype="multipart/form-data"> -->
				<form id="submit_consolidation_form">
					<div class="mb-3">
						<input type="hidden" id="cons_man_id" name="cons_man_id">
						<div class="mb-3">
							<label for="cons_file" class="form-label fw-bold">Consolidated reviews/remarks 
								<!-- <span class="badge rounded-pill bg-primary">WORD</span> -->
								<span class="badge rounded-pill bg-danger">PDF</span>
							</label>
							<!-- <input class="form-control" type="file" id="cons_file" name="cons_file" accept=".doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/pdf"> -->
							<input class="form-control" type="file" id="cons_file" name="cons_file" accept="application/pdf">
						</div>
					</div>
					<div class="mb-3">
						<label class="fw-bold form-label" for="cons_remarks">Remarks</label>
						<textarea class="form-control form-control-sm" id="cons_remarks" name="cons_remarks"
							placeholder="(Type N/A if no remarks)"></textarea>
					</div>
					<!-- <div>
						<label for="cons_action" class="fw-bold form-label">Need Revision?</label>
						<div class="d-flex gap-1" id="cons_revise">
							<div class="form-check form-check-inline mt-2">
								<input class="form-check-input" type="checkbox" id="cons_revise_yes" name="cons_revise" value="1" onclick="checkOnlyOne(this)">
								<label class="form-check-label pt-1" for="cons_revise_yes">Yes</label>
							</div>
							<div class="form-check form-check-inline mt-2">
								<input class="form-check-input" type="checkbox" id="cons_revise_no" name="cons_revise" value="2" onclick="checkOnlyOne(this)">
								<label class="form-check-label pt-1" for="cons_revise_no">No</label>
							</div>
						</div>
					</div> -->
			</div>
			<div class="modal-footer">
				<button class="btn btn-outline-secondary me-auto" type="button" data-bs-toggle="modal" data-bs-target="#reviewsModal">Back to Reviews</button>
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary">Submit</button>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- /.Technicak Desk Editor Consolidation -->


<!-- EIC Review Author Revision -->
<div class="modal fade" id="reviewRevisionModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Review Revised Manuscript</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<table class="table">
					<tbody>
						<tr id="desk_revision_consolidations_row"><td class="fw-bold bg-light">Consolidated Review/Remarks</td><td id="desk_revision_consolidations"></td></tr>
						<tr><td class="fw-bold bg-light">Remarks</td><td id="desk_revision_remarks"></td></tr></tbody>
				</table>
				<!-- <form id="submit_consolidation_form" method="POST" enctype="multipart/form-data"> -->
				<form id="review_revised_form">
					<input type="hidden" id="desk_rev_man_id" name="desk_rev_man_id">
					<div class="mb-3">
						<label class="fw-bold form-label" for="desk_rev_remarks">Remarks</label>
						<textarea class="form-control form-control-sm" id="desk_rev_remarks" name="desk_rev_remarks"
							placeholder="(Type N/A if no remarks)"></textarea>
					</div>
					<div>
						<label for="desk_rev_action" class="fw-bold form-label">Need Revision?</label>
						<div class="d-flex gap-1" id="desk_rev_revise">
							<div class="form-check form-check-inline mt-2">
								<input class="form-check-input" type="checkbox" id="desk_rev_revise_yes" name="desk_rev_revise" value="1" onclick="checkOnlyOne(this)">
								<label class="form-check-label pt-1" for="desk_rev_revise_yes">Yes</label>
							</div>
							<div class="form-check form-check-inline mt-2">
								<input class="form-check-input" type="checkbox" id="desk_rev_revise_no" name="desk_rev_revise" value="2" onclick="checkOnlyOne(this)">
								<label class="form-check-label pt-1" for="desk_rev_revise_no">No</label>
							</div>
						</div>
					</div>
			</div>
			<div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary">Submit</button>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- /.EIC Review Author Revision -->

<!-- View Score -->
<div class="modal fade" id="scoreModal" tabindex="-1" role="dialog" aria-labelledby="scoreModal" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Score</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<table class="table table-hover">
					<thead>
						<tr>
							<th>TITLE</th>
							<th colspan="4" id="score_title" class="fw-normal"></th>
						</tr>
						<tr>
							<th>AUTHOR</th>
							<th colspan="4" id="score_author" class="fw-normal"></th>
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
							<td class="text-center"><?php echo $c->crt_weight; ?></td>
							<td id="<?php echo $c->crt_input_name; ?>" class="text-primary text-center"></td>
							<?php if ($c->crt_type == 'text') {?>
							<td id="scr_rem_<?php echo $x;
                            $x++; ?>" class="text-primary"></td>
							<?php $y++;}?>
						</tr>
						<?php endforeach;?>
						<tr>
							<td colspan="2" class="fw-bold">TOTAL SCORE</td>
							<td colspan="3" id="scr_total" class="text-primary text-center"></td>
						</tr>
						<tr>
							<td class="fw-bold">GENERAL REMARKS</td>
							<td colspan="4" id="scr_remarks"></td>
						</tr>
						<tr>
							<td class="fw-bold">REVIEWER</td>
							<td colspan="4" id="score_reviewer"></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!--./. View Score -->

<!-- Review inputs before process (UNUSED)-->
<div class="modal fade" id="processReviewModal" tabindex="-1" role="dialog" aria-labelledby="processReviewModal"
	aria-hidden="true" style="z-index:999999">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Verify Process Manuscript</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<p>Do you want to check information you have entered?, click Cancel.</p>
				<p>Otherwise, click Submit.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
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
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<p>Do you want to check information you have entered?, click Cancel.</p>
				<p>Otherwise, click Submit.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary" id="submit_to_editor">Submit</button>
			</div>
		</div>
	</div>
</div>
<!--/. Review inputs before process -->

<!-- Publish Modal -->
<div class="modal fade" id="publishModal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Publish Manuscript to eJournal</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="pub_to_e_form">
					<input type="hidden" id="pub_man_id" name="pub_man_id">
					<table class="table table-bordered">
						<tbody>
						</tbody>
					</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-primary">Publish to eJournal</button>
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
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				Do you want to submit?
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
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
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				This action cannot be undo. Do you want to remove anyway?
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
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
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="remarks_form">
					<div class="mb-3">
						<textarea class="form-control" id="man_remarks" name="man_remarks"
							placeholder="Type your remarks here..."></textarea>
					</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
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
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
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
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
								<th scope="row">Upload Final Abstract <span class="badge bg-danger">PDF</span></th>
								<td>
									<input type="file" class="form-control-file" id="man_abs" name="man_abs"
										accept="application/pdf" required></td>
							</tr>
							</tr>
							<tr>
								<th scope="row">Upload Final Manuscript <span class="badge bg-danger">PDF</span></th>
								<td>
									<input type="file" class="form-control-file" id="man_file" name="man_file"
										accept="application/pdf" required>
									<input type="hidden" id="man_id" name="man_id"></td>
							</tr>
						</tbody>
					</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-primary">Save changes</button>
				</form>
			</div>
		</div>
	</div>
</div>
<!--/. For Publishable Modal -->

<!-- Edit User Type Modal -->
<div class="modal fade" id="editUserTypeModal" tabindex="-1" role="dialog" >
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit User Type</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="form_edit_user_type">
			<input type="hidden" id="row_id" name="row_id">
          <div class="mb-3">
            <label for="role_name" class="form-label">Type</label>
            <input type="text" class="form-control" id="role_name" name="role_name">
          </div>
          <div class="mb-3">
            <label for="role_access" class="form-label">System Access</label>
            <select id="role_access" name="role_access" class="form-select">
              <option value="" selected>Select System Access</option>
              <option value='1'>eJournal</option>
              <option value='2'>eReview</option>
              <option value='3'>eJournal/eReview</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="role_status" class="form-label">Status</label>
            <select id="role_status" name="role_status" class="form-select">
              <option value="" selected>Select Status</option>
              <option value="1">Enable</option>
              <option value="2">Disable</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Edit Status Type Modal -->
<div class="modal fade" id="editStatusTypeModal" tabindex="-1" role="dialog" >
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Status Type</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="form_edit_status_type">
			<input type="hidden" id="id" name="id">
          <div class="mb-3">
            <label for="status_desc" class="form-label">Description</label>
            <input type="text" class="form-control" id="status_desc" name="status_desc">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Edit Publication Type Modal -->
<div class="modal fade" id="editPublicationTypeModal" tabindex="-1" role="dialog" >
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Publication Type</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="form_edit_publication_type">
			<input type="hidden" id="id" name="id">
          <div class="mb-3">
            <label for="publication_desc" class="form-label">Description</label>
            <input type="text" class="form-control" id="publication_desc" name="publication_desc">
          </div>
          <div class="mb-3">
            <label for="publication_status" class="form-label">Status</label>
            <select id="publication_status" name="publication_status" class="form-select">
              <option value="" selected>Select Status</option>
              <option value="1">Enable</option>
              <option value="2">Disable</option>
            </select>
		  </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Edit Technical Review Criteria Modal -->
<div class="modal fade" id="editTRCModal" tabindex="-1" role="dialog" >
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Tehnical Review Criteria</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="form_edit_tech_rev_crit">
			<input type="hidden" id="crt_id" name="crt_id">
          <div class="mb-3">
            <label for="crt_code" class="form-label">Criteria Code</label>
            <input type="text" class="form-control" id="crt_code" name="crt_code">
          </div>
          <div class="mb-3">
            <label for="crt_desc" class="form-label">Description</label>
            <input type="text" class="form-control" id="crt_desc" name="crt_desc">
          </div>
          <!-- <div class="mb-3">
            <label for="publication_status" class="form-label">Status</label>
            <select id="publication_status" name="publication_status" class="form-select">
              <option value="" selected>Select Status</option>
              <option value="1">Enable</option>
              <option value="2">Disable</option>
            </select>
		  </div> -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Edit Peer Review Criteria Modal -->
<div class="modal fade" id="editPRCModal" tabindex="-1" role="dialog" >
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Peer Review Criteria</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="form_edit_peer_rev_crit">
			<input type="hidden" id="pcrt_id" name="pcrt_id">
          <div class="mb-3">
            <label for="pcrt_code" class="form-label">Criteria Code</label>
            <input type="text" class="form-control" id="pcrt_code" name="pcrt_code">
          </div>
          <div class="mb-3">
            <label for="pcrt_desc" class="form-label">Description</label>
            <input type="text" class="form-control" id="pcrt_desc" name="pcrt_desc">
          </div>
          <div class="mb-3">
            <label for="pcrt_score" class="form-label">Score</label>
            <input type="text" class="form-control" id="pcrt_score" name="pcrt_score">
		  </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </form>
      </div>
    </div>
  </div>
</div>


<!-- Technical Desk Editor Process  -->
<div class="modal fade" id="tedEdCriteriaModal" tabindex="-1" role="dialog" aria-labelledby="tedEdCriteriaModal"
	aria-hidden="true">
	<!-- <div class="modal-dialog modal-lg" role="document" style="max-width:90%"> -->
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"><?php echo $this->session->userdata('_oprs_type'); ?> Review</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="row">
					<!-- <div class="col-6">
						<embed id="manus_review" width="100%" height="700px" type="application/pdf"
							class="border border-dark">
					</div> -->
					<!-- <div class="col-6"> -->
					<div class="col">
						<form id="tech_rev_form">
							<input type="hidden" name="tr_man_id" id="tr_man_id">
							<table class="table table-hover table-bordered">
								<thead>
									<tr>
										<th width="15%">Criteria</th>
										<th>Description</th>
										<th width="20%" class="text-center">Status</th>
									</tr>
								</thead>
								<tbody> 
									<?php $i = 0; foreach ($tech_rev_critera as $row): ?>
									<?php $i++;?>
									<tr>
										<td><?php echo $row->code; ?></td>
										<td class="text-wrap"><?php echo $row->desc; ?></td>
										<td>
											<select class="form-select" name="tr_crt_<?php echo $i;?>" id="tr_crt_<?php echo $i;?>">
												<option value="1">Passed</option>
												<option value="2">Failed</option>
											</select>
										</td>
									</tr>
									<?php endforeach ?>
									<tr><td colspan="2" class="text-end">Overall</td>
										<td>
											<select class="form-select" name="tr_final" id="tr_final">
												<option value="1">Passed</option>
												<option value="2">Failed</option>
											</select>
										</td>
									</tr>
								</tbody>
							</table>
							<div>
								<label for="tr_remarks" class="fw-bold form-label">Remarks</label>
								<textarea class="form-control" id="tr_remarks" name="tr_remarks" placeholder="(Type N/A if no remarks)"></textarea>
							</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
				<div class="btn-group" role="group">
					<button type="submit" class="btn btn-primary" id="submit_tech_rev_crit">Submit Review</button>
				</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- /.Technical Desk Editor Criteria Status  -->

<!-- Editor-in-Chief Process  -->
<div class="modal fade" id="eicProcessModal" tabindex="-1" role="dialog" aria-labelledby="eicProcessModal" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document" style="max-width:90%">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><?php echo $this->session->userdata('_oprs_type'); ?> Review</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
	  	<div class="row">
			<div class="col-6">
				<p class="fw-bold">Manuscript Title:</p>
				<div class="fst-italic mb-3" id="man_title"></div>
				<p clas="fw-bold">Technical Desk Editor Review Results:</p>
				<table class="table table-hover table-bordered" id="eic_table">
					<thead>
						<tr>
							<th width="15%">Criteria</th>
							<th>Description</th>
							<th width="20%" class="text-center">Status</th>
						</tr>
					</thead>
					<tbody> 
						<?php $i = 0; foreach ($tech_rev_critera as $row): ?>
						<?php $i++;?>
						<tr>
							<td><?php echo $row->code; ?></td>
							<td class="text-wrap"><?php echo $row->desc; ?></td>
							<td class="text-center fw-bold" id="tr_crt_<?php echo $i;?>"></td>
						</tr>
						<?php endforeach ?>
						<tr><td colspan="2" class="text-end">Overall</td>
							<td class="text-center fw-bold text-white" id="tr_final"></td>
						</tr>
						<tr><td>Remarks</td>
							<td colspan="2" id="tr_remarks"></td></tr>
					</tbody>
				</table>
			</div>
			<div class="col-6">
				<div class="row">
					<ul class="nav nav-tabs" id="myTab" role="tablist">
						<li class="nav-item" role="presentation">
							<button class="nav-item nav-link active" role="tab" id="submit-eic-review-tab" data-bs-toggle="tab" data-bs-target="#submit-eic-review-tab-pane" type="button" aria-controls="submit-eic-review-tab-pane" aria-selected="true"><span class="fa fa-check"></span> Submit Review</button>
						</li>
						<li class="nav-item" role="presentation">
							<button class="nav-item nav-link" role="tab" id="select-associate-tab" data-bs-toggle="tab" data-bs-target="#select-associate-tab-pane" type="button" role="tab" aria-controls="select-associate-tab-pane" aria-selected="true"><span class="fa fa-plus-square"></span> Endorse to Associate Editor</button>
						</li>
						<li class="nav-item" role="presentation">
							<button class="nav-item nav-link" role="tab" id="eic-suggest-peer-tab" data-bs-toggle="tab" data-bs-target="#eic-suggest-peer-tab-pane" type="button" role="tab" aria-controls="eic-suggest-peer-tab-pane" aria-selected="true"><span class="fa fa-plus-square"></span> Suggest Peer Reviewers</button>
						</li>
					</ul>

					<div class="tab-content p-3" id="myTabContent">
						<div class="tab-pane fade show active" role="tabpanel" id="submit-eic-review-tab-pane" role="tabpanel" aria-labelledby="submit-eic-review-tab" tabindex="0" >
							<div class="mb-3">
								<form id="eic_review_form">
									<label for="man_remarks" class="form-label fw-bold">Remarks</label>
									<textarea class="form-control" id="man_remarks" name="man_remarks" placeholder="(Type N/A if no remarks)"
										maxlength="255"></textarea>
								</form>
								<!-- <small class="text-muted float-right limit"></small> -->
							</div>
							<label for="" class="form-label fw-bold">Action</label>
							<div class="d-flex gap-3">
								<button class="btn btn-outline-danger w-100" onclick="editor_action('reject','editor_chief')"><span class="fa fa-times-circle me-1"></span>Reject</button>
								<button class="btn btn-outline-secondary w-100" onclick="editor_action('revise','editor_chief')"><span class="fa fa-refresh me-1"></span>Revise</button>
								<button class="btn btn-success w-100" onclick="editor_action('accept','editor_chief')"><span class="fa fa-check-circle me-1"></span>Accept</button>
							</div>
						</div>
						
						<div class="tab-pane fade" role="tabpanel" id="select-associate-tab-pane" role="tabpanel" aria-labelledby="select-associate-tab" tabindex="0">
							<form id="endorse_associate_form">
								<div class="mb-3">
									<label for="associate_editor" class="form-label fw-bold">Associate Editor</label>
									<select class="form-select" name="associate_editor" id="associate_editor">
										<option value="">Select Associate Editor</option>
										<?php foreach($associate as $row): ?>
											<option value="<?php echo $row->usr_id;?>"><?php echo $row->usr_full_name . ' (' . $row->usr_username . ')';?></option>
										<?php endforeach ?>
									</select>
								</div>
								<div class="mb-3">
									<label for="man_remarks" class="form-label fw-bold">Remarks</label>
									<textarea class="form-control" id="man_remarks" name="man_remarks" placeholder="(Type N/A if no remarks)"
										maxlength="255"></textarea>
									<!-- <small class="text-muted float-right limit"></small> -->
								</div>
							</form>
								<div>
									<button class="btn btn-primary" type="button" onclick="editor_action('endorse','editor_chief')">Submit</button>
								</div>
						</div>
						
						<div class="tab-pane fade" role="tabpanel" id="eic-suggest-peer-tab-pane" role="tabpanel" aria-labelledby="eic-suggest-peer-tab-pane" tabindex="0">
							<form id="suggest_peer_form">

								<div class="accordion mb-3" id="suggest_peer_accordion">
									<div class="accordion-item">
										<h2 class="accordion-header" id="headingOne">
										<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
											Peer Reviewer 1
										</button>
										</h2>
										<div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
											<div class="accordion-body">
												<div class="row mb-3">
													<div class="col-3">
														<select class="form-select" id="eic_suggested_peer_rev_title1" name="suggested_peer_rev_title[]" placeholder="Title">
															<option value="">Select Title</option>
															<?php foreach ($titles as $t): ?>
															<?php echo '<option value=' . $t->title_name . '>' . $t->title_name . '</option>'; ?>
															<?php endforeach;?>
														</select>
													</div>
													<div class="col autocomplete">
													<input type="text" class="form-control " id="eic_suggested_peer_rev1" name="suggested_peer_rev[]" placeholder="Search by Name or Specialization">
													</div>
												</div>
												<div class="row">
													<div class="col mb-3">
														<input type="text" class="form-control" placeholder="Email" id="eic_suggested_peer_rev_email1" name="suggested_peer_rev_email[]">
													</div>
													<div class="col mb-3">
														<input type="text" class="form-control" placeholder="Contact" id="eic_suggested_peer_rev_num1" name="suggested_peer_rev_num[]">
													</div>
													<input type="hidden" id="eic_suggested_peer_rev_id1" name="suggested_peer_rev_id[]">
												</div>
												<div class="row">
													<div class="col">
														<input type="text" class="form-control" placeholder="Specialization" id="eic_suggested_peer_rev_spec1" name="suggested_peer_rev_spec[]">
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="mb-3">
									<label for="man_remarks" class="form-label fw-bold">Remarks</label>
									<textarea class="form-control" id="man_remarks" name="man_remarks" placeholder="(Type N/A if no remarks)"
											maxlength="255"></textarea>
								</div>	
							</form>
							<button class="btn btn-outline-secondary" onclick="suggest_peer('editor_chief')"><span class="fa fa-plus-square me-1"></span>Add Reviewer</button>
							<button class="btn btn-primary" onclick="editor_action('suggest','editor_chief')">Submit</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	  </div>
	  <div class="modal-footer">
		<button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
		<!-- <button type="submit" class="btn btn-primary">Submit</button> -->
	  </div>
    </div>
  </div>
</div>
<!-- /.Editor-in-Chief Process  -->

<!-- Associate Editor Process  -->
<div class="modal fade" id="assocEdProcessModal" tabindex="-1" role="dialog" aria-labelledby="assocEdProcessModal" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><?php echo $this->session->userdata('_oprs_type'); ?> Review</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
	  	<div class="row">
			<!-- <div class="col-6">
				<p>Technical Desk Editor Review Results:</p>
				<table class="table table-hover table-bordered" id="assoc_table">
					<thead>
						<tr>
							<th width="15%">Criteria</th>
							<th>Description</th>
							<th width="20%" class="text-center">Status</th>
						</tr>
					</thead>
					<tbody> 
						<?php $i = 0; foreach ($tech_rev_critera as $row): ?>
						<?php $i++;?>
						<tr>
							<td><?php echo $row->code; ?></td>
							<td class="text-wrap"><?php echo $row->desc; ?></td>
							<td class="text-center fw-bold" id="tr_crt_<?php echo $i;?>"></td>
						</tr>
						<?php endforeach ?>
						<tr><td colspan="2" class="text-end">Overall</td>
							<td class="text-center fw-bold text-white" id="tr_final"></td>
						</tr>
						<tr>
							<td>Remarks</td>
							<td colspan="2" id="tr_remarks"></td>
						</tr>
					</tbody>
				</table>
			</div> -->
			<div class="col">
				<p class="fw-bold">Manuscript Title:</p>
				<div class="fst-italic mb-3" id="man_title"></div>
				<p class="fw-bold">Editor-in-Chief Remarks:</p>
				<div class="fst-italic mb-3" id="eic_remarks"></div>
				<div class="row">
					<ul class="nav nav-tabs" id="myTab" role="tablist">
						<li class="nav-item" role="presentation">
							<button class="nav-item nav-link active" role="tab" id="submit-assoc-review-tab" data-bs-toggle="tab" data-bs-target="#submit-assoc-review-tab-pane" type="button" aria-controls="submit-assoc-review-tab-pane" aria-selected="true"><span class="fa fa-check"></span> Submit Review</button>
						</li>
						<li class="nav-item" role="presentation">
							<button class="nav-item nav-link" role="tab" id="select-cluster-tab" data-bs-toggle="tab" data-bs-target="#select-cluster-tab-pane" type="button" role="tab" aria-controls="select-cluster-tab-pane" aria-selected="true"><span class="fa fa-plus-square"></span> Endorse to Cluster Editors</button>
						</li>
						<li class="nav-item" role="presentation">
							<button class="nav-item nav-link" role="tab" id="assoc-suggest-peer-tab" data-bs-toggle="tab" data-bs-target="#assoc-suggest-peer-tab-pane" type="button" role="tab" aria-controls="assoc-suggest-peer-tab-pane" aria-selected="true"><span class="fa fa-plus-square"></span> Suggest Peer Reviewers</button>
						</li>
					</ul>

					<div class="tab-content p-3" id="myTabContent">
						<div class="tab-pane fade show active" role="tabpanel" id="submit-assoc-review-tab-pane" role="tabpanel" aria-labelledby="submit-assoc-review-tab" tabindex="0" >
							<div class="mb-3">
								<form id="assoc_review_form">
									<label for="man_remarks" class="form-label fw-bold">Remarks</label>
									<textarea class="form-control" id="man_remarks" name="man_remarks" placeholder="(Type N/A if no remarks)"
										maxlength="255"></textarea>
								</form>
								<!-- <small class="text-muted float-right limit"></small> -->
							</div>
							<label for="" class="form-label fw-bold">Action</label>
							<div class="d-flex gap-3">
								<button class="btn btn-outline-danger w-100" onclick="editor_action('reject','associate')"><span class="fa fa-times-circle me-1"></span>Reject</button>
								<button class="btn btn-outline-secondary w-100" onclick="editor_action('revise','associate')"><span class="fa fa-refresh me-1"></span>Revise</button>
								<button class="btn btn-success w-100" onclick="editor_action('accept','associate')"><span class="fa fa-check-circle me-1"></span>Accept</button>
							</div>
						</div>
						
						<div class="tab-pane fade" role="tabpanel" id="select-cluster-tab-pane" role="tabpanel" aria-labelledby="select-cluster-tab" tabindex="0">
							<form id="endorse_cluster_form">
								<div class="mb-3">
									<label for="cluster_editor" class="form-label fw-bold">Select Cluster Editors</label>
									<div id="cluster_editors" class="mb-1">
										<?php foreach($cluster as $row): ?>
											<div class="form-check">
												<input class="form-check-input" type="checkbox" value="<?php echo $row->usr_id;?>" id="flexCheckDefault<?php echo $row->usr_id;?>" name="cluster_editor[]">
												<label class="form-check-label ms-1 mt-1" for="flexCheckDefault<?php echo $row->usr_id;?>">
													<?php echo $row->usr_full_name . ' (' . $row->usr_username . ')'; ?>
												</label>
											</div>
										<?php endforeach ?>
									</div>
								</div>
								<div class="mb-3">
									<label for="man_remarks" class="form-label fw-bold">Remarks</label>
									<textarea class="form-control" id="man_remarks" name="man_remarks" placeholder="(Type N/A if no remarks)"
										maxlength="255"></textarea>
									<!-- <small class="text-muted float-right limit"></small> -->
								</div>
							</form>
								<div>
									<button class="btn btn-primary" type="button" onclick="editor_action('endorse','associate')">Submit</button>
								</div>
						</div>
						
						<div class="tab-pane fade" role="tabpanel" id="assoc-suggest-peer-tab-pane" role="tabpanel" aria-labelledby="assoc-suggest-peer-tab-pane" tabindex="0">
							<form id="suggest_peer_form">

								<div class="accordion mb-3" id="suggest_peer_accordion">
									<div class="accordion-item">
										<h2 class="accordion-header" id="headingOne">
										<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
											Peer Reviewer 1
										</button>
										</h2>
										<div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
											<div class="accordion-body">
												<div class="row mb-3">
													<div class="col-3">
														<select class="form-select" id="assoc_suggested_peer_rev_title1" name="suggested_peer_rev_title[]" placeholder="Title">
															<option value="">Select Title</option>
															<?php foreach ($titles as $t): ?>
															<?php echo '<option value=' . $t->title_name . '>' . $t->title_name . '</option>'; ?>
															<?php endforeach;?>
														</select>
													</div>
													<div class="col autocomplete">
													<input type="text" class="form-control " id="assoc_suggested_peer_rev1" name="suggested_peer_rev[]" placeholder="Search by Name or Specialization">
													</div>
												</div>
												<div class="row">
													<div class="col mb-3">
														<input type="text" class="form-control" placeholder="Email" id="assoc_suggested_peer_rev_email1" name="suggested_peer_rev_email[]">
													</div>
													<div class="col mb-3">
														<input type="text" class="form-control" placeholder="Contact" id="assoc_suggested_peer_rev_num1" name="suggested_peer_rev_num[]">
													</div>
													<input type="hidden" id="assoc_suggested_peer_rev_id1" name="suggested_peer_rev_id[]">
												</div>
												<div class="row">
													<div class="col">
														<input type="text" class="form-control" placeholder="Specialization" id="assoc_suggested_peer_rev_spec1" name="suggested_peer_rev_spec[]">
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="mb-3">
									<label for="man_remarks" class="form-label fw-bold">Remarks</label>
									<textarea class="form-control" id="man_remarks" name="man_remarks" placeholder="(Type N/A if no remarks)"
											maxlength="255"></textarea>
								</div>	
							</form>
							<button class="btn btn-outline-secondary" onclick="suggest_peer('associate')"><span class="fa fa-plus-square me-1"></span>Add Reviewer</button>
							<button class="btn btn-primary" onclick="editor_action('suggest','associate')">Submit</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	  </div>
	  <div class="modal-footer">
		<button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
		<!-- <button type="submit" class="btn btn-primary">Submit</button> -->
	  </div>
    </div>
  </div>
</div>
<!-- /.Associate Editor Process  -->

<!-- Cluster Editor Process  -->
<div class="modal fade" id="cluEdProcessModal" tabindex="-1" role="dialog" aria-labelledby="clueEdProcessModal" aria-hidden="true">
  <!-- <div class="modal-dialog modal-lg" role="document" style="max-width:90%"> -->
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><?php echo $this->session->userdata('_oprs_type'); ?> Review</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
	  	<div class="row">
			<div class="col">
				<p class="fw-bold">Manuscript Title:</p>
				<div class="fst-italic mb-3" id="man_title"></div>
				<p class="fw-bold">Associate Editor Remarks:</p>
				<div class="fst-italic mb-3" id="assoc_remarks"></div>
				<div class="row">
					<ul class="nav nav-tabs" id="myTab" role="tablist">
						<li class="nav-item" role="presentation">
							<button class="nav-item nav-link active" role="tab" id="submit-cluster-review-tab" data-bs-toggle="tab" data-bs-target="#submit-cluster-review-tab-pane" type="button" aria-controls="submit-cluster-review-tab-pane" aria-selected="true"><span class="fa fa-check"></span> Submit Review</button>
						</li>
						<li class="nav-item" role="presentation">
							<button class="nav-item nav-link" role="tab" id="select-peer-tab" data-bs-toggle="tab" data-bs-target="#select-peer-tab-pane" type="button" role="tab" aria-controls="select-peer-tab-pane" aria-selected="true"><span class="fa fa-plus-square"></span> Suggest Peer Reviewers</button>
						</li>
					</ul>

					<div class="tab-content p-3" id="myTabContent">
						<div class="tab-pane fade show active" role="tabpanel" id="submit-cluster-review-tab-pane" role="tabpanel" aria-labelledby="submit-cluster-review-tab" tabindex="0" >
							<div class="mb-3">
								<form id="cluster_review_form">
									<label for="man_remarks" class="form-label fw-bold">Remarks</label>
									<textarea class="form-control" id="man_remarks" name="man_remarks" placeholder="(Type N/A if no remarks)"
										maxlength="255"></textarea>
								</form>
								<!-- <small class="text-muted float-right limit"></small> -->
							</div>
							<label for="" class="form-label fw-bold">Action</label>
							<div class="d-flex gap-3">
								<button class="btn btn-outline-danger w-100" onclick="editor_action('reject','cluster')"><span class="fa fa-times-circle me-1"></span>Reject</button>
								<button class="btn btn-outline-secondary w-100" onclick="editor_action('revise','cluster')"><span class="fa fa-refresh me-1"></span>Revise</button>
								<button class="btn btn-success w-100" onclick="editor_action('accept','cluster')"><span class="fa fa-check-circle me-1"></span>Accept</button>
							</div>
						</div>
						
						<div class="tab-pane fade" role="tabpanel" id="select-peer-tab-pane" role="tabpanel" aria-labelledby="select-peer-tab" tabindex="0">
							<form id="suggest_peer_form">

								<div class="accordion mb-3" id="suggest_peer_accordion">
									<div class="accordion-item">
										<h2 class="accordion-header" id="headingOne">
										<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
											Peer Reviewer 1
										</button>
										</h2>
										<div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
											<div class="accordion-body">
												<div class="row mb-3">
													<div class="col-3">
														<select class="form-select" id="suggested_peer_rev_title1" name="suggested_peer_rev_title[]" placeholder="Title">
															<option value="">Select Title</option>
															<?php foreach ($titles as $t): ?>
															<?php echo '<option value=' . $t->title_name . '>' . $t->title_name . '</option>'; ?>
															<?php endforeach;?>
														</select>
													</div>
													<div class="col autocomplete">
													<input type="text" class="form-control " id="suggested_peer_rev1" name="suggested_peer_rev[]" placeholder="Search by Name or Specialization">
													</div>
												</div>
												<div class="row">
													<div class="col mb-3">
														<input type="text" class="form-control" placeholder="Email" id="suggested_peer_rev_email1" name="suggested_peer_rev_email[]">
													</div>
													<div class="col mb-3">
														<input type="text" class="form-control" placeholder="Contact" id="suggested_peer_rev_num1" name="suggested_peer_rev_num[]">
													</div>
													<input type="hidden" id="suggested_peer_rev_id1" name="suggested_peer_rev_id[]">
												</div>
												<div class="row">
													<div class="col">
														<input type="text" class="form-control" placeholder="Specialization" id="suggested_peer_rev_spec1" name="suggested_peer_rev_spec[]">
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="mb-3">
									<label for="man_remarks" class="form-label fw-bold">Remarks</label>
									<textarea class="form-control" id="man_remarks" name="man_remarks" placeholder="(Type N/A if no remarks)"
											maxlength="255"></textarea>
								</div>	
							</form>
							<button class="btn btn-outline-secondary" onclick="suggest_peer('#clueEdProcessModal')"><span class="fa fa-plus-square me-1"></span>Add Reviewer</button>
							<button class="btn btn-primary" onclick="editor_action('endorse','cluster')">Submit</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	  </div>
	  <div class="modal-footer">
		<button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
		<!-- <button type="submit" class="btn btn-primary">Submit</button> -->
	  </div>
    </div>
  </div>
</div>
<!-- /.Cluster Editor Process  -->

<!-- Technical Desk Editor on Revision - Copy Editor -->
<div class="modal fade" id="checkRevisionModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Manuscript Revision Matrix</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<!-- <form id="submit_consolidation_form" method="POST" enctype="multipart/form-data"> -->
				<form id="submit_revision_endorsement_form">
					<div class="mb-3">
						<input type="hidden" id="cons_man_id" name="cons_man_id">
						<div class="mb-3">
							<label for="cons_file" class="form-label fw-bold">Uploaded Revision Matrix
							</label>
							<p id="uploaded_revision_matrix"></p>
						</div>
					</div>
					<div class="mb-3">
						<label class="fw-bold form-label" for="cons_remarks">Remarks</label>
						<textarea class="form-control form-control-sm" id="cons_remarks" name="cons_remarks"
							placeholder="(Type N/A if no remarks)"></textarea>
					</div>
					<div>
						<label for="cons_action" class="fw-bold form-label">Need Revision?</label>
						<div class="d-flex gap-1" id="cons_check_revise">
							<div class="form-check form-check-inline mt-2">
								<input class="form-check-input" type="checkbox" id="cons_revise_yes" name="cons_check_revise" value="1" onclick="checkOnlyOne(this)">
								<label class="form-check-label pt-1" for="cons_revise_yes">Yes</label>
							</div>
							<div class="form-check form-check-inline mt-2">
								<input class="form-check-input" type="checkbox" id="cons_revise_no" name="cons_check_revise" value="2" onclick="checkOnlyOne(this)">
								<label class="form-check-label pt-1" for="cons_revise_no">No</label>
							</div>
						</div>
					</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary">Submit</button>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- /.Technical Desk Editor on Revision - Copy Editor -->

<!-- Copy Editor Process -->
<div class="modal fade" id="copEdProcessModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Proofread/Edit Manuscript</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<!-- <form id="submit_consolidation_form" method="POST" enctype="multipart/form-data"> -->
				<form id="submit_coped_process_form">
					<input type="hidden" id="coped_man_id" name="coped_man_id">
					<div class="mb-3">
						<label for="coped_file" class="form-label fw-bold">Upload reviews/edited 
							<!-- <span class="badge rounded-pill bg-primary">WORD</span> -->
							<span class="badge rounded-pill bg-danger">PDF</span>
						</label>
						<!-- <input class="form-control" type="file" id="cons_file" name="cons_file" accept=".doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/pdf"> -->
						<input class="form-control" type="file" id="coped_file" name="coped_file" accept="application/pdf">
					</div>
					<div class="mb-3">
						<label class="fw-bold form-label" for="coped_remarks">Remarks</label>
						<textarea class="form-control form-control-sm" id="coped_remarks" name="coped_remarks"
							placeholder="(Type N/A if no remarks)"></textarea>
					</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary">Submit</button>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- /.Copy Editor Process -->

<!-- EIC Final Review -->
<div class="modal fade" id="finalReviewModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Final Review and decision to publish</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<!-- <form id="submit_consolidation_form" method="POST" enctype="multipart/form-data"> -->
				<form id="submit_final_review_form">
					<input type="hidden" id="final_man_id" name="final_man_id">
					<div class="mb-3">
						<label class="fw-bold form-label" for="final_remarks">Remarks</label>
						<textarea class="form-control form-control-sm" id="final_remarks" name="final_remarks"
							placeholder="(Type N/A if no remarks)"></textarea>
					</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary">Submit</button>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- /.EIC Final Review -->

<!-- Layout Arist Formatting -->
<div class="modal fade" id="layoutProcessModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Layout and Formatting</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="submit_layout_form">
					<div class="mb-3">
						<input type="hidden" id="lay_man_id" name="lay_man_id">
						<div class="mb-3">
							<label for="lay_file" class="form-label fw-bold">Upload Layout and Formatting
								<!-- <span class="badge rounded-pill bg-primary">WORD</span> -->
								<span class="badge rounded-pill bg-danger">PDF</span>
							</label><input class="form-control" type="file" id="lay_file" name="lay_file" accept="application/pdf">
						</div>
					</div>
					<div class="mb-3">
						<label class="fw-bold form-label" for="lay_remarks">Remarks</label>
						<textarea class="form-control form-control-sm" id="lay_remarks" name="lay_remarks"
							placeholder="(Type N/A if no remarks)"></textarea>
					</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary">Submit</button>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- /.Layout Arist Formatting -->
         
<!-- Clickables in Statistics Modal -->
<div class="modal fade" id="statsModal" tabindex="-1" role="dialog" aria-labelledby="reviewerModal"
	aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Manuscripts</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="table-responsive">
					<table class="table table-hover" id="stats-manuscript" width="100%" cellspacing="0">
						<thead>
							<tr>
								<th>#</th>
								<th>Title</th>
								<th>Author/Co-authors</th>
								<th>Date Submitted</th>
								<th>Status</th>
								<th>Tracking No.</th>
								<th>Remarks</th>
								<th>Action</th>
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
<!-- Clickables in Statistics Modal -->

<script type="text/javascript" >
var base_url = '<?php echo base_url(); ?>';
var prv_add = <?php echo (!empty($this->session->userdata('_prv_add'))) ? $this->session->userdata('_prv_add') : '0'; ?>;
var prv_edt = <?php echo (!empty($this->session->userdata('_prv_edt'))) ? $this->session->userdata('_prv_edt') : '0'; ?>;
var prv_del = <?php echo (!empty($this->session->userdata('_prv_del'))) ? $this->session->userdata('_prv_del') : '0'; ?>;
var prv_view = <?php echo (!empty($this->session->userdata('_prv_view'))) ? $this->session->userdata('_prv_view') : '0'; ?>;
var prv_exp = <?php echo (!empty($this->session->userdata('_prv_exp'))) ? $this->session->userdata('_prv_exp') : '0'; ?>;
</script>

<script type="text/javascript" src="<?php echo base_url("assets/js/sweetalert2@11.js");?>"></script>
<script src="<?php echo base_url("assets/oprs/js/chart.js");?>"></script>
<script src="<?php echo base_url("assets/oprs/js/jquery.min.js");?>"></script>


<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>

<!-- <script src="<?php echo base_url("assets/oprs/js/bootstrap.bundle.min.js");?>"></script>
<script src="<?php echo base_url("assets/oprs/js/datatables.js");?>"></script> -->
<!-- Main jquery-->
<script src="<?php echo base_url("assets/oprs/js/oprs.js");?>"></script>
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
<script type="text/javascript" src="<?php echo base_url("assets/oprs/js/jquery.loading.admin.js"); ?>"></script>

<!-- Datatable buttons -->
<script type="text/javascript" src="<?php echo base_url("assets/oprs/js/dataTables.buttons.min.js"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/oprs/js/buttons.flash.min.js"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/oprs/js/jszip.min.js"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/oprs/js/pdfmake.min.js"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/oprs/js/vfs_fonts.js"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/oprs/js/buttons.html5.min.js"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/oprs/js/buttons.print.min.js"); ?>"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>


<!-- Core plugin JavaScript-->
<!-- <script src="<?php echo base_url(); ?>assets/oprs/sbadmin/vendor/jquery-easing/jquery.easing.min.js"></script> -->
<!-- Page level plugin JavaScript-->
<!-- <script src="<?php echo base_url(); ?>assets/oprs/sbadmin/vendor/datatables/jquery.dataTables.js"></script> -->
<!-- <script src="<?php echo base_url(); ?>assets/oprs/sbadmin/vendor/datatables/dataTables.bootstrap4.js"></script> -->
<!-- Custom scripts for all pages-->
<!-- <script src="<?php echo base_url(); ?>assets/oprs/sbadmin/js/sb-admin.min.js"></script> -->
<!-- <script src="<?php echo base_url(); ?>assets/oprs/sbadmin/vendor/jquery/jquery.min.js"></script> -->
 
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