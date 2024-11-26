<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-VDLLX3HKBL"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'G-VDLLX3HKBL');
    
    let recaptchaWidgetId_create_author_account;

    // Initialize reCAPTCHA and store the widget ID
    window.onload = function () {
        recaptchaWidgetId_create_author_account = grecaptcha.render('captcha_author', {
            'sitekey': '6LcTEV8qAAAAACVwToj7gI7BRdsoEEhJCnnFkWC6',
            'callback': onRecaptchaSuccess,
            'expired-callback': onRecaptchaExpired
        });
    };

</script>

<?php error_reporting(0);?>
<div class="container-fluid mt-3 p-4">
    <div class="row">
        <div class="col col-lg-4">

        </div>
        <div class="col col-lg-6 p-3">
            <h2>Create Author Account</h2>
            <div>
                
                <?php if ($this->session->flashdata('error')) { ?>
                    <div class="alert alert-danger d-flex align-items-center">
                        <i class="fa fa-info-circle me-1"></i><?php echo $this->session->flashdata('error'); ?>
                    </div>
                <?php } ?>

                <?=form_open('client/signup/create_author_account', ['method' => 'post', 'id' => 'authorSignUpForm'])?>
                    <p class="mb-3 fs-italic"><span class="text-danger fw-bold">*</span>Required fields</p>
                    <div class="mb-3">
                        <div class="form-check">
                            <!-- author_type -->
                                <?php $author_type = $this->session->flashdata('author_type'); ?>

                            <input class="form-check-input" type="radio" name="author_type" value="1" id="author_nrcp_member" <?= ($author_type == 1) ? 'checked' : '' ?> >
                            <label class="form-check-label" for="author_nrcp_member">
                                NRCP Member
                            </label>
                            </div>
                            <div class="form-check">
                            <input class="form-check-input" type="radio" name="author_type" value="2" id="author_non_member" <?= ($author_type == 2) ? 'checked' : '' ?> >
                            <label class="form-check-label" for="author_non_member">
                            Non-Member
                            </label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="new_email"><span
                        class="text-danger fw-bold">*</span>Email</label>
                        <input type="email" class="form-control <?php if($this->session->flashdata('signup_validation_errors')['new_email']){ echo 'is-invalid';} ?>" id="new_email" name="new_email"
                            placeholder="Enter valid email address" value="<?= set_value('new_email', $this->session->flashdata('new_email')); ?>">
                        <span class="invalid-feedback"><?= $this->session->flashdata('signup_validation_errors')['new_email'] ?></span>
                    </div>
                    <div class="mb-3">
                        <div class="row">
                            <div class="col">
                                <label class="form-label" for="title"><span
                                class="text-danger fw-bold">*</span>Title</label>
                                <select class="form-select <?php if($this->session->flashdata('signup_validation_errors')['title']){ echo 'is-invalid';} ?>" name="title" id="title" value="<?= set_value('title', $this->session->flashdata('title')); ?>" <?= ($author_type == 1) ? 'disabled' : '' ?> >
                                    <option selected disabled>Select Title</option>
                                        <?php foreach ($titles as $row): ?>
                                        <?php $selected = ($row->title_name == set_value('title', $this->session->flashdata('title')) ? 'selected' : '' ); ?>
                                        <?php echo '<option value=' . $row->title_name . ' ' . $selected . '>' . $row->title_name . '</option>';?>
                                        <?php endforeach;?>
                                </select>
                                <span class="invalid-feedback"><?= $this->session->flashdata('signup_validation_errors')['title'] ?></span>
                            </div>
                            <div class="col">
                                <label class="form-label" for="first_name"><span
                                class="text-danger fw-bold">*</span>First Name</label>
                                <input type="first_name" class="form-control <?php if($this->session->flashdata('signup_validation_errors')['first_name']){ echo 'is-invalid';} ?>" id="first_name" name="first_name" value="<?= set_value('first_name', $this->session->flashdata('first_name')); ?>" <?= ($author_type == 1) ? 'disabled' : '' ?> >
                                <span class="invalid-feedback"><?= $this->session->flashdata('signup_validation_errors')['first_name'] ?></span>
                            </div>
                            <div class="col">
                                <label class="form-label" for="last_name"><span
                                class="text-danger fw-bold">*</span>Last Name</label>
                                <input type="last_name" class="form-control <?php if($this->session->flashdata('signup_validation_errors')['last_name']){ echo 'is-invalid';} ?>" id="last_name" name="last_name" value="<?= set_value('last_name', $this->session->flashdata('last_name')); ?>" <?= ($author_type == 1) ? 'disabled' : '' ?> >
                                <span class="invalid-feedback"><?= $this->session->flashdata('signup_validation_errors')['last_name'] ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="row">
                            <div class="col">
                                <label class="form-label" for="middle_name">Middle Name</label>
                                <input type="middle_name" class="form-control <?php if($this->session->flashdata('signup_validation_errors')['middle_name']){ echo 'is-invalid';} ?>" id="middle_name" name="middle_name" value="<?= set_value('middle_name', $this->session->flashdata('middle_name')); ?>" <?= ($author_type == 1) ? 'disabled' : '' ?> >
                                <span class="invalid-feedback"><?= $this->session->flashdata('signup_validation_errors')['middle_name'] ?></span>
                            </div>
                            <div class="col">
                                <label class="form-label" for="extension_name">Extension Name</label>
                                <input type="extension_name" class="form-control <?php if($this->session->flashdata('signup_validation_errors')['extension_name']){ echo 'is-invalid';} ?>" id="extension_name" name="extension_name" value="<?= set_value('extension_name', $this->session->flashdata('extension_name')); ?>" <?= ($author_type == 1) ? 'disabled' : '' ?> >
                                <span class="invalid-feedback"><?= $this->session->flashdata('signup_validation_errors')['extension_name'] ?></span>
                            </div>
                            <div class="col">
                                <label class="form-label" for="sex"><span
                                class="text-danger fw-bold">*</span>Sex</label>
                                <select class="form-select <?php if($this->session->flashdata('signup_validation_errors')['sex']){ echo 'is-invalid';} ?>" id="sex" name="sex" <?= ($author_type == 1) ? 'disabled' : '' ?> >
                                    <option selected disabled>Select Sex</option>
                                    <option value="1" <?= (set_value('sex', $this->session->flashdata('sex')) == 1) ? 'selected' : '' ?>>Male</option>
                                    <option value="2" <?= (set_value('sex', $this->session->flashdata('sex')) == 2) ? 'selected' : '' ?>>Female</option>
                                </select>
                                <span class="invalid-feedback"><?= $this->session->flashdata('signup_validation_errors')['sex'] ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="educational_attainment"><span
                        class="text-danger fw-bold">*</span>Educational Attainment</label>
                        <select class="form-select <?php if($this->session->flashdata('signup_validation_errors')['educational_attainment']){ echo 'is-invalid';} ?>" id="educational_attainment" name="educational_attainment" <?= ($author_type == 1) ? 'disabled' : '' ?> >
                            <option selected disabled>Select Educational Attainment</option>
                            <?php
                                $currentGroup = null;
                                foreach ($educations as $row) {
                                    if ($currentGroup !== $row->educ_category) {
                                        echo '<optgroup label="' . $row->educ_category . '">';
                                        $currentGroup = $row->educ_category;
                                    }

                                    $selected = ($row->id == set_value('educational_attainment', $this->session->flashdata('educational_attainment')) ? 'selected' : '' );
                                    echo '<option value=' . $row->id . ' ' . $selected . '>' . $row->educ_name . '</option>';

                                    // Close the optgroup immediately after the last option within the group
                                    if ($row === end($educations)) {
                                        echo '</optgroup>';
                                    }

                                }?>
                        </select>
                        <span class="invalid-feedback"><?= $this->session->flashdata('signup_validation_errors')['educational_attainment'] ?></span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="affiliation"><span
                        class="text-danger fw-bold">*</span>Affiliation</label>
                        <input type="text" class="form-control <?php if($this->session->flashdata('signup_validation_errors')['affiliation']){ echo 'is-invalid';} ?>" id="affiliation" name="affiliation"  value="<?= set_value('affiliation', $this->session->flashdata('affiliation')); ?>" <?= ($author_type == 1) ? 'disabled' : '' ?> >
                        <span class="invalid-feedback"><?= $this->session->flashdata('signup_validation_errors')['affiliation'] ?></span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="country"><span
                        class="text-danger fw-bold">*</span>Country</label>
                        <select class="form-select <?php if($this->session->flashdata('signup_validation_errors')['country']){ echo 'is-invalid';} ?>" id="country" name="country" <?= ($author_type == 1) ? 'disabled' : '' ?> >
                            <option disabled>Select Country</option>
                            <?php foreach ($country as $row): ?>
                            <?php $country_input = set_value('country', $this->session->flashdata('country')); 
                            $country_id = ($country_input) ? $country_input : 175;
                            $selected = ($row->country_id == $country_id) ? 'selected' : '';
                            echo '<option value=' . $row->country_id . ' '.$selected.'>' . $row->country_name . '</option>';?>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <div class="row">
                            <div class="col">
                                <label class="form-label" for="region"><span
                                class="text-danger fw-bold">*</span>Region</label>
                                <select class="form-select <?php if($this->session->flashdata('signup_validation_errors')['region']){ echo 'is-invalid';} ?>" id="region" name="region"
                                <?php if($country_id != 175){ echo 'disabled';} ?>  <?= ($author_type == 1) ? 'disabled' : '' ?> >
                                    <option selected disabled>Select Region</option>
                                    <?php foreach ($regions as $row): ?>
                                    <?php $region_input = set_value('region', $this->session->flashdata('region')); 
                                    $selected = ($row->region_id == $region_input) ? 'selected' : '';
                                    echo '<option value=' . $row->region_id . ' ' . $selected .'>' . $row->region_name . '</option>';?>
                                    <?php endforeach;?>
                                </select>
                                <span class="invalid-feedback"><?= $this->session->flashdata('signup_validation_errors')['region'] ?></span>
                            </div>
                            <div class="col">
                                <label class="form-label" for="province"><span
                                class="text-danger fw-bold">*</span>Province</label>
                                <select class="form-select <?php if($this->session->flashdata('signup_validation_errors')['province']){ echo 'is-invalid';} ?>" id="province" name="province" <?php if($country_id != 175){ echo 'disabled';} ?> <?= ($author_type == 1) ? 'disabled' : '' ?> >
                                    <option selected disabled>Select Province</option>
                                    <?php $provinces = ($this->session->flashdata('provinces')) ? $this->session->flashdata('provinces') : '' ?>
                                    <?php $jsonData = json_encode($provinces); ?>
                                    <?php $jsonDataDecoded = json_decode($jsonData, true); ?>
                                    <?php foreach ($jsonDataDecoded as $row): ?>
                                    <?php $province_input = set_value('province', $this->session->flashdata('province')); 
                                    $selected = ($row['province_id'] == $province_input) ? 'selected' : '';
                                    echo '<option value=' . $row['province_id'] . ' ' . $selected .'>' . $row['province_name'] . '</option>';?>
                                    <?php endforeach;?>
                                </select>
                                <span class="invalid-feedback"><?= $this->session->flashdata('signup_validation_errors')['province'] ?></span>
                            </div>
                            <div class="col">
                                <label class="form-label" for="city"><span
                                class="text-danger fw-bold">*</span>City</label>
                                <select class="form-select <?php if($this->session->flashdata('signup_validation_errors')['city']){ echo 'is-invalid';} ?>" id="city" name="city" <?php if($country_id != 175){ echo 'disabled';} ?> <?= ($author_type == 1) ? 'disabled' : '' ?> >
                                    <option selected disabled>Select City</option>
                                    <?php $cities = ($this->session->flashdata('cities')) ? $this->session->flashdata('cities') : '' ?>
                                    <?php $jsonData = json_encode($cities); ?>
                                    <?php $jsonDataDecoded = json_decode($jsonData, true); ?>
                                    <?php foreach ($jsonDataDecoded as $row): ?>
                                    <?php $city_input = set_value('city', $this->session->flashdata('city')); 
                                    $selected = ($row['city_id'] == $city_input) ? 'selected' : '';
                                    echo '<option value=' . $row['city_id'] . ' ' . $selected .'>' . $row['city_name'] . '</option>';?>
                                    <?php endforeach;?>
                                </select>
                                <span class="invalid-feedback"><?= $this->session->flashdata('signup_validation_errors')['city'] ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="contact"><span
                        class="text-danger fw-bold">*</span>Contact No.</label>
                        <input type="text" class="form-control <?php if($this->session->flashdata('signup_validation_errors')['contact']){ echo 'is-invalid';} ?>" id="contact" name="contact"
                            placeholder="11-digit"  value="<?= set_value('contact', $this->session->flashdata('contact')); ?>" <?= ($author_type == 1) ? 'disabled' : '' ?> >
                            <span class="invalid-feedback"><?= $this->session->flashdata('signup_validation_errors')['contact'] ?></span>
                    </div>
                    <div class="mb-4">
                        <div class="row">
                            <div class="col">
                                <label class="form-label" for="new_password"><span class="text-danger fw-bold">*</span>Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control <?php if($this->session->flashdata('signup_validation_errors')['new_password']){ echo 'is-invalid';} ?>" id="new_password" name="new_password" placeholder="" value="<?= set_value('new_password', $this->session->flashdata('new_password')); ?>">
                                    <span class="input-group-text bg-white text-muted rounded-end" id="inputGroupPrepend3"><a class="text-muted cursor-pointer" href="javascript:void(0);" onclick="togglePassword('#new_password', '#new_passsword_icon')"><i class="fa fa-eye-slash" id="new_passsword_icon"></i></a></span>
                                    <span class="invalid-feedback"><?= $this->session->flashdata('signup_validation_errors')['new_password'] ?></span>
                                </div>
                            </div>
                            <div class="col">
                                <label class="form-label" for="confirm_password"><span
                                class="text-danger fw-bold">*</span>Confirm Password</label>
                                <div class="input-group">
                                <input type="password" class="form-control <?php if($this->session->flashdata('signup_validation_errors')['confirm_password']){ echo 'is-invalid';} ?>" id="confirm_password" name="confirm_password" placeholder="" value="<?= set_value('confirm_password', $this->session->flashdata('confirm_password')); ?>">
                                    <span class="input-group-text bg-white text-muted rounded-end" id="inputGroupPrepend3"><a class="text-muted cursor-pointer" href="javascript:void(0);"  onclick="togglePassword('#confirm_password', '#confirm_password_icon')"><i class="fa fa-eye-slash" id="confirm_password_icon"></i></a></span>
                                    <span class="invalid-feedback"><?= $this->session->flashdata('signup_validation_errors')['confirm_password'] ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                        
                    <div class="card mb-3 d-none" id="password_strength_container">
                        <div class="card-body text-secondary w-50">
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

                    <div class="mb-3 w-100" id="google_recaptchav2_container">
                        <div data-sitekey="6LcTEV8qAAAAACVwToj7gI7BRdsoEEhJCnnFkWC6" id="captcha_author"></div>
                        <p class="text-danger" id="g-recaptcha"></p>
                    </div>
                    
                    <button type="submit" class="btn main-btn w-100 <?= ($this->session->flashdata('author_type') > 0) ? '' : 'disabled' ?>" id="create_account">Create Account</button>
                <?=form_close()?>
            </div>
        </div>
        <div class="col col-lg-2 p-3">
            <?php $this->load->view('common/side_panel');?>
        </div>
    </div>
</div>
