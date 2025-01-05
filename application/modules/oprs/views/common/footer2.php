
<footer class="py-4 bg-light mt-auto">
      <div class="container-fluid px-4">
          <div class="d-flex align-items-center justify-content-between small">
              <div class="text-muted">Copyright &copy; 2018 NRCP Online Research Journal (eJournal), Online Peer Review System (eReview) All Rights Reserved</div>
			  <div class="text-muted">
				Currently v2.1.85
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
            <label for="usr_username" class="form-label">Email</label>
            <input type="email" class="form-control" id="usr_username" name="usr_username" placeholder="Your email address">
          </div>
          <!-- <div class="mb-3">
            <label for="usr_username">Username</label>
            <input type="text" class="form-control" id="usr_username" name="usr_username" placeholder="Your username">
          </div> -->
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

<!-- View Score -->
<div class="modal fade" id="scoreModal" tabindex="-1" role="dialog" aria-labelledby="scoreModal" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Score</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
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
              <th  colspan="4"  id="score_author"></th>
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
            <?php $y = 1;
  $x = 1;foreach ($criteria as $key => $c): ?>
            <tr>
              <td><?php echo $c->crt_subject; ?></td>
              <td><?php echo $c->crt_description; ?></td>
              <td><?php echo $c->crt_weight; ?></td>
              <td id="<?php echo $c->crt_input_name; ?>" class="text-primary"></td>
              <?php if ($c->crt_type == 'text') {
	?>
              <td id="scr_rem_<?php echo $x;
	$x++; ?>" class="text-primary"></td>
              <?php $y++;}?>
            </tr>
            <?php endforeach;?>
            <tr>
              <td colspan="3" class="fw-bold">TOTAL SCORE</td>
              <td colspan="2" id="scr_total" class="text-primary"></td>
              <td></td>
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

<!-- Reviewers -->
<div class="modal fade" id="reviewerModal" tabindex="-1" role="dialog" aria-labelledby="processModal" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Reviewers</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="fw-bold"></p>
        <div class="table-responsive">
          <table class="table table-hover" id="table-reviewers" width="100%" cellspacing="0" style="font-size:14px">
            <thead>
              <tr>
                <th></th>
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
          <span class="fa fa-user-secret"></span> - Reviewers hidden to Authors
          <span class="fas fa-user-alt-slash ml-2"></span> - Authors hidden to Reviewers
        </div>
        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Process Manuscript-->
<div class="modal fade" id="processModal" tabindex="-1" role="dialog" aria-labelledby="processModal" aria-hidden="true" style="z-index:9999">
  <div class="modal-dialog modal-lg" role="document" style="max-width:90%">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Process Manuscript</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="process_manuscript_form" autocomplete="off">
          <div class="form-row">
            <div class="col-6">
              <div class="form-group" id="form_journal">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="new-tab" data-toggle="tab" href="#new" role="tab" aria-controls="new" aria-selected="true"><span class="fa fa-book"></span> New Journal</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="article-tab" data-toggle="tab" href="#article" role="tab" aria-controls="article" aria-selected="false"><span class="fa fa-plus-square"></span> Add Article</a>
                  </li>
                </ul>
                <div class="tab-content p-3" id="myTabContent">
                  <div class="tab-pane fade show active" id="new" role="tabpanel" aria-labelledby="new-tab">
                    <div class="form-row">
                      <div class="col">
                        <label class="fw-bold" for="jor_volume">Volume No.</label>
                        <select class="form-select text-uppercase" id="jor_volume" name="jor_volume" placeholder="ex. X" style="background-color:white">
                          <?php foreach ($u_journal as $j): ?>
                          <?php echo '<option value=' . $j->jor_volume . '>' . $j->jor_volume . '</option>'; ?>
                          <?php endforeach;?>
                        </select>
                      </div>
                      <div class="col">
                        <label class="fw-bold" for="jor_issue">Issue No.</label>
                        <select class="form-select" id="jor_issue" name="jor_issue">
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
                        <label class="fw-bold" for="jor_year">Year</label>
                        <input type="number" class="form-control" id="jor_year" name="jor_year" max="9999" min="1000" >
                      </div>
                    </div>
                  </div>
                  <div class="tab-pane fade" id="article" role="tabpanel" aria-labelledby="article-tab">
                    <div class="form-row">
                      <div class="col">
                        <div class="col">
                          <label for="jor_issue">Year</label>
                          <select class="form-select" id="art_year" name="art_year">
                            <option value="">Select year</option>
                            <?php foreach ($u_year as $j): ?>
                            <?php echo '<option value=' . $j->jor_year . '>' . $j->jor_year . '</option>'; ?>
                            <?php endforeach;?>
                          </select>
                        </div>
                      </div>
                      <div class="col">
                        <label for="art_issue">Volume, Issue</label>
                        <select class="form-select" id="art_issue" name="art_issue">
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
                  <a class="nav-item nav-link active" data-toggle="tab" href="#nav-rev" role="tab" aria-controls="nav-rev" aria-selected="true"  id="btn_add_rev"><span class="fa fa-plus-square"></span> Add Reviewer</a>
                  <a class="nav-item nav-link disabled" data-toggle="tab" href="#nav-rev" role="tab" aria-controls="nav-rev" aria-selected="true"  id="btn_add_rev"><small>All reviewer emails will be Cc to <span class="text-info">exec_dir@gmail.com</span></small> </a>
                </div>
                </nav>
                <div class="tab-content p-3" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-rev" role="tabpanel">
                  <div class="form-group">
                    <div id="rev_acc">
                      <div class="card">
                        <div class="card-header p-0" id="heading1"  data-toggle="collapse" data-target="#collapse1">
                          <h5 class="mb-0">
                          <button class="btn btn-link" type="button">
                          <span class="fa fa-address-card"></span> Reviewer 1 : <span id="rev_header1"></span>
                          </button>
                          </h5>
                        </div>
                        <div id="collapse1" class="collapse show" data-parent="#rev_acc">
                          <div class="card-body">
                            <div class="form-row mb-2">
                              <div class="col-3">
                                <select class="form-select" id="trk_title1" name="trk_title[]" placeholder="Title">
                                  <?php foreach ($titles as $t): ?>
                                  <?php echo '<option value=' . $t->title_name . '>' . $t->title_name . '</option>'; ?>
                                  <?php endforeach;?>
                                </select>
                              </div>
                              <div class="col autocomplete">
                                <input type="text" class="form-control " id="trk_rev1" name="trk_rev[]" placeholder="Search by Name/Specialization/Non-member/Non-account">
                              </div>
                            </div>
                            <div class="form-row mb-2">
                              <div class="col">
                                <input type="text" class="form-control" placeholder="Email" id="trk_rev_email1" name="trk_rev_email[]">
                              </div>
                              <div class="col">
                                <input type="text" class="form-control" placeholder="Contact" id="trk_rev_num1" name="trk_rev_num[]">
                              </div>
                              <input type="hidden" id="trk_rev_id1" name="trk_rev_id[]">
                            </div>
                            <div class="form-row">
                              <div class="col">
                                <input type="text" class="form-control" placeholder="Specialization" id="trk_rev_spec1" name="trk_rev_spec[]">
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
                  <a class="nav-item nav-link active" id="nav-timeframe-tab" data-toggle="tab" href="#nav-timeframe" role="tab" aria-controls="nav-timeframe" aria-selected="true"><span class="fas fa-stopwatch"></span> Timeframes</a>  </div>
                </nav>
                <div class="tab-content p-3" id="nav-tabContent">
                  <div class="tab-pane fade show active" id="nav-timeframe" role="tabpanel" aria-labelledby="nav-timeframe-tab">
                    <p class="fw-bold">Accept Review</p>
                    <div class="input-group mb-3">
                      <input type="number" placeholder="0" id="trk_request_timer" name="trk_request_timer" style="width:70px !important;" min="1">
                      <div class="input-group-append">
                        <span class="input-group-text">Days to accept/decline the review request.</span>
                      </div>
                    </div>
                    <p class="fw-bold">Review Request</p>
                    <div class="input-group mb-3">
                      <input type="number" placeholder="0" id="trk_timeframe" name="trk_timeframe" style="width:70px !important;" min="1">
                      <div class="input-group-append">
                        <span class="input-group-text">Days to finish the review task</span>
                      </div>
                    </div>
                  </div>
                </div>
                <nav>
                  <div class="nav nav-tabs" id="nav-tab" role="tablist">
                  <a class="nav-item nav-link active" id="nav-timeframe-tab" data-toggle="tab" href="#nav-timeframe" role="tab" aria-controls="nav-timeframe" aria-selected="true"><span class="fas fa-check-square"></span> Optionals</a>  </div>
                </nav>
                <div class="tab-content p-3" id="nav-tabContent">
                  <div class="tab-pane fade show active" id="nav-timeframe" role="tabpanel" aria-labelledby="nav-timeframe-tab">
                    <div class="form-group text-left">
                      <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" value="1" id="rev_hide_auth" name="rev_hide_auth">
                        <label class="custom-control-label pt-1" for="rev_hide_auth"> Hide Authors to Reviewers <small>(Names, affiliations and emails are hidden)</small></label>
                      </div>
                    </div>
                    <div class="form-group text-left">
                      <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" value="1" id="rev_hide_rev" name="rev_hide_rev">
                        <label class="custom-control-label pt-1" for="rev_hide_rev"> Hide Reviewers to Authors <small>(Names, affiliations and emails are hidden)</small></label>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="fw-bold" for="man_remarks">Remarks</label>
                      <textarea class="form-control" id="trk_remarks" name="trk_remarks" placeholder="Type your remarks here" onkeydown="countChar(this)"></textarea>
                      <small class="text-muted float-right limit"></small>
                    </div>
                  </div>
                </div>
                </div>
            <div class="col-6">
              <div class="accordion" id="rev_acc_mail">
                <h6 class="fw-bold">Request for Manuscript Review Email</h6>
                <div class="card">
                  <div class="card-header p-0" id="heading1" data-toggle="collapse" data-target="#collapse_mail1">
                    <h5 class="mb-0">
                    <button class="btn btn-link" type="button" >
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
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Manuscript Details</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<table class="table table-borderless">
					<tbody>
					</tbody>
				</table>
				<form id="manuscript_form">
					<div class="mb-3">
						<label class="fw-bold" for="man_title">Title</label>
						<textarea class="form-control" id="man_title" name="man_title" placeholder=""></textarea>
					</div>
					<div class="mb-3">
						<label class="fw-bold mr-1" for="man_title">Member?</label>
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
					<div class="mb-3 autocomplete" style="width:100% !important">
						<label class="fw-bold principal" for="man_author">Principal Author</label>
						<input type="text" class="form-control mt-2" id="man_author" name="man_author"
							placeholder="Search/Type by Name/Specialization/Non-member/Non-account">
					</div>
					<div class="mb-3">
						<input type="text" class="form-control" placeholder="Affiliation" id="man_affiliation"
							name="man_affiliation">
					</div>
					<div class="mb-3">
						<input type="email" class="form-control" placeholder="Email" id="man_email" name="man_email">
					</div>
					<input type="hidden" class="form-control" id="man_usr_id" name="man_usr_id">
					<span id="coauthors"></span>
					<div class="mb-3" id="man_abs_div">
						<label class="fw-bold" for="man_abs">Upload Abstract</label>
						<span class="badge badge-danger" id="badge_abs">PDF only</span>
						<input type="file" class="form-control" id="man_abs" name="man_abs" accept="application/pdf">
					</div>
					<div class="mb-3" id="man_file_div">
						<label class="fw-bold" for="man_file">Upload Full Manuscript</label>
						<span class="badge badge-danger" id="badge_pdf">PDF only</span>
						<input type="file" class="form-control" id="man_file" name="man_file" accept="application/pdf">
					</div>
					<div class="mb-3" id="man_word_div">
						<label class="fw-bold" for="man_word">Upload Full Manuscript</label>
						<span class="badge badge-primary" id="badge_word">WORD only</span>
						<input type="file" class="form-control" id="man_word" name="man_word"
							accept=".doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
					</div>
					<div class="mb-3">
						<label class="fw-bold" for="man_pages">Number of pages</label>
						<input type="number" class="form-control w-25" placeholder="0" id="man_pages" name="man_pages"
							min="1">
					</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-outline-secondary mr-auto" type="button" id="btn_add_coa"><i
						class="fa fa-plus"></i> Add Co-author</button>
				<button class="btn btn-outline-secondary btn_cancel" type="button" data-bs-dismiss="modal">Cancel</button>
				<button class="btn btn-outline-secondary btn_close" type="button" data-bs-dismiss="modal">Close</button>
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
            <span class="badge badge-warning" id="badge_pdf">PDF only</span>
            <input type="file" class="form-control" id="man_file" name="man_file" accept="application/pdf">
          </div> -->
					<div class="mb-3" id="man_word_div">
						<label class="fw-bold" for="man_word">Upload Final Manuscript</label>
						<span class="badge badge-primary" id="badge_word">WORD</span>
						<input type="file" class="form-control" id="man_word" name="man_word"
							accept=".doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
					</div>
					<div class="mb-3" id="man_abs_div">
						<label for="man_abs">Upload Abstract</label>
						<span class="badge badge-danger" id="badge_pdf">PDF</span>
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

<!-- Process Manuscript-->
<div class="modal fade" id="processModal" tabindex="-1" data-keyboard="false" data-backdrop="static" role="dialog"
	aria-labelledby="processModal" aria-hidden="true" style="z-index:9999">
	<div class="modal-dialog modal-lg" role="document" style="max-width:90%">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Process Manuscript</h5>
				<button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="process_manuscript_form" autocomplete="off">
					<div class="form-row">
						<div class="col-6">
							<div class="mb-3" id="form_journal">
								<ul class="nav nav-tabs" id="myTab" role="tablist">
									<li class="nav-item">
										<a class="nav-link active" id="new-tab" data-bs-toggle="tab" href="#new" role="tab"
											aria-controls="new" aria-selected="true"><span class="fa fa-book"></span>
											Manuscript</a>
									</li>
									<!-- <li class="nav-item">
                                        <a class="nav-link" id="article-tab" data-bs-toggle="tab" href="#article"
                                            role="tab" aria-controls="article" aria-selected="false"><span
                                                class="fa fa-plus-square"></span> Select Existing Journal/Issue</a>
                                    </li> -->
								</ul>
								<div class="tab-content p-3" id="myTabContent">
									<div class="tab-pane fade show active" id="new" role="tabpanel"
										aria-labelledby="new-tab">
										<div class="form-row">
											<div class="col">
												<label class="fw-bold" for="jor_volume">Volume No.</label>
												<select class="form-select text-uppercase" id="jor_volume"
													name="jor_volume" placeholder="ex. X"
													style="background-color:white">
													<?php foreach ($u_journal as $j): ?>
													<?php echo '<option value=' . $j->jor_volume . '>' . $j->jor_volume . '</option>'; ?>
													<?php endforeach;?>
												</select>
											</div>
											<div class="col">
												<label class="fw-bold" for="jor_issue">Issue No.</label>
												<select class="form-select" id="jor_issue" name="jor_issue">
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
												<label class="fw-bold" for="jor_year">Year</label>
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
													<select class="form-select" id="art_year" name="art_year">
														<option value="">Select year</option>
														<?php foreach ($u_year as $j): ?>
														<?php echo '<option value=' . $j->jor_year . '>' . $j->jor_year . '</option>'; ?>
														<?php endforeach;?>
													</select>
												</div>
											</div>
											<div class="col">
												<label for="art_issue">Volume, Issue</label>
												<select class="form-select" id="art_issue" name="art_issue">
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
									<a class="nav-item nav-link active" data-bs-toggle="tab" href="#nav-rev" role="tab"
										aria-controls="nav-rev" aria-selected="true" id="btn_add_rev"><button
											class="btn btn-primary btn-sm"><span class="fa fa-plus-square"></span> Add
											Reviewer</button></a>
									<a class="nav-item nav-link disabled" data-bs-toggle="tab" href="#nav-rev" role="tab"
										aria-controls="nav-rev" aria-selected="true" id="btn_add_rev">
										<!-- <small>All reviewer emails will be Cc to <span class="text-info">oed@nrcp.dost.gov.ph</span></small>  -->
									</a>
								</div>
							</nav>
							<div class="tab-content p-3" id="nav-tabContent">
								<div class="tab-pane fade show active" id="nav-rev" role="tabpanel">
									<div class="mb-3">
										<div id="rev_acc">
											<div class="card">
												<div class="card-header p-0" id="heading1" data-bs-toggle="collapse"
													data-bs-target="#collapse1">
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
																<select class="form-select" id="trk_title1"
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
									<a class="nav-item nav-link active" id="nav-timeframe-tab" data-bs-toggle="tab"
										href="#nav-timeframe" role="tab" aria-controls="nav-timeframe"
										aria-selected="true"><span class="fas fa-stopwatch"></span> Time frames</a>
								</div>
							</nav>
							<div class="tab-content p-3" id="nav-tabContent">
								<div class="tab-pane fade show active" id="nav-timeframe" role="tabpanel"
									aria-labelledby="nav-timeframe-tab">
									<p class="fw-bold">Accept Review
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
									<p class="fw-bold">Review Request
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
									<a class="nav-item nav-link active" id="nav-timeframe-tab" data-bs-toggle="tab"
										href="#nav-timeframe" role="tab" aria-controls="nav-timeframe"
										aria-selected="true"><span class="fas fa-check-square"></span> Optional</a>
								</div>
							</nav>
							<div class="tab-content p-3" id="nav-tabContent">
								<div class="tab-pane fade show active" id="nav-timeframe" role="tabpanel"
									aria-labelledby="nav-timeframe-tab">
									<div class="mb-3 text-left">
										<div class="custom-control custom-checkbox">
											<input type="checkbox" class="custom-control-input" value="1"
												id="rev_hide_auth" name="rev_hide_auth">
											<label class="custom-control-label pt-1" for="rev_hide_auth"> Hide Authors
												to Reviewers <small>(Names, affiliations and emails are
													hidden)</small></label>
										</div>
									</div>
									<div class="mb-3 text-left">
										<div class="custom-control custom-checkbox">
											<input type="checkbox" class="custom-control-input" value="1"
												id="rev_hide_rev" name="rev_hide_rev">
											<label class="custom-control-label pt-1" for="rev_hide_rev"> Hide Reviewers
												to Authors <small>(Names, affiliations and emails are
													hidden)</small></label>
										</div>
									</div>
									<!-- <div class="mb-3 text-left">
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" value="1" id="rev_cc" name="rev_cc">
                          <label class="custom-control-label pt-1" for="rev_cc"> Additional CC</label>
                        </div>
                        <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
                      </div> -->
									<div class="mb-3">
										<label class="fw-bold" for="man_remarks">Remarks</label>
										<textarea class="form-control" id="trk_remarks" name="trk_remarks"
											placeholder="Type your remarks here" onkeydown="countChar(this)"></textarea>
										<small class="text-muted float-right limit"></small>
									</div>
								</div>
							</div>
						</div>
						<div class="col-6">
							<div class="accordion" id="rev_acc_mail">
								<h6 class="fw-bold">Request for Manuscript Review Email</h6>
								<div class="alert alert-warning" role="alert">
									<span class="fas fa-exclamation-triangle"></span> Do not change or remove words with
									square brackets. [EXAMPLE]
								</div>
								<div class="card">
									<div class="card-header p-0" id="heading1" data-bs-toggle="collapse"
										data-bs-target="#collapse_mail1">
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
				<button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
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
				<button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="edit_manuscript_form" autocomplete="off">
					<div class="form-row">
						<div class="col-6">
							<div class="mb-3">
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
												<label class="fw-bold" for="jor_volume">Volume No.</label>
												<select class="form-select text-uppercase" id="jor_volume"
													name="jor_volume" placeholder="ex. X"
													style="background-color:white">
													<?php foreach ($u_journal as $j): ?>
													<?php echo '<option value=' . $j->jor_volume . '>' . $j->jor_volume . '</option>'; ?>
													<?php endforeach;?>
												</select>
											</div>
											<div class="col">
												<label class="fw-bold" for="jor_issue">Issue No.</label>
												<select class="form-select" id="jor_issue" name="jor_issue">
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
												<label class="fw-bold" for="jor_year">Year</label>
												<input type="number" class="form-control" id="jor_year" name="jor_year"
													max="9999" min="1000">
											</div>
										</div>
									</div>
								</div>
							</div>
							<nav>
								<div class="nav nav-tabs" id="nav-editor-tab" role="tablist">
									<!-- <a class="nav-item nav-link active" data-bs-toggle="tab" href="#nav-editor" role="tab"
                                        aria-controls="nav-editor" aria-selected="true" id="btn_add_editor"><button
                                            class="btn btn-primary btn-sm"><span class="fa fa-plus-square"></span> Add
                                            Editor-in-chief</button></a>
                                    <a class="nav-item nav-link disabled" data-bs-toggle="tab" href="#nav-editor" role="tab"
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
									<div class="mb-3">
										<div id="editor_acc">
											<div class="card">
												<div class="card-header p-0" id="heading1" data-bs-toggle="collapse"
													data-bs-target="#collapse1">
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
																<select class="form-select" id="editor_title1"
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
									<a class="nav-item nav-link active" id="nav-timeframe-tab" data-bs-toggle="tab"
										href="#nav-timeframe" role="tab" aria-controls="nav-timeframe"
										aria-selected="true"><span class="fas fa-stopwatch"></span> Time frames
										<small>(optional)</small></a>
								</div>
							</nav>
							<div class="tab-content p-3" id="nav-tabContent">
								<div class="tab-pane fade show active" id="nav-timeframe" role="tabpanel"
									aria-labelledby="nav-timeframe-tab">

									<p class="fw-bold">Review Request
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
							<div class="mb-3">
								<label class="fw-bold" for="man_remarks">Remarks</label>
								<textarea class="form-control" id="editor_remarks" name="editor_remarks"
									placeholder="Type your remarks here" onkeydown="countChar(this)"></textarea>
								<small class="text-muted float-right limit"></small>
							</div>
						</div>
						<div class="col-6">
							<div class="accordion" id="editor_acc_mail">
								<h6 class="fw-bold">Request for Manuscript Review Email</h6>
								<div class="alert alert-warning" role="alert">
									<span class="fas fa-exclamation-triangle"></span> Do not change or remove words with
									square brackets. [EXAMPLE]
								</div>
								<div class="card">
									<div class="card-header p-0" id="heading1" data-bs-toggle="collapse"
										data-bs-target="#collapse_editor_mail1">
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
				<button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
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
<div class="modal fade" id="editorialReviewModal" tabindex="-1" role="dialog" aria-labelledby="processModal"
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
<div class="modal fade" id="reviewerModal" tabindex="-1" role="dialog" aria-labelledby="processModal"
	aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Reviewers</h5>
				<button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
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
				<div class="mr-auto">
					( <span class="fa fa-user-secret"></span> ) <span class="text-danger">Reviewers hidden to
						Authors</span>
					( <span class="fas fa-user-alt-slash ml-2"></span> ) <span class="text-danger">Authors hidden to
						Reviewers</span>
				</div>
				<?php if(_UserRoleFromSession() != 8) { ?>
				<!-- <a href="javascript:void(0);" id="new_rev" data-bs-toggle="modal" data-bs-target="#processModal"
                    class="btn btn-primary"><span class="fa fa-search"></span> Find new reviewer</a> -->
				<?php } ?>
				<button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
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
				<button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
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
				<button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
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
											<td colspan="3" class="fw-bold">TOTAL SCORE</td>
											<td><input type="text" id="crt_total" name="scr_total"
													class="form-control border border-dark" readonly=""></td>
										</tr>
										<tr>
											<td colspan="4">
												<div class="mb-3">
													<label for="scr_remarks">General Remarks</label>
													<textarea class="form-control form-control-sm" id="scr_remarks"
														name="scr_remarks" placeholder="(Required)"></textarea>
												</div>
											</td>
										</tr>
										<tr>
											<td colspan="4">
												<div class="mb-3">
													<label class="fw-bold" for="scr_file">You may upload your
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
												<div class="mb-3">
													<label class="fw-bold" for="scr_nda">Non-Disclosure
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
										</tr>
									</tbody>
								</table>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
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
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="submit_editorial_review_form" method="POST" enctype="multipart/form-data">
					<div class="mb-3">
						<input type="hidden" id="edit_man_id" name="edit_man_id">
						<label class="fw-bold">Upload file</label>
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
					<div class="mb-3 pt-3">
						<label class="fw-bold" for="edit_remarks">Remarks</label>
						<textarea class="form-control form-control-sm" id="edit_remarks" name="edit_remarks"
							placeholder="(Optional)"></textarea>
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
<!-- /.Final Review -->

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
							<td colspan="3" class="fw-bold">TOTAL SCORE</td>
							<td colspan="2" id="scr_total" class="text-primary"></td>
							<td></td>
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

<!-- Review inputs before process -->
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
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Publish Manuscript to eJournal</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
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