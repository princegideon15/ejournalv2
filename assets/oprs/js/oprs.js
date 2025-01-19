var mems = [];
var mem_mail = [];
var mem_num = [];
var mem_spec = [];
var mem_id = [];
var mem_exp = [];
var mem_aff = [];
var mem_prf = [];
var mem_type = [];
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
var sst,sstt,abst; // statistics
var arta_table,arta_age_table,arta_reg_table,arta_cc_table,arta_sqd_table; // arta
var uiux_table, uiux_sex_table; // uiux

var minutes = 5,          // otp timer
seconds = 0,          // otp timer
intervalId,           // otp timer
isStartTimer = false, // otp timer
refCode,              // reference code for otp
accessToken,          // user access token generated on logged in   
current_button_id;    // button to enable/disable for catpcha  

var recaptchaWidgetId_logout; // recaptcha widget 

var inpIncr = 0; // added peer reviewer count
var suggIncr = 1; // added peer reviewer count

$(document).ready(function() {
    
    // get user access token
    accessToken = $.ajax({
        type: "GET",
        url: base_url + "oprs/login/get_access_token",
        async:false,
        crossDomain: true,
        success: function(data) {
            if(data != 0){
                return data;
            }
        },
        error: function(xhr, status, error) {
        reject(error);
        }
    }); 

    accessToken = (accessToken.responseText).trim();

    // feedback suggestion box character limit
    var $textArea = $("#fb_suggest_ui");
    var $charCount = $("#char_count_ui");
    var maxLength = $textArea.attr("maxlength");

    $textArea.on("input", function () {
        var currentLength = $(this).val().length;
        $charCount.text(`${currentLength} / ${maxLength} characters`);

        if (currentLength > maxLength) {
            $charCount.addClass("exceeded");
        } else {
            $charCount.removeClass("exceeded");
        }
    });

    var $textArea2 = $("#fb_suggest_ux");
    var $charCount2 = $("#char_count_ux");
    var maxLength2 = $textArea2.attr("maxlength");

    $textArea2.on("input", function () {
        var currentLength = $(this).val().length;
        $charCount2.text(`${currentLength} / ${maxLength2} characters`);

        if (currentLength > maxLength2) {
            $charCount2.addClass("exceeded");
        } else {
            $charCount2.removeClass("exceeded");
        }
    });

    // process duration number

    $(".duration").keyup(function() {
        var minValue = 0; // Minimum allowed value
        var maxValue = 365; // Maximum allowed value
        var currentValue = parseInt($(this).val());

        if (currentValue < minValue) {
        $(this).val(minValue);
        } else if (currentValue > maxValue) {
        $(this).val(maxValue);
        }
    });

    // csf ui ux star rating
    var selectedRatingUI = 0;
    var selectedRatingUX = 0;

    $('.rate-ui').on('mouseover', function () {
        const value = $(this).data('value');
        $('.rate-ui').each(function () {
            $(this).toggleClass('selected', $(this).data('value') <= value);
        });
    });

    $('.rate-ui').on('mouseleave', function () {
        $('.rate-ui').each(function () {
            $(this).toggleClass('selected', $(this).data('value') <= selectedRatingUI);
        });
    });

    $('.rate-ui').on('click', function () {
        selectedRatingUI = $(this).data('value');
        
        // Remove 'selected' class from all stars and add it to the clicked star and previous stars
        $(".rate-ui").removeClass("selected");
        $(".rate-ui").each(function () {
          if ($(this).data("value") <= selectedRatingUI) {
            $(this).addClass("selected");
            $('.rate-ui-validation').text('');
          }
        });
    });
    
    $('.rate-ux').on('mouseover', function () {
        const value = $(this).data('value');
        $('.rate-ux').each(function () {
            $(this).toggleClass('selected', $(this).data('value') <= value);
        });
    });

    $('.rate-ux').on('mouseleave', function () {
        $('.rate-ux').each(function () {
            $(this).toggleClass('selected', $(this).data('value') <= selectedRatingUX);
        });
    });

    $('.rate-ux').on('click', function () {
        selectedRatingUX = $(this).data('value');
        
        // Remove 'selected' class from all stars and add it to the clicked star and previous stars
        $(".rate-ux").removeClass("selected");
        $(".rate-ux").each(function () {
          if ($(this).data("value") <= selectedRatingUX) {
            $(this).addClass("selected");
            $('.rate-ux-validation').text('');
          }
        });
    });

    // $("#feedback_form").validate({
    //     debug: true,
    //     errorClass: 'text-danger',
    //     rules: {
    //         non_title: {
    //             required: true,
    //             minlength: 2
    //         },
     
    //     },
    //     messages: {
    //         usr_captcha: {
    //             equalTo: "Incorrect verification code"
    //         },
    //         non_email: {
    //             remote: "Email already in use"
    //         }
    //     },
    //     submitHandler: function() {
    //         $.ajax({
    //             type: "POST",
    //             url: base_url + "oprs/signup/sign_up",
    //             data: $('#form_sign_up').serializeArray(),
    //             cache: false,
    //             crossDomain: true,
    //             success: function(data) {
    //                 $.notify({
    //                     icon: 'fa fa-check-circle',
    //                     message: 'Thank you for signing up. You can now log in.'
    //                 }, {
    //                     type: 'success',
    //                     timer: 3000,
    //                 });

    //                 $('#form_sign_up')[0].reset();
    //                 $('#refresh_captcha').click();
    //             }
    //         });
    //     }
    // });

    // tech rev criteria process
    $("#tech_rev_form").validate({
        debug: true,
        errorClass: 'text-danger',
        rules: {
            tr_remarks: {
                required: {
                    depends: function () {
                        return $("#tr_final").val() === "2";
                    }
                }
            }
        },
        submitHandler: function() {
            Swal.fire({
                title: "Are you sure?",
                // text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#007bff",
                cancelButtonColor: "#d33",
                confirmButtonText: "Submit"
              }).then((result) => {
                if (result.isConfirmed) {
                    $('body').loading('start');
                    $('#tedEdCriteriaModal').modal('toggle');
                    $('#submit_tech_rev_crit').prop('disabled', true);
                    var formdata = new FormData($('#tech_rev_form')[0]);
                    $.ajax({
                        url: base_url + "oprs/manuscripts/technical_review_process",
                        data: formdata,
                        cache: false,
                        contentType: false,
                        processData: false,
                        crossDomain: true,
                        type: 'POST',
                        success: function(data) {
                            
                            $('#tech_rev_form')[0].reset();
                            $('body').loading('stop');
                            Swal.fire({
                            title: "Review submitted successfully!",
                            icon: 'success',
                            // html: "I will close in <b></b> milliseconds.",
                            timer: 2000,
                            timerProgressBar: true,
                            didOpen: () => {
                                Swal.showLoading();
                                const timer = Swal.getPopup().querySelector("b");
                                timerInterval = setInterval(() => {
                                timer.textContent = `${Swal.getTimerLeft()}`;
                                }, 100);
                            },
                            willClose: () => {
                                clearInterval(timerInterval);
                                location.reload();
                            }
                            }).then((result) => {
                                /* Read more about handling dismissals below */
                                if (result.dismiss === Swal.DismissReason.timer) {
                                    console.log("I was closed by the timer");
                                }
                                location.reload();
                            });
                        }
                    });
                }
            });
        }
    });

    $('#submit_feedback').on('click', function(){
        if ($(".rate-ui.selected").length > 0 && $(".rate-ux.selected").length > 0) {

            var uiSuggestion = $('#fb_suggest_ui').val();
            var uxSuggestion = $('#fb_suggest_ux').val();
            
            var data = {
            'ui' : selectedRatingUI,
            'ux' : selectedRatingUX,
            'ui_sug' : uiSuggestion,
            'ux_sug' : uxSuggestion,
            'csf_system' : 'eReview'
            };
    
            const captcha = grecaptcha.getResponse(recaptchaWidgetId_logout);
    
            if (captcha) {
            $(this).prop('disabled', true);
                // alert("reCAPTCHA is checked and valid!");
                $.ajax({
                type: "POST",
                url: base_url + 'oprs/feedbacks/submit_csf_ui_ux',
                data:  data,
                cache: false,
                crossDomain: true,
                success: function(data) {
                    $('#feedbackModal').modal('toggle');
                    if(data == 1){
                    var timerInterval;
                    Swal.fire({
                        title: "Thank you for your feedback.",
                        html: "Logging out...",
                        icon: "success",
                        allowOutsideClick: false, // Prevent closing by clicking outside
                        allowEscapeKey: false,   // Prevent closing with the Escape key
                        allowEnterKey: false,    // Prevent closing with the Enter key
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: () => {
                        Swal.showLoading();
                        // const timer = Swal.getPopup().querySelector("b");
                        // timerInterval = setInterval(() => {
                        //   timer.textContent = `${Swal.getTimerLeft()}`;
                        // }, 100);
                        },
                        willClose: () => {
                        clearInterval(timerInterval);
                        }
                    }).then((result) => {
                        /* Read more about handling dismissals below */
                        if (result.dismiss === Swal.DismissReason.timer) {
                        window.location.href = base_url + "oprs/login/logout";
                        }
                    });
                    }else{
                    console.log('Something went wrong.');
                    }
                }
                });
            } else {
                console.log("Please complete the reCAPTCHA!");
            }
    
        } else {
            if($(".rate-ui.selected").length == 0){
                $('.rate-ui-validation').text('Please select at least one star.');
            }

            if($(".rate-ux.selected").length == 0){
                $('.rate-ux-validation').text('Please select at least one star.');
            }

            return;
        }
    });

    $('#search').on('keypress', function (event) {
        if (event.which === 13) { // 13 is the key code for Enter
            event.preventDefault(); // Prevent form submission
            var search_value = $(this).val();
            var search_filter = $('input[name="search_filter"]:checked').val();

            if(search_value){

                
                var data = {
                    search: search_value,
                    filter: search_filter
                };
                
                $.ajax({
                    url: base_url + "oprs/manuscripts/search",
                    data: data,
                    cache: false,
                    crossDomain: true,
					dataType: 'json',
                    type: "POST",
                    success: function(data) {
						$('#search_result').empty();

                        if(data.length > 0){
                            $('#searchModal .alert').addClass('d-none');


							var html = '<div class="list-group overflow-hidden" style="max-height:65vh" id="search_result_list">';
							$.each(data, function(key, val){
								var coas = (val.coas) ? ', ' + val.coas : '';
								html += `<a href="javscript:void(0);" class="list-group-item list-group-item-action p-3 pe-5" aria-current="true" onclick="view_manus('${val.row_id}')">
										<h6 class="mb-1 fw-bold text-truncate">${val.man_title}</h6>
										<p class="mb-1 text-truncate">${val.man_author}${coas}</p>
										<p class="small text-truncate">${val.man_keywords}</p>
										</a>`;
							});

							html += '</div>';
							
							$('#search_result').append(html);
								
                        }else{
                            $('#searchModal .alert').removeClass('d-none');
                            $('#searchModal .alert').html('<span class="oi oi-warning"></span>Sorry, no results found.');
                        }
                    }
                });

            }


            
        }
    });

    var idleTime = 0;

    if(accessToken != 0){
      $(document).on('mousemove keydown scroll', function() {
          idleTime = 0;
      });
  
      var timerInterval = setInterval(function() {
          idleTime += 1;
          
          if (idleTime >= 1200) { // 20 minutes in seconds
  
              Swal.fire({
                title: "Session Expired",
                text: "You have been idle for 20 minutes. Please log in again.",
                icon: "info",
                confirmButtonColor: "#0c6bcb",
              
              }).then(function () {
                window.location = base_url + "oprs/login";
              });
              
              // Trigger logout or other actions
              clearInterval(timerInterval); // Stop the timer
              destroyUserSession();
          }
      }, 1000); // Check every 1 second
    }

    // 5 mins timer for otp
    var url = window.location.pathname; // Get the current path
    var segments = url.split('/'); // Split the path by '/'
    // Make sure there are enough segments
    if (segments.length > 2) {
      var secondToLastSegment = segments[segments.length - 2];
      refCode = url.split('/').pop();
      
      if(secondToLastSegment == 'verify_otp'){ // login otp, create client account otp
        getCurrentOTP(refCode);
      }else if(secondToLastSegment == 'csf_arta'){
        current_button_id = "#submit_csf_arta";
      }
    } else {
        // console.log("Not enough segments in the URL.");
    }

    // get members info
    $.ajax({
        type: "GET",
        url: base_url + "oprs/manuscripts/members",
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
                mem_type.push((val.usr_grp_id == 3) ? 'Member' : 'Non-Member');
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

    if($('#tiny_mail1').length > 0){
        tinymce.init({
            selector: '#tiny_mail1',
            forced_root_block : false,
            height : "750"
        });
    }

    if($('#editor_tiny_mail1').length > 0){
        tinymce.init({
            selector: '#editor_tiny_mail1',
            forced_root_block : false,
            height : "750"
        });
    }

    if($('#enc_content').length > 0){
        tinymce.init({
            selector: '#enc_content',
            forced_root_block : false,
            height : "400"
        });
    }
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
            url: base_url + "oprs/notifications/notif_tracker",
            dataType: "json",
            crossDomain: true,
            success: function(data) {  
                // console.log(data);
                var html = '<div class="list-group" style="font-size:14px"> \
                <a class="list-group-item fw-bold pl-3 pb-1 pt-1 h4">Notifications</a>';
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
        //                html += '<a href="notifications" class="text-center p-1 text-primary"><small class="fw-bold">See All</small></a>';
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

    $.validator.addMethod("texFile", function (value, element) {
        return this.optional(element) || /\.(tex)$/i.test(value);
    }, "Please upload a valid .tex file.");

    // unused upload manuscript (author only)
    // jQuery(function ($) {
        // "use strict";
        //validate upload manuscript form
        $("#manuscript_form").validate({
            debug: true,
            errorClass: 'text-danger',
            rules: {
                man_title: {
                    required: true,
                    remote: {
                        url: base_url + "oprs/manuscripts/unique_title",
                        type: "post"
                    }
                },
                man_author: {
                    required: true,
                },
                man_pages: {
                    required: true,
                },
                man_abs : {
                    required: true,
                    extension: "pdf",
                    filesize : 20000000,
                },
                man_file: {
                    required: true,
                    extension: "pdf",
                    filesize : 20000000,
                },
                man_word: {
                    required: true,
                    extension: "doc|docx",
                    filesize : 20000000,
                },
                man_latex: {
                    texFile: true, // Use the custom rule
                    filesize : 20000000,
                },
                man_type: {
                    required: true,
                },
                
                man_page_position: {
                    required: true,
                },
                man_author_type: {
                    required: true,
                },
                man_affiliation: {
                    required: true,
                },
                man_email: {
                    required: true,
                }
            },
            messages: {
                man_title: {
                    remote: "Manuscript title already exist."
                }
            },
            errorPlacement: function (error, element) {
              if (element.attr("name") === "man_author_type") {
                error.appendTo("#author_type_error"); // Place the error below the radio buttons
              } else {
                error.insertAfter(element); // Default behavior for other inputs
              }
            },
            submitHandler: function() {
                // var full = $('#man_file')[0].files[0].size;
                // var abs = $('#man_abs')[0].files[0].size;
                // var word = $('#man_word')[0].files[0].size;
                // var latex = $('#man_latex')[0].files[0].size;
                // if(full < 20000000) {
                //     $('#badge_full').next('.bg-danger').hide();
                // }else if(abs < 20000000){
                //     $('#badge_abs').next('.bg-danger').hide();
                // }else if(word < 20000000){
                //     $('#badge_word').next('.bg-danger').hide();
                // }else if(latex < 20000000){
                //     $('#badge_latex').next('.bg-danger').hide();
                // }

                // if (full >= 20000000) {
                //     $('#badge_full').after(' <span class="badge rounded-pill bg-danger"><span class="oi oi-warning"></span> File size must not exceed 20 MB</span>');
                // }else if(abs >= 20000000){
                //     $('#badge_abs').after(' <span class="badge rounded-pill bg-danger"><span class="oi oi-warning"></span> File size must not exceed 20 MB</span>');
                // }else if(word >= 20000000){
                //     $('#badge_word').after(' <span class="badge rounded-pill bg-danger"><span class="oi oi-warning"></span> File size must not exceed 20 MB</span>');
                // }else {
                // }
                // $('#confirmUploadModal').modal('toggle');

                
                Swal.fire({
                    title: "Are you sure?",
                    // text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#007bff",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Submit"
                  }).then((result) => {
                    if (result.isConfirmed) {

                        $('body').loading('start');
                        $('#uploadModal').modal('toggle');
                        
                        var formdata = new FormData($('#manuscript_form')[0]);
        
                        $.ajax({
                            url: base_url + "oprs/manuscripts/upload",
                            data: formdata,
                            cache: false,
                            contentType: false,
                            processData: false,
                            crossDomain: true,
                            type: 'POST',
                            success: function(data) {
                                $('body').loading('stop');
                                Swal.fire({
                                title: "Manuscript submitted successfully!",
                                icon: 'success',
                                // html: "I will close in <b></b> milliseconds.",
                                timer: 2000,
                                timerProgressBar: true,
                                didOpen: () => {
                                    Swal.showLoading();
                                    const timer = Swal.getPopup().querySelector("b");
                                    timerInterval = setInterval(() => {
                                    timer.textContent = `${Swal.getTimerLeft()}`;
                                    }, 100);
                                },
                                willClose: () => {
                                    clearInterval(timerInterval);
                                    location.reload();
                                }
                                }).then((result) => {
                                    /* Read more about handling dismissals below */
                                    if (result.dismiss === Swal.DismissReason.timer) {
                                        console.log("I was closed by the timer");
                                    }
                                    location.reload();
                                });
                            }
                        });
                    }
                  });

                // var form = $('#manuscript_form');
                // var formdata = false;
        
                // if (window.FormData) {
                //     formdata = new FormData(form[0]);
                // }


            }
        });

    // });

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
            url: base_url + "oprs/manuscripts/upload",
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

    // user types 
    var utt = $('#user_types_table').DataTable();
 
    utt.on( 'order.dt search.dt', function () {
        utt.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();

    // process time duration 
    var pdt = $('#process_duration_table').DataTable();
 
    pdt.on( 'order.dt search.dt', function () {
        pdt.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();
    
    // status types 
    var utt = $('#status_types_table').DataTable();
 
    utt.on( 'order.dt search.dt', function () {
        utt.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();

    // publication types 
    var utt = $('#publication_types_table').DataTable();
 
    utt.on( 'order.dt search.dt', function () {
        utt.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();
    
    // criteria 
    var ct = $('#criteria_table').DataTable();
 
    ct.on( 'order.dt search.dt', function () {
        ct.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();
    
    // submission summary 
    sst = $('#sub_sum_table').DataTable({
        "order": [
            [0, "asc"]
        ],
        columnDefs: [
            {
                targets: 0, // Target the first column (ID column)
                visible: false // Hide the ID column
            }
        ],
        autowidth: true,
        dom: "<'row'<'col-sm-12'B>>" +    // Buttons in their own row at the top
             "<'row'<'col-sm-6'l><'col-sm-6'f>>" +  // Length menu and Search
             "<'row'<'col-sm-12'tr>>" +   // Table itself
             "<'row'<'col-sm-5'i><'col-sm-7'p>>",   // Info and Pagination
        buttons: [
            {
                extend: 'colvis',
                text: 'Column Visibility'
            },
            {
                extend: 'copy',
                text: 'Copy to clipboard',
                messageTop: 'Submission Summary',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRCP Research Journal',
                action: function (e, dt, node, config) {
                    // action saved to logs table
                    // log_export('copied activity logs to clipboard');
                    $.fn.dataTable.ext.buttons.copyHtml5.action.call(this, e, dt, node, config);
                }
            },
            {
                extend: 'excel',
                text: 'Export as Excel',
                messageTop: 'Submission Summary',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRCP Research Journal',
                action: function (e, dt, node, config) {
                    // action saved to logs table
                    // log_export('exported activity logs as excel');
                    $.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, node, config);
                }
            },
            {
                extend: 'pdf',
                text: 'Export as PDF',
                messageTop: 'Submission Summary',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES' + '\n' + 'NRCP Research Journal',
                action: function (e, dt, node, config) {
                    // action saved to logs table
                    // log_export('exported activity logs as pdf');
                    $.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, node, config);
                }
            },
            {
                extend: 'print',
                messageTop: 'Submission Summary',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRCP Research Journal',
                action: function (e, dt, node, config) {
                    // action saved to logs table
                    // log_export('printed activity logs');
                    window.print();
                }
            }
        ]
    });
    
    // submission statistics 
    sstt = $('#sub_stats_table').DataTable({
        "order": [
            [0, "asc"]
        ],
        columnDefs: [
            {
                targets: 0, // Target the first column (ID column)
                visible: false // Hide the ID column
            }
        ],
        autowidth: true,
        dom: "<'row'<'col-sm-12'B>>" +    // Buttons in their own row at the top
             "<'row'<'col-sm-6'l><'col-sm-6'f>>" +  // Length menu and Search
             "<'row'<'col-sm-12'tr>>" +   // Table itself
             "<'row'<'col-sm-5'i><'col-sm-7'p>>",   // Info and Pagination
        buttons: [
            {
                extend: 'colvis',
                text: 'Column Visibility'
            },
            {
                extend: 'copy',
                text: 'Copy to clipboard',
                messageTop: 'Submission Statistics',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRCP Research Journal',
                action: function (e, dt, node, config) {
                    // action saved to logs table
                    // log_export('copied activity logs to clipboard');
                    $.fn.dataTable.ext.buttons.copyHtml5.action.call(this, e, dt, node, config);
                }
            },
            {
                extend: 'excel',
                text: 'Export as Excel',
                messageTop: 'Submission Statistics',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRCP Research Journal',
                action: function (e, dt, node, config) {
                    // action saved to logs table
                    // log_export('exported activity logs as excel');
                    $.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, node, config);
                }
            },
            {
                extend: 'pdf',
                text: 'Export as PDF',
                messageTop: 'Submission Statistics',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES' + '\n' + 'NRCP Research Journal',
                action: function (e, dt, node, config) {
                    // action saved to logs table
                    // log_export('exported activity logs as pdf');
                    $.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, node, config);
                }
            },
            {
                extend: 'print',
                messageTop: 'Submission Statistics',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRCP Research Journal',
                action: function (e, dt, node, config) {
                    // action saved to logs table
                    // log_export('printed activity logs');
                    window.print();
                }
            }
        ]
    });
    
    // author by sex statistics 
    abst = $('#auth_by_sex_table').DataTable({
        "order": [
            [0, "desc"]
        ],
        autowidth: true,
        dom: "<'row'<'col-sm-12'B>>" +    // Buttons in their own row at the top
             "<'row'<'col-sm-6'l><'col-sm-6'f>>" +  // Length menu and Search
             "<'row'<'col-sm-12'tr>>" +   // Table itself
             "<'row'<'col-sm-5'i><'col-sm-7'p>>",   // Info and Pagination
        buttons: [
            {
                extend: 'copy',
                text: 'Copy to clipboard',
                messageTop: 'Submission Statistics',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRCP Research Journal',
                action: function (e, dt, node, config) {
                    // action saved to logs table
                    // log_export('copied activity logs to clipboard');
                    $.fn.dataTable.ext.buttons.copyHtml5.action.call(this, e, dt, node, config);
                }
            },
            {
                extend: 'excel',
                text: 'Export as Excel',
                messageTop: 'Submission Statistics',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRCP Research Journal',
                action: function (e, dt, node, config) {
                    // action saved to logs table
                    // log_export('exported activity logs as excel');
                    $.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, node, config);
                }
            },
            {
                extend: 'pdf',
                text: 'Export as PDF',
                messageTop: 'Submission Statistics',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES' + '\n' + 'NRCP Research Journal',
                action: function (e, dt, node, config) {
                    // action saved to logs table
                    // log_export('exported activity logs as pdf');
                    $.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, node, config);
                }
            },
            {
                extend: 'print',
                messageTop: 'Submission Statistics',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRCP Research Journal',
                action: function (e, dt, node, config) {
                    // action saved to logs table
                    // log_export('printed activity logs');
                    window.print();
                }
            }
        ]
    });
    
    // arta 
    arta_table = $('#arta_table').DataTable({
        "order": [
            [0, "asc"]
        ],
        columnDefs: [
            {
                targets: '_all',
                className: 'dt-center'
            }
        ],
        autowidth: true,
        dom: "<'row'<'col-sm-12'B>>" +    // Buttons in their own row at the top
             "<'row'<'col-sm-6'l><'col-sm-6'f>>" +  // Length menu and Search
             "<'row'<'col-sm-12'tr>>" +   // Table itself
             "<'row'<'col-sm-5'i><'col-sm-7'p>>",   // Info and Pagination
        buttons: [
            {
                extend: 'colvis',
                text: 'Column Visibility'
            },
            {
                extend: 'copy',
                text: 'Copy to clipboard',
                messageTop: 'CSF-ARTA Respondents',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRCP Research Journal',
                action: function (e, dt, node, config) {
                    // action saved to logs table
                    // log_export('copied activity logs to clipboard');
                    $.fn.dataTable.ext.buttons.copyHtml5.action.call(this, e, dt, node, config);
                }
            },
            {
                extend: 'excel',
                text: 'Export as Excel',
                messageTop: 'CSF-ARTA Respondents',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRCP Research Journal',
                action: function (e, dt, node, config) {
                    // action saved to logs table
                    // log_export('exported activity logs as excel');
                    $.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, node, config);
                }
            },
            {
                extend: 'pdf',
                text: 'Export as PDF',
                messageTop: 'CSF-ARTA Respondents',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES' + '\n' + 'NRCP Research Journal',
                action: function (e, dt, node, config) {
                    // action saved to logs table
                    // log_export('exported activity logs as pdf');
                    $.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, node, config);
                }
            },
            {
                extend: 'print',
                messageTop: 'CSF-ARTA Respondents',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRCP Research Journal',
                action: function (e, dt, node, config) {
                    // action saved to logs table
                    // log_export('printed activity logs');
                    window.print();
                }
            }
        ]
    });
    
    // arta age 
    arta_age_table = $('#arta_age_table').DataTable({
        "order": [
            [0, "asc"]
        ],
        paging: false,
        columnDefs: [
            {
                targets: 0, // Target the first column (ID column)
                visible: false // Hide the ID column
            },
            {
                targets: '_all', // Target the first column (ID column)
                className: 'dt-center' // Hide the ID column
            }
        ],
        autowidth: true,
        dom: "<'row'<'col-sm-12'B>>" +    // Buttons in their own row at the top
             "<'row'<'col-sm-6'l><'col-sm-6'f>>" +  // Length menu and Search
             "<'row'<'col-sm-12'tr>>" +   // Table itself
             "<'row'<'col-sm-5'i><'col-sm-7'p>>",   // Info and Pagination
        buttons: [
            {
                extend: 'colvis',
                text: 'Column Visibility'
            },
            {
                extend: 'copy',
                text: 'Copy to clipboard',
                messageTop: 'CSF-ARTA Respondents by Age',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRCP Research Journal',
                action: function (e, dt, node, config) {
                    // action saved to logs table
                    // log_export('copied activity logs to clipboard');
                    $.fn.dataTable.ext.buttons.copyHtml5.action.call(this, e, dt, node, config);
                }
            },
            {
                extend: 'excel',
                text: 'Export as Excel',
                messageTop: 'CSF-ARTA Respondents by Age',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRCP Research Journal',
                action: function (e, dt, node, config) {
                    // action saved to logs table
                    // log_export('exported activity logs as excel');
                    $.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, node, config);
                }
            },
            {
                extend: 'pdf',
                text: 'Export as PDF',
                messageTop: 'CSF-ARTA Respondents by Age',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES' + '\n' + 'NRCP Research Journal',
                action: function (e, dt, node, config) {
                    // action saved to logs table
                    // log_export('exported activity logs as pdf');
                    $.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, node, config);
                }
            },
            {
                extend: 'print',
                messageTop: 'CSF-ARTA Respondents by Age',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRCP Research Journal',
                action: function (e, dt, node, config) {
                    // action saved to logs table
                    // log_export('printed activity logs');
                    window.print();
                }
            }
        ]
    });

    // arta region 
    arta_reg_table = $('#arta_reg_table').DataTable({
        "order": [
            [0, "asc"]
        ],
        paging: false,
        columnDefs: [
            {
                targets: 0, // Target the first column (ID column)
                visible: false // Hide the ID column
            },
            {
                targets: '_all', // Target the first column (ID column)
                className: 'dt-center' // Hide the ID column
            }
        ],
        autowidth: true,
        dom: "<'row'<'col-sm-12'B>>" +    // Buttons in their own row at the top
             "<'row'<'col-sm-6'l><'col-sm-6'f>>" +  // Length menu and Search
             "<'row'<'col-sm-12'tr>>" +   // Table itself
             "<'row'<'col-sm-5'i><'col-sm-7'p>>",   // Info and Pagination
        buttons: [
            {
                extend: 'colvis',
                text: 'Column Visibility'
            },
            {
                extend: 'copy',
                text: 'Copy to clipboard',
                messageTop: 'CSF-ARTA Respondents by Region',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRCP Research Journal',
                action: function (e, dt, node, config) {
                    // action saved to logs table
                    // log_export('copied activity logs to clipboard');
                    $.fn.dataTable.ext.buttons.copyHtml5.action.call(this, e, dt, node, config);
                }
            },
            {
                extend: 'excel',
                text: 'Export as Excel',
                messageTop: 'CSF-ARTA Respondents by Region',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRCP Research Journal',
                action: function (e, dt, node, config) {
                    // action saved to logs table
                    // log_export('exported activity logs as excel');
                    $.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, node, config);
                }
            },
            {
                extend: 'pdf',
                text: 'Export as PDF',
                messageTop: 'CSF-ARTA Respondents by Region',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES' + '\n' + 'NRCP Research Journal',
                action: function (e, dt, node, config) {
                    // action saved to logs table
                    // log_export('exported activity logs as pdf');
                    $.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, node, config);
                }
            },
            {
                extend: 'print',
                messageTop: 'CSF-ARTA Respondents by Region',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRCP Research Journal',
                action: function (e, dt, node, config) {
                    // action saved to logs table
                    // log_export('printed activity logs');
                    window.print();
                }
            }
        ]
    });

    // arta cc 
    arta_cc_table = $('#arta_cc_table').DataTable({
        // "order": [
        //     [0, "asc"]
        // ],
        paging: false,
        columnDefs: [
            // {
            //     targets: 0, // Target the first column (ID column)
            //     visible: false // Hide the ID column
            // },
            {
                targets: '_all', // Target the first column (ID column)
                className: 'dt-center' // Hide the ID column
            }
        ],
        autowidth: true,
        dom: "<'row'<'col-sm-12'B>>" +    // Buttons in their own row at the top
             "<'row'<'col-sm-6'l><'col-sm-6'f>>" +  // Length menu and Search
             "<'row'<'col-sm-12'tr>>" +   // Table itself
             "<'row'<'col-sm-5'i><'col-sm-7'p>>",   // Info and Pagination
        buttons: [
            {
                extend: 'colvis',
                text: 'Column Visibility'
            },
            {
                extend: 'copy',
                text: 'Copy to clipboard',
                messageTop: 'CSF-ARTA Respondents by Citizen Charter',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRCP Research Journal',
                action: function (e, dt, node, config) {
                    // action saved to logs table
                    // log_export('copied activity logs to clipboard');
                    $.fn.dataTable.ext.buttons.copyHtml5.action.call(this, e, dt, node, config);
                }
            },
            {
                extend: 'excel',
                text: 'Export as Excel',
                messageTop: 'CSF-ARTA Respondents by Citizen Charter',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRCP Research Journal',
                action: function (e, dt, node, config) {
                    // action saved to logs table
                    // log_export('exported activity logs as excel');
                    $.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, node, config);
                }
            },
            {
                extend: 'pdf',
                text: 'Export as PDF',
                messageTop: 'CSF-ARTA Respondents by Citizen Charter',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES' + '\n' + 'NRCP Research Journal',
                action: function (e, dt, node, config) {
                    // action saved to logs table
                    // log_export('exported activity logs as pdf');
                    $.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, node, config);
                }
            },
            {
                extend: 'print',
                messageTop: 'CSF-ARTA Respondents by Citizen Charter',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRCP Research Journal',
                action: function (e, dt, node, config) {
                    // action saved to logs table
                    // log_export('printed activity logs');
                    window.print();
                }
            }
        ]
    });

    // arta sqd 
    arta_sqd_table = $('#arta_sqd_table').DataTable({
        // "order": [
        //     [0, "asc"]
        // ],
        paging: false,
        columnDefs: [
            // {
            //     targets: 0, // Target the first column (ID column)
            //     visible: false // Hide the ID column
            // },
            {
                targets: '_all', // Target the first column (ID column)
                className: 'dt-center' // Hide the ID column
            }
        ],
        autowidth: true,
        dom: "<'row'<'col-sm-12'B>>" +    // Buttons in their own row at the top
             "<'row'<'col-sm-6'l><'col-sm-6'f>>" +  // Length menu and Search
             "<'row'<'col-sm-12'tr>>" +   // Table itself
             "<'row'<'col-sm-5'i><'col-sm-7'p>>",   // Info and Pagination
        buttons: [
            {
                extend: 'colvis',
                text: 'Column Visibility'
            },
            {
                extend: 'copy',
                text: 'Copy to clipboard',
                messageTop: 'CSF-ARTA Respondents by SQD',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRCP Research Journal',
                action: function (e, dt, node, config) {
                    // action saved to logs table
                    // log_export('copied activity logs to clipboard');
                    $.fn.dataTable.ext.buttons.copyHtml5.action.call(this, e, dt, node, config);
                }
            },
            {
                extend: 'excel',
                text: 'Export as Excel',
                messageTop: 'CSF-ARTA Respondents by SQD',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRCP Research Journal',
                action: function (e, dt, node, config) {
                    // action saved to logs table
                    // log_export('exported activity logs as excel');
                    $.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, node, config);
                }
            },
            {
                extend: 'pdf',
                text: 'Export as PDF',
                messageTop: 'CSF-ARTA Respondents by SQD',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES' + '\n' + 'NRCP Research Journal',
                action: function (e, dt, node, config) {
                    // action saved to logs table
                    // log_export('exported activity logs as pdf');
                    $.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, node, config);
                }
            },
            {
                extend: 'print',
                messageTop: 'CSF-ARTA Respondents by SQD',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRCP Research Journal',
                action: function (e, dt, node, config) {
                    // action saved to logs table
                    // log_export('printed activity logs');
                    window.print();
                }
            }
        ]
    });

    // ui/ux sex
    uiux_sex_table = $('#uiux_sex_table').DataTable({
        "order": [
            [0, "desc"]
        ],
        paging: false,
        columnDefs: [
            // {
            //     targets: 0, // Target the first column (ID column)
            //     visible: false // Hide the ID column
            // },
            {
                targets: '_all', // Target the first column (ID column)
                className: 'dt-center' // Hide the ID column
            }
        ],
        autowidth: true,
        dom: "<'row'<'col-sm-12'B>>" +    // Buttons in their own row at the top
             "<'row'<'col-sm-6'l><'col-sm-6'f>>" +  // Length menu and Search
             "<'row'<'col-sm-12'tr>>" +   // Table itself
             "<'row'<'col-sm-5'i><'col-sm-7'p>>",   // Info and Pagination
        buttons: [
            {
                extend: 'colvis',
                text: 'Column Visibility'
            },
            {
                extend: 'copy',
                text: 'Copy to clipboard',
                messageTop: 'CSF UI/UX by Sex',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRCP Research Journal',
                action: function (e, dt, node, config) {
                    // action saved to logs table
                    // log_export('copied activity logs to clipboard');
                    $.fn.dataTable.ext.buttons.copyHtml5.action.call(this, e, dt, node, config);
                }
            },
            {
                extend: 'excel',
                text: 'Export as Excel',
                messageTop: 'CSF UI/UX by Sex',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRCP Research Journal',
                action: function (e, dt, node, config) {
                    // action saved to logs table
                    // log_export('exported activity logs as excel');
                    $.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, node, config);
                }
            },
            {
                extend: 'pdf',
                text: 'Export as PDF',
                messageTop: 'CSF UI/UX by Sex',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES' + '\n' + 'NRCP Research Journal',
                action: function (e, dt, node, config) {
                    // action saved to logs table
                    // log_export('exported activity logs as pdf');
                    $.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, node, config);
                }
            },
            {
                extend: 'print',
                messageTop: 'CSF UI/UX by Sex',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRCP Research Journal',
                action: function (e, dt, node, config) {
                    // action saved to logs table
                    // log_export('printed activity logs');
                    window.print();
                }
            }
        ]
    });
    

    // all manuscripts;
    var amt = $('#all-manuscript').DataTable({
        "order": [[ 2, "desc" ]],
        "columnDefs" : [
            {"targets":2, "type":"date"},
        ]
    });
 
    amt.on( 'order.dt search.dt', function () {
        amt.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();

    
    $('#collapse_new_table').DataTable({
        columnDefs: [
            { width: "10px", targets: 0 } // Set the width of the first column
        ],
        // Optional: to ensure the table layout is applied correctly
        autoWidth: false 
    });

    $('#collapse_lapreq_table').DataTable({
        columnDefs: [
          { width: "10px", targets: 0 } // Set the width of the first column
        ],
        // Optional: to ensure the table layout is applied correctly
        autoWidth: false 
    });

    $('#collapse_decreq_table').DataTable({
        columnDefs: [
          { width: "10px", targets: 0 } // Set the width of the first column
        ],
        // Optional: to ensure the table layout is applied correctly
        autoWidth: false 
      });
    $('#collapse_laprev_table').DataTable({
        columnDefs: [
          { width: "10px", targets: 0 } // Set the width of the first column
        ],
        // Optional: to ensure the table layout is applied correctly
        autoWidth: false 
      });
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
    $('#collapse_reviewed_table').DataTable({
        columnDefs: [
          { width: "10px", targets: 0 } // Set the width of the first column
        ],
        // Optional: to ensure the table layout is applied correctly
        autoWidth: false 
      });
    $('#collapse_complete_table').DataTable({
        columnDefs: [
          { width: "10px", targets: 0 } // Set the width of the first column
        ],
        // Optional: to ensure the table layout is applied correctly
        autoWidth: false 
      });
    $('#collapse_reviewers_table').DataTable({
        columnDefs: [
          { width: "10px", targets: 0 } // Set the width of the first column
        ],
        // Optional: to ensure the table layout is applied correctly
        autoWidth: false 
      });
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
        $('#report_manuscript_table').DataTable({
            columnDefs: [
                { width: "10px", targets: 0 } // Set the width of the first column
            ],
            // Optional: to ensure the table layout is applied correctly
            autoWidth: false 
        });
    } else {
        $('#report_manuscript_table').DataTable({
            columnDefs: [
                { width: "10px", targets: 0 } // Set the width of the first column
            ],
            // Optional: to ensure the table layout is applied correctly
            autoWidth: false,
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
        $('#report_reviewer_table').DataTable({
            columnDefs: [
                { width: "10px", targets: 0 } // Set the width of the first column
            ],
            // Optional: to ensure the table layout is applied correctly
            autoWidth: false 
        });
    } else {
        $('#report_reviewer_table').DataTable({
            columnDefs: [
                { width: "10px", targets: 0 } // Set the width of the first column
            ],
            // Optional: to ensure the table layout is applied correctly
            autoWidth: false ,
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
        $('#report_lapreq_table').DataTable({
            columnDefs: [
                { width: "10px", targets: 0 } // Set the width of the first column
            ],
            // Optional: to ensure the table layout is applied correctly
            autoWidth: false 
        });
    } else {
        $('#report_lapreq_table').DataTable({
            columnDefs: [
                { width: "10px", targets: 0 } // Set the width of the first column
            ],
            // Optional: to ensure the table layout is applied correctly
            autoWidth: false ,
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
        $('#report_decreq_table').DataTable({
            columnDefs: [
                { width: "10px", targets: 0 } // Set the width of the first column
            ],
            // Optional: to ensure the table layout is applied correctly
            autoWidth: false 
        });
    } else {
        $('#report_decreq_table').DataTable({
            columnDefs: [
                { width: "10px", targets: 0 } // Set the width of the first column
            ],
            // Optional: to ensure the table layout is applied correctly
            autoWidth: false ,
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
        $('#report_laprev_table').DataTable({
            columnDefs: [
                { width: "10px", targets: 0 } // Set the width of the first column
            ],
            // Optional: to ensure the table layout is applied correctly
            autoWidth: false 
        });
    } else {
        $('#report_laprev_table').DataTable({
            columnDefs: [
                { width: "10px", targets: 0 } // Set the width of the first column
            ],
            // Optional: to ensure the table layout is applied correctly
            autoWidth: false ,
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
        $('#report_revman_table').DataTable({
            columnDefs: [
                { width: "10px", targets: 0 } // Set the width of the first column
            ],
            // Optional: to ensure the table layout is applied correctly
            autoWidth: false 
        });
    } else {
        $('#report_revman_table').DataTable({
            columnDefs: [
                { width: "10px", targets: 0 } // Set the width of the first column
            ],
            // Optional: to ensure the table layout is applied correctly
            autoWidth: false ,
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
        $('#report_comrev_table').DataTable({
            columnDefs: [
                { width: "10px", targets: 0 } // Set the width of the first column
            ],
            // Optional: to ensure the table layout is applied correctly
            autoWidth: false 
        });
    } else {
        $('#report_comrev_table').DataTable({
            columnDefs: [
                { width: "10px", targets: 0 } // Set the width of the first column
            ],
            // Optional: to ensure the table layout is applied correctly
            autoWidth: false ,
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
        uiux_table = $('#uiux_table').DataTable({
            columnDefs: [
                { width: "10px", targets: 0 } // Set the width of the first column
            ],
            // Optional: to ensure the table layout is applied correctly
            autoWidth: false 
        });
    } else {
        uiux_table = $('#uiux_table').DataTable({
            columnDefs: [
                { width: "10px", targets: 0 } // Set the width of the first column
            ],
            // Optional: to ensure the table layout is applied correctly
            autoWidth: false ,
            dom: 'lBfrtip',
            buttons: [{
                extend: 'copy',
                text: 'Copy to clipboard',
                messageTop: 'List of UI/UX Feedbacks',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRPC Research Journal', 
                action: function(e, dt, node, config) {
                    log_export('Copy to clipboard', 'List of UI/UX Feedbacks');
                    $.fn.dataTable.ext.buttons.copyHtml5.action.call(this, e, dt, node, config);
                },
                exportOptions: {
                    columns: ':visible',
                    format: {
                        body: function (data, row, column, node) {
                            // Replace stars with numerical rating
                            if ($(node).find('.star-icon').length) {
                                // Count the number of stars or extract the rating
                                return $(node).find('.star-icon').length;
                            }
                            // Return plain text if not stars
                            return data;
                        }
                    }
                }
            }, {
                extend: 'excel',
                text: 'Export as Excel',
                messageTop: 'List of UI/UX Feedbacks',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRPC Research Journal', 
                action: function(e, dt, node, config) {
                    log_export('Export as Excel', 'List of UI/UX Feedbacks');
                    $.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, node, config);
                },
                exportOptions: {
                    columns: ':visible',
                    format: {
                        body: function (data, row, column, node) {
                            // Replace stars with numerical rating
                            if ($(node).find('.star-icon').length) {
                                // Count the number of stars or extract the rating
                                return $(node).find('.star-icon').length;
                            }
                            // Return plain text if not stars
                            return data;
                        }
                    }
                }
            }, {
                extend: 'pdf',
                messageTop: 'List of UI/UX Feedbacks',
                text: 'Export as PDF',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES' + '\n' + 'NRPC Research Journal', 
                action: function(e, dt, node, config) {
                    log_export('Export as PDF', 'List of UI/UX Feedbacks');
                    $.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, node, config);
                },
                exportOptions: {
                    columns: ':visible',
                    format: {
                        body: function (data, row, column, node) {
                            // Replace stars with numerical rating
                            if ($(node).find('.star-icon').length) {
                                // Count the number of stars or extract the rating
                                return $(node).find('.star-icon').length;
                            }
                            // Return plain text if not stars
                            return data;
                        }
                    }
                }
            }, {
                extend: 'print',
                messageTop: 'List of UI/UX Feedbacks',
                title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRPC Research Journal', 
                action: function(e, dt, node, config) {
                    log_export('Print', 'List of UI/UX Feedbacks');
                    window.print();
                },
                exportOptions: {
                    columns: ':visible',
                    format: {
                        body: function (data, row, column, node) {
                            // Replace stars with numerical rating
                            if ($(node).find('.star-icon').length) {
                                // Count the number of stars or extract the rating
                                return $(node).find('.star-icon').length;
                            }
                            // Return plain text if not stars
                            return data;
                        }
                    }
                }
            }]
        });
    }

    // NDA in reports
    if (prv_exp == 0) {
        $('#report_nda_table').DataTable({
            columnDefs: [
                { width: "10px", targets: 0 } // Set the width of the first column
            ],
            // Optional: to ensure the table layout is applied correctly
            autoWidth: false 
        });
    } else {
        $('#report_nda_table').DataTable({
            columnDefs: [
                { width: "10px", targets: 0 } // Set the width of the first column
            ],
            // Optional: to ensure the table layout is applied correctly
            autoWidth: false ,
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
    // $.ajax({
    //     type: "GET",
    //     url: base_url + "oprs/manuscripts/authors",
    //     dataType: "json",
    //     crossDomain: true,
    //     success: function(data) {
    //         $.each(data, function(key, val) {
    //             acoa.push(val);
    //         });
    //         acoa.sort();
    //         $.unique(acoa);
    //     }
    // });

    // show member name on keyup
    if ($('#suggested_peer_rev1').length){
        autocomplete(document.getElementById("suggested_peer_rev1"), mem_exp, '#suggested_peer_rev_email1', '#suggested_peer_rev_num1', '#suggested_peer_rev_id1', '1', '#suggested_peer_rev_spec1', '#suggested_peer_rev_title1');
    }

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
        autocomplete_acoa(document.getElementById("man_author"), mem_exp, '#man_affiliation', '#man_email', '#man_usr_id', '#author_status');
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
                    url: base_url + "oprs/signup/verify_email",
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
                url: base_url + "oprs/signup/sign_up",
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

    $('#form_add_user #usr_password').on('keyup', function() {
        $("#password_strength_container").removeClass('d-none');
        if($(this).val().length > 0){
          var password = $(this).val();
          var strength = getPasswordStrength(password);
          var barColor, passwordStrength;
          if (strength <= 25) {
              barColor = 'red';
              passwordStrength = 'Weak';
          } else if (strength <= 50) {
              barColor = 'orange';
              passwordStrength = 'Good';
          } else if (strength <= 75) {
              barColor = 'yellow';
              passwordStrength = 'Fair';
          }else {
            barColor = 'green';
            passwordStrength = 'Excellent';
          }
          $('#password-strength').text(passwordStrength);
          $('#password-strength-bar').css('width' , strength + '%');
          $('#password-strength-bar').css('background-color', barColor);
        }
      });


      // Add custom validation method for password
      $.validator.addMethod("passwordCheck", function(value, element) {
        return this.optional(element) || 
            /[A-Za-z]/.test(value) && // At least 1 letter
            /\d/.test(value) &&      // At least 1 number
            /[!@#$%^&*(),.?":{}|<>]/.test(value); // At least 1 special character
    }, "Password must contain at least 1 letter, 1 number, and 1 special character.");

    // add user validation
    $("#form_add_user").validate({
        debug: true,
        errorClass: 'text-danger',
        rules: {
            usr_full_name: {
                required: true,
            },
            usr_password: {
                required: true,
                minlength: 8,
                passwordCheck: true
            },
            usr_rep_password: {
                required: true,
                equalTo: "#form_add_user #usr_password"
            },
            usr_username: {
                required: true,
                minlength: 3,
                email: true,
                remote: {
                    url: base_url + "oprs/user/verify_email",
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
            usr_sex: {
                required: true,
            },
        },
        messages: {
            usr_password: {
                // required: "Please provide a password",
                minlength: "Your password must be at least 8 characters long"
            },
            usr_rep_password: {
                // required: "Please provide a password",
                minlength: "Your password must be at least 5 characters long",
                equalTo: "The Repeast Password field does not match the Password field."
            },
            usr_username: {
                // required: "Please provide a username",
                minlength: "Your username must be at least 3 characters long",
                remote: "Email already used",
            },
            usr_role: {
                // required: "Please select user role",
            },
            usr_sex: {
                // required: "Please select sex",
            },
        },
        submitHandler: function() {

            $.ajax({
                type: "POST",
                url: base_url + "oprs/user/add_user",
                data: $('#form_add_user').serializeArray(),
                cache: false,
                crossDomain: true,
                success: function(data) {
                    Swal.fire({
                        title: "New user added successfully!",
                        icon: 'success',
                        // html: "I will close in <b></b> milliseconds.",
                        timer: 2000,
                        timerProgressBar: true,
                        didOpen: () => {
                            Swal.showLoading();
                            const timer = Swal.getPopup().querySelector("b");
                            timerInterval = setInterval(() => {
                            timer.textContent = `${Swal.getTimerLeft()}`;
                            }, 100);
                        },
                        willClose: () => {
                            clearInterval(timerInterval);
                            location.reload();
                        }
                        }).then((result) => {
                            /* Read more about handling dismissals below */
                            if (result.dismiss === Swal.DismissReason.timer) {
                                console.log("I was closed by the timer");
                            }
                            location.reload();
                        });
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
            usr_esx: {
                required: true,
            }
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
                    $('#editUserModal').modal('toggle');
                    Swal.fire({
                        title: "User updated successfully!",
                        icon: 'success',
                        // html: "I will close in <b></b> milliseconds.",
                        timer: 2000,
                        timerProgressBar: true,
                        didOpen: () => {
                            Swal.showLoading();
                            const timer = Swal.getPopup().querySelector("b");
                            timerInterval = setInterval(() => {
                            timer.textContent = `${Swal.getTimerLeft()}`;
                            }, 100);
                        },
                        willClose: () => {
                            clearInterval(timerInterval);
                            location.reload();
                        }
                        }).then((result) => {
                            /* Read more about handling dismissals below */
                            if (result.dismiss === Swal.DismissReason.timer) {
                                console.log("I was closed by the timer");
                            }
                            location.reload();
                        });
                }
            });
        }
    });

    // edit user type validation
    $("#form_edit_user_type").validate({
        debug: true,
        errorClass: 'text-danger',
        rules: {
            role_name: {
                required: true,
                remote: {
                    url: base_url + "oprs/roles/check_unique_role",
                    type: "POST",
                    data: {
                        role: function () {
                            return $("#form_edit_user_type #role_name").val(); // Name field value
                        },
                        id: function () {
                            return $("#form_edit_user_type #row_id").val(); // Current record ID
                        },
                    },
                }
            },
        },
        messages: {
            role_name: {
                remote: "This user type is already taken."
            },
        },
        submitHandler: function() {
            $.ajax({
                type: "POST",
                url: base_url + "oprs/roles/update",
                data: $('#form_edit_user_type').serializeArray(),
                cache: false,
                crossDomain: true,
                success: function(data) {
                    $('#editUserTypeModal').modal('toggle');
                    Swal.fire({
                        title: "User type updated successfully!",
                        icon: 'success',
                        // html: "I will close in <b></b> milliseconds.",
                        timer: 2000,
                        timerProgressBar: true,
                        didOpen: () => {
                            Swal.showLoading();
                            const timer = Swal.getPopup().querySelector("b");
                            timerInterval = setInterval(() => {
                            timer.textContent = `${Swal.getTimerLeft()}`;
                            }, 100);
                        },
                        willClose: () => {
                            clearInterval(timerInterval);
                            location.reload();
                        }
                        }).then((result) => {
                            /* Read more about handling dismissals below */
                            if (result.dismiss === Swal.DismissReason.timer) {
                                console.log("I was closed by the timer");
                            }
                            location.reload();
                        });
                }
            });
        }
    });

    // edit status type validation
    $("#form_edit_status_type").validate({
        debug: true,
        errorClass: 'text-danger',
        rules: {
            status_desc: {
                required: true,
                remote: {
                    url: base_url + "oprs/status/check_unique_status",
                    type: "POST",
                    data: {
                        status: function () {
                            return $("#form_edit_status_type #status_desc").val(); // Name field value
                        },
                        id: function () {
                            return $("#form_edit_status_type #id").val(); // Current record ID
                        },
                    },
                }
            },
        },
        messages: {
            status_desc: {
                remote: "This status type is already taken."
            },
        },
        submitHandler: function() {
            $.ajax({
                type: "POST",
                url: base_url + "oprs/status/update",
                data: $('#form_edit_status_type').serializeArray(),
                cache: false,
                crossDomain: true,
                success: function(data) {
                    $('#editStatusTypeModal').modal('toggle');
                    Swal.fire({
                        title: "Status type updated successfully!",
                        icon: 'success',
                        // html: "I will close in <b></b> milliseconds.",
                        timer: 2000,
                        timerProgressBar: true,
                        didOpen: () => {
                            Swal.showLoading();
                            const timer = Swal.getPopup().querySelector("b");
                            timerInterval = setInterval(() => {
                            timer.textContent = `${Swal.getTimerLeft()}`;
                            }, 100);
                        },
                        willClose: () => {
                            clearInterval(timerInterval);
                            location.reload();
                        }
                        }).then((result) => {
                            /* Read more about handling dismissals below */
                            if (result.dismiss === Swal.DismissReason.timer) {
                                console.log("I was closed by the timer");
                            }
                            location.reload();
                        });
                }
            });
        }
    });

    // edit publication type validation
    $("#form_edit_publication_type").validate({
        debug: true,
        errorClass: 'text-danger',
        rules: {
            publication_desc: {
                required: true,
                remote: {
                    url: base_url + "oprs/publication_types/check_unique_publication_type",
                    type: "POST",
                    data: {
                        publication: function () {
                            return $("#form_edit_publication_type #publication_desc").val(); // Name field value
                        },
                        id: function () {
                            return $("#form_edit_publication_type #id").val(); // Current record ID
                        },
                    },
                }
            },
        },
        messages: {
            publication_desc: {
                remote: "This publication type is already taken."
            },
        },
        submitHandler: function() {
            $.ajax({
                type: "POST",
                url: base_url + "oprs/publication_types/update",
                data: $('#form_edit_publication_type').serializeArray(),
                cache: false,
                crossDomain: true,
                success: function(data) {
                    $('#editPublicationTypeModal').modal('toggle');
                    Swal.fire({
                        title: "Publication type updated successfully!",
                        icon: 'success',
                        // html: "I will close in <b></b> milliseconds.",
                        timer: 2000,
                        timerProgressBar: true,
                        didOpen: () => {
                            Swal.showLoading();
                            const timer = Swal.getPopup().querySelector("b");
                            timerInterval = setInterval(() => {
                            timer.textContent = `${Swal.getTimerLeft()}`;
                            }, 100);
                        },
                        willClose: () => {
                            clearInterval(timerInterval);
                            location.reload();
                        }
                        }).then((result) => {
                            /* Read more about handling dismissals below */
                            if (result.dismiss === Swal.DismissReason.timer) {
                                console.log("I was closed by the timer");
                            }
                            location.reload();
                        });
                }
            });
        }
    });

    // edit tech rev criteria validation
    $("#form_edit_tech_rev_crit").validate({
        debug: true,
        errorClass: 'text-danger',
        rules: {
            crt_code: {
                required:true,
                remote: {
                    url: base_url + "oprs/criterion/check_unique_criteria_code/1",
                    type: "POST",
                    data: {
                        code: function () {
                            return $("#form_edit_tech_rev_crit #crt_code").val(); // Name field value
                        },
                        id: function () {
                            return $("#form_edit_tech_rev_crit #crt_id").val(); // Current record ID
                        },
                    },
                }
            },
            crt_desc: {
                required: true,
                remote: {
                    url: base_url + "oprs/criterion/check_unique_criteria_desc/1",
                    type: "POST",
                    data: {
                        desc: function () {
                            return $("#form_edit_tech_rev_crit #crt_desc").val(); // Name field value
                        },
                        id: function () {
                            return $("#form_edit_tech_rev_crit #crt_id").val(); // Current record ID
                        },
                    },
                }
            },
        },
        messages: {
            crt_code: {
                remote: "This Criteria code is already taken."
            },
            crt_desc: {
                remote: "This Criteria description is already taken."
            },
        },
        submitHandler: function() {
            $.ajax({
                type: "POST",
                url: base_url + "oprs/criterion/update/1",
                data: $('#form_edit_tech_rev_crit').serializeArray(),
                cache: false,
                crossDomain: true,
                success: function(data) {
                    $('#editTRCModal').modal('toggle');
                    Swal.fire({
                        title: "Criteria updated successfully!",
                        icon: 'success',
                        // html: "I will close in <b></b> milliseconds.",
                        timer: 2000,
                        timerProgressBar: true,
                        didOpen: () => {
                            Swal.showLoading();
                            const timer = Swal.getPopup().querySelector("b");
                            timerInterval = setInterval(() => {
                            timer.textContent = `${Swal.getTimerLeft()}`;
                            }, 100);
                        },
                        willClose: () => {
                            clearInterval(timerInterval);
                            location.reload();
                        }
                        }).then((result) => {
                            /* Read more about handling dismissals below */
                            if (result.dismiss === Swal.DismissReason.timer) {
                                console.log("I was closed by the timer");
                            }
                            location.reload();
                        });
                }
            });
        }
    });

    // edit peer rev criteria validation
    $("#form_edit_peer_rev_crit").validate({
        debug: true,
        errorClass: 'text-danger',
        rules: {
            pcrt_code: {
                required:true,
                remote: {
                    url: base_url + "oprs/criterion/check_unique_criteria_code/2",
                    type: "POST",
                    data: {
                        code: function () {
                            return $("#form_edit_peer_rev_crit #pcrt_code").val(); // Name field value
                        },
                        id: function () {
                            return $("#form_edit_peer_rev_crit #pcrt_id").val(); // Current record ID
                        },
                    },
                }
            },
            pcrt_desc: {
                required: true,
                remote: {
                    url: base_url + "oprs/criterion/check_unique_criteria_desc/2",
                    type: "POST",
                    data: {
                        desc: function () {
                            return $("#form_edit_peer_rev_crit #pcrt_desc").val(); // Name field value
                        },
                        id: function () {
                            return $("#form_edit_peer_rev_crit #pcrt_id").val(); // Current record ID
                        },
                    },
                }
            },
            pcrt_score: {
                required: true,
            }
        },
        messages: {
            pcrt_code: {
                remote: "This Criteria code is already taken."
            },
            pcrt_desc: {
                remote: "This Criteria description is already taken."
            },
        },
        submitHandler: function() {
            $.ajax({
                type: "POST",
                url: base_url + "oprs/criterion/update/2",
                data: $('#form_edit_peer_rev_crit').serializeArray(),
                cache: false,
                crossDomain: true,
                success: function(data) {
                    $('#editPRCModal').modal('toggle');
                    Swal.fire({
                        title: "Criteria updated successfully!",
                        icon: 'success',
                        // html: "I will close in <b></b> milliseconds.",
                        timer: 2000,
                        timerProgressBar: true,
                        didOpen: () => {
                            Swal.showLoading();
                            const timer = Swal.getPopup().querySelector("b");
                            timerInterval = setInterval(() => {
                            timer.textContent = `${Swal.getTimerLeft()}`;
                            }, 100);
                        },
                        willClose: () => {
                            clearInterval(timerInterval);
                            location.reload();
                        }
                        }).then((result) => {
                            /* Read more about handling dismissals below */
                            if (result.dismiss === Swal.DismissReason.timer) {
                                console.log("I was closed by the timer");
                            }
                            location.reload();
                        });
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
                url: base_url + "oprs/manuscripts/final_review",
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
    // $("#form_forgot").validate({
    //     debug: true,
    //     errorClass: 'text-danger',
    //     rules: {
    //         get_email: {
    //             required: true,
    //             email: true,
    //             remote: {
    //                 url: base_url + "support/forgot/check_email/",
    //                 type: "post"
    //             }
    //         },
    //         usr_role: {
    //             required: true,
    //         }
    //     },
    //     messages: {
    //         get_email: {
    //             remote: "Email not found"
    //         },
    //         usr_role: {
    //             required: "Please select one",
    //         }
    //     },
    //     submitHandler: function() {
    //         $.ajax({
    //             type: "POST",
    //             url: base_url + "support/forgot/send_password/",
    //             data: $('#form_forgot').serializeArray(),
    //             cache: false,
    //             crossDomain: true,
    //             success: function(data) {
    //                 $.notify({
    //                     icon: 'fa fa-check-circle',
    //                     message: 'Email sent! Please check your inbox.'
    //                 }, {
    //                     type: 'success',
    //                     timer: 3000,
    //                 });

    //                 $('#form_forgot')[0].reset();
    //                 $('#user_option').empty();
    //             }
    //         });
    //     }
    // });

    // dynamic adding of co-author
    var inpIncr = 0;
    $('#btn_add_coa').click(function() {
        var html = '';
        inpIncr++;

        
		html = '<div class="row mb-3">' +
                    '<p class="fw-bold">Co-author: <span id="coauthor_status' + inpIncr + '" class="text-primary"></span></p>'+
                    '<div class="col-4 autocomplete">' +
                        '<label for="coa_name' + inpIncr + '" class="fw-bold form-label">Full Name</label>' +
                        '<input class="form-control" id="coa_name' + inpIncr + '" name="coa_name[]" placeholder="First Name M.I. Last name">' +
                    '</div>' +
                    '<div class="col-3">' +
                        '<label for="coa_affiliation' + inpIncr + '" class="fw-bold form-label">Affiliation</label>' +
                        '<input type="text" class="form-control" id="coa_affiliation' + inpIncr + '" name="coa_affiliation[]" placeholder="Enter affiliation">' +
                    '</div>' +
                    '<div class="col-4">' +
                        '<label for="coa_email' + inpIncr + '" class="fw-bold form-label">Email Address</label>' +
                        '<input type="text" class="form-control" id="coa_email' + inpIncr + '" name="coa_email[]" placeholder="Enter a valid email">' +
                    '</div>' +
                    '<div class="col-1">' +
                        '<button type="button" class="btn btn-outline-danger mt-4"><span class="oi oi-x"></span></button>' +
                    '</div>' +
                '<div>';

        $('#coauthors').append(html);
        autocomplete_acoa(document.getElementById("coa_name" + inpIncr), mem_exp, '#coa_affiliation' + inpIncr, '#coa_email' + inpIncr, '', '#coauthor_status' + inpIncr);
    });

    // remove added co-author
    $('#report_reviewer_table').on('click', 'button', function() {
        $(this).closest('button').replaceWith("<span class='badge bg-success'><span class='fas fa-check-circle'> eCertification</span<");
    });

    // change button on send ecretification
    $('#coauthors').on('click', 'button', function() {
		$(this).closest('.row').remove();
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
            '<input type="text" class="form-control " id="trk_rev' + revIncr + '" name="trk_rev[]" placeholder="Search by Name or Specialization">' +
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
            },
            scr_rem_1: {
                required: true,
            },
            scr_rem_2: {
                required: true,
            },
            scr_rem_3: {
                required: true,
            },
            scr_rem_4: {
                required: true,
            },
            scr_remarks: {
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
            url: base_url + "oprs/signup/refresh_captcha",
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
    // $('#get_email').change(function() {
    //     if ($(this).val() != '')
    //         $.ajax({
    //             type: "GET",
    //             url: base_url + "support/forgot/check_multiple_account/" + $(this).val(),
    //             dataType: "json",
    //             crossDomain: true,
    //             success: function(data) {
    //                 console.log(data);
    //                 $('#user_option').empty();
    //                 if (data.length > 1) {
    //                     $('#user_option').append('<p class="fw-bold small">You have multiple account. Select (1) account only.</p>');

    //                     $.each(data, function(key, val) {
    //                         var role = (val.usr_role == 1) ? 'Author' : 'Reviewer';

    //                         $('#user_option').append('<div class="custom-control custom-radio">' +
    //                             '<input type="radio" value="' + val.usr_id + '" id="' + role + '' + val.usr_role + '" name="usr_id" class="custom-control-input">' +
    //                             '<label class="custom-control-label pt-1" for="' + role + '' + val.usr_role + '"> ' + val.usr_username + ' (' + role + ')</label>' +
    //                             '</div>');
    //                     });
    //                 } else {

    //                     $('#user_option').append('<p class="fw-bold small">Current account:</p>');

    //                     $.each(data, function(key, val) {
    //                         var role = (val.usr_role == 1) ? 'Author' : 'Reviewer';

    //                         $('#user_option').append('<div class="custom-control custom-radio">' +
    //                             '<input type="radio" checked value="' + val.usr_id + '" id="' + role + '' + val.usr_role + '" name="usr_id" class="custom-control-input">' +
    //                             '<label class="custom-control-label pt-1" for="' + role + '' + val.usr_role + '"> ' + val.usr_username + ' (' + role + ')</label>' +
    //                             '</div>');
    //                     });
    //                 }
    //             }
    //         });
    // });

    $('#form_forgot input[name="user_id"]').on('change', function(){
        $('#reset_password_btn').prop('disabled', false);
    })

    $('#loginForm input[name="user_id"]').on('change', function(){
        $('#admin_login').prop('disabled', false);
    })

    

    // for author/reviewer multiple account validation (unused)
    // $('.login #usr_username').change(function() {
    //     if ($(this).val() != '')
    //         $.ajax({
    //             type: "GET",
    //             url: base_url + "oprs/login/check_multiple_account/" + $(this).val(),
    //             dataType: "json",
    //             crossDomain: true,
    //             success: function(data) {
    //                 // console.log(data);
    //                 $('#user_option').empty();
    //                 if (data.length > 1) {
    //                     $('#user_option').append('<p class="fw-bold small">You have multiple account. Select (1) account only.</p>');

    //                     $.each(data, function(key, val) {
    //                         // var role = (val.usr_role == 1) ? 'Author' : 'Reviewer';
    //                         var usr_id = (val.usr_grp_id > 0) ? val.usr_id : val.usr_id;
    //                         var role = (val.usr_grp_id > 0) ? 'Author' : (val.usr_role == 1) ? 'Author' : 'Reviewer';
    //                         var usr_role = (val.usr_grp_id > 0) ? '1' : (val.usr_role == 1) ? '1' : '5';
    //                         var usr_username = (val.usr_grp_id > 0) ? val.usr_name : val.usr_username;

    //                         $('#user_option').append('<div class="custom-control custom-radio">' +
    //                             '<input type="radio" value="' + usr_role + '" id="' + role + '' + usr_id + '" name="usr_role" class="custom-control-input">' +
    //                             '<label class="custom-control-label pt-1" for="' + role + '' + usr_id + '"> ' + usr_username + ' (' + role + ')</label>' +
    //                             '</div>');
    //                     });
    //                 }
    //             }
    //         });
    // });

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
                    url: base_url + "oprs/user/verify_old_password",
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
                url: base_url + "oprs/user/change_password",
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
            url: base_url + "oprs/manuscripts/default_auth",
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
    // $('#usr_sys_acc').change(function() {
    //     $('#usr_role').prop('disabled', false);

    //     $('#usr_role').empty();

            
    //     $.ajax({
    //         method: 'GET',
    //         url: base_url + "oprs/user/get_user_types",
    //         async: false,
    //         dataType: "json",
    //         success: function (data) {
    //             $.each(data, function(key,val){
    //                 if ($(this).val() == 1) { // eJournal
    //                     if(val.)
    //                     $('#usr_role').append('<option value="" selected>Select User Role</option>' +
    //                         '<option value="7">Admin</option>' +
    //                         '<option value="6">Manager</option>');
    //                 }else if($(this).val() == 2) { // oprs only
    //                     $('#usr_role').append('<option value="" selected>Select User Role</option>' +
    //                         '<option value="7">Admin</option>' +
    //                         '<option value="9">Publication Committee</option>' +
    //                         '<option value="3">Managing Editor</option>' +
    //                         '<option value="6">Manager</option>' +
    //                         '<option value="10">Editor</option>' +
    //                         '<option value="11">Guest Editor</option>' +
    //                         '<option value="12">Editor-in-Chief</option> '+
    //                         '<option value="13">Layout</option>');
    //                 }else{ //both
    //                     $('#usr_role').append('<option value="" selected>Select User Role</option>' +
    //                         '<option value="3">Managing Editor</option>');
    //                 }
    //             });

    //         }
    //     });
    // });

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

                var table = $('#controls_table').DataTable({
                    columnDefs: [
                        { width: "10px", targets: 0 } // Set the width of the first column
                    ],
                    // Optional: to ensure the table layout is applied correctly
                    autoWidth: false 
                });

                if(data.length > 0){
                    $.each(data, function(key, val) {
                            var c = 1;
                      

                                var check_add = (val.prv_add == 1) ? 'checked' : '';
                                var check_edit = (val.prv_edit == 1) ? 'checked' : '';
                                var check_delete = (val.prv_delete == 1) ? 'checked' : '';
                                var check_view = (val.prv_view == 1) ? 'checked' : '';
                                var check_export = (val.prv_export == 1) ? 'checked' : '';
                                var access = (val.usr_sys_acc == 1) ? 'eJournal' : (val.usr_sys_acc == 2) ? 'eReview' : 'eJournal-eReview';

                                var html = "<div class='form-check'> \
                                            <input class='form-check-input' type='checkbox' name='prv_add[]' value='" + val.usr_id + "' " + check_add + "> \
                                            <label class='form-check-label mt-2'>Add</label> \
                                        </div> \
                                        <div class='form-check'> \
                                            <input class='form-check-input' type='checkbox' name='prv_edit[]' value='" + val.usr_id + "' " + check_edit + "> \
                                            <label class='form-check-label mt-2'>Edit</label> \
                                        </div> \
                                        <div class='form-check'> \
                                            <input class='form-check-input' type='checkbox' name='prv_delete[]' value='" + val.usr_id + "' " + check_delete + "> \
                                            <label class='form-check-label mt-2'>Delete</label> \
                                        </div> \
                                        <div class='form-check'> \
                                            <input class='form-check-input' type='checkbox' name='prv_view[]' value='" + val.usr_id + "' " + check_view + " disabled> \
                                            <label class='form-check-label mt-2'>View</label> \
                                        </div> \
                                        <div class='form-check'> \
                                            <input class='form-check-input' type='checkbox' name='prv_export[]' value='" + val.usr_id + "' " + check_export + "> \
                                            <label class='form-check-label mt-2'>Export</label> \
                                        </div>";


                                table.row.add([
                                    c++,
                                    val.usr_username,
                                    access,
                                    html
                                ]);
                                r++;
                           



                            
                            table.on('order.dt search.dt', function() {
                                table.column(0, {
                                    search: 'applied',
                                    order: 'applied'
                                }).nodes().each(function(cell, i) {
                                    cell.innerHTML = i + 1;
                                });
                            }).draw();
                    });
                }else{
                    table.on('order.dt search.dt', function() {
                        table.column(0, {
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
                url: base_url + "oprs/logs/import_backup",
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
            url: base_url + "oprs/logs/clear_logs",
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

    // author type
    $('input:radio[name="man_author_type"]').change(function(){
        var val = $(this).val();
        $('#coauthors').empty();
        if(val == 1){
            $('#add_coauthors').removeClass('d-none');
            $('#add_main_author').addClass('d-none');
        }else{
            $('#add_coauthors').addClass('d-none');
            $('#add_main_author').removeClass('d-none');
        }

        // $('#man_author').val('');
        // $('#man_affiliation').val('');
        // $('#man_email').val('');
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
    
    // submit ui/ux feedback form (unused)
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
                                <p class="text-center btn-link fw-bold"><u><a href="'+ base_url + 'oprs/login/logout");">Proceed to logout</a></u></p>';
                            
                
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
            url: base_url + "oprs/manuscripts/add_remarks",
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
            url: base_url + 'oprs/emails/update_email_content',
            data:  formData,
            cache: false,
            crossDomain: true,
            success: function(data) {
                
                $('#emailContentModal').modal('toggle');
                
                Swal.fire({
                    title: "Email notification updated successfully!",
                    icon: 'success',
                    // html: "I will close in <b></b> milliseconds.",
                    timer: 2000,
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading();
                        const timer = Swal.getPopup().querySelector("b");
                        timerInterval = setInterval(() => {
                        timer.textContent = `${Swal.getTimerLeft()}`;
                        }, 100);
                    },
                    willClose: () => {
                        clearInterval(timerInterval);
                        location.reload();
                    }
                    }).then((result) => {
                        /* Read more about handling dismissals below */
                        if (result.dismiss === Swal.DismissReason.timer) {
                            console.log("I was closed by the timer");
                        }
                        location.reload();
                    });
            }
        });
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
                url: base_url + "oprs/manuscripts/for_publication",
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
// function tracking(id, role, title, status) {

function tracking(id, role, title, status) {
    
    $('#trackingModal').modal('toggle');

    if(status == 1){
        $('#trackingModal .dropdown').show();
    }else{
        $('#trackingModal .dropdown').hide();
    }


    // var manuscript_title = decodeURIComponent(title);
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
            console.log(data);
            // if (data.length > 0) {

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
                        '<h6 class="mb-1 fw-bold">' + user + ' (' + user_role + ')</h6>' +
                        '<small>' + moment(val.trk_process_datetime, 'YYYY-MM-DD HH:mm').format("MMMM D, h:mm a") + '</small>' +
                        '</div>' +
                        '<small class="mb-1">' + user_action + '</small><br/>' +
                        '<span class="usr' + trk_c + '"></span>' +
                        '</li>';

                    $('#track_list').append(html);

                });


                $('#track_list .justify-content-between').first().addClass('text-primary');
                $('#track_list .list-group-item-secondary').first().addClass('list-group-item-primary').removeClass('list-group-item-secondary');


            // } else {

                

            //     html = '<li class="list-group-item list-group-item-secondary flex-column align-items-start">' +
            //         '<div class="d-flex w-100 justify-content-between">' +
            //         '<h6 class="mb-1 fw-bold">Pending action from Technical Desk Editor</h6>' +
            //         '</div>' +
            //         '<small class="mb-1">You have just submitted manuscript.</small><br/>' +
            //         '</li>';
            //     $('#track_list').append(html);
            // }
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

    $('#uploadModal .table-bordered > tbody').empty();

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

            $.ajax({
                type: "GET",
                url: base_url + "oprs/manuscripts/manuscript/" + id,
                dataType: "json",
                crossDomain: true,
                success: function(data) {
                    $.each(data, function(key, val) {

                        var vol = (val.man_volume != null) ? val.man_volume : 'N/a';
                        var iss = (val.man_issue != null) ? val.man_issue : 'N/a';
                        var iss = (iss >= 5) ? 'Special Issue No. ' + (iss - 4) : iss;
                        var yer = (val.man_year != null) ? val.man_year : 'N/a';
                        var rem = (val.man_remarks != null) ? val.man_remarks : 'N/a';
                        var coas = (coa.length > 0) ? coa.join('') : 'N/a';
                        var prim = (hide == 1) ? '<em>Undisclosed</em>' : val.man_author + ', ' + val.man_affiliation + ', ' + val.man_email;
                        var hide_coas = (hide == 1) ? '<em>Undisclosed</em>' : coas;
                        var man_type = (val.man_type) ? val.publication_desc : 'N/a';
                        // var author_type = (val.man_author_type == 1) ? '(Main Author)' : ((val.man_author_type == 2) ? '(Co-author)' : '');
                        var latex = (val.man_latex) ? '<a href="' + base_url + "assets/oprs/uploads/initial_latex/" + val.man_latex + '" target="_blank">LaTex</a>' : 'N/a';

                        html += '<tr>' +
                                    '<th>Title</th>' +
                                    '<td>' + val.man_title + '</td>' +
                                '</tr>' +
                                '<tr>' +
                                    '<th>No. of Pages</th>' +
                                    '<td>' + val.man_pages + '</td>' +
                                '</tr>' +
                                '<tr>' +
                                    '<th>Type of Publication</th>'+
                                    '<td>' + man_type + '</td>' +
                                '</tr>';


                                if(val.man_author_type == 1){
                                    html += '<tr>' +
                                            '<th>Corresponding Author</th>' +
                                            '<td>' + prim + ' (Main Author)</td>' +
                                        '</tr>' +
                                        '<tr>' +
                                            '<th>Co-author(s)</th>' +
                                            '<td>' + coas + '</td>' +
                                        '</tr>';
                                }
                                
                                if(val.man_author_type == 2){
                                    html += '<tr>' +
                                            '<th>Corresponding Author</th>' +
                                            '<td>' + coas + ' (Co-author)</td>' +
                                        '</tr>' +
                                        '<tr>' +
                                            '<th>Main Author</th>' +
                                            '<td>' + prim + '</td>' +
                                        '</tr>';
                                }

                        html +=  '<tr>'+
                                    '<th>Abstract</th>' +
                                    '<td><a href="' + base_url + "assets/oprs/uploads/initial_abstracts_pdf/" + val.man_abs + '" target="_blank">PDF</a></td>' +
                                '</tr>' +
                                '<tr>'+
                                    '<th>Full Text Manuscript</th>' +
                                    '<td><a href="' + base_url + "assets/oprs/uploads/initial_manuscripts_pdf/" + val.man_file + '" target="_blank">PDF</a> | <a href="' + base_url + "assets/oprs/uploads/initial_manuscripts_word/" + val.man_word + '" download>WORD</a></td>' +
                                '</tr>' +
                                '<tr>'+
                                    '<th>Latex</th>' +
                                    '<td>' + latex + '</td>' +
                                '</tr>' +
                                '<tr>' +
                                    '<th>Volume</th>' +
                                    '<td>' + vol + '</td>' +
                                '</tr>' +
                                '<tr>' +
                                    '<th>Issue</th>' +
                                    '<td>' + iss + '</td>' +
                                '</tr>' +
                                '<tr>' +
                                    '<th>Year</th>' +
                                    '<td>' + yer + '</td>' +
                                '</tr>' + 
                                '<tr>' +
                                    '<th>Remarks</th>' +
                                    '<td>' + rem + '</td>' +
                                '</tr>';
                    });

                    if(html){
                        $('#uploadModal .table-bordered > tbody').html(html);
                        $('#uploadModal').modal('toggle');
                        $('#man_file_div').hide();
                        $('#man_abs_div').hide();
                        $('#man_key_div').hide();
                        $('#uploadModal .modal-footer .btn').hide();
                        $('#manuscript_form').hide();
                        $('.table-bordered').show();
                    }
                }
            });
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
    $('.table-bordered').hide();
    $('#author_status').text('');
    $('#add_main_author').addClass('d-none');
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

                var table = $('#table-reviewers').DataTable({
                    columnDefs: [
                        { width: "10px", targets: 0 } // Set the width of the first column
                    ],
                    // Optional: to ensure the table layout is applied correctly
                    autoWidth: false 
                });
              
                revs = [];
                var c = 0;
                var r = 0;

                $.each(data, function(key, val) {

                    revs.push(val.rev_email);
                    var date = (val.rev_date_respond != null) ? moment(val.rev_date_respond, 'YYYY-MM-DD HH:mm').format("MMMM D, YYYY h:mm a") : '-';
                    var req_status = (val.rev_status == 1) ? '<span class="badge rounded-pill  bg-success">ACCEPTED</span>' :
                        (val.rev_status == 9) ? '<span class="badge rounded-pill  bg-danger">DECLINED</span>' :
                        (val.rev_status == 2) ? '<span class="badge rounded-pill  badge-secondary">PENDING REQUEST</span>' :
                        '<span class="badge rounded-pill  bg-danger">LAPSED REQUEST</span>';

                    var stat = get_review_status(val.rev_id);

                    var label = ((stat == 4) ? '<span class="badge rounded-pill  bg-success">Recommended as submitted</span>' :
                        ((stat == 5) ? '<span class="badge rounded-pill  bg-warning">Recommended with minor revisions</span>' :
                        ((stat == 6) ? '<span class="badge rounded-pill  bg-warning">Recommended with major revisions</span>' :
                        ((stat == 7) ? '<span class="badge rounded-pill  bg-danger">Not recommended</span>' :
                        ((stat == 3) ? '<span class="badge rounded-pill  bg-danger">LAPSED REVIEW</span>' :
                        ((stat == 2) ? '<span class="badge rounded-pill  badge-secondary">PENDING REVIEW</span>' :
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

                    table.row.add([
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



                table.on('order.dt search.dt', function() {
                    table.column(0, {
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

                
                var table = $('#table-editors').DataTable({
                    columnDefs: [
                        { width: "10px", targets: 0 } // Set the width of the first column
                    ],
                    // Optional: to ensure the table layout is applied correctly
                    autoWidth: false 
                });
              
                revs = [];
                var c = 0;

                $.each(data, function(key, val) {

                    var date = moment(val.date_created, 'YYYY-MM-DD HH:mm').format("MMMM D, YYYY h:mm a");
                    

                    table.row.add([
                        c++,
                        val.edit_name,
                        val.edit_specialization,
                        val.edit_email,
                        val.edit_contact,
                        date
                    ]);
                });



                table.on('order.dt search.dt', function() {
                    table.column(0, {
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

                var reco = ((status == 4) ? '<span class="badge rounded-pill  bg-success">Recommended as submitted</span>' :
                    ((status == 5) ? '<span class="badge rounded-pill  bg-warning">Recommended with minor revisions</span>' :
                    ((status == 6) ? '<span class="badge rounded-pill  bg-warning">Recommended with major revisions</span>' :
                        '<span class="badge rounded-pill  bg-danger">Not recommended</span>')));
                
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
                var status = ((val.scr_status == 4) ? '<span class="badge rounded-pill bg-success mr-1">Recommended as submitted</span>' :
                    ((val.scr_status == 5) ? '<span class="badge rounded-pill bg-warning mr-1">Recommended with minor revisions</span>' :
                    ((val.scr_status == 6) ? '<span class="badge rounded-pill bg-warning mr-1">Recommended with major revisions</span>' :
                    ((val.scr_status == 7) ? '<span class="badge rounded-pill bg-danger mr-1">Not recommended</span>' : ''))));


                $('.usr' + trk).append('<a href="javascript:void(0);" onclick="view_score(\'' + id + '\',\'' + man_id + '\',\'' + rev_name + '\')" data-toggle="modal" data-target="#scoreModal"><span class="badge rounded-pill bg-info mr-1" >Score : ' + val.scr_total + '/100</span></a>' +
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
                $('#rev_header' + mid).text($('#trk_rev' + mid).val());
                $('#rev_header_mail' + mid).text($('#trk_rev' + mid).val());

                $.ajax({
                    type: "POST",
                    url: base_url + "oprs/manuscripts/reviewer_info/" + rid,
                    dataType: "json",
                    crossDomain: true,
                    success: function(data) {

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

    $(' .table-bordered > tbody').empty();
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

            $(' .table-bordered > tbody').append(html);
            $('#uploadModal').modal('toggle');
            $('#man_file_div').hide();
            $('#uploadModal .modal-footer .btn').hide();
            $('#manuscript_form').hide();
            $('.table-bordered').show();
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
    // $('#editUserModal #usr_role').empty();

    $.ajax({
        type: "GET",
        url: base_url + "oprs/user/get_info/" + id,
        dataType: "json",
        crossDomain: true,
        success: function(data) {

            console.log(data);
            $.each(data, function(key, val) {
                
                // if(val.usr_sys_acc == 1){
                //     $('#editUserModal #usr_role').append('<option value="" selected>Select User Role</option>' +
                //         '<option value="7">Admin</option>' +
                //         '<option value="6">Manager</option>');
                // }else if(val.usr_sys_acc == 2){
                //     $('#editUserModal #usr_role').append('<option value="" selected>Select User Role</option>' +
                //         '<option value="7">Admin</option>' +
                //         '<option value="9">Publication Committee</option>' +
                //         '<option value="3">Managing Editor</option>' +
                //         '<option value="6">Manager</option>' +
                //         '<option value="10">Editor</option>' +
                //         '<option value="11">Guest Editor</option>' +
                //         '<option value="12">Editor-in-Chief</option> '+
                //         '<option value="13">Layout</option>');
                // }else{
                //     $('#editUserModal #usr_role').append('<option value="" selected>Select User Role</option>' +
                //     '<option value="3">Managing Editor</option>');
                // }

                // if(val.usr_role == 5 || val.usr_role == 1){
                //     $('#editUserModal #usr_role').attr('disabled', 'disabled')
                //     $('#editUserModal #usr_sys_acc').attr('disabled', 'disabled')
                // }else{
                //     $('#editUserModal #usr_role').attr('disabled', false)
                //     $('#editUserModal #usr_sys_acc').attr('disabled', false)
                // }

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
                        <p class="fw-bold"> \
                        ' + val['title'] + ' \
                        </p> \
                        <div class="form-group"> \
                        <label>Upload Final Manuscript</label> <span class="badge rounded-pill bg-danger">PDF</span></label>\
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
            url: base_url + "oprs/notifications/notif_tracker",
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
                    $('.oprs_notif').append('<span class="badge rounded-pill bg-danger fw-bold notif_count" style="font-size:11px;position:fixed; margin-left:-5px;margin-top:2px">' + notif_count + '</span');
                }
            }
            
        });
    }else{
        var notif_count = 0;
        var a = moment().format('MMMM DD YYYY hh:mm:ss');
        $.ajax({
            type: "GET",
            url: base_url + "oprs/notifications/notif_tracker",
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
                    $('.oprs_notif').append('<span class="badge rounded-pill bg-danger fw-bold notif_count" style="font-size:11px;position:fixed; margin-left:-5px;margin-top:2px">' + notif_count + '</span');
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
            $('.notif_count').append('<span class="badge rounded-pill bg-danger fw-bold notif_count" style="font-size:11px;position:fixed; margin-left:-5px;margin-top:2px">' + notif_count + '</span');
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

                var status = ((val.com_review == 1) ? '<span class="badge rounded-pill bg-success mr-1">No Revisions, Approve</span>' 
                : ((val.com_review == 2 ? '<span class="badge rounded-pill bg-info mr-1">Recommended with Minor Revisions</span>' 
                : ((val.com_review == 3) ? '<span class="badge rounded-pill bg-warning mr-1">Recommended with Major Revisions</span>' 
                : '<span class="badge rounded-pill bg-danger mr-1">Disapprove</span>'))));
        
        
                $('.usr' + trk).append(status);
        
                if (val.com_remarks != '' && val.com_remarks != null)
                    $('.usr' + trk).append('<div class="alert p-1 mt-1 mb-0"><small><strong>Remarks:</strong> ' +
                        val.com_remarks +
                        '</small></div>');
            });
        }
    });
}

// verify if feedback is submited already (unused)
function logout(){
    current_button_id = '#submit_feedback';
    $('#feedbackModal').modal('toggle');
    recaptchaWidgetId_logout = grecaptcha.render('captcha_logout', {
        'sitekey': '6LcTEV8qAAAAACVwToj7gI7BRdsoEEhJCnnFkWC6',
        'callback': onRecaptchaSuccess,
        'expired-callback': onRecaptchaExpired
    });

    
    // var jqXHR = $.ajax({
    //     type: "GET",
    //     url: base_url + "admin/feedback/verify/999999",
    //     async: false,
    //     crossDomain: true,
    // });
  
    // var stat = jqXHR.responseText.replace(/\"/g, '');
    // if(stat == 0){
    //     $('#feedbackModal').modal('toggle');
    // }else{
    //   window.location.href = base_url + 'oprs/login/logout';
    // }
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
                ui_values.push(val.total_ratings);
                // var star = '';
                // for (let i = 0; i < val.star_count; i++) {
                //     // Append a star icon (can be an image or a Unicode character)
                //     star += '★'; // Unicode for a filled star
                // }
                ui_labels.push(val.star_count + ' Stars');
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
                    title: {
                        display: true,
                        text: 'User Interface Rating'
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
                    position: 'bottom',
                },
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
                ux_values.push(val.total_ratings);
                // var star = '';
                // for (let i = 0; i < val.star_count; i++) {
                //     // Append a star icon (can be an image or a Unicode character)
                //     star += '★'; // Unicode for a filled star
                // }
                ux_labels.push(val.star_count + ' Stars');
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
                      title: {
                          display: true,
                          text: 'User Experience Rating',
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
                      position: 'bottom',
                  },
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
        url: base_url + "oprs/manuscripts/change_status/" + man_id + "/" + status,
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            $('#trackingModal').modal('toggle');
            location.reload();
        }
    });
}

function send_cert(rev, man){
    $.ajax({
        type: "POST",
        url: base_url + "oprs/manuscripts/send_certification/" + rev + "/" + man,
        crossDomain: true,
        success: function(data) {
            
            
            $.notify({
                icon: 'fa fa-check-circle',
                message: 'eCertification sent.'
            }, {
                type: 'success',
                timer: 3000,
            });

        }
    });
}

function togglePassword(elementID, iconID){
    var passwordInput = $(elementID);
    var passwordIcon = $(iconID);
    if (passwordInput.attr('type') === 'password') {
      passwordInput.attr('type', 'text');
      passwordIcon.removeClass('fa-eye-slash').addClass('fa-eye');
    } else {
      passwordInput.attr('type', 'password');
      passwordIcon.removeClass('fa-eye').addClass('fa-eye-slash');
    }
}

function disableOnSubmit(element, form, action){
    var newButtonText = (action == 'verify') ? 'Verifying' : 'Loading';

    $(element).prop('disabled' ,true);
    $(element).html('<span class="spinner-grow spinner-grow-sm me-1" role="status" aria-hidden="true"></span>' + newButtonText);
    $(form).submit();
}

function getCurrentOTP(refCode){
    // console.log("🚀 ~ getCurrentOTP ~ refCode, otpType:", refCode, otpType)
    var currentDate = new Date();
    var otpDate;
    // var url = (otpType == 1) ? base_url + "client/login/get_current_otp/" + refCode : base_url + "client/signup/get_current_otp_oprs/" + refCode;
    
    
    $.ajax({
      type: "GET",
      url: base_url + "oprs/login/get_current_otp/" + refCode,
      dataType: "json",
      crossDomain: true,
      success: function(data) {
        // console.log("🚀 ~ getCurrentOTP ~ data:", data)
        try{
          otpDate = new Date(data[0]['otp_date']);
           
          var diff = currentDate.getTime() - otpDate.getTime();
          var diffHours = Math.floor(diff / (1000 * 60 * 60));
          var diffMinutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
          var diffSeconds = Math.floor((diff % (1000 * 60)) / 1000);   
    
          if(diffHours == 0){
            if(diffMinutes < 5 && diffSeconds <= 60){
              minutes = 4 - diffMinutes;
              seconds = 60 - diffSeconds;
              startTimer();
              $('#resend_code').addClass('disabled');
              $('#verify_code').removeClass('disabled');
            }else{
              clearInterval(intervalId);
              minutes = 5;
              seconds = 0;
              
              var url = window.location.pathname; // Get the current path
              var segments = url.split('/'); // Split the path by '/'
              var secondToLastSegment = segments[segments.length - 2];
      
              refCode = url.split('/').pop();
              if(secondToLastSegment == 'verify_otp'){ // login otp
                $('#resend_code').attr('href', base_url + 'oprs/login/resend_login_code/' + refCode);
              }
              
              $('#resend_code').removeClass('disabled');
              $('#verify_code').addClass('disabled');
            }
          }else{
            clearInterval(intervalId);
            minutes = 5;
            seconds = 0;
            $('#resend_code').removeClass('disabled');
            $('#verify_code').addClass('disabled');
          }
        }catch(err){
          // console.log('No Login Request, No code exist.');
          $('#resend_code').addClass('d-none');
        }
      }
    });
}

function startTimer() {
intervalId = setInterval(function() {
    if (seconds === 0) {
        minutes--;
        seconds = 59;
    } else {
        seconds--;
    }

    var minutesStr = minutes < 10 ? '0' + minutes : minutes;
    var secondsStr = seconds < 10 ? '0' + seconds : seconds;

    $('#resend_code').text('Resend Code (' + minutesStr + ':' + secondsStr + ')');
    if (minutes === 0 && seconds === 0) {
        clearInterval(intervalId);
        // Perform action when countdown is finished
        $('#resend_code').removeClass('disabled');
        $('#verify_code').addClass('disabled');
        $('#resend_code').text('Resend Code');

        var url = window.location.pathname; // Get the current path
        var segments = url.split('/'); // Split the path by '/'
        var secondToLastSegment = segments[segments.length - 2];

        refCode = url.split('/').pop();
        if(secondToLastSegment == 'verify_otp'){ // login otp
        $('#resend_code').attr('href', base_url + 'oprs/login/resend_login_code/' + refCode);
        }
    }
}, 1000);
}

function destroyUserSession(){

    $.ajax({
    type: "POST",
    url: base_url + "oprs/login/destroy_user_session" ,
    data: { user_access_token : accessToken },
    success: function(data) {
        // console.log(data);
    }
    });
}

function onRecaptchaSuccess(token) {
console.log("reCAPTCHA validated!");
$(current_button_id).prop('disabled', false); // Enable submit button
}

// Callback when reCAPTCHA expires
function onRecaptchaExpired() {
console.log("reCAPTCHA expired.");
$(current_button_id).prop('disabled', true);
}

function toggleSearch(){
$('#searchModal').modal('toggle');
setTimeout(function (){
    $('#searchModal #search').focus();
}, 100);
}

function edit_user_type(id){
$('#editUserTypeModal').modal('toggle');
$.ajax({
    type: "GET",
    url: base_url + "oprs/user/get_user_types/"+id,
    dataType: "json",
    crossDomain: true,
    success: function(data) {
        $.each(data, function(key, val) {
            $.each(val, function(k, v){
                $('#form_edit_user_type #'+k).val(v);
            });
        });
    }
});
}

function edit_status_type(id){
$('#editStatusTypeModal').modal('toggle');
$.ajax({
    type: "GET",
    url: base_url + "oprs/status/get_status_types/"+id,
    dataType: "json",
    crossDomain: true,
    success: function(data) {
        $.each(data, function(key, val) {
            $.each(val, function(k, v){
                $('#form_edit_status_type #'+k).val(v);
            });
        });
    }
});
}

function edit_publication_type(id){
$('#editPublicationTypeModal').modal('toggle');
$.ajax({
    type: "GET",
    url: base_url + "oprs/publication_types/get_publication_types/"+id,
    dataType: "json",
    crossDomain: true,
    success: function(data) {
        $.each(data, function(key, val) {
            $.each(val, function(k, v){
                $('#form_edit_publication_type #'+k).val(v);
            });
        });
    }
});
}


function edit_criteria(id, criteria){
var url = '', form = '';

if(criteria == 1){ // tech rev criteria
    $('#editTRCModal').modal('toggle');
    url = base_url + "oprs/criterion/get_criteria/"+id+"/"+criteria;
    form = '#form_edit_tech_rev_crit';
}else{ // peer rev criteria
    $('#editPRCModal').modal('toggle');
    url = base_url + "oprs/criterion/get_criteria/"+id+"/"+criteria;
    form = '#form_edit_peer_rev_crit';
}

$.ajax({
    type: "GET",
    url: url,
    dataType: "json",
    crossDomain: true,
    success: function(data) {

        $.each(data, function(key, val) {
            if(criteria == 1){ // tech rev criteria
                $(form + ' #crt_id').val(val.id);
                $(form + ' #crt_code').val(val.code);
                $(form + ' #crt_desc').val(val.desc);
            }else{ // peer rev criteria
                $(form + ' #pcrt_id').val(val.id);
                $(form + ' #pcrt_code').val(val.code);
                $(form + ' #pcrt_desc').val(val.desc);
                $(form + ' #pcrt_score').val(val.score);
            }
        });
    }
});
}

function filter_submission_summary(){
var from = $('#sub_sum #date_from').val();
var to = $('#sub_sum #date_to').val();

var data = {
    from: from,
    to: to
};

$.ajax({
    url: base_url + "oprs/statistics/filter_sub_sum",
    data: data,
    cache: false,
    crossDomain: true,
    dataType: 'json',
    type: "POST",
    success: function(data) {

        sst.clear();
            $.each(data, function(key, val){

                sst.row.add([
                    val.pub_id,
                    val.publication_desc,
                    val.subm_count,
                    val.subm_count,
                    val.subm_count,
                    val.subm_count,
                    val.subm_count,
                    val.subm_count,
                    val.subm_count,
                    val.subm_count,
                    val.subm_count,
                ]);
            });
        sst.draw();
    }
});
}

function filter_submission_statistics(){
var from = $('#sub_stat #date_from').val();
var to = $('#sub_stat #date_to').val();

var data = {
    from: from,
    to: to
};

$.ajax({
    url: base_url + "oprs/statistics/filter_sub_stat",
    data: data,
    cache: false,
    crossDomain: true,
    dataType: 'json',
    type: "POST",
    success: function(data) {

        sstt.clear();
            $.each(data, function(key, val){

                sstt.row.add([
                    val.pub_id,
                    val.publication_desc,
                    val.subm_count,
                    val.subm_count,
                    val.subm_count,
                    val.subm_count,
                    val.subm_count,
                    val.subm_count,
                    val.subm_count,
                    val.subm_count,
                    val.subm_count,
                    val.subm_count,
                    val.subm_count,
                    val.subm_count,
                    val.subm_count,
                ]);
            });
        sstt.draw();
    }
});
}

function filter_author_by_sex(){
var from = $('#auth_sex #date_from').val();
var to = $('#auth_sex #date_to').val();

var data = {
    from: from,
    to: to
};

$.ajax({
    url: base_url + "oprs/statistics/filter_auth_by_sex",
    data: data,
    cache: false,
    crossDomain: true,
    dataType: 'json',
    type: "POST",
    success: function(data) {
        console.log(data);
        abst.clear();
        var total_auth = 0;
        var total_coa = 0;
        var author_row_array = [];
        var coauthor_row_array = [];

        author_row_array.push('Primary Author');
            $.each(data['authors'], function(key, val){
                total_auth += parseInt(val.total);
                author_row_array.push(val.total);
            });
            author_row_array.push(total_auth);
        abst.row.add(author_row_array);
        
        coauthor_row_array.push('Co-Authors');
            $.each(data['coauthors'], function(key, val){
                total_coa += parseInt(val.total);
                coauthor_row_array.push(val.total);
            });
        coauthor_row_array.push(total_coa);
        abst.row.add(coauthor_row_array);

        abst.draw();
    }
});
}

function filter_uiux(){
var from = $('#uiux #date_from').val();
var to = $('#uiux #date_to').val();

var data = {
    from: from,
    to: to
};

uiux_table.clear();

$.ajax({
    url: base_url + "oprs/feedbacks/filter_uiux",
    data: data,
    cache: false,
    crossDomain: true,
    dataType: 'json',
    type: "POST",
    success: function(data) {
        var i = 1;
        $.each(data, function(key, val){

            var ui_star = '', ux_star = '';
            for(var x=0;x<val.csf_rate_ui;x++){
                ui_star += '<span class="text-warning fs-5 star-icon">★</span>';
            }
            for(var x=0;x<val.csf_rate_ux;x++){
                ux_star += '<span class="text-warning fs-5 star-icon">★</span>';
            }

            uiux_table.row.add([
                i++,
                val.email,
                ui_star,
                val.csf_ui_suggestions,
                ux_star,
                val.csf_ux_suggestions,
                val.csf_system,
                moment(val.csf_created_at).format('MMMM D, YYYY h:mm a')
            ]);
        });
        uiux_table.draw();
    }
});
}

function filter_uiux_sex(){
    
    var from = $('#uiux-sex #date_from').val();
    var to = $('#uiux-sex #date_to').val();
    
    var data = {
        from: from,
        to: to
    };

    uiux_sex_table.clear();

    $.ajax({
        url: base_url + "oprs/feedbacks/filter_uiux_sex",
        data: data,
        cache: false,
        crossDomain: true,
        dataType: 'json',
        type: "POST",
        success: function(data) {
            $.each(data, function(key, val){
                uiux_sex_table.row.add([
                    val.sex_label,
                    val.total_count
                ]);
            });

            uiux_sex_table.draw();
        }
    });
}

function filter_arta(){
    
    var from = $('#arta-tab #date_from').val();
    var to = $('#arta-tab #date_to').val();
    var region = $('#arta-tab #region').val();
    var ctype = $('#arta-tab #customer_type').val();
    var sex = $('#arta-tab #sex').val();
    
    var data = {
        from: from,
        to: to,
        region: region,
        ctype: ctype,
        sex: sex
    };

    arta_table.clear();

    $.ajax({
        url: base_url + "oprs/arta/filter_arta",
        data: data,
        cache: false,
        crossDomain: true,
        dataType: 'json',
        type: "POST",
        success: function(data) {
            var i = 1;
            $.each(data, function(key, val){
                arta_table.row.add([
                    i++,
                    val.name,
                    val.arta_age,
                    val.sex_name,
                    val.region_name,
                    val.arta_agency,
                    val.arta_service,
                    val.ctype_desc,
                    val.arta_cc1,
                    val.arta_cc2,
                    val.arta_cc3,
                    val.arta_sqd1,
                    val.arta_sqd2,
                    val.arta_sqd3,
                    val.arta_sqd4,
                    val.arta_sqd5,
                    val.arta_sqd6,
                    val.arta_sqd7,
                    val.arta_sqd8,
                    val.arta_suggestion,
                    moment(val.arta_created_at).format('MMMM D, YYYY h:mm a')
                ]);
            });

            arta_table.draw();
        }
    });
}

function filter_arta_age(){
    
    var from = $('#arta-age-tab #date_from').val();
    var to = $('#arta-age-tab #date_to').val();
    
    var data = {
        from: from,
        to: to
    };

    arta_age_table.clear();

    $.ajax({
        url: base_url + "oprs/arta/filter_arta_age",
        data: data,
        cache: false,
        crossDomain: true,
        dataType: 'json',
        type: "POST",
        success: function(data) {
            var i = 1;
            var total_male = 0, total_female = 0;
            $.each(data, function(key, val){
                total_male += parseInt(val.male);
                total_female += parseInt(val.female);
                arta_age_table.row.add([
                    i++,
                    (val.age_range == '70-100') ? 'Above 70' : ((val.age_range == '1-19') ? 'Below 19' : val.age_range),
                    val.male,
                    val.female
                ]);
            });
            
            arta_age_table.row.add([
                i++,
                'Total',
                total_male,
                total_female
            ]);

            arta_age_table.draw();
            arta_age_table.rows().nodes().to$().find('td:first-child').addClass('fw-bold');
            arta_age_table.rows().nodes().to$().find('td:first-child').addClass('bg-light');
        }
    });
}

function filter_arta_region(){

var from = $('#arta-reg-tab #date_from').val();
var to = $('#arta-reg-tab #date_to').val();

var data = {
    from: from,
    to: to
};

arta_reg_table.clear();

$.ajax({
    url: base_url + "oprs/arta/filter_arta_region",
    data: data,
    cache: false,
    crossDomain: true,
    dataType: 'json',
    type: "POST",
    success: function(data) {
        var i = 1;
        var total_male = 0, total_female = 0, total_per_region = 0, total_region = 0;
        $.each(data, function(key, val){
            total_male += parseInt(val.male);
            total_female += parseInt(val.female);
            total_per_region == parseInt(val.female) + parseInt(val.male);
            total_region += parseInt(total_per_region);
            arta_reg_table.row.add([
                i++,
                val.region_name,
                val.male,
                val.female,
                total_per_region
            ]);
        });
        
        arta_reg_table.row.add([
            i++,
            'Total',
            total_male,
            total_female,
            total_region
        ]);

        arta_reg_table.draw();
        arta_reg_table.rows().nodes().to$().find('td:first-child').addClass('fw-bold');
        arta_reg_table.rows().nodes().to$().find('td:first-child').addClass('bg-light');
    }
});
}

function filter_arta_cc(){
    
    var from = $('#arta-cc-tab #date_from').val();
    var to = $('#arta-cc-tab #date_to').val();
    
    var data = {
        from: from,
        to: to
    };

    arta_cc_table.clear();

    $.ajax({
        url: base_url + "oprs/arta/filter_arta_cc",
        data: data,
        cache: false,
        crossDomain: true,
        dataType: 'json',
        type: "POST",
        success: function(data) {
            $.each(data, function(key, val){
                arta_cc_table.row.add([
                    val.cc,
                    val.c1,
                    val.c2,
                    val.c3,
                    val.c4,
                    val.c5,
                ]);
            });
            
            arta_cc_table.draw();
            arta_cc_table.rows().nodes().to$().find('td:first-child').addClass('fw-bold');
            arta_cc_table.rows().nodes().to$().find('td:first-child').addClass('bg-light');
        }
    });
}

function filter_arta_sqd(){
    
    var from = $('#arta-sqd-tab #date_from').val();
    var to = $('#arta-sqd-tab #date_to').val();
    
    var data = {
        from: from,
        to: to
    };

    arta_sqd_table.clear();

    $.ajax({
        url: base_url + "oprs/arta/filter_arta_sqd",
        data: data,
        cache: false,
        crossDomain: true,
        dataType: 'json',
        type: "POST",
        success: function(data) {
            var sqd1 = 0, sqd2 = 0, sqd3 = 0, sqd4 = 0, sqd5 = 0, sqdna = 0;
            $.each(data, function(key, val){
                sqd1 += parseInt(sqd1);
                sqd2 += parseInt(sqd2);
                sqd3 += parseInt(sqd3);
                sqd4 += parseInt(sqd4);
                sqd5 += parseInt(sqd5);
                sqdna += parseInt(sqdna);
                arta_sqd_table.row.add([
                    val.sqd,
                    val.sqd1,
                    val.sqd2,
                    val.sqd3,
                    val.sqd4,
                    val.sqd5,
                    val.sqdna
                ]);
            });
            
            
            arta_sqd_table.row.add([
                'Total',
                sqd1,
                sqd2,
                sqd3,
                sqd4,
                sqd5,
                sqdna
            ]);

            arta_sqd_table.draw();
            arta_sqd_table.rows().nodes().to$().find('td:first-child').addClass('fw-bold');
            arta_sqd_table.rows().nodes().to$().find('td:first-child').addClass('bg-light');
        }
    });
}

function getPasswordStrength(password) {
// Implement your password strength logic here
// For example, you can check for length, uppercase, lowercase, numbers, and special characters
var strength = 0;
if (password.length >= 8) {
    strength+=10;
}
if (password.length >= 12) {
    strength+=15;
}
if (password.length >= 16) {
    strength+=20;
}
if (/[A-Z]/.test(password)) {
    strength+=15;
}
if (/[a-z]/.test(password)) {
    strength+=10;
}
if (/[0-9]/.test(password)) {
    strength+=15;
}
if   
(/[^A-Za-z0-9]/.test(password)) {
    strength+=15;
}
return strength;   

}

function tech_rev_criterion(id, status){
$('#tedEdCriteriaModal').modal('toggle');
$('#tr_man_id').val(id);
}

function eic_process(id) {

    var manuscript_title = decodeURIComponent(title);
    $('#eicProcessModal #man_title').text('').text(manuscript_title);

    $('#eic_table #tr_remarks').text('');

    $.ajax({
        type: "GET",
        url: base_url + "oprs/manuscripts/get_tech_rev_score/"+id,
        dataType: "json",
        crossDomain: true,
        success: function(data) {

            $.each(data, function(key, val){
                $.each(val, function(k, v){
                    var status_class = (v == 1) ? 'text-success' : 'text-danger';
                    var status_bg_class = (v == 1) ? 'bg-success' : 'bg-danger';
                    var status_text = (v == 1) ? 'Passed' : 'Failed';

                    if(k == 'tr_final'){
                        $('#eic_table #' +k).text(status_text);
                        $('#eic_table #' +k).addClass(status_bg_class);
                    }else{
                        $('#eic_table #' +k).text(status_text);
                        $('#eic_table #' +k).addClass(status_class);
                    }
                });

                $('#eic_table #tr_remarks').text(val.tr_remarks ?? 'No remarks.');

            });
        }
    });
}

function update_process_time_duration(element,id){

    var days = $(element).closest('tr').find('input').val();

    var data = {
        id: id,
        days: days
    };
    
    Swal.fire({
        title: "Apply changes?",
        // text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#007bff",
        cancelButtonColor: "#d33",
        confirmButtonText: "Submit"
    }).then((result) => {
        if (result.isConfirmed) {

            $.ajax({
                url: base_url + "oprs/emails/update_process_time_duration",
                data: data,
                cache: false,
                crossDomain: true,
                type: "POST",
                success: function(data) {
                    Swal.fire({
                    title: "Process time duration updated successfully!",
                    icon: 'success',
                    // html: "I will close in <b></b> milliseconds.",
                    timer: 2000,
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading();
                        const timer = Swal.getPopup().querySelector("b");
                        timerInterval = setInterval(() => {
                        timer.textContent = `${Swal.getTimerLeft()}`;
                        }, 100);
                    },
                    willClose: () => {
                        clearInterval(timerInterval);
                        location.reload();
                    }
                    }).then((result) => {
                        /* Read more about handling dismissals below */
                        if (result.dismiss === Swal.DismissReason.timer) {
                            console.log("I was closed by the timer");
                        }
                        location.reload();
                    });
                }
            });
        }
    });
}

function editor_action(action,editor_type){

    if(editor_type == 'editor_chief'){
        if(action == 'endorse'){

            $("#endorse_associate_form").validate({
                debug: true,
                errorClass: 'text-danger',
                rules: {
                    associate_editor: {
                        required: true,
                    },
                    man_remarks: {
                        required: true,
                    },
                
                },
                submitHandler: function() {
                
                    Swal.fire({
                        title: "Endorse review?",
                        // text: "You won't be able to revert this!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#007bff",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Submit"
                    }).then((result) => {
                        if (result.isConfirmed) {
        
                            $('body').loading('start');
                            $('#eicProcessModal').modal('toggle');
                            $('#endorse_associate_form').prop('disabled', true);

                            var status = 3;
                            var data = {
                                id: man_id,
                                status: status,
                                remarks: $('#endorse_associate_form #man_remarks').val(),
                                associate_editor: $('#endorse_associate_form #associate_editor').val()
                            };

                            $.ajax({
                                url: base_url + "oprs/manuscripts/eic_review_process",
                                data: data,
                                cache: false,
                                crossDomain: true,
                                type: "POST",
                                success: function(data) {
                                    
                                    $('body').loading('stop');
                                    $('#endorse_associate_form')[0].reset();

                                    Swal.fire({
                                    title: "Associate editor review request submitted successfully!",
                                    icon: 'success',
                                    // html: "I will close in <b></b> milliseconds.",
                                    timer: 2000,
                                    timerProgressBar: true,
                                    didOpen: () => {
                                        Swal.showLoading();
                                        const timer = Swal.getPopup().querySelector("b");
                                        timerInterval = setInterval(() => {
                                        timer.textContent = `${Swal.getTimerLeft()}`;
                                        }, 100);
                                    },
                                    willClose: () => {
                                        clearInterval(timerInterval);
                                        location.reload();
                                    }
                                    }).then((result) => {
                                        /* Read more about handling dismissals below */
                                        if (result.dismiss === Swal.DismissReason.timer) {
                                            console.log("I was closed by the timer");
                                        }
                                        location.reload();
                                    });
                                }
                            });
                        }
                    });
        
                }
            });
        
            $("#endorse_associate_form").submit();
        }else{
            $("#eic_review_form").validate({
                debug: true,
                errorClass: 'text-danger',
                rules: {
                    man_remarks: {
                        required: true,
                    },
                
                },
                submitHandler: function() {
                
                    Swal.fire({
                        title: "Submit review?",
                        // text: "You won't be able to revert this!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#007bff",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Submit"
                    }).then((result) => {
                        if (result.isConfirmed) {
        
                            $('body').loading('start');
                            $('#eicProcessModal').modal('toggle');
                            $('#eic_review_form').prop('disabled', true);
                            
                            // 14-rejected
                            var status = (action == 'accept') ? '15' : ((action == 'revise') ? '10' : '14');
                            var data = {
                                id: man_id,
                                status: status,
                                remarks: $('#eic_review_form #man_remarks').val()
                            };
                            
                            $.ajax({
                                url: base_url + "oprs/manuscripts/eic_review_process",
                                data: data,
                                cache: false,
                                crossDomain: true,
                                type: "POST",
                                success: function(data) {
                                    $('body').loading('stop');
                                    $('#eic_review_form')[0].reset();
                                    Swal.fire({
                                    title: "Review submitted successfully!",
                                    icon: 'success',
                                    // html: "I will close in <b></b> milliseconds.",
                                    timer: 2000,
                                    timerProgressBar: true,
                                    didOpen: () => {
                                        Swal.showLoading();
                                        const timer = Swal.getPopup().querySelector("b");
                                        timerInterval = setInterval(() => {
                                        timer.textContent = `${Swal.getTimerLeft()}`;
                                        }, 100);
                                    },
                                    willClose: () => {
                                        clearInterval(timerInterval);
                                        location.reload();
                                    }
                                    }).then((result) => {
                                        /* Read more about handling dismissals below */
                                        if (result.dismiss === Swal.DismissReason.timer) {
                                            console.log("I was closed by the timer");
                                        }
                                        location.reload();
                                    });
                                }
                            });
                        }
                    });
        
                }
            });
        
            $("#eic_review_form").submit();
        }
    }else if(editor_type == 'associate'){
        if(action == 'endorse'){

            // Add custom validation rule
            $.validator.addMethod("atLeastOneChecked", function (value, element) {
                return $('input[name="cluster_editor[]"]:checked').length > 0;
            }, "Please select at least one cluster editor.");
            
            $("#endorse_cluster_form").validate({
                debug: true,
                errorClass: 'text-danger',
                rules: {
                    "cluster_editor[]": {
                        atLeastOneChecked: true
                    },
                    man_remarks: {
                        required: true,
                    },
                
                },
                messages: {
                    "options[]": {
                        atLeastOneChecked: "Please select at least one cluster editor."
                    }
                },
                errorPlacement: function (error, element) {
                    // Place error message below the group of checkboxes
                    if (element.attr("name") === "cluster_editor[]") {
                        error.insertAfter("#cluster_editors");
                    } else {
                        error.insertAfter(element);
                    }
                },
                submitHandler: function() {
                
                    Swal.fire({
                        title: "Endorse review?",
                        // text: "You won't be able to revert this!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#007bff",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Submit"
                    }).then((result) => {
                        if (result.isConfirmed) {
        
                            $('body').loading('start');
                            $('#assocEdProcessModal').modal('toggle');
                            $('#endorse_cluster_form').prop('disabled', true);

                            var cluster_editor = $('input[name="cluster_editor[]"]:checked')
                            .map(function () {
                                return $(this).val();
                            })
                            .get(); // Convert to an array

                            var status = 4;
                            var data = {
                                id: man_id,
                                status: status,
                                remarks: $('#endorse_cluster_form #man_remarks').val(),
                                cluster_editor: cluster_editor
                            };

                            $.ajax({
                                url: base_url + "oprs/manuscripts/assoc_review_process",
                                data: data,
                                cache: false,
                                crossDomain: true,
                                type: "POST",
                                success: function(data) {
                                    $('body').loading('stop');
                                    $('#endorse_cluster_form')[0].reset();

                                    Swal.fire({
                                    title: "Cluster editor review request submitted successfully!",
                                    icon: 'success',
                                    // html: "I will close in <b></b> milliseconds.",
                                    timer: 2000,
                                    timerProgressBar: true,
                                    didOpen: () => {
                                        Swal.showLoading();
                                        const timer = Swal.getPopup().querySelector("b");
                                        timerInterval = setInterval(() => {
                                        timer.textContent = `${Swal.getTimerLeft()}`;
                                        }, 100);
                                    },
                                    willClose: () => {
                                        clearInterval(timerInterval);
                                        location.reload();
                                    }
                                    }).then((result) => {
                                        /* Read more about handling dismissals below */
                                        if (result.dismiss === Swal.DismissReason.timer) {
                                            console.log("I was closed by the timer");
                                        }
                                        location.reload();
                                    });
                                }
                            });
                        }
                    });
        
                }
            });
        
            $("#endorse_cluster_form").submit();
        }else{
            $("#assoc_review_form").validate({
                debug: true,
                errorClass: 'text-danger',
                rules: {
                    man_remarks: {
                        required: true,
                    },
                
                },
                submitHandler: function() {
                
                    Swal.fire({
                        title: "Submit review?",
                        // text: "You won't be able to revert this!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#007bff",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Submit"
                    }).then((result) => {
                        if (result.isConfirmed) {
        
                            $('body').loading('start');
                            $('#assocEdProcessModal').modal('toggle');
                            $('#assoc_review_form').prop('disabled', true);
                            
                            // 14-rejected
                            var status = (action == 'accept') ? '15' : ((action == 'revise') ? '10' : '14');
                            var data = {
                                id: man_id,
                                status: status,
                                remarks: $('#assoc_review_form #man_remarks').val()
                            };
                            
                            $.ajax({
                                url: base_url + "oprs/manuscripts/assoc_review_process",
                                data: data,
                                cache: false,
                                crossDomain: true,
                                type: "POST",
                                success: function(data) {
                                    $('body').loading('stop');
                                    $('#assoc_review_form')[0].reset();
                                    Swal.fire({
                                    title: "Review submitted successfully!",
                                    icon: 'success',
                                    // html: "I will close in <b></b> milliseconds.",
                                    timer: 2000,
                                    timerProgressBar: true,
                                    didOpen: () => {
                                        Swal.showLoading();
                                        const timer = Swal.getPopup().querySelector("b");
                                        timerInterval = setInterval(() => {
                                        timer.textContent = `${Swal.getTimerLeft()}`;
                                        }, 100);
                                    },
                                    willClose: () => {
                                        clearInterval(timerInterval);
                                        location.reload();
                                    }
                                    }).then((result) => {
                                        /* Read more about handling dismissals below */
                                        if (result.dismiss === Swal.DismissReason.timer) {
                                            console.log("I was closed by the timer");
                                        }
                                        location.reload();
                                    });
                                }
                            });
                        }
                    });
        
                }
            });
        
            $("#assoc_review_form").submit();
        }
    }else{ // cluster editor
        if(action == 'endorse'){

            // Add custom validation rule
            $.validator.addMethod("atLeastOneChecked", function (value, element) {
                return $('input[name="cluster_editor[]"]:checked').length > 0;
            }, "Please select at least one cluster editor.");
            
            $("#endorse_cluster_form").validate({
                debug: true,
                errorClass: 'text-danger',
                rules: {
                    "cluster_editor[]": {
                        atLeastOneChecked: true
                    },
                    man_remarks: {
                        required: true,
                    },
                
                },
                messages: {
                    "options[]": {
                        atLeastOneChecked: "Please select at least one cluster editor."
                    }
                },
                errorPlacement: function (error, element) {
                    // Place error message below the group of checkboxes
                    if (element.attr("name") === "cluster_editor[]") {
                        error.insertAfter("#cluster_editors");
                    } else {
                        error.insertAfter(element);
                    }
                },
                submitHandler: function() {
                
                    Swal.fire({
                        title: "Endorse review?",
                        // text: "You won't be able to revert this!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#007bff",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Submit"
                    }).then((result) => {
                        if (result.isConfirmed) {
        
                            $('body').loading('start');
                            $('#assocEdProcessModal').modal('toggle');
                            $('#endorse_cluster_form').prop('disabled', true);

                            var cluster_editor = $('input[name="cluster_editor[]"]:checked')
                            .map(function () {
                                return $(this).val();
                            })
                            .get(); // Convert to an array

                            var status = 4;
                            var data = {
                                id: man_id,
                                status: status,
                                remarks: $('#endorse_cluster_form #man_remarks').val(),
                                cluster_editor: cluster_editor
                            };

                            $.ajax({
                                url: base_url + "oprs/manuscripts/assoc_review_process",
                                data: data,
                                cache: false,
                                crossDomain: true,
                                type: "POST",
                                success: function(data) {
                                    $('body').loading('stop');
                                    $('#endorse_cluster_form')[0].reset();

                                    Swal.fire({
                                    title: "Cluster editor review request submitted successfully!",
                                    icon: 'success',
                                    // html: "I will close in <b></b> milliseconds.",
                                    timer: 2000,
                                    timerProgressBar: true,
                                    didOpen: () => {
                                        Swal.showLoading();
                                        const timer = Swal.getPopup().querySelector("b");
                                        timerInterval = setInterval(() => {
                                        timer.textContent = `${Swal.getTimerLeft()}`;
                                        }, 100);
                                    },
                                    willClose: () => {
                                        clearInterval(timerInterval);
                                        location.reload();
                                    }
                                    }).then((result) => {
                                        /* Read more about handling dismissals below */
                                        if (result.dismiss === Swal.DismissReason.timer) {
                                            console.log("I was closed by the timer");
                                        }
                                        location.reload();
                                    });
                                }
                            });
                        }
                    });
        
                }
            });
        
            $("#endorse_cluster_form").submit();
        }else{
            $("#cluster_review_form").validate({
                debug: true,
                errorClass: 'text-danger',
                rules: {
                    man_remarks: {
                        required: true,
                    },
                
                },
                submitHandler: function() {
                
                    Swal.fire({
                        title: "Submit review?",
                        // text: "You won't be able to revert this!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#007bff",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Submit"
                    }).then((result) => {
                        if (result.isConfirmed) {
        
                            $('body').loading('start');
                            $('#cluEdProcessModal').modal('toggle');
                            $('#cluster_review_form').prop('disabled', true);
                            
                            // 14-rejected
                            var status = (action == 'accept') ? '15' : ((action == 'revise') ? '10' : '14');
                            var data = {
                                id: man_id,
                                status: status,
                                remarks: $('#cluster_review_form #man_remarks').val()
                            };
                            
                            $.ajax({
                                url: base_url + "oprs/manuscripts/cluster_review_process",
                                data: data,
                                cache: false,
                                crossDomain: true,
                                type: "POST",
                                success: function(data) {
                                    $('body').loading('stop');
                                    $('#cluster_review_form')[0].reset();
                                    Swal.fire({
                                    title: "Review submitted successfully!",
                                    icon: 'success',
                                    // html: "I will close in <b></b> milliseconds.",
                                    timer: 2000,
                                    timerProgressBar: true,
                                    didOpen: () => {
                                        Swal.showLoading();
                                        const timer = Swal.getPopup().querySelector("b");
                                        timerInterval = setInterval(() => {
                                        timer.textContent = `${Swal.getTimerLeft()}`;
                                        }, 100);
                                    },
                                    willClose: () => {
                                        clearInterval(timerInterval);
                                        location.reload();
                                    }
                                    }).then((result) => {
                                        /* Read more about handling dismissals below */
                                        if (result.dismiss === Swal.DismissReason.timer) {
                                            console.log("I was closed by the timer");
                                        }
                                        location.reload();
                                    });
                                }
                            });
                        }
                    });
        
                }
            });
        
            $("#cluster_review_form").submit();
        }
    }

}


function assoced_process(id, title) {

    man_id = id;
    var manuscript_title = decodeURIComponent(title);
    $('#assocEdProcessModal #man_title').text('').text(manuscript_title);
    // get technical desk editor review
    $.ajax({
        type: "GET",
        url: base_url + "oprs/manuscripts/get_tech_rev_score/"+id,
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            $.each(data, function(key, val){
                $.each(val, function(k, v){
                    var status_class = (v == 1) ? 'text-success' : 'text-danger';
                    var status_bg_class = (v == 1) ? 'bg-success' : 'bg-danger';
                    var status_text = (v == 1) ? 'Passed' : 'Failed';

                    if(k == 'tr_final'){
                        $('#assoc_table #' +k).text(status_text);
                        $('#assoc_table #' +k).addClass(status_bg_class);
                    }else if(k == 'tr_remarks'){
                        $('#assoc_table #' +k).text(v);
                    }else{
                        $('#assoc_table #' +k).text(status_text);
                        $('#assoc_table #' +k).addClass(status_class);
                    }
                });


            });
        }
    });

    $('#eic_remarks').text('');
    // get editor in chief remarks
    $.ajax({
        type: "GET",
        url: base_url + "oprs/manuscripts/get_editors_review/"+id,
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            $.each(data, function(key, val){
                $('#eic_remarks').append(val.edit_remarks ?? 'No remarks.');
            });
        }
    });
}

function clued_process(id, title) {

    man_id = id;
    var manuscript_title = decodeURIComponent(title);
    $('#cluEdProcessModal #man_title').text('').text(manuscript_title);
    // get technical desk editor review
    $.ajax({
        type: "GET",
        url: base_url + "oprs/manuscripts/get_tech_rev_score/"+id,
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            $.each(data, function(key, val){
                $.each(val, function(k, v){
                    var status_class = (v == 1) ? 'text-success' : 'text-danger';
                    var status_bg_class = (v == 1) ? 'bg-success' : 'bg-danger';
                    var status_text = (v == 1) ? 'Passed' : 'Failed';

                    if(k == 'tr_final'){
                        $('#assoc_table #' +k).text(status_text);
                        $('#assoc_table #' +k).addClass(status_bg_class);
                    }else if(k == 'tr_remarks'){
                        $('#assoc_table #' +k).text(v);
                    }else{
                        $('#assoc_table #' +k).text(status_text);
                        $('#assoc_table #' +k).addClass(status_class);
                    }
                });


            });
        }
    });

    $('#assoc_remarks').text('');
    // get editor in chief remarks
    $.ajax({
        type: "GET",
        url: base_url + "oprs/manuscripts/get_editors_review/"+id,
        dataType: "json",
        crossDomain: true,
        success: function(data) {
            $.each(data, function(key, val){
                $('#assoc_remarks').append(val.edit_remarks ?? 'No remarks.');
            });
        }
    });
}

function suggest_peer(){

    suggIncr++;

    var html = '';

    html = '<div class="row mb-3">'+
                '<div class="col autocomplete">'+
                    '<input type="text" class="form-control " id="suggested_peer_rev'+suggIncr+'" name="suggested_peer_rev[]" placeholder="Search by Name or Specialization">'+
                '</div>'+
                '<div class="col"><input type="text" class="form-control " id="suggested_peer_rev_spec'+suggIncr+'" name="suggested_peer_rev_spec[]"></div>'+
                '<input type="hidden" id="suggested_peer_rev_id'+suggIncr+'" name="suggested_peer_rev_id[]">'+
            '</div>';

    $('#suggested_peers').append(html);
    autocomplete(document.getElementById("suggested_peer_rev"+suggIncr), mem_exp, '#suggested_peer_rev_email'+suggIncr, '#suggested_peer_rev_num'+suggIncr, '#suggested_peer_rev_id'+suggIncr,  suggIncr , '#suggested_peer_rev_spec'+suggIncr, '#suggested_peer_rev_title'+suggIncr);

}