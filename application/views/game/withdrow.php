
<div class="container" style="margin-top:70px;">
    <div class="row">
        <div class="col-md-12">
            <div class="card" style="box-shadow: 1px 1px 21px #e8e8e8;border-radius: 12px;">
                <div class="card-body">
                    <?php $acc = $this->db->where('user',$this->session->userdata('gameuser'))->select('beneId,bankAccount,ifsc,status')->from('accounts')->get()->row();?>
                    <?php if(!empty($acc)){?>
                    <div>
                        <!--- start account details page-->
                        <a href="javascript:;" class="text-dark">
                            <div class="showAccountsdetails p-2">
                                <h6>A/C <?=$acc->bankAccount?></h6>
                                <p>IFSC CODE <span><?=$acc->ifsc?></span></p>
                                <p class="<?php if($acc->status == 'SUCCESS'){?>text-success<?php } else{echo 'text-danger';}?>"><i class="fa fa-shield-alt <?php if($acc->status == 'SUCCESS'){?>text-success<?php } else{echo 'text-danger';}?>"></i> <?php if($acc->status == 'SUCCESS'){?>Verifyed<?php } else{echo 'Un-verify';}?></p>
                            </div>
                            <div class="defaultCheck"><i class="h5 fa <?php if($acc->status == 'SUCCESS'){?>fa-check-circle text-info<?php } else{echo ' fa-times-circle text-danger';}?>"></i></div>
                        </a>
                    </div>
                    
                    <?php } else { ?>
                    <div>
                        <!--- start benifiecry link page-->
                        <a href="<?=base_url()?>api/add-benificery" class="text-dark">
                            <div class="addBenificery">
                                <div class="text-center">
                                    <i class="fa fa-plus-circle" style="font-size: 30px;color: #b5b4b4c2;"></i>
                                    <p style="color: #b5b4b4c2;">ADD BENIFICERY</p>
                                </div>
                            </div>
                        </a><!--- end benifiecry link page-->
                    </div>
                    <?php } ?>
                    
                </div>
            </div>
        </div>
    </div>
</div>    

<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card" style="border-radius: 12px;">
                <div class="card-body">
                    <?php if(!empty($acc)){?>
                    <div>
                        <form class="withdrawBalancePaymentsThrough" action="<?=base_url()?>api/withdrawlForm" method="post">
                            <div class="form-group">
                                <label>Enter Amount</label>
                                <input type="number" class="form-control" placeholder="Enter Amount" name="amount" id="amount" value="<?=$this->db->select('default')->from('admin')->get()->row('default')?>">
                                <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>">  
                            </div>
                            <div class="form-group">
                                <button class="btn btn-block btn-dark p-2" name="submit" id="withdrawFinal">WITHDRAW BALANCE</button>
                            </div>
                        </form>
                        
                    </div>
                    
                    <?php }  ?>
                </div>
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

.addBenificery {
    padding-top: 25px;
    padding-bottom: 20px;
    background: #ececec33;
    border-radius: 4px;
}
</style>