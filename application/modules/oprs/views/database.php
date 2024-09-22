<div class="container-fluid" style="margin-top:3.5em">
  <?php if ($this->session->flashdata('_oprs_usr_message')) {
$msg = $this->session->flashdata('_oprs_usr_message');
$message = $msg['msg'];
$class = $msg['class'];
$icon = $msg['icon'];?>
<div class="alert <?php echo $class; ?> alert-dismissible fade show" role="alert">
  <strong><span class="<?php echo $icon; ?>"></span> <?php echo $message; ?></strong>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
  <span aria-hidden="true">&times;</span>
  </button>
</div>
<?php
}?>

  <!-- BREADCRUMBS -->
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="javascript:void(0);">Control Panel</a></li>
      <li class="breadcrumb-item active"><?php echo $this->session->userdata('_oprs_type'); ?>  (<?php echo $this->session->userdata('_oprs_username'); ?>)</li>
  </ol>
  <!-- /.BREADCRUMBS -->
  <div class="row">
    <div class="col">
      <div class="card mb-3">
        <div class="card-header font-weight-bold">
        <i class="fas fa-database"></i> Backup/Restore Database</div>
        
      <div class="card">
          <div class="card-header">
              Backup Database
          </div>
          <div class="card-body">
              <form id="export_db_form" action="<?php echo site_url('oprs/backup/export');?>" method="POST">
              
              <strong>Export method:</strong>
              <hr/>
              <div class="form-check">
                  <input class="form-check-input" type="radio" name="export_method" id="quick_export" value="1" checked>
                  <label class="form-check-label" for="quick_export">
                      Quick - Create backup of the database
                  </label>
              </div>
              <div class="form-check mt-1 mb-3">
                  <input class="form-check-input" type="radio" name="export_method" id="custom_export" value="2">
                  <label class="form-check-label" for="custom_export">
                      Custom - Select specific table to backup
                  </label>
              </div>
              <!-- <strong>Format:</strong>
              <hr/>
              <select class="form-control w-25 form-control-sm" id="export_format" name="export_format">
                  <option value="sql">SQL</option>
                  <option value="csv">CSV</option>
              </select> -->
              <table id="sd_table" class="table table-striped mt-3 table-sm table-bordered w-50">
                      <thead>
                          <tr>
                          <th scope="col">Table</th>
                          <th scope="col">Structure</th>
                          <th scope="col">Data</th>
                          </tr>
                      </thead>
                      <tbody>
                      <tr class="table-warning"><td>Select all</td>
                          <td>
                              <div class="form-check">
                                  <input class="form-check-input" type="checkbox" value="1" id="select_all_structure" name="select_all_structure">
                                  <label class="form-check-label" for="defaultCheck1">
                                  </label>
                              </div>
                          </td>
                          <td>
                              <div class="form-check">
                                  <input class="form-check-input" type="checkbox" value="1" id="select_all_data" name="select_all_data">
                                  <label class="form-check-label" for="defaultCheck1">
                                  </label>
                              </div>
                          </td>
                      </tr>
                  <?php  foreach($tables as $table){

                  echo '<tr> 
                            <td>' . $table . '</td> 
                            <td>
                              <div class="form-check">
                                  <input class="form-check-input" type="checkbox" name="table_structure[]" value="'. $table .'" id="defaultCheck1">
                                  <label class="form-check-label" for="defaultCheck1">
                                  </label>
                              </div>
                            </td> 
                            <td>
                              <div class="form-check">
                                  <input class="form-check-input" type="checkbox" name="table_data[]" value="'. $table .'" id="defaultCheck1">
                                  <label class="form-check-label" for="defaultCheck1">
                                  </label>
                              </div>
                            </td> 
                        </tr>';
                    }?>
                      </tbody>
                  </table>
                  <div class="mt-3">
                    <button type="submit" id="export_button" class="btn btn-dark">Go</button>
                  </div>
                  </form>
          </div>
      </div>
      <div class="card mt-3">
          <div class="card-header">
              Import Backup
          </div>
          <div class="card-body">
              <form id="import_db_form" method="POST" enctype="multipart/form-data">
              
                  <div class="input-group is-invalid w-50">
                      <div class="custom-file">
                      <input type="file" class="custom-file-input" id="import_file" name="import_file">
                      <label class="custom-file-label" for="import_file">Choose file...</label>
                      </div>
                      <div class="input-group-append">
                      <button type="submit" class="btn btn-dark" >Go</button>
                      </div>
                  </div>
                  <div class="invalid-feedback">
                  </div>
              </form>

              
      <div id="success_import" class="mt-3 w-50"></div>
          </div>
      </div>
    </div>
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
