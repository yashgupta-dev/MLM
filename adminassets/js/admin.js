/* toaster setting===================*/
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $("meta[name='csrf_test_name']").attr("content")
    }
});

$(window).on('load', function() {
    setTimeout(function() {
        $(".preloader").delay(700).fadeOut(700).addClass('loaded');
    }, 800);
    $(this).scrollTop(0);
});

toastr.options = {
    "closeButton": true,
    "debug": true,
    "newestOnTop": true,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "preventDuplicates": false,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
}

//
function csrfdata() {
    let token = $("meta[name='csrf_test_name']").attr("content");
    return token;
}
$(function() {
    countRightTeam();
    fetchROi();
});


$(function() {
    scarcthFunction();
    e_requestAjax();
    transaction();
    teamDataFetch();
    var pageURL = $(location).attr("href").split('?')[0];
    if (pageURL == '/my/play-game') {

        gamePlayTour();
    }

});


function teamDataFetch() {
    
    var url = $(location).attr("href").split('?')[1];
    var side = url.split('=')[1];
    
    let token = csrfdata();
    $('#myteamdata').html('<div class="container"><div class="row"><div class="col-md-12 text-center"><i class="fa fa-spin fa-spinner"></i></div></div></div>');
    $.ajax({
        url: '/my/teamFetchShowMe',
        method: "post",
        cache: false,
        data: { 'csrf_test_name': token,side:side },
        success: function(data) {
            $('#myteamdata').html(data);


        }
    });
}

function teambtn(page_num) {

    var url = $(location).attr("href").split('?')[1];
    var side = url.split('=')[1];
    page_num = page_num ? page_num : 0;
    let token = csrfdata();
    var keywords = $('#searchOffer').val();
    var sortBy = $('#sortBy').val();
    var PageData = $('#pageData').val();
    $.ajax({
        type: 'POST',
        url: '/myteamFetch/ajaxPaginationData/' + page_num,
        data: 'page=' + page_num + '&side='+ side + '&keywords=' + keywords + '&sortBy=' + sortBy + '&pageby=' + PageData + '&csrf_test_name=' + token,
        beforeSend: function() {
            $('#myteamdata').html('<div class="container"><div class="row"><div class="col-md-12 text-center"><i class="fa fa-spin fa-spinner"></i></div></div></div>');
        },
        success: function(html) {
            $('#myteamdata').html(html);

        }
    });
}



function transaction() {
    let token = csrfdata();
    $('#transactionRequest').html('<div class="container"><div class="row"><div class="col-md-12 text-center"><i class="fa fa-spin fa-spinner"></i></div></div></div>');
    $.ajax({
        url: '/my/trnsactionPageMain',
        method: "post",
        cache: false,
        data: { 'csrf_test_name': token },
        success: function(data) {
            $('#transactionRequest').html(data);


        }
    });
}

function transactionPage(page_num) {

    page_num = page_num ? page_num : 0;
    let token = csrfdata();
    var keywords = $('#searchOffer').val();
    var sortBy = $('#sortBy').val();
    var PageData = $('#pageData').val();
    $.ajax({
        type: 'POST',
        url: '/my/transactionAjax/ajaxPaginationData/' + page_num,
        data: 'page=' + page_num + '&keywords=' + keywords + '&sortBy=' + sortBy + '&pageby=' + PageData + '&csrf_test_name=' + token,
        beforeSend: function() {
            $('#transactionRequest').html('<div class="container"><div class="row"><div class="col-md-12 text-center"><i class="fa fa-spin fa-spinner"></i></div></div></div>');
        },
        success: function(html) {
            $('#transactionRequest').html(html);

        }
    });
}


$(document).on('click', '#checkoutButton', function(e) {

    e.preventDefault();
    var form = $('#scratchCard').serialize();
    $.ajax({
        url: "/requestToId",
        method: "post",
        data: form,
        dataType: 'json',
        cache: false,
        beforeSend: function() {
            $('#checkoutButton').addClass('disable');
            $('#checkoutButton').html('please wait <i class="fa fa-spin fa-spinner"></i>');
            $('#username').removeClass('is-invalid');
            $('#package_name').removeClass('is-invalid');
            $('#number').removeClass('is-invalid');
            $('#credit').removeClass('is-invalid');
            $('#debit').removeClass('is-invalid');

        },
        success: function(data) {
            $('#checkoutButton').removeClass('disable');
            $('#checkoutButton').html('Continue to checkout');
            console.log(data);
            if (data.a) {
                $('#username').addClass('is-invalid');
            }
            if (data.b) {
                $('#package_name').addClass('is-invalid');
            }
            if (data.e) {
                $('#email').addClass('is-invalid');
            }
            if (data.f) {
                $('#phone').addClass('is-invalid');
            }
            if (data.c) {
                $('#number').addClass('is-invalid');
            }
            if (data.d) {
                $('#credit').addClass('is-invalid');
                $('#debit').addClass('is-invalid');
            }
            if (data.ok) {
                $.confirm({
                    title: data.title,
                    content: data.msg,
                    type: 'green',
                    typeAnimated: true,
                    buttons: {
                        tryAgain: {
                            text: 'Next Step',
                            btnClass: 'btn-green',
                            action: function() {
                                location.reload();
                            }
                        },
                        close: function() {

                        }
                    }
                });
            }
            if (data.er) {
                $.confirm({
                    title: data.title,
                    content: data.er,
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
        },
        error: function(jqXhr, textStatus, errorMessage) { // error callback 
            $('#checkoutButton').removeClass('disable');
            $('#checkoutButton').html('Continue to checkout');
            toastr.error('' + errorMessage + '');
        }
    });

});


$(document).on('keyup', '#cutInterest', function() {

    let token = csrfdata();
    $.ajax({
        url: '/interest',
        method: "post",
        cache: false,
        dataType: 'json',
        data: { 'csrf_test_name': token, bal: $(this).val() },
        success: function(data) {
            $('#chargeAmt').html(data);


        }
    });

});
$(document).on('click', '#otpVerify', function() {
    $.ajax({
        url: '/otpVerifyFunction',
        method: "post",
        cache: false,
        dataType: 'json',
        data: $('.form-otp').serialize(),
        beforeSend: function() {
            $('#otpVerify').addClass('disabled');
            $('#otpVerify').html('verifying <i class="fa fa-spin fa-spinner"></i>');
            $('#otpName').removeClass('is-invalid');
            $('#otpError').html('');
        },
        success: function(data) {
            $('#otpVerify').removeClass('disabled');
            $('#otpVerify').html('Otp verify');
            console.log(data);
            if (data.er) {
                $('#otpName').addClass('is-invalid');
                $('#otpError').html(data.er);
            }
            if (data.msg) {
                $.confirm({
                    title: 'Transaction',
                    content: data.msg,
                    type: 'green',
                    typeAnimated: true,
                    buttons: {
                        tryAgain: {
                            text: 'Close',
                            btnClass: 'btn-green',
                            action: function() {
                                window.location.href = data.url;
                            }
                        }
                    }
                });
            }
        }
    });
});
$(document).on('click', '#transfer-money', function() {
    $.ajax({
        url: '/transfer-money',
        method: "post",
        cache: false,
        dataType: 'json',
        data: $('.formAMt').serialize(),
        beforeSend: function() {
            $('#transfer-money').addClass('disabled');
            $('#transfer-money').html('<i class="fa fa-spin fa-spinner"></i>');
            $('#otpErrorMain').removeClass('text-danger');
            $('#otpErrorMain').removeClass('text-success');
            $('#otpErrorMain').html('');

        },
        success: function(data) {
            $('#transfer-money').addClass('disabled');
            $('#transfer-money').html('<i class="fa fa-spin fa-spinner"></i>');
            if (data.error) {
                $.confirm({
                    title: 'Transaction',
                    content: data.error,
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

            if (data.ok) {
                $.confirm({
                    title: 'Transaction',
                    content: data.ok,
                    type: 'green',
                    typeAnimated: true,
                    buttons: {
                        tryAgain: {
                            text: 'Done',
                            btnClass: 'btn-green',
                            action: function() {
                                location.reload();
                            }
                        }
                    }
                });
            }
            if (data.otper) {
                $('#otpErrorMain').addClass('text-success');
                $('#otpErrorMain').addClass('text-danger');
                $('#otpErrorMain').html(data.otper);
            }
            $('#transfer-money').removeClass('disabled');
            $('#transfer-money').html('transfer-money');

        }
    });
});

function e_requestAjax() {
    let token = csrfdata();
    $('#e_pin_request').html('<div class="container"><div class="row"><div class="col-md-12 text-center"><i class="fa fa-spin fa-spinner"></i></div></div></div>');
    $.ajax({
        url: '/e_requestFunction',
        method: "post",
        cache: false,
        data: { 'csrf_test_name': token },
        success: function(data) {
            $('#e_pin_request').html(data);


        }
    });
}

function e_request(page_num) {

    page_num = page_num ? page_num : 0;
    let token = csrfdata();
    var keywords = $('#searchOffer').val();
    var sortBy = $('#sortBy').val();
    var PageData = $('#pageData').val();
    $.ajax({
        type: 'POST',
        url: '/e_requestUserAjax/ajaxPaginationData/' + page_num,
        data: 'page=' + page_num + '&keywords=' + keywords + '&sortBy=' + sortBy + '&pageby=' + PageData + '&csrf_test_name=' + token,
        beforeSend: function() {
            $('#e_pin_request').html('<div class="container"><div class="row"><div class="col-md-12 text-center"><i class="fa fa-spin fa-spinner"></i></div></div></div>');
        },
        success: function(html) {
            $('#e_pin_request').html(html);

        }
    });
}


function scarcthFunction() {
    let token = csrfdata();
    $('#scarcthData').html('<div class="container"><div class="row"><div class="col-md-12 text-center"><i class="fa fa-spin fa-spinner"></i></div></div></div>');
    $.ajax({
        url: '/scratchDefault',
        method: "post",
        cache: false,
        data: { 'csrf_test_name': token },
        success: function(data) {
            $('#scarcthData').html(data);


        }
    });
}

function scratchAjax(page_num) {

    page_num = page_num ? page_num : 0;
    let token = csrfdata();
    var keywords = $('#searchOffer').val();
    var sortBy = $('#sortBy').val();
    var PageData = $('#pageData').val();
    var Type = $('#typeBy').val();
    $.ajax({
        type: 'POST',
        url: '/scratchDefaultAjax/ajaxPaginationData/' + page_num,
        data: 'page=' + page_num + '&type=' + Type + '&keywords=' + keywords + '&sortBy=' + sortBy + '&pageby=' + PageData + '&csrf_test_name=' + token,
        beforeSend: function() {
            $('#scarcthData').html('<div class="container"><div class="row"><div class="col-md-12 text-center"><i class="fa fa-spin fa-spinner"></i></div></div></div>');
        },
        success: function(html) {
            $('#scarcthData').html(html);

        }
    });
}


/*$(document).on('click','.deleteImag',function(){
    var id = $(this).attr('data-id');
    let token = csrfdata();
    $.ajax({
        url:"/removeImageProof",
        method:"post",
        //dataType:'json',
        cache:false,
        data:{'csrf_test_name':token,id:id},
        success:function(data)
        {
            if(data.er){
                $.confirm({
                    title: 'KYC',
                    content: data.er,
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
                    checkKyc();            
               }, error: function (jqXhr, textStatus, errorMessage) { // error callback 
                    toastr.error('' + errorMessage + '');
                } 
            });
    });
    */

$('#copy-me').click(function(e) {
    e.preventDefault();
    var copyText = $(this).attr('url');
    document.addEventListener('copy', function(e) {
        e.clipboardData.setData('text/plain', copyText);
        e.preventDefault();
    }, true);
    document.execCommand('copy');
    toastr.info('url code copied.');
});

function fetchROi() {
    $.ajax({
        url: "/ROIIncrease",
        method: "post",
        dataType: 'json',
        cache: false,
        success: function(data) {
            $('#ROIStatus').html(data);
        },
        error: function(jqXhr, textStatus, errorMessage) { // error callback 
            toastr.error('' + errorMessage + '');
        }
    });
}



function countRightTeam() {
    let token = csrfdata();
    $.ajax({
        url: '/RightTeamCount',
        method: 'post',
        dataType: 'json',
        data: { 'csrf_test_name': token },
        success: function(data) {

            if(data.panelstatus){
                $('#panelstatus').html(data.panelstatus);
            }
            if (data.leftBusiness) {
                $('#leftBusiness').html(data.leftBusiness);
            }
            if (data.rightBusiness) {
                $('#rightBusiness').html(data.rightBusiness);
            }
            if (data.todayleftBusiness) {
                $('#todayleftBusiness').html(data.todayleftBusiness);
            }
            if (data.todayrightBusiness) {
                $('#todayrightBusiness').html(data.todayrightBusiness);
            }
            if (data.balance) {
                $('#balance').html(data.balance);
            }
            if (data.binary) {
                $('#binary').html(data.binary);
            }
            if (data.direct_income) {
                $('#direct_income').html(data.direct_income);
            }
            if (data.rightTotal) {
                $('#RightTeam').html(data.rightTotal);
            }
            if (data.LeftTeam) {
                $('#LeftTeam').html(data.LeftTeam);
            }
            if (data.direct) {
                $('#direct').html(data.direct);
            }

            if (data.dailyp) {
                $('#dailyp').html(data.dailyp);
            }
            if (data.trns) {
                $('#trns').html(data.trns);
            }


            /*if(data.binaryincome){
                $('#binary').html(data.binaryincome);
            }*/
        },
        error: function(jqXhr, textStatus, errorMessage) { // error callback 
            toastr.error('Dashboard: ' + errorMessage + '');

        }
    });
}

var pageURL = $(location).attr("href").split('?')[0];
if (pageURL == '/my/play-game') {

    function gamePlayTour() {
        //var id = getUrlParameter('q');
        var params = new window.URLSearchParams(window.location.search);
        //console.log(params.get('q'));
        //console.log(urlParam('q'));
        let token = csrfdata();
        $.ajax({
            url: "/my/gameEnterTo",
            method: "post",
            dataType: 'json',
            cache: false,
            data: { 'csrf_test_name': token, id: params.get('q') },
            beforeSend: function() {
                $('.tournamentDetailsShow').html('<div class="container"><div class="row"><div class="col-md-12 text-center"><i class="fa fa-spin fa-spinner"></i></div></div></div>');
            },
            success: function(data) {
                $('.tournamentDetailsShow').html(data);

            },
            error: function(jqXhr, textStatus, errorMessage) { // error callback 

                toastr.error('' + errorMessage + '');
            }
        });
    }

}

$(document).on('click', '.closeModalNow', function() {
    $('.mainACtiveShow').hide('200').css('display', 'none');
});


$( document ).ready(function() {
    $('#appswitch').change(function(){
            document.onOffform.submit();
});
});

/*$(document).ready(function(){
                 $('#appswitch').change(function(){
            document.onOffform.submit();
});
   
   */

