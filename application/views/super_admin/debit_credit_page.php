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
                            <form  action="<?=base_url()?>admin/add_transaction_admin" method="post" id="debitCreditForm" style="border-radius:35px 35px 35px 35px;" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label>USER ID</label>
                                    <input type="text" readonly class="form-control" name="userid" id="userid" placeholder="Game Name" value="<?=$_GET['q']?>">
                                    <div class="text-danger"><?=$this->db->where('member_id',$_GET['q'])->select()->get('login')->row('name')?></div>
                                    <small class="text-danger"><?=$this->session->flashdata('a')?></small>
                                </div>

                                <div class="form-group">
                                    <?php
                                        $trs =  $this->db->where('user',$_GET['q'])
                                          //->where('gate','app')    
                                          ->where('dr_cr=','Dr')
                                          ->where('order !=','Join Megacontest')
                                          ->where('order !=','Upgrade Account')
                                          ->select('t_amt')
                                          ->from('transaction')
                                          ->get()->result();  
                                        
                                        $txn = 0;
                            
                                        foreach ($trs as $key) {
                                            $txn += $key->t_amt;
                                        }
                                        $wallet = $this->db->where('user',$_GET['q'])->select('amount')->get('wallet')->row('amount');
                                        if($trs > $wallet){
                                          $left_bus = $wallet-$txn;
                                        }else{
                                            $left_bus = 0;        
                                           
                                        }
                                    ?>
                                    <label>Wallet Balance</label>
                                    <input type="text" class="form-control" readonly name="wallet" id="wallet" placeholder="User Wallet" value=" Rs. <?=$left_bus?>">
                                    <small class="text-danger"><?=$this->session->flashdata('b')?></small>
                                </div>
                                <div class="form-group">
                                    <label>Select Option</label>
                                    <select class="form-control" name="drOpt" id="drOpt">
                                        <option value="">Choose What You Do</option>
                                        <option value="Dr">Debit</option>
                                        <option value="Cr">Credit</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Credit & Debit Amount</label>
                                    <input type="number" class="form-control" name="crDr" id="crDr" placeholder="Credit & Debit Balance">
                                    <small class="text-danger"><?=$this->session->flashdata('c')?></small>
                                </div>

                                <div class="form-group">
                                    <label>Remarks</label>
                                    <textarea class="form-control" rows="3" name="remarks" id="remarks" placeholder="Enter remarks here!"></textarea>
                                    <small class="text-danger"><?=$this->session->flashdata('d')?></small>
                                </div>
                                
                            
                                <div class="form-group text-center">
                                    <input name="<?= $this->security->get_csrf_token_name()?>" value="<?= $this->security->get_csrf_hash(); ?>" type="hidden">
                                    <button class="btn btn-dark" style="border-radius:35px 35px 35px 35px;" type="button" id="debitCreditSave"> Save</button>
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
      
  

