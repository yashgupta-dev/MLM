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

	<div class="main-wrapper">
		<nav class="sidebar">
      <div class="sidebar-header">
        <a href="#" class="sidebar-brand">
          <img src="<?=base_url()?>adminassets/site/<?=$site->logo?>"  class="img-fluid" style="height: 50px;">
        </a>
        <div class="sidebar-toggler not-active">
          <span></span>
          <span></span>
          <span></span>
        </div>
      </div>
      <div class="sidebar-body">
        <ul class="nav">
          <li class="nav-item nav-category text-dark">Dashboard</li>
          <li class="nav-item">
            <a href="<?=base_url()?>admin/dashboard" class="nav-link">
              <i class="link-icon fab fa-dashcube" data-feather="box"></i>
              <span class="link-title">Overview</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#emails1" >
              <i class="link-icon" data-feather="user"></i>
              <span class="link-title">Profile/Account Manage</span>
              <i class="link-arrow" data-feather="chevron-down"></i>
            </a>
            <div class="collapse" id="emails1">
              <ul class="nav sub-menu">
                <li class="nav-item">
                  <a href="javascript:;" class="nav-link">Profile <span class="text-warning"> (comming soon)</span></a>
                </li>                
                <li class="nav-item">
                  <a href="<?=base_url()?>admin/rate-manage" class="nav-link">Insterest/Default Amount</a>
                </li>
                
              </ul>
            </div>
          </li>
          <li class="nav-item nav-category text-dark">Income History & Manage</li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#history" >
              <i class="link-icon fa fa-money-check"></i>
              <span class="link-title">Income History & Manage</span>
              <i class="link-arrow" data-feather="chevron-down"></i>
            </a>
            <div class="collapse" id="history">
              <ul class="nav sub-menu">
                <li class="nav-item">
                  <a href="<?=base_url()?>admin/income?q=referral" class="nav-link">Referral income</a>
                </li>
                <li class="nav-item">
                  <a href="<?=base_url()?>admin/payout" class="nav-link">Daily income</a>
                </li>
                <li class="nav-item">
                  <a href="<?=base_url()?>admin/binary-income" class="nav-link">Binary Income</a>
                </li>
                <li class="nav-item">
                  <a href="<?php echo site_url();?>admin/payment-control" class="nav-link">Member Payment Manage</a>
                </li>
                <li class="nav-item">
                  <a href="<?php echo site_url();?>admin/package-manage" class="nav-link">Package Manage</a>
                </li>
              </ul>
            </div>
          </li>
          <li class="nav-item nav-category text-dark">All Members</li>
          <li class="nav-item">
            <a href="<?php echo site_url();?>admin/all-user" class="nav-link">
              <i class="link-icon fa fa-users"></i>
              <span class="link-title">All Member's</span>
            </a>
          </li>
          <li class="nav-item nav-category text-dark">Bank Accounts</li>
          <li class="nav-item">
            <a href="<?php echo site_url();?>admin/all-bank-accounts" class="nav-link">
              <i class="link-icon fa fa-money-check-alt"></i>
              <span class="link-title">Bank Accounts</span>
            </a>
          </li>
          <li class="nav-item nav-category text-dark">Pin Manage</li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#emails23" >
              <i class="link-icon" data-feather="key"></i>
              <span class="link-title">E-pin Managment</span>
              <i class="link-arrow" data-feather="chevron-down"></i>
            </a>
            <div class="collapse" id="emails23">
              <ul class="nav sub-menu">
                <li class="nav-item">
                  <a href="<?php echo site_url();?>admin/scratch-request" class="nav-link">E-pin Request</a>
                </li>
                
              </ul>
            </div>
          </li>
          
        
          <li class="nav-item nav-category text-dark">Winners Announcement</li>
          <li class="nav-item">
            <a href="<?=base_url()?>admin/winners-annoucment" class="nav-link">
              <i class="link-icon fa fa-share"></i>
              <span class="link-title">Annouce</span>
            </a>
          </li>
          
          <li class="nav-item nav-category text-dark">Rewards Manage</li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#reward" >
              <i class="link-icon fa fa-trophy"></i>
              <span class="link-title">Rewards Manage</span>
              <i class="link-arrow" data-feather="chevron-down"></i>
            </a>
            <div class="collapse" id="reward">
              <ul class="nav sub-menu">
                <li class="nav-item">
                  <a href="<?php echo site_url();?>admin/rewards" class="nav-link">My Rewards</a>
                </li>                
              </ul>
            </div>
          </li>
          
          <li class="nav-item nav-category text-dark">KYC</li>
          <li class="nav-item">
            <a href="<?=base_url()?>admin/user-kyc" class="nav-link">
              <i class="link-icon fa fa-shield-alt"></i>
              <span class="link-title">KYC Approval</span>
            </a>
          </li>
          <li class="nav-item nav-category text-dark">Transaction</li>
          <li class="nav-item">
            <a href="<?=base_url()?>admin/transaction-request" class="nav-link">
              <i class="link-icon fa fa-money-check-alt"></i>
              <span class="link-title">Transaction history</span>
            </a>
          </li>
          
          
        <li class="nav-item nav-category text-dark">Site Manage</li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#admin" >
              <i class="link-icon fa fa-cog"></i>
              <span class="link-title">Site Manage</span>
              <i class="link-arrow" data-feather="chevron-down"></i>
            </a>
            <div class="collapse" id="admin">
              <ul class="nav sub-menu">
                <li class="nav-item">
                  <a href="<?php echo site_url();?>admin/site-setting" class="nav-link">Site Setting</a>
                </li>                
              </ul>
            </div>
          </li>
          
          <li class="nav-item nav-category text-dark">BULK/SINGLE REGISTER USER</li>
          <li class="nav-item">
            <a href="<?=base_url()?>admin/create-user-bulk" class="nav-link">
              <i class="link-icon fa fa-users"></i>
              <span class="link-title">REGISTER</span>
            </a>
          </li>
          
          
          
          
          

          <li class="nav-item nav-category text-dark">Log-out</li>
          <li class="nav-item">
            <a href="<?=base_url()?>admin/log-out" class="nav-link">
              <i class="link-icon" data-feather="log-out"></i>
              <span class="link-title">Log-out</span>
            </a>
          </li>
          <li class="nav-item nav-category text-dark">Tournament Manage</li>
          <li class="nav-item">
            <a href="<?=base_url()?>admin/tournament?q=list" class="nav-link bg-dark p-3" style="border-radius:35px 35px 35px 35px;">
              <span class="link-title text-white"><i class="fa fa-gamepad"></i> Tournament </span>
            </a>
          </li>
        </ul>
      </div>
    </nav>
 		<!-- partial -->

    <div class="page-wrapper">
      <div class="row">
          <div class="offset-md-4 col-md-5">
                      <!-- partial:../../partials/_navbar.html -->
      <nav class="navbar">
        <a href="#" class="sidebar-toggler">
          <i data-feather="menu"></i>
        </a>
        <div class="navbar-content">
            <a href="<?=base_url()?>admin.manage/database" class="nav-link" style="margin-top:10px;">
                <i class="fa fa-cog text-dark h4"></i> <span class="text-dark" style="vertical-align: text-bottom;">Manage</span>
            </a>
          <ul class="navbar-nav ml-auto">
            
            <li class="nav-item dropdown nav-profile">
              <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img src="<?=base_url()?>adminassets/images/faces/<?=$site->default_user?>" alt="profile">
                <span id="angleId">
                    <i class="fas fa-angle-down"></i>
                    
                </span>
                
              </a>
              <div class="dropdown-menu" aria-labelledby="profileDropdown">
                <div class="dropdown-header d-flex flex-column align-items-center">
                    <img src="<?=base_url()?>adminassets/images/faces/<?=$site->default_user?>" alt="profile" style="width:80px;">
                  <div class="info text-center">
                    <p class="email text-muted mb-3"> hello, <?=$this->session->userdata('admin')?></p>
                    <p class="email text-muted mb-3"><?=$this->session->userdata('admin_email')?></p>
                  </div>
                </div>
                <div class="dropdown-body">
                  <ul class="profile-nav p-0 pt-3">
                    <li class="nav-item">
                      <a href="javascript:;" class="nav-link">
                        <i data-feather="edit"></i>
                        <span>Profile <strong class="text-warning">(comming soon)</strong></span>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="javascript:;" class="nav-link">
                        <i data-feather="edit"></i>
                        <span>Change Password <strong  class="text-warning">(comming soon)</strong></span>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="<?=base_url()?>admin/log-out" class="nav-link">
                        <i data-feather="log-out"></i>
                        <span>Log Out</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </div>
            </li>
          </ul>
        </div>
      </nav>
      <!-- partial -->              
          </div>
      </div>  
