
  <div id="layoutSidenav_content">
    <main>
      <div class="container-fluid pt-3">
        <h3 class="fw-bold">Dashboard</h3>

        
        <!-- <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Dashboard</li>
            <li class="breadcrumb-item"><?php echo $this->session->userdata('_oprs_type'); ?>  (<?php echo $this->session->userdata('_oprs_username'); ?>)</li>
        </ol> -->
      
        <!-- Icon Cards-->
        <div class="accordion">
          <div class="row">
            <div class="col-xl-3 col-sm-6 mb-3">
              <div class="card text-dark bg-warning o-hidden h-100 rounded">
                <div class="card-body d-flex justify-content-start align-items-center">
                  <p class="card-text">
                    <span class="fw-bold me-2" style="font-size:50px"><?php echo count($new); ?></span> 
                    <i class="fas fa-fw fa-address-card me-1"></i>New Manuscripts
                  </p>
                </div>
                <a class="card-footer text-dark clearfix small z-1 text-decoration-none text-end" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapse_new">
                  View Details<i class="ms-1 fas fa-angle-right"></i>
                </a>
              </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-3">
              <div class="card text-light bg-danger o-hidden h-100 rounded">
                <div class="card-body d-flex justify-content-start align-items-center">
                  <p class="card-text">
                  <span class="fw-bold me-2" style="font-size:50px"><?php echo count($decreq);?></span> 
                  <i class="far fa-times-circle me-1"></i>Declined Requests
                </p>
                </div>
                <a class="card-footer text-light clearfix small z-1 text-decoration-none text-end" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapse_decreq">
                    View Details<i class="ms-1 fas fa-angle-right"></i>
                </a>
              </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-3">
              <div class="card text-light bg-danger o-hidden h-100 rounded">
                <div class="card-body d-flex justify-content-start align-items-center">
                  <div class="card-text">
                    <span class="fw-bold me-2" style="font-size:50px"><?php echo count($lapreq); ?></span> <i class="fas fa-fw fa-history me-1"></i>Lapsed Requests
                  </div>
                </div>
                <a class="card-footer text-light clearfix small z-1 text-decoration-none text-end"  href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapse_lapreq">
                  View Details<i class="ms-1 fas fa-angle-right"></i>
                </a>
              </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-3">
              <div class="card text-light bg-danger o-hidden h-100 rounded">
                <div class="card-body d-flex justify-content-start align-items-center">
                  <div class="card-text">
                    <span class="fw-bold me-2" style="font-size:50px"><?php echo count($laprev); ?></span><i class="fas fa-fw fa-history me-1"></i>Lapsed Reviews</div>
                  </div>
                <a class="card-footer text-light clearfix small z-1 text-decoration-none text-end" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapse_laprev">
                  View Details<i class="fas fa-angle-right ms-1"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
        <!-- NEW MANUSCRIPT -->
        <div id="collapse_parent">
          <div id="collapse_new" class="accordion-collapse collapse" data-bs-parent="#collapse_parent">
            <div class="card border-warning mb-3">
              <div class="card-header text-warning fw-bold fs-6 bg-white">New Manuscripts</div>
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
                      <td class="text-center align-middle"><?php echo $c++; ?></td>
                      <td class="align-middle" style="width:70%; !important"><a href="javascript:void(0);" class="btn-link text-dark" onclick="open_manuscript('<?php echo $row->man_title;?>')"><?php echo $title;?></a></td>
                      <td class="align-middle"><?php echo date_format(new DateTime($row->date_created), 'F j, Y, g:i a'); ?></td>
                    </tr>
                    <?php endforeach;?>
                  </tbody>
                    </table>
              </div>
            </div>
          </div>
          <!-- /.NEW MANUSCRIPT -->
          <!-- LAPSED REQUEST -->
          <div id="collapse_lapreq" class="accordion-collapse collapse" data-bs-parent="#collapse_parent">
            <div class="card border-danger mb-3">
              <div class="card-header text-danger fw-bold fs-6">Lapsed Requests</div>
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
                      <td class="text-center"><?php echo $c++; ?></td>
                      <td class="align-middle"><?php echo $row->revname; ?></td>
                      <td class="align-middle" style="width:50%; !important"><a href="javascript:void(0);" class="btn-link text-dark" onclick="open_manuscript('<?php echo $row->mantitle;?>')"><?php echo $row->mantitle;?></a></td>
                      <td class="align-middle"><?php echo date_format(new DateTime($row->date_created), 'F j, Y, g:i a'); ?></td>
                    </tr>
                    <?php endforeach;?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <!-- /.LAPSED REQUEST -->
          <!-- DECLINED REQUEST -->
          <div id="collapse_decreq" class="accordion-collapse collapse" data-bs-parent="#collapse_parent">
            <div class="card border-danger mb-3">
              <div class="card-header text-danger fw-bold fs-6">Declined Requests</div>
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
                      <td class="text-center"><?php echo $c++; ?></td>
                      <td class="align-middle"><?php echo $row->revname; ?></td>
                      <td class="align-middle" style="width:50%; !important"><a href="javascript:void(0);" class="btn-link text-dark" onclick="open_manuscript('<?php echo $row->mantitle;?>')"><?php echo $row->mantitle;?></a></td>
                      <td class="align-middle"><?php echo date_format(new DateTime($row->date_declined), 'F j, Y, g:i a'); ?></td>
                    </tr>
                    <?php endforeach;?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <!-- /.DECLINED REQUEST -->
          <!-- LAPSED REVIEW -->
          <div id="collapse_laprev" class="accordion-collapse collapse" data-bs-parent="#collapse_parent">
            <div class="card border-danger mb-3">
              <div class="card-header text-danger fw-bold fs-6">Lapsed Reviews</div>
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
                      <td class="text-center align-middle"><?php echo $c++; ?></td></td>
                      <td class="align-middle"><?php echo $row->revname; ?></td>
                      <td class="align-middle" style="width:50%; !important"><a href="javascript:void(0);" class="btn-link text-dark" onclick="open_manuscript('<?php echo $row->mantitle;?>')"><?php echo $row->mantitle;?></a></td>
                      <td class="align-middle"><?php echo date_format(new DateTime($row->date_respond), 'F j, Y, g:i a'); ?></td>
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
                <div class="card-body d-flex justify-content-start align-items-center">
                  <div class="card-text">
                    <span class="fw-bold me-2" style="font-size:50px"><?php echo count($reviewed); ?></span><i class="far fa-edit me-1"></i>Reviewed
                  </div>
                </div>
                <a class="card-footer text-light clearfix small z-1 text-decoration-none text-end" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapse_reviewed_manuscripts">
                  View Details<i class="fas fa-angle-right ms-1"></i>
                </a>
              </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-3">
              <div class="card text-light bg-info o-hidden h-100 rounded">
                <div class="card-body d-flex justify-content-start align-items-center">
                  <div class="card-text text-dark">
                    <span class="fw-bold me-2" style="font-size:50px"><?php echo count($completed); ?></span><i class="far fa-clipboard me-1"></i>Completed Reviews
                  </div>
                </div>
                <a class="card-footer text-dark clearfix small z-1 text-decoration-none text-end" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapse_completed_reviews">
                  View Details<i class="fas fa-angle-right ms-1"></i>
                </a>
              </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-3">
              <div class="card border border-dark o-hidden h-100 rounded">
                <div class="card-body d-flex justify-content-start align-items-center">
                  <div class="card-text">
                    <span class="fw-bold me-2" style="font-size:50px"><?php echo count($reviewers); ?></span><i class="fas fa-user-friends me-1"></i>Reviewers
                  </div>
                </div>
                <a class="card-footer text-dark clearfix small z-1 text-decoration-none text-end" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapse_reviewers">
                  View Details<i class="fas fa-angle-right ms-1"></i>
                </a>
              </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-3">
              <div class="card border border-dark o-hidden h-100 rounded">
                <div class="card-body d-flex justify-content-start align-items-center">
                  <div class="card-text">
                    <span class="fw-bold me-2" style="font-size:50px"><?php echo count($publish); ?></span><i class="far fa-check-circle me-1"></i>Publishables
                  </div>
                </div>
                <a class="card-footer text-dark clearfix small z-1 text-decoration-none text-end" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapse_publishables">
                  View Details<i class="fas fa-angle-right ms-1"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
        <div id="collapse_parent_sub" >
          <!-- LIST OF REVIEWED MANUSCRIPTS -->
          <div id="collapse_reviewed_manuscripts" class="accordion-collapse collapse" data-bs-parent="#collapse_parent_sub">
            <div class="card border-primary">
              <div class="card-header text-primary fw-bold fs-6">
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
                        <td class="text-center align-middle"><?php echo $c++; ?></td></td>
                        <td class="align-middle" style="width:70%; !important"><a href="javascript:void(0);" class="btn-link text-dark" onclick="open_manuscript('<?php echo $row->mantitle;?>')"><?php echo $row->mantitle;?></a></td>
                        <td class="align-middle"><?php echo date_format(new DateTime($row->date_reviewed), 'F j, Y, g:i a'); ?></td>
                      </tr>
                    <?php endforeach;?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <!-- /.LIST OF REVIEWED MANUSCRIPTS -->
          <!-- LIST OF COMPLETED REVIEWS -->
          <div id="collapse_completed_reviews" class="accordion-collapse collapse" data-bs-parent="#collapse_parent_sub">
            <div class="card border-info">
              <div class="card-header text-info fw-bold fs-6">
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
                        <td class="text-center align-middle" style="width:1%; !important"><?php echo $c++; ?></td></td>
                        <td class="align-middle" style="width:70%; !important"><a href="javascript:void(0);" class="btn-link text-dark" onclick="open_manuscript('<?php echo $row->mantitle;?>')"><?php echo $row->mantitle;?></a></td>
                        <td class="align-middle"><?php echo date_format(new DateTime($row->date_reviewed), 'F j, Y, g:i a'); ?></td>
                      </tr>
                    <?php endforeach;?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <!-- /.LIST OF COMPLETED REVIEWS -->
          <!-- LIST OF REVIEWERS -->
          <div id="collapse_reviewers" class="accordion-collapse collapse" data-bs-parent="#collapse_parent_sub">
            <div class="card border-dark">
              <div class="card-header fw-bold fs-6">
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
                    <?php $rev_status = (($row->scr_status == '4') ? '<span class="badge rounded-pill text-bg-success">Approved</span>' :
                ((($row->scr_status == '5') ? '<span class="badge rounded-pill text-bg-warning">Need Revision</span>' :
                  '<span class="badge rounded-pill text-bg-danger">Disapproved</span>')));?>
                    <tr>
                      <td class="text-center align-middle"><?php echo $c++; ?></td></td>
                      <td class="align-middle"><?php echo $this->Manuscript_model->get_reviewer_name($row->scr_man_rev_id); ?></td>
                      <td class="align-middle"><?php echo $title; ?></td>
                      <td class="align-middle"><?php echo $row->scr_total; ?></td>
                      <td class="align-middle"><?php echo $rev_status; ?></td>
                    </tr>
                    <?php endforeach;?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <!-- /.LIST OF REVIEWERS -->
        </div>
      </div>
    </main>  
  <!-- Scroll to Top Button-->
  <!-- <a class="scroll-to-top rounded" href="#page-top">
  <i class="fas fa-angle-up"></i>
  </a> -->