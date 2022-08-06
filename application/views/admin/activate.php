
      <div class="page-content">
        <div class="row">
          <div class="col-lg-12 col-xl-12 stretch-card">
            <div class="card">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-baseline mb-2">
                  <h6 class="card-title mb-0">Member Activate</h6>
                </div>
                <div class="row">
                    <div class="offset-md-3 col-md-6 mb-2">
                        <form class="form  bg-dark p-3 text-white" action="<?php echo base_url();?>activation" method="post" id="activationMember" style="border-radius:35px 35px 35px 35px;">
                            <div class="form-group">
                                <label>Enter Scratch</label>
                                <input type="text" class="form-control" required name="scratch" id="scratch" placeholder="scratch no.">
                                <input type="hidden" value="<?php echo $this->security->get_csrf_hash();?>" name="<?php echo  $this->security->get_csrf_token_name();?>">
                                <input type="hidden" value="<?=$_GET['q']?>" name="member_id">
                            </div>
                            <div class="form-group">
                            <label>Enter Pin</label>
                                <input type="text" class="form-control" required name="Pin" id="Pin" placeholder="Pin no.">
                            </div>
                            <div class="form-group">
                                <button type="submit" name="submit" class="btn btn-primary">Activate</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-2">
                      <div class="form-group">
                        <select id="pageData" class="form-control" onchange="scratchAjax()">
                          <option value="10">10</option>
                          <option value="25">25</option>
                          <option value="50">50</option>
                          <option value="100">100</option>
                        </select>        
                      </div>    
                    </div>
                    <div class="col-md-4">
                      <input type="text" id="searchOffer" placeholder="search here..." onkeyup="scratchAjax()" class="form-control"/>        
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <select id="sortBy" class="form-control" onchange="scratchAjax()">
                          <option value="">Sort By</option>
                          <option value="asc">Ascending</option>
                          <option value="desc">Descending</option>
                        </select>        
                      </div>            
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <select id="typeBy" class="form-control" onchange="scratchAjax()">
                          <option value="">Type By</option>
                          <option value="used">Used pin</option>
                          <option value="unused">Unused Pin</option>
                        </select>        
                      </div>            
                    </div>
                  </div>
                <div class="table-responsive" id="scarcthData"></div>
              </div> 
            </div>
          </div>
        </div> <!-- row -->

      </div>
<!-- Modal -->
<div class="modal fade close_scracth_model" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Scratch ID</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" id="scratch_id_user">
      <div class="modal-body">
        <div class="form-group">
            <select class="form-control" id="package_name">
                
                <?php 
                  $data = $this->db->where('isActive','0')->select('id,name,price')->from('package')->get()->result();
                  if(isset($data)){
                    echo '<option value="">Choose package</option>';
                  foreach ($data as $key) {?>
                      <option value="<?=$key->id?>"><?=$key->name?>(Rs. <?=$key->price?> /-)</option>
                  <?php }

                    }else{
                      echo '<option value="">No Packages here!</option>';
                    }

                 ?>
            </select>
        </div>
        <div class="form-group">
            <input type="text" class="form-control" maxlength="500" value='1' placeholder="how many id you want ?" id="number" name="number">
        </div>
        <div class="form-group">
            <input type="hidden" class="form-control" value="<?php echo $this->session->userdata('username');?>" id="user_id" name="user_id">
        </div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Payment</button>
      </div>
      </form>
    </div>
  </div>
</div>