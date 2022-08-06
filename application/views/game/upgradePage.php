
<div class="container" >
    <div class="row" style="margin-top:70px;">
        <div class="col-md-12">
            <div class="card" style="border-radius:12px;">
                <div class="card-body">
                    <div>
                        <form method="post" action="<?=base_url()?>api/upgradeform">
                            <div class="form-group">
                                <label for="pkg">Select Package</label>
                                <select class="form-control <?php if($this->session->flashdata('error')) echo ' is-invalid';?>" name="pkg" id="pkg">
                                    <option value="">Choose Package</option>
                                    <?php $pkg = $this->db->where('isActive !=','2')->select('name,price,id')->order_by('id','asc')->from('package')->get()->result();?>
                                    <?php if(!empty($pkg)){ foreach($pkg as $row){ ?>
                                    <option value="<?=$row->id?>"><?=$row->name?> (<?=$row->price?> /-)</option>
                                    <?php } }?>
                                </select>
                                <small class="text-danger"><?php if($this->session->flashdata('error')) echo $this->session->flashdata('error');?></small>
                            </div>
                            <input type="hidden" name="<?= $this->security->get_csrf_token_name()?>" value="<?= $this->security->get_csrf_hash(); ?>">
                            <div class="form-group">
                                <button id="procceedtoapy" type="submit" class="btn btn-block btn-primary">UPGRADE</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>      
        </div>
    </div>    
</div>
