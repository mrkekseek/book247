@extends('layouts.main')

@section('globalMandatoryStyle')
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/simple-line-icons/simple-line-icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/uniform/css/uniform.default.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('pageLevelPlugins')
    <link href="{{ asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('themeGlobalStyle')
    <link href="{{ asset('assets/global/css/components-rounded.min.css') }}" rel="stylesheet" id="style_components" type="text/css" />
    <link href="{{ asset('assets/global/css/plugins.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/jquery-notific8/jquery.notific8.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('themeLayoutStyle')
    <link href="{{ asset('assets/pages/css/location_calendar_day_view.css') }}" rel="stylesheet" type="text/css" />

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
                            <!-- BEGIN SAMPLE TABLE PORTLET-->
                            <div class="portlet box blue-steel hidden-xs">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <div class="form-inline" style="margin-bottom:0px;">
                                            <div class="input-group input-small date date-picker" data-date="{{ $header_vals['date_selected'] }}" data-date-start-date="+0d" data-date-end-date="+6d" data-date-format="dd-mm-yyyy" data-date-viewmode="years">
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
                                            <div class="input-group input-small">
                                                <select id="header_activity_selected" class="form-control reload_calendar_page" style="border-radius:4px;">
                                                    @foreach($all_activities as $an_activity)
                                                        <option value="{{ $an_activity->id }}" {{ $an_activity->id==$header_vals['selected_activity']?" selected ":'' }} >{{ $an_activity->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tools ">
                                        <a href="{{ route('front_calendar_booking_all', ['day'=>$header_vals['prev_date'],'location'=>$header_vals['selected_location'],'activity'=>$header_vals['selected_activity']]) }}" class="bs-glyphicons font-white" style="margin-bottom:0px;"> <span class="glyphicon glyphicon-chevron-left"> </span> Prev </a>
                                        <a href="javascript:;" class="bs-glyphicons font-white" style="margin-bottom:0px;"> <span class="glyphicon glyphicon-repeat"> </span> Reload </a>
                                        <a href="{{ route('front_calendar_booking_all', ['day'=>$header_vals['next_date'],'location'=>$header_vals['selected_location'],'activity'=>$header_vals['selected_activity']]) }}" class="bs-glyphicons font-white" style="margin-bottom:0px;"> Next <span class="glyphicon glyphicon-chevron-right"> </span> </a>
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
                                                    <td class="{{ isset($location_bookings[$key][$resource['id']]['color_stripe'])?$location_bookings[$key][$resource['id']]['color_stripe']:$hour['color_stripe'] }}
                                                    {{ ( $hour['color_stripe']=='' && !isset($location_bookings[$key][$resource['id']]['color_stripe']) )?' isfreetime':'' }}" style="padding:4px 8px;">
                                                        @if ( isset($location_bookings[$key][$resource['id']]) )
                                                            <a class="font-white" href="">Booked</a>
                                                        @else
                                                            <span data-resource="{{ $resource['id'] }}" data-time="{{ $key }}">&nbsp;</span>
                                                        @endif
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="portlet light bordered  visible-xs margin-top-20">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="icon-edit font-dark"></i>
                                        <span class="caption-subject font-dark bold uppercase">System Message</span>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <div class="note note-warning">
                                        <h4 class="block">This page is viewed better on large screen devices</h4>
                                        <p> You are viewing this page on a device with a smaller than recommended screen size. For a proper functionality of this page please use it on a bigger screen device or
                                            use the link for the mobile optimized page (the link at the bottom of this message). Thank you for understanding and we wish you have a great game and a great user experience.</p>
                                        <p class="margin-top-10">
                                            <a href="{{route('homepage')}}" class="btn purple"> Mobile Optimized Booking Page </a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!-- END SAMPLE TABLE PORTLET-->
                        </div>

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
                                                <!-- Booking first step Start -->
                                                <form action="#" id="booking_form_option" role="form" name="booking_form_option">
                                                    <input type="hidden" name="booking_made_by" value="0" />

                                                    <div class="form-body" style="padding-top:0px; padding-bottom:0px;">
                                                        <div class="form-group note note-info booking_summary_box" style="padding-top:0px; padding-bottom:0px; margin-bottom:2px;">
                                                            <p class="form-control-static"><strong>Booking Summary</strong></p>
                                                            <div class="booking_step_content" style="display:none;">
                                                                <div class="booking_summary_price_membership"></div>
                                                                <div class="form-actions right" style="padding-top:5px; padding-bottom:5px;">
                                                                    <a class="btn blue-hoki booking_step_back" style="padding-top:4px; padding-bottom:4px;">Back</a>
                                                                    <a class="btn blue-hoki " style="padding-top:4px; padding-bottom:4px;" onclick="cancel_booking_keys(-1)">Cancel</a>
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
                </div>
                <!-- END PAGE CONTENT INNER -->
            </div>
        </div>
        <!-- END PAGE CONTENT BODY -->
        <!-- END CONTENT BODY -->
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
    <script src="{{ asset('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/bootstrap-confirmation-2-2/bootstrap-confirmation.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>

    <script src="{{ asset('assets/global/plugins/jquery-notific8/jquery.notific8.min.js') }}" type="text/javascript"></script>
@endsection

@section('themeBelowGlobalScripts')
    <script src="{{ asset('assets/global/scripts/app.min.js') }}" type="text/javascript"></script>
@endsection

@section('pageBelowLevelScripts')
    <script src="{{ asset('assets/pages/scripts/ui-notific8.min.js') }}" type="text/javascript"></script>
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

        var timeinterval = ''; /* Interval timer */
        var resourceName = {
        @foreach($resources as $resource)
        {{ $resource['id'] }} : '{{ $resource['name'] }}',
        @endforeach
        };

        /* Calendar view dropdown */
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
            jQuery(document).ready(function () {
                // initialize date/time pickersz
                ComponentsDateTimePickers.init();
            });
        }

        function draw_booking_box(){
            location.reload();
        }

        $('.reload_calendar_page').on('change', function(){
            var date = $('#header_date_selected').val();
            var location = $('#header_location_selected').val();
            var activity = $('#header_activity_selected').val();

            var the_link = '{{ route('front_calendar_booking_all',['day'=>'##day##', 'location'=>'##location##', 'activity'=>'##activity##']) }}';
            the_link = the_link.replace('##day##',date);
            the_link = the_link.replace('##location##',location);
            the_link = the_link.replace('##activity##',activity);

            window.location.href = the_link;
        });

        $(document).on('click', '.add_custom_bookings_btn', function(){
            // add temporary bookings on the employee name until the other names are set up
            var resources = new Array();
            var time_intervals = new Array();

            $('input[name="booking_made_by"]').val(1);

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
                    'resources': resources,
                    'time_interval': time_intervals,
                },
                success: function(data){
                    $.each(data, function(index, value){
                        $('span[data-time="'+ value.booking_start_time +'"][data-resource="'+ value.booking_resource +'"]').attr({ 'booking-key':value.booking_key });
                    });

                    $('#booking_modal_end_time').modal('show');

                    if(typeof timeinterval !== "undefined"){
                        clearInterval(timeinterval);
                    }
                    var deadline = new Date(Date.parse(new Date()) + 60 * 1000);
                    initializeClock('countdown_60', deadline);

                    show_play_with_friends();
                }
            });
        });

        $('#booking_modal_end_time').on('hidden.bs.modal', function () {

            if ($('input[name="booking_made_by"]').val()==1){
                var all_bookings = select_all_booking_keys();

                $('.prebook').each(function(key, val){
                    $(this).find('span').first().removeAttr('booking-key');
                    $(this).removeClass();
                    $(this).addClass('is_free_time');
                });

                cancel_booking_keys(all_bookings);
                show_notification('Booking Operation Was Broken', 'You closed the popup window before the booking was finished.', 'tangerine', 3500, 0);

                $('.add_custom_bookings_btn').hide();
            }

            clean_booking_popup();
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
            console.log(all_bookings);
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
            $('.friend_booking').remove();
            $('.prebook').each(function(index, elem){
                $(this).find('span').removeAttr('booking-key');
            });
            $('input[name="time_book_key"]').remove();
        }

        function confirm_booking(){
            var all_bookings = '';
            $('input[name="time_book_key"]').each(function(){
                if ( $(this).val().length > 4 ) {
                    all_bookings += $(this).val() + ',';
                }
            });

            $.ajax({
                url: '{{route('ajax/confirm_bookings')}}',
                type: "post",
                cache: false,
                data: {
                    'selected_bookings': all_bookings,
                },
                success: function(data){
                    $('#booking_modal_end_time').modal('hide');
                    show_notification('Booking Confirmed', 'Your booking is now confirmed. You can see it in the calendar view.', 'lime', 2000, 0);

                    //clean_booking_popup();
                    $('input[name="booking_made_by"]').val(0);

                    setTimeout(function(){
                        location.reload();
                    },1500);
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

                        if ($(this).hasClass(sel_classes)==false && $(".prebook").length > 5){
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
                            if ($(this).hasClass(sel_classes)==false && $(".prebook").length > 5){
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

            var key = first_booking_box.parent().find('input[name="time_book_key"]').val();
            get_players_list(select_container, key);
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

        function get_players_list(container, key){
            App.blockUI({
                target: '#booking_form_option',
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
                            var cash_bookings = '<h5>Paid bookings : <span id="membership_bookings_nr">' + data.cash_nr + '</span> of <span>' + data.cash_amount + '</span> in total</h5>';
                        }
                        else{
                            var cash_bookings = '';
                        }

                        var recurring = '';
                        if (data.recurring_nr > 0){
                            recurring = '<h5>Recurring bookings : <span id="membership_bookings_nr">' + data.recurring_nr + '</span> bookings of <span>' + data.recurring_cash + '</span> in total</h5>';
                        }

                        $('.booking_summary_price_membership').html(membership_bookings + ' ' + cash_bookings + ' ' + recurring);
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
            save_calendar_booking(own_box, search_key, by_player, for_player);

            if (own_next.hasClass('friend_booking')) {
                var players_list_select = own_next.find('select[name="friend_booking"]');
                get_players_list(players_list_select, search_key);
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