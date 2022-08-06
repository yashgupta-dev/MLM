
      <div class="page-content">
        <div class="row">
          <div class="col-lg-12 col-xl-12 stretch-card">
            <div class="card">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-baseline mb-2">
                  <h6 class="card-title mb-0">DATABASE MANAGE</h6>
                </div>
                <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                          <th>#</th>
                          <th>DB_Tables</th>
                          <th>Manage</th>
                        </tr>
                    </thead> 
                    <tbody>
                        <?php $table = $this->db->list_tables();?>
                        <?php if(!empty($table)){for($i=0; $i< count($table); $i++){ ?>
                        <tr>
                            <td><?=$i?></td>
                            <td><?=$table[$i]?></td>
                            <td>
                                <div class="row">
                                    <div class="d-flex justify-content-between">
                                        <div class="ml-2">
                                            <a href="<?=base_url()?>admin.manage/database/table?q=<?=$table[$i]?>"><i class="fas fa-file-export"></i> Download CSV</a>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php }} ?>
                        
                    </tbody>
                </table>
                </div>
                 
              </div> 
            </div>
          </div>
        </div> <!-- row -->

      </div>