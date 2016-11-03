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
                            <button type="button" class="btn btn-circle yellow-mint btn-sm member_send_message">Send Message</button>
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
                                <li>
                                    <a href="{{route("admin/front_users/view_account_settings", $user->id)}}">
                                        <i class="icon-settings"></i> Account Settings </a>
                                </li>
                                <li class="active">
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
                                        <span class="caption-subject font-blue-madison bold uppercase">Member Bookings</span>
                                    </div>
                                    <ul class="nav nav-tabs">
                                        <li class="active">
                                            <a href="#tab_1_1" data-toggle="tab">Current Bookings</a>
                                        </li>
                                        <li>
                                            <a href="#tab_1_2" data-toggle="tab">Bookings History</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="portlet-body">
                                    <div class="tab-content">
                                        <!-- PERSONAL INFO TAB -->
                                        <div class="tab-pane active" id="tab_1_1">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <!-- BEGIN BORDERED TABLE PORTLET-->
                                                    @if (sizeof($upcomingBookings)>0)
                                                    <div class="portlet light portlet-fit bordered">
                                                        <div class="table-scrollable">
                                                            <table class="table table-bordered table-hover">
                                                                <thead>
                                                                <tr>
                                                                    <th> # </th>
                                                                    <th> Date - Time Interval </th>
                                                                    <th> Location and Room </th>
                                                                    <th class="hidden-xs"> Booked By </th>
                                                                    <th class="hidden-xs"> Player </th>
                                                                    <th class="hidden-xs"> Added On </th>
                                                                    <th class="hidden-xs"> Type </th>
                                                                    <th> Status </th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                @foreach($upcomingBookings as $key=>$booking)
                                                                    <tr>
                                                                        <td> {{ $key+1 }}</td>
                                                                        <td> <small>{{$booking['date']}} {{$booking['timeInterval']}}</small> </td>
                                                                        <td class="hidden-xs"> <small>{{$booking['location']}} {{$booking['room']}}</small> </td>
                                                                        <td class="hidden-xs"> <small>{{$booking['bookingByName']}}</small> </td>
                                                                        <td class="hidden-xs"> <a href="{{ route('admin/front_users/view_user',['id'=>$booking['player_id']])}}" target="_blank">{{$booking['player_name']}}</a> </td>
                                                                        <td class="hidden-xs"> <small>{{$booking['added_on']}}</small> </td>
                                                                        <td class="hidden-xs"> <small>{{$booking['type']}}</small> </td>
                                                                        <td> <a class="label label-sm {{$booking['status-color']}} booking_details_modal" data-key="{{$booking['search_key']}}">{{$booking['status']}}</a> </td>
                                                                    </tr>
                                                                @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    @else
                                                    <div class="note note-warning">
                                                        <h4 class="block">No upcoming bookings - For old bookings check History</h4>
                                                        <p> All your old bookings are visible in the "Bookings History" tab. </p>
                                                    </div>
                                                    @endif
                                                    <!-- END BORDERED TABLE PORTLET-->
                                                </div>
                                            </div>
                                        </div>
                                        <!-- END PERSONAL INFO TAB -->
                                        <!-- CHANGE AVATAR TAB -->
                                        <div class="tab-pane" id="tab_1_2">
                                            <div class="row">
                                                <div class="col-md-12">
                                                <!-- BEGIN SAMPLE TABLE PORTLET-->
                                                @if (sizeof($bookings)>0)
                                                    <div class="portlet">
                                                        <div class="portlet-body">
                                                            <div class="table-scrollable">
                                                                <table class="table table-striped table-bordered table-advance table-hover">
                                                                    <thead>
                                                                    <tr>
                                                                        <th>
                                                                            <i class="fa fa-briefcase"></i> Booking Date </th>
                                                                        <th>
                                                                            <i class="fa fa-briefcase"></i> Booking Time - Location - Room </th>
                                                                        <th class="hidden-xs">
                                                                            <i class="fa fa-user"></i> Player </th>
                                                                        <th>
                                                                            <i class="fa fa-briefcase"></i> Booked By </th>
                                                                        <th>
                                                                            <i class="fa fa-briefcase"></i> Added On </th>
                                                                        <th>
                                                                            <i class="fa fa-briefcase"></i> Type </th>
                                                                        <th>
                                                                            <i class="fa fa-shopping-cart"></i> Status </th>
                                                                        <th> </th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    @foreach ($bookings as $booking)
                                                                    <tr>
                                                                        <td class="highlight">
                                                                            <div class="{{ $booking['color_status'] }}"></div>
                                                                            &nbsp; <small><span href="javascript:;"> {{ $booking['dateShort'] }} </span></small>
                                                                        </td>
                                                                        <td> <small>{{ $booking['timeInterval'] }} - {{ $booking['location']}}  - {{  $booking['room'] }}</small> </td>
                                                                        <td class="hidden-xs">
                                                                            <small><a href="{{route('admin/front_users/view_user',['id'=>$booking['player_id']])}}" target="_blank">{{$booking['player_name']}}</a></small>
                                                                        </td>
                                                                        <td> <small>{{$booking['bookingByName']}}</small> </td>
                                                                        <td> <small>{{ $booking['added_on'] }}</small> </td>
                                                                        <td> <small>{{ $booking['type'] }}</small> </td>
                                                                        <td> <small>{{ $booking['status'] }}</small> </td>
                                                                        <td>
                                                                            <a class="btn {{ $booking['color_button'] }} btn-sm booking_details_modal" data-key="{{$booking['search_key']}}" href="javascript:;">
                                                                                <i class="fa fa-edit"></i> Details </a>
                                                                        </td>
                                                                    </tr>
                                                                    @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="note note-warning">
                                                        <h4 class="block">No past bookings</h4>
                                                        <p> Member has no old/passed bookings. Once he adds a new booking, it will be visible here after the booking date/time passed. </p>
                                                    </div>
                                                @endif
                                                <!-- END SAMPLE TABLE PORTLET-->
                                                </div>
                                            </div>
                                        </div>
                                        <!-- END CHANGE AVATAR TAB -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="changeIt" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                <h4 class="modal-title"> Booking Details </h4>
                            </div>
                            <div class="modal-body form-horizontal" id="book_main_details_container" style="min-height:200px;">

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn green show_rec_booking_btn" data-toggle="modal" href="#all_rec_bookings_box">Recurrent lists</button>
                                <button type="button" class="btn green btn_no_show" data-toggle="modal" href="#not_show_confirm_box" style="display:none;" >Not show up</button>
                                <button type="button" class="btn green btn_modify_booking" style="display:none;" onclick="javascript:change_booking_player();">Modify Booking</button>
                                <button type="button" class="btn green btn_cancel_booking" data-toggle="modal" href="#cancel_confirm_box" style="display:none;">Cancel Booking</button>
                                <button type="button" class="btn green btn_show_invoice" style="display:none;" onclick="javascript:show_booking_invoice();">Show Invoice</button>
                                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Return</button>
                                <input type="hidden" value="" name="search_key_selected" />
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>

                <div class="modal fade" id="not_show_confirm_box" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                <h4 class="modal-title"> Not Show Status Change </h4>
                            </div>
                            <div class="modal-body form-horizontal" id="book_main_details_container">
                                <div class="form-body">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label"> Booking Note</label>
                                        <div class="col-md-8">
                                            <textarea type="text" class="form-control input-inline input-large" name="custom_player_message" style="font-size:12px;"></textarea>
                                            <br /><small>visible by members and employees</small>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label"> Internal Note</label>
                                        <div class="col-md-8">
                                            <textarea type="text" class="form-control input-inline input-large" name="private_player_message" style="font-size:12px;"></textarea>
                                            <br /><small>visible by employees only</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn green btn_no_show_invoice" onclick="javascript:not_show_invoice();">Invoice Customer</button>
                                <button type="button" class="btn green btn_no_show_warning" onclick="javascript:not_show_warning();">Send Warning</button>
                                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Return</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>

                <div class="modal fade bs-modal-sm" id="cancel_confirm_box" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                <h4 class="modal-title">Do you want to cancel?</h4>
                            </div>
                            <div class="modal-body"> By clicking "Cancel Booking" this booking will be canceled and the player notified. Do you want to proceed with the cancellation? </div>
                            <div class="modal-footer">
                                <button type="button" class="btn dark btn-outline" data-dismiss="modal">No, Go Back</button>
                                <button type="button" class="btn green" onclick="javascript:cancel_booking();">Yes, Cancel</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>

                <!-- BEGIN Booking No Show modal window -->
                <div class="modal fade" id="all_rec_bookings_box" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" style="margin-top:45px;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                <h4 class="modal-title"> List of recurrent bookings </h4>
                            </div>
                            <div class="modal-body form-horizontal" id="recurring_details_container" style="min-height:200px;">

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn green btn_no_show" data-toggle="modal" href="#cancel_recurrent_confirm_box">Cancel Selected</button>
                                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Return</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <!-- END No Show modal window -->

                <!-- BEGIN Recurrent Cancel Confirm modal window show -->
                <div class="modal fade" id="cancel_recurrent_confirm_box" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content" style="margin-left:20px; margin-right:20px; margin-top:60px;">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                <h4 class="modal-title">Do you want to cancel?</h4>
                            </div>
                            <div class="modal-body">
                                <div class="note note-info" style="margin-bottom:0px;">
                                    <h4 class="block">Cancel selected bookings</h4>
                                    <p> By clicking "Yes, Cancel" these recurrent bookings that you selected will be canceled and the player notified. Do you want to proceed with the cancellation? </p>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn dark btn-outline" data-dismiss="modal">No, Go Back</button>
                                <button type="button" class="btn green" onclick="javascript:cancel_recurrent_booking();">Yes, Cancel</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <!-- END Recurrent Cancel Confirm modal window show -->
                <!-- END PROFILE CONTENT -->
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
                                        <label class="col-md-4 control-label"> Title / Topic </label>
                                        <div class="col-md-8">
                                            <input class="form-control input-large input-sm" name="title_general_message" placeholder="message title or topic" type="text" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label"> Public Message<br /><small>visible by members</small></label>
                                        <div class="col-md-8">
                                            <textarea type="text" class="form-control input-inline input-large input-sm" name="custom_general_message"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label"> Internal Message<br /><small>visible by employees only</small> </label>
                                        <div class="col-md-8">
                                            <textarea type="text" class="form-control input-inline input-large input-sm" name="private_general_message"></textarea>
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
                                            <label class="control-label"> Public Message <small>visible by members</small></label>
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
                            minlength: 3,
                            required: true
                        },
                        personalLastName: {
                            minlength: 3,
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
                    handleValidationAccChange();
                }
            };
        }();

        $(document).ready(function(){
            FormValidation.init();
        });

        $(document).on('click', '.booking_details_modal', function(){
            modify_booking_details($(this).attr('data-key'));
            $('#changeIt').modal('show');
        });

        // adds the information to the popup with bookings details
        function modify_booking_details(key){
            $.ajax({
                url: '{{route('ajax/get_single_booking_details')}}',
                type: "post",
                cache: false,
                data: {
                    'search_key': key,
                    'the_user': "{{ $user->id }}"
                },
                success: function (data) {
                    var allNotes = '<small>';
                    /* Get booking notes */
                    if (data.bookingNotes.length !=0){
                        $.each(data.bookingNotes, function(key, value){
                            allNotes+= '<dl style="margin-bottom:7px;"><dt class="font-grey-mint"><span>' + value.created_at + '</span> ' +
                                    'by <span> ' + value.added_by + '</span></dt>' +
                                    '<dd> <span class="font-blue-steel"> ' + value.note_title + ' </span> : ' +
                                    '<span class="font-blue-dark">' + value.note_body + '</span></dd></dl>';
                        });
                    }
                    else{
                        allNotes = 'No notes';
                    }
                    allNotes+='</small>';

                    if (data.employee_involved!=''){
                        var bookedBy = data.employee_involved;
                    }
                    else{
                        var bookedBy = data.byUserName;
                    }

                    var book_details =
                            '<div class="row margin-bottom-5"><div class="col-md-4 bg-grey-salt bg-font-grey-salt"> Booked By </div><div class="col-md-8 bg-grey-steel bg-font-grey-steel"> ' + bookedBy + ' </div></div>' +
                            '<div class="row margin-bottom-5"><div class="col-md-4 bg-grey-salt bg-font-grey-salt"> Booked On </div><div class="col-md-8 bg-grey-steel bg-font-grey-steel"> ' + data.addedOn + ' </div></div>' +
                            '<div class="row margin-bottom-5"><div class="col-md-4 bg-grey-salt bg-font-grey-salt"> Booking Date </div><div class="col-md-8 bg-grey-steel bg-font-grey-steel"> ' + data.bookingDate + ' </div></div>' +
                            '<div class="row margin-bottom-5"><div class="col-md-4 bg-grey-salt bg-font-grey-salt"> Time of booking </div><div class="col-md-8 bg-grey-steel bg-font-grey-steel"> ' + data.timeStart + ' - ' + data.timeStop + ' </div></div>' +
                            '<div class="row margin-bottom-5"><div class="col-md-4 bg-grey-salt bg-font-grey-salt"> Booking Location </div><div class="col-md-8 bg-grey-steel bg-font-grey-steel"> ' + data.location + ' - ' + data.room + ' </div></div>' +
                            '<div class="row margin-bottom-5"><div class="col-md-4 bg-grey-salt bg-font-grey-salt"> Activity </div><div class="col-md-8 bg-grey-steel bg-font-grey-steel"> ' + data.category + ' </div></div>' +
                            '<div class="row margin-bottom-5"><div class="col-md-4 bg-grey-salt bg-font-grey-salt"> Player </div><div class="col-md-8 bg-grey-steel bg-font-grey-steel"> ' + data.forUserName + ' </div></div>' +
                            '<div class="row margin-bottom-5"><div class="col-md-4 bg-grey-salt bg-font-grey-salt"> Finance </div><div class="col-md-8 bg-grey-steel bg-font-grey-steel"> ' + data.financialDetails + ' </div></div>' +
                            '<div class="row margin-bottom-5"><div class="col-md-4 bg-grey-salt bg-font-grey-salt"> Notes </div><div class="col-md-8 bg-grey-steel bg-font-grey-steel"> ' + allNotes + ' </div></div>';
                    $('#book_main_details_container').html(book_details);

                    if (data.canCancel=="1"){
                        $('.btn_cancel_booking').show();
                    }
                    else{
                        $('.btn_cancel_booking').hide();
                    }
                    if (data.canModify=="1"){
                        $('.btn_modify_booking').show();
                    }
                    else{
                        $('.btn_modify_booking').hide();
                    }
                    if (data.canNoShow=="1"){
                        $('.btn_no_show').show();
                    }
                    else{
                        $('.btn_no_show').hide();
                    }
                    if (data.invoiceLink!="0"){
                        $('.btn_show_invoice').show();
                        $('.btn_show_invoice').attr({'data-id':data.invoiceLink});
                    }
                    else{
                        $('.btn_show_invoice').hide();
                        $('.btn_show_invoice').attr({'data-id':''});
                    }

                    if (data.recurrentList==1){
                        $('.show_rec_booking_btn').show();
                        get_recurrent_list(key);
                    }
                    else{
                        $('.show_rec_booking_btn').hide();
                    }

                    $('input[name="search_key_selected"]').val(key);

                    /* Get booking notes */
                    if (data.bookingNotes.length !=0){
                        var notesPlace = $('#all_booking_notes').find('.col-md-9').first();
                        var allNotes = '<small>';
                        $.each(data.bookingNotes, function(key, value){
                            allNotes+= '<dl style="margin-bottom:7px;"><dt class="font-grey-mint"><span>' + value.created_at + '</span> ' +
                                    'by <span> ' + value.added_by + '</span></dt>' +
                                    '<dd> <span class="font-blue-steel"> ' + value.note_title + ' </span> : ' +
                                    '<span class="font-blue-dark">' + value.note_body + '</span></dd></dl>';
                        });
                        allNotes+='</small>';
                        notesPlace.html(allNotes);
                        $('#all_booking_notes').show();
                    }
                }
            });
        }

        function cancel_booking() {
            var search_key = $('input[name="search_key_selected"]').val();

            $.ajax({
                url: '{{route('ajax/cancel_booking')}}',
                type: "post",
                cache: false,
                data: {
                    'search_key': search_key
                },
                success: function (data) {
                    show_notification('Booking Canceled', 'The selected booking was canceled.', 'lemon', 3500, 0);
                    //$('#small').find('.book_details_cancel_place').html('');
                    $('#cancel_confirm_box').modal('hide');
                    $('#changeIt').modal('hide');
                }
            });
        }

        function change_booking_player(){
            var search_key = $('input[name="search_key_selected"]').val();
            var new_player = $('select[name="book_player"]').val();
            var new_player_name = $('select[name="book_player"] option:selected').text();

            $.ajax({
                url: '{{route('ajax/change_booking_player')}}',
                type: "post",
                cache: false,
                data: {
                    'search_key' : search_key,
                    'player'     : new_player
                },
                success: function (data) {
                    show_notification('Booking Updated', 'The new player : '+new_player_name+' was added to the selected booking.', 'lemon', 3500, 0);

                    //$('#changeIt').find('.book_details_cancel_place').html('');
                    $('#changeIt').modal('hide');
                }
            });
        }

        function show_invoice(){
            alert('Redirect to invoice page!');
        }

        function not_show_invoice(){
            var search_key = $('input[name="search_key_selected"]').val();
            add_note_to_booking(search_key, 1);
        }

        function not_show_warning(){
            var search_key = $('input[name="search_key_selected"]').val();
            add_note_to_booking(search_key, 0);
        }

        function add_note_to_booking(search_key, add_invoice){
            $.ajax({
                url: '{{route('ajax/booking_not_show_change_status')}}',
                type: "post",
                cache: false,
                data: {
                    'search_key': search_key,
                    'add_invoice': add_invoice,
                    //'default_message': $('select[name="default_player_messages"]  :selected').val(),
                    'custom_message': $('textarea[name="custom_player_message"]').val(),
                    'private_message': $('textarea[name="private_player_message"]').val()
                },
                success: function (data) {
                    if (data.success) {
                        show_notification(data.title, data.message, 'lemon', 3500, 0);
                    }
                    else{
                        show_notification(data.title, data.errors, 'ruby', 3500, 0);
                    }

                    $('#not_show_confirm_box').modal('hide');
                    $('#changeIt').modal('hide');
                }
            });
        }

        function get_players_list(container, player_name, player_id, key){
            App.blockUI({
                target: '#book_main_details_container',
                boxed: true,
                message: 'Processing...'
            });

            $.ajax({
                url: '{{route('ajax/get_players_list')}}',
                type: "post",
                cache: false,
                data: {
                    'resourceID': '',
                    'booking_time_start': '',
                    'booking_day': '',
                    'search_key': key,
                    'userID': '{{ $user->id }}',
                },
                success: function(data){
                    var all_list = '<option value="'+ player_id +'" selected="selected">'+ player_name +'</option>';
                    $.each(data, function(key, value){
                        if (value.id != player_id) {
                            all_list += '<option value="' + value.id + '">' + value.name + '</option>';
                        }
                    });
                    container.html(all_list);

                    App.unblockUI('#book_main_details_container');
                }
            });
        }

        function get_recurrent_list(key){
            $.ajax({
                url: '{{route('ajax/get_recurrent_bookings_list')}}',
                type: "post",
                cache: false,
                data: {
                    'search_key': key,
                },
                success: function (data) {
                    var list = '<div class="row margin-bottom-5">'+
                            '<div class="col-md-1 bg-blue-steel bg-font-blue-steel" style="min-height:26px;"><input type="checkbox" name="recc_booking_all" id="recc_booking_all" value="1" /></div>'+
                            '<div class="col-md-5 bg-blue-steel bg-font-blue-steel" style="min-height:26px;"> Booking Date & Time </div>' +
                            '<div class="col-md-6 bg-blue-steel bg-font-blue-steel" style="min-height:26px;"> Location / Room </div>' +
                            '</div>';
                    $.each(data.recurrent_list, function(key, value){
                        list += '<div class="row margin-bottom-5">';
                        if (value.status!='active'){
                            list+=  '<div class="col-md-1 bg-grey-salt bg-font-grey-salt" style="min-height:22px;"><i class="fa fa-check-square"></i></div>';
                        }
                        else{
                            list+=  '<div class="col-md-1 bg-grey-salt bg-font-grey-salt" style="min-height:22px;"><input class="sel_rec" type="checkbox" name="recc_booking[]" value="' + value.search_key + '" /></div>';
                        }
                        list +=     '<div class="col-md-5 bg-grey-steel bg-font-grey-steel" style="min-height:22px;"> ' + value.date_of_booking + ' ' + value.time_of_booking + '</div>' +
                                '<div class="col-md-6 bg-grey-steel bg-font-grey-steel" style="min-height:22px;"> ' + value.location_name + '-' + value.resource_name + ' / ' + value.status + ' </div>' +
                                '</div>';
                    });
                    $('#recurring_details_container').html(list);
                }
            });
        }

        function cancel_recurrent_booking(){
            // get keys list to send to bookings cancelation
            var all_keys = '';
            $('input:checkbox.sel_rec').each(function () {
                all_keys += (this.checked ? $(this).val()+',' : "");
            });

            $.ajax({
                url: '{{route('ajax/cancel_recurrent_booking')}}',
                type: "post",
                cache: false,
                data: {
                    'selected_bookings': all_keys,
                },
                success: function(data){
                    $('#cancel_recurrent_confirm_box').modal('hide');
                    $('#all_rec_bookings_box').modal('hide');
                    $('#changeIt').modal('hide');
                    show_notification('Bookings Canceled', 'The selected recurrent bookings were canceled.', 'lemon', 3500, 0);

                    setTimeout(function(){
                        location.reload();
                    }, 2000);
                }
            });
        }

        $('#changeIt').on('hidden.bs.modal', function () {
            $('.btn_no_show').hide();
            $('.btn_modify_booking').hide();
            $('.btn_cancel_booking').hide();
            $('.btn_show_invoice').hide();
            $('.btn_show_invoice').attr('data-id','-1');
            $('input[name="search_key_selected"]').val('');

            $('#book_main_details_container').find('input').val('');
            $('#book_main_details_container').find('select').html('');

            $('#all_booking_notes').find('.col-md-9').first().html('');
            $('#all_booking_notes').hide();
        });

        /* Start general - send message */
        $(".member_send_message").on("click", function(){
            $('#general_message_box').modal('show');
        });

        function send_member_general_message(){
            $.ajax({
                url: '{{route('ajax/general_note_add_new')}}',
                type: "post",
                cache: false,
                data: {
                    'title_message':    $('input[name=title_general_message]').val(),
                    'memberID':         '{{ $user->id }}',
                    'custom_message':   $('textarea[name="custom_general_message"]').val(),
                    'private_message':  $('textarea[name="private_general_message"]').val()
                },
                success: function (data) {
                    if (data.success) {
                        show_notification(data.title, data.message, 'lemon', 3500, 0);
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