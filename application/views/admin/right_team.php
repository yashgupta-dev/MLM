<div class="page-content">
    <div class="row">
        <div class="col-lg-12 col-xl-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                        <h6 class="card-title mb-0">My <?=$_GET['q']?> Team</h6>
                    </div>
                    <div class="form-row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <select id="pageData" class="form-control" onchange="teambtn()">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>        
                            </div>    
                        </div>
                        <div class="col-md-5">
                            <input type="text" id="searchOffer" placeholder="search ex:(status,member_id,package etc.) " onkeyup="teambtn()" class="form-control"/>        
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <select id="sortBy" class="form-control" onchange="teambtn()">
                                    <option value="">Sort By</option>
                                    <option value="desc">Newest</option>
                                    <option value="asc">Oldest</option>
                                </select>        
                            </div>            
                        </div>
                    </div>
                    <div class="table-responsive" id="myteamdata"></div>
                    </div> 
                </div>
            </div>
        </div> <!-- row -->
    </div>