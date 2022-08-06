<div class="page-content">

    <div class="row">
        <div class="col-lg-12 col-xl-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                        <h6 class="card-title mb-0">KYC All Verifyed </h6>
                    </div>
                    <div class="form-row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="customCheckAll" name="example1">
                                    <label class="custom-control-label" for="customCheckAll" id="changeName">Select all</label>
                                </div>    
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex">
                                <?php for($l=0;$l<=2;$l++){?>
                                <div class="ml-2">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" <?php if($l == $_GET['q']) echo 'checked';?> value="q=<?=$l?>&u=<?php if($l=='0'){echo 'Reject';} elseif($l=='1'){echo 'Pending';}elseif($l=='2'){echo 'Approve';}?>" id="customCheckUrl<?=$l?>" name="urlHit">
                                        <label class="custom-control-label" for="customCheckUrl<?=$l?>">
                                            <?php if($l=='0'){echo 'Reject';} elseif($l=='1'){echo 'Pending';}elseif($l=='2'){echo 'Approve';}?>
                                        </label>
                                    </div>
                                </div>    
                                <?php } ?>
                                
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="d-flex">
                                <button type="button" id="approveBtn" data-id="<?=base_url()?>admin/kyc.approve" class="ml-5 btn btn-sm btn-success">Approve</button>
                                <button type="button" id="rejectBtn" data-id="<?=base_url()?>admin/kyc.reject" class=" ml-2 btn btn-sm  btn-danger">Reject</button>
                            </div>
                        </div>
                        
                    </div>
                    <hr>
                    <div id="">
                        <form id="kycDataAllVerify" method="post">
                            <input name="<?= $this->security->get_csrf_token_name()?>" value="<?= $this->security->get_csrf_hash(); ?>" type="hidden">
                            <div class="row">
                                <style>
                                    img.img-fluid.rounded {
                                        object-fit: cover;
                                        height: 100px;
                                        width: 100%;
                                        size: landscape;
                                    }
                                </style>
                                <?php if(!empty($kyc)){ $i=1; foreach($kyc as $row){++$i; ?>
                                <div class="col-md-2 mb-2">
                                    <div>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="customCheck<?=$i?>" name="checkAllData[]" value="<?=$row->id?>">
                                            <label class="custom-control-label" for="customCheck<?=$i?>"><?=$row->userid?></label>
                                        </div>
                                    </div>
                                    <a href="<?=base_url()?>uploads/<?=$row->userid?>/<?=$row->panimg?>" data-toggle="lightbox" data-gallery="gallery">
                                      <img src="<?=base_url()?>uploads/<?=$row->userid?>/<?=$row->panimg?>" class="img-fluid rounded">
                                    </a>    
                                </div>
                                <?php } }?>
                            </div>
                        </form> 
                </div>
            </div>
        </div>
    </div>
</div>
    <!-- row -->

</div>
