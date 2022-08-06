
<div class="container" style="margin-top:70px; margin-bottom:70px;">
    <div class="row">
        <div class="col-md-12">
            <div class="card" style="box-shadow: 1px 1px 21px #b1b1ae;border-radius: 12px;">
                <div class="card-body">
                    <!-- start form page add Benificery-->
                    <div>
                    <?php $st = $this->db->where('userid',$this->session->userdata('gameuser'))->select('userid,status,panname,dob,panimg,panno')->get('kyccomplete')->row();?>
                    <?php if($st->status == 0){?>
                        <div class="mb-3">
                            <h4 class="text-danger">YOUR KYC REJECTED <i class="far fa-times-circle"></i></h4>
                        </div>
                        <form class="dokycform" method="post" action="<?=base_url()?>api/complete-kyc-data" enctype="multipart/form-data">
                            <div class="form-group">
                                <label>Enter Pancard No <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?php if($this->session->userdata('p')) echo ' is-invalid';?>" name="pan" id="pan" placeholder="XXXXXXXXX">
                                <div class="text-danger mt-1" id="textpanerror"><?php if($this->session->userdata('p')) echo $this->session->userdata('p');?></div>
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="<?= $this->security->get_csrf_token_name()?>" value="<?= $this->security->get_csrf_hash(); ?>">
                                <input type="hidden" name="userid" value="<?= $this->session->userdata('gameuser'); ?>">
                            </div>
                            <div class="form-group">
                                <label>Enter Name <span class="text-dark"> (as per pancard)</span><span class="text-danger"> *</span></label>
                                <input type="text" class="form-control <?php if($this->session->userdata('a')) echo ' is-invalid';?>" name="name" id="name" placeholder="name as per pancard">
                            </div>
                            <div class="form-group">
                                <label>Enter DOB <span class="text-dark"> (as per pancard)</span><span class="text-danger"> *</span></label>
                                <input type="date" class="form-control <?php if($this->session->userdata('c')) echo ' is-invalid';?>" name="dob" id="dob" placeholder="name as per pancard">
                            </div>
                            <div class="form-group">
                                <label>Pancard Photo <span class="text-danger">*</span></label>
                                <a class="btn btn-dark  text-white btn-sm btn-block" id="pancardopen"><i class="fas fa-address-card" style="vertical-align: bottom;"></i> <span class="textselect">Select Pancard</span></a>
                                <input type="file" class="form-control" name="panp[]" id="panp" required style="display:none;"> 
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-info btn-block text-white p-2" id="clicktoLoad" name="submit">KYC COMPLETE</button>
                            </div>
                        </form>
                        
                    <?php } elseif($st->status == 1){?>
                        <style>
                            img{
                                    transform: scale(0.85);
                                    border: 5px solid #dde;
                                    border-radius:12px;
                            }
                            ul li{
                                font-size:22px;
                            }
                        </style>
                        <img class="card-img-top" src="<?=base_url()?>uploads/<?=$st->userid?>/<?=$st->panimg?>">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><h4 class="text-warning">Pending For Approval <i class="far fa-clock"></i></h4></li>
                            <li class="list-group-item"><i class="far fa-clock text-warning" style="font-size:16px;"></i> <?=$st->panname?></li>
                            <li class="list-group-item"><i class="far fa-clock text-warning" style="font-size:16px;"></i> <?=$st->dob?></li>
                            <li class="list-group-item"><i class="far fa-clock text-warning" style="font-size:16px;"></i> <?=$st->panno?></li>
                        </ul>
                        
                    <?php }elseif($st->status == 2){?>
                        <style>
                            img{
                                    transform: scale(0.85);
                                    border: 5px solid #dde;
                                    border-radius:12px;
                            }
                            ul li{
                                font-size:22px;
                            }
                        </style>
                        <img class="card-img-top" src="<?=base_url()?>uploads/<?=$st->userid?>/<?=$st->panimg?>">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><h4 class="text-success">KYC VERIFYED <i class="fa fa-check-circle"></i></h4></li>
                            <li class="list-group-item"><i class="fa fa-check text-success" style="font-size:16px;"></i> <?=$st->panname?></li>
                            <li class="list-group-item"><i class="fa fa-check text-success" style="font-size:16px;"></i> <?=$st->dob?></li>
                            <li class="list-group-item"><i class="fa fa-check text-success" style="font-size:16px;"></i> <?=$st->panno?></li>
                        </ul>
                          
                        <!--div class="text-center">
                            <h6 class="text-success">KYC COMPLETED</h6>
                            <p>Thank you<br> your KYC has been Completed</p>
                        </div>
                        <div class="text-center mt-3">
                            <a href="<?=base_url()?>api/account-profile" class="btn btn-dark" id="clicktoLoad"><i class="fa fa-angle-left"></i> Back</a>
                        </div-->
                    <?php } else {?>
                        <form class="dokycform" method="post" action="<?=base_url()?>api/complete-kyc-data" enctype="multipart/form-data">
                            <div class="form-group">
                                <label>Enter Pancard No <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?php if($this->session->userdata('p')) echo ' is-invalid';?>" name="pan" id="pan" placeholder="XXXXXXXXX">
                                <div class="text-danger mt-1" id="textpanerror"><?php if($this->session->userdata('p')) echo $this->session->userdata('p');?></div>
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="<?= $this->security->get_csrf_token_name()?>" value="<?= $this->security->get_csrf_hash(); ?>">
                                <input type="hidden" name="userid" value="<?= $this->session->userdata('gameuser'); ?>">
                            </div>
                            <div class="form-group">
                                <label>Enter Name <span class="text-dark"> (as per pancard)</span><span class="text-danger"> *</span></label>
                                <input type="text" class="form-control <?php if($this->session->userdata('a')) echo ' is-invalid';?>" name="name" id="name" placeholder="name as per pancard">
                            </div>
                            <div class="form-group">
                                <label>Enter DOB <span class="text-dark"> (as per pancard)</span><span class="text-danger"> *</span></label>
                                <input type="date" class="form-control <?php if($this->session->userdata('c')) echo ' is-invalid';?>" name="dob" id="dob" placeholder="name as per pancard">
                            </div>
                            <div class="form-group">
                                <label>Pancard Photo <span class="text-danger">*</span></label>
                                <a class="btn btn-dark  text-white btn-sm btn-block" id="pancardopen"><i class="fas fa-address-card" style="vertical-align: bottom;"></i> <span class="textselect">Select Pancard</span></a>
                                <input type="file" class="form-control" name="panp[]" id="panp" required style="display:none;"> 
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-info btn-block text-white p-2" id="clicktoLoad" name="submit">KYC COMPLETE</button>
                            </div>
                        </form>
                    
                    <?php } ?>
                    </div>
                    <!-- end form page add Benificery-->
                    
                </div>
            </div>
        </div>
    </div>
</div>    

<style>
    .defaultCheck {
    position: absolute;
    right: 25px;
    margin-top: -40px;
}

.addBenificery {
    padding-top: 25px;
    padding-bottom: 20px;
    background: #ececec33;
    border-radius: 4px;
}
</style>