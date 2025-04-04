var mems = [];
var mem_mail = [];
var mem_num = [];
var mem_spec = [];
var mem_id = [];
var mem_exp = [];
var mem_aff = [];
var mem_prf = [];
var acoa = [];
var man_id;
var maxx;
var r;
var revInterval;
var user_id;
var user_status;
var array_prf = [];
var manuscripts = [];
var revs = [];
var revIncr = 1;
var session_count = 0;
var remove_man_id;
var mail_content;
var mail_title = '';
var editor_mail_content;

$(document).ready(function() {

    // get members info
    $.ajax({
        type: "GET",
        url: base_url + "oprs/manuscripts/members/",
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            $.each(data, function(key, val) {
                mems.push(val.pp_first_name + ' ' + val.pp_middle_name + ' ' + val.pp_last_name);
                mem_mail.push(val.pp_email);
                mem_num.push(val.pp_contact);
                mem_spec.push(val.mpr_gen_specialization);
                mem_id.push(val.pp_usr_id);
                mem_exp.push(val.pp_first_name + ' ' + val.pp_middle_name + ' ' + val.pp_last_name + ' (' + val.mpr_gen_specialization + ')');
                mem_aff.push(val.bus_name);
                mem_prf.push(val.title_name);
            });

        }
    });

    // get email content for add reviewer
    $.ajax({    
        type: "GET",
        url: base_url + "oprs/emails/get_email_content/"+2,
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            $.each(data, function(key, val) {
                mail_content = val.enc_content;
            });
        }
    });

     // get email content for add editor
     $.ajax({    
        type: "GET",
        url: base_url + "oprs/emails/get_email_content/"+10,
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            $.each(data, function(key, val) {
                editor_mail_content = val.enc_content;
            });
        }
    });

    tinymce.init({
        selector: '#tiny_mail1',
        forced_root_block : false,
        height : "750"
    });

    tinymce.init({
        selector: '#editor_tiny_mail1',
        forced_root_block : false,
        height : "750"
    });

    tinymce.init({
        selector: '#enc_content',
        forced_root_block : false,
        height : "400"
    });

    // get notifications and count
    notifications();

    // validate if notifications opened already
    var notif_data = localStorage.getItem('notif_data');

    if(notif_data)
    {
        $( '#dataTable' ).DataTable().search(notif_data).draw();
        localStorage.removeItem('notif_data');
    }
    
    $('#alertsDropdown').click(function(e)
    {
        $('.oprs_notif span').remove();
        var user_id = $('.cookie_id').text();
        localStorage.setItem('notif_open_date_' + user_id, moment().format('MMMM DD YYYY hh:mm:ss'));
        localStorage.setItem('notif_open_id_' + user_id, $('.cookie_id').text());


        $('.oprs_notif_list').empty();

        $.ajax({
            type: "GET",
            url: base_url + "oprs/notifications/notif_tracker/",
            dataType: "json",
            crossDomain: true,
            success: function(data) {  
                // console.log(data);
                var html = '<div class="list-group" style="font-size:14px"> \
                <a class="list-group-item font-weight-bold pl-3 pb-1 pt-1 h4">Notifications</a>';
                       $.each(data, function(key, val)
                       {
                            // var proc = get_member(val.trk_processor);
                            var bg = (val.notif_open == 0) ? 'bg-info' : '';
                            var tc = (val.notif_open == 0) ? 'text-white' : 'text-dark';
                            var proc_id = val.trk_processor;
                            // var name = (proc == undefined) ? val.usr_username : proc;
                            var name = val.user_name;
                        
               
                            if (proc_id.indexOf('R') != -1 || val.trk_source == '_sk_r') {
                                var desc = 'Reviewed Manuscript';
                            } else {
                                if (val.trk_source == '_sk' || val.trk_source == '_op' || val.trk_source == '_me') {
                                    var desc = (val.trk_remarks == null) ? 'Submitted initial manuscript for review' : 'Submitted final manuscript'; 
                                } else {
                                    var desc = 'Processed Manuscript';
                                }
                            }

                            html += '<a href="javascript:void(0);" onclick="open_notif(\'' + val.man_title + '\',\'' + val.row_id + '\');" \
                            class="list-group-item list-group-item-action p-3 '+ bg + ' ' + tc +'"> \
                            <strong>' + name + '</strong> ' + desc + ' <strong> \
                            ' + val.man_title + '</strong>\
                            <small class="d-flex mt-1">'+ moment(val.trk_process_datetime).fromNow() + '</small></a>';

                       });
            
                       html += '</div>';
            
                       $('.oprs_notif_list').append(html);
            }
        });

        e.preventDefault();
        // $.ajax({
        //     type: "POST",
        //     url: base_url + "oprs/logs/get_logs/0" , 
        //     dataType: "json",
        //     crossDomain: true,
        //     success: function(data) {
        //         // console.log(data);return false;

        //        var html = '<div class="list-group ">';
        //        $.each(data, function(key, val)
        //        {
        //            if(key <= 5){
        //             var bg = (val.notif_open == 0) ? 'bg-info' : '';
        //             var tc = (val.notif_open == 0) ? 'text-white' : 'text-dark';
        //             // var title = get_manuscript_log(val.log_insert_id);
        //             html += '<a href="javascript:void(0);" onclick="open_notif(\'' + val.man_title + '\',\'' + val.row_id + '\');" class="list-group-item list-group-item-action p-3 '+ bg + ' ' + tc +'"><strong>' + val.usr_username + '</strong> ' + val.log_action + ' <strong> \
        //                                                                                             ' + val.man_title + '</strong>\
        //                     <small class="d-flex mt-1">'+ moment(val.date_created).fromNow() + '</small></a>';
        //            }else if(key == 7){
        //                html += '<a href="notifications" class="text-center p-1 text-primary"><small class="font-weight-bold">See All</small></a>';
        //            }
                   
             
        //        });
    
        //        html += '</div>';
    
        //        $('.oprs_notif_list').append(html);
        //     }
        // });
    });

    // validate file size before uploading
    $.validator.addMethod('filesize', function (value, element, param) {
        return this.optional(element) || (element.files[0].size <= param)
    }, 'File size must be less than 20 MB');

    // unused upload manuscript (author only)
    jQuery(function ($) {
        "use strict";
        //validate upload manuscript form
        $("#manuscript_form").validate({
            debug: true,
            errorClass: 'text-danger',
            rules: {
                man_title: {
                    required: true,
                },
                man_author: {
                    required: true,
                },
                man_pages: {
                    required: true,
                },
                man_abs : {
                    required: true,
                    // extension: "pdf",
                    // filesize : 20000000,
                },
                man_file: {
                    required: true,
                    // extension: "pdf",
                    // filesize : 20000000,
                },
                man_word: {
                    required: true,
                    // extension: "doc|docx",
                    // filesize : 20000000,
                },
                man_page_position: {
                    required: true,
                },
                optradio: {
                    required: true,
                },
                man_affiliation: {
                    required: true,
                },
                man_email: {
                    required: true,
                }
            },
            submitHandler: function() {
                var full = $('#man_file')[0].files[0].size;
                var abs = $('#man_abs')[0].files[0].size;
                var word = $('#man_word')[0].files[0].size;
                if(full < 20000000) {
                    $('#badge_full').next('.badge-danger').hide();
                }else if(abs < 20000000){
                    $('#badge_abs').next('.badge-danger').hide();
                }else if(word < 20000000){
                    $('#badge_word').next('.badge-danger').hide();
                }

                if (full >= 20000000) {
                    $('#badge_full').after(' <span class="badge badge-danger"><span class="oi oi-warning"></span> File size must not exceed 20 MB</span>');
                }else if(abs >= 20000000){
                    $('#badge_abs').after(' <span class="badge badge-danger"><span class="oi oi-warning"></span> File size must not exceed 20 MB</span>');
                }else if(word >= 20000000){
                    $('#badge_word').after(' <span class="badge badge-danger"><span class="oi oi-warning"></span> File size must not exceed 20 MB</span>');
                }else {
                    $('#confirmUploadModal').modal('toggle');
                }

            }
        });

    });

    // submit manuscript if author account (unused)
    $('#submit_upload_manuscript').click(function(){

        $('.modal').modal('hide');

        $('body').loading('start');

        $(this).prop('disabled', true);
        var form = $('#manuscript_form');
        var formdata = false;

        if (window.FormData) {
            formdata = new FormData(form[0]);
        }

        var formAction = form.attr('action');
        
        $.ajax({
            url: base_url + "oprs/manuscripts/upload/",
            data: formdata ? formdata : form.serialize(),
            cache: false,
            contentType: false,
            processData: false,
            crossDomain: true,
            type: 'POST',
            success: function(data, textStatus, jqXHR) {
                $('body').loading('stop');
                $('#refreshModal').modal('toggle');
            }
        });
    });
    
    // delete manuscript (super admin only)
    $('#remove_manus').click(function(){
        $('#confirmRemoveModal').modal('toggle');
        $.ajax({
            type: "POST",
            url: base_url + "oprs/manuscripts/remove_manus/" + remove_man_id,
            cache: false,
            crossDomain: true,
            success: function(data) {
                location.reload();
            }
        });
    });

    var href = $(window.location).attr("href").split('/').pop();
    $('a[href="'+href+'"]').parent().addClass('active');

    // check if multiple email exists in one manuscript
    $.validator.addMethod(
        "repeatEmail",
        function(value, element) {

            var timeRepeated = 0;
            $("[name*='trk_rev_email[]']").each(function() {

                if ($(this).val() === value) {
                    timeRepeated++;
                }
            });

            if (timeRepeated === 1 || timeRepeated === 0) {
                return true
            } else {
                return false
            }
        }
    );

    // check if multiple name exists in one manuscript
    $.validator.addMethod(
        "uniqueName",
        function(value, element) {

            var timeRepeated = 0;
            $("[name*='trk_rev[]']").each(function() {

                if ($(this).val() === value) {
                    timeRepeated++;
                }

            });

            if (timeRepeated === 1 || timeRepeated === 0) {
                return true
            } else {
                return false
            }
        }
    );

    // check if multiple email exists in one manuscript
    $.validator.addMethod(
        "uniqueEmail",
        function(value, element) {
            response = (revs.indexOf(value) != -1) ? false : true;
            return response;
        }
    );

    // all manuscripts;
    var amt = $('#dataTable').DataTable({
        "order": [[ 2, "desc" ]],
        "columnDefs" : [{"targets":2, "type":"date"}]
    });
 
    amt.on( 'order.dt search.dt', function () {
        amt.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();

    $('#collapse_new_table').DataTable();
    $('#collapse_lapreq_table').DataTable();
    $('#collapse_decreq_table').DataTable();
    $('#collapse_laprev_table').DataTable();
    $('#controls_table').DataTable();
    // $('#uiux_table').DataTable();
    $('#cfs_table').DataTable();

    // new manuscripts
    var nmt = $('#new_manus_table').DataTable({
        "order": [[ 2, "desc" ]],
        "columnDefs" : [{"targets":2, "type":"date"}]
    });
 
    nmt.on( 'order.dt search.dt', function () {
        nmt.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();

    // on-review manuscripts
    var ort = $('#onreview_manus_table').DataTable({
        "order": [[ 2, "desc" ]],
        "columnDefs" : [{"targets":2, "type":"date"}]
    });
 
    ort.on( 'order.dt search.dt', function () {
        ort.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();

    // reviewed manuscripts
    var rmt = $('#reviewed_manus_table').DataTable({
        "order": [[ 2, "desc" ]],
        "columnDefs" : [{"targets":2, "type":"date"}]
    });
 
    rmt.on( 'order.dt search.dt', function () {
        rmt.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();

    // completed manuscripts
    var cmt = $('#completed_manus_table').DataTable({
        "order": [[ 2, "desc" ]],
        "columnDefs" : [{"targets":2, "type":"date"}]
    });
 
    cmt.on( 'order.dt search.dt', function () {
        cmt.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();

    // editorial review manuscripts
    var erm = $('#editorial_reviews_table').DataTable({
        "order": [[ 2, "desc" ]],
        "columnDefs" : [{"targets":2, "type":"date"}]
    });
 
    erm.on( 'order.dt search.dt', function () {
        erm.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();

    // final manuscripts
    var fmt = $('#final_manus_table').DataTable({
        "order": [[ 2, "desc" ]],
        "columnDefs" : [{"targets":2, "type":"date"}]
    });
 
    fmt.on( 'order.dt search.dt', function () {
        fmt.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();

    // for publication manuscripts
    var fpmt = $('#for_p_manus_table').DataTable({
        "order": [[ 2, "desc" ]],
        "columnDefs" : [{"targets":2, "type":"date"}]
    });
 
    fpmt.on( 'order.dt search.dt', function () {
        fpmt.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();

    // for layout manuscripts
    var flmt = $('#for_l_manus_table').DataTable({
        "order": [[ 2, "desc" ]],
        "columnDefs" : [{"targets":2, "type":"date"}]
    });
 
    flmt.on( 'order.dt search.dt', function () {
        flmt.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();

    // publishable manuscripts
    var pt = $('#publishables_table').DataTable({
        "order": [[ 2, "desc" ]],
        "columnDefs" : [{"targets":2, "type":"date"}]
    });
 
    pt.on( 'order.dt search.dt', function () {
        pt.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();

    // published manuscripts
    var pbdt = $('#pub_manus_table').DataTable({
        "order": [[ 2, "desc" ]],
        "columnDefs" : [{"targets":2, "type":"date"}]
    });
 
    pbdt.on( 'order.dt search.dt', function () {
        pbdt.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();
    
    pt.on( 'order.dt search.dt', function () {
        pt.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();

    // published manuscripts to other journal platforms
    var emt = $('#existing_manus_table').DataTable({
        "order": [[ 2, "desc" ]],
        "columnDefs" : [{"targets":2, "type":"date"}]
    });
 
    emt.on( 'order.dt search.dt', function () {
        emt.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();

    $('#reviews_table').DataTable();
    $('#layout_table').DataTable();
    $('#collapse_reviewed_table').DataTable();
    $('#collapse_complete_table').DataTable();
    $('#collapse_reviewers_table').DataTable();
    $('#email_contents_table').DataTable();

    // activity logs datatable
    if (prv_exp == 0) {
        $('#activity_logs_table').DataTable();
    } else {
        $('#activity_logs_table').DataTable({
            dom: 'lBfrtip',
            buttons: [{
                text: 'Create Backup',
                title: 'Activity Logs',
                action: function(e, dt, node, config) {
                    log_export('Create backup', 'Activity Logs');
                    window.location = base_url + "oprs/logs/export_logs";
                }
            },{
                extend: 'copy',
                text: 'Copy to clipboard',
                title: 'Activity Logs',
                action: function(e, dt, node, config) {
                    log_export('Copy to clipboard', 'Activity Logs');
                    $.fn.dataTable.ext.buttons.copyHtml5.action.call(this, e, dt, node, config);
                }
            }, {
                extend: 'excel',
                text: 'Export as Excel',
                title: 'Activity Logs',
                action: function(e, dt, node, config) {
                    log_export('Export as Excel', 'Activity Logs');
                    $.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, node, config);
                }
            }, {
                extend: 'pdf',
                text: 'Export as PDF',
                title: 'Activity Logs',
                action: function(e, dt, node, config) {
                    log_export('Export as PDF', 'Activity Logs');
                    $.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, node, config);
                }
            }, {
                extend: 'print',
                title: 'Activity Logs',
                action: function(e, dt, node, config) {
                    log_export('Print', 'Activity Logs');
                    window.print();
                }
            }]
        });
    }

    // manuscript datatable in reports
    if (prv_exp == 0) {
        $('#report_manuscript_table').DataTable();
    } else {
        $('#report_manuscript_table').DataTable({
            dom: 'lBfrtip',
            buttons: [{
                extend: 'copy',
                text: 'Copy to clipboard',
                title: 'List of Manuscripts',
                action: function(e, dt, node, config) {
                    log_export('Copy to clipboard', 'List of Manuscripts');
                    $.fn.dataTable.ext.buttons.copyHtml5.action.call(this, e, dt, node, config);
                }
            }, {
                extend: 'excel',
                text: 'Export as Excel',
                title: 'List of Manuscripts',
                action: function(e, dt, node, config) {
                    log_export('Export as Excel', 'List of Manuscripts');
                    $.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, node, config);
                }
            }, {
                extend: 'pdf',
                text: 'Export as PDF',
                title: 'List of Manuscripts',
                action: function(e, dt, node, config) {
                    log_export('Export as PDF', 'List of Manuscripts');
                    $.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, node, config);
                }
            }, {
                extend: 'print',
                title: 'List of Manuscripts',
                action: function(e, dt, node, config) {
                    log_export('Print', 'List of Manuscripts');
                    window.print();
                }
            }]
        });
    }

    // reviewers datatable in reports
    if (prv_exp == 0) {
        $('#report_reviewer_table').DataTable();
    } else {
        $('#report_reviewer_table').DataTable({
            dom: 'lBfrtip',
            buttons: [{
                extend: 'copy',
                text: 'Copy to clipboard',
                messageTop: 'List of Reviewers',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRPC Research Journal', 
                action: function(e, dt, node, config) {
                    log_export('Copy to clipboard', 'List of Reviewers');
                    $.fn.dataTable.ext.buttons.copyHtml5.action.call(this, e, dt, node, config);
                }
            }, {
                extend: 'excel',
                text: 'Export as Excel',
                messageTop: 'List of Reviewers',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRPC Research Journal', 
                action: function(e, dt, node, config) {
                    log_export('Export as Excel', 'List of Reviewers');
                    $.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, node, config);
                }
            }, {
                extend: 'pdf',
                text: 'Export as PDF',
                messageTop: 'List of Reviewers',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES' + '\n' + 'NRPC Research Journal', 
                action: function(e, dt, node, config) {
                    log_export('Export as PDF', 'List of Reviewers');
                    $.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, node, config);
                }
            }, {
                extend: 'print',
                messageTop: 'List of Reviewers',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRPC Research Journal', 
                action: function(e, dt, node, config) {
                    log_export('Print', 'List of Reviewers');
                    window.print();
                }
            }]
        });
    }

    // lapsed request datatable in reports
    if (prv_exp == 0) {
        $('#report_lapreq_table').DataTable();
    } else {
        $('#report_lapreq_table').DataTable({
            dom: 'lBfrtip',
            buttons: [{
                extend: 'copy',
                text: 'Copy to clipboard',
                messageTop: 'List of Lapsed Requests',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRPC Research Journal', 
                action: function(e, dt, node, config) {
                    log_export('Copy to clipboard', 'List of Lapsed Requests');
                    $.fn.dataTable.ext.buttons.copyHtml5.action.call(this, e, dt, node, config);
                }
            }, {
                extend: 'excel',
                text: 'Export as Excel',
                messageTop: 'List of Lapsed Requests',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRPC Research Journal', 
                action: function(e, dt, node, config) {
                    log_export('Export as Excel', 'List of Lapsed Requests');
                    $.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, node, config);
                }
            }, {
                extend: 'pdf',
                text: 'Export as PDF',
                messageTop: 'List of Lapsed Requests',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES' + '\n' + 'NRPC Research Journal', 
                action: function(e, dt, node, config) {
                    log_export('Export as PDF', 'List of Lapsed Requests');
                    $.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, node, config);
                }
            }, {
                extend: 'print',
                messageTop: 'List of Lapsed Requests',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRPC Research Journal', 
                action: function(e, dt, node, config) {
                    log_export('Print', 'List of Lapsed Requests');
                    window.print();
                }
            }]
        });
    }

    // declined request datatable in reports
    if (prv_exp == 0) {
        $('#report_decreq_table').DataTable();
    } else {
        $('#report_decreq_table').DataTable({
            dom: 'lBfrtip',
            buttons: [{
                extend: 'copy',
                text: 'Copy to clipboard',
                messageTop: 'List of Declined Requests',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRPC Research Journal', 
                action: function(e, dt, node, config) {
                    log_export('Copy to clipboard', 'List of Declined Requests');
                    $.fn.dataTable.ext.buttons.copyHtml5.action.call(this, e, dt, node, config);
                }
            }, {
                extend: 'excel',
                text: 'Export as Excel',
                messageTop: 'List of Declined Requests',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRPC Research Journal', 
                action: function(e, dt, node, config) {
                    log_export('Export as Excel', 'List of Declined Requests');
                    $.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, node, config);
                }
            }, {
                extend: 'pdf',
                text: 'Export as PDF',
                messageTop: 'List of Declined Requests',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES' + '\n' + 'NRPC Research Journal', 
                action: function(e, dt, node, config) {
                    log_export('Export as PDF', 'List of Declined Requests');
                    $.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, node, config);
                }
            }, {
                extend: 'print',
                messageTop: 'List of Declined Requests',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRPC Research Journal', 
                action: function(e, dt, node, config) {
                    log_export('Print', 'List of Declined Requests');
                    window.print();
                }
            }]
        });
    }

    // lapsed review datatable in reports
    if (prv_exp == 0) {
        $('#report_laprev_table').DataTable();
    } else {
        $('#report_laprev_table').DataTable({
            dom: 'lBfrtip',
            buttons: [{
                extend: 'copy',
                text: 'Copy to clipboard',
                messageTop: 'List of Lapsed Reviews',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRPC Research Journal', 
                action: function(e, dt, node, config) {
                    log_export('Copy to clipboard', 'List of Lapsed Reviews');
                    $.fn.dataTable.ext.buttons.copyHtml5.action.call(this, e, dt, node, config);
                }
            }, {
                extend: 'excel',
                text: 'Export as Excel',
                messageTop: 'List of Lapsed Reviews',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRPC Research Journal', 
                action: function(e, dt, node, config) {
                    log_export('Export as Excel', 'List of Lapsed Reviews');
                    $.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, node, config);
                }
            }, {
                extend: 'pdf',
                text: 'Export as PDF',
                messageTop: 'List of Lapsed Reviews',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES' + '\n' + 'NRPC Research Journal', 
                action: function(e, dt, node, config) {
                    log_export('Export as PDF', 'List of Lapsed Reviews');
                    $.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, node, config);
                }
            }, {
                extend: 'print',
                messageTop: 'List of Lapsed Reviews',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRPC Research Journal', 
                action: function(e, dt, node, config) {
                    log_export('Print', 'List of Lapsed Reviews');
                    window.print();
                }
            }]
        });
    }

    // reviewed manuscripts datatable 
    if (prv_exp == 0) {
        $('#report_revman_table').DataTable();
    } else {
        $('#report_revman_table').DataTable({
            dom: 'lBfrtip',
            buttons: [{
                extend: 'copy',
                text: 'Copy to clipboard',
                messageTop: 'List of Reviewed Manuscrtips',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRPC Research Journal', 
                action: function(e, dt, node, config) {
                    log_export('Copy to clipboard', 'List of Reviewed Manuscrtips');
                    $.fn.dataTable.ext.buttons.copyHtml5.action.call(this, e, dt, node, config);
                }
            }, {
                extend: 'excel',
                text: 'Export as Excel',
                messageTop: 'List of Reviewed Manuscrtips',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRPC Research Journal', 
                action: function(e, dt, node, config) {
                    log_export('Export as Excel', 'List of Reviewed Manuscrtips');
                    $.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, node, config);
                }
            }, {
                extend: 'pdf',
                text: 'Export as PDF',
                messageTop: 'List of Reviewed Manuscrtips',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES' + '\n' + 'NRPC Research Journal', 
                action: function(e, dt, node, config) {
                    log_export('Export as PDF', 'List of Reviewed Manuscrtips');
                    $.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, node, config);
                }
            }, {
                extend: 'print',
                messageTop: 'List of Reviewed Manuscrtips',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRPC Research Journal', 
                action: function(e, dt, node, config) {
                    log_export('Print', 'List of Reviewed Manuscrtips');
                    window.print();
                }
            }]
        });
    }

    // completed reviews datatable in reports
    if (prv_exp == 0) {
        $('#report_comrev_table').DataTable();
    } else {
        $('#report_comrev_table').DataTable({
            dom: 'lBfrtip',
            buttons: [{
                extend: 'copy',
                text: 'Copy to clipboard',
                messageTop: 'List of Completed Reviews',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRPC Research Journal', 
                action: function(e, dt, node, config) {
                    log_export('Copy to clipboard', 'List of Completed Reviews');
                    $.fn.dataTable.ext.buttons.copyHtml5.action.call(this, e, dt, node, config);
                }
            }, {
                extend: 'excel',
                text: 'Export as Excel',
                messageTop: 'List of Completed Reviews',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRPC Research Journal', 
                action: function(e, dt, node, config) {
                    log_export('Export as Excel', 'List of Completed Reviews');
                    $.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, node, config);
                }
            }, {
                extend: 'pdf',
                text: 'Export as PDF',
                messageTop: 'List of Completed Reviews',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES' + '\n' + 'NRPC Research Journal', 
                action: function(e, dt, node, config) {
                    log_export('Export as PDF', 'List of Completed Reviews');
                    $.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, node, config);
                }
            }, {
                extend: 'print',
                messageTop: 'List of Completed Reviews',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRPC Research Journal', 
                action: function(e, dt, node, config) {
                    log_export('Print', 'List of Completed Reviews');
                    window.print();
                }
            }]
        });
    }

    // ui/ux datatable in reports
    if (prv_exp == 0) {
        $('#uiux_table').DataTable();
    } else {
        $('#uiux_table').DataTable({
            dom: 'lBfrtip',
            buttons: [{
                extend: 'copy',
                text: 'Copy to clipboard',
                messageTop: 'List of UI/UX Feedbacks',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRPC Research Journal', 
                action: function(e, dt, node, config) {
                    log_export('Copy to clipboard', 'List of UI/UX Feedbacks');
                    $.fn.dataTable.ext.buttons.copyHtml5.action.call(this, e, dt, node, config);
                }
            }, {
                extend: 'excel',
                text: 'Export as Excel',
                messageTop: 'List of UI/UX Feedbacks',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRPC Research Journal', 
                action: function(e, dt, node, config) {
                    log_export('Export as Excel', 'List of UI/UX Feedbacks');
                    $.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, node, config);
                }
            }, {
                extend: 'pdf',
                messageTop: 'List of UI/UX Feedbacks',
                text: 'Export as PDF',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES' + '\n' + 'NRPC Research Journal', 
                action: function(e, dt, node, config) {
                    log_export('Export as PDF', 'List of UI/UX Feedbacks');
                    $.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, node, config);
                }
            }, {
                extend: 'print',
                messageTop: 'List of UI/UX Feedbacks',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRPC Research Journal', 
                action: function(e, dt, node, config) {
                    log_export('Print', 'List of UI/UX Feedbacks');
                    window.print();
                }
            }]
        });
    }

    // NDA in reports
    if (prv_exp == 0) {
        $('#report_nda_table').DataTable();
    } else {
        $('#report_nda_table').DataTable({
            dom: 'lBfrtip',
            buttons: [{
                extend: 'copy',
                text: 'Copy to clipboard',
                messageTop: 'List of NDAs',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRPC Research Journal', 
                action: function(e, dt, node, config) {
                    log_export('Copy to clipboard', 'List of NDAs');
                    $.fn.dataTable.ext.buttons.copyHtml5.action.call(this, e, dt, node, config);
                }
            }, {
                extend: 'excel',
                text: 'Export as Excel',
                messageTop: 'List of NDAs',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRPC Research Journal', 
                action: function(e, dt, node, config) {
                    log_export('Export as Excel', 'List of NDAs');
                    $.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, node, config);
                }
            }, {
                extend: 'pdf',
                messageTop: 'List of NDAs',
                text: 'Export as PDF',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES' + '\n' + 'NRPC Research Journal', 
                action: function(e, dt, node, config) {
                    log_export('Export as PDF', 'List of NDAs');
                    $.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, node, config);
                }
            }, {
                extend: 'print',
                messageTop: 'List of NDAs',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRPC Research Journal', 
                action: function(e, dt, node, config) {
                    log_export('Print', 'List of NDAs');
                    window.print();
                }
            }]
        });
    }

    

    // get titles (mr., ms. etc)
    $.ajax({
        type: "GET",
        url: base_url + "oprs/manuscripts/get_titles",
        dataType: "json",
        crossDomain: true,
        success: function(data) {

            $.each(data, function(key, val) {
                array_prf.push(val.title_name);
            });

        }
    });

    // slide effect of titles of non-members (unused)
    $('#non_title').editableSelect({
        effects: 'slide'
    });

    // slide effect of initial reviewer title
    $('#trk_title1, #editor_title1').editableSelect({
        effects: 'slide'
    });

    // slide effect of journal volume
    $('#process_manuscript_form #jor_volume, #edit_manuscript_form #jor_volume').editableSelect({
        effects: 'slide'
    });

    //get non-members info (unused)
    // $.ajax({
    //     type: "GET",
    //     url: base_url + "oprs/manuscripts/non_members/",
    //     dataType: "json",
    //     crossDomain: true,
    //     success: function(data) {
    //         if (data.length > 0) {
    //             $.each(data, function(key, val) {
    //                 mems.push(val.non_first_name + ' ' + val.non_middle_name + ' ' + val.non_last_name);
    //                 mem_mail.push(val.non_email);
    //                 mem_num.push(val.non_contact);
    //                 mem_spec.push(val.non_specialization);
    //                 mem_id.push(val.non_usr_id);
    //                 mem_exp.push(val.non_first_name + ' ' + val.non_middle_name + ' ' + val.non_last_name + ' (' + val.non_specialization + ')');
    //                 mem_aff.push(val.non_affiliation);
    //                 mem_prf.push(val.non_title);
    //             });
    //         }

    //     }
    // });

    // get authors info
    $.ajax({
        type: "GET",
        url: base_url + "oprs/manuscripts/authors/",
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            $.each(data, function(key, val) {
                acoa.push(val);
            });
            acoa.sort();
            $.unique(acoa);
        }
    });

    // show member name on keyup
    if ($('#trk_rev1').length){
        autocomplete(document.getElementById("trk_rev1"), mem_exp, '#trk_rev_email1', '#trk_rev_num1', '#trk_rev_id1', '1', '#trk_rev_spec1', '#trk_title1');
    }
    // show member name on keyup
    if ($('#editor_rev1').length){
        autocomplete_editor(document.getElementById("editor_rev1"), mem_exp, '#editor_rev_email1', '#editor_rev_num1', '#editor_rev_id1', '1', '#editor_rev_spec1', '#editor_title1');
    }
        
    // show aythir name on keyup
    if ($('#man_author').length){
        autocomplete_acoa(document.getElementById("man_author"), mem_exp, '#man_affiliation', '#man_email', '#man_usr_id');
    }

    $('body').tooltip({
        selector: '[rel=tooltip]'
    });

    $('#sidenavToggler').click(function() {
        if ($('.pp').is(':visible')) {
            $('.pp').hide();
        } else {
            $('.pp').show();
        }
    });

    // sign up (unused)
    $("#form_sign_up").validate({
        debug: true,
        errorClass: 'text-danger',
        rules: {
            non_title: {
                required: true,
                minlength: 2
            },
            non_first_name: {
                required: true,
                minlength: 2
            },
            non_last_name: {
                required: true,
                minlength: 2,
            },
            non_middle_name: {
                required: true,
                minlength: 2
            },
            non_affiliation: {
                required: true,
            },
            non_email: {
                required: true,
                email: true,
                remote: {
                    url: base_url + "oprs/signup/verify_email/",
                    type: "post"
                }
            },
            non_contact: {
                required: true,
            },
            non_password: {
                required: true,
            },
            usr_captcha: {
                required: true,
                equalTo: "#hidden_captcha",
            },
            non_specialization: {
                required: true,
            }
        },
        messages: {
            usr_captcha: {
                equalTo: "Incorrect verification code"
            },
            non_email: {
                remote: "Email already in use"
            }
        },
        submitHandler: function() {
            $.ajax({
                type: "POST",
                url: base_url + "oprs/signup/sign_up/",
                data: $('#form_sign_up').serializeArray(),
                cache: false,
                crossDomain: true,
                success: function(data) {
                    $.notify({
                        icon: 'fa fa-check-circle',
                        message: 'Thank you for signing up. You can now log in.'
                    }, {
                        type: 'success',
                        timer: 3000,
                    });

                    $('#form_sign_up')[0].reset();
                    $('#refresh_captcha').click();
                }
            });
        }
    });

    // add user validation
    $("#form_add_user").validate({
        debug: true,
        errorClass: 'text-danger',
        rules: {
            usr_password: {
                required: true,
                minlength: 5
            },
            usr_rep_password: {
                required: true,
                minlength: 5,
                equalTo: "#form_add_user #usr_password"
            },
            usr_username: {
                required: true,
                minlength: 3,
                email: true,
                remote: {
                    url: base_url + "oprs/user/verify_email/",
                    type: "post",
                    data: {
                        role: function() {
                            return $('#form_add_user #usr_role').val();
                        },
                        sys: function() {
                            return $('#usr_sys_acc').val();
                        }
                    },

                }
            },
            usr_role: {
                required: true,
            },
        },
        messages: {
            usr_password: {
                required: "Please provide a password",
                minlength: "Your password must be at least 5 characters long"
            },
            usr_rep_password: {
                required: "Please provide a password",
                minlength: "Your password must be at least 5 characters long",
                equalTo: "Please enter the same password entered previously"
            },
            usr_username: {
                required: "Please provide a username",
                minlength: "Your username must be at least 3 characters long",
                remote: "Email already used",
            },
            usr_role: {
                required: "Please select user role",
            },
        },
        submitHandler: function() {

            $('body').loading('start');
            $.ajax({
                type: "POST",
                url: base_url + "oprs/user/add_user/",
                data: $('#form_add_user').serializeArray(),
                cache: false,
                crossDomain: true,
                success: function(data) {
                    location.reload();
                }
            });
        }
    });

    // edit user validation
    $("#form_edit_user").validate({
        debug: true,
        errorClass: 'text-danger',
        rules: {
            usr_password: {
                minlength: 5
            },
            usr_rep_password: {
                minlength: 5,
                equalTo: {
                    param: "#form_edit_user #usr_password",
                    depends: function(element) {
                        return $("#form_edit_user #usr_password").val().length > 0;
                    }
                },
                required: function(element) {
                    return $("#form_edit_user #usr_password").val().length > 0;
                }
            },
            usr_username: {
                required: true,
                minlength: 3
            },
            usr_role: {
                required: true,
            },
            // usr_email: {
            // 	email: true,
            // 	required: true,
            // }
        },
        messages: {
            usr_password: {
                minlength: "Your password must be at least 5 characters long"
            },
            usr_rep_password: {
                minlength: "Your password must be at least 5 characters long",
                equalTo: "Please enter the same password entered previously"
            },
            usr_username: {
                required: "Please provide a username",
                minlength: "Your username must be at least 3 characters long"
            },
            usr_role: {
                required: "Please select user role",
            },
            // usr_email: {
            // 	required: "Please enter valid email",
            // }
        },
        submitHandler: function() {
            $.ajax({
                type: "POST",
                url: base_url + "oprs/user/edit_user/" + user_id,
                data: $('#form_edit_user').serializeArray(),
                cache: false,
                crossDomain: true,
                success: function(data) {
                    // console.log(data);
                    location.reload();
                }
            });
        }
    });

    // // upload manuscript validation
    // $("#manuscript_form").validate({
    //     debug: true,
    //     errorClass: 'text-danger',
    //     rules: {
    //         man_title: {
    //             required: true,
    //         },
    //         man_author: {
    //             required: true,
    //         },
    //         man_pages: {
    //             required: true,
    //         },
    //         man_file: {
    //             required: true,
    //             extension: 'pdf',
    //             maxFileSize: {
    //                 "unit": "MB",
    //                 "size": "25"
    //             }
    //         },
    //         man_page_position: {
    //             required: true,
    //         },
    //         man_affiliation: {
    //             required: true,
    //         },
    //         man_email: {
    //             required: true,
    //         }
    //     },
    //     submitHandler: function() {

    //         var form = $('#manuscript_form');
    //         var fromdata = false;

    //         if (window.FormData) {
    //             formdata = new FormData(form[0]);
    //         }

    //         var formAction = form.attr('action');

    //         $('#uploadModal').modal('toggle');
    //         $('body').loading('start');

    //         $.ajax({
    //             url: base_url + "oprs/manuscripts/upload/",
    //             data: formdata ? formdata : form.serialize(),
    //             cache: false,
    //             contentType: false,
    //             processData: false,
    //             crossDomain: true,
    //             type: 'POST',
    //             success: function(data, textStatus, jqXHR) {
    //                 location.reload();
    //             }
    //         });
    //     }
    // });

    // process manuscript validation
    $("#process_manuscript_form").validate({
        debug: true,
        errorClass: 'text-danger',
        rules: {
            'trk_title[]': {
                required: true,
            },
            'trk_rev_spec[]': {
                required: true,
            },
            'trk_rev[]': {
                required: true,
                uniqueName: true,
            },
            'trk_rev_email[]': {
                required: true,
                email: true,
                uniqueEmail: true,
                repeatEmail: true,
            },
            trk_timeframe: {
                required: true,
            },
            trk_request_timer: {
                required: true,
            },
            jor_volume: {
                required: true,
            },
            jor_issue: {
                required: true,
            },
            jor_year: {
                required: true,
                number: true,
            }
        },
        messages: {
            'trk_rev_email[]': {
                uniqueEmail: "Email already in use",
                repeatEmail: "Email can not be repeated",
            },
            'trk_rev[]': {
                uniqueName: "Name can not be repeated",
            }
        },
        submitHandler: function() {

            var x = tinyMCE.editors.length;
            for (var i = 1; i <= x; i++) {
                var mail = $(tinymce.get('tiny_mail' + i).getBody()).html();
                var req = [];

                if (mail.indexOf("[FULL NAME]") != -1) {
                    req.push('FULL NAME');
                }
                if (mail.indexOf("[POSITION]") != -1) {
                    req.push('POSITION');
                }
                if (mail.indexOf("[AFFILIATION]") != -1) {
                    req.push('AFFILIATION');
                }
                if (mail.indexOf("[ADDRESS]") != -1) {
                    req.push('ADDRESS');
                }
                if (mail.indexOf("[TITLE]") != -1) {
                    req.push('TITLE');
                }
                if (mail.indexOf("[LAST NAME]") != -1) {
                    req.push('LAST NAME');
                }
                if (mail.indexOf("[SPECIALIZATION]") != -1) {
                    req.push('SPECIALIZATION');
                }
                var rev = i;
            }

            if (req.length == 0) {
                $('#processReviewModal').modal('toggle');
            } else {

                var alert = '	<div class="alert alert-danger" role="alert"><h6 class="alert-heading"><span class="fa fa-times-circle"></span> Please enter missing information:</h6>';
                var al_num = 1;

                $.each(req, function(key, val) {
                    alert += '(' + (al_num++) + ') ' + val + '</br>';
                });

                alert += '</div>';
                $('#tiny_mail' + rev).next().remove();
                $('#tiny_mail' + rev).after(alert);
            }
        }
    });

    // process manuscript validation
    $("#edit_manuscript_form").validate({
        debug: true,
        errorClass: 'text-danger',
        rules: {
            'editor_title[]': {
                required: true,
            },
            'editor_rev_spec[]': {
                required: true,
            },
            'editor_rev[]': {
                required: true,
                uniqueName: true,
            },
            'editor_rev_email[]': {
                required: true,
                email: true,
                uniqueEmail: true,
                repeatEmail: true,
            },
            jor_volume: {
                required: true,
            },
            jor_issue: {
                required: true,
            },
            jor_year: {
                required: true,
                number: true,
            }
        },
        messages: {
            'editor_rev_email[]': {
                uniqueEmail: "Email already in use",
                repeatEmail: "Email can not be repeated",
            },
            'editor_rev[]': {
                uniqueName: "Name can not be repeated",
            }
        },
        submitHandler: function() {

            var x = tinyMCE.editors.length;
            for (var i = 1; i <= x; i++) {
                var mail = $(tinymce.get('editor_tiny_mail' + i).getBody()).html();
                var req = [];

                if (mail.indexOf("[FULL NAME]") != -1) {
                    req.push('FULL NAME');
                }
                if (mail.indexOf("[POSITION]") != -1) {
                    req.push('POSITION');
                }
                if (mail.indexOf("[DEPARTMENT]") != -1) {
                    req.push('DEPARTMENT');
                }
                if (mail.indexOf("[AFFILIATION]") != -1) {
                    req.push('AFFILIATION');
                }
                if (mail.indexOf("[ADDRESS]") != -1) {
                    req.push('ADDRESS');
                }
                if (mail.indexOf("[TITLE]") != -1) {
                    req.push('TITLE');
                }
                if (mail.indexOf("[LAST NAME]") != -1) {
                    req.push('LAST NAME');
                }
                if (mail.indexOf("[SPECIALIZATION]") != -1) {
                    req.push('SPECIALIZATION');
                }
                var rev = i;
            }

            if (req.length == 0) {
                $('#editorReviewModal').modal('toggle');
            } else {

                var alert = '	<div class="alert alert-danger" role="alert"><h6 class="alert-heading"><span class="fa fa-times-circle"></span> Please enter missing information:</h6>';
                var al_num = 1;

                $.each(req, function(key, val) {
                    alert += '(' + (al_num++) + ') ' + val + '</br>';
                });

                alert += '</div>';
                $('#editor_tiny_mail' + rev).next().remove();
                $('#editor_tiny_mail' + rev).after(alert);
            }
        }
    });

    // publish manuscript to ejournal
    // $("#publish_form").validate({
    //     debug: true,
    //     errorClass: 'text-danger',
    //     submitHandler: function() {

    //         $('body').loading('start');

    //         var form = $('#publish_form');
       
    //         if (window.FormData) {
    //             formdata = new FormData(form[0]);
    //         }

    //         $.ajax({
    //             url: base_url + "oprs/manuscripts/publish/",
    //             data: formdata ? formdata : form.serialize(),
    //             cache: false,
    //             contentType: false,
    //             processData: false,
    //             crossDomain: true,
    //             type: 'POST',
    //             success: function(data) {
    //                 location.reload();
    //                 // console.log(data);
    //             }
    //         });
 
    //     }
    // });

    // final review 
    $("#final_review_form").validate({  
        debug: true,
        errorClass: 'text-danger',
        rules: {
            com_rev: {
                required: true,
            },
        },
        submitHandler: function() {

            $('#committeeModal .modal-footer button').addClass('disabled');

            var form = $('#final_review_form');
            var fromdata = false;
            if (window.FormData) {
                formdata = new FormData(form[0]);
            }
            var formAction = form.attr('action');

            $.ajax({
                url: base_url + "oprs/manuscripts/final_review/",
                data: formdata ? formdata : form.serialize(),
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                crossDomain: true,
                success: function(data, textStatus, jqXHR) {
                    location.reload();
                    // console.log(data);
                }
            });
        }
    });

    // display reviewer name in card header after entering/select reviewer
    $(document).on('keyup', 'input[name^="trk_rev"]', function() {
        if (event.keyCode == 8 || event.keyCode == 46) {
            $(this).closest('div.card').find('input[name^="trk_rev_id"]').val('');
        }
    });

    // replaced [FULL NAME] in tinymce after entering/select reviewer
    $(document).on('blur', 'input[name="trk_rev[]"]', function() {

        if($(this).val() != ''){
            var id = $(this).attr('id');
            var pos = id.substr(id.length - 1);
    
            $('#rev_header' + pos).text($(this).val());
            $('#rev_header_mail' + pos).text($(this).val());
    
            var rev_name_blur = localStorage.getItem('rev_name_blur');
            localStorage.setItem('rev_name_blur', $(this).val());
            var text = $(this).val();
            var mail = $(tinymce.get('tiny_mail'+pos).getBody()).html();
    
            if (mail.indexOf('[FULL NAME]') != -1) {
    
                var new_mail = mail.replace('[FULL NAME]', text);
            } else {
                var new_mail = mail.replace(rev_name_blur, text);
            }
     
            $(tinymce.get('tiny_mail'+pos).getBody()).html(new_mail);
        }

    });

    // replaced [TITLE] in tinymce after entering/select reviewer
    $(document).on('blur', 'input[name="trk_title[]"]', function() {

        if($(this).val() != ''){
            var id = $(this).attr('id');
            var pos = id.substr(id.length - 1);
    
            var rev_title_blur = localStorage.getItem('rev_title_blur');
            localStorage.setItem('rev_title_blur', $(this).val());
            var text = $(this).val();
            var mail = $(tinymce.get('tiny_mail'+pos).getBody()).html();
    
            if (mail.indexOf('[TITLE]') != -1) {
    
                var new_mail = mail.replace('[TITLE]', text);
            } else {
                var new_mail = mail.replace(rev_title_blur, text);
            }
     
            $(tinymce.get('tiny_mail'+pos).getBody()).html(new_mail);
        }
    });

    // replaced [SPECIALIZATION] in tinymce after entering/select reviewer
    $(document).on('blur', 'input[name="trk_rev_spec[]"]', function() {

        if($(this).val() != ''){
            var id = $(this).attr('id');
            var pos = id.substr(id.length - 1);
    
            var rev_spec_blur = localStorage.getItem('rev_spec_blur');
            localStorage.setItem('rev_spec_blur', $(this).val());
            var text = $(this).val();
            var mail = $(tinymce.get('tiny_mail'+pos).getBody()).html();
    
            if (mail.indexOf('[SPECIALIZATION]') != -1) {
    
                var new_mail = mail.replace('[SPECIALIZATION]', text);
            } else {
                var new_mail = mail.replace(rev_spec_blur, text);
            }
     
            $(tinymce.get('tiny_mail'+pos).getBody()).html(new_mail);
        }
    });

    // send to reviewers and send email
    $('#submit_final_process').click(function() {

        $('.modal').modal('hide');

        $('body').loading('start');

        var form = $('#process_manuscript_form');
        var fromdata = false;
        if (window.FormData) {
            formdata = new FormData(form[0]);
        }
        var formAction = form.attr('action');

        $.ajax({
            url: base_url + "oprs/manuscripts/process/" + man_id,
            data: formdata ? formdata : form.serialize(),
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            crossDomain: true,
            success: function(data, textStatus, jqXHR) {
                location.reload();
                // console.log(data);
            }
        });
    });

    // send to editor and send email
    $('#submit_to_editor').click(function() {

        $('.modal').modal('hide');

        $('body').loading('start');

        var form = $('#edit_manuscript_form');
        var fromdata = false;
        if (window.FormData) {
            formdata = new FormData(form[0]);
        }
        var formAction = form.attr('action');

        $.ajax({
            url: base_url + "oprs/manuscripts/editor/" + man_id,
            data: formdata ? formdata : form.serialize(),
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            crossDomain: true,
            success: function(data, textStatus, jqXHR) {
                location.reload();
                // console.log(data);
            }
        });
    });

    // submit final manuscript
    $("#final_manuscript_form").validate({
        debug: true,
        errorClass: 'text-danger',
        rules: {
            man_keywords: {
                required: true,
            },
            man_abs: {
                required: true,
                extension: 'pdf',
                maxFileSize: {
                    "unit": "MB",
                    "size": "25"
                }
            },
            man_word: {
                required: true,
                extension: 'doc|docx',
                maxFileSize: {
                    "unit": "MB",
                    "size": "25"
                }
            },
        },
        submitHandler: function() {

            var form = $('#final_manuscript_form');
            var fromdata = false;

            if (window.FormData) {
                formdata = new FormData(form[0]);
            }

            var formAction = form.attr('action');
            
     

            $('body').loading('start');

            $.ajax({
                url: base_url + "oprs/manuscripts/revision/" + man_id,
                data: formdata ? formdata : form.serialize(),
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                crossDomain: true,
                success: function(data, textStatus, jqXHR) {
                    location.reload();
                    // console.log(data);
                }
            });
        }
    });

    // forgot password email verification
    $("#form_forgot").validate({
        debug: true,
        errorClass: 'text-danger',
        rules: {
            get_email: {
                required: true,
                email: true,
                remote: {
                    url: base_url + "support/forgot/check_email/",
                    type: "post"
                }
            },
            usr_role: {
                required: true,
            }
        },
        messages: {
            get_email: {
                remote: "Email not found"
            },
            usr_role: {
                required: "Please select one",
            }
        },
        submitHandler: function() {
            $.ajax({
                type: "POST",
                url: base_url + "support/forgot/send_password/",
                data: $('#form_forgot').serializeArray(),
                cache: false,
                crossDomain: true,
                success: function(data) {
                    $.notify({
                        icon: 'fa fa-check-circle',
                        message: 'Email sent! Please check your inbox.'
                    }, {
                        type: 'success',
                        timer: 3000,
                    });

                    $('#form_forgot')[0].reset();
                    $('#user_option').empty();
                }
            });
        }
    });

    // dynamic adding of co-author
    var inpIncr = 0;
    $('#btn_add_coa').click(function() {
        var html = '';
        inpIncr++;

        html = '<div id="added_coa"><div class="form-group autocomplete w-100">' +
            '<label class="font-weight-bold" for="coa_name">Co-author ' + inpIncr + '</label> <small><a href="javascript:void(0);" class="text-danger"> Remove</a></small>' +
            '<input class="form-control" id="coa_name' + inpIncr + '" name="coa_name[]" placeholder="Search/Type by Name/Specialization/Non-member/Non-account">' +
            '</div>' +
            '<div class="form-group">' +
            '<div class="form-row">' +
            '<div class="col">' +
            '<input type="text" class="form-control" placeholder="Affiliation" id="coa_affiliation' + inpIncr + '"" name="coa_affiliation[]">' +
            '</div>' +
            '<div class="col">' +
            '<input type="email" class="form-control" placeholder="Email" id="coa_email' + inpIncr + '"" name="coa_email[]">' +
            '</div>' +
            '</div>' +
            '</div></div>';


        $('#coauthors').append(html);
        // autocomplete_acoa(document.getElementById("coa_name" + inpIncr), acoa);
        autocomplete_acoa(document.getElementById("coa_name" + inpIncr), mem_exp, '#coa_affiliation' + inpIncr, '#coa_email' + inpIncr);
    });

    // remove added co-author
    $('#coauthors').on('click', 'a', function() {
        $(this).closest('#added_coa').remove();
    });

    // add reviewers
    $('#btn_add_rev').click(function() {
        var select;
        $.each(array_prf, function(key, val) {
            select += '<option value="' + val + '">' + val + '</option>';

        });

        $('#rev_acc .collapse').removeClass('show');
        $('#rev_acc_mail .collapse').removeClass('show');

              
        revIncr++;

        var html = '';

        html = '<div class="card" id="added_rev">' +
            '<div class="card-header p-0" id="heading' + revIncr + '"  data-toggle="collapse" data-target="#collapse' + revIncr + '">' +
            '<h5 class="mb-0">' +
            '<button class="btn btn-link" type="button">' +
            '<span class="fa fa-address-card"></span> Reviewer ' + revIncr + ' : <span id="rev_header' + revIncr + '"></span>' +
            '</button>' +
            '<button type="button" class="btn btn-link float-right text-danger"><span class="fa fa-trash" id="' + revIncr + '"></span></button>' +
            '</h5>' +
            '</div>' +
            '<div id="collapse' + revIncr + '" class="collapse show" data-parent="#rev_acc">' +
            '<div class="card-body">' +
            '<div class="form-row mb-2">' +
            '<div class="col-3">' +
            '<select class="form-control" id="trk_title' + revIncr + '" name="trk_title[]" placeholder="Title">' +
            select +
            '</select>' +
            '</div>' +
            '<div class="col autocomplete">' +
            '<input type="text" class="form-control " id="trk_rev' + revIncr + '" name="trk_rev[]" placeholder="Search by Name/Specialization/Non-member/Non-account">' +
            '</div>' +
            '</div>' +
            '<div class="form-row mb-2">' +
            '<div class="col">' +
            '<input type="text" class="form-control" placeholder="Email" id="trk_rev_email' + revIncr + '" name="trk_rev_email[]">' +
            '</div>' +
            '<div class="col">' +
            '<input type="text" class="form-control" placeholder="Contact" id="trk_rev_num' + revIncr + '" name="trk_rev_num[]">' +
            '</div>' +
            '<input type="hidden" id="trk_rev_id' + revIncr + '" name="trk_rev_id[]">' +
            '</div>' +
            '<div class="form-row">' +
            '<div class="col">' +
            '<input type="text" class="form-control" placeholder="Specialization" id="trk_rev_spec' + revIncr + '" name="trk_rev_spec[]">' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>';

        $('#rev_acc').append(html);
        autocomplete(document.getElementById("trk_rev" + revIncr), mem_exp, '#trk_rev_email' + revIncr, '#trk_rev_num' + revIncr, '#trk_rev_id' + revIncr, revIncr, '#trk_rev_spec' + revIncr, '#trk_title' + revIncr);

        $('#trk_rev' + revIncr).rules("add", {
            required: true
        });

        $('#trk_rev_email' + revIncr).rules("add", {
            required: true
        });

        $('#trk_title' + revIncr).rules("add", {
            required: true
        });

        $('#trk_rev_spec' + revIncr).rules("add", {
            required: true
        });

        $('#trk_title' + revIncr).editableSelect({
            effects: 'slide'
        });


        var mail = '';

        mail = '<div class="card">' +
            '<div class="card-header p-0" id="heading' + revIncr + '" data-toggle="collapse" data-target="#collapse_mail' + revIncr + '">' +
            '<h5 class="mb-0">' +
            '<button class="btn btn-link" type="button">' +
            '<span class="fa fa-envelope"></span> Reviewer ' + revIncr + ' : <span id="rev_header_mail' + revIncr + '"' +
            '</button>' +
            '</h5>' +
            '</div>' +

            '<div id="collapse_mail' + revIncr + '" class="collapse show" data-parent="#rev_acc_mail">' +
            '<div class="card-body p-0">' +
            '<textarea type="text" id="tiny_mail' + revIncr + '" name="tiny_mail[]" style="height:500px"></textarea>' +
            '</div>' +
            '</div>' +
            '</div>';

            
            
        $('#rev_acc_mail').append(mail);


        $('#trk_rev' + revIncr).focus();
        load_email_content(revIncr);
  

        
// console.log(revIncr)

    });

    // remove added reviewer
    $('#rev_acc').on('click', '.btn .fa-trash', function() {
        
        $(this).closest('#added_rev').remove();
        var find_mail = $(this).closest('#added_rev').find('.btn-link').text();
        $('#rev_acc_mail').find('.card:contains(' + find_mail + ')').remove();

        var id = $(this).attr("id");
        // alert(id);
     
        tinyMCE.get("tiny_mail" + id).remove();


        // revIncr--;
    });

    // rename card header based on selected reviewer
    $('#rev_acc').on('click', '.card-header', function() {
        var find_mail = $(this).closest('#added_rev').find('.btn-link').text();
        $('#rev_acc_mail').find('.card-header:contains(' + find_mail + ')').click();
        
    });

    // auto total score entered in evaluation form of reviewer
    $(document).on("keyup", ".crt_score", function(e) {
        if (/\D/g.test(this.value)) {
            // Filter non-digits from input value.
            this.value = this.value.replace(/\D/g, '');
        }

        var sum = 0;

        $(".crt_score").each(function() {
            sum += +$(this).val();
        });

        $("#crt_total").val(sum);
    });

    // validate review of manuscript
    $("#submit_review_form").validate({
        debug: true,
        errorClass: 'text-danger',
        rules: {
            scr_crt_1: {
                required: true,
                max: parseInt($('#scr_crt_1').closest('td').prev('td').text()),
            },
            scr_crt_2: {
                required: true,
                max: parseInt($('#scr_crt_2').closest('td').prev('td').text()),
            },
            scr_crt_3: {
                required: true,
                max: parseInt($('#scr_crt_3').closest('td').prev('td').text()),
            },
            scr_crt_4: {
                required: true,
                max: parseInt($('#scr_crt_4').closest('td').prev('td').text()),
            },
            scr_status: {
                required: true,
            }
        },
        messages: {
            scr_crt_1: {
                max: 'Max (' + $('#scr_crt_1').closest('td').prev('td').text() + ')'
            },
            scr_crt_2: {
                max: 'Max (' + $('#scr_crt_2').closest('td').prev('td').text() + ')'
            },
            scr_crt_3: {
                max: 'Max (' + $('#scr_crt_3').closest('td').prev('td').text() + ')'
            },
            scr_crt_4: {
                max: 'Max (' + $('#scr_crt_4').closest('td').prev('td').text() + ')'
            },
            // scr_status: {
            //     required: "Please "
            // }
        },
        submitHandler: function() {

            $('#confirmSubmitReviewModal').modal('toggle');
        }
    });

    // submit review manuscript
    $('#submit_review_manuscript').click(function(){
        $('#confirmSubmitReviewModal').modal('toggle');
        $('#startReviewModal').modal('toggle');
            $('body').loading('start');

            var form = $('#submit_review_form');
            var fromdata = false;
            if (window.FormData) {
                formdata = new FormData(form[0]);
            }
            var formAction = form.attr('action');

            $.ajax({
                url: base_url + "oprs/manuscripts/review/" + man_id,
                data: formdata ? formdata : form.serialize(),
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                crossDomain: true,
                success: function(data, textStatus, jqXHR) {
                    location.reload();
                    // console.log(data);
                }
            });
    });

    $(document).on('hide.bs.modal', '#reviewerModal', function() {
        for (i = 0; i < 100; i++) {
            window.clearInterval(i);
        }
    });

    // unused
    $('#refresh_captcha').click(function() {
        $.ajax({
            type: "GET",
            url: base_url + "oprs/signup/refresh_captcha/",
            dataType: "json",
            crossDomain: true,
            success: function(data) {
                $('#new_captcha').empty();
                $('#new_captcha').append(data.image);
                $('#hidden_captcha').val(data.word);
            }
        });
    });

    // approve manuscript (for finalization)
    $('#btn_approve').click(function() {
        if ($('#man_page_position').val() != '') {
            //approve and publish Manuscripts
            $('body').loading('start');
            $('.modal').modal('hide');

            $.ajax({
                url: base_url + "oprs/manuscripts/approve/" + man_id + "/" + $('#man_page_position').val(),
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                crossDomain: true,
                success: function(data, textStatus, jqXHR) {
                    location.reload();
                    // console.log(data);
                }
            });
        }
    });

   // replaced (volume) in tinymce after entering/select reviewer
   $("#jor_volume").blur(function() {

    var jor_vol = localStorage.getItem('jor_vol');
    localStorage.setItem('jor_vol', $(this).val());
    var text = $(this).val();
    var mail = $(tinymce.get('tiny_mail1').getBody()).html();

    if (mail.indexOf('[VOLUME]') != -1) {
        var new_mail = mail.replace('[VOLUME]', 'Volume ' + text.toUpperCase());
    } else {
        var new_mail = mail.replace('Volume ' + jor_vol.toUpperCase(), 'Volume ' + text.toUpperCase());
    }

    var tiny_count = $('#rev_acc_mail .card-body').length;

    for (i = 1; i <= tiny_count; i++) {
        $(tinymce.get('tiny_mail' + i).getBody()).html(new_mail);
    }
    });

    // replaced [ISSUE] in tinymce after entering/select reviewer 
    $("#jor_issue").blur(function() {

        var jor_iss = localStorage.getItem('jor_issue');
        localStorage.setItem('jor_issue', $(this).val());
        var text = $(this).val();
        var mail = $(tinymce.get('tiny_mail1').getBody()).html();
        var prefix = text >= 5 ? 'Special Issue No. ' + (text - 4) : 'Issue ' + text;
        var prefix_rep = jor_iss >= 5 ? 'Special Issue No. ' + (jor_iss - 4) : 'Issue ' + jor_iss;

        if (mail.indexOf('[ISSUE]') != -1) {

            var new_mail = mail.replace('[ISSUE]', prefix);
        } else {
            var new_mail = mail.replace(prefix_rep, prefix);
        }

        var tiny_count = $('#rev_acc_mail .card-body').length;

        for (i = 1; i <= tiny_count; i++) {
            $(tinymce.get('tiny_mail' + i).getBody()).html(new_mail);
        }
    });

    // replaced [YEAR] in tinymce after entering/select reviewer 
    $("#jor_year").blur(function() {
    

        var jor_year = localStorage.getItem('jor_year');
        localStorage.setItem('jor_year', $(this).val());
        var mail = $(tinymce.get('tiny_mail1').getBody()).html();
    
        if (mail.indexOf('[YEAR]') != -1) {
            var new_mail = mail.replace('[YEAR]', $(this).val());
        } else {
            var new_mail = mail.replace(jor_year, $(this).val());
        }

        var tiny_count = $('#rev_acc_mail .card-body').length;

        for (i = 1; i <= tiny_count; i++) {
            $(tinymce.get('tiny_mail' + i).getBody()).html(new_mail);
        }

        
    });

    // show journal counts per year 
    $('#art_year').on('change', function() {
        $.ajax({
            type: "POST",
            url: base_url + "oprs/manuscripts/volume_issue/" + $(this).val(),
            dataType: "json",
            crossDomain: true,
            success: function(data) {
                if (data.length > 0) {
                    var iss_ctr = 0;
                    $('#art_issue').empty();
                    $('#art_issue').append('<option value="">Select Volume, Issue</option>');
                    $.each(data, function(key, val) {
                        var prefix = val.jor_issue >= 5 ? 'Special Issue No. ' + (val.jor_issue - 4) : 'Issue ' + val.jor_issue;


                        $('#art_issue').append('<option value=' + val.jor_volume + '-' + val.jor_issue + '>Volume ' + val.jor_volume + ', ' + prefix + '</option>');

                        iss_ctr++;
                    });
                    $('#art_issue').next('small').remove();
                    $('#art_issue').after('<small class="text-primary"><span class="fa fa-exclamation-circle"></span> ' + iss_ctr + ' Journal/s</small>');
                }
            }
        });
    });

    // show article counts per year
    // $('#jor_year, #jor_issue, #jor_volume').on('change', function() {

    //     $('#jor_year').next('small').remove();

    //     if ($('#jor_year').val().length != 0) {
    //         $.ajax({
    //             type: "POST",
    //             url: base_url + "oprs/manuscripts/get_jor_id/" + $('#jor_volume').val() + "/" + $('#jor_issue').val() + "/" + $('#jor_year').val(),
    //             dataType: "json",
    //             crossDomain: true,
    //             success: function(data) {

    //                 $('#jor_year').after('<small class="text-primary"><span class="fa fa-exclamation-circle"></span> ' + data + ' Article/s</small>');
    //             }
    //         });
    //     }
    // });

    // replaced (volume issue) in tinymce after entering/select reviewer
    $("#art_issue").on('change', function() {

        var text = $('#art_issue option:selected').text();

        var art_iss = localStorage.getItem('art_iss');
        localStorage.setItem('art_iss', text);


        var mail = $(tinymce.get('tiny_mail1').getBody()).html();

        if (mail.indexOf('[VOLUME]') != -1) {
            var new_mail = mail.replace('[VOLUME], [ISSUE]', text);
        } else {
            var new_mail = mail.replace(art_iss, text);
        }

        var tiny_count = $('#rev_acc_mail .card-body').length;

        for (i = 1; i <= tiny_count; i++) {
            $(tinymce.get('tiny_mail' + i).getBody()).html(new_mail);
        }
    });

    // display exisiting journal volume issue
    $('#article-tab').click(function() {
        var jor_vol = localStorage.getItem('jor_vol');
        var jor_iss = localStorage.getItem('jor_issue');

        var mail = $(tinymce.get('tiny_mail1').getBody()).html();
        var new_mail = mail.replace('Volume ' + jor_vol.toUpperCase() + ', ', '[VOLUME],');

        if (jor_iss >= 5) {

            var new_mail = new_mail.replace('Special Issue No. ' + (jor_iss - 4), '[ISSUE]');
        } else {
            var new_mail = new_mail.replace('Issue ' + jor_iss, '[ISSUE]');
        }


        var tiny_count = $('#rev_acc_mail .card-body').length;

        for (i = 1; i <= tiny_count; i++) {
            $(tinymce.get('tiny_mail' + i).getBody()).html(new_mail);
        }

        $('#jor_volume').val('');
        $('#jor_issue').val('');
        $('#jor_year').val('');
        $('#jor_year').next().hide();

        localStorage.setItem('jor_vol', '0');
        localStorage.setItem('jor_issue', '0');
    });

    // display input for new journal volume issue
    $('#new-tab').click(function() {
        var split_iss = localStorage.getItem('art_iss');
        var x = split_iss.split(',');
        localStorage.setItem('jor_vol', x[0]);
        localStorage.setItem('jor_issue', $.trim(x[1]));

        var mail = $(tinymce.get('tiny_mail1').getBody()).html();
        var new_mail = mail.replace(x[0], '[VOLUME]');
        var new_mail = new_mail.replace($.trim(x[1]), '[ISSUE]');


        var tiny_count = $('#rev_acc_mail .card-body').length;

        for (i = 1; i <= tiny_count; i++) {
            $(tinymce.get('tiny_mail' + i).getBody()).html(new_mail);
        }

        $('#art_year').val('');
        $('#art_issue').val('');
        $('#art_issue').next().hide();
    });

    // load content of tinymce on adding new reviewer
    // $('#new_rev').click(function() {
    //     $('#form_journal').hide();
    //     $('#process_manuscript_form')[0].reset();
    //     $('#rev_acc .card').not(':first').remove();
    //     $('#rev_acc #collapse1').addClass('show');
    //     $('#rev_acc_mail .card').not(':first').remove();
    //     $('#rev_acc_mail #collapse_mail1').addClass('show');
    //     load_email_content();
    
    // });

    // check if multiple email in one manuscript
    $('#get_email').change(function() {
        if ($(this).val() != '')
            $.ajax({
                type: "GET",
                url: base_url + "support/forgot/check_multiple_account/" + $(this).val(),
                dataType: "json",
                crossDomain: true,
                success: function(data) {
                    $('#user_option').empty();
                    if (data.length > 1) {
                        $('#user_option').append('<p class="font-weight-bold small">You have multiple account. Select (1) account only.</p>');

                        $.each(data, function(key, val) {
                            var role = (val.usr_role == 1) ? 'Author' : 'Reviewer';

                            $('#user_option').append('<div class="custom-control custom-radio">' +
                                '<input type="radio" value="' + val.usr_id + '" id="' + role + '' + val.usr_role + '" name="usr_id" class="custom-control-input">' +
                                '<label class="custom-control-label pt-1" for="' + role + '' + val.usr_role + '"> ' + val.usr_username + ' (' + role + ')</label>' +
                                '</div>');
                        });
                    } else {

                        $('#user_option').append('<p class="font-weight-bold small">Current account:</p>');

                        $.each(data, function(key, val) {
                            var role = (val.usr_role == 1) ? 'Author' : 'Reviewer';

                            $('#user_option').append('<div class="custom-control custom-radio">' +
                                '<input type="radio" checked value="' + val.usr_id + '" id="' + role + '' + val.usr_role + '" name="usr_id" class="custom-control-input">' +
                                '<label class="custom-control-label pt-1" for="' + role + '' + val.usr_role + '"> ' + val.usr_username + ' (' + role + ')</label>' +
                                '</div>');
                        });
                    }
                }
            });
    });

    // for author/reviewer multiple account validation (unused)
    $('.login #usr_username').change(function() {
        if ($(this).val() != '')
            $.ajax({
                type: "GET",
                url: base_url + "oprs/login/check_multiple_account/" + $(this).val(),
                dataType: "json",
                crossDomain: true,
                success: function(data) {
                    // console.log(data);
                    $('#user_option').empty();
                    if (data.length > 1) {
                        $('#user_option').append('<p class="font-weight-bold small">You have multiple account. Select (1) account only.</p>');

                        $.each(data, function(key, val) {
                            // var role = (val.usr_role == 1) ? 'Author' : 'Reviewer';
                            var usr_id = (val.usr_grp_id > 0) ? val.usr_id : val.usr_id;
                            var role = (val.usr_grp_id > 0) ? 'Author' : (val.usr_role == 1) ? 'Author' : 'Reviewer';
                            var usr_role = (val.usr_grp_id > 0) ? '1' : (val.usr_role == 1) ? '1' : '5';
                            var usr_username = (val.usr_grp_id > 0) ? val.usr_name : val.usr_username;

                            $('#user_option').append('<div class="custom-control custom-radio">' +
                                '<input type="radio" value="' + usr_role + '" id="' + role + '' + usr_id + '" name="usr_role" class="custom-control-input">' +
                                '<label class="custom-control-label pt-1" for="' + role + '' + usr_id + '"> ' + usr_username + ' (' + role + ')</label>' +
                                '</div>');
                        });
                    }
                }
            });
    });

    // unused
    $('#revise_review').click(function() {
        $('#approve_review').prop('checked', true);
    });

    // unused
    $('#disapprove_review').click(function() {
        $('#revise_review').prop('checked', false);
    });

    // change password
    $("#form_change_pass").validate({
        debug: true,
        errorClass: 'text-danger',
        rules: {
            usr_password: {
                required: true,
                minlength: 5
            },
            old_password: {
                required: true,
                minlength: 5,
                remote: {
                    url: base_url + "oprs/user/verify_old_password/",
                    type: "post"
                }
            },
            repeat_password: {
                required: true,
                minlength: 5,
                equalTo: "#usr_password"
            }
        },
        messages: {
            usr_password: {
                required: "Please enter old password",
                minlength: "Your password must be at least 5 characters long"
            },
            old_password: {
                required: "Please enter new password",
                minlength: "Your password must be at least 5 characters long",
                remote: "Incorrect password"
            },
            repeat_password: {
                required: "Please repeat new password",
                minlength: "Your password must be at least 5 characters long",
                equalTo: "Please enter the same password as above"
            }
        },
        submitHandler: function() {
            $.ajax({
                type: "POST",
                url: base_url + "oprs/user/change_password/",
                data: $('#form_change_pass').serializeArray(),
                cache: false,
                crossDomain: true,
                success: function(data) {

                    $('#form_change_pass')[0].reset();
                    $('#changePassModal').modal('toggle');
                    $.notify({
                        icon: 'fa fa-check-circle',
                        message: 'Password changed successfull.'
                    }, {
                        type: 'success',
                        timer: 3000,
                    });

                }
            });
        }
    });

    $(document).on('blur', 'input[name="trk_rev[]"]', function() {
        var id = $(this).attr('id');
        var pos = id.substr(id.length - 1);

        $('#rev_header' + pos).text($(this).val());
        $('#rev_header_mail' + pos).text($(this).val());
    });

    // unused
    $('#custom_auth').click(function() {
        $('#man_author').val('');
        $('#man_affiliation').val('');
        $('#man_email').val('');
    });

    // unused
    $('#default_auth').click(function() {
        $.ajax({
            type: "POST",
            url: base_url + "oprs/manuscripts/default_auth/",
            dataType: "json",
            cache: false,
            crossDomain: true,
            success: function(data) {
                $.each(data, function(key, val) {
                    if (val.non_first_name != null) {
                        $('#man_author').val(val.non_first_name + ' ' + val.non_middle_name + ' ' + val.non_last_name);
                        $('#man_affiliation').val(val.non_affiliation);
                        $('#man_email').val(val.non_email);
                    } else if (val.bus_name != null) {
                        $('#man_author').val(val.pp_first_name + ' ' + val.pp_middle_name + ' ' + val.pp_last_name);
                        $('#man_affiliation').val(val.bus_name);
                        $('#man_email').val(val.pp_email);
                    } else {
                        $('#man_author').val(val.pp_first_name + ' ' + val.pp_middle_name + ' ' + val.pp_last_name);
                        $('#man_email').val(val.pp_email);
                    }
                });
            }
        });

    });

    // dynamicallly show user role per system access
    $('#usr_sys_acc').change(function() {
        $('#usr_role').prop('disabled', false);

        $('#usr_role').empty();

        if ($(this).val() == 1) {
            $('#usr_role').append('<option value="" selected>Select User Role</option>' +
                '<option value="7">Admin</option>' +
                '<option value="6">Manager</option>');
        }else if($(this).val() == 2) {
            $('#usr_role').append('<option value="" selected>Select User Role</option>' +
                '<option value="7">Admin</option>' +
                '<option value="9">Publication Committee</option>' +
                '<option value="3">Managing Editor</option>' +
                '<option value="6">Manager</option>' +
                '<option value="10">Editor</option>' +
                '<option value="11">Guest Editor</option>' +
                '<option value="12">Editor-in-Chief</option> '+
                '<option value="13">Layout</option>');
        }else{
            $('#usr_role').append('<option value="" selected>Select User Role</option>' +
                '<option value="3">Managing Editor</option>');
        }
    });

    // edit user
    $('#editUserModal #usr_sys_acc').change(function() {
        $('#editUserModal #usr_role').prop('disabled', false);

        $('#editUserModal #usr_role').empty();

        if ($(this).val() == 1) {
            $('#editUserModal #usr_role').append('<option value="" selected>Select User Role</option>' +
                '<option value="7">Admin</option>' +
                '<option value="6">Manager</option>');
        }else if($(this).val() == 2) {
            $('#editUserModal #usr_role').append('<option value="" selected>Select User Role</option>' +
                '<option value="7">Admin</option>' +
                '<option value="9">Publication Committee</option>' +
                '<option value="3">Managing Editor</option>' +
                '<option value="6">Manager</option>' +
                '<option value="10">Editor</option>' +
                '<option value="11">Guest Editor</option>' +
                '<option value="12">Editor-in-Chief</option> '+
                '<option value="13">Layout</option>');
        }else{
            $('#editUserModal #usr_role').append('<option value="" selected>Select User Role</option>' +
                '<option value="3">Managing Editor</option>');
        }
    });

    // show users privileges
    $('#user_control').change(function() {
        var usr_grp = $(this).val();

        $.ajax({
            type: "GET",
            url: base_url + "oprs/user/get_user_by_role/" + usr_grp,
            dataType: "json",
            crossDomain: true,
            success: function(data) {

                
                if ($.fn.DataTable.isDataTable("#controls_table")) {
                    $('#controls_table').DataTable().clear().destroy();
                }

                if(data.length > 0){
                    $.each(data, function(key, val) {
                            var c = 1;
                      

                                var check_add = (val.prv_add == 1) ? 'checked' : '';
                                var check_edit = (val.prv_edit == 1) ? 'checked' : '';
                                var check_delete = (val.prv_delete == 1) ? 'checked' : '';
                                var check_view = (val.prv_view == 1) ? 'checked' : '';
                                var check_export = (val.prv_export == 1) ? 'checked' : '';
                                var access = (val.usr_sys_acc == 1) ? 'eJournal' : (val.usr_sys_acc == 2) ? 'eReview' : 'eJournal-eReview';

                                var html = "<div class='form-check form-check-inline'> \
                                            <input class='form-check-input' type='checkbox' name='prv_add[]' value='" + val.usr_id + "' " + check_add + "> \
                                            <label class='form-check-label'>Add</label> \
                                        </div> \
                                        <div class='form-check form-check-inline'> \
                                            <input class='form-check-input' type='checkbox' name='prv_edit[]' value='" + val.usr_id + "' " + check_edit + "> \
                                            <label class='form-check-label'>Edit</label> \
                                        </div> \
                                        <div class='form-check form-check-inline'> \
                                            <input class='form-check-input' type='checkbox' name='prv_delete[]' value='" + val.usr_id + "' " + check_delete + "> \
                                            <label class='form-check-label'>Delete</label> \
                                        </div> \
                                        <div class='form-check form-check-inline'> \
                                            <input class='form-check-input' type='checkbox' name='prv_view[]' value='" + val.usr_id + "' " + check_view + " disabled> \
                                            <label class='form-check-label'>View</label> \
                                        </div> \
                                        <div class='form-check form-check-inline'> \
                                            <input class='form-check-input' type='checkbox' name='prv_export[]' value='" + val.usr_id + "' " + check_export + "> \
                                            <label class='form-check-label'>Export</label> \
                                        </div>";

                                $('#controls_table').dataTable().fnAddData([
                                    c++,
                                    val.usr_username,
                                    access,
                                    html
                                ]);
                                r++;
                           



                            var t = $('#controls_table').DataTable();
                            t.on('order.dt search.dt', function() {
                                t.column(0, {
                                    search: 'applied',
                                    order: 'applied'
                                }).nodes().each(function(cell, i) {
                                    cell.innerHTML = i + 1;
                                });
                            }).draw();
                    });
                }else{
                    var t = $('#controls_table').DataTable();
                            t.on('order.dt search.dt', function() {
                                t.column(0, {
                                    search: 'applied',
                                    order: 'applied'
                                }).nodes().each(function(cell, i) {
                                    cell.innerHTML = i + 1;
                                });
                            }).draw();
                }
            }
        });

    });

    // privilege to add
    $(document).on('click', 'input[name="prv_add[]"]', function() {
        var priv = ($(this).prop("checked") == true) ? 1 : 0;
        var id = $(this).val();

        $.ajax({
            type: "POST",
            url: base_url + "oprs/user/set_privilege/1/" + id + "/" + priv,
            dataType: "json",
            cache: false,
            crossDomain: true,
            success: function(data) {
                console.log(data);
            }
        });

    });

    // privilege to edit
    $(document).on('click', 'input[name="prv_edit[]"]', function() {
        var priv = ($(this).prop("checked") == true) ? 1 : 0;
        var id = $(this).val();

        $.ajax({
            type: "POST",
            url: base_url + "oprs/user/set_privilege/2/" + id + "/" + priv,
            dataType: "json",
            cache: false,
            crossDomain: true,
            success: function(data) {
                console.log(data);
            }
        });
    });

    // privilege to delete
    $(document).on('click', 'input[name="prv_delete[]"]', function() {
        var priv = ($(this).prop("checked") == true) ? 1 : 0;
        var id = $(this).val();

        $.ajax({
            type: "POST",
            url: base_url + "oprs/user/set_privilege/3/" + id + "/" + priv,
            dataType: "json",
            cache: false,
            crossDomain: true,
            success: function(data) {
                console.log(data);
            }
        });
    });

    // privilege to view
    $(document).on('click', 'input[name="prv_view[]"]', function() {
        var priv = ($(this).prop("checked") == true) ? 1 : 0;
        var id = $(this).val();

        $.ajax({
            type: "POST",
            url: base_url + "oprs/user/set_privilege/4/" + id + "/" + priv,
            dataType: "json",
            cache: false,
            crossDomain: true,
            success: function(data) {
                console.log(data);
            }
        });
    });

    // privilege to export
    $(document).on('click', 'input[name="prv_export[]"]', function() {
        var priv = ($(this).prop("checked") == true) ? 1 : 0;
        var id = $(this).val();

        $.ajax({
            type: "POST",
            url: base_url + "oprs/user/set_privilege/5/" + id + "/" + priv,
            dataType: "json",
            cache: false,
            crossDomain: true,
            success: function(data) {
                console.log(data);
            }
        });
    });

    // process import backup
    $("#import_backup_form").validate({
        debug: true,
        errorClass: 'text-danger',
        rules: {
            import_backup: {
                required: true,
                extension: 'xls|csv',
            },
        },
        submitHandler: function() {
            
            $('body').loading('start');

            var form = $('#import_backup_form');
            var fromdata = false;
            if (window.FormData) {
                formdata = new FormData(form[0]);
            }
            var formAction = form.attr('action');

            $.ajax({
                url: base_url + "oprs/logs/import_backup/",
                data: formdata ? formdata : form.serialize(),
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                crossDomain: true,
                success: function(data, textStatus, jqXHR) {
                    location.reload();
                    // console.log(data);
                }
            });
        }
    });

    // clear logs
    $('#btn_clear_logs').click(function()
    {   
        $('#clearLogsModal').modal('toggle');
        $('body').loading('start');

        $.ajax({
            url: base_url + "oprs/logs/clear_logs/",
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            crossDomain: true,
            success: function(data, textStatus, jqXHR) {
                location.reload();
                // console.log(data);
            }
        });
    });

    // non member
    $('input:radio[name="non_member"]').change(function(){
        var val = $(this).val();
        $('.principal').find('span').empty();
        if(val == 1){
            $('.principal').append(' <span class="badge badge-primary">Type Non-Member</span>');
            $('#man_usr_id').val('');
        }else{
            $('.principal').append(' <span class="badge badge-danger">Search Member</span>');
        }

        $('#man_author').val('');
        $('#man_affiliation').val('');
        $('#man_email').val('');
    });

    // save feedback
    $('input:radio[name="non_member"]').change(
    function(){
        if (this.checked) {
                $(".ui-container .alert-danger").remove();
        }
    });
    
    // validate ux rate selection
    $('input:radio[name="fb_rate_ux"]').change(
    function(){
        if (this.checked) {
                $(".ux-container .alert-danger").remove();
        }
    });
    
    // submit ui/ux feedback form
    $('#feedback_form').on('submit', function(e){

    e.preventDefault();

    var alert = '<div class="alert alert-danger w-100" role="alert"> \
                        Please select your rating. \
                        </div>';

    if (!$("input[name='fb_rate_ui']").is(':checked')) {
        $(".ui-container .alert-danger").remove();
        $(alert).hide().appendTo(".ui-container").fadeIn();
    }
    
    if (!$("input[name='fb_rate_ux']").is(':checked')) {
        $(".ux-container .alert-danger").remove();
        $(alert).hide().appendTo(".ux-container").fadeIn();
    }

    if($("input[name='fb_rate_ui']").is(':checked') && $("input[name='fb_rate_ux']").is(':checked')){
        $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var formdata = $(this).serializeArray();
        // console.log(formdata);
        $.ajax({
            type: "POST",
            url: base_url + 'admin/feedback/submit/2',
            data:  formdata,
            cache: false,
            crossDomain: true,
            success: function(data) {
                // console.log(data);return false;
                $('#feedback_form').remove();

                var thanks = '<p class="text-center h2">Thank you for your feedback.</p> \
                                <p class="text-center btn-link font-weight-bold"><u><a href="'+ base_url + 'oprs/login/logout");">Proceed to logout</a></u></p>';
                            
                
                $(thanks).hide().appendTo("#feedbackModal .modal-body").fadeIn();

            }
        });
    }
    });

    // select all structure only for backup
    $("#select_all_structure").change(function() {
      if (this.checked) {
          $("input[name='table_structure[]']").each(function() {
              this.checked=true;
          });
      } else {
          $("input[name='table_structure[]']").each(function() {
              this.checked=false;
          });
      }
    });
  
    // select all data only for backup
    $("#select_all_data").change(function() {
        if (this.checked) {
            $("input[name='table_data[]']").each(function() {
                this.checked=true;
            });
        } else {
            $("input[name='table_data[]']").each(function() {
                this.checked=false;
            });
        }
    });
  
    $('#sd_table').hide();
  
    // hide structure/data table if quick export
    $('#quick_export').change(function(){
  
        $('#sd_table').hide();
    });
  
    // show structure/data table if custom export
    $('#custom_export').change(function(){
  
        $('#sd_table').show();
    });
  
    // display sql file in text field
    $('#import_file').change(function(){
      $('.custom-file-label').text($(this).val().split('\\').pop());
    });

    // submit sql file to import
    $("#import_db_form").validate({
        debug: true,
        errorClass: 'text-danger',
        rules: {
            import_file: {
                required: true,
            },
        },
        errorLabelContainer: '.invalid-feedback',
        submitHandler: function() {
          
            var form = $('#import_db_form');
            var formdata = false;
    
            if(window.FormData)
            {
              formdata = new FormData(form[0]);
            }
    
            $.ajax({
                type: "POST",
                url: base_url + 'oprs/backup/import',
                data : formdata ? formdata :form.serialize(),
                contentType: false,
                processData: false,
                success: function(response) {
                  
                        if(response == 1){
                            $('#success_import').hide().append('<div class="alert alert-success" role="alert"> \
                            SQL file imported successfully! \
                            </div>').fadeIn(1000);
                        }
                }
            });
        }
      });

    // add remarks in manuscriot (managing editor only)
    $("#remarks_form").validate({
    debug: true,
    errorClass: 'text-danger',
    rules: {
        man_remarks: {
            required: true,
        },
    },
    submitHandler: function() {
        var form = $('#remarks_form');
        var formdata = false;

        if(window.FormData)
        {
            formdata = new FormData(form[0]);
        }

        formdata.append('man_id', man_id);

        $.ajax({
            type: 'POST',
            url: base_url + "oprs/manuscripts/add_remarks/",
            data : formdata ? formdata :form.serialize(),
            cache: false,
            contentType: false,
            processData: false,
            success: function(response) {
            }
        });
        location.reload();
    }
    });

    // display nda file in text field
    $('#scr_nda').change(function(){
    $('.custom-file-label').text($(this).val().split('\\').pop());
    });

    // display nda file in text field
    $('#edit_file').change(function(){
    $('.custom-file-label').text($(this).val().split('\\').pop());
    });

      // submit nda file 
    $('#submit_nda').on('submit', function(e){
        var form = $('#submit_nda');
        var formdata = false;

        if(window.FormData)
        {
            formdata = new FormData(form[0]);
        }

        $.ajax({
            type: "POST",
            url: base_url + 'oprs/manuscripts/upload_nda',
            data : formdata ? formdata :form.serialize(),
            contentType: false,
            processData: false,
            success: function(response) {
                window.location.reload();
            }
        });
    });

    $('#update_email_content_btn').click(function(){
        var enc_content = tinyMCE.get('enc_content').getContent();
        var formData = $('#email_content_form').serializeArray();
        formData.push({ name: 'enc_content', value: enc_content });
        
        $.ajax({
            type: "POST",
            url: base_url + 'oprs/emails/update_email_content/',
            data:  formData,
            cache: false,
            crossDomain: true,
            success: function(data) {
            }
        });

        location.reload();
    });


    $("#submit_editorial_review_form").validate({
        debug: true,
        errorClass: 'text-danger',
        rules: {
            edit_file: {
                required: true,
            },
        },
        messages: {},
        errorElement : 'div',
        errorLabelContainer: '.errorTxt',
        submitHandler: function() {

            var form = $('#submit_editorial_review_form');
            var formdata = false;
    
            if(window.FormData)
            {
                formdata = new FormData(form[0]);
            }
    
            $.ajax({
                type: "POST",
                url: base_url + 'oprs/manuscripts/editorial_review',
                data : formdata ? formdata :form.serialize(),
                contentType: false,
                processData: false,
                success: function(response) {
                    location.reload();
                }
            });

            $('#editorialModal').modal('toggle');
        }
    });

    // submit to layout manager
    $("#publication_form").validate({
        debug: true,
        errorClass: 'text-danger',
        submitHandler: function() {
            $.ajax({
                type: "POST",
                url: base_url + "oprs/manuscripts/for_publication/",
                data: $('#publication_form').serializeArray(),
                cache: false,
                crossDomain: true,
                success: function(data) {
                    location.reload();
                    // console.log(data);
                }
            });
        }
    });

    // submit publishable manuscript 
    $('#publishable_form').on('submit', function(e){
        var form = $('#publishable_form');
        var formdata = false;

        if(window.FormData)
        {
            formdata = new FormData(form[0]);
        }

        $.ajax({
            type: "POST",
            url: base_url + 'oprs/manuscripts/upload_publishable',
            data : formdata ? formdata :form.serialize(),
            contentType: false,
            processData: false,
            success: function(response) {
                // window.location.reload();
                console.log(response);
            }
        });
    });

    // publish to ejournal 
    $('#pub_to_e_form').on('submit', function(e){
        var form = $('#pub_to_e_form');
        var formdata = false;

        if(window.FormData)
        {
            formdata = new FormData(form[0]);
        }

        $.ajax({
            type: "POST",
            url: base_url + 'oprs/manuscripts/publish',
            data : formdata ? formdata :form.serialize(),
            contentType: false,
            processData: false,
            success: function(response) {
                // window.location.reload();
                console.log(response);
            }
        });
    });


});

// count character for limited input in remarks
function countChar(val) {
    var len = val.value.length;
    if (len >= 255) {
        val.value = val.value.substring(0, 255);
    } else {
        $('.limit').text(254 - len + '/255');
    }
};

// process manuscript by adding reviewers
// show modal with tinymcye
// add many reviewers
function process_man(id) {

    tinyMCE.remove();
    
    revIncr = 1;
    $('#process_manuscript_form')[0].reset();
    $('#rev_acc .card').not(':first').remove();
    $('#rev_acc #collapse1').addClass('show');
    $('#rev_acc_mail .card').not(':first').remove();
    $('#rev_acc_mail #collapse_mail1').addClass('show');
    
    man_id = id;

    $.ajax({
        type: "GET",
        url: base_url + "oprs/manuscripts/get_manuscript_by_id/"+id,
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            $.each(data, function(key, val) {
                mail_title = val.man_title;

                $('#jor_volume').val(val.man_volume);
                $('#jor_issue').val(val.man_issue);
                $('#jor_year').val(val.man_year);

                if(val.man_status > 1){
                    localStorage.setItem('jor_vol', val.man_volume);
                    localStorage.setItem('jor_issue', val.man_issue);
                    localStorage.setItem('jor_year', val.man_year);
                }
           
            });

        }
    });

    $('#form_journal').show();
    if($('#trk_rev1').val() == ''){
        load_email_content();       
    }

    
}

// process manuscript by adding editor
// show modal with tinymcye
function edit_man(id) {

    tinyMCE.remove();
    
    revIncr = 1;
    $('#edit_manuscript_form')[0].reset();
    $('#rev_acc .card').not(':first').remove();
    $('#rev_acc #collapse1').addClass('show');
    $('#rev_acc_mail .card').not(':first').remove();
    $('#rev_acc_mail #collapse_mail1').addClass('show');
    
    man_id = id;

    $.ajax({
        type: "GET",
        url: base_url + "oprs/manuscripts/get_manuscript_by_id/"+id,
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            $.each(data, function(key, val) {
                mail_title = val.man_title;

                $('#edit_manuscript_form #jor_volume').val(val.man_volume);
                $('#edit_manuscript_form #jor_issue').val(val.man_issue);
                $('#edit_manuscript_form #jor_year').val(val.man_year);

                if(val.man_status > 1){
                    localStorage.setItem('jor_vol', val.man_volume);
                    localStorage.setItem('jor_issue', val.man_issue);
                    localStorage.setItem('jor_year', val.man_year);
                }
           
            });

        }
    });

    $('#form_journal').show();
    if($('#editor_rev1').val() == ''){
        load_editor_email_content();       
    }

    
}

// show tracking modal
function tracking(id, role, title, status) {
    

    if(status == 1){
        $('#trackingModal .dropdown').show();
    }else{
        $('#trackingModal .dropdown').hide();
    }


    var manuscript_title = decodeURIComponent(title);
    man_id = id;
    $.ajax({
        type: "GET",
        url: base_url + "oprs/manuscripts/tracker/" + id,
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            $('#track_list').empty();
            var html = '';
            var trk_c = 0;
            if (data.length > 0) {

                $.each(data, function(key, val) {
                    trk_c++;
                    var user = get_processor(val.trk_processor, val.trk_source, role);
                    var proc_id = val.trk_processor;

                    if (proc_id.indexOf('R') != -1 || val.trk_source == '_sk_r') {
                        var user_role = '<span class="text-muted small">Reviewer</span>';
                        var user_action = 'Reviewed manuscript';
                        review(proc_id, trk_c, user);
                    } else {
                        if (val.trk_source == '_sk' || val.trk_source == '_op') {
                            var user_role = '<span class="text-muted small">Author</span>';
                            if (val.trk_remarks == null) {
                                var user_action = 'Submitted initial manuscript for review';
                            } else {
                                var user_action = '<Submitted final manuscript<br/>' +
                                    '<span class="alert p-1 mt-1 mb-0 pb-0"><strong>Remarks:</strong> ' +
                                    val.trk_remarks +
                                    '</span>';
                            }

                        } else {

                            if(val.trk_description == 'EDITOR'){
                                var user_role = '<span class="text-muted small">Managing Editor</span>';
                                var user_action = 'Submitted to Editor-in-Chief';
                            }else if(val.trk_description == 'FINAL'){
                                var user_role = '<span class="text-muted small">Editor-in-Chief</span>';
                                var user_action = 'Submitted to author for revision/final manuscript'; 
                            }else if(val.trk_description == 'LAYOUT'){
                                var user_role = '<span class="text-muted small">Editor-in-Chief</span>';
                                var user_action = 'Submitted to Layout Manager';
                            }else if(val.trk_description == 'PUBLISHABLE'){
                                var user_role = '<span class="text-muted small">Managing Editor</span>';
                                var user_action = 'Submitted to Editor-in-Chief for finalization';
                            }else if(val.trk_description == 'PUBLISHED'){
                                var user_role = '<span class="text-muted small">Managing Editor</span>';
                                var user_action = 'Manuscript is published and viewable in <a href="https://researchjournal.nrcp.dost.gov.ph/" target="_blank">researchjournal.nrcp.dost.gov.ph</a>'; 
                            }else if(val.trk_description == 'PUBLISHED TO OTHER JOURNAL PLATFORM'){
                                var user_role = '<span class="text-muted small">Managing Editor</span>';
                                var user_action = 'Manuscript is already published to other journal platform'; 
                           
                            }else {
                                var user_role = '<span class="text-muted small">Managing Editor</span>';
                                var user_action = 'Submitted to reviewers';
                                reviewers(id, trk_c, val.trk_remarks, role, val.trk_description, val.trk_process_datetime, title, status);
                            }
                        }

                    }

                    html = '<li class="list-group-item list-group-item-secondary flex-column align-items-start">' +
                        '<div class="d-flex w-100 justify-content-between">' +
                        '<h6 class="mb-1 font-weight-bold">' + user + ' (' + user_role + ')</h6>' +
                        '<small>' + moment(val.trk_process_datetime, 'YYYY-MM-DD HH:mm').format("MMMM D, h:mm a") + '</small>' +
                        '</div>' +
                        '<small class="mb-1">' + user_action + '</small><br/>' +
                        '<span class="usr' + trk_c + '"></span>' +
                        '</li>';

                    $('#track_list').append(html);

                });


                $('#track_list .justify-content-between').first().addClass('text-primary');
                $('#track_list .list-group-item-secondary').first().addClass('list-group-item-primary').removeClass('list-group-item-secondary');


            } else {

                

                html = '<li class="list-group-item list-group-item-secondary flex-column align-items-start">' +
                    '<div class="d-flex w-100 justify-content-between">' +
                    '<h6 class="mb-1 font-weight-bold">Pending action from Managing Editor</h6>' +
                    '</div>' +
                    '<small class="mb-1">You have just submitted manuscript.</small><br/>' +
                    '</li>';
                $('#track_list').append(html);
            }
        }
    });
}

// get name of the processor of manuscript
function get_processor(id, src, u_role) {
    var processor;

    $.ajax({
        type: "POST",
        url: base_url + "oprs/user/get_processor/" + id + "/" + src,
        dataType: "json",
        crossDomain: true,
        async: false,
        success: function(data) {
            // console.log(data);
            $.each(data, function(key, val) {
                if (id.indexOf('R') != -1 || src == '_sk_r') {
                    processor = (u_role == 1 && val.rev_hide_rev == 1) ? 'Undisclosed' : val.rev_name;

                } else if (id.indexOf('NM') != -1) {
                    processor = val.non_first_name + ' ' + val.non_middle_name + ' ' + val.non_last_name;
                } else {
                    if (src == '_sk') {
                        processor = val.pp_first_name + ' ' + val.pp_middle_name + ' ' + val.pp_last_name;
                    } else {
                        processor = val.usr_username;
                    }
                }

            });
        }
    });

    return processor;
}

// export logs
function log_export(act, msg) {
    $.ajax({
        type: "POST",
        url: base_url + "oprs/logs/log_export/" + act + " - " + msg,
        dataType: "json",
        crossDomain: true,
        async: false,
        success: function(data) {
            console.log(data);
        }
    });
}

// get name of a member
function get_member(id) {
    var member;

    $.ajax({
        type: "POST",
        url: base_url + "oprs/user/get_member/" + id,
        dataType: "json",
        crossDomain: true,
        async: false,
        success: function(data) {
            $.each(data, function(key, val) {
                member = val.pp_first_name + ' ' + val.pp_middle_name + ' ' + val.pp_last_name;
            });
        }
    });

    return member;
}

// hide reviewer name
function hide_rev(id, user) {
    var string;

    $.ajax({
        type: "GET",
        url: base_url + "oprs/manuscripts/hide_rev/" + id + "/" + user,
        dataType: "json",
        crossDomain: true,
        async: false,
        success: function(data) {
            $.each(data, function(key, val) {
                if (val.rev_hide_rev == 1) {
                    string = '<em>Undisclosed</em>';
                } else {
                    string = val.rev_name;
                }
            });
        }
    });

    return string;
}

// view manuscript full text or abstract
function manus_view(file, type) {
   
    if(type == 'abs'){
        $('#manus_view').replaceWith($('#manus_view').clone().attr('src', base_url + 'assets/oprs/uploads/initial_abstracts_pdf/' + file));
        $('#manusModal .modal-title').text('Abstract');
    }else if(type == 'full'){
        $('#manus_view').replaceWith($('#manus_view').clone().attr('src', base_url + 'assets/oprs/uploads/initial_manuscripts_pdf/' + file));
        $('#manusModal .modal-title').text('Full Text PDF');
    }else if(type == 'final_absl'){
        $('#manus_view').replaceWith($('#manus_view').clone().attr('src', base_url + 'assets/oprs/uploads/final_abstracts_pdf/' + file));
        $('#manusModal .modal-title').text('Abstract');
    }else{
        $('#manus_view').replaceWith($('#manus_view').clone().attr('src', base_url + 'assets/oprs/uploads/final_manuscripts_pdf/' + file));
        $('#manusModal .modal-title').text('Full Text PDF');
    }     
    
}

function unique(array) {
    return array.filter(function(el, index, arr) {
        return index == arr.indexOf(el);
    });
}

// get all info of uploaded manuscript
function view_manus(id, hide) {

    $('#uploadModal .table-borderless > tbody').empty();

    var coa = [];
    var html = '';

    $.ajax({
        type: "GET",
        url: base_url + "oprs/manuscripts/authors/get/" + id,
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            if (data.length > 0) {
                $.each(data, function(key, val) {
                    coa.push(val.coa_name + ', ' + val.coa_affiliation + ', ' + val.coa_email + '<br/>');
                });
            }
        }
    });

    $.ajax({
        type: "GET",
        url: base_url + "oprs/manuscripts/manuscript/" + id,
        dataType: "json",
        crossDomain: true,
        success: function(data) {

            $.each(data, function(key, val) {

                var vol = (val.man_volume != null) ? val.man_volume : '-';
                var iss = (val.man_issue != null) ? val.man_issue : '-';
                var iss = (iss >= 5) ? 'Special Issue No. ' + (iss - 4) : iss;
                var yer = (val.man_year != null) ? val.man_year : '-';
                var rem = (val.man_remarks != null) ? val.man_remarks : '-';
                var coas = (coa.length > 0) ? coa.join('') : '-';
                var prim = (hide == 1) ? '<em>Undisclosed</em>' : val.man_author + ', ' + val.man_affiliation + ', ' + val.man_email;
                var hide_coas = (hide == 1) ? '<em>Undisclosed</em>' : coas;

                html += '<tr>' +
                    '<th>Title</th>' +
                    '<td>' + val.man_title + '</td>' +
                    '</tr>';

                html += '<tr>' +
                    '<th>Primary Author</th>' +
                    '<td>' + prim + '</td>' +
                    '</tr>';

                html += '<tr>' +
                    '<th>Co-authors</th>' +
                    '<td>' + coas + '</td>' +
                    '</tr>';

                html += '<tr>' +
                    '<th>Volume</th>' +
                    '<td>' + vol + '</td>' +
                    '</tr>';

                html += '<tr>' +
                    '<th>Issue</th>' +
                    '<td>' + iss + '</td>' +
                    '</tr>';

                html += '<tr>' +
                    '<th>Year</th>' +
                    '<td>' + yer + '</td>' +
                    '</tr>';

                html += '<tr>' +
                    '<th>Remarks</th>' +
                    '<td>' + rem + '</td>' +
                    '</tr>';
            });

            $('#uploadModal .table-borderless > tbody').append(html);
            $('#uploadModal').modal('toggle');
            $('#man_file_div').hide();
            $('#man_abs_div').hide();
            $('#man_key_div').hide();
            $('#uploadModal .modal-footer .btn').hide();
            $('#manuscript_form').hide();
            $('.table-borderless').show();
        }
    });

}

// show hidden buttons for uploading manuscript (unused)
function show_hidden_manus() {
    $('#manuscript_form')[0].reset();
    $('#coauthors').empty();
    $('#man_file_div').show();
    $('#man_abs_div').show();
    $('#man_word_div').show();
    $('#uploadModal .modal-footer #btn_add_coa').show();
    $('#uploadModal .modal-footer .btn_cancel').show();
    $('#uploadModal .modal-footer #btn_save').show();
    $('#uploadModal .modal-footer .btn_close').hide();
    $('#manuscript_form').show();
    $('.table-borderless').hide();
}

// show all reviewers per manuscript
function view_reviewers(id, time, title, status) {

    var manuscript_title = decodeURIComponent(title);
    // if(status == 5 || status == 4 || status == 6){ $('#new_rev').hide(); }else{ $('#new_rev').show(); }
    if(status == 5 || status == 4){ $('#new_rev').hide(); }else{ $('#new_rev').show(); }

    $('#reviewerModal p').text('TITLE : ' + manuscript_title);

    man_id = id;

    $('#form_journal').show();

    $.ajax({
        type: "GET",
        url: base_url + "oprs/manuscripts/reviewers/" + id + "/" + time,
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            // console.log(data);
            if ($.fn.DataTable.isDataTable("#table-reviewers")) {
                $('#table-reviewers').DataTable().clear().destroy();
            }

            if (data.length > 0) {
              
                revs = [];
                var c = 0;
                var r = 0;

                $.each(data, function(key, val) {

                    revs.push(val.rev_email);
                    var date = (val.rev_date_respond != null) ? moment(val.rev_date_respond, 'YYYY-MM-DD HH:mm').format("MMMM D, YYYY h:mm a") : '-';
                    var req_status = (val.rev_status == 1) ? '<span class="badge badge-pill badge-success">ACCEPTED</span>' :
                        (val.rev_status == 0) ? '<span class="badge badge-pill badge-danger">DECLINED</span>' :
                        (val.rev_status == 2) ? '<span class="badge badge-pill badge-secondary">PENDING REQUEST</span>' :
                        '<span class="badge badge-pill badge-danger">LAPSED REQUEST</span>';

                    var stat = get_review_status(val.rev_id);

                    var label = ((stat == 4) ? '<span class="badge badge-pill badge-success">Recommended as submitted</span>' :
                        ((stat == 5) ? '<span class="badge badge-pill badge-warning">Recommended with minor revisions</span>' :
                        ((stat == 6) ? '<span class="badge badge-pill badge-warning">Recommended with major revisions</span>' :
                        ((stat == 7) ? '<span class="badge badge-pill badge-danger">Not recommended</span>' :
                        ((stat == 3) ? '<span class="badge badge-pill badge-danger">LAPSED REVIEW</span>' :
                        ((stat == 2) ? '<span class="badge badge-pill badge-secondary">PENDING REVIEW</span>' :
                        '-'))))));

                    if (val.rev_status == 3) {
                        $('#new_rev').attr('onclick', 'process_man(' + val.rev_man_id + ')');
                    }

                    if (date == '-') {
                        timer(val.date_created, r, val.rev_request_timer);
                    } else {
                        if (stat == 2) {
                            timer(val.rev_date_respond, r, val.rev_timeframe);
                        } else {
                            $("#table-reviewers tbody tr:eq(" + r + ") td:last()").text('-');
                        }
                    }

                    if (val.rev_hide_rev == 1 && val.rev_hide_auth == 0) {
                        var name = val.rev_name + '<br/><span class="fa fa-user-secret text-muted pt-1"></span>';

                    } else if (val.rev_hide_auth == 1 && val.rev_hide_rev == 0) {
                        var name = val.rev_name + '<br/><span class="fas fa-user-alt-slash text-muted pt-1"></span>';

                    } else if (val.rev_hide_rev == 1 && val.rev_hide_auth == 1) {
                        var name = val.rev_name + '<br/><span class="fas fa-user-alt-slash text-muted pt-1"></span> <span class="fa fa-user-secret text-muted pt-1"></span>';
                    } else {
                        var name = val.rev_name;
                    }

                    $('#table-reviewers').dataTable().fnAddData([
                        c++,
                        name,
                        val.rev_email,
                        val.rev_contact,
                        req_status,
                        label,
                        date,
                        '-',
                    ]);
                    r++;
                });



                var t = $('#table-reviewers').DataTable();
                t.on('order.dt search.dt', function() {
                    t.column(0, {
                        search: 'applied',
                        order: 'applied'
                    }).nodes().each(function(cell, i) {
                        cell.innerHTML = i + 1;
                    });
                }).draw();
            }


        }
    });
}

// show all editors per manuscript
function view_editors(id, title) {

    var manuscript_title = decodeURIComponent(title);

    $('#editorialReviewModal p').text('TITLE : ' + manuscript_title);

    man_id = id;


    $.ajax({
        type: "GET",
        url: base_url + "oprs/manuscripts/editors/" + id,
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            // console.log(data);
            if ($.fn.DataTable.isDataTable("#table-editors")) {
                $('#table-editors').DataTable().clear().destroy();
            }

            if (data.length > 0) {
              
                revs = [];
                var c = 0;

                $.each(data, function(key, val) {

                    var date = moment(val.date_created, 'YYYY-MM-DD HH:mm').format("MMMM D, YYYY h:mm a");
                    

                    $('#table-editors').dataTable().fnAddData([
                        c++,
                        val.edit_name,
                        val.edit_specialization,
                        val.edit_email,
                        val.edit_contact,
                        date
                    ]);
                });



                var t = $('#table-editors').DataTable();
                t.on('order.dt search.dt', function() {
                    t.column(0, {
                        search: 'applied',
                        order: 'applied'
                    }).nodes().each(function(cell, i) {
                        cell.innerHTML = i + 1;
                    });
                }).draw();
            }


        }
    });
}

// show all reviewers per manuscript
function view_reviews(id, title) {

    var manuscript_title = decodeURIComponent(title);

    $('#reviewsModal p').text('TITLE : ' + manuscript_title);

    man_id = id;

    $('#reviews_table tbody').empty();
    $.ajax({
        type: "GET",
        url: base_url + "oprs/manuscripts/reviews/" + id,
        dataType: "json",
        crossDomain: true,
        success: function(data) {

            if ($.fn.DataTable.isDataTable("#reviews_table")) {
                $('#reviews_table ').DataTable().clear().destroy();
            }

            var i = 1;
            $.each(data, function(key, val) {

                var name = val.rev_name;
                var score = val.scr_total + '/100';
                var status = val.scr_status;
                var rem = (val.scr_remarks == '' || val.scr_remarks == null) ? '-' : val.scr_remarks;
                var file = (val.scr_file == null || val.scr_file == '') ? 'N/A' : '<a class="text-primary" href="' + base_url + "assets/oprs/uploads/reviewersdoc/" + val.scr_file + '" target="_blank" download>Downlod</a>';

                var reco = ((status == 4) ? '<span class="badge badge-pill badge-success">Recommended as submitted</span>' :
                    ((status == 5) ? '<span class="badge badge-pill badge-warning">Recommended with minor revisions</span>' :
                    ((status == 6) ? '<span class="badge badge-pill badge-warning">Recommended with major revisions</span>' :
                        '<span class="badge badge-pill badge-danger">Not recommended</span>')));
                
                $('#reviews_table tbody').append('<tr><td>' + i +'</td> \
                                                <td>' + name + '</td> \
                                                <td>' + score + '</td> \
                                                <td>' + reco + ' \
                                                <td>' + file +'</td> \
                                                <td>' + rem +'</td></tr>');
                
                i++;
            });

            
            var t = $('#reviews_table').DataTable();
            t.on('order.dt search.dt', function() {
                t.column(0, {
                    search: 'applied',
                    order: 'applied'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();

            


        }
    });
}

// get review submitted by reviewer
function get_review_status(rev_id) {

    var jqXHR = $.ajax({
        type: "GET",
        url: base_url + "oprs/manuscripts/review_status/" + rev_id + "/" + man_id,
        async: false,
        crossDomain: true,
    });

    var stat = jqXHR.responseText.replace(/\"/g, '');

    return stat;
}

// display full text manuscript and evaluation form (reviewers only)
function start_review(file, id, title, auth, hide_auth) {
    $('#manus_review').attr('src', base_url + 'assets/oprs/uploads/initial_manuscripts_pdf/' + file);
    $('#rev_title').text(decodeURIComponent(title));
    var author = (hide_auth == 1) ? 'Undisclosed' : auth;
    $('#rev_author').text(author);
    man_id = id;
}

// get reviewers from clicking in tracking
function reviewers(id, trk, rem, u_role, desc, time, title, status) {
    
    var manuscript_title = decodeURIComponent(title);
    $.ajax({
        type: "POST",
        url: base_url + "oprs/manuscripts/reviewers/" + id + "/" + time,
        dataType: "json",
        crossDomain: true,
        success: function(data) {

            if (u_role == 3) {
                $('.usr' + trk).append('<small><a href="javascript:void(0);" onclick="view_reviewers(\'' + id + '\',\'' + time + '\',\'' + manuscript_title + '\',\'' + status + '\')" data-toggle="modal" data-target="#reviewerModal">View added reviewers</small>');
            }


            if (rem != '' && rem != null) {
                $('.usr' + trk).append('<div class="alert p-1 mt-1 mb-0"><small><strong>Remarks:</strong> ' +
                    rem +
                    '</small></div>');
            }
        }
    });

}

// show review status in tracking
function review(id, trk, rev_name) {

    $.ajax({
        type: "POST",
        url: base_url + "oprs/manuscripts/tracker_review/" + id + "/" + man_id,
        dataType: "json",
        crossDomain: true,
        success: function(data) {

            $.each(data, function(key, val) {
                var status = ((val.scr_status == 4) ? '<span class="badge badge-success mr-1">Recommended as submitted</span>' :
                    ((val.scr_status == 5) ? '<span class="badge badge-warning mr-1">Recommended with minor revisions</span>' :
                    ((val.scr_status == 6) ? '<span class="badge badge-warning mr-1">Recommended with major revisions</span>' :
                    ((val.scr_status == 7) ? '<span class="badge badge-danger mr-1">Not recommended</span>' : ''))));


                $('.usr' + trk).append('<a href="javascript:void(0);" onclick="view_score(\'' + id + '\',\'' + man_id + '\',\'' + rev_name + '\')" data-toggle="modal" data-target="#scoreModal"><span class="badge badge-info mr-1" >Score : ' + val.scr_total + '/100</span></a>' +
                    status);

                if (val.scr_remarks != '' && val.scr_remarks != null)
                    $('.usr' + trk).append('<div class="alert p-1 mt-1 mb-0"><small><strong>Remarks:</strong> ' +
                        truncate(val.scr_remarks, id, man_id, rev_name) +
                        '</small></div>');
            });
        }
    });
}

// truncate long remakrs in tracking
function truncate(input, id, man_id, rev_name) {
    if (input.length > 150)
        return input.substring(0, 150) + '... <a href="javascript:void(0);" onclick="view_score(\'' + id + '\',\'' + man_id + '\',\'' + rev_name + '\')" data-toggle="modal" data-target="#scoreModal"><small>See more</small></a>';
    else
        return input;
};

// generate review request email content in tinymce
function generate_email(rid, mid) {
    $.ajax({
        type: "GET",
        url: base_url + "oprs/manuscripts/get_manuscript_by_id/"+man_id,
        dataType: "json",
        crossDomain: true,
        success: function(data) {

            console.log(data);
            $.each(data, function(key, val) {
                t = val.man_title;
                a = val.man_author;
                f = val.man_affiliation;

                var header = '';
                var dear = '';
                var exp = '';
                var address = '';
                var new_mail = '';
                
                var mail = moment().format('MMMM D, YYYY') + '<br/><br/>' + mail_content;
console.log(mail);
                $('#rev_header' + mid).text($('#trk_rev' + mid).val());
                $('#rev_header_mail' + mid).text($('#trk_rev' + mid).val());

                $.ajax({
                    type: "POST",
                    url: base_url + "oprs/manuscripts/reviewer_info/" + rid,
                    dataType: "json",
                    crossDomain: true,
                    success: function(data) {

                        console.log(data);
                        $.each(data, function(key, val) {

                            var emp_pos = (val.emp_pos != null && (val.emp_pos).length > 0) ? val.emp_pos : '[POSITION]';
                            var emp_dept = (val.emp_div_dept != null && (val.emp_div_dept).length > 0) ? val.emp_div_dept : '[DEPARTMENT]';
                            var emp_ins = (val.emp_ins != null && (val.emp_ins).length > 0) ? val.emp_ins : '[AFFILIATION]';
                            var emp_address = (val.emp_address != null && (val.emp_address).length > 0) ? val.emp_address : '[ADDRESS]';
                            
                            header = '<span style="text-transform:uppercase"><strong>' + val.title_name + ' ' + val.pp_first_name + ' ' + val.pp_middle_name + ' ' + val.pp_last_name + ' ' + val.pp_extension + '</strong></span></span>';

                            // address = emp_pos_id + 
                            // emp_div_dept + 
                            // emp_ins_id + 
                            // emp_address + '<br/><br/>';

                            dear = 'Dear <span style="text-transform:uppercase"><strong>' + val.title_name + ' ' + val.pp_last_name + '</strong></span> : <br/><br/>';
                            exp = val.mpr_gen_specialization;

                            new_mail = mail.replace('[FULL NAME]', header);
                            new_mail = new_mail.replace('[TITLE]', val.title_name);
                            new_mail = new_mail.replace('[LAST NAME]', val.pp_last_name);
                            new_mail = new_mail.replace('[POSITION]', emp_pos);
                            new_mail = new_mail.replace('[DEPARTMENT]', emp_dept);
                            new_mail = new_mail.replace('[AFFILIATION]', emp_ins);
                            new_mail = new_mail.replace('[ADDRESS]', emp_address);
                            new_mail = new_mail.replace('[SPECIALIZATION]', exp);
                            new_mail = new_mail.replace('[MANUSCRIPT]', t);
                        });

                        var jor_iss = localStorage.getItem('jor_issue');
                        var jor_vol = localStorage.getItem('jor_vol');
                        var art_iss = localStorage.getItem('art_iss');
                        var jor_year = localStorage.getItem('jor_year');

                        if (jor_vol == 0) {
                            new_mail = new_mail.replace('[ISSUE], [VOLUME] ([YEAR])', art_iss + '(' + jor_year + ')');
                        } else if (jor_vol == null) {
                            new_mail = new_mail;
                        } else {
                            var prefix = jor_iss >= 5 ? 'Special Issue No. ' + (jor_iss - 4) : 'Issue ' + jor_iss;
                            
                            new_mail = new_mail.replace('[VOLUME]', 'Volume ' + jor_vol + ', ');
                            new_mail = new_mail.replace('[ISSUE]', prefix);
                            new_mail = new_mail.replace('[YEAR]', jor_year);
                        }

                        
                        console.log(new_mail);
                        
                        tinymce.remove('tiny_mail' + mid);
                        tinymce.init({
                            selector: '#tiny_mail' + mid,
                            forced_root_block : false,
                            height : "750"
                        });


                        $(tinymce.get('tiny_mail' + mid).getBody()).html(new_mail);

                        }
                });
            });
        }
    });
    $('#rev_acc_mail .collapse').removeClass('show');
    $('#collapse_mail' + mid).addClass('show');
}

// generate review request email content in tinymce
function generate_editor_email(rid, mid) {
    $.ajax({
        type: "GET",
        url: base_url + "oprs/manuscripts/get_manuscript_by_id/"+man_id,
        dataType: "json",
        crossDomain: true,
        success: function(data) {

            console.log(data);
            $.each(data, function(key, val) {
                t = val.man_title;
                a = val.man_author;
                f = val.man_affiliation;

                var header = '';
                var dear = '';
                var exp = '';
                var address = '';
                var new_mail = '';
                
                var mail = moment().format('MMMM D, YYYY') + '<br/><br/>' + editor_mail_content;
console.log(mail);
                $('#rev_header' + mid).text($('#trk_rev' + mid).val());
                $('#rev_header_mail' + mid).text($('#trk_rev' + mid).val());

                $.ajax({
                    type: "POST",
                    url: base_url + "oprs/manuscripts/reviewer_info/" + rid,
                    dataType: "json",
                    crossDomain: true,
                    success: function(data) {

                        console.log(data);
                        $.each(data, function(key, val) {

                            var emp_pos = (val.emp_pos != null && (val.emp_pos).length > 0) ? val.emp_pos : '[POSITION]';
                            var emp_dept = (val.emp_div_dept != null && (val.emp_div_dept).length > 0) ? val.emp_div_dept : '[DEPARTMENT]';
                            var emp_ins = (val.emp_ins != null && (val.emp_ins).length > 0) ? val.emp_ins : '[AFFILIATION]';
                            var emp_address = (val.emp_address != null && (val.emp_address).length > 0) ? val.emp_address : '[ADDRESS]';
                            
                            header = '<span style="text-transform:uppercase"><strong>' + val.title_name + ' ' + val.pp_first_name + ' ' + val.pp_middle_name + ' ' + val.pp_last_name + ' ' + val.pp_extension + '</strong></span></span>';

                            // address = emp_pos_id + 
                            // emp_div_dept + 
                            // emp_ins_id + 
                            // emp_address + '<br/><br/>';

                            dear = 'Dear <span style="text-transform:uppercase"><strong>' + val.title_name + ' ' + val.pp_last_name + '</strong></span> : <br/><br/>';
                            exp = val.mpr_gen_specialization;

                            new_mail = mail.replace('[FULL NAME]', header);
                            new_mail = new_mail.replace('[TITLE]', val.title_name);
                            new_mail = new_mail.replace('[LAST NAME]', val.pp_last_name);
                            new_mail = new_mail.replace('[POSITION]', emp_pos);
                            new_mail = new_mail.replace('[DEPARTMENT]', emp_dept);
                            new_mail = new_mail.replace('[AFFILIATION]', emp_ins);
                            new_mail = new_mail.replace('[ADDRESS]', emp_address);
                            new_mail = new_mail.replace('[SPECIALIZATION]', exp);
                            new_mail = new_mail.replace('[MANUSCRIPT]', t);
                        });

                        var jor_iss = localStorage.getItem('jor_issue');
                        var jor_vol = localStorage.getItem('jor_vol');
                        var art_iss = localStorage.getItem('art_iss');
                        var jor_year = localStorage.getItem('jor_year');

                        if (jor_vol == 0) {
                            new_mail = new_mail.replace('[ISSUE], [VOLUME] ([YEAR])', art_iss + '(' + jor_year + ')');
                        } else if (jor_vol == null) {
                            new_mail = new_mail;
                        } else {
                            var prefix = jor_iss >= 5 ? 'Special Issue No. ' + (jor_iss - 4) : 'Issue ' + jor_iss;
                            
                            new_mail = new_mail.replace('[VOLUME]', 'Volume ' + jor_vol + ', ');
                            new_mail = new_mail.replace('[ISSUE]', prefix);
                            new_mail = new_mail.replace('[YEAR]', jor_year);
                        }

                        
                        console.log(new_mail);
                        
                        tinymce.remove('editor_tiny_mail' + mid);
                        tinymce.init({
                            selector: '#editor_tiny_mail' + mid,
                            forced_root_block : false,
                            height : "750"
                        });


                        $(tinymce.get('editor_tiny_mail' + mid).getBody()).html(new_mail);

                        }
                });
            });
        }
    });
    $('#rev_acc_mail .collapse').removeClass('show');
    $('#collapse_mail' + mid).addClass('show');
}

// stroe manuscript id in global variable
function submit_final(id) {
    man_id = id;
}

// reviewer countdown
function timer(date, r, tf) {
    var compareDate = new Date(date);
    compareDate.setDate(compareDate.getDate() + parseInt(tf)); //just for this demo today + 7 days

    revInterval = setInterval(function() {
        $("#table-reviewers tbody tr:eq(" + r + ") td:last()").text(timeBetweenDates(compareDate, r));
    }, 1000);

}

// compute difference of two dates
function timeBetweenDates(toDate, r) {
    var dateEntered = toDate;
    var now = new Date();
    var difference = dateEntered.getTime() - now.getTime();
    // difference = Math.abs(difference);

    if (difference <= 0) {
        // Timer done

        // $("#table-reviewers tbody tr:eq(" + r + ") td:last()").text('expired');
        // clearInterval(revInterval);
    } else {

        var seconds = Math.floor(difference / 1000);
        var minutes = Math.floor(seconds / 60);
        var hours = Math.floor(minutes / 60);
        var days = Math.floor(hours / 24);

        hours %= 24;
        minutes %= 60;
        seconds %= 60;

        cd = days + 'days ';
        cd += hours + 'hrs ';
        cd += minutes + 'mins ';
        cd += seconds + 'secs';

        return cd;

    }
}

// unused
function dash_reviewer(id) {
    man_id = id;
    $('#form_journal').hide();
    load_email_content();
}

// show replaced variables in email content in tinymce
function load_email_content(num = 1) {


    var new_mail = '';
    var mail = moment().format('MMMM D, YYYY') + '<br/><br/>' + mail_content;
    var jor_iss = localStorage.getItem('jor_issue');
    var jor_vol = localStorage.getItem('jor_vol');
    var jor_year = localStorage.getItem('jor_year');
    // var art_iss = localStorage.getItem('art_iss');

    if (jor_vol == null) {
        new_mail = mail;
    } else {
            prefix = jor_iss >= 5 ? 'Special Issue No. ' + (jor_iss - 4) : 'Issue ' + jor_iss;
            new_mail = mail.replace('[VOLUME]', 'Volume ' + jor_vol + ' ');
        new_mail = new_mail.replace('[ISSUE]', prefix);
        new_mail = new_mail.replace('[YEAR],', jor_year);
    }

    new_mail = new_mail.replace('[MANUSCRIPT]', mail_title);

    tinymce.init({
        selector: '#tiny_mail' + num,
        forced_root_block : false,
        height : "750"
    });

    $(tinymce.get('tiny_mail' + num).getBody()).html(new_mail);

   
}

// show replaced variables in email content in tinymce (editor)
function load_editor_email_content(num = 1) {

    var new_mail = '';
    var mail = moment().format('MMMM D, YYYY') + '<br/><br/>' + editor_mail_content;
    var jor_iss = localStorage.getItem('jor_issue');
    var jor_vol = localStorage.getItem('jor_vol');
    var jor_year = localStorage.getItem('jor_year');
    // var art_iss = localStorage.getItem('art_iss');

    if (jor_vol == null) {
        new_mail = mail;
    } else {
            prefix = jor_iss >= 5 ? 'Special Issue No. ' + (jor_iss - 4) : 'Issue ' + jor_iss;
            new_mail = mail.replace('[VOLUME]', 'Volume ' + jor_vol + ' ');
        new_mail = new_mail.replace('[ISSUE]', prefix);
        new_mail = new_mail.replace('[YEAR],', jor_year);
    }

    new_mail = new_mail.replace('[MANUSCRIPT]', mail_title);

    tinymce.init({
        selector: '#editor_tiny_mail' + num,
        forced_root_block : false,
        height : "750"
    });

    $(tinymce.get('editor_tiny_mail' + num).getBody()).html(new_mail);
   
}

// unused
function approve_manus(id) {
    man_id = id;

    $(' .table-borderless > tbody').empty();
    var coa = [];

    $.ajax({
        type: "GET",
        url: base_url + "oprs/manuscripts/authors/get/" + id,
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            if (data.length > 0) {
                var html = '';
                $.each(data, function(key, val) {
                    coa.push(val.coa_name + ', ' + val.coa_affiliation + ', ' + val.coa_email + '<br/>');
                });
            }
        }
    });

    $.ajax({
        type: "GET",
        url: base_url + "oprs/manuscripts/manuscript/" + id,
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            var html = '';
            $.each(data, function(key, val) {

                var vol = (val.man_volume != null) ? val.man_volume : '-';
                var iss = (val.man_issue != null) ? val.man_issue : '-';
                var yer = (val.man_year != null) ? val.man_year : '-';
                var rem = (val.man_remarks != null) ? val.man_remarks : '-';
                var coas = (coa.length > 0) ? coa.join('') : '-';


                html += '<tr>' +
                    '<th>Title</th>' +
                    '<td>' + val.man_title + '</td>' +
                    '</tr>';

                html += '<tr>' +
                    '<th>Primary Author</th>' +
                    '<td>' + val.man_author + ', ' + val.man_affiliation + ', ' + val.man_email + '</td>' +
                    '</tr>';

                html += '<tr>' +
                    '<th>Co-authors</th>' +
                    '<td>' + coas + '</td>' +
                    '</tr>';

                html += '<tr>' +
                    '<th>Volume</th>' +
                    '<td>' + vol + '</td>' +
                    '</tr>';

                html += '<tr>' +
                    '<th>Issue</th>' +
                    '<td>' + iss + '</td>' +
                    '</tr>';

                html += '<tr>' +
                    '<th>Year</th>' +
                    '<td>' + yer + '</td>' +
                    '</tr>';

                html += '<tr>' +
                    '<th>Abstract</th>' +
                    '<td><small><a class="text-primary" href="' + base_url + "assets/oprs/uploads/abstracts/" + val.man_abs + '" target="_blank">' + val.man_abs + '</a></small></td>' +
                    '</tr>';

                html += '<tr>' +
                    '<th>Manuscript</th>' +
                    '<td><small><a class="text-primary" href="' + base_url + "assets/oprs/uploads/manuscripts/" + val.man_file + '" target="_blank">' + val.man_file + '</a></small></td>' +
                    '</tr>';

                html += '<tr>' +
                    '<th>Keywords</th>' +
                    '<td>' + val.man_keywords + '</td>' +
                    '</tr>';

                html += '<tr>' +
                    '<th>Remarks</th>' +
                    '<td>' + rem + '</td>' +
                    '</tr>';

                html += '<tr>' +
                    '<th>Page Position </th>' +
                    '<td><input class="form-control col-6 border-danger" placeholder="(Example: 1-3)" type="text" id="man_page_position" name="man_page_position"></td>' +
                    '</tr>';
            });

            $(' .table-borderless > tbody').append(html);
            $('#uploadModal').modal('toggle');
            $('#man_file_div').hide();
            $('#uploadModal .modal-footer .btn').hide();
            $('#manuscript_form').hide();
            $('.table-borderless').show();
            $('#btn_approve').show();
            $('#btn_cancel').show();
        }
    });
}

// show score from tracking when score is clicked
function view_score(id, manus_id, reviewer) {

    $.ajax({
        type: "GET",
        url: base_url + "oprs/manuscripts/tracker_review/" + id + "/" + manus_id,
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            // console.log(data);
            $.each(data, function(key, val) {

                $('#score_title').text(val.man_title);
                $('#score_author').text(val.man_author);

                $.each(val, function(k, v) {
                    $('td#' + k).text(v);
                });
            });
        }
    });

    $('#score_reviewer').html(reviewer);
}


// edit user
function edit_user(id) {
    user_id = id;
    $('#editUserModal').modal('toggle');
    $('#form_edit_user')[0].reset();
    $('#editUserModal #usr_role').empty();

    $.ajax({
        type: "GET",
        url: base_url + "oprs/user/get_info/" + id,
        dataType: "json",
        crossDomain: true,
        success: function(data) {

            $.each(data, function(key, val) {
                
                if(val.usr_sys_acc == 1){
                    $('#editUserModal #usr_role').append('<option value="" selected>Select User Role</option>' +
                        '<option value="7">Admin</option>' +
                        '<option value="6">Manager</option>');
                }else if(val.usr_sys_acc == 2){
                    $('#editUserModal #usr_role').append('<option value="" selected>Select User Role</option>' +
                        '<option value="7">Admin</option>' +
                        '<option value="9">Publication Committee</option>' +
                        '<option value="3">Managing Editor</option>' +
                        '<option value="6">Manager</option>' +
                        '<option value="10">Editor</option>' +
                        '<option value="11">Guest Editor</option>' +
                        '<option value="12">Editor-in-Chief</option> '+
                        '<option value="13">Layout</option>');
                }else{
                    $('#editUserModal #usr_role').append('<option value="" selected>Select User Role</option>' +
                    '<option value="3">Managing Editor</option>');
                }

                if(val.usr_role == 5 || val.usr_role == 1){
                    $('#editUserModal #usr_role').attr('disabled', 'disabled')
                    $('#editUserModal #usr_sys_acc').attr('disabled', 'disabled')
                }else{
                    $('#editUserModal #usr_role').attr('disabled', false)
                    $('#editUserModal #usr_sys_acc').attr('disabled', false)
                }

                $.each(val, function(k, v) {
                    if (k != 'usr_password')
                        $('#form_edit_user #' + k).val(v);
                });

                if (val.usr_status == 2) {
                    $('.activate').show();
                    $('.deactivate').hide();
                } else {
                    $('.deactivate').show();
                    $('.activate').hide();
                }
            });
        }
    });
}

// activate/deactive modal
function act_deact_modal(val) {
    user_status = val;
    $('#confirmDeactivationModal').modal('toggle');

    if (val == 2) {
        $('#confirmDeactivationModal .modal-title').text('Deactivate Account');
    } else {
        $('#confirmDeactivationModal .modal-title').text('Activate Account');
    }
}

// activate/deactivate user
function activate_deactivate_user() {

    $.ajax({
        type: "POST",
        url: base_url + "oprs/user/activate_deactivate/" + user_status + "/" + user_id,
        dataType: "json",
        crossDomain: true,
        success: function(data) {
        }
    });

    
    location.reload();
}

// publish manuscripts (for finalization)
function publish_articles(c, id)
{

    if (($('#table-publishables tr:eq('+ (c + 1) +') input[name="publish_title"]:checked').length) <= 0) {
        $('.alert-danger').remove();
        $('#publishables').before('<div class="alert alert-danger" role="alert">\
        <span class="fa fa-exclamation-circle"></span> You must check at least 1 box\
      </div>');
    }
    else{
    
        var publishables = [];

        $('#table-publishables tr:eq('+ (c + 1) +') input[name="publish_title"]:checked').each(function() {
            var label = $("label[for='publish_title" + $(this).val() +"']");
            publishables.push({
            id : $(this).val(),
            title : label.text()
            });
        });
   
        var html  = '';
        $('.list-publish').empty();
        $.each(publishables, function(key, val)
        {
            html += '<li class="list-group-item"> \
                        <p class="font-weight-bold"> \
                        ' + val['title'] + ' \
                        </p> \
                        <div class="form-group"> \
                        <label>Upload Final Manuscript</label> <span class="badge badge-danger">PDF</span></label>\
                        <input type="hidden" name="man_id[]" value="' + val['id'] + '"> \
                        <input type="file" class="form-control upload_file" id="man_file'+val['id']+'" name="man_file['+val['id']+']" accept="application/pdf"> \
                        </div> \
                        <div class="form-group"> \
                        <label>Page(s)</label>\
                        <input type="number" class="form-control  col-3 upload_page" id="man_page_position'+val['id']+'" name="man_page_position['+val['id']+'] min="1" placeholder="ex. 1-3"> \
                        </div> \
                     </li>';


        });
 
        $('.list-publish').append(html);
        
        $(".upload_file").each(function() {
            $(this).rules("add", {
                required: true,
                extension: 'pdf',
                maxFileSize: {
                    "unit": "MB",
                    "size": "25"
                }
            });
        });

        $(".upload_page").each(function() {
            $(this).rules("add", {
                required: true
            });
        });

        // $('#table-publishables tr:eq('+ (c + 1) +') input[name="publish_title"]:checked').each(function() {
        //     publishables.push($(this).val());
        // });

        // $('body').loading('start');

        // $.ajax({
        //     type: "POST",
        //     url: base_url + "oprs/manuscripts/publish/",
        //     data : { ids : publishables},
        //     dataType: "json",
        //     crossDomain: true,
        //     success: function(data) {
               
        //     }
        // });

        // location.reload();

        $('#publishModal').modal('toggle');
    }
}

// get user log
function get_user_log(id)
{
    var jqXHR = $.ajax({
        type: "GET",
        url: base_url + "oprs/user/get_user_log/" + id,
        async: false,
        crossDomain: true,
    });

    var data = jqXHR.responseText.replace(/\"/g, '');

    return data;
}

// get manuscript log
function get_manuscript_log(id)
{
    var jqXHR = $.ajax({
        type: "GET",
        url: base_url + "oprs/manuscripts/get_manuscript_title/" + id,
        async: false,
        crossDomain: true,
    });

    // console.log(jqXHR);return false;

    // var data = jqXHR.responseText.replace(/\"/g, '');

    // return data;

    // return jqXHR.responseText;
    return jqXHR.responseText;
}

// open notifications
function open_notif(data, id)
{
    // console.log(data + ' ' + id);return false;
    // notifications();
    localStorage.setItem('notif_data',data);
    window.location.href = base_url + "oprs/manuscripts";

    $.ajax({
        type: "POST",
        url: base_url + "oprs/logs/notif_open/" + id,
        async: false,
        success: function(data){
            console.log(data);
        }
    });

}

// view manucscript from clicked notification
function open_manuscript(data)
{
    // console.log(data + ' ' + id);return false;
    // notifications();
    localStorage.setItem('notif_data',data);
    window.location.href = base_url + "oprs/manuscripts";

}

// get notifications
function notifications(){

    var user_id = $('.cookie_id').text();
    var cookie_id = localStorage.getItem('notif_open_id_' + user_id);
    var cookie_date = localStorage.getItem('notif_open_date_' + user_id);
    var today = moment().format('MMMM DD YYYY hh:mm:ss');

    // console.log(cookie_id + ' ' + cookie_date);
    // console.log(cookie_date + ' ' + today);

    if(cookie_id == null){
        var notif_count = 0;
        var a = moment().format('MMMM DD YYYY hh:mm:ss');

        $.ajax({
            type: "GET",
            url: base_url + "oprs/notifications/notif_tracker/",
            dataType: "json",
            crossDomain: true,
            success: function(data) {  
                $.each(data, function(key, val){
                    b = moment(val.trk_process_datetime).format('MMMM DD YYYY hh:mm:ss');
                    if(a > b){
                            notif_count++;
                    }
                });
                
                if(notif_count > 0)
                {
                    $('.oprs_notif').append('<span class="badge badge-danger font-weight-bold notif_count" style="font-size:11px;position:fixed; margin-left:-5px;margin-top:2px">' + notif_count + '</span');
                }
            }
            
        });
    }else{
        var notif_count = 0;
        var a = moment().format('MMMM DD YYYY hh:mm:ss');
        $.ajax({
            type: "GET",
            url: base_url + "oprs/notifications/notif_tracker/",
            dataType: "json",
            crossDomain: true,
            success: function(data) {
                // console.log(data);  
                $.each(data, function(key, val){
                    b = moment(val.trk_process_datetime).format('MMMM DD YYYY hh:mm:ss');
                    if(cookie_date < b && b < today){
                            notif_count++;
                    }
                });
                
                if(notif_count > 0)
                {
                    $('.oprs_notif').append('<span class="badge badge-danger font-weight-bold notif_count" style="font-size:11px;position:fixed; margin-left:-5px;margin-top:2px">' + notif_count + '</span');
                }
            }
        });
    }
}

// unused
function notifications2()
{
    var notif_count = 0;

    $.ajax({
        type: "POST",
        url: base_url + "oprs/logs/get_logs",
        dataType: "json",
        crossDomain: true,
        success: function(data) {
          
           $.each(data, function(key, val)
           {
               if(val.notif_open == 0){
                    var alerted = localStorage.getItem('notif'+val.row_id) || '';
                    if (alerted != 'yes') {
                        $.notify({
                            icon: 'fa fa-bell',
                            message: '<strong>' + val.usr_username + '</strong> ' + val.log_action + ' <strong> \
                                    ' + val.man_title + '</strong> \
                                    <small class="d-flex mt-1">'+ moment(val.date_created).fromNow() + '</small></a>'
                        }, {
                            type: 'info',
                            timer: 3000,
                        });
                     localStorage.setItem('notif'+val.row_id,'yes');
                    }

                    notif_count++;
               }
           });

           $('.notif_count').empty();
           if(notif_count > 0)
           {
            $('.notif_count').append('<span class="badge badge-danger font-weight-bold notif_count" style="font-size:11px;position:fixed; margin-left:-5px;margin-top:2px">' + notif_count + '</span');
           }
           
           

           
        }
    });
}

// for final review of editor (for finalization)
function final_review(id){
    $('#committeeModal').modal('toggle');
    
    $.ajax({
        type: "GET",
        url: base_url + "oprs/manuscripts/get_manuscript_by_id/" + id,
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            $.each(data, function(key, val){
                $('#manus_title').val(val.man_title);
                $('#manus_author').val(val.man_author);
                $('#com_man_id').val(id);
            });
        }
    });
}

// show publication committee review in tracking (for finalization)
function com_review(id, trk){
    $.ajax({
        type: "GET",
        url: base_url + "oprs/manuscripts/get_committee_review/" + id,
        dataType: "json",
        crossDomain: true,
        success: function(data) {console.log(data);
            $.each(data, function(key, val) {

                var status = ((val.com_review == 1) ? '<span class="badge badge-success mr-1">No Revisions, Approve</span>' 
                : ((val.com_review == 2 ? '<span class="badge badge-info mr-1">Recommended with Minor Revisions</span>' 
                : ((val.com_review == 3) ? '<span class="badge badge-warning mr-1">Recommended with Major Revisions</span>' 
                : '<span class="badge badge-danger mr-1">Disapprove</span>'))));
        
        
                $('.usr' + trk).append(status);
        
                if (val.com_remarks != '' && val.com_remarks != null)
                    $('.usr' + trk).append('<div class="alert p-1 mt-1 mb-0"><small><strong>Remarks:</strong> ' +
                        val.com_remarks +
                        '</small></div>');
            });
        }
    });
}

// verify if feedback is submited already
function verify_feedback(){
    $('#logoutModal').modal('toggle');
  
    var jqXHR = $.ajax({
        type: "GET",
        url: base_url + "admin/feedback/verify/999999",
        async: false,
        crossDomain: true,
    });
  
    var stat = jqXHR.responseText.replace(/\"/g, '');
    if(stat == 0){
        $('#feedbackModal').modal('toggle');
    }else{
      window.location.href = base_url + 'oprs/login/logout';
    }
  }

// view feedback
function view_feedbacks(){
    window.location.href = base_url + 'oprs/feedbacks';
}

// confirmation modal before deleting manuscript
// store mannuscript id to global variable
function remove_manus(id){

    $('#confirmRemoveModal').modal('toggle');
    remove_man_id = id;
}

// refresh
function refresh_manus(){
    location.reload();
}

// show csf in table
function view_csf_feedback(id){

    $('#csf_modal').modal('toggle');

    $.ajax({
        type: "GET",
        url: base_url + "oprs/feedbacks/get_csf_feedback_by_ref/" + id,
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            var html;

            html = '<table class="table table-striped table-bordered"><thead><tr><th>Question</th><th>Answer</th></tr></thead><tbody>';

            $.each(data, function(key, val){
                if(val.svc_fdbk_q_id == 1){
                    var answer = val.aff_type;
                }else if(val.svc_fdbk_q_id == 2){
                    var answer = val.nrcp_svc;
                }else{
                    if(val.svc_fdbk_q_answer > 0){
                        var answer = val.svc_fdbk_rating;
                    }else{
                        var answer = val.svc_fdbk_q_answer;
                    }
                }
                
                html += '<tr><td>' + val.svc_fdbk_q + '</td><td>' + answer + '</td></tr>';
            });

            html += '<tbody></table>';

            $('#csf_modal .modal-body').empty().append(html);
        }
    });
}

// generate ui ux graph
function generate_uiux_graph(){
    var ui_labels = [];
    var ui_values = [];
    var ui_bgcolors = ['#CD6155','#F1C40F','#52BE80'];
    var ux_labels = [];
    var ux_values = [];
    var ux_bgcolors = ['#CD6155','#F1C40F','#52BE80'];
  
    //ui
    $.ajax({
      method: 'GET',
      url: base_url + "oprs/feedbacks/get_ui_graph",
      async: false,
      dataType: "json",
      success: function (response) {
            $.each(response, function(key, val){
            ui_values.push(val.total);
                ui_labels.push(val.label);
            });
    
            
            var bar = document.getElementById('ui_bar_chart').getContext('2d');
            var barChart = new Chart(bar, {
                type: 'horizontalBar',
                data: {
                    labels: ui_labels,
                    datasets: [{
                        data: ui_values,
                        backgroundColor: ui_bgcolors,
                        borderColor: ui_bgcolors,
                        borderWidth: 1
                    }]
                },
                options: {
                    legend: {
                        display: false,
                        position: 'top',
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero:true,
                            },
                        }],
                    }
                }
            });
    
            var pie = document.getElementById('ui_pie_chart').getContext('2d');
            var pieChart = new Chart(pie, {
                type: 'pie',
                data: {
                    labels: ui_labels,
                    datasets: [{
                        data: ui_values,
                        backgroundColor: ui_bgcolors,
                        borderColor: ui_bgcolors,
                        borderWidth: 1
                    }]
                },
                options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: true,
                    position: 'top',
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:true,
                        },
                    }],
                }
                }
            });
        }
    });

    //ux
    $.ajax({
        method: 'GET',
        url: base_url + "oprs/feedbacks/get_ux_graph",
        async: false,
        dataType: "json",
        success: function (response) {
              $.each(response, function(key, val){
              ux_values.push(val.total);
                  ux_labels.push(val.label);
              });
      
              
              var bar = document.getElementById('ux_bar_chart').getContext('2d');
              var barChart = new Chart(bar, {
                  type: 'horizontalBar',
                  data: {
                      labels: ux_labels,
                      datasets: [{
                          data: ux_values,
                          backgroundColor: ux_bgcolors,
                          borderColor: ux_bgcolors,
                          borderWidth: 1
                      }]
                  },
                  options: {
                      legend: {
                          display: false,
                          position: 'top',
                      },
                      scales: {
                          yAxes: [{
                              ticks: {
                                  beginAtZero:true,
                              },
                          }],
                      }
                  }
              });
      
              var pie = document.getElementById('ux_pie_chart').getContext('2d');
              var pieChart = new Chart(pie, {
                  type: 'pie',
                  data: {
                      labels: ux_labels,
                      datasets: [{
                          data: ux_values,
                          backgroundColor: ux_bgcolors,
                          borderColor: ux_bgcolors,
                          borderWidth: 1
                      }]
                  },
                  options: {
                  responsive: true,
                  maintainAspectRatio: false,
                  legend: {
                      display: true,
                      position: 'top',
                  },
                  scales: {
                      yAxes: [{
                          ticks: {
                              beginAtZero:true,
                          },
                      }],
                  }
                  }
              });
          }
      });
}

// generate csf graph
function generate_csf_graph(id){
  
    id = $('#csf_questions').val();

    if(id > 0){
        $('#csf_bar_chart').remove();
        $('#csf_pie_chart').remove();

        $('.csf-bar').append('<canvas id="csf_bar_chart" height="100"></canvas>');
        $('.csf-pie').append('<canvas id="csf_pie_chart" height="100"></canvas>');
    }

    var csf_labels = [];
    var csf_values = [];
    var csf_bgcolors = ['#52BE80','#58D68D','#F4D03F','#F5B7B1','#CD6155'];
  
    //csf
    $.ajax({
      method: 'GET',
      url: base_url + "oprs/feedbacks/get_csf_graph/" + id,
      async: false,
      dataType: "json",
      success: function (response) {
            $.each(response, function(key, val){
            csf_values.push(val.total);
                csf_labels.push(val.label);
            });
    
            
            var bar = document.getElementById('csf_bar_chart').getContext('2d');
            var barChart = new Chart(bar, {
                type: 'horizontalBar',
                data: {
                    labels: csf_labels,
                    datasets: [{
                        data: csf_values,
                        backgroundColor: csf_bgcolors,
                        borderColor: csf_bgcolors,
                        borderWidth: 1
                    }]
                },
                options: {
                    legend: {
                        display: false,
                        position: 'top',
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero:true,
                            },
                        }],
                    }
                }
            });
    
            var pie = document.getElementById('csf_pie_chart').getContext('2d');
            var pieChart = new Chart(pie, {
                type: 'pie',
                data: {
                    labels: csf_labels,
                    datasets: [{
                        data: csf_values,
                        backgroundColor: csf_bgcolors,
                        borderColor: csf_bgcolors,
                        borderWidth: 1
                    }]
                },
                options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: true,
                    position: 'top',
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:true,
                        },
                    }],
                }
                }
            });
        }
    });
}

// store manuscript id to global variable for adding remarks
function add_remarks(id){
    man_id = id;
}

// manage email notification content
function edit_email_content(id){

    var html_body;
    var roles = '';
    var check_roles = '';


    $.ajax({
        type: "GET",
        url: base_url + "oprs/emails/get_email_content/"+id,
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            $.each(data, function(key, val) {
                $('#emailContentModal .modal-title').text(val.enc_subject);
                $('#email_content_form #enc_subject').val(val.enc_subject);
                $('#email_content_form #enc_description').val(val.enc_description);
                $('#email_content_form #enc_cc').val(val.enc_cc);
                $('#email_content_form #enc_bcc').val(val.enc_bcc);
                $('#email_content_form #enc_process_id').val(val.enc_process_id);
                html_body = val.enc_content;
                var user_groups = val.enc_user_group;

                check_roles = user_groups.split(',')
            });

            $.each(check_roles,function(key, val){
                if(id == 1){
                    $('#enc_user_group'+id).attr('onclick','return false;');
                }else{
                    $('#enc_user_group'+id).removeAttr('onclick','return false;');
                }
                $('#enc_user_group'+val).prop('checked', true);
            });

            $(tinymce.get('enc_content').getBody()).html(html_body);
        }
    });
    $('#emailContentModal').modal('toggle');

    
}

// manage email notification content
function edit_email_content(id){

    var html_body;
    var roles = '';
    var check_roles = '';


    $.ajax({
        type: "GET",
        url: base_url + "oprs/emails/get_email_content/"+id,
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            $.each(data, function(key, val) {
                $('#emailContentModal .modal-title').text(val.enc_subject);
                $('#email_content_form #enc_subject').val(val.enc_subject);
                $('#email_content_form #enc_description').val(val.enc_description);
                $('#email_content_form #enc_cc').val(val.enc_cc);
                $('#email_content_form #enc_bcc').val(val.enc_bcc);
                $('#email_content_form #enc_process_id').val(val.enc_process_id);
                html_body = val.enc_content;
                var user_groups = val.enc_user_group;

                check_roles = user_groups.split(',')
            });

            $.each(check_roles,function(key, val){
                if(id == 1){
                    $('#enc_user_group'+id).attr('onclick','return false;');
                }else{
                    $('#enc_user_group'+id).removeAttr('onclick','return false;');
                }
                $('#enc_user_group'+val).prop('checked', true);
            });

            $(tinymce.get('enc_content').getBody()).html(html_body);
        }
    });
    $('#emailContentModal').modal('toggle');

    
}

function submit_editorial_review(id, title){
    // console.log(id + ' ' + title);
    $('#edit_man_id').val(id);
}


function submit_publishable(id, title){
    // console.log(id + ' ' + title);
    $('#edit_man_id').val(id);
}

function for_publication(id){
    $.ajax({
        type: "GET",
        url: base_url + "oprs/manuscripts/manuscript/" + id,
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            console.log(data);
            $.each(data, function(key, val) {
                $.each(val, function(k, v){
                    if(k == 'man_issue'){

                        var iss = (v >= 5) ? 'Special Issue No. ' + (v - 4) : v;
                        $('#publication_table #' + k).text(iss);
                    }else{
                        $('#publication_table #' + k).text(v);
                    }
                    $('#publication_form #man_id').val(id);

                });
            });
        }
    });
}

function submit_publishable(id){
    $.ajax({
        type: "GET",
        url: base_url + "oprs/manuscripts/manuscript/" + id,
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            console.log(data);
            $.each(data, function(key, val) {
                $.each(val, function(k, v){
                    if(k == 'man_issue'){

                        var iss = (v >= 5) ? 'Special Issue No. ' + (v - 4) : v;
                        $('#publishable_table #' + k).text(iss);
                    }else{
                        $('#publishable_table #' + k).text(v);
                    }
                    $('#publishable_form #man_id').val(id);

                });
            });
        }
    });
}

function publish_to_ejournal(id){
    $.ajax({
        type: "GET",
        url: base_url + "oprs/manuscripts/manuscript/" + id,
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            console.log(data);
            $.each(data, function(key, val) {
                $.each(val, function(k, v){
                    if(k == 'man_issue'){

                        var iss = (v >= 5) ? 'Special Issue No. ' + (v - 4) : v;
                        $('#pub_to_e_table #' + k).text(iss);
                    }else{
                        $('#pub_to_e_table #' + k).text(v);
                    }
                    $('#pub_to_e_form #man_id').val(id);
                    $('#pub_to_e_form #man_id').val(id);

                });
            });
        }
    });
}

function change_status(status){
    $.ajax({
        type: "POST",
        url: base_url + "oprs/manuscripts/change_status/" + man_id + '/' + status,
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            $('#trackingModal').modal('toggle');
            location.reload();
        }
    });
}
