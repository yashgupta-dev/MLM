                <div class="page-content">
                <div class="row">
                    <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <div class="row">
                    <div class="col-12 bg-secondary p-2 mb-4 text-white">
                        <div class="d-flex justify-content-between">
                            <h5>Benefeciery <?=$_GET['q']?></h5>
                            <h5><a class="text-white" href="<?=base_url()?>admin/all-bank-accounts"><i class="fa fa-angle-left"></i> back</a></h5>
                        </div>
                    </div>  
                </div>
                
                <div class="row">
                    
                    <?php foreach($bene as $row){?>
                    <div class="col-md-4 mt-1">
                        <div class="card" style="border-radius: 10px;box-shadow: 1px 1px 12px #dde;">
                            <div class="card-body">
                                    <div>
                                        <!--- start account details page-->
                                        <a href="javascript:;" class="text-dark">
                                            <div class="showAccountsdetails p-2">
                                                <h6>A/C <?=$row->bankAccount?></h6>
                                                <p>IFSC CODE <span><?=$row->ifsc?></span></p>
                                                <p class="<?php if($row->status == 'SUCCESS'){?>text-success<?php } else{echo 'text-danger';}?>"><i class="fa fa-shield-alt <?php if($row->status == 'SUCCESS'){?>text-success<?php } else{echo 'text-danger';}?>"></i> <?php if($row->status == 'SUCCESS'){?>Verifyed<?php } else{echo 'Un-verify';}?></p>
                                                <p class="strong small">Member Id: <?=$row->user?></p>
                                            </div>
                                            <div class="defaultCheck"><i class="h5 fa <?php if($row->status == 'SUCCESS'){?>fa-check-circle text-info<?php } else{echo ' fa-times-circle text-danger';}?>"></i></div>
                                            <div class=""><a href="<?=base_url()?>admin/bank-delete?c=<?=$row->user?>&q=<?=$row->beneId?>"><i class="fa fa-trash-alt text-danger"></i></a></div>
                                        </a>
                                    </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
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