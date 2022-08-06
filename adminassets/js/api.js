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



$(document).on('click', '#pancardopen', function() {
    $('#panp').click();
});

$(function() {
    var pageURL = $(location).attr("href").split('?')[0];
    if (pageURL == 'http://jaiswal.mlm/api/play-game') {
        gamePlayTour();
    }
});
var check = $(location).attr("href");
if (check == 'http://jaiswal.mlm/api/game') {
    setInterval(function() {
        game();
    }, 2000);
}

function game() {
    let token = csrfdata();
    $.ajax({
        url: '/api/start_gameData',
        method: "post",
        cache: false,
        dataType: 'json',
        data: { 'csrf_test_name': token },
        success: function(data) {
            console.log(data);
            if (data.all) {
                $('#showGames').html(data.all);
            }
            if (data.live) {
                $('#showlive').html(data.live);
            }
        }
    });
}

var pageURL = $(location).attr("href").split('?')[0];
console.log('URL: '+pageURL);
if (pageURL == 'http://jaiswal.mlm/api/play-game') {

    function gamePlayTour() {
        //var id = getUrlParameter('q');
        var params = new window.URLSearchParams(window.location.search);
        let token = csrfdata();
        $.ajax({
            url: "/api/gameEnterTo",
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

$(document).on('click', '#clickToload', function() {
    $('#clickToload').html('wait while..');
});


$('#sign_in_game_btn').on('click', function(e) {
    e.preventDefault();
    if (navigator.onLine) {
        let form = $('.signe_in_game').serialize();
        $.ajax({
            url: '/api/login',
            method: 'post',
            dataType: 'json',
            data: form,
            beforeSend: function() {
                $('#sign_in_game_btn').addClass('disabled');
                $('#sign_in_game_btn').html('please wait..');
                $('#exampleInputEmail12').removeClass('is-invalid');
                $('#exampleInputPassword12').removeClass('is-invalid');
            },
            success: function(data) {

                if (data.user) {
                    $('#sign_in_game_btn').removeClass('disabled');
                    $('#sign_in_game_btn').html('Sign in');
                    $('#exampleInputEmail12').addClass('is-invalid');
                    toastr.error('' + data.user + '');

                }
                if (data.pass) {
                    $('#sign_in_game_btn').removeClass('disabled');
                    $('#sign_in_game_btn').html('Sign in');
                    $('#exampleInputPassword12').addClass('is-invalid');
                    toastr.error('' + data.pass + '');

                }
                if (data.notfound) {
                    $('#sign_in_game_btn').removeClass('disabled');
                    $('#sign_in_game_btn').html('Sign in');
                    toastr.error('' + data.notfound + '');

                }
                if (data.loginerror) {
                    $('#sign_in_game_btn').removeClass('disabled');
                    $('#sign_in_game_btn').html('Sign in');
                    toastr.error('' + data.loginerror + '');

                }
                if (data.link) {
                    $('.preloader').css('display', 'block');
                    //$('#sign_in_btn').removeClass('disabled');
                    $('#sign_in_game_btn').html('logged in!');
                    window.location.href = data.link;

                }


            },
            error: function(jqXhr, textStatus, errorMessage) { // error callback 
                $('#sign_in_game_btn').removeClass('disabled');
                $('#sign_in_game_btn').html('Sign in');
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


$(document).on('click', '.money', function() {
    $('input[name="orderAmount"]').val($(this).html());
});

$('#submitTodata').on('click', function() {

    if (navigator.onLine) {
        $('#submitTodata').addClass('disabled');
        $('#submitTodata').html('<i class="fa fa-spin fa-spinner"></i>');

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

$(document).on('click', '.gameMenuAnchor', function() {
    $('.preloader').css('display', 'block');
});
$(document).on('click', '#notificationPage', function() {
    $('.preloader').css('display', 'block');
});
$(document).on('click', '#gameEnterToJoin', function() {
    $('.preloader').css('display', 'block');
});
$(document).on('click', '#balanceAffOkagjhsg', function() {
    $('.preloader').css('display', 'block');
});


$(document).on('click', '#clickTopass', function(e) {
    e.preventDefault();
    var form = $('#passwordForm').serialize();
    $.ajax({
        url: '/api/change_password_page',
        method: 'post',
        data: form,
        cache: false,
        dataType: 'json',
        beforeSend: function() {
            $('#clickTopass').addClass('disabled');
            $('#clickTopass').html('please wait <i class="fa fa-spin fa-spinner"></i>');
        },
        success: function(data) {
            $('#clickTopass').removeClass('disabled');
            $('#clickTopass').html('Change Password');

            if (data.cr) {
                toastr.error(data.cr);
            }
            if (data.ok) {
                toastr.success(data.ok);

            }

        },
        error: function(jqXhr, textStatus, errorMessage) { // error callback 
            $('#clickTopass').removeClass('disabled');
            $('#clickTopass').html('Change Password');
            toastr.error('' + errorMessage + '');
        }
    });
});


$(document).on('keyup', '#cutInterest', function() {
    let token = csrfdata();
    $.ajax({
        url: '/api/cutinterest',
        method: "post",
        cache: false,
        dataType: 'json',
        data: { 'csrf_test_name': token, bal: $(this).val() },
        success: function(data) {
            $('#chargeAmt').html(data);

        }
    });
});

$(document).on('click', '#transfer-money', function() {
    $.ajax({
        url: '/api/transferMoneyRequest',
        method: "post",
        cache: false,
        dataType: 'json',
        data: $('#withdrawRaise').serialize(),
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

        },
        error: function(jqXhr, textStatus, errorMessage) { // error callback 
            $('#transfer-money').removeClass('disabled');
            $('#transfer-money').html('transfer-money');
            toastr.error('' + errorMessage + '');
        }
    });
});

$(document).on('click', '#otpVerify', function() {
    $.ajax({
        url: '/api/otp_verifyFunctionPage',
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

$('#api_create_acount').on('click', function(e) {
    e.preventDefault();
    if (navigator.onLine) {
        let form = $('.create_form').serialize();
        $.ajax({
            url: '/api/signupToCreate',
            method: 'post',
            dataType: 'json',
            data: form,
            beforeSend: function() {
                $('#api_create_acount').addClass('disabled');
                $('#api_create_acount').html('please wait..');
                $('#phone').removeClass('is-invalid');
            },
            success: function(data) {
                $('#api_create_acount').removeClass('disabled');
                $('#api_create_acount').html('Create account');


                if (data.phone) {
                    $('#phone').addClass('is-invalid');
                }
                if (data.er) {
                    $('.errorterms').html(data.er);
                }
                if (data.ok) {
                    $.confirm({
                        title: data.title,
                        content: data.ok,
                        type: 'green',
                        typeAnimated: true,
                        buttons: {
                            tryAgain: {
                                text: 'DONE',
                                btnClass: 'btn-green',
                                action: function() {
                                    location.reload();
                                }
                            }
                        }
                    });
                }


            },
            error: function(jqXhr, textStatus, errorMessage) { // error callback 
                $('#api_create_acount').removeClass('disabled');
                $('#api_create_acount').html('Create account');
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

$('#otpVerifyapi').on('click', function(e) {
    e.preventDefault();
    if (navigator.onLine) {
        let form = $('.otpform').serialize();
        $.ajax({
            url: '/api/otp_verify',
            method: 'post',
            dataType: 'json',
            data: form,
            beforeSend: function() {
                $('#otpVerifyapi').addClass('disabled');
                $('#otpVerifyapi').html('please wait..');
                $('#phone').removeClass('is-invalid');
            },
            success: function(data) {
                $('#otpVerifyapi').removeClass('disabled');
                $('#otpVerifyapi').html('Verify');
                if (data.er) {
                    $('#Otpveri').addClass('is-invalid');
                    $('.errotp').html(data.er);
                }
                if (data.ok) {
                    $.confirm({
                        title: data.title,
                        content: data.msg,
                        type: 'green',
                        typeAnimated: true,
                        buttons: {
                            tryAgain: {
                                text: data.btn,
                                btnClass: 'btn-green',
                                action: function() {
                                    window.location.href = data.link;
                                }
                            }
                        }
                    });
                }


            },
            error: function(jqXhr, textStatus, errorMessage) { // error callback 
                $('#otpVerifyapi').removeClass('disabled');
                $('#otpVerifyapi').html('Verify');
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

$('#finishToAccount').on('click', function(e) {
    e.preventDefault();
    if (navigator.onLine) {
        let form = $('.final_form').serialize();
        $.ajax({
            url: '/api/finishAccountSetup',
            method: 'post',
            dataType: 'json',
            data: form,
            beforeSend: function() {
                $('#finishToAccount').addClass('disabled');
                $('#finishToAccount').html('please wait..');
                $('#email').removeClass('is-invalid');
                $('#name').removeClass('is-invalid');

            },
            success: function(data) {
                $('#finishToAccount').removeClass('disabled');
                $('#finishToAccount').html('Complete registration');


                if (data.a) {
                    $('#name').addClass('is-invalid');
                }
                if (data.b) {
                    $('#email').addClass('is-invalid');
                    $('.emailer').html(data.b);
                }
                if (data.c) {
                    $('#user').addClass('is-invalid');
                    $('.userer').html(data.c);
                }
                if (data.d) {
                    $('#pass').addClass('is-invalid');
                }
                if (data.ok) {
                    $.confirm({
                        title: data.title,
                        content: data.msg,
                        type: 'green',
                        typeAnimated: true,
                        buttons: {
                            tryAgain: {
                                text: data.btn,
                                btnClass: 'btn-green',
                                action: function() {
                                    window.location.href = data.link;
                                }
                            }
                        }
                    });
                }


            },
            error: function(jqXhr, textStatus, errorMessage) { // error callback 
                $('#finishToAccount').removeClass('disabled');
                $('#finishToAccount').html('Complete registration');
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

$('#forget_btn').on('click', function(e) {
    e.preventDefault();
    if (navigator.onLine) {
        let form = $('.forget_form').serialize();
        $.ajax({
            url: '/api/forgetCheckMember',
            method: 'post',
            dataType: 'json',
            data: form,
            beforeSend: function() {
                $('#forget_btn').addClass('disabled');
                $('#forget_btn').html('please wait <i class="fa fa-spin fa-spinner"></i>');
                $('#forget_member').removeClass('is-invalid');
                $('#errorMsg').html('');
                $('#errorMsg').removeClass('text-success');
                $('#errorMsg').removeClass('text-danger');
            },
            success: function(data) {
                $('#forget_btn').removeClass('disabled');
                $('#forget_btn').html('forget ?');
                if (data.er) {
                    $('#forget_member').addClass('is-invalid');
                    $('#errorMsg').removeClass('text-success');
                    $('#errorMsg').addClass('text-danger');
                    $('#errorMsg').html(data.er);
                }
                if (data.ok) {
                    $('#errorMsg').removeClass('text-danger');
                    $('#errorMsg').addClass('text-success');
                    $('#errorMsg').html(data.ok);
                    setTimeout(function() {
                        location.reload();
                    }, 500);
                }

            },
            error: function(jqXhr, textStatus, errorMessage) { // error callback 
                $('#forget_btn').removeClass('disabled');
                $('#forget_btn').html('forget ?');
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

$('#otp_btn').on('click', function(e) {
    e.preventDefault();
    if (navigator.onLine) {
        let form = $('.otp_verify').serialize();
        $.ajax({
            url: '/api/otp_verify',
            method: 'post',
            dataType: 'json',
            data: form,
            beforeSend: function() {
                $('#otp_btn').addClass('disabled');
                $('#otp_btn').html('verifying <i class="fa fa-spin fa-spinner"></i>');
                $('#otp').removeClass('is-invalid');
                $('#otpError').html('');
                $('#otpError').removeClass('text-success');
                $('#otpError').removeClass('text-danger');
            },
            success: function(data) {
                $('#otp_btn').removeClass('disabled');
                $('#otp_btn').html('verify');

                if (data.er) {
                    $('#otp').addClass('is-invalid');
                    $('#otpError').removeClass('text-success');
                    $('#otpError').addClass('text-danger');
                    $('#otpError').html(data.er);
                }
                if (data.ok) {
                    $('#otp_btn').removeClass('disabled');
                    $('#otp_btn').html('verifyed');
                    setTimeout(function() {
                        location.reload();
                    }, 500);
                }

            },
            error: function(jqXhr, textStatus, errorMessage) { // error callback 
                $('#otp_btn').removeClass('disabled');
                $('#otp_btn').html('verify');
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

$('#chng_btn').on('click', function(e) {
    e.preventDefault();
    if (navigator.onLine) {
        let form = $('.chng_verify').serialize();
        $.ajax({
            url: '/api/change_passwordapi',
            method: 'post',
            dataType: 'json',
            data: form,
            beforeSend: function() {
                $('#chng_btn').addClass('disabled');
                $('#chng_btn').html('please wait <i class="fa fa-spin fa-spinner"></i>');
                $('#new_pass').removeClass('is-invalid');
                $('#cnf_pass').removeClass('is-invalid');
                $('#newpass').html('');
                $('#newpass').removeClass('text-success');
                $('#newpass').removeClass('text-danger');
                $('#cnfpass').html('');
                $('#cnfpass').removeClass('text-success');
                $('#cnfpass').removeClass('text-danger');

            },
            success: function(data) {
                $('#chng_btn').removeClass('disabled');
                $('#chng_btn').html('Change password');

                if (data.new) {
                    $('#new_pass').addClass('is-invalid');
                    $('#newpass').removeClass('text-success');
                    $('#newpass').addClass('text-danger');
                    $('#newpass').html(data.new);
                }
                if (data.cnf) {
                    $('#cnf_pass').addClass('is-invalid');
                    $('#cnfpass').removeClass('text-success');
                    $('#cnfpass').addClass('text-danger');
                    $('#cnfpass').html(data.cnf);
                }
                if (data.ok) {
                    $('#chng_btn').removeClass('disabled');
                    $('#chng_btn').html('Password changed');
                    setTimeout(function() {
                        window.location.href = data.ok;
                    }, 500);

                }

            },
            error: function(jqXhr, textStatus, errorMessage) { // error callback 
                $('#chng_btn').removeClass('disabled');
                $('#chng_btn').html('Change password');
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




$('#procceedtoapyToNext').on('click', function(e) {
    e.preventDefault();
    if (navigator.onLine) {
        let form = $('.addAccountAsBenificery').serialize();
        $.ajax({
            url: $('.addAccountAsBenificery').attr('action'),
            method: $('.addAccountAsBenificery').attr('method'),
            dataType: 'json',
            data: form,
            beforeSend: function() {
                $('#procceedtoapyToNext').addClass('disabled');
                $('#procceedtoapyToNext').html('please wait <i class="fa fa-spin fa-spinner"></i>');
                $('#email').removeClass('is-invalid');
                $('#name').removeClass('is-invalid');
                $('#phone').removeClass('is-invalid');
                $('#acco').removeClass('is-invalid');
                $('#cnfAcc').removeClass('is-invalid');
                $('#brnch').removeClass('is-invalid');
                $('#ifsc').removeClass('is-invalid');

            },
            success: function(data) {
                $('#procceedtoapyToNext').removeClass('disabled');
                $('#procceedtoapyToNext').html('ADD ACCOUNT');

                    console.log(data);

                if (data.a) {
                    $('#name').addClass('is-invalid');
                }
                if (data.b) {
                    $('#acco').addClass('is-invalid');
                    $('#error').html(data.c);
                }
                if (data.c) {
                    $('#cnfAcc').addClass('is-invalid');
                }
                if (data.e) {
                    $('#ifsc').addClass('is-invalid');
                }
                if (data.f) {
                    $('#phone').addClass('is-invalid');
                }
                if (data.g) {
                    $('#email').addClass('is-invalid');
                }
                if (data.h) {
                    $('#brnch').addClass('is-invalid');
                }
                if (data.kyc) {
                    $.confirm({
                        title: data.kycTitle,
                        content: data.kycmsg,
                        type: 'blue',
                        typeAnimated: true,
                        buttons: {
                            tryAgain: {
                                text: 'ADD ACCOUNT',
                                btnClass: 'btn-red',
                                action: function() {
                                    window.location.href = data.url;
                                }
                            }
                        }
                    });
                }
                if (data.i) {
                    $.confirm({
                        title: 'ACCOUNT HAVE',
                        content: 'ACCOUNT ALLREADY ADDED',
                        type: 'red',
                        typeAnimated: true,
                        buttons: {
                            tryAgain: {
                                text: 'BACK TO ACCOUNT',
                                btnClass: 'btn-red',
                                action: function() {
                                    window.location.href = '/api/withdrow-amount';
                                }
                            }
                        }
                    });
                }
                if (data.succ) {
                    $.confirm({
                        title: 'BENIFICERY ' + data.status,
                        content: data.msg,
                        type: 'blue',
                        typeAnimated: true,
                        buttons: {
                            tryAgain: {
                                text: 'DONE',
                                btnClass: 'btn-blue',
                                action: function() {
                                    window.location.href = '/api/withdrow-amount';
                                }
                            }
                        }
                    });
                }
                if (data.errorcode) {
                    $.confirm({
                        title: 'BENIFICERY ' + data.errorcode,
                        content: data.msg,
                        type: 'blue',
                        typeAnimated: true,
                        buttons: {
                            tryAgain: {
                                text: 'CLOSE',
                                btnClass: 'btn-blue',
                                action: function() {
                                    window.location.href = '/api/withdrow-amount';
                                }
                            }
                        }
                    });
                }
                if (data.mainerror) {
                    $.confirm({
                        title: data.post,
                        content: data.mainerror,
                        type: 'red',
                        typeAnimated: true,
                        buttons: {
                            tryAgain: {
                                text: 'close',
                                btnClass: 'btn-red',
                                close: function() {
                                    location.reload();
                                }
                            }
                        }
                    });
                }
                if (data.d) {
                    $.confirm({
                        title: 'BENIFICERY EXIST',
                        content: 'BENIFICERY ALL READY EXIST',
                        type: 'red',
                        typeAnimated: true,
                        buttons: {
                            tryAgain: {
                                text: 'CLOSE',
                                btnClass: 'btn-red',
                                action: function() {
                                    window.location.href = '/api/withdrow-amount';
                                }
                            }
                        }
                    });
                }


            },
            error: function(jqXhr, textStatus, errorMessage) { // error callback 
                $('#procceedtoapyToNext').removeClass('disabled');
                $('#procceedtoapyToNext').html('ADD ACCOUNT');
                //toastr.error('' + errorMessage + '');

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

$('#withdrawFinal').on('click', function(e) {
    e.preventDefault();
    if (navigator.onLine) {
        let form = $('.withdrawBalancePaymentsThrough').serialize();
        $.ajax({
            url: $('.withdrawBalancePaymentsThrough').attr('action'),
            method: $('.withdrawBalancePaymentsThrough').attr('method'),
            dataType: 'json',
            data: form,
            beforeSend: function() {
                $('#withdrawFinal').addClass('disabled');
                $('#withdrawFinal').html('please wait <i class="fa fa-spin fa-spinner"></i>');
                $('#amount').removeClass('is-invalid');

            },
            success: function(data) {
                $('#withdrawFinal').removeClass('disabled');
                $('#withdrawFinal').html('WITHDRAW BALANCE');


                if (data.a) {
                    $('#amount').addClass('is-invalid');
                }
                if (data.kyc) {
                    $.confirm({
                        title: data.kycTitle,
                        content: data.kycmsg,
                        type: 'red',
                        typeAnimated: true,
                        buttons: {
                            tryAgain: {
                                text: 'close',
                                btnClass: 'btn-red',
                                close: function() {
                                    location.reload();
                                }
                            }
                        }
                    });
                }
                if (data.errorcode) {
                    $.confirm({
                        title: 'TRANSACTION ' + data.errorcode,
                        content: data.msg,
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
                }

                if (data.succ) {
                    $.confirm({
                        title: 'TRANSACTION ' + data.status,
                        content: data.msg + ' Your refrence id #' + data.refrence,
                        type: 'blue',
                        typeAnimated: true,
                        buttons: {
                            tryAgain: {
                                text: 'DONE',
                                btnClass: 'btn-blue',
                                action: function() {
                                    location.reload();
                                }
                            }
                        }
                    });
                }

                if (data.mainerror) {
                    $.confirm({
                        title: data.post,
                        content: data.mainerror,
                        type: 'red',
                        typeAnimated: true,
                        buttons: {
                            tryAgain: {
                                text: 'close',
                                btnClass: 'btn-red',
                                close: function() {
                                    location.reload();
                                }
                            }
                        }
                    });
                }





            },
            error: function(jqXhr, textStatus, errorMessage) { // error callback 
                $('#withdrawFinal').removeClass('disabled');
                $('#withdrawFinal').html('WITHDRAW BALANCE');
                //toastr.error('' + errorMessage + '');

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


/*$('#proccedtokyc').on('click', function(e) {
    e.preventDefault();
    if (navigator.onLine) {
        let form = $('.dokycform').serialize();
        $.ajax({
            url: $('.dokycform').attr('action'),
            method: $('.dokycform').attr('method'),
            //dataType: 'json',
            data: form,
            beforeSend: function() {
                $('#proccedtokyc').addClass('disabled');
                $('#proccedtokyc').html('please wait <i class="fa fa-spin fa-spinner"></i>');
                $('#name').removeClass('is-invalid');
                $('#pan').removeClass('is-invalid');
                $('#panp').removeClass('is-invalid');
                $('#addhrf').removeClass('is-invalid');
                $('#addhrb').removeClass('is-invalid');
                $('#textpanerror').html('');

            },
            success: function(data) {
                $('#proccedtokyc').removeClass('disabled');
                $('#proccedtokyc').html('KYC COMPLETE');

                console.log(data);
                if (data.a) {
                    $('#name').addClass('is-invalid');
                }
                if (data.p) {
                    $('#pan').addClass('is-invalid');
                    $('#textpanerror').html(data.p);
                }
                if (data.er) {
                    $('#panp').addClass('is-invalid');
                }
                if (data.er) {
                    $('#addhrf').addClass('is-invalid');
                }
                if (data.er) {
                    $('#addhrb').addClass('is-invalid');
                }
                /* if(data.errorcode){
                     $.confirm({
                         title: 'TRANSACTION '+data.errorcode,
                         content: data.msg, 
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
                 }

            },
            error: function(jqXhr, textStatus, errorMessage) { // error callback 
                $('#proccedtokyc').removeClass('disabled');
                $('#proccedtokyc').html('KYC COMPLETE');
                //toastr.error('' + errorMessage + '');

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
});*/