<?php $id = $this->session->userdata('username');?>

				<div class="page-content">
				<div class="row">
					<div class="col-md-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <div class="row">
                    <div class="col-12 p-2 mb-4 text-dark">
                        <h5>Interest Rate:</h5>
                    </div>
                </div>
                <div class="row">
                  <div class="offset-md-3 col-md-6 bg-light text-dark p-5 paymentDesign">
                      <div>
                          <form class="form-widthrow formRate">
                            <div class="form-group" id="ruppesSymbol">
                              <label> Ineterest Rate</label>
                              <input type="text" name="rate" class="form-control widthAmt" placeholder="0%" value="<?=$this->db->select('interest')->get('admin')->row('interest')?>">
                            </div>
                            <div class="form-group" id="ruppesSymbol">
                              <label> Default Amount</label>
                              <input type="text" name="dm" class="form-control widthAmt" placeholder="0.00" value="<?=$this->db->select('default')->get('admin')->row('default')?>">
                            </div>
                            <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>">  
                            
                            </div>
                            <div class="form-group text-center">
                              <button class="btn btn-dark text-light" type="button" id="Change_rate" style="border-radius: 35px 35px 35px 35px">Save</button>
                            </div>
                          </form>
                      </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
				</div>
			</div>
    </div>
