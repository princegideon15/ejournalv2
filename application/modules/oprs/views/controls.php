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
  <div class="accordion">
    <div class="form-group">
      <div class="form-group w-25">
        <label>User group: </label>
        <select class="form-control" id="user_control">
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
      <div class="card mb-3">
        <div class="card-header font-weight-bold">
        <i class="fa fa-table"></i> Control Panel</div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" id="controls_table" width="100%" cellspacing="0">
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
            <label for="usr_sys_acc">System Access</label>
            <select id="usr_sys_acc" name="usr_sys_acc" class="form-control">
              <option value="" selected>Select System Access</option>
              <option value='1'>OPRS</option>
              <option value='2'>eJournal</option>
            </select>
          </div>
          <div class="form-group">
            <label for="usr_role">User Role</label>
            <select id="usr_role" name="usr_role" class="form-control">
              <option value="" selected>Select User Role</option>
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