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


$(document).ready(function() {

    setInterval(function(){

        // auto show notifications
        // show to managing editor / superadmin only
        notifications();
    
      } ,3000);

    var notif_data = localStorage.getItem('notif_data');

    if(notif_data)
    {
        $( '#dataTable' ).DataTable().search(notif_data).draw();
        localStorage.removeItem('notif_data');
    }
    
    $('#alertsDropdown').click(function()
    {
        $('.oprs_notif_list').empty();

        $.ajax({
            type: "POST",
            url: base_url + "oprs/logs/get_logs",
            dataType: "json",
            crossDomain: true,
            success: function(data) {

               var html = '<div class="list-group ">';
               $.each(data, function(key, val)
               {
                   if(key <= 6){
                    var bg = (val.notif_open == 0) ? 'bg-info' : '';
                    var tc = (val.notif_open == 0) ? 'text-white' : 'text-dark';
                    var title = get_manuscript_log(val.log_insert_id);
                    html += '<a href="javascript:void(0);" onclick="open_notif(\'' + title + '\',\'' + val.row_id + '\');" class="list-group-item list-group-item-action p-3 '+ bg + ' ' + tc +'"><strong>' + get_user_log(val.log_user_id) + '</strong> ' + val.log_action + ' <strong> \
                                                                                                    ' + title + '</strong>\
                            <small class="d-flex mt-1">'+ moment(val.date_created).fromNow() + '</small></a>';
                   }else if(key == 7){
                       html += '<a href="notifications" class="text-center p-1 text-primary"><small class="font-weight-bold">See All</small></a>';
                   }
                   
             
               });
    
               html += '</div>';
    
               $('.oprs_notif_list').append(html);
            }
        });
    });
    

    var href = $(window.location).attr("href").split('/').pop();
    $('a[href="'+href+'"]').parent().addClass('active');
    

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

    $.validator.addMethod(
        "uniqueEmail",
        function(value, element) {

            response = (revs.indexOf(value) != -1) ? false : true;

            return response;
        }
    );


    $('#table_complete_review').DataTable();
    $('#collapse_new_table').DataTable();
    $('#collapse_lapreq_table').DataTable();
    $('#collapse_decreq_table').DataTable();
    $('#collapse_laprev_table').DataTable();
    $('#controls_table').DataTable();


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

    if (prv_exp == 0) {
        $('#report_reviewer_table').DataTable();
    } else {
        $('#report_reviewer_table').DataTable({
            dom: 'lBfrtip',
            buttons: [{
                extend: 'copy',
                text: 'Copy to clipboard',
                title: 'List of Reviewers',
                action: function(e, dt, node, config) {
                    log_export('Copy to clipboard', 'List of Reviewers');
                    $.fn.dataTable.ext.buttons.copyHtml5.action.call(this, e, dt, node, config);
                }
            }, {
                extend: 'excel',
                text: 'Export as Excel',
                title: 'List of Reviewers',
                action: function(e, dt, node, config) {
                    log_export('Export as Excel', 'List of Reviewers');
                    $.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, node, config);
                }
            }, {
                extend: 'pdf',
                text: 'Export as PDF',
                title: 'List of Reviewers',
                action: function(e, dt, node, config) {
                    log_export('Export as PDF', 'List of Reviewers');
                    $.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, node, config);
                }
            }, {
                extend: 'print',
                title: 'List of Reviewers',
                action: function(e, dt, node, config) {
                    log_export('Print', 'List of Reviewers');
                    window.print();
                }
            }]
        });
    }


    $('#dataTable').DataTable();

    //get title
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

    $('#non_title').editableSelect({
        effects: 'slide'
    });

    $('#trk_title1').editableSelect({
        effects: 'slide'
    });

    $('#jor_volume').editableSelect({
        effects: 'slide'
    });

    //get members info
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
                mem_spec.push(val.mpr_specialization);
                mem_id.push(val.pp_usr_id);
                mem_exp.push(val.pp_first_name + ' ' + val.pp_middle_name + ' ' + val.pp_last_name + ' (' + val.mpr_specialization + ')');
                mem_aff.push(val.bus_name);
                mem_prf.push(val.title_name);
            });

        }
    });

    //get non-members info
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

    //get authors info
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


    if ($('#trk_rev1').length)
        autocomplete(document.getElementById("trk_rev1"), mem_exp, '#trk_rev_email1', '#trk_rev_num1', '#trk_rev_id1', '1', '#trk_rev_spec1', '#trk_title1');


    if ($('#man_author').length)
    // autocomplete_acoa(document.getElementById("man_author"), acoa);
        autocomplete_acoa(document.getElementById("man_author"), mem_exp, '#man_affiliation', '#man_email');

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

    // sign up
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
                    // url: base_url + "oprs/user/verify_email/",
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

    // upload manuscript validation
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
            man_file: {
                required: true,
                extension: 'pdf',
                maxFileSize: {
                    "unit": "MB",
                    "size": "25"
                }
            },
            man_page_position: {
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

            var form = $('#manuscript_form');
            var fromdata = false;

            if (window.FormData) {
                formdata = new FormData(form[0]);
            }

            var formAction = form.attr('action');

            $('#uploadModal').modal('toggle');
            $('body').loading('start');

            $.ajax({
                url: base_url + "oprs/manuscripts/upload/",
                data: formdata ? formdata : form.serialize(),
                cache: false,
                contentType: false,
                processData: false,
                crossDomain: true,
                type: 'POST',
                success: function(data, textStatus, jqXHR) {
                    location.reload();
                }
            });
        }
    });

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
            },
            art_year: {
                required: true,
            },
            art_issue: {
                required: true,
            },
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
                // $('#reviewerModal').modal('toggle');
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

    //publish manuscript to ejournal
    $("#publish_form").validate({
        debug: true,
        errorClass: 'text-danger',
        submitHandler: function() {

            $('body').loading('start');

            var form = $('#publish_form');
       
            if (window.FormData) {
                formdata = new FormData(form[0]);
            }

            $.ajax({
                url: base_url + "oprs/manuscripts/publish/",
                data: formdata ? formdata : form.serialize(),
                cache: false,
                contentType: false,
                processData: false,
                crossDomain: true,
                type: 'POST',
                success: function(data) {
                    location.reload();
                    // console.log(data);
                }
            });
 
        }
    });

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

    $(document).on('keyup', 'input[name^="trk_rev"]', function() {
        if (event.keyCode == 8 || event.keyCode == 46) {
            $(this).closest('div.card').find('input[name^="trk_rev_id"]').val('');
        }
    });

    $(document).on('blur', 'input[name="trk_rev[]"]', function() {

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
    });

    $(document).on('blur', 'input[name="trk_title[]"]', function() {

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
    });

    $(document).on('blur', 'input[name="trk_rev_spec[]"]', function() {

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
    });

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

            $('#finalModal').modal('toggle');

            $('body').loading('start');

            var form = $('#final_manuscript_form');
            var fromdata = false;
            if (window.FormData) {
                formdata = new FormData(form[0]);
            }
            var formAction = form.attr('action');

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

    //forgot password email verification
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

    // $('#app_btn').click(function() {
    // 	$('#man_status').val(4);
    // });

    // $('#rev_btn').click(function() {
    // 	$('#man_status').val(5);
    // });

    // $('#dis_btn').click(function() {
    // 	$('#man_status').val(6);
    // });


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

    $('#coauthors').on('click', 'a', function() {
        $(this).closest('#added_coa').remove();
    });

    var revIncr = 1;
    $('#btn_add_rev').click(function() {


        var select;
        $.each(array_prf, function(key, val) {
            select += '<option value="' + val + '">' + val + '</option>';

        });

        $('#rev_acc .collapse').removeClass('show');
        $('#rev_acc_mail .collapse').removeClass('show');
        var html = '';
        revIncr++;

        html = '<div class="card" id="added_rev">' +
            '<div class="card-header p-0" id="heading' + revIncr + '"  data-toggle="collapse" data-target="#collapse' + revIncr + '">' +
            '<h5 class="mb-0">' +
            '<button class="btn btn-link" type="button">' +
            '<span class="fa fa-address-card"></span> Reviewer ' + revIncr + ' : <span id="rev_header' + revIncr + '"></span>' +
            '</button>' +
            '<button type="button" class="btn btn-link float-right text-danger"><span class="fa fa-trash"></span></button>' +
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
        tinymce.init({
            selector: '#tiny_mail' + revIncr
        });

        load_email_content(revIncr);


    });

    $('#rev_acc').on('click', '.btn .fa-trash', function() {
        $(this).closest('#added_rev').remove();
        var find_mail = $(this).closest('#added_rev').find('.btn-link').text();
        $('#rev_acc_mail').find('.card:contains(' + find_mail + ')').remove();
    });

    $('#rev_acc').on('click', '.card-header', function() {
        var find_mail = $(this).closest('#added_rev').find('.btn-link').text();
        $('#rev_acc_mail').find('.card-header:contains(' + find_mail + ')').click();
    });


    // $('#jor_volume').blur(function()
    // {

    // 	var jor = $('#jor_volume').val();
    // 	$("#jor_issue option").attr("hidden", false);

    // 	$.ajax({
    //       type:"GET",
    //       url: base_url + "oprs/manuscripts/journal/"+jor,
    //       dataType:"json",
    //       crossDomain: true,
    //       success:
    //               function(data)
    //               {
    //               	if(data.length > 0)
    //               	{
    //               		$.each(data,function(key,val)
    // 	              	{
    // 	              		$("#jor_issue option[value='"+val.jor_issue+"']").attr('hidden',true);
    // 	              	});
    //               	}

    //               }
    //     });

    // });


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

    // prcess manuscript validation
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
            scr_status: {
                required: ""
            }
        },
        submitHandler: function() {
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
        }
    });


    $(document).on('hide.bs.modal', '#reviewerModal', function() {
        for (i = 0; i < 100; i++) {
            window.clearInterval(i);
        }
    });


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


    $("#jor_volume").blur(function() {

        var jor_vol = localStorage.getItem('jor_vol');
        localStorage.setItem('jor_vol', $(this).val());
        var text = $(this).val();
        var mail = $(tinymce.get('tiny_mail1').getBody()).html();

        if (mail.indexOf('(volume)') != -1) {
            var new_mail = mail.replace('(volume),', 'Volume ' + text.toUpperCase() + ', ');
        } else {
            var new_mail = mail.replace('Volume ' + jor_vol.toUpperCase() + ', ', 'Volume ' + text.toUpperCase() + ', ');
        }

        var tiny_count = $('#rev_acc_mail .card-body').length;

        for (i = 1; i <= tiny_count; i++) {
            $(tinymce.get('tiny_mail' + i).getBody()).html(new_mail);
        }
    });

    $("#jor_issue").change(function() {

        var jor_iss = localStorage.getItem('jor_issue');
        localStorage.setItem('jor_issue', $(this).val());
        var text = $(this).val();
        var mail = $(tinymce.get('tiny_mail1').getBody()).html();
        var prefix = text >= 5 ? 'Special Issue No. ' + (text - 4) : 'Issue ' + text;
        var prefix_rep = jor_iss >= 5 ? 'Special Issue No. ' + (jor_iss - 4) : 'Issue ' + jor_iss;

        if (mail.indexOf('(issue)') != -1) {

            var new_mail = mail.replace('(issue)', prefix);
        } else {
            var new_mail = mail.replace(prefix_rep, prefix);
        }

        var tiny_count = $('#rev_acc_mail .card-body').length;

        for (i = 1; i <= tiny_count; i++) {
            $(tinymce.get('tiny_mail' + i).getBody()).html(new_mail);
        }
    });

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

    $('#jor_year, #jor_issue, #jor_volume').on('change', function() {

        $('#jor_year').next('small').remove();

        if ($('#jor_year').val().length != 0) {
            $.ajax({
                type: "POST",
                url: base_url + "oprs/manuscripts/get_jor_id/" + $('#jor_volume').val() + "/" + $('#jor_issue').val() + "/" + $('#jor_year').val(),
                dataType: "json",
                crossDomain: true,
                success: function(data) {

                    $('#jor_year').after('<small class="text-primary"><span class="fa fa-exclamation-circle"></span> ' + data + ' Article/s</small>');
                }
            });
        }
    });

    $("#art_issue").on('change', function() {

        var text = $('#art_issue option:selected').text();

        var art_iss = localStorage.getItem('art_iss');
        localStorage.setItem('art_iss', text);


        var mail = $(tinymce.get('tiny_mail1').getBody()).html();

        if (mail.indexOf('(volume)') != -1) {
            var new_mail = mail.replace('(volume), (issue)', text);
        } else {
            var new_mail = mail.replace(art_iss, text);
        }

        var tiny_count = $('#rev_acc_mail .card-body').length;

        for (i = 1; i <= tiny_count; i++) {
            $(tinymce.get('tiny_mail' + i).getBody()).html(new_mail);
        }
    });

    $('#article-tab').click(function() {
        var jor_vol = localStorage.getItem('jor_vol');
        var jor_iss = localStorage.getItem('jor_issue');

        var mail = $(tinymce.get('tiny_mail1').getBody()).html();
        var new_mail = mail.replace('Volume ' + jor_vol.toUpperCase() + ', ', '(volume),');

        if (jor_iss >= 5) {

            var new_mail = new_mail.replace('Special Issue No. ' + (jor_iss - 4), '(issue)');
        } else {
            var new_mail = new_mail.replace('Issue ' + jor_iss, '(issue)');
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


    $('#new-tab').click(function() {
        var split_iss = localStorage.getItem('art_iss');
        var x = split_iss.split(',');
        localStorage.setItem('jor_vol', x[0]);
        localStorage.setItem('jor_issue', $.trim(x[1]));

        var mail = $(tinymce.get('tiny_mail1').getBody()).html();
        var new_mail = mail.replace(x[0], '(volume)');
        var new_mail = new_mail.replace($.trim(x[1]), '(issue)');


        var tiny_count = $('#rev_acc_mail .card-body').length;

        for (i = 1; i <= tiny_count; i++) {
            $(tinymce.get('tiny_mail' + i).getBody()).html(new_mail);
        }

        $('#art_year').val('');
        $('#art_issue').val('');
        $('#art_issue').next().hide();
    });

    $('#new_rev').click(function() {
        $('#form_journal').hide();
    });

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

    $('#revise_review').click(function() {
        $('#approve_review').prop('checked', true);
    });

    $('#disapprove_review').click(function() {
        $('#revise_review').prop('checked', false);
    });

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


    $('#custom_auth').click(function() {
        $('#man_author').val('');
        $('#man_affiliation').val('');
        $('#man_email').val('');
    });

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
                '<option value="9">Committee</option>' +
                '<option value="6">Manager</option>' +
                '<option value="3">Managing Editor</option>');
        }else{
            $('#usr_role').append('<option value="" selected>Select User Role</option>' +
                '<option value="3">Managing Editor</option>');
        }
    });

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
                '<option value="9">Committee</option>' +
                '<option value="6">Manager</option>' +
                '<option value="3">Managing Editor</option>');
        }else{
            $('#editUserModal #usr_role').append('<option value="" selected>Select User Role</option>' +
                '<option value="3">Managing Editor</option>');
        }
    });

    $('#user_control').change(function() {
        var usr_grp = $(this).val();

        $.ajax({
            type: "GET",
            url: base_url + "oprs/user/get_user_by_role/" + usr_grp,
            dataType: "json",
            crossDomain: true,
            success: function(data) {
                $.each(data, function(key, val) {
                    if ($.fn.DataTable.isDataTable("#controls_table")) {
                        $('#controls_table').DataTable().clear().destroy();
                    }

                    if (data.length > 0) {
                        var c = 1;
                        $.each(data, function(key, val) {

                            var check_add = (val.prv_add == 1) ? 'checked' : '';
                            var check_edit = (val.prv_edit == 1) ? 'checked' : '';
                            var check_delete = (val.prv_delete == 1) ? 'checked' : '';
                            var check_view = (val.prv_view == 1) ? 'checked' : '';
                            var check_export = (val.prv_export == 1) ? 'checked' : '';
                            var access = (val.usr_sys_acc == 1) ? 'OPRS' : 'eJournal';

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

                });
            }
        });

    });

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

    // prcess import backup
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



});

function countChar(val) {
    var len = val.value.length;
    if (len >= 255) {
        val.value = val.value.substring(0, 255);
    } else {
        $('.limit').text(254 - len + '/255');
    }
};

function process_man(id) {

    man_id = id;
    $('#form_journal').show();
    if($('#trk_rev1').val() == ''){
        load_email_content();       
    }

}

function tracking(id, role, title, status) {
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
                        var user_role = 'Reviewer';
                        review(proc_id, trk_c, user);
                    } else {
                        if (val.trk_source == '_sk' || val.trk_source == '_op') {
                            if (val.trk_remarks == null) {
                                var user_role = 'Author <br/>Submitted initial manuscript for review';
                            } else {
                                var user_role = 'Author <br/>Submitted final manuscript<br/>' +
                                    '<span class="alert p-1 mt-1 mb-0 pb-0"><strong>Remarks:</strong> ' +
                                    val.trk_remarks +
                                    '</span>';
                            }

                        } else {

                            if (val.trk_description == 'APPROVED' || val.trk_description == 'PUBLISHED') {

                                var user_role = 'Managing Editor' +
                                    '<br/><strong>' + val.trk_remarks + '</strong>';
                            }else if(val.trk_description == 'FINAL'){
                                var user_role = 'Committee';
                                com_review(id ,trk_c);
                            }
                            else {

                                var user_role = 'Managing Editor';
                                reviewers(id, trk_c, val.trk_remarks, role, val.trk_description, val.trk_process_datetime, title, status);
                            }
                        }

                    }

                    html = '<li class="list-group-item list-group-item-secondary flex-column align-items-start">' +
                        '<div class="d-flex w-100 justify-content-between">' +
                        '<h6 class="mb-1 font-weight-bold">' + user + '</h6>' +
                        '<small>' + moment(val.trk_process_datetime, 'YYYY-MM-DD HH:mm').format("MMMM D, h:mm a") + '</small>' +
                        '</div>' +
                        '<small class="mb-1">' + user_role + '</small><br/>' +
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

function manus_view(file, type) {
    if(type == 'abs'){
        $('#manus_view').attr('src', base_url + 'assets/oprs/uploads/abstracts/' + file);
    }else{
        $('#manus_view').attr('src', base_url + 'assets/oprs/uploads/manuscripts/' + file);
    }
    
}

function unique(array) {
    return array.filter(function(el, index, arr) {
        return index == arr.indexOf(el);
    });
}

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

function show_hidden_manus() {
    $('#default_auth').trigger('click');
    $('#manuscript_form')[0].reset();
    $('#coauthors').empty();
    $('#man_file_div').show();
    $('#man_abs_div').show();
    $('#man_key_div').show();
    $('#uploadModal .modal-footer .btn').show();
    $('#manuscript_form').show();
    $('.table-borderless').hide();
    $('#btn_approve').hide();
}

function view_reviewers(id, time, title, status) {

    // if(status == 5 || status == 4 || status == 6){ $('#new_rev').hide(); }else{ $('#new_rev').show(); }
    if(status == 5 || status == 4){ $('#new_rev').hide(); }else{ $('#new_rev').show(); }

    $('#reviewerModal p').text('TITLE : ' + title);

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
                    var date = (val.rev_date_respond != null) ? moment(val.rev_date_respond, 'YYYY-MM-DD HH:mm').format("MMMM D, h:mm a") : '-';
                    var req_status = (val.rev_status == 1) ? '<span class="badge badge-pill badge-success">ACCEPTED</span>' :
                        (val.rev_status == 0) ? '<span class="badge badge-pill badge-danger">DECLINED</span>' :
                        (val.rev_status == 2) ? '<span class="badge badge-pill badge-secondary">PENDING</span>' :
                        '<span class="badge badge-pill badge-danger">LAPSED</span>';

                    var stat = get_review_status(val.rev_id);

                    var label = (stat == 4) ? '<span class="badge badge-pill badge-success">APPROVED</span>' :
                        (stat == 5) ? '<span class="badge badge-pill badge-warning">NEEDS REVISION</span>' :
                        (stat == 6) ? '<span class="badge badge-pill badge-danger">DISAPPROVED</span>' :
                        (stat == 3) ? '<span class="badge badge-pill badge-danger">LAPSED</span>' :
                        (stat == 2) ? '<span class="badge badge-pill badge-secondary">PENDING</span>' :
                        '-';

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

function start_review(file, id, title, auth, hide_auth) {
    $('#manus_review').attr('src', base_url + 'assets/oprs/uploads/manuscripts/' + file);
    $('#rev_title').text(title);
    var author = (hide_auth == 1) ? 'Undisclosed' : auth;
    $('#rev_author').text(author);
    man_id = id;
}

function reviewers(id, trk, rem, u_role, desc, time, title, status) {

    $.ajax({
        type: "POST",
        url: base_url + "oprs/manuscripts/reviewers/" + id + "/" + time,
        dataType: "json",
        crossDomain: true,
        success: function(data) {

            if (u_role == 3) {
                $('.usr' + trk).append('<small><a href="javascript:void(0);" onclick="view_reviewers(\'' + id + '\',\'' + time + '\',\'' + title + '\',\'' + status + '\')" data-toggle="modal" data-target="#reviewerModal">View added reviewers</small>');
            }


            if (rem != '' && rem != null) {
                $('.usr' + trk).append('<div class="alert p-1 mt-1 mb-0"><small><strong>Remarks:</strong> ' +
                    rem +
                    '</small></div>');
            }
        }
    });

}

function review(id, trk, rev_name) {

    $.ajax({
        type: "POST",
        url: base_url + "oprs/manuscripts/tracker_review/" + id + "/" + man_id,
        dataType: "json",
        crossDomain: true,
        success: function(data) {

            $.each(data, function(key, val) {
                var status = (val.scr_status == 4) ? '<span class="badge badge-success mr-1">Approve</span>' :
                    (val.scr_status == 5) ? '<span class="badge badge-warning mr-1">Needs Revision</span>' :
                    (val.scr_status == 6) ? '<span class="badge badge-danger mr-1">Disapprove</span>' : '';


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

function truncate(input, id, man_id, rev_name) {
    if (input.length > 150)
        return input.substring(0, 150) + '... <a href="javascript:void(0);" onclick="view_score(\'' + id + '\',\'' + man_id + '\',\'' + rev_name + '\')" data-toggle="modal" data-target="#scoreModal"><small>See more</small></a>';
    else
        return input;
};

function generate_email(rid, mid) {


    $.ajax({
        type: "GET",
        url: base_url + "oprs/manuscripts/get_manuscript_by_id/"+man_id,
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            $.each(data, function(key, val) {
                t = val.man_title;
                a = val.man_author;
                f = val.man_affiliation;

                var header = '';
    var dear = '';
    var exp = '';
    var mail = '';

    $('#rev_header' + mid).text($('#trk_rev' + mid).val());
    $('#rev_header_mail' + mid).text($('#trk_rev' + mid).val());

    $.ajax({
        type: "POST",
        url: base_url + "oprs/manuscripts/reviewer_info/" + rid,
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            // console.log(data);
            $.each(data, function(key, val) {

                var emp_pos_id = (val.emp_pos_id != null) ? val.emp_pos_id : '';
                var emp_div_dept = (val.emp_div_dept != null) ? val.emp_div_dept : '';
                var emp_ins_id = (val.emp_ins_id != null) ? val.emp_ins_id : '';
                var emp_address = (val.emp_address != null) ? val.emp_address : '';

                header = '<span style="text-transform:uppercase"><strong>' + val.title_name + ' ' + val.pp_first_name + ' ' + val.pp_middle_name + ' ' + val.pp_last_name + ' ' + val.pp_extension + '</strong></span><br/>' +
                    emp_pos_id + '<br/>' +
                    emp_div_dept + '<br/>' +
                    emp_ins_id + '<br/>' +
                    emp_address + '<br/><br/>';

                dear = 'Dear <span style="text-transform:uppercase"><strong>' + val.title_name + ' ' + val.pp_last_name + '</strong></span> : <br/><br/>';

                exp = val.mpr_specialization;

                mail = moment().format('MMMM D, YYYY') + '<br/><br/>' +
                    header +
                    dear +

                    'The NRCP Research Journal, issued twice a year, features completed research projects of members of the National Research Council of the ' +
                    'Philippines (NRCP), particularly those funded by the Council. This is one of NRCP\'s contribution to the body of knowledge in the realm of basic ' +
                    'research which, we believe, paves the way for further research. <br/><br>' +
            
                    'For (volume), (issue), we have selected manuscripts for possible publication and one of these is entitled ' +
                    '<em>"' + t + '"</em> authored by ' + a + ' from the ' + f + '. <br/><br/>' +
            
                    'In recognition of your expertise particularly in ' + exp + ', may we request you to be a Reviewer of' +
                    'the above-mentioned manuscript. We are certain that as a Reviewer, you will render the paper a sound technical perspective worthy of publication in ' +
                    'the NRCP Research Journal. <br/></br/>' +
            
                    '<em>[SYSTEM GENERATED LINE - PLEASE DO NOT REMOVE]</em> <br/></br/>' +
            
                    'For any concern, please do not hesitate to contact the following: <br/>' +
                    '1. For publication related matters - Lanie P. Manalo, Tel. No.: 837-6141. email: laniemanalo94@yahoo.com <br/> ' +
                    '2. For technical concerns - Gerard Paul D. Balde, email: nrcp1933@gmail.com <br/><br/>' +
            
                    'Thank you for your favorable action to this request. </br><br/>' +
        
                    'Managing Editor<br/>' +
                    'NRCP Research Journal <br/><br/>' +
                    '<em>This is a sytem generated email.</em>';
            });

            var jor_iss = localStorage.getItem('jor_issue');
            var jor_vol = localStorage.getItem('jor_vol');
            var art_iss = localStorage.getItem('art_iss');

            if (jor_vol == 0) {
                var new_mail = mail.replace('(volume), (issue)', art_iss);
            } else if (jor_vol == null) {
                new_mail = mail;
            } else {
                var prefix = jor_iss >= 5 ? 'Special Issue No. ' + (jor_iss - 4) : 'Issue ' + jor_iss;
                var new_mail = mail.replace('(volume),', 'Volume ' + jor_vol + ', ');
                new_mail = new_mail.replace('(issue),', prefix);
            }

            $(tinymce.get('tiny_mail' + mid).getBody()).html(new_mail);

        }
    });
            });
        }
        });

    
    $('#rev_acc_mail .collapse').removeClass('show');
    // $('.card-header[data-target="#collapse'+mid).get(0).click();
    $('#collapse_mail' + mid).addClass('show');


}

function submit_final(id) {
    man_id = id;
}

//countdown
function timer(date, r, tf) {
    var compareDate = new Date(date);
    compareDate.setDate(compareDate.getDate() + parseInt(tf)); //just for this demo today + 7 days

    revInterval = setInterval(function() {
        $("#table-reviewers tbody tr:eq(" + r + ") td:last()").text(timeBetweenDates(compareDate, r));
    }, 1000);

}

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

function dash_reviewer(id) {
    man_id = id;
    $('#form_journal').hide();
    load_email_content();
}

function load_email_content(num = 1) {

    $.ajax({
        type: "GET",
        url: base_url + "oprs/manuscripts/get_manuscript_by_id/"+man_id,
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            $.each(data, function(key, val) {
                
                var header = '';
                var dear = '';
                var exp = '';
                var mail = '';
            
            
                dear = 'Dear <span style="text-transform:uppercase"><strong>[TITLE] [LAST NAME]</strong></span> : <br/><br/>';
                header = '<strong>[FULL NAME]</strong><br/>' +
                    '[ADDRESS]<br/><br/>';
            
                mail = moment().format('MMMM D, YYYY') + '<br/><br/>' +
                    header +
                    dear +
            
                    'The NRCP Research Journal, issued twice a year, features completed research projects of members of the National Research Council of the ' +
                    'Philippines (NRCP), particularly those funded by the Council. This is one of NRCP\'s contribution to the body of knowledge in the realm of basic ' +
                    'research which, we believe, paves the way for further research. <br/><br>' +
            
                    'For (volume), (issue), we have selected manuscripts for possible publication and one of these is entitled ' +
                    '<em>"' + val.man_title + '"</em> authored by ' + val.man_author + ' from the ' + val.man_affiliation + '. <br/><br/>' +
            
                    'In recognition of your expertise particularly in [SPECIALIZATION], may we request you to be a Reviewer of' +
                    'the above-mentioned manuscript. We are certain that as a Reviewer, you will render the paper a sound technical perspective worthy of publication in ' +
                    'the NRCP Research Journal. <br/></br/>' +
            
                    '<em>[SYSTEM GENERATED LINE - PLEASE DO NOT REMOVE]</em> <br/></br/>' +
            
                    'For any concern, please do not hesitate to contact the following: <br/>' +
                    '1. For publication related matters - Lanie P. Manalo, Tel. No.: 837-6141. email: laniemanalo94@yahoo.com <br/> ' +
                    '2. For technical concerns - Gerard Paul D. Balde, email: nrcp1933@gmail.com <br/><br/>' +
            
                    'Thank you for your favorable action to this request. </br><br/>' +
        
                    'Managing Editor<br/>' +
                    'NRCP Research Journal <br/><br/>' +
                    '<em>This is a sytem generated email.</em>';
            
            
                var jor_iss = localStorage.getItem('jor_issue');
                var jor_vol = localStorage.getItem('jor_vol');
                var art_iss = localStorage.getItem('art_iss');
            
                if (jor_vol == 0) {
                    var new_mail = mail.replace('(volume), (issue)', art_iss);
            
                } else if (jor_vol == null) {
                    new_mail = mail;
                } else {
                    var prefix = jor_iss >= 5 ? 'Special Issue No. ' + (jor_iss - 4) : 'Issue ' + jor_iss;
                    var new_mail = mail.replace('(volume),', 'Volume ' + jor_vol + ', ');
                    new_mail = new_mail.replace('(issue),', prefix);
                }
            
                $(tinymce.get('tiny_mail' + num).getBody()).html(new_mail);

            });
        }
        });

 

}

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

// function generate_non_mem_email(rid, mid) {
//     var header = '';
//     var dear = '';
//     var exp = '';
//     var mail = '';

//     $('#rev_header' + mid).text($('#trk_rev' + mid).val());
//     $('#rev_header_mail' + mid).text($('#trk_rev' + mid).val());

//     $.ajax({
//         type: "POST",
//         url: base_url + "oprs/manuscripts/non_member_info/" + rid,
//         dataType: "json",
//         crossDomain: true,
//         success: function(data) {

//             $.each(data, function(key, val) {
//                 header = '<span style="text-transform:uppercase"><strong>' + val.non_first_name + ' ' + val.non_middle_name + ' ' + val.non_last_name + '</strong></span><br/>' +
//                     val.non_affiliation + '<br/>' +
//                     val.non_email + '<br/>' +
//                     val.non_contact + '<br/><br/>';

//                 dear = 'Dear <span style="text-transform:uppercase"><strong>' + val.non_title + ' ' + val.non_last_name + '</strong></span> : <br/><br/>';

//                 exp = val.non_specialization;


//                 mail = moment().format('MMMM D, YYYY') + '<br/><br/>' +
//                     header +
//                     dear +

//                     'The DOST-NRCP publishes its NRCP Research Journal, which features the completed projects ' +
//                     'of NRCP members particularly those funded by NRCP, on a quarterly basis. This is one of ' +
//                     'NRCP\'s contribution to the body of knowledge in the realm of basic research which is we ' +
//                     'believe gives impact to further research or even new areas for research. <br/><br>' +

//                     'For (volume), (issue), we have selected the papers that will be featured and one of these is ' +
//                     'entitled <em>"' + manuscripts[man_id - 1]['title'] + '"</em> authored by ' + manuscripts[man_id - 1]['author'] + ' from the ' + manuscripts[man_id - 1]['affiliation'] + '. <br/><br/>' +

//                     'In recognition of your expertise particularly in ' + exp + ', ' +
//                     'may we request you to be a Reviewer of the paper submitted. We are certain that as Reviewer, you will render the paper a ' +
//                     'sound technical perspective worthy of publication in the NRCP Journal. <br/></br/>' +

//                     '<em>[SYSTEM GENERATED LINE - PLEASE DO NOT REMOVE]</em> <br/></br/>' +

//                     'For any concern, please do not hesitate to contact the following: <br/>' +
//                     '1. For publication related matters - Lanie P. Manalo, Tel. No.: 837-6141. email: laniemanalo94@yahoo.com <br/> ' +
//                     '2. For technical concerns - Gerard Paul D. Balde, email: nrcp1933@gmail.com <br/><br/>' +

//                     'Thank you in advance for your favorable action to this request. </br><br/>' +

//                     'Very truly yours, <br/><br/>' +


//                     '<strong>MARIETA BAÑEZ-SUMAGAYSAY, Ph. D.</strong> </br>' +
//                     'Executive Director';


//             });

//             var jor_iss = localStorage.getItem('jor_issue');
//             var jor_vol = localStorage.getItem('jor_vol');
//             var art_iss = localStorage.getItem('art_iss');

//             if (jor_vol == 0) {
//                 var new_mail = mail.replace('(volume), (issue)', art_iss);
//             } else if (jor_vol == null) {
//                 new_mail = mail;
//             } else {
//                 var prefix = jor_iss >= 5 ? 'Special Issue No. ' + (jor_iss - 4) : 'Issue ' + jor_iss;
//                 var new_mail = mail.replace('(volume),', 'Volume ' + jor_vol + ', ');
//                 new_mail = new_mail.replace('(issue),', prefix);
//             }

//             $(tinymce.get('tiny_mail' + mid).getBody()).html(new_mail);
//         }
//     });
//     $('#rev_acc_mail .collapse').removeClass('show');
//     // $('.card-header[data-target="#collapse'+mid).get(0).click();
//     $('#collapse_mail' + mid).addClass('show');

// }

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
                        '<option value="9">Committee</option>' +
                        '<option value="6">Manager</option>' +
                        '<option value="3">Managing Editor</option>');
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

function act_deact_modal(val) {
    user_status = val;
    $('#confirmDeactivationModal').modal('toggle');

    if (val == 2) {
        $('#confirmDeactivationModal .modal-title').text('Deactivate Account');
    } else {
        $('#confirmDeactivationModal .modal-title').text('Activate Account');
    }
}

function activate_deactivate_user() {

    $.ajax({
        type: "POST",
        url: base_url + "oprs/user/activate_deactivate/" + user_status + "/" + user_id,
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            location.reload();
        }
    });

    
}

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

function get_manuscript_log(id)
{
    var jqXHR = $.ajax({
        type: "GET",
        url: base_url + "oprs/manuscripts/get_manuscript_title/" + id,
        async: false,
        crossDomain: true,
    });

    var data = jqXHR.responseText.replace(/\"/g, '');

    return data;
}

function open_notif(data, id)
{
    localStorage.setItem('notif_data',data);
    window.location.href = base_url + "oprs/manuscripts";

    $.ajax({
        type: "GET",
        url: base_url + "oprs/logs/notif_open/" + id,
        async: false,
        crossDomain: true,
    });

}

function notifications()
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
                            message: '<strong>' + get_user_log(val.log_user_id) + '</strong> ' + val.log_action + ' <strong> \
                                    ' + get_manuscript_log(val.log_insert_id) + '</strong> \
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




