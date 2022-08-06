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
                    <a href="<?=base_url()?>" class="noble-ui-logo d-block mb-2">
                      <img src="<?=base_url()?>adminassets/site/<?=$site->logo?>" class="img-fluid" style="width:50%;" id="mobileHideShow">
                    </a>
                    <?php if($this->session->userdata('otp') !=''){ ?>
                    
                    <form class="otp_verify" method="post">
                      <div class="form-group">
                        <label for="forget_member">Enter Otp</label>
                        <input type="text" name="otp" class="form-control" id="otp">
                        <small style="font-weight:800;" id="otpError"></small>
                      </div>
                      <input type="hidden" name="<?= $this->security->get_csrf_token_name()?>" value="<?= $this->security->get_csrf_hash(); ?>">
                      <div class="mt-3">
                        <button type="button" id="otp_btn" class="btn btn-primary text-white mr-2 mb-2 mb-md-0">verify</button>
                      </div>
                    </form>
                    
                    <?php }elseif($this->session->userdata('chng') !=''){ ?>
                    
                    <form class="chng_verify" method="post">
                      <div class="form-group">
                        <label for="forget_member">New Password</label>
                        <input type="password" name="password" class="form-control" id="new_pass">
                        <small style="font-weight:800;" id="newpass"></small>
                      </div>
                      <div class="form-group">
                        <label for="forget_member">Confirm Password</label>
                        <input type="password" name="cnfpassword" class="form-control" id="cnf_pass">
                        <small style="font-weight:800;" id="cnfpass"></small>
                      </div>
                      <input type="hidden" name="<?= $this->security->get_csrf_token_name()?>" value="<?= $this->security->get_csrf_hash(); ?>">
                      <div class="mt-3">
                        <button type="button" id="chng_btn" class="btn btn-primary text-white mr-2 mb-2 mb-md-0">Change password</button>
                      </div>
                    </form>
                    
                    <?php } else { ?>
                    
                    <form class="forget_form">
                      <div class="form-group">
                        <label for="forget_member">Enter Member ID</label>
                        <input type="text" name="member" class="form-control" id="forget_member" placeholder="member id">
                        <small style="font-weight:800;" id="errorMsg"></small>
                      </div>
                      <input type="hidden" name="<?= $this->security->get_csrf_token_name()?>" value="<?= $this->security->get_csrf_hash(); ?>">
                      <div class="mt-3">
                        <button type="button" id="forget_btn" class="btn btn-primary text-white mr-2 mb-2 mb-md-0">forget ?</button>
                      </div>
                    </form>
                    
                    <?php }?>
                    <a href="<?=base_url()?>" class="d-block mt-3 text-muted"><i class="fas fa-angle-left"></i> back</a>
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
  <script src="<?=base_url()?>adminassets/js/script.js"></script>
  <script src="<?=base_url()?>adminassets/js/jquery-confirm.js"></script>
</body>
</html>