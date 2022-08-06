                <div class="page-content">
                <div class="row">
                    <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <div class="row">
                    <div class="col-12 bg-secondary p-2 mb-4 text-white">
                        <h5>BANK ACCOUNTS DETAILS</h5>
                    </div>  
                    
                </div>
                <div class="row">
                  <div class="col-md-12">
                      <div class="d-flex jutify-content-between">
                         <div>

                            <div class="form-group">
                            <input class="form-control" type="search" id="beneInput" onkeyup="leftTableFunction()" placeholder="Search for Member id" title="Type in a name">
                            </div>
                         </div> 
                      </div>
                  </div>
                </div>
                <div class="table-responsive">
                    
                  <table id="LeftTable" class="table table-hover">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Member Id</th>
                        <th>Name</th>
                        <th>BeneID</th>
                        <th>Account No</th>
                        <th>Ifsc code</th>
                        <th>Setting</th>
                      </tr>
                    </thead>
                   <tbody>
                   <?php $accounts = $this->db->select('id,beneId,name,bankAccount,ifsc,address1,user,status,email,phone')->get('accounts')->result_array();?>
                   <?php $i=1; if(!empty($accounts)){ foreach($accounts as $row){?>
                      <tr>
                          <td><?=$i?></td>
                          <td><?=$row['user']?></td>
                          <td><?=$row['name']?></td>
                          <td><?=$row['beneId']?></td>
                          <td><?=$row['bankAccount']?></td>
                          <td><?=$row['ifsc']?></td>
                          <td>
                            <div class="row">
                              <div class="col-md-12 bg-light p-2">
                                  <div class="">
                                    
                                    <div class="">
                                      <a href="<?=base_url()?>admin/manage-account?q=<?=$row['beneId']?>" class="text-info">
                                        <i class="fa fa-cog"></i>
                                      </a>
                                    </div>
                                    
                                  </div>
                              </div>
                            </div>
                          </td>
                      </tr>
                   <?php $i++;} }else{?>

                   <?php } ?>
                  </tbody>
                  </table>
                </div>
              </div>
            </div>
                    </div>
                </div>

            </div>