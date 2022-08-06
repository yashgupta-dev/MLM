<div class="page-content">

    <div class="row">
        <div class="col-lg-12 col-xl-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                        <h6 class="card-title mb-0">All Scratch Request </h6>
                        <div class="form-row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <select id="pageData" class="form-control" onchange="scratchAddAjax()">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <input type="text" id="searchOffer" placeholder="search here..." onkeyup="scratchAddAjax()" class="form-control" />
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <select id="sortBy" class="form-control" onchange="scratchAddAjax()">
                                        <option value="">Sort By</option>
                                        <option value="asc">Ascending</option>
                                        <option value="desc">Descending</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="dropdown mb-2">
                            <button class="btn p-0" type="button" id="dropdownMenuButton7" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton7">

                                <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="download" class="icon-sm mr-2"></i> <span class="">Download</span></a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive" id="scarcthAddData"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- row -->

</div>