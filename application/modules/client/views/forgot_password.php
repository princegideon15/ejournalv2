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
        <div class="col col-lg-4">

        </div>
        <div class="col col-lg-6 p-3">
            <!-- <h2>Forgot your password</h2> -->
            
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="login-tab" data-bs-toggle="tab"
                        data-bs-target="#login-tab-pane" type="button" role="tab" aria-controls="login-tab-pane"
                        aria-selected="true">Reset Password</button>
                </li>
            </ul>
            
            <div class="tab-content" id="myTabContent">
                    <!-- Login -->
                    <div class="tab-pane fade show active p-3" id="login-tab-pane" role="tabpanel" aria-labelledby="home-tab"
                        tabindex="0">
                        <?php if ($this->session->flashdata('error')) { ?>
                            <div class="alert alert-danger d-flex align-items-center">
                                <i class="oi oi-circle-x me-1"></i><?php echo $this->session->flashdata('error'); ?>
                            </div>
                        <?php } ?>

                        <?php if ($this->session->flashdata('reset_password_success')) { ?>
                            <h1><i class="oi oi-circle-check me-1 text-success fs-3"></i>Success!</h1>
                            <div class="alert alert- border" role="alert">
                                <?php echo $this->session->flashdata('reset_password_success'); ?>
                                <div><a class="btn main-btn mt-3" href="<?= base_url('client/login') ?>">Continue to Login</a></div>
                            </div>
                        <?php } else { ?>

                           
                            <label for="email" class="form-label">Enter your email address and we will send you instructions on how to reset your password.</label>
                            <?=form_open('client/login/reset_password', ['method' => 'post', 'id' => 'resetPasswordForm', 'class' => 'w-50'])?>
                                <div class="mb-3">
                                    <input type="email" class="form-control <?php if($this->session->flashdata('validation_errors')['email']){ echo 'is-invalid';} ?>" id="email" name="email" placeholder="name@example.com">
                                    <span class="invalid-feedback"><?=$this->session->flashdata('validation_errors')['email']?></span>
                                </div>
                                <button type="submit" class="btn main-btn mt-1 w-100" onclick="disableOnSubmit(this, '#resetPasswordForm', 'reset')">Submit </button>
                            <?=form_close()?>

                            <div class="mt-5"><a class="main-link" href="<?= base_url('client/login') ?>">Back to Login</a></div>
                        <?php } ?>
                    </div>
            </div>
        </div>
        <div class="col col-lg-2 p-3">
            <?php $this->load->view('common/side_panel');?>
        </div>
    </div>
</div>
   