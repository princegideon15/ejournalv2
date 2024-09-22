<div class="container-fluid"  style="padding-top:3.5em">
  <!-- Breadcrumbs-->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
      <a href="javascript:void(0);">Reports</a></li>
      <li class="breadcrumb-item active"><?php echo $this->session->userdata('_oprs_type'); ?>  (<?php echo $this->session->userdata('_oprs_username'); ?>)</li>
    </ol>
    <div class="row">
      <div class="col-3">
        <div class="accordion">
          <div class="form-group">
            <ul class="list-group list-group-flush">
              <a class="list-group-item text-primary" data-toggle="collapse" data-target="#collapseManuscripts"><i class="fas fa-angle-right"></i> List of Manuscripts</a>
              <li class="list-group-item text-primary" data-toggle="collapse" data-target="#collapseDecReq"><i class="fas fa-angle-right"></i> List of Declined Requests</li>
              <li class="list-group-item text-primary" data-toggle="collapse" data-target="#collapseLapReq"><i class="fas fa-angle-right"></i> List of Lapsed Requests</li>
              <li class="list-group-item text-primary" data-toggle="collapse" data-target="#collapseLapRev"><i class="fas fa-angle-right"></i> List of Lapsed Reviews</li>
              <li class="list-group-item text-primary" data-toggle="collapse" data-target="#collapseRevMan"><i class="fas fa-angle-right"></i> List of Reviewed Manuscripts</li>
              <li class="list-group-item text-primary" data-toggle="collapse" data-target="#collapseComRev"><i class="fas fa-angle-right"></i> List of Completed Reviews</li>
              <li class="list-group-item text-primary" data-toggle="collapse" data-target="#collapseRevs"><i class="fas fa-angle-right"></i> List of Reviewers</li>
              <li class="list-group-item text-primary" data-toggle="collapse" data-target="#collapseNda"><i class="fas fa-angle-right"></i> List of Non-Disclosure Agreement (NDA)</li>
            </ul>
          </div>
        </div>
      </div>
      <div class="col">  
        <!-- list of Manuscripts -->
        <div id="collapseParentReports">
          <div id="collapseManuscripts" class="collapse" data-parent="#collapseParentReports">
            <div class="card">
              <div class="card-header font-weight-bold">
                <i class="fa fa-table"></i> List of Manuscripts
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-hover" id="report_manuscript_table" width="100%" cellspacing="0">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Manuscript Title</th>
                        <th>Authors</th>
                        <th>Date Submitted</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $c = 1;foreach ($manus as $m): ?>
                      <?php $acoa = (empty($this->Coauthor_model->get_author_coauthors($m->row_id))) ? '' : ', ' . $this->Coauthor_model->get_author_coauthors($m->row_id);?>
                      <?php $authors = $m->man_author . $acoa?>
                      <?php $status = (($m->man_status == '1') ? 'New' :
          ((($m->man_status == '2') ? 'On-review' :
            ((($m->man_status == '3') ? 'Reviewed' :
              ((($m->man_status == '4') ? 'For Approval' :
                'Published')))))));?>
                          <?php $class = (($m->man_status == '1') ? 'warning' :
          ((($m->man_status == '2') ? 'primary' :
            ((($m->man_status == '3') ? 'info' :
              ((($m->man_status == '4') ? 'danger' :
                'success')))))));?>
                      <tr>
                        <td><?php echo $c++; ?></td>
                        <td><?php echo $m->man_title; ?></td>
                        <td><?php echo $authors; ?></td>
                        <td><?php echo date_format(new DateTime($m->date_created), 'F j, Y'); ?></td>
                        <td><span style="cursor:pointer" class="badge badge-pill badge-<?php echo $class; ?>" data-toggle="modal" rel="tooltip" data-placement="top" title="View Tracking" data-target="#trackingModal" onclick="tracking(<?php echo $m->row_id; ?>,<?php echo $this->session->userdata('_oprs_type_num'); ?>);"><?php echo $status; ?></span></td>
                      </tr>
                      <?php endforeach;?>
                    </tbody>
                  </table>
                </div>
              </div>
          </div>
        </div>
  
        <!-- List of Reviewed Manuscripts -->
        <div id="collapseRevMan" class="collapse"  data-parent="#collapseParentReports">
          <div class="card">
            <div class="card-header font-weight-bold">
              <span class=" fa fa-user-secret"></span> List of Reviewed Manuscripts
            </div>
            <div class="card-body">
              <table class="table" id="report_revman_table">
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
        </div>
        <!-- /.List of Reviewed Manuscripts -->
        
        <!-- List of Completed Reviews -->
        <div id="collapseComRev" class="collapse"  data-parent="#collapseParentReports">
          <div class="card">
            <div class="card-header font-weight-bold">
              <span class=" fa fa-user-secret"></span> List of Completed Reviews
            </div>
            <div class="card-body">
              <table class="table" id="report_comrev_table">
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
        </div>
        <!-- /.List of Completed Reviews -->
        
        <!-- List of Reviewers -->
        <div id="collapseRevs" class="collapse"  data-parent="#collapseParentReports">
          <div class="card">
            <div class="card-header font-weight-bold">
              <span class=" fa fa-user-secret"></span> List of Reviewers
            </div>
            <div class="card-body">
              <!-- <div class="table-responsive"> -->
                <table class="table" id="report_reviewer_table">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Reviewer</th>
                      <th>Manuscript Reviewed</th>
                      <th>Authors</th>
                      <th>Date Reviewed</th>
                      <th>Status</th>
                      <th>File</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $c = 1;foreach ($reviewers as $row): ?>
                      <?php $rev_name = $this->Manuscript_model->get_reviewer_name($row->scr_man_rev_id); ?>
                    <?php $acoa = (empty($this->Coauthor_model->get_author_coauthors($row->row_id))) ? '' : ', ' . $this->Coauthor_model->get_author_coauthors($row->row_id);?>
                    <?php $authors = $row->man_author . $acoa; ?>
                    <?php $file = ($row->scr_file != '') ? "<a class='text-truncate' href='" . base_url('assets/oprs/uploads/reviewersdoc/'.$row->scr_file.'') ."' download>Download</a>" : 'N/A'; ?>
                    <?php $rev_status = (($row->scr_status == '4') ? '<span class="badge badge-pill badge-success">Recommended as submitted</span>' 
                              : ((($row->scr_status == '5') ? '<span class="badge badge-pill badge-warning">Recommended with minor revisions</span>' 
                              : ((($row->scr_status == '6') ? '<span class="badge badge-pill badge-warning">Recommended with major revisions</span>'  
                              : ((($row->scr_status == '7') ? '<span class="badge badge-pill badge-danger">Not recommended</span>' 
                              : '-'))))))
	                            );?>
                    <?php $cert = ($row->scr_cert == 1) ? '<span class="badge badge-success"><span class="fas fa-check-circle"></span> eCertification</span>' : '<button class="btn btn-sm btn-outline-secondary" onclick="send_cert(\'' . $row->scr_man_rev_id . '\',\'' . $row->man_id . '\')">Send eCertification</button>';?>
                    <tr>
                      <td><?php echo $c++; ?></td></td>
                      <td><?php echo $rev_name; ?></td>
                      <td><?php echo $row->man_title; ?></td>
                      <td><?php echo $authors; ?></td>
                      <td><?php echo date_format(new DateTime($row->date_reviewed), 'F j, Y'); ?></td>
                      <td><a href="javascript:void(0);" onclick="view_score('<?php echo $row->scr_man_rev_id; ?>','<?php echo $row->row_id; ?>','<?php echo $rev_name; ?>')" data-toggle="modal" data-target="#scoreModal"><?php echo $rev_status; ?></td>
                      <td><?php echo $file;?></td>
                      <td><?php echo $cert;?></td>
                    </tr>
                    <?php endforeach;?>
                  </tbody>
                </table>
              <!-- </div> -->
            </div>
          </div>
        </div>
        <!-- /.List of Reviewers -->

        <!-- List of NDAs -->
        <div id="collapseNda" class="collapse"  data-parent="#collapseParentReports">
          <div class="card">
            <div class="card-header font-weight-bold">
              <span class=" fa fa-user-secret"></span> List of NDAs
            </div>
            <div class="card-body">
              <table class="table" id="report_nda_table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Reviewer</th>
                    <th>Manuscript Reviewed</th>
                    <th>NDA</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $c = 1;foreach ($ndas as $row): ?>
                  <?php $rev_name = $this->Manuscript_model->get_reviewer_name($row->scr_man_rev_id);?>
                  <?php $acoa = (empty($this->Coauthor_model->get_author_coauthors($row->row_id))) ? '' : ', ' . $this->Coauthor_model->get_author_coauthors($row->row_id);?>
                  <?php $authors = $row->man_author . $acoa?>
                  <?php $rev_status = (($row->scr_status == '4') ? '<span class="badge badge-pill badge-success">Recommended as submitted</span>' 
                              : ((($row->scr_status == '5') ? '<span class="badge badge-pill badge-warning">Recommended with minor revisions</span>' 
                              : ((($row->scr_status == '6') ? '<span class="badge badge-pill badge-warning">Recommended with major revisions</span>'  
                              : ((($row->scr_status == '7') ? '<span class="badge badge-pill badge-danger">Not recommended</span>' 
                              : '-'))))))
	                            );?>
                  <tr>
                    <td><?php echo $c++; ?></td></td>
                    <td><?php echo $rev_name; ?></td>
                    <td><?php echo $row->man_title; ?></td>
                    <td><a href="<?php echo base_url('assets/oprs/uploads/nda/'.$row->scr_nda.'');?>" download><?php echo $row->scr_nda; ?></a></td>
                </tr>
                  <?php endforeach;?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <!-- /.List of NDAs -->

        <!-- List of Lapsed Request-->
        <div id="collapseLapReq" class="collapse" data-parent="#collapseParentReports">
          <div class="card">
            <div class="card-header font-weight-bold"><span class="fas fa-fw fa-american-sign-language-interpreting"></span> Lapsed Requests</div>
            <div class="card-body">
              <table class="table" id="report_lapreq_table">
                <thead class="thead-success">
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
        <!-- /.List of Lapsed Requests -->

        <!-- List of Declined Requests -->
        <div id="collapseDecReq" class="collapse" data-parent="#collapseParentReports">
          <div class="card">
            <div class="card-header font-weight-bold"><span class="fas fa-fw fa-question-circle"></span> Declined Requests</div>
            <div class="card-body">
              <table class="table" id="report_decreq_table">
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
        <!-- /.List of Declined Requests -->

        <!-- List of Lapsed Reviews -->
        <div id="collapseLapRev" class="collapse" data-parent="#collapseParentReports">
          <div class="card">
            <div class="card-header font-weight-bold"><span class="fas fa-fw fa-history"></span> Lapsed Reviews</div>
            <div class="card-body">
              <table class="table" id="report_laprev_table">
                <thead class="thead-warning">
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
        <!-- /.List of Lapsed Reviews -->
      </div>
    </div>
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

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
        <a class="btn btn-primary" href="<?php echo base_url('oprs/login/logout'); ?>">Logout</a>
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
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body m-0 p-0" style="font-size:20px;">
        <div class="list-group w-100" id="track_list">
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
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

<!-- Reviewers -->
<div class="modal fade" id="reviewerModal" tabindex="-1" role="dialog" aria-labelledby="processModal" aria-hidden="true">
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
        <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
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
                            <div class="form-row mb-2">
                              <div class="col-3">
                                <select class="form-control" id="trk_title1" name="trk_title[]" placeholder="Title">
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
                      <label class="font-weight-bold" for="man_remarks">Remarks</label>
                      <textarea class="form-control" id="trk_remarks" name="trk_remarks" placeholder="Type your remarks here" onkeydown="countChar(this)"></textarea>
                      <small class="text-muted float-right limit"></small>
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