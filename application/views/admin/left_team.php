                <div class="page-content">
                <div class="row">
                    <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <div class="row">
                    <div class="col-12 bg-secondary p-2 mb-4 text-white">
                        <h5>My Team : <?=$this->session->userdata('username')?></h5>
                    </div>  
                    
                </div>
                <div class="row">
                  <div class="col-md-12">
                      <div class="d-flex jutify-content-between">
                         <div>

                            <div class="form-group">
                            <input class="form-control" type="search" id="leftInput" onkeyup="leftTableFunction()" placeholder="Search for Member id" title="Type in a name">
                            </div>
                         </div> 
                      </div>
                  </div>
                </div>
                <div class="table-responsive">
                    
                  <table id="LeftTable" class="table">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Member Id</th>
                        <th>Name</th>
                        <th>Package Name</th>
                        <th>Joining Date</th>
                        <th>Activation Date</th>
                        <th>Status</th>
                        <th></th>
                      </tr>
                    </thead>
                   <tbody>
                    <?php $right = $this->db->where('parent',$this->session->userdata('usrf'))->where('teamside','Left')->select('*')->get('makebinary')->result(); ?>   
                      <?php  $i = 1;if(!empty($right)){ foreach($right as $row){?>
                        <tr>
                            <td><?=$i?></td>
                            <td><?=$row->user_id?></td>
                            <td><?=$this->db->where('member_id',$row->user_id)->select('name')->get('login')->row('name')?></td>
                            <td>joining (Rs. <?=$row->p_amt?>-/)</td>
                            <td><?=$this->db->where('member_id',$row->user_id)->select('time')->get('login')->row('time')?></td>
                            <td><?=$row->activation?></td>
     
                            <td>
                <?php if($row->status == 1)
                {echo '<i data-feather="alert-circle" class="text-danger"></i> In-active';} 
                elseif($row->status == 2){
                  echo '<i data-feather="alert-circle" class="text-danger"></i> De-active';
                }
                elseif($row->status == 3){
                  echo '<i data-feather="alert-circle" class="text-danger"></i> BLOCKED';
                }
                elseif($row->status == 0){
                  echo '<i data-feather="check-circle" class="text-success"></i> Active';
                }?></td>
                <td>
                    <?php if($row->status =='1'){
                        echo "<a href='".base_url()."member-activate?q=".$row->user_id."'><i class='fa fa-play-circle text-danger' style='font-size: 25px;'></i></a>";
                    }?>
                  </td>


                        </tr>
                      <?php $i++;} } ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
                    </div>
                </div>

            </div>