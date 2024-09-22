<?php $this->Log_model->see_all(); ?>
<div class="container-fluid"  style="padding-top:3.5em">
  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="javascript:void(0);">Notifications</a></li>
      <li class="breadcrumb-item active"><?php echo $this->session->userdata('_oprs_type'); ?>  (<?php echo $this->session->userdata('_oprs_username'); ?>)</li>
    </ol>
    <ul class="list-group list-group-flush notifications">
    <?php foreach ($all_logs as $l): ?>
        <?php $bg = ($l->notif_open == 0) ? 'bg-info' : '';
            $tc = ($l->notif_open == 0) ? 'text-white' : 'text-dark'; ?>
        <a href="javascript:void(0);" onclick="open_notif('<?php echo $l->man_title;?>','<?php echo $l->row_id;?>');" class="list-group-item list-group-item-action p-3 <?php echo $bg . ' ' . $tc;?>">
        <strong><?php echo $l->usr_username; ?></strong> <?php echo $l->log_action;?><strong> <?php echo $l->man_title;?></strong>
        <small class="d-flex mt-1"><?php echo date_format(new DateTime($l->date_created), 'F j, Y, g:i a');?></small></a>
    <?php endforeach;?>

    </ul>
           
  </div>