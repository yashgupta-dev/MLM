<?php $id = $this->session->userdata('username');?>

				<div class="page-content">
				<div class="row">
					<div class="col-md-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <div class="row">
                    <div class="col-12 p-2 mb-4 text-dark">
                        <h5>Member ID: <?=$id?> </h5>
                    </div>
                </div>
                <div class="row">
                  <div class="offset-md-3 col-md-6 bg-light text-dark p-5 paymentDesign">
                      <div>
                          <?php if($this->session->userdata('otp') !=''){ ?>
                            <form class="form-otp formAMt">
                            <div class="form-group">
                              <label>Enter Otp</label>
                              <input type="text" name="otpName" class="form-control widthAmt" id="otpName">
                              <small class="text-danger ml-4" id="otpError" style="font-weight:800;"></small>
                            </div>
                            <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>">  
                            <div class="form-group text-center">
                              <button class="btn btn-dark text-light" type="button" id="otpVerify" style="border-radius: 35px 35px 35px 35px">Otp verify</button>
                            </div>
                          </form>
                          <style>
                              input.is-invalid {
                                border-radius: 35px 35px 35px 35px;
                                padding: 25px;
                            }
                          </style>
                          <?php }  else{ ?>
                          <form class="form-widthrow formAMt">
                            <div class="form-group">
                                <small class="" id="otpErrorMain" style="font-weight:800;"></small>
                            </div>
                            <div class="form-group" id="ruppesSymbol">
                              <label><i class="fa fa-wallet text-muted"></i> Wallet Balance</label>
                              <input type="text" name="wallet" class="form-control widthAmt" placeholder="0.00" value="<?=$this->db->where('user',$this->session->userdata('username'))->select('amount')->from('wallet')->get()->row('amount') - $this->db->query("SELECT sum(t_amt) as total FROM transaction where user='$id'")->row('total')?>" readonly>
                            </div>
                            <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>">  
                            <div class="form-group" id="ruppesSymbol">
                              <label><i class="fa fa-exchange-alt text-muted"></i> Transaction Amount</label>
                              <input type="text" name="trans" id="cutInterest" class="form-control widthAmt" placeholder="0.00" value="<?=$this->db->select('default')->from('admin')->get()->row('default')?>">
                              <span class="text-dark text-muted mt-2" id="chargeAmt">
                                <?php 
                                  $interest = $this->db->select('interest')->from('admin')->get()->row('interest');
                                    $get = $this->db->select('default')->from('admin')->get()->row('default') * $interest/100;
                                    $final = $this->db->select('default')->from('admin')->get()->row('default') - $get;
                                    
                                  echo 'widthrow amount: '.$final.' <i class="fa fa-rupee-sign" style="font-size:12px"></i>. (';echo isset($interest)?$interest:'0';echo '% interest)';
                                ?>
                              </span>
                            </div>
                            <div class="form-group text-center">
                              <button class="btn btn-dark text-light" type="button" id="transfer-money" style="border-radius: 35px 35px 35px 35px">Transfer Money</button>
                            </div>
                          </form>
                          <?php } ?>
                      </div>
                  </div>
                </div>
              </div>
            </div>
					</div>
				</div>

			</div>
