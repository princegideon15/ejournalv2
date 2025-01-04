<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid pt-3">
        <div class="card border border-dark">
          <div class="card-body">
          <h3 class="fw-bold"><?= $crit_name ?></h3>
            <div class="table-responsive">
              <table class="table table-hover" id="criteria_table" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Criteria Code</th>
                    <th>Description</th>
                    <?php echo $crit_cat == 2 ? '<th>Score</th>' : ''; ?>
                    <th>Date created</th>
                    <th>Last updated</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $c = 1;foreach ($criteria as $row): ?>
                    <?php //$status = ($row->publication_status == 0) ? 'Disabled' : 'Enabled';?>
                  <tr>
                    <td><?php echo $c++; ?></td>
                    <td><?php echo $row->code; ?></td>
                    <td><?php echo $row->desc; ?></td>
                    <?php echo $crit_cat == 2 ? '<td>' . $row->score . '</th></td>' : ''; ?>
                    <td><?php echo ($row->created_at) ? date_format(new DateTime($row->created_at), 'F j, Y g:i a') : '-'; ?></td>
                    <td><?php echo ($row->last_updated) ? date_format(new DateTime($row->last_updated), 'F j, Y g:i a') : '-'; ?></td>
                    <td>
                      <button type="button" class="btn btn-light text-primary btn-sm w-100"
                      onclick="edit_criteria(<?php echo $row->id;?>,<?php echo $crit_cat;?>)"><span class="fa fa-edit"></span> Edit</button>
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