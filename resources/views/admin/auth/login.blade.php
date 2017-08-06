<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->

<head>
    <meta charset="utf-8" />
    <title>Booking Management System | User Login</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/simple-line-icons/simple-line-icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/uniform/css/uniform.default.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="{{ asset('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/jquery-notific8/jquery.notific8.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN THEME GLOBAL STYLES -->
    <link href="{{ asset('assets/global/css/components-rounded.min.css') }}" rel="stylesheet" id="style_components" type="text/css" />
    <link href="{{ asset('assets/global/css/plugins.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- END THEME GLOBAL STYLES -->
    <!-- BEGIN PAGE LEVEL STYLES -->
    <link href="{{ asset('assets/pages/css/login-5.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL STYLES -->
    <!-- BEGIN THEME LAYOUT STYLES -->
    <!-- END THEME LAYOUT STYLES -->
    <link rel="shortcut icon" href="favicon.ico" /> </head>
<!-- END HEAD -->
<body class=" login">
<!-- BEGIN : LOGIN PAGE 5-1 -->
<div class="user-login-5">
    <div class="row bs-reset">
        <div class="col-md-6 bs-reset">
            <div class="login-bg" style="background-image:url(../assets/pages/img/login/bg1.jpg)">
                <img class="login-logo" src="../assets/pages/img/login/logo_sqf.png" /> </div>
        </div>
        <div class="col-md-6 login-container bs-reset">
            <div class="login-content">
                <form class="login-form" method="post" action="{{ route('admin/login') }}">
                    <div class="alert alert-danger display-hide">
                        <button class="close" data-close="alert"></button> You have some mistakes highlighted in red. Enter your username and password </div>
                    <div class="alert alert-success display-hide">
                        <button class="close" data-close="alert"></button> All went well. Please wait while we authorize you! </div>

                    <h1>Booking System Administration</h1>
                    <p> This page and the functionality on it is intended only for the employees of "Business Name" and is not for general use. If you got here by mistake please close the page and go on your way. Thanks!  </p>

                    {!! csrf_field() !!}
                    <div class="row">
                        <div class="col-xs-6">
                            <input class="form-control form-control-solid placeholder-no-fix form-group{{ $errors->has('username') ? ' has-error' : '' }}" type="email" value="{{ old('username') }}" autocomplete="off" placeholder="Email" name="email" required/> </div>
                        <div class="col-xs-6">
                            <input class="form-control form-control-solid placeholder-no-fix form-group{{ $errors->has('password') ? ' has-error' : '' }}" type="password" autocomplete="off" placeholder="Password" name="password" required/> </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="rem-password">
                                <p>Remember Me
                                    <input type="checkbox" name="remember" class="rem-checkbox" />
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-8 text-right">
                            <div class="forgot-password">
                                <a href="javascript:;" id="forget-password" class="forget-password">Forgot Password?</a>
                            </div>
                            <button class="btn blue" type="submit">Sign In</button>
                        </div>
                    </div>
                </form>
                <!-- BEGIN FORGOT PASSWORD FORM -->
                <form class="forget-form" role="form" id="form_forgot_passwd" name="form_forgot_passwd">
                    <div class="alert alert-danger display-hide">
                        <button class="close" data-close="alert"></button> You have some form errors. Please check below. </div>
                    <div class="alert alert-success display-hide">
                        <button class="close" data-close="alert"></button> Your form validation is successful! </div>
                    <h3 class="font-green">Forgot Password ?</h3>
                    <p> Enter your e-mail address below to reset your password. </p>
                    <div class="form-group">
                        <input class="form-control placeholder-no-fix form-group" type="text" autocomplete="off" placeholder="Email" name="forgot_email" /> </div>
                    <div class="form-actions">
                        <button type="button" id="back-btn" class="btn grey btn-default">Back</button>
                        <button type="submit" class="btn blue btn-success uppercase pull-right">Submit</button>
                    </div>
                </form>
                <!-- END FORGOT PASSWORD FORM -->
            </div>
            <div class="login-footer">
                <div class="row bs-reset">
                    <div class="col-xs-5 bs-reset">
                        <ul class="login-social">
                            <li>
                                <a href="javascript:;">
                                    <i class="icon-social-facebook"></i>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:;">
                                    <i class="icon-social-twitter"></i>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:;">
                                    <i class="icon-social-dribbble"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-xs-7 bs-reset">
                        <div class="login-copyright text-right">
                            <p>Copyright &copy; BookingSystem 2016</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END : LOGIN PAGE 5-1 -->
<!--[if lt IE 9]>
<script src="{{ asset('assets/global/plugins/respond.min.js') }}"></script>
<script src="{{ asset('assets/global/plugins/excanvas.min.js') }}"></script>
<![endif]-->
<!-- BEGIN CORE PLUGINS -->
<script src="{{ asset('assets/global/plugins/jquery.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/js.cookie.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery.blockui.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/uniform/jquery.uniform.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="{{ asset('assets/global/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery-validation/js/additional-methods.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/backstretch/jquery.backstretch.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery-notific8/jquery.notific8.min.js') }}" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN THEME GLOBAL SCRIPTS -->
<script src="{{ asset('assets/global/scripts/app.min.js') }}" type="text/javascript"></script>
<!-- END THEME GLOBAL SCRIPTS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->

<script type="text/javascript">
    $.validator.addMethod("validate_email",function(value, element) {
        if(/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test( value )) {
            return true;
        }
        else {
            return false;
        }
    },"Please enter a valid Email.");

    var Login = function() {

        var handleLogin = function() {

            $('.login-form').validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                rules: {
                    username: {
                        required: true
                    },
                    password: {
                        required: true
                    },
                    remember: {
                        required: false
                    }
                },

                messages: {
                    username: {
                        required: "Username is required."
                    },
                    password: {
                        required: "Password is required."
                    }
                },

                invalidHandler: function(event, validator) { //display error alert on form submit
                    $('.alert-danger', $('.login-form')).show();
                },

                highlight: function(element) { // hightlight error inputs
                    $(element)
                        .closest('.form-group').addClass('has-error'); // set error class to the control group
                },

                success: function(label) {
                    label.closest('.form-group').removeClass('has-error');
                    label.remove();
                },

                errorPlacement: function(error, element) {
                    error.insertAfter(element.closest('.input-icon'));
                },

                submitHandler: function(form) {
//                    form.submit(); // form validation success, call ajax form submit
                }
            });

            $('.login-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: '{{ route('admin/ajax_login')}}',
                    type: "post",
                    cache: false,
                    data: {
                        'email': $('.login-form').find('input[name=email]').val(),
                        'password': $('.login-form').find('input[name=password]').val(),
                        '_token': '{{ csrf_token() }}'
                    },
                    success: function (data) {
                        if (data.success == true) {
                            if (data.title.length) {
                                show_notification(data.title, data.message, 'lime', 5000, 0);
                            }
                            window.location.href = data.redirect_url;
                        }
                        else {
                            if (data.title.length) {
                                show_notification(data.title,  data.errors, 'ruby', 5000, true);
                            }
                        }
                    }
                });
            });

            $('.login-form input').keypress(function(e) {
                if (e.which == 13) {
                    if ($('.login-form').validate().form()) {
                        $('.login-form').submit(); //form validation success, call ajax form submit
                    }
                    return false;
                }
            });

            $('.forget-form input').keypress(function(e) {
                if (e.which == 13) {
                    if ($('.forget-form').validate().form()) {
                        $('.forget-form').submit();
                    }
                    return false;
                }
            });

            $('#forget-password').click(function(){
                $('.login-form').hide();
                $('.forget-form').show();
            });

            $('#back-btn').click(function(){
                $('.login-form').show();
                $('.forget-form').hide();
            });
        }

        return {
            //main function to initiate the module
            init: function() {
                handleLogin();

                // init background slide images
                $('.login-bg').backstretch([
                        "../assets/pages/img/login/bg1.jpg",
                        "../assets/pages/img/login/bg2.jpg",
                        "../assets/pages/img/login/bg3.jpg"
                    ], {
                        fade: 1000,
                        duration: 8000
                    }
                );

                $('.forget-form').hide();

            }

        };

    }();

    jQuery(document).ready(function() {
        Login.init();
    });

    function show_notification(title_heading, message, theme, life, sticky) {
        var settings = {
            theme: theme,
            sticky: sticky,
            horizontalEdge: 'top',
            verticalEdge: 'right',
            life : life,
        };

        if ($.trim(title_heading) != '') {
            settings.heading = title_heading;
        }

        $.notific8('zindex', 11500);
        $.notific8($.trim(message), settings);
    }

    @if(strlen($errors->first('message_body'))>0)
        setTimeout(function() {
            show_notification('{{$errors->first('header')}}', '{{$errors->first('message_body')}}', 'ruby', 10000, true);
        }, 500);
    @endif

    function request_reset_email(email){
        $.ajax({
            url: '{{ route('ajax/backend_password_reset_request') }}',
            type: "post",
            cache: false,
            data: {
                'email': email,
                '_token': '{{ csrf_token() }}'
            },
            success: function (data) {
                if (data.success==true) {
                    show_notification(data.title, data.message, 'lime', 5000, 0);
                    setTimeout(function(){
                        window.location.reload(true);
                    },5500);
                }
                else{
                    show_notification(data.title, data.errors, 'lemon', 5000, 0);
                }
            }
        });
    }

    var FormValidation = function () {
        /* Personal Info */
        var handleValidation3 = function() {
            var form1 = $('#form_forgot_passwd');
            var error1 = $('.alert-danger', form1);
            var success1 = $('.alert-success', form1);

            form1.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",  // validate all fields including form hidden input
                rules: {
                    forgot_email: {
                        required: true,
                        email: true,
                        validate_email: true
                    },
                },

                invalidHandler: function (event, validator) { //display error alert on form submit
                    success1.hide();
                    error1.show();
                    App.scrollTo(error1, -200);
                },

                errorPlacement: function (error, element) { // render error placement for each input type
                    var icon = $(element).parent('.input-icon').children('i');
                    icon.removeClass('fa-check').addClass("fa-warning");
                    icon.attr("data-original-title", error.text()).tooltip({'container': 'body'});
                },

                highlight: function (element) { // hightlight error inputs
                    $(element)
                        .closest('.form-group').removeClass("has-success").addClass('has-error'); // set error class to the control group
                },

                unhighlight: function (element) { // revert the change done by hightlight

                },

                success: function (label, element) {
                    var icon = $(element).parent('.input-icon').children('i');
                    $(element).closest('.form-group').removeClass('has-error').addClass('has-success'); // set success class to the control group
                    icon.removeClass("fa-warning").addClass("fa-check");
                },

                submitHandler: function (form1) {
                    success1.show();
                    error1.hide();
                    request_reset_email($('input[name=forgot_email]').val()); // submit the form
                }
            });
        }

        return {
            //main function to initiate the module
            init: function () {
                handleValidation3();
            }
        };
    }();

    $(document).ready(function(){
        FormValidation.init();
    });

</script>
<!-- END PAGE LEVEL SCRIPTS -->
<!-- BEGIN THEME LAYOUT SCRIPTS -->
<!-- END THEME LAYOUT SCRIPTS -->
</body>

</html>