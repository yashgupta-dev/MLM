<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

$site = $this->db->select('title,logo,favicon,meta_author,meta_desc,meta_keyword,loader,default_user')
        ->from('site_setting')
        ->get()->row();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="author" content="<?=$site->meta_author?>">
  <meta name="description" content="<?=$site->meta_desc?>">
  <meta name="keywords" content="<?=$site->meta_desc?>">
  <meta name="msapplication-tap-highlight" content="no">
  <meta name="<?= $this->security->get_csrf_token_name()?>" content="<?= $this->security->get_csrf_hash(); ?>">
  <title><?=$site->title?></title>
  <link rel="stylesheet" href="<?=base_url()?>adminassets/css/all.min.css">
	<link rel="stylesheet" href="<?=base_url()?>adminassets/fonts/feather-font/css/iconfont.css">
	<link rel="stylesheet" href="<?=base_url()?>adminassets/css/demo_1/style.css">
	<link rel="stylesheet" href="<?=base_url()?>adminassets/css/custom.css">
  <link rel="stylesheet" href="<?=base_url()?>adminassets/css/toastr.min.css">
  <link rel="stylesheet" href="<?=base_url()?>adminassets/css/demo_1/jquery-confirm.css">
  <link rel="shortcut icon" href="<?=base_url()?>adminassets/site/<?=$site->favicon?>" />
</head>
<body>
    <!-- LOADER -->
<div class="preloader">
    <div class="loader"></div> 
</div>
	<div class="main-wrapper">
		<div class="page-wrapper full-page" style="background:#1e4686;">
			<div class="page-content d-flex align-items-center justify-content-center">

				<div class="row w-100 mx-0 auth-page">
					<div class="col-md-8 col-xl-6 mx-auto">
						<div class="card">
							<div class="row">
                <div class="col-md-4 pr-md-0">
                  <div class="auth-form-wrapper px-4 py-5" style="width: 100%; height: 100%;background-image: url(<?=base_url()?>adminassets/site/<?=$site->logo?>); background-size: cover;">
                  </div>
                </div>
                <div class="col-md-8 pl-md-0">
                  <div class="auth-form-wrapper px-4 py-5">
                    <a href="<?=base_url()?>api/game" class="noble-ui-logo d-block mb-2">
                      <img src="<?=base_url()?>adminassets/site/<?=$site->logo?>"  class="img-fluid" style="height: 80px;" id="mobileHideShow">
                    </a>
                    <?php if($this->session->userdata('apiOtp') !=''){?>

                    <form class="otpform">
                      <div class="form-group">
                        <label for="sponser">Enter OTP <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="Otpveri" id="Otpveri" placeholder="_ _ _  _ _ _">
                        <small class="errotp mt-1 text-danger"></small>
                      </div>
                      <input type="hidden" name="<?= $this->security->get_csrf_token_name()?>" value="<?= $this->security->get_csrf_hash(); ?>">
                      <div class="mt-3">
                        <button type="button" id="otpVerifyapi" class="btn btn-primary text-white mr-2 mb-2 mb-md-0">Verify</button>
                      </div>
                    </form>

                  <?php } else{ ?>
                    <form class="create_form">
                      <div class="form-group">
                        <label for="sponser">Enter Phone Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="phone" id="phone" placeholder="phone number">
                        <div class="errorterms mt-1 text-danger"></div>
                      </div>
                      <input type="hidden" name="<?= $this->security->get_csrf_token_name()?>" value="<?= $this->security->get_csrf_hash(); ?>">
                      <div class="">
                        <small class="form-check-label">
                          i accept all <span class="text-danger">terms & condition</span>
                        </small>
                      </div>
                      <div class="mt-3">
                        <button type="button" id="api_create_acount" class="btn btn-primary text-white mr-2 mb-2 mb-md-0">Create account</button>
                      </div>
                      <a href="<?=base_url()?>api/form" class="d-block mt-3 text-muted">Already a user? Sign in</a>
                    </form>
                  <?php } ?>
                  
                  </div>
                </div>
              </div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>

	<!-- core:js -->
	<script src="<?=base_url()?>adminassets/vendors/core/core.js"></script>
	<script src="<?=base_url()?>adminassets/vendors/feather-icons/feather.min.js"></script>
	<script src="<?=base_url()?>adminassets/js/template.js"></script>
  <script src="<?=base_url()?>adminassets/js/toastr.min.js"></script>
  <script src="<?=base_url()?>adminassets/js/jquery-3.4.1.js"></script>
  <script src="<?=base_url()?>adminassets/js/api.js"></script>
  <script src="<?=base_url()?>adminassets/js/clipboard.min.js"></script>
  <script src="<?=base_url()?>adminassets/js/clipboard.mintwo.js"></script>
  <script src="<?=base_url()?>adminassets/js/jquery-confirm.js"></script>
</body>
</html>