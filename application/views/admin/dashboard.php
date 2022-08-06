      <div class="page-content">
        <div class="row">
          <div class="col-12 col-xl-12 stretch-card">
            <!------------------ ROI --------------------------->
            <div class="row flex-grow">
            <?php $ust = $this->db->where('member_id',$this->session->userdata('username'))->select('status')->from('login')->get()->row('status');?>
            <div class="RightSide10">
                <div class="row">
                    <div class="d-flex">
                        <div class="icon10">
                            <i class="fas fa-user-circle <?php if($ust == '0'){ echo 'bg-success';}elseif($ust == '1'){ echo 'bg-warning';}elseif($ust == '2'){ echo 'bg-secondary';}elseif($ust == '3'){ echo 'bg-danger';}?>"></i>
                        </div>
                        <div class="ml-2 textStyle10" id="showOnhover10">
                            <p><small>My Status</small></p>
                            <h4 id="panelstatus">....</h4>
                        </div>
                    </div>
                </div>
            </div>
            
            <!--working do-->
            <div class="RightSide4" style="margin-top:60px;">
                <div class="row">
                    <div class="d-flex">
                        <div class="icon4">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <div class="ml-2 textStyle4" id="showOnhover4">
                            <p><small>Wallet Balance</small></p>
                            <h4 id="balance">0.00</h4>
                        </div>
                    </div>
                </div>
            </div>
            
            <!--working do-->
            <div class="RightSide3" style="margin-top:120px;">
                <div class="row">
                    <div class="d-flex">
                        <div class="icon3">
                            <i class="fab fa-cc-amazon-pay"></i>
                        </div>
                        <div class="ml-2 textStyle3" id="showOnhover3">
                            <p><small>Total Transfer</small></p>
                            <h4 id="trns">0.00</h4>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php $st = $this->db->where('member_id',$this->session->userdata('username'))->select('appswitch')->get('login')->row('appswitch');?>
            <div class="RightSide<?php if($st== '0'){echo '8';} elseif($st == '1'){echo '7';}?>" style="margin-top:180px;">
                <div class="row">
                    <div class="d-flex">
                        <div class="icon<?php if($st== '0'){echo '8';} elseif($st == '1'){echo '7';}?>">
                            <i class="fas fa-mobile <?php if($st== '0'){echo 'bg-warning';} elseif($st == '1'){echo 'bg-success';}?>"></i>
                        </div>
                        <div class="ml-2 textStyle7" id="showOnhover<?php if($st== '0'){echo '8';} elseif($st == '1'){echo '7';}?>">
                            <p><small>App Switch ON/OFF</small></p>
                            <h4>
                                <form name="onOffform" action="<?=base_url()?>my/app_swicthAccess"  method="post">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input"<?php if($st == '1'){?> checked <?php } elseif($st == '0'){?> <?php } ?>value="1" name="appswitch" id="appswitch">
                                        <label class="custom-control-label" for="appswitch">
                                            <?php $st = $this->db->select('appswitch')->where('member_id',$this->session->userdata('username'))->get('login')->row('appswitch');?>
                                            <?php if($st == '1'){?>On<?php } elseif($st == '0'){?> Off<?php } ?>
                                        </label>
                                    </div>
                                  <input name="<?= $this->security->get_csrf_token_name()?>" value="<?= $this->security->get_csrf_hash(); ?>" type="hidden">
                                </form>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="RightSide2" style="margin-top:240px;">
                <div class="row">
                    <div class="d-flex">
                        <div class="icon2">
                            <i class="fa fa-copy" style="padding: 10px 10px !important;"></i>
                        </div>
                        <div class="ml-2 textStyle2" id="showOnhover2">
                            <p><small>Refer link</small>  <span class="ml-2" id="lossprofit"></span></p>
                            <a href="javascript:;" class="float-right" url="<?php echo base_url().'customer/Register-New/'.$this->session->userdata('user_id');?>" id="copy-me">
                                    click to copy
                            </a>
                            
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="RightSide5" style="margin-top:300px;">
                <div class="row">
                    <div class="d-flex">
                        <div class="icon5">
                            <i class="fas fa-lightbulb bg-warning" style="padding:8px 10px; font-size:16px;"></i>
                        </div>
                        <div class="ml-2 textStyle5" id="showOnhover5">
                            <p><small>My BV</small></p>
                            <a href="<?=base_url()?>my/rewards"class="text-dark"><h4 id="ROIStatus">0</h4></a>
                        </div>
                    </div>
                </div>
            </div>
            
            
            <div class="col-md-12 grid-margin strech-card">
                <nav aria-label="breadcrumb" class="bg-light">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page"><i class="fa fa-angle-down mt-1"></i>&nbsp;YOUR PANEL <strong>(<i>welcome</i>, <?=$this->session->userdata('name')?>)</strong></li>
                  </ol>
                </nav>
            </div>
            <!-- left today Business -->
            <div class="col-md-3 grid-margin stretch-card">
                <div class="card" style="background-image: linear-gradient(to right, #099283,#6cb6ed00),url(<?=base_url()?>adminassets/userimg/dashboard/up-left.png);background-repeat: no-repeat;background-size: contain;background-position: right;">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline">
                      <h6 class="card-title mb-0 text-white">Today Left Business</h6>
                    </div>
                    <hr>
                      <div class="d-flex justify-content-between" id="todayleftBusin">
                        <div class="incomeText">
                          <h4 class="mb-2" id="todayleftBusiness">0</h4>
                        </div>
                      </div>
                  </div>
                </div>
            </div>
            
            <!-- Right today Business -->
            <div class="col-md-3 grid-margin stretch-card">
                <div class="card" style="background-image: linear-gradient(to right, #099283,#6cb6ed00),url(<?=base_url()?>adminassets/userimg/dashboard/up-right.png);background-repeat: no-repeat;background-size: contain;background-position: right;">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline">
                      <h6 class="card-title mb-0" id="textColor">Today Right Business</h6>                      
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between" id="todayrightBusin">
                        <div class="incomeText">
                          <h4 class="mb-2" id="todayrightBusiness">0</h4>
                        </div>
                    </div>
                  </div>
                </div>
            </div>
            
            <!-- left  Business -->
            <div class="col-md-3 grid-margin stretch-card">
                <div class="card" style="background-image: linear-gradient(to right, #099283,#6cb6ed00),url(<?=base_url()?>adminassets/userimg/dashboard/up-left.png);background-repeat: no-repeat;background-size: contain;background-position: right;">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline">
                      <h6 class="card-title mb-0 text-white">My Left Business</h6>
                    </div>
                    <hr>
                      <div class="d-flex justify-content-between" id="todayleftBusin">
                        <div class="incomeText">
                          <h4 class="mb-2" id="leftBusiness">0</h4>
                        </div>
                      </div>
                  </div>
                </div>
            </div>
            
            <!-- Right  Business -->
            <div class="col-md-3 grid-margin stretch-card">
                <div class="card" style="background-image: linear-gradient(to right, #099283,#6cb6ed00),url(<?=base_url()?>adminassets/userimg/dashboard/up-right.png);background-repeat: no-repeat;background-size: contain;background-position: right;">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline">
                      <h6 class="card-title mb-0" id="textColor">My Right Business</h6>                      
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between" id="todayrightBusin">
                        <div class="incomeText">
                          <h4 class="mb-2" id="rightBusiness">0</h4>
                        </div>
                    </div>
                  </div>
                </div>
            </div>
            
            <!--- Right total member -->
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card" style="background-image: linear-gradient(130deg, #4e514f,#454c52cc),url(<?=base_url()?>adminassets/userimg/dashboard/group.png);background-repeat: no-repeat;background-size: contain;background-position: right;">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline">
                      <h6 class="card-title mb-0 text-white">My Right Downline</h6>
                    </div>
                    <hr>
                      <div class="d-flex justify-content-between" id="totalMember">
                        <div class="incomeText">
                          <h4 class="mb-2" id="RightTeam">0</h4>
                        </div>
                      </div>
                  </div>
                </div>
            </div>
            
            <!-- left Downline -->  
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card" style="background-image: linear-gradient(130deg, #4e514f,#454c52cc),url(<?=base_url()?>adminassets/userimg/dashboard/group.png);background-repeat: no-repeat;background-size: contain;background-position: right;">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline">
                      <h6 class="card-title mb-0 text-white">My Left Downline</h6>
                    </div>
                    <hr>
                      <div class="d-flex justify-content-between" id="totalMember">
                        <div class="incomeText">
                          <h4 class="mb-2" id="LeftTeam">0</h4>
                        </div>
                      </div>
                  </div>
                </div>
            </div>  
            
            <!-- left Downline -->  
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card" style="background-image: linear-gradient(130deg, #4e514f,#454c52cc),url(<?=base_url()?>adminassets/userimg/dashboard/share.png);background-repeat: no-repeat;background-size: contain;background-position: right;">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline">
                      <h6 class="card-title mb-0 text-white">Referral Income</h6>
                    </div>
                    <hr>
                      <div class="d-flex justify-content-between" id="refrral">
                        <div class="incomeText">
                          <h4 class="mb-2" id="direct_income">0</h4>
                        </div>
                      </div>
                  </div>
                </div>
            </div>  
              
            
            <!--Total Binary income-->
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card" style="background-image: linear-gradient(130deg, #f5e49e,#f5e49ea3),url(<?=base_url()?>adminassets/userimg/dashboard/binary.png);background-repeat: no-repeat;background-size: contain;background-position: right;">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline">
                      <h6 class="card-title mb-0" id="textColor">Team Bonus</h6>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between" id="directIncomeIcon">
                        <div class="incomeText">
                          <h4 class="mb-2" id="binary">0</h4>
                        </div>
                    </div>
                  </div>
                </div>
            </div>

              
            <!-- Total Daily Income-->
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card" style="background-image: linear-gradient(130deg, #df7174,#df7174a8),url(<?=base_url()?>adminassets/userimg/dashboard/daily.png);background-repeat: no-repeat;background-size: contain;background-position: right;">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline">
                      <h6 class="card-title mb-0" id="textColor">My Daily Income</h6>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between" id="directIncomeIcon">
                        <div class="incomeText">
                        <h4 class="mb-2"  id="dailyp">0</h4> 
                      </div>
                    </div>
                  </div>
                </div>
            </div>

            </div>
          </div>
        </div> <!-- row -->


      </div>
      
  

