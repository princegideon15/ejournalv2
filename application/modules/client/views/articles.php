<?php error_reporting(0);?>
<div class="container-fluid mt-3 p-4">
    <div class="row pt-3">
        <!-- SIDE NAVIGATION -->
        
        <div class="col col-3 p-3">
			<ul class="list-unstyled">
				<li><a class="btn btn-link main-link" href="<?=base_url('/client/ejournal/articles')?>">Articles</a></li>
				<!-- <li><a class="btn btn-link main-link" href="<?=base_url('/client/ejournal/articles')?>">Volumes</a></li> -->
			</ul>
            
            
        </div>
        <div class="col-7 p-3">
            <div class="tab-content" id="nav-tabContent">
				<?php $c = 0;foreach ($articles as $row):

					$c++;
					
					$lat[$c]['title'] = $row->art_title;
					$lat[$c]['id'] = $row->art_id;
					$lat[$c]['id_jor'] = $row->art_jor_id;
					$lat[$c]['file'] = $row->art_abstract_file;
					$lat[$c]['keyw'] = $row->art_keywords;
					$lat[$c]['page'] = $row->art_page;
					$lat[$c]['year'] = $row->art_year;
					$lat[$c]['vol'] = $row->jor_volume;
					$lat[$c]['issn'] = $row->jor_issn;
					$lat[$c]['coa'] = $this->Client_journal_model->get_author_coauthors($row->art_id);
					$issue = (
						($row->jor_issue == 5) ? 'Special Issue No. 1' :
						(($row->jor_issue == 6) ? 'Special Issue No. 2' :
							(($row->jor_issue == 7) ? 'Special Issue No. 3' :
								(($row->jor_issue == 8) ? 'Special Issue No. 4' : 'Issue ' . $row->jor_issue)))
					);
					$lat[$c]['cite'] =  $this->Client_journal_model->get_citation($row->art_id) . '('. $row->art_year .'). ' . ucfirst(strtolower($row->art_title)) . '. NRCP Research Journal, Volume ' . $row->jor_volume . ', ' . $issue . ', ' . $row->art_page;

					$server_dir = '/var/www/html/ejournal/assets/uploads/pdf/';
					$get_file = filesize($server_dir . $row->art_full_text_pdf);

					// $get_file = filesize($_SERVER['DOCUMENT_ROOT'].'/ejournal/assets/uploads/pdf/'.$row->art_full_text_pdf);

					if ($get_file >= 1048576) {
						$lat[$c]['fsize'] = round($get_file / 1024 / 1024, 1) . ' MB';
					} elseif ($get_file >= 1024) {
						$lat[$c]['fsize'] = round($get_file / 1024, 1) . ' KB';
					} else {
						$lat[$c]['fsize'] = round($get_file, 1) . ' bytes';
					}

					$lat[$c]['citations'] = $this->Client_journal_model->count_citation($row->art_id);
					$lat[$c]['count'] = $this->Client_journal_model->count_pdf($row->art_id);
					$lat[$c]['abs'] = $this->Client_journal_model->count_abstract($row->art_id);
					$lat[$c]['cover'] = $this->Client_journal_model->get_cover($row->art_jor_id);

					$cover = $this->Client_journal_model->get_cover($row->art_jor_id);
					$issn = $row->jor_issn;
					$journal = $row->jor_volume . ' ' . $issue;
					$pub_date = $row->jor_month . ' ' . $row->jor_year;
					$description = $row->jor_description;
					
				endforeach;?>

				<div class="d-flex">
					<div class="flex-shrink-0">
						<img class="mr-2 img-thumbnail" height="150" width="150"
						src="<?php echo base_url('assets/uploads/cover/' . $cover . ''); ?>">
					</div>
					<div class="flex-grow-1 ms-2 mt-0">
						<p class="mt-0 text-dark">
							<h2>Volume <?php echo $journal; ?></h2>
							<h5 class="text-muted small"><?php echo $pub_date; ?></h5>
							<!-- <h5 class="text-muted small">ISSN: <?php echo $issn; ?></h5> -->
							<h5 class="text-muted small">Articles: <?php echo count($articles); ?></h5>
							<small class="text-muted"><?php echo $description; ?></small>
						</p>
					</div>
				</div>

				<hr>

				<?php $c = 1;foreach ($lat as $row): ?>
				<?php $coa_arr = (explode(",& ", $row['coa']));?>

				<p class="mt-0 text-dark mb-0"><?php echo $row['title']; ?></p>
				
				<div class="mt-1">
					<?php $i = 0; foreach ($coa_arr as $c): ?>
					<a href="javascript:void(0);" class="text-muted"
						onclick="author_details('<?php echo $row['id_jor']; ?>','<?php echo $c; ?>')"><?php echo $c; ?></a>
					<?php if($i < (count($coa_arr) - 1)) echo '<span class="font-italic text-muted">|</span>'; ?>
					<?php $i++; ?>
					<?php endforeach;?>

					<?php $key = 'do_not_replace'; $keywords = preg_replace("/\p{L}*?".str_replace("+"," ",$key)."\p{L}*/ui", "<span>$0</span>", $row['keyw']); ?>
				</div>
				
				<div class="text-muted mt-3">Keywords:
					<?php
					$string = explode(', ', $keywords);
					foreach ($string as $i => $key) {
						if ($key == strip_tags($key)) {
							echo ' <a class="text-muted" href="' . base_url() . 'client/ejournal/advanced?search_filter=1&search=' . str_replace(' ','+',$key) . '">' . $key . '</a>; ';
						} else {
							echo $key . '; ';
						}
					}
					?>
				</div>

				<p class="text-muted">Pages: <?php echo $row['page']; ?></p>
				
				<div class="d-flex justify-content-between align-items-center">
					<div class='mb-2 mt-2'>
						<!-- <span class="badge bg-light text-dark" data-toggle="tooltip"
							data-placement="top" title="File Size">
							<span class="oi oi-paperclip"></span> <?=$fsize?></span> -->
						
						<span class="badge bg-light text-dark" data-toggle="tooltip" data-placement="top"
							title="Full Text Downloads"><i class="oi oi-data-transfer-download"></i>
							<?=number_format($row['count'], 0, '', ',')?></span>
						<span class="badge bg-light text-dark" data-toggle="tooltip" data-placement="top"
							title="Abstract Hits"><i class="oi oi-eye"></i>
							<?=number_format($row['abs'], 0, '', ',')?></span>
						<span class="badge bg-light text-dark" data-toggle="tooltip" data-placement="top"
							title="Cited"><i class="oi oi-document"></i>
							<?=number_format($row['citations'], 0, '', ',')?></span>
					</div>
					<div class="d-flex gap-3">
						<a data-bs-toggle="modal" data-bs-target="#client_modal"
							class="main-link text-decoration-underline" href="javascript:void(0);"
							role="button" onclick="get_download_id(<?=$row['id']?>)">
							<span class="oi oi-file"></span> Download</a>
						<a class="main-link text-decoration-underline"
							onclick="get_download_id(<?=$row['id']?>,'hits','<?=$row['file']?>')"
							href="javascript:void(0);" role="button">
							<span class="oi oi-eye"></span> Abstract</a>
						<a data-bs-toggle="modal" data-bs-target="#citationModal"
							class="main-link text-decoration-underline" href="javascript:void(0);"
							role="button"
							onclick="get_citee_info('<?=addslashes($row['cite'])?>','<?=$row['id']?>')">
							<span class='oi oi-document'></span> Cite this article</a>
					</div>
				</div>

				<hr>
				<?php endforeach;?>
            </div>
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

    <!-- ABSTRACT MODAL -->
    <div class="modal fade" id="abstract_modal" role="dialog" aria-labelledby="abstract_modal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Abstract</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <embed id="abstract_view" WMODE="transparent" width="100%" height="700px" type="application/pdf">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
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
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
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
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
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
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <embed id="top_abstract_view" WMODE="transparent" width="100%" height="700px"
                        type="application/pdf">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
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
                    <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
                        data-dismiss="modal">Cancel</button>
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
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
    <!-- Citation Modal -->
    <div class="modal fade" id="citationModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Citation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Please provide us with your Full Name and Email Address. Then click SUBMIT to show the APA
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
                            <input type="text" class="form-control" id="cite_affiliation" name="cite_affiliation"
                                placeholder="Affiliation">
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
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <div id="cite_content" class="w-100">
                        <ul class="nav nav-tabs" id="cite_tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#apa" role="tab">APA</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="cite_tab_content">
                            <div class="tab-pane fade show active text-center" role="tabpanel" id="apa">
                                <textarea id="apa_format" class="form-control" readonly rows="5"></textarea>
                            </div>
                        </div>
                        <button type="button" onClick="copyCitationToClipboard('#apa_format')"
                            class="btn btn-outline-primary mt-3 w-100">Copy to clipboard</button>
                    </div>
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
                    <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
                            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Submit Feedback</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /.feedback modal -->


    <!-- Modal -->
    <div class="modal " id="mbsModal" aria-labelledby="mbsLabel" aria-hidden="true" style="top:57%;left:67%;overflow:hidden;
      z-index:1" data-backdrop="false">
        <div class="modal-dialog ">
            <div class="modal-content"
                style="background-image:  url('<?php echo base_url("assets/images/bg2.jpg");?>');">
                <div class="modal-header border-0 mb-0 pb-0">
                    <img src="<?php echo base_url('assets/images/nrcp.png');?>" width="30" height="30" alt="">
                    <img src="<?php echo base_url('assets/images/ejicon-07.png');?>" width="30" height="30" alt="">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body font-weight-bold mt-1 pt-0">
                    <div class="card border-0">
                        <div class="row no-gutters">
                            <div class="col-md-7">
                                <div class="card-body">
                                    <p class="card-text display-5" style="font-size:18px;">Thank you, Doc Mayet
                                        Sumagaysay, for the profound support of the digitalization and digitization
                                        efforts of the NRCP (National Research Council of the Philppines).</p>
                                    <!-- <p class="card-text"><small class="text-muted">- MIS Team</small></p> -->
                                </div>
                            </div>
                            <div class="col-md-5 pr-3">
                                <img src="<?php echo base_url("assets/images/mayet2.jpg");?>" class="card-img"
                                    alt="...">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Submit Feedback Floater -->
<!-- <div class="fixed-bottom text-right pr-5">
	<button type="button" class="btn btn-sm btn-warning font-weight-bold px-3 py-2" data-target="#feedbackModal"
		data-toggle="modal">
		<span class="oi oi-comment-square mr-1"></span>
		Submit Feedback
	</button>
</div> -->


<script>
function send_verification_code() {
    let clt_email = $("#clt_email").val();
    $("#send_verification_code").html("Please wait..");
    var url = "<?php echo base_url('client/ejournal/send_verification_code');?>";
    $.post(url, {
        clt_email: clt_email
    }, function(data, status) {
        console.log(status);
        console.log(data);
        if (status == "success") {
            $("#verification_code_div").html(data);
        } else {
            $("#verification_code_div").html(data);
        }
    })


}
</script>