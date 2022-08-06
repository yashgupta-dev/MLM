

				<div class="page-content">
				<div class="row">
					<div class="col-md-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                  <div class="row">
                    <div class="col-md-12">
                        
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 bg-secondary p-2 mb-4 text-white">
                        <h5>My Left Team : <?=$this->session->userdata('username')?></h5>
                    </div>  
                    
                </div>
                
                <div class="table-responsive">
                    
                  <table id="dataTableExample" class="table">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Member ID</th>
                        <th>Amount</th>
                        <th>Rank</th>
                        <th>Your Team</th>
                        <th>Per Day</th>
                        <th>Updated Date</th>
                      </tr>
                    </thead>
                    <tbody>
                       <?php $i=1; foreach($t->result() as $row)
                       {?>
                        <tr>
                            <td><?=$i?></td>
                            <td><?=$row->userid?></td>
                            <td>&#8377; <?=$row->amount?></td>
                            <td><span class="badge badge-success"><?=$row->rank?></span></td>
                            <td><?=$row->team?></td>
                            <td>&#8377; <?=$row->dm?></td>
                            <td><?=$row->day?></td>
                        </tr>

                       <?php $i++; } 
                       
                       ?>
                      
                      
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
					</div>
				</div>

			</div>
