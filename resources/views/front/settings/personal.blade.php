@extends('layouts.main')

@section('pageLevelPlugins')
    <link href="{{ asset('assets/global/plugins/jquery-notific8/jquery.notific8.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/dropzone/basic.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('themeGlobalStyle')
    <link href="{{ asset('assets/global/css/components-rounded.min.css') }}" rel="stylesheet" id="style_components" type="text/css" />
    <link href="{{ asset('assets/global/css/plugins.min.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('assets/pages/css/profile.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/layouts/layout/css/custom.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('themeLayoutStyle')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('title', 'Main Page')
@section('pageBodyClass','page-container-bg-solid page-boxed')

@section('pageContentBody')
    <div class="page-content-wrapper">
        <!-- BEGIN CONTENT BODY -->
        <!-- BEGIN PAGE CONTENT BODY -->
        <div class="page-content">
            <div class="container">
                <!-- BEGIN PAGE CONTENT INNER -->
                <div class="page-content-inner">
                    <div class="row">
                        <div class="col-md-12">
                            <!-- BEGIN PROFILE SIDEBAR -->
                            <div class="profile-sidebar">
                                <!-- PORTLET MAIN -->
                                <div class="portlet light profile-sidebar-portlet ">
                                    <!-- SIDEBAR USERPIC -->
                                    <div class="profile-userpic" style="background-image: url('{{ $avatar }}'); ">
                                       <!-- <img src="{{ $avatar }}" class="img-responsive" alt="" /> -->
                                    </div>
                                    <!-- END SIDEBAR USERPIC -->
                                    <!-- SIDEBAR USER TITLE -->
                                    <div class="profile-usertitle padding-tb-20">
                                        <div class="profile-usertitle-name"> {{ $user->first_name.' '.$user->middle_name.' '.$user->last_name }} </div>
                                        <div class="profile-usertitle-job"> {{$membershipName}} </div>
                                    </div>
                                    <!-- END SIDEBAR USER TITLE -->
                                </div>
                                <!-- END PORTLET MAIN -->
                                <!-- PORTLET MAIN -->
                                <div class="portlet light ">
                                    <!-- STAT -->
                                    <div class="row list-separated profile-stat">
                                        <div class="col-md-4 col-sm-4 col-xs-6">
                                            <div class="uppercase profile-stat-title"> {{ $countOldBookings }} </div>
                                            <div class="uppercase profile-stat-text"> Old Bookings </div>
                                        </div>
                                        <div class="col-md-4 col-sm-4 col-xs-6">
                                            <div class="uppercase profile-stat-title"> {{$countCancelledBookings}} </div>
                                            <div class="uppercase profile-stat-text"> Cancelled Bookings </div>
                                        </div>
                                        <div class="col-md-4 col-sm-4 col-xs-6">
                                            <div class="uppercase profile-stat-title"> {{$countActiveBookings}} </div>
                                            <div class="uppercase profile-stat-text"> Active Bookings </div>
                                        </div>
                                    </div>
                                    <!-- END STAT -->
                                </div>
                                <!-- END PORTLET MAIN -->
                            </div>
                            <!-- END BEGIN PROFILE SIDEBAR -->
                            <!-- BEGIN PROFILE CONTENT -->
                            <div class="profile-content">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="portlet light ">
                                            <div class="portlet-title tabbable-line">
                                                <div class="caption caption-md">
                                                    <i class="icon-globe theme-font hide"></i>
                                                    <span class="caption-subject font-blue-madison bold uppercase">Profile Account</span>
                                                </div>
                                                <ul class="nav nav-tabs">
                                                    <li class="active">
                                                        <a href="#tab_1_1" data-toggle="tab">Personal Info</a>
                                                    </li>
                                                    <li>
                                                        <a href="#tab_1_2" data-toggle="tab">Change Avatar</a>
                                                    </li>
                                                    <li>
                                                        <a href="#tab_1_3" data-toggle="tab">Change Password</a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="portlet-body">
                                                <div class="tab-content">
                                                    <!-- PERSONAL INFO TAB -->
                                                    <div class="tab-pane active" id="tab_1_1">
                                                        <form role="form" id="form_acc_personal" action="#">
                                                            <div id="errors_list">
                                                            </div>
                                                            <div class="alert alert-danger display-hide">
                                                                <button class="close" data-close="alert"></button> You have some form errors. Please check below. </div>
                                                            <div class="alert alert-success display-hide">
                                                                <button class="close" data-close="alert"></button> Your form validation is successful! </div>
                                                            <div class="form-group">
                                                                <label class="control-label">First Name</label>
                                                                <input type="text" name="personalFirstName" id="personalFirstName" placeholder="First Name" value="{{$user->first_name}}" class="form-control" /> </div>
                                                            <div class="form-group">
                                                                <label class="control-label">Middle Name</label>
                                                                <input type="text" name="personalMiddleName" id="personalMiddleName" placeholder="Middle Name" value="{{$user->middle_name}}" class="form-control" /> </div>
                                                            <div class="form-group">
                                                                <label class="control-label">Last Name</label>
                                                                <input type="text" name="personalLastName" id="personalLastName" placeholder="Last Name" value="{{$user->last_name}}" class="form-control" /> </div>
                                                            <div class="form-group">
                                                                <label class="control-label">Gender</label>
                                                                <select name="gender" class="form-control">
                                                                    <option>Select Gender</option>
                                                                    <option {!! $user->gender=='F'?'selected="selected"':'' !!} value="F"> Female </option>
                                                                    <option {!! $user->gender=='M'?'selected="selected"':'' !!} value="M"> Male </option>
                                                                </select></div>
                                                            <div class="form-group">
                                                                <label class="control-label">Citizenship</label>
                                                                <select name="personalCountry" id="personalCountry" class="form-control">
                                                                    @foreach ($countries as $country)
                                                                        <option value="{{ $country->id }}" {!! ($country->id==$user->country_id ? ' selected="selected" ' : '') !!}>{{ $country->name }}</option>
                                                                    @endforeach
                                                                </select></div>
                                                            <div class="form-group">
                                                                <label class="control-label">Date of Birth</label>
                                                                <div class="control-label">
                                                                    <div class="input-group input-medium date date-picker" data-date="{{ @$personal->dob_format }}" data-date-format="dd-mm-yyyy" data-date-viewmode="years" data-date-end-date="-0d">
                                                                        <input type="text" class="form-control" name="personalDOB" id="personalDOB" value="{{ @$personal->dob_format }}" readonly>
                                                                        <span class="input-group-btn">
                                                                            <button class="btn default" type="button">
                                                                                <i class="fa fa-calendar"></i>
                                                                            </button>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label">Registration Email</label>
                                                                <input type="text" name="personalEmail" id="personalEmail" placeholder="Personal Email Address" class="form-control" value="{{@$personal->personal_email}}" /> </div>
                                                            <div class="form-group">
                                                                <label class="control-label">Mobile Phone Number</label>
                                                                <input type="text" name="personalPhone" id="personalPhone" placeholder="123456789" class="form-control" value="{{@$personal->mobile_number}}" /> </div>
                                                            <div class="form-group">
                                                                <label class="control-label">Preferred Location</label>
                                                                <select name="preferredLocation" id="preferredLocation" class="form-control">
                                                                    <option> No Location </option>
                                                                    @foreach ($locations as $location)
                                                                        <option value="{{ $location->id }}" {!! ($location->id==@$user->get_general_setting('settings_preferred_location') ? ' selected="selected" ' : '') !!}>{{ $location->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="margiv-top-10">
                                                                <a href="javascript:;" onclick="javascript: $('#form_acc_personal').submit();" class="btn green"> Update Details </a>
                                                                <a href="javascript:;" class="btn default"> Cancel </a>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <!-- END PERSONAL INFO TAB -->
                                                    <!-- CHANGE AVATAR TAB -->
                                                    <div class="tab-pane" id="tab_1_2">
                                                        <form action="{{ route('settings/personal/avatar') }}" id="user_picture_upload1" class="form-horizontal" method="post" enctype="multipart/form-data">
                                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                            <div class="form-body">
                                                                <div class="alert alert-danger display-hide">
                                                                    <button class="close" data-close="alert"></button> You have some form errors. Please check below. </div>
                                                                <div class="alert alert-success display-hide">
                                                                    <button class="close" data-close="alert"></button> Your form validation is successful! </div>
                                                                <div class="form-group last">
                                                                    <label class="control-label col-md-2">Member Avatar</label>
                                                                    <div class="col-md-9">
                                                                        <div class="fileinput fileinput-{{ (strlen($avatar)>10) ? 'exists':'new' }} " data-provides="fileinput">
                                                                            <div class="fileinput-new thumbnail" style="width: 200px; height: 244px;">
                                                                                <img src="{{ asset('assets/global/img/default-notext-text.png') }}" alt="" /> </div>
                                                                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 240px;">
                                                                                <img src="{{ $avatar }}" class="img-responsive" alt="" />
                                                                            </div>
                                                                            <div>
                                                                    <span class="btn default btn-file">
                                                                        <span class="fileinput-new"> Select image </span>
                                                                        <span class="fileinput-exists"> Change </span>
                                                                        <input type="file" name="user_avatar" class="user_avatar_select_btn1" /> </span>
                                                                                <a href="javascript:;" data-toggle="modal" data-target="#confirm-remove-avatar" class="btn red fileinput-exists " data-dismiss=""> Reset to default </a>
                                                                            </div>
                                                                        </div>
                                                                        <div class="clearfix margin-top-10">
                                                                            <div class="note note-warning margin-bottom-5">
                                                                                <p> Image preview only works in IE10+, FF3.6+, Safari6.0+, Chrome6.0+ and Opera11.1+. In older browsers the filename is shown instead. </p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="clearfix margin-top-10">
                                                                            <a class="btn green" onclick="javascript: $('#user_picture_upload1').submit();" href="javascript:;"> Update avatar </a>
                                                                            <a class="btn default" href="javascript:;"> Cancel </a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <!-- END CHANGE AVATAR TAB -->
                                                    <!-- CHANGE PASSWORD TAB -->
                                                    <div class="tab-pane" id="tab_1_3">
                                                        <form action="#" id="form_password_update" role="form">
                                                            <div class="alert alert-danger display-hide">
                                                                <button class="close" data-close="alert"></button> You have some form errors. Please check below. </div>
                                                            <div class="alert alert-success display-hide">
                                                                <button class="close" data-close="alert"></button> Your form validation is successful! </div>
                                                            <div class="form-group">
                                                                <label class="control-label">Old Password</label>
                                                                <div class="input-icon">
                                                                    <i class="fa"></i>
                                                                    <input type="password" name="old_password" id="old_password" class="form-control" /> </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label">New Password</label>
                                                                <div class="input-icon">
                                                                    <i class="fa"></i>
                                                                    <input type="password" name="new_password1" id="new_password1" class="form-control" /> </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label">Re-type New Password</label>
                                                                <div class="input-icon">
                                                                    <i class="fa"></i>
                                                                    <input type="password" name="new_password2" id="new_password2" class="form-control" /> </div>
                                                            </div>
                                                            <div class="margin-top-10">
                                                                <a href="javascript:;" class="btn green" onClick="javascript: $('#form_password_update').submit();"> Change Password </a>
                                                                <a href="javascript:;" class="btn default"> Cancel </a>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <!-- END CHANGE PASSWORD TAB -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END PROFILE CONTENT -->
                        </div>
                    </div>
                </div>
                <!-- END PAGE CONTENT INNER -->
            </div>
        </div>
        <!-- END PAGE CONTENT BODY -->
        <!-- END CONTENT BODY -->
    </div>

    <div class="modal fade" id="confirm-remove-avatar">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Remove avatar</h4>
                </div>
                <div class="modal-body">
                    <p>Do you want to remove avatar?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default " data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-danger remove-avatar">Yes</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('pageBelowCorePlugins')
    <script src="{{ asset('assets/global/plugins/jquery.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/js.cookie.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/jquery.blockui.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/uniform/jquery.uniform.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}" type="text/javascript"></script>
@endsection

@section('pageBelowLevelPlugins')
    <script src="{{ asset('assets/global/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/jquery-validation/js/additional-methods.min.js') }}" type="text/javascript"></script>

    <script src="{{ asset('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/jquery.sparkline.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>

    <script src="{{ asset('assets/global/plugins/jquery-notific8/jquery.notific8.min.js') }}" type="text/javascript"></script>
@endsection

@section('themeBelowGlobalScripts')
    <script src="{{ asset('assets/global/scripts/app.min.js') }}" type="text/javascript"></script>
@endsection

@section('pageBelowLevelScripts')
    <script src="{{ asset('assets/pages/scripts/profile.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/pages/scripts/ui-notific8.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/dropzone/dropzone.min.js') }}" type="text/javascript"></script>
@endsection

@section('themeBelowLayoutScripts')
    <script src="{{ asset('assets/layouts/layout3/scripts/layout.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/layouts/global/scripts/quick-sidebar.min.js') }}" type="text/javascript"></script>
@endsection

@section('pageCustomJScripts')
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

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
            /* Personal Info */
            var handleValidation1 = function() {
                var form1 = $('#form_acc_personal');
                var error1 = $('.alert-danger', form1);
                var success1 = $('.alert-success', form1);

                form1.validate({
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block help-block-error', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    ignore: "",  // validate all fields including form hidden input
                    rules: {
                        personalFirstName: {
                            minlength: 2,
                            required: true
                        },
                        personalLastName: {
                            minlength: 2,
                            required: true
                        },
                        personalDOB: {
                            required: true,
                            datePickerDate:true
                        },
                        personalEmail: {
                            required: true,
                            email: true,
                            validate_email: true,
                        },
                        personalPhone: {
                            required: true,
                            digits: true,
                            minlength: 8,
                            maxlength: 20,
                            remote: {
                                url: "{{ route('ajax/check_phone_for_member_registration') }}",
                                type: "post",
                                data: {
                                    phone: function() {
                                        return $( "input[name='personalPhone']" ).val();
                                    }
                                }
                            }
                        },
                        gender: {
                            required: true,
                            minlength:1
                        }
                    },

                    invalidHandler: function (event, validator) { //display error alert on form submit
                        success1.hide();

                        var errors_list = "";
                        for(var i in validator.errorList)
                        {
                            errors_list  += "<div class='alert alert-danger'>";
                            errors_list  += $(validator.errorList[i].element).parent().find("label").text() + ": ";
                            errors_list  += validator.errorList[i].message + "</div>";
                        }

                        $("#errors_list").html(errors_list);
                        
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

                    submitHandler: function (form) {
                       $("#errors_list").empty();
                        success1.show();
                        store_account_personal(); // submit the form
                    }
                });
            }

            var handleValidation2 = function() {
                // for more info visit the official plugin documentation:
                // http://docs.jquery.com/Plugins/Validation
                var form2 = $('#form_acc_info');
                var error2 = $('.alert-danger', form2);
                var success2 = $('.alert-success', form2);

                form2.validate({
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block help-block-error', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    ignore: "",  // validate all fields including form hidden input
                    rules: {
                        accountUsername: {
                            minlength: 3,
                            required: true
                        },
                        accountEmail: {
                            required: true,
                            email: true,
                            validate_email:true
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
                        store_account_info(); // submit the form
                    }
                });
            }

            var handleValidation3 = function() {
                var form3 = $('#form_personal_address');
                var error3 = $('.alert-danger', form3);
                var success3 = $('.alert-success', form3);

                form3.validate({
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block help-block-error', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    ignore: "",  // validate all fields including form hidden input
                    rules: {
                        personal_addr1: {
                            minlength: 5,
                            required: true
                        },
                        personal_addr_city: {
                            minlength: 3,
                            required: true
                        },
                        personal_addr_region: {
                            minlength:2,
                            required: true
                        },
                        personal_addr_pcode: {
                            minlength: 2,
                            required: true
                        },
                    },

                    invalidHandler: function (event, validator) { //display error alert on form submit
                        success3.hide();
                        error3.show();
                        App.scrollTo(error3, -200);
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
                        success3.show();
                        error3.hide();
                        update_personal_address(); // submit the form
                    }
                });
            }

            var handleValidation4 = function() {
                var form4 = $('#form_password_update');
                var error4 = $('.alert-danger', form4);
                var success4 = $('.alert-success', form4);

                form4.validate({
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block help-block-error', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    ignore: "",  // validate all fields including form hidden input
                    rules: {
                        new_password1: {
                            minlength: 8,
                            required: true,
                        },
                        new_password2: {
                            minlength: 8,
                            required: true,
                            equalTo: '#new_password1',
                        },
                    },

                    invalidHandler: function (event, validator) { //display error alert on form submit
                        success4.hide();
                        error4.show();
                        App.scrollTo(error4, -200);
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
                        success4.show();
                        error4.hide();
                        update_passwd(); // submit the form
                    }
                });
            }

            var handleValidation5 = function() {
                var form5 = $('#user_picture_upload1');
                var error5 = $('.alert-danger', form5);
                var success5 = $('.alert-success', form5);

                form5.validate({
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block help-block-error', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    ignore: "",  // validate all fields including form hidden input
                    rules: {
                        user_avatar: {
                            required: true,
                            accept: "image/*",
                            filesize: 1048576,
                        },
                    },
                    messages: {
                        user_avatar: {
                            required: "We need your avatar before submitting the form",
                            accept: "The uploaded file must be an image",
                            filesize: "File must be JPG, GIF or PNG, less than 1MB",
                        }
                    },

                    invalidHandler: function (event, validator) { //display error alert on form submit
                        success5.hide();
                        error5.show();
                        App.scrollTo(error5, -200);
                    },

                    highlight: function (element) { // hightlight error inputs
                        $(element)
                                .closest('.form-group').addClass('has-error'); // set error class to the control group
                    },

                    unhighlight: function (element) { // revert the change done by hightlight
                        $(element)
                                .closest('.form-group').removeClass('has-error'); // set error class to the control group
                    },

                    success: function (label) {
                        label
                                .closest('.form-group').removeClass('has-error'); // set success class to the control group
                    },

                    submitHandler: function (form) {
                        success5.show();
                        error5.hide();
                        form.submit();
                    }
                });
            }

            return {
                //main function to initiate the module
                init: function () {
                    handleValidation1();
                    handleValidation2();
                    handleValidation3();
                    handleValidation4();
                    handleValidation5();
                }
            };
        }();

        var ComponentsDateTimePickers = function () {

            var handleDatePickers = function () {
                if (jQuery().datepicker) {
                    $('.date-picker').datepicker({
                        rtl: App.isRTL(),
                        orientation: "left",
                        autoclose: true
                    });
                    //$('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
                }
                /* Workaround to restrict daterange past date select: http://stackoverflow.com/questions/11933173/how-to-restrict-the-selectable-date-ranges-in-bootstrap-datepicker */
            }

            return {
                //main function to initiate the module
                init: function () {
                    handleDatePickers();
                }
            };
        }();

        var FormDropzone = function () {
            return {
                //main function to initiate the module
                init: function () {

                    Dropzone.options.myDropzone = {
                        paramName: "user_doc", // The name that will be used to transfer the file
                        maxFilesize: 20, // MB
                        acceptedFiles: "image/jpeg,image/png,application/pdf,.psd,.doc,.docx,.xls,.xlsx,.JPG",
                        dictDefaultMessage: '',
                        dictResponseError: 'Error uploading file!',
                        init: function() {
                            this.on("sending", function(file, xhr, data) {
                                data.append("_token", '{{ csrf_token() }}');
                            });
                            this.on("addedfile", function(file) {
                                // Create the remove button
                                var removeButton = Dropzone.createElement("<a href='javascript:;'' class='btn red btn-sm btn-block'>Remove</a>");

                                // Capture the Dropzone instance as closure.
                                var _this = this;

                                // Listen to the click event
                                removeButton.addEventListener("click", function(e) {
                                    // Make sure the button click doesn't submit the form:
                                    e.preventDefault();
                                    e.stopPropagation();

                                    // Remove the file preview.
                                    _this.removeFile(file);
                                    // If you want to the delete the file on the server as well,
                                    // you can do the AJAX request here.
                                });

                                // Add the button to the file preview element.
                                file.previewElement.appendChild(removeButton);
                            });
                        }
                    }
                }
            };
        }();

        $(document).ready(function(){
            ComponentsDateTimePickers.init();
            FormValidation.init();
            FormDropzone.init();

            $(".remove-avatar").click(function(){
                
                $.ajax({
                    url : "{{route('settings/personal/remove_avatar')}}",
                    type : "post",
                    success : function(response)
                    {
                        if (response.success)
                        {
                            window.location.reload();
                        }
                    }
                });
            });

        });

        /* Done */
        function store_account_personal(){
            $.ajax({
                url: '{{route('settings/personal/info')}}',
                type: "post",
                data: {
                    'first_name':       $('input[name=personalFirstName]').val(),
                    'middle_name':      $('input[name=personalMiddleName]').val(),
                    'last_name':        $('input[name=personalLastName]').val(),
                    'date_of_birth':    $('input[name=personalDOB]').val(),
                    'personal_email':   $('input[name=personalEmail]').val(),
                    'mobile_number':    $('input[name=personalPhone]').val(),
                    'country_id':       $('select[name=personalCountry]').val(),
                    'gender':           $('select[name=gender]').val(),
                    'preferred_location':$('select[name=preferredLocation]').val(),
                },
                success: function(data){
                    if (data.success) {
                        show_notification(data.title, data.message, 'lime', 3500, 0);
                        setTimeout(function(){
                            location.reload();
                        }, 1500);
                    }
                    else{
                        var msg_error = '';
                        $.each(data.errors, function(index, value){
                            $.each(value, function(index1, value1){
                                msg_error = msg_error + value1 + '<br />';
                            });
                        });
                        show_notification(data.title, msg_error, 'ruby', 3500, 0);
                    }
                }
            });
        }

        /* Done */
        function update_passwd(){
            $.ajax({
                url: '{{route('admin/front_users/view_user/password_update', ['id'=>$user->id])}}',
                type: "post",
                data: {
                    'old_password': $('input[name=old_password]').val(),
                    'password1':    $('input[name=new_password1]').val(),
                    'password2':    $('input[name=new_password2]').val(),
                },
                success: function(data){
                    if (data.success) {
                        show_notification(data.title, data.message, 'lime', 3500, 0);
                        $('#form_password_update').find('.alert').css('display','none');
                        $('#form_password_update').find('i.fa').removeClass('fa-check');

                        $('#new_password1').val('');
                        $('#new_password2').val('');
                    }
                    else{
                        show_notification(data.title, data.errors, 'tangerine', 3500, 0);
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
    </script>
@endsection