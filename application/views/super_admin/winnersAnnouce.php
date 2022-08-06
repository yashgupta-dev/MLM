<div class="page-content">
    <div class="row">
        <div class="col-lg-12 col-xl-12 stretch-card">
            <div class="card">
                <div class="card-body">
                <div class="d-flex justify-content-between align-items-baseline mb-2">
                  <h6 class="card-title mb-0">Winnners Annoucment</h6>
                </div>
                <div class="form-row">
                    <div class="col-md-3">
                      <div class="form-group">
                        <select id="gn" class="form-control" onchange="winners()">
                          <?php 
                            $data = $this->db->select('id,name')->get('game')->result();
                            foreach($data as $row){ ?>
                            <option value="<?=$row->id?>"><?=$row->name?></option>      
                            <?php } ?>
                        </select>        
                      </div>            
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <select id="wl" class="form-control" onchange="winners()">
                          <option value="Desc">Winner</option>      
                          <option value="Asc">Looser</option>      
                        </select>        
                      </div>            
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <select id="rank" class="form-control" onchange="winners()">
                          <?php
                            for($i = 1; $i<101; $i++){ 
                                echo '<option value="'.$i.'">'.$i.'</option>';
                            } ?>
                        </select>        
                      </div>            
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <button class="btn btn-primary" id="clickToAnnounce" style="border-radius:35px 35px 35px 35px; border:1px solid #000;"><i class="fab fa-speaker-deck"></i> Annouce</button>
                      </div>            
                    </div>
                  </div>
                <div class="table-responsive" id="getAlldataWinners">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tournament ID</th>
                                <th>Game ID</th>
                                <th>User ID</th>
                                <th>Score</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($re)){ $i=0; foreach($re as $row){ ++$i;?>
                            <tr>
                                <td><?=$i?></td>
                                <td><input type="text" readonly value="<?=$row->game_id?>" name="id[]" class="form-control" placeholder="Amount" style="border-radius:35px 35px 35px 35px; border:1px solid #000;"></td>
                                <td><?=$this->db->where('id',$row->gameRealId)->select('name')->get('game')->row('name')?></td>
                                <td><input type="text" readonly value="<?=$row->user?>" name="user[]" class="form-control" placeholder="Amount" style="border-radius:35px 35px 35px 35px; border:1px solid #000;"></td>
                                <td><span class="badge badge-success"><?=$row->score?></span></td>
                                <td><input type="text" name="priceUpdate[]" class="form-control" placeholder="Amount" style="border-radius:35px 35px 35px 35px; border:1px solid #000;"></td>
                            </tr>
                            <?php } } else{ ?>
                            <tr>
                                <td colspan="5" class="text-center">No data Found</td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
              </div> 
            </div>
          </div>
        </div> <!-- row -->

      </div>