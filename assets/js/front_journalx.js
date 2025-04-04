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
var apa_format;
var apa_id;
var fb_clt_id; //feedback client id
var fn_clt_email; //feedback client email

$(document).ready(function()
{


  //$('#mbsModal').modal('toggle');

  // $('#defaultCheck2').on('click', function(){
    
  //   $(document).scrollTop($(this).parent().next().offset().top);
  //   // $(this).parent().next() // this is the next div container.
  //   return false; // prevent anchor
  // });

  // $("input[type=radio]").on('change', function() {
  //   $(document).scrollTop($(this).parent().next().offset().top);
  //   // $(this).parent().next() // this is the next div container.
  //   return false; // prevent anchor
  
  // });

  $('body').tooltip({ selector: '[data-toggle=tooltip]' });

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

                                $.ajax({
                                  url : base_url + "client/ejournal/download_pdf/",
                                  data : formdata ? formdata :form.serialize(),
                                  cache : false,
                                  contentType : false,
                                  processData : false,
                                  type : 'POST',
                                  success : function(data,textStatus,jqXHR){
                                    // console.log(data);
                                    // return false;

                                    fb_clt_id = parseInt(data);
                       
                                    $('#form-client').remove();

                                    // $('#client_modal').modal('toggle');
                                    $('#client_modal .modal-body').append('<div class="alert alert-success" role="alert"> \
                                    <span class="oi oi-check"></span> Full Text PDF sent! Please check your email. \
                                    </div><h5 class="text-center"></h5>\
                                    <button class="btn btn-light w-100" id="btn_feedback">Close</button>');
                                    $('#client_modal .modal-footer').hide();


                                    // $.notify({
                                    // icon: 'oi oi-check',
                                    // message: 'Full Text PDF sent! Please check your email.'
                                    // },{
                                    //   type: 'primary',
                                    //   timer: 3000,
                                    // });
                                  }
                                });

                                
                                $('#form-client')[0].reset();
                                 $('#btn_submit_client_info').hide();
                                 $('#btn_cancel_client_info').hide();
                                 $('#alert-processing').removeClass('invisible');
                               }
  });

  // search ejournal and redirect to result page
  $('#search_ejournal').keypress(function(e)
  {
    if(e.which == 13)
    {
      if($(this).val() != '')
      {
        var keyword = $(this).val();
        var filter  = $('#search_filter').val();
        // window.open(base_url + "client/Ejournal/search/"+filter+"/"+encodeURIComponent(keyword));
        window.open(base_url + "client/Ejournal/search/"+filter+"/"+escape(keyword));
      }
    }
  });

  // search eournal in result page
  $('#search_ejournal2').keypress(function(e)
  {
    if(e.which == 13)
    {
      if($(this).val() != '')
      {
        var keyword = $(this).val();
        var filter  = $('#search_filter2').val();
        // var url     = base_url + "client/Ejournal/search/"+filter+"/"+encodeURIComponent(keyword);
        var url     = base_url + "client/ejournal/search/"+filter+"/"+escape(keyword);
        window.location.replace(url);
      }
    }
  });

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
        // console.log(formdata);
        $.ajax({
            type: "POST",
            url: base_url + 'admin/feedback/submit/3',
            data:  formdata,
            cache: false,
            crossDomain: true,
            success: function(data) {
                $('#feedback_form').remove();
                $('#feedback_form .btn-primary').hide();

                var thanks = '<p class="text-center h2">Thank you for your feedback.</p> \
                              <button class="btn btn-light w-100" data-dismiss="modal" onClick="window.location.reload();">Close</button>';
                         
                
                $(thanks).hide().appendTo("#feedbackModal .modal-body").fadeIn();

            }
        });
      }
  });
});

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
      url: base_url + "client/Ejournal/get_articles/"+id,
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
                            '<h5 class="mb-1 text-primary">'+val.art_title+'</h5>'+
                           '</div>By ';
                                 
                    var i = 0;
                    var aut_cit = [];
               

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
                   
                      html += '<a href="javascript:void(0);" class="mb-1 text-dark text-capitalize" onclick="author_details(\''+val.art_jor_id+'\',\''+v+'\');">'+v+'</a>; ';
                    });
                 
                    var title_cit = (val.art_title).toLowerCase();
                    var final_title_cit =    title_cit.substr(0,1).toUpperCase() + title_cit.substr(1);

                    cite = aut_cit + ' ('+ val.art_year +'). ' + final_title_cit + '. NRCP Research Journal, Volume ' + val.jor_volume + ', ' + issue + ', ' + val.art_page;

                    html +=  "<br/><small><strong>Keywords:</strong> " + click_keyword(val.art_keywords) + "</small><br/> \
                              \
                              <div class='mb-2'><span class='badge badge badge-secondary mr-1' data-toggle='tooltip' data-placement='top' title='Full Text Downloads'> \
                              <span class='oi oi-data-transfer-download'></span>" + clients_count(val.art_id) + "</span> \
                              <span class='badge badge badge-secondary mr-1' data-toggle='tooltip' data-placement='top' title='Abstract Hits'> \
                              <span class='oi oi-eye'></span> " + hits_count(val.art_id) + "</span> \
                              <span class='badge badge badge-secondary' data-toggle='tooltip' data-placement='top' title='File Size'> \
                              <span class='oi oi-paperclip'></span> " + file_size(val.art_id) + "</span> \
                              <span class='badge badge badge-secondary mr-1' data-toggle='tooltip' data-placement='top' title='Cited'> \
                              <span class='oi oi-pin'></span>" + cite_count(val.art_id) + "</span></div> \
                              <div class='btn-group' role='group'> \
                              \
                              <a data-toggle='modal' data-target='#abstract_modal' class='btn btn-sm btn-outline-primary'  onclick='get_download_id(\"" + val.art_id + "\",\"hits\",\"" + val.art_abstract_file + "\")' href='javscript:void(0);' role='button'> \
                                <span class='oi oi-eye'></span> View Abstract</a> \
                              <a data-toggle='modal' data-target='#client_modal' class='btn btn-sm btn-outline-danger' href='javascript:void(0);' role='button' onclick='get_download_id(\"" + val.art_id + "\")'> \
                                <span class='oi oi-file'></span> Request Full Text PDF</a> \
                              <a data-toggle='modal' data-target='#citationModal' class='btn btn-sm btn-outline-dark' href='javascript:void(0);' role='button' onclick='get_citee_info(\"" + escape(cite) + "\",\"" + val.art_id + "\")'> \
                                <span class='oi oi-pin'></span> Cite This Paper</a> \
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
        key += '<a class="text-dark" href="'+ base_url +'client/Ejournal/search/3/'+array[i]+'" target="_blank">'+array[i]+'</a>; ';
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
    url: base_url + "client/Ejournal/get_journal/"+id,
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
function get_download_id(id, flag=null, file=null)
{
  $('#clt_journal_downloaded_id').val(id);

  if(flag == 'hits')
  {
    $('#abstract_modal').modal('toggle');
    // $('#abstract_view').removeAttr('src');
    // $('#abstract_view').attr('src', );
    $('#abstract_view').replaceWith($('#abstract_view').clone().attr('src',base_url+"assets/uploads/abstract/"+file+'#toolbar=0&navpanes=0&scrollbar=0'));
    $.ajax({
      type:"POST",
      url: base_url + "client/Ejournal/abstract_hits/"+id,
      async: false
    });
  }
  else if(flag == 'top')
  {
    // $('#top_modal').modal('toggle');
    // $('#client_modal').modal('toggle');
    // $.ajax({
    //   type:"POST",
    //   url: base_url + "client/Ejournal/abstract_hits/"+id,
    //   async: false
    // });
  }
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
                        url: base_url + "client/Ejournal/client_count/"+id,
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
                        url: base_url + "client/Ejournal/hits_count/"+id,
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
                        url: base_url + "client/Ejournal/cite_count/"+id,
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
                      url: base_url + "client/Ejournal/file_size/"+id,
                      async:false
                    });

    return jqXHR.responseText;
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
          url: base_url + "client/Ejournal/get_coauthors/"+id,
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
function top_article(id, flag, file)
{
  $('#top_modal').modal('toggle');
  $('#top_abstract_view').attr('src', base_url+('assets/uploads/abstract/'+file+'#toolbar=0&navpanes=0&scrollbar=0&menubar=0'));
  $('#top_download_pdf').attr('onClick', 'get_download_id('+id+')');
  
}

/**
 * search selected filter and entered keyword
 *
 * @param   {string}  keyword  author/co-author, title, keywords
 * @param   {int}  filter   author/co-author, title, keywords
 *
 * @return  {void}           [return description]
 */
function click_top_search(keyword, filter)
{
  $('#search_ejournal2').val(decodeURIComponent(keyword));
  $('#search_filter2').val(filter);
  var url = base_url + "client/Ejournal/search/"+filter+"/"+keyword;
  window.location.replace(url);
}

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
          url: base_url + "client/Ejournal/get_acoa_details/"+id+"/"+ name.replace(/ /g, '_'),
          dataType:"json",
          crossDomain: true,
          success:
                  function(data)
                  {
                    $('#acoa_details_modal small').empty();
                    var list = '';

                    $.each(data.authors,function(key, val)
                    {
                      var get_aff   = ('coa_affiliation' in val) ? val.coa_affiliation : val.art_affiliation ;
                      var get_mail  = ('coa_email' in val) ? val.coa_email : val.art_email;
                      var aff       = (get_aff == '' || get_aff == null) ? 'Affiliation unavailable'  : get_aff;
                      var mail      = (get_mail == '' || get_mail == null)  ? 'Email unavailable' : get_mail;

                      $('#acoa_details_modal small').html('<span class="oi oi-flag"></span> '+aff+ '<br/>'+
                                                          '<span class="oi oi-envelope-closed"></span> '+mail);

                      list += '<li><a href="javascript:void(0);" class="text-dark" onclick="top_article(\''+val.art_id+'\',\'top\',\''+val.art_abstract_file+'\')">'+val.art_title+'</a></li>';
                    });

                    $.each(data.coas, function(key, val)
                    {
                      var get_aff   = ('coa_affiliation' in val) ? val.coa_affiliation : val.art_affiliation ;
                      var get_mail  = ('coa_email' in val) ? val.coa_email : val.art_email;
                      var aff       = (get_aff == '' || get_aff == null) ? 'Affiliation unavailable'  : get_aff;
                      var mail      = (get_mail == '' || get_mail == null) ? 'Email unavailable' : get_mail;

                      $('#acoa_details_modal small').html('<span class="oi oi-flag"></span> '+aff+ '<br/>'+
                                                          '<span class="oi oi-envelope-closed"></span> '+mail);

                      list += '<li><a href="javascript:void(0);" class="text-dark"  onclick="top_article(\''+val.art_id+'\',\'top\',\''+val.art_abstract_file+'\')">'+val.art_title+'</a></li>';
                    });

                    $('#acoa_details_modal p').html('<hr><p>Related Articles</p><ol class="pl-0 ml-3">'+list+'</ol');
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
function author_details_search(id, name)
{
  $('#acoa_details_modal_search').modal('toggle');
  $('#acoa_details_modal_search .modal-title').text(name);

  $.ajax({
          type:"POST",
          url: base_url + "client/Ejournal/get_acoa_details/"+id+"/"+escape(name),
          dataType:"json",
          crossDomain: true,
          success:
                  function(data)
                  {
                    $('#acoa_details_modal_search small').empty();
                    var list = '';

                    $.each(data.authors,function(key, val)
                    {
                      var get_aff   = ('coa_affiliation' in val) ? val.coa_affiliation : val.art_affiliation ;
                      var get_mail  = ('coa_email' in val) ? val.coa_email : val.art_email;
                      var aff       = (get_aff == '') ? 'No affiliation'  : get_aff;
                      var mail      = (get_mail == '') ? 'No email' : get_mail;

                      $('#acoa_details_modal_search small').html('<span class="oi oi-flag"></span> '+aff+ '<br/>'+
                                                                 '<span class="oi oi-envelope-closed"></span> '+mail);

                      list += '<li><a href="javascript:void(0);" class="text-dark" onclick="get_download_id(\''+val.art_id+'\',\'hits\',\''+val.art_abstract_file+'\')">'+val.art_title+'</a></li>';
                    });

                    $.each(data.coas,function(key,val)
                    {
                      var get_aff   = ('coa_affiliation' in val) ? val.coa_affiliation : val.art_affiliation ;
                      var get_mail  = ('coa_email' in val) ? val.coa_email : val.art_email;
                      var aff       = (get_aff == '') ? 'No affiliation'  : get_aff;
                      var mail      = (get_mail == '') ? 'No email' : get_mail;

                      $('#acoa_details_modal_search small').html('<span class="oi oi-flag"></span> '+aff+ '<br/>'+
                                                                 '<span class="oi oi-envelope-closed"></span> '+mail);

                      list += '<li><a href="javascript:void(0);" class="text-dark"  onclick="get_download_id(\''+val.art_id+'\',\'hits\',\''+val.art_abstract_file+'\')">'+val.art_title+'</a></li>';
                    });

                    $('#acoa_details_modal_search p').html('<hr><p>Related Articles</p><ol class="pl-0 ml-3">'+list+'</ol');
                  }
        });
}

function get_citee_info(value,id) {

  $('#citationModal p').val('Please provide us with your Full Name and Email Address. Then click SUBMIT to show the APA citation');
  $('#form_citation').show();
  $('#apa_format').val('');
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
}