<?php error_reporting(0);?>

<div class="container-fluid mt-3 p-4">
    <div class="row pt-3">
        <div class="col col-3 p-3">
            <a class="btn btn-link main-link" href="<?=base_url('/client/ejournal/articles')?>">View all articles</a>
        </div>
        <div class="col col-7 p-3">
            <h3>Advanced search</h3>

            <div class="accordion mb-3" id="accordionPanelsStayOpenExample">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="panelsStayOpen-headingOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                        Edit Search
                    </button>
                    </h2>
                    
                    <?php $results = (array_key_exists('authors',$result)) ? array_merge($result['authors'],$result['coas']) : $result;?>
                 
                    <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse <?=(count($results) > 0) ? '' : 'show' ?>" aria-labelledby="panelsStayOpen-headingOne">
                    <div class="accordion-body">
                        <?=form_open('client/ejournal/advanced', ['method' => 'get', 'id' => 'advancedSearchForm'])?>
                            <div class="row mb-3">
                                <div class="col col-3">
                                    <select name="search_filter" id="search_filter" class="form-select">
                                        <option value="1"  <?= ($filter == 1) ? 'selected' : ''?> >All content</option>
                                        <option value="2"  <?= ($filter == 2) ? 'selected' : ''?>>Title</option>
                                        <option value="3"  <?= ($filter == 3) ? 'selected' : ''?>>Author</option>
                                        <option value="4"  <?= ($filter == 4) ? 'selected' : ''?>>Affiliation</option>
                                        <option value="5"  <?= ($filter == 5) ? 'selected' : ''?>>Keywords</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <input type="text" class="form-control" name="search" id="advanceSearch" placeholder="Enter search term" value="<?=$search?>">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col col-3 align-self-center text-end">Volume:</div>
                                <div class="col">
                                    <select name="jor_volume" id="jor_volume" class="form-select">  
                                        <option value=''>Select Volume</option>
                                        <?php foreach($journals as $row):?>    
                                            <option value="<?=$row->jor_volume?>" <?= ($volume == $row->jor_volume) ? 'selected' : ''?> ><?=$row->jor_volume?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col col-3 align-self-center text-end">Issue:</div>
                                <div class="col">
                                    <select name="jor_issue" id="jor_issue" class="form-select">
                                        <option value=''>Select Issue</option>
                                        <option value="1" <?= ($issue == 1) ? 'selected' : ''?>>1</option>
                                        <option value="2" <?= ($issue == 2) ? 'selected' : ''?>>2</option>
                                        <option value="3" <?= ($issue == 3) ? 'selected' : ''?>>3</option>
                                        <option value="4" <?= ($issue == 4) ? 'selected' : ''?>>4</option>
                                        <option value="5" <?= ($issue == 5) ? 'selected' : ''?>>Special Issue No. 1</option>
                                        <option value="6" <?= ($issue == 6) ? 'selected' : ''?>>Special Issue No. 2</option>
                                        <option value="7" <?= ($issue == 8) ? 'selected' : ''?>>Special Issue No. 3</option>
                                        <option value="8" <?= ($issue == 9) ? 'selected' : ''?>>Special Issue No. 4</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col col-3 align-self-center text-end">Year:</div>
                                <div class="col"> 
                                    <select name="jor_year" id="jor_year" class="form-select">
                                        <option value=''>Select Year</option>
                                        <?php foreach($years as $row):?>    
                                            <option value="<?=$row->jor_year?>" <?= ($year == $row->jor_year) ? 'selected' : ''?> ><?=$row->jor_year?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row mt-5">
                                <div class="col col-3"></div>
                                <div class="col">
                                    <button type="submit" class="btn main-btn w-50 disabled" id="advanceSearchBtn">Search</button>
                                </div>
                            </div>
                            
                        <?=form_close()?>
                    </div>
                    </div>
                </div>
                </div>




                <?php if(count($results) > 0){ ?>
                    <div class="row mb-0 pb-0">
                        <div class="col col-6 d-flex align-items-end">
                            <?php if($search){?>
                                <div class="h6">
                                    <span class="main-link fw-bold"><?=(($per_page * $page - 10) + 1)?> - <?=(($per_page * $page - 10) + 1) + (count($results) - 1)?></span> of <span class="main-link fw-bold"><?=$total_rows;?></span> result(s) of <span class="main-link fw-bold">'<?=str_replace('%C3%B1','ñ',str_replace('+',' ',$search));?>'</span>
                                </div>
                            <?php }else{ ?>
                                <div class="h6">
                                    <span class="main-link fw-bold"><?=(($per_page * $page - 10) + 1)?> - <?=(($per_page * $page - 10) + 1) + (count($results) - 1)?></span> of <span class="main-link fw-bold"><?=$total_rows;?></span> result(s)
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col col-6">
                                <ul class="pagination my-custom-pagination justify-content-end p-0 mb-2">
                                    <?php echo $pagination; ?>
                                </ul>
                        </div>
                    </div>
                    <hr class="mt-2 pb-2">
                <?php } ?>
                    
                <?php $results = $result;
                        $c = 1;
                        foreach($results as $res):

                        $title = preg_replace("/\p{L}*?".preg_quote(str_replace("+"," ",$search))."\p{L}*/ui", "<b>$0</b>", $res->art_title);
                        $author = preg_replace("/\p{L}*?".preg_quote(str_replace("%20"," ",$search))."\p{L}*/ui", "<b>$0</b>", $res->art_author);
                        $affiliation = preg_replace("/\p{L}*?".preg_quote(str_replace("%20"," ",$search))."\p{L}*/ui", "<b>$0</b>", $res->art_affiliation);
                        $keywords = preg_replace("/\p{L}*?".preg_quote(str_replace("+"," ",$search))."\p{L}*/ui", "<b>$0</b>", $res->art_keywords);
                        $file =  $res->art_abstract_file;
                        $get_cover = $this->Search_model->get_cover($res->art_jor_id);
                        $cover = ($get_cover > 0) ? base_url('assets/uploads/cover/'.$get_cover) : base_url('assets/images/unavailable.jpg');
                        $coas  = $this->Search_model->get_coauthors($res->art_id);
                        $coa =  $this->Client_journal_model->get_author_coauthors($res->art_id);
                        
                        $jor_id = $res->art_jor_id;
                        $pdf = $this->Client_journal_model->count_pdf($res->art_id);
                        $abs = $this->Client_journal_model->count_abstract($res->art_id);
                        $citations = $this->Client_journal_model->count_citation($res->art_id);
                        $pages = $res->art_page;
                        $issn = $res->jor_issn;
                        $vol = $res->jor_volume;
                        $issue = (
                            ($res->jor_issue == 5) ? 'Special Issue No. 1' :
                            (($res->jor_issue == 6) ? 'Special Issue No. 2' :
                                (($res->jor_issue == 7) ? 'Special Issue No. 3' :
                                    (($res->jor_issue == 8) ? 'Special Issue No. 4' : 'Issue ' . $res->jor_issue)))
                        );
                        $cite =  $this->Client_journal_model->get_citation($res->art_id) . ' ('. $res->art_year .'). ' . ucfirst(strtolower($res->art_title)) . '. NRCP Research Journal, Volume ' . $res->jor_volume . ', ' . $issue . ', ' . $res->art_page;


                        $server_dir = '/var/www/html/ejournal/assets/uploads/pdf/';
                        $get_file = filesize($server_dir.$res->art_full_text_pdf);


                        // $get_file = filesize($_SERVER['DOCUMENT_ROOT'].'/ejournal/assets/uploads/pdf/'.$res->art_full_text_pdf);

                        if ($get_file >= 1048576){
                        $fsize = round($get_file / 1024 / 1024,1) . ' MB';
                        } elseif($get_file >= 1024) {
                        $fsize = round($get_file / 1024,1) . ' KB';
                        } else {
                        $fsize = round($get_file,1) . ' bytes';
                        }

                        $coa_arr = (explode(",& ",$coa));


                    ?>


                
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <img class="me-3" src="<?=$cover;?>" height="200" width="150" alt="Loading image">
                    </div>
                    <div class="flex-grow-1 ms-2">
                        <p class="mt-0 text-dark mb-0"><?php ; echo $title;?></p>
                        <?php $i = 0; foreach($coa_arr as $cr):?>
                        <?php $cc = preg_replace("/\p{L}*?".preg_quote(str_replace("+"," ",$search))."\p{L}*/ui", "<b>$0</b>", $cr);?>
                        <a href="javascript:void(0);" class="main-link fs-6"
                            onclick="author_details_search('<?=$jor_id;?>','<?=$cr;?>')"><?=$cc;?></a>
                            
                        <?php if($i < (count($coa_arr) - 1)) echo '<span class="font-italic text-muted ">|</span>'; ?>
                        <?php $i++; ?>
                        <?php endforeach;?>
                        <div class="text-muted mt-3 small">Keywords:
                            <?php $this->Client_journal_model->click_keyword($keywords);?></div>

                        
                        <div class="text-muted mt-1 small">Pages: <?=$pages?></div>
                        
                        <div class="text-muted mt-1 small"><?=$res->art_year?></div>
                        
                        <div class="text-muted mt-1 small"><a class="text-muted" href="<?= base_url('/client/ejournal/get_articles/'.$vol.'/'.$jor_id.'');?>">Volume <?=$res->jor_volume . ' ' . $issue?></a></div>
                
                        <div class="d-flex justify-content-between align-items-center">
                            <div class='mb-2 mt-2'>
                                <!-- <span class="badge bg-light text-dark" data-toggle="tooltip"
                                    data-placement="top" title="File Size">
                                    <span class="oi oi-paperclip"></span> <?=$fsize?></span> -->
                                <span class="badge bg-light text-dark" data-toggle="tooltip"
                                    data-placement="top" title="Full Text PDF Requests"><span
                                        class="oi oi-data-transfer-download"></span>
                                    <?=$pdf?></span>
                                <span class="badge bg-light text-dark" data-toggle="tooltip"
                                    data-placement="top" title="Abstract Hits"><span
                                        class="oi oi-eye"></span> <?=$abs?></span>
                                <span class="badge bg-light text-dark" data-toggle="tooltip"
                                    data-placement="top" title="Cited"><span class="oi oi-document"></span>
                                    <?=$citations?></span>
                            </div>
                            <div class="d-flex gap-3">
                                <a data-bs-toggle="modal" data-bs-target="#client_modal"
                                    class="main-link text-decoration-underline" href="javascript:void(0);"
                                    role="button" onclick="get_download_id(<?=$res->art_id?>)">
                                    <span class="oi oi-file"></span> Download</a>
                                <a class="main-link text-decoration-underline"
                                    onclick="get_download_id(<?=$res->art_id?>,'hits','<?=$file?>')"
                                    href="javascript:void(0);" role="button">
                                    <span class="oi oi-eye"></span> Abstract</a>
                                <a data-bs-toggle="modal" data-bs-target="#citationModal"
                                    class="main-link " href="javascript:void(0);"
                                    role="button"
                                    onclick="get_citee_info('<?=addslashes($cite)?>','<?=$res->art_id?>')">
                                    <span class='oi oi-document'></span> Cite this article</a>
                            </div>
                        </div>
                    </div>
                </div>

                <?php endforeach; ?>
                
                <?php if(count($results) > 0){ ?>
                    <hr class="pb-2">
                    <div class="row">
                        <div class="col col-6 d-flex align-items-start">
                            <?php if($search){?>
                                <div class="h6">
                                    <span class="main-link fw-bold"><?=(($per_page * $page - 10) + 1)?> - <?=(($per_page * $page - 10) + 1) + (count($results) - 1)?></span> of <span class="main-link fw-bold"><?=$total_rows;?></span> result(s) of <span class="main-link fw-bold">'<?=str_replace('%C3%B1','ñ',str_replace('+',' ',$search));?>'</span>
                                </div>
                            <?php }else{ ?>
                                <div class="h6">
                                    <span class="main-link fw-bold"><?=(($per_page * $page - 10) + 1)?> - <?=(($per_page * $page - 10) + 1) + (count($results) - 1)?></span> of <span class="main-link fw-bold"><?=$total_rows;?></span> result(s)
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col col-6">
                                <ul class="pagination my-custom-pagination justify-content-end p-0 mb-2">
                                    <?php echo $pagination; ?>
                                </ul>
                        </div>
                    </div>
                <?php } ?>

                <?php if(count($results) == 0 && $search != null){ ?>
                    <div class="alert alert-secondary d-flex align-items-center" role="alert">
                        <div>
                        <span class="oi oi-warning"></span> Sorry, no results found for <strong>'<?=str_replace('%C3%B1','ñ',str_replace('+',' ',$search))?>'</strong>. Try entering fewer or broader query terms.
                        </div>
                    </div>
                <?php } ?>
        </div>
        <div class="col col-2 p-3">
            <?php $this->load->view('common/side_panel');?>
        </div>
    </div>

    <div class="mt-5">    
        <a rel="license" href="http://creativecommons.org/licenses/by/4.0/" target="_blank">
            <img alt="Creative Commons License" style="border-width:0" src="https://i.creativecommons.org/l/by/4.0/88x31.png" />
        </a>
        <p>This work is licensed under a <a class="text-dark" rel="license" href="http://creativecommons.org/licenses/by/4.0/" target="_blank">Creative Commons
            Attribution 4.0 International License</a>.
        </p>
    </div>



    <!-- PDF MODAL -->
    <div class="modal fade" id="client_modal" role="dialog" aria-labelledby="client_modal" aria-hidden="true"
        style="z-index: 3000" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="media">
                        <div class="media-body">
                            <h5 class="mt-0">Please provide your information</h5>
                            <small>This file will be sent to your email</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?=form_open('client/ejournal/download_pdf',array('id'=>'form-client'))?>
                    <div class="form-group">
                        <label class="font-weight-bold" for="clt_title">Title</label><span
                            class="text-danger font-weight-bold">*</span>
                        <input type="text" class="form-control" id="clt_title" name="clt_title"
                            placeholder="Mr. / Ms. / Dr." required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="clt_name">Full Name</label><span
                            class="text-danger font-weight-bold">*</span>
                        <input type="text" class="form-control" id="clt_name" name="clt_name"
                            placeholder="Juan Dela Cruz" required>
                    </div>
                    <div class="form-group">
                            <label class="font-weight-bold" for="clt_age">Age<span
                                    class="text-danger font-weight-bold">*</span></label>
                            <input type="number" class="form-control" id="clt_age" name="clt_age" min="20" max="100">
                        </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="clt_sex">Sex</label><span
                            class="text-danger font-weight-bold">*</span>
                        <select class="form-control" id="clt_sex" name="clt_sex" required>
                            <option value="">Select Sex</option>
                            <option value="1">Male</option>
                            <option value="2">Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="clt_affiliation">Affiliation</label><span
                            class="text-danger font-weight-bold">*</span>
                        <input type="text" class="form-control" id="clt_affiliation" name="clt_affiliation" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="clt_country">Country</label> <span
                            class="text-danger font-weight-bold">*</span>
                        <select class="form-control" id="clt_country" name="clt_country" placeholder="Select Country"
                            style="background-color: white" required>
                            <!-- foreach of country -->
                            <?php foreach($country as $c): ?>
                            <?php $selected = ($c->country_id == '175')?'selected':'';
                            echo '<option value='.$c->country_id.' '.$selected.'>'.$c->country_name.'</option>'; ?>
                            <?php endforeach;?>
                            <!-- /.end of foreach-->
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="clt_email">Email</label><span
                            class="text-danger font-weight-bold">*</span>
                        <input type="email" class="form-control" id="clt_email" name="clt_email"
                            placeholder="Valid email is required" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="clt_purpose">Purpose</label><span
                            class="text-danger font-weight-bold">*</span>
                        <textarea class="form-control" id="clt_purpose" name="clt_purpose" required></textarea>
                    </div>
                    <div class="form-group text-left">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" value="1" name="clt_member"
                                id="clt_member">
                            <label class="custom-control-label" for="clt_member">Please check the box if you are an NRCP
                                member?</label>
                        </div>
                    </div>
                    <input type="hidden" id="clt_journal_downloaded_id" name="clt_journal_downloaded_id">


                </div>
                <div class="modal-footer">
                    <div id="alert-processing" class="alert alert-primary text-center h6 w-100 invisible" role="alert">
                        <span class="oi oi-clock oi-spin "></span> Sending Full Text PDF...
                        <!-- <span class="font-weight-bold" id="pdf_mail"></span> -->
                    </div>

                    <button type="button" class="btn btn-outline-secondary" id="btn_cancel_client_info"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger" id="btn_submit_client_info"
                        name="btn_submit_client_info"><span class="oi oi-check"></span> Submit</button>
                    <?=form_close()?>
                </div>
            </div>
        </div>
    </div>

    <!-- ABSTRACT MODAL -->
    <div class="modal fade" id="abstract_modal" role="dialog" aria-labelledby="abstract_modal" aria-hidden="true"
        style="z-index: 9999 !important">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Abstract</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <embed id="abstract_view" width="100%" height="700px" type="application/pdf">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <!-- <button type="button" class="btn btn-danger" id="download_pdf"><span
                            class="oi oi-data-transfer-download"></span> Request Full Text PDF</button> -->
                </div>
            </div>
        </div>
    </div>

    <!-- IMAGE PREVIEW -->
    <div class="modal fade" id="enlargeImageModal" tabindex="-1" role="dialog" aria-labelledby="enlargeImageModal"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <img src="" class="enlargeImageModalSource" style="height:50%;width: 100%;">
                </div>
            </div>
        </div>
    </div>

    <!-- AUTHOR DETAILS -->
    <div class="modal fade" tabindex="-1" role="dialog" id="acoa_details_modal_search">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>

    <!-- CITATION MODAL -->
    <div class="modal fade" id="citationModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Citation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Please fill up the required fields. Then click SUBMIT to show the APA
                        citation</p>
                    <form id="form_citation">
                        
                    <input type="hidden" id="cite_value" name="cite_value">
                        <div class="form-group">
                            <label class="font-weight-bold" for="cite_title">Title<span
                                    class="text-danger font-weight-bold">*</span></label>
                            <input type="text" class="form-control" id="cite_title" name="cite_title"
                                placeholder="Mr. / Ms. / Dr.">
                        </div>
                        <div class="form-group">
                        <label class="font-weight-bold" for="cite_name">Name<span
                                    class="text-danger font-weight-bold">*</span></label>
                            <input type="text" class="form-control" id="cite_name" name="cite_name"
                                placeholder="Full name">
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="cite_sex">Sex<span
                                    class="text-danger font-weight-bold">*</span></label>
                            <select class="form-control" id="cite_sex" name="cite_sex">
                                <option value="">Select Sex</option>
                                <option value="1">Male</option>
                                <option value="2">Female</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="clt_affiliation">Affiliation<span
                                    class="text-danger font-weight-bold">*</span></label>
                            <input type="text" class="form-control" id="cite_affiliation" name="cite_affiliation" placeholder="Affiliation">
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="cite_country">Country<span
                                    class="text-danger font-weight-bold">*</span></label>
                            <select class="form-control" id="cite_country" name="cite_country"
                                placeholder="Select Country" style="background-color: white">
                                <!-- foreach of country -->
                                <?php foreach ($country as $c): ?>
                                d
                                <?php $selected = ($c->country_id == '175') ? 'selected' : '';
            echo '<option value=' . $c->country_id . ' ' . $selected . '>' . $c->country_name . '</option>';?>
                                <?php endforeach;?>
                                <!-- /.end of foreach-->
                            </select>
                        </div>
                        <div class="form-group">
                        <label class="font-weight-bold" for="cite_email">Email<span
                                    class="text-danger font-weight-bold">*</span></label>
                            <input type="email" class="form-control" id="cite_email" name="cite_email"
                                placeholder="Email">
                        </div>
                        <div class="form-group text-left">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" value="1" name="cite_member"
                                    id="cite_member">
                                <label class="custom-control-label" for="cite_member">Please check the box if you are an
                                    NRCP member?</label>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <div id="cite_content" class="w-100">
                        <ul class="nav nav-tabs" id="cite_tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#apa" role="tab">APA</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="cite_tab_content">
                            <div class="tab-pane fade show active text-center" role="tabpanel" id="apa">
                                <textarea id="apa_format" class="form-control" readonly rows="5"></textarea>
                            </div>
                        </div>
                        <button type="button" onClick="copyCitationToClipboard('#apa_format')" class="btn btn-outline-primary mt-3 w-100">Copy to clipboard</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FEEDBACK MODAL -->
    <div class="modal fade" id="feedbackModal" data-backdrop="static" data-keyboard="false" style="z-index: 1051 !important;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header pb-0">
                    <p><span class="modal-title font-weight-bold h3">Your feedback</span><br />
                        <small>We would like your feedback to improve our system.</small>
                    </p>
                    <!-- <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button> -->
                </div>
                <div class="modal-body p-4">
                    <form id="feedback_form">
                        <input type="hidden" id="fb_usr_id" name="fb_usr_id">
                        <input type="hidden" id="fb_source" name="fb_source">
                        <div class="feedback text-center">
                            <p class="font-weight-bold h4 text-center">User Interface</p>
                            <div class="feedback-container ui-container">
                                <div class="feedback-item">
                                    <label for="ui-1" data-toggle="tooltip" data-placement="bottom" title="Sad">
                                        <input class="radio" type="radio" name="fb_rate_ui" id="ui-1" value="1">
                                        <span>🙁</span>
                                    </label>
                                </div>

                                <div class="feedback-item">
                                    <label for="ui-2" data-toggle="tooltip" data-placement="bottom" title="Neutral">
                                        <input class="radio" type="radio" name="fb_rate_ui" id="ui-2" value="2">
                                        <span>😶</span>
                                    </label>
                                </div>

                                <div class="feedback-item">
                                    <label for="ui-3" data-toggle="tooltip" data-placement="bottom" title="Happy">
                                        <input class="radio" type="radio" name="fb_rate_ui" id="ui-3" value="3">
                                        <span>🙂</span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="fb_suggest_ui"></label>
                                <textarea class="form-control" name="fb_suggest_ui" id="fb_suggest_ui" rows="3"
                                    placeholder="Type your suggestions here"></textarea>
                            </div>

                            <hr />

                            <p class="font-weight-bold h4 text-center">User Experience</p>
                            <div class="feedback-container ux-container">
                                <div class="feedback-item">
                                    <label for="ux-1" data-toggle="tooltip" data-placement="bottom" title="Sad">
                                        <input class="radio" type="radio" name="fb_rate_ux" id="ux-1" value="1">
                                        <span>🙁</span>
                                    </label>
                                </div>

                                <div class="feedback-item">
                                    <label for="ux-2" data-toggle="tooltip" data-placement="bottom" title="Nuetral">
                                        <input class="radio" type="radio" name="fb_rate_ux" id="ux-2" value="2">
                                        <span>😶</span>
                                    </label>
                                </div>

                                <div class="feedback-item">
                                    <label for="ux-3" data-toggle="tooltip" data-placement="bottom" title="Happy">
                                        <input class="radio" type="radio" name="fb_rate_ux" id="ux-3" value="3">
                                        <span>🙂</span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="fb_suggest_ux"></label>
                                <textarea class="form-control" name="fb_suggest_ux" id="fb_suggest_ux" rows="3"
                                    placeholder="Type your suggestions here"></textarea>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary  w-100">Submit Feedback</button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>