<div class="container-fluid"  style="padding-top:3.5em">
  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="javascript:void(0);">Activity Logs</a></li>
      <li class="breadcrumb-item active"><?php echo $this->session->userdata('_oprs_type'); ?>  (<?php echo $this->session->userdata('_oprs_username'); ?>)</li>
  </ol>

  <div class="card">
    <div class="card-header font-weight-bold">
      <i class="fa fa-table"></i> Activity Logs
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover" id="activity_logs_table" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>#</th>
              <th>User</th>
              <th>Action</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
            <?php $c = 1;foreach ($all_logs as $l): ?>
            <tr>
              <td><?php echo $c++; ?></td>
              <td><?php echo $this->User_model->get_user_name($l->log_user_id); ?></td>
              <td><?php echo $l->log_action; ?></td>
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
            <div class="form-group">
              <label>Upload CSV file format</label>
              <input type="file" class="form-control-file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" id="import_backup" name="import_backup">
            </div>
            <button class="btn btn-outline-secondary">Import Backup</button>
            <button type="button" class="btn btn-outline-danger"  data-toggle="modal" data-target="#clearLogsModal">Clear Logs</button>
          </form>
        </div>
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

<!-- Clear Logs Modal -->
<div class="modal fade" id="clearLogsModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Clear Logs</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Please check if you already created backup before proceeding.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" id="btn_clear_logs" class="btn btn-primary">Proceed</button>
      </div>
    </div>
  </div>
</div>