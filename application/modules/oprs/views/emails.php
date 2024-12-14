<div id="layoutSidenav_content">
    <main>
		  <div class="container-fluid pt-3">
        <h3 class="fw-bold">Email Notifications</h3>
        <!-- Breadcrumbs-->
        <!-- <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="javascript:void(0);">Email Notifications</a></li>
            <li class="breadcrumb-item active"><?php echo $this->session->userdata('_oprs_type'); ?>  (<?php echo $this->session->userdata('_oprs_username'); ?>)</li>
        </ol> -->
        <div class="card border border-dark">
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
      </main>