<div id="layoutSidenav_content">
    <main>
		  <div class="container-fluid pt-3">
        <h3 class="fw-bold">
          Library
        </h3>
        <!-- Breadcrumbs-->
        <!-- <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="javascript:void(0);">Email Notifications</a></li>
            <li class="breadcrumb-item active"><?php echo $this->session->userdata('_oprs_type'); ?>  (<?php echo $this->session->userdata('_oprs_username'); ?>)</li>
        </ol> -->
        <div class="card border border-dark">
          <div class="card-header">
          <i class="fas fa-clock me-1"></i>Process Time Duration
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover" id="process_duration_table" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Email subject</th>
                    <th>Description</th>
                    <th>Process Owner</th>
                    <th>Target User</th>
                    <th width="12%">Duration</th>
                    <th>Actions</th>
                  </tr>
                </thead>
              <tbody>
                  <?php $c = 1;foreach ($emails as $e): ?>
                  <tr>
                    <td><?php echo $c++; ?></td>
                    <td  class="font-weight-bold"><?php echo $e->enc_subject; ?></td>
                    <td><?php echo $e->enc_description; ?></td>
                    <td><?php echo $e->processor ?? 'Auto'; ?></td>
                    <td><?php echo ($e->enc_target_user == 7) ? 'Cluster Editors' : (($e->enc_target_user == 0) ? 'Auto' : $e->target ) ?></td> 
                    <td>
                      <?php if($e->processor){ ?>
                        <div class="input-group mb-3">
                          <input type="number" class="form-control duration" value="<?php echo $e->enc_process_duration; ?>" min="0" max="365">
                          <span class="input-group-text" id="basic-addon2">Days</span>
                        </div>
                      <?php }else{ ?>
                      N/a
                      <?php } ?>
                    </td> 
                    <td>
                      
                    <?php if($e->processor){ ?>
                      <button type="button" class="btn btn-light text-primary w-100"
                      onclick="update_process_time_duration(this, <?php echo $e->enc_process_id;?>)" rel="tooltip"
                      data-bs-placement="top" title="Apply Changes" ><span class="fa fa-check"></span></button>
                      <?php }?>
                    </td>
                  </tr>
                  <?php endforeach;?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      </main>