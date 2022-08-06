      <div class="page-content">
        <div class="row">
          <div class="col-lg-12 col-xl-12 stretch-card">
            <div class="card">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-baseline mb-2">
                  <h6 class="card-title mb-0">Rewards</h6>
                </div>
                <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                          <th>#</th>
                          <th>Points</th>
                          <th>Rewards</th>
                          <th>Status</th>
                        </tr>
                    </thead> 
                    <tbody>
                        <?php if(!empty($reward)){
                            $i=0;
                            foreach($reward as $row){ $i++;?>
                             
                             <tr>
                                 <td><?=$i?></td>
                                 <td><strong><?=$row->point?></strong></td>
                                 <td><strong><?=$row->name?></strong></td>
                                 <td><?php 
                                    $bv = $this->db->where('userid',$this->session->userdata('username'))->select('RightBV')->from('bv')->get()->row();
                                     $e = $bv->RightBV;
                                    if($row->point <= $e){
                                        echo '<span class="badge badge-success">Eligable</span>';
                                    }else{
                                        echo '<span class="badge badge-danger">Not Eligable</span>';
                                    }
                                 ?></td>
                             </tr>   
                        <?php    }
                        
                        }else{ ?>
                        <tr>
                            <td colspan="2">No Result Found!</td>
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