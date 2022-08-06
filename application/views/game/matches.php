
<div class="container" style="margin-top:70px;">
    <ul class="nav nav-pills mb-3 d-flex justify-content-between" id="pills-tab" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">RESULTS</a>
      </li>
     
    </ul>
<div class="border-top mb-2"></div>    
<div class="tab-content" id="pills-tabContent">
  <div class="tab-pane fade show active" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
      <div class="row">
          <div class="col-md-12">
            <?php $winningCheck = $this->db->where('user',$this->session->userdata('gameuser'))->order_by('id','desc')->select('tournament,amount')->get('winners')->result(); ?>  
            <?php if(!empty($winningCheck)){foreach($winningCheck as $row){ ?>
                <div class="card mb-1" style="box-shadow: 1px 1px 17px #dde;border-radius: 15px;">
                  <div class="card-body">
                      <div class="media">
                          <i class="mr-3 fa fa-trophy" style="color:gold; font-size:35px; margin-top:10px;"></i>
                          <span class="border-right ml-1 mr-1"></span>
                          <div class="media-body">
                            <h5 class="mt-0">You Win</h5>
                            <div class="border-top mb-1 mt-1"></div>
                            <div class="">
                                <i class="fa fa-gift" style="color:#FF5722; font-size:20px;"></i> <span style="font-size:18px;">Prize win ₹ <?=$row->amount?></span>
                                <div><small class="text-dark" style="font-size: 13px;">Tournament ID: #<?=$row->tournament?></small></div>
                            </div>
                          </div>
                        </div>
                  </div>
              </div>
            <?php } } else{?> 
                <div class="text-center">
                  <p style="font-size:20px;    color: #47474c;">No Results</p>
                  <div class="">
                      <i class="fa fa-trophy" style="font-size:200px;color: #eaeaea;"></i>
                  </div>
                  <div class="mt-5">
                      <a href="<?=base_url()?>api/game" class="btn btn-success btn-md" id="clickToload">VIEW LIVE MATCHES</a>
                  </div>
              </div>
            <?php } ?>
        </div>
      </div>
  </div>
</div>
</div>
