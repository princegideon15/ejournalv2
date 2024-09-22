<?php error_reporting(0);?>
<div class="container-fluid" style="margin-top:70px; background-color:#F3F4F6">

	<div class="row">
		<div class="col">
			<img src="<?php echo base_url("assets/images/banner2024.png"); ?>" class="img-fluid w-100"
				alt="Responsive image">
		</div>
	</div>
	<br />
	<div class="row">
		<!-- SIDE NAVIGATION -->
		<div class="col-2 mb-5">
			<div class="list-group sticky" id="list_group_menu">
				<li class="list-group-item font-weight-bold bg-light"><span class="oi oi-book"></span> JOURNALS</li>
				<?php if ($journals != null) {
				$c = 0;foreach ($journals as $row): ?>
                <?php $active = ($row->jor_volume == $selected_journal) ? 'active' : ''; ?>
				<?php $c++;?>
				<?php echo '<a href="'.base_url('/client/ejournal/get_issues/'.$row->jor_volume.'').'" class="list-group-item '.$active.' list-group-item-action">Volume ' . $row->jor_volume . ', ' . $row->jor_year . '<span class="oi oi-chevron-right float-right mt-1" style="font-size:10px"></span></a>'; ?>
				<?php endforeach;}?>
			</div>
		</div>
		<div class="col-10">
			<div class="tab-content" id="nav-tabContent">
				<!-- HOME -->
				<div class="tab-pane fade show active " id="home" role="tabpanel" aria-labelledby="home">
					<div class="jumbotron shadow p-5 mb-5 bg-white rounded ">
						<div class="row">
							<div class="col">
								<div class="btn-group" role="group" aria-label="Basic example">
								<a type="button" class="btn btn-dark mr-1 text-white disabled">ARTICLES:</a>
									<a href="<?php echo base_url('/client/ejournal/get_index');?>" type="button" class="btn btn-outline-primary mr-1">INDEX</a>
									 <?php foreach (range('A', 'Z') as $char) {
                                        $class = $article_index == $char ? 'active' : '';
										echo '<a href="'.base_url('/client/ejournal/get_index/'.$char.'').'" type="button" class="btn btn-outline-primary mr-1 '. $class .'">'. $char .'</a>';
										}
									 ?>
								</div>
								
							</div>
						</div>
						<div class="row">
                        <!-- Latest Articles -->
								<?php $c = 0;foreach ($issues as $row):

                                    $c++;
                                    
                                    $issue = (
                                        ($row->jor_issue == 5) ? 'Special Issue No. 1' :
                                        (($row->jor_issue == 6) ? 'Special Issue No. 2' :
                                            (($row->jor_issue == 7) ? 'Special Issue No. 3' :
                                                (($row->jor_issue == 8) ? 'Special Issue No. 4' : 'Issue ' . $row->jor_issue)))
                                    );

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

                                    $cover = $this->Client_journal_model->get_cover($row->jor_id);
                                    $issn = $row->jor_issn;
                                    $pub_date = $row->jor_month . ' ' . $row->jor_year;
                                    $description = $row->jor_description;
                                    $jor_id = $row->jor_id;
                                    $volume = $row->jor_volume;
                                    $articles = $row->articles;

                                ?>
                                <div class="col-6">
                                <a href="<?php echo base_url('/client/ejournal/get_articles/'.$volume.'/'.$jor_id.'');?>" class="text-dark">
                                    <div class="media mb-3 mt-5 border ">
                                        <img class="mr-2 img-thumbnail" height="20%" width="20%"
                                            src="<?php echo base_url('assets/uploads/cover/' . $cover . ''); ?>">
                                        <div class="media-body ml-2">
                                            <p class="mt-0">
                                                    <h2><?php echo $issue; ?></h2>
                                                    <h5 class="text-muted small"><?php echo $pub_date; ?></h5>
                                                    <!-- <h5 class="text-muted small">ISSN: <?php echo $issn; ?></h5> -->
                                                    <h5 class="text-muted small">Articles: <?php echo $articles; ?></h5>
                                                    <small class="text-muted"><?php echo $description; ?></small>
                                            </p>
                                        </div>
                                    </div>
                                </a>
                                </div>
                                <?php endforeach;?>
						</div>
						
                        <div class="mt-5">
							<a rel="license" href="http://creativecommons.org/licenses/by/4.0/" target="_blank"><img
									alt="Creative Commons License" style="border-width:0"
									src="https://i.creativecommons.org/l/by/4.0/88x31.png" /></a><br />This work is
							licensed
							under a <a rel="license" href="http://creativecommons.org/licenses/by/4.0/"
								target="_blank">Creative Commons Attribution 4.0 International License</a>.
						</div>
					</div>
				</div>
			</div>
		</div>
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
					<form id="form-client">
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
							<label class="font-weight-bold" for="clt_age">Age<span
									class="text-danger font-weight-bold">*</span></label>
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
							<label class="font-weight-bold" for="clt_email">Email<span
									class="text-danger font-weight-bold">*</span></label>
							<input type="email" class="form-control" id="clt_email" name="clt_email"
								placeholder="Valid email is required">
						</div>
						<div class="form-group">
							<label class="font-weight-bold" for="clt_purpose">Purpose<span
									class="text-danger font-weight-bold">*</span></label>
							<textarea class="form-control" id="clt_purpose" name="clt_purpose"></textarea>
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
