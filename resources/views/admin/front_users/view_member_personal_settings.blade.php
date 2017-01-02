@extends('admin.layouts.main')

@section('pageLevelPlugins')
    <link href="{{ asset('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('themeGlobalStyle')
    <link href="{{ asset('assets/global/css/components-rounded.min.css') }}" rel="stylesheet" id="style_components" type="text/css" />
    <link href="{{ asset('assets/global/css/plugins.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/jquery-notific8/jquery.notific8.min.css') }}" rel="stylesheet" type="text/css" />
    @endsection

    @section('themeLayoutStyle')
            <!-- BEGIN PAGE LEVEL STYLES -->
    <link href="{{ asset('assets/pages/css/profile.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL STYLES -->
    <link href="{{ asset('assets/layouts/layout4/css/layout.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/layouts/layout4/css/themes/light.min.css') }}" rel="stylesheet" type="text/css" id="style_color" />
    <link href="{{ asset('assets/layouts/layout4/css/custom.min.css') }}" rel="stylesheet" type="text/css" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('title', 'Back-end users - User Details')
@section('pageBodyClass','page-container-bg-solid page-header-fixed page-sidebar-closed-hide-logo')

@section('pageContentBody')
    <div class="page-content fix_padding_top_0">
        <!-- BEGIN PAGE BASE CONTENT -->
        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN PROFILE SIDEBAR -->
                <div class="profile-sidebar">
                    <!-- PORTLET MAIN -->
                    <div class="portlet light profile-sidebar-portlet bordered">
                        <!-- SIDEBAR USERPIC -->
                        <div class="profile-userpic">
                            <img src="{{ $avatar }}" class="img-responsive" alt="" />
                        </div>
                        <!-- END SIDEBAR USERPIC -->
                        <!-- SIDEBAR USER TITLE -->
                        <div class="profile-usertitle">
                            <div class="profile-usertitle-name"> {{$user->first_name.' '.$user->middle_name.' '.$user->last_name}} </div>
                            <div class="profile-usertitle-job"> {{ $user->membership_status() }} </div>
                        </div>
                        <!-- END SIDEBAR USER TITLE -->
                        <!-- SIDEBAR BUTTONS -->
                        <div class="profile-userbuttons">
                            <button type="button" class="btn btn-circle yellow-mint btn-sm member_send_message">Add Message</button>
                            <button type="button" class="btn btn-circle btn-sm member_suspend_access {{ $user->status=='active'?'red':'green-jungle' }}">{{ $user->status=='active'?'Suspend ':'Reactivate ' }} Member</button>
                        </div>
                        <!-- END SIDEBAR BUTTONS -->
                        <!-- SIDEBAR MENU -->
                        <div class="profile-usermenu">
                            <ul class="nav">
                                <li>
                                    <a href="{{route("admin/front_users/view_user", $user->id)}}">
                                        <i class="icon-home"></i> Overview </a>
                                </li>
                                <li class="active">
                                    <a href="{{route("admin/front_users/view_personal_settings", $user->id)}}">
                                        <i class="icon-settings"></i> Personal Settings </a>
                                </li>
                                <li>
                                    <a href="{{route("admin/front_users/view_account_settings", $user->id)}}">
                                        <i class="icon-settings"></i> Account Settings </a>
                                </li>
                                <li>
                                    <a href="{{route("admin/front_users/view_bookings", $user->id)}}">
                                        <i class="fa fa-calendar"></i> Bookings </a>
                                </li>
                                <li>
                                    <a href="{{route("admin/front_users/view_finance", $user->id)}}">
                                        <i class="fa fa-money"></i> Finance </a>
                                </li>
                            </ul>
                        </div>
                        <!-- END MENU -->
                    </div>
                    <!-- END PORTLET MAIN -->
                </div>
                <!-- END BEGIN PROFILE SIDEBAR -->
                <!-- BEGIN PROFILE CONTENT -->
                <div class="profile-content">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="portlet light bordered">
                                <div class="portlet-title tabbable-line">
                                    <div class="caption caption-md">
                                        <i class="icon-globe theme-font hide"></i>
                                        <span class="caption-subject font-blue-madison bold uppercase">Profile Account</span>
                                    </div>
                                    <ul class="nav nav-tabs">
                                        <li class="active">
                                            <a href="#tab_1_1" data-toggle="tab">General Info</a>
                                        </li>
                                        <li>
                                            <a href="#tab_1_4" data-toggle="tab">Address</a>
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
                                        <div class="tab-pane active row" id="tab_1_1">
                                            <form role="form" id="form_acc_personal" action="#">
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
                                                        <option value>Select Gender</option>
                                                        <option {!! $user->gender=='F'?'selected="selected"':'' !!} value="F"> Female </option>
                                                        <option {!! $user->gender=='M'?'selected="selected"':'' !!} value="M"> Male </option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Citizenship</label>
                                                    <select name="personalCountry" id="personalCountry" class="form-control">
                                                        @foreach ($countries as $country)
                                                            <option value="{{ $country->id }}" {!! ($country->id==$user->country_id ? ' selected="selected" ' : '') !!}>{{ $country->citizenship }}</option>
                                                        @endforeach
                                                    </select></div>
                                                <div class="form-group">
                                                    <label class="control-label">Date of Birth</label>
                                                    <div class="control-label">
                                                        <div class="input-group input-medium date date-picker" data-date="{{ @$personal->dob_format }}" data-date-format="dd-mm-yyyy" data-date-viewmode="years">
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
                                                    <label class="control-label">Personal Email</label>
                                                    <input type="text" name="personalEmail" id="personalEmail" placeholder="Personal Email Address" class="form-control" value="{{@$personal->personal_email}}" /> </div>
                                                <div class="form-group">
                                                    <label class="control-label">Mobile Phone Number</label>
                                                    <input type="text" name="personalPhone" id="personalPhone" placeholder="+1 234 567 8910 (6284)" class="form-control" value="{{@$personal->mobile_number}}" /> </div>
                                                <div class="form-group">
                                                    <label class="control-label">About user information</label>
                                                    <textarea class="form-control" rows="3" placeholder="About Me!!!" id="personalAbout" name="personalAbout">{{@$personal->about_info}}</textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Contract Signed Location</label>
                                                    <input type="text" placeholder="No signed location defined" class="form-control" value="{{@$user->get_signed_location_name()}}" readonly disabled /> </div>
                                                <div class="form-group">
                                                    <label class="control-label">Preferred Location</label>
                                                    <input type="text" placeholder="No signed location defined" class="form-control" value="{{@$user->get_preferred_location_name()}}" readonly disabled /> </div>
                                                <div class="margiv-top-10">
                                                    <a href="javascript:;" onclick="javascript: $('#form_acc_personal').submit();" class="btn green"> Update Details </a>
                                                    <a href="javascript:;" class="btn default"> Cancel </a>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- END PERSONAL INFO TAB -->
                                        <!-- CHANGE AVATAR TAB -->
                                        <div class="tab-pane" id="tab_1_2">
                                            <form action="{{ route('admin/front_users/view_user/avatar_image', ['id'=>$user->id]) }}" id="user_picture_upload1" class="form-horizontal" method="post" enctype="multipart/form-data">
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
                                                                    <img src="http://www.placehold.it/200x246/EFEFEF/AAAAAA&amp;text=no+image" alt="" /> </div>
                                                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 240px;">
                                                                    @if ( strlen($avatar)>10 )
                                                                        <img src="{{ $avatar }}" />
                                                                    @endif
                                                                </div>
                                                                <div>
                                                                    <span class="btn default btn-file">
                                                                        <span class="fileinput-new"> Select image </span>
                                                                        <span class="fileinput-exists"> Change </span>
                                                                        <input type="file" name="user_avatar" class="user_avatar_select_btn1" /> </span>
                                                                    <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> Remove </a>
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

                                            <div class="portlet light bordered">
                                                <div class="portlet-title">
                                                    <div class="caption">
                                                        <i class="icon-edit font-dark"></i>
                                                        <span class="caption-subject font-dark bold uppercase">Avatar Archive</span>
                                                    </div>
                                                </div>
                                                <div class="portlet-body">
                                                    <div class="row">
                                                        @foreach ($old_avatars as $old_avatar)
                                                            <div class="col-md-3" style="text-align: center;">
                                                                <img src="data:{{ $old_avatar['type'] }};base64,{{ base64_encode($old_avatar['data']) }}" style="max-width:100%; max-height:200px;" />
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
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
                                        <!-- DOCUMENTS TAB -->
                                        <div class="tab-pane" id="tab_1_4">
                                            <form action="#" name="form_personal_address" id="form_personal_address">
                                                <div class="alert alert-danger display-hide">
                                                    <button class="close" data-close="alert"></button> You have some form errors. Please check below. </div>
                                                <div class="alert alert-success display-hide">
                                                    <button class="close" data-close="alert"></button> Your form validation is successful! </div>
                                                <div class="form-group">
                                                    <label class="control-label">Address Line1</label>
                                                    <input type="text" name="personal_addr1" id="personal_addr1" placeholder="Address1" value="{{ @$personalAddress->address1 }}" class="form-control" /> </div>
                                                <div class="form-group">
                                                    <label class="control-label">Address Line2</label>
                                                    <input type="text" name="personal_addr2" id="personal_addr2" placeholder="Address2" value="{{ @$personalAddress->address2 }}" class="form-control" /> </div>
                                                <div class="form-group">
                                                    <label class="control-label">City</label>
                                                    <input type="text" name="personal_addr_city" id="personal_addr_city" placeholder="City" value="{{ @$personalAddress->city }}" class="form-control" /> </div>
                                                <div class="form-group">
                                                    <label class="control-label">Region</label>
                                                    <input type="text" name="personal_addr_region" id="personal_addr_region" placeholder="Region" value="{{ @$personalAddress->region }}" class="form-control" /> </div>
                                                <div class="form-group">
                                                    <label class="control-label">Postal Code</label>
                                                    <input type="text" name="personal_addr_pcode" id="personal_addr_pcode" placeholder="Postal Code" value="{{ @$personalAddress->postal_code }}" class="form-control" /> </div>
                                                <div class="form-group">
                                                    <label class="control-label">Country</label>
                                                    <select class="form-control" name="personal_addr_country" id="personal_addr_country">
                                                        @foreach ($countries as $country)
                                                            <option value="{{ $country->id }}" {!! @$personalAddress->country_id==$country->id?'selected':'' !!}>{{ $country->name }}</option>
                                                        @endforeach
                                                    </select></div>
                                                <div class="margiv-top-10">
                                                    <a href="javascript:;" class="btn green" onclick="javascript: $('#form_personal_address').submit();"> Save Changes </a>
                                                    <a href="javascript:;" class="btn default"> Cancel </a>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- END DOCUMENTS TAB -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- BEGIN General Message modal window -->
                <div class="modal fade" id="general_message_box" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                <h4 class="modal-title"> Send a message to this member or about this member </h4>
                            </div>
                            <div class="modal-body form-horizontal">
                                <div class="form-body">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label"> Public Message<br /><small>visible by members</small></label>
                                        <div class="col-md-8">
                                            <textarea type="text" class="form-control input-inline input-large input-sm" name="custom_general_message"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label"> Internal Message<br /><small>visible by employees only</small> </label>
                                        <div class="col-md-8">
                                            <textarea type="text" class="form-control input-inline input-large input-sm" name="private_general_message"></textarea><br />
                                            <input value="1" name="private_general_action" type="checkbox"><small>check if this note will require future action </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn green btn_modify_booking" onclick="javascript:send_member_general_message();">Send Message</button>
                                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Return</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <!-- END General Message modal window -->
                <!-- BEGIN Status Change modal window -->
                <div class="modal fade" id="change_member_status" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form role="form" id="form_account_change_status" action="#">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                    <h4 class="modal-title"> {{ $user->status=='active'?'Suspend ':'Reactivate ' }} member </h4>
                                </div>
                                <div class="modal-body form-horizontal">
                                    <div class="alert alert-danger display-hide">
                                        <button class="close" data-close="alert"></button> Please add a short message about your action - more than 15 characters. </div>
                                    <div class="alert alert-success display-hide">
                                        <button class="close" data-close="alert"></button> Your form validation is successful! </div>
                                    <div class="note note-info" style="margin-bottom:0px;">
                                        <p> Current status : <span style="text-transform: uppercase; font-weight:bold;">{{ $user->status }}</span> . In order to change user status you have to provide a reason / note to this action. </p>
                                        <div class="form-group" style="margin:0px -15px 0px 0px;">
                                            <label class="control-label"> Internal Message <small>visible by employees</small> </label>
                                            <textarea class="form-control input-sm" name="custom_status_change_message"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" onclick="javascript: $('#form_account_change_status').submit();" class="btn green btn_modify_booking">{{ $user->status=='active'?'Suspend User':'Reactivate User' }}</button>
                                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Return</button>
                                </div>
                            </form>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <!-- END Status Change modal window -->
            </div>
            <!-- END PROFILE CONTENT -->
        </div>
        <!-- END PAGE BASE CONTENT -->
    </div>
@endsection

@section('pageBelowLevelPlugins')
    <script src="{{ asset('assets/global/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/jquery-validation/js/additional-methods.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/jquery.sparkline.min.js') }}" type="text/javascript"></script>
@endsection

@section('pageBelowLevelScripts')
    <script src="{{ asset('assets/global/plugins/jquery-notific8/jquery.notific8.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/jquery.blockui.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/pages/scripts/profile.min.js') }}" type="text/javascript"></script>
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
                            validate_email: true
                        },
                        personalPhone: {
                            required: true,
                            digits: true,
                            minlength:4,
                            maxlength:12
                        },
                        gender: {
                            required: true,
                        }
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

                    submitHandler: function (form) {
                        success1.show();
                        error1.hide();
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
                            validate_email: true
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

            var handleValidationAccChange = function() {
                var form1 = $('#form_account_change_status');
                var error1 = $('.alert-danger', form1);
                var success1 = $('.alert-success', form1);

                form1.validate({
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block help-block-error', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    ignore: "",  // validate all fields including form hidden input
                    rules: {
                        custom_status_change_message: {
                            minlength: 15,
                            required: true
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

                    submitHandler: function (form) {
                        success1.show();
                        error1.hide();
                        change_member_status(); // submit the form
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
                    handleValidationAccChange();
                }
            };
        }();

        var ComponentsDateTimePickers = function () {

            var handleDatePickers = function () {

                if (jQuery().datepicker) {
                    $('.date-picker').datepicker({
                        rtl: App.isRTL(),
                        orientation: "left",
                        autoclose: true,
                        daysOfWeekHighlighted: "0",
                        weekStart:1,
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

        $(document).ready(function(){
            FormValidation.init();
            ComponentsDateTimePickers.init();
        });

        /* Done */
        function store_account_personal(){
            $.ajax({
                url: '{{route('admin/front_users/view_user/personal_info', ['id'=>$user->id])}}',
                type: "post",
                data: {
                    'first_name':       $('input[name=personalFirstName]').val(),
                    'middle_name':      $('input[name=personalMiddleName]').val(),
                    'last_name':        $('input[name=personalLastName]').val(),
                    'gender':           $('select[name=gender]').val(),
                    'date_of_birth':    $('input[name=personalDOB]').val(),
                    'personal_email':   $('input[name=personalEmail]').val(),
                    'mobile_number':    $('input[name=personalPhone]').val(),
                    'about_info':       $('textarea[name=personalAbout]').val(),
                    'country_id':       $('select[name=personalCountry]').val(),
                },
                success: function(data){
                    if (data.success) {
                        show_notification(data.title, data.message, 'lime', 3500, 0);
                    }
                    else{
                        show_notification(data.title, data.errors, 'tangerine', 3500, 0);
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

        $(".user_avatar_select_btn1").on("click", function(){
            App.blockUI({
                target: '#user_picture_upload1',
                boxed: true
            });
        });

        $(".user_avatar_select_btn1").on("change", function(){
            App.unblockUI('#user_picture_upload1');
        });

        $(".user_avatar_select_btn2").on("click", function(){
            App.blockUI({
                target: '#user_picture_upload2',
                boxed: true
            });
        });

        $(".user_avatar_select_btn2").on("change", function(){
            App.unblockUI('#user_picture_upload2');
        });

        function update_personal_address(){
            $.ajax({
                url: '{{route('admin/front_users/view_user/personal_address', ['id'=>$user->id])}}',
                type: "post",
                data: {
                    'address1':     $('input[name=personal_addr1]').val(),
                    'address2':     $('input[name=personal_addr2]').val(),
                    'city':         $('input[name=personal_addr_city]').val(),
                    'region':       $('input[name=personal_addr_region]').val(),
                    'postal_code':  $('input[name=personal_addr_pcode]').val(),
                    'country_id':   $('select[name=personal_addr_country]').val(),
                },
                success: function(data){
                    if (data.success) {
                        show_notification(data.title, data.message, 'lime', 3500, 0);
                        setTimeout(function(){
                            location.reload();
                        },2000);
                    }
                    else{
                        show_notification(data.title, data.errors, 'tangerine', 3500, 0);
                    }
                }
            });
        }

        /* Start general - send message */
        $(".member_send_message").on("click", function(){
            $('#general_message_box').modal('show');
        });

        function send_member_general_message(){
            if ($('input[name=private_general_action]').is(':checked')){
                var pending_action = 1;
            }
            else{
                var pending_action = 0;
            }

            $.ajax({
                url: '{{route('ajax/general_note_add_new')}}',
                type: "post",
                cache: false,
                data: {
                    'title_message':    'General Note',
                    'memberID':         '{{ $user->id }}',
                    'custom_message':   $('textarea[name="custom_general_message"]').val(),
                    'private_message':  $('textarea[name="private_general_message"]').val(),
                    'note_action':      pending_action,
                },
                success: function (data) {
                    if (data.success) {
                        show_notification(data.title, data.message, 'lemon', 3500, 0);
                        setTimeout(function(){
                            location.reload();
                        }, 1000);
                    }
                    else{
                        show_notification(data.title, data.errors, 'ruby', 3500, 0);
                    }

                    $('#general_message_box').modal('hide');
                }
            });
        }
        /* Stop general - send message */

        /* Start general - suspend user access */
        $(".member_suspend_access").on("click", function(){
            $('#change_member_status').modal('show');
        });

        function change_member_status(){
            $.ajax({
                url: '{{route('ajax/front_member_change_status')}}',
                type: "post",
                cache: false,
                data: {
                    'memberID':         '{{ $user->id }}',
                    'custom_message':   $('textarea[name="custom_status_change_message"]').val(),
                },
                success: function (data) {
                    if (data.success) {
                        $('#change_member_status').modal('hide');
                        show_notification(data.title, data.message, 'lemon', 3500, 0);
                        location.reload();
                    }
                    else{
                        show_notification(data.title, data.errors, 'ruby', 3500, 0);
                    }
                }
            });
        }
        /* Stop general - suspend user access */
    </script>
@endsection
