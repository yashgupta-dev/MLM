<div class="page-content">

    <div class="row">
        <div class="col-lg-12 col-xl-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                        <h6 class="card-title mb-0">All User </h6>
                        
                    </div>
                    <div class="form-row">
                            <div class="col-md-1">
                                <div class="form-group">
                                    <select id="pageData" class="form-control" onchange="allUserPag()">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">500</option>
                                        <option value="100">100</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <input type="search" id="searchOffer" placeholder="search here..." onkeyup="allUserPag()" class="form-control" />
                            </div>

                            <div class="col-md-1">
                                <div class="form-group">
                                    <select id="sortBy" class="form-control" onchange="allUserPag()">
                                        <option value="">Sort By</option>
                                        <option value="asc">Old</option>
                                        <option value="desc">New</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <select id="rankWise" class="form-control" onchange="allUserPag()">
                                        <option value="">All Rank</option>
                                        <?php
                                            $data = $this->db->where('type','single')->select('rank,id')->get('single_leg')->result();
                                            foreach ($data as $row) {
                                             echo '<option value="'.$row->id.'">'.$row->rank.'</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <select id="datefilter" class="form-control" onchange="allUserPag()">
                                        <option value="">Date Filter</option>
                                        <?php $date = date('Y-m-d');?>
                                        <?php for($i = 0; $i <= 7; $i++){ ?>
                                        <option value="<?php echo date('Y-m-d', strtotime('-'.$i.' days'));?>"><?php echo date('Y-m-d', strtotime('-'.$i.' days'));?></option>
                                        <?php } ?>
                                        
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <div class="form-group">
                                    <select id="statusWise" class="form-control" onchange="allUserPag()">
                                        <option value="">Status Filter</option>
                                        <option value="0">Active</option>
                                        <option value="1">In-Active</option>
                                        <option value="2">De-active</option>
                                        <option value="3">Blocked</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <select id="pkgwise" class="form-control" onchange="allUserPag()">
                                        <option value="">Package Filter</option>
                                        <option value="1000">1000 Rs.</option>
                                        <option value="2000">2000 Rs.</option>
                                        <option value="3000">3000 Rs.</option>
                                        <option value="4000">4000 Rs.</option>
                                        <option value="5000">5000 Rs.</option>
                                        <option value="6000">6000 Rs.</option>
                                        <option value="7000">7000 Rs.</option>
                                        <option value="8000">8000 Rs.</option>
                                        <option value="9000">9000 Rs.</option>
                                        <option value="10000">10000 Rs.</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    <div class="table-responsive" id="allUserHtmlData"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- row -->

</div>
