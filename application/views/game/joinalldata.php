<?php
    $id = $this->db->where('member_id',$this->session->userdata('gameuser'))->select('id')->get('login')->row('id');
    $mid = $this->db->where('parent',$id)->select('user_id')->get('makebinary')->result_array();
?>
<div class="container-fluid mb-4" style="margin-top:70px">
    <div class="row">
        <div class="col-md-12">
        <?php if(count($mid) > 0){ ?>
        <table class="table table-borderd">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Side</th>
                    <th>Package</th>
                </tr>
            </thead>
            <tbody>
                <style>
                    #icon {
                        font-size:5px;
                        margin-bottom:2px;
                    }
                    #fontSize{
                        font-size:11px;
                    }
                </style>
        <?php foreach($mid as $row){ ?>
            <tr>
                <td>
                    <?php $s = $this->db->where('member_id',$row['user_id'])->select('status')->get('login')->row('status');?>
                    <?php if($s == 0){?>
                        <i class="fa fa-circle text-success" id="icon"></i>
                    <?php } elseif($s == 1) {?>
                        <i class="fa fa-circle text-dark" id="icon"></i>
                    <?php } elseif($s == 2) {?>
                        <i class="fa fa-circle text-warning" id="icon"></i>
                    <?php } elseif($s == 3) {?>
                        <i class="fa fa-circle text-danger" id="icon"></i>
                    <?php } ?>
                    <span id="fontSize"><?=$this->db->where('member_id',$row['user_id'])->select('name')->get('login')->row('name')?></span>
                </td>
                <td id="fontSize"><?=$this->db->where('member_id', $row['user_id'])->select('side')->get('login')->row('side')?></td>
                <td id="fontSize">₹ <?php $p = $this->db->where('member_id',$row['user_id'])->select('package_amt')->get('login')->row('package_amt'); if(!empty($p)) echo $p; else echo '0.00';?></td>
            </tr>
        <?php }?>
        </tbody>
        </table>
        <?php }  else{ ?>
            <ul style="list-style:none; text-decoration:none;" class="moreMenuParentall">
                <li>
                    <a href="javascript:;" class="text-dark">
                        <i class="fa fa-user-circle"></i> 
                        <span class="border-right ml-1 mr-1"></span>
                        <span>You have <strong><?=count($mid)?></strong> teams! </span>
                    </a>
                </li>
            </ul>
        <?php } ?>
        </div>
    </div>
</div>
