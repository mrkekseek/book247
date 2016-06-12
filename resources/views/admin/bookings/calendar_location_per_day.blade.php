@extends('admin.layouts.main')

@section('pageLevelPlugins')
    <link href="{{ asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('themeGlobalStyle')
    <link href="{{ asset('assets/global/css/components-rounded.min.css') }}" rel="stylesheet" id="style_components" type="text/css" />
    <link href="{{ asset('assets/global/css/plugins.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/jquery-notific8/jquery.notific8.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('themeLayoutStyle')
    <link href="{{ asset('assets/pages/css/location_calendar_day_view.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('assets/layouts/layout4/css/layout.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/layouts/layout4/css/themes/light.min.css') }}" rel="stylesheet" type="text/css" id="style_color" />
    <link href="{{ asset('assets/layouts/layout4/css/custom.min.css') }}" rel="stylesheet" type="text/css" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('title', 'Back-end bookings - Calendar View per Location')
@section('pageBodyClass','page-container-bg-solid page-header-fixed page-sidebar-closed-hide-logo')

@section('pageContentBody')
    <div class="page-content">
        <!-- BEGIN PAGE HEAD-->
        <div class="page-head">
            <!-- BEGIN PAGE TITLE -->
            <div class="page-title">
                <h1>Bookings - Calendar View
                    <small>for specified day</small>
                </h1>
            </div>
            <!-- END PAGE TITLE -->
        </div>
        <!-- END PAGE HEAD-->
        <!-- BEGIN PAGE BREADCRUMB -->
        <ul class="page-breadcrumb breadcrumb">
            @foreach($breadcrumbs as $key=>$val)
                @if ($val=='')
                    <li>
                        <span class="active">{{$key}}</span>
                    </li>
                @else
                    <li>
                        <a href="{{$val}}">{{$key}}</a>
                        <i class="fa fa-circle"></i>
                    </li>
                @endif
            @endforeach
        </ul>
        <!-- END PAGE BREADCRUMB -->
        <!-- BEGIN PAGE BASE CONTENT -->

        <div class="row">
            <div class="col-md-12">
                <div class="portlet light form-fit bordered form-horizontal bg-green-haze bg-font-green-haze" style="border-radius:5px; margin-bottom:10px;">
                    <div class="portlet-title" style="padding-top:7px; padding-bottom:7px;">
                        <div class="col-md-4">
                            <div class="form-group" style="margin-bottom:0px;">
                                <label class="control-label col-md-4">Select Date</label>
                                <div class="col-md-7">
                                    <div class="input-group input-small date date-picker" data-date="{{ $header_vals['date_selected'] }}" data-date-format="dd-mm-yyyy" data-date-viewmode="years">
                                        <input type="text" id="header_date_selected" class="form-control reload_calendar_page" value="{{ \Carbon\Carbon::now()->format('d-m-Y') }}" readonly>
                                            <span class="input-group-btn">
                                                <button class="btn default" type="button">
                                                    <i class="fa fa-calendar"></i>
                                                </button>
                                            </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" style="margin-bottom:0px;">
                                <label class="control-label col-md-4">Select Location</label>
                                <div class="col-md-8">
                                    <div class="input-group input-medium">
                                        <select id="header_location_selected" class="form-control reload_calendar_page" style="border-radius:4px;">
                                            @foreach($all_locations as $a_location)
                                                <option value="{{ $a_location->id }}" {{ $a_location->id==$header_vals['selected_location']?" selected ":'' }} >{{ $a_location->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" style="margin-bottom:0px;">
                                <label class="control-label col-md-4">Select Activity</label>
                                <div class="col-md-8">
                                    <div class="input-group input-medium">
                                        <select id="header_activity_selected" class="form-control reload_calendar_page" style="border-radius:4px;">
                                            @foreach($all_activities as $an_activity)
                                                <option value="{{ $an_activity->id }}" {{ $an_activity->id==$header_vals['selected_activity']?" selected ":'' }} >{{ $an_activity->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- BEGIN SAMPLE TABLE PORTLET-->
                <div class="portlet box green">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-cogs"></i>Responsive Flip Scroll Tables </div>
                        <div class="tools ">
                            <a href="{{ route('bookings/location_calendar_day_view_all', ['day'=>$header_vals['prev_date'],'location'=>$header_vals['selected_location'],'activity'=>$header_vals['selected_activity']]) }}" class="bs-glyphicons font-white" style="margin-bottom:0px;"> <span class="glyphicon glyphicon-chevron-left"> </span> Prev </a>
                            <a href="javascript:;" class="bs-glyphicons font-white" style="margin-bottom:0px;"> <span class="glyphicon glyphicon-repeat"> </span> Reload </a>
                            <a href="{{ route('bookings/location_calendar_day_view_all', ['day'=>$header_vals['next_date'],'location'=>$header_vals['selected_location'],'activity'=>$header_vals['selected_activity']]) }}" class="bs-glyphicons font-white" style="margin-bottom:0px;"> Next <span class="glyphicon glyphicon-chevron-right"> </span> </a>
                        </div>
                    </div>
                    <div class="portlet-body flip-scroll">
                        <table class="table table-striped table-bordered table-hover" id="bookings_calendar_view_admin">
                            <thead class="flip-content">
                            <tr>
                                <th width="10%"> Time Interval </th>
                                @foreach ($resources as $resource)
                                    <th>{{ $resource['name'] }}</th>
                                @endforeach
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($time_intervals as $key=>$hour)
                            <tr>
                                <td >{{ $key }} <a class="btn btn-circle btn-icon-only border-white bg-green-meadow bg-font-green-meadow add_custom_bookings_btn" href="javascript:;"> + </a></td>
                                @foreach ($resources as $resource)
                                    <td class="{{ isset($location_bookings[$key][$resource['id']]['color_stripe'])?$location_bookings[$key][$resource['id']]['color_stripe']:$hour['color_stripe'] }}  {{ $hour['color_stripe']==''?' isfreetime':'' }}" style="padding:4px 8px;">
                                        @if ( isset($location_bookings[$key][$resource['id']]) )
                                        <a class="font-white" href="">{{ @$location_bookings[$key][$resource['id']]['player_name'] }}</a>
                                        <div class="actions" search-key="{{ $location_bookings[$key][$resource['id']]['search_key'] }}" style="float:right;">
                                            @if ($location_bookings[$key][$resource['id']]['button_show'] == 'is_disabled')
                                                <a class="btn btn-circle btn-icon-only {{ $button_color['is_disabled'] }} border-white"
                                                   style="height:30px; width:30px; padding:4px 3px 0 0; margin-right:1px; cursor:default;" href="javascript:;"
                                                   data-status="{{ $location_bookings[$key][$resource['id']]['status'] }}" >
                                                   <i class="icon-login"></i>
                                                </a>
                                            @elseif ($location_bookings[$key][$resource['id']]['button_show'] == 'is_no_show')
                                                <a class="btn btn-circle btn-icon-only {{ $button_color[$location_bookings[$key][$resource['id']]['button_show']] }} border-white"
                                                   style="height:30px; width:30px; padding:4px 3px 0 0; margin-right:1px; cursor:default;" href="javascript:;"
                                                   data-status="{{ $location_bookings[$key][$resource['id']]['status'] }}" >
                                                   <i class="icon-login"></i>
                                                </a>
                                            @else
                                                <a class="btn btn-circle btn-icon-only {{ $button_color[$location_bookings[$key][$resource['id']]['button_show']] }} border-white check_as_in" style="height:30px; width:30px; padding:4px 3px 0 0; margin-right:1px;" href="javascript:;"
                                                   data-status="{{ $location_bookings[$key][$resource['id']]['status'] }}" >
                                                   <i class="icon-login"></i>
                                                </a>
                                            @endif

                                            @if ($location_bookings[$key][$resource['id']]['button_finance'] == 'is_disabled')
                                                <a style="height:30px; width:30px; padding-top:0px; margin-right:1px; font-size:20px; line-height:26px; cursor:default;" href="javascript:;"
                                                   class="btn btn-circle btn-icon-only {{ $button_color['is_disabled'] }} border-white "> $ </a>
                                            @elseif ($location_bookings[$key][$resource['id']]['button_finance'] == 'is_paid_cash' || $location_bookings[$key][$resource['id']]['button_finance'] == 'is_paid_card' || $location_bookings[$key][$resource['id']]['button_finance'] == 'is_paid_online')
                                                <a style="height:30px; width:30px; padding-top:0px; margin-right:1px; font-size:20px; line-height:26px; cursor:default;" href="javascript:;"
                                                   class="btn btn-circle btn-icon-only {{ $button_color[$location_bookings[$key][$resource['id']]['button_finance']] }} border-white "> $ </a>
                                            @else
                                                <a style="height:30px; width:30px; padding-top:0px; margin-right:1px; font-size:20px; line-height:26px;" href="javascript:;"
                                                   data-key="{{ $location_bookings[$key][$resource['id']]['search_key'] }}"
                                                   class="btn btn-circle btn-icon-only {{ $button_color[$location_bookings[$key][$resource['id']]['button_finance']] }} border-white invoice_cash_card"
                                                   data-toggle="confirmation" data-original-title="How would you pay?"
                                                   data-btn-ok-label="CASH" data-btn-ok-icon=""
                                                   data-btn-cancel-label="CARD" data-btn-cancel-icon=""> $ </a>
                                            @endif

                                            @if ($location_bookings[$key][$resource['id']]['button_more'] == 'is_disabled')
                                                <a style="height:30px; width:30px; padding-top:4px; margin-right:4px; cursor:default;" href="javascript:;"
                                                   class="btn btn-circle btn-icon-only {{ $button_color['is_disabled'] }} border-white">
                                                    <i class="icon-speech"></i>
                                                </a>
                                            @else
                                                <a style="height:30px; width:30px; padding-top:4px; margin-right:4px;" href="javascript:;" class="btn btn-circle btn-icon-only btn-default border-white open_more_options">
                                                    <i class="icon-speech"></i>
                                                </a>
                                            @endif
                                        </div>
                                        @else
                                            &nbsp;
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- END SAMPLE TABLE PORTLET-->
            </div>

            <!-- BEGIN More Options modal window show -->
            <div class="modal fade" id="more_options_bookings_show" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                            <!--<h4 class="modal-title">More Options</h4>-->
                        </div>
                        <div class="modal-body">
                            <div id="player_summary_stats" class="personal_details_view row">
                                <div class="col-md-4 text-center">
                                    <img src="" class="player_avatar_img" style="max-width:150px; max-height:150px;" />
                                </div>
                                <div class="col-md-8" style="font-size:15px;">
                                    <span class="item-box">
                                        <span class="item font-green-jungle">
                                            <span class="icon-like" aria-hidden="true"></span> &nbsp;<span class="show_bookings">-</span> : Show bookings </span>
                                    </span><br />
                                    <span class="item-box">
                                        <span class="item font-red-thunderbird">
                                            <span class="icon-dislike" aria-hidden="true"></span> &nbsp;<span class="no_show_bookings">-</span> : No-Show bookings </span>
                                    </span><br />
                                    <span class="item-box">
                                        <span class="item font-yellow-mint">
                                            <span class="icon-close" aria-hidden="true"></span> &nbsp;<span class="cancel_bookings">-</span> : Cancelled bookings </span>
                                    </span>

                                    <blockquote class="bg-grey-cararra bg-font-grey-cararra player_short_note" style="padding:5px; margin:7px 0; font-size:14px;"> - </blockquote>
                                </div>
                            </div>
                            <div class="book_details_cancel_place" style="padding:0px 15px; margin-top:5px; clear:both;"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn green btn_cancel_booking" data-toggle="modal" href="#not_show_confirm_box">No show options</button>
                            <button type="button" class="btn green btn_cancel_booking" data-toggle="modal" href="#cancel_confirm_box">Cancel Booking</button>
                            <button type="button" class="btn dark btn-outline" data-dismiss="modal">Return</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- END More Options modal window show -->

            <!-- BEGIN No Show modal window -->
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
                            <button type="button" class="btn green btn_no_show" onclick="javascript:not_show_invoice();">Invoice Customer</button>
                            <button type="button" class="btn green btn_modify_booking" onclick="javascript:not_show_warning();">Send Warning</button>
                            <button type="button" class="btn dark btn-outline" data-dismiss="modal">Return</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- END No Show modal window -->

            <!-- BEGIN Cancel Confirm modal window show -->
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
            <!-- END Cancel Confirm modal window show -->

            <!-- BEGIN Custom booking modal window show -->
            <div class="modal fade draggable-modal" id="booking_modal_end_time" tabindex="-1" role="basic" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body form-horizontal">
                            <div class="portlet light " style="padding-bottom:0px;margin-bottom:0px;">
                                <div class="portlet-title form-group">
                                    <div class="caption">
                                        <i class="icon-social-dribbble font-green"></i>
                                        <span class="caption-subject font-green bold uppercase">Blockquotes</span>
                                    </div>
                                    <div style="float:right;" class="caption" id="countdown_60"><span class="minutes"></span>:<span class="seconds"></span></div>
                                </div>
                                <div class="portlet-body form">
                                    <!-- Booking first step Start -->
                                    <form action="#" id="booking-step-one" role="form" name="new_booking1">
                                        <div class="form-body" style="padding-top:0px; padding-bottom:0px;">
                                            <div class="form-group note note-info" style="padding-top:0px; padding-bottom:0px; margin-bottom:2px;">
                                                <p class="form-control-static"><strong>Select End Time</strong></p>

                                                <div class="booking_step_content" style="display:block;">
                                                    <select class="form-control" name="booking_end_time" id="booking_end_time"> </select>
                                                    <div class="form-actions right" style="padding-top:5px; padding-bottom:5px;">
                                                        <a class="btn blue-hoki booking_step_next" data-id="to_own_booking" style="padding-top:4px; padding-bottom:4px;">Next</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group note note-info is_own_booking" style="padding-top:0px; padding-bottom:0px; margin-bottom:2px;">
                                                <input type="hidden" autocomplete="off" value="" name="time_book_key" />
                                                <input type="hidden" autocomplete="off" value="" name="time_book_hour" />
                                                <p class="form-control-static"><strong>
                                                        <span data-id="booking_name"> The Name </span>
                                                        <span data-id="start_time"></span>
                                                        <span data-id="room_booked"></span></strong></p>
                                                <div class="form-control-static fa-item booking_payment_type" style="float:right;"></div>
                                                <div class="booking_step_content" style="display:none;">
                                                    <select class="form-control" name="resources_room" id="resources_rooms"></select>
                                                    <div class="form-actions right" style="padding-top:5px; padding-bottom:5px;">
                                                        <a class="btn blue-hoki booking_step_back" style="padding-top:4px; padding-bottom:4px;">Back</a>
                                                        <a class="btn blue-hoki booking_step_next" style="padding-top:4px; padding-bottom:4px;">Next</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group note note-info booking_summary_box" style="padding-top:0px; padding-bottom:0px; margin-bottom:2px;">
                                                <p class="form-control-static"><strong>Booking Summary</strong></p>

                                                <div class="booking_step_content" style="display:none;">
                                                    <div class="booking_summary_price_membership"></div>
                                                    <div class="form-actions right" style="padding-top:5px; padding-bottom:5px;">
                                                        <a class="btn blue-hoki booking_step_back" style="padding-top:4px; padding-bottom:4px;">Back</a>
                                                        <a class="btn blue-hoki " style="padding-top:4px; padding-bottom:4px;" onclick="cancel_booking()">Cancel</a>
                                                        <a class="btn blue-hoki " style="padding-top:4px; padding-bottom:4px;" onclick="confirm_booking()">Confirm</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <!-- Booking first step End -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- END Custom booking modal window show -->

        </div>
        <!-- END PAGE BASE CONTENT -->
    </div>
@endsection

@section('pageBelowLevelPlugins')
    <script src="{{ asset('assets/global/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>
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
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.validator.addMethod(
                "datePickerDate",
                function(value, element) {
                    // put your own logic here, this is just a (crappy) example
                    return value.match(/^\d\d?-\d\d?-\d\d\d\d$/);
                },
                "Please enter a date in the format dd/mm/yyyy."
        );

        $.validator.addMethod(
                'filesize',
                function(value, element, param) {
                    // param = size (in bytes)
                    // element = element to validate (<input>)
                    // value = value of the element (file name)
                    return this.optional(element) || (element.files[0].size <= param);
                },
                "File must be JPG, GIF or PNG, less than 1MB"
        );

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

        if (App.isAngularJsApp() === false) {
            jQuery(document).ready(function() {
                ComponentsDateTimePickers.init();
            });
        }

        $('[data-toggle=confirmation]').confirmation({ container: 'body', btnOkClass: 'btn btn-sm blue', btnCancelClass: 'btn purple btn-sm', copyAttributes: 'data-key'});

        $('.check_as_in').on('click', function(){
            var book_key = $(this).parent().attr('search-key');

            if ($(this).hasClass('{{ $button_color['is_show'] }}')){
                $(this).removeClass('{{ $button_color['is_show'] }}');
                $(this).addClass('{{ $button_color['show_btn_active'] }}');
            }
            else{
                $(this).removeClass('{{ $button_color['show_btn_active'] }}');
                $(this).addClass('{{ $button_color['is_show'] }}');
            }
            player_is_show(book_key);
        });

        function player_is_show(booking_key){
            $.ajax({
                url: '{{route('ajax/booking_action_player_show')}}',
                type: "post",
                cache: false,
                data: {
                    'search_key': booking_key
                },
                success: function (data) {
                    show_notification('Booking Canceled', 'The selected booking was canceled.', 'lemon', 3500, 0);
                    //$('#small').find('.book_details_cancel_place').html('');
                    $('#cancel_confirm_box').modal('hide');
                    $('#changeIt').modal('hide');
                }
            });
        }

        $('[data-toggle=confirmation]').on('confirmed.bs.confirmation', function () {
            var alink = $('div[search-key="' + $(this).attr('data-key') + '"]');
            var abutton = alink.find('a[data-key="' + $(this).attr('data-key') + '"]');
            abutton.addClass('{{ $button_color['is_paid_cash'] }}');

            abutton.confirmation('destroy');
            abutton.css('cursor','default');
            mark_invoice_as_paid($(this).attr('data-key'), 'cash');
            //console.log("Cash for : " + $(this).attr('data-key'));
        });

        $('[data-toggle=confirmation]').on('canceled.bs.confirmation', function () {
            var alink = $('div[search-key="' + $(this).attr('data-key') + '"]');
            var abutton = alink.find('a[data-key="' + $(this).attr('data-key') + '"]');
            abutton.addClass('{{ $button_color['is_paid_card'] }}');

            abutton.confirmation('destroy');
            abutton.css('cursor','default');
            mark_invoice_as_paid($(this).attr('data-key'), 'card');
            //console.log(" Card for :" + $(this).attr('data-key'));
        });

        function mark_invoice_as_paid(booking_key, payment_type){
            $.ajax({
                url: '{{route('ajax/booking_action_invoice_paid')}}',
                type: "post",
                cache: false,
                data: {
                    'search_key': booking_key,
                    'method': payment_type
                },
                success: function (data) {
                    show_notification('Booking Canceled', 'The selected booking was canceled.', 'lemon', 3500, 0);
                    //$('#small').find('.book_details_cancel_place').html('');
                    $('#cancel_confirm_box').modal('hide');
                    $('#changeIt').modal('hide');
                }
            });
        }

        $(document).on('click', '.open_more_options', function(){
            //alert('Cancel Booking' + $(this).attr('data-id'));
            var search_key = $(this).parent().attr('search-key');

            get_player_statistics(search_key, $('#player_summary_stats'));
            get_booking_details(search_key, $('#more_options_bookings_show'));
            $('#more_options_bookings_show').modal('show');
        });

        function get_player_statistics(key, div_container){
            $.ajax({
                url: '{{route('ajax/simple_player_bookings_statistic')}}',
                type: "post",
                cache: false,
                data: {
                    'search_key': key,
                },
                success: function (data) {
                    $('.cancel_bookings').html(data.booking_cancel);
                    $('.no_show_bookings').html(data.booking_no_show);
                    $('.show_bookings').html(data.booking_show);
                    $('.player_short_note').html(data.player_about);
                    $('.player_avatar_img').attr('src', data.player_avatar);

                    return true;

                    var booking_notes = 'No Notes';
                    /* Get booking notes */
                    if (data.bookingNotes.length !=0){
                        var booking_notes = '<small>';
                        $.each(data.bookingNotes, function(key, value){
                            booking_notes+= '<dl style="margin-bottom:7px;"><dt class="font-grey-mint"><span>' + value.created_at + '</span> ' +
                                    'by <span> ' + value.added_by + '</span></dt>' +
                                    '<dd> <span class="font-blue-steel"> ' + value.note_title + ' </span> : ' +
                                    '<span class="font-blue-dark">' + value.note_body + '</span></dd></dl>';
                        });
                        booking_notes+='</small>';
                    }

                    var book_details =
                            '<div class="row margin-bottom-5"><div class="col-md-4 bg-grey-salt bg-font-grey-salt"> Booking Date </div><div class="col-md-8 bg-grey-steel bg-font-grey-steel"> ' + data.bookingDate + ' </div></div>' +
                            '<div class="row margin-bottom-5"><div class="col-md-4 bg-grey-salt bg-font-grey-salt"> Time of booking </div><div class="col-md-8 bg-grey-steel bg-font-grey-steel"> ' + data.timeStart + ' - ' + data.timeStop + ' </div></div>' +
                            '<div class="row margin-bottom-5"><div class="col-md-4 bg-grey-salt bg-font-grey-salt"> Booking Location </div><div class="col-md-8 bg-grey-steel bg-font-grey-steel"> ' + data.location + ' - ' + data.room + ' </div></div>' +
                            '<div class="row margin-bottom-5"><div class="col-md-4 bg-grey-salt bg-font-grey-salt"> Activity </div><div class="col-md-8 bg-grey-steel bg-font-grey-steel"> ' + data.category + ' </div></div>' +
                            '<div class="row margin-bottom-5"><div class="col-md-4 bg-grey-salt bg-font-grey-salt"> Player </div><div class="col-md-8 bg-grey-steel bg-font-grey-steel"> ' + data.forUserName + ' </div></div>' +
                            '<div class="row margin-bottom-5"><div class="col-md-4 bg-grey-salt bg-font-grey-salt"> Finance </div><div class="col-md-8 bg-grey-steel bg-font-grey-steel"> ' + data.financialDetails + ' </div></div>' +
                            '<div class="row margin-bottom-5"><div class="col-md-4 bg-grey-salt bg-font-grey-salt"> Notes </div><div class="col-md-8 bg-grey-steel bg-font-grey-steel"> ' + booking_notes + ' </div></div>' +
                            '<input type="hidden" value="' + key + '" name="search_key_selected" />';

                    container.find('.book_details_cancel_place').html(book_details);
                }
            });
        }

        function get_booking_details(key, container){
            $.ajax({
                url: '{{route('ajax/get_single_booking_details')}}',
                type: "post",
                cache: false,
                data: {
                    'search_key': key,
                },
                success: function (data) {
                    var booking_notes = 'No Notes';
                    /* Get booking notes */
                    if (data.bookingNotes.length !=0){
                        var booking_notes = '<small>';
                        $.each(data.bookingNotes, function(key, value){
                            booking_notes+= '<dl style="margin-bottom:7px;"><dt class="font-grey-mint"><span>' + value.created_at + '</span> ' +
                                    'by <span> ' + value.added_by + '</span></dt>' +
                                    '<dd> <span class="font-blue-steel"> ' + value.note_title + ' </span> : ' +
                                    '<span class="font-blue-dark">' + value.note_body + '</span></dd></dl>';
                        });
                        booking_notes+='</small>';
                    }

                    var book_details =
                            '<div class="row margin-bottom-5"><div class="col-md-4 bg-grey-salt bg-font-grey-salt"> Booking Date </div><div class="col-md-8 bg-grey-steel bg-font-grey-steel"> ' + data.bookingDate + ' </div></div>' +
                            '<div class="row margin-bottom-5"><div class="col-md-4 bg-grey-salt bg-font-grey-salt"> Time of booking </div><div class="col-md-8 bg-grey-steel bg-font-grey-steel"> ' + data.timeStart + ' - ' + data.timeStop + ' </div></div>' +
                            '<div class="row margin-bottom-5"><div class="col-md-4 bg-grey-salt bg-font-grey-salt"> Booking Location </div><div class="col-md-8 bg-grey-steel bg-font-grey-steel"> ' + data.location + ' - ' + data.room + ' </div></div>' +
                            '<div class="row margin-bottom-5"><div class="col-md-4 bg-grey-salt bg-font-grey-salt"> Activity </div><div class="col-md-8 bg-grey-steel bg-font-grey-steel"> ' + data.category + ' </div></div>' +
                            '<div class="row margin-bottom-5"><div class="col-md-4 bg-grey-salt bg-font-grey-salt"> Player </div><div class="col-md-8 bg-grey-steel bg-font-grey-steel"> ' + data.forUserName + ' </div></div>' +
                            '<div class="row margin-bottom-5"><div class="col-md-4 bg-grey-salt bg-font-grey-salt"> Finance </div><div class="col-md-8 bg-grey-steel bg-font-grey-steel"> ' + data.financialDetails + ' </div></div>' +
                            '<div class="row margin-bottom-5"><div class="col-md-4 bg-grey-salt bg-font-grey-salt"> Notes </div><div class="col-md-8 bg-grey-steel bg-font-grey-steel"> ' + booking_notes + ' </div></div>' +
                            '<input type="hidden" value="' + key + '" name="search_key_selected" />';

                    container.find('.book_details_cancel_place').html(book_details);
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

                    var action_buttons = $('div[search-key="'+search_key+'"]');
                    action_buttons.css('display','none');
                    action_buttons.after(' - canceled');
                    action_buttons.parent().removeClass();
                    action_buttons.parent().addClass('bg-yellow-saffron bg-font-yellow-saffron');

                    $('#cancel_confirm_box').modal('hide');
                    $('#more_options_bookings_show').modal('hide');
                    draw_booking_box();
                }
            });
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
                    'custom_message':  $('textarea[name="custom_player_message"]').val(),
                    'private_message': $('textarea[name="private_player_message"]').val()
                },
                success: function (data) {
                    show_notification(data.message_title, data.message_body, 'lemon', 3500, 0);
                    $('#not_show_confirm_box').modal('hide');
                    $('#more_options_bookings_show').modal('hide');

                    draw_booking_box();
                }
            });
        }

        function draw_booking_box(){
            location.reload();
        }

        $('.reload_calendar_page').on('change', function(){
            var date = $('#header_date_selected').val();
            var location = $('#header_location_selected').val();
            var activity = $('#header_activity_selected').val();

            var the_link = '{{ route('bookings/location_calendar_day_view_all',['day'=>'##day##', 'location'=>'##location##', 'activity'=>'##activity##']) }}';
            the_link = the_link.replace('##day##',date);
            the_link = the_link.replace('##location##',location);
            the_link = the_link.replace('##activity##',activity);

            window.location.href = the_link;
        });

        $(document).on('click', '.add_custom_bookings_btn', function(){
            $('#booking_modal_end_time').modal('show');
        });

        $(function () {
            var is_btn_show = false;
            var isMouseDown = false,
                    isHighlighted;
            $("#bookings_calendar_view_admin td.isfreetime")
                    .mousedown(function () {
                        if ($(".add_custom_bookings_btn:visible").length==0){
                            $(this).parent().find('.add_custom_bookings_btn').css('display','block');
                        }

                        isMouseDown = true;
                        $(this).toggleClass("bg-purple-medium bg-font-purple-medium prebook");
                        isHighlighted = $(this).hasClass("bg-purple-medium bg-font-purple-medium prebook");

                        if ($(".prebook").length==0){
                            $('.add_custom_bookings_btn').css('display','none');
                        }

                        return false; // prevent text selection
                    })
                    .mouseover(function () {
                        if (isMouseDown) {
                            $(this).toggleClass("bg-purple-medium bg-font-purple-medium prebook", isHighlighted);

                            if ($(".prebook").length==0){
                                $('.add_custom_bookings_btn').css('display','none');
                            }
                        }
                    })
                    .bind("selectstart", function () {
                        if ($(".prebook").length==0){
                            $('.add_custom_bookings_btn').css('display','none');
                        }

                        return false;
                    })

            $(document)
                    .mouseup(function () {
                        isMouseDown = false;
                    });
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
    </script>
@endsection