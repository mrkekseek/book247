@extends('admin.layouts.main')

@section('pageLevelPlugins')
    <link href="{{ asset('assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('themeGlobalStyle')
    <link href="{{ asset('assets/global/css/components-rounded.min.css') }}" rel="stylesheet" id="style_components" type="text/css" />
    <link href="{{ asset('assets/global/css/plugins.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/jquery-notific8/jquery.notific8.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('themeLayoutStyle')
    <link href="{{ asset('assets/layouts/layout4/css/layout.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/layouts/layout4/css/themes/light.min.css') }}" rel="stylesheet" type="text/css" id="style_color" />
    <link href="{{ asset('assets/layouts/layout4/css/custom.min.css') }}" rel="stylesheet" type="text/css" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('title', 'Back-end users - All list')
@section('pageBodyClass','page-container-bg-solid page-header-fixed page-sidebar-closed-hide-logo')

@section('pageContentBody')
    <div class="page-content">
        <!-- BEGIN PAGE HEAD-->
        <div class="page-head">
            <!-- BEGIN PAGE TITLE -->
            <div class="page-title">
                <h1>{!!$text_parts['title']!!}
                    <small>{!!$text_parts['subtitle']!!}</small>
                </h1>
            </div>
            <!-- END PAGE TITLE -->
        </div>
        <!-- END PAGE HEAD-->
        <!-- BEGIN PAGE BASE CONTENT -->
        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                <div class="portlet light bordered">
                    <div class="portlet-body">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="icon-settings font-dark"></i>
                                    <span class="caption-subject font-dark sbold uppercase">Register new client</span>
                                </div>
                            </div>
                            <div class="portlet-body form">
                                <form class="register-form portlet light " method="post" name="user_registration_form" id="user_registration_form">
                                    <div class="alert alert-danger display-hide">
                                        <button class="close" data-close="alert"></button> You have some errors in the form. Please check below. </div>
                                    <div class="alert alert-success display-hide">
                                        <button class="close" data-close="alert"></button> Information is valid, please wait! </div>
                                    <p class="hint"> Enter personal details below: </p>
                                    <div class="form-group">
                                        <label class="control-label visible-ie8 visible-ie9">First Name</label>
                                        <input class="form-control placeholder-no-fix" type="text" placeholder="First Name" name="firstname" /> </div>
                                    <div class="form-group">
                                        <label class="control-label visible-ie8 visible-ie9">Middle Name</label>
                                        <input class="form-control placeholder-no-fix" type="text" placeholder="Middle Name" name="middlename" /> </div>
                                    <div class="form-group">
                                        <label class="control-label visible-ie8 visible-ie9">Last Name</label>
                                        <input class="form-control placeholder-no-fix" type="text" placeholder="Last Name" name="lastname" /> </div>
                                    <div class="form-group">
                                        <label class="control-label visible-ie8 visible-ie9">Phone Number</label>
                                        <input class="form-control placeholder-no-fix" type="text" placeholder="Phone Number" name="phone" /> </div>

                                    <p class="hint"> Enter account details below: </p>
                                    <div class="form-group">
                                        <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                                        <label class="control-label visible-ie8 visible-ie9">Email</label>
                                        <input class="form-control placeholder-no-fix" type="text" placeholder="Email" name="reg_email" /> </div>
                                    <div class="form-group">
                                        <label class="control-label visible-ie8 visible-ie9">Password</label>
                                        <input class="form-control placeholder-no-fix" type="password" autocomplete="off" id="register_password" placeholder="Password" name="password" /> </div>
                                    <div class="form-group">
                                        <label class="control-label visible-ie8 visible-ie9">Re-type Your Password</label>
                                        <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="Re-type Your Password" name="rpassword" /> </div>

                                    @if (sizeof($memberships)>0)
                                    <p class="hint"> Select membership plan (if needed): </p>
                                    <div class="form-group">
                                        <select class="form-control" name="membership_plan">
                                            <option value="1" selected="selected">No membership plan</option>
                                            @foreach($memberships as $plan)
                                            <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @endif

                                    <div class="form-actions">
                                        <button type="submit" class="btn red uppercase pull-right">Register New</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END EXAMPLE TABLE PORTLET-->
            </div>
        </div>
        <!-- END PAGE BASE CONTENT -->
    </div>
@endsection

@section('pageBelowLevelPlugins')
    <script src="{{ asset('assets/global/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/jquery-validation/js/additional-methods.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/bootstrap-confirmation-2-2/bootstrap-confirmation.min.js') }}" type="text/javascript"></script>
@endsection

@section('pageBelowLevelScripts')
    <script src="{{ asset('assets/global/plugins/jquery-notific8/jquery.notific8.min.js') }}" type="text/javascript"></script>
@endsection

@section('themeBelowLayoutScripts')
    <script src="{{ asset('assets/layouts/layout4/scripts/layout.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/layouts/layout4/scripts/demo.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/layouts/global/scripts/quick-sidebar.min.js') }}" type="text/javascript"></script>
@endsection

@section('pageCustomJScripts')
    <script type="text/javascript">
        $.validator.addMethod("datePickerDate",function(value, element) {
            // put your own logic here, this is just a (crappy) example
            return value.match(/^\d\d?-\d\d?-\d\d\d\d$/);
        },"Please enter a date in the format dd/mm/yyyy.");
        $.validator.addMethod('filesize',function(value, element, param) {
            // param = size (in bytes)
            // element = element to validate (<input>)
            // value = value of the element (file name)
            return this.optional(element) || (element.files[0].size <= param);
        },"File must be JPG, GIF or PNG, less than 1MB");
        $.validator.addMethod("validate_email",function(value, element) {
            if(/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test( value )) {
                return true;
            }
            else {
                return false;
            }
        },"Please enter a valid Email.");

        var FormValidation = function () {
            // validation using icons
            var handleValidation2 = function() {
                var form2 = $('#user_registration_form');
                var error2 = $('.alert-danger', form2);
                var success2 = $('.alert-success', form2);

                form2.validate({
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block help-block-error', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    ignore: "",  // validate all fields including form hidden input
                    rules: {
                        firstname: {
                            minlength: 2,
                            maxlength: 150,
                            required: true
                        },
                        lastname: {
                            minlength: 2,
                            maxlength: 150,
                            required: true
                        },
                        phone: {
                            number: true,
                            required: true,
                            remote: {
                                url: "{{ route('ajax/check_phone_for_member_registration') }}",
                                type: "post",
                                data: {
                                    phone: function() {
                                        return $( "input[name='phone']" ).val();
                                    }
                                }
                            }
                        },
                        reg_email: {
                            email: true,
                            validate_email: true,
                            required: true,
                            remote: {
                                url: "{{ route('ajax/check_email_for_member_registration') }}",
                                type: "post",
                                data: {
                                    email: function() {
                                        return $( "input[name='reg_email']" ).val();
                                    }
                                }
                            }
                        },
                        password: {
                            minlength: 5,
                            maxlength: 150,
                        },
                        rpassword: {
                            minlength: 5,
                            maxlength: 150,
                            equalTo:"#register_password"
                        },
                    },

                    messages: { // custom messages for radio buttons and checkboxes
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
                        register_member(); // submit the form
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

        function register_member(){
            $.ajax({
                url: '{{route('ajax/register_new_member')}}',
                type: "post",
                cache: false,
                data: {
                    'first_name': $('input[name="firstname"]').val(),
                    'middle_name': $('input[name="middlename"]').val(),
                    'last_name': $('input[name="lastname"]').val(),
                    'email': $('input[name="reg_email"]').val(),
                    'phone_number': $('input[name="phone"]').val(),
                    'password': $('input[name="password"]').val(),
                    'membership_plan': $('select[name="membership_plan"]').val()
                },
                success: function (data) {
                    if (data.success) {
                        show_notification('New user registered', 'The details entered were correct so the user is now registered.', 'lime', 3500, 0);
                        setTimeout(function(){
                            window.location.reload(true);
                        },2500);
                    }
                    else{
                        show_notification('User registration ERROR', 'Something went wrong with the registration. Try changing the email/phone number or try reloading the page', 'tangerine', 3500, 0);
                    }
                }
            });
        }

        $(document).ready(function(){
            FormValidation.init();
        });
    </script>
@endsection