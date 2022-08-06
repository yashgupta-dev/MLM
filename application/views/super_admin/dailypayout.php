<div class="page-content">

    <div class="row">
        <div class="col-lg-12 col-xl-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                        <h6 class="card-title mb-0">All Daily Income </h6>
                        <div class="form-row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <select id="pageData" class="form-control" onchange="allDp()">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <input type="text" id="searchOffer" placeholder="search here..." onkeyup="allDp()" class="form-control" />
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <select id="sortBy" class="form-control" onchange="allDp()">
                                        <option value="">Sort By</option>
                                        <option value="asc">Ascending</option>
                                        <option value="desc">Descending</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <select id="rankWise" class="form-control" onchange="allDp()">
                                        <option value="">All Rank</option>
                                        <?php
                                            $data = $this->db->where('type','single')->select('rank')->get('single_leg')->result();
                                            foreach ($data as $row) {
                                             echo '<option value="'.$row->rank.'">'.$row->rank.'</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive" id="allpayout"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- row -->

</div>
