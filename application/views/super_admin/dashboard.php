
      <div class="page-content">
        <div class="row">
          <div class="col-12 col-xl-12 stretch-card">
            <!------------------ ROI --------------------------->
            <div class="row flex-grow">
            <?php if($sms <= '10'){ ?>
            <div class="RightSide11">
                <div class="row">
                    <div class="d-flex">
                        <div class="icon11">
                            <?php if($sms <= '10'){ ?>
                            <i class="fas fa-exclamation-circle text-warning"></i>
                            <?php } else{?>
                            <i class="fas fa-check-circle text-success"></i>  
                            <?php } ?>
                            
                        </div>
                        <div class="ml-2 textStyle11" id="showOnhover11">
                            <p class="text-danger"><small>Warning from sms..</small></p>
                            <?php if($sms <= '10'){ ?>
                            <p class="text-warning">low balance in sms account!</p>
                            <?php } else{ ?>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php } else{?>
                            
            <?php } ?>
            <?php if(!empty($bal['availableBalance']) && $bal['availableBalance'] <= '100'){ ?>
            <div class="RightSide11">
                <div class="row">
                    <div class="d-flex">
                        <div class="icon11">
                            <?php if(!empty($bal['availableBalance']) && $bal['availableBalance'] <= '100'){ ?>
                            <i class="fas fa-exclamation-circle text-warning"></i>
                            <?php $this->db->where('id','1')->set('payment','0')->update('admin');?>
                            <?php } else{?>
                            <i class="fas fa-check-circle text-success"></i>   
                            <?php } ?>
                            
                        </div>
                        <div class="ml-2 textStyle11" id="showOnhover11">
                            <p class="text-danger"><small>Cashfree: Warning! <span class="text-info">Payment Auto Close</span></small></p>
                            <?php if(!empty($bal['availableBalance']) && $bal['availableBalance'] <= '100'){ ?>
                            <p class="text-warning">low balance in cashfree account!</p>
                            <?php } else{ ?>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php } else{?>
                            
            <?php } ?>
            
            <div class="RightSide4" style="margin-top:60px;">
                <div class="row">
                    <div class="d-flex">
                        <div class="icon4">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <div class="ml-2 textStyle4" id="showOnhover4">
                            <p><small>Wallet Balance</small></p>
                            <h4 id="wallet">0.00</h4>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="RightSide" style="margin-top:120px;">
                <div class="row">
                    <div class="d-flex">
                        <div class="icon">
                            <i class="fas fa-rupee-sign"></i>
                        </div>
                        <div class="ml-2 textStyle" id="showOnhover">
                            <p><small>Today Business</small></p>
                            <h4 id="rightBusinessToday">0.00</h4>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="RightSide2" style="margin-top:180px;">
                <div class="row">
                    <div class="d-flex">
                        <div class="icon2">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div class="ml-2 textStyle2" id="showOnhover2">
                            <p><small>Joining & Upgrade</small>  <span class="ml-2" id="lossprofit"></span></p>
                            <h4 id="joiningUpgrade">0.00</h4>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="RightSide3" style="margin-top:240px;">
                <div class="row">
                    <div class="d-flex">
                        <div class="icon3">
                            <i class="fab fa-cc-amazon-pay"></i>
                        </div>
                        <div class="ml-2 textStyle3" id="showOnhover3">
                            <p><small>Withdraw Balance</small></p>
                            <h4 id="withdrawBalance">0.00</h4>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="RightSide1" style="margin-top:300px;">
                <div class="row">
                    <div class="d-flex">
                        <div class="icon1">
                            <i class="fas fa-share-alt"></i>
                        </div>
                        <div class="ml-2 textStyle1" id="showOnhover1">
                            <p><small>Referral Income</small></p>
                            <h4 id="direct_income">0.00</h4>
                        </div>
                    </div>
                </div>
            </div>
          
            <?php $st = $this->db->select('payment')->get('admin')->row('payment');?>
            <div class="RightSide<?php if($st== '0'){echo '6';} elseif($st == '1'){echo '5';}?>" style="margin-top:360px;">
                <div class="row">
                    <div class="d-flex">
                        <div class="icon<?php if($st== '0'){echo '6';} elseif($st == '1'){echo '5';}?>">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                        <div class="ml-2 textStyle5" id="showOnhover<?php if($st== '0'){echo '6';} elseif($st == '1'){echo '5';}?>">
                            <p><small>Payment ON/OFF</small></p>
                            <h4>
                                <form name="onOffform" action="<?=base_url()?>admin/withdrawPaymentOption"  method="post">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" <?php if($st == '1'){?> checked <?php } elseif($st == '0'){?> <?php } ?> value="1" name="withdrawOnOff" id="withdrawOnOff"><label class="custom-control-label" for="withdrawOnOff">
                                        <?php $st = $this->db->select('payment')->get('admin')->row('payment');?>
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
            
            <div class="col-md-12 grid-margin strech-card">
                <nav aria-label="breadcrumb"  class="bg-light">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page"><i class="fa fa-angle-down mt-1"></i>&nbsp;SMS & CASHFREE INFORMATION</li>    
                  </ol>
                </nav>
            </div>
            
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card" style="background-image: linear-gradient(to right, #036158,#498d8673),url(<?=base_url()?>adminassets/userimg/dashboard/sms.png);background-repeat: no-repeat;background-size: contain;background-position: right;">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline">
                      <h6 class="card-title mb-0 text-white">
                          <?php if($sms <= '10'){ ?>
                            <i class="fas fa-exclamation-circle text-warning"></i>
                            <?php } else{?>
                            <i class="fas fa-check-circle text-success"></i>  
                            <?php } ?>
                          <span class="ml-2">SMS Balance</span></h6>
                    </div>
                    <hr>
                      <div class="d-flex justify-content-between" id="directIncomeIcon">
                        <div class="incomeText">
                          <h4 class="mb-2"><?=$sms?></h4>
                        </div>
                      </div>
                  </div>
                </div>
              </div>
        
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card" style="background-image: linear-gradient(to right, #444b54,#6cb6ed00),url(<?=base_url()?>adminassets/userimg/dashboard/balance.png);background-repeat: no-repeat;background-size: contain;background-position: right;">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline">
                        <h6 class="card-title mb-0 text-white">
                        <?php if(!empty($data['availableBalance']) && $bal['availableBalance'] <= '100'){ ?>
                            <i class="fas fa-exclamation-circle text-warning"></i>
                            <?php } else{?>
                            <i class="fas fa-check-circle text-success"></i>  
                            <?php } ?>      
                          <span class="ml-2">Cashfree Today Available Balance</span>
                        </h6>
                    </div>
                    <hr>
                      <div class="d-flex justify-content-between" id="directIncomeIcon">
                        <div class="incomeText">
                          <h4 class="mb-2">
                          <?php if(!empty($data['availableBalance'])){ ?>
                            <?=$bal['availableBalance']?>
                          <?php } else { echo 0;} ?>
                          </h4>
                        </div>
                      </div>
                  </div>
                </div>
              </div>
            <div class="col-md-12 grid-margin strech-card">
                <nav aria-label="breadcrumb" class="bg-light">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page"><i class="fa fa-angle-down mt-1"></i>&nbsp;SMARTGOGAME PANEL INFORMATION</li>
                  </ol>
                </nav>
                
            </div>
            <!--- total member -->
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card" style="background-image: linear-gradient(to right, #5e7782,#6cb6ed00),url(<?=base_url()?>adminassets/userimg/dashboard/member.png);background-repeat: no-repeat;background-size: contain;background-position: right;">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline">
                      <h6 class="card-title mb-0 text-white">Total Member</h6>
                    </div>
                    <hr>
                      <div class="d-flex justify-content-between" id="totalMember">
                        <div class="incomeText">
                          <h4 class="mb-2" id="total_member">0</h4>
                        </div>
                      </div>
                  </div>
                </div>
            </div>
            
            <!-- active -->
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card" style="background-image: linear-gradient(to right, #039080,#6cb6ed00),url(<?=base_url()?>adminassets/userimg/dashboard/active.png);background-repeat: no-repeat;background-size: contain;background-position: right;">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline">
                      <h6 class="card-title mb-0" id="textColor">Active Member</h6>                      
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between" id="activeMember">
                        <div class="incomeText">
                          <h4 class="mb-2" id="ltc">0</h4>
                        </div>
                    </div>
                  </div>
                </div>
            </div>
            
            <!-- in active-->  
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card" style="background-image: linear-gradient(to right, #1f8fec,#6cb6ed00),url(<?=base_url()?>adminassets/userimg/dashboard/lazy.png);background-repeat: no-repeat;background-size: contain;background-position: right;">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline">
                      <h6 class="card-title mb-0 text-white">Inactive Member</h6>
                    </div>
                    <hr>
                      <div class="d-flex justify-content-between" id="inactive">
                        <div class="incomeText">
                          <h4 class="mb-2" id="lint">0</h4>
                        </div>
                      </div>
                  </div>
                </div>
            </div>  
              
            <!-- deactive-->  
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card" style="background-image: linear-gradient(to right, #54809c,#6cb6ed00),url(<?=base_url()?>adminassets/userimg/dashboard/waiting.png);background-repeat: no-repeat;background-size: contain;background-position: right;">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline">
                      <h6 class="card-title mb-0 text-white">Deactive Member</h6>
                    </div>
                    <hr>
                      <div class="d-flex justify-content-between" id="deactiveMember">
                        <div class="incomeText">
                          <h4 class="mb-2" id="deactivemember">0</h4>
                        </div>
                      </div>
                  </div>
                </div>
            </div>
            
            <!-- blocked-->  
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card" style="background-image: linear-gradient(to right, #c76e7b,#6cb6ed00),url(<?=base_url()?>adminassets/userimg/dashboard/bane.png);background-repeat: no-repeat;background-size: contain;background-position: right;">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline">
                      <h6 class="card-title mb-0 text-white">Blocked Member</h6>
                    </div>
                    <hr>
                      <div class="d-flex justify-content-between" id="Blocked">
                        <div class="incomeText">
                          <h4 class="mb-2" id="blocked">0</h4>
                        </div>
                      </div>
                  </div>
                </div>
            </div>
            
            <!--Total Binary income-->
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card" style="background-image: linear-gradient(to right, #f5e49e,#6cb6ed00),url(<?=base_url()?>adminassets/userimg/dashboard/binary.png);background-repeat: no-repeat;background-size: contain;background-position: right;">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline">
                      <h6 class="card-title mb-0" id="textColor">Total Binary income</h6>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between" id="directIncomeIcon">
                        <div class="incomeText">
                          <h4 class="mb-2" id="binary_incomeSecond">0</h4>
                        </div>
                    </div>
                  </div>
                </div>
            </div>

              
            <!-- Total Daily Income-->
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card" style="background-image: linear-gradient(to right, #df7174,#6cb6ed00),url(<?=base_url()?>adminassets/userimg/dashboard/daily.png);background-repeat: no-repeat;background-size: contain;background-position: right;">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline">
                      <h6 class="card-title mb-0" id="textColor">Total Daily Income</h6>
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
      
  

