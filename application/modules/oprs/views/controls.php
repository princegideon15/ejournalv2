<div id="layoutSidenav_content">
    <main>
		  <div class="container-fluid pt-3">
          <h3 class="fw-bold">Control Panel</h3>
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
        <!-- <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="javascript:void(0);">Control Panel</a></li>
          <li class="breadcrumb-item active"><?php echo $this->session->userdata('_oprs_type'); ?>  (<?php echo $this->session->userdata('_oprs_username'); ?>)</li>
        </ol> -->
        <div class="accordion">
          <div class="form-group">
            <div class="mb-3 w-25">
              <label>User group: </label>
              <select class="form-select" id="user_control">
                <option value="">Select</option>
                <option value="7">Admin</option>
                <option value="9">Publication Committee</option>
                <option value="3">Managing Editor</option>
                <option value="6">Manager</option>
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col">
            <div class="card mb-3 border border-dark">
              <div class="card-header fw-bold">
              <i class="fa fa-table"></i> Control Panel</div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-hover" id="controls_table" width="100%" cellspacing="0">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Username</th>
                        <th>Access</th>
                        <th>Privilege</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
              </div>
              <!-- <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div> -->
            </div>
          </div>
        </div>
      </div>
    </main>