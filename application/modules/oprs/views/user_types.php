<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid pt-3">
        <div class="card border border-dark">
          <!-- <div class="card-header font-weight-bold">
            <i class="fas fa-envelope-open"></i> User Types
          </div> -->
          <div class="card-body">
          <h3 class="fw-bold">User Types</h3>
            <div class="table-responsive">
              <table class="table table-hover" id="user_types_table" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Description</th>
                    <th>System Access</th>
                    <th>Status</th>
                    <th>Date created</th>
                    <th>Last updated</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $c = 1;foreach ($roles as $row): ?>
                    <?php $access = ($row->role_access == 1) ? 'eJournal' : (($row->role_access == 2) ? 'eReview' : 'eJournal/eReview'); ?>
                    <?php $status = ($row->role_status == 0) ? 'Disabled' : 'Enabled';?>
                  <tr>
                    <td><?php echo $c++; ?></td>
                    <td><?php echo $row->role_name; ?></td>
                    <td><?php echo $access; ?></td>
                    <td><?php echo $status; ?></td>
                    <td><?php echo ($row->created_at) ? date_format(new DateTime($row->created_at), 'F j, Y g:i a') : '-'; ?></td>
                    <td><?php echo ($row->last_updated) ? date_format(new DateTime($row->last_updated), 'F j, Y g:i a') : '-'; ?></td>
                    <td>
                      <button type="button" class="btn btn-light text-primary btn-sm w-100"
                      onclick="edit_user_type(<?php echo $row->row_id;?>)"><span class="fa fa-edit"></span> Edit</button>
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