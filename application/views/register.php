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
                      <img src="<?=base_url()?>adminassets/site/<?=$site->logo?>"  class="img-fluid" style="height: 80px;" id="mobileHideShow">
                    </a>
                    <h5 class="text-muted font-weight-normal mb-4">Create a free account.</h5>
                    <form class="signup_form">
                      <div class="form-group">
                        <label for="sponser">Sponser ID <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="sponser" id="sponser" autocomplete="off" placeholder="Sponser ID" 
                        value="<?php if($this->uri->segment(3)){echo $this->uri->segment(3);}?>">
                        <div class="mt-2 text-success"><small id="pleaseW"></small></div>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputname1">Full name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" id="exampleInputname1" autocomplete="Full name" placeholder="Full name">
                      </div>
                      <div class="form-group">
                        <label>Select Position <span class="text-danger">*</span></label>
                        <select name="side" id="side" class="form-control">
                            <option value="">Choose postion</option>
                            <option value="Right">Right</option>
                            <option value="Left">Left</option>
                        </select>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputEmail2">Email address <span class="text-danger">*</span></label>
                        <input type="email" name="email"  class="form-control" id="exampleInputEmail2" placeholder="Email">
                      </div>
                      <div class="form-group">
                        <label for="exampleInputPhone1">Phone <span class="text-danger">*</span></label>
                        <input type="text" name="phone_number" class="form-control" id="exampleInputPhone1" autocomplete="Phone" placeholder="phone">
                      </div>
                      <div class="form-group">
                        <label for="exampleInputPassword2">Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control" id="exampleInputPassword2" autocomplete="current-password" placeholder="Password">
                      </div>
                      <input type="hidden" name="<?= $this->security->get_csrf_token_name()?>" value="<?= $this->security->get_csrf_hash(); ?>">
                      <div class="form-group">
                        <label for="cnf">Confirm Password <span class="text-danger">*</span></label>
                        <input type="password" name="cnfpassword" class="form-control" id="cnf" autocomplete="current-password" placeholder="Password">
                      </div>
                      <div class="">
                            <small>
                                By continuing, you agree to Smart Go Game <span class="text-primary">Conditions of Use</span> and <span class="text-primary">Privacy Notice</span>.
                            </small>
                        </div>
                      <div class="mt-3">
                        <button type="button" id="sign_up_btn" class="btn btn-primary text-white mr-2 mb-2 mb-md-0">Sing up</button>
                       
                      </div>
                      
                      <a href="<?=base_url()?>customer/login-to-account" class="d-block mt-3 text-muted">Already a user? Sign in</a>
                    </form>
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
  <script src="<?=base_url()?>adminassets/js/clipboard.min.js"></script>
  <script src="<?=base_url()?>adminassets/js/clipboard.mintwo.js"></script>
  <script src="<?=base_url()?>adminassets/js/jquery-confirm.js"></script>
  <script>
      $(document).ready(function(){
        $('#sponser').keyup(function(){
            
            let token = $("meta[name='csrf_test_name']").attr("content");
            $.ajax({
                url:'/ftech_username',
                method:'post',
                dataType:'json',
                data:{'csrf_test_name':token,id:$(this).val()},
                beforeSend:function()
                {
                    $('#pleaseW').html('please wait <i class="fa fa-spin fa-spinner" style="font-size:9px;"></i>');
                },
                success:function(data){
                    
                    if(data.n){
                        $('#pleaseW').html(data.n);    
                    }else{
                        $('#pleaseW').html('unknown');    
                    }
                    
                }
            });
          
         });
    });
  </script>
</body>
</html>