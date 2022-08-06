
<div  style="margin-top:70px;"></div>
<div class="container-fluid">
    <div class="card">
        <div class="card-body" style="box-shadow:1px 1px 12px #dde;">
            <div class="row" style="padding: 30px 0px;">
                <div class="col-md-3">
                    <div class="cameraIcon"><i class="fa fa-camera text-white" style="background: #fff;padding: 7px 8px;border-radius: 35px 35px 35px 35px;"></i></div>
                    <div class="profile_pic">
                        <i class="fa fa-user-circle"></i>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="userDetails">
                        <ul class="nav-item" style="list-style:none;">
                            <li><span style="font-size:18px;"><?=$this->session->userdata('name')?></span></li>
                            <li class=""><span style="font-size:12px;" class="text-success">userid: <?=$this->session->userdata('gameuser')?></span></li>
                            <li class="phoneAfter"><span style="font-size:12px;"><?=$this->session->userdata('phone')?></span></li>
                            <li class="emailAfter"><span style="font-size:12px;"><?=$this->session->userdata('email')?></span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt-2">
    <div class="card">
        <div class="card-body" style="box-shadow:1px 1px 12px #dde;">
            <div class="col-sm-12">
                <h6 class="text-dark">My Account</h6>
            </div>
            <div class="d-flex justify-content-between" style="padding: 20px 0px;">
                <div class="Deposited text-muted">
                    <i class="fa fa-rupee-sign"></i> <span><?php 
                        $data = $this->db->where('user',$this->session->userdata('gameuser'))
                                        //->where('gate','app') 
                                        ->where('order','Balance added')
                                        ->where('status','SUCCESS')
                                        ->where('dr_cr=','Cr')
                                        ->select('t_amt')
                                        ->from('transaction')
                                        ->get()->result();
                                        
                            $left_bus = 0;
                            foreach ($data as $key) {
                                 $left_bus += $key->t_amt;
                            }
                           // $left_bus;              
                            $precision = 1;
                                if ($left_bus < 900) {
                                // 0 - 900
                                $n_format = number_format($left_bus, $precision);
                                    $n_format.''.$suffix = '';
                                    echo floatval($n_format);
                                } else if ($left_bus < 900000) {
                                    // 0.9k-850k
                                    $n_format = number_format($left_bus / 1000, $precision);
                                    echo $n_format.''. $suffix = 'K';
                                } else if ($left_bus < 900000000) {
                                    // 0.9m-850m
                                    $n_format = number_format($left_bus / 1000000, $precision);
                                    echo $n_format.''.$suffix = 'M';
                                } else if ($left_bus < 900000000000) {
                                    // 0.9b-850b
                                    $n_format = number_format($left_bus / 1000000000, $precision);
                                    echo $n_format.''.$suffix = 'B';
                                } else {
                                    // 0.9t+
                                    $n_format = number_format($left_bus / 1000000000000, $precision);
                                    echo $n_format.''.$suffix = 'T';
                                }
                                    // Remove unecessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1"
                                    // Intentionally does not affect partials, eg "1.50" -> "1.50"
                                    if ( $precision > 0 ) {
                                        $dotzero = '.' . str_repeat( '0', $precision );
                                        $n_format = str_replace( $dotzero, '', $n_format );
                                    } 
                    ?></span>
                    <div class="widthrow mt-1">
                        <small class="text-muted">Deposited</small>
                    </div>
                    <div class="addBlance mt-4">
                        <a href="<?=base_url()?>api/add-balance" class="addbtn">+ BALANCE</a>
                    </div>    
                </div>
                <span class="border-right ml-1 mr-1"></span>
                <div class="Bonus text-muted">
                    <i class="fa fa-rupee-sign"></i> <span>0.00</span>
                    <div class="widthrow mt-1">
                        <small class="text-muted">Bonus</small>
                    </div>
                </div>
                <span class="border-right ml-1 mr-1"></span>
                <div class="Winnings text-muted">
                    <i class="fa fa-rupee-sign"></i> <span><?php 
                         $data =  $this->db->where('user',$this->session->userdata('gameuser'))
                                          //->where('gate','app')    
                                          ->where('order','Winnings')    
                                          ->select('t_amt')
                                          ->from('transaction')
                                          ->get()->result();  
                        $left_bus = 0;
                           foreach ($data as $key) {
                             $left_bus += $key->t_amt;
                           }
                           
                        $precision = 1;
                                if ($left_bus < 900) {
                                // 0 - 900
                                $n_format = number_format($left_bus, $precision);
                                    $n_format.''.$suffix = '';
                                    echo floatval($n_format);
                                } else if ($left_bus < 900000) {
                                    // 0.9k-850k
                                    $n_format = number_format($left_bus / 1000, $precision);
                                    echo $n_format.''. $suffix = 'K';
                                } else if ($left_bus < 900000000) {
                                    // 0.9m-850m
                                    $n_format = number_format($left_bus / 1000000, $precision);
                                    echo $n_format.''.$suffix = 'M';
                                } else if ($left_bus < 900000000000) {
                                    // 0.9b-850b
                                    $n_format = number_format($left_bus / 1000000000, $precision);
                                    echo $n_format.''.$suffix = 'B';
                                } else {
                                    // 0.9t+
                                    $n_format = number_format($left_bus / 1000000000000, $precision);
                                    echo $n_format.''.$suffix = 'T';
                                }
                                    // Remove unecessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1"
                                    // Intentionally does not affect partials, eg "1.50" -> "1.50"
                                    if ( $precision > 0 ) {
                                        $dotzero = '.' . str_repeat( '0', $precision );
                                        $n_format = str_replace( $dotzero, '', $n_format );
                                    } 
                        
                        ?>
                    </span>
                    <div class="widthrow mt-1">
                        <small class="text-muted">Winnings</small>
                    </div>
                    <div class="widthrow mt-4">
                        <a href="<?=base_url()?>api/withdrow-amount" class="withbtn">WITHDRAW</a>

                    </div>    
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between">
                <div>
                    <a href="<?=base_url()?>api/transaction_history" class="btn sameBtnDesign" id="clicktoLoad">Transaction History</a>
                </div>
                <div class="ml-2">
                    <a href="<?=base_url()?>api/widthdrawlHistory" class="btn sameBtnDesign"  id="clicktoLoad">Withdrawl History</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $up = $this->db->where('member_id',$this->session->userdata('gameuser'))->select('status')->get('login')->row();?>
<?php if(!empty($up)){?>
<?php if($up->status == '0'){ ?>
<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-md-12 text-center">
            <div>
                <?php $upt = $this->db->where('member_id',$this->session->userdata('gameuser'))->select('upgrade')->get('login')->row();?>
                <?php if(!empty($upt)){?>
                <?php if($upt->upgrade == '0'){ ?>
                <a href="<?=base_url()?>api/upgrade" class="btn-block btn-primary p-2" style="font-size:20px;"  id="clicktoLoad"><i class="fas fa-level-up-alt"></i> UPGRADE</a>
                <?php } else {?>
                <a href="javascript:;" class="btn-block btn-success p-2" style="font-size:15px;"><i class="fa fa-check-circle"></i> YOUR ARE UPGRADE</a>
                <?php } }?>
            </div>
        </div>
    </div>
</div>
<?php } }?>

<div class="container-fluid mt-3" style="margin-bottom: 70px;">
    <div class="row">
        <div class="col-md-12 text-center">
            <div>
                <a href="<?=base_url()?>api/log-out" class="btn-block logoutbtndesigb"  id="clicktoLoad"><i class="fas fa-sign-out-alt"></i> Sign out</a>
            </div>
            <style type="text/css">
                a.logoutbtndesigb {
                        background: #282f3a;
                        color: #fff;
                        padding: 6px;
                        border-radius: 5px;
                        font-size: 23px;
                    }
            </style>
        </div>
    </div>
</div>
<style type="text/css">
    a.btn.sameBtnDesign {
        background: #ff3366;
        color: #fff;
        font-size: 14px;
        padding: 15px;
        font-family: inherit;
    }
</style>

