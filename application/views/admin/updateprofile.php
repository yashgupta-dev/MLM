<?php $st = $this->db->where('member_id',$this->session->userdata('username'))->select('status')->from('login')->get()->row('status');?>

<div class="page-content">
    <div class="profile-page tx-13">
        <div class="row">
            <div class="col-12 grid-margin">
			    <div class="profile-header">
				    <div class="cover">
					    <div class="gray-shade"></div>
						    <figure>
						 <?php $site = $this->db->select('logo')->from('site_setting')->get()->row();?>
							    <img src="<?=base_url()?>adminassets/site/<?=$site->logo?>" class="img-fluid" alt="profile cover">
							</figure>
							<div class="cover-body d-flex justify-content-between align-items-center">
							    <div><?php $site = $this->db->select('default_user')->from('site_setting')->get()->row();
                                  echo '<img class="profile-pic" src="'.base_url().'adminassets/images/faces/'.$site->default_user.'" alt="profile">'; ?>
                                        <span class="profile-name"><?=$this->session->userdata('name')?></span>
                                </div>
                            </div>
						</div>
						<div class="header-links bs-example">
							<ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">
                                        <i class="mr-1 icon-md" data-feather="user"></i> 
                                        My Profile
                                    </a>
                                </li>
                                
                                <li class="nav-item">
                                    <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">
                                        <i class="mr-1 icon-md" data-feather="file-minus"></i>
                                        Contact Info
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="nominee-tab" data-toggle="tab" href="#nominee" role="tab" aria-controls="nominee" aria-selected="false">
                                        <i class="mr-1 icon-md" data-feather="activity"></i>
                                        Nominee
                                    </a>
                                </li>
                            </ul>
						</div>
					</div>
            	</div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <div class="card">
                                <div class="card-body">
                                  <h3 class="card-title">My Profile</h3>
                                  <?php foreach($profile->result() as $row){?>
                                    <form class="form profile_form" <?php if($st == 1 || $st == 2){?>method="post" action="<?php echo base_url();?>profile-information-update"<?php }?>>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>User-id</label>
                                                <input type="text" id="user_id" <?php if($st == 0) echo ' readonly ';?> class="form-control" value="<?=$this->session->userdata('username')?>" readonly name="user_id">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Full name</label>
                                            <input type="text" id="name" <?php if($st == 0) echo ' readonly ';?> class="form-control" value="<?=$row->name?>" name="name">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Father/husband name</label>
                                                <input type="text" id="father_name" <?php if($st == 0) echo ' readonly ';?> class="form-control" value="<?=$row->father_name?>" name="father_name">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Date of Birth</label>
                                            <input type="date" id="dob" <?php if($st == 0) echo ' readonly ';?> class="form-control" value="<?=$row->dob?>" name="dob">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="hidden" value="<?php echo $this->security->get_csrf_hash();?>" name="<?php echo  $this->security->get_csrf_token_name();?>">
                                            <div class="form-group">
                                                <label>Gender</label>
                                                <select class="form-control" name="gender" <?php if($st == 0) echo ' readonly ';?>>
                                                    <option value="<?php if(!empty($row->gender)){echo $row->gender;}else{echo '';}?>"><?php if(!empty($row->gender)){echo $row->gender;}else{echo 'choose your gender';}?></option>
                                                    <option value="Male">Male</option>
                                                    <option value="Female">Female</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div><?php if($st == 1 || $st == 2){?>
                                    <div class="form-group">
                                        <button class="btn btn-primary" type="submit" name="submit" id="submit">Update Profile</button>
                                    </div><?php } ?>
                                </form> <?php } ?>
                            </div>
                        </div>
                    </div>
                  
            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">My Contact </h3>
                        <?php foreach($contact->result() as $data){ ?>
                        <form class="form account_form" <?php if($st == 1 || $st == 2){?>action="<?php echo base_url();?>contact-information-update" method="post"<?php } ?>>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Address</label>
                                    <input type="text" id="address" <?php if($st == 0) echo ' readonly ';?> class="form-control" value="<?=$data->address?>" name="address">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>City</label>
                                    <input type="text" id="City" <?php if($st == 0) echo ' readonly ';?> class="form-control" value="<?=$data->city?>" name="City">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>State</label>
                                <input type="text" id="State" <?php if($st == 0) echo ' readonly ';?> class="form-control" value="<?=$data->state?>" name="State">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>District</label>
                                    <input type="text" id="District" <?php if($st == 0) echo ' readonly ';?> class="form-control" value="<?=$data->district?>" name="District">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Pincode</label>
                                    <input type="text" id="postalcode" <?php if($st == 0) echo ' readonly ';?> class="form-control" value="<?=$data->postal?>" name="postalcode">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <input type="hidden" value="<?php echo $this->security->get_csrf_hash();?>" name="<?php echo  $this->security->get_csrf_token_name();?>">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" id="email" <?php if($st == 0) echo ' readonly ';?> class="form-control" value="<?=$data->email?>" name="email">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Mobile no.</label>
                                    <input type="text" id="phone" <?php if($st == 0) echo ' readonly ';?> class="form-control" value="<?=$data->phone?>" name="phone">
                                </div>
                            </div>
                        </div><?php if($st == 1 || $st == 2){?>
                        <div class="form-group">
                            <button class="btn btn-primary" type="submit" name="submit" id="submit">Update Contct</button>
                        </div><?php } ?>
                    </form><?php } ?>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="nominee" role="tabpanel" aria-labelledby="nominee-tab">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Nominee </h3>
                        <?php foreach($nomiee->result() as $n){?>
                        <form class="form account_form" <?php if($st == 1 || $st == 2){?>action="<?php echo base_url();?>nominee-update" method="post"<?php } ?>>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Nominee Name</label>
                                    <input type="text" id="nominee_name" <?php if($st == 0) echo ' readonly ';?> class="form-control" required value="<?=$n->nominee_name?>" name="nominee_name">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" value="<?php echo $this->security->get_csrf_hash();?>"  required name="<?php echo  $this->security->get_csrf_token_name();?>">
                                <div class="form-group">
                                    <label>Relation</label>
                                    <select class="form-control" required name="relation" id="relation" <?php if($st == 0) echo ' readonly ';?>>
                                        <option value="<?php if(!empty($n->nominee_reltion)){echo $n->nominee_reltion;}else{'select option';}?>"><?php if(!empty($n->nominee_reltion)){echo $n->nominee_reltion;}else{'select option';}?></option>
                                        <option value="None">None</option>
                                        <option value="Father">Father</option>
                                        <option value="Mother">Mother</option>
                                        <option value="Husband">Husband</option>
                                        <option value="Wife">Wife</option>
                                        <option value="Brother">Brother</option>
                                        <option value="Sister">Sister</option>
                                    </select>
                                </div>
                            </div>
                        </div><?php if($st == 1 || $st == 2){?>
                        <div class="form-group">
                            <button class="btn btn-primary" type="submit" name="submit" id="submit">Update Nominee</button>
                        </div><?php } ?>
                    </form><?php }?>
                </div>
            </div>   
        </div>
    </div>
</div>
</div>
        </div>
    </div>
        