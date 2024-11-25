/**
* File Name: front_journal.js
* ----------------------------------------------------------------------------------------------------
* Purpose of this file: 
* managae all functions in client/home page
* ----------------------------------------------------------------------------------------------------
* System Name: NRCP Research Journal
* ----------------------------------------------------------------------------------------------------
* Author: Gerard Paul D. Balde
* ----------------------------------------------------------------------------------------------------
* Date of revision: Sep 25, 2019
* ----------------------------------------------------------------------------------------------------
* Copyright Notice:
* Copyright (C) 2019 By the Department of Science and Technology - National Research Council of the Philippines
*/
var apa_format,       //apa format for article
apa_id,               //article id
fb_clt_id,            //feedback client id
fn_clt_email,         //feedback client email
minutes = 5,          //otp timer
seconds = 0,          //otp timer
intervalId,           //otp timer
isStartTimer = false, //otp timer
refCode,              //reference code for otp
accessToken,          //user access token generated on logged in
article_id,           //article id 
article_page_timeout; //article page timeout for saving abstract hits         

$(document).ready(function()
{
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
      // $('#rating-message').text(`You rated ${selectedRating} out of 5.`);
      if(selectedRatingUI > 0 && selectedRatingUX > 0){
        $('#submit_feedback').prop('disabled', false);
      }
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
      // $('#rating-message').text(`You rated ${selectedRating} out of 5.`)
      
      if(selectedRatingUI > 0 && selectedRatingUX > 0){
        $('#submit_feedback').prop('disabled', false);
      }
  });

  $('#submit_feedback').on('click', function(){

    let uiSuggestion = $('#fb_suggest_ui').val();
    let uxSuggestion = $('#fb_suggest_ux').val();
    
    let data = {
      'ui' : selectedRatingUI,
      'ux' : selectedRatingUX,
      'ui_sug' : uiSuggestion,
      'ux_sug' : uxSuggestion
    };

    console.log(data);
    //TODO::save function, validation, sweet alert2, logout
  });
  
  // get user access token
  accessToken = $.ajax({
    type: "GET",
    url: base_url + "client/login/get_access_token/",
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
  
  $('body').tooltip({ selector: '[data-bs-toggle=tooltip]' });

  $('#share_link').on('click', function(){
    $(this).html('Copied <span class="fa fa-check"></span>');

    // Get the input element
    let textToCopy = $('#share_link_article').val();
    navigator.clipboard.writeText(textToCopy);

    const tooltipInstance = bootstrap.Tooltip.getInstance(this);
    if (tooltipInstance) {
        tooltipInstance.dispose(); // Completely removes the tooltip
    }

    setTimeout(() => $(this).html('Share <span class="oi oi-share ms-1"></span>'), 3000); // Hide after 1 second
  });

  $('#my-downloads-table').DataTable();

  var url = window.location.pathname; // Get the current path
  var segments = url.split('/'); // Split the path by '/'
  // Make sure there are enough segments
  if (segments.length > 2) {
    var secondToLastSegment = segments[segments.length - 2];
    refCode = url.split('/').pop();
    
    if(secondToLastSegment == 'verify_otp' || secondToLastSegment == 'new_account_verify_otp'){ // login otp, create client account otp
      getCurrentOTP(refCode, 1);
    }else if(secondToLastSegment == 'author_account_verify_otp'){ // create author account otp
      getCurrentOTP(refCode, 2);
    }else if(secondToLastSegment == 'article'){ // save hits if page is viewed for more than 5 seconds
      let art_id = url.split('/').pop();
      let article_page_view_time = new Date().getTime();
  
      article_page_timeout = setTimeout(function() {
        let currentTime = new Date().getTime();
        let elapsedTime = currentTime - article_page_view_time;
        let seconds = elapsedTime / 1000;
        if (seconds >= 5) {
          // console.log('page has been open for more than 5 seconds');
          //save hits
          save_hits(art_id);
        }
      }, 5000); // Check after 5 seconds
    }else{
      // clear only if timeout exists
      if(article_page_timeout){
        clearTimeout(article_page_timeout);
      }
    }
  } else {
      // console.log("Not enough segments in the URL.");
  }


  $('#resend_code').on('click', function(){
    $(this).addClass('disabled').html('<span class="spinner-grow spinner-grow-sm me-1" role="status" aria-hidden="true"></span>Loading');
  });


  // $('#abstract_modal').on('show.bs.modal', function() {
  //   var modalOpenTime = new Date().getTime();
  //   var modalTimeout;

  //   modalTimeout = setTimeout(function() {
  //     var currentTime = new Date().getTime();
  //     var elapsedTime = currentTime - modalOpenTime;
  //     var seconds = elapsedTime / 1000;
  //     if (seconds >= 5) {
  //       // console.log('Modal has been open for more than 5 seconds');
  //       //save hits
  //       save_hits(article_id);
  //     }
  //   }, 5000); // Check after 5 seconds

  //   $('#abstract_modal').on('hidden.bs.modal', function() {
  //     clearTimeout(modalTimeout);
  //     // console.log('Modal has been closed');
  //   });
  // });


  let volumeList = $('#volume_list');
  let originalHeight = volumeList.height();
  let showMoreButton = $('#see_more_volumes');

  volumeList.css('height', originalHeight + 'px'); // Set initial height

  showMoreButton.on('click', function() {
    if (volumeList.height() === originalHeight) {
      volumeList.css('height', 'auto');
      showMoreButton.html('Show Less<span class="fa fa-chevron-circle-left main-link ms-1"></span>');
    } else {
      volumeList.css('height', originalHeight + 'px');
      showMoreButton.html('Show More<span class="fa fa-chevron-circle-right main-link ms-1"></span>');
    }
  });

  $(' #country').on('change', function(){
    let form = $(this).closest('form').attr('id');
    if($(this).val() != 175){
      $('#' + form + ' #region').prop('disabled', true);
      $('#' + form + ' #province').prop('disabled', true);
      $('#' + form + ' #city').prop('disabled', true);
    }else{
      $('#' + form + ' #region').prop('disabled', false);
      $('#' + form + ' #province').prop('disabled', false);
      $('#' + form + ' #city').prop('disabled', false);
      
    }
  });

  $('#region').on('change', function(){
    let form = $(this).closest('form').attr('id');
    let region = $(this).val()
    
		$.ajax({
			type: "GET",
			url: base_url + "client/ejournal/get_provinces/" + region,
			dataType: "json",
			crossDomain: true,
			success: function(data) {
        var html = '<option selected disabled>Select Province</option>';
				$.each(data, function(key, val) {
          html += '<option value="'+ val.province_id +  '">'+ val.province_name +'</option>';
				});
        $('#' + form + ' #province').empty().append(html);
			},
      error: function(xhr, status, error) {
          console.error('Error:', error);
      }
		});
  });

  $('#province').on('change', function(){
    let form = $(this).closest('form').attr('id');
    let province = $(this).val()
		$.ajax({
			type: "GET",
			url: base_url + "client/ejournal/get_city/" + province,
			dataType: "json",
			crossDomain: true,
			success: function(data) {
        var html = '<option selected disabled>Select City</option>';
				$.each(data, function(key, val) {
          html += '<option value="'+ val.city_id +  '">'+ val.city_name +'</option>';
				});
        $('#' + form + ' #city').empty().append(html);
			}
		});
  });

  $('#new_password').on('keyup', function() {
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
  
  $('#updateProfileForm #new_password').on('keyup', function() {
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

  $('#searchForm').submit(function(e){
      if ($("#searchArticlesInput").val() === "") {
        e.preventDefault();
      }
  });

  $('input:radio[name="svc_fdbk_q_answer[1]"]').on('change',function(){

      if (this.checked) {  
        if($(this).val() == 6){
          $('#svc_fdbk_q_other_answer1').removeAttr('disabled');
          
        }else{
          $('#svc_fdbk_q_other_answer1').attr('disabled','disabled');
        }
      }
  });


  // trigger auto click on previously clicked side navigation after page reload
  // var activeTab     = localStorage.getItem('redirectTab');
  // var activeTabSub  = localStorage.getItem('redirectTabSub');

  // if(activeTab)
  // {
  //   $('a[href="' + activeTab + '"]').trigger('click');
  // }

  // if(activeTabSub)
  // {
  //   $('#' + activeTabSub + '').trigger('click');
  // }

  $(document).on('input', 'input[name="search[]"]', function() {
    const button = $('#advanceSearchBtn');
    if ($(this).val().trim() !== '') {
      button.removeClass('disabled');
      button.prop('disabled', false);
    } else {
      button.addClass('disabled');
      button.prop('disabled', true);
    }
  });

  $('#advancedSearchForm select').change(function() {
    const button = $('#advanceSearchBtn');
    if ($('#advanceSearch').val().trim() !== '') {
      button.removeClass('disabled');
      button.prop('disabled', false);
    } else {
      button.addClass('disabled');
      button.prop('disabled', true);
    }
  });


  // show home tab
  $('#nav_home').click(function()
  {
    // if browser url has client, redirect to home page
    if(window.location.href.indexOf("client") > -1)
    {
      window.location.href = base_url;
      // localStorage.setItem('redirectTab', '#home');
    }
    else // else trigger side navigation item click
    {
      $('a[href="#home"]').trigger('click');
    }
  });

  // show guidelines tab
  $('#nav_gdl').click(function()
  {
    // if browser url has client, redirect to guidelines
    if(window.location.href.indexOf("client") > -1)
    {
      window.location.href = base_url;
      // localStorage.setItem('redirectTab', '#guidelines');
    }
    else // else trigger side navigation item click
    {
      $('a[href="#guidelines"]').trigger('click');
    }

  });

  // show editorial boards tab
  $('#nav_edt').click(function()
  {
     if(window.location.href.indexOf("client") > -1)
    {
      window.location.href = base_url;
      // localStorage.setItem('redirectTab', '#editorials');
    }
    else // else trigger side navigation item click
    {
      $('a[href="#editorials"]').trigger('click');
    }
  });

  // store id of clicked item in side navigation
  // $('#list_group_menu').on('click', '.list-group-item', function()
  // {
  //   localStorage.removeItem('redirectTabSub');
  //   if($(this).attr('href') != '#list_articles')
  //   href = $(this).attr('href');
  //   localStorage.setItem('redirectTab', href);

  // });

  // store id of clicked sub item in side navigation
  // $('a[href="#list_articles"]').on('click',function()
  // {
  //   id = $(this).attr('id');
  //   localStorage.setItem('redirectTabSub', id);
  // });

  $("#form_citation").validate({
    debug: true,
    errorClass: 'text-danger',
    rules: {
        cite_name: {
            required: true,
        },
        cite_email: {
            required: true,
            email:true,
        },
        cite_sex: {
            required: true,
        },
        cite_affiliation: {
            required: true,
            minlength: 2,
        },
        cite_country: {
            required: true,
        },
        cite_title: {
            required: true,
        }
    } ,
    submitHandler: function() {

      var form = $('#form_citation');
      var fromdata = false;
      if(window.FormData)
      {
        formdata = new FormData(form[0]);
      }
      var formAction = form.attr('action');

      $.ajax({
        url : base_url + "client/ejournal/save_citation/" + apa_id,
        data : formdata ? formdata :form.serialize(),
        cache : false,
        contentType : false,
        processData : false,
        type : 'POST',
        success : function(data,textStatus,jqXHR){
             fb_clt_id = parseInt(data);
             
             $('#form_citation')[0].reset();
             $('#apa_format').val(unescape(apa_format));
        }
      });

      $('#citationModal p ').text('Thank you!');
      $('#form_citation').hide();
       
     }
});

$('#citationModal .close').click(function(){
  if ($('#apa_format').val() == ''){
    $('#citationModal').modal('toggle');
  }else{
    verify_citation(fb_clt_id,2);
  }
});

  // download pdf with validations for clients
  $("#form-client").validate({
                              debug: true,
                              errorClass: 'text-danger',
                              rules: {
                                  clt_title: {
                                      required: true,
                                  },
                                  clt_name: {
                                      required: true,
                                      minlength: 2,
                                  },
                                  clt_sex: {
                                      required: true,
                                  },
                                  clt_affiliation: {
                                      required: true,
                                      minlength: 2,
                                  },
                                  clt_country: {
                                      required: true,
                                  },
                                  clt_email: {
                                      required: true,
                                      email:true,
                                  },
                                  clt_purpose: {
                                      required: true,
                                      minlength: 2,
                                  },
                                  clt_vcode: {
                                      required: true,
                                  }
                              }
                              ,
                              messages: {
                                  clt_title: {
                                      required: "Please provide your title",
                                  },
                                  clt_name: {
                                      required: "Please provide your full name",
                                      minlength: "Your password must be at least 2 characters long",
                                  },
                                  clt_sex: {
                                      required: "Please select sex",
                                  },
                                  clt_affiliation: {
                                      required: "Please provide your affiliation",
                                      minlength: "Your affiliation must be at least 2 characters long",
                                  },
                                  clt_country: {
                                      required: "Please select country",
                                  },
                                  clt_email: {
                                      required: "Please provide your email",
                                      email: "Please provide a valid email",
                                  },
                                  clt_purpose: {
                                      required: "Please provide your purpose",
                                      minlength: "Your purpose must be at least 2 characters long",
                                  },
                                  clt_vcode: {
                                      required: "Please provide the verification code forwarded to your email.",
                                      minlength: "Verification code must be 6 characters long",
                                  }
                              },
                              submitHandler: function() {

                                // $('#pdf_mail').text($('#clt_email').val());
                                // fb_clt_email = $('#clt_email').val();

                                var form = $('#form-client');
                                var fromdata = false;
                                if(window.FormData)
                                {
                                  formdata = new FormData(form[0]);
                                }
                                var formAction = form.attr('action');
                                $("#btn_submit_client_info").text("Please wait...");
                                $.ajax({
                                  url : base_url + "client/ejournal/download_pdf/",
                                  data : formdata ? formdata :form.serialize(),
                                  cache : false,
                                  contentType : false,
                                  processData : false,
                                  type : 'POST',
                                  success : function(data,textStatus,jqXHR){
                                    //console.log(data);
                                    console.log(textStatus);
                                    console.log(jqXHR.status);
                                    console.log(jqXHR);
                                    if(textStatus=="success" && jqXHR.status==200){
                                      fb_clt_id = parseInt(data);

                                      //$('#form-client').remove();

                                      // $('#client_modal').modal('toggle');
                                    /*
                                    $('#client_modal .modal-body').append('<div class="alert alert-success" role="alert"> \
                                    <span class="oi oi-check"></span> Full Text PDF sent! Please check your email. \
                                    </div><h5 class="text-center"></h5>\
                                    <button class="btn btn-light w-100" id="btn_feedbackx">Close</button>');
                                    */
                                    if(jqXHR.responseText==401){
                                      $("#btn_submit_client_info").text("Submit");
                                      let msg = "<div class='alert alert-danger font-weight-bold'> Wrong Verification code. Please enter the right verification code.</div>";
                                      //$('#client_modal .modal-body').html(msg);
                                      $("#message_notif").html(msg);
                                    }
                                    else if(jqXHR.responseText==200){
                                      $('#form-client').remove();
                                      let msg = "<div class='alert alert-success font-weight-bold' role='alert'>\
				                                         <span class='oi oi-check'></span> Full Text PDF sent! Please check your email.</div><h5 class='text-center'></h5> \
				                                          <button class='btn btn-light w-100 font-weight-bold' id='btn_feedback'>Close</button>";
                                      $('#client_modal .modal-body').html(msg);
                                      $('#client_modal .modal-footer').hide();
                                      //$("#message_notif").html(msg);
                                    }else{
                                      $("#btn_submit_client_info").text("Submit");
                                      $("#message_notif").html(jqXHR.responseText);
                                    }
                                    
                                    // $('#client_modal .modal-footer').hide();  
                                    // $.notify({
                                    // icon: 'oi oi-check',
                                    // message: 'Full Text PDF sent! Please check your email.'
                                    // },{
                                    //   type: 'primary',
                                    //   timer: 3000,
                                    // });
                                    //$('#form-client')[0].reset();
                                    //$('#btn_submit_client_info').hide();
                                    // $('#btn_cancel_client_info').hide();
                                    //$('#alert-processing').removeClass('invisible');
                                    }
                                  },
                                  error: function(jqXHR, textStatus, errorThrown) {
                                    console.error("AJAX error: " + textStatus, errorThrown);
                                    $('#client_modal .modal-body').append('<div class="alert alert-danger" role="alert"> \
                                        <span class="oi oi-x"></span> Something went wrong. Please try again later.</div>\
                                        <button class="btn btn-light w-100" id="btn_feedback">Close</button>');
                                }
                                });

                                
                               }
  });


  // search ejournal and redirect to result page
  // $('#searchArticlesInput').keydown(function(e)
  // {
  //   var keyword = $(this).val();
  //   if(e.which == 13)
  //   {
  //     if(keyword != ''){
  //       window.location.href = base_url + "client/ejournal/articles/"+ keyword.replace(/ /g, '+');
  //     }
  //   }
  // });

  // $('#searchArticlesBtn').click(function(e){
  //   let keyword = $('#searchArticlesInput').val()
  //   if(keyword != ''){;
  //     window.location.href = base_url + "client/ejournal/articles/"+ keyword.replace(/ /g, '+');
  //   }
  // });

  // $('#searchArticlesBtn2').click(function(e){
  //   let keyword = $('#searchArticlesInput2').val();
  //   if(keyword != ''){
  //     window.location.href = base_url + "client/ejournal/articles/"+ keyword.replace(/ /g, '+');
  //   }
  // });

  // $('#searchArticlesInput2').keydown(function(e)
  // {
  //   var keyword = $(this).val();
  //   if(e.which == 13)
  //   {
  //     if(keyword != ''){
  //       window.location.href = base_url + "client/ejournal/articles/"+ keyword.replace(/ /g, '+');
  //     }
  //   }
  // });



  // show modal on downloading pdf
  $('#download_pdf').click(function()
  {
    $('#abstract_modal').modal('hide');
    
    $('#abstract_modal').on('hidden.bs.modal', function () {
      // Load up a new modal...
      $('#client_modal').modal('show')
    })
  });

  // show modal on downloading pdf  
  $('#top_download_pdf').click(function()
  {
    $('#top_modal').modal('hide');
    // setTimeout(function(){ $('#client_modal').modal('show'); }, 500);

    $('#top_modal').on('hidden.bs.modal', function () {
      // Load up a new modal...
      $('#client_modal').modal('show')
    })
  });

 



  // remove class active show
  $('.list-group-item-action ').click(function()
  {
    $('.sub-item').removeClass('active show');
  });

  // set editableselect effects to slide
  $('#clt_country, #cite_country').editableSelect({ effects: 'slide' });
  
  $('#clt_country, #cite_country').val('Philippines');

  // show larger image
  $(document).on('click', '#viy_photo', function()
  {
      $('.enlargeImageModalSource').attr('src', $(this).attr('src'));
      $('#enlargeImageModal').modal('show');
  });

  //conrtol feedback form display
  $(document).on('click', '#btn_feedback', function()
  { 
      $('#client_modal').modal('toggle');
    $('#feedbackModal').modal('toggle');
    verify_feedback(fb_clt_id, 1);

  });

  $('input:radio[name="fb_rate_ui"]').change(
    function(){
        if (this.checked) {
                $(".ui-container .alert-danger").remove();
        }
    });

    $('input:radio[name="fb_rate_ux"]').change(
    function(){
        if (this.checked) {
                $(".ux-container .alert-danger").remove();
        }
    });

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
         //console.log(formdata);
         $("#submit_feedback").text("Please wait...");
        $.ajax({
            type: "POST",
            url: base_url + 'admin/feedback/submit/3',
            data:  formdata,
            cache: false,
            crossDomain: true,
            success: function(data, status) {
              if(status=="success" && data!=1){
                $("#alert_prompt").html(data);
                $("#submit_feedback").text("Submit Feedback");
              }
              if(status=="success" && data==1){
                $('#feedback_form').remove();
                $('#feedback_form .btn-primary').hide();
                $("#submit_feedback").text("Saved");
                var thanks = '<p class="text-center h2">Thank you for your feedback.</p> \
                              <button class="btn btn-light w-100" data-dismiss="modal" onClick="window.location.reload();">Close</button>';       
                $(thanks).hide().appendTo("#feedbackModal .modal-body").fadeIn();

              }
            }
        });
      }
  });

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
              window.location = base_url + "client/login/";
            });
        }
    }, 1000); // Check every 1 second
  }

  $('input[name="author_type"]').on('click', function(){
    let authType = this.value;

    if(authType == 1){
      // disabled some fields
      $('#authorSignUpForm input, #authorSignUpForm select').each(function(){
        let inputType = $(this).attr('type');
        if(inputType != 'radio' && inputType != 'password' && inputType != 'email'){
          $(this).attr('disabled', true);
        }
      });
    }else{
      // enabled some fields
      $('#authorSignUpForm input, #authorSignUpForm select').each(function(){
        let inputID = $(this).attr('id');
        // if(inputID != 'region' && inputID != 'province' && inputID != 'city'){
          $(this).attr('disabled', false);
        // }
      });

      // check if email exist as client in ejournal
      let email = $('#new_email').val();
      let member = authType;
  
      if(email && member){
        
        let formData = {
          'email' : email,
          'member' : member
        };
  
        checkEmail(formData);
      }
    }

    $('#create_account').removeClass('disabled');
  })

  $('#authorSignUpForm #new_email').on('blur', function(){
    
    let email = $(this).val();
    let member = $('input[name="author_type"]:checked').val();

    if(email && member){
      
      let formData = {
        'email' : email,
        'member' : member
      };

      checkEmail(formData);
    }

  });
});


function checkEmail(formData){
  $.ajax({
    type: 'POST',
    url: base_url + "oprs/signup/check_author_email/",
    dataType: 'json',
    data: formData,
    success: function (response) {
      if(formData['member'] == 1){
        if(response == 1){ // nrcp member and not oprs author
          $('#new_email').removeClass('is-invalid')
          $('.invalid-feedback').text();
          $('#create_account').removeClass('disabled');
        }else if(response == 2){
          $('#new_email').addClass('is-invalid')
          $('.invalid-feedback').text('Email already in use.');
          $('#create_account').addClass('disabled');
        }else{
          $('#new_email').addClass('is-invalid')
          $('.invalid-feedback').text('Email does not exist.');
          $('#create_account').addClass('disabled');
        }
      }else{
        if(response['ej'] == 1 && response['op'] == 0){
  
          Swal.fire({
            title: "Email already exists in eJournal",
            text: "Do you want to register this account as author?",
            icon: "warning",
            showCancelButton: true,
            cancelButtonColor: "#adb5bd",
            confirmButtonColor: "#0c6bcb",
            confirmButtonText: "Proceed"
          }).then((result) => {
            if (result.isConfirmed) {
              register_author_acccount(formData);
            } else {
              $('#authorSignUpForm')[0].reset();
              $('form input, select').removeClass('is-invalid');
              $('.alert-danger').alert('close');
            }
          });
  
          $('#create_account').addClass('disabled');
        }else if(response['ej'] >= 1 && response['op'] >= 1){
          let oprs_url = base_url + "oprs/login";
          $('#new_email').addClass('is-invalid')
          $('.invalid-feedback').html('Email already exist as Author. Click <a class="text-danger text-decoration-underline fw-bold" href="' + oprs_url +'">here</a> to login as Author.');
          $('#create_account').addClass('disabled');
        }else{
          $('#new_email').removeClass('is-invalid')
          $('.invalid-feedback').text();
          $('#create_account').removeClass('disabled');
        }
      }
    }
  });

}

function register_author_acccount(formData){
  $.ajax({
    type: 'POST',
    url: base_url + "client/signup/register_author_account/",
    data: formData,
    success: function (response) {
      console.log("🚀 ~ register_author_acccount ~ response:", response)
      //TODO: display loading
      window.location.href = response;
    }
  });
}

/**
 * get all articles per journal
 *
 * @param   {int}  id  journal id
 *
 * @return  {void}     ]
 */
function get_articles(id)
{
    $('body').loading('start');
    $('.sub-item').removeClass('active show');

    $.ajax({
      type:"POST",
      url: base_url + "client/ejournal/get_articles/"+id,
      dataType:"json",
      crossDomain: true,
      success:
              function(data)
              {
                if(data.length > 0)
                {
                  $('.list-group-articles').empty();

                  var html ;
                  var issue ;
                  var cite;
                  var final; 

                  $.each(data, function(key, val)
                  {
                    viy(val.art_jor_id);
                    get_coauthors(val.art_id);
                    coasss    = (coas == '') ? val.art_author : val.art_author+',& '+coas;
                    coa_array = coasss.split(',& ');
                    issue = (
                      (val.jor_issue == 5) ? 'Special Issue No. 1' :
                      ((val.jor_issue == 6) ? 'Special Issue No. 2' :
                        ((val.jor_issue == 7) ? 'Special Issue No. 3' :
                          ((val.jor_issue == 8) ? 'Special Issue No. 4' : 'Issue ' + val.jor_issue)))
                    );

                    html = '<div class="list-group-item">'+
                            '<div class="row"><div class="col-md-11"><div class="d-flex w-100 justify-content-between">'+
                            '<h5 class="mb-1 text-dark">'+val.art_title+'</h5>'+
                           '</div>By: ';
                                 
                    var i = 0;
                    var aut_cit = [];
                    var separator = '';

                    $.each(coa_array, function(k, v)
                    {
                      i++;
                      var detect_comma = v.split(",");
                      // var detect_comma = ex.split(",");
                      if(detect_comma.length > 1){
                        // get last name
                        var lastname = detect_comma[0].split(' ').pop(); 
                        // omit middle name with period
                        var lastIndex = detect_comma[0].lastIndexOf(".")
                        // get first names
                        var first_names = detect_comma[0].substring(0, lastIndex - 1);
                        // get first names
                        var get_first_names = first_names.split(' ');
                        // store first name initials
                        var initial = '';
                        $.each(get_first_names, function(key,val)
                        {
                          if(val.length != 0)
                          {
                            if(val.indexOf('-') >= 0)
                            {
                              var dash_name = val.split('-');
                              initial += dash_name[0].charAt(0) + '.' + dash_name[1].charAt(0) + '.';
                            }
                            else{
                              initial += val.charAt(0) + '.';
                            }
                          }
                        });
                      }else{
                        // get last name
                        var lastname = v.split(' ').pop();
                        // omit middle name with period
                        if(v.indexOf('.') >= 0)
                        {
                          var lastIndex = v.lastIndexOf(".")
                          var first_names = v.substring(0, lastIndex - 1); 
                          var get_first_names = first_names.split(' ');
                        }
                        else{
                          
                          var get_first_names = v.split(' ');
                          get_first_names.pop(); 
                        }
                        
                        // store first name initials
                        var initial = '';
                        $.each(get_first_names, function(key,val)
                        {
                          if(val.length != 0)
                          {
                            if(val.indexOf('-') >= 0)
                            {
                              var dash_name = val.split('-');
                              initial += dash_name[0].charAt(0) + '.' + dash_name[1].charAt(0) + '.';
                            }
                            else{
                              initial += val.charAt(0) + '.';
                            }
                          }
                        });
                      }

                      var final;

                    
                      if(i > 1)
                      {
                        if(i == coa_array.length)
                        {
                          final = ' & ' + lastname + ', ' + initial;
                        }
                        else
                        {
                          final = lastname + ', ' + initial;
                        }
                      }else{
                        final = lastname + ', ' + initial;
                      }
                
                      aut_cit.push(final);


                     if( i < coa_array.length) { separator = ' | '; }else{ separator = '';}

                      html += '<a href="javascript:void(0);" class="mb-1 text-primary text-capitalize" onclick="author_details(\''+val.art_jor_id+'\',\''+v+'\');">'+v+'</a>' + separator;

                     
                    });
                 
                    var title_cit = (val.art_title).toLowerCase();
                    var final_title_cit =    title_cit.substr(0,1).toUpperCase() + title_cit.substr(1);

                    cite = aut_cit + ' ('+ val.art_year +'). ' + final_title_cit + '. NRCP Research Journal, Volume ' + val.jor_volume + ', ' + issue + ', ' + val.art_page;

                    html +=  "<br/><small>Keywords: " + click_keyword(val.art_keywords) + "</small><br/> \
                              \
                              <div class='mb-2'> \
                              <span class='badge badge badge-light' data-toggle='tooltip' data-placement='top' title='File Size'> \
                              <span class='oi oi-paperclip'></span> " + file_size(val.art_id) + "</span> \<span class='badge badge badge-light mr-1' data-toggle='tooltip' data-placement='top' title='Full Text Downloads'> \
                              <span class='oi oi-data-transfer-download'></span>" + clients_count(val.art_id) + "</span> \
                              <span class='badge badge badge-light mr-1' data-toggle='tooltip' data-placement='top' title='Abstract Hits'> \
                              <span class='oi oi-eye'></span> " + hits_count(val.art_id) + "</span> \
                              <span class='badge badge badge-light mr-1' data-toggle='tooltip' data-placement='top' title='Cited'> \
                              <span class='oi oi-pin'></span>" + cite_count(val.art_id) + "</span></div> \
                              <div class='btn-group mt-5' role='group'> \
                              \
                              <a data-toggle='modal' data-target='#client_modal' class='btn btn-sm btn-primary mr-2 pr-3 pl-3' href='javascript:void(0);' role='button' onclick='get_download_id(\"" + val.art_id + "\")'> \
                                <span class='oi oi-file'></span> Download</a> \
                              <a data-toggle='modal' data-target='#abstract_modal' class='btn btn-sm btn-outline-dark mr-2 pr-3 pl-3'  onclick='get_download_id(\"" + val.art_id + "\",\"hits\",\"" + val.art_abstract_file + "\")' href='javscript:void(0);' role='button'> \
                                <span class='oi oi-eye'></span> Abstract</a> \
                              <a data-toggle='modal' data-target='#citationModal' class='btn btn-sm btn-outline-dark mr-2 pr-3 pl-3' href='javascript:void(0);' role='button' onclick='get_citee_info(\"" + cite.replace(/ /g, '+') + "\",\"" + val.art_id + "\")'> \
                                <span class='oi oi-document'></span> Cite</a> \
                              </div> \
                              </div></div></div>";
                             

                    $('.list-group-articles').append(html);

                  });

                  $('body').loading('stop');
                }
              }
    });
}

/**
 * auto search on clicked keyword
 *
 * @param   {string}  keyword   article keywords
 *
 * @return  {void}           
 */
function click_keyword(keyword)
{
    if(keyword != null)
    {
      var string = keyword;
      var array = string.split(', ');
      var key  = '';
      for (i = 0; i < array.length; i++) {
        key += '<a class="text-primary" href="'+ base_url +'client/ejournal/articles/'+array[i].replace( / /g, '+')+'" target="_blank">'+array[i]+'</a>; ';
      }

      return key;
    }else {
      return 'No keywords available.';
    }
}

/**
 * get journal data, viy (volume, issue, year)
 *
 * @param   {int}  id  journal id
 *
 * @return  {void}      
 */
function viy(id)
{
  $.ajax({
    type:"POST",
    url: base_url + "client/ejournal/get_journal/"+id,
    dataType:"json",
    crossDomain: true,
    success:
            function(data)
            {
              $('#viy').text('');

              $.each(data, function(key, val)
              {

                var iss = (
                  (val.jor_issue == 5) ? 'Special Issue No. 1' :
                  ((val.jor_issue == 6) ? 'Special Issue No. 2' :
                    ((val.jor_issue == 7) ? 'Special Issue No. 3' :
                      ((val.jor_issue == 8) ? 'Special Issue No. 4' : 'Issue ' + val.jor_issue)))
                );
                $('#viy').text('Volume '+val.jor_volume+', '+iss+', '+val.jor_year);
                $('#viy_photo').attr('src',base_url+'assets/uploads/cover/'+val.jor_cover);
                var desc = (val.jor_description != null) ? val.jor_description : '' ;
                $('#viyd').text(desc);
              });
            }
  });
}

/**
 * get article id on viewing abstract
 *
 * @param   {int}  id    article id
 * @param   {string}  flag    
 * @param   {file}  file  pdf file
 *
 * @return  {void}        
 */
function get_download_id(id, flag=null, file=null, logged_in = null)
{
  $('#clt_journal_downloaded_id').val(id);

  if(flag == 'hits')
  {
    article_id = id;
    $('#abstract_modal').modal('toggle');
    // $('#abstract_view').removeAttr('src');
    // $('#abstract_view').attr('src', );
    $('#abstract_view').replaceWith($('#abstract_view').clone().attr('src',base_url+"assets/uploads/abstract/"+file+'#toolbar=0&navpanes=0&scrollbar=0'));

  }else if(flag == 'top') {
    // $('#top_modal').modal('toggle');
    // $('#client_modal').modal('toggle');
    // $.ajax({
    //   type:"POST",
    //   url: base_url + "client/ejournal/abstract_hits/"+id,
    //   async: false
    // });
  }
}

function save_hits(id){
  console.log("🚀 ~ save_hits ~ id:", id)
  $.ajax({
    type:"POST",
    url: base_url + "client/ejournal/abstract_hits/"+id,
    async: false
  });
}

/**
 * count total clients who downloaded pdf
 *
 * @param   {int}  id  article id
 *
 * @return  {int}      total number of clients
 */
function clients_count(id)
{
   var jqXHR = $.ajax({
                        type:"GET",
                        url: base_url + "client/ejournal/client_count/"+id,
                        async:false
                     });

    return jqXHR.responseText;
}

/**
 * count total hits of an article
 *
 * @param   {[type]}  id  article id
 *
 * @return  {int}      total number of hits of an article
 */
function hits_count(id)
{
   var jqXHR = $.ajax({
                        type:"GET",
                        url: base_url + "client/ejournal/hits_count/"+id,
                        async:false
                     });

    return jqXHR.responseText;
}

/**
 * count total citations
 *
 * @param   {[type]}  id  article id
 *
 * @return  {int}      total number of citation 
 */
function cite_count(id)
{
   var jqXHR = $.ajax({
                        type:"GET",
                        url: base_url + "client/ejournal/cite_count/"+id,
                        async:false
                     });

    return jqXHR.responseText;
}

/**
 * get file size of article pdf
 *
 * @param   {int}  id  article id
 *
 * @return  {string}      file size in mb/kb/byte
 */
function file_size(id)
{
  var jqXHR = $.ajax({
                      type:"GET",
                      url: base_url + "client/ejournal/file_size/"+id,
                      async:false
                    });

                    if(jqXHR != ''){
    return 'No file found.';
                    }else{
    return jqXHR.responseText;
                    }

}

/**
 * get co-authors of an article
 *
 * @param   {int}  id  article id
 *
 * @return  {void} 
 */
function get_coauthors(id)
{
  var coa = [];

  $.ajax({
          type:"POST",
          url: base_url + "client/ejournal/get_coauthors/"+id,
          dataType:"json",
          async: false,
          crossDomain: true,
          success:
                  function(data)
                  {
                    if(data.length > 0)
                    {
                      $.each(data, function(key, val)
                      {
                        coa.push(val.coa_name);
                      });
                    }

                    coas = coa.join(',& ');
                  }
        });
  }

/**
 * view article data listed under top articles in home page
 *
 * @param   {int}  id    article id
 * @param   {string}  flag  
 * @param   {string}  file  pdf file
 *
 * @return  {void}        
 */
function top_article(id, flag, file, modalTitle)
{
  $('#top_abstract_view').attr('src', base_url+('assets/uploads/abstract/'+file+'#toolbar=0&navpanes=0&scrollbar=0&menubar=0'));
  $('#top_download_pdf').attr('onClick', 'get_download_id('+id+')');
  $('#top_modal .modal-title').text(modalTitle);
  $('#top_modal').modal('toggle');
  
}

/**
 * search selected filter and entered keyword
 *
 * @param   {string}  keyword  author/co-author, title, keywords
 * @param   {int}  filter   author/co-author, title, keywords
 *
 * @return  {void}           [return description]
 */
// function click_top_search(keyword, filter)
// {
//   $('#search_ejournal2').val(keyword.replace('+', / /g));
//   $('#search_filter2').val(filter);
//   var url = base_url + "client/ejournal/search/"+filter+"/"+keyword;
//   window.location.replace(url);
// }

/**
 * get author details
 *
 * @param   {int}  id    article id
 * @param   {string}  name  author name
 *
 * @return  {void}        
 */
function author_details(id, name)
{
  $('#acoa_details_modal').modal('toggle');
  $('#acoa_details_modal .modal-title').text(name);

  $.ajax({
          type:"POST",
          url: base_url + "client/ejournal/get_acoa_details/"+id+"/"+ name.replace(/ /g, '+'),
          dataType:"json",
          crossDomain: true,
          success:
                  function(data)
                  {
                    $('#acoa_details_modal .modal-body').empty();
                    var list = '';
                    var aff = '';
                    var mail = '';


                      $.each(data.authors,function(key, val)
                      {
                         get_aff   = ('coa_affiliation' in val) ? val.coa_affiliation : val.art_affiliation ;
                         get_mail  = ('coa_email' in val) ? val.coa_email : val.art_email;
                        //  aff       = (get_aff == '' || get_aff == null) ? 'Affiliation unavailable'  : get_aff;
                        //  mail      = (get_mail == '' || get_mail == null || get_mail.length <= 1)  ? 'Email unavailable' : get_mail;
                         aff       = (typeof(val.art_affiliation) != "undefined" && val.art_affiliation !== null) ? val.art_affiliation : 'No Affiliation';
                         mail      = (typeof val.art_email !== 'undefined' && val.art_email)  ?  val.art_email : 'No Email Address';


                        $('#acoa_details_modal .modal-body').html(`<div class="d-flex align-items-baseline gap-1">
                                                                      <span class="oi oi-flag text-secondary me-2"></span>${aff}
                                                                    </div>
                                                                    <div class="d-flex align-items-baseline gap-1">
                                                                      <span class="oi oi-envelope-closed text-secondary me-2"></span>${mail}
                                                                    </div>`);

                        // list += '<li><a href="javascript:void(0);" class="text-primary" onclick="top_article(\''+val.art_id+'\',\'top\',\''+val.art_abstract_file+'\')">'+val.art_title+'</a></li>';
                      });
                

                      $.each(data.coas, function(key, val)
                      {
                        //  get_aff   = ('coa_affiliation' in val) ? val.coa_affiliation : val.art_affiliation ;
                        //  get_mail  = ('coa_email' in val) ? val.coa_email : val.art_email;
                        //  aff       = (get_aff == '' || get_aff == null) ? 'Affiliation unavailable'  : get_aff;
                        //  mail      = (get_mail == '' || get_mail == null) ? 'Email unavailable' : get_mail;
                         aff       = (typeof(val.coa_affiliation) != "undefined" && val.coa_affiliation !== null)  ? val.coa_affiliation : 'No ffiliation';
                         mail      = (typeof(val.coa_email) != "undefined" && val.coa_email !== null)  ?  val.coa_email : 'No Email Address';

                        $('#acoa_details_modal .modal-body').html(`<div class="d-flex align-items-baseline gap-1">
                                                                      <span class="oi oi-flag text-secondary me-2"></span>${aff}
                                                                    </div>
                                                                    <div class="d-flex align-items-baseline gap-1">
                                                                      <span class="oi oi-envelope-closed text-secondary me-2"></span>${mail}
                                                                    </div>`);

                        // list += '<li><a href="javascript:void(0);" class="text-primary"  onclick="top_article(\''+val.art_id+'\',\'top\',\''+val.art_abstract_file+'\')">'+val.art_title+'</a></li>';
                      });


                    $('#acoa_details_modal .modal-footer .btn').attr('href', base_url + "client/ejournal/articles?search="+ name.replace(/ /g, '+'));
                    // $('#acoa_details_modal .modal-footer .btn').html('<a href="' +  + '" type="button" class="btn main-link font-weight-bold w-100 me-1 d-flex align-items-center">Show related articles<i class="oi oi-chevron-right ms-1" style="font-size: .7rem"></i></a>');
                    // $('#acoa_details_modal p').html('<hr><p>Related Articles</p><ol class="pl-0 ml-3">'+list+'</ol');
                    }
        });
}

/**
 * get author details from search result page
 *
 * @param   {int}  id    article id
 * @param   {string}  name  autrho name
 *
 * @return  {void}        
 */
function author_details_search(id, name, url_segment)
{
  $('#acoa_details_modal_search').modal('toggle');
  $('#acoa_details_modal_search .modal-title').text(name);

  $.ajax({
          type:"POST",
          url: base_url + "client/ejournal/get_acoa_details/"+id+"/"+ name.replace(/ /g, '+'),
          dataType:"json",
          crossDomain: true,
          success:
                  function(data)
                  {
                    $('#acoa_details_modal_search .modal-body').empty();
                    var list = '';
                    var aff = '';
                    var mail = '';


                    // if(data.authors.length > 0){
                      $.each(data.authors,function(key, val)
                      {
                        //  get_aff   = ('coa_affiliation' in val) ? val.coa_affiliation : val.art_affiliation ;
                        //  get_mail  = ('coa_email' in val) ? val.coa_email : val.art_email;
                        //  aff       = (get_aff == '' || get_aff == null) ? 'Affiliation unavailable'  : get_aff;
                        //  mail      = (get_mail == '' || get_mail == null)  ? 'Email unavailable' : get_mail;
                         aff       = (typeof(val.art_affiliation) != "undefined" && val.art_affiliation !== null) ? val.art_affiliation : 'No Affiliation';
                         mail      = (typeof(val.art_email) != "undefined" && val.art_email !== null)  ?  val.art_email : 'No Email Address';

                        $('#acoa_details_modal_search .modal-body').html(`<div class="d-flex align-items-baseline gap-1">
                                                                      <span class="oi oi-flag text-secondary me-2"></span>${aff}
                                                                    </div>
                                                                    <div class="d-flex align-items-baseline gap-1">
                                                                      <span class="oi oi-envelope-closed text-secondary me-2"></span>${mail}
                                                                    </div>`);

                        // list += '<li><a href="javascript:void(0);" class="text-primary" onclick="top_article(\''+val.art_id+'\',\'top\',\''+val.art_abstract_file+'\')">'+val.art_title+'</a></li>';
                      });
                    // }

                    // if(data.coas.length > 0){
                      $.each(data.coas, function(key, val)
                      {
                        //  get_aff   = ('coa_affiliation' in val) ? val.coa_affiliation : val.art_affiliation ;
                        //  get_mail  = ('coa_email' in val) ? val.coa_email : val.art_email;
                        //  aff       = (get_aff == '' || get_aff == null) ? 'Affiliation unavailable'  : get_aff;
                        //  mail      = (get_mail == '' || get_mail == null) ? 'Email unavailable' : get_mail;
                         aff       = (typeof(val.coa_affiliation) != "undefined" && val.coa_affiliation !== null)  ? val.coa_affiliation : 'No ffiliation';
                         mail      = (typeof(val.coa_email) != "undefined" && val.coa_email !== null)  ?  val.coa_email : 'No Email Address';

                         $('#acoa_details_modal_search .modal-body').html(`<div class="d-flex align-items-baseline gap-1">
                                                                       <span class="oi oi-flag text-secondary me-2"></span>${aff}
                                                                     </div>
                                                                     <div class="d-flex align-items-baseline gap-1">
                                                                       <span class="oi oi-envelope-closed text-secondary me-2"></span>${mail}
                                                                     </div>`);

                        // list += '<li><a href="javascript:void(0);" class="text-primary"  onclick="top_article(\''+val.art_id+'\',\'top\',\''+val.art_abstract_file+'\')">'+val.art_title+'</a></li>';
                      });
                    // }

                    if(url_segment == 'advanced'){
                      url_segment += '?search_filter=1&search=';
                    }else{
                      url_segment += '?search=';
                    }


                    $('#acoa_details_modal_search .modal-footer .btn').attr('href', base_url + "client/ejournal/" + url_segment + name.replace(/ /g, '+'));
                    // $('#acoa_details_modal_search .modal-footer').html('<a href="' + base_url + "client/ejournal/" + url_segment + name.replace(/ /g, '+') + '" type="button" class="btn main-link fw-bold w-100 me-1">Show related articles<i class="oi oi-chevron-right text-dark ms-1" style="font-size: .9rem"></i></a>');
                    // $('#acoa_details_modal p').html('<hr><p>Related Articles</p><ol class="pl-0 ml-3">'+list+'</ol');
                    }
        });

 
}

function get_citee_info(value,id) {
  // $('#citationModal p').val('Please provide us with your Full Name and Email Address. Then click SUBMIT to show the APA citation');
  $('#form_citation').show();
  $('#apa_format').val(value);
  $('#cite_value').val(value);
  apa_format = value;
  apa_id = id;
}


function copyToClipboard(values)
{
// alert(unescape(values));
  var tempInput = document.createElement("input");
  tempInput.style = "position: absolute; left: -1000px; top: -1000px";
  tempInput.value = unescape(values);
  document.body.appendChild(tempInput);
  tempInput.select();
  document.execCommand("copy");
  document.body.removeChild(tempInput);
  $(document).on('click','.copy_citation',function() {
    $(this).attr('data-original-title', 'Copied').tooltip('show');
  });

  $('.copy_citation').attr('data-original-title', "Click to copy & paste");
}

function verify_citation(fb_clt_id, source){


    $('#feedbackModal').modal('toggle');
    $('#feedbackModal #fb_usr_id').val(fb_clt_id);
    $('#fb_source').val(source);

}

function verify_feedback(fb_clt_id, source){

  $('#client_modal').modal('toggle');
  $('#feedbackModal').modal('toggle');
  $('#feedbackModal #fb_usr_id').val(fb_clt_id);
  $('#fb_source').val(source);

  // var jqXHR = $.ajax({
  //     type: "GET",
  //     url: base_url + "admin/feedback/verify/" + fb_clt_id,
  //     async: false,
  //     crossDomain: true,
  // });

  // console.log(fb_clt_id);
  // // var stat = jqXHR.responseText.replace(/\"/g, '');
  // var stat = jqXHR.responseText;
  // if(stat == 0){
  //   $('#client_modal').modal('toggle');
  //   $('#feedbackModal').modal('toggle');
  //   $('#feedbackModal #fb_usr_id').val(fb_clt_id);
  // }else{
  //   $('#client_modal').modal('toggle');
  //   location.reload();
  // }
}

function copyCitationToClipboard(element){
  $(element).select();
  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val($(element).text()).select();
  document.execCommand("copy");
  $temp.remove();
  save_cite(apa_id);
}

function save_cite(apa_id){
  $.ajax({
    type:"POST",
    url: base_url + "client/ejournal/save_citation/"+apa_id,
    async: false
  });
}



function disableOnSubmit(element, form, action){
  let newButtonText = (action == 'reset') ? 'Submitting' : (action == 'verify' ? 'Verifying' : 'Loading');

  $(element).prop('disabled' ,true);
  $(element).html('<span class="spinner-grow spinner-grow-sm me-1" role="status" aria-hidden="true"></span>' + newButtonText);
  $(form).submit();
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
      // var resendCodeBtnText;

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
          $('#resend_code').attr('href', base_url + 'client/login/resend_login_code/' + refCode);
        }else if(secondToLastSegment == 'new_account_verify_otp'){ // create ejournal client account otp
          $('#resend_code').attr('href', base_url + 'client/signup/resend_new_client_account_code/' + refCode);
        }else{
          $('#resend_code').attr('href', base_url + 'client/signup/author_account_verify_otp/' + refCode);
        }

        
      }

      

  }, 1000);
}

function getCurrentOTP(refCode, otpType){
  console.log("🚀 ~ getCurrentOTP ~ refCode, otpType:", refCode, otpType)
  var currentDate = new Date();
  var otpDate;
  var url = (otpType == 1) ? base_url + "client/login/get_current_otp/" + refCode : base_url + "client/signup/get_current_otp_oprs/" + refCode;
  
  
  $.ajax({
    type: "GET",
    url: url,
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
              $('#resend_code').attr('href', base_url + 'client/login/resend_login_code/' + refCode);
            }else if(secondToLastSegment == 'new_account_verify_otp'){ // create ejournal client account otp
              $('#resend_code').attr('href', base_url + 'client/signup/resend_new_client_account_code/' + refCode);
            }else{ // author_account_verify_otp
              $('#resend_code').attr('href', base_url + 'client/signup/resend_author_account_code/' + refCode);
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

function addSearch(element){
  $(element).html('<span class="fa fa-minus text-danger"></span>');

  $(element).attr('onclick', 'removeSearch(this)');
  let html = `<div class="row mb-3">
                                <div class="col col-3">
                                    <select name="search_filter[]" id="search_filter" class="form-select">
                                        <option value="1">All content</option>
                                        <option value="2">Title</option>
                                        <option value="3">Author</option>
                                        <option value="4">Affiliation</option>
                                        <option value="5">Keywords</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <div class="input-group">
                                        <input type="text" class="form-control rounded" name="search[]" id="advanceSearch" placeholder="Enter search term">
                                        <button class="btn btn-light ms-1 rounded" type="button" id="button-addon2" onclick="addSearch(this)"><span class="fa fa-plus main-link"></span></button>
                                    </div>
                                </div>
                            </div>`;

                            $('#additional_search').append(html);
}

function removeSearch(element){
  $(element).closest('div.row').remove();
  let len = $('input[name="search[]"]').length;
  // if(len > 1){
    $('#advanceSearchBtn').removeClass('disabled');
  // }
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

function clearAdvanceSearch(element){

  var url = window.location.href;
  
  if (url.indexOf('?') !== -1) {
    window.location.href = base_url + '/client/ejournal/advanced';
  } else {
    let formID = $(element).closest('form').attr('id');
    $('#' + formID).trigger('reset');
    $('#advanceSearchBtn').addClass('disabled');
  }
}

function logout(){
  $('#feedbackModal').modal('toggle');
}

// function getUserAccessToken(){

 
//   $.ajax({
//     type: "GET",
//     url: base_url + "/client/login/get_access_token/",
//     crossDomain: true,
//     success: function(data) {
//       if(data != 0){
//         accessToken = data;
//         console.log('start timer');
//       }else{
//         console.log('do not start timer');
//       }
//     },
//     error: function(xhr, status, error) {
//       reject(error);
//     }
//   }); 

// }

// data: {
//   'csrf_test_name': '<?= $this->security->get_csrf_hash(); ?>', // Token
//   'other_data': 'value'
// },

function destroyUserSession(){
  $.ajax({
    type: "POST",
    url: base_url + "client/login/destroy_user_session/" ,
    data: { user_access_token : accessToken },
    success: function(data) {
      // console.log(data);
    }
  });
}