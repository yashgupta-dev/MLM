<div class="page-content">
    <div class="row">
        <div class="col-12 col-xl-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row flex-grow">
                        <div class="col-12 text-center">
                                <?php if(!empty($tree)){ foreach($tree as $row){ ?>
                                <?php
                                    function countRight($id){
                                        $url  = base_url().'get/apifunctiongetdata?q='.$id;
                                        $ch = curl_init($url);
                                        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                        curl_setopt($ch, CURLOPT_HTTPHEADER, [
                                        'Content-Type: application/json'
                                        ]);
                                        $result = curl_exec($ch);
                                        if(curl_errno($ch)){
                                            print('error in posting');
                                            print(curl_error($ch));
                                            die();
                                        }
                                        curl_close($ch);
                                        $rObj = json_decode($result,true);
                                        if($rObj['status'] != 'SUCCESS' || $rObj['subCode'] != '200') return $rObj['right'];
                                    }
                                    function countLeft($id){
                                        $url  = base_url().'get/apifunctiongetdataLeftCount?q='.$id;
                                        $ch = curl_init($url);
                                        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                        curl_setopt($ch, CURLOPT_HTTPHEADER, [
                                        'Content-Type: application/json'
                                        ]);
                                        $result = curl_exec($ch);
                                        if(curl_errno($ch)){
                                            print('error in posting');
                                            print(curl_error($ch));
                                            die();
                                        }
                                        curl_close($ch);
                                        $rObj = json_decode($result,true);
                                        if($rObj['status'] != 'SUCCESS' || $rObj['subCode'] != '200') return $rObj['right'];
                                    }
                                    function rightBusiness($id){
                                        $url  = base_url().'get/apiGetRightBusniness?q='.$id;
                                        $ch = curl_init($url);
                                        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                        curl_setopt($ch, CURLOPT_HTTPHEADER, [
                                            'Content-Type: application/json'
                                        ]);
                                    
                                        $result = curl_exec($ch);
                                        if(curl_errno($ch)){
                                            print('error in posting');
                                            print(curl_error($ch));
                                            die();
                                        }
                                        curl_close($ch);
                                        $rObj = json_decode($result,true);
                                        if($rObj['status'] != 'SUCCESS' || $rObj['subCode'] != '200') return $rObj['rightBusiness'];
                                        
                                    }
                                    function leftBusiness($id){
                                        $url  = base_url().'get/apiGetLeftBusniness?q='.$id;
                                        $ch = curl_init($url);
                                        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                        curl_setopt($ch, CURLOPT_HTTPHEADER, [
                                            'Content-Type: application/json'
                                        ]);
                                    
                                        $result = curl_exec($ch);
                                        if(curl_errno($ch)){
                                            print('error in posting');
                                            print(curl_error($ch));
                                            die();
                                        }
                                        curl_close($ch);
                                        $rObj = json_decode($result,true);
                                        if($rObj['status'] != 'SUCCESS' || $rObj['subCode'] != '200') return $rObj['leftBusiness'];
                                        
                                    }
                                ?>
                                <!---first top-->
                                <div class="bottom">
                                    <a class="tooltipnew" id="mouseHover_to_SHow" data-id="<?php echo base64_encode($row->member_id);?>" href="javascript:;">
                                        <img id="responsive_classImg"  src="<?php echo base_url();?>adminassets/userimg/<?php if($row->status == '0' || $row->status == '2' || $row->status == '3'){echo 'green';} elseif($row->status == '1'){echo 'red';}?>.png">
                                         <div class="tooltiptextnew">
                                            <ul class="myulnew">
                                                <li class="d-flex justify-content-between align-items-center">
                                                    User Id
                                                    <span class="text-white"><?=$row->member_id?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Sponser Id
                                                    <span class="text-white"><?=$this->db->where('member_id',$row->member_id)->select('direct_sp')->get('login')->row('direct_sp')?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Full Name
                                                    <span class="text-white"><?=$this->db->where('member_id',$row->member_id)->select('name')->get('login')->row('name')?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Date Of Joining
                                                    <span class="text-white"><i class="fa fa-calendar-alt"></i> <?=$this->db->where('member_id',$row->member_id)->select('time')->get('login')->row('time')?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Joining Package
                                                    <span class="text-white"><i class="fa fa-rupee-sign"></i> <?=$this->db->where('member_id',$row->member_id)->select('package_amt')->get('login')->row('package_amt')?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Total Right
                                                    <span class="text-white"> <?=countRight($row->member_id)?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Total Left
                                                    <span class="text-white"> <?=countLeft($row->member_id)?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Total Business
                                                    <span class="text-white"><i class="fa fa-rupee-sign"></i> <?php echo rightBusiness($row->member_id) + leftBusiness($row->member_id); ?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Total Right Business
                                                    <span class="text-white"><i class="fa fa-rupee-sign"></i> <?=rightBusiness($row->member_id)?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Total Left Business
                                                    <span class="text-white"><i class="fa fa-rupee-sign"></i> <?=leftBusiness($row->member_id)?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Total BV
                                                    <span class="text-white"><i class="fa fa-check-circle"></i> <?=$this->db->where('userid',$row->member_id)->select('RightBV')->get('bv')->row('RightBV')?></span>
                                                </li>
                                                
                                                
                                            </ul>
                                        </div>
                                    </a>
                                    <div class="text-center" id="responsiveTextFile">
                                    <a <?php if($row->status == '1'){echo 'href="'.base_url().'my/team/Genealogy?q='.$row->member_id.'"';}else{echo 'href="'.base_url().'my/team/Genealogy?q='.$row->member_id.'"';}?>><?=$row->name?></a><br>
                                        <a <?php if($row->status == '1'){echo 'href="'.base_url().'member-activate?q='.$row->member_id.'"';}else{echo 'href="'.base_url().'my/team/Genealogy?q='.$row->member_id.'"';}?>><?=$row->member_id?></a>
                                    </div>
                                </div>
                                <!---first end-->
                                <div class="col-12 text-center">
                                <!---first img-->
                                    <img id="responsive_classImg" src="<?php echo base_url();?>adminassets/userimg/l1.png" style="width:50%; height: auto;">
                                    <!---first end-->
                                </div>
                                <div class="row">
                                    <?php if(isset($row->sub_name)){
                                    foreach($row->sub_name  as $child) { 
                                        if(!empty($child->side == 'Left')) { ?>
                                    <div class="col-6">
                                    <!---seond Left top-->
                                        <div class="bottom">
                                            <a class="tooltipnewsecond" id="mouseHover_to_SHow1"  data-id="<?php echo base64_encode($child->member_id);?>"href="javascript:;">
                                                <img id="responsive_classImg"  src="<?php echo base_url();?>adminassets/userimg/<?php if($child->status == '0' || $child->status == '2' || $child->status == '3'){echo 'green';} elseif($child->status == '1'){echo 'red';}?>.png">
                                                <div class="tooltiptextnewsecond">
                                                    <ul class="myulnew">
                                                <li class="d-flex justify-content-between align-items-center">
                                                    User Id
                                                    <span class="text-white"><?=$child->member_id?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Sponser Id
                                                    <span class="text-white"><?=$this->db->where('member_id',$child->member_id)->select('direct_sp')->get('login')->row('direct_sp')?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Full Name
                                                    <span class="text-white"><?=$this->db->where('member_id',$child->member_id)->select('name')->get('login')->row('name')?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Date Of Joining
                                                    <span class="text-white"><i class="fa fa-calendar-alt"></i> <?=$this->db->where('member_id',$child->member_id)->select('time')->get('login')->row('time')?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Joining Package
                                                    <span class="text-white"><i class="fa fa-rupee-sign"></i> <?=$this->db->where('member_id',$child->member_id)->select('package_amt')->get('login')->row('package_amt')?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Total Right
                                                    <span class="text-white"> <?=countRight($child->member_id)?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Total Left
                                                    <span class="text-white"> <?=countLeft($child->member_id)?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Total Business
                                                    <span class="text-white"><i class="fa fa-rupee-sign"></i> <?php echo rightBusiness($child->member_id) + leftBusiness($child->member_id); ?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Total Right Business
                                                    <span class="text-white"><i class="fa fa-rupee-sign"></i> <?=rightBusiness($child->member_id)?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Total Left Business
                                                    <span class="text-white"><i class="fa fa-rupee-sign"></i> <?=leftBusiness($child->member_id)?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Total BV
                                                    <span class="text-white"><i class="fa fa-check-circle"></i> <?=$this->db->where('userid',$child->member_id)->select('RightBV')->get('bv')->row('RightBV')?></span>
                                                </li>
                                                
                                            </ul>
                                                </div>
                                            </a>
                                            <div class="text-center" id="responsiveTextFile">
                                            <a <?php if($child->status == '1'){echo 'href="'.base_url().'my/team/Genealogy?q='.$child->member_id.'"';}else{echo 'href="'.base_url().'my/team/Genealogy?q='.$child->member_id.'"';}?>><?=$child->name?></a><br>
                                                <a <?php if($child->status == '1'){echo 'href="'.base_url().'member-activate?q='.$child->member_id.'"';}else{echo 'href="'.base_url().'my/team/Genealogy?q='.$child->member_id.'"';}?>><?=$child->member_id?></a>
                                            </div>
                                        </div>
                                        <!---second Left end-->
                                    </div>
                                    <?php } else{  ?>
                                    <div class="col-6">
                                        <div class="con-tooltip bottom">
                                            <a href="<?=base_url()?>customer/Register-New/<?=$this->session->userdata('user_id')?>" target="_blank" class="nav-link">
                                                <img id="responsive_classImg" src="<?php echo base_url();?>adminassets/userimg/black.png" >
                                            </a>
                                            <div class="text-center" id="responsiveTextFile">
                                               Vacant<br>
                                                add
                                            </div>
                                        </div>  
                                    </div>
                                    <?php }  ?>
                                    <?php } } else{ ?>
                                    <div class="col-6">
                                        <div class="con-tooltip bottom">
                                            <a href="<?=base_url()?>customer/Register-New/<?=$this->session->userdata('user_id')?>" target="_blank" class="nav-link">
                                                <img id="responsive_classImg" src="<?php echo base_url();?>adminassets/userimg/black.png" >
                                            </a>
                                            <div class="text-center" id="responsiveTextFile">
                                                Vacant<br>
                                                    Add 
                                            </div>
                                        </div>  
                                    </div>
                                    <?php }?>
                                    <?php if(!empty($rightsecond)){
                                    foreach ($rightsecond as $rightfetch) {
                                        if(isset($rightfetch->sub_name)) {
                                            foreach ($rightfetch->sub_name as $m) { ?>
                                    <div class="col-6">
                                        <!---seond Left top-->
                                        <div class=" bottom">
                                            <a class="tooltipnewthird"id="mouseHover_to_SHow2" data-id="<?php echo base64_encode($m->member_id);?>" href="javascript:;">
                                                <img id="responsive_classImg"  src="<?php echo base_url();?>adminassets/userimg/<?php if($m->status == '0' || $m->status == '2' ||$m->status == '3'){echo 'green';} elseif($m->status == '1'){echo 'red';}?>.png">
                                                <div class="tooltiptextnewthird">
                                                    <ul class="myulnew">
                                                <li class="d-flex justify-content-between align-items-center">
                                                    User Id
                                                    <span class="text-white"><?=$m->member_id?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Sponser Id
                                                    <span class="text-white"><?=$this->db->where('member_id',$m->member_id)->select('direct_sp')->get('login')->row('direct_sp')?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Full Name
                                                    <span class="text-white"><?=$this->db->where('member_id',$m->member_id)->select('name')->get('login')->row('name')?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Date Of Joining
                                                    <span class="text-white"><i class="fa fa-calendar-alt"></i> <?=$this->db->where('member_id',$m->member_id)->select('time')->get('login')->row('time')?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Joining Package
                                                    <span class="text-white"><i class="fa fa-rupee-sign"></i> <?=$this->db->where('member_id',$m->member_id)->select('package_amt')->get('login')->row('package_amt')?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Total Right
                                                    <span class="text-white"> <?=countRight($m->member_id)?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Total Left
                                                    <span class="text-white"> <?=countLeft($m->member_id)?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Total Business
                                                    <span class="text-white"><i class="fa fa-rupee-sign"></i> <?php echo rightBusiness($m->member_id) + leftBusiness($m->member_id); ?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Total Right Business
                                                    <span class="text-white"><i class="fa fa-rupee-sign"></i> <?=rightBusiness($m->member_id)?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Total Left Business
                                                    <span class="text-white"><i class="fa fa-rupee-sign"></i> <?=leftBusiness($m->member_id)?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Total BV
                                                    <span class="text-white"><i class="fa fa-check-circle"></i> <?=$this->db->where('userid',$m->member_id)->select('RightBV')->get('bv')->row('RightBV')?></span>
                                                </li>
                                                
                                            </ul>
                                                </div>
                                            </a>
                                            <div class="text-center" id="responsiveTextFile">
                                            <a <?php if($m->status == '1'){echo 'href="'.base_url().'my/team/Genealogy?q='.$m->member_id.'"';}else{echo 'href="'.base_url().'my/team/Genealogy?q='.$m->member_id.'"';}?>><?=$m->name?></a><br>
                                               <a <?php if($m->status == '1'){echo 'href="'.base_url().'member-activate?q='.$m->member_id.'"';}else{echo 'href="'.base_url().'my/team/Genealogy?q='.$m->member_id.'"';}?>><?=$m->member_id?></a>
                                            </div>
                                        </div>
                                        <!---second Left end-->
                                    </div>
                                    <?php }?> <?php } else{?>
                                    <div class="col-6">
                                        <div class="con-tooltip bottom">
                                            <a href="<?=base_url()?>customer/Register-New/<?=$this->session->userdata('user_id')?>" target="_blank" class="nav-link">
                                                <img id="responsive_classImg" src="<?php echo base_url();?>adminassets/userimg/black.png" >
                                            </a>
                                            <div class="text-center" id="responsiveTextFile">
                                               Vacant<br>
                                                Add 
                                            </div>
                                        </div>  
                                    </div>
                                    <?php } } } ?>
                                </div>
                                <div class="row">
                                    <!---third Right img top-->
                                    <div class="col-6">
                                        <img id="responsive_classImg" src="<?php echo base_url();?>adminassets/userimg/l2.png" style="width:50%">
                                    </div>
                                    <div class="col-6">
                                        <img id="responsive_classImg" src="<?php echo base_url();?>adminassets/userimg/l2.png" style="width:50%">
                                    </div>
                                    <!---third Right img end-->
                                </div>
                                <div class="row">
                                    <?php if(!empty($thirdleft_child)) {
                                        foreach ($thirdleft_child as $rightfetch) {
                                            if(isset($rightfetch->sub_name)){
                                                foreach ($rightfetch->sub_name as $m) { ?>
                                    <div class="col-3">
                                        <!---seond Left top-->
                                        <div class="bottom">
                                            <a class="tooltipnewfourleft"  id="mouseHover_to_SHow4" data-id="<?php echo base64_encode($m->member_id);?>" href="javascript:;">
                                                <img id="responsive_classImg"   src="<?php echo base_url();?>adminassets/userimg/<?php if($m->status == '0' || $m->status == '2' ||$m->status == '3'){echo 'green';} elseif($m->status == '1'){echo 'red';}?>.png">
                                                <div class="tooltiptextnewfourleft">
                                                    <ul class="myulnew">
                                                <li class="d-flex justify-content-between align-items-center">
                                                    User Id
                                                    <span class="text-white"><?=$m->member_id?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Sponser Id
                                                    <span class="text-white"><?=$this->db->where('member_id',$m->member_id)->select('direct_sp')->get('login')->row('direct_sp')?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Full Name
                                                    <span class="text-white"><?=$this->db->where('member_id',$m->member_id)->select('name')->get('login')->row('name')?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Date Of Joining
                                                    <span class="text-white"><i class="fa fa-calendar-alt"></i> <?=$this->db->where('member_id',$m->member_id)->select('time')->get('login')->row('time')?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Joining Package
                                                    <span class="text-white"><i class="fa fa-rupee-sign"></i> <?=$this->db->where('member_id',$m->member_id)->select('package_amt')->get('login')->row('package_amt')?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Total Right
                                                    <span class="text-white"> <?=countRight($m->member_id)?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Total Left
                                                    <span class="text-white"> <?=countLeft($m->member_id)?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Total Business
                                                    <span class="text-white"><i class="fa fa-rupee-sign"></i> <?php echo rightBusiness($m->member_id) + leftBusiness($m->member_id); ?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Total Right Business
                                                    <span class="text-white"><i class="fa fa-rupee-sign"></i> <?=rightBusiness($m->member_id)?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Total Left Business
                                                    <span class="text-white"><i class="fa fa-rupee-sign"></i> <?=leftBusiness($m->member_id)?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Total BV
                                                    <span class="text-white"><i class="fa fa-check-circle"></i> <?=$this->db->where('userid',$m->member_id)->select('RightBV')->get('bv')->row('RightBV')?></span>
                                                </li>
                                                
                                                
                                            </ul>
                                                </div>
                                            </a>
                                            <div class="text-center" id="responsiveTextFile">
                                            <a <?php if($m->status == '1'){echo 'href="'.base_url().'my/team/Genealogy?q='.$m->member_id.'"';}else{echo 'href="'.base_url().'my/team/Genealogy?q='.$m->member_id.'"';}?>><?=$m->name?></a><br>
                                                <a <?php if($m->status == '1'){echo 'href="'.base_url().'member-activate?q='.$m->member_id.'"';}else{echo 'href="'.base_url().'my/team/Genealogy?q='.$m->member_id.'"';}?>><?=$m->member_id?></a>
                                            </div>
                                        </div>
                                        <!---second Left end-->
                                    </div>
                                    <?php }?> <?php } else{?>
                                    <div class="col-3">
                                        <div class="con-tooltip bottom">
                                            <a href="<?=base_url()?>customer/Register-New/<?=$this->session->userdata('user_id')?>" target="_blank" class="nav-link">
                                                <img id="responsive_classImg" src="<?php echo base_url();?>adminassets/userimg/black.png" >   
                                            </a>
                                            <div class="text-center" id="responsiveTextFile">
                                               Vacant<br>
                                                Add 
                                            </div>
                                        </div>  
                                    </div>
                                    <?php } } } else{?>
                                    <div class="col-3">
                                        <div class="con-tooltip bottom">
                                            <a href="<?=base_url()?>customer/Register-New/<?=$this->session->userdata('user_id')?>" target="_blank" class="nav-link">
                                                <img id="responsive_classImg" src="<?php echo base_url();?>adminassets/userimg/black.png" >
                                            </a>
                                            <div class="text-center" id="responsiveTextFile">
                                               Vacant<br>
                                                Add 
                                            </div>
                                        </div>  
                                    </div>
                                    <?php } ?> <?php if(!empty($thirdright_child)) {
                                        foreach ($thirdright_child as $rightfetch) {
                                            if(isset($rightfetch->sub_name)){
                                                foreach ($rightfetch->sub_name as $m) { ?>
                                    <div class="col-3">
                                        <!---seond Left top-->
                                        <div class=" bottom">
                                            <a class="tooltipnewfiveleft"id="mouseHover_to_SHow5" data-id="<?php echo base64_encode($m->member_id);?>"  href="javascript:;">
                                                <img id="responsive_classImg"   src="<?php echo base_url();?>adminassets/userimg/<?php if($m->status == '0' || $m->status == '2' ||$m->status == '3'){echo 'green';} elseif($m->status == '1'){echo 'red';}?>.png">
                                                <div class="tooltiptextnewfiveleft">
                                                    <ul class="myulnew">
                                                <li class="d-flex justify-content-between align-items-center">
                                                    User Id
                                                    <span class="text-white"><?=$m->member_id?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Sponser Id
                                                    <span class="text-white"><?=$this->db->where('member_id',$m->member_id)->select('direct_sp')->get('login')->row('direct_sp')?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Full Name
                                                    <span class="text-white"><?=$this->db->where('member_id',$m->member_id)->select('name')->get('login')->row('name')?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Date Of Joining
                                                    <span class="text-white"><i class="fa fa-calendar-alt"></i> <?=$this->db->where('member_id',$m->member_id)->select('time')->get('login')->row('time')?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Joining Package
                                                    <span class="text-white"><i class="fa fa-rupee-sign"></i> <?=$this->db->where('member_id',$m->member_id)->select('package_amt')->get('login')->row('package_amt')?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Total Right
                                                    <span class="text-white"> <?=countRight($m->member_id)?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Total Left
                                                    <span class="text-white"> <?=countLeft($m->member_id)?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Total Business
                                                    <span class="text-white"><i class="fa fa-rupee-sign"></i> <?php echo rightBusiness($m->member_id) + leftBusiness($m->member_id); ?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Total Right Business
                                                    <span class="text-white"><i class="fa fa-rupee-sign"></i> <?=rightBusiness($m->member_id)?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Total Left Business
                                                    <span class="text-white"><i class="fa fa-rupee-sign"></i> <?=leftBusiness($m->member_id)?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Total BV
                                                    <span class="text-white"><i class="fa fa-check-circle"></i> <?=$this->db->where('userid',$m->member_id)->select('RightBV')->get('bv')->row('RightBV')?></span>
                                                </li>
                                               
                                            </ul>
                                                </div>
                                            </a>
                                            <div class="text-center" id="responsiveTextFile">
                                            <a <?php if($m->status == '1'){echo 'href="'.base_url().'my/team/Genealogy?q='.$m->member_id.'"';}else{echo 'href="'.base_url().'my/team/Genealogy?q='.$m->member_id.'"';}?>><?=$m->name?></a><br>
                                                <a <?php if($m->status == '1'){echo 'href="'.base_url().'member-activate?q='.$m->member_id.'"';}else{echo 'href="'.base_url().'my/team/Genealogy?q='.$m->member_id.'"';}?>><?=$m->member_id?></a>
                                            </div>
                                        </div>
                                        <!---second Left end-->
                                    </div>
                                    <?php } ?><?php } else{?>
                                    <div class="col-3">
                                      <div class="con-tooltip bottom">
                                            <a href="<?=base_url()?>customer/Register-New/<?=$this->session->userdata('user_id')?>" target="_blank" class="nav-link">
                                                <img id="responsive_classImg" src="<?php echo base_url();?>adminassets/userimg/black.png" >
                                            </a>
                                            <div class="text-center" id="responsiveTextFile">
                                               Vacant<br>
                                                Add 
                                            </div>
                                        </div>  
                                    </div>
                                    <?php } } } else{?>
                                    <div class="col-3">
                                        <div class="con-tooltip bottom">
                                            <a href="<?=base_url()?>customer/Register-New/<?=$this->session->userdata('user_id')?>" target="_blank" class="nav-link">
                                                <img id="responsive_classImg" src="<?php echo base_url();?>adminassets/userimg/black.png" >
                                            </a>
                                            <div class="text-center" id="responsiveTextFile">
                                               Vacant<br>
                                                Add 
                                            </div>
                                        </div>  
                                    </div>
                                    <?php } ?>
                                    <!------------------------------------------------------------------------------------------>
                                    <?php if(!empty($thirdleft2_child)) {
                                        foreach ($thirdleft2_child as $rightfetch) {
                                            if(isset($rightfetch->sub_name)){
                                                foreach ($rightfetch->sub_name as $m) { ?>
                                    <div class="col-3">
                                        <!---seond Left top-->
                                        <div class="bottom">
                                            <a class="tooltipnewfourright"id="mouseHover_to_SHow6" data-id="<?php echo base64_encode($m->member_id);?>" href="javascript:;">
                                                <img id="responsive_classImg"   src="<?php echo base_url();?>adminassets/userimg/<?php if($m->status == '0' || $m->status == '2' ||$m->status == '3'){echo 'green';} elseif($m->status == '1'){echo 'red';}?>.png">
                                                <div class="tooltiptextnewfourright">
                                                    <ul class="myulnew">
                                                <li class="d-flex justify-content-between align-items-center">
                                                    User Id
                                                    <span class="text-white"><?=$m->member_id?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Sponser Id
                                                    <span class="text-white"><?=$this->db->where('member_id',$m->member_id)->select('direct_sp')->get('login')->row('direct_sp')?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Full Name
                                                    <span class="text-white"><?=$this->db->where('member_id',$m->member_id)->select('name')->get('login')->row('name')?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Date Of Joining
                                                    <span class="text-white"><i class="fa fa-calendar-alt"></i> <?=$this->db->where('member_id',$m->member_id)->select('time')->get('login')->row('time')?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Joining Package
                                                    <span class="text-white"><i class="fa fa-rupee-sign"></i> <?=$this->db->where('member_id',$m->member_id)->select('package_amt')->get('login')->row('package_amt')?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Total Right
                                                    <span class="text-white"> <?=countRight($m->member_id)?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Total Left
                                                    <span class="text-white"> <?=countLeft($m->member_id)?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Total Business
                                                    <span class="text-white"><i class="fa fa-rupee-sign"></i> <?php echo rightBusiness($m->member_id) + leftBusiness($m->member_id); ?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Total Right Business
                                                    <span class="text-white"><i class="fa fa-rupee-sign"></i> <?=rightBusiness($m->member_id)?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Total Left Business
                                                    <span class="text-white"><i class="fa fa-rupee-sign"></i> <?=leftBusiness($m->member_id)?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Total BV
                                                    <span class="text-white"><i class="fa fa-check-circle"></i> <?=$this->db->where('userid',$m->member_id)->select('RightBV')->get('bv')->row('RightBV')?></span>
                                                </li>
                                                
                                            </ul>
                                                </div>
                                            </a>
                                            <div class="text-center" id="responsiveTextFile">
                                            <a <?php if($m->status == '1'){echo 'href="'.base_url().'my/team/Genealogy?q='.$m->member_id.'"';}else{echo 'href="'.base_url().'my/team/Genealogy?q='.$m->member_id.'"';}?>><?=$m->name?></a><br>
                                                <a <?php if($m->status == '1'){echo 'href="'.base_url().'member-activate?q='.$m->member_id.'"';}else{echo 'href="'.base_url().'my/team/Genealogy?q='.$m->member_id.'"';}?>><?=$m->member_id?></a>
                                            </div>
                                        </div>
                                        <!---second Left end-->
                                    </div>
                                    <?php } ?><?php } else{?>
                                    <div class="col-3">
                                        <div class="con-tooltip bottom">
                                            <a href="<?=base_url()?>customer/Register-New/<?=$this->session->userdata('user_id')?>" target="_blank" class="nav-link">
                                                <img id="responsive_classImg" src="<?php echo base_url();?>adminassets/userimg/black.png" >
                                            </a>
                                            <div class="text-center" id="responsiveTextFile">
                                               Vacant<br>
                                                Add 
                                            </div>
                                        </div>  
                                    </div>
                                    <?php } } } else{?>
                                    <div class="col-3">
                                        <div class="con-tooltip bottom">
                                            <a href="<?=base_url()?>customer/Register-New/<?=$this->session->userdata('user_id')?>" target="_blank" class="nav-link">
                                                <img id="responsive_classImg" src="<?php echo base_url();?>adminassets/userimg/black.png" >
                                            </a>
                                            <div class="text-center" id="responsiveTextFile">
                                               Vacant<br>
                                                Add 
                                            </div>
                                        </div>  
                                    </div>
                                    <?php } ?> <?php if(!empty($thirdright2_child)){
                                        foreach ($thirdright2_child as $rightfetch) {
                                            if(isset($rightfetch->sub_name)){
                                                foreach ($rightfetch->sub_name as $m) { ?>
                                    <div class="col-3">
                                        <!---seond Left top-->
                                        <div class="bottom">
                                            <a class="tooltipnewfiveright"id="mouseHover_to_SHow7" data-id="<?php echo base64_encode($m->member_id);?>" href="javascript:;">
                                                <img id="responsive_classImg"   src="<?php echo base_url();?>adminassets/userimg/<?php if($m->status == '0' || $m->status == '2' ||$m->status == '3'){echo 'green';} elseif($m->status == '1'){echo 'red';}?>.png">
                                                <div class="tooltiptextnewfiveright">
                                                    <ul class="myulnew">
                                                <li class="d-flex justify-content-between align-items-center">
                                                    User Id
                                                    <span class="text-white"><?=$m->member_id?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Sponser Id
                                                    <span class="text-white"><?=$this->db->where('member_id',$m->member_id)->select('direct_sp')->get('login')->row('direct_sp')?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Full Name
                                                    <span class="text-white"><?=$this->db->where('member_id',$m->member_id)->select('name')->get('login')->row('name')?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Date Of Joining
                                                    <span class="text-white"><i class="fa fa-calendar-alt"></i> <?=$this->db->where('member_id',$m->member_id)->select('time')->get('login')->row('time')?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Joining Package
                                                    <span class="text-white"><i class="fa fa-rupee-sign"></i> <?=$this->db->where('member_id',$m->member_id)->select('package_amt')->get('login')->row('package_amt')?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Total Right
                                                    <span class="text-white"> <?=countRight($m->member_id)?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Total Left
                                                    <span class="text-white"> <?=countLeft($m->member_id)?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Total Business
                                                    <span class="text-white"><i class="fa fa-rupee-sign"></i> <?php echo rightBusiness($m->member_id) + leftBusiness($m->member_id); ?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Total Right Business
                                                    <span class="text-white"><i class="fa fa-rupee-sign"></i> <?=rightBusiness($m->member_id)?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Total Left Business
                                                    <span class="text-white"><i class="fa fa-rupee-sign"></i> <?=leftBusiness($m->member_id)?></span>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    Total BV
                                                    <span class="text-white"><i class="fa fa-check-circle"></i> <?=$this->db->where('userid',$m->member_id)->select('RightBV')->get('bv')->row('RightBV')?></span>
                                                </li>
                                               
                                            </ul>
                                                </div>
                                            </a>
                                            <div class="text-center" id="responsiveTextFile">
                                            <a <?php if($m->status == '1'){echo 'href="'.base_url().'my/team/Genealogy?q='.$m->member_id.'"';}else{echo 'href="'.base_url().'my/team/Genealogy?q='.$m->member_id.'"';}?>><?=$m->name?></a><br>
                                                <a <?php if($m->status == '1'){echo 'href="'.base_url().'member-activate?q='.$m->member_id.'"';}else{echo 'href="'.base_url().'my/team/Genealogy?q='.$m->member_id.'"';}?>><?=$m->member_id?></a>
                                            </div>
                                        </div>
                                        <!---second Left end-->
                                    </div>
                                    <?php } ?><?php } else{?>
                                    <div class="col-3">
                                        <div class="con-tooltip bottom">
                                            <a href="<?=base_url()?>customer/Register-New/<?=$this->session->userdata('user_id')?>" target="_blank" class="nav-link">
                                                <img id="responsive_classImg" src="<?php echo base_url();?>adminassets/userimg/black.png" >
                                            </a>
                                            <div class="text-center" id="responsiveTextFile">
                                               Vacant<br>
                                                Add 
                                            </div>
                                        </div>  
                                    </div>
                                    <?php } } } else{?>
                                    <div class="col-3">
                                        <div class="con-tooltip bottom">
                                            <a href="<?=base_url()?>customer/Register-New/<?=$this->session->userdata('user_id')?>" target="_blank" class="nav-link">
                                                <img id="responsive_classImg" src="<?php echo base_url();?>adminassets/userimg/black.png" >
                                            </a>
                                            <div class="text-center" id="responsiveTextFile">
                                               Vacant<br>
                                                Add 
                                            </div>
                                        </div>  
                                    </div>
                                    <?php } ?>
                                </div>
                                <?php } }?>
                            </div>
                    </div>
                    <div class="row flex-grow bg-dark mt-5 p-3">
                        <div class="col-md-4 text-center" >
                              <img id="responsive_classImg" src="<?=base_url()?>adminassets/userimg/green.png">
                              <div class="text-center text-white">
                                <strong>Active</strong>
                              </div>
                            </div>
                        <div class="col-md-4 text-center">
                              <img id="responsive_classImg" src="<?=base_url()?>adminassets/userimg/red.png">
                              <div class="text-center text-white">
                                <strong>In-active</strong>
                              </div>
                            </div>
                        <div class="col-md-4 text-center">
                              <img id="responsive_classImg" src="<?=base_url()?>adminassets/userimg/black.png">
                              <div class="text-center text-white">
                                <strong>No User</strong>
                              </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- row -->
</div>			
			
			
			
