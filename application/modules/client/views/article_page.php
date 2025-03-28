<?php error_reporting(0);?>
<?php $logged_in = $this->session->userdata('user_id'); ?>
<div class="container-fluid mt-2 p-4">
    <div class="row">
        <div class="col col-3 p-3">
            <a class="main-link text-decoration-underline" href="<?=base_url('/client/ejournal/articles')?>">All Articles</a>
        </div>
        <div class="col-7 p-3">
            <div class="border p-5 mb-5 bg-white rounded">
                <div class="row">
                    <div class="col">
                        
                        <?php 
                            
                            $coa =  $this->Client_journal_model->get_author_coauthors($article[0]->art_id);
                            $coa_arr = (explode(",& ",$coa));
                            $issue = (
                                ($article[0]->jor_issue == 5) ? 'Special Issue No. 1' :
                                (($article[0]->jor_issue == 6) ? 'Special Issue No. 2' :
                                    (($article[0]->jor_issue == 7) ? 'Special Issue No. 3' :
                                        (($article[0]->jor_issue == 8) ? 'Special Issue No. 4' : 'Issue ' . $article[0]->jor_issue)))
                            );
                            $pdf = $this->Client_journal_model->count_pdf($article[0]->art_id);
                            $abs = $this->Client_journal_model->count_abstract($article[0]->art_id);
                            $citations = $this->Client_journal_model->count_citation($article[0]->art_id);
                            $cite =  $this->Client_journal_model->get_citation($article[0]->art_id) . ' ('. $article[0]->art_year .'). ' . ucfirst(strtolower($article[0]->art_title)) . '. NRCP Research Journal, Volume ' . $article[0]->jor_volume . ', ' . $issue . ', ' . $article[0]->art_page;
    

                        ?>

                        <h5 class="fw-bold"><?= $article[0]->art_title ?></h5>
                        <div class="mt-2">
                            <?php $i = 0; foreach($coa_arr as $cr):?>
                            <?php $cc = $cr; ?>
                            <!-- <a href="javascript:void(0);" class="text-muted"
                                onclick="author_details_search('<?=$jor_id;?>','<?=$cr;?>','articles')"><?=$cc;?></a> -->
                            <a href="<?= base_url() . 'client/ejournal/articles?search=' . str_replace(' ', '+', $cr) ?>" class="text-muted"><?=$cc;?></a>
                                
                            <?php if($i < (count($coa_arr) - 1)) echo '<span class="font-italic text-muted ">|</span>'; ?>
                            <?php $i++; ?>
                            <?php endforeach;?>
                        </div>
                        <div class="text-muted mt-3 small">Keywords:
                            <?php
                            $string = explode(', ', $article[0]->art_keywords);
                            foreach ($string as $i => $key) {
                                if ($key == strip_tags($key)) {
                                    // echo ' <a class="text-muted" href="' . base_url() . 'client/ejournal/advanced?search_filter=1&search=' . str_replace(' ','+',$key) . '">' . $key . '</a>; ';
                                    echo ' <a class="text-muted" href="' . base_url() . 'client/ejournal/articles?search=' . str_replace(' ','+',$key) . '">' . $key . '</a>; ';
                                } else {
                                    echo $key . '; ';
                                }
                            }
                            ?>
                        </div>
                        <div class="text-muted mt-1 small">Pages: <?=$article[0]->art_page?></div>
                        
                        <div class="text-muted mt-1 small">Year Published: <?=$article[0]->art_year?></div>
                        
                        <div class="text-muted mt-1 small"><a class="text-muted" href="<?= base_url('/client/ejournal/volume/'.$article[0]->jor_volume.'/'.$article[0]->jor_issue.'');?>">Volume <?=$article[0]->jor_volume . ' ' . $issue?></a></div>

                        <div class='mb-5 mt-3'>
                            <span class="badge bg-light text-dark" data-toggle="tooltip" data-placement="top"
                                title="Full Text Downloads"><i class="oi oi-data-transfer-download"></i>
                                <?=number_format($pdf, 0, '', ',')?> Downloads</span>
                            <span class="badge bg-light text-dark" data-toggle="tooltip" data-placement="top"
                                title="Abstract Hits"><i class="oi oi-eye"></i>
                                <?=number_format($abs, 0, '', ',')?> Abstract Hits</span>
                            <span class="badge bg-light text-dark" data-toggle="tooltip" data-placement="top"
                                title="Cited"><i class="oi oi-document"></i>
                                <?=number_format($citations, 0, '', ',')?> Citations</span>
                        </div>
                        <h5>Abstract</h5>
                        <hr>

                        <embed class="mb-3" src="<?= base_url('assets/uploads/abstract/'.$article[0]->art_abstract_file) ?>#toolbar=0&navpanes=0&scrollbar=0" width="100%" height="700px" type="application/pdf">
                        
                        <?php if($logged_in){
                            echo '<div class="d-flex gap-1 mb-3">
                            <a class="main-btn btn" href="'.base_url('client/ejournal/download_file/'. $article[0]->art_id .'/'.$article[0]->art_full_text_pdf).'"
                                role="button">
                                Download Full Text PDF <span class="oi oi-data-transfer-download ms-2" style="font-size:.8rem"></span></a>
                            <a  data-bs-toggle="modal" data-bs-target="#citationModal"
                                class="main-btn btn " href="javascript:void(0);"
                                role="button"
                                onclick="get_citee_info(\''.addslashes($cite).'\','.$article[0]->art_id.')">
                                Cite this article  <span class="oi oi-double-quote-sans-left ms-1" style="font-size:.8rem"></span></a>
                           
                           
                            </div>';
                        }?>

                        
                            
                                <div class="input-group">
                                <button class="btn btn-outline-dark" type="button" id="share_link" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Copy to clipboard">Share <span class="oi oi-share ms-1"></span></button>
                                <input type="text" id="share_link_article" class="form-control w-50 bg-light" value="<?= base_url('client/ejournal/article/'. $article[0]->art_id) ?>" readonly>
                            </div>


                    </div>
                </div>
            </div>
        </div>
        <div class="col col-2 p-3">
            <?php $this->load->view('common/side_panel');?>
        </div>
    </div>

	<div class="mt-5">
    <h6 class="fw-bold">Open Access</h6>       
        <a rel="license" href="http://creativecommons.org/licenses/by/4.0/" target="_blank">
            <img alt="Creative Commons License" style="border-width:0" src="https://i.creativecommons.org/l/by/4.0/88x31.png" />
        </a>
        <p>This work is licensed under a <a class="text-dark" rel="license" href="http://creativecommons.org/licenses/by/4.0/" target="_blank">Creative Commons
            Attribution 4.0 International License</a>.
        </p>
    </div>

    <!-- ABSTRACT MODAL -->
    <div class="modal fade" id="abstract_modal" role="dialog" aria-labelledby="abstract_modal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Abstract</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <embed id="abstract_view" WMODE="transparent" width="100%" height="700px" type="application/pdf">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-close" data-bs-dismiss="modal">Close</button>
                    <!-- <button type="button" class="btn btn-danger" id="download_pdf"><span
							class="oi oi-data-transfer-download"></span> Request Full Text PDF</button> -->
                </div>
            </div>
        </div>
    </div>

    <!-- IMAGE PREVIEW -->
    <!-- <div class="modal fade" id="enlargeImageModal" tabindex="-1" role="dialog" aria-labelledby="enlargeImageModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        </div>
        <div class="modal-body">
          <img src="" class="enlargeImageModalSource" style="width: 100%;height:50%">
        </div>
      </div>
    </div>
</div> -->

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

    <!-- TOP ARTICLE MODAL -->
    <div class="modal fade" id="top_modal" tabindex="-1" role="dialog" style="z-index: 1051 !important;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Abstract</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <embed id="top_abstract_view" WMODE="transparent" width="100%" height="700px"
                        type="application/pdf">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-close" data-bs-dismiss="modal">Close</button>
                    <!-- <button type="button" class="btn btn-danger" id="top_download_pdf"><span
							class="oi oi-data-transfer-download"></span> Request Full Text PDF</button> -->
                </div>
            </div>
        </div>
    </div>

    <!-- PDF MODAL -->
    <div class="modal fade" id="client_modal" role="dialog" aria-labelledby="client_modal" aria-hidden="true"
        data-backdrop="static" style="z-index: 1052 !important;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="media">
                        <div class="media-body">
                            <h5 class="mt-0">Please provide your information</h5>
                            <small>This file will be sent to your email</small>
                        </div>
                    </div>
                    <!-- <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button> -->
                </div>
                <div class="modal-body">
                    <form id="form-client" autocomplete="off">
                        <div class="form-group">
                            <label class="font-weight-bold" for="clt_title">Title<span
                                    class="text-danger font-weight-bold">*</span></label>
                            <input type="text" class="form-control" id="clt_title" name="clt_title"
                                placeholder="Mr. / Ms. / Dr.">
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="clt_name">Full name<span
                                    class="text-danger font-weight-bold">*</span></label>
                            <input type="text" class="form-control" id="clt_name" name="clt_name"
                                placeholder="Juan Dela Cruz">
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="clt_age">Age<sup
                                    class="text-info font-weight-bold small">(<i class="text-danger">*</i> Must be 20
                                    years old and above)</sup></label>
                            <input type="number" class="form-control" id="clt_age" name="clt_age" min="20" max="100">
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="clt_sex">Sex<span
                                    class="text-danger font-weight-bold">*</span></label>
                            <select class="form-control" id="clt_sex" name="clt_sex">
                                <option value="">Select Sex</option>
                                <option value="1">Male</option>
                                <option value="2">Female</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="clt_affiliation">Affiliation<span
                                    class="text-danger font-weight-bold">*</span></label>
                            <input type="text" class="form-control" id="clt_affiliation" name="clt_affiliation">
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="clt_country">Country<span
                                    class="text-danger font-weight-bold">*</span></label>
                            <select class="form-control" id="clt_country" name="clt_country"
                                placeholder="Select Country" style="background-color: white">
                                <!-- foreach of country -->
                                <?php foreach ($country as $c): ?>
                                <?php $selected = ($c->country_id == '175') ? 'selected' : '';
echo '<option value=' . $c->country_id . '>' . $c->country_name . '</option>';?>
                                <?php endforeach;?>
                                <!-- /.end of foreach-->
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="clt_purpose">Purpose<span
                                    class="text-danger font-weight-bold">*</span></label>
                            <textarea class="form-control" id="clt_purpose" name="clt_purpose"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="clt_email">Email<span
                                    class="text-danger font-weight-bold">*</span></label>
                            <input type="email" class="form-control" id="clt_email" name="clt_email"
                                placeholder="Valid email is required">
                            <div id="verification_code_div" class="mt-1">
                                <div class="btn btn-warning btn-block small font-weight-bold"
                                    id="send_verification_code" onclick="send_verification_code()"
                                    style="font-size:0.9em; width:100%;"
                                    title="Click this button to get the verification code emailed to you."><sup
                                        class="text-danger font-weight-bold">*</sup>Click this button to get the
                                    verification code emailed to you.</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="clt_vcode">Verification Code<span
                                    class="text-danger font-weight-bold">*</span></label>
                            <input type="text" class="form-control font-weight-bold text-center" id="clt_vcode"
                                name="clt_vcode" placeholder="Verification code is required">
                        </div>
                        <div class="form-group text-left">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" value="1" name="clt_member"
                                    id="clt_member">
                                <label class="custom-control-label" for="clt_member">Please check the box if you are an
                                    <strong>NRCP member</strong>.</label>
                            </div>
                        </div>
                        <input type="hidden" id="clt_journal_downloaded_id" name="clt_journal_downloaded_id">
                        <?php /*
						<div class="bg-danger px-2">
							<label class="text-white font-weight-bold">"Sorry, our request form is currently undergoing development. Please check back later. Thank you for your understanding!" </label>
						</div>
						*/;?>
                        <div id="message_notif"></div>
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
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- AUTHOR DETAILS -->
    <div class="modal fade" tabindex="-1" role="dialog" id="acoa_details_modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                </div>
				<div class="modal-footer">
					<a class="btn main-link font-weight-bold w-100 me-1 d-flex align-items-center justify-content-center">Show related articles<i class="oi oi-chevron-right ms-1" style="font-size: .7rem"></i></a>
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
                    <!-- <p>Please fill up the required fields. Then click SUBMIT to show the APA
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
                                <?php foreach ($country as $c): ?>
                                <?php $selected = ($c->country_id == '175') ? 'selected' : '';
                                    echo '<option value=' . $c->country_id . ' ' . $selected . '>' . $c->country_name . '</option>';?>
                                <?php endforeach;?>
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
                    </form> -->

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
                        <button type="button" onClick="copyCitationToClipboard('#apa_format')" class="btn main-btn mt-3 w-100">Copy to clipboard</button>
                    </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- feedback modal -->
    <div class="modal fade" id="feedbackModal" data-backdrop="static" data-keyboard="false"
        style="z-index: 1051 !important;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header pb-0">
                    <p><span class="modal-title font-weight-bold h3">Your Feedback</span><br />
                    </p>
                    <!-- <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button> -->
                </div>
                <div class="modal-body p-4">

                    <form id="feedback_form">
                        <h6 class="font-weigh-bold mb-3">Please rate your experience for the following</h6>
                        <input type="hidden" id="fb_usr_id" name="fb_usr_id">
                        <input type="hidden" id="fb_source" name="fb_source">

                        <p>1. User Interface - Overall design of the website.</p>

                        <div class="rating">
                            <input type="radio" name="fb_rate_ui" value="5" id="ui-5"><label for="ui-5"
                                data-toggle="tooltip" data-placement="top" title="Excellent">★</label>
                            <input type="radio" name="fb_rate_ui" value="4" id="ui-4"><label for="ui-4"
                                data-toggle="tooltip" data-placement="top" title="Good">★</label>
                            <input type="radio" name="fb_rate_ui" value="3" id="ui-3"><label for="ui-3"
                                data-toggle="tooltip" data-placement="top" title="Fair">★</label>
                            <input type="radio" name="fb_rate_ui" value="2" id="ui-2"><label for="ui-2"
                                data-toggle="tooltip" data-placement="top" title="Poor">★</label>
                            <input type="radio" name="fb_rate_ui" value="1" id="ui-1"><label for="ui-1"
                                data-toggle="tooltip" data-placement="top" title="Very Poor">★</label>
                        </div>

                        <p>2. Any other suggestions</p>

                        <textarea class="form-control mb-3" name="fb_suggest_ui" id="fb_suggest_ui" rows="3"
                            placeholder="Optional"></textarea>


                        <p>3. User Experience - Overall experience of the website.</p>

                        <div class="rating">
                            <input type="radio" name="fb_rate_ux" value="5" id="ux-5"><label for="ux-5"
                                data-toggle="tooltip" data-placement="top" title="Excellent">★</label>
                            <input type="radio" name="fb_rate_ux" value="4" id="ux-4"><label for="ux-4"
                                data-toggle="tooltip" data-placement="top" title="Good">★</label>
                            <input type="radio" name="fb_rate_ux" value="3" id="ux-3"><label for="ux-3"
                                data-toggle="tooltip" data-placement="top" title="Fair">★</label>
                            <input type="radio" name="fb_rate_ux" value="2" id="ux-2"><label for="ux-2"
                                data-toggle="tooltip" data-placement="top" title="Poor">★</label>
                            <input type="radio" name="fb_rate_ux" value="1" id="ux-1"><label for="ux-1"
                                data-toggle="tooltip" data-placement="top" title="Very Poor">★</label>
                        </div>

                        <p>4. Any other suggestions</p>

                        <textarea class="form-control" name="fb_suggest_ux" id="fb_suggest_ux" rows="3"
                            placeholder="Optional"></textarea>

                        <!-- <div class="feedback text-center">
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

                            

                        </div> -->

                        <div class="form-group text-right mt-3 pb-0 mb-0">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Submit Feedback</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /.feedback modal -->
</div>
</div>
