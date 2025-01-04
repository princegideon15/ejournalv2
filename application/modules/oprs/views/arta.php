<div id="layoutSidenav_content">
    <main>
		  <div class="container-fluid pt-3">
        <!-- Breadcrumbs-->
        <!-- <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="javascript:void(0);">Feedbacks</a></li>
            <li class="breadcrumb-item active"><?php echo $this->session->userdata('_oprs_type'); ?>  (<?php echo $this->session->userdata('_oprs_username'); ?>)</li>
        </ol> -->
        <div class="card border border-dark">
          <div class="card-body">
            <h3 class="fw-bold">CSF ARTA</h3>
            <ul class="nav nav-tabs" id="myTab" role="tablist">
              <li class="nav-item" role="presentation">
                <a class="nav-link active" data-bs-toggle="tab" href="#arta-tab" role="tab" aria-controls="arta-tab" aria-selected="true">Respondents</a>
              </li>
              <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="tab" href="#arta-age-tab" role="tab" aria-controls="arta-age-tab" aria-selected="false">Respondents by Age</a>
              </li>
              <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="tab" href="#arta-reg-tab" role="tab" aria-controls="arta-reg-tab" aria-selected="false">CSF-ARTA by Region</a>
              </li>
              <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="tab" href="#arta-cc-tab" role="tab" aria-controls="arta-cc-tab" aria-selected="false">CSF-ARTA by Citizen Charter</a>
              </li>
              <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="tab" href="#arta-sqd-tab" role="tab" aria-controls="arta-sqd-tab" aria-selected="false">CSF-ARTA by SQD</a>
              </li>
            </ul>
            <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade show active" id="arta-tab" role="tabpanel" aria-labelledby="arta-tab">
                <div class="d-flex gap-2 mt-3">
                  <div class="mb-1">
                      <label for="date_from" class="form-label">Start Date</label>
                      <input type="date" id="date_from" class="form-control">
                  </div>
                  <div class="mb-1">
                      <label for="date_to" class="form-label">End Date</label>
                      <input type="date" id="date_to" class="form-control">
                  </div>
                  <div class="mb-1">
                      <label for="region" class="form-label">Region</label>
                      <select class="form-select" name="region" id="region">
                        <option value="">All</option>
                        <?php foreach($regions as $row): ?>
                        <option value="<?php echo $row->region_id; ?>"><?php echo $row->region_name; ?></option>
                        <?php endforeach ?>
                      </select>
                  </div>
                  <div class="mb-1">
                      <label for="customer_type" class="form-label">Customer Type</label>
                      <select class="form-select" name="customer_type" id="customer_type">
                        <option value="">All</option>
                        <?php foreach($client_type as $row): ?>
                        <option value="<?php echo $row->ctype_value; ?>"><?php echo $row->ctype_desc; ?></option>
                        <?php endforeach ?>
                      </select>
                  </div>
                  <div class="mb-1">
                      <label for="sex" class="form-label">Sex</label>
                      <select class="form-select" name="sex" id="sex">
                        <option value="">All</option>
                        <option value="1">Male</option>
                        <option value="2">Female</option>
                      </select>
                  </div>
                  <div class="mb-1 d-flex flex-column justify-content-end">
                      <button class="btn btn-light border border-1" onclick="filter_arta()">Go</button>
                  </div>
                </div>
                <div class="table-responsive mt-3">
                  <table class="table table-hover" id="arta_table" width="100%" cellspacing="0">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Age</th>
                        <th>Sex</th>
                        <th>Region</th>
                        <th>Agency</th>
                        <th>Service Availed</th>
                        <th>Customer Type</th>
                        <th>CC1</th>
                        <th>CC2</th>
                        <th>CC3</th>
                        <th>SQD1</th>
                        <th>SQD2</th>
                        <th>SQD3</th>
                        <th>SQD4</th>
                        <th>SQD5</th>
                        <th>SQD6</th>
                        <th>SQD7</th>
                        <th>SQD8</th>
                        <th>Suggestions</th>
                        <th>Date Submitted</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $c = 1;foreach ($arta as $row): ?>
                        <td><?php echo $c++; ?></td>
                        <td><?php echo $row->name; ?></td>
                        <td><?php echo $row->arta_age; ?></td>
                        <td><?php echo $row->sex_name; ?></td>
                        <td><?php echo $row->region_name;?></td>
                        <td><?php echo $row->arta_agency;?></td>
                        <td><?php echo $row->arta_service;?></td>
                        <td><?php echo $row->ctype_desc;?></td>
                        <td><?php echo $row->arta_cc1;?></td>
                        <td><?php echo $row->arta_cc2;?></td>
                        <td><?php echo $row->arta_cc3;?></td>
                        <td><?php echo $row->arta_sqd1;?></td>
                        <td><?php echo $row->arta_sqd2;?></td>
                        <td><?php echo $row->arta_sqd3;?></td>
                        <td><?php echo $row->arta_sqd4;?></td>
                        <td><?php echo $row->arta_sqd5;?></td>
                        <td><?php echo $row->arta_sqd6;?></td>
                        <td><?php echo $row->arta_sqd7;?></td>
                        <td><?php echo $row->arta_sqd8;?></td>
                        <td><?php echo $row->arta_suggestion;?></td>
                        <td><?php echo date_format(new DateTime($row->arta_created_at), 'F j, Y g:i a'); ?></td>
                      </tr>
                      <?php endforeach;?>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="tab-pane fade" id="arta-age-tab" role="tabpanel" aria-labelledby="arta-age-tab">
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
                      <button class="btn btn-light border border-1" onclick="filter_arta()">Go</button>
                  </div>
                </div>
                <div class="table-responsive mt-3">
                  <table class="table table-hover" id="arta_age_table" width="100%" cellspacing="0">
                    <thead>
                      <tr>
                        <th></th>
                        <th>Age Range</th>
                        <th>Male</th>
                        <th>Female</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $i = 0; $total_male = 0; $total_female = 0; foreach ($arta_age as $row): ?>
                        <?php $total_male += $row->male;?>
                        <?php $total_female += $row->female;?>
                      <tr>
                        <td><?php echo $i++; ?></td>
                        <td class="bg-light fw-bold"><?php echo ($row->age_range == '70-100') ? 'Above 70' : (($row->age_range == '1-19') ? 'Below 19' : $row->age_range); ?></td>
                        <td><?php echo ($row->male > 0) ? $row->male : '0'; ?></td>
                        <td><?php echo ($row->female > 0) ? $row->female : '0'; ?></td>
                      </tr>
                      <?php endforeach;?>
                      <tr>
                        <td><?php echo $i++; ?></td>
                        <td class="bg-light fw-bold">Total</td>
                        <td><?php echo $total_male; ?></td>
                        <td><?php echo $total_female;?></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="tab-pane fade" id="arta-reg-tab" role="tabpanel" aria-labelledby="arta-reg-tab">
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
                      <button class="btn btn-light border border-1" onclick="filter_arta()">Go</button>
                  </div>
                </div>
                <div class="table-responsive mt-3">
                  <table class="table table-hover" id="arta_reg_table" width="100%" cellspacing="0">
                    <thead>
                      <tr>
                        <th></th>
                        <th>Region</th>
                        <th>Male</th>
                        <th>Female</th>
                        <th>Total</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $i = 0; $total_male = 0; $total_female = 0; $total_per_region = 0; $total_region = 0;
                        foreach ($arta_reg as $row): ?>
                        <?php $total_male += $row->male;?>
                        <?php $total_female += $row->female;?>
                        <?php $total_per_region = $row->male + $row->female;?>
                        <?php $total_region += $total_per_region;?>
                      <tr>
                        <td><?php echo $i++; ?></td>
                        <td class="bg-light fw-bold"><?php echo $row->region_name ?></td>
                        <td><?php echo ($row->male > 0) ? $row->male : '0'; ?></td>
                        <td><?php echo ($row->female > 0) ? $row->female : '0'; ?></td>
                        <td><?php echo ($total_per_region > 0) ? $total_per_region : '0' ?></td>
                      </tr>
                      <?php endforeach;?>
                      <tr>
                        <td><?php echo $i++; ?></td>
                        <td class="bg-light fw-bold">Total</td>
                        <td><?php echo $total_male; ?></td>
                        <td><?php echo $total_female;?></td>
                        <td><?php echo $total_region;?></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="tab-pane fade" id="arta-cc-tab" role="tabpanel" aria-labelledby="arta-cc-tab">
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
                      <button class="btn btn-light border border-1" onclick="filter_arta()">Go</button>
                  </div>
                </div>
                <div class="table-responsive mt-3">
                  <table class="table table-hover" id="arta_cc_table" width="100%" cellspacing="0">
                    <thead>
                      <tr>
                        <!-- <th></th> -->
                        <th>Citizen Charter</th>
                        <th>1</th>
                        <th>2</th>
                        <th>3</th>
                        <th>4</th>
                        <th>5</th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php
                          foreach ($arta_cc as $row): ?>
                          <tr>
                            <td class="bg-light fw-bold"><?php echo $row->cc; ?></td>
                            <td><?php echo $row->c1; ?></td>
                            <td><?php echo $row->c2; ?></td>
                            <td><?php echo $row->c3; ?></td>
                            <td><?php echo $row->c4; ?></td>
                            <td><?php echo $row->c5; ?></td>
                          </tr>
                        <?php endforeach;?>

                    </tbody>
                  </table>
                </div>
              </div>
              <div class="tab-pane fade" id="arta-sqd-tab" role="tabpanel" aria-labelledby="arta-sqd-tab">
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
                      <button class="btn btn-light border border-1" onclick="filter_arta()">Go</button>
                  </div>
                </div>
                <div class="table-responsive mt-3">
                  <table class="table table-hover" id="arta_sqd_table" width="100%" cellspacing="0">
                    <thead>
                      <tr>
                        <th>SQD</th>
                        <th>strongly disagree<br>(1)</th>
                        <th>disagree<br>(2)</th>
                        <th>neither agree or disaggree<br>(3)</th>
                        <th>agree<br>(4)</th>
                        <th>strongly agree<br>(5)</th>
                        <th>N/A</th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php $sqd1 = 0; $sqd2 = 0; $sqd3 = 0; $sqd4 = 0; $sqd5 = 0; $sqdna = 0;
                          foreach ($arta_sqd as $row): ?>
                          <?php 
                            $sqd1 += $row->sqd1; 
                            $sqd2 += $row->sqd2; 
                            $sqd3 += $row->sqd3; 
                            $sqd4 += $row->sqd4; 
                            $sqd5 += $row->sqd5; 
                            $sqdna += $row->sqdna; 
                          ?>
                          <tr>
                            <td class="bg-light fw-bold"><?php echo $row->sqd; ?></td>
                            <td><?php echo $row->sqd1; ?></td>
                            <td><?php echo $row->sqd2; ?></td>
                            <td><?php echo $row->sqd3; ?></td>
                            <td><?php echo $row->sqd4; ?></td>
                            <td><?php echo $row->sqd5; ?></td>
                            <td><?php echo $row->sqdna; ?></td>
                          </tr>
                        <?php endforeach;?>
                        <tr>
                          <td>Total</td>
                          <td><?php echo $sqd1; ?></td>
                          <td><?php echo $sqd2; ?></td>
                          <td><?php echo $sqd3; ?></td>
                          <td><?php echo $sqd4; ?></td>
                          <td><?php echo $sqd5; ?></td>
                          <td><?php echo $sqdna; ?></td>
                        </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>