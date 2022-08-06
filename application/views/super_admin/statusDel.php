                <div class="page-content">
                <div class="row">
                    <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <div class="row">
                    
                    
                    <div class="offset-md-3 col-md-4 mt-1">
                        <div class="card" style="border-radius: 10px;box-shadow: 1px 1px 12px #dde;">
                            <div class="card-body">
                                    <div>
                                        <!--- start account details page-->
                                        <a href="javascript:;" class="text-dark">
                                            <div class="showAccountsdetails p-2">
                                                <h6>CASHFREE STATUS: <?=$data['status']?></h6>
                                                <h6>CASHFREE STATUS: <?=$db?></h6>
                                                <p>MESSAGE: <span><?=$data['message']?></span></p>
                                                <p class="<?php if($data['status'] == 'SUCCESS'){?>text-success<?php } else{echo 'text-danger';}?>"><i class="fa fa-check-circle <?php if($data['status'] == 'SUCCESS'){?>text-success<?php } else{echo 'text-danger';}?>"></i> <?php if($data['status'] == 'SUCCESS'){?>Deleted<?php } else{echo 'Allready Deleted';}?></p>
                                                
                                            </div>
                                            <div class=""><a href="<?=base_url()?>admin/manage-account?q=&q=<?=$_GET['q']?>"><i class="fa fa-angle-left info"></i></a></div>
                                        </a>
                                    </div>
                            </div>
                        </div>
                    </div>
                    
                    <style>
                        .defaultCheck {
                            position: absolute;
                            right: 25px;
                            margin-top: -55px;
                        }
                    </style>
                </div>
              </div>
            </div>
                    </div>
                </div>

            </div>