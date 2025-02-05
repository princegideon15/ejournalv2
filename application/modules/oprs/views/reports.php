<div id="layoutSidenav_content">
    <main>
		  <div class="container-fluid pt-3">
          <h3 class="fw-bold">Reports</h3>
          <!-- Breadcrumbs-->
          <!-- <ol class="breadcrumb">
            <li class="breadcrumb-item">
            <a href="javascript:void(0);">Reports</a></li>
            <li class="breadcrumb-item active"><?php echo $this->session->userdata('_oprs_type'); ?>  (<?php echo $this->session->userdata('_oprs_username'); ?>)</li>
          </ol> -->
        <div class="row">
          <div class="col-3">
            <div class="accordion ">
                <ul class="list-group list-group-flush">
                  <button class="list-group-item list-group-item-action text-primary cursor-pointer" data-bs-toggle="collapse" data-bs-target="#collapseManuscripts"><i class="fas fa-angle-right"></i> List of Submitted Manuscripts</button>
                  <li class="list-group-item list-group-item-action text-primary cursor-pointer" data-bs-toggle="collapse" data-bs-target="#collapseDecReq"><i class="fas fa-angle-right"></i> List of Declined Requests</li>
                  <li class="list-group-item list-group-item-action text-primary cursor-pointer" data-bs-toggle="collapse" data-bs-target="#collapseLapReq"><i class="fas fa-angle-right"></i> List of Lapsed Requests</li>
                  <li class="list-group-item list-group-item-action text-primary cursor-pointer" data-bs-toggle="collapse" data-bs-target="#collapseLapRev"><i class="fas fa-angle-right"></i> List of Lapsed Reviews</li>
                  <li class="list-group-item list-group-item-action text-primary cursor-pointer" data-bs-toggle="collapse" data-bs-target="#collapseRevMan"><i class="fas fa-angle-right"></i> List of Reviewed Manuscripts</li>
                  <li class="list-group-item list-group-item-action text-primary cursor-pointer" data-bs-toggle="collapse" data-bs-target="#collapseComRev"><i class="fas fa-angle-right"></i> List of Completed Reviews</li>
                  <li class="list-group-item list-group-item-action text-primary cursor-pointer" data-bs-toggle="collapse" data-bs-target="#collapseRevs"><i class="fas fa-angle-right"></i> List of Reviewers</li>
                  <li class="list-group-item list-group-item-action text-primary cursor-pointer" data-bs-toggle="collapse" data-bs-target="#collapseNda"><i class="fas fa-angle-right"></i> List of Non-Disclosure Agreement (NDA)</li>
                </ul>
            </div>
          </div>
          <div class="col-9">  
            <!-- list of Manuscripts -->
            <div id="collapseParentReports">
              <div id="collapseManuscripts" class="collapse show" data-bs-parent="#collapseParentReports">
                <div class="card border border-dark">
                  <div class="card-header fw-bold">
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
                            <td class="text-center"><?php echo $c++; ?></td>
                            <td><?php echo $m->man_title; ?></td>
                            <td><?php echo $authors; ?></td>
                            <td><?php echo date_format(new DateTime($m->date_created), 'F j, Y'); ?></td>
                            <td><span style="cursor:pointer" class="badge rounded-pill text-bg-<?php echo $class; ?>" data-bs-toggle="modal" rel="tooltip" data-placement="top" title="View Tracking" data-bs-target="#trackingModal" onclick="tracking(<?php echo $m->row_id; ?>,<?php echo $this->session->userdata('_oprs_type_num'); ?>);"><?php echo $status; ?></span></td>
                          </tr>
                          <?php endforeach;?>
                        </tbody>
                      </table>
                    </div>
                  </div>
              </div>
            </div>
      
            <!-- List of Reviewed Manuscripts -->
            <div id="collapseRevMan" class="collapse"  data-bs-parent="#collapseParentReports">
              <div class="card border border-dark">
                <div class="card-header fw-bold">
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
                          <td><?php echo $c++; ?></td></td>
                          <td><a href="javascript:void(0);" class="btn-link text-dark" onclick="open_manuscript('<?php echo $row->mantitle;?>')"><?php echo $row->mantitle;?></a></td>
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
            <div id="collapseComRev" class="collapse"  data-bs-parent="#collapseParentReports">
              <div class="card border border-dark">
                <div class="card-header fw-bold">
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
                          <td><?php echo $c++; ?></td></td>
                          <td><a href="javascript:void(0);" class="btn-link text-dark" onclick="open_manuscript('<?php echo $row->mantitle;?>')"><?php echo $row->mantitle;?></a></td>
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
            <div id="collapseRevs" class="collapse"  data-bs-parent="#collapseParentReports">
              <div class="card border border-dark">
                <div class="card-header fw-bold">
                  <span class=" fa fa-user-secret"></span> List of Reviewers
                </div>
                <div class="card-body">
                  <div class="table-responsive">
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
                        <?php $rev_status = (($row->scr_status == '4') ? '<span class="badge rounded-pill bg-success">Recommended as submitted</span>' 
                                  : ((($row->scr_status == '5') ? '<span class="badge rounded-pill bg-warning">Recommended with minor revisions</span>' 
                                  : ((($row->scr_status == '6') ? '<span class="badge rounded-pill bg-warning">Recommended with major revisions</span>'  
                                  : ((($row->scr_status == '7') ? '<span class="badge rounded-pill bg-danger">Not recommended</span>' 
                                  : '-'))))))
                                  );?>
                        <?php $cert = ($row->scr_cert == 1) ? '<span class="badge bg-success"><span class="fas fa-check-circle"></span> eCertification</span>' : '<button class="btn btn-sm btn-outline-secondary" onclick="send_cert(\'' . $row->scr_man_rev_id . '\',\'' . $row->man_id . '\')">Send eCertification</button>';?>
                        <tr>
                          <td><?php echo $c++; ?></td></td>
                          <td><?php echo $rev_name; ?></td>
                          <td><?php echo $row->man_title; ?></td>
                          <td><?php echo $authors; ?></td>
                          <td><?php echo date_format(new DateTime($row->date_reviewed), 'F j, Y'); ?></td>
                          <td><a href="javascript:void(0);" onclick="view_score('<?php echo $row->scr_man_rev_id; ?>','<?php echo $row->row_id; ?>','<?php echo $rev_name; ?>')" data-bs-toggle="modal" data-bs-target="#scoreModal"><?php echo $rev_status; ?></td>
                          <td><?php echo $file;?></td>
                          <td><?php echo $cert;?></td>
                        </tr>
                        <?php endforeach;?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.List of Reviewers -->

            <!-- List of NDAs -->
            <div id="collapseNda" class="collapse"  data-bs-parent="#collapseParentReports">
              <div class="card border border-dark">
                <div class="card-header fw-bold">
                  <span class=" fa fa-user-secret"></span> List of NDAs
                </div>
                <div class="card-body">
                  <div class="table-responsive">
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
                        <?php $rev_status = (($row->scr_status == '4') ? '<span class="badge rounded-pill bg-success">Recommended as submitted</span>' 
                                    : ((($row->scr_status == '5') ? '<span class="badge rounded-pill bg-warning">Recommended with minor revisions</span>' 
                                    : ((($row->scr_status == '6') ? '<span class="badge rounded-pill bg-warning">Recommended with major revisions</span>'  
                                    : ((($row->scr_status == '7') ? '<span class="badge rounded-pill bg-danger">Not recommended</span>' 
                                    : '-'))))))
                                    );?>
                        <tr>
                          <td><?php echo $c++; ?></td></td>
                          <td><?php echo $rev_name; ?></td>
                          <td><?php echo $row->man_title; ?></td>
                          <td><a href="<?php echo base_url('assets/oprs/uploads/nda/'.$row->scr_nda.'');?>" download>Download</a></td>
                      </tr>
                        <?php endforeach;?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.List of NDAs -->

            <!-- List of Lapsed Request-->
            <div id="collapseLapReq" class="collapse" data-bs-parent="#collapseParentReports">
              <div class="card border border-dark">
                <div class="card-header fw-bold"><span class="fas fa-fw fa-american-sign-language-interpreting"></span> Lapsed Requests</div>
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
                          <td><?php echo $c++; ?></td>
                          <td><?php echo $row->revname; ?></td>
                          <td><a href="javascript:void(0);" class="btn-link text-dark" onclick="open_manuscript('<?php echo $row->mantitle;?>')"><?php echo $row->mantitle;?></a></td>
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
            <div id="collapseDecReq" class="collapse" data-bs-parent="#collapseParentReports">
              <div class="card border border-dark">
                <div class="card-header fw-bold"><span class="fas fa-fw fa-question-circle"></span> Declined Requests</div>
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
                          <td ><a href="javascript:void(0);" class="btn-link text-dark" onclick="open_manuscript('<?php echo $row->mantitle;?>')"><?php echo $row->mantitle;?></a></td>
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
            <div id="collapseLapRev" class="collapse" data-bs-parent="#collapseParentReports">
              <div class="card border border-dark">
                <div class="card-header fw-bold"><span class="fas fa-fw fa-history"></span> Lapsed Reviews</div>
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
                          <td ><a href="javascript:void(0);" class="btn-link text-dark" onclick="open_manuscript('<?php echo $row->mantitle;?>')"><?php echo $row->mantitle;?></a></td>
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
    </main>

