<div class="page-content">
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0"><i class="fa fa-cog"></i> Site Setting</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-xl-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <!------------------------------------------------------------------------------ Tabs Links ------------------------------------------------------------------------------------>
                            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">

                                <a class="nav-link active" id="v-pills-Meta_Tags-tab" data-toggle="pill" href="#v-pills-Meta_Tags" role="tab" aria-controls="v-pills-Meta_Tags" aria-selected="false"><i class="fas fa-tags"></i> SEO</a>
                                <a class="nav-link" id="v-pills-messages-tab" data-toggle="pill" href="#v-pills-messages" role="tab" aria-controls="v-pills-messages" aria-selected="false"><i class="fab fa-internet-explorer"></i> Site</a>
                                <a class="nav-link" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-settings" role="tab" aria-controls="v-pills-settings" aria-selected="false"><i class="fas fa-money-check"></i> Cashfree Configration</a>
                                <a class="nav-link" id="v-pills-policy-tab" data-toggle="pill" href="#v-pills-policy" role="tab" aria-controls="v-pills-policy" aria-selected="false"><i class="fas fa-comment-alt"></i> Sms <strong class="text-warning">(Comming soon)</strong></a>
                                <a class="nav-link" id="v-pills-mail-tab" data-toggle="pill" href="#v-pills-mail" role="tab" aria-controls="v-pills-mail" aria-selected="false"><i class="fas fa-envelope-open"></i> Mail Configration</a>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <!------------------------------------------------------------------------------ Tabs Page ------------------------------------------------------------------------------------>
                            <div class="tab-content" id="v-pills-tabContent">

                                <!--- seo --->
                            <div class="tab-pane fade show active" id="v-pills-Meta_Tags" role="tabpanel" aria-labelledby="v-pills-Meta_Tags-tab">

                                    <form class="form" method="post" id="seoMetaTags_form">
                                        <div class="alert_show"></div>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text" id="btnGroupAddon">Author</div>
                                                </div>
                                                <input type="text" class="form-control" name="author" id="author" placeholder="Author name" aria-describedby="btnGroupAddon" maxlength="30">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text" id="btnGroupAddon">Meta Descrption</div>
                                                </div>
                                                <input type="text" class="form-control" name="m_desc" id="m_desc" placeholder="Meta Descrption" aria-describedby="btnGroupAddon" maxlength="120">
                                            </div>
                                        </div>

                                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text" id="btnGroupAddon">Keywords</div>
                                                </div>
                                                <input type="text" class="form-control" name="keywords" id="keywords" placeholder="example: (handloom, parlour,beauty)" aria-describedby="btnGroupAddon">
                                            </div>
                                            <small class="text-danger">please after completed text add sperator (,)</small>
                                        </div>
                                        <div class="form-group">
                                            <button class="btn btn-light text-dark" type="submit" id="text_chng_btn">Save</button>
                                        </div>
                                    </form>

                                </div>
                                <!--- seo end --->
                                <!--- Site --->
                                <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">
                                    <div class="alert_success"></div>
                                    <form class="from" method="post" id="title_upload">

                                        <div class="form-group">
                                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text text-dark" id="btnGroupAddon">Title</div>
                                                </div>
                                                <input type="text" required class="form-control" name="title" id="title" placeholder="site title" aria-describedby="btnGroupAddon" maxlength="100">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <button class="btn btn-light text-dark" type="submit" id="title_text_btn">Save</button>
                                        </div>
                                    </form>
                                    <form class="from" id="default_user">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text text-dark" id="btnGroupAddon">Default user</div>
                                                        </div>
                                                        <input type="file" required name="default" accept="image/png,image/jpeg,image/jpg" id="logo" aria-describedby="btnGroupAddon">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div id="default_user_img">
                                                    <p class="text-center text-muted">please wait..</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <button class="btn btn-light text-dark" type="submit" id="default_text_btn">Save</button>
                                        </div>
                                    </form>

                                    <form class="from" id="favicon_upload" method="post">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group">

                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text text-dark" id="btnGroupAddon">Favicon</div>
                                                        </div>
                                                        <input type="file" required name="favicon" accept="image/png" id="favicon" aria-describedby="btnGroupAddon">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div id="img_favicon">
                                                    <p class="text-center text-muted">please wait..</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <button class="btn btn-light text-dark" type="submit" id="favicon_text_btn">Save</button>
                                        </div>
                                    </form>

                                    <form class="from" id="logo_upload">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text text-dark" id="btnGroupAddon">logo</div>
                                                        </div>
                                                        <input type="file" required name="logo" accept="image/png" id="logo" aria-describedby="btnGroupAddon">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div id="img_logo">
                                                    <p class="text-center text-muted">please wait..</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <button class="btn btn-light text-dark" type="submit" id="logo_text_btn">Save</button>
                                        </div>
                                    </form>
                                    <form class="from" id="loaderUpload">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text text-dark" id="btnGroupAddon">Loader</div>
                                                        </div>
                                                        <input type="file" required name="loaderUpdate" accept="image/gif" id="loaderUpdate" aria-describedby="btnGroupAddon">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div id="loader_logo">
                                                    <p class="text-center text-muted">please wait..</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <button class="btn btn-light text-dark" type="submit" id="loader_text_btn">Save</button>
                                        </div>
                                    </form>
                                </div>
                                <!--- Site --->

                                <!--- terms & conditions --->
                                <div class="tab-pane fade" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                                    <?php $cash = $this->db->where('type','cashfree')->select('*')->get('credential')->row();?>
                                    <?php $pay = $this->db->where('type','payout')->select('*')->get('credential')->row();?>
                                    <form>
                                        <div class="form-row">
                                            <div class="col-md-12 mb-2">
                                                <div class="bg-dark p-2 text-white"><i class="fa fa-angle-down"></i><span class="ml-2">CASHFREE ACCOUNT DETAILS</span></div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><strong>App id</strong></label>
                                                    <input type="text" name="cappid" value="<?=$cash->appid?>" id="cappid" class="form-control" placeholder="paste your appid" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><strong>Seceret Key</strong></label>
                                                    <input type="text" name="cskey" id="cskey" value="<?=$cash->secretkey?>" class="form-control" placeholder="paste your seceret key" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><strong>Currency Type</strong></label>
                                                    <input type="text" name="ccurn" id="ccurn"  value="<?=$cash->currency?>" class="form-control" placeholder="ex: INR" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><strong>SELECT MODE</strong></label>
                                                    <select class="form-control" name="cmode" id="cmode" required>
                                                        <?php 
                                                            if($cash->mode == 'PROD'){
                                                                echo '<option value="PROD">PRODUCTION</option>';        
                                                            }elseif($cash->mode == 'TEST'){
                                                                echo '<option value="TEST">TEST</option>';        
                                                            }else{
                                                                echo '<option value="PROD">PRODUCTION</option>';        
                                                            }
                                                        ?>
                                                        
                                                        <?php 
                                                            if($cash->mode == 'PROD'){
                                                                echo '<option value="TEST">TEST</option>';        
                                                                      
                                                            }elseif($cash->mode == 'TEST'){
                                                                echo '<option value="PROD">PRODUCTION</option>';  
                                                            }else{
                                                                echo '<option value="TEST">TEST</option>';        
                                                            }
                                                        ?>
                                                        
                                                        
                                                    </select>
                                                </div>
                                            </div>

                                        </div><!--payout id-->
                                    </form>
                                    <form>
                                        <div class="form-row">
                                            <div class="col-md-12 mb-2">
                                                <div class="bg-dark p-2 text-white"><i class="fa fa-angle-down"></i><span class="ml-2">CASHFREE PAYOUT DETAILS</span></div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><strong>Client id</strong></label>
                                                    <input type="text" name="pappid" id="pappid" value="<?=$pay->appid?>" class="form-control" placeholder="paste your client id" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><strong>Client Seceret</strong></label>
                                                    <input type="text" name="pskey" id="pskey" value="<?=$pay->secretkey?>" class="form-control" placeholder="paste your client seceret" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><strong>SELECT MODE</strong></label>
                                                    <select class="form-control" name="pmode" id="pmode" required>
                                                        <?php 
                                                            if($pay->mode == 'PROD'){
                                                                echo '<option value="PROD">PRODUCTION</option>';        
                                                            }elseif($pay->mode == 'TEST'){
                                                                echo '<option value="TEST">TEST</option>';        
                                                            }
                                                            else{
                                                                echo '<option value="PROD">PRODUCTION</option>';  
                                                            }
                                                        ?>
                                                        
                                                        <?php 
                                                            if($pay->mode == 'PROD'){
                                                                echo '<option value="TEST">TEST</option>';        
                                                                      
                                                            }elseif($pay->mode == 'TEST'){
                                                                echo '<option value="PROD">PRODUCTION</option>';  
                                                            }else{
                                                                echo '<option value="TEST">TEST</option>';        
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <!--- terms & conditions --->
                                <div class="tab-pane fade" id="v-pills-mail" role="tabpanel" aria-labelledby="v-pills-mail-tab">
                                    <?php $mail = $this->db->where('id','1')->select('smtp_user,smpt_pass,smpt_port,smtp_host,protocol,website_name')->get('admin')->row();?>
                                    <form>
                                        <div class="form-row">
                                            <div class="col-md-12 mb-2">
                                                <div class="bg-dark p-2 text-white"><i class="fa fa-angle-down"></i><span class="ml-2">MAIL CONFIGRATION</span></div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label><strong>SMTP_USER</strong></label>
                                                    <input type="text" name="smtp_user" value="<?=$mail->smtp_user?>" id="smtp_user" class="form-control" placeholder="type your smtp mail" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label><strong>SMTP_HOST</strong></label>
                                                    <input type="text" name="host" id="host"  value="<?=$mail->smtp_host?>" class="form-control" placeholder="type your smtp host" required>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label><strong>SMTP_PORT</strong></label>
                                                    <input type="text" name="smtp_port" id="smtp_port" value="<?=$mail->smpt_port?>" class="form-control" placeholder="type smtp port" required>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label><strong>SMTP_HOST</strong></label>
                                                    <input type="text" name="smtp_host" id="smtp_host" value="<?=$mail->protocol?>" class="form-control" placeholder="type your host" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><strong>SMTP_PASSWORD</strong></label>
                                                    <input type="text" name="smtp_pass" id="smtp_pass"  value="<?php
                                                     echo substr($mail->smpt_pass,0,4).'*******';
                                                     ?>" class="form-control" placeholder="type your smtp password" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><strong>YOUR WEBSITE NAME</strong></label>
                                                    <input type="text" name="smtp_web" id="smtp_web"  value="<?=$mail->website_name?>" class="form-control" placeholder="your name will show on mail type here" required>
                                                </div>
                                            </div>
                                        </div>    
                                    </form>
                                </div>
                                <!--end-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- row -->
</div>
<script src="https://cdn.ckeditor.com/4.13.0/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('termsCondition');
    CKEDITOR.replace('privacyPolicy');
</script>