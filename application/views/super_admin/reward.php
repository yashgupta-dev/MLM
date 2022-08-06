
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
                          <th>Status <br><span class="text-info">(1: is Active OR 0: is Inactive)</span></th>
                        </tr>
                    </thead> 
                    <tbody>
                        <?php if(!empty($reward)){
                            $i=0;
                            
                            foreach($reward as $row){ $i++;?>
                            <tr>
                                <td><?=$i?></td>
                                <td contenteditable="true" id="clickChange" data-id="<?=$row->point?>&<?=$row->id?>&point"><?=$row->point?></td>
                                <td contenteditable="true" id="clickChange" data-id="<?=$row->name?>&<?=$row->id?>&name"><?=$row->name?></td>
                                <td contenteditable="true" id="clickChange" data-id="<?=$row->opt?>&<?=$row->id?>&opt"><?=$row->opt?></td>
                                 
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