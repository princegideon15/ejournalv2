<div class="container-fluid"  style="padding-top:3.5em">
  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="javascript:void(0);">Email Notifications</a></li>
      <li class="breadcrumb-item active"><?php echo $this->session->userdata('_oprs_type'); ?>  (<?php echo $this->session->userdata('_oprs_username'); ?>)</li>
  </ol>
  <div class="card">
    <div class="card-header font-weight-bold">
      <i class="fas fa-envelope-open"></i> Email notifications
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover" id="email_contents_table" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>#</th>
              <th>Email subject</th>
              <th>Description</th>
              <th>CC</th>
              <th>BCC</th>
              <th>Last updated</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php $c = 1;foreach ($emails as $e): ?>
            <tr>
              <td><?php echo $c++; ?></td>
              <td  class="font-weight-bold"><?php echo $e->enc_subject; ?></td>
              <td><?php echo $e->enc_description; ?></td>
              <td class="small"><?php $cc = explode(",",$e->enc_cc);
                  foreach($cc as $email){
                    echo $email . '</br>';
                  } ?>
              </td>
              <td class="small"><?php $bcc = explode(",",$e->enc_bcc);
                  foreach($bcc as $email){
                    echo $email . '</br>';
                  } ?>
              </td>
              <!-- <td><?php echo $e->enc_user_group; ?></td> -->
              <td><?php echo date_format(new DateTime($e->last_updated), 'F j, Y g:i a'); ?></td>
              <td>
                <button type="button" class="btn btn-light text-primary btn-sm w-100"
                onclick="edit_email_content(<?php echo $e->enc_process_id;?>)"><span class="fa fa-edit"></span> Edit</button>
              </td>
            </tr>
            <?php endforeach;?>
          </tbody>
        </table>
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

<!-- Manage email notification contents -->
<div class="modal fade" id="emailContentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title font-weight-bold"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="email_content_form" name="email_content_form">
        <input type="hidden" id="enc_process_id" name="enc_process_id">
          <div class="row">
            <div class="col-5">
              <div class="form-group">
                <label class="font-weight-bold"  for="enc_subject">Email subject</label>
                <input type="text" class="form-control" id="enc_subject" name="enc_subject" required>
              </div>
              <div class="form-group">
                <label class="font-weight-bold"  for="enc_description">Notification trigger</label>
                <input type="text" class="form-control" id="enc_description" name="enc_description" required>
              </div>
              <div class="form-group">
                <label class="font-weight-bold" for="enc_cc">CC <small class="text-muted">(optional)</small></label>
                <input type="text" class="form-control" id="enc_cc" name="enc_cc" placeholder="juandelacruz@gmail.com,mariadelacruz@gmail.com">
                <small class="text-muted pt-2">Please separate emails by comma (,)</small>
              </div>
              <div class="form-group">
                <label class="font-weight-bold" for="enc_bcc">BCC <small class="text-muted">(optional)</small></label>
                <input type="text" class="form-control" id="enc_bcc" name="enc_bcc" placeholder="juandelacruz@gmail.com,mariadelacruz@gmail.com">
                <small class="text-muted pt-2">Please separate emails by comma (,)</small>
              </div>
              <div class="form-group enc_user_group">
                <label class="font-weight-bold" for="enc_user_group">User group
                <br/><small class="text-muted">Following user roles will also receive this email notification.</small>
                </label>
               
                <?php foreach($user_roles as $row): ?>
                  <div class="form-check"> 
                    <input class="form-check-input" id="enc_user_group<?php echo $row->role_id;?>" name="enc_user_group[]" value="<?php echo $row->role_id;?>" type="checkbox" > 
                    <label class="form-check-label" for="<?php echo $row->role_id;?>"> 
                     <?php echo $row->role_name;?> 
                    </label> 
                  </div>
                  <?php endforeach ?>
              </div>
            </div>
            <div class="col-7">
              <div class="form-group">
              <label class="font-weight-bold" for="enc_content">Email content</label>
                <div class="alert alert-warning" role="alert">
                  <span class="fas fa-exclamation-triangle"></span> Do not change or remove words with square brackets. [EXAMPLE]
                </div>
                <textarea type="text" id="enc_content" class="form-control"></textarea>
              </div>
              <div class="alert alert-warning pt-3" role="alert">
                <span class="fas fa-exclamation-triangle"></span> Do not change or remove words with square brackets. [EXAMPLE]
              </div>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" id="update_email_content_btn" class="btn btn-primary">Save changes</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!--/. Manage email notification contents -->

