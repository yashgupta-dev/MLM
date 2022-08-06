
				<div class="page-content">
				<div class="row">
					<div class="col-md-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <div class="row">
                    <div class="col-12 bg-secondary p-2 mb-4 text-white">
                        <h5>Direct sponser : <?=$this->session->userdata('username')?></h5>
                    </div>  
                    
                </div>
                <div class="table-responsive">
                    
                  <table id="dataTableExample" class="table">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Direct sponser</th>
                        <th>Name</th>
                        <th>Package Name</th>
                        <th>Joining Date</th>
                        <th>Activation Date</th>
                        <th>Side</th>
                        <th>Status</th>
                        
                      </tr>
                    </thead>
                    <tbody>
                       <?php $i=1; foreach($sp->result() as $row){ ?>
                            <tr>
                                <td><?=$i?></td>
                                <td><?=$row->member_id?></td>
                                <td><?=$row->name?></td>
                                <td><?=$row->package_name?>(Rs. <?=$row->package_amt?>-/)</td>
                                <td><?=$row->time?></td>
                                <td><?=$row->activation?></td>
                                <td><span class="badge badge-primary"><?=$row->side?></span></td>
                                <td><?php if($row->status == 1)
                                  {echo '<i data-feather="alert-circle" class="text-danger"></i> In-active';} 
                                  elseif($row->status == 2){
                                    echo '<i data-feather="alert-circle" class="text-danger"></i> De-active';
                                  }
                                  elseif($row->status == 3){
                                    echo '<i data-feather="alert-circle" class="text-danger"></i> BLOCKED';
                                  }
                                  elseif($row->status == 0){
                                    echo '<i data-feather="check-circle" class="text-success"></i> Active';
                                  }?>
                  
                                </td>
                            </tr>
                       
                       
                       <?php $i++; }?>
                      
                      
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
					</div>
				</div>

			</div>
