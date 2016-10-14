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
                            <button type="button" class="btn btn-circle green btn-sm">Follow</button>
                            <button type="button" class="btn btn-circle red btn-sm">Message</button>
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
                            <div class="modal-body form-horizontal" id="book_main_details_container">
                                <div class="form-body">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label"> Date/Time</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control input-sm" name="book_date_time" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label"> Location</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control input-sm" name="book_location" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label"> Room</label>
                                        <div class="col-md-9">
                                            <input class="form-control input-sm" name="book_room" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label"> Activity</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control input-sm" name="book_activity" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label"> Player </label>
                                        <div class="col-md-9">
                                            <select class="form-control input-sm" name="book_player"></select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label"> Financial </label>
                                        <div class="col-md-9">
                                            <input class="form-control input-sm" name="book_finance" readonly />
                                            <!--<select class="form-control input-inline input-large" name="book_finance"></select>-->
                                        </div>
                                    </div>
                                    <div class="form-group" id="all_booking_notes" style="display:none; margin-bottom:0px;">
                                        <label class="col-md-3 text-right"> Notes </label>
                                        <div class="col-md-9"> </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
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
                                        <label class="col-md-4 control-label"> Booking Note<br /><small>for external use</small></label>
                                        <div class="col-md-8">
                                            <select class="form-control input-inline input-large" name="default_player_messages">
                                                <option value="">Select default message</option>
                                                <option value="">First time not show</option>
                                                <option value="">Second time not show</option>
                                                <option value="">Three and up times not show</option>
                                            </select>
                                            <h5 class="font-blue-steel"> or send Custom Message</h5>
                                            <textarea type="text" class="form-control input-inline input-large" name="custom_player_message"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label"> Internal Note<br /><small>for internal use</small> </label>
                                        <div class="col-md-8">
                                            <textarea type="text" class="form-control input-inline input-large" name="private_player_message"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn green btn_no_show" style="display:none;" onclick="javascript:not_show_invoice();">Invoice Customer</button>
                                <button type="button" class="btn green btn_modify_booking" style="display:none;" onclick="javascript:not_show_warning();">Send Warning</button>
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
                <!-- END PROFILE CONTENT -->
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

            return {
                //main function to initiate the module
                init: function () {
                    handleValidation1();
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
                    $('input[name="book_date_time"]').val(data.bookingDate + ', ' + data.timeStart + ' - ' + data.timeStop);
                    $('input[name="book_location"]').val(data.location);
                    $('input[name="book_room"]').val(data.room);
                    $('input[name="book_activity"]').val(data.category);
                    var players_dropd = $('select[name="book_player"]');
                    players_dropd.removeAttr('disabled');
                    players_dropd.removeAttr('readonly');

                    get_players_list(players_dropd, data.forUserName, data.forUserID, key);

                    if (data.paymentType=='cash'){
                        var book_finance = '<option value="cash" selected="selected">' + data.financialDetails + '</option>' +
                                '<option value="membership">Membership</option>';
                    }
                    else{
                        var book_finance = '<option value="cash"> Payment of ' + data.paymentAmount + ' </option>' +
                                '<option value="membership" selected="selected">Membership</option>';
                    }
                    //$('select[name="book_finance"]').html(book_finance);
                    $('input[name="book_finance"]').val(data.financialDetails);

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
                        players_dropd.attr('disabled','disabled');
                        players_dropd.attr('readonly','readonly');
                        $('.btn_modify_booking').hide();
                    }
                    if (data.canNoShow=="1"){
                        $('.btn_no_show').show();
                        //players_dropd.attr('disabled','disabled');
                        //players_dropd.attr('readonly','readonly');
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
                    'default_message': $('select[name="default_player_messages"]  :selected').val(),
                    'custom_message': $('textarea[name="custom_player_message"]').val(),
                    'private_message': $('textarea[name="private_player_message"]').val()
                },
                success: function (data) {
                    show_notification(data.message_title, data.message_body, 'lemon', 3500, 0);
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
    </script>
@endsection