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
<div class="container-fluid mt-2 p-4">
    <div class="row">
        <div class="col col-3 p-3">
            <a class="btn btn-link main-link" href="<?=base_url('/client/ejournal/articles')?>">View all articles</a>
		</div>
        <div class="col col-7 p-3">
            <div class="border rounded p-5 ">
                <h2>My Profile</h2>
                <?php if ($this->session->flashdata('message')) { ?>
                <?php echo $this->session->flashdata('message'); ?>
                <?php } ?>
                <?=form_open('client/user/update_profile', ['id' => 'updateProfileForm'])?>
                <div class="mb-3">
                    <label class="form-label" for="new_email">Email</label>
                    <input type="email"
                        class="form-control <?php if($this->session->flashdata('signup_validation_errors')['new_email']){ echo 'is-invalid';} ?>"
                        id="new_email" name="new_email" placeholder="name@example.com"
                        value="<?= set_value('new_email', $this->session->flashdata('new_email')) ?? $profile[0]->email ?>">
                    <span
                        class="invalid-feedback"><?= $this->session->flashdata('signup_validation_errors')['new_email'] ?></span>
                </div>
                <div class="mb-3">
                    <div class="row">
                        <div class="col">
                            <label class="form-label" for="title">Title</label>
                            <select
                                class="form-select <?php if($this->session->flashdata('signup_validation_errors')['title']){ echo 'is-invalid';} ?>"
                                name="title" id="title"
                                value="<?= set_value('title', $this->session->flashdata('title')); ?>">
                                <option selected disabled>Select Title</option>
                                <?php foreach ($titles as $row): ?>
                                <?php $selected = ($row->title_name == set_value('title', $this->session->flashdata('title') ?? $profile[0]->title) ? 'selected' : '' ); ?>
                                <?php echo '<option value=' . $row->title_name . ' ' . $selected . '>' . $row->title_name . '</option>';?>
                                <?php endforeach;?>
                            </select>
                            <span
                                class="invalid-feedback"><?= $this->session->flashdata('signup_validation_errors')['title'] ?></span>
                        </div>
                        <div class="col">
                            <label class="form-label" for="first_name">First
                                Name</label> <?= $profile['email'] ?>
                            <input type="first_name"
                                class="form-control <?php if($this->session->flashdata('signup_validation_errors')['first_name']){ echo 'is-invalid';} ?>"
                                id="first_name" name="first_name"
                                value="<?= set_value('first_name', $this->session->flashdata('first_name')) ?? $profile[0]->first_name?>">
                            <span
                                class="invalid-feedback"><?= $this->session->flashdata('signup_validation_errors')['first_name'] ?></span>
                        </div>
                        <div class="col">
                            <label class="form-label" for="last_name">Last
                                Name</label>
                            <input type="last_name"
                                class="form-control <?php if($this->session->flashdata('signup_validation_errors')['last_name']){ echo 'is-invalid';} ?>"
                                id="last_name" name="last_name"
                                value="<?= set_value('last_name', $this->session->flashdata('last_name'))  ?? $profile[0]->last_name?>">
                            <span
                                class="invalid-feedback"><?= $this->session->flashdata('signup_validation_errors')['last_name'] ?></span>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="row">
                        <div class="col">
                            <label class="form-label" for="middle_name">Middle Name</label>
                            <input type="middle_name"
                                class="form-control <?php if($this->session->flashdata('signup_validation_errors')['middle_name']){ echo 'is-invalid';} ?>"
                                id="middle_name" name="middle_name"
                                value="<?= set_value('middle_name', $this->session->flashdata('middle_name')) ?? $profile[0]->middle_name?>">
                            <span
                                class="invalid-feedback"><?= $this->session->flashdata('signup_validation_errors')['middle_name'] ?></span>
                        </div>
                        <div class="col">
                            <label class="form-label" for="extension_name">Extension Name</label>
                            <input type="extension_name"
                                class="form-control <?php if($this->session->flashdata('signup_validation_errors')['extension_name']){ echo 'is-invalid';} ?>"
                                id="extension_name" name="extension_name"
                                value="<?= set_value('extension_name', $this->session->flashdata('extension_name')) ?? $profile[0]->extension_name ?>">
                            <span
                                class="invalid-feedback"><?= $this->session->flashdata('signup_validation_errors')['extension_name'] ?></span>
                        </div>
                        <div class="col">
                            <label class="form-label" for="sex">Sex</label>
                            <select
                                class="form-select <?php if($this->session->flashdata('signup_validation_errors')['sex']){ echo 'is-invalid';} ?>"
                                id="sex" name="sex">
                                <option selected disabled>Select Sex</option>
                                <option value="1"
                                    <?= (set_value('sex', $this->session->flashdata('sex') ?? $profile[0]->sex) == 1) ? 'selected' : '' ?>>
                                    Male</option>
                                <option value="2"
                                    <?= (set_value('sex', $this->session->flashdata('sex') ?? $profile[0]->sex) == 2) ? 'selected' : '' ?>>
                                    Female</option>
                            </select>
                            <span
                                class="invalid-feedback"><?= $this->session->flashdata('signup_validation_errors')['sex'] ?></span>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="educational_attainment">Educational Attainment</label>
                    <select
                        class="form-select <?php if($this->session->flashdata('signup_validation_errors')['educational_attainment']){ echo 'is-invalid';} ?>"
                        id="educational_attainment" name="educational_attainment">
                        <option selected disabled>Select Educational Attainment</option>
                        <?php
                                    $currentGroup = null;
                                    foreach ($educations as $row) {
                                        if ($currentGroup !== $row->educ_category) {
                                            echo '<optgroup label="' . $row->educ_category . '">';
                                            $currentGroup = $row->educ_category;
                                        }

                                        $selected = ($row->id == set_value('educational_attainment', $this->session->flashdata('educational_attainment') ?? $profile[0]->educational_attainment) ? 'selected' : '' );
                                        echo '<option value=' . $row->id . ' ' . $selected . '>' . $row->educ_name . '</option>';

                                        // Close the optgroup immediately after the last option within the group
                                        if ($row === end($educations)) {
                                            echo '</optgroup>';
                                        }

                                    }?>
                    </select>
                    <span
                        class="invalid-feedback"><?= $this->session->flashdata('signup_validation_errors')['educational_attainment'] ?></span>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="affiliation">Affiliation</label>
                    <input type="text"
                        class="form-control <?php if($this->session->flashdata('signup_validation_errors')['affiliation']){ echo 'is-invalid';} ?>"
                        id="affiliation" name="affiliation"
                        value="<?= set_value('affiliation', $this->session->flashdata('affiliation')) ?? $profile[0]->affiliation ?>">
                    <span
                        class="invalid-feedback"><?= $this->session->flashdata('signup_validation_errors')['affiliation'] ?></span>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="country">Country</label>
                    <select
                        class="form-select <?php if($this->session->flashdata('signup_validation_errors')['country']){ echo 'is-invalid';} ?>"
                        id="country" name="country">
                        <option disabled>Select Country</option>
                        <?php foreach ($country as $row): ?>
                        <?php $country_input = set_value('country', $this->session->flashdata('country') ?? $profile[0]->country); 
                                $country_id = ($country_input) ? $country_input : 175;
                                $selected = ($row->country_id == $country_id) ? 'selected' : '';
                                echo '<option value=' . $row->country_id . ' '.$selected.'>' . $row->country_name . '</option>';?>
                        <?php endforeach;?>
                    </select>
                </div>
                <div class="mb-3">
                    <div class="row">
                        <div class="col">
                            <label class="form-label" for="region">Region</label>
                            <select
                                class="form-select <?php if($this->session->flashdata('signup_validation_errors')['region']){ echo 'is-invalid';} ?>"
                                id="region" name="region" <?php if($country_id != 175){ echo 'disabled';} ?>>
                                <option selected disabled>Select Region</option>
                                <?php foreach ($regions as $row): ?>
                                <?php $region_input = set_value('region', $this->session->flashdata('region') ?? $profile[0]->region); 
                                        $selected = ($row->region_id == $region_input) ? 'selected' : '';
                                        echo '<option value=' . $row->region_id . ' ' . $selected .'>' . $row->region_name . '</option>';?>
                                <?php endforeach;?>
                            </select>
                            <span
                                class="invalid-feedback"><?= $this->session->flashdata('signup_validation_errors')['region'] ?></span>
                        </div>
                        <div class="col">
                            <label class="form-label" for="province">Province</label>
                            <select
                                class="form-select <?php if($this->session->flashdata('signup_validation_errors')['province']){ echo 'is-invalid';} ?>"
                                id="province" name="province" <?php if($country_id != 175){ echo 'disabled';} ?>>
                                <option selected disabled>Select Province</option>
                                <?php $provinces = ($this->session->flashdata('provinces')) ? $this->session->flashdata('provinces') : $provinces ?>
                                <?php $jsonData = json_encode($provinces); ?>
                                <?php $jsonDataDecoded = json_decode($jsonData, true); ?>
                                <?php foreach ($jsonDataDecoded as $row): ?>
                                <?php $province_input = set_value('province', $this->session->flashdata('province') ?? $profile[0]->province); 
                                        $selected = ($row['province_id'] == $province_input) ? 'selected' : '';
                                        echo '<option value=' . $row['province_id'] . ' ' . $selected .'>' . $row['province_name'] . '</option>';?>
                                <?php endforeach;?>
                            </select>
                            <span
                                class="invalid-feedback"><?= $this->session->flashdata('signup_validation_errors')['province'] ?></span>
                        </div>
                        <!-- TODO::get province and city, update function uBOtoPN3 -->
                        <div class="col">
                            <label class="form-label" for="city">City</label>
                            <select
                                class="form-select <?php if($this->session->flashdata('signup_validation_errors')['city']){ echo 'is-invalid';} ?>"
                                id="city" name="city" <?php if($country_id != 175){ echo 'disabled';} ?>>
                                <option selected disabled>Select City</option>
                                <?php $cities = ($this->session->flashdata('cities')) ? $this->session->flashdata('cities') : $cities ?>
                                <?php $jsonData = json_encode($cities); ?>
                                <?php $jsonDataDecoded = json_decode($jsonData, true); ?>
                                <?php foreach ($jsonDataDecoded as $row): ?>
                                <?php $city_input = set_value('city', $this->session->flashdata('city') ?? $profile[0]->city); 
                                        $selected = ($row['city_id'] == $city_input) ? 'selected' : '';
                                        echo '<option value=' . $row['city_id'] . ' ' . $selected .'>' . $row['city_name'] . '</option>';?>
                                <?php endforeach;?>
                            </select>
                            <span
                                class="invalid-feedback"><?= $this->session->flashdata('signup_validation_errors')['city'] ?></span>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="contact">Contact No.</label>
                    <input type="text"
                        class="form-control <?php if($this->session->flashdata('signup_validation_errors')['contact']){ echo 'is-invalid';} ?>"
                        id="contact" name="contact" placeholder=""
                        value="<?= set_value('contact', $this->session->flashdata('contact')) ?? $profile[0]->contact ?>">
                    <span
                        class="invalid-feedback"><?= $this->session->flashdata('signup_validation_errors')['contact'] ?></span>
                </div>
                <div class="mb-4">
                    <div class="row">
                        <div class="col">
                            <label class="form-label" for="new_password">Password</label>
                            <div class="input-group">

                                <input type="password"
                                    class="form-control <?php if($this->session->flashdata('signup_validation_errors')['new_password']){ echo 'is-invalid';} ?>"
                                    id="new_password" name="new_password" placeholder=""
                                    value="<?= set_value('new_password', $this->session->flashdata('new_password')); ?>">
                                <span class="input-group-text bg-white text-muted rounded-end"
                                    id="inputGroupPrepend3"><a class="text-muted cursor-pointer"
                                        href="javascript:void(0);"
                                        onclick="togglePassword('#new_password', '#new_passsword_icon')"><i
                                            class="fa fa-eye-slash" id="new_passsword_icon"></i></a></span>
                                <span
                                    class="invalid-feedback"><?= $this->session->flashdata('signup_validation_errors')['new_password'] ?></span>
                            </div>

                            <div class="card d-none mt-3" id="password_strength_container">
                                <div class="card-body text-secondary">
                                    <div><span class="me-1 fs-6">Password strength:</span><span class="fw-bold" id="password-strength"></span></div>
                                    <div class="progress mt-1" style="height: .5rem;">
                                        <div class="progress-bar" role="progressbar" <?= $this->session->flashdata('bar_style')?> id="password-strength-bar" aria-label="Success example" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <ul class="mt-3 small text-muted ps-3">
                                        <li>8-20 characters long.</li>
                                        <li>At least 1 letter.</li>
                                        <li>At lestt 1 number.</li>
                                        <li>At least 1 special character.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <label class="form-label" for="confirm_password">Confirm Password</label>
                            <div class="input-group">
                                <input type="password"
                                    class="form-control <?php if($this->session->flashdata('signup_validation_errors')['confirm_password']){ echo 'is-invalid';} ?>"
                                    id="confirm_password" name="confirm_password" placeholder=""
                                    value="<?= set_value('confirm_password', $this->session->flashdata('confirm_password')); ?>">
                                <span class="input-group-text bg-white text-muted rounded-end"
                                    id="inputGroupPrepend3"><a class="text-muted cursor-pointer"
                                        href="javascript:void(0);"
                                        onclick="togglePassword('#confirm_password', '#confirm_password_icon')"><i
                                            class="fa fa-eye-slash" id="confirm_password_icon"></i></a></span>

                                <span
                                    class="invalid-feedback"><?= $this->session->flashdata('signup_validation_errors')['confirm_password'] ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn main-btn w-100 mt-3" id="update_profile"
                    onclick="disableOnSubmit(this, '#updateProfileForm', 'profile')">Save Changes</button>
                <?=form_close()?>
            </div>
        </div>
        <div class="col col-lg-2 p-3">
            <?php $this->load->view('common/side_panel');?>
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