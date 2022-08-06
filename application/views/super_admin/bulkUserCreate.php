<div class="page-content">
    <div class="row">
        <div class="offset-md-2 col-md-8">
            <div class="card" style="border-radius:12px;">
                <div class="card-body">
                    <form class="signup_form">
                        <div class="form-group">
                          <label for="pkg">Select Package</label>
                            <select class="form-control <?php if($this->session->flashdata('error')) echo ' is-invalid';?>" name="pkg" id="pkg">
                                <?php $pkg = $this->db->select('name,price,id')->order_by('id','asc')->from('package')->get()->result();?>
                                <?php if(!empty($pkg)){ foreach($pkg as $row){ ?>
                                <option value="<?=$row->id?>"><?=$row->name?> (<?=$row->price?> /-)</option>
                                <?php } }else{ ?>
                                <option value="">Packages Are Deactive</option>
                                <?php }?>
                            </select>
                    </div>
                      <div class="form-row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sponser">Sponser ID <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="sponser" id="sponser" autocomplete="off" placeholder="Sponser ID" >
                                <div class="mt-2 text-success"><small id="pleaseW"></small></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputname1">Full name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" id="exampleInputname1" autocomplete="Full name" placeholder="Full name" value="Raj Kumar">
                                <small class="">YOUR DEFAULT USER NAME: Raj Kumar</small>
                              </div>      
                        </div>
                      </div>
                      <div class="form-row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Select Position <span class="text-danger">*</span></label>
                                <select name="side" id="side" class="form-control">
                                    <option value="">Choose postion</option>
                                    <option value="Right">Right</option>
                                    <option value="Left">Left</option>
                                </select>
                              </div>      
                        </div>    
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="numbers">Bulk ADD </label>
                                <input type="number" name="numbers"  class="form-control" id="numbers" placeholder="Bulk userid">
                            </div>      
                        </div>    
                      </div>
                      
                      <div class="form-row">
                          <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail2">Email address <span class="text-danger">*</span></label>
                                    <input type="email" name="email"  class="form-control" id="exampleInputEmail2" placeholder="Email">
                                </div>        
                          </div>
                          <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputPhone1">Phone <span class="text-danger">*</span></label>
                                    <input type="text" name="phone_number" class="form-control" id="exampleInputPhone1" autocomplete="Phone" placeholder="phone" value="9999999999">
                                    <small class="">YOUR DEFAULT PHONE: +91 999-9999-999</small>
                                </div>        
                          </div>
                      </div>
                      <div class="form-row">
                          <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputPassword2">Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password" class="form-control" id="exampleInputPassword2" autocomplete="current-password" placeholder="Password" value="123456789">
                                    <small class="">YOUR DEFAULT PASSWORD IS: 123456789</small>
                                  </div>
                                  <input type="hidden" name="<?= $this->security->get_csrf_token_name()?>" value="<?= $this->security->get_csrf_hash(); ?>">        
                          </div>
                          <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cnf">Confirm Password <span class="text-danger">*</span></label>
                                    <input type="password" name="cnfpassword" class="form-control" id="cnf" autocomplete="current-password" placeholder="Password" value="123456789">
                                    <small class="">YOUR DEFAULT PASSWORD IS: 123456789</small>
                                  </div>  
                          </div>
                      </div>
                        <div class="">
                            <small>
                                By continuing, you agree to YOYO Play <span class="text-primary">Conditions of Use</span> and <span class="text-primary">Privacy Notice</span>.
                            </small>
                        </div>
                      <div class="mt-3">
                        <button type="button" id="sign_up_btn" class="btn btn-primary btn-block text-white mr-2 mb-2 mb-md-0">Sing up</button>
                       
                      </div>
                      
                    </form>
                </div>
            </div>
        </div>
    </div>    
</div>