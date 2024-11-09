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
            <h2 class="mb-5">Login Verification</h2>
            <!-- Login -->
            <?php if ($this->session->flashdata('otp')) { ?>
                <?php echo $this->session->flashdata('otp'); ?>
            <?php } ?>
            
            <?=form_open('client/login/verify_otp/'.$this->session->userdata('otp_ref_code') ?? '', ['method' => 'post', 'id' => 'verifyOTPForm'])?>
                <div class="mb-3">
                    <label for="otp" class="form-label">Enter the 6-digit One-Time-Password (OTP) below</label>
                    <input type="otp" class="form-control w-50 <?php if($this->session->flashdata('validation_errors')['otp']){ echo 'is-invalid';} ?>" id="otp" name="otp" placeholder="6 digit code">
                    <span class="invalid-feedback"><?=$this->session->flashdata('validation_errors')['otp']?></span>
                    <input type="hidden" name="ref" value="<?= $this->session->userdata('otp_ref_code') ?? '' ?>">
                </div>
                <button type="submit" class="btn main-btn w-50 mt-1 <?= $disabled ?>" id="verify_code" onclick="disableOnSubmit(this, '#verifyOTPForm', 'verify')">Verify Code</button>
                <a type="button" class="btn main-btn w-50 mt-1 disabled" id="resend_code" onclick="disableOnSubmit(this, '#verifyOTPForm', 'resend')">Resend Code</a>
            <?=form_close()?>
            <div class="mt-5"><a class="main-link" href="<?= base_url('client/ejournal/login') ?>">Back to Login</a></div>
        </div>
        <div class="col col-lg-2 p-3">
            <?php $this->load->view('common/side_panel');?>
        </div>
    </div>
</div>