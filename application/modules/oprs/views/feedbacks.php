<div id="layoutSidenav_content">
    <main>
		  <div class="container-fluid pt-3">
        <!-- Breadcrumbs-->
        <!-- <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="javascript:void(0);">Feedbacks</a></li>
            <li class="breadcrumb-item active"><?php echo $this->session->userdata('_oprs_type'); ?>  (<?php echo $this->session->userdata('_oprs_username'); ?>)</li>
        </ol> -->
        <h3 class="fw-bold">Feedbacks</h3>
        <div class="card border border-dark">
          <div class="card-header">
          <i class="fas fa-star me-1"></i>CSF UI/UX
          </div>
          <div class="card-body">
            <h6 class="mb-3">Customer Service Feedback - User Interface / User Experience</h6>
            <ul class="nav nav-tabs" id="myTab" role="tablist">
              <li class="nav-item" role="presentation">
                <a class="nav-link active" data-bs-toggle="tab" href="#uiux" role="tab" aria-controls="uiux" aria-selected="true">UI/UX Feedbacks</a>
              </li>
              <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="tab" href="#uiux-sex" role="tab" aria-controls="uiux-sex" aria-selected="true">UI/UX by Sex</a>
              </li>
              <li class="nav-item" role="presentation">
                <a class="nav-link" onclick="generate_uiux_graph()" data-bs-toggle="tab" href="#uiux-grph" role="tab" aria-controls="uiux-grph" aria-selected="false">UI/UX Graph</a>
              </li>
              <!-- <li class="nav-item" role="presentation">
                <a class="nav-link" id="csf-tab" data-bs-toggle="tab" href="#csf" role="tab" aria-controls="csf" aria-selected="false">Client Satisfaction Feedbacks</a>
              </li>
              <li class="nav-item" role="presentation">
                <a class="nav-link" id="csf-grph-tab"  onclick="generate_csf_graph(0)" data-bs-toggle="tab" href="#csf-grph" role="tab" aria-controls="csf-grph" aria-selected="false">CSF Graph</a>
              </li> -->
            </ul>
            <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade show active" id="uiux" role="tabpanel" aria-labelledby="uiux">
                <div class="d-flex gap-2 mt-3">
                  <div class="mb-1">
                      <label for="date_from" class="form-label">Start Date</label>
                      <input type="date" id="date_from" class="form-control">
                  </div>
                  <div class="mb-1">
                      <label for="date_to" class="form-label">End Date</label>
                      <input type="date" id="date_to" class="form-control">
                  </div>
                  <div class="mb-1 d-flex flex-column justify-content-end">
                      <button class="btn btn-light border border-3" onclick="filter_uiux()">Apply Filter</button>
                  </div>
                  <div class="mb-1 d-flex flex-column justify-content-end">
                      <button class="btn btn-light" onclick="filter_uiux('clear')">Clear</button>
                  </div>
                </div>
                <div class="table-responsive mt-3">
                  <table class="table table-hover" id="uiux_table" width="100%" cellspacing="0">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Email</th>
                        <th>UI Rating</th>
                        <th>UI Suggestions</th>
                        <th>UX Rating</th>
                        <th>UX Suggestions</th>
                        <th>System</th>
                        <th>Feedback Date</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $c = 1;foreach ($uiux as $row): ?>
                      <!-- <?php $name = $this->Feedback_model->get_name($row->csf_user_id, $row->csf_system); ?> -->
                        <td><?php echo $c++; ?></td>
                        <td><?php echo $row->email; ?></td>
                        <td class="mt-0 pt-0">
                            <?php for($i = 0; $i < $row->csf_rate_ui; $i++): ?>
                              <span class="text-warning fs-5 star-icon">★</span>
                            <?php endfor ?>
                        </td>
                        <td><?php echo $row->csf_ui_suggestions;?></td>
                        <td class="mt-0 pt-0">
                            <?php for($i = 0; $i < $row->csf_rate_ux; $i++): ?>
                              <span class="text-warning fs-5 star-icon">★</span>
                            <?php endfor ?>
                        </td>
                        <td><?php echo $row->csf_ux_suggestions;?></td>
                        <td><?php echo $row->csf_system;?></td>
                        <td><?php echo date_format(new DateTime($row->csf_created_at), 'F j, Y g:i a'); ?></td>
                      </tr>
                      <?php endforeach;?>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="tab-pane fade" id="uiux-sex" role="tabpanel" aria-labelledby="uiux-sex">
                <div class="d-flex gap-2 mt-3">
                  <div class="mb-1">
                      <label for="date_from" class="form-label">Start Date</label>
                      <input type="date" id="date_from" class="form-control">
                  </div>
                  <div class="mb-1">
                      <label for="date_to" class="form-label">End Date</label>
                      <input type="date" id="date_to" class="form-control">
                  </div>
                  <div class="mb-1 d-flex flex-column justify-content-end">
                      <button class="btn btn-light border border-1" onclick="filter_uiux_sex()">Apply Filter</button>
                  </div>
                  <div class="mb-1 d-flex flex-column justify-content-end">
                      <button class="btn btn-light" onclick="filter_uiux_sex('clear')">Clear</button>
                  </div>
                </div>
                <div class="table-responsive mt-3">
                  <table class="table table-hover" id="uiux_sex_table" width="100%" cellspacing="0">
                    <thead>
                      <tr>
                        <th>Sex</th>
                        <th>Total</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $c = 1;foreach ($uiux_sex as $row): ?>
                        <td><?php echo $row->sex_label; ?></td>
                        <td><?php echo $row->total_count; ?></td>
                      </tr>
                      <?php endforeach;?>
                    </tbody>
                  </table>
                </div>
              </div>
              <!-- <div class="tab-pane fade" id="csf" role="tabpanel" aria-labelledby="csf-tab">
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
              </div> -->
              <div class="tab-pane fade" id="uiux-grph" role="tabpanel" aria-labelledby="uiux-grph-tab">
                <div class="row">
                  <div class="col">
                    <div class="mb-3"><canvas id="ui_bar_chart" height="100"></canvas></div>
                    <div><canvas id="ui_pie_chart" height="300"></canvas></div>
                    
                    <button class="btn btn-light btn-sm mt-5" id="downloadBtn"><span class="fas fa-download"></span> Download Graph</button>
                  </div>
                  <div class="col">
                    <div>
                      <canvas id="ux_bar_chart" height="100"></canvas>
                    </div>
                    <div class="mb-3">
                      <canvas id="ux_pie_chart" height="300"></canvas>
                    </div>
                  </div>
                  
                </div>
              </div>
              <!-- <div class="tab-pane fade" id="csf-grph" role="tabpanel" aria-labelledby="csf-grph-tab" >
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
              </div> -->
            </div>
          </div>
        </div>
      </div>
    </main>

    <script>
        document.getElementById('downloadBtn').addEventListener('click', () => {const charts = [
            document.getElementById('ui_bar_chart'),
            document.getElementById('ui_pie_chart'),
            document.getElementById('ux_bar_chart'),
            document.getElementById('ux_pie_chart')
        ];

        // Create a combined canvas
        const combinedCanvas = document.createElement('canvas');
        const ctx = combinedCanvas.getContext('2d');

        // Determine total height and max width for combined canvas
        const width = charts[0].width;
        const totalHeight = charts.reduce((sum, chart) => sum + chart.height, 0);

        combinedCanvas.width = width;
        combinedCanvas.height = totalHeight;

        // Draw each chart onto the combined canvas
        let yOffset = 0;
        charts.forEach(chart => {
            ctx.drawImage(chart, 0, yOffset);
            yOffset += chart.height;
        });

        // Download the combined canvas as an image
        const now = new Date();

        // Format the date and time as a string
        const formattedDateTime = `${now.getFullYear()}-${(now.getMonth() + 1)
        .toString()
        .padStart(2, '0')}-${now.getDate()
        .toString()
        .padStart(2, '0')} ${now.getHours()
        .toString()
        .padStart(2, '0')}:${now.getMinutes()
        .toString()
        .padStart(2, '0')}:${now.getSeconds().toString().padStart(2, '0')}`;

        const link = document.createElement('a');
        link.download = formattedDateTime + '_ui_ux_charts.jpg';
        link.href = combinedCanvas.toDataURL('image/jpeg');
        link.click();
        });
    </script>