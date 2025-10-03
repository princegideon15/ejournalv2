<div id="layoutSidenav_content">
    <main>
		  <div class="container-fluid pt-3">
          <h3 class="fw-bold">Settings</h3>
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
          </div>
        </div>
        <div class="row">
          <div class="col">
            <div class="card mb-3 border border-dark">
              <div class="card-header">
              <i class="fas fa-fw fa-cogs me-1"></i>Menu Access Control Panel</div>
              <div class="card-body">
                
              <div class="mb-3 w-25">
                <label class="form-label">Select User Type </label>
                <select class="form-select" id="user_control">
                  <option value="0">All</option>
                  <?php foreach($user_types as $row): ?>
                    <?php if($row->role_id != 1 && $row->role_id != 16){ ?>
                    <option value="<?= $row->role_id ?>"><?= $row->role_name ?></option>
                    <?php } ?>
                  <?php endforeach ?>
                </select>
              </div>

                <div class="table-responsive">
                  <table class="table table-hover" id="controls_table" width="100%" cellspacing="0">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>System Access</th>
                        <th>Module Access</th>
                        <th>Table Access Privilege</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $i=1;foreach($privileges as $row):?>
                        <tr>
                          <td><?= $i++ ?></td>
                          <td><?= $row->usr_username ?></td>
                          <td><?= $row->usr_desc ?></td>
                          <td><?= ($row->usr_sys_acc == 1) ? '<span class="badge rounded-pill bg-primary">eJournal</span>' : (($row->usr_sys_acc == 2) ? '<span class="badge rounded-pill bg-dark">eReview</span>' : '<span class="badge rounded-pill bg-primary">eJournal</span> <span class="badge rounded-pill bg-dark">eReview</span>') ?></td>
                          <td>
                            <?php if($row->usr_sys_acc > 1){ ?>
                            <p class="fst-italic mb-1 text-decoration-underline" style="font-size: 10px;">*For eReview only</p>
                              <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" name='acc_dashboard' value='<?= $row->usr_id ?>' <?= $row->acc_dashboard == 1 ? 'checked' : '' ?>>
                                <label class="form-check-label" for="flexSwitchCheckDefault">Dashboard Cards</label>
                              </div>
                              <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" name='acc_reports' value='<?= $row->usr_id ?>' <?= $row->acc_reports == 1 ? 'checked' : '' ?>>
                                <label class="form-check-label" for="flexSwitchCheckDefault">Reports & Statistics</label>
                              </div>
                              <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" name='acc_user_mgt' value='<?= $row->usr_id ?>' <?= $row->acc_user_mgt == 1 ? 'checked' : '' ?>>
                                <label class="form-check-label" for="flexSwitchCheckDefault">User Management</label>
                              </div>
                              <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" name='acc_lib' value='<?= $row->usr_id ?>' <?= $row->acc_lib == 1 ? 'checked' : '' ?>>
                                <label class="form-check-label" for="flexSwitchCheckDefault">Libraries</label>
                              </div>
                              <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" name='acc_settings' value='<?= $row->usr_id ?>' <?= $row->acc_settings == 1 ? 'checked' : '' ?>>
                                <label class="form-check-label" for="flexSwitchCheckDefault">Settings</label>
                              </div>
                              <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" name='acc_feedbacks' value='<?= $row->usr_id ?>' <?= $row->acc_feedbacks == 1 ? 'checked' : '' ?>>
                                <label class="form-check-label" for="flexSwitchCheckDefault">Feedbacks</label></label>
                              </div>
                              <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" name='acc_logs' value='<?= $row->usr_id ?>' <?= $row->acc_logs == 1 ? 'checked' : '' ?>>
                                <label class="form-check-label" for="flexSwitchCheckDefault">Logs</label>
                              </div>
                            <?php } else { echo 'N/a'; } ?>
                          </td>
                          <td>
                            <div class='form-check'>
                                <input class='form-check-input' type='checkbox' name='prv_add[]' value='<?= $row->usr_id ?>' <?= $row->prv_add == 1 ? 'checked' : '' ?> >
                                <label class='form-check-label mt-2'>Add</label>
                            </div>
                            <div class='form-check'>
                                <input class='form-check-input' type='checkbox' name='prv_edit[]' value='<?= $row->usr_id ?>' <?= $row->prv_edit == 1 ? 'checked' : '' ?> >
                                <label class='form-check-label mt-2'>Edit</label>
                            </div>
                            <div class='form-check'>
                                <input class='form-check-input' type='checkbox' name='prv_delete[]' value='<?= $row->usr_id ?>' <?= $row->prv_delete == 1 ? 'checked' : '' ?> >
                                <label class='form-check-label mt-2'>Delete</label>
                            </div>
                            <div class='form-check'>
                                <input class='form-check-input' type='checkbox' name='prv_view[]' value='<?= $row->usr_id ?>' <?= $row->prv_view == 1 ? 'checked' : '' ?> >
                                <label class='form-check-label mt-2'>View</label>
                            </div>
                            <div class='form-check'>
                                <input class='form-check-input' type='checkbox' name='prv_export[]' value='<?= $row->usr_id ?>' <?= $row->prv_export == 1 ? 'checked' : '' ?> >
                                <label class='form-check-label mt-2'>Export</label>
                            </div>
                          </td>
                        </tr>
                        <?php endforeach ?>
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