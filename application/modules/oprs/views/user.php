<div id="layoutSidenav_content">
    <main>
		  <div class="container-fluid pt-3">
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

        <!-- Breadcrumbs-->
        <!-- <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="javascript:void(0);">Users</a></li>
          <li class="breadcrumb-item active"><?php echo $this->session->userdata('_oprs_type'); ?>  (<?php echo $this->session->userdata('_oprs_username'); ?>)</li>
        </ol> -->
        <div class="row">
          <div class="col-3">
            <!-- ADD USER -->
            <div class="card mb-3 border border-dark">
              <div class="card-header fw-bold">
              <i class="fa fa-user-plus"></i> Add User</div>
              <div class="card-body">
                <form id="form_add_user">
                  <div class="mb-3">
                    <label for="usr_full_name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="usr_full_name" name="usr_full_name" placeholder="First name, Middle name, Last name">
                  </div>
                  <div class="mb-3">
                    <label for="usr_username" class="form-label">Email</label>
                    <input type="email" class="form-control" id="usr_username" name="usr_username" placeholder="Your email address">
                  </div>
                  <div class="mb-3">
                    <label for="usr_password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="usr_password" name="usr_password" placeholder="Your password">
                  </div>
                  <div class="card mb-3 d-none" id="password_strength_container">
                      <div class="card-body text-secondary">
                          <div><span class="me-1 fs-6">Password strength:</span><span class="fw-bold" id="password-strength"></span></div>
                          <div class="progress mt-1" style="height: .5rem;">
                              <div class="progress-bar" role="progressbar"  id="password-strength-bar" aria-label="Success example" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                          </div>
                          <ul class="mt-3 small text-muted ps-3">
                              <li>8-20 characters long.</li>
                              <li>At least 1 letter.</li>
                              <li>At lestt 1 number.</li>
                              <li>At least 1 special character.</li>
                          </ul>
                      </div>
                  </div>
                  <div class="mb-3">
                    <label for="usr_rep_password" class="form-label">Repeat Password</label>
                    <input type="password" class="form-control" id="usr_rep_password" name="usr_rep_password" placeholder="Repeat your password">
                  </div>
                  <div class="mb-3">
                    <label for="usr_contact" class="form-label">Contact</label>
                    <input type="text" class="form-control" id="usr_contact" name="usr_contact" placeholder="Your contact number">
                  </div>
                  <div class="mb-3">
                    <label for="usr_sex" class="form-label">Sex</label>
                    <select id="usr_sex" name="usr_sex" class="form-control">
                      <option value="" selected>Select Sex</option>
                      <option value='1'>Male</option>
                      <option value='2'>Female</option>
                    </select>
                  </div>
                  <!-- <div class="mb-3">
                    <label for="usr_sys_acc" class="form-label">System Access</label>
                    <select id="usr_sys_acc" name="usr_sys_acc" class="form-control">
                      <option value="" selected>Select System Access</option>
                      <option value='1'>eJournal</option>
                      <option value='2'>eReview</option>
                      <option value='3'>eJournal/eReview</option>
                    </select>
                  </div> -->
                  <div class="mb-3">
                    <label for="usr_role" class="form-label">User Role</label>
                    <select id="usr_role" name="usr_role" class="form-select">
                      <option value="" selected>Select User Role</option>
                      <?php foreach($user_types as $row): ?>
                        <option value="<?= $row->role_id ?>"><?= $row->role_name ?></option>
                      <?php endforeach ?>
                    </select>
                  </div>
                  <button type="submit" class="btn btn-primary ">Save</button>
                </form>
              </div>
            </div>
          </div>
          <!-- /.ADD USER -->
          <!-- USER ACCOUNT -->
          <div class="col-9">
            <div class="card mb-3 border border-dark">
              <div class="card-header fw-bold">
              <i class="fa fa-table"></i> User Accounts</div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Username</th>
                        <th>User Role</th>
                        <th>Access</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($users as $u): ?>
                      <?php $status = (($u->usr_status == 1) ? 'Online' 
                                      : ((($u->usr_status == 0) ? 'Offline' 
                                      :	'Deactivated')));?>
                                      
                      <?php $class = (($u->usr_status == 1) ? 'success' 
                                    : ((($u->usr_status == 0) ? 'secondary' 
                                    : 'danger')));?>

                      <?php $sys = (($u->usr_sys_acc == 1) ? 'eJournal' 
                                    : (($u->usr_sys_acc == 2) ? 'eReview' : 'eJournal | eReview'));?>
                      
                      <?php $sys_class = (($u->usr_sys_acc == 1) ? 'primary' 
                                    : (($u->usr_sys_acc == 2) ? 'success' : 'dark'));?>
                      <tr>
                        <td class="text-center"><img class="img-fluid" src="<?php echo base_url(); ?>/assets/oprs/img/default-avatar.png" width="50px" /></td>
                        <td>
                          <div class="media-body">
                            <h6 class="mt-0"><?php echo $u->usr_username; ?>
                            <br/><small class="text-primary" style="font-size:10px;"><?php echo 'ID : ' . $u->usr_id; ?></small>
                            </h6>
                            <small class="text-muted">Last active: <?php echo $u->usr_logout_time; ?></small>
                          </div>
                        </td>
                        <td><?php echo $u->role_name; ?></td>
                        <td><span class="badge text-bg-<?php echo $sys_class; ?>"><?php echo $sys; ?></td>
                        <td><span class="badge text-bg-<?php echo $class; ?>"><?php echo $status; ?></td>
                        <td>
                          <button type="button" class="btn btn-light text-primary btn-sm" onclick="edit_user('<?php echo $u->usr_id; ?>')" rel="tooltip" data-bs-placement="top" title="Update/Remove User Info"><span class="fa fa-edit"></span> Edit</button>
                        </td>
                      </tr>
                      <?php endforeach;?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
       </div>
    </main>
