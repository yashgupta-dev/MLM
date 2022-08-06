<div class="page-content">
    <div class="row">
        <div class="col-12 col-xl-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between border-bottom">
                        <h6>USER EDIT</h6>    
                    </div>
                    <div class="row flex-grow mt-5">
                        <div class="offset-md-3 col-md-6">
                            <form  action="<?=base_url()?>admin/edit-user-details" method="post" id="usereditform" style="border-radius:35px 35px 35px 35px;" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label>USER ID</label>
                                    <input type="text" readonly class="form-control" name="userid" id="userid" placeholder="Game Name" value="<?=$_GET['q']?>">
                                    <small class="text-danger"><?=$this->session->flashdata('a')?></small>
                                </div>

                                <div class="form-group">
                                    <label>USER NAME</label>
                                    <input type="text" class="form-control" name="name" id="name" placeholder="user name" value="<?=$this->db->where('member_id',$_GET['q'])->select('name')->get('login')->row('name')?>">
                                    <small class="text-danger"><?=$this->session->flashdata('b')?></small>
                                </div>
                                
                                <div class="form-group">
                                    <label>USER EMAIL</label>
                                    <input type="text" class="form-control" name="email" id="email" placeholder="user email" value="<?=$this->db->where('member_id',$_GET['q'])->select('email')->get('login')->row('email')?>">
                                    <small class="text-danger"><?=$this->session->flashdata('c')?></small>
                                </div>

                                <div class="form-group">
                                    <label>USER PHONE</label>
                                    <input type="text" class="form-control" name="phone" id="phone" placeholder="user phone" value="<?=$this->db->where('member_id',$_GET['q'])->select('phone')->get('login')->row('phone')?>">
                                    <small class="text-danger"><?=$this->session->flashdata('d')?></small>
                                </div>
                                
                            
                                <div class="form-group text-center">
                                    <input name="<?= $this->security->get_csrf_token_name()?>" value="<?= $this->security->get_csrf_hash(); ?>" type="hidden">
                                    <button class="btn btn-dark" style="border-radius:35px 35px 35px 35px;" type="button" id="edituserbtn"> Save</button>
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
      
  

