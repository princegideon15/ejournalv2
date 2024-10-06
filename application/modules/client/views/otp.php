<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-VDLLX3HKBL"></script>
<script>
window.dataLayer = window.dataLayer || [];

function gtag() {
    dataLayer.push(arguments);
}
gtag('js', new Date());

gtag('config', 'G-VDLLX3HKBL');
</script>

<?php error_reporting(0);?>
<div class="container-fluid mt-3 p-4">
    <div class="row">
        <div class="col col-lg-4">

        </div>
        <div class="col col-lg-6 p-3">
            <h2>Welcome</h2>
            
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="login-tab" data-bs-toggle="tab"
                        data-bs-target="#login-tab-pane" type="button" role="tab" aria-controls="login-tab-pane"
                        aria-selected="true">Login</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="create-account-tab" data-bs-toggle="tab"
                        data-bs-target="#create-account-tab-pane" type="button" role="tab"
                        aria-controls="create-account-tab-pane" aria-selected="false">Create Account</button>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                    <!-- Login -->
                    <div class="tab-pane fade show active p-3" id="login-tab-pane" role="tabpanel" aria-labelledby="home-tab"
                        tabindex="0">

    
                        <?php if ($this->session->flashdata('otp')) { ?>
                            <?php echo $this->session->flashdata('otp'); ?>
                        <?php } ?>

                        <?= $otp ?>
                        <?=form_open('client/login/verify_otp/'.$this->session->userdata('otp_ref_code') ?? '', ['method' => 'post', 'id' => 'verifyOTPForm'])?>
                            <div class="mb-3">
                                <label for="otp" class="form-label">Enter the One-Time-Password (OTP) below</label>
                                <input type="otp" class="form-control w-50 <?php if($this->session->flashdata('validation_errors')['otp']){ echo 'is-invalid';} ?>" id="otp" name="otp" placeholder="6-digit Code">
                                <span class="invalid-feedback"><?=$this->session->flashdata('validation_errors')['otp']?></span>
                                <input type="hidden" name="ref" value="<?= $this->session->userdata('otp_ref_code') ?? '' ?>">
                            </div>
                            <button type="submit" class="btn main-btn w-50 mt-1 <?= $disabled ?>">Verify</button>
                        <?=form_close()?>
                    </div>
                    <!-- Create Account -->
                    <div class="tab-pane fade p-3" id="create-account-tab-pane" role="tabpanel" aria-labelledby="create-account-tab"
                        tabindex="0">
                        
                        <?=form_open('client/ejournal/articles', ['method' => 'get', 'id' => 'signUpForm'])?>
                            <div class="mb-3">
                                <label class="form-label" for="clt_title">Title<span
                                        class="text-danger fw-bold">*</span></label>
                                <input type="text" class="form-control" id="clt_title" name="clt_title"
                                    placeholder="Mr. / Ms. / Dr.">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="clt_name">Full name<span
                                        class="text-danger fw-bold">*</span></label>
                                <input type="text" class="form-control" id="clt_name" name="clt_name"
                                    placeholder="Juan Dela Cruz">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="clt_age">Age<span
                                    class="text-danger fw-bold">*</span>
                                    <span class="main-link fs-6">(Must be 20 years old and above)</span></label>
                                <input type="number" class="form-control" id="clt_age" name="clt_age" min="20" max="100">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="clt_sex">Sex<span
                                        class="text-danger fw-bold">*</span></label>
                                <select class="form-control" id="clt_sex" name="clt_sex">
                                    <option value="">Select Sex</option>
                                    <option value="1">Male</option>
                                    <option value="2">Female</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="clt_affiliation">Affiliation<span
                                        class="text-danger fw-bold">*</span></label>
                                <input type="text" class="form-control" id="clt_affiliation" name="clt_affiliation">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="clt_country">Country<span
                                        class="text-danger fw-bold">*</span></label>
                                <select class="form-control" id="clt_country" name="clt_country" placeholder="Select Country"
                                    style="background-color: white">
                                    <!-- foreach of country -->
                                    <?php foreach ($country as $c): ?>
                                    <?php $selected = ($c->country_id == '175') ? 'selected' : '';
                                    echo '<option value=' . $c->country_id . '>' . $c->country_name . '</option>';?>
                                    <?php endforeach;?>
                                    <!-- /.end of foreach-->
                                </select>
                            </div>
                            <!-- <div class="mb-3">
                                <label class="form-label" for="clt_purpose">Purpose<span
                                        class="text-danger fw-bold">*</span></label>
                                <textarea class="form-control" id="clt_purpose" name="clt_purpose"></textarea>
                            </div> -->
                            <div class="mb-3">
                                <label class="form-label" for="clt_email">Email<span
                                        class="text-danger fw-bold">*</span></label>
                                <input type="email" class="form-control" id="clt_email" name="clt_email"
                                    placeholder="Valid email is required">
                                <div id="verification_code_div" class="mt-1">
                                    <div class="btn btn-warning btn-block small fw-bold" id="send_verification_code"
                                        onclick="send_verification_code()" style="font-size:0.9em; width:100%;"
                                        title="Click this button to get the verification code emailed to you."><sup
                                            class="text-danger fw-bold">*</sup>Click this button to get the
                                        verification code emailed to you.</div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="clt_vcode">Verification Code<span
                                        class="text-danger fw-bold">*</span></label>
                                <input type="text" class="form-control fw-bold text-center" id="clt_vcode"
                                    name="clt_vcode" placeholder="Verification code is required">
                            </div>
                            <div class="mb-3 text-left">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" value="1" name="clt_member"
                                        id="clt_member">
                                    <label class="custom-control-label" for="clt_member">Please check the box if you are an
                                        <strong>NRCP member</strong>.</label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-outline-secondary w-100">Create</button>
                        <?=form_close()?>
                    </div>
            </div>
        </div>
        <div class="col col-lg-2 p-3">
            <?php $this->load->view('common/side_panel');?>
        </div>
    </div>
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
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" id="download_pdf"><span
                        class="oi oi-data-transfer-download"></span> Request Full Text PDF</button>
            </div>
        </div>
    </div>
</div>

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
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <embed id="top_abstract_view" WMODE="transparent" width="100%" height="700px" type="application/pdf">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" id="top_download_pdf"><span
                        class="oi oi-data-transfer-download"></span> Request Full Text PDF</button>
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
                <form id="form-client" action="<?php //echo base_url('client/ejournal/download_pdf');?>" method="post"
                    autocomplete="off">
                    <div class="form-group">
                        <label class="font-weight-bold" for="clt_title">Title<span
                                class="text-danger fw-bold">*</span></label>
                        <input type="text" class="form-control" id="clt_title" name="clt_title"
                            placeholder="Mr. / Ms. / Dr.">
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="clt_name">Full name<span
                                class="text-danger fw-bold">*</span></label>
                        <input type="text" class="form-control" id="clt_name" name="clt_name"
                            placeholder="Juan Dela Cruz">
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="clt_age">Age <sup
                                class="text-info fw-bold small">(<i class="text-danger">*</i> Must be 20 years
                                old and above)</sup></label>
                        <input type="number" class="form-control" id="clt_age" name="clt_age" min="20" max="100">
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="clt_sex">Sex<span
                                class="text-danger fw-bold">*</span></label>
                        <select class="form-control" id="clt_sex" name="clt_sex">
                            <option value="">Select Sex</option>
                            <option value="1">Male</option>
                            <option value="2">Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="clt_affiliation">Affiliation<span
                                class="text-danger fw-bold">*</span></label>
                        <input type="text" class="form-control" id="clt_affiliation" name="clt_affiliation">
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="clt_country">Country<span
                                class="text-danger fw-bold">*</span></label>
                        <select class="form-control" id="clt_country" name="clt_country" placeholder="Select Country"
                            style="background-color: white">
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
                                class="text-danger fw-bold">*</span></label>
                        <textarea class="form-control" id="clt_purpose" name="clt_purpose"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="clt_email">Email<span
                                class="text-danger fw-bold">*</span></label>
                        <input type="email" class="form-control" id="clt_email" name="clt_email"
                            placeholder="Valid email is required">
                        <div id="verification_code_div" class="mt-1">
                            <div class="btn btn-warning btn-block small fw-bold" id="send_verification_code"
                                onclick="send_verification_code()" style="font-size:0.9em; width:100%;"
                                title="Click this button to get the verification code emailed to you."><sup
                                    class="text-danger fw-bold">*</sup>Click this button to get the
                                verification code emailed to you.</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="clt_vcode">Verification Code<span
                                class="text-danger fw-bold">*</span></label>
                        <input type="text" class="form-control fw-bold text-center" id="clt_vcode"
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
							<label class="text-white fw-bold">"Sorry, our request form is currently undergoing development. Please check back later. Thank you for your understanding!" </label>
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
                <a class="btn main-link fw-bold w-100 me-1 d-flex align-items-center justify-content-center">Show related articles<i class="oi oi-chevron-right ms-1" style="font-size: .7rem"></i></a>
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
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Please provide us with your Full Name and Email Address. Then click SUBMIT to show the APA
                    citation</p>
                <form id="form_citation" autocomplete="off">
                    <input type="hidden" id="cite_value" name="cite_value">
                    <div class="form-group">
                        <label class="font-weight-bold" for="cite_title">Title<span
                                class="text-danger fw-bold">*</span></label>
                        <input type="text" class="form-control" id="cite_title" name="cite_title"
                            placeholder="Mr. / Ms. / Dr.">
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="cite_name">Name<span
                                class="text-danger fw-bold">*</span></label>
                        <input type="text" class="form-control" id="cite_name" name="cite_name" placeholder="Full name">
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="cite_sex">Sex<span
                                class="text-danger fw-bold">*</span></label>
                        <select class="form-control" id="cite_sex" name="cite_sex">
                            <option value="">Select Sex</option>
                            <option value="1">Male</option>
                            <option value="2">Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="clt_affiliation">Affiliation<span
                                class="text-danger fw-bold">*</span></label>
                        <input type="text" class="form-control" id="cite_affiliation" name="cite_affiliation"
                            placeholder="Affiliation">
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="cite_country">Country<span
                                class="text-danger fw-bold">*</span></label>
                        <select class="form-control" id="cite_country" name="cite_country" placeholder="Select Country"
                            style="background-color: white">
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
                                class="text-danger fw-bold">*</span></label>
                        <input type="email" class="form-control" id="cite_email" name="cite_email" placeholder="Email">
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
                <p><span class="modal-title fw-bold h3">Your Feedback</span><br />
                </p>
                <!-- <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button> -->
            </div>
            <div class="modal-body p-4">

                <form id="feedback_form" autocomplete="off">

                    <h6 class="font-weigh-bold mb-3">Please rate your experience for the following</h6>
                    <input type="hidden" id="fb_usr_id" name="fb_usr_id">
                    <input type="hidden" id="fb_source" name="fb_source">

                    <p>1. User Interface - Overall design of the website.</p>

                    <div class="rating">
                        <input type="radio" name="fb_rate_ui" value="5" id="ui-5" required><label for="ui-5"
                            data-toggle="tooltip" data-placement="top" title="Excellent">★</label>
                        <input type="radio" name="fb_rate_ui" value="4" id="ui-4" required><label for="ui-4"
                            data-toggle="tooltip" data-placement="top" title="Good">★</label>
                        <input type="radio" name="fb_rate_ui" value="3" id="ui-3" required><label for="ui-3"
                            data-toggle="tooltip" data-placement="top" title="Fair">★</label>
                        <input type="radio" name="fb_rate_ui" value="2" id="ui-2" required><label for="ui-2"
                            data-toggle="tooltip" data-placement="top" title="Poor">★</label>
                        <input type="radio" name="fb_rate_ui" value="1" id="ui-1" required><label for="ui-1"
                            data-toggle="tooltip" data-placement="top" title="Very Poor">★</label>
                    </div>

                    <p>2. Any other suggestions <sup class="text-info hide" id="fb_suggest_ui_prompt"></sup></p>

                    <textarea class="form-control mb-3" name="fb_suggest_ui" id="fb_suggest_ui" rows="3"
                        placeholder="Optional" maxlength="300"></textarea>


                    <p>3. User Experience - Overall experience of the website.</p>

                    <div class="rating">
                        <input type="radio" name="fb_rate_ux" value="5" id="ux-5" required><label for="ux-5"
                            data-toggle="tooltip" data-placement="top" title="Excellent">★</label>
                        <input type="radio" name="fb_rate_ux" value="4" id="ux-4" required><label for="ux-4"
                            data-toggle="tooltip" data-placement="top" title="Good">★</label>
                        <input type="radio" name="fb_rate_ux" value="3" id="ux-3" required><label for="ux-3"
                            data-toggle="tooltip" data-placement="top" title="Fair">★</label>
                        <input type="radio" name="fb_rate_ux" value="2" id="ux-2" required><label for="ux-2"
                            data-toggle="tooltip" data-placement="top" title="Poor">★</label>
                        <input type="radio" name="fb_rate_ux" value="1" id="ux-1" required><label for="ux-1"
                            data-toggle="tooltip" data-placement="top" title="Very Poor">★</label>
                    </div>

                    <p>4. Any other suggestions <sup class="text-info hide" id="fb_suggest_ux_prompt"></sup></p>

                    <textarea class="form-control" name="fb_suggest_ux" id="fb_suggest_ux" rows="3"
                        placeholder="Optional" maxlength="300"></textarea>

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
                    <div class="alert-prompt my-1" id="alert_prompt"></div>
                    <div class="form-group text-right mt-3 pb-0 mb-0">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="submit_feedback">Submit Feedback</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /.feedback modal -->


<script>
function send_verification_code() {
    let clt_email = $("#clt_email").val();
    $("#send_verification_code").html("Please wait..");
    var url = "<?php base_url('client/ejournal/send_verification_code');?>";
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

$("#fb_suggest_ui").on('input', function() {
    var inputText = $(this).val();
    let x = $(this).val().length;
    if (x > 0 && x <= 300) {
        $("#fb_suggest_ui_prompt").text(x + " (Maximum of 300 characters in length)");
    } else if (x > 0 && x > 300) {
        $("#fb_suggest_ui_prompt").html(
            "<span class='text-danger fw-bold'>&#9888; Exceeded 300 characters limit!</span>");
    } else {
        $("#fb_suggest_ui_prompt").text("");
    }
    var sqlInjectionPattern = /^[^';()\/\\]*$/; // Regular expression pattern for sql Injection Pattern
    var foundSpecialChars = false;

    // Loop through each character in the input text
    for (var i = 0; i < x; i++) {
        if (!sqlInjectionPattern.test(inputText[i])) {
            foundSpecialChars = true;
            break; // Exit loop if a special character is found
        }
    }

    if (foundSpecialChars) {
        $("#fb_suggest_ui_prompt").html(
            "<span class='text-danger fw-bold'>  &#9888; Special characters not allowed</span>");
    }
});

$("#fb_suggest_ux").on('input', function() {
    var inputText = $(this).val();
    let x = $(this).val().length;
    if (x > 0 && x <= 300) {
        $("#fb_suggest_ux_prompt").text(x + " (Maximum of 300 characters in length)");
    } else if (x > 0 && x > 300) {
        $("#fb_suggest_ux_prompt").html(
            "<span class='text-danger fw-bold'> &#9888; Exceeded 300 characters limit!</span>");
    } else {
        $("#fb_suggest_ux_prompt").text("");
    }
    var sqlInjectionPattern = /^[^';()\/\\]*$/; // Regular expression pattern for sql Injection Pattern
    var foundSpecialChars = false;

    // Loop through each character in the input text
    for (var i = 0; i < x; i++) {
        if (!sqlInjectionPattern.test(inputText[i])) {
            foundSpecialChars = true;
            break; // Exit loop if a special character is found
        }
    }

    if (foundSpecialChars) {
        $("#fb_suggest_ux_prompt").html(
            "<span class='text-danger fw-bold'>  &#9888; Special characters not allowed</span>");
    }
});
</script>