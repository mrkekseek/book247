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
            <div class="login-bg" style="background-image:url('{{ asset('assets/pages/img/login/bg1.jpg') }}')">
                <img class="login-logo" src="{{ asset('assets/pages/img/login/logo_sqf.png') }}" /> </div>
        </div>
        <div class="col-md-6 login-container bs-reset">
            <div class="login-content">
                <h1>Password Reset Form</h1>
                <p> Use your registration email, the email that you received the reset password link, in the "Email Address" field.
                    Use the same password in the two password fields, passwords must be at least 8 characters long.</p>
                <form class="login-form" id="form_reset" method="post">
                    {!! csrf_field() !!}
                    <h3 class="font-green">Forgot Password ?</h3>
                    <p> Enter your e-mail address below to reset your password. </p>
                    <div class="form-group">
                        <input class="form-control placeholder-no-fix form-group" type="text" autocomplete="off" placeholder="Email" name="email" />
                    </div>
                    <div class="form-group">
                        <input class="form-control placeholder-no-fix form-group" type="password" autocomplete="off" placeholder="Password" name="password" />
                    </div>
                    <div class="form-group">
                        <input class="form-control placeholder-no-fix form-group" type="password" autocomplete="off" placeholder="Password" name="rpassword" />
                    </div>
                    <div class="form-actions">
                        <button type="button" id="back-btn" class="btn grey btn-default">Back</button>
                        <button type="button" class="btn blue btn-success uppercase pull-right" onclick="javascript: password_reset();">Submit</button>
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
<script src="{{ asset('assets/pages/scripts/login-5.min.js') }}" type="text/javascript"></script>
<script type="text/javascript">



    var FormValidation = function () {
        var handleValidation2 = function() {
            var form2 = $('#form_reset');
            var error2 = $('.alert-danger', form2);
            var success2 = $('.alert-success', form2);

            form2.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",  // validate all fields including form hidden input
                rules: {
                    email: {
                        email: true,
                        validate_email: true,
                        required: true,
                    },
                    password: {
                        required:true,
                        minlength: 8,
                        maxlength: 150,
                    },
                    rpassword: {
                        required:true,
                        minlength: 8,
                        maxlength: 150,
                        equalTo:"#InputPassword1"
                    },
                },

                invalidHandler: function (event, validator) { //display error alert on form submit
                    success2.hide();
                    error2.show();
                    App.scrollTo(error2, -200);
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

                submitHandler: function (form) {
                    success2.show();
                    error2.hide();
                    password_reset(); // submit the form
                }
            });
        }

        return {
            //main function to initiate the module
            init: function () {
                handleValidation2();
            }
        };
    }();
    $(document).ready(function(){
        FormValidation.init();
    });

    function password_reset(){
        $.ajax({
            url: '{{ route('reset_password', ['token'=>$token]) }}',
            type: "post",
            cache: false,
            data: {
                'email': $('input[name="email"]').val(),
                'password1': $('input[name="password"]').val(),
                'password2': $('input[name="rpassword"]').val(),
                '_token': '{{csrf_token()}}',
                'token': "{{$token}}"
            },
            success: function (data) {
                if (data.success==1) {
                    show_notification(data.title, data.message, 'lime', 3500, true);
                    setTimeout(function(){
                        location.href = "{{route('admin/login')}}";
                    },5000);
                }
                else{
                    show_notification(data.title, data.errors, 'red', 3500, true);
                }
            }
        });
    }

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

    @if($errors->has('email') || $errors->has('password'))
        setTimeout(function() {
        show_notification('{{$errors->first('header')}}', '{{$errors->first('message_body')}}', 10000, false);
    }, 500);
    @endif

    {{--function request_reset_email(email){--}}
        {{--$.ajax({--}}
            {{--url: '{{ route('ajax/backend_password_reset_request') }}',--}}
            {{--type: "post",--}}
            {{--cache: false,--}}
            {{--data: {--}}
                {{--'email': $('input[name=forgot_email]').val(),--}}
                {{--'_token': '{{ csrf_token() }}'--}}
            {{--},--}}
            {{--success: function (data) {--}}
                {{--console--}}
                {{--if (data.success==true) {--}}
                    {{--show_notification(data.title, data.message, 'lime', 5000, 0);--}}
                    {{--setTimeout(function(){--}}
                        {{--window.location.reload(true);--}}
                    {{--},5500);--}}
                {{--}--}}
                {{--else{--}}
                    {{--show_notification(data.title, data.errors, 'lemon', 5000, 0);--}}
                {{--}--}}
            {{--}--}}
        {{--});--}}
    {{--}--}}


</script>
<!-- END PAGE LEVEL SCRIPTS -->
<!-- BEGIN THEME LAYOUT SCRIPTS -->
<!-- END THEME LAYOUT SCRIPTS -->
</body>

</html>