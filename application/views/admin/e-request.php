<div class="page-content">
    <div class="row">
        <div class="col-12 col-xl-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row flex-grow">
                        <?php if($this->session->userdata('set') != ''){?>
                        <div class="offset-md-4 col-md-4 order-md-1">
                            <div  id="scratchCard">
                                <h5 class="mb-3">Checkout</h5>
                            <ul class="list-group mb-3">
                                <li class="list-group-item d-flex justify-content-between lh-condensed">
                                  <div>
                                    <h6 class="my-0">Pin name</h6>
                                  </div>
                                  <span class="text-muted"><?=$this->session->userdata('pn')?></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between lh-condensed">
                                  <div>
                                    <h6 class="my-0">Per Pin</h6>
                                  </div>
                                  <span class="text-muted">₹ <?=$this->session->userdata('pp')?></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between lh-condensed">
                                  <div>
                                    <h6 class="my-0">Request pin</h6>
                                  </div>
                                  <span class="text-muted"><?=$this->session->userdata('req')?></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between lh-condensed">
                                  <div>
                                    <h6 class="my-0"><i class="fa fa-wallet"></i> Wallet Balance</h6>
                                  </div>
                                  <span class="text-muted">₹ <?=$this->session->userdata('wallet')?></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between lh-condensed">
                                  <div>
                                    <h6 class="my-0"></h6>
                                  </div>
                                  <span class="text-danger">- ₹ <?php echo $this->session->userdata('req') * $this->session->userdata('pp');?></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between lh-condensed bg-light text-dark">
                                  <div>
                                    <h6 class="my-0"><i class="fa fa-wallet"></i> Available Balance</h6>
                                  </div>
                                  <span class="text-muted">₹ <?php echo $this->session->userdata('wallet')- $this->session->userdata('req') * $this->session->userdata('pp');?></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between bg-dark text-white">
                                  <span>Total (INR)</span>
                                  <strong>₹ <?php echo $this->session->userdata('req') * $this->session->userdata('pp');?></strong>
                                </li>
                              </ul>
                            <div class="form-row">
                                   <div class="col-md-6  p-2">
                                        <a href="<?=base_url()?>cancel_order" class="btn btn-danger btn-block">Cancel <i class="fa fa-times"></i></a>
                                    </div>
                                   <div class="col-md-6 p-2">
                                        <a href="<?=base_url()?>bookOrder" class="btn btn-success btn-block">Complete <i class="fa fa-angle-right"></i></a>
                                   </div>
                                   
                               </div>
                            </div>
                        </div>       
                          <?php }else {?>
                        <div class="offset-md-3 col-md-6 order-md-1">
                            <h5 class="mb-3">Raise Pin Request</h5>
                            <form id="scratchCard">
                            <div class="mb-3">
                              <label for="username">Username <span class="text-danger">*</span></label>
                              <div class="input-group">
                                <div class="input-group-prepend">
                                  <span class="input-group-text">@</span>
                                </div>
                                <input type="text" class="form-control" name="username" readonly id="username" placeholder="Username" value="<?=$this->session->userdata('username')?>"required>
                              </div>
                            </div>
                            <input name="<?= $this->security->get_csrf_token_name()?>" value="<?= $this->security->get_csrf_hash(); ?>" type="hidden">
                            <div class="mb-3">
                              <label for="email">Email</label>
                              <input type="email" class="form-control" id="email" name="email" placeholder="you@example.com">
                            </div>
                            <div class="mb-3">
                              <label for="phone">Phone</label>
                              <input type="text" class="form-control" id="phone" name="phone" placeholder="ex: 000-0000-000">
                            </div>
                
                            <div class="mb-3">
                                <label>Choose Package <span class="text-danger">*</span></label>
                                <select class="form-control" id="package_name" name="package_name">
                                <?php $data = $this->db->where('isActive !=','2')->select('id,name,price')->from('package')->get()->result(); if(isset($data)){
                                    echo '<option value="">Choose package</option>';
                                        foreach ($data as $key) {?>
                                            <option value="<?=$key->id?>"><?=$key->name?>(Rs. <?=$key->price?> /-)</option>
                                        <?php } }else{
                                      echo '<option value="">No Packages here!</option>';
                                        } ?>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="number">how many id you want ? <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="number" name="number">
                            </div>
                            
                            <hr class="mb-4">
                
                            <h4 class="mb-3">Payment</h4>
                
                            <div class="d-block my-3">
                              <!--div class="custom-control custom-radio">
                                <input id="credit" name="paymentMethod" type="radio" class="custom-control-input" value="1">
                                <label class="custom-control-label" for="credit">Online</label>
                              </div-->
                              <div class="custom-control custom-radio">
                                <input id="debit" name="paymentMethod" type="radio" class="custom-control-input" checked value="0">
                                <label class="custom-control-label" for="debit">Pay With Wallet</label>
                              </div>
                            </div>
                            
                            <hr class="mb-4">
                            <button class="btn btn-primary btn-lg btn-block" type="button" id="checkoutButton">Continue to checkout</button>
                          </form>
                        </div>
                          <?php }?>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- row -->
</div>
      
  

