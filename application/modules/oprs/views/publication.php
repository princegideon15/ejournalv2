<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid pt-3">
        <h3 class="fw-bold">Library</h3>
        <div class="card border border-dark">
          <div class="card-header">
          <i class="fas fa-book me-2"></i>Publication Types
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover" id="publication_types_table" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Date created</th>
                    <th>Last updated</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $c = 1;foreach ($publ_types as $row): ?>
                    <?php $status = ($row->publication_status == 0) ? 'Disabled' : 'Enabled';?>
                  <tr>
                    <td><?php echo $c++; ?></td>
                    <td><?php echo $row->publication_desc; ?></td>
                    <td><?php echo $status; ?></td>
                    <td><?php echo ($row->created_at) ? date_format(new DateTime($row->created_at), 'F j, Y g:i a') : '-'; ?></td>
                    <td><?php echo ($row->last_updated) ? date_format(new DateTime($row->last_updated), 'F j, Y g:i a') : '-'; ?></td>
                    <td>
                      <button type="button" class="btn btn-light text-primary btn-sm w-100"
                      onclick="edit_publication_type(<?php echo $row->id;?>)"><span class="fa fa-edit"></span> Edit</button>
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