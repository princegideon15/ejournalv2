<script type="text/javascript" >
var base_url = '<?php echo base_url(); ?>';
var prv_add = <?php echo (!empty($this->session->userdata('_prv_add'))) ? $this->session->userdata('_prv_add') : '0'; ?>;
var prv_edt = <?php echo (!empty($this->session->userdata('_prv_edt'))) ? $this->session->userdata('_prv_edt') : '0'; ?>;
var prv_del = <?php echo (!empty($this->session->userdata('_prv_del'))) ? $this->session->userdata('_prv_del') : '0'; ?>;
var prv_view = <?php echo (!empty($this->session->userdata('_prv_view'))) ? $this->session->userdata('_prv_view') : '0'; ?>;
var prv_exp = <?php echo (!empty($this->session->userdata('_prv_exp'))) ? $this->session->userdata('_prv_exp') : '0'; ?>;
</script>
<!-- chart -->
<script src="<?php echo base_url(); ?>assets/oprs/js/chart.js"></script>
<!-- Bootstrap core JavaScript-->
<script src="<?php echo base_url(); ?>assets/oprs/sbadmin/vendor/jquery/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>assets/oprs/sbadmin/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Core plugin JavaScript-->
<script src="<?php echo base_url(); ?>assets/oprs/sbadmin/vendor/jquery-easing/jquery.easing.min.js"></script>
<!-- Page level plugin JavaScript-->
<script src="<?php echo base_url(); ?>assets/oprs/sbadmin/vendor/datatables/jquery.dataTables.js"></script>
<script src="<?php echo base_url(); ?>assets/oprs/sbadmin/vendor/datatables/dataTables.bootstrap4.js"></script>
<!-- Custom scripts for all pages-->
<script src="<?php echo base_url(); ?>assets/oprs/sbadmin/js/sb-admin.min.js"></script>
<!-- Main jquery-->
<script src="<?php echo base_url(); ?>assets/oprs/js/oprs.js"></script>
<!-- Jquery Validate-->
<script src="<?php echo base_url(); ?>assets/oprs/js/jquery.validate.min.js"></script>
<!-- Jquery Validate Additional-->
<script src="<?php echo base_url(); ?>assets/oprs/js/additional-methods.min.js"></script>
<!-- Jquery Validate File-->
<script src="<?php echo base_url(); ?>assets/oprs/js/jquery.validate.file.js"></script>
<!-- Editable dropdown-->
<script src="<?php echo base_url(); ?>assets/oprs/js/jquery-editable-select.min.js"></script>
<!-- Bootstrap notify-->
<script src="<?php echo base_url(); ?>assets/oprs/js/bootstrap-notify.js"></script>
<!-- Bootstrap datepicker-->
<script src="<?php echo base_url(); ?>assets/oprs/js/bootstrap-datetimepicker.min.js"></script>
<!-- Autocomplete-->
<script src="<?php echo base_url(); ?>assets/oprs/js/sh-autocomplete.min.js"></script>
<!-- Moment-->
<script src="<?php echo base_url(); ?>assets/oprs/js/moment.min.js">moment().format();moment().tz("Asia/Manila").format();</script>
<!-- Text Editor TinyMCE -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.9.2/tinymce.min.js"></script>
<!-- Loading Screen -->
<script type="text/javascript" src="<?php echo base_url("assets/oprs/js/jquery.loading.admin.js"); ?>"></script>
<!-- Datatable buttons -->
<script type="text/javascript" src="<?php echo base_url("assets/oprs/js/dataTables.buttons.min.js"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/oprs/js/buttons.flash.min.js"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/oprs/js/jszip.min.js"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/oprs/js/pdfmake.min.js"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/oprs/js/vfs_fonts.js"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/oprs/js/buttons.html5.min.js"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/oprs/js/buttons.print.min.js"); ?>"></script>

</body>
</html>