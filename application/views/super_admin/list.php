<div class="page-content">

    <div class="row">
        <div class="col-lg-12 col-xl-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                        <h6 class="card-title mb-0">My All Tournaments Game</h6>
                        
                        <!--div class="dropdown mb-2">
                            <button class="btn p-0" type="button" id="dropdownMenuButton7" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton7">

                                <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="download" class="icon-sm mr-2"></i> <span class="">Download</span></a>
                            </div>
                        </div-->
                    </div>
                    <!--div class="form-row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <select id="pageData" class="form-control" onchange="allUserPag()">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <input type="text" id="searchOffer" placeholder="search here..." onkeyup="allUserPag()" class="form-control" />
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <select id="sortBy" class="form-control" onchange="allUserPag()">
                                        <option value="">Sort By</option>
                                        <option value="asc">Ascending</option>
                                        <option value="desc">Descending</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <select id="rankWise" class="form-control" onchange="allUserPag()">
                                        <option value="">All Rank</option>
                                        
                                    </select>
                                </div>
                            </div>

                        </div-->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Game Id</th>
                                    <th>Name</th>
                                    <th>Tournament Price</th>
                                    <th>Tournament End</th>
                                    <th>Status</th>
                                    <th>Action</th
                                    >
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($game)){ foreach($game as $row){?>
                                <tr>
                                    <td>#<?=$row->id?></td>
                                    <td><?=$row->name?></td>
                                    <td><?=$row->price?></td>
                                    <td><?=$row->end?></td>
                                    <td>
                                        <?php if($row->del == 0){
                                        echo '<span class="badge badge-danger">not active</span>';
                                        }elseif($row->del == 1){
                                            echo '<span class="badge badge-success">active</span>';
                                        }?>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-between">
                                            <div class="bg-dark p-1" style="border-radius:35px 35px 35px 35px;">
                                                <a href="javascript:;" id="clickToStartTournament" data-id="<?=$row->id?>">
                                                    <span class="text-white" style="vertical-align: middle;">start</span> 
                                                    <i class="fa fa-bullseye text-white"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php } } else{?>
                                <tr>
                                    <td colspn="6">No Tournament Found</td>        
                                </tr>
                                <?php }?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="mainACtiveShow">
            <div class="formActiveShow">
                <div class="offset-md-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="cancelToPopup"><i class="fa fa-times" style="vertical-align: sub;color: #fff;"></i></div>
                            <form action="<?=base_url()?>admin/startGame" method="post" class="startGameMatch">
                                <div class="form-group">
                                    <label>Game ID</label>
                                    <input type="text" readonly required placeholder="Game Id" name="game_id" id="game_id" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Tournament Entry Fees</label>
                                    <input type="text" required placeholder="entry fees" name="game_fees" id="game_fees" class="form-control" placeholder="0.00">
                                </div>
                                <div class="form-group">
                                    <label>Tournament Start</label>
                                    <div class="form-row">
                                        <div class="col-md-4">
                                            <input type="date" name="game_s_date" class="form-control" id="game_s_date" value="<?=date('Y-m-d')?>">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="time" name="game_s_time" class="form-control " id="game_s_time" value="<?=date('h:i')?>">
                                        </div>
                                        <div class="col-md-4">
                                            <select class="form-control" name="s_sec">
                                                <option><?=date('s')?></option>
                                                <?php for($i=1; $i<60;$i++){?>
                                                    <?php if($i<10 ){ ?>
                                                    <option value="0<?=$i?>">0<?=$i?></option>
                                                    <?php }else{?>
                                                    <option value="<?=$i?>"><?=$i?></option>
                                                <?php } } ?>
                                                    <option value="00">00</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Tournament End</label>
                                    <div class="form-row">
                                        <div class="col-md-4">
                                            <input type="date" name="game_date" class="form-control" id="game_date" value="<?=date('Y-m-d')?>">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="time" name="game_time" class="form-control" id="game_time">
                                        </div>
                                        <div class="col-md-4">
                                            <select class="form-control" name="sec">
                                                <option value="">select seconds</option>
                                                <?php for($i=1; $i<60;$i++){?>
                                                    <?php if($i<10 ){ ?>
                                                    <option value="0<?=$i?>">0<?=$i?></option>
                                                    <?php }else{?>
                                                    <option value="<?=$i?>"><?=$i?></option>
                                                <?php } } ?>
                                                    <option value="00">00</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group text-center">
                                    <input name="<?= $this->security->get_csrf_token_name()?>" value="<?= $this->security->get_csrf_hash(); ?>" type="hidden">
                                    <button class="btn btn-dark" style="border-radius:35px 35px 35px 35px;" type="button" id="clickToGo"><i class="fa fa-gamepad"></i> Tournament Start</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>    
        </div>
    </div>
    <!-- row -->

</div>

