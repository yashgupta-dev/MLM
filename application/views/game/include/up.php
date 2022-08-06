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
<meta http-equiv='cache-control' content='no-cache'>
<meta http-equiv='expires' content='0'>
<meta http-equiv='pragma' content='no-cache'>
  <meta name="<?= $this->security->get_csrf_token_name()?>" content="<?= $this->security->get_csrf_hash(); ?>">
  <title><?=$site->title?></title>
  <link rel="stylesheet" href="<?=base_url()?>adminassets/css/all.min.css">
  <link rel="stylesheet" href="<?=base_url()?>adminassets/vendors/core/core.css">
  <link rel="stylesheet" href="<?=base_url()?>adminassets/fonts/feather-font/css/iconfont.css">
  <link rel="stylesheet" href="<?=base_url()?>adminassets/css/demo_1/style.css">
  <link rel="stylesheet" href="<?=base_url()?>adminassets/css/custom.css">
  <link rel="stylesheet" href="<?=base_url()?>adminassets/css/animate.css">
  <link rel="stylesheet" href="<?=base_url()?>adminassets/css/dropzone.css">
  <link rel="stylesheet" href="<?=base_url()?>adminassets/css/toastr.min.css">
  <link rel="stylesheet" href="<?=base_url()?>adminassets/css/demo_1/jquery-confirm.css">
  <link rel="shortcut icon" href="<?=base_url()?>adminassets/site/<?=$site->favicon?>" />
</head>
<body>
    <style>
        body { 
            animation: fadeInAnimation ease 3s; 
            animation-iteration-count: 1; 
            animation-fill-mode: forwards; 
        } 
        @keyframes fadeInAnimation { 
            0% { 
                opacity: 0; 
            } 
            100% { 
                opacity: 1; 
            } 
        } 
        .preloader { display: none;  }
		.preloader { display: block; position: absolute; top: 0; }
    </style>
<!-- LOADER -->
<div class="preloader">
    <div class="loader"></div> 
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 bg-dark text-white p-3  fixed-top">
            <div class="d-flex justify-content-between">
                <div class=""><?php if($this->session->userdata('heading')) {?><a href="<?=base_url()?>api/game" class="text-white"><i class="fa fa-angle-left"></i> <?=$this->session->userdata('heading')?> </a><?php }?></div>
                <div class="">
                    <i class="fa fa-wallet"></i> 
                    <span class="ml-1 border-right mr-1"></span> <span class="walletShowBalnce1">₹
                    <?php 
                    $txn =  $this->db->where('user',$this->session->userdata('gameuser'))
                        //->where('gate','app')    
                        ->where('dr_cr','Dr')    
                        ->where('order','Withdraw Amount')
                        ->where('status','SUCCESS')
                        // ->where('order !=','Join Megacontest')
                        // ->where('order !=','Upgrade Account')
                        ->select('t_amt')
                        ->from('transaction')
                        ->get()->result();    
                            $total = 0;
                            
                            foreach ($txn as $key) {
                                $total += $key->t_amt;
                            }
                        $wallet = $this->db->where('user',$this->session->userdata('gameuser'))->select('amount')->from('wallet')->get()->row('amount');
                        if($total > $wallet){
                             $n = 0;        
                        }else{
                        $n = $wallet-$total;
                        }

                        if(!empty($n)){
    $n = (0+str_replace(",", "", $n));
        // is this a number?
        if (!is_numeric($n)){
          echo false;
        }
        if ($n > 1000000000000) {
          echo round(($n/1000000000000), 2).'T';

        }elseif ($n > 1000000000) {
          echo round(($n/1000000000), 2).'B';

        }elseif ($n > 1000000) {
          echo round(($n/1000000), 2).'M';

        }elseif ($n > 1000) {
          echo round(($n/1000), 2).'K';

        }else{
          echo $n;
        }
      }else{
        echo 0;
      }

                        ?>
                    </span>
                    <span class="ml-4 mr-1"></span> <span><a class="text-white h5" href="<?=base_url()?>api/app-setting"><i class="fa fa-cog"></i></a></span>
                </div>
            </div>
        </div>
    </div>
</div>
