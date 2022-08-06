
<div class="container" style="margin-top:70px; margin-bottom:70px;">
    <div class="row">
        <div class="col-md-12">
            <div class="card" style="box-shadow: 1px 1px 21px #b1b1ae;border-radius: 12px;">
                <div class="card-body">
                    <!-- start form page add Benificery-->
                    <div>
                        <form class="addAccountAsBenificery" method="post" action="<?=base_url()?>api/add-benificery-account">
                             <div class="form-group">
                                <label>Account holder name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Account holder name">
                            </div>
                            <div class="form-group">
                                <label>Account number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="acco" id="acco" placeholder="Account number">
                                <div class="text-danger" id="error"></div>
                            </div>
                            <div class="form-group">
                                <input type="hidden" class="form-control" name="baneId" id="baneId" value="<?=substr($this->session->userdata('gameuser'),2,4)?><?=strtotime(date('Y-m-d h:i:s'))?>">
                                <input type="hidden" name="<?= $this->security->get_csrf_token_name()?>" value="<?= $this->security->get_csrf_hash(); ?>">
                                <input type="hidden" name="userid" value="<?= $this->session->userdata('gameuser'); ?>">
                            </div>
                            <div class="form-group">
                                <label>Confirm Account number <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" name="cnfAcc" id="cnfAcc" placeholder="Confirm Account number">
                            </div>
                            <div class="form-group">
                                <label>Ifsc code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="ifsc" id="ifsc" placeholder="ifsc code">
                            </div>
                            <div class="form-group">
                                <!--label>Phone <span class="text-danger">*</span></label-->
                                <input type="hidden" class="form-control" name="phone" id="phone" value="<?=$this->session->userdata('phone')?>"  placeholder="phone">
                                
                            </div>
                            <div class="form-group">
                                <!--label>Email <span class="text-danger">*</span></label-->
                                <input type="hidden" class="form-control" name="email" id="email" value="<?=$this->session->userdata('email')?>" placeholder="email">
                            </div>
                            <div class="form-group">
                                <label>Branch Name  <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="brnch" id="brnch" placeholder="branch name">
                            </div>
                            <div class="form-group">
                                <!--label>City</label-->
                                <input type="hidden" class="form-control " name="city" id="city" placeholder="city">
                            </div>
                            <div class="form-group">
                                <!--label>State</label-->
                                <input type="hidden" class="form-control" name="state" id="state" placeholder="state">
                            </div>
                            <div class="form-group">
                                <!--label>City</label-->
                                <input type="hidden" class="form-control" name="pincode" id="pincode" placeholder="pincode">
                            </div>
                            <div class="form-group">
                                <button type="button" class="btn btn-info btn-block text-white p-2" id="procceedtoapyToNext" name="submit">ADD ACCOUNT</button>
                            </div>
                        </form>
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