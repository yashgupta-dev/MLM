/* toaster setting===================*/

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

$('#forget_btn').on('click', function(e) {
    e.preventDefault();
    if (navigator.onLine) {
        let form = $('.forget_form').serialize();
        $.ajax({
            url: '/forgetCheckMember',
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
            url: '/otp_verify',
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
            url: '/change_password',
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
                if (data.er) {
                    toastr.error(data.er);
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

$('#sign_in_btn_second').on('click', function(e) {
    e.preventDefault();
    if (navigator.onLine) {
        let form = $('.sign_in_form_second').serialize();
        $.ajax({
            url: '/admin-login',
            method: 'post',
            dataType: 'json',
            data: form,
            beforeSend: function() {
                $('#sign_in_btn_second').addClass('disabled');
                $('#sign_in_btn_second').html('please wait..');
                $('#exampleInputEmail12').removeClass('is-invalid');
                $('#exampleInputPassword12').removeClass('is-invalid');
            },
            success: function(data) {

                if (data.user) {
                    $('#sign_in_btn_second').removeClass('disabled');
                    $('#sign_in_btn_second').html('Sign in');
                    $('#exampleInputEmail12').addClass('is-invalid');
                    toastr.error('' + data.user + '');

                }
                if (data.pass) {
                    $('#sign_in_btn_second').removeClass('disabled');
                    $('#sign_in_btn_second').html('Sign in');
                    $('#exampleInputPassword12').addClass('is-invalid');
                    toastr.error('' + data.pass + '');

                }
                if (data.notfound) {
                    $('#sign_in_btn_second').removeClass('disabled');
                    $('#sign_in_btn_second').html('Sign in');
                    toastr.error('' + data.notfound + '');

                }
                if (data.loginerror) {
                    $('#sign_in_btn_second').removeClass('disabled');
                    $('#sign_in_btn_second').html('Sign in');
                    toastr.error('' + data.loginerror + '');

                }
                if (data.link) {
                    $('.preloader').css('display', 'block');
                    //$('#sign_in_btn').removeClass('disabled');
                    $('#sign_in_btn_second').html('logged in!');
                    window.location.href = data.link;

                }


            },
            error: function(jqXhr, textStatus, errorMessage) { // error callback 
                $('#sign_in_btn_second').removeClass('disabled');
                $('#sign_in_btn_second').html('Sign in');
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



$('#sign_in_btn').on('click', function(e) {
    e.preventDefault();
    if (navigator.onLine) {
        let form = $('.sign_in_form').serialize();
        $.ajax({
            url: '/login',
            method: 'post',
            dataType: 'json',
            data: form,
            beforeSend: function() {
                $('#sign_in_btn').addClass('disabled');
                $('#sign_in_btn').html('please wait..');
                $('#exampleInputEmail1').removeClass('is-invalid');
                $('#exampleInputPassword1').removeClass('is-invalid');
            },
            success: function(data) {

                if (data.user) {
                    $('#sign_in_btn').removeClass('disabled');
                    $('#sign_in_btn').html('Sign in');
                    $('#exampleInputEmail1').addClass('is-invalid');
                    toastr.error('' + data.user + '');

                }
                if (data.pass) {
                    $('#sign_in_btn').removeClass('disabled');
                    $('#sign_in_btn').html('Sign in');
                    $('#exampleInputPassword1').addClass('is-invalid');
                    toastr.error('' + data.pass + '');

                }
                if (data.notfound) {
                    $('#sign_in_btn').removeClass('disabled');
                    $('#sign_in_btn').html('Sign in');
                    toastr.error('' + data.notfound + '');

                }
                if (data.loginerror) {
                    $('#sign_in_btn').removeClass('disabled');
                    $('#sign_in_btn').html('Sign in');
                    toastr.error('' + data.loginerror + '');

                }
                if (data.link) {
                    //$('#sign_in_btn').removeClass('disabled');
                    $('#sign_in_btn').html('logged in!');
                    if ($(window).width() > 768) {

                        window.location.href = data.link;
                    } else {
                        window.location.href = data.link;
                    }


                }


            },
            error: function(jqXhr, textStatus, errorMessage) { // error callback 
                $('#sign_in_btn').removeClass('disabled');
                $('#sign_in_btn').html('Sign in');
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


$('#sign_up_btn').on('click', function(e) {
    e.preventDefault();
    if (navigator.onLine) {
        let form = $('.signup_form').serialize();
        $.ajax({
            url: '/signu_up_me',
            method: 'post',
            dataType: 'json',
            data: form,
            beforeSend: function() {
                $('.preloader').css('display','block');
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
            success: function(data) {
                $('.preloader').css('display','none');
                $('#sign_up_btn').removeClass('disabled');
                $('#sign_up_btn').html('Sign in');

                if (data.name) {
                    $('#exampleInputname1').addClass('is-invalid');
                    toastr.error('' + data.name + '');
                }
                if (data.email) {
                    $('#exampleInputEmail2').addClass('is-invalid');
                    toastr.error('' + data.email + '');
                }
                if (data.phone) {
                    $('#exampleInputPhone1').addClass('is-invalid');
                    toastr.error('' + data.phone + '');
                }
                if (data.term) {
                    $('.errorterms').html('accept the terms & conditions.');
                    //toastr.error('' + data.term + '');
                }
                if (data.cnf) {
                    $('#cnf').addClass('is-invalid');
                    toastr.error('' + data.cnf + '');
                }
                if (data.side) {
                    $('#side').addClass('is-invalid');
                    toastr.error('' + data.side + '');
                }
                if (data.pass) {
                    $('#exampleInputPassword2').addClass('is-invalid');
                    toastr.error('' + data.pass + '');
                }

                if (data.success) {

                    $.confirm({
                        title: 'Congratulations!',
                        content: 'Your username is: ' + data.su + '<br> Your password is: ' + data.sp + '<br> Thank you user!',
                        type: 'green',
                        typeAnimated: true,
                        buttons: {
                            tryAgain: {
                                text: 'Thank you',
                                btnClass: 'btn-green',
                                action: function() {
                                    $.alert({
                                        title: 'Do not forget to copy',
                                        content: 'Your username is: ' + data.su + '&nbsp;&nbsp; <a href="javascript:;" data-id="' + data.su + '" id="clickTocopy"><i data-feather="copy"> <span class="text-dark    ">copy<span></i></a><br> Your password is: ' + data.sp + '<br> Thank you user!',
                                    });

                                }
                            },
                            close: function() {
                                $.alert({
                                    title: 'Do not forget to copy',
                                    content: 'Your username is: ' + data.su + '&nbsp;&nbsp; <a href="javascript:;" data-id="' + data.su + '" id="clickTocopy"><i data-feather="copy"> <span class="text-dark    ">copy<span></i></a><br> Your password is: ' + data.sp + '<br> Thank you user!',
                                });
                            }
                        }
                    });
                    $('.signup_form')[0].reset();
                    toastr.success('' + data.success + '');



                }
                if (data.DB) {

                    toastr.warning('' + data.DB + '');

                }
                if (data.errorWRONG) {

                    toastr.error('' + data.errorWRONG + '');

                }
                if (data.sponser) {

                    toastr.error('' + data.sponser + '');

                }
            },
            error: function(jqXhr, textStatus, errorMessage) { // error callback 
                $('.preloader').css('display','none');
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



$(document).on('click', '#clickTocopy', function(e) {
    e.preventDefault();
    var copyText = $(this).attr('data-id');

    document.addEventListener('copy', function(e) {
        e.clipboardData.setData('text/plain', copyText);
        e.preventDefault();
    }, true);

    document.execCommand('copy');
    toastr.info('username copied.');
});