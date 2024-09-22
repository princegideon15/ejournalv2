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
  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="javascript:void(0);">Users</a></li>
      <li class="breadcrumb-item active"><?php echo $this->session->userdata('_oprs_type'); ?>  (<?php echo $this->session->userdata('_oprs_username'); ?>)</li>
    </ol>
    <div class="row">
      <div class="col-3">
        <!-- ADD USER -->
        <div class="card mb-3">
          <div class="card-header font-weight-bold">
          <i class="fa fa-user-plus"></i> Add User</div>
          <div class="card-body">
            <form id="form_add_user">
              <div class="form-group">
                <label for="usr_email" class="font-weight-bold">Email</label>
                <input type="email" class="form-control" id="usr_username" name="usr_username" placeholder="Your email address">
              </div>
              <div class="form-group">
                <label for="usr_password" class="font-weight-bold">Password</label>
                <input type="password" class="form-control" id="usr_password" name="usr_password" placeholder="Your password">
              </div>
              <div class="form-group">
                <label for="usr_rep_password" class="font-weight-bold">Repeat Password</label>
                <input type="password" class="form-control" id="usr_rep_password" name="usr_rep_password" placeholder="Repeat your password">
              </div>
              <div class="form-group">
                <label for="usr_contact" class="font-weight-bold">Contact</label>
                <input type="text" class="form-control" id="usr_contact" name="usr_contact" placeholder="Your contact number">
              </div>
              <div class="form-group">
                <label for="usr_sys_acc" class="font-weight-bold">Module Access</label>
                <select id="usr_sys_acc" name="usr_sys_acc" class="form-control">
                  <option value="" selected>Select Module Access</option>
                  <option value='1'>eJournal only</option>
                  <option value='2'>OPRS only</option>
                  <option value='3'>eJournal/OPRS</option>
                </select>
              </div>
              <div class="form-group">
                <label for="usr_role" class="font-weight-bold">User Role</label>
                <select id="usr_role" name="usr_role" class="form-control" disabled="disabled">
                  <option value="" selected>Select User Role</option>
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
        <div class="card mb-3">
          <div class="card-header font-weight-bold">
          <i class="fa fa-table"></i> User Accounts</div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered table-hover table-striped" id="dataTable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th></th>
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
                                : (($u->usr_sys_acc == 2) ? 'OPRS' : 'eJournal | OPRS'));?>
                  
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
                    <td><span class="badge badge-<?php echo $sys_class; ?>"><?php echo $sys; ?></td>
                    <td><span class="badge badge-<?php echo $class; ?>"><?php echo $status; ?></td>
                    <td>
                      <button type="button" class="btn btn-light text-primary btn-sm" onclick="edit_user('<?php echo $u->usr_id; ?>')" rel="tooltip" data-placement="top" title="Update/Remove User Info"><span class="fa fa-edit"></span> Edit</button>
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
  <!-- /.USER ACCOUNT -->
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

<!-- LOGOUT -->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
        <a class="btn btn-primary" href="<?php echo base_url('oprs/login/logout'); ?>">Logout</a>
      </div>
    </div>
  </div>
</div>
<!-- /.LOGOUT -->

<!-- UPDATE USER -->
<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" >
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Info</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form_edit_user">
          <div class="form-group">
            <label for="usr_email">Email</label>
            <input type="email" class="form-control" id="usr_username" name="usr_username" placeholder="Your email address">
          </div>
          <!-- <div class="form-group">
            <label for="usr_username">Username</label>
            <input type="text" class="form-control" id="usr_username" name="usr_username" placeholder="Your username">
          </div> -->
          <div class="form-group">
            <label for="usr_password">New Password (If any)</label>
            <input type="password" class="form-control" id="usr_password" name="usr_password" placeholder="Your password">
          </div>
          <div class="form-group">
            <label for="usr_rep_password">Repeat New Password</label>
            <input type="password" class="form-control" id="usr_rep_password" name="usr_rep_password" placeholder="Repeat your password">
          </div>
          <div class="form-group">
            <label for="usr_contact">Contact</label>
            <input type="text" class="form-control" id="usr_contact" name="usr_contact" placeholder="Your contact number">
          </div>
          <div class="form-group">
            <label for="usr_sys_acc">Module Access</label>
            <select id="usr_sys_acc" name="usr_sys_acc" class="form-control">
              <option value="" selected>Select Module Access</option>
              <option value='1'>eJournal only</option>
              <option value='2'>OPRS only</option>
              <option value='3'>eJournal/OPRS</option>
            </select>
          </div>
          <div class="form-group">
            <label for="usr_role">User Role</label>
            <select id="usr_role" name="usr_role" class="form-control">
              <!-- <option value="" selected>Select User Role</option> -->
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger mr-auto deactivate " style="display:none;"  onclick="act_deact_modal(2);">Deactivate Account</button>
          <button type="button" class="btn btn-success mr-auto activate " style="display:none;" onclick="act_deact_modal(0);">Activate Account</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- /.UPDATE USER -->

<!-- REVIEW INPUT -->
<div class="modal fade" id="confirmDeactivationModal" tabindex="-1" role="dialog" aria-hidden="true" style="z-index:999999">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Are you sure?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" onclick="activate_deactivate_user();">Confirm</button>
      </div>
    </div>
  </div>
</div>
<!-- /.REVIEW INPUT -->