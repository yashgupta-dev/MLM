<!-- partial:partials/_footer.html -->
			<footer class="footer d-flex flex-column flex-md-row align-items-center justify-content-between">
				<p class="text-muted text-center text-md-left">Copyright © 2019  All rights reserved</p>
			</footer>
			<!-- partial -->
		
		</div>
	</div>

<script src="<?=base_url()?>adminassets/vendors/core/core.js"></script>
<script src="<?=base_url()?>adminassets/vendors/feather-icons/feather.min.js"></script>
<script src="<?=base_url()?>adminassets/js/template.js"></script>
<script src="<?=base_url()?>adminassets/js/dashboard.js"></script>
<script src="<?=base_url()?>adminassets/js/toastr.min.js"></script>
<script src="<?=base_url()?>adminassets/js/jquery-3.4.1.js"></script>
<script src="<?=base_url()?>adminassets/js/jquery-confirm.js"></script>
<script type="text/javascript">

    $(function(){
      <?php echo $this->session->flashdata('acc');?>    
    });
    
  </script>
  <script>
$(window).on('load', function() {
        setTimeout(function() {
            $(".preloader").delay(700).fadeOut(700).addClass('loaded');
        }, 800);
        $(this).scrollTop(0);
    });
    
$(document).on("click", '[data-toggle="lightbox"]', function(event) {
  event.preventDefault();
  $(this).ekkoLightbox();
});

 $(document).ready(function () {
     
    <?php if(current_url() == base_url().'admin/package-manage') { ?>
    $('input[type=radio][name=primary]').change(function() {
        
        var id = $(this).attr('data-id');
        $.ajax({
            url:'<?=base_url()?>admin/set_default_primary',
            method:'post',
            dataType:'json',
            data:{'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',id:id},
            success:function(data)
            {
                console.log(data);
                if(data.ok){
                    toastr.success(data.ok);
                    
                }
                if(data.er){
                    toastr.warning(data.er);
                    
                }
                
            },
            error: function (jqXhr, textStatus, errorMessage) { // error callback 
                toastr.error('' + errorMessage + '');
              
            } 
        });
        
        
    });
    
    var count = <?=$this->db->query('select count(id) as total from package')->row('total')?>;
    var last = $("tr td").last().attr('data-id');        
    $(document).on('click','#clickToAddNow',function(){
        last++;
        count++;
        //var last = $("tr td").last().attr('data-id');        
        
        var row = '<tr id="remove'+count+'"><td>'+count+'</td> <td contenteditable="true" id="packageChange" data-id="'+last+'&name">name here</td> <td contenteditable="true" id="packageChange" data-id="'+last+'&price">0.00</td> <td contenteditable="true" id="packageChange" data-id="'+last+'&isActive">1</td> <td class="text-center" data-id="'+last+'"><a href="javascript:;" id="removedata" data-id="'+count+'" style="background: #ff3366;padding: 6px 7px;border-radius: 50px; color: #fff;"><i class="fa fa-minus" style="vertical-align: middle;"></i></a></td>  </tr>';
        $("html, body").animate({ scrollTop: $(document).height() }, 1000);
        
        $.ajax({
            url:'<?=base_url()?>admin/add-row-new',
            method:'post',
            dataType:'json',
            data:{'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',count:count},
            success:function(data)
            {
                if(data.ok){
                    toastr.success(data.ok);
                    $('#appendRow').append(row); 
                    
                }
                if(data.er){
                    toastr.warning(data.er);
                    
                }
                
            },
            error: function (jqXhr, textStatus, errorMessage) { // error callback 
                toastr.error('' + errorMessage + '');
              
            } 
        });
    });
    
    $(document).on('click','#removedata',function(){
        var id = $(this).attr('data-id');
        $.ajax({
            url:'<?=base_url()?>admin/add-row-new-remove',
            method:'post',
            dataType:'json',
            data:{'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',id:id},
            success:function(data)
            {
                if(data.ok){
                    toastr.success(data.ok);
                    $('#remove'+id).remove(); 
                    
                }
                if(data.er){
                    toastr.warning(data.er);
                    
                }
                
            },
            error: function (jqXhr, textStatus, errorMessage) { // error callback 
                toastr.error('' + errorMessage + '');
              
            } 
        });
        
    });
    
    $(document).on('blur','#packageChange',function(){
        let text = $(this).html();
        let data = $(this).attr('data-id');
        
        $.ajax({
            url:'<?=base_url()?>admin/package_change-edit',
            method:'post',
            dataType:'json',
            data:{'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',text:text,data:data},
            success:function(data)
            {
                $(this).html(text);    
                
                if(data.ok){
                    toastr.success(data.ok);
                }
                if(data.er){
                    
                    toastr.warning(data.er);
                }
            },
            error: function (jqXhr, textStatus, errorMessage) { // error callback 
                toastr.error('' + errorMessage + '');
              
            } 
        });
    }); 
     <?php } ?>
     
     
    $(document).on('blur','#paymentControl',function(){
        let text = $(this).html();
        let data = $(this).attr('data-id');
        
        $.ajax({
            url:'<?=base_url()?>admin/payment-control-edit',
            method:'post',
            dataType:'json',
            data:{'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',text:text,data:data},
            success:function(data)
            {
                $(this).html(text);    
                
                if(data.ok){
                    toastr.success(data.ok);
                }
                if(data.er){
                    
                    toastr.warning(data.er);
                }
            },
            error: function (jqXhr, textStatus, errorMessage) { // error callback 
                toastr.error('' + errorMessage + '');
              
            } 
        });
    });
    $(document).on('blur','#clickChange',function(){
        let text = $(this).html();
        let data = $(this).attr('data-id');
        
        
        $.ajax({
            url:'<?=base_url()?>admin/reward-edit',
            method:'post',
            dataType:'json',
            data:{'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',text:text,data:data},
            success:function(data)
            {
                $(this).html(text);    
                if(data.ok){
                    toastr.success(data.ok);
                }
                if(data.er){
                    
                    toastr.warning(data.er);
                }
            },
            error: function (jqXhr, textStatus, errorMessage) { // error callback 
                toastr.error('' + errorMessage + '');
              
            } 
        });
    });
        $('input[name="urlHit"]').click(function(){
           window.location.assign('/admin/kyc.verify.all?'+$(this).val()); 
           //console.log();
        });
        $("#customCheckAll").click(function () {
            if($(this).prop("checked") == true){
                $("input[name='checkAllData[]']").prop('checked', true);
                $('#changeName').html('Deselect all');
            }else{
                $('#changeName').html('Select all');
                $("input[name='checkAllData[]']").prop('checked', false);
            }
            
        });
    });
    
$('#approveBtn').on('click',function(e){
    e.preventDefault();
    $('.preloader').css('display','block');
    let form = $('#kycDataAllVerify').serialize();
    
    $.ajax({
        url:$(this).attr('data-id'),
        method:'post',
        dataType:'json',
        data:form,
        success:function(data)
        {
            $('.preloader').css('display','none');
            
            if(data.ok)
            {
                
                $.confirm({
                    title: 'Congratulations!',
                    content: data.msg,
                    type: 'green',
                    typeAnimated: true,
                    buttons: {
                        tryAgain: {
                            text: 'Close',
                            btnClass: 'btn-green',
                            action: function(){
                                location.reload();
                            }
                        }
                    }
                });
            }
            if(data.er)
            {
                
                $.confirm({
                    title: data.title,
                    content: data.msg,
                    type: 'red',
                    typeAnimated: true,
                    buttons: {
                        tryAgain: {
                            text: 'Close',
                            btnClass: 'btn-red',
                            close: function(){}
                        }
                    }
                });
            }
            
        },
        error: function (jqXhr, textStatus, errorMessage) { // error callback 
            $('.preloader').css('display','none');
            toastr.error('' + errorMessage + '');
          
        } 
    });
});  


$('#rejectBtn').on('click',function(e){
    e.preventDefault();
    $('.preloader').css('display','block');
    let form = $('#kycDataAllVerify').serialize();
    
    $.ajax({
        url:$(this).attr('data-id'),
        method:'post',
        dataType:'json',
        data:form,
        success:function(data)
        {
            $('.preloader').css('display','none');
            
            if(data.ok)
            {
                
                $.confirm({
                    title: 'Congratulations!',
                    content: data.msg,
                    type: 'green',
                    typeAnimated: true,
                    buttons: {
                        tryAgain: {
                            text: 'Close',
                            btnClass: 'btn-green',
                            action: function(){
                                location.reload();
                            }
                        }
                    }
                });
            }
            if(data.er)
            {
                
                $.confirm({
                    title: data.title,
                    content: data.msg,
                    type: 'red',
                    typeAnimated: true,
                    buttons: {
                        tryAgain: {
                            text: 'Close',
                            btnClass: 'btn-red',
                            close: function(){}
                        }
                    }
                });
            }
            
        },
        error: function (jqXhr, textStatus, errorMessage) { // error callback 
            $('.preloader').css('display','none');
            toastr.error('' + errorMessage + '');
          
        } 
    });
});  

$('#debitCreditSave').on('click',function(e){
    e.preventDefault();
    if (navigator.onLine) {
    let form = $('#debitCreditForm').serialize();
    $.ajax({
        url:$('#debitCreditForm').attr('action'),
        method:'post',
        dataType:'json',
        data:form,
        beforeSend:function()
        {
            $('#debitCreditSave').addClass('disabled');
            $('#debitCreditSave').html('please wait..');   
            $('#userid').removeClass('is-invalid');
            $('#wallet').removeClass('is-invalid');
            $('#crDr').removeClass('is-invalid');
            $('#remarks').removeClass('is-invalid');
            $('#drOpt').removeClass('is-invalid');
            
        },
        success:function(data)
        {
            $('#debitCreditSave').removeClass('disabled');
            $('#debitCreditSave').html('Save');   
            
            if(data.a)
            {
                $('#userid').addClass('is-invalid');
            }
            if(data.b)
            {
                $('#wallet').addClass('is-invalid');
            }
            if(data.c)
            {
                $('#crDr').addClass('is-invalid');
            }
            if(data.d)
            {
                $('#remarks').addClass('is-invalid');
            }
            if(data.e){
                $('#drOpt').addClass('is-invalid');
            }

            if(data.ok)
            {
                
                $.confirm({
                    title: 'Congratulations!',
                    content: data.msg,
                    type: 'green',
                    typeAnimated: true,
                    buttons: {
                        tryAgain: {
                            text: 'Thank you',
                            btnClass: 'btn-green',
                            action: function(){
                                window.top.close();    
                            }
                        }
                    }
                });
            }
            if(data.er)
            {
                
                $.confirm({
                    title: data.title,
                    content: data.msg,
                    type: 'red',
                    typeAnimated: true,
                    buttons: {
                        tryAgain: {
                            text: 'Okay',
                            btnClass: 'btn-red',
                            close: function(){
                                
                            }
                        }
                    }
                });
            }
            
        },
        error: function (jqXhr, textStatus, errorMessage) { // error callback 
            $('#debitCreditSave').removeClass('disabled');
            $('#debitCreditSave').html('Save');   
            toastr.error('' + errorMessage + '');
          
        } 
    });
    } else {
            $.confirm({
                title: 'Connection Interption',
                content: 'You have no internet!',
                type: 'red',
                typeAnimated: true,
                buttons: {
                    tryAgain: {
                        text: 'Close',
                        btnClass: 'btn-red',
                        close: function() {}
                    }
                }
            });
        }
});          



$('#edituserbtn').on('click',function(e){
    e.preventDefault();
    if (navigator.onLine) {
    let form = $('#usereditform').serialize();
    $.ajax({
        url:$('#usereditform').attr('action'),
        method:'post',
        dataType:'json',
        data:form,
        beforeSend:function()
        {
            $('#edituserbtn').addClass('disabled');
            $('#edituserbtn').html('please wait..');   
            $('#userid').removeClass('is-invalid');
            $('#name').removeClass('is-invalid');
            $('#email').removeClass('is-invalid');
            $('#phone').removeClass('is-invalid');
            
        },
        success:function(data)
        {
            $('#edituserbtn').removeClass('disabled');
            $('#edituserbtn').html('Save');   
            
            if(data.a)
            {
                $('#name').addClass('is-invalid');
            }
            if(data.b)
            {
                $('#email').addClass('is-invalid');
            }
            if(data.c)
            {
                $('#phone').addClass('is-invalid');
            }

            if(data.ok)
            {
                
                $.confirm({
                    title: 'Congratulations!',
                    content: data.msg,
                    type: 'green',
                    typeAnimated: true,
                    buttons: {
                        tryAgain: {
                            text: 'Thank you',
                            btnClass: 'btn-green',
                            action: function(){
                                window.top.close();    
                            }
                        }
                    }
                });
            }
            
        },
        error: function (jqXhr, textStatus, errorMessage) { // error callback 
            $('#edituserbtn').removeClass('disabled');
            $('#edituserbtn').html('Save');   
            toastr.error('' + errorMessage + '');
          
        } 
    });
    } else {
            $.confirm({
                title: 'Connection Interption',
                content: 'You have no internet!',
                type: 'red',
                typeAnimated: true,
                buttons: {
                    tryAgain: {
                        text: 'Close',
                        btnClass: 'btn-red',
                        close: function() {}
                    }
                }
            });
        }
});          

    
$('#pkgupgradeBTb').on('click',function(e){
    e.preventDefault();
    if (navigator.onLine) {
    let form = $('#pckagechange').serialize();
    $.ajax({
        url:$('#pckagechange').attr('action'),
        method:'post',
        dataType:'json',
        data:form,
        beforeSend:function()
        {
            $('#pkgupgradeBTb').addClass('disabled');
            $('#pkgupgradeBTb').html('please wait..');   
            $('#userid').removeClass('is-invalid');
            $('#pkg').removeClass('is-invalid');
            $('#upg').removeClass('is-invalid');
            $('#acu').removeClass('is-invalid');
            
        },
        success:function(data)
        {
            $('#pkgupgradeBTb').removeClass('disabled');
            $('#pkgupgradeBTb').html('Sign in');   
            
            if(data.pkg)
            {
                $('#pkg').addClass('is-invalid');
            }
            if(data.acu){
                $('#acu').addClass('is-invalid');    
            }
            
            if(data.userid)
            {
                $('#userid').addClass('is-invalid');
            }
            if(data.upg)
            {
                $('#upg').addClass('is-invalid');
            }

            if(data.ok)
            {
                
                $.confirm({
                    title: 'Congratulations!',
                    content: data.msg,
                    type: 'green',
                    typeAnimated: true,
                    buttons: {
                        tryAgain: {
                            text: 'Thank you',
                            btnClass: 'btn-green',
                            action: function(){
                                window.top.close();    
                            }
                        }
                    }
                });
            }
            
        },
        error: function (jqXhr, textStatus, errorMessage) { // error callback 
            $('#pkgupgradeBTb').removeClass('disabled');
            $('#pkgupgradeBTb').html('Sign in');   
            toastr.error('' + errorMessage + '');
          
        } 
    });
    } else {
            $.confirm({
                title: 'Connection Interption',
                content: 'You have no internet!',
                type: 'red',
                typeAnimated: true,
                buttons: {
                    tryAgain: {
                        text: 'Close',
                        btnClass: 'btn-red',
                        close: function() {}
                    }
                }
            });
        }
});          

$('#sign_up_btn').on('click',function(e){
    e.preventDefault();
    if (navigator.onLine) {
    let form = $('.signup_form').serialize();
    $.ajax({
        url:'/bulk-create-user',
        method:'post',
        dataType:'json',
        data:form,
        beforeSend:function()
        {
            $('#sign_up_btn').addClass('disabled');
            $('#sign_up_btn').html('please wait..');   
            $('#sponser').removeClass('is-invalid');
            $('#side').removeClass('is-invalid');
            $('#exampleInputname1').removeClass('is-invalid');
            $('#bulk').removeClass('is-invalid');
            $('#exampleInputEmail2').removeClass('is-invalid');
            $('#exampleInputPhone1').removeClass('is-invalid');
            $('#exampleInputPassword2').removeClass('is-invalid');
            $('#cnf').removeClass('is-invalid');
            $('#side').removeClass('is-invalid');
            $('.errorterms').html('');
            
        },
        success:function(data)
        {
            $('#sign_up_btn').removeClass('disabled');
            $('#sign_up_btn').html('Sign in');   
            
            if(data.sponser)
            {
                $('#sponser').addClass('is-invalid');
                toastr.error('' + data.sponser + '');
            }
            if(data.name)
            {
                $('#exampleInputname1').addClass('is-invalid');
                toastr.error('' + data.name + '');
            }
            if(data.email)
            {
                $('#exampleInputEmail2').addClass('is-invalid');
                toastr.error('' + data.email + '');
            }
            if(data.phone)
            {
                $('#exampleInputPhone1').addClass('is-invalid');
                toastr.error('' + data.phone + '');
            }
            if(data.term)
            {
                $('.errorterms').html('accept the terms & conditions.');
                //toastr.error('' + data.term + '');
            }
            if(data.cnf)
            {
                $('#cnf').addClass('is-invalid');
                toastr.error('' + data.cnf + '');
            }
            if(data.side)
            {
                $('#side').addClass('is-invalid');
                toastr.error('' + data.side + '');
            }
            if(data.pass)
            {
                $('#exampleInputPassword2').addClass('is-invalid');
                toastr.error('' + data.pass + '');
            }

            if(data.success)
            {
                
                $.confirm({
                    title: 'Congratulations!',
                    content: 'Your username is: '+data.su+'<br> Your password is: '+data.sp+'<br> Thank you user!',
                    type: 'green',
                    typeAnimated: true,
                    buttons: {
                        tryAgain: {
                            text: 'Thank you',
                            btnClass: 'btn-green',
                            action: function(){
                                $.alert({
                                    title: 'Do not forget to copy',
                                    content: 'Your username is: '+data.su+'&nbsp;&nbsp; <a href="javascript:;" data-id="'+data.su+'" id="clickTocopy"><i data-feather="copy"> <span class="text-dark    ">copy<span></i></a><br> Your password is: '+data.sp+'<br> Thank you user!',
                                });

                            }
                        },
                        close: function () {
                            $.alert({
                                title: 'Do not forget to copy',
                                content: 'Your username is: '+data.su+'&nbsp;&nbsp; <a href="javascript:;" data-id="'+data.su+'" id="clickTocopy"><i data-feather="copy"> <span class="text-dark    ">copy<span></i></a><br> Your password is: '+data.sp+'<br> Thank you user!',
                            });
                        }
                    }
                });
                $('.signup_form')[0].reset();
                toastr.success('' + data.success + '');

                    

            }
            if(data.DB)
            {
                
                toastr.warning('' + data.DB + '');

            }
            if(data.errorWRONG)
            {
                
                toastr.error('' + data.errorWRONG + '');

            }
            
        },
        error: function (jqXhr, textStatus, errorMessage) { // error callback 
            $('#sign_up_btn').removeClass('disabled');
            $('#sign_up_btn').html('Sign in');   
            toastr.error('' + errorMessage + '');
          
        } 
    });
    } else {
            $.confirm({
                title: 'Connection Interption',
                content: 'You have no internet!',
                type: 'red',
                typeAnimated: true,
                buttons: {
                    tryAgain: {
                        text: 'Close',
                        btnClass: 'btn-red',
                        close: function() {}
                    }
                }
            });
        }

});      
$(document).ready(function(){
                 $('#withdrawOnOff').change(function(){
            document.onOffform.submit();
        });


        $('#sponser').keyup(function(){
            
            let token = $("meta[name='csrf_test_name']").attr("content");
            $.ajax({
                url:'/ftech_username',
                method:'post',
                dataType:'json',
                data:{'csrf_test_name':token,id:$(this).val()},
                beforeSend:function()
                {
                    $('#pleaseW').html('please wait <i class="fa fa-spin fa-spinner" style="font-size:9px;"></i>');
                },
                success:function(data){
                    
                    if(data.n){
                        $('#pleaseW').html(data.n);    
                    }else{
                        $('#pleaseW').html('Invalid Sponser Id');    
                    }
                    
                }
            });
          
         });
    });    
    
function winners(){
    var rank = $('#rank').val();
    var wl = $('#wl').val();
    var gn = $('#gn').val();
        
    $.ajax({
        type: 'POST',
        url:'<?=base_url()?>admin/winners_secondList',
        dataType:'json',
        data:{'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',rank:rank,wl:wl,gn:gn},
        cache:false,
        beforeSend: function () {
            $('#getAlldataWinners').html('<div class="container"><div class="row"><div class="col-md-12 text-center"><i class="fa fa-spin fa-spinner"></i></div></div></div>');
        },
        success: function (html) {
            $('#getAlldataWinners').html(html);
        }
    });
        
    }
    $(document).on('click','#clickToAnnounce',function(e){
        e.preventDefault();
         var variant_attribute = [];
            for (var i = 0; i < $('input[name="priceUpdate[]"]').length; i++) {
                variant_attribute.push($('input[name="priceUpdate[]"]:nth("' + i + '")').val());
            }
            var user = [];
            for (var i = 0; i < $('input[name="user[]"]').length; i++) {
                user.push($('input[name="user[]"]:nth("' + i + '")').val());
            }
            
            var id = [];
            for (var i = 0; i < $('input[name="id[]"]').length; i++) {
                id.push($('input[name="id[]"]:nth("' + i + '")').val());
            }
            
        $.ajax({
        type: 'POST',
        url:'<?=base_url()?>admin/SendTo_Winners',
        dataType:'json',
        data:{'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',variant_attribute:variant_attribute,user:user,id:id},
        cache:false,
        beforeSend:function(){
          $('#clickToAnnounce').html('please wait <i class="fa fa-spin fa-spinner"></i>');  
        },
        success: function (html) {
            $('#clickToAnnounce').html('<i class="fab fa-speaker-deck"></i> Annouce');  
            console.log(html);
            if(html.ok){
                $.confirm({
                      title: 'Winners Announced',
                      content: html.ok,
                      icon: 'fa fa-question',
                      animation: 'scale',
                      closeAnimation: 'scale',
                      type: 'blue',
                      buttons: {   
                          ok: {
                              text: "close",
                              btnClass: 'btn-blue',
                              action: function(){
                                  location.reload();
                              }
                          }
                      }
                    });
            }
        }
    });    
    });
$(function(){
    
    
    $(document).on('click','#clickToStartTournament',function(){
       let id =$(this).attr('data-id');
       $('#game_id').val(id);
       $('.mainACtiveShow').show('200').css('display','block');
    });
    $(document).on('click','#clickToGo',function(e){
       e.preventDefault();
       let form = $('.startGameMatch').serialize();
        $.ajax({
            url:$('.startGameMatch').attr('action'),
            method:"post",
            data:form,
            dataType:'json',
            beforeSend: function() {
                $('#clickToGo').html('please wait <i class="fa fa-spin fa-spinner"></i>');
            },
            success:function(data)
            { 
                $('#clickToGo').html('<i class="fa fa-gamepad"></i> Tournament Start');
                
                
                if(data.ok){
                    $.confirm({
                      title: 'Tournament Started',
                      content: data.ok,
                      icon: 'fa fa-question',
                      animation: 'scale',
                      closeAnimation: 'scale',
                      type: 'blue',
                      buttons: {   
                          ok: {
                              text: "close",
                              btnClass: 'btn-blue',
                              action: function(){
                                  location.reload();
                              }
                          }
                      }
                    });
                }
                if(data.er){
                    $.confirm({
                      title: 'Db error',
                      content: data.er,
                      icon: 'fa fa-question',
                      animation: 'scale',
                      closeAnimation: 'scale',
                      type: 'red',
                      buttons: {   
                          ok: {
                              text: "close",
                              btnClass: 'btn-red',
                              close: function(){}
                          }
                      }
                    });
                }
                if(data.a){
                    $('#game_id').addClass('is-invalid');
                }
                if(data.b){
                    $('#game_fees').addClass('is-invalid');
                }
                if(data.c){
                    $('#game_s_date').addClass('is-invalid');
                }
                if(data.d){
                    $('#game_s_time').addClass('is-invalid');
                }
                if(data.e){
                    $('#s_sec').addClass('is-invalid');
                }
                if(data.f){
                    $('#game_date').addClass('is-invalid');
                }
                if(data.g){
                    $('#game_time').addClass('is-invalid');
                }
                if(data.h){
                    $('#sec').addClass('is-invalid');
                }
                
                
                
            },
            error: function (jqXhr, textStatus, errorMessage) { // error callback 
                $('#clickToGo').html('<i class="fa fa-gamepad"></i> Tournament Start');
                toastr.error('' + errorMessage + '');
            } 
        });
    });
    $(document).on('click','#activeNow',function(){
        $(this).html('<i class="fa fa-spin fa-spinner"></i>');
       let id = $(this).attr('data-id'); 
       $.ajax({
            url:'<?=base_url()?>admin/activePage',
            method:"post",
            data:{'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',id:id},
            dataType:'json',
            
            success:function(data)
            { 
                if(data.er){
                    toastr.danger(data.er);  
                }
                if(data.ok){
                    toastr.success(data.ok);  
                    alluserpagme();
                }
            }
       });
    });

    $(document).on('click','#deactiveNow',function(){
        $(this).html('<i class="fa fa-spin fa-spinner"></i>');
       let id = $(this).attr('data-id'); 
       $.ajax({
            url:'<?=base_url()?>admin/deactivePage',
            method:"post",
            data:{'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',id:id},
            dataType:'json',
            
            success:function(data)
            { 
                if(data.er){
                    toastr.danger(data.er);  
                }
                if(data.ok){
                    toastr.success(data.ok);  
                    alluserpagme();
                }
            }
       });
    });

    $(document).on('click','#block',function(){
        $(this).html('<i class="fa fa-spin fa-spinner"></i>');
       let id = $(this).attr('data-id'); 
       $.ajax({
            url:'<?=base_url()?>admin/blockUser',
            method:"post",
            data:{'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',id:id},
            dataType:'json',
            
            success:function(data)
            { 
                if(data.er){
                    toastr.danger(data.er);  
                }
                if(data.ok){
                    toastr.success(data.ok);  
                    alluserpagme();
                }
            }
       });
    });

    $(document).on('click','#unblock',function(){
      $(this).html('<i class="fa fa-spin fa-spinner"></i>');
       let id = $(this).attr('data-id'); 
       $.ajax({
            url:'<?=base_url()?>admin/unblockpage',
            method:"post",
            data:{'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',id:id},
            dataType:'json',
            
            success:function(data)
            { 
                if(data.er){
                    toastr.danger(data.er);  
                }
                if(data.ok){
                    toastr.success(data.ok);  
                    alluserpagme();
                }
            }
       });
    });

    $(document).on('click','.cancelToPopup',function(){
        $('.mainACtiveShow').hide('200').css('display','none');
    });
   
    $(document).on('change','#clickChange',function(){
        $('#clickChange').keypress(function (e) {
          if (e.which == 13) {
            // alert($(this).attr('data-id')); 
            
          }else{
              return false;    //<---- Add this line
          }
        });
    });
$(document).on('click','#reject_id_scratch',function(e){
            e.preventDefault();
            var id = $(this).attr('data-id');
            var txn = $(this).attr('data-txn');
            
            $.confirm({
                  title: 'What is up?',
                  content: 'Do you reject this Ids',
                  icon: 'fa fa-question',
                  animation: 'scale',
                  closeAnimation: 'scale',
                  type: 'green',
                  buttons: {   
                      ok: {
                          text: "ok!",
                          btnClass: 'btn-primary',
                          keys: ['enter'],
                          action: function(){
                              $.ajax({
                                 url:"<?=base_url()?>reject_id",
                                 method:"post",
                                 data:{'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',id:id,txn:txn},
                                 dataType:'json',
                                 beforeSend: function() {
                                     $('#loderAjax').removeClass('d-none');
                                 },
                                 success:function(data)
                                 { 
                                     $('#loderAjax').addClass('d-none'); 
                                      if(data.ok){
                                          toastr.success(data.ok);  
                                          scarcthFunctionrequest();
                                      }
                                      if(data.er){
                                        toastr.error(data.er);
                                      
                                      }

                                 },
                                  error: function (jqXhr, textStatus, errorMessage) { // error callback 
                                      toastr.error('' + errorMessage + '');
                                    
                                  } 
                              });
                          }
                      },
                      cancel: function(){
                              console.log('the user clicked cancel');
                      }
                  }
              });
          });       
$(document).on('click','#accept_id_scratch',function(e){
            e.preventDefault();
            var id = $(this).attr('data-id');
            $.confirm({
                  title: 'What is up?',
                  content: 'Do you accept this Ids',
                  icon: 'fa fa-question',
                  animation: 'scale',
                  closeAnimation: 'scale',
                  type: 'green',
                  buttons: {   
                      ok: {
                          text: "ok!",
                          btnClass: 'btn-primary',
                          keys: ['enter'],
                          action: function(){
                              $.ajax({
                                 url:"<?=base_url()?>accept_id",
                                 method:"post",
                                 data:{id:id},
                                 beforeSend: function() {
                                     $('#loderAjax').removeClass('d-none');
                                 },
                                 success:function(data)
                                 { 
                                     $('#loderAjax').addClass('d-none'); 
                                      if(data == 1)
                                      {
                                          
                                          scarcthFunctionrequest();
                                      }
                                      else if(data == 2)
                                      {
                                        toastr.error('we can not add record');
                                      
                                      }
                                      else if(data == 12)
                                      {
                                        toastr.error('we can not fetch request id');
                                        
                                      }
                                      else{
                                        toastr.error('Soory! we can not accept your request.');
                                        
                                      }
                                 },
                                  error: function (jqXhr, textStatus, errorMessage) { // error callback 
                                      toastr.error('' + errorMessage + '');
                                    
                                  } 
                              });
                          }
                      },
                      cancel: function(){
                              console.log('the user clicked cancel');
                      }
                  }
              });
          });
});

function csrfdata() {
        let token = $("meta[name='csrf_test_name']").attr("content");
        return token;
    }

$(function(){
  notification();
});


$(document).on('click','#Change_rate',function(){
     $.ajax({
        url:'<?=base_url()?>ratechange',
        method:"post",
        cache:false,
        dataType:'json',
        data:$('.formRate').serialize(),
        beforeSend:function(){
             $('#Change_rate').addClass('disabled');
             $('#Change_rate').html('<i class="fa fa-spin fa-spinner"></i>');
             
        },
        success:function(data)
        {  
                
                $.confirm({
                    title: 'Interest Rate',
                    content:'Changed saved successfull',
                    type: 'green',
                    typeAnimated: true,
                    buttons: {
                        tryAgain: {
                            text: 'Close',
                            btnClass: 'btn-green',
                            close: function() {}
                        }
                    }
                });
            
             $('#Change_rate').removeClass('disabled');
             $('#Change_rate').html('Change_rate');
            
        },
        error: function (jqXhr, textStatus, errorMessage) { // error callback 
            toastr.error('' + errorMessage + '');
          
        }
     });           
});

$(document).on('change','#kycerror',function(){
    let val = $(this).val();
    let id = $(this).attr('data-id');
    let m = $(this).attr('data-m');
    let token = csrfdata();
    $.ajax({
        url:'<?=base_url()?>savedata',
        method:'post',
        //dataType:'json',  
        data:{'csrf_test_name':token,id:id,val:val,m:m},
        success:function(data)
        {
          
            $.confirm({
                    title: 'KYC',
                    content: data,
                    type: 'blue',
                    typeAnimated: true,
                    buttons: {
                        tryAgain: {
                            text: 'Done',
                            btnClass: 'btn-blue',
                            close: function() {}
                        }
                    }
                });
        },
        error: function (jqXhr, textStatus, errorMessage) { // error callback 
            toastr.error('' + errorMessage + '');
          
        }
    });
});
$(document).on('click','#verifydocs',function(){
    let id = $(this).attr('data-id');
    let token = csrfdata();
    $.ajax({
        url:'<?=base_url()?>verfiyDocs',
        method:'post',
        dataType:'json',  
        data:{'csrf_test_name':token,id:id},
        beforeSend:function(){
          $('#kycUser').html('<div class="row"><div class="col-md-12 text-center p-5"><i class="fa fa-spin fa-spinner"></i></div></div>');
        },
        success:function(data)
        {
            $('#kycUser').html(data);
        },
        error: function (jqXhr, textStatus, errorMessage) { // error callback 
            toastr.error('' + errorMessage + '');
          
        }
    });

});
function notification()
{
     $.ajax({
       url:'<?=base_url()?>notification',
       method:'post',
       data:{'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>'},
       dataType:'json',
       success:function(data)
       {  
          /*if(data.binary_income){
            $('#binary_income').html(data.binary_income);
          }*/
          if(data.lossprofit){
              $('#lossprofit').html(data.lossprofit);
              
          }
          
          if(data.total_member){
            $('#total_member').html(data.total_member);
          }
          if(data.blocked){
              $('#blocked').html(data.blocked);
          }
          
          if(data.deactivemember){
              $('#deactivemember').html(data.deactivemember);
          }
          if(data.rightBusinessToday){
            $('#rightBusinessToday').html(data.rightBusinessToday);
          }
          
          
          if(data.binary_incomeSecond){
            $('#binary_incomeSecond').html(data.binary_incomeSecond);
          }
          if(data.PayableBalance){
            $('#PayableBalance').html(data.PayableBalance);
          }
          
          if(data.withdrawBalance){
            $('#withdrawBalance').html(data.withdrawBalance);
          }
          if(data.joiningUpgrade){
            $('#joiningUpgrade').html(data.joiningUpgrade);
          }
          
          if(data.wallet){
            $('#wallet').html(data.wallet);
          }
          if(data.direct_income)
          {
            $('#direct_income').html(data.direct_income);
          } 
          if(data.users)
          {
            $('#ltc').html(data.users);
          } 
          if(data.inuser)
          {
            $('#lint').html(data.inuser);
          } 
     
          if(data.daily)
          {
            $('#dailyp').html(data.daily);
          } 
          if(data.req)
          {
            $('#strequest').html(data.req);
          } 
           
       },
        error: function (jqXhr, textStatus, errorMessage) { // error callback 
          
            toastr.error('' + errorMessage + '');
          
        } 
    });
}

   /////////////////////////////////////// my new code
   $(function(){
   seo_data(); 
   fetch_title_site();
   fetch_favicon_logo();
});
$(document).on('submit','#logo_upload',function(e){
    e.preventDefault();
    var form = new FormData(this);
$.ajax({
        url:'<?=base_url()?>upload_logo',
        method:"post",
        data:form,
        //dataType:'json',
        contentType: false,
        cache: false,
        processData:false,
        beforeSend:function()
        {
            $('.alert_success').html('');
          $('#logo_text_btn').html('Please wait..');  
        },
        success:function(data)
        {
         
            $('#logo_text_btn').html('Saved');
            fetch_favicon_logo();
            $('.alert_success').html(data);
            
        },
        error: function (jqXhr, textStatus, errorMessage) { // error callback 
          
            toastr.error('' + errorMessage + '');
          
        } 
    });
});

$(document).on('submit','#loaderUpload',function(e){
    e.preventDefault();
    var form = new FormData(this);
$.ajax({
        url:'<?=base_url()?>loaderUpload',
        method:"post",
        data:form,
        //dataType:'json',
        contentType: false,
        cache: false,
        processData:false,
        beforeSend:function()
        {
            $('.alert_success').html('');
          $('#loader_text_btn').html('Please wait..');  
        },
        success:function(data)
        {
         
            $('#loader_text_btn').html('Saved');
            fetch_favicon_logo();
            $('.alert_success').html(data);
            
        },
        error: function (jqXhr, textStatus, errorMessage) { // error callback 
          
            toastr.error('' + errorMessage + '');
          
        } 
    });
});

$(document).on('submit','#default_user',function(e){
    e.preventDefault();
    var form = new FormData(this);
$.ajax({
        url:'<?=base_url()?>default_user',
        method:"post",
        data:form,
        //dataType:'json',
        contentType: false,
        cache: false,
        processData:false,
        beforeSend:function()
        {
            $('.alert_success').html('');
          $('#default_text_btn').html('Please wait..');  
        },
        success:function(data)
        {
         
            $('#default_text_btn').html('Saved');
            fetch_favicon_logo();
            $('.alert_success').html(data);
            
        },
        error: function (jqXhr, textStatus, errorMessage) { // error callback 
          
            toastr.error('' + errorMessage + '');
          
        } 
    });
});

$(document).on('submit','#favicon_upload',function(e){
    e.preventDefault();
    var form = new FormData(this);
    alert(form);
$.ajax({
        url:'<?=base_url()?>upload_favicon',
        method:"post",
        data:form,
        //dataType:'json',
        contentType: false,
        cache: false,
        processData:false,
        beforeSend:function()
        {
            $('.alert_success').html('');
          $('#favicon_text_btn').html('Please wait..');  
        },
        success:function(data)
        {
         
          
            $('#favicon_text_btn').html('Saved');
            fetch_favicon_logo();
            $('.alert_success').html(data);
            
        },
        error: function (jqXhr, textStatus, errorMessage) { // error callback 
          
            toastr.error('' + errorMessage + '');
          
        } 
    });
});

function fetch_favicon_logo()
{
    
    $.ajax({
       url:'<?=base_url()?>fetch_favicon_logo',
       method:'post',
       data:{'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>'},
       dataType:'json',
       success:function(data)
       {
           
         $('#img_favicon').html(data.favicon);
         $('#img_logo').html(data.logo);
         $('#loader_logo').html(data.loader);
         $('#default_user_img').html(data.default_user);
         
       },
        error: function (jqXhr, textStatus, errorMessage) { // error callback 
          
            toastr.error('' + errorMessage + '');
          
        } 
    });
    
}
function fetch_title_site()
{
    $.ajax({
       url:'<?=base_url()?>fetch_title_site',
       method:'post',
       data:{'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>'},
       dataType:'json',
       success:function(data)
       {

         $('#title').val(data.title);
         
         
       },
        error: function (jqXhr, textStatus, errorMessage) { // error callback 
          
            toastr.error('' + errorMessage + '');
          
        } 
    });
    
}

$(document).on('submit','#title_upload',function(e){
    e.preventDefault();
    var form = $('#title_upload').serialize();
    $.ajax({
       url:'<?=base_url()?>site-Title',
       method:'post',
       data:form,
       dataType:'json',
       beforeSend:function()
       {
         $('#title_text_btn').html('Please wait...');  
       },
       success:function(data)
       {
        
         $('#title_text_btn').html('Saved');  
         $('.alert_success').html(data);
         
         
       },
        error: function (jqXhr, textStatus, errorMessage) { // error callback 
          
            toastr.error('' + errorMessage + '');
          
        } 
    });
});


function seo_data()
{
    $.ajax({
       url:'<?=base_url()?>seo_fetch',
       method:'post',
       data:{'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>'},
       dataType:'json',
       success:function(data)
       {
        
         $('#author').val(data.author);
         $('#m_desc').val(data.desc);
         $('#keywords').val(data.key);
         
       },
        error: function (jqXhr, textStatus, errorMessage) { // error callback 
          
            toastr.error('' + errorMessage + '');
          
        } 
    });
    
}
$(document).on('submit','#seoMetaTags_form',function(e){
    e.preventDefault();
    var form = $('#seoMetaTags_form').serialize();
    $.ajax({
       url:'<?=base_url()?>seo',
       method:'post',
       data:form,
       dataType:'json',
       beforeSend:function()
       {
         $('#text_chng_btn').html('Please wait...');  
       },
       success:function(data)
       {
         $('#text_chng_btn').html('Saved');  
         $('.alert_show').html(data);
         
         
       },
        error: function (jqXhr, textStatus, errorMessage) { // error callback 
          
            toastr.error('' + errorMessage + '');
          
        } 
    });
});

$(function(){
  scarcthFunctionrequest();  
  alluserpagme();
  allDailypay();
  singleLeg();
  myverifiy();
  transc();
  direct();
  binaryTable();
});

//binary 

function binaryTable()
  {
    $('#binaryTable').html('<div class="container"><div class="row"><div class="col-md-12 text-center"><i class="fa fa-spin fa-spinner"></i></div></div></div>');
 $.ajax({
    url:'<?=base_url()?>admin/binaryTableMain',
    method:"post",
    cache:false,
    data:{'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>'},
    success:function(data)
    {$('#binaryTable').html(data);    
    }
 });   
}
function binary(page_num) {
    
    page_num = page_num?page_num:0;
    var keywords = $('#searchOffer').val();
    var sortBy = $('#sortBy').val();
    var PageData = $('#pageData').val();
    $.ajax({
        type: 'POST',
        url:'<?=base_url()?>admin/binaryAjax/ajaxPaginationData/'+page_num,
        data:'page='+page_num+'&keywords='+keywords+'&sortBy='+sortBy+'&pageby='+PageData+'&<?php echo $this->security->get_csrf_token_name(); ?>='+'<?php echo $this->security->get_csrf_hash(); ?>',
        beforeSend: function () {
            $('#binaryTable').html('<div class="container"><div class="row"><div class="col-md-12 text-center"><i class="fa fa-spin fa-spinner"></i></div></div></div>');
        },
        success: function (html) {
            $('#binaryTable').html(html);
        }
    });
}

//kyc
function direct()
  {
    $('#directData').html('<div class="container"><div class="row"><div class="col-md-12 text-center"><i class="fa fa-spin fa-spinner"></i></div></div></div>');
 $.ajax({
    url:'<?=base_url()?>directIncomeFunc',
    method:"post",
    cache:false,
    data:{'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>'},
    success:function(data)
    {$('#directData').html(data);    
    }
 });   
}
function directF(page_num) {
    
    page_num = page_num?page_num:0;
    var keywords = $('#searchOffer').val();
    var sortBy = $('#sortBy').val();
    var PageData = $('#pageData').val();
    $.ajax({
        type: 'POST',
        url:'<?=base_url()?>directAjax/ajaxPaginationData/'+page_num,
        data:'page='+page_num+'&keywords='+keywords+'&sortBy='+sortBy+'&pageby='+PageData+'&<?php echo $this->security->get_csrf_token_name(); ?>='+'<?php echo $this->security->get_csrf_hash(); ?>',
        beforeSend: function () {
            $('#directData').html('<div class="container"><div class="row"><div class="col-md-12 text-center"><i class="fa fa-spin fa-spinner"></i></div></div></div>');
        },
        success: function (html) {
            $('#directData').html(html);
        }
    });
}

// all user;

function transc()
  {
    $('#trans').html('<div class="container"><div class="row"><div class="col-md-12 text-center"><i class="fa fa-spin fa-spinner"></i></div></div></div>');
 $.ajax({
    url:'<?=base_url()?>transactionpageFunction',
    method:"post",
    cache:false,
    data:{'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>'},
    success:function(data)
    {

        $('#trans').html(data);    
        
        
    }
 });   
}

function trsnf(page_num) {
    
    page_num = page_num?page_num:0;
    var keywords = $('#searchOffer').val();
    var sortBy = $('#sortBy').val();
    var PageData = $('#pageData').val();
    var rank = $('#rankWise').val();
    $.ajax({
        type: 'POST',
        url:'<?=base_url()?>transactionAjax/ajaxPaginationData/'+page_num,
        data:'page='+page_num+'&rank='+rank+'&keywords='+keywords+'&sortBy='+sortBy+'&pageby='+PageData+'&<?php echo $this->security->get_csrf_token_name(); ?>='+'<?php echo $this->security->get_csrf_hash(); ?>',
        beforeSend: function () {
            $('#trans').html('<div class="container"><div class="row"><div class="col-md-12 text-center"><i class="fa fa-spin fa-spinner"></i></div></div></div>');
        },
        success: function (html) {
            $('#trans').html(html);
        }
    });
}

function myverifiy()
  {
    
    $('#kycUser').html('<div class="container"><div class="row"><div class="col-md-12 text-center"><i class="fa fa-spin fa-spinner"></i></div></div></div>');
 $.ajax({
    url:'<?=base_url()?>show-kyc',
    method:"post",
    cache:false,
    data:{'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>'},
    success:function(data)
    {

        $('#kycUser').html(data);    
        
        
    }
 });   
}

function checkKyc(page_num) {
    
    page_num = page_num?page_num:0;
    var keywords = $('#searchOffer').val();
    var sortBy = $('#sortBy').val();
    var PageData = $('#pageData').val();
    var rank = $('#rankWise').val();
    $.ajax({
        type: 'POST',
        url:'<?=base_url()?>kycAjax/ajaxPaginationData/'+page_num,
        data:'page='+page_num+'&rank='+rank+'&keywords='+keywords+'&sortBy='+sortBy+'&pageby='+PageData+'&<?php echo $this->security->get_csrf_token_name(); ?>='+'<?php echo $this->security->get_csrf_hash(); ?>',
        beforeSend: function () {
            $('#kycUser').html('<div class="container"><div class="row"><div class="col-md-12 text-center"><i class="fa fa-spin fa-spinner"></i></div></div></div>');
        },
        success: function (html) {
            $('#kycUser').html(html);
            
        }
    });
}
// single leg
function singleLeg()
  {
    
    $('#singleLeg').html('<div class="container"><div class="row"><div class="col-md-12 text-center"><i class="fa fa-spin fa-spinner"></i></div></div></div>');
 $.ajax({
    url:'<?=base_url()?>singledataLeg',
    method:"post",
    cache:false,
    data:{'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>'},
    success:function(data)
    {

        $('#singleLeg').html(data);    
        
        
    }
 });   
}

function singlelegC(page_num) {
    
    page_num = page_num?page_num:0;
    var keywords = $('#searchOffer').val();
    var sortBy = $('#sortBy').val();
    var PageData = $('#pageData').val();
    $.ajax({
        type: 'POST',
        url:'<?=base_url()?>singleLefAjax/ajaxPaginationData/'+page_num,
        data:'page='+page_num+'&keywords='+keywords+'&sortBy='+sortBy+'&pageby='+PageData+'&<?php echo $this->security->get_csrf_token_name(); ?>='+'<?php echo $this->security->get_csrf_hash(); ?>',
        beforeSend: function () {
            $('#singleLeg').html('<div class="container"><div class="row"><div class="col-md-12 text-center"><i class="fa fa-spin fa-spinner"></i></div></div></div>');
        },
        success: function (html) {
            $('#singleLeg').html(html);
            
        }
    });
}
//single leg income;
function allDailypay()
  {
    
    $('#allpayout').html('<div class="container"><div class="row"><div class="col-md-12 text-center"><i class="fa fa-spin fa-spinner"></i></div></div></div>');
 $.ajax({
    url:'<?=base_url()?>allDailypayR',
    method:"post",
    cache:false,
    data:{'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>'},
    success:function(data)
    {

        $('#allpayout').html(data);    
        
        
    }
 });   
}

function allDp(page_num) {
    
    page_num = page_num?page_num:0;
    var keywords = $('#searchOffer').val();
    var sortBy = $('#sortBy').val();
    var PageData = $('#pageData').val();
    var rank = $('#rankWise').val();
    $.ajax({
        type: 'POST',
        url:'<?=base_url()?>allpayoutData/ajaxPaginationData/'+page_num,
        data:'page='+page_num+'&rank='+rank+'&keywords='+keywords+'&sortBy='+sortBy+'&pageby='+PageData+'&<?php echo $this->security->get_csrf_token_name(); ?>='+'<?php echo $this->security->get_csrf_hash(); ?>',
        beforeSend: function () {
            $('#allpayout').html('<div class="container"><div class="row"><div class="col-md-12 text-center"><i class="fa fa-spin fa-spinner"></i></div></div></div>');
        },
        success: function (html) {
            $('#allpayout').html(html);
            
        }
    });
}
//scratch request;

function scarcthFunctionrequest()
  {
      
      $('#scarcthAddData').html('<div class="container"><div class="row"><div class="col-md-12 text-center"><i class="fa fa-spin fa-spinner"></i></div></div></div>');
   $.ajax({
      url:'<?=base_url()?>scratchDefaultrequest',
      method:"post",
      cache:false,
      data:{'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>'},
      success:function(data)
      {

          $('#scarcthAddData').html(data);    
          
          
      }
   });   
}

function scratchAddAjax(page_num) {
    
    page_num = page_num?page_num:0;
    var keywords = $('#searchOffer').val();
    var sortBy = $('#sortBy').val();
    var PageData = $('#pageData').val();
    $.ajax({
        type: 'POST',
        url:'<?=base_url()?>scratchDefaultAjaxRequest/ajaxPaginationData/'+page_num,
        data:'page='+page_num+'&keywords='+keywords+'&sortBy='+sortBy+'&pageby='+PageData+'&<?php echo $this->security->get_csrf_token_name(); ?>='+'<?php echo $this->security->get_csrf_hash(); ?>',
        beforeSend: function () {
            $('#scarcthAddData').html('<div class="container"><div class="row"><div class="col-md-12 text-center"><i class="fa fa-spin fa-spinner"></i></div></div></div>');
        },
        success: function (html) {
            $('#scarcthAddData').html(html);
            
        }
    });
}
// all user;

function alluserpagme()
  {
    
    $('#allUserHtmlData').html('<div class="container"><div class="row"><div class="col-md-12 text-center"><i class="fa fa-spin fa-spinner"></i></div></div></div>');
 $.ajax({
    url:'<?=base_url()?>showUserPag',
    method:"post",
    cache:false,
    data:{'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>'},
    success:function(data)
    {

        $('#allUserHtmlData').html(data);    
        
        
    }
 });   
}

function allUserPag(page_num) {
    
    page_num = page_num?page_num:0;
    var keywords = $('#searchOffer').val();
    var sortBy = $('#sortBy').val();
    var PageData = $('#pageData').val();
    var rank = $('#rankWise').val();
    var statusWise = $('#statusWise').val();
    var pkgwise = $('#pkgwise').val();
    var datefilter = $('#datefilter').val();
    $.ajax({
        type: 'POST',
        url:'<?=base_url()?>allUserPagnet/ajaxPaginationData/'+page_num,
        data:'page='+page_num+'&rank='+rank+'&statusWise='+statusWise+'&datefilter='+datefilter+'&pkgwise='+pkgwise+'&keywords='+keywords+'&sortBy='+sortBy+'&pageby='+PageData+'&<?php echo $this->security->get_csrf_token_name(); ?>='+'<?php echo $this->security->get_csrf_hash(); ?>',
        beforeSend: function () {
            $('#allUserHtmlData').html('<div class="container"><div class="row"><div class="col-md-12 text-center"><i class="fa fa-spin fa-spinner"></i></div></div></div>');
        },
        success: function (html) {
            $('#allUserHtmlData').html(html);
            
        }
    });
}



  </script>
</body>
</html>    