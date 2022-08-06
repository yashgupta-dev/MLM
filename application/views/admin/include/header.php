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
<div class="main-wrapper">
    <nav class="sidebar">
      <div class="sidebar-header">
        <a href="javascript:;" class="sidebar-brand">
          <img src="<?=base_url()?>adminassets/site/<?=$site->logo?>"  class="img-fluid" style="height: 50px;">
        </a>
        <div class="sidebar-toggler not-active">
          <span></span>
          <span></span>
          <span></span>
        </div>
      </div>
      <div class="sidebar-body">
        <div class="moobileDeviceShow">
            <div class="profile-header">
			    <div class="cover">
				    <div class="gray-shade"></div>
						<figure style="position: absolute;">
						    <img src="<?=base_url()?>assets/back.jpg" class="img-fluid" alt="profile cover">
						</figure>
						<div class="cover-body d-flex justify-content-between align-items-center">
						    <div style="position: absolute; top:15px;"><?php $site = $this->db->select('default_user')->from('site_setting')->get()->row();
						    echo '<img class="profile-pic" src="'.base_url().'adminassets/images/faces/'.$site->default_user.'" alt="profile" style="width: 35px;">'; ?>
						        <span class="profile-name text-dark"><?=$this->session->userdata('name')?></span>
						    </div>
						</div>
					</div>
            </div>
        </div>
        <ul class="nav">
          <li class="nav-item nav-category text-dark">Main</li>
          <li class="nav-item">
            <a href="<?=base_url()?>my/dashboard" class="nav-link">
              <i class="link-icon fab fa-dashcube"></i>
              <span class="link-title">Dashboard</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#emails1" >
              <i class="link-icon" data-feather="user"></i>
              <span class="link-title">Profile Managment</span>
              <i class="link-arrow" data-feather="chevron-down"></i>
            </a>
            <div class="collapse" id="emails1">
              <ul class="nav sub-menu">
                <li class="nav-item">
                  <a href="<?=base_url()?>my/profile/update-password" class="nav-link">Change Password</a>
                </li>
                <li class="nav-item">
                  <a href="<?=base_url()?>my/profile/update-profile" class="nav-link">My Profile</a>
                </li>
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#emails2" >
              <i class="link-icon fa fa-users"></i>
              <span class="link-title">Team</span>
              <i class="link-arrow" data-feather="chevron-down"></i>
            </a>
            <div class="collapse" id="emails2">
              <ul class="nav sub-menu">
                  
                <li class="nav-item">
                    <a href="<?=base_url()?>my/team/Genealogy?q=<?=$this->session->userdata('user_id')?>" class="nav-link">My Binary Tree</a>
                </li>
                
                <li class="nav-item">
                  <a href="<?=base_url()?>my/team?q=Right" class="nav-link">My Right Team</a>
                </li>
                
                <li class="nav-item">
                  <a href="<?=base_url()?>my/team?q=Left" class="nav-link">My Left Team</a>
                </li>
                
                <li class="nav-item">
                  <a href="<?=base_url()?>my/team/Direct-sponser" class="nav-link">Direct Sponser</a>
                </li>
                
              </ul>
            </div>
          </li>

          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#income" >
              <i class="link-icon fa fa-money-check"></i>
              <span class="link-title">Income</span>
              <i class="link-arrow" data-feather="chevron-down"></i>
            </a>
            <div class="collapse" id="income">
              <ul class="nav sub-menu">
                <li class="nav-item">
                  <a href="<?=base_url()?>my/income?q=referral" class="nav-link">Referral income</a>
                </li>
                <li class="nav-item">
                  <a href="<?=base_url()?>my/income?q=binary" class="nav-link">Binary income</a>
                </li>
                <li class="nav-item">
                  <a href="<?=base_url()?>my/income?q=daily" class="nav-link">Daily income</a>
                </li>
              </ul>
            </div>
          </li>
          
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#epin" >
              <i class="link-icon" data-feather="key"></i>
              <span class="link-title">E-pin Managment</span>
              <i class="link-arrow" data-feather="chevron-down"></i>
            </a>
            <div class="collapse" id="epin">
              <ul class="nav sub-menu">
                <li class="nav-item">
                  <a href="<?=base_url()?>my/scratch?q=my-pin" class="nav-link">My E-pin</a>
                </li>
                <li class="nav-item">
                  <a href="<?=base_url()?>my/scratch?q=raise-request" class="nav-link">Raise Pin Request</a>
                </li>
                <li class="nav-item">
                  <a href="<?=base_url()?>my/scratch?q=my-request" class="nav-link">My Request</a>
                </li>
              </ul>
            </div>
          </li>
    
        <li class="nav-item nav-category text-dark">Registration</li>
          <li class="nav-item">
            <a href="<?=base_url()?>customer/Register-New/<?=$this->session->userdata('user_id')?>" target="_blank" class="nav-link">
              <i class="link-icon fa fa-user-plus"></i>
              <span class="link-title">Add New User</span>
            </a>
          </li>
          <li class="nav-item nav-category text-dark">Account</li>
          <!--li class="nav-item">
            <a href="my/widthdrow" class="nav-link">
              <i class="link-icon fab fa-accessible-icon"></i>
              <span class="link-title">Amount Widthdrow</span>
            </a>
          </li-->
          <li class="nav-item">
            <a href="<?=base_url()?>my/transaction" class="nav-link">
              <i class="link-icon fab fa-accessible-icon"></i>
              <span class="link-title">My Transaction's</span>
            </a>
          </li>
       
          <li class="nav-item nav-category text-dark">Log-out</li>
          <li class="nav-item">
            <a href="<?=base_url()?>my/log-out" class="nav-link">
              <i class="link-icon" data-feather="log-out"></i>
              <span class="link-title">Log-out</span>
            </a>
          </li>
        </ul>
      </div>
    </nav>
    
<div class="page-wrapper">
    <div class="row">
    <div class="offset-md-4 col-md-5">
    <nav class="navbar">
        <a href="javascript:;" class="sidebar-toggler">
            <i data-feather="menu"></i>
        </a>
        <div class="navbar-content">
            
          <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown nav-profile" id="menuIconBarHide">
              <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img src="<?=base_url()?>adminassets/images/faces/<?=$site->default_user?>" alt="profile">
                <span id="angleId">
                    <i class="fas fa-angle-down text-dark"></i>
                </span>
              </a>
              <div class="dropdown-menu" aria-labelledby="profileDropdown">
                <div class="dropdown-header d-flex flex-column align-items-center">
                    <img src="<?=base_url()?>adminassets/images/faces/<?=$site->default_user?>" alt="profile" style="width:80px;">
                  <div class="info text-center">
                    <p class="email text-muted mb-3"> hello, <?=$this->session->userdata('name')?></p>
                    <p class="email text-muted mb-3" id="getUserNameUsingJavascript"><?=$this->session->userdata('username')?></p>
                  </div>
                </div>
                <div class="dropdown-body">
                  <ul class="profile-nav p-0 pt-3">
                    <li class="nav-item">
                      <a href="<?=base_url()?>my/profile/update-profile" class="nav-link">
                        <i data-feather="edit"></i>
                        <span>Edit Profile</span>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="<?=base_url()?>my/profile/update-password" class="nav-link">
                        <i data-feather="edit"></i>
                        <span>Change Password</span>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="<?=base_url()?>customer/Register-New/<?=$this->session->userdata('user_id')?>" target="_blank" class="nav-link">
                        <i data-feather="plus-circle"></i>
                        <span>Add Team Member</span>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="<?=base_url()?>my/log-out" class="nav-link">
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
          </div>
        </div>
