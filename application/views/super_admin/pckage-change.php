<div class="page-content">
    <div class="row">
        <div class="col-12 col-xl-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between border-bottom">
                        <h6>USER PACKAGE</h6>    
                    </div>
                    <div class="row flex-grow mt-5">
                        <div class="offset-md-3 col-md-6">
                            <form  action="<?=base_url()?>admin/changenow_package" method="post" id="pckagechange" style="border-radius:35px 35px 35px 35px;" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label>USER ID</label>
                                    <input type="text" readonly class="form-control" name="userid" id="userid" placeholder="Game Name" value="<?=$_GET['q']?>">
                                    <small class="text-danger"><?=$this->session->flashdata('a')?></small>
                                </div>
                                    <div class="form-group">
                                        <label>What You Do ?</label>
                                        <select class="form-control" name="acu" id="acu">
                                            <option>Choose Option</option>
                                            <option value="1">Activate</option>
                                            <option value="2">Package Change & Reset User Data</option>
                                        </select>
                                        <small class="text-danger"></small>
                                    </div>
                                
                                <div class="form-group">
                                <label for="pkg">SELECT PACKAGE</label>
                                <select class="form-control" name="pkg" id="pkg">
                                    <option value="">Choose Package</option>
                                    <?php $pkg = $this->db->select('name,price,id')->order_by('id','asc')->from('package')->get()->result();?>
                                    <?php if(!empty($pkg)){ foreach($pkg as $row){ ?>
                                    <option value="<?=$row->id?>"><?=$row->name?> (<?=$row->price?> /-)</option>
                                    <?php } }?>
                                    
                                </select>
                                <small class="text-danger"><?php if($this->session->flashdata('error')) echo $this->session->flashdata('error');?></small>
                            </div>
                                    <div class="form-group">
                                        <label>UPGRADE PACKAGE</label>
                                        <select class="form-control" name="upg" id="upg">
                                            <option value="1">Normal</option>
                                            <option value="2">Upgrade</option>
                                        </select>
                                        <small class="text-danger"></small>
                                    </div>
                                
                                <div class="form-group text-center">
                                    <input name="<?= $this->security->get_csrf_token_name()?>" value="<?= $this->security->get_csrf_hash(); ?>" type="hidden">
                                    <button class="btn btn-dark" style="border-radius:35px 35px 35px 35px;" type="button" id="pkgupgradeBTb"> Save</button>
                                </div>
                                <div class="text-center mt-1">
                                    <a href="<?=base_url()?>admin/all-user" class="text-dark"><i class="fa fa-angle-left"></i> back</a>
                                </div>    
                            </form>
                            
                        </div>    
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- row -->
</div>
      
  

