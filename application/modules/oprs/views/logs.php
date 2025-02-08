<div id="layoutSidenav_content">
    <main>
		  <div class="container-fluid pt-3">
        <h3 class="fw-bold">Logs</h3>
        <!-- Breadcrumbs-->
        <!-- <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="javascript:void(0);">Activity Logs</a></li>
            <li class="breadcrumb-item active"><?php echo $this->session->userdata('_oprs_type'); ?>  (<?php echo $this->session->userdata('_oprs_username'); ?>)</li>
        </ol> -->
        <div class="card border border-dark">
          <div class="card-header">
            <i class="fa fa-table me-1"></i>Activity Logs
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover" id="activity_logs_table" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Activity</th>
                    <th>IP Address/Browser</th>
                    <th>Date</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $c = 1;foreach ($all_logs as $l): ?>
                    <?php $user = $this->User_model->get_user_name($l->log_user_id); ?>
                  <tr>
                    <td><?php echo $c++; ?></td>
                    <td><?php echo $user; ?></td>
                    <td><?php echo $user; ?></td>
                    <td><?php echo $l->log_action; ?></td>
                    <td><?php echo $l->log_ip . '/' . $l->log_browser; ?></td>
                    <td><?php echo date_format(new DateTime($l->date_created), 'F j, Y g:i a'); ?></td>
                  </tr>
                  <?php endforeach;?>
                </tbody>
              </table>
            </div>
          </div>
          <div class="card-footer">
            <div class="row">
              <div class="col-6">
                <form id="import_backup_form">
                  <p>Upload CSV file format</p>
                  <div class="input-group">
                      <input type="file" class="form-control" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" id="import_backup" name="import_backup">
                      <button class="btn btn-outline-secondary" type="submit" id="inputGroupFileAddon04">Import Backup</button>
                  </div>
                  <!-- <div class="form-group">
                    <label>Upload CSV file format</label>
                    <input type="file" class="form-control-file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" id="import_backup" name="import_backup">
                  </div>
                  <button class="btn btn-outline-secondary">Import Backup</button> -->
                  <div>
                    <button type="button" class="btn btn-outline-danger mt-3"  data-bs-toggle="modal" data-target="#clearLogsModal">Clear Logs</button>
                  </div>
                </form>
              </div>
            </div>

              
          
          </div>
        </div>
      </div>
    </main>