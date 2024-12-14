<div id="layoutSidenav_content">
    <main>
		  <div class="container-fluid pt-3">
        <h3 class="fw-bold">Feedbacks</h3>
        <!-- Breadcrumbs-->
        <!-- <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="javascript:void(0);">Feedbacks</a></li>
            <li class="breadcrumb-item active"><?php echo $this->session->userdata('_oprs_type'); ?>  (<?php echo $this->session->userdata('_oprs_username'); ?>)</li>
        </ol> -->
        <div class="card border border-dark">
          <div class="card-header fw-bold">
            <i class="fa fa-table"></i> Feedbacks
          </div>
          <div class="card-body">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
              <li class="nav-item" role="presentation">
                <a class="nav-link active" id="uiux-tab" data-bs-toggle="tab" href="#uiux" role="tab" aria-controls="uiux" aria-selected="true">UI/UX Feedbacks</a>
              </li>
              <li class="nav-item" role="presentation">
                <a class="nav-link" id="uiux-grph-tab" onclick="generate_uiux_graph()" data-bs-toggle="tab" href="#uiux-grph" role="tab" aria-controls="uiux-grph" aria-selected="false">UI/UX Graph</a>
              </li>
              <li class="nav-item" role="presentation">
                <a class="nav-link" id="csf-tab" data-bs-toggle="tab" href="#csf" role="tab" aria-controls="csf" aria-selected="false">Client Satisfaction Feedbacks</a>
              </li>
              <li class="nav-item" role="presentation">
                <a class="nav-link" id="csf-grph-tab"  onclick="generate_csf_graph(0)" data-bs-toggle="tab" href="#csf-grph" role="tab" aria-controls="csf-grph" aria-selected="false">CSF Graph</a>
              </li>
            </ul>
            <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade show active" id="uiux" role="tabpanel" aria-labelledby="uiux-tab">
                <div class="table-responsive mt-3">
                  <table class="table table-hover" id="uiux_table" width="100%" cellspacing="0">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>UI Rating</th>
                        <th>UI Suggestions</th>
                        <th>UX Rating</th>
                        <th>UX Rating</th>
                        <th>Category</th>
                        <th>Date</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $c = 1;foreach ($feedbacks as $row): ?>
                      <?php $rate_ui = (($row->fb_rate_ui == 1) ? 'Sad' : (($row->fb_rate_ui == 2) ? 'Neutral' : 'Happy')); ?>
                      <?php $badge_ui = (($row->fb_rate_ui == 1) ? 'danger' : (($row->fb_rate_ui == 2) ? 'warning' : 'success')); ?>
                      <?php $rate_ux = (($row->fb_rate_ux == 1) ? 'Sad' : (($row->fb_rate_ux == 2) ? 'Neutral' : 'Happy')); ?>
                      <?php $badge_ux = (($row->fb_rate_ux == 1) ? 'danger' : (($row->fb_rate_ux == 2) ? 'warning' : 'success')); ?>
                      <?php $sys = (($row->fb_system == 1) ? 'eJournal' : (($row->fb_system == 2) ? 'OPRS' : 'Client')); ?>
                      <?php $name = $this->Feedback_model->get_name($row->fb_usr_id, $row->fb_system, $row->fb_source); ?>
                        <td><?php echo $c++; ?></td>
                        <td><?php echo $name; ?></td>
                        <td><span class="badge bg-<?php echo $badge_ui;?>"><?php echo $rate_ui;?></span></td>
                        <td><?php echo $row->fb_suggest_ui;?></td>
                        <td><span class="badge bg-<?php echo $badge_ux;?>"><?php echo $rate_ux;?></span></td>
                        <td><?php echo $row->fb_suggest_ux;?></td>
                        <td><?php echo $sys;?></td>
                        <td><?php echo date_format(new DateTime($row->date_created), 'F j, Y g:i a'); ?></td>
                      </tr>
                      <?php endforeach;?>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="tab-pane fade" id="csf" role="tabpanel" aria-labelledby="csf-tab">
                <div class="table-responsive mt-3">
                  <table class="table table-hover" id="cfs_table" width="100%" cellspacing="0">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Sex</th>
                        <th>Email</th>
                        <th>Date</th>
                        <th>Feedback</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $c = 1;foreach ($csf_feedbacks as $row): ?>
                      <?php $sex = ($row->clt_sex == 1) ? 'Male' : 'Female'; ?>
                        <td><?php echo $c++; ?></td>
                        <td><?php echo $row->clt_name; ?></td>
                        <td><?php echo $sex; ?></td>
                        <td><?php echo $row->clt_email; ?></td>
                        <td><?php echo date_format(new DateTime($row->date_created), 'F j, Y g:i a'); ?></td>
                        <td><button class="btn btn-light text-primary" onclick="view_csf_feedback('<?php echo $row->svc_fdbk_ref; ?>')">View Feedback</button></td>
                      </tr>
                      <?php endforeach;?>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="tab-pane fade" id="uiux-grph" role="tabpanel" aria-labelledby="uiux-grph-tab">
                <div class="alert alert-secondary" role="alert">
                  User Interface Feedbacks
                </div>
                <div class="row">
                  <div class="col-8">
                    <canvas id="ui_bar_chart" height="100"></canvas>
                  </div>
                  <div class="col-4">
                    <canvas id="ui_pie_chart" height="80"></canvas>
                  </div>
                </div>
                <div class="alert alert-secondary" role="alert">
                  User Experience Feedbacks
                </div>
                <div class="row">
                  <div class="col-8">
                    <canvas id="ux_bar_chart" height="100"></canvas>
                  </div>
                  <div class="col-4">
                    <canvas id="ux_pie_chart" height="80"></canvas>
                  </div>
                </div>
              </div>
              <div class="tab-pane fade" id="csf-grph" role="tabpanel" aria-labelledby="csf-grph-tab" >
                <select class="form-control" id="csf_questions" onchange="generate_csf_graph()">
                <option value="0">Overall Satisfcation</option>
                <?php foreach($questions as $q){
                  if($q->svc_fdbk_q_id != 12 && $q->svc_fdbk_q_id != 13) // question w/o choices
                  echo '<option value="'. $q->svc_fdbk_q_id . '">' . $q->svc_fdbk_q . '</option>';  } ?>
                </select>

                <div class="row">
                  <div class="col-8 csf-bar">
                    <canvas id="csf_bar_chart" height="100"></canvas>
                  </div>
                  <div class="col-4 csf-pie">
                    <canvas id="csf_pie_chart" height="100"></canvas>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>