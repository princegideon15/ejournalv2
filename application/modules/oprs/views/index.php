<div class="container-fluid"  style="padding-top:3.5em">
  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="javascript:void(0);">Dashboard</a></li>
      <li class="breadcrumb-item active"><?php echo $this->session->userdata('_oprs_type'); ?>  (<?php echo $this->session->userdata('_oprs_username'); ?>)</li>
    </ol>
    <!-- feedbacks alert -->
    <?php //if(_UserRoleFromSession() == 8 && $count_feedbacks > 0){ ?>
    <!-- <?php //$msg = ($count_feedbacks == 1) ? 'New Feedback!' : 'New Feedbacks!'; ?>
        <div>
            <div class="alert alert-warning alert-dismissible fade show fb_notif" role="alert">
            
            <a onclick="view_feedbacks()">
            <span class="badge badge-warning"><?php echo $count_feedbacks; ?></span> <?php echo $msg; ?>
            </a>

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
        </div> -->
    <?php //} ?>
    <!-- Icon Cards-->
    <div class="accordion">
      <div class="row">
        <div class="col-xl-3 col-sm-6 mb-3">
          <div class="card text-dark bg-warning o-hidden h-100 rounded">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fas fa-fw fa-address-card"></i>
              </div>
              <div class="mr-5">
              <span class="font-weight-bold" style="font-size:50px"><?php echo count($new); ?></span> New Manuscripts</div>
            </div>
            <a class="card-footer text-dark clearfix small z-1" href="javascript:void(0);" data-toggle="collapse" data-target="#collapse_new">
              <span class="float-left">View Details</span>
              <span class="float-right">
                <i class="fas fa-angle-right"></i>
              </span>
            </a>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-3">
          <div class="card text-light bg-danger o-hidden h-100 rounded">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="far fa-times-circle"></i>
              </div>
              <div class="mr-5">
              <span class="font-weight-bold" style="font-size:50px"><?php echo count($decreq);?></span> Declined Requests</div>
            </div>
            <a class="card-footer text-light clearfix small z-1" href="javascript:void(0);" data-toggle="collapse" data-target="#collapse_decreq">
              <span class="float-left">View Details</span>
              <span class="float-right">
                <i class="fas fa-angle-right"></i>
              </span>
            </a>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-3">
          <div class="card text-light bg-danger o-hidden h-100 rounded">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fas fa-fw fa-history"></i>
              </div>
              <div class="mr-5">
              <span class="font-weight-bold" style="font-size:50px"><?php echo count($lapreq); ?></span> Lapsed Requests</div>
            </div>
            <a class="card-footer text-light clearfix small z-1"  href="javascript:void(0);" data-toggle="collapse" data-target="#collapse_lapreq">
              <span class="float-left">View Details</span>
              <span class="float-right">
                <i class="fas fa-angle-right"></i>
              </span>
            </a>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-3">
          <div class="card text-light bg-danger o-hidden h-100 rounded">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fas fa-fw fa-history"></i>
              </div>
              <div class="mr-5">
              <span class="font-weight-bold" style="font-size:50px"><?php echo count($laprev); ?> </span>Lapsed Reviews</div>
            </div>
            <a class="card-footer text-light clearfix small z-1" href="javascript:void(0);" data-toggle="collapse" data-target="#collapse_laprev">
              <span class="float-left">View Details</span>
              <span class="float-right">
                <i class="fas fa-angle-right"></i>
              </span>
            </a>
          </div>
        </div>
      </div>
    </div>
    <!-- NEW MANUSCRIPT -->
    <div id="collapse_parent">
      <div id="collapse_new" class="collapse" data-parent="#collapse_parent">
        <div class="card border-warning mb-3">
          <div class="card-header text-warning h5">New Manuscripts</div>
          <div class="card-body">
            <table class="table table-hover" id="collapse_new_table">
              <thead class="thead-warning">
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Title</th>
                  <th scope="col">Date Submitted</th>
                </tr>
              </thead>
              <tbody>
                <?php $c = 1;foreach ($new as $row): ?>
                <?php $acoa = $this->Coauthor_model->get_author_coauthors($row->row_id);?>
                <?php $title = $row->man_title . ', ' . $row->man_author . ', ' . $acoa?>
                <tr>
                  <td  style="width:1%; !important"><?php echo $c++; ?></td>
                  <td  style="width:70%; !important"><a href="javascript:void(0);" class="btn-link text-dark" onclick="open_manuscript('<?php echo $row->man_title;?>')"><?php echo $title;?></a></td>
                  <td><?php echo date_format(new DateTime($row->date_created), 'F j, Y, g:i a'); ?></td>
                </tr>
                <?php endforeach;?>
              </tbody>
                </table>
          </div>
        </div>
      </div>
      <!-- /.NEW MANUSCRIPT -->
      <!-- LAPSED REQUEST -->
      <div id="collapse_lapreq" class="collapse" data-parent="#collapse_parent">
        <div class="card border-danger mb-3">
          <div class="card-header text-danger h5">Lapsed Requests</div>
          <div class="card-body">
            <table class="table table-hover" id="collapse_lapreq_table">
              <thead class="thead-danger">
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Reviewer</th>
                  <th scope="col">Manuscript</th>
                  <th scope="col">Date Requested</th>
                </tr>
              </thead>
              <tbody>
                <?php $c = 1;foreach ($lapreq as $row): ?>
                <tr>
                  <td style="width:1%; !important"><?php echo $c++; ?></td>
                  <td><?php echo $row->revname; ?></td>
                  <td  style="width:50%; !important"><a href="javascript:void(0);" class="btn-link text-dark" onclick="open_manuscript('<?php echo $row->mantitle;?>')"><?php echo $row->mantitle;?></a></td>
                  <td><?php echo date_format(new DateTime($row->date_created), 'F j, Y, g:i a'); ?></td>
                </tr>
                <?php endforeach;?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <!-- /.LAPSED REQUEST -->
      <!-- DECLINED REQUEST -->
      <div id="collapse_decreq" class="collapse" data-parent="#collapse_parent">
        <div class="card border-danger mb-3">
          <div class="card-header text-danger h5">Declined Requests</div>
          <div class="card-body">
            <table class="table table-hover" id="collapse_decreq_table">
              <thead class="thead-danger">
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Reviewer</th>
                  <th scope="col">Manuscript</th>
                  <th scope="col">Date Declined</th>
                </tr>
              </thead>
              <tbody>
                <?php $c = 1;foreach ($decreq as $row): ?>
                <tr>
                  <td><?php echo $c++; ?></td>
                  <td><?php echo $row->revname; ?></td>
                  <td  style="width:50%; !important"><a href="javascript:void(0);" class="btn-link text-dark" onclick="open_manuscript('<?php echo $row->mantitle;?>')"><?php echo $row->mantitle;?></a></td>
                  <td><?php echo date_format(new DateTime($row->date_declined), 'F j, Y, g:i a'); ?></td>
                </tr>
                <?php endforeach;?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <!-- /.DECLINED REQUEST -->
      <!-- LAPSED REVIEW -->
      <div id="collapse_laprev" class="collapse" data-parent="#collapse_parent">
        <div class="card border-danger mb-3">
          <div class="card-header text-danger h5">Lapsed Reviews</div>
          <div class="card-body">
            <table class="table table-hover" id="collapse_laprev_table">
              <thead class="thead-danger">
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Reviewer</th>
                  <th scope="col">Manuscript</th>
                  <th scope="col">Last action</th>
                </tr>
              </thead>
              <tbody>
                <?php $c = 1;foreach ($laprev as $row): ?>
                <tr>
                  <td><?php echo $c++; ?></td></td>
                  <td><?php echo $row->revname; ?></td>
                  <td  style="width:50%; !important"><a href="javascript:void(0);" class="btn-link text-dark" onclick="open_manuscript('<?php echo $row->mantitle;?>')"><?php echo $row->mantitle;?></a></td>
                  <td><?php echo date_format(new DateTime($row->date_respond), 'F j, Y, g:i a'); ?></td>
                </tr>
                <?php endforeach;?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div>
    </div>
    <!-- /.LAPSED REVIEW -->
    <div class="accordion">
      <div class="row">
        <div class="col-xl-3 col-sm-6 mb-3">
          <div class="card text-light bg-primary o-hidden h-100 rounded">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="far fa-edit"></i>
              </div>
              <div>
              <span class="font-weight-bold" style="font-size:50px"><?php echo count($reviewed); ?> </span>Reviewed Manuscripts</div>
            </div>
            <a class="card-footer text-light clearfix small z-1" href="javascript:void(0);" data-toggle="collapse" data-target="#collapse_reviewed_manuscripts">
              <span class="float-left">View Details</span>
              <span class="float-right">
                <i class="fas fa-angle-right"></i>
              </span>
            </a>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-3">
          <div class="card text-light bg-info o-hidden h-100 rounded">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="far fa-clipboard"></i>
              </div>
              <div>
              <span class="font-weight-bold" style="font-size:50px"><?php echo count($completed); ?> </span>Completed Reviews</div>
            </div>
            <a class="card-footer text-light clearfix small z-1" href="javascript:void(0);" data-toggle="collapse" data-target="#collapse_completed_reviews">
              <span class="float-left">View Details</span>
              <span class="float-right">
                <i class="fas fa-angle-right"></i>
              </span>
            </a>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-3">
          <div class="card border border-dark o-hidden h-100 rounded">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fas fa-user-friends"></i>
              </div>
              <div>
              <span class="font-weight-bold" style="font-size:50px"><?php echo count($reviewers); ?> </span>Reviewers</div>
            </div>
            <a class="card-footer text-dark clearfix small z-1" href="javascript:void(0);" data-toggle="collapse" data-target="#collapse_reviewers">
              <span class="float-left">View Details</span>
              <span class="float-right">
                <i class="fas fa-angle-right"></i>
              </span>
            </a>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-3">
          <div class="card border border-dark o-hidden h-100 rounded">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="far fa-check-circle"></i>
              </div>
              <div>
              <span class="font-weight-bold" style="font-size:50px"><?php echo count($publish); ?> </span>Publishables</div>
            </div>
            <a class="card-footer text-dark clearfix small z-1" href="javascript:void(0);" data-toggle="collapse" data-target="#collapse_publishables">
              <span class="float-left">View Details</span>
              <span class="float-right">
                <i class="fas fa-angle-right"></i>
              </span>
            </a>
          </div>
        </div>
      </div>
    </div>
    <div id="collapse_parent_sub" >
      <!-- LIST OF REVIEWED MANUSCRIPTS -->
      <div class="card border-primary collapse" data-parent="#collapse_parent_sub" id="collapse_reviewed_manuscripts">
        <div class="card-header font-weight-bold">
          Reviewed Manuscripts
        </div>
        <div class="card-body">
          <table class="table table-hover" id="collapse_reviewed_table">
            <thead>
              <tr>
                <th>#</th>
                <th>Manuscript</th>
                <th>Date Reviewed</th>
              </tr>
            </thead>
            <tbody>
              <?php $c = 1;foreach ($reviewed as $row): ?>
                  <tr>
                  <td style="width:1%; !important"><?php echo $c++; ?></td></td>
                  <td style="width:70%; !important"><a href="javascript:void(0);" class="btn-link text-dark" onclick="open_manuscript('<?php echo $row->mantitle;?>')"><?php echo $row->mantitle;?></a></td>
                  <td><?php echo date_format(new DateTime($row->date_reviewed), 'F j, Y, g:i a'); ?></td>
                </tr>
              <?php endforeach;?>
            </tbody>
          </table>
        </div>
      </div>
      <!-- /.LIST OF REVIEWED MANUSCRIPTS -->
      <!-- LIST OF COMPLETED REVIEWS -->
      <div class="card border-info collapse" data-parent="#collapse_parent_sub" id="collapse_completed_reviews">
        <div class="card-header font-weight-bold">
          Completed Reviews
        </div>
        <div class="card-body">
          <table class="table table-hover" id="collapse_complete_table">
            <thead>
              <tr>
                <th>#</th>
                <th>Manuscript</th>
                <th>Date Reviewed</th>
              </tr>
            </thead>
            <tbody>
              <?php $c = 1;foreach ($completed as $row): ?>
                  <tr>
                  <td style="width:1%; !important"><?php echo $c++; ?></td></td>
                  <td style="width:70%; !important"><a href="javascript:void(0);" class="btn-link text-dark" onclick="open_manuscript('<?php echo $row->mantitle;?>')"><?php echo $row->mantitle;?></a></td>
                  <td><?php echo date_format(new DateTime($row->date_reviewed), 'F j, Y, g:i a'); ?></td>
                </tr>
              <?php endforeach;?>
            </tbody>
          </table>
        </div>
      </div>
      <!-- /.LIST OF COMPLETED REVIEWS -->
      <!-- LIST OF REVIEWERS -->
      <div class="card border-dark collapse" data-parent="#collapse_parent_sub" id="collapse_reviewers">
        <div class="card-header font-weight-bold">
          List of Reviewers
        </div>
        <div class="card-body">
          <table class="table table-hover" id="collapse_reviewers_table">
            <thead>
              <tr>
                <th>#</th>
                <th>Reviewer</th>
                <th>Manuscript</th>
                <th>Score</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php $c = 1;foreach ($reviewers as $row): ?>
              <?php $acoa = (empty($this->Coauthor_model->get_author_coauthors($row->row_id))) ? '' : ', ' . $this->Coauthor_model->get_author_coauthors($row->row_id);?>
              <?php $title = $row->man_title . ', ' . $row->man_author . $acoa?>
              <?php $rev_status = (($row->scr_status == '4') ? '<span class="badge badge-pill badge-success">Approved</span>' :
          ((($row->scr_status == '5') ? '<span class="badge badge-pill badge-warning">Need Revision</span>' :
            '<span class="badge badge-pill badge-danger">Disapproved</span>')));?>
              <tr>
                <td><?php echo $c++; ?></td></td>
                <td><?php echo $this->Manuscript_model->get_reviewer_name($row->scr_man_rev_id); ?></td>
                <td><?php echo $title; ?></td>
                <td><?php echo $row->scr_total; ?></td>
                <td><?php echo $rev_status; ?></td>
              </tr>
              <?php endforeach;?>
            </tbody>
          </table>
        </div>
      </div>
      <!-- /.LIST OF REVIEWERS -->
    </div>
  </div>
  <!-- /.container-fluid -->
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

<!-- PROCESS MANUSCRIPT -->
<div class="modal fade" id="processModal" tabindex="-1" role="dialog" aria-labelledby="processModal" aria-hidden="true" style="z-index:9999">
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
                      <label class="font-weight-bold" for="jor_volume">Volume No.</label>
                      <select class="form-control text-uppercase" id="jor_volume" name="jor_volume" placeholder="ex. X" style="background-color:white">
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
                      <input type="number" class="form-control" id="jor_year" name="jor_year" max="9999" min="1000" >
                    </div>
                  </div>
                </div>
                <div class="tab-pane fade" id="article" role="tabpanel" aria-labelledby="article-tab">
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
                          <div class="form-group autocomplete w-100">
                            <input type="text" class="form-control" id="trk_rev1" name="trk_rev[]" placeholder="Search by Name/Specialization or Type Non-member name">
                          </div>
                          <div class="form-row">
                            <div class="col">
                              <input type="text" class="form-control" placeholder="Email" id="trk_rev_email1" name="trk_rev_email[]">
                            </div>
                            <div class="col">
                              <input type="text" class="form-control" placeholder="Contact" id="trk_rev_num1" name="trk_rev_num[]">
                            </div>
                            <input type="hidden" id="trk_rev_id1" name="trk_rev_id[]">
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
                  <p class="font-weight-bold">Accept Review</p>
                  <div class="input-group mb-3">
                    <input type="number" placeholder="0" id="trk_request_timer" name="trk_request_timer" style="width:70px !important;" min="1">
                    <div class="input-group-append">
                      <span class="input-group-text">Days to accept/decline the review request.</span>
                    </div>
                  </div>
                  <p class="font-weight-bold">Review Request</p>
                  <div class="input-group mb-3">
                    <input type="number" placeholder="0" id="trk_timeframe" name="trk_timeframe" style="width:70px !important;" min="1">
                    <div class="input-group-append">
                      <span class="input-group-text">Days to finish the review task</span>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="font-weight-bold" for="man_remarks">Remarks</label>
                    <textarea class="form-control" id="trk_remarks" name="trk_remarks" placeholder="(optional)"></textarea>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-6">
              <div class="accordion" id="rev_acc_mail">
                <h6 class="font-weight-bold">Request for Manuscript Review Email</h6>
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
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Submit</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- /.PROCESS MANUSCRIPT -->