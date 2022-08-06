
<div class="container-fluid mb-4" style="margin-top:70px">
    <div class="row">
        <div class="col-md-12">
            <div class="card" style="border-radius:12px;">
                <div class="card-body">
                    <table class="table table-borderless table-responsive">
                        <tbody>
                <?php $allda = $this->db->where('user',$this->session->userdata('gameuser'))->order_by('id','desc')->select('txn,method,status,t_amt,dr_cr,order,refrence,timestamp')->from('transaction')->get()->result();?>
                <?php if(!empty($allda)){ foreach($allda as $row){?>    
                  <tr class="border-bottom ">
                    <td>
                        <div class="">
                            <div>
                                <?php $time = strtotime($row->timestamp);?>
                                <?=date('d',$time)?> <?=date('F',$time)?>, <?=date('Y',$time)?>
                            </div>
                            <small>
                                <?php if($row->status == 'CANCELLED'){?>
                                <?=$row->status?>
                                <?php }elseif($row->status == 'PENDING'){ ?>
                                <?=$row->status?>
                                <?php } else{?>
                                
                                <?=$row->method?>/<?=$row->order?>
                                <div>
                                    ref #<?=$row->refrence?>
                                <?php } ?>
                                </div>
                            </small>
                        </div>
                    </td>      
                    <td>
                        <div>
                            <h6><?php if($row->dr_cr == 'Cr') {echo '<sub><small class="mb-2 text-success">'.$row->dr_cr.'</small></sub>';}elseif($row->dr_cr == 'Dr'){echo '<sub><small class="ml-1 mb-2 text-danger">'.$row->dr_cr.'</small></sub>';}?> <?=$row->t_amt?> <i class="fa fa-rupee-sign"></i> </h6>
                        </div>
                    </td>      
                  </tr>
                <?php } }else{ ?>
                    <tr>
                        <td colspan="2">
                            <div class="">
                                No! transaction found
                            </div>
                        </td>
                    </tr>
                <?php } ?>
              </tbody>  
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
