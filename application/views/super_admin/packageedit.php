
      <div class="page-content">
        <div class="row">
          <div class="col-lg-12 col-xl-12 stretch-card">
            <div class="card">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-baseline mb-2">
                  <h6 class="card-title mb-0">Package Edit</h6>
                  <h6 class="card-title mb-0">
                      <button class="btn btn-info" id="clickToAddNow"><i class="fa fa-plus"></i> <span style="vertical-align: middle;">ADD PACKAGE</span></button>
                  </h6>
                </div>
                <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                          <th>#</th>
                          <th>Name</th>
                          <th>Price</th>
                          <th>Status <br><span class="text-info"><small>(1: is Inactive OR 0: is active OR 2: is Admin )</small></span></th>
                          <th>Primary</th>
                          <th>Remove</th>
                        </tr>
                    </thead> 
                    <tbody id="appendRow">
                        <?php if(!empty($package)){
                            $i=0;
                            
                            foreach($package as $row){ $i++;?>
                            <tr id="remove<?=$i?>">
                                <td><?=$i?></td>
                                <td contenteditable="true" id="packageChange" data-id="<?=$row->id?>&name"><?=$row->name?></td>
                                <td contenteditable="true" id="packageChange" data-id="<?=$row->id?>&price">₹ <?=$row->price?></td>
                                <td contenteditable="true" id="packageChange" data-id="<?=$row->id?>&isActive"><?=$row->isActive?></td>
                                <td>
                                    
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" class="custom-control-input" <?php if($row->forAdmin == 1) echo 'checked'; ?> data-id="<?=$row->id?>&forAdmin" id="customRadio<?=$i?>" name="primary" value="1">
                                        <label class="custom-control-label" for="customRadio<?=$i?>">Primary</label>
                                    </div>
                                    
                                </td>
                                <td class="text-center" data-id="<?=$row->id?>">
                                    <a href="javascript:;" id="removedata" data-id="<?=$i?>" style="background: #ff3366;padding: 6px 7px;border-radius: 50px; color: #fff;"><i class="fa fa-minus" style="vertical-align: middle;"></i></a>
                                </td> 
                            </tr>   
                        <?php    }
                        
                        }else{ ?>
                        <tr>
                            <td colspan="2">No Result Found!</td>
                        </tr>
                        
                        <?php } ?>
                    </tbody>
                    <div id="div1"></div>
                </table>
                </div>
                 
              </div> 
            </div>
          </div>
        </div> <!-- row -->

      </div>