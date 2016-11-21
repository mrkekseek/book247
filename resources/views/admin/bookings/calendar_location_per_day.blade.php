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
@section('pageBodyClass','page-container-bg-solid page-header-fixed page-sidebar-closed-hide-logo page-sidebar-closed')

@section('pageContentBody')
    <div class="page-content fix_padding_top_0">
        <!-- BEGIN PAGE BASE CONTENT -->
        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN SAMPLE TABLE PORTLET-->
                <div class="portlet box green">
                    <div class="portlet-title">
                        <div class="caption">
                            <div class="form-inline" style="margin-bottom:0px;">
                                <div class="input-group input-small date date-picker" data-date="{{ $header_vals['date_selected'] }}" data-date-format="dd-mm-yyyy" data-date-viewmode="years">
                                    <input type="text" id="header_date_selected" class="form-control reload_calendar_page" value="{{ $header_vals['date_selected'] }}" readonly>
                                    <span class="input-group-btn">
                                        <button class="btn default" type="button">
                                            <i class="fa fa-calendar"></i>
                                        </button>
                                    </span>
                                </div>
                                <div class="input-group input-medium">
                                    <select id="header_location_selected" class="form-control reload_calendar_page" style="border-radius:4px;">
                                        @foreach($all_locations as $a_location)
                                            <option value="{{ $a_location->id }}" {{ $a_location->id==$header_vals['selected_location']?" selected ":'' }} >{{ $a_location->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="input-group input-medium">
                                    <select id="header_activity_selected" class="form-control reload_calendar_page" style="border-radius:4px;">
                                        @foreach($all_activities as $an_activity)
                                            <option value="{{ $an_activity['id'] }}" {{ $an_activity['id']==$header_vals['selected_activity']?" selected ":'' }} >{{ $an_activity['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="tools margin-top-10">
                            <a href="{{ route('bookings/location_calendar_day_view_all', ['day'=>$header_vals['prev_date'],'location'=>$header_vals['selected_location'],'activity'=>$header_vals['selected_activity']]) }}" class="bs-glyphicons font-white" style="margin-bottom:0px;"> <span class="glyphicon glyphicon-chevron-left"> </span> Prev </a>
                            <a href="javascript:;" class="bs-glyphicons font-white" style="margin-bottom:0px;"> <span class="glyphicon glyphicon-repeat"> </span> Reload </a>
                            <a href="{{ route('bookings/location_calendar_day_view_all', ['day'=>$header_vals['next_date'],'location'=>$header_vals['selected_location'],'activity'=>$header_vals['selected_activity']]) }}" class="bs-glyphicons font-white" style="margin-bottom:0px;"> Next <span class="glyphicon glyphicon-chevron-right"> </span> </a>
                        </div>
                    </div>
                    <div class="portlet-body flip-scroll">
                        <div class="row" style="padding-left:5px; margin-bottom:10px;">
                            @foreach($membership_legend as $each_legend)
                                <div style="display:inline-block; margin-bottom:4px;">
                                    <a class="border-grey-salt" style="background-color:{{ $each_legend['color'] }}; padding:0px 10px; border-radius: {{ $each_legend['status']=='product'?'0px':'10px' }};  margin-left:10px; margin-right:5px;"> </a><small>{{ $each_legend['name'] }}</small>
                                </div>
                            @endforeach
                        </div>

                        <table class="table table-striped table-bordered table-hover" id="bookings_calendar_view_admin">
                            <thead class="flip-content">
                            <tr>
                                <th width="5%"> Time </th>
                                @foreach ($resources as $resource)
                                    <th style="width:{{ 95/sizeof($resources) }}%;">{{ $resource['name'] }}</th>
                                @endforeach
                            </tr>
                            </thead>
                            <tbody>
                            @if (sizeof($time_intervals)>0)
                                @foreach ($time_intervals as $key=>$hour)
                                <tr>
                                    <td {!! isset($jump_to[$key])?'id="jump_right_here"':'' !!} >{{ $key }} <a class="btn btn-circle btn-icon-only border-white bg-green-meadow bg-font-green-meadow add_custom_bookings_btn" href="javascript:;"> + </a></td>
                                    @foreach ($resources as $resource)
                                        <td class="{{ isset($location_bookings[$key][$resource['id']]['color_stripe'])?$location_bookings[$key][$resource['id']]['color_stripe']:$hour['color_stripe'] }}
                                            {{ ( $hour['color_stripe']=='' && !isset($location_bookings[$key][$resource['id']]['color_stripe']) )?' isfreetime':'' }}"
                                            style="padding:4px 8px; overflow:hidden; {{ isset($location_bookings[$key][$resource['id']]['custom_color'])?'background-color:'.$location_bookings[$key][$resource['id']]['custom_color'].';':'' }}">
                                            @if ( isset($location_bookings[$key][$resource['id']]) )
                                            <a class="font-white pull-xs-left" href="{{ @$location_bookings[$key][$resource['id']]['player_link'] }}" target="_blank" style="white-space: nowrap;">{{ @$location_bookings[$key][$resource['id']]['player_name'] }}</a>
                                            <div class="actions pull-xs-left" search-key="{{ $location_bookings[$key][$resource['id']]['search_key'] }}">
                                                @if ($location_bookings[$key][$resource['id']]['button_show'] == 'is_disabled')
                                                    <a class="btn btn-circle btn-icon-only {{ $button_color['is_disabled'] }} border-white"
                                                       style="height:30px; width:30px; padding:4px 3px 0 0; margin-right:1px; cursor:default; margin-left: -3px;" href="javascript:;"
                                                       data-status="{{ $location_bookings[$key][$resource['id']]['status'] }}" >
                                                       <i class="icon-login"></i>
                                                    </a>
                                                @elseif ($location_bookings[$key][$resource['id']]['button_show'] == 'is_no_show')
                                                    <a class="btn btn-circle btn-icon-only {{ $button_color[$location_bookings[$key][$resource['id']]['button_show']] }} border-white"
                                                       style="height:30px; width:30px; padding:4px 3px 0 0; margin-right:1px; cursor:default; margin-left: -3px;" href="javascript:;"
                                                       data-status="{{ $location_bookings[$key][$resource['id']]['status'] }}" >
                                                       <i class="icon-login"></i>
                                                    </a>
                                                @else
                                                    <a class="btn btn-circle btn-icon-only {{ $button_color[$location_bookings[$key][$resource['id']]['button_show']] }} border-white check_as_in"
                                                       style="margin-left: -3px; height:30px; width:30px; padding:4px 3px 0 0; margin-right:1px;" href="javascript:;"
                                                       data-status="{{ $location_bookings[$key][$resource['id']]['status'] }}" >
                                                       <i class="icon-login"></i>
                                                    </a>
                                                @endif
                                                @if ($location_bookings[$key][$resource['id']]['button_finance'] == 'is_disabled')
                                                    <a style="margin-left: -3px; height:30px; width:30px; padding-top:0px; margin-right:1px; font-size:20px; line-height:26px; cursor:default;" href="javascript:;"
                                                       class="btn btn-circle btn-icon-only {{ $button_color['is_disabled'] }} border-white "> $ </a>
                                                @elseif ($location_bookings[$key][$resource['id']]['button_finance'] == 'is_paid_cash' || $location_bookings[$key][$resource['id']]['button_finance'] == 'is_paid_card' || $location_bookings[$key][$resource['id']]['button_finance'] == 'is_paid_online')
                                                    <a style="margin-left: -3px; height:30px; width:30px; padding-top:0px; margin-right:1px; font-size:20px; line-height:26px; cursor:default;" href="javascript:;"
                                                       class="btn btn-circle btn-icon-only {{ $button_color[$location_bookings[$key][$resource['id']]['button_finance']] }} border-white "> $ </a>
                                                @else
                                                    <a style="margin-left: -3px; height:30px; width:30px; padding-top:0px; margin-right:1px; font-size:20px; line-height:26px;" href="javascript:;"
                                                       data-key="{{ $location_bookings[$key][$resource['id']]['search_key'] }}"
                                                       class="btn btn-circle btn-icon-only {{ $button_color[$location_bookings[$key][$resource['id']]['button_finance']] }} border-white invoice_cash_card"
                                                       data-toggle="confirmation" data-original-title="How would you pay?"
                                                       data-btn-ok-label="CASH" data-btn-ok-icon=""
                                                       data-btn-cancel-label="CARD" data-btn-cancel-icon=""> $ </a>
                                                @endif

                                                @if ($location_bookings[$key][$resource['id']]['button_more'] == 'is_disabled')
                                                    <a style="margin-left: -3px; height:30px; width:30px; padding-top:4px; margin-right:4px; cursor:default;" href="javascript:;"
                                                       class="btn btn-circle btn-icon-only {{ $button_color['is_disabled'] }} border-white">
                                                        <i class="icon-speech"></i>
                                                    </a>
                                                @else
                                                    <a style="margin-left: -3px; height:30px; width:30px; padding-top:4px; margin-right:4px;" href="javascript:;" class="btn btn-circle btn-icon-only btn-default border-white open_more_options">
                                                        <i class="icon-speech"></i>
                                                    </a>
                                                @endif

                                                @if ($location_bookings[$key][$resource['id']]['button_finance'] != 'is_disabled')
                                                <a style="margin-left: -4px; height:30px; width:30px; padding-top:4px; margin-right:4px;" href="javascript:;" data-id="{{ $location_bookings[$key][$resource['id']]['membership_product'] }}" class="btn btn-circle btn-icon-only btn-default border-white open_product_options">
                                                    <i class="icon-notebook"></i>
                                                </a>
                                                @endif
                                            </div>
                                            @else
                                            <span data-resource="{{ $resource['id'] }}" data-time="{{ $key }}">&nbsp;</span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td></td>
                                    <td colspan="{{sizeof($resources)}}" style="height: 300px; vertical-align:middle;">
                                        <h1>Location is closed for selected date</h1>
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                        </table>

                        <div class="row" style="padding-left:5px;">
                            @foreach($membership_legend as $each_legend)
                            <div style="display:inline-block; margin-bottom:4px;">
                                <a class="border-grey-salt" style="background-color:{{ $each_legend['color'] }}; padding:0px 10px; border-radius: {{ $each_legend['status']=='product'?'0px':'10px' }};  margin-left:10px; margin-right:5px;"> </a><small>{{ $each_legend['name'] }}</small>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- END SAMPLE TABLE PORTLET-->
            </div>

            <!-- BEGIN Booking More Options modal window show -->
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
                                            <span class="icon-like" aria-hidden="true"></span> &nbsp;<span class="show_bookings">-</span> : Showed Up </span>
                                    </span><br />
                                    <span class="item-box">
                                        <span class="item font-red-thunderbird">
                                            <span class="icon-dislike" aria-hidden="true"></span> &nbsp;<span class="no_show_bookings">-</span> : No Show  </span>
                                    </span><br />
                                    <span class="item-box">
                                        <span class="item font-yellow-mint">
                                            <span class="icon-close" aria-hidden="true"></span> &nbsp;<span class="cancel_bookings">-</span> : Cancelled bookings </span>
                                    </span>

                                    <blockquote class="bg-grey-cararra bg-font-grey-cararra player_short_note" style="padding:5px; margin:7px 0; font-size:14px; max-height:79px; height:79px; overflow:hidden;"> - </blockquote>
                                </div>
                            </div>
                            <div id="booking_summary_stats" class="book_details_cancel_place" style="padding:0px 15px; margin-top:5px; clear:both;"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn green show_rec_booking_btn" data-toggle="modal" href="#all_rec_bookings_box">Recurrent lists</button>
                            <button type="button" class="btn green no_show_booking_btn" data-toggle="modal" href="#not_show_confirm_box">No show options</button>
                            <button type="button" class="btn green cancel_booking_btn" data-toggle="modal" href="#cancel_confirm_box">Cancel Booking</button>
                            <button type="button" class="btn dark btn-outline" data-dismiss="modal">Return</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- END More Options modal window show -->

            <!-- BEGIN Booking No Show modal window -->
            <div class="modal fade" id="all_rec_bookings_box" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" style="margin-top:45px;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                            <h4 class="modal-title"> List of recurrent bookings </h4>
                        </div>
                        <div class="modal-body form-horizontal" id="book_main_details_container" style="min-height:200px;">

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

            <!-- BEGIN Booking No Show modal window -->
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
                                    <label class="col-md-4 control-label"> Booking Note<br /><small>visible by members</small></label>
                                    <div class="col-md-8">
                                        <textarea type="text" class="form-control input-inline input-large" name="custom_player_message"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label"> Internal Note<br /><small>visible by employees only</small> </label>
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

            <!-- BEGIN Booking Cancel Confirm modal window show -->
            <div class="modal fade" id="cancel_confirm_box" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content" style="margin-left:20px; margin-right:20px; margin-top:60px;">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                            <h4 class="modal-title">Do you want to cancel?</h4>
                        </div>
                        <div class="modal-body">
                            <div class="note note-warning" id="booking_cancel_rules_no" style="display:none; margin-bottom:0px;">
                                <h4 class="block">Booking cancellation is outside booking rules.</h4>
                                <p> The booking that you are trying to cancel passed the minimum hours requirements for being able to cancel. Do you still want to cancel this booking? </p>
                            </div>
                            <div class="note note-info" id="booking_cancel_rules_yes" style="display:none; margin-bottom:0px;">
                                <h4 class="block">Cancel selected bookings</h4>
                                <p> By clicking "Cancel Booking" this booking will be canceled and the player notified. Do you want to proceed with the cancellation? </p>
                            </div>
                            <div class="form-body" style="margin-top:5px;">
                                <div class="form-group" style="margin-bottom:5px;">
                                    <label class="control-label"> Public Note <small>visible by member</small></label>
                                    <textarea class="form-control input-sm" name="cancellation_player_message" rows="2"></textarea>
                                </div>
                                <div class="form-group" style="margin-bottom:0px;">
                                    <label class="control-label"> Internal Note <small>visible by employees only</small> </label>
                                    <textarea class="form-control input-sm" name="cancellation_internal_message" rows="2"></textarea>
                                </div>
                            </div>
                        </div>
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
            <div class="modal fade draggable-modal" id="booking_modal_end_time" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body form-horizontal">
                            <div class="portlet light " style="margin-bottom:0px; padding:0px 20px;">
                                <div class="portlet-title form-group" style="min-height:30px;">
                                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button"></button>
                                    <div class="caption" style="padding-top:0px;">
                                        <i class="icon-social-dribbble font-green"></i>
                                        <span class="caption-subject font-green bold uppercase">Save Bookings</span>
                                    </div>
                                    <div style="float:right; margin-right:40px; padding-top:0px; font-size:16px;" class="caption" id="countdown_60">Time Left - <span class="minutes"></span>:<span class="seconds"></span></div>
                                </div>
                                <div class="portlet-body form">
                                    <!-- Start : Search for player -->
                                    <form action="#" id="search_for_player" name="search_for_player" class="form-horizontal">
                                        <div class="form-body" style="padding-top:0px; padding-bottom:0px; margin-bottom:2px;">
                                            <div class="form-group note note-info" style="padding-top:0px; padding-bottom:0px; margin-bottom:2px;">
                                                <p class="form-control-static">
                                                    <strong class="booking_made_by">
                                                        <span class="font-blue-steel">Search for Player</span> or
                                                        <a class="font-green-jungle" data-toggle="modal" href="#register_new_user_popup">REGISTER NEW</a></strong>
                                                </p>
                                                <div class="booking_step_content" style="display:block;">
                                                    <select id="find_customer_name" name="find_customer_name" class="form-control js-data-members-ajax">
                                                        <option value="" selected="selected">Select...</option>
                                                    </select>
                                                    <div class="form-actions left" style="padding-top:5px; padding-bottom:10px;">
                                                        <a class="btn blue-hoki booking_type_next" data-id="play_with_friends_booking" style="padding-top:4px; padding-bottom:4px; display:none;">Play with Friends</a>
                                                        <a class="btn blue-hoki booking_type_next" data-id="play_alone_booking" style="padding-top:4px; padding-bottom:4px; display:none;">Play Alone</a>
                                                        <a class="btn blue-hoki booking_type_next" data-id="is_recurring_booking" style="padding-top:4px; padding-bottom:4px; display:none;">Recurring Booking</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <input type="hidden" name="booking_made_by" />
                                        <input type="hidden" name="booking_made_for" value="0" />
                                    </form>
                                    <!-- Stop : Search for player -->

                                    <!-- Booking first step Start -->
                                    <form action="#" id="booking_form_option" role="form" name="booking_form_option">
                                        <div class="form-body" style="padding-top:0px; padding-bottom:0px;">
                                            <div class="form-group note note-info is_recurring_booking" style="padding-top:0px; padding-bottom:0px; margin-bottom:2px;  display:none;">
                                                <p class="form-control-static"><strong>
                                                        <span data-id="booking_name"> The Name </span>
                                                        - Recurring booking</strong></p>
                                                <div class="form-control-static fa-item booking_payment_type" style="float:right;"></div>
                                                <div class="booking_step_content" style="display:block;">
                                                    <label><small>Select Interval for recurrence</small></label>
                                                    <select class="form-control input-large" name="recurrence_time" id="recurrence_time">
                                                        <option value="1">Daily</option>
                                                        <option value="7">Once per week</option>
                                                        <option value="14">Once every 2 weeks</option>
                                                        <option value="21">Once every 3 weeks</option>
                                                        <option value="28">Once every 4 weeks</option>
                                                    </select>

                                                    <label><small>Select End Date</small></label>
                                                    <div data-date-start-date="+0d" data-date-format="dd-mm-yyyy" class="input-group input-medium date date-picker input-large">
                                                        <input type="text" name="recurrence_end_date" readonly="" class="form-control bg-white bg-font-white">
                                                        <span class="input-group-btn">
                                                            <button type="button" class="btn default">
                                                                <i class="fa fa-calendar"></i>
                                                            </button>
                                                        </span>
                                                    </div>

                                                    <div class="form-actions right" style="padding-top:5px; padding-bottom:5px; margin-top:10px;">
                                                        <a class="btn blue-hoki recurring_step_next date_and_time_recurrence" style="padding-top:4px; padding-bottom:4px;">Next</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group note note-info is_recurring_booking" style="padding-top:0px; padding-bottom:0px; margin-bottom:2px; display:none;">
                                                <p class="form-control-static"><strong>List of Bookings</strong></p>
                                                <div class="booking_step_content" style="display:none;">
                                                    <div class="booking_summary_recurring_play" style="max-height:220px; overflow:auto;"></div>
                                                    <div class="form-actions right" style="padding-top:5px; padding-bottom:5px;">
                                                        <a class="btn blue-hoki recurring_step_back" style="padding-top:4px; padding-bottom:4px;">Back</a>
                                                        <a class="btn blue-hoki recurring_step_next" style="padding-top:4px; padding-bottom:4px;">Next</a>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group note note-info play_alone_booking" style="padding-top:0px; padding-bottom:0px; margin-bottom:2px; display:none;">
                                                <p class="form-control-static"><strong>List of Bookings</strong></p>
                                                <div class="booking_step_content">
                                                    <div class="booking_summary_single_play"></div>
                                                    <div class="form-actions right" style="padding-top:5px; padding-bottom:5px;">
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
                                                        <a class="btn blue-hoki " style="padding-top:4px; padding-bottom:4px;" data-dismiss="modal">Cancel</a>
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

            <!-- BEGIN REGISTRATION FORM -->
            <div class="modal fade" id="register_new_user_popup" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                            <h4 class="modal-title">New User Registration</h4>
                        </div>
                        <div class="modal-body">
                            <form class="register-form" method="post" name="user_registration_form" id="user_registration_form">
                                <div class="alert alert-danger display-hide">
                                    <button class="close" data-close="alert"></button> You have some errors in the form. Please check below. </div>
                                <div class="alert alert-success display-hide">
                                    <button class="close" data-close="alert"></button> Information is valid, please wait! </div>
                                <p class="hint" style="margin:5px 0;"> Enter personal details below: </p>
                                <div class="form-group">
                                    <label class="control-label visible-ie8 visible-ie9">First Name</label>
                                    <input class="form-control placeholder-no-fix" type="text" placeholder="First Name" name="firstname" /> </div>
                                <div class="form-group">
                                    <label class="control-label visible-ie8 visible-ie9">Last Name</label>
                                    <input class="form-control placeholder-no-fix" type="text" placeholder="Last Name" name="lastname" /> </div>
                                <div class="form-group">
                                    <label class="control-label visible-ie8 visible-ie9">Phone Number</label>
                                    <input class="form-control placeholder-no-fix" type="text" placeholder="Phone Number" name="phone" /> </div>

                                <p class="hint" style="margin:5px 0;"> Enter account details below: </p>
                                <div class="form-group">
                                    <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                                    <label class="control-label visible-ie8 visible-ie9">Email</label>
                                    <input class="form-control placeholder-no-fix" type="text" placeholder="Email" name="email" /> </div>
                                <div class="form-group">
                                    <label class="control-label visible-ie8 visible-ie9">Password</label>
                                    <input class="form-control placeholder-no-fix" type="password" autocomplete="off" id="register_password" placeholder="Password" name="password" /> </div>
                                <div class="form-group">
                                    <label class="control-label visible-ie8 visible-ie9">Re-type Your Password</label>
                                    <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="Re-type Your Password" name="rpassword" /> </div>

                                <p class="hint" style="margin:5px 0;"> Enter membership plan below: </p>
                                <div class="form-group">
                                    <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                                    <label class="control-label visible-ie8 visible-ie9"> Available Memberships </label>
                                    <select name="membership_plans_list" class="form-control list_all_plans">
                                        <option value="-1"> No Membership Plan </option>
                                        @foreach ($memberships as $membership)
                                            <option value="{{$membership->id}}"> {{$membership->name}} </option>
                                        @endforeach
                                    </select> </div>

                                <div class="form-actions">
                                    <button type="button" data-dismiss="modal" class="btn grey-steel">Back</button>
                                    <button type="submit" id="register-submit-btn" class="btn red uppercase pull-right">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- END REGISTRATION FORM -->

            <!-- BEGIN Booking Membership Products modal window show -->
            <div class="modal fade" id="membership_product_options" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content" style="margin-left:20px; margin-right:20px; margin-top:60px;">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                            <h4 class="modal-title">Do you want to cancel?</h4>
                        </div>
                        <div class="modal-body">
                            <div class="note note-info" style="margin-bottom:0px;">
                                <h4 class="block">Selected membership product</h4>
                                <p> If this booking is assigned to a membership product, it will be selected in the list below. If not you can assign one to it and click update button at the bottom of the popup window. </p>
                            </div>

                                <div class="form-group form-md-radios">
                                    <div class="md-radio-inline">
                                        <div class="md-radio">
                                            <input type="radio" id="radio14" value="-1" checked="checked" name="mp_radio" class="md-radiobtn">
                                            <label for="radio14" style="color:{!! $defaultProductColor !!};">
                                                <span></span>
                                                <span class="check"></span>
                                                <span class="box"></span> Default Free Plan </label>
                                        </div>
                                        @foreach ($membership_products as $single)
                                            <div class="md-radio">
                                                <input type="radio" id="radio{{$single->id}}" value="{{$single->id}}" name="mp_radio" class="md-radiobtn" checked>
                                                <label for="radio{{$single->id}}" style="color:{!! $single->color_code !!};">
                                                    <span></span>
                                                    <span class="check"></span>
                                                    <span class="box"></span> {{$single->name}} </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="mp_search_key" value="" />
                            <input type="hidden" name="mp_id" value="" />

                            <button type="button" class="btn dark btn-outline" data-dismiss="modal">No, Go Back</button>
                            <button type="button" class="btn green" onclick="javascript:update_membership_product();">Yes, Update</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- END Cancel Membership Products modal window show -->
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

        var timeinterval = ''; /* Interval timer */
        var resourceName = {
        @foreach($resources as $resource)
            {{ $resource['id'] }} : '{{ $resource['name'] }}',
        @endforeach
        };

        /* Members search using select drop-down */
        var ComponentsSelectCalendarView = function() {
            var handlePopupSearch = function() {

                // Set the "bootstrap" theme as the default theme for all Select2
                // widgets.
                //
                // @see https://github.com/select2/select2/issues/2927
                $.fn.select2.defaults.set("theme", "bootstrap");
                $.fn.modal.Constructor.prototype.enforceFocus = function() {};

                var placeholder = "Select a State";

                $(".select2, .select2-multiple").select2({
                    placeholder: placeholder,
                    width: null
                });

                $(".select2-allow-clear").select2({
                    allowClear: true,
                    placeholder: placeholder,
                    width: null
                });

                function formatMemberData(repo) {
                    if (repo.loading) return repo.text;

                    var markup = "<div class='select2-result-repository clearfix'>" +
                            "<div class='select2-result-repository__avatar'><img src='" + repo.avatar_image + "' /></div>" +
                            "<div class='select2-result-repository__meta'>" +
                            "<div class='select2-result-repository__title'>" + repo.first_name + " " + repo.middle_name + " " + repo.last_name + "</div> ";

                    if (repo.description) {
                        //markup += "<div class='select2-result-repository__description'>" + repo.description + "</div>";
                    }

                    markup += "<div class='select2-result-repository__statistics'>";
                    if (repo.membership){
                        markup += " <div class='select2-result-repository__forks'><span class='fa fa-phone-square'></span> " + repo.membership + "</div><br />";
                    }
                    if (repo.email) {
                        markup += " <div class='select2-result-repository__forks'><span class='fa fa-envelope-square'></span> " + repo.email + "</div> ";
                    }
                    if (repo.phone) {
                        markup += " <div class='select2-result-repository__forks'><span class='fa fa-phone-square'></span> " + repo.phone + "</div> ";
                    }
                    markup += '<br />';

                    if (repo.city || repo.region) {
                        markup += "<div class='select2-result-repository__stargazers'><span class='fa fa-map-o'></span> " + repo.city + ", " + repo.region + "</div>";
                    }

                    markup += "</div>" +
                            "</div></div>";

                    return markup;
                }

                function formatMemberDataSelection(repo) {
                    // we add product price to the form
                    //$('input[name=inventory_list_price]').val(repo.list_price);
                    //$('input[name=inventory_entry_price]').val(repo.entry_price);
                    //$('.price_currency').html(repo.currency);

                    if (repo.first_name==null && repo.first_name==null && repo.first_name==null){
                        var full_name = null;
                    }
                    else{
                        var full_name = repo.first_name + " " + repo.middle_name + " " + repo.last_name;
                        $('input[name="booking_made_by"]').val(repo.id).trigger('change');
                        $('span[data-id="booking_name"]').html(full_name);
                    }

                    return full_name || repo.text;
                }

                $(".js-data-members-ajax").select2({
                    width: "off",
                    ajax: {
                        url: "{{ route('admin/users/ajax_get_users') }}",
                        type: "post",
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                q: params.term, // search term
                                page: params.page
                            };
                        },
                        processResults: function(data, page) {
                            // parse the results into the format expected by Select2.
                            // since we are using custom formatting functions we do not need to
                            // alter the remote JSON data
                            return {
                                results: data.items
                            };
                        },
                        cache: true
                    },
                    escapeMarkup: function(markup) {
                        return markup;
                    }, // let our custom formatter work
                    minimumInputLength: 1,
                    templateResult: formatMemberData,
                    templateSelection: formatMemberDataSelection
                });

                function formatUserData(repo) {
                    if (repo.loading) return repo.text;

                    var markup = "<div class='select2-result-repository clearfix' >" +
                            "<div class='select2-result-repository__avatar'><img src='" + repo.avatar_image + "' /></div>" +
                            "<div class='select2-result-repository__meta'>" +
                            "<div class='select2-result-repository__title'>" + repo.first_name + " " + repo.middle_name + " " + repo.last_name + "</div> ";

                    if (repo.description) {
                        //markup += "<div class='select2-result-repository__description'>" + repo.description + "</div>";
                    }

                    markup += "<div class='select2-result-repository__statistics'>";
                    if (repo.membership){
                        markup += " <div class='select2-result-repository__forks'><span class='fa fa-phone-square'></span> " + repo.membership + "</div><br />";
                    }
                    if (repo.email) {
                        markup += " <div class='select2-result-repository__forks'><span class='fa fa-envelope-square'></span> " + repo.email + "</div> ";
                    }
                    if (repo.phone) {
                        markup += " <div class='select2-result-repository__forks'><span class='fa fa-phone-square'></span> " + repo.phone + "</div> ";
                    }
                    markup += '<br />';

                    if (repo.city || repo.region) {
                        markup += "<div class='select2-result-repository__stargazers'><span class='fa fa-map-o'></span> " + repo.city + ", " + repo.region + "</div>";
                    }

                    markup += "</div>" +
                            "</div></div>";

                    return markup;
                }

                function formatUserDataSelection(repo) {
                    // we add product price to the form
                    //$('input[name=inventory_list_price]').val(repo.list_price);
                    //$('input[name=inventory_entry_price]').val(repo.entry_price);
                    //$('.price_currency').html(repo.currency);

                    if (repo.first_name==null && repo.first_name==null && repo.first_name==null){
                        var full_name = null;
                    }
                    else{
                        var full_name = repo.first_name + " " + repo.middle_name + " " + repo.last_name;
                        location.href = repo.user_link_details;
                    }

                    return full_name || repo.text;
                }

                $(".js-data-users-ajax").select2({
                    width: "off",
                    ajax: {
                        url: "{{ route('admin/users/ajax_get_users') }}",
                        type: "post",
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                q: params.term, // search term
                                page: params.page
                            };
                        },
                        processResults: function(data, page) {
                            // parse the results into the format expected by Select2.
                            // since we are using custom formatting functions we do not need to
                            // alter the remote JSON data
                            return {
                                results: data.items
                            };
                        },
                        cache: true
                    },
                    escapeMarkup: function(markup) {
                        return markup;
                    }, // let our custom formatter work
                    minimumInputLength: 1,
                    templateResult: formatUserData,
                    templateSelection: formatUserDataSelection
                });

                $("button[data-select2-open]").click(function() {
                    $("#" + $(this).data("select2-open")).select2("open");
                });

                $(":checkbox").on("click", function() {
                    $(this).parent().nextAll("select").prop("disabled", !this.checked);
                });

                // copy Bootstrap validation states to Select2 dropdown
                //
                // add .has-waring, .has-error, .has-succes to the Select2 dropdown
                // (was #select2-drop in Select2 v3.x, in Select2 v4 can be selected via
                // body > .select2-container) if _any_ of the opened Select2's parents
                // has one of these forementioned classes (YUCK! ;-))
                $(".select2, .select2-multiple, .select2-allow-clear, .js-data-example-ajax, .js-data-members-ajax, .js-data-users-ajax").on("select2:open", function() {
                    if ($(this).parents("[class*='has-']").length) {
                        var classNames = $(this).parents("[class*='has-']")[0].className.split(/\s+/);

                        for (var i = 0; i < classNames.length; ++i) {
                            if (classNames[i].match("has-")) {
                                $("body > .select2-container").addClass(classNames[i]);
                            }
                        }
                    }
                });

                $(".js-btn-set-scaling-classes").on("click", function() {
                    $("#select2-multiple-input-sm, #select2-single-input-sm").next(".select2-container--bootstrap").addClass("input-sm");
                    $("#select2-multiple-input-lg, #select2-single-input-lg").next(".select2-container--bootstrap").addClass("input-lg");
                    $(this).removeClass("btn-primary btn-outline").prop("disabled", true);
                });
            }

            return {
                //main function to initiate the module
                init: function() {
                    handlePopupSearch();
                }
            };
        }();

        /* Calendar view dropdown */
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

        /* Forms validation codes */
        var FormValidation = function () {

            var handleValidation1 = function() {
                var form1 = $('#user_registration_form');
                var error1 = $('.alert-danger', form1);
                var success1 = $('.alert-success', form1);

                form1.validate({
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
                        email: {
                            email: true,
                            validate_email:true,
                            required: true,
                            remote: {
                                url: "{{ route('ajax/check_email_for_member_registration') }}",
                                type: "post",
                                data: {
                                    email: function() {
                                        return $( "input[name='email']" ).val();
                                    }
                                }
                            }
                        },
                        password: {
                            minlength: 8,
                            maxlength: 150,
                        },
                        rpassword: {
                            minlength: 8,
                            maxlength: 150,
                            equalTo:"#register_password"
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
                        register_member(); // submit the form
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

        function scrollToAvailableHours(){
            if($("#jump_right_here").length != 0) {
                var jumpTo = $("#jump_right_here").offset().top - 74;
                console.log(jumpTo);

                $('html, body').animate({
                    scrollTop: jumpTo
                }, 500);
            }
        }

        if (App.isAngularJsApp() === false) {
            jQuery(document).ready(function () {
                // initialize select2 drop downs
                ComponentsSelectCalendarView.init();
                // initialize date/time pickersz
                ComponentsDateTimePickers.init();
                // initialize the forms validation part
                FormValidation.init();
                //scroll to first available hour in calendar
                scrollToAvailableHours();
            });
        }

        $( "body" ).on( "click", "#recc_booking_all",function(event) {
            if(this.checked) {
                // Iterate each checkbox
                $(':checkbox.sel_rec').each(function() {
                    this.checked = true;
                });
            }
            else {
                $(':checkbox.sel_rec').each(function() {
                    this.checked = false;
                });
            }
        });

        function register_member(){
            $.ajax({
                url: '{{route('ajax/register_new_member')}}',
                type: "post",
                cache: false,
                data: {
                    'first_name': $('input[name="firstname"]').val(),
                    'last_name': $('input[name="lastname"]').val(),
                    'email': $('input[name="email"]').val(),
                    'phone_number': $('input[name="phone"]').val(),
                    'password': $('input[name="password"]').val(),
                    'membership_plan': $('select[name="membership_plans_list"]').val()
                },
                success: function (data) {
                    if (data.success==1) {
                        show_notification('New user registration', 'New user registered and selected. You can continue with the booking.', 'lime', 3500, 0);
                        $('input[name="booking_made_by"]').val(data.member_id);
                        $('input[name="booking_made_by"]').trigger('change');
                        $('#select2-find_customer_name-container').html(data.member_name);

                        $('#register_new_user_popup').modal('hide');
                    }
                    else{
                        show_notification('User registration ERROR', 'Something went wrong with the registration. Try changing the email/phone number or try reloading the page', 'ruby', 3500, 0);
                    }
                }
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
                    if (data.success==1) {
                        show_notification(data.title, data.message, 'lime', 2500, 0);
                    }
                    else{
                        show_notification(data.title, data.errors, 'ruby', 3500, 0);
                    }

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
                    if(data.success){
                        show_notification('Booking Paid', data.message, 'lime', 3500, 0);
                    }
                    else{
                        show_notification('Error marking Paiment', data.errors, 'ruby', 3500, 0);
                    }

                    //$('#small').find('.book_details_cancel_place').html('');
                    $('#cancel_confirm_box').modal('hide');
                    $('#changeIt').modal('hide');
                }
            });
        }

        $(document).on('click', '.open_more_options', function(){
            //alert('Cancel Booking' + $(this).attr('data-id'));
            var booking_status = $(this).parent().find('a').first().attr('data-status');
            if (booking_status == 'old'){
                // hide no show and cancel
                $('#more_options_bookings_show').find('.no_show_booking_btn').hide();
                $('#more_options_bookings_show').find('.cancel_booking_btn').hide();
            }
            else if (booking_status == "noshow"){
                $('#more_options_bookings_show').find('.no_show_booking_btn').hide();
                $('#more_options_bookings_show').find('.cancel_booking_btn').show();
            }
            else if (booking_status == "unpaid" || booking_status == "paid"){
                $('#more_options_bookings_show').find('.no_show_booking_btn').show();
                $('#more_options_bookings_show').find('.cancel_booking_btn').hide();
            }
            else{
                $('#more_options_bookings_show').find('.no_show_booking_btn').show();
                $('#more_options_bookings_show').find('.cancel_booking_btn').show();
            }

            $('#show_rec_booking_btn').hide();

            $("textarea[name=cancellation_player_message]").val('');
            $("textarea[name=cancellation_internal_message]").val('');
            var search_key = $(this).parent().attr('search-key');

            get_player_statistics(search_key, $('#player_summary_stats'));
            get_booking_details(search_key, $('#more_options_bookings_show'));
            $('#more_options_bookings_show').modal('show');
        });

        $(document).on('click', '.open_product_options', function(){
            var search_key = $(this).parent().attr('search-key');
            var membership_product = $(this).attr('data-id');

            $('input[name=mp_search_key]').val(search_key);
            $('input[name=mp_id]').val(membership_product);

            $('input:radio[name=mp_radio]').each(function(){
                if ($(this).val()==membership_product){
                    $(this).trigger('click');
                }
            });

            $('#membership_product_options').modal('show');
        });

        function get_player_statistics(key, div_container){
            App.blockUI({
                target: '#player_summary_stats',
                overlayColor: 'none',
                centerX: false,
                centerY: false,
                animate: true
            });
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

                    App.unblockUI('#player_summary_stats');
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
            App.blockUI({
                target: '#booking_summary_stats',
                overlayColor: 'none',
                cenrerY: true,
                cenrerX: true,
                animate: true
            });

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

                    $('.player_short_note').html(data.forPlayerDetails);
                    $('#book_main_details_container').html('');

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
                            '<div class="row margin-bottom-5"><div class="col-md-4 bg-grey-salt bg-font-grey-salt"> Notes </div><div class="col-md-8 bg-grey-steel bg-font-grey-steel"> ' + booking_notes + ' </div></div>' +
                            '<input type="hidden" value="' + data.canCancelRules + '" name="can_cancel_rules" />' +
                            '<input type="hidden" value="' + key + '" name="search_key_selected" />';
                    container.find('.book_details_cancel_place').html(book_details);

                    if (data.canCancelRules==1){
                        $('#booking_cancel_rules_yes').show();
                        $('#booking_cancel_rules_no').hide();
                    }
                    else{
                        $('#booking_cancel_rules_yes').hide();
                        $('#booking_cancel_rules_no').show();
                    }

                    if (data.recurrentList==1){
                        $('.show_rec_booking_btn').show();
                        get_recurrent_list(key);
                    }
                    else{
                        $('.show_rec_booking_btn').hide();
                    }

                    App.unblockUI('#booking_summary_stats');
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
                    $('#book_main_details_container').html(list);
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
                    $('#more_options_bookings_show').modal('hide');
                    show_notification('Bookings Canceled', 'The selected recurrent bookings were canceled.', 'lemon', 3500, 0);
                }
            });
        }

        function cancel_booking() {
            var search_key = $('input[name="search_key_selected"]').val();
            var public_note = $('textarea[name=cancellation_player_message]').val();
            var internal_note = $('textarea[name=cancellation_internal_message]').val();

            $.ajax({
                url: '{{route('ajax/cancel_booking')}}',
                type: "post",
                cache: false,
                data: {
                    'search_key': search_key,
                    'public_note': public_note,
                    'internal_note': internal_note
                },
                success: function (data) {
                    if (data.success){
                        show_notification(data.title, data.message, 'lemon', 3500, 0);

                        var action_buttons = $('div[search-key="'+search_key+'"]');
                        action_buttons.css('display','none');
                        action_buttons.after(' - canceled');
                        action_buttons.parent().removeClass();
                        action_buttons.parent().addClass('bg-yellow-saffron bg-font-yellow-saffron');

                        $('#cancel_confirm_box').modal('hide');
                        $('#more_options_bookings_show').modal('hide');
                        draw_booking_box();
                    }
                    else{
                        show_notification(data.title, data.errors, 'ruby', 3500, 0);
                        $('#cancel_confirm_box').modal('hide');
                        $('#more_options_bookings_show').modal('hide');
                    }
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
                    //'default_message': $('select[name="default_player_messages"]  :selected').val(),
                    'custom_message':  $('textarea[name="custom_player_message"]').val(),
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
            // add temporary bookings on the employee name until the other names are set up
            var resources = new Array();
            var time_intervals = new Array();

            $('input[name="booking_made_by"]').removeAttr('value');
            $('input[name="booking_made_for"]').val('0');

            $('.prebook').each(function(key, value){
                resources.push($(this).find('span').attr('data-resource'));
                time_intervals.push($(this).find('span').attr('data-time'));
            });

            $.ajax({
                url: '{{route('ajax/calendar_booking_keep_selected')}}',
                type: "post",
                cache: false,
                data: {
                    'date': $('#header_date_selected').val(),
                    'location': $('#header_location_selected').val(),
                    'userID': -1,
                    'resources': resources,
                    'time_interval': time_intervals,
                },
                success: function(data){
                    if (data.success){
                        $.each(data.bookings, function(index, value){
                            $('span[data-time="'+ value.booking_start_time +'"][data-resource="'+ value.booking_resource +'"]').attr({ 'booking-key':value.booking_key });
                        });

                        $('#booking_modal_end_time').modal('show');

                        if(typeof timeinterval !== "undefined"){
                            clearInterval(timeinterval);
                        }
                        var deadline = new Date(Date.parse(new Date()) + 300 * 1000);
                        initializeClock('countdown_60', deadline);
                    }
                    else{
                        show_notification(data.title, data.errors, 'ruby', 3500, 0);
                        setTimeout(function(){
                            location.reload();
                        }, 500);
                    }
                }
            });
        });

        $('#booking_modal_end_time').on('hidden.bs.modal', function () {

            if ($('input[name="booking_made_for"]').val()==1){
            }
            else if ($('input[name="booking_made_by"]').val()!=''){
                var all_bookings = select_all_booking_keys();

                if ($('input[name="booking_made_for"]').val()==0) {
                    cancel_booking_keys(all_bookings);
                    clean_booking_popup();
                    show_notification('Booking Operation Was Broken', 'You closed the popup window before the booking was finished.', 'tangerine', 3500, 0);
                }
            }
            else if ($('.prebook').length > 0){
                var all_bookings = select_all_booking_keys();

                if ($('input[name="booking_made_for"]').val()==0) {
                    $('.prebook').each(function(key, val){
                        $(this).find('span').first().removeAttr('booking-key');
                        $(this).removeClass();
                        $(this).addClass('is_free_time');
                    });

                    cancel_booking_keys(all_bookings);
                    show_notification('Booking Operation Was Broken', 'You closed the popup window before the booking was finished.', 'tangerine', 3500, 0);
                }

                $('.add_custom_bookings_btn').hide();
            }
        });

        function save_calendar_booking(own_box, search_key, by_player, for_player){
            $.ajax({
                url: '{{route('ajax/calendar_booking_save_selected')}}',
                type: "post",
                cache: false,
                data: {
                    'book_key':     search_key,
                    'for_player':   for_player,
                    'by_player':    by_player
                },
                success: function(data){
                    if (data.booking_key==''){
                        // something went wrong, reload resources for the window
                    }
                    else{
                        var payment_type_book = own_box.find('.booking_payment_type');
                        if (data.booking_type == 'membership'){
                            payment_type_book.html('<i class="fa fa-thumbs-o-up"></i>');
                        }
                        else{
                            payment_type_book.html('<i class="fa fa-credit-card"></i>');
                        }
                    }
                }
            });
        }

        function cancel_booking_keys(all_bookings){
            if (all_bookings==-1){
                all_bookings = select_all_booking_keys();
            }

            $.ajax({
                url: '{{route('ajax/cancel_bookings')}}',
                type: "post",
                cache: false,
                data: {
                    'selected_bookings': all_bookings,
                },
                success: function(data){
                    $('#booking_modal_end_time').modal('hide');
                    show_notification('Booking Canceled', 'The pending bookings were canceled.', 'lemon', 3500, 0);
                    $('input[name="booking_made_by"]').val('');
                }
            });
        }

        function select_all_booking_keys(){
            var all_keys = '';
            $('.prebook').each(function(index, elem) {
                var search_key = $(this).find('span').attr('booking-key');

                if (typeof search_key === "undefined"){

                }
                else if (search_key.length > 4) {
                    all_keys += search_key + ',';
                }
            });

            return all_keys;
        }

        function clean_booking_popup(){
            var all_bookings = '';
            $('.prebook').each(function(index, elem){
                //var search_key = $(this).find('span').attr('booking-key');
                //if ( search_key.length > 4 ) {
                //    all_bookings += search_key + ',';
                //}
                $(this).find('span').removeAttr('booking-key');
            });

            $('.booking_summary_price_membership').html('');
            $('.friend_booking').remove();
            $('.booking_step_content').hide();

            $('input[name="time_book_key"]').remove();
            $('.play_alone_booking').css('display','none');

            $('.is_recurring_booking').css('display','none');

            $('#search_for_player').find('.booking_step_content').show();
            $("#find_customer_name").val('').trigger('change');

            $('span[data-id="booking_name"]').html('');
            $('.booking_summary_recurring_play').html('');

            $('#register_new_user_popup').modal('hide');

            $('a[data-id=play_with_friends_booking]').hide();
            $('a[data-id=play_alone_booking]').hide();
            $('a[data-id=is_recurring_booking]').hide();
            //$('#search_for_player > .form-body > .note-info > .booking_step_content > .form-actions').hide();
        }

        function confirm_booking(){
            var all_bookings = '';
            $('input[name="time_book_key"]').each(function(){
                if ( $(this).val().length > 4 ) {
                    all_bookings += $(this).val() + ',';
                }
            });
            var selected_product = $('select[name=membership_products]').val();

            $.ajax({
                url: '{{route('ajax/confirm_bookings')}}',
                type: "post",
                cache: false,
                data: {
                    'selected_bookings': all_bookings,
                    'membership_product': selected_product,
                },
                success: function(data){
                    $('input[name="booking_made_for"]').val(1);
                    $('#booking_modal_end_time').modal('hide');

                    show_notification('Booking Confirmed', 'Your booking is now confirmed. You can see it in the calendar view.', 'lime', 2000, 0);

                    //clean_booking_popup();
                    $('input[name="booking_made_by"]').val('');

                    setTimeout(function(){
                        location.reload();
                    },1500);
                }
            });
        }

        function update_membership_product(){
            var booking_key = $('input[name=mp_search_key]').val();
            var selected_product = $('input[name=mp_radio]:checked').val();

            $.ajax({
                url: '{{route('ajax/booking_membership_product_update')}}',
                type: "post",
                cache: false,
                data: {
                    'selected_booking': booking_key,
                    'membership_product': selected_product,
                },
                success: function(data){
                    if (data.success) {
                        show_notification(data.title, data.message, 'lemon', 3500, 0);
                        setTimeout(function(){
                            location.reload();
                        },2000);
                    }
                    else{
                        show_notification(data.title, data.errors, 'ruby', 3500, 0);
                    }

                    $('#membership_product_options').modal('hide');
                }
            });
        }

        $(function () {
            var is_btn_show = false;
            var sel_classes = 'bg-yellow-saffron bg-font-yellow-saffron prebook';
            var isMouseDown = false,
                    isHighlighted;
            $("#bookings_calendar_view_admin td.isfreetime")
                    .mousedown(function () {
                        if ($(".add_custom_bookings_btn:visible").length==0){
                            $(this).parent().find('.add_custom_bookings_btn').css('display','block');
                        }

                        if ($(this).hasClass(sel_classes)==false && $(".prebook").length > 9999){
                            return false;
                        }

                        isMouseDown = true;
                        $(this).toggleClass(sel_classes);
                        isHighlighted = $(this).hasClass(sel_classes);

                        if ($(".prebook").length==0){
                            $('.add_custom_bookings_btn').css('display','none');
                        }

                        return false; // prevent text selection
                    })
                    .mouseover(function () {
                        if (isMouseDown) {
                            if ($(this).hasClass(sel_classes)==false && $(".prebook").length > 9999){
                                return false;
                            }

                            $(this).toggleClass(sel_classes, isHighlighted);

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

        $('.booking_type_next').on('click', function(){
            $('.'+$(this).attr('data-id')).slideToggle('slow');
            $('#find_customer_name').parent().slideToggle('slow');

            if ($(this).attr('data-id')=="play_with_friends_booking"){
                show_play_with_friends();
            }
            else if ($(this).attr('data-id')=="is_recurring_booking") {
                $('.is_recurring_booking').first().find('.booking_step_content').show();
                show_is_recurring_booking();
            }
            else{
                show_is_single_booking();
            }
        });

        $('input[name="booking_made_by"]').on('change', function(){
            if ($(this).val()==''){
                // hide buttons
                $('.booking_type_next').hide();
            }
            else{
                // show buttons
                $('.booking_type_next').show();
                if($('.prebook').length < 2){
                    // hide play with friends
                    $('a[data-id="play_with_friends_booking"]').hide();
                }
            }
        });

        /* Start Play with friends part */
        function show_play_with_friends(){
            var time_intv = new Array('');
            var resource = new Array('');

            $(".prebook").each(function() {
                resource.push( $(this).find('span').attr('data-resource'));
                time_intv.push( $(this).find('span').attr('data-time'));
            });

            add_friends_for_bookings(resource, time_intv);
            var first_booking_box = $('.friend_booking:first').find('.booking_step_content');
            var select_container = first_booking_box.find('select[name="friend_booking"]');
            first_booking_box.show();
            get_players_list(select_container);
        }

        function hide_play_with_friends(){

        }

        function add_friends_for_bookings (resource, time_interval){
            var append_to = '';
            var nr_bookings = resource.length;
            var book_key = '';

            for (var i=1; i<nr_bookings; i++){
                book_key = $('span[data-time="'+ time_interval[i] +'"][data-resource="'+ resource[i] +'"]').attr('booking-key');

                append_to +=
                '<div class="form-group note note-info friend_booking" style="padding-top:0px; padding-bottom:0px; margin-bottom:2px;">' +
                    '<input type="hidden" name="time_book_key" value="'+ book_key +'" />' +
                    '<p class="form-control-static">' +
                        '<strong><span data-id="booking_name">  </span> ' +
                        '<span data-id="start_time"> - ' + time_interval[i] + '</span> ' +
                        '<span data-id="room_booked">' + resourceName[resource[i]] + '</span></strong>' +
                    '</p>' +
                    '<div style="float:right;" class="form-control-static fa-item booking_payment_type"></div>' +
                    '<div class="booking_step_content" style="display:none;">' +
                        '<label><small>Select Player</small></label>' +
                        '<select class="form-control margin-bottom-5 input-sm" name="friend_booking"></select>' +
                        '<div class="form-actions right" style="padding-top:5px; padding-bottom:5px; border-top:none;">';
                if (i>1){
                    append_to += '<a class="btn blue-hoki booking_step_back" style="padding-top:4px; padding-bottom:4px;">Back</a> ';
                }
                append_to += '<a class="btn blue-hoki booking_step_next" style="padding-top:4px; padding-bottom:4px;">Next</a>' +
                        '</div>' +
                    '</div>' +
                '</div>';
            }

            $('.booking_summary_box').before(append_to);
        }

        function get_players_list(container){
            App.blockUI({
                target: '#booking_form_option',
                boxed: true,
                message: 'Processing...'
            });
            var book_key = container.parent().parent().find('input[name="time_book_key"]').val();

            $.ajax({
                url: '{{route('ajax/get_players_list')}}',
                type: "post",
                cache: false,
                data: {
                    'resourceID': '',
                    'booking_time_start': '',
                    'booking_day': '',
                    'search_key': book_key,
                    'userID': $('input[name="booking_made_by"]').val()
                },
                success: function(data){
                    var all_list = "";
                    $.each(data, function(key, value){
                        all_list += '<option value="'+ value.id +'">'+ value.name +'</option>';
                    });
                    container.html(all_list);

                    App.unblockUI('#booking_form_option');
                }
            });
        }

        function get_booking_summary(place){
            var all_bookings = '';
            $('input[name="time_book_key"]').each(function(){
                if ( $(this).val().length > 4 ) {
                    all_bookings += $(this).val() + ',';
                }
            });

            $.ajax({
                url: '{{route('ajax/get_bookings_summary')}}',
                type: "post",
                cache: false,
                data: {
                    'all_bookings': all_bookings,
                },
                success: function(data){
                    if (data.success=='true') {
                        if (data.membership_nr == 0){
                            var membership_bookings = '<h5>Membership included bookings : <span id="membership_bookings_nr"> None </span></h5>';
                        }
                        else{
                            var membership_bookings = '<h5>Membership included bookings : <span id="membership_bookings_nr">' + data.membership_nr + '</span></h5>';
                        }

                        if (data.cash_nr == 0 && data.recurring_nr == 0){
                            var cash_bookings = '<h5>Paid bookings : <span id="membership_bookings_nr"> None </span></h5>';
                        }
                        else if (data.cash_nr > 0 && data.recurring_nr == 0){
                            var cash_bookings = '<h5>Paid bookings : <span id="membership_bookings_nr">' + data.cash_nr + '</span> - <span>' + data.cash_amount + '{{ Config::get('constants.finance.currency') }}</span> in total</h5>';
                        }
                        else{
                            var cash_bookings = '';
                        }

                        var recurring = '';
                        if (data.recurring_nr > 0){
                            recurring = '<h5>Recurring bookings : <span id="membership_bookings_nr">' + data.recurring_nr + '</span> - <span>' + data.recurring_cash + '{{ Config::get('constants.finance.currency') }}</span> in total</h5>';
                        }

                        var membership_products = '';
                        if (data.membership_products.length > 0){
                            var all_products = '<option value="-1">Paid Booking</option>';
                            $.each(data.membership_products, function(key, value){
                                all_products += '<option value="'+ value.id +'">'+ value.name +'</option>';
                            });

                            membership_products = '<div class="membership_products" style="">' +
                                    '<label><small><i>Optional : calendar products associated to these paid bookings</i></small></label>' +
                                    '<select class="form-control margin-bottom-5 input-sm" name="membership_products">' +
                                    all_products +
                                    '</select>' +
                                '</div>';
                        }

                        $('.booking_summary_price_membership').html(membership_bookings + ' ' + cash_bookings + ' ' + recurring + ' ' + membership_products);
                    }
                    else {
                        show_notification(data.error.title, data.error.message, 'lemon', 3500, 0);
                    }
                }
            });
        }

        $(document).on('click', '.booking_step_next', function(){
            var own_box = $(this).parents('.form-group').first();

            var friend_name = own_box.find('select[name="friend_booking"]');
            if (friend_name.length==0){}
            else{
                own_box.find('span[data-id="booking_name"]').html(' ' + friend_name.find(":selected").text());
            }

            var own_next = own_box.next('div.form-group');
            //get_resources_for_hour(book_friend_time, own_next.find('select[name="resources_room"]'));
            //var resource_for_hour = {book_friend_time:book_friend_time, resource_room:own_next.find('select[name="resources_room"]')};
            var search_key = own_box.find('input[name="time_book_key"]').val();
            var by_player  = $('#find_customer_name').val();

            var for_player = own_box.find('select[name="friend_booking"]').val();
            if (typeof for_player === "undefined") {
                for_player = by_player;
            }

            save_calendar_booking(own_box, search_key, by_player, for_player);

            if (own_next.hasClass('friend_booking')) {
                var players_list_select = own_next.find('select[name="friend_booking"]');
                get_players_list(players_list_select);
            }
            else if (own_next.hasClass('booking_summary_box')){
                get_booking_summary(own_next);
            }

            own_box.find('.booking_step_content').first().hide();
            own_next.find('.booking_step_content').first().show();
        });

        $(document).on('click', '.booking_step_back', function(){
            var own_box = $(this).parents('.form-group').first();
            own_box.find('.booking_step_content').first().hide();

            var own_next = own_box.prev('div.form-group');
            own_next.find('.booking_step_content').first().show();
        });
        /* Stop Play with friends part */

        /* Start Recurring Booking */
        function show_is_recurring_booking(){

        }

        $('.date_and_time_recurrence').on('click', function(){
            var end_time = $('input[name="recurrence_end_date"]').val();
            var occurrence = $('#recurrence_time').val();
            var for_player = $('input[name="booking_made_by"]').first().val();

            var all_bookings = '';
            $('.prebook').each(function(index, elem) {
                var search_key = $(this).find('span').attr('booking-key');
                if (search_key.length > 4) {
                    all_bookings += search_key + ',';
                }
            });

            $.ajax({
                url: '{{route('ajax/calendar_booking_save_recurring')}}',
                type: "post",
                cache: false,
                data: {
                    'booking_key':  all_bookings,
                    'occurrence':   occurrence,
                    'end_time':     end_time,
                    'for_player':   for_player,
                    'by_player':    for_player,
                },
                success: function(data){
                    var booking_list = '';
                    var bookings = '';
                    if (data.booking_key==''){
                        // something went wrong, reload resources for the window
                    }

                    $.each(data.keys, function(key, val){
                        booking_list+=
                                '<div style="padding:0px 5px; margin:1px 0px;" class="form-group note note-info">' +
                                '<p class="form-control-static" style="margin:0px; padding:0px; min-height:20px;">' +
                                '<span data-id="booking_name">' + val.booking_date + '</span> ' +
                                '<span data-id="start_time"> - '+ val.booking_time + '</span> at ' +
                                '<span data-id="room_booked">'+resourceName[val.booking_resource]+'</span>' +
                                '</p>' +
                                '<div class="form-control-static fa-item booking_payment_type" style="float: right; margin: 5px 0px 0px; padding: 0px; min-height: 16px;">';

                        if (data.is_alternative == 0){
                            booking_list+= '<i class="fa fa-history"></i>';
                        }
                        else{
                            booking_list+= '<i class="fa fa-copy"></i>';
                        }
                        booking_list+='</div></div>';

                        bookings += ' <input type="hidden" value="' + val.booking_key + '" name="time_book_key"> ';
                    });

                    $('.booking_summary_recurring_play').html(booking_list);
                    $('.booking_summary_recurring_play').append(bookings);

                    //get_booking_summary();
                }
            });
        });

        $(document).on('click', '.recurring_step_next', function(){
            var own_box = $(this).parents('.form-group').first();
            var own_next = own_box.next('div.form-group');

            if (own_next.hasClass('play_alone_booking')){
                own_next = own_next.next('div.form-group');
            }
            if (own_next.hasClass('booking_summary_box')){
                get_booking_summary(own_next);
            }

            own_box.find('.booking_step_content').first().hide();
            own_next.find('.booking_step_content').first().show();
        });

        $(document).on('click', '.recurring_step_back', function(){
            var own_box = $(this).parents('.form-group').first();
            own_box.find('.booking_step_content').first().hide();

            var own_next = own_box.prev('div.form-group');
            own_next.find('.booking_step_content').first().show();
        });
        /* Stop Recurring Booking */

        /* Start Single invoice booking */
        function save_single_bookings(own_box){
            var bookings = '';
            $('input[name="time_book_key"]').each(function(key, val){
                bookings+=$(this).val()+',';
            });
            var for_player = $('input[name="booking_made_by"]').first().val();

            $.ajax({
                url: '{{route('ajax/calendar_booking_save_play_alone')}}',
                type: "post",
                cache: false,
                data: {
                    'book_keys':     bookings,
                    'for_player':   for_player,
                    'by_player':    for_player,
                },
                success: function(data){
                    var booking_list = '';
                    /*if (data.booking_key==''){
                        // something went wrong, reload resources for the window
                    }
                    else{
                        var payment_type_book = own_box.find('.booking_payment_type');
                        if (data.booking_type == 'membership'){
                            payment_type_book.html('<i class="fa fa-thumbs-o-up"></i>');
                        }
                        else{
                            payment_type_book.html('<i class="fa fa-credit-card"></i>');
                        }
                    }*/

                    $.each(data.keys, function(key, val){
                        var book_box_val = $('span[booking-key="'+val.booking_key+'"]');

                        booking_list+=
                        '<div style="padding:0px 5px; margin:1px 0px;" class="form-group note note-info">' +
                            '<p class="form-control-static" style="margin:0px; padding:0px; min-height:20px;">' +
                                '<span data-id="booking_name">' + $('#select2-find_customer_name-container').html() + '</span> ' +
                                '<span data-id="start_time"> - '+ book_box_val.attr('data-time') + '</span> at ' +
                                '<span data-id="room_booked">'+resourceName[book_box_val.attr('data-resource')]+'</span>' +
                            '</p>' +
                            '<div class="form-control-static fa-item booking_payment_type" style="float: right; margin: 5px 0px 0px; padding: 0px; min-height: 16px;">';

                        if (val.booking_type == 'membership'){
                            booking_list+= '<i class="fa fa-thumbs-o-up"></i>';
                        }
                        else{
                            booking_list+= '<i class="fa fa-credit-card"></i>';
                        }

                        booking_list+='</div></div>';
                    });

                    own_box.find('.booking_summary_single_play').html(booking_list);
                }
            });
        }

        function show_is_single_booking(){
            var box_container = $(".play_alone_booking");
            var bookings = '';

            box_container.find('.booking_step_content').first().show();

            $("td.prebook span[booking-key]").each(function(key, val){
                bookings += ' <input type="hidden" value="' + $(this).attr('booking-key') + '" name="time_book_key"> ';
            });
            box_container.append(bookings);
            save_single_bookings(box_container);
            box_container.show();
            //var place = $('.booking_summary_box');
            //get_booking_summary(place);
            //place.find('.booking_step_content').first().show();
        }
        /* Stop Single invoice booking */

        /* Timer functions - Start */
        function getTimeRemaining(endtime) {
            var t = Date.parse(endtime) - Date.parse(new Date());
            var seconds = Math.floor((t / 1000) % 60);
            var minutes = Math.floor((t / 1000 / 60) % 60);
            var hours = Math.floor((t / (1000 * 60 * 60)) % 24);
            var days = Math.floor(t / (1000 * 60 * 60 * 24));
            return {
                'total': t,
                'days': days,
                'hours': hours,
                'minutes': minutes,
                'seconds': seconds
            };
        }

        function initializeClock(id, endtime) {
            var clock = document.getElementById(id);
            //var daysSpan = clock.querySelector('.days');
            //var hoursSpan = clock.querySelector('.hours');
            var minutesSpan = clock.querySelector('.minutes');
            var secondsSpan = clock.querySelector('.seconds');

            function updateClock() {
                var t = getTimeRemaining(endtime);

                //daysSpan.innerHTML = t.days;
                //hoursSpan.innerHTML = ('0' + t.hours).slice(-2);
                minutesSpan.innerHTML = ('0' + t.minutes).slice(-2);
                secondsSpan.innerHTML = ('0' + t.seconds).slice(-2);

                if (t.total <= 0) {
                    //clearInterval(timeinterval);
                    $('#booking_modal_end_time').modal('hide');
                }
            }

            updateClock();
            timeinterval = setInterval(updateClock, 1000);
        }
        /* Timer function - Stop */
        function booking_calendar_view_redirect(selected_date){
            var calendar_book = "{{route('bookings/location_calendar_day_view',['day'=>'##day##'])}}";
            the_link = calendar_book.replace('##day##', $('#calendar_booking_top_menu').data('datepicker').getFormattedDate('dd-mm-yyyy'));
            window.location.href = the_link;
        }

        /* Page auto refresh after 5 min of inactivity - Start */
        var time = new Date().getTime();
        $(document.body).bind("mousemove keypress", function(e) {
            time = new Date().getTime();
        });

        function refresh() {
            if(new Date().getTime() - time >= 300000)
                window.location.reload(true);
            else
                setTimeout(refresh, 10000);
        }

        setTimeout(refresh, 10000);
        /* Page auto refresh after 5 min of inactivity - Stop */
    </script>
@endsection