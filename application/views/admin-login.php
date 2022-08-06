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
  <link rel="stylesheet" href="<?=base_url()?>adminassets/fonts/feather-font/css/iconfont.css">
  <link rel="stylesheet" href="<?=base_url()?>adminassets/css/demo_1/style.css">
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
		<div class="page-wrapper full-page">
			<div class="page-content d-flex align-items-center justify-content-center">

				<div class="row w-100 mx-0 auth-page">
					<div class="col-md-8 col-xl-6 mx-auto">
						<div class="card">
							<div class="row">
                <div class="col-md-4 pr-md-0">
                  <div class="auth-form-wrapper px-4 py-5" style="width: 100%; height: 100%;background-image: url(<?=base_url()?>adminassets/images/pattern.png);">
                  </div>
                </div>
                <div class="col-md-8 pl-md-0">
                  <div class="auth-form-wrapper px-4 py-5">
                    <a href="<?=base_url()?>" class="noble-ui-logo d-block mb-2">
                      <img src="<?=base_url()?>adminassets/site/<?=$site->logo?>" class="img-fluid" style="width:80px;">
                    </a>
                    <form class="sign_in_form_second">
                      <div class="form-group">
                        <label for="exampleInputEmail1">Email address</label>
                        <input type="text" name="username" class="form-control" id="exampleInputEmail12" placeholder="Email">
                      </div>
                      <input type="hidden" name="<?= $this->security->get_csrf_token_name()?>" value="<?= $this->security->get_csrf_hash(); ?>">
                      <div class="form-group">
                        <label for="exampleInputPassword1">Password</label>
                        <input type="password" name="password" class="form-control" id="exampleInputPassword12" autocomplete="current-password" placeholder="Password">
                      </div>
                      <div class="mt-3">
                        <button type="button" id="sign_in_btn_second" class="btn btn-primary text-white mr-2 mb-2 mb-md-0">Sing in</button>
                       
                      </div>
                    </form>
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
  <script type="text/javascript">
    $(function(){
      <?php echo $this->session->flashdata('acc');?>    
    });
    
  </script>
  
</body>
</html>