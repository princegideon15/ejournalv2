/**
 * File Name: journal.js
 * ----------------------------------------------------------------------------------------------------
 * Purpose of this file: 
 * managae all functions for admin users
 * ----------------------------------------------------------------------------------------------------
 * System Name: NRCP Research Journal
 * ----------------------------------------------------------------------------------------------------
 * Author: Gerard Paul D. Balde
 * ----------------------------------------------------------------------------------------------------
 * Date of revision: Sep 24, 2019 
 * ----------------------------------------------------------------------------------------------------
 * Copyright Notice:
 * Copyright (C) 2019 By the Department of Science and Technology - National Research Council of the Philippines
 */

var rcvr; // message receiver's user id
var author_and_coauthor_list; //array of author and co-authors
var x_coa; // co-author's id

var mems = [];
var mem_mail = [];
var mem_num = [];
var mem_spec = [];
var mem_id = [];
var mem_exp = [];
var mem_aff = [];
var mem_prf = [];
var array_prf = [];
var ejChart;

var global_coauthor_total = 0;

var minutes = 5,          // otp timer
seconds = 0,          // otp timer
intervalId,           // otp timer
isStartTimer = false, // otp timer
refCode,              // reference code for otp
accessToken,          // user access token generated on logged in   
current_button_id;    // button to enable/disable for catpcha  

var recaptchaWidgetId_logout; // recaptcha widget 

$(document).ready(function () {
	
    // get user account info
    $.ajax({
        type: "GET",
        url: base_url + "oprs/user/get_account_info",
        async:false,
        crossDomain: true,
        dataType: 'json',
        success: function(data) {
            $.each(data, function(key, val){
                $('#form_update_account #usr_username').val(val.usr_username);
                $('#form_update_account #usr_full_name').val(val.usr_full_name);
                $('#form_update_account #usr_sex').val(val.usr_sex);
                $('#form_update_account #usr_contact').val(val.usr_contact);
            });
        },
        error: function(xhr, status, error) {
        reject(error);
        }
    }); 

	
	// validate file size before uploading
    $.validator.addMethod('filesize', function (value, element, param) {
        return this.optional(element) || (element.files[0].size <= param)
    }, `File size exceeds the allowed limit.`);


	 // get user access token
	 accessToken = $.ajax({
        type: "GET",
        url: base_url + "oprs/login/get_access_token/",
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
	
	
    let idleTime = 0;

    if(accessToken != 0){
      $(document).on('mousemove keydown scroll', function() {
          idleTime = 0;
      });
  
      let timerInterval = setInterval(function() {
          idleTime += 1;
          
          if (idleTime >= 1200) { // 20 minutes in seconds
              // Trigger logout or other actions
              clearInterval(timerInterval); // Stop the timer
  
              destroyUserSession();
  
              Swal.fire({
                title: "Session Expired",
                text: "You have been idle for 20 minutes. Please log in again.",
                icon: "info",
                confirmButtonColor: "#0c6bcb",
              
              }).then(function () {
                window.location = base_url + "oprs/login";
              });
          }
      }, 1000); // Check every 1 second
    }
	// feedback suggestion box character limit
	let $textArea = $("#fb_suggest_ui");
	let $charCount = $("#char_count_ui");
	let maxLength = $textArea.attr("maxlength");

	$textArea.on("input", function () {
		let currentLength = $(this).val().length;
		$charCount.text(`${currentLength} / ${maxLength} characters`);

		if (currentLength > maxLength) {
			$charCount.addClass("exceeded");
		} else {
			$charCount.removeClass("exceeded");
		}
	});

	let $textArea2 = $("#fb_suggest_ux");
	let $charCount2 = $("#char_count_ux");
	let maxLength2 = $textArea2.attr("maxlength");

	$textArea2.on("input", function () {
		let currentLength = $(this).val().length;
		$charCount2.text(`${currentLength} / ${maxLength2} characters`);

		if (currentLength > maxLength2) {
			$charCount2.addClass("exceeded");
		} else {
			$charCount2.removeClass("exceeded");
		}
	});

	// csf ui ux star rating
	let selectedRatingUI = 0;
	let selectedRatingUX = 0;

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
	// 	debug: true,
	// 	errorClass: 'text-danger',
	// 	rules: {
	// 		non_title: {
	// 			required: true,
	// 			minlength: 2
	// 		},
		
	// 	},
	// 	messages: {
	// 		usr_captcha: {
	// 			equalTo: "Incorrect verification code"
	// 		},
	// 		non_email: {
	// 			remote: "Email already in use"
	// 		}
	// 	},
	// 	submitHandler: function() {
	// 		$.ajax({
	// 			type: "POST",
	// 			url: base_url + "oprs/signup/sign_up/",
	// 			data: $('#form_sign_up').serializeArray(),
	// 			cache: false,
	// 			crossDomain: true,
	// 			success: function(data) {
	// 				$.notify({
	// 					icon: 'fa fa-check-circle',
	// 					message: 'Thank you for signing up. You can now log in.'
	// 				}, {
	// 					type: 'success',
	// 					timer: 3000,
	// 				});

	// 				$('#form_sign_up')[0].reset();
	// 				$('#refresh_captcha').click();
	// 			}
	// 		});
	// 	}
	// });

	$('#submit_feedback').on('click', function(){
		if ($(".rate-ui.selected").length > 0 && $(".rate-ux.selected").length > 0) {

			let uiSuggestion = $('#fb_suggest_ui').val();
			let uxSuggestion = $('#fb_suggest_ux').val();
			
			let data = {
			'ui' : selectedRatingUI,
			'ux' : selectedRatingUX,
			'ui_sug' : uiSuggestion,
			'ux_sug' : uxSuggestion,
			'csf_system' : 'eJournal Admin'
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
					let timerInterval;
					Swal.fire({
						title: "Thank you for your feedback.",
						text: "Logging out...",
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
						window.location.href = base_url + "oprs/login/logout/";
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

                
                let data = {
                    search: search_value,
                    filter: search_filter
                };
                
                $.ajax({
                    url: base_url + "admin/journal/search",
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
								html += `<a href="${base_url}/client/ejournal/article/${val.art_id}" target="_blank" class="list-group-item list-group-item-action p-3 pe-5" aria-current="true">
										<h6 class="mb-1 fw-bold text-truncate">${val.art_title}</h6>
										<p class="mb-1 text-truncate">${val.art_author}${coas}</p>
										<p class="small text-truncate">${val.art_keywords}</p>
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

	tinymce.init({
		selector: '#enc_content',
		forced_root_block: false,
		height: "400",
		toolbar: 'undo redo | bold italic | alignleft aligncenter alignright alignjustify | link', // Add alignment buttons
		menubar: false // Optional: Hide the menubar if not needed
	});

	tinymce.init({
		selector: '#home_description',
		forced_root_block: false,
		height: "400",
		toolbar: 'undo redo | bold italic | alignleft aligncenter alignright alignjustify | link', // Add alignment buttons
		menubar: false // Optional: Hide the menubar if not needed
	});

	tinymce.init({
		selector: '#ep_content',
		forced_root_block: false,
		height: "900",
		toolbar: 'undo redo | bold italic | alignleft aligncenter alignright alignjustify | link', // Add alignment buttons
		menubar: false // Optional: Hide the menubar if not needed,
	});

	tinymce.init({
		selector: '#gd_content',
		forced_root_block: false,
		height: "900",
		plugins: 'link',
		toolbar: 'undo redo | bold italic | alignleft aligncenter alignright alignjustify | link', // Add alignment buttons
		menubar: false // Optional: Hide the menubar if not needed
	});

	$('#table-registry').DataTable();

	var _edt; // editorial board's id
	var _year; // journal year
	var _jor; // journal id
	var _ref; // user account status
	//get members info
	$.ajax({
		type: "GET",
		url: base_url + "oprs/manuscripts/members/",
		dataType: "json",
		crossDomain: true,
		success: function (data) {
			$.each(data, function (key, val) {

				mems.push(val.pp_first_name + ' ' + val.pp_middle_name + ' ' + val.pp_last_name);
				mem_mail.push(val.pp_email);
				mem_num.push(val.pp_contact);
				mem_spec.push(val.mpr_specialization);
				mem_id.push(val.pp_usr_id);
				mem_exp.push(val.pp_first_name + ' ' + val.pp_middle_name + ' ' + val.pp_last_name + ' (' + val.mpr_specialization + ')');
				mem_aff.push(val.bus_name);
				mem_prf.push(array_prf[val.pp_title]);
			});

		}
	});

	if ($('#art_author_p').length)
		autocomplete_acoa(document.getElementById("art_author_p"), mem_exp, '#art_affiliation_p', '#art_email_p');

	// get new messages from another user
	// newMessages();

	// count new messages
	// countNewMessage();

	// get list of authors and co-authors
	// acoaList();

	// enable use of tooltip
	$('body').tooltip({
		selector: '[data-bs-toggle=tooltip]'
	});

	// hide upload file element for user display picture
	$('#set_d_p').hide();

	// trigger click of upload file for user display picture
	$('#browse_dp').click(function () {
		$('#set_d_p').trigger('click');
	});

	// set default value in article year dropdown
	$("#art_year").val($("#art_year option:eq(1)").val());
	// $("#edt_art_year").val($("#edt_art_year option:eq(1)").val());

	// set editableSelect plugin effects to slide
	// $('#art_author ,#article_modal#art_author').editableSelect({ effects: 'slide' });

	// set editableSelect plugin effects to slide
	$('#jor_volume').editableSelect({
		effects: 'slide'
	});

	// set editableSelect plugin effects to slide
	// $('#edt_country ,#editorial_modal#edt_country').editableSelect({ effects: 'slide' });

	// check and display export button if privilege to export is enabled 
	if (prv_exp == 0) {
		var tct = $('#table-citees').DataTable({
			"order": [
				[1, "asc"]
			],
			retrieve: true,
			"columnDefs": [{
				"targets": 0,
				"orderable": false
			}]
		});
	} else {
		var tct = $('#table-citees').DataTable({
			"order": [
				[1, "asc"]
			],
			retrieve: true,
			columnDefs: [
				{
				"targets": 0,
				"orderable": false
				},
				// { width: "500px", targets: 1 }, // Set the width of the first column
				
			],
			autowidth: true,
			dom: "<'row'<'col-sm-12'B>>" +    // Buttons in their own row at the top
				 "<'row'<'col-sm-6'l><'col-sm-6'f>>" +  // Length menu and Search
				 "<'row'<'col-sm-12'tr>>" +   // Table itself
				 "<'row'<'col-sm-5'i><'col-sm-7'p>>",   // Info and Pagination
			buttons: [{
					extend: 'copy',
					text: 'Copy to clipboard',
					messageTop: 'List of Citees',
					title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRCP Research Journal',
					action: function (e, dt, node, config) {
						// action saved to logs table
						log_export('copied Citees to clipboard');
						$.fn.dataTable.ext.buttons.copyHtml5.action.call(this, e, dt, node, config);
					}
				},
				{
					extend: 'excel',
					text: 'Export as Excel',
					messageTop: 'List of Citees',
					title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRCP Research Journal',
					action: function (e, dt, node, config) {
						// action saved to logs table
						log_export('exported Citees as excel');
						$.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, node, config);
					}
				},
				{
					extend: 'pdf',
					text: 'Export as PDF',
					messageTop: 'List of Citees',
					title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES' + '\n' + 'NRCP Research Journal',
					action: function (e, dt, node, config) {
						// action saved to logs table
						log_export('exported Citees as pdf');
						$.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, node, config);
					}
				},
				{
					extend: 'print',
					messageTop: 'List of Citees',
					title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRCP Research Journal',
					action: function (e, dt, node, config) {
						// action saved to logs table
						log_export('printed Citees');
						window.print();
					}
				}
			]
		});
	}

	// initiate and disable sorting on clicking first column first row
	tct.on('draw', function () {
		tct.rows({
			search: 'applied',
			order: 'applied',
			filter: 'applied'
		}).data().each(function (d, i) {
			d[0] = i + 1;
		});
	});

	tct.on('order.dt search.dt', function () {
		tct.column(0, {
			search: 'applied',
			order: 'applied'
		}).nodes().each(function (cell, i) {
			cell.innerHTML = i + 1;
		});
	}).draw(false);

	// check and display export button if privilege to export is enabled 
	// if(prv_exp == 0){
	//       $('#table-registry').DataTable();
	// }
	// else{
	//   $('#table-registry').DataTable({
	//         dom: 'lBfrtip',
	//         buttons: [
	//                {
	//                 extend: 'copy',
	//                 text: 'Copy to clipboard',
	//                 messageTop: 'List of Author Registry',
	//                 title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRCP Research Journal', 
	//                 action: function(e, dt, node, config)
	//                 {
	//                   // action saved to logs table
	//                   log_export('copied authors registry to clipboard');
	//                   $.fn.dataTable.ext.buttons.copyHtml5.action.call(this, e, dt, node, config);
	//                 }
	//             },
	//             {
	//                 extend: 'excel',
	//                 text: 'Export as Excel',
	//                 messageTop: 'List of Author Registry',
	//                 title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRCP Research Journal', 
	//                 action: function(e, dt, node, config)
	//                 {
	//                   // action saved to logs table
	//                   log_export('exported authors registry as excel');
	//                   $.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, node, config);
	//                 }
	//             },
	//             {
	//                 extend: 'pdf',
	//                 text: 'Export as PDF',
	//                 messageTop: 'List of Author Registry',
	//                 title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES' + '\n' + 'NRCP Research Journal', 
	//                 action: function(e, dt, node, config)
	//                 {
	//                   // action saved to logs table
	//                   log_export('exported authors registry as pdf');
	//                   $.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, node, config);
	//                 }
	//             },
	//             {
	//                 extend: 'print',
	//                 messageTop: 'List of Author Registry',
	//                 title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRCP Research Journal', 
	//                 action: function(e, dt, node, config)
	//                 {
	//                   // action saved to logs table
	//                   log_export('printed authors registry');
	//                   window.print();
	//                 }
	//             }
	//         ]
	//     });
	// }

	// check and display export button if privilege to export is enabled
	if (prv_exp == 0) {
		var tallart = $('#table-all-articles').DataTable({
			"order": [
				[1, "asc"]
			],
			retrieve: true,
			columnDefs: [
				{
				"targets": 0,
				"orderable": false
				},
				{ width: "500px", targets: 1 }, // Set the width of the first column
				
			],
			autowidth: false,
		});

	} else {
		var tallart = $('#table-all-articles').DataTable({
			"order": [
				[1, "asc"]
			],
			retrieve: true,
			columnDefs: [
				{
				"targets": 0,
				"orderable": false
				},
				{ width: "500px", targets: 1 }, // Set the width of the first column
				
			],
			autowidth: true,
			dom: "<'row'<'col-sm-12'B>>" +    // Buttons in their own row at the top
				 "<'row'<'col-sm-6'l><'col-sm-6'f>>" +  // Length menu and Search
				 "<'row'<'col-sm-12'tr>>" +   // Table itself
				 "<'row'<'col-sm-5'i><'col-sm-7'p>>",   // Info and Pagination
			buttons: [{
					extend: 'copy',
					text: 'Copy to clipboard',
					messageTop: 'List of Articles',
					title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRCP Research Journal',
					action: function (e, dt, node, config) {
						// action saved to logs table
						log_export('copied list of Articles to clipboard');
						$.fn.dataTable.ext.buttons.copyHtml5.action.call(this, e, dt, node, config);
					}
				},
				{
					extend: 'excel',
					text: 'Export as Excel',
					messageTop: 'List of Articles',
					title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRCP Research Journal',
					action: function (e, dt, node, config) {
						// action saved to logs table
						log_export('exported list of Articles as excel');
						$.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, node, config);
					}
				},
				{
					extend: 'pdf',
					text: 'Export as PDF',
					messageTop: 'List of Articles',
					title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES' + '\n' + 'NRCP Research Journal',
					action: function (e, dt, node, config) {
						// action saved to logs table
						log_export('exported list of Articles as pdf');
						$.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, node, config);
					}
				},
				{
					extend: 'print',
					messageTop: 'List of Articles',
					title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRCP Research Journal',
					action: function (e, dt, node, config) {
						// action saved to logs table
						log_export('printed list of Articles');
						window.print();
					}
				}
			]
		});
	}

	// initiate and disable sorting on clicking first column first row
	tallart.on('draw', function () {
		tallart.rows({
			search: 'applied',
			order: 'applied',
			filter: 'applied'
		}).data().each(function (d, i) {
			d[0] = i + 1;
		});
	});

	tallart.on('order.dt search.dt', function () {
		tallart.column(0, {
			search: 'applied',
			order: 'applied'
		}).nodes().each(function (cell, i) {
			cell.innerHTML = i + 1;
		});
	}).draw(false);

	// check and display export button if privilege to export is enabled
	if (prv_exp == 0) {
		$('#table-journals').DataTable({
			"order": [
				[1, "desc"]
			],
			retrieve: true,
			"columnDefs": [{
				"targets": 0,
				"orderable": false
			}]
		});
	} else {
		$('#table-journals').DataTable({
			"order": [
				[1, "desc"]
			],
			retrieve: true,
			"columnDefs": [{
				"targets": 0,
				"orderable": false
			}],
			dom: "<'row'<'col-sm-12'B>>" +    // Buttons in their own row at the top
				 "<'row'<'col-sm-6'l><'col-sm-6'f>>" +  // Length menu and Search
				 "<'row'<'col-sm-12'tr>>" +   // Table itself
				 "<'row'<'col-sm-5'i><'col-sm-7'p>>",   // Info and Pagination
			buttons: [{
					extend: 'copy',
					text: 'Copy to clipboard',
					messageTop: 'List of Journals',
					title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRCP Research Journal',
					exportOptions: {
						columns: ':not(:last-child)',
					},
					action: function (e, dt, node, config) {
						// action saved to logs table
						log_export('copied list of journals to clipboard');
						$.fn.dataTable.ext.buttons.copyHtml5.action.call(this, e, dt, node, config);
					}
				},
				{
					extend: 'excel',
					text: 'Export as Excel',
					messageTop: 'List of Journals',
					title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRCP Research Journal',
					exportOptions: {
						columns: ':not(:last-child)',
					},
					action: function (e, dt, node, config) {
						// action saved to logs table
						log_export('exported list of journals as excel');
						$.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, node, config);
					}
				},
				{
					extend: 'pdf',
					text: 'Export as PDF',
					messageTop: 'List of Journals',
					title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES' + '\n' + 'NRCP Research Journal',
					exportOptions: {
						columns: ':not(:last-child)',
					},
					action: function (e, dt, node, config) {
						// action saved to logs table
						log_export('exported list of journals as pdf');
						$.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, node, config);
					}
				},
				{
					extend: 'print',
					messageTop: 'List of Journals',
					title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRCP Research Journal',
					exportOptions: {
						columns: ':not(:last-child)',
					},
					action: function (e, dt, node, config) {
						// action saved to logs table
						log_export('printed list of journals');
						window.print();
					}
				}
			]
		});
	}

	// initiate and disable sorting on clicking first column first row
	var tjor = $('#table-journals').DataTable();

	tjor.on('draw', function () {
		tjor.rows({
			search: 'applied',
			order: 'applied',
			filter: 'applied'
		}).data().each(function (d, i) {
			d[0] = i + 1;
		});
	});

	tjor.on('order.dt search.dt', function () {
		tjor.column(0, {
			search: 'applied',
			order: 'applied'
		}).nodes().each(function (cell, i) {
			cell.innerHTML = i + 1;
		});
	}).draw(false);

	// check and display export button if privilege to export is enabled
	if (prv_exp == 0) {
		var tpop = $('#table-popular').DataTable({
			"order": [
				[3, "desc"]
			],
			retrieve: true,
			"columnDefs": [{
				"targets": 0,
				"orderable": false
			}]
		});
	} else {
		var tpop = $('#table-popular').DataTable({
			"order": [
				[3, "desc"]
			],
			retreive: true,
			"columnDefs": [{
				"targets": 0,
				"orderable": false
			}],
			dom: "<'row'<'col-sm-12'B>>" +    // Buttons in their own row at the top
				 "<'row'<'col-sm-6'l><'col-sm-6'f>>" +  // Length menu and Search
				 "<'row'<'col-sm-12'tr>>" +   // Table itself
				 "<'row'<'col-sm-5'i><'col-sm-7'p>>",   // Info and Pagination
			buttons: [{
					extend: 'copy',
					text: 'Copy to clipboard',
					messageTop: 'List Popular Articles',
					title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRCP Research Journal',
					action: function (e, dt, node, config) {
						// action saved to logs table
						log_export('copied popular articles to clipboard');
						$.fn.dataTable.ext.buttons.copyHtml5.action.call(this, e, dt, node, config);
					}
				},
				{
					extend: 'excel',
					text: 'Export as Excel',
					messageTop: 'List Popular Articles',
					title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRCP Research Journal',
					action: function (e, dt, node, config) {
						// action saved to logs table
						log_export('exported popular articles as excel');
						$.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, node, config);
					}
				},
				{
					extend: 'pdf',
					text: 'Export as PDF',
					messageTop: 'List Popular Articles',
					title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES' + '\n' + 'NRCP Research Journal',
					action: function (e, dt, node, config) {
						// action saved to logs table
						log_export('exported popular articles as pdf');
						$.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, node, config);
					}
				},
				{
					extend: 'print',
					messageTop: 'List Popular Articles',
					title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRCP Research Journal',
					action: function (e, dt, node, config) {
						// action saved to logs table
						log_export('printed popular articles');
						window.print();
					}
				}
			]
		});
	}

	tpop.on('draw', function () {
		tpop.rows({
			search: 'applied',
			order: 'applied',
			filter: 'applied'
		}).data().each(function (d, i) {
			d[0] = i + 1;
		});
	});

	tpop.on('order.dt search.dt', function () {
		tpop.column(0, {
			search: 'applied',
			order: 'applied'
		}).nodes().each(function (cell, i) {
			cell.innerHTML = i + 1;
		});
	}).draw(false);

	// check and display export button if privilege to export is enabled
	if (prv_exp == 0) {
		$('#table-clients').DataTable({
			"order": [
				[3, "desc"]
			],
			retrieve: true,
			"columnDefs": [{
				"targets": 0,
				"orderable": false
			}]
		});
	} else {
		$('#table-clients').DataTable({
			"order": [
				[3, "desc"]
			],
			retrieve: true,
			"columnDefs": [{
				"targets": 0,
				"orderable": false
			}],
			dom: "<'row'<'col-sm-12'B>>" +    // Buttons in their own row at the top
				 "<'row'<'col-sm-6'l><'col-sm-6'f>>" +  // Length menu and Search
				 "<'row'<'col-sm-12'tr>>" +   // Table itself
				 "<'row'<'col-sm-5'i><'col-sm-7'p>>",   // Info and Pagination
			buttons: [{
					extend: 'copy',
					text: 'Copy to clipboard',
					messageTop: 'List of Clients',
					title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRCP Research Journal',
					action: function (e, dt, node, config) {
						// action saved to logs table
						log_export('copied list of clients to clipboard');
						$.fn.dataTable.ext.buttons.copyHtml5.action.call(this, e, dt, node, config);
					}
				},
				{
					extend: 'excel',
					text: 'Export as Excel',
					messageTop: 'List of Clients',
					title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRCP Research Journal',
					action: function (e, dt, node, config) {
						// action saved to logs table
						log_export('exported list of clients as excel');
						$.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, node, config);
					}
				},
				{
					extend: 'pdf',
					text: 'Export as PDF',
					messageTop: 'List of Clients',
					title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES' + '\n' + 'NRCP Research Journal',
					orientation: 'landscape',
					pageSize: 'LEGAL',
					action: function (e, dt, node, config) {
						// action saved to logs table
						log_export('exported list of clients as pdf');
						$.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, node, config);
					}
				},
				{
					extend: 'print',
					messageTop: 'List of Clients',
					title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRCP Research Journal',
					action: function (e, dt, node, config) {
						// action saved to logs table
						log_export('printed list of clients');
						window.print();
					}
				}
			]
		});
	}

	// initiate and disable sorting on clicking first column first row  
	var tcli = $('#table-clients').DataTable();
	// tcli.on( 'order.dt search.dt', function () {
	//     tcli.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
	//         cell.innerHTML = i+1;
	//     } );
	// } ).draw();

	tcli.on('draw', function () {
		tcli.rows({
			search: 'applied',
			order: 'applied',
			filter: 'applied'
		}).data().each(function (d, i) {
			d[0] = i + 1;
		});
	});

	tcli.on('order.dt search.dt', function () {
		tcli.column(0, {
			search: 'applied',
			order: 'applied'
		}).nodes().each(function (cell, i) {
			cell.innerHTML = i + 1;
		});
	}).draw(false);

	// check and display export button if privilege to export is enabled
	if (prv_exp == 0) {
		var tvwrs = $('#table-viewers').DataTable({
			"order": [
				[1, "asc"]
			],
			retrieve: true,
			"columnDefs": [{
				"targets": 0,
				"orderable": false
			}]
		});
	} else {
		var tvwrs = $('#table-viewers').DataTable({
			"order": [
				[1, "asc"]
			],
			retrieve: true,
			columnDefs: [
				{
				"targets": 0,
				"orderable": false
				},
				// { width: "500px", targets: 1 }, // Set the width of the first column
				
			],
			autowidth: true,
			dom: "<'row'<'col-sm-12'B>>" +    // Buttons in their own row at the top
				 "<'row'<'col-sm-6'l><'col-sm-6'f>>" +  // Length menu and Search
				 "<'row'<'col-sm-12'tr>>" +   // Table itself
				 "<'row'<'col-sm-5'i><'col-sm-7'p>>",   // Info and Pagination
			buttons: [{
					extend: 'copy',
					text: 'Copy to clipboard',
					messageTop: 'List of Abstract Hits',
					title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRCP Research Journal',
					action: function (e, dt, node, config) {
						// action saved to logs table
						log_export('copied abstract hits to clipboard');
						$.fn.dataTable.ext.buttons.copyHtml5.action.call(this, e, dt, node, config);
					}
				},
				{
					extend: 'excel',
					text: 'Export as Excel',
					messageTop: 'List of Abstract Hits',
					title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRCP Research Journal',
					action: function (e, dt, node, config) {
						// action saved to logs table
						log_export('exported abstract hits as excel');
						$.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, node, config);
					}
				},
				{
					extend: 'pdf',
					text: 'Export as PDF',
					messageTop: 'List of Abstract Hits',
					title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES' + '\n' + 'NRCP Research Journal',
					action: function (e, dt, node, config) {
						// action saved to logs table
						log_export('exported abstract hits as pdf');
						$.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, node, config);
					}
				},
				{
					extend: 'print',
					messageTop: 'List of Abstract Hits',
					title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRCP Research Journal',
					action: function (e, dt, node, config) {
						// action saved to logs table
						log_export('printed abstract hits');
						window.print();
					}
				}
			]
		});
	}

	// initiate and disable sorting on clicking first column first row 
	tvwrs.on('draw', function () {
		tvwrs.rows({
			search: 'applied',
			order: 'applied',
			filter: 'applied'
		}).data().each(function (d, i) {
			d[0] = i + 1;
		});
	});

	tvwrs.on('order.dt search.dt', function () {
		tvwrs.column(0, {
			search: 'applied',
			order: 'applied'
		}).nodes().each(function (cell, i) {
			cell.innerHTML = i + 1;
		});
	}).draw(false);

	// check and display export button if privilege to export is enabled
	if (prv_exp == 0) {
		var tedt = $('#table-editorials').DataTable({
			
			"order": [
				[1, "asc"]
			],
			retrieve: true,
			columnDefs: [
				{
				"targets": 0,
				"orderable": false
				},
				// { width: "500px", targets: 1 }, // Set the width of the first column
				
			],
			autowidth: true,
		});
	} else {
		var tedt = $('#table-editorials').DataTable({
			"order": [
				[1, "asc"]
			],
			retrieve: true,
			columnDefs: [
				{
				"targets": 0,
				"orderable": false
				},
				// { width: "500px", targets: 1 }, // Set the width of the first column
				
			],
			autowidth: true,
			dom: "<'row'<'col-sm-12'B>>" +    // Buttons in their own row at the top
				 "<'row'<'col-sm-6'l><'col-sm-6'f>>" +  // Length menu and Search
				 "<'row'<'col-sm-12'tr>>" +   // Table itself
				 "<'row'<'col-sm-5'i><'col-sm-7'p>>",   // Info and Pagination
			buttons: [{
					extend: 'copy',
					text: 'Copy to clipboard',
					messageTop: 'List of Editorial Boards',
					title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRCP Research Journal',
					action: function (e, dt, node, config) {
						// action saved to logs table
						log_export('copied editorial boards to clipboard');
						$.fn.dataTable.ext.buttons.copyHtml5.action.call(this, e, dt, node, config);
					}
				},
				{
					extend: 'excel',
					text: 'Export as Excel',
					messageTop: 'List of Editorial Boards',
					title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRCP Research Journal',
					action: function (e, dt, node, config) {
						// action saved to logs table
						log_export('exported editorial boards as excel');
						$.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, node, config);
					}
				},
				{
					extend: 'pdf',
					text: 'Export as PDF',
					messageTop: 'List of Editorial Boards',
					title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES' + '\n' + 'NRCP Research Journal',
					action: function (e, dt, node, config) {
						// action saved to logs table
						log_export('exported editorial boards as pdf');
						$.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, node, config);
					}
				},
				{
					extend: 'print',
					messageTop: 'List of Editorial Boards',
					title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRCP Research Journal',
					action: function (e, dt, node, config) {
						// action saved to logs table
						log_export('printed editorial boards');
						window.print();
					}
				}
			]
		});
	}

	// initiate and disable sorting on clicking first column first row 
	tedt.on('draw', function () {
		tedt.rows({
			search: 'applied',
			order: 'applied',
			filter: 'applied'
		}).data().each(function (d, i) {
			d[0] = i + 1;
		});
	});

	tedt.on('order.dt search.dt', function () {
		tedt.column(0, {
			search: 'applied',
			order: 'applied'
		}).nodes().each(function (cell, i) {
			cell.innerHTML = i + 1;
		});
	}).draw(false);

	var temnot = $('#table-email-notifications').DataTable();
	// initiate and disable sorting on clicking first column first row 
	temnot.on('draw', function () {
		temnot.rows({
			search: 'applied',
			order: 'applied',
			filter: 'applied'
		}).data().each(function (d, i) {
			d[0] = i + 1;
		});
	});

	temnot.on('order.dt search.dt', function () {
		temnot.column(0, {
			search: 'applied',
			order: 'applied'
		}).nodes().each(function (cell, i) {
			cell.innerHTML = i + 1;
		});
	}).draw(false);

	// check and display export button if privilege to export is enabled
	if (prv_exp == 0) {
		$('#table-activities').DataTable();
	} else {
		$('#table-activities').DataTable({
			dom: 'lBfrtip',
			buttons: [{
					extend: 'copy',
					text: 'Copy to clipboard',
					messageTop: 'List of Activity Logs',
					title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRCP Research Journal',
					action: function (e, dt, node, config) {
						// action saved to logs table
						log_export('copied activity logs to clipboard');
						$.fn.dataTable.ext.buttons.copyHtml5.action.call(this, e, dt, node, config);
					}
				},
				{
					extend: 'excel',
					text: 'Export as Excel',
					messageTop: 'List of Activity Logs',
					title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRCP Research Journal',
					action: function (e, dt, node, config) {
						// action saved to logs table
						log_export('exported activity logs as excel');
						$.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, node, config);
					}
				},
				{
					extend: 'pdf',
					text: 'Export as PDF',
					messageTop: 'List of Activity Logs',
					title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES' + '\n' + 'NRCP Research Journal',
					action: function (e, dt, node, config) {
						// action saved to logs table
						log_export('exported activity logs as pdf');
						$.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, node, config);
					}
				},
				{
					extend: 'print',
					messageTop: 'List of Activity Logs',
					title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRCP Research Journal',
					action: function (e, dt, node, config) {
						// action saved to logs table
						log_export('printed activity logs');
						window.print();
					}
				}
			]
		});
	}

	// check and display export button if privilege to export is enabled
	if (prv_exp == 0) {
		$('#activityLogsTable').DataTable({
			"order": [
				[1, "asc"]
			],
			retrieve: true,
			columnDefs: [
				{
				"targets": 0,
				"orderable": false
				},
				// { width: "500px", targets: 1 }, // Set the width of the first column
				
			],
			autowidth: true,
		});
	} else {
		$('#activityLogsTable').DataTable({
			"order": [
				[1, "asc"]
			],
			retrieve: true,
			columnDefs: [
				{
				"targets": 0,
				"orderable": false
				},
				// { width: "500px", targets: 1 }, // Set the width of the first column
				
			],
			autowidth: true,
			dom: "<'row'<'col-sm-12'B>>" +    // Buttons in their own row at the top
				 "<'row'<'col-sm-6'l><'col-sm-6'f>>" +  // Length menu and Search
				 "<'row'<'col-sm-12'tr>>" +   // Table itself
				 "<'row'<'col-sm-5'i><'col-sm-7'p>>",   // Info and Pagination
			buttons: [{
					extend: 'copy',
					text: 'Copy to clipboard',
					messageTop: 'List of Activity Logs',
					title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRCP Research Journal',
					action: function (e, dt, node, config) {
						// action saved to logs table
						log_export('copied activity logs to clipboard');
						$.fn.dataTable.ext.buttons.copyHtml5.action.call(this, e, dt, node, config);
					}
				},
				{
					extend: 'excel',
					text: 'Export as Excel',
					messageTop: 'List of Activity Logs',
					title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRCP Research Journal',
					action: function (e, dt, node, config) {
						// action saved to logs table
						log_export('exported activity logs as excel');
						$.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, node, config);
					}
				},
				{
					extend: 'pdf',
					text: 'Export as PDF',
					messageTop: 'List of Activity Logs',
					title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES' + '\n' + 'NRCP Research Journal',
					action: function (e, dt, node, config) {
						// action saved to logs table
						log_export('exported activity logs as pdf');
						$.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, node, config);
					}
				},
				{
					extend: 'print',
					messageTop: 'List of Activity Logs',
					title: 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES - NRCP Research Journal',
					action: function (e, dt, node, config) {
						// action saved to logs table
						log_export('printed activity logs');
						window.print();
					}
				}
			]
		});
	}

	// initiate and disable sorting on clicking first column first row 
	var alt = $('#activityLogsTable').DataTable();

    // Custom date range filter
    $.fn.dataTable.ext.search.push(
        function(settings, data, dataIndex) {

			if (settings.nTable.id !== 'activityLogsTable') {
                return true; // Bypass filtering for other tables
            }

            // Get the date column value
            var dateColumnData = data[3]; // Assume date is in the first column (index 0). Adjust if needed.
            var min = $('#dateFrom').val();
            var max = $('#dateTo').val();

			// Parse the date from the table and filter input values
			var date = moment(dateColumnData, 'YYYY-MM-DD HH:mm:ss A'); // Use format 'YYYY-MM-DD HH:mm:ss A'
			var minDate = min ? moment(min, 'YYYY-MM-DD') : null;
			var maxDate = max ? moment(max, 'YYYY-MM-DD') : null;

             // Filter by date range
			 if (
                (!minDate || date.isSameOrAfter(minDate)) &&
                (!maxDate || date.isSameOrBefore(maxDate))
            ) {
                return true;
            }
            return false;
        }
    );

	
	alt.on('order.dt search.dt', function () {
		alt.column(0, {
			search: 'applied',
			order: 'applied'
		}).nodes().each(function (cell, i) {
			cell.innerHTML = i + 1;
		});
	}).draw();

    // Event listeners for the date inputs
    $('#dateFrom, #dateTo').on('change', function() {
        alt.draw(); // Redraw the table with the new filter applied
    });


	// trigger click on side navigation to create journal
	$('#btn-create-journal').click(function () {
		$('#v-pills-create-journal-tab').trigger('click');
	});

	// trigger click on side navigation to add editorial board
	$('#btn-add-editorial').click(function () {
		$('#v-pills-add-editorial-tab').trigger('click');
	});

	// trigger click on side navigation to view list of journals
	$('#view_journals').click(function () {
		$('#v-pills-journal-list-tab').trigger('click');
	});

	// trigger click on side navigation to view list of editorail boards
	$('#view_editorials').click(function () {
		$('#v-pills-editorial-list-tab').trigger('click');
	});

	// trigger click on side navigation to view list of clients downloaded journals
	$('#view_clients').click(function () {
		$('#v-pills-client-list-tab').trigger('click');
	});

	// remove class 
	$('.sub-item').click(function () {
		$('.sub-item').removeClass('active show');
	});

	// remove class
	$('.list-group-item').click(function () {
		$('.sub-item').removeClass('active show');
	});

	// add editorial board with validations
	$("#add_editorial_form").validate({
		debug: true,
		errorClass: 'text-danger',
		rules: {
			edt_position: {
				required: true,
			},
			edt_name: {
				required: true,
			},
			edt_email: {
				required: true,
			},
			edt_sex: {
				required: true,
			},
			edt_affiliation: {
				required: true,
			},
      edt_position_affiliation: {
				required: true,
      },
			edt_specialization: {
				required: false,
			},
			edt_country: {
				required: false,
			},
			edt_affiliation: {
				required: true,
			},
			edt_address: {
				required: true,
			},
			edt_country: {
				required: true,
			},
			edt_specialization: {
				required: true,
			},
			edt_year: {
				required: true,
			},
			edt_volume: {
				required: true,
			},
			edt_issue: {
				required: true,
			}
		},
		submitHandler: function () {

      $('#submit_editorial').prop('disabled', true);

			var form = $('#add_editorial_form');
			var formdata = false;

			if (window.FormData) {
				formdata = new FormData(form[0]);
			}


			$.ajax({
				url: base_url + "admin/journal/editorial/add",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				type: 'POST',
				success: function (data, textStatus, jqXHR) {

					let timerInterval;

					Swal.fire({
					title: "Editorial board added successfully!",
					text: 'Page reloading...',
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
					});

					// $.notify({
					// 	icon: 'oi oi-check',
					// 	message: 'Editorial board added successfully. Page will reload after 3 seconds.'
					// }, {
					// 	type: 'success',
					// 	timer: 3000
					// });

					// setTimeout(function () {
					// 	history.go(0);
					// }, 3000);

				}
			});

      $('#add_editorial_form')[0].reset();
      $('#submit_editorial').prop('disabled', false);
		}
	});

	// add editorial board with validations in modal
	$("#editorial_modal_form").validate({
		debug: true,
		errorClass: 'text-danger',
		rules: {
			edt_position: {
				required: true,
			},
			edt_name: {
				required: true,
			},
			edt_sex: {
				required: true,
			},
			edt_affiliation: {
				required: true,
			},
			edt_specialization: {
				required: false,
			},
			edt_country: {
				required: false,
			},
			edt_affiliation: {
				required: true,
			}
		},
		submitHandler: function () {

			$('#editorial_modal .btn-primary').attr("disabled", true);

			var form = $('#editorial_modal_form');
			var formdata = false;

			if (window.FormData) {
				formdata = new FormData(form[0]);
			}

			$.ajax({
				url: base_url + "admin/journal/editorial/update/",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				type: 'POST',
				success: function (data, textStatus, jqXHR) {

					
					let timerInterval;

					Swal.fire({
					title: "Editorial board updated successfully!",
					text: 'Page reloading...',
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
					});

					// $.notify({
					// 	icon: 'oi oi-check',
					// 	message: 'Editorial board updated successfully. Page will reload after 3 seconds.'
					// }, {
					// 	type: 'success',
					// 	timer: 3000
					// });

					$('#editorial_modal').modal('toggle');
					// setTimeout(function () {
					// 	history.go(0);
					// }, 3000);

				}
			});

      $('#editorial_modal_form')[0].reset();
			$('#editorial_modal .btn-primary').attr("disabled", true);
		}
	});

	// update journal with validations in modal
	$("#journal_modal_form").validate({
		debug: true,
		errorClass: 'text-danger',
		rules: {
			jor_volume: {
				required: true,
			},
			jor_issue: {
				required: true,
			},
			jor_year: {
				required: true,
				minlength: 4,
			},
			jor_cover : {
				extension: "jpg",
				filesize : 2000000,
			},
		},
		messages: {
			jor_cover: {
				filesize: function (param) {
					// Dynamically construct the message based on the file size limit
					return `File size must be less than 20 MB`;
				}
			}
		},
		submitHandler: function () {
			var form = $('#journal_modal_form');
			var formdata = false;

			if (window.FormData) {
				formdata = new FormData(form[0]);
			}

			var formAction = form.attr('action');

			$.ajax({
				url: base_url + "admin/journal/journal/update/",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				type: 'POST',
				success: function (data, textStatus, jqXHR) {

					$('#journal_modal button').prop('disabled', true);
					let timerInterval;

					Swal.fire({
					title: "Journal updated successfully!",
					text: "Page reloading...",
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
					});

					// $.notify({
					// 	icon: 'oi oi-check',
					// 	message: 'Journal updated successfully. Page will reload after 3 seconds.'
					// }, {
					// 	type: 'success',
					// 	timer: 3000
					// });

					$('#journal_modal').modal('toggle');
					// setTimeout(function () {
					// 	history.go(0);
					// }, 3000);

				}
			});
		}
	});

	// add article with validations in modal
	$("#article_modal_form").validate({
		debug: true,
		errorClass: 'text-danger',
		rules: {
			art_year: {
				required: true,
			},
			art_jor_id: {
				required: true,
			},
			art_title: {
				required: true,
			},
			art_keywords: {
				required: true,
			},
			art_page: {
				required: true,
			},
			art_abstract_file: {
				required: true,
				extension: "pdf",
				filesize : 20000000,
			},
			art_full_text_pdf: {
				required: true,
				extension: "pdf",
				filesize : 20000000,
			},
			art_author: {
				required: true,
			},
			art_affiliation: {
				required: true,
			}
		},
		messages: {
			art_abstract_file: {
				filesize: function (param) {
					// Dynamically construct the message based on the file size limit
					return `File size must be less than 20 MB`;
				}
			},
			art_full_text_pdf: {
				filesize: function (param) {
					// Dynamically construct the message based on the file size limit
					return `File size must be less than 20 MB`;
				}
			}
		},
		submitHandler: function () {
			var form = $('#article_modal_form');
			var formdata = false;

			if (window.FormData) {
				formdata = new FormData(form[0]);
			}

			var formAction = form.attr('action');

			$.ajax({
				url: base_url + "admin/journal/article/update/",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				type: 'POST',
				success: function (data, textStatus, jqXHR) {

					let timerInterval;

					Swal.fire({
					title: "Article updated successfully!",
					text: "Page reloading...",
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
					});

					// $.notify({
					// 	icon: 'oi oi-check',
					// 	message: 'Article updated successfully. Page will reload after 3 seconds.'
					// }, {
					// 	type: 'success',
					// 	timer: 3000
					// });

					$('#article_modal').modal('toggle');
					// setTimeout(function () {
					// 	history.go(0);
					// }, 3000);

				}
			});
		}
	});

	$('#upload_call_papers').on('change', function(){
		var selected = $(this).val();
		if(selected == 1){
			$('.upload_cfp').removeClass('d-none');
			$('.upload_cfpi').addClass('d-none');
		}else{
			$('.upload_cfpi').removeClass('d-none');
			$('.upload_cfp').addClass('d-none');
		}
	});

	// update home page content with validations
	$("#form_home").validate({
		debug: true,
		errorClass: 'text-danger',
		rules: {
			home_description: {
				required: true,
			},
			upload_cfp: {
				required: function () {
					return $('#upload_call_papers').val() === '1'; // Check if the select value is '1'
				}
			},
			upload_cfpi: {
				required: function () {
					return $('#upload_call_papers').val() === '2'; // Check if the select value is '2'
				}
			}
		},
		submitHandler: function () {

			var form = $('#form_home');
			var formdata = false;

			if (window.FormData) {
				formdata = new FormData(form[0]);
			}

			var formAction = form.attr('action');
			var file_size_pdf = ($('#upload_cfp').val()) ? $('#upload_cfp')[0].files[0].size : '';
			var file_size_img = ($('#upload_cfpi').val()) ? $('#upload_cfpi')[0].files[0].size : '';

			// if file size of pdf is less than 20mb hide warning
			if (file_size_pdf < '20000000') {
				$('#upload_cfp').next('.bg-danger').hide();
			}
			// if file size of image is less than 20mb hide warning
			if (file_size_img < 2000000) {
				$('#upload_cfpi').next('.bg-danger').hide();
			}

			// if file size of pdf is more than 20mb show warning
			if (file_size_pdf >= '20000000') {
				$('#upload_cfp').next('.bg-danger').hide();
				$('#upload_cfp').after(' <span class="badge bg-danger"><span class="oi oi-warning"></span> File size must not exceed 20 MB</span>');
			} else if (file_size_img >= 2000000) // if file size of image is more than 20mb show warning
			{
				$('#upload_cfpi').next('.bg-danger').hide();
				$('#upload_cfpi').after(' <span class="badge bg-danger"><span class="oi oi-warning"></span> File size must not exceed 2 MB</span>');
			} else {
				$.ajax({
					url: base_url + "admin/dashboard/update_home/",
					data: formdata ? formdata : form.serialize(),
					cache: false,
					contentType: false,
					processData: false,
					type: 'POST',
					success: function (data, textStatus, jqXHR) {

						$('#form_home')[0].reset();

						let timerInterval;

						Swal.fire({
						title: "Saved successfully!",
						text: "Page reloading...",
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
						});

						
						// $.notify({
						// 	icon: 'oi oi-check',
						// 	message: 'Saved successfully. Page will reload in 3 seconds'
						// }, {
						// 	type: 'success',
						// 	timer: 3000
						// });

						// setTimeout(function () {
						// 	history.go(0);
						// }, 3000);

					}
				});
			}
		}
	});

	// update editor polcy content
	$("#form_policy").validate({
		debug: true,
		errorClass: 'text-danger',
		rules: {
			// ep_content: {
			// 	required: true,
			// },
			ep_file : {
				extension: "pdf",
				filesize : 20000000,
			},
		},
		messages: {
			ep_file: {
				filesize: function (param) {
					// Dynamically construct the message based on the file size limit
					return `File size must be less than 20 MB`;
				}
			}
		},
		submitHandler: function () {

			// var ep_content = tinyMCE.get('ep_content').getContent();

			var form = $('#form_policy');
			var formdata = false;

			if (window.FormData) {
				formdata = new FormData(form[0]);
			}

			$.ajax({
				url: base_url + "admin/dashboard/update_policy/",
				// data: { content: ep_content },
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				// contentType: 'application/x-www-form-urlencoded; charset=UTF-8', // Use default content type for simple data
				// processData: true, // Allow jQuery to process the data
				type: 'POST',
				success: function (data, textStatus, jqXHR) {

					let timerInterval;

					Swal.fire({
					title: "Editorial Policy saved successfully!",
					text: "Page reloading...",
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
					});

					
					// $.notify({
					// 	icon: 'oi oi-check',
					// 	message: 'Saved successfully. Page will reload in 3 seconds'
					// }, {
					// 	type: 'success',
					// 	timer: 3000
					// });

					// setTimeout(function () {
					// 	history.go(0);
					// }, 3000);

				}
			});
			
		}
	});

	// update author guidelines with validation
	$("#form_guidelines").validate({
		debug: true,
		errorClass: 'text-danger',
		rules: {
			// upload_guidelines: {
			// 	required: true,
			// },
			gd_content: {
				required: true,
			},
		},
		submitHandler: function () {

			// var form = $('#form_guidelines');
			// var formdata = false;

			// if (window.FormData) {
			// 	formdata = new FormData(form[0]);
			// }

			// var formAction = form.attr('action');
			// var file_size = $('#upload_guidelines')[0].files[0].size;

			// // if file size of pdf is less than 20mb hide warning
			// if (file_size < '20000000') {
			// 	$('#upload_guidelines').next('.bg-danger').hide();
			// }

			// // if file size of pdf is more than 20mb hide warning
			// if (file_size >= '20000000') {
			// 	$('#upload_guidelines').next('.bg-danger').hide();
			// 	$('#upload_guidelines').after(' <span class="badge bg-danger"><span class="oi oi-warning"></span> File size must not exceed 20 MB</span>');
			// } else {

			
			var gd_content = tinyMCE.get('gd_content').getContent();


				$.ajax({
					url: base_url + "admin/dashboard/update_guidelines/",
					data: { content: gd_content },
					// data: formdata ? formdata : form.serialize(),
					cache: false,
					contentType: 'application/x-www-form-urlencoded; charset=UTF-8', // Use default content type for simple data
					processData: true, // Allow jQuery to process the data
					// contentType: false,
					// processData: false,
					type: 'POST',
					success: function (data, textStatus, jqXHR) {

						// $('#form_guidelines')[0].reset();

						let timerInterval;

						Swal.fire({
						// title: "File uploaded successfully!",
						title: "Guidelines saved successfully!",
						text: "Page reloading...",
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
						});

						// $.notify({
						// 	icon: 'oi oi-check',
						// 	message: 'File uploaded successfully. Page will reload in 3 seconds'
						// }, {
						// 	type: 'success',
						// 	timer: 3000
						// });

						// setTimeout(function () {
						// 	history.go(0);
						// }, 3000);

					}
				});
			// }
		}
	});


	//add journal with validations
	$("#form_create_journal").validate({
		debug: true,
		errorClass: 'text-danger',
		rules: {
			jor_volume: {
				required: true,
			},
			jor_issue: {
				required: true,
			},
			jor_year: {
				required: true,
				minlength: 4,
			},
			jor_cover : {
				extension: "jpg",
				filesize : 2000000,
			},
		},
		messages: {
			jor_cover: {
				filesize: function (param) {
					// Dynamically construct the message based on the file size limit
					return `File size must be less than 20 MB`;
				}
			}
		},
		submitHandler: function (submittedForm, event) {

			$('#submit_journal').prop('disabled', true);
			var form = $('#form_create_journal');
			var formdata = false;

			if (window.FormData) {
				formdata = new FormData(form[0]);
			}

			
			var file_size = ($('#jor_cover').val() != '') ? $('#jor_cover')[0].files[0].size : '';

			if (file_size > '0') {
				
				// if file size of pdf is less than 20mb hide warning
				if (file_size < '2000000') {
					$('#jor_cover').next('.bg-danger').hide();
					$('#submit_journal').prop('disabled', false);

					$.ajax({
						url: base_url + "admin/dashboard/journal/",
						data: formdata ? formdata : form.serialize(),
						cache: false,
						contentType: false,
						processData: false,
						type: 'POST',
						success: function (data, textStatus, jqXHR) {
	
							var res = jQuery.parseJSON(data);
	
							if (res.flag == 0) {

								let timerInterval;

								Swal.fire({
								title: res.msg,
								text: 'Page reloading...',
								icon: 'error',
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
									// location.reload();
								}
								}).then((result) => {
								/* Read more about handling dismissals below */
								if (result.dismiss === Swal.DismissReason.timer) {
									console.log("I was closed by the timer");
								}
								});

								// $.notify({
								// 	icon: res.icon,
								// 	message: res.msg
								// }, {
								// 	type: 'danger',
								// 	timer: 3000
								// });
							} else {
								$('#form_create_journal')[0].reset();
	
								let timerInterval;

								Swal.fire({
								title: res.msg,
								text: 'Page reloading...',
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
								});

								// $.notify({
								// 	icon: res.icon,
								// 	message: res.msg
								// }, {
								// 	type: 'success',
								// 	timer: 3000
								// });
	
								// setTimeout(function () {
								// 	history.go(0);
								// }, 3000);
							}
	
							$('#form_create_journal')[0].reset();
							$('#submit_journal').prop('disabled', false);
						}
					});
					
				}
				// if file size of pdf is less than 20mb show warning
				// else if (file_size >= '2000000') {
				// 	$('#jor_cover').after(' <span class="badge bg-danger"><span class="oi oi-warning"></span> File size must not exceed 2 MB</span>');
				// 	$('#submit_journal').prop('disabled', false);
				// }
			} else {
				$.ajax({
					url: base_url + "admin/dashboard/journal/",
					data: formdata ? formdata : form.serialize(),
					cache: false,
					contentType: false,
					processData: false,
					type: 'POST',
					success: function (data, textStatus, jqXHR) {

						var res = jQuery.parseJSON(data);

						if (res.flag == 0) {

							let timerInterval;

								Swal.fire({
								title: res.msg,
								text: 'Page reloading...',
								icon: 'error',
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
									// location.reload();
								}
								}).then((result) => {
								/* Read more about handling dismissals below */
								if (result.dismiss === Swal.DismissReason.timer) {
									console.log("I was closed by the timer");
								}
								});

							// $.notify({
							// 	icon: res.icon,
							// 	message: res.msg
							// }, {
							// 	type: 'danger',
							// 	timer: 3000
							// });
						} else {
							$('#form_create_journal')[0].reset();

							let timerInterval;

							Swal.fire({
							title: res.msg,
							text: 'Page reloading...',
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
							});

							// $.notify({
							// 	icon: res.icon,
							// 	message: res.msg
							// }, {
							// 	type: 'success',
							// 	timer: 3000
							// });

							// setTimeout(function () {
							// 	history.go(0);
							// }, 3000);
						}

						$('#form_create_journal')[0].reset();
						$('#submit_journal').prop('disabled', false);
					}
				});
			}

			event.preventDefault();
		}
	});

	// add article with validations
	$("#form_add_article").validate({
		debug: true,
		errorClass: 'text-danger',
		rules: {
			art_year: {
				required: true,
			},
			art_jor_id: {
				required: true,
			},
			art_title: {
				required: true,
			},
			art_keywords: {
				required: true,
			},
			art_page: {
				required: true,
			},
			art_abstract_file: {
				required: true,
				extension: "pdf",
				filesize : 20000000,
			},
			art_full_text_pdf: {
				required: true,
				extension: "pdf",
				filesize : 20000000,
			},
			art_author: {
				required: true,
			},
			art_affiliation: {
				required: true,
			},
			art_email: {
				required: true,
			}
		},
		messages: {
			art_abstract_file: {
				filesize: function (param) {
					// Dynamically construct the message based on the file size limit
					return `File size must be less than 20 MB`;
				}
			},
			art_full_text_pdf: {
				filesize: function (param) {
					// Dynamically construct the message based on the file size limit
					return `File size must be less than 30 MB`;
				}
			}
		},
		submitHandler: function (submittedForm, event) {

			$('#submit_article').prop('disabled', true);
			var form = $('#form_add_article');
			var formdata = false;

			if (window.FormData) {
				formdata = new FormData(form[0]);
			}

			// // var file_size = ($('#jor_cover').val() != '') ? $('#jor_cover')[0].files[0].size : '';
			// var file_size_abs = $('#art_abstract_file')[0].files[0].size;
			// // if ($('#art_full_text_pdf').val() != '')
			// var file_size_txt = $('#art_full_text_pdf')[0].files[0].size;


			// if (file_size_abs < '20000000' && file_size_txt < '20000000') {

				$.ajax({
					url: base_url + "admin/dashboard/article/",
					data: formdata ? formdata : form.serialize(),
					cache: false,
					contentType: false,
					processData: false,
					type: 'POST',
					success: function (data, textStatus, jqXHR) {

						var res = jQuery.parseJSON(data);

						let timerInterval;

						Swal.fire({
						title: res.msg,
						text: 'Page reloading...',
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
						});

						// $.notify({
						// 	icon: res.icon,
						// 	message: res.msg
						// }, {
						// 	type: 'success',
						// 	timer: 1000
						// });

						// setTimeout(function () {
						// 	history.go(0);
						// }, 3000);

					}
				});

				$('#form_add_article')[0].reset();
				$('#submit_article').prop('disabled', false);

			// } else {
			// 	// if file size of pdf is less than 20mb hide warning
			// 	if (file_size_abs < '20000000') {
			// 		$('#badge_pdf').next('.bg-danger').hide();
			// 		$('#submit_article').prop('disabled', false);
			// 	}
			// 	// if file size of pdf is less than 20mb hide warning
			// 	else if (file_size_txt < '20000000') {
			// 		$('#badge_text').next('.bg-danger').hide();
			// 		$('#submit_article').prop('disabled', false);
			// 	}
			// 	// if file size of pdf is more than 20mb show warning
			// 	else if (file_size_abs >= '20000000') {
			// 		$('#badge_pdf').after(' <span class="badge bg-danger"><span class="oi oi-warning"></span> File size must not exceed 20 MB</span>');
			// 		$('#submit_article').prop('disabled', false);
			// 	}
			// 	// if file size of pdf is less than 20mb show warning
			// 	else if(file_size_txt >= '20000000') {
			// 		$('#badge_text').after(' <span class="badge bg-danger"><span class="oi oi-warning"></span> File size must not exceed 20 MB</span>');
			// 		$('#submit_article').prop('disabled', false);
			// 	}
			// }
      		event.preventDefault();
		}
	});

	$('#form_change_pass #new_password').on('keyup', function() {
        $("#ejournal_password_strength_container").removeClass('d-none');
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
          $('#ejournal-password-strength').text(passwordStrength);
          $('#ejournal-password-strength-bar').css('width' , strength + '%');
          $('#ejournal-password-strength-bar').css('background-color', barColor);
        }
      });

	// change password with validations
	$("#form_change_pass").validate({
		debug: true,
		errorClass: 'text-danger',
		rules: {
			acc_password: {
				required: true,
				minlength: 8,
				maxlength: 20
			},
			repeat_password: {
				required: true,
				minlength: 8,
				maxlength: 20,
				equalTo: "#new_password"
			},
            old_password: {
                required: true,
                remote: {
                    url: base_url + "oprs/user/verify_old_password",
                    type: "post"
                }
            },
		},
        messages: {
            acc_password: {
                required: "Please enter new password",
                minlength: "Your password must be at least 8 characters long",
                maxlength: "Your password must be 20 characters long max"
            },
            repeat_password: {
                required: "Please repeat new password",
                minlength: "Your password must be 8-20 characters long",
                equalTo: "Please enter the same password as above",
                maxlength: "Your password must be 20 characters long max"
            },
            old_password: {
                required: "Please enter current password",
                remote: "Incorrect password"
            },
        },
        errorPlacement: function (error, element) {
            // Place error message below the group of checkboxes
            if (element.attr("name") === "acc_password" || element.attr("name") === "old_password") {
                error.insertAfter(element.closest("[name='" + element.attr("name") + "']").parent());
            } else {
                error.insertAfter(element);
            }
        },
		submitHandler: function () {

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

					$('#change_password').prop('disabled', true);
                    $('#change_pass').modal('toggle');

                    $.ajax({
                        type: "POST",
						url: base_url + "admin/dashboard/change_password/",
						data: $('#form_change_pass').serializeArray(),
                        cache: false,
                        crossDomain: true,
                        success: function(data) {

                            $('#form_change_pass')[0].reset();
							$('#change_password').prop('disabled', false);

                            Swal.fire({
                                title: "Password updated successfully!",
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
                                }
                            }).then((result) => {
                                /* Read more about handling dismissals below */
                                if (result.dismiss === Swal.DismissReason.timer) {
                                    console.log("I was closed by the timer");
                                }
                            });
                        }
                    });
                }
            });

		}
	});

	// add user with validations for superadmin account (unused)
	$("#form_add_user").validate({
		debug: true,
		errorClass: 'text-danger',
		rules: {
			acc_password: {
				required: true,
				minlength: 5
			},
			repeat_password: {
				required: true,
				minlength: 5,
				equalTo: "#acc_password"
			},
			acc_username: {
				required: true,
				minlength: 5
			},
			acc_type: {
				required: true,
			}
		},
		messages: {
			acc_password: {
				required: "Please provide a password",
				minlength: "Your password must be at least 5 characters long"
			},
			repeat_password: {
				required: "Please provide a password",
				minlength: "Your password must be at least 5 characters long",
				equalTo: "Please enter the same password as above"
			},
			acc_username: {
				required: "Please provide a username",
				minlength: "Your username must be at least 5 characters long"
			},
			acc_type: {
				required: "Please select user type",
			}
		},
		submitHandler: function () {
			$.ajax({
				type: "POST",
				url: base_url + "admin/dashboard/add_user/",
				data: $('#form_add_user').serializeArray(),
				cache: false,
				crossDomain: true,
				success: function (data) {

					let timerInterval;

					Swal.fire({
					title: 'User added successfully!',
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
						// location.reload();
					}
					}).then((result) => {
					/* Read more about handling dismissals below */
					if (result.dismiss === Swal.DismissReason.timer) {
						console.log("I was closed by the timer");
					}
					});

					// $.notify({
					// 	icon: 'oi oi-check',
					// 	message: 'User added successfully.'
					// }, {
					// 	type: 'success',
					// 	timer: 3000
					// });

					$('#form_add_user')[0].reset();
					$('#user_modal').modal('toggle');
				}
			});
		}
	});

	// show selected image for user display picture (unused)
	$('#set_d_p').change(function () {
		readURL_dp(this);
	});

	// show pdf file when file is selected
	$('#upload_guidelines').change(function () {
		readURL_guidelines(this);
	});

	// show selected image for journal cover
	$('#jor_cover').change(function () {
		readURL(this);
	});

	// show selected image for journal cover
	$('#edt_photo').change(function () {
		preview_editorial(this);
	});

	// show selected image for journal cover
	$('#editorial_modal #edt_photo').change(function () {
		preview_editorial_modal(this);
	});



	// show selected image for journal cover in modal
	$('#journal_modal #jor_cover').change(function () {
		readURL_modal(this);
	});

	// get articles related to author after pressing a key
	$('#authors_reg').keypress(function (e) {

		if (e.which == 13) {
			var $this = $(this).val();

			if ($this != '') {
				$.ajax({
					type: "POST",
					url: base_url + "admin/dashboard/authors_articles/" + $this,
					dataType: "json",
					crossDomain: true,
					success: function (data) {
						var html = '';
						var c = 1;

						if ($.fn.DataTable.isDataTable("#table-registry")) {
							$('#table-registry').DataTable().clear().destroy();
						}

						var table = $('#table-registry').DataTable({
							"order": [
								[1, "desc"]
							],
							retrieve: true,
							"columnDefs": [{
								"targets": 0,
								"orderable": false
							}]
						});

						$.each(data.authors, function (key, val) {

						   table.row.add([
								'',
								val.art_author + ' (Author)',
								val.art_affiliation,
								val.art_email,
								'<a href="javascript:void(0);" class="text-primary text-decoration-none" onclick="reg_list(\'' + val.art_author + '\',\'aut\')"><span class="oi oi-list"></span> View</a>'
							]);
						});

						$.each(data.coas, function (key, val) {
							table.row.add([
								'',
								val.coa_name + ' (Co-author)',
								val.coa_affiliation,
								val.coa_email,
								'<a href="javascript:void(0);" class="text-primary text-decoration-none" onclick="reg_list(\'' + val.coa_name + '\',\'coa\')"><span class="oi oi-list"></span> View</a>'
							]);

						});

						
						table.on('order.dt search.dt', function () {
							table.column(0, {
								search: 'applied',
								order: 'applied'
							}).nodes().each(function (cell, i) {
								cell.innerHTML = i + 1;
							});
						}).draw();
					}
				});
			}
		}
	});

	// delete co-author
	$(document).on('click', '#delete-coauthor', function () {
		$.ajax({
			type: "POST",
			url: base_url + "admin/journal/coauthor/delete/" + x_coa,
			dataType: "json",
			crossDomain: true
		});

		$('#remove_modal').modal('toggle');
		$('#form_row' + x_coa).remove();

	});

	// delete editorial board from modal
	$(document).on('click', '#delete-editorial-out', function () {
		$.ajax({
			type: "POST",
			url: base_url + "admin/journal/editorial/delete/" + _edt,
			dataType: "json",
			crossDomain: true
		});


		let timerInterval;

		Swal.fire({
		title: 'Editorial board deleted successfully!',
		text: 'Page reloading...',
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
		});

		// $.notify({
		// 	icon: 'oi oi-check',
		// 	message: 'Editorial board deleted successfully. Page will reload in 3 seconds.'
		// }, {
		// 	type: 'success',
		// 	timer: 3000
		// });

		$('#editorial_modal ,#remove_modal').modal('toggle');
		// setTimeout(function () {
		// 	history.go(0);
		// }, 3000);

	});

	// get editorial board
	$(document).on('click', '#delete-editorial', function () {
		var id = $('#edt_id').val();
	});

	// delete journal
	$(document).on('click', '#delete-journal', function () {
		var id = $('#jor_id').val();

		$.ajax({
			type: "POST",
			url: base_url + "admin/journal/journal/delete/" + id,
			dataType: "json",
			crossDomain: true,
		});

		let timerInterval;

		Swal.fire({
		title: 'Journal deleted successfully!',
		text: 'Page reloading...',
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
		});

		// $.notify({
		// 	icon: 'oi oi-check',
		// 	message: 'Journal deleted successfully. Page will reload in 3 seconds.'
		// }, {
		// 	type: 'success',
		// 	timer: 3000
		// });

		$('#journal_modal, #remove_modal').modal('toggle');
		// setTimeout(function () {
		// 	history.go(0);
		// }, 3000);

	});

	// delete article
	$(document).on('click', '#delete-article', function () {
		var id = $('#art_id').val();

		$.ajax({
			type: "POST",
			url: base_url + "admin/journal/article/delete/" + id,
			dataType: "json",
			crossDomain: true,
		});

		
		let timerInterval;

		Swal.fire({
		title: 'Article deleted successfully!',
		text: 'Page reloading...',
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
		});

		// $.notify({
		// 	icon: 'oi oi-check',
		// 	message: 'Article deleted successfully. Page will reload in 3 seconds.'
		// }, {
		// 	type: 'success',
		// 	timer: 3000
		// });

		$('#article_modal,#remove_modal').modal('toggle');
		// setTimeout(function () {
		// 	history.go(0);
		// }, 3000);

	});

	// get list of volume and issue on article year select
	$('#edt_art_year').on('change', function () {
		$.ajax({
			type: "POST",
			url: base_url + "admin/journal/journals_by_year/" + $(this).val(),
			dataType: "json",
			crossDomain: true,
			success: function (data) {
				console.log(data);
				if (data.length > '0') {
					$('#edt_art_jor_id').empty();
					$('#edt_art_jor_id').append('<option>Select Volume, Issue</option>');
					$.each(data, function (key, val) {
						$('#edt_art_jor_id').append('<option value=' + val.jor_id + '>Vol. ' + val.jor_volume + ', Issue ' + val.jor_issue + '</option>');
					});
				}
			}
		});
	})



	// get list of volume and issue on article year select
	$('#editorial_modal #edt_art_year').on('change', function () {
		$.ajax({
			type: "POST",
			url: base_url + "admin/journal/journals_by_year/" + $(this).val(),
			dataType: "json",
			crossDomain: true,
			success: function (data) {
				console.log(data);
				if (data.length > '0') {
					$('#editorial_modal #edt_art_jor_id').empty();
					$('#editorial_modal #edt_art_jor_id').append('<option>Select Volume, Issue</option>');
					$.each(data, function (key, val) {
						$('#editorial_modal #edt_art_jor_id').append('<option value=' + val.jor_id + '>Vol. ' + val.jor_volume + ', Issue ' + val.jor_issue + '</option>');
					});
				}
			}
		});
	})

	// get list of volume and issue on article year select
	$('#art_year').on('change', function () {
		$.ajax({
			type: "POST",
			url: base_url + "admin/journal/journals_by_year/" + $(this).val(),
			dataType: "json",
			crossDomain: true,
			success: function (data) {
				if (data.length > '0') {
					$('#art_jor_id').empty();
					$('#art_jor_id').append('<option>Select Volume, Issue</option>');
					$.each(data, function (key, val) {
						$('#art_jor_id').append('<option value=' + val.jor_id + '>Vol. ' + val.jor_volume + ', Issue ' + val.jor_issue + '</option>');
					});
				}
			}
		});
	})

	// get list of volume and issue on article year select in modal
	$('#article_modal #art_year').on('change', function () {
		$('#article_modal #art_jor_id').prop('disabled', false);

		$.ajax({
			type: "POST",
			url: base_url + "admin/journal/journals_by_year/" + $(this).val(),
			dataType: "json",
			crossDomain: true,
			success: function (data) {
				if (data.length > '0') {
					$('#article_modal #art_jor_id').empty();
					$('#article_modal #art_jor_id').append('<option>Select Volume, Issue</option>');
					$.each(data, function (key, val) {
						$('#article_modal #art_jor_id').append('<option value=' + val.jor_id + '>Vol. ' + val.jor_volume + ', Issue ' + val.jor_issue + '</option>');
					});
				}
			}
		});
	})

	var inpIncr = 0; // incremental value for adding co-author

	// add co-author dynamically
	$('#btn-add-coauthor').click(function () {
		var html = '';
		var option = '';
		inpIncr++;

		// $.each(author_and_coauthor_list, function(key, val)
		//   {
		//     option += '<option value="'+val+'">'+val+'</option>';
		//   });

		html = '<div class="row mb-3">' +
					'<div class="col-3 autocomplete">' +
						'<label for="jor_coauthors" class="form-label">Co-Author</label>' +
						'<input class="form-control" id="coa_name' + inpIncr + '" name="coa_name[]" placeholder="Search by name or specialization">' +
					'</div>' +
					'<div class="col-3">' +
						'<label for="jor_affiliation" class="form-label">Affiliation</label>' +
						'<input type="text" class="form-control" id="coa_affiliation' + inpIncr + '" name="coa_affiliation[]" placeholder="Enter affiliation">' +
					'</div>' +
					'<div class="col-3">' +
						'<label for="jor_email" class="form-label">Email Address</label>' +
						'<input type="text" class="form-control" id="coa_email' + inpIncr + '" name="coa_email[]" placeholder="Enter a valid email">' +
					'</div>' +
					'<div class="col-3">' +
						'<button type="button" class="btn btn-outline-danger mt-4"><span class="oi oi-x"></span></button>' +
					'</div>' +
				'<div>';

		$('#coauthors').append(html);
		// $('#coa_name'+inpIncr).editableSelect({ effects: 'slide' });
		// $('#coa_name'+inpIncr).rules("add" ,{ required:true });
		autocomplete_acoa(document.getElementById("coa_name" + inpIncr), mem_exp, '#coa_affiliation' + inpIncr, '#coa_email' + inpIncr);

	});

	// var inpIncr2 = 0; // incremental value for adding co-author in modal
	$('#article_modal #btn-add-coauthor').click(function () {
		var html = '';
		var option = '';
		// inpIncr2++;
		global_coauthor_total++;

		// $.each(author_and_coauthor_list, function(key, val)
		// {
		//   option += '<option value="'+val+'">'+val+'</option>';
		// });

		html = '<div class="row mb-3">' +
					'<div class="col autocomplete">' +
						'<label for="jor_coauthors" class="form-label">Co-Author</label>' +
						'<input class="form-control" id="coa_name' + global_coauthor_total + '" name="coa_name[]" placeholder="Search by name or specialization">' +
					'</div>' +
					'<div class="col">' +
						'<label for="jor_affiliation" class="form-label">Affiliation</label>' +
						'<input type="text" class="form-control" id="coa_affiliation' + global_coauthor_total + '" name="coa_affiliation[]" placeholder="Enter affiliation">' +
					'</div>' +
					'<div class="col">' +
						'<label for="jor_email" class="form-label">Email Address</label>' +
						'<input type="text" class="form-control" id="coa_email' + global_coauthor_total + '" name="coa_email[]" placeholder="Enter a valid email">' +
					'</div>' +
					'<div class="col-1">' +
						'<button type="button" class="btn btn-outline-danger mt-4"><span class="oi oi-x"></span></button>' +
					'</div>' +
				'<div>';


		$('#coa_list').append(html);
		// $('#article_modal #coa_name'+inpIncr2).editableSelect({ effects: 'slide' });
		// $('#article_modal #coa_name'+inpIncr2).rules("add", {required:true});
		autocomplete_acoa(document.getElementById("coa_name" + global_coauthor_total), mem_exp, '#coa_affiliation' + global_coauthor_total, '#coa_email' + global_coauthor_total);

	});

	// get id to delete editorial board and store to global variable
	$(document).on('click', '#table-editorials .btn-danger', function () {
		_edt = $(this).closest('tr').find('#delete_edt_id').val();
	});

	// remove data row after deleting co-author
	$('#coauthors').on('click', '.btn-outline-danger', function () {
		$(this).closest('.row').remove();
	});

	// remove data row after deleting co-author from modal
	$('#article_modal #coa_list').on('click', '.btn-outline-danger', function () {
		$(this).closest('.row').remove();
	});

	// store id of clicked item in side navigation
	// $('#list-tab ').on('click', '.list-group-item', function () {
	// 	$('.list-group-item').css('border-left', 'none');
	// 	$(this).css('border-left', '10px solid #5bc0de');

	// 	href = $(this).attr('href');
	// 	localStorage.setItem('activeTab', href);
	// 	$('#table-clients').DataTable().search('').draw();
	// 	$('#table-viewers').DataTable().search('').draw();

	// });

	// var activeTab = localStorage.getItem('activeTab'); // store side navigation item id

	// auto click stored side navigation id after reload event
	// if (activeTab) {
	// 	if (activeTab == '#add-editorial-tab' || activeTab == '#editorials') {
	// 		$('a[href="#manage-editorials"]').trigger('click');
	// 	}

	// 	$('#list-tab a[href="' + activeTab + '"]').trigger('click');
	// }

	// delete co-authors
	$('#table-coauthor').on('click', '#delete-coauthor', function (e) {
		e.preventDefault();
		$(this).closest('tr').remove();
	});


	setInterval(function () {

		// auto show online or offline users
		// online_users();
		// auto show notifications
		// notifications();

	}, 1000);

	// display count of remaining character to enter
	$('#type_message').keypress(function (e) {
		var key = e.which;

		if (key == 13) // the enter key code
		{
			if ($('#type_message').val().length > '0') {
				var msg = '<div class="d-flex flex-row-reverse text-white bd-highlight text-right mb-3">' +
					'<div class="p-2 bd-highlight bg-primary rounded ">' + $('#type_message').val() + '</div>' +
					'</div>';

				$('#message_body').append(msg);

				sendMessage(rcvr, $('#type_message').val());

				$('#type_message').val('').focus();
				$('#message_body').animate({
					scrollTop: $('#message_body').get(0).scrollHeight
				}, 0);

			}
		}
	});

	// send message 
	$('#btn-send').click(function (e) {
		var msg = '<div class="d-flex flex-row-reverse text-white bd-highlight text-right mb-3">' +
			'<div class="p-2 bd-highlight bg-primary rounded ">' + $('#type_message').val() + '</div>' +
			'</div>';

		$('#message_body').append(msg);

		sendMessage(rcvr, $('#type_message').val());

		$('#type_message').val('').focus();
		$('#message_body').animate({
			scrollTop: $('#message_body').get(0).scrollHeight
		}, 0);
	});

	// get messages every second if modal is open
	var msgInterval;
	$(document).on('show.bs.modal', '#messageModal', function () {
		msgInterval = setInterval(function () {
			getMessages();
		}, 1000);
	});

	// stop interval if modal is closed
	$(document).on('hide.bs.modal', '#messageModal', function () {
		clearInterval(msgInterval);
	});

	// get messages every second if scroll is moving
	$('#message_body').scroll(function () {
		clearInterval(msgInterval);

		clearTimeout($.data(this, "scrollCheck"));

		$.data(this, "scrollCheck", setTimeout(function () {
			msgInterval = setInterval(function () {
				getMessages();
			}, 1000);
		}, 250));

	});

	$('input:radio[name="fb_rate_ui"]').change(
		function () {
			if (this.checked) {
				$(".ui-container .alert-danger").remove();
			}
		});

	$('input:radio[name="fb_rate_ux"]').change(
		function () {
			if (this.checked) {
				$(".ux-container .alert-danger").remove();
			}
		});

		
   
    // update account
    $("#form_update_account").validate({
        debug: true,
        errorClass: 'text-danger',
        rules: {
            usr_full_name: {
                required: true,
            },
            usr_username: {
                required: true,
                remote: {
                    url: base_url + "oprs/user/verify_email_except_self",
                    type: "post"
                }
            },
            usr_sex: {
                required: true,
            },
        },
        messages: {
            usr_username: {
                remote: "Email already in use"
            }
        },
        submitHandler: function() {
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

                    $('body').loading('start');
                    $('#accountSettingModal').modal('toggle');

                    $.ajax({
                        type: "POST",
                        url: base_url + "oprs/user/udpate_account",
                        data: $('#form_update_account').serializeArray(),
                        cache: false,
                        crossDomain: true,
                        success: function(data) {

                            $('body').loading('stop');
                            $('#form_update_account')[0].reset();

                            Swal.fire({
                                title: "Account updated successfully!",
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

	// $('#feedback_form').on('submit', function (e) {

	// 	e.preventDefault();

	// 	var alert = '<div class="alert alert-danger w-100" role="alert"> \
    //                     Please select your rating. \
    //                     </div>';

	// 	if (!$("input[name='fb_rate_ui']").is(':checked')) {
	// 		$(".ui-container .alert-danger").remove();
	// 		$(alert).hide().appendTo(".ui-container").fadeIn();
	// 	}

	// 	if (!$("input[name='fb_rate_ux']").is(':checked')) {
	// 		$(".ux-container .alert-danger").remove();
	// 		$(alert).hide().appendTo(".ux-container").fadeIn();
	// 	}

	// 	if ($("input[name='fb_rate_ui']").is(':checked') && $("input[name='fb_rate_ux']").is(':checked')) {

	// 		$.ajaxSetup({
	// 			headers: {
	// 				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	// 			}
	// 		});

	// 		var formdata = $(this).serializeArray();
	// 		// console.log(formdata);
	// 		$.ajax({
	// 			type: "POST",
	// 			url: base_url + 'admin/feedback/submit/1',
	// 			data: formdata,
	// 			cache: false,
	// 			crossDomain: true,
	// 			success: function (data) {
	// 				// console.log(data);return false;
	// 				$('#feedback_form').remove();

	// 				var thanks = '<p class="text-center h2">Thank you for your feedback.</p> \
    //                           <p class="text-center btn-link fw-bold"><u><a href="' + base_url + 'oprs/login/logout");">Proceed to logout</a></u></p>';


	// 				$(thanks).hide().appendTo("#feedbackModal .modal-body").fadeIn();

	// 			}
	// 		});
	// 	}
	// });



	$("#select_all_structure").change(function () {
		if (this.checked) {
			$("input[name='table_structure[]']").each(function () {
				this.checked = true;
			});
		} else {
			$("input[name='table_structure[]']").each(function () {
				this.checked = false;
			});
		}
	});

	$("#select_all_data").change(function () {
		if (this.checked) {
			$("input[name='table_data[]']").each(function () {
				this.checked = true;
			});
		} else {
			$("input[name='table_data[]']").each(function () {
				this.checked = false;
			});
		}
	});

	$('#sd_table').hide();

	$('#quick_export').change(function () {

		$('#sd_table').hide();
	});

	$('#custom_export').change(function () {

		$('#sd_table').show();
	});


	$('#import_file').change(function () {
		$('.custom-file-label').text($(this).val().split('\\').pop());
	});


	$("#import_db_form").validate({
		debug: true,
		errorClass: 'text-danger',
		rules: {
			import_file: {
				required: true,
			},
		},
		errorLabelContainer: '.invalid-feedback',
		submitHandler: function () {

			var form = $('#import_db_form');
			var formdata = false;

			if (window.FormData) {
				formdata = new FormData(form[0]);
			}

			$.ajax({
				type: "POST",
				url: base_url + 'admin/backup/import',
				data: formdata ? formdata : form.serialize(),
				contentType: false,
				processData: false,
				success: function (response) {

					if (response == 1) {
						$('#success_import').hide().append('<div class="alert alert-success" role="alert"> \
                        SQL file imported successfully! \
                        </div>').fadeIn(1000);
					}
				}
			});
		}
	});

	$('#update_email_content_btn').click(function () {
		var enc_content = tinyMCE.get('enc_content').getContent();
		var formData = $('#email_content_form').serializeArray();
		formData.push({
			name: 'enc_content',
			value: enc_content
		});

		$.ajax({
			type: "POST",
			url: base_url + 'admin/emails/update_email_content/',
			data: formData,
			cache: false,
			crossDomain: true,
			success: function (data) {

				$('#update_email_content_btn').attr('disabled', true);
				$('#emailContentModal').modal('toggle');

				let timerInterval;

				Swal.fire({
				title: "Email notification updated successfully!",
				text: 'Page reloading...',
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
				});
			}
		});

		location.reload();
	});


});

/**
 * manage user for superadmin only
 *
 * @param   {int}     id      user id
 * @param   {string}  user    user name
 * @param   {int}     status  user status
 *
 * @return  {void}          
 */
function manage_user(id, user, status) {
	if (status == 0) {
		$('#manage_user_content').show();
		$('#offline_user').hide();
		$('#manage_username').val(user);
		$('.reset-pass').attr('href', base_url + 'admin/dashboard/reset_password/' + id);
		$('.remove-user').attr('href', base_url + 'admin/dashboard/remove_user/' + id);
		$('.super').attr('href', base_url + 'admin/dashboard/change_user_type/0/' + id);
		$('.admin').attr('href', base_url + 'admin/dashboard/change_user_typer/1/' + id);
		$('.man').attr('href', base_url + 'admin/dashboard/change_user_type/2/' + id);
	} else {
		$('#manage_user_content').hide();
		$('#offline_user').show();
	}
}

/**
 * display user status 
 *
 * @return  {void}  
 */
function online_users() {
	var detect = [];

	$.ajax({
		type: "POST",
		url: base_url + "admin/login/get_online_users",
		dataType: "json",
		crossDomain: true,
		success: function (data) {
			var html = '<ul class="list-group list-group-flush">';

			$('.online-users').empty();

			$.each(data, function (key, val) {
				if (key != 'current_user') {
					_ref = val.acc_status;
					detect.push(_ref);
					var icon = (val.acc_status == 1) ? "text-success" : "text-muted";
					var notif = (val.acc_login_time != null) ? ' <small class="text-muted ml-2">Active ' + login_t + '</small>' : '';
					var cog = (data.current_user == 0 && val.acc_type != 0) ?
						'<a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#manage_user_modal" onclick="manage_user(' + val.row_id + ',\'' + val.acc_username + '\',' + val.acc_status + ')"><span class="oi oi-cog text-muted float-right mt-1" data-bs-toggle="tooltip" data-placement="top" title="Settings"></span></a>' : '';
					var msg = '<div class="icon-wrapper  float-right">' +
						'<a href="javascript:void(0);" onclick="message(' + val.row_id + ',\'' + val.acc_username + '\')"><span class="oi oi-envelope-closed mt-1 ml-2 text-muted" data-bs-toggle="tooltip" data-placement="top" title="Message"></span>' +
						'</a></div>';

					html += '<li class="list-group-item">' +
						'<span class="oi oi-media-record ' + icon + '"></span> <span >' + val.acc_username + '</span>' +
						notif +
						msg +
						cog +
						'</li>';
				}
			});

			html += '</ul>';

			$('.online-users').append(html);

		}
	});

}

/**
 * show notification
 *
 * @return  {void} 
 */
function notifications() {
	$.ajax({
		type: "POST",
		url: base_url + "admin/dashboard/notifications",
		dataType: "json",
		crossDomain: true,
		success: function (data) {
			$.each(data, function (key, val) {
				// $.notify({
				// 	icon: 'oi oi-bell',
				// 	message: '<span class="text-uppercase"><strong>' + val.acc_username + '</strong></span> ' + val.log_action
				// }, {
				// 	type: 'secondary',
				// 	timer: 5000
				// });

				$.ajax({
					type: "POST",
					url: base_url + "admin/dashboard/notifications/" + val.row_id,
					crossDomain: true,
				});
			});
		}
	});
}

/**
 * edit editorial board data
 *
 * @param   {int}  id  editorial board id
 *
 * @return  {void}  
 */
function edit_editorial(id) {
	$('#editorial_modal').modal('toggle');
	$('#editorial_modal #edt_id').val(id);

	$.ajax({
		type: "POST",
		url: base_url + "admin/journal/editorial/view/" + id,
		dataType: "json",
		crossDomain: true,
		success: function (data) {
			console.log("🚀 ~ edit_editorial ~ data:", data)
			if (data.length > '0') {
				$.each(data, function (key, val) {
					$('#edt_photo_exist').val(val.edt_photo);
					if (val.edt_photo != '') {
						$('#editorial_modal #editorial_photo').attr('src', base_url + 'assets/uploads/editorial/' + val.edt_photo);
					} else {
						$('#editorial_modal #editorial_photo').attr('src', base_url + 'assets/images/unavailable.png');
						// $('#editorial_modal #editorial_photo').attr('src', base_url + 'assets/images/unavailable.jpg');
					}
					$.each(val, function (k, v) {
						if (k != 'edt_photo'){
							if(k == 'edt_position'){
								$('#editorial_modal #' + k).val(v).trigger('change');
							}else{
								$('#editorial_modal #' + k).val(v)
							}
						};
					});
				});
			}
		}
	});
}

/**
 * edit journal data
 *
 * @param   {int}     id   journal id
 * @param   {[type]}  art  count of articles per journal
 *
 * @return  {void}       
 */
function edit_journal(id, art = null) {
	$('#journal_modal_form')[0].reset();

	if (art > '0') {
		$('#journal_modal .btn-danger').hide();
	} else {
		$('#journal_modal .btn-danger').show();
	}

	$('#journal_modal').modal('toggle');

	$.ajax({
		type: "POST",
		url: base_url + "admin/journal/journal/view/" + id,
		dataType: "json",
		crossDomain: true,
		success: function (data) {
			if (data.length > '0') {
				$.each(data, function (key, val) {
					$.each(val, function (k, v) {
						if (k != 'jor_cover')
							$('#journal_modal #' + k).val(v);

					});

					$('#journal_modal #cover_photo').attr('src', base_url + 'assets/uploads/cover/' + val.jor_cover);
				});
			}
		}
	});
}

/**
 * confirmation message before delete
 *
 * @param   {string}  button  button id
 * @param   {int}     id      null or co-autrho id
 *
 * @return  {void}          
 */
function _remove(button, id = null) {
	$('#remove_modal').modal('toggle');
	$('#remove_modal #btn-remove').attr('id', button);

	if (button == 'delete-article') {
		$('#remove_modal .modal-body').html('Are you sure? </br></br>Uploaded files will also be removed in the system.');
	} else if (button == 'delete-journal') {
		$('#remove_modal .modal-body').html('Are you sure?');
	} else if (button == 'delete-editorial') {
		$('#remove_modal .modal-body').html('Are you sure?');
	} else if (button == 'delete-editorial-out') {
		$('#remove_modal .modal-body').html('Are you sure?');
	} else if (button == 'delete-coauthor') {
		$('#remove_modal .modal-body').html('Are you sure?');
		x_coa = id;
	} else {
		$('#remove_modal .modal-body').html('Are you sure?');
	}
}

/**
 * view data of an article
 *
 * @param   {int}  id    article id
 * @param   {string}  vol   volume of article
 * @param   {string}  iss   issue of article
 * @param   {string}  mos   month publication of article
 * @param   {string}  year  year publication of article
 *
 * @return  {void} 
 */
function view_articles(id, vol, iss, mos, year) {
	$('body').loading('start');
	_year = year;
	_jor = id;
	var c = 1;

	$('#btn-export-excel-articles').attr('href', base_url + "admin/export_excel/export_excel/a/" + _jor);
	$('#btn-export-pdf-articles').attr('href', base_url + "admin/export_excel/export_pdf/a/" + _jor);

	$.ajax({
		type: "POST",
		url: base_url + "admin/journal/article/get/" + id,
		dataType: "json",
		crossDomain: true,
		success: function (data) {
			$('body').loading('stop');

			if ($.fn.DataTable.isDataTable("#table-articles")) {
				$('#table-articles').DataTable().clear().destroy();
			}

			var table = $('#table-articles').DataTable({
				columnDefs: [
					{ width: "200px", targets: 3 }, // Set the width of the first column
					{ width: "100px", targets: 4 } // Set the width of the first column
				],
				// Optional: to ensure the table layout is applied correctly
				autoWidth: false 
			});

			if (data.length > '0') {
				$.each(data, function (key, val) {
					if (prv_edt == 1) {
						var btn = "<button type='button' class='btn btn-sm btn-light text-primary' onclick='edit_article(" + val.art_id + ")'><span class='oi oi-pencil'></span> Edit Article</button>";
					} else {
						btn = '';
					}

					var pdfc = count_pdf(val.art_id);
					var absc = count_abstract(val.art_id);

					if (pdfc > '0') {
						var pdf = "<a href='javascript:void(0);''  class='text-success fw-bold' onclick='get_client_info(" + val.art_id + ")'  >" + pdfc + "</a>";
					} else {
						var pdf = '0';
					}

					if (absc > '0') {
						var abs = "<a href='javascript:void(0);'  class='text-default fw-bold' onclick='get_hits_info(" + val.art_id + ")'  >" + absc + "</a>";
					} else {
						var abs = '0';
					}

					var date = moment(val.date_created, 'YYYY-MM-DD HH:mm').format("MMMM D, h:mm a");
					get_coauthors(val.art_id);
					coasss = (coas == '') ? val.art_author : val.art_author + ', ' + coas;

					table.row.add([
						// c++,
						'<strong>' + val.art_title + '</strong>, ' + coasss,
						abs,
						pdf,
						date,
						btn
					]);
				});

				table.on('order.dt search.dt').draw();
			}

			
			$('#articles .h3').text('Vol. ' + vol + ', Issue ' + iss + ', ' + year);
			$('#articles').collapse('show');
			$('html,body').animate({
				scrollTop: $("#articles").offset().top - 50
			}, 'fast');

		}
	});
}

/**
 * show list of citees 
 *
 * @param   {string}  data  article id
 *
 * @return  {void}        
 */
function get_citees(data) {
	$('#v-pills-citees-list-tab').trigger('click');
	$('#table-citees').DataTable().search('ID:' + data).draw();
}

/**
 * show list of clients who downloaded articles
 *
 * @param   {string}  data  article id
 *
 * @return  {void}        
 */
function get_client_info(data) {
	$('#v-pills-client-list-tab').trigger('click');
	$('#table-clients').DataTable().search('ID:' + data).draw();
}

/**
 * show list of clients who viewed abstract of article
 *
 * @param   {string}  data  article id
 *
 * @return  {void} 
 */
function get_hits_info(data) {
	$('#v-pills-viewers-list-tab').trigger('click');
	$('#table-viewers').DataTable().search('ID:' + data).draw();
}

/**
 * count total hits of abstract
 *
 * @param   {int}  art_id  article id
 *
 * @return  {int}          total number of abstract hits
 */
function count_abstract(art_id) {
	var result = false;

	$.ajax({
		type: "POST",
		url: base_url + "admin/journal/count_abstract/" + art_id,
		dataType: "html",
		async: false,
		success: function (data) {
			result = data;
		}
	});

	return result;
}

/**
 * count total pdf downloaded
 *
 * @param   {int}  art_id  article id
 *
 * @return  {int}        total number of downloads
 */
function count_pdf(art_id) {
	var result = false;

	$.ajax({
		type: "POST",
		url: base_url + "admin/journal/count_pdf/" + art_id,
		dataType: "html",
		async: false,
		success: function (data) {
			result = data;
		}
	});

	return result;
}

/**
 * set value on add article
 *
 * @param   {string}  year journal year
 * @param   {int}  jor_id  journal id
 *
 * @return  {void}   
 */
function add_article(year, jor_id) {
	$('#v-pills-add-article-tab').trigger('click');
	$('#art_year').val(year).change();
	setTimeout("$('#art_jor_id').val(" + jor_id + ");", 500);
}

/**
 * edit article data
 *
 * @param   {int}  id  article id
 *
 * @return  {void}
 */
function edit_article(id) {
	$('#coa_list').empty();

	autocomplete_acoa(document.getElementById("art_author"), mem_exp, '#art_affiliation', '#art_email');

	$.ajax({
		type: "POST",
		url: base_url + "admin/journal/article/view/" + id,
		dataType: "json",
		crossDomain: true,
		success: function (data) {
			console.log(data);
			if (data.length > '0') {
				$('#article_modal').modal('toggle');

				$.each(data, function (key, val) {
					$.each(val, function (k, v) {
						$('#article_modal #' + k).val(v);

						if (k == 'art_abstract_file') {
							$('#view_abstract').attr('href', base_url + 'assets/uploads/abstract/' + v);
						} else if (k == 'art_full_text_pdf') {
							$('#view_pdf').attr('href', base_url + 'assets/uploads/pdf/' + v);
						}
					});
				});
			}
		}
	});

	var option = '';
	var html = '';

	// $.each(author_and_coauthor_list,function(key, val)
	// {
	//   option += '<option value="'+val+'">'+val+'</option>';
	// });

	$.ajax({
		type: "POST",
		url: base_url + "admin/journal/coauthor/get/" + id,
		dataType: "json",
		crossDomain: true,
		success: function (data) {
			$('#article_modal #table-coauthor tbody').empty();

			if (data.length > '0') {
				var c = 0;

				$.each(data, function (key, val) {
					c++;

					
					html = `<div class="row mb-3" id="form_row${val.coa_id}">
								<div class="col" >
									<div class="autocomplete">
										<label for="jor_coauthors" class="form-label">Co-Author</label>
										<input type="text" class="form-control" id="coa_name${c}" name="coa_affiliation[]" placeholder="Enter affiliation" value="${val.coa_name}">
									</div>
								</div>
								<div class="col">
										<label for="jor_affiliation" class="form-label">Affiliation</label>
										<input type="text" class="form-control" id="coa_affiliation${c}" name="coa_affiliation[]" placeholder="Enter affiliation" value="${val.coa_affiliation}">
								</div>
								<div class="col">
									<label for="jor_email" class="form-label">Email Address</label>
									<input type="text" class="form-control" id="coa_email${c}" name="coa_email[]" placeholder="Enter a valid email" value="${val.coa_email}">
								</div>`;

								
					
					if (prv_del == 1) {
						html += '<div class="col-1"><button type="button" class="btn btn-outline-danger mt-4" onclick="_remove(\'delete-coauthor\',\'' + val.coa_id + '\')"><span class="oi oi-trash"></span></button></div>';
					} else {
						html += '';
					}



					html += '</div>';

					$('#coa_list').append(html);
					// $('#coa_name'+c).editableSelect({ effects: 'slide' });
					// $('#coa_name'+c).val(val.coa_name);
					autocomplete_acoa(document.getElementById("coa_name" + c), mem_exp, '#coa_affiliation' + c, '#coa_email' + c);
				});

				global_coauthor_total = c;
			}
		}
	});
}

/**
 * display selected image
 *
 * @param   {file}  input  user display picture
 *
 * @return  {void} 
 */
function readURL_dp(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();

		reader.onload = function (e) {
			$('#img_dp').attr('src', e.target.result);
		}

		reader.readAsDataURL(input.files[0]);
	}
}

/**
 * display selected image
 *
 * @param   {file}  input  image for journal cover
 *
 * @return  {void}         
 */
function readURL(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();

		reader.onload = function (e) {
			$('#cover_photo').attr('src', e.target.result);
		}

		reader.readAsDataURL(input.files[0]);
	}
}

function preview_editorial(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();

		reader.onload = function (e) {
			$('#editorial_photo').attr('src', e.target.result);
		}

		reader.readAsDataURL(input.files[0]);
	}
}

function preview_editorial_modal(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();

		reader.onload = function (e) {
			$('#editorial_modal #editorial_photo').attr('src', e.target.result);
		}

		reader.readAsDataURL(input.files[0]);
	}
}

/**
 * display selected image in modal
 *
 * @param   {file}  input  image for journal cover
 *
 * @return  {void}         
 */
function readURL_modal(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();

		reader.onload = function (e) {
			$('#journal_modal #cover_photo').attr('src', e.target.result);
		}

		reader.readAsDataURL(input.files[0]);
	}
}

/**
 * display selected file
 *
 * @param   {file}  input  pdf file
 *
 * @return  {void}
 */
function readURL_guidelines(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();

		reader.onload = function (e) {
			$('#embed_guidelines').attr('src', e.target.result);
		}

		reader.readAsDataURL(input.files[0]);
	}
}

/**
 * get visitor info from accessing home page
 *
 * @return  {void}
 */
function get_visitors() {
	var c = 1;

	$.ajax({
		type: "POST",
		url: base_url + "admin/dashboard/visitor",
		dataType: "json",
		crossDomain: true,
		success: function (data) {
			if ($.fn.DataTable.isDataTable("#table-visitors")) {
				$('#table-visitors').DataTable().clear().destroy();
			}

			if (data.length > '0') {
				$.each(data, function (key, val) {
					var date = moment(val.vis_datetime, 'YYYY-MM-DD HH:mm').format("MMMM D, h:mm a");

					$('#table-visitors').dataTable().fnAddData([
						c++,
						val.vis_ip,
						val.vis_location,
						val.vis_user_agent,
						date,
					]);
				});

				var t = $('#table-visitors').DataTable();
				t.on('order.dt search.dt', function () {
					t.column(0, {
						search: 'applied',
						order: 'applied'
					}).nodes().each(function (cell, i) {
						cell.innerHTML = i + 1;
					});
				}).draw();
			}
		}
	});

	$('#visitor_modal').modal('toggle');
}

/**
 * get list of co-authors
 *
 * @param   {int}  id  article id
 *
 * @return  {void}      
 */
function get_coauthors(id) {
	var coa = [];

	$.ajax({
		type: "POST",
		url: base_url + "client/Ejournal/get_coauthors/" + id,
		dataType: "json",
		async: false,
		crossDomain: true,
		success: function (data) {
			if (data.length > '0') {
				$.each(data, function (key, val) {
					coa.push(val.coa_name);
				});
			}

			coas = coa.join(', ');
		}
	});
}

/**
 * get articles related to author or co-author
 *
 * @param   {string}  data  author or co-author name
 * @param   {string}  flag  determine if looking for author or co-author
 *
 * @return  {void}
 */
function reg_list(data, flag) {
	$.ajax({
		type: "POST",
		url: base_url + "admin/Dashboard/reg_list/" + data + "/" + flag,
		dataType: "json",
		async: false,
		crossDomain: true,
		success: function (data) {
			if (data.length > '0') {
				$('#registry_modal').modal('toggle');
				$('.registry').empty();

				$.each(data, function (key, val) {
					$('.registry').append('<li class="list-group-item"><span class="oi oi-chevron-right"></span> ' + val.art_title + '</li>');
				});
			}
		}
	});
}

/**
 * display message box and messages
 *
 * @param   {int}  rcvrnum  receiver user id
 * @param   {string}  name     username
 *
 * @return  {void}           
 */
function message(rcvrnum, name) {

	$('#messageModal').modal('toggle');
	$('#message_body').empty();
	$('.message_username').text(name);
	$('#type_message').val('');
	rcvr = rcvrnum;
	getMessages();

}

/**
 * count remaining character
 *
 * @param   {string}  val  content 
 *
 * @return  {void}
 */
function countChar(val) {
	var len = val.value.length;
	if (len >= 255) {
		val.value = val.value.substring(0, 255);
	} else {
		$('.limit').text(254 - len + '/255');
	}
};

/**
 * send message
 *
 * @param   {int}  rcvr  receiver user id
 * @param   {string}  msg   content of message
 *
 * @return  {void}        
 */
function sendMessage(rcvr, msg) {
	$.ajax({
		type: "POST",
		url: base_url + "admin/Dashboard/message/send/" + rcvr + "/" + encodeURIComponent(msg),
		dataType: "json",
		crossDomain: true,
		success: function (data) {
			console.log(data);
		}
	});
}

/**
 * get messages
 *
 * @return  {void}
 */
function getMessages() {
	$.ajax({
		type: "GET",
		url: base_url + "admin/Dashboard/message/get/" + rcvr,
		dataType: "json",
		crossDomain: true,
		success: function (data) {
			$('#message_body').empty();
			$.each(data, function (key, val) {
				if (val.msg_receiver == rcvr) {
					var msg = '<div class="d-flex flex-row-reverse text-white bd-highlight text-right mb-3">' +
						'<div class="p-2 bd-highlight bg-primary rounded ">' + decodeURIComponent(val.msg_content) +
						'<br/><small>' + moment(val.date_created).fromNow(); + '</small></div>' +
					'</div>';
				} else {
					var msg = '<div class="d-flex flex-row text-white bd-highlight text-left mb-3">' +
						'<div class="p-2 bd-highlight bg-secondary rounded ">' + decodeURIComponent(val.msg_content) +
						'<br/><small>' + moment(val.date_created).fromNow(); + '</small></div>' +
					'</div>';
				}

				$('#message_body').append(msg);
				$('#message_body').animate({
					scrollTop: $('#message_body').get(0).scrollHeight
				}, 0);
			});

		}
	});

}

/**
 * get new messages
 *
 * @return  {void}
 */
function newMessages() {
	$('#new_msg').empty();

	$.ajax({
		type: "POST",
		url: base_url + "admin/Dashboard/new_messages",
		dataType: "json",
		crossDomain: true,
		success: function (data) {
			if (data.length > '0') {
				var html = '<div class="dropdown-menu dropdown-menu-right">' +
					'<h6 class="dropdown-header">New Messages:</h6>';

				$.each(data, function (key, val) {
					html += '<div class="dropdown-divider"></div>' +
						'<a class="dropdown-item" href="javascript:void(0);" onclick="message(\'' + val.msg_sender + '\',\'' + val.acc_username + '\')">' +
						'<strong>' + val.acc_username + '</strong>' +
						'<span class="small float-right text-muted">' + moment(val.date_created).format('h:mm a') + '</span>' +
						'<div class="dropdown-message text-truncate" style="width:200px;max-width: 250px;">' + val.msg_content + '</div>' +
						'</a>';
				});

				// html += '<div class="dropdown-divider"></div>'+
				//   '<a class="dropdown-item small" href=javascript:void(0);>View all messages</a>'+
				html += '</div>';
			} else {
				var html = '<div class="dropdown-menu dropdown-menu-right">' +
					'<h6 class="dropdown-header">No new messages</h6></div>';
			}

			$('#new_msg').append(html);

		}
	});
}

/**
 * count new messages
 *
 * @return  {void}
 */
function countNewMessage() {
	$.ajax({
		type: "POST",
		url: base_url + "admin/Dashboard/count_new_messages",
		crossDomain: true,
		success: function (data) {
			if (data > '0') {
				$('.badge-nav').text(data);
			}
		}
	});
}

/**
 * get and store data of authors and co-authors
 *
 * @return  {array} data stored to global variable author_and_coauthor_list
 */
function acoaList() {
	author_and_coauthor_list = $.ajax({
		type: "GET",
		url: base_url + "admin/Dashboard/get_list_author_coa",
		crossDomain: true,
		dataType: 'json',
		async: false,
		success: function (data) {
			return data;
		}
	}).responseJSON;
}

/**
 * save export logs
 *
 * @param   {string}  log  actions
 *
 * @return  {void}        
 */
function log_export(log) {
	$.ajax({
		type: "POST",
		url: base_url + "admin/dashboard/log_export/" + log,
		dataType: "json",
		crossDomain: true,
		async: false,
		success: function (data) {}
	});
}

function generate_sex_chart() {

	
	$('body').loading('start');

	var sex_labels = [];
	var sex_values = [];
	var sex_bgcolors = ['#5DADE2', '#F5B7B1'];
	var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
	var sex_title = 'Sex';
	var sex_total = 0;
	var sex_pie = [];
	var i = 0;

	$.ajax({
		method: 'GET',
		url: base_url + "admin/dashboard/sex_chart",
		async: false,
		dataType: "json",
		success: function (response) {
			$.each(response, function (key, val) {
				sex_values.push(parseInt(val.total));
				sex_labels.push(val.sex);
				sex_total += parseInt(val.total);

				sex_pie.push({
					name: val.sex,
					y: parseFloat(val.total),
					color: sex_bgcolors[i],
				});
				i++;
			});
			
			$('body').loading('stop');
		}
	});


	ejChart = new Highcharts.Chart('client_bar', {
		chart: {
			type: 'bar',
			backgroundColor: '#FFFFFF',
		},
		title: {
			text: 'Overall Clients By Sex as of ' + moment().format("MMM DD, YYYY"),
		},
		subtitle: {
			text: 'Source: https://researchjournal.nrcp.dost.gov.ph',
		},
		xAxis: {
			categories: sex_labels,
			title: {
				text: null
			},
			labels: {
				style: {
					fontSize: '14px'
				}
			}
		},
		yAxis: {
			min: 0,
			title: {
				text: 'Total Clients (' + sex_total + ')',
				align: 'high'
			},
			labels: {
				overflow: 'justify'
			}
		},
		plotOptions: {
			bar: {
				dataLabels: {
					enabled: true,
					formatter: function () {
						var pcnt = (this.y / sex_total) * 100;
						return this.y + '(' + Highcharts.numberFormat(pcnt) + '%)';
					}
				}
			},
			series: {
				colorByPoint: true,
				colors: sex_bgcolors,
				pointWidth: '60',
			}
		},
		legend: {
			layout: 'vertical',
			x: -40,
			y: 80,
			floating: true,
			shadow: true
		},
		credits: {
			enabled: false
		},
		series: [{
			name: 'Clients',
			data: sex_values,
		}]
	});

	ejChart = new Highcharts.Chart('client_pie', {
		chart: {
			plotBackgroundColor: null,
			plotBorderWidth: null,
			plotShadow: false,
			type: 'pie',
			marginBottom: 50
		},
		title: {
			text: 'Overall Clients By Sex as of ' + moment().format("MMM DD, YYYY"),
		},
		subtitle: {
			text: 'Source: https://researchjournal.nrcp.dost.gov.ph'
		},
		tooltip: {
			pointFormat: '{series.name}: <b>{point.percentage:.2f}%</b>'
		},
		accessibility: {
			point: {
				valueSuffix: '%'
			}
		},
		plotOptions: {
			pie: {
				allowPointSelect: true,
				cursor: 'pointer',
				dataLabels: {
					enabled: true,
					format: '<b>{point.name}</b>: {point.percentage:.1f} %'
				},
			}
		},
		credits: {
			text: 'Total Clients (' + sex_total + ')',
			position: {
				align: 'right',
			},
			style: {
				fontSize: '9pt',
			}
		},
		colors: sex_bgcolors,
		series: [{
			name: '',
			data: sex_pie
		}]
	});

	// monthly
	var sex_labels = [];
	var year_today = new Date().getFullYear();

	$.ajax({
		method: 'GET',
		url: base_url + "admin/dashboard/sex_monthly_line",
		async: false,
		dataType: "json",
		success: function (response) {
			console.log(response);
			if (response.length > '0') {

				var line = 0;
				for (line; line < response.length; line++) {

					$.each(response[line], function (key, val) {

						var sex_total_values = [];
						$.each(val, function (k, v) {
							if (v.length > '0') {

								$.each(v, function (x, y) {
									sex_total_values.push(parseInt(y.total));
									months.push(y.label);
								});

								sex_labels.push({
									name: k,
									color: sex_bgcolors[line],
									data: sex_total_values
								})
							}
						});
					});
				}
			}
		}
	});

	ejChart = new Highcharts.Chart('client_monthly_line', {

		title: {
			text: 'Clients By Sex, Monthly ' + year_today,
		},

		subtitle: {
			text: 'https://researchjournal.nrcp.dost.gov.ph/'
		},
		yAxis: {
			title: {
				text: 'Number of Clients'
			}
		},
		plotOptions: {
			line: {
				dataLabels: {
					enabled: true
				},
				enableMouseTracking: false
			}
		},
		xAxis: {
			categories: months
		},
		legend: {
			layout: 'vertical',
			align: 'right',
			verticalAlign: 'middle'
		},

		plotOptions: {
			line: {
				dataLabels: {
					enabled: true
				},
				enableMouseTracking: false
			}
		},

		series: sex_labels,

		responsive: {
			rules: [{
				condition: {
					maxWidth: 500
				},
				chartOptions: {
					legend: {
						layout: 'horizontal',
						align: 'center',
						verticalAlign: 'bottom'
					}
				}
			}]
		}

	});



	// yearly
	var sex_labels = [];
	var sex_years = [];

	$.ajax({
		method: 'GET',
		url: base_url + "admin/dashboard/sex_line",
		async: false,
		dataType: "json",
		success: function (response) {
			console.log(response);
			if (response.length > '0') {

				var line = 0;
				for (line; line < response.length; line++) {

					$.each(response[line], function (key, val) {

						var sex_total_values = [];
						$.each(val, function (k, v) {
							if (v.length > '0') {

								$.each(v, function (x, y) {
									sex_total_values.push(parseInt(y.total));
									sex_years.push(y.label);
								});

								sex_labels.push({
									name: k,
									color: sex_bgcolors[line],
									data: sex_total_values
								})
							}
						});
					});
				}
			}
		}
	});

	ejChart = new Highcharts.Chart('client_line', {

		title: {
			text: 'Clients By Sex, ' + sex_years[0] + '-' + sex_years[sex_years.length - 1],
		},

		subtitle: {
			text: 'https://researchjournal.nrcp.dost.gov.ph/'
		},
		yAxis: {
			title: {
				text: 'Number of Clients'
			}
		},
		plotOptions: {
			line: {
				dataLabels: {
					enabled: true
				},
				enableMouseTracking: false
			}
		},
		xAxis: {
			categories: sex_years
		},
		legend: {
			layout: 'vertical',
			align: 'right',
			verticalAlign: 'middle'
		},

		plotOptions: {
			line: {
				dataLabels: {
					enabled: true
				},
				enableMouseTracking: false
			}
		},

		series: sex_labels,

		responsive: {
			rules: [{
				condition: {
					maxWidth: 500
				},
				chartOptions: {
					legend: {
						layout: 'horizontal',
						align: 'center',
						verticalAlign: 'bottom'
					}
				}
			}]
		}

	});


}

function verify_feedback() {
	$('#logoutModal').modal('toggle');

	var jqXHR = $.ajax({
		type: "GET",
		url: base_url + "admin/feedback/verify/999999",
		async: false,
		crossDomain: true,
	});

	var stat = jqXHR.responseText.replace(/\"/g, '');
	if (stat == 0) {
		$('#feedbackModal').modal('toggle');
	} else {
		window.location.href = base_url + '/oprs/login/logout';
	}
}

// manage email notification content
function edit_email_content(id) {

	var html_body;
	var roles = '';
	var check_roles = '';


	$.ajax({
		type: "GET",
		url: base_url + "admin/emails/get_email_content/" + id,
		dataType: "json",
		crossDomain: true,
		success: function (data) {
			$.each(data, function (key, val) {
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

			$.each(check_roles, function (key, val) {
				if (id == 1) {
					$('#enc_user_group' + id).attr('onclick', 'return false;');
				} else {
					$('#enc_user_group' + id).removeAttr('onclick', 'return false;');
				}
				$('#enc_user_group' + val).prop('checked', true);
			});

			$(tinymce.get('enc_content').getBody()).html(html_body);
		}
	});
	$('#emailContentModal').modal('toggle');


}

function confirmClearLogs(element){

	$('.btn').attr('disabled', true);
	$(element).text('Clearing logs...');

    $.ajax({
		url: base_url + "admin/backup/export_clear_log/",
        type: 'GET',
		// dataType: "json",
        xhrFields: {
            responseType: 'blob' // Handle binary data
        },
        success: function(data) {
            // Create a downloadable link for the blob
            let downloadUrl = window.URL.createObjectURL(data);
            let a = document.createElement('a');
            a.href = downloadUrl;
            a.download = 'exported_data.csv';
            document.body.appendChild(a);
            a.click();
            a.remove();
			
			location.reload();
        },
        error: function() {
            alert("Failed to export data.");
        },
		finally: function(){
			location.reload();
		}
    });

}

function logout(){
    current_button_id = '#submit_feedback';
    $('#feedbackModal').modal('toggle');
    recaptchaWidgetId_logout = grecaptcha.render('captcha_logout', {
        'sitekey': '6LcTEV8qAAAAACVwToj7gI7BRdsoEEhJCnnFkWC6',
        'callback': onRecaptchaSuccess,
        'expired-callback': onRecaptchaExpired
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

function destroyUserSession(){

	$.ajax({
		type: "POST",
		url: base_url + "oprs/login/destroy_user_session/" ,
		data: { user_access_token : accessToken },
		success: function(data) {
		// console.log(data);
		}
	});
}

function toggleSearch(){
	$('#searchModal').modal('toggle');
	setTimeout(function (){
		$('#search').focus();
	}, 100);
}

function disableOnSubmit(element, form, action){
	$(element).prop('disabled' ,true);
	$(element).html('<span class="spinner-grow spinner-grow-sm me-1" role="status" aria-hidden="true"></span>Saving');
	$(form).submit();
  }
  
function togglePassword(elementID, iconID, elementID2){
	var passwordInput = $(elementID);
	if(elementID2){
		var passwordInput2 = $(elementID2);
		var passwordIcon = $(iconID);
		if (passwordInput.attr('type') === 'password' && passwordInput2.attr('type') === 'password') {
		  passwordInput.attr('type', 'text');
		  passwordInput2.attr('type', 'text');
		  passwordIcon.removeClass('fa-eye-slash').addClass('fa-eye');
		} else {
		  passwordInput.attr('type', 'password');
		  passwordInput2.attr('type', 'password');
		  passwordIcon.removeClass('fa-eye').addClass('fa-eye-slash');
		}
	}else{
		var passwordIcon = $(iconID);
		if (passwordInput.attr('type') === 'password') {
		  passwordInput.attr('type', 'text');
		  passwordIcon.removeClass('fa-eye-slash').addClass('fa-eye');
		} else {
		  passwordInput.attr('type', 'password');
		  passwordIcon.removeClass('fa-eye').addClass('fa-eye-slash');
		}
	}
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