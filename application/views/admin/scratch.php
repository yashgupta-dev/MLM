
      <div class="page-content">


        
        <div class="row">
          <div class="col-lg-12 col-xl-12 stretch-card">
            <div class="card">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-baseline mb-2">
                  <h6 class="card-title mb-0">All E-Pin </h6>
                </div>
                <div class="form-row">
                    <div class="col-md-2">
                      <div class="form-group">
                        <select id="pageData" class="form-control" onchange="scratchAjax()">
                          <option value="10">10</option>
                          <option value="25">25</option>
                          <option value="50">50</option>
                          <option value="100">100</option>
                        </select>        
                      </div>    
                    </div>
                    <div class="col-md-4">
                      <input type="text" id="searchOffer" placeholder="search here..." onkeyup="scratchAjax()" class="form-control"/>        
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <select id="sortBy" class="form-control" onchange="scratchAjax()">
                          <option value="">Sort By</option>
                          <option value="asc">Ascending</option>
                          <option value="desc">Descending</option>
                        </select>        
                      </div>            
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <select id="typeBy" class="form-control" onchange="scratchAjax()">
                          <option value="">Type By</option>
                          <option value="used">Used pin</option>
                          <option value="unused">Unused Pin</option>
                        </select>        
                      </div>            
                    </div>
                  </div>
                <div class="table-responsive" id="scarcthData"></div>
              </div> 
            </div>
          </div>
        </div> <!-- row -->

      </div>