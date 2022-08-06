
<div class="container"  style="margin-top:70px;">
</div>

<div class="container mt-3">
    <div class="row">
        <div class="col-md-12">
            <?php $re = $this->db->where('member_id',$this->session->userdata('gameuser'))->where('status','0')->select('member_id')->from('login')->get()->row();?>
            <?php if(empty($re)){?>
            <a href="<?=base_url()?>api/join-contest" class="btn btn-block btn-info p-3">JOIN TOURNAMENT</a>
            <?php } else{ ?>
            <a href="<?=base_url()?>api/view-mega-contest-all" class="btn btn-block btn-info p-3">VIEW MEGACONTEST TOURNAMENT</a>
            <?php } ?>
        </div>
    </div>    
</div>
<div class="container mt-3">
    <ul class="nav nav-pills mb-3 d-flex justify-content-between" id="pills-tab" role="tablist">
      <li class="nav-item">
        <a class="nav-link checkyo" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">ALL GAMES</a>
      </li>
      <li class="nav-item">
        <a class="nav-link checkyo active" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false"><span><i class="fa fa-circle text-white" style="font-size:8px;vertical-align: top;"></i></span> LIVE</a>
      </li>
     
    </ul>
<div class="border-top mb-2"></div>    
<div class="tab-content" id="pills-tabContent">
  <div class="tab-pane fade" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
    <div class="container">
        <div class="row mt-3 mb-5">
            <div class="col-12 col-xl-12 col-md-12">
                <div class="row flex-grow" id="showGames"></div>
            </div>
        </div> <!-- row -->
    </div>      
  </div>
  <div class="tab-pane fade show active" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
      <div class="row flex-grow" id="showlive"></div>
    </div>
  </div>
</div>
</div>
<div style="margin-bottom:70px;"></div>
