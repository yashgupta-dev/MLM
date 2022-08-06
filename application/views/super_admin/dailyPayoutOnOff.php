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
	<link rel="stylesheet" href="<?=base_url()?>adminassets/vendors/core/core.css">
	<link rel="stylesheet" href="<?=base_url()?>adminassets/fonts/feather-font/css/iconfont.css">
  <link rel="stylesheet" href="<?=base_url()?>adminassets/css/demo_1/style.css">
  <link rel="stylesheet" href="<?=base_url()?>adminassets/css/custom.css">
  <link rel="stylesheet" href="<?=base_url()?>adminassets/css/animate.css">
  <link rel="stylesheet" href="<?=base_url()?>adminassets/css/toastr.min.css">
  <link rel="stylesheet" href="<?=base_url()?>adminassets/css/demo_1/jquery-confirm.css">
  <link rel="shortcut icon" href="<?=base_url()?>adminassets/site/<?=$site->favicon?>" />
</head>
<body>
<div class="container" style="margin-top:20%;">
    <div class="row">
        <div class="offset-md-3 col-md-6">
            <div class="text-center">
                <i class="fa fa-spin fa-spinner text-warning" style="font-size:60px;"></i>
                <p class="mt-1">wait a momment</p>
                <p><?php if($_GET['c'] == '1'){ echo 'we doing <strong>Off</strong> daily payout of member '.$_GET['q'];} elseif($_GET['c'] == '0'){ echo 'we doing <strong>On</strong> daily payout of member '.$_GET['q'];}?></p>
                <p><strong>Don't close tab & Browser, will be task complete browser automatically close!</strong></p>
            </div>
        </div>
    </div>
</div>
<script src="<?=base_url()?>adminassets/vendors/core/core.js"></script>
<script src="<?=base_url()?>adminassets/vendors/feather-icons/feather.min.js"></script>
<script src="<?=base_url()?>adminassets/js/template.js"></script>
<script src="<?=base_url()?>adminassets/js/dashboard.js"></script>
<script src="<?=base_url()?>adminassets/js/toastr.min.js"></script>
<script src="<?=base_url()?>adminassets/js/jquery-3.4.1.js"></script>
<script src="<?=base_url()?>adminassets/js/jquery-confirm.js"></script>
<script>
$(function(){
    setTimeout(function(){
        window.close();    
    },200);
});
</script>
</body>
</html>