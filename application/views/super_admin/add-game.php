<div class="page-content">
    <div class="row">
        <div class="col-12 col-xl-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between border-bottom">
                        <h6>Touranament Manage</h6>    
                    </div>
                    <div class="row flex-grow mt-5">
                        <div class="offset-md-3 col-md-6">
                            <form  action="<?=base_url()?>game/add-function" method="post" id="scratchCard" style="border-radius:35px 35px 35px 35px;" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label>Game name</label>
                                    <input type="text" class="form-control" name="g_name" id="g_name" placeholder="Game Name" value="<?php echo set_value('g_name'); ?>">
                                    <small class="text-danger"><?=$this->session->flashdata('a')?></small>
                                </div>
                                
                                <!--div class="form-group">
                                    <label>Game Price</label>
                                    <input type="text" class="form-control" name="g_price" id="g_price" placeholder="Game Price" value="<?php echo set_value('g_price'); ?>">
                                    <small class="text-danger"><?//$this->session->flashdata('b')?></small>
                                </div>
                                
                                <div class="form-row">
                                    
                                    <div class="col-md-6 mb-2">
                                        <label>Tournement start</label>
                                        <input type="time" class="form-control" readonly name="g_start" id="g_start" value="<?=date('h:i')?>">
                                        <small class="text-danger"><?//$this->session->flashdata('d')?></small>
                                    </div>
                                    
                                    <div class="col-md-6  mb-2">
                                        <label>Tournement End</label>
                                        <div class="form-row">
                                            <div class="col-md-12">
                                                <select class="form-control" name="hours">
                                                    <option>Choose Hours</option>
                                                    
                                                </select>
                                            </div>
                                            
                                        </div>
                                        <small class="text-danger"><?//$this->session->flashdata('c')?></small>
                                    </div>
                                    
                                </div-->
                                
                                <div class="form-group">
                                    <label>Tournement Image</label>
                                    <input type="file" class="form-control" name="g_file" id="g_file" value="<?php echo set_value('g_file'); ?>">
                                    <small class="text-danger"><?=$this->session->flashdata('er')?></small>
                                </div>
                                
                                <div class="form-group text-center">
                                    <input name="<?= $this->security->get_csrf_token_name()?>" value="<?= $this->security->get_csrf_hash(); ?>" type="hidden">
                                    <button class="btn btn-dark" style="border-radius:35px 35px 35px 35px;" type="submit"><i class="fa fa-gamepad"></i> Upload Game Tournament</button>
                                </div>
                                <div class="text-center mt-1">
                                    <a href="<?=base_url()?>admin/tournament?q=list" class="text-dark">view game list <i class="fa fa-angle-right"></i></a>
                                </div>    
                            </form>
                            
                        </div>    
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- row -->
</div>
      
  

