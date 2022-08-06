<style>
    section#footer-menu {
        border-top: 1px solid #282f3a;
        box-shadow: 1px 1px 12px #282f3a;
        background: #282f3a;
    }
    section#footer-menu a i{
        color:#fff;
        font-size: 20px;
    }
    section#footer-menu a small{
        color:#fff;
    }
    span.notificationArea {
    color: #fff;
    background: #ff3366;
    padding: 3px 5px;
    border-radius: 35px;
    position: absolute;
    bottom: 25px;
    font-size: 10px;
}

<?php $noti = $this->db->where('user',$this->session->userdata('gameuser'))->where('st','1')->from('notification')->get()->num_rows();?>
</style>
<div class="container-fluid">
<section id="footer-menu" style=" width:100%;" class="fixed-bottom">
        <div class="d-flex justify-content-between p-2">
            
                <div class="text-center ">
                    <a href="<?=base_url()?>api/game" class="gameMenuAnchor">
                        <i class="fas fa-tachometer-alt"></i>
                        <div class="">
                            <small class="text-white">HOME</small>
                        </div>
                    </a>
                </div>
                
                <div class="text-center ">
                    <a href="<?=base_url()?>api/my-matches" class="gameMenuAnchor">
                        <i class="fa fa-gamepad"></i>
                        <div class="">
                            <small class="text-white">MATCHES</small>
                        </div>
                    </a>
                </div>
                
                <div class="text-center ">
                    <a href="<?=base_url()?>api/notification" class="gameMenuAnchor">
                        <i class="fa fa-bell"></i>
                        <?php if(!empty($noti)){?>
                        <?php if($noti == 1 || $noti == 2 || $noti == 3 || $noti == 4 || $noti == 5 || $noti == 6 || $noti == 7 || $noti == 8 || $noti == 9){ ?>
                            <span class="notificationArea">0<?=$noti?></span>
                        <?php } else{ ?>
                                <span class="notificationArea"><?=$noti?></span>
                        <?php } ?>
                        
                        <?php } ?>
                        <div class="">
                            <small class="text-white">NOTIFICTION</small>
                        </div>
                    </a>
                </div>    
                
                <div class="text-center ">
                    <a href="<?=base_url()?>api/account-profile" class="gameMenuAnchor">
                        <i class="fa fa-user-circle"></i>
                        <div class="">
                            <small class="text-white">ACCOUNT</small>
                        </div>
                    </a>
                </div>
                
                <div class="text-center ">
                    <a href="<?=base_url()?>api/more-info" class="gameMenuAnchor">
                        <i class="fa fa-ellipsis-h"></i>
                        <div class="">
                            <small class="text-white">MORE</small>
                        </div>
                    </a>    
                </div>
            
        </div>
    </section>
</div>


<script src="<?=base_url()?>adminassets/vendors/core/core.js"></script>
<script src="<?=base_url()?>adminassets/vendors/feather-icons/feather.min.js"></script>
<script src="<?=base_url()?>adminassets/js/template.js"></script>
<script src="<?=base_url()?>adminassets/js/dashboard.js"></script>
<script src="<?=base_url()?>adminassets/js/toastr.min.js"></script>
<script src="<?=base_url()?>adminassets/js/jquery-3.4.1.js"></script>
<script src="<?=base_url()?>adminassets/js/dropzone.js"></script>
<script src="<?=base_url()?>adminassets/js/api.js"></script>
<script src="<?=base_url()?>adminassets/js/jquery-confirm.js"></script>
<script src="<?=base_url()?>adminassets/js/holder.min.js"></script>
	<!-- end custom js for this page -->
  <script type="text/javascript">
    $(function(){
      <?php echo $this->session->flashdata('acc');?>    
    });
    
        $(document).on('click','.addbtn',function(){
        $('.addbtn').html('<i class="fa fa-spin fa-spinner ml-2 mr-2" style="padding: 4px 14px;"></i>');
    });
    $(document).on('click','.withbtn',function(){
        $('.withbtn').html('<i class="fa fa-spin fa-spinner ml-2 mr-2" style="padding: 4px 14px;"></i>');
    });
    $(document).on('click','#clicktoLoad',function(){
        $(this).html('<i class="fa fa-spin fa-spinner" style="padding: 0px 50px;"></i>');
    });
    
    $('#procceedtoapy').on('click',function(){
    
    $('#procceedtoapy').html('please wait <i class="fa fa-spin fa-spinner"></i>');
    
});
  </script>

