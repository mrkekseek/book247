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
    <link href="{{ asset('assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('assets/global/plugins/jquery-notific8/jquery.notific8.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('themeGlobalStyle')
    <link href="{{ asset('assets/global/css/components-rounded.min.css') }}" rel="stylesheet" id="style_components" type="text/css" />
    <link href="{{ asset('assets/global/css/plugins.min.css') }}" rel="stylesheet" type="text/css" />
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
            <div class="container" id="main_body_container">
                <!-- BEGIN PAGE CONTENT INNER -->
                <div class="page-content-inner">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="portlet light bordered">
                                <div class="portlet-title tabbable-line">
                                    <div class="caption caption-md">
                                        <i class="icon-globe theme-font hide"></i>
                                        <span class="caption-subject font-blue-madison bold uppercase">My Bookings</span>
                                    </div>
                                    <ul class="nav nav-tabs">
                                        <li class="active">
                                            <a href="#tab_1_1" data-toggle="tab">Upcoming Bookings</a>
                                        </li>
                                        <li>
                                            <a href="#tab_1_2" data-toggle="tab">Booking History</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="portlet-body">
                                    <div class="tab-content">
                                        <!-- PERSONAL INFO TAB -->
                                        <div class="tab-pane active" id="tab_1_1">
                                            <p> Here you can see all your upcoming bookings, the old/canceled ones are shown in "Booking History" </p>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <!-- BEGIN BORDERED TABLE PORTLET-->
                                                    @if (sizeof($lastTen)>0)
                                                    <div class="portlet light portlet-fit bordered">
                                                        <div class="table-scrollable">
                                                        <table class="table table-bordered table-hover">
                                                            <thead>
                                                            <tr>
                                                                <th> # </th>
                                                                <th> Date </th>
                                                                <th> Time Interval </th>
                                                                <th class="hidden-xs"> Player </th>
                                                                <th> Location </th>
                                                                <th class="hidden-xs"> Room </th>
                                                                <th class="hidden-xs"> Activity </th>
                                                                <th> Status </th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php $theNr = 1; ?>
                                                            @foreach($lastTen as $booking)
                                                                <tr>
                                                                    <?php   if (!isset($booking['colspan'])){
                                                                        echo '<td rowspan="'.($multipleBookingsIndex[$theNr]).'">'.$theNr.'</td>';
                                                                        $theNr++;
                                                                    } else {  } ?>
                                                                    <td> <small>{{$booking['date']}}</small> </td>
                                                                    <td> <small>{{$booking['timeInterval']}}</small> </td>
                                                                    <td class="hidden-xs"> <small>{{$booking['player_name']}}</small> </td>
                                                                    <td class="hidden-xs"> <small>{{$booking['location']}}</small> </td>
                                                                    <td> <small>{{$booking['room']}}</small> </td>
                                                                    <td class="hidden-xs"> <small>{{$booking['activity']}}</small> </td>
                                                                    <td> {!! $booking['button_actions'] !!} </td>
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    </div>
                                                    @else
                                                    <div class="note note-warning">
                                                        <h4 class="block">No upcoming bookings - Book some more</h4>
                                                        <p> All your old bookings are visible in the "Booking History" tab. </p>
                                                    </div>
                                                    @endif
                                                    <!-- END BORDERED TABLE PORTLET-->
                                                </div>
                                            </div>
                                        </div>
                                        <!-- END PERSONAL INFO TAB -->
                                        <!-- CHANGE AVATAR TAB -->
                                        <div class="tab-pane" id="tab_1_2">
                                            <p> Here you can see all your old or canceled bookings, the active/pending ones are shown in "Upcoming Bookings" </p>
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
                                                                        <i class="fa fa-briefcase"></i> Booking Time </th>
                                                                    <th>
                                                                        <i class="fa fa-briefcase"></i> Location - Room </th>
                                                                    <th class="hidden-xs">
                                                                        <i class="fa fa-user"></i> Player </th>
                                                                    <th>
                                                                        <i class="fa fa-briefcase"></i> Added On </th>
                                                                    <th>
                                                                        <i class="fa fa-shopping-cart"></i> Status </th>
                                                                    <th> </th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                @foreach ($bookings as $booking)
                                                                    @if ($booking['status']=='active') { continue;} @endif
                                                                    <tr>
                                                                        <td class="highlight">
                                                                            <div class="{{ $booking['color_status'] }}"></div>
                                                                            <small>&nbsp; <span class="font-blue-soft"> {{ $booking['dateShort'] }}  </span> </small>
                                                                        </td>
                                                                        <td> <small>{{ $booking['timeInterval'] }}</small> </td>
                                                                        <td> <small>{{ $booking['location'] }} - {{  $booking['room'] }}</small> </td>
                                                                        <td class="hidden-xs"> <small>{{$booking['player_name']}}</small> </td>
                                                                        <td> <small>{{ $booking['added_on'] }}</small> </td>
                                                                        <td> <small>{{ $booking['status'] }}</small> </td>
                                                                        <td> {!! $booking['button_actions'] !!} </td>
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
                                                            <p> You have no old/passed bookings. Once you create a new booking, it will be visible here after the booking date/time passed. </p>
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

                    <div class="modal fade" id="small_cancel" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                    <h4 class="modal-title">Cancel booking</h4>
                                </div>
                                <div class="modal-body">
                                    <h4>You are about to cancel this booking :</h4>
                                    <div class="book_details_cancel_place" style="padding:0px 15px;"></div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">No - return</button>
                                    <button type="button" class="btn green" onclick="javascript:cancel_booking();">Yes - cancel</button>
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>

                    <div class="modal fade" id="small_details" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                    <h4 class="modal-title">Booking Details</h4>
                                </div>
                                <div class="modal-body">
                                    <h4>You are seeing here the booking details and the available notes :</h4>
                                    <div class="book_details_cancel_place" style="padding:0px 15px;"></div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Back to list</button>
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>

                    <div class="modal fade" id="changeIt" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                    <h4 class="modal-title">Update Booking</h4>
                                </div>
                                <div class="modal-body form-horizontal" id="book_main_details_container">
                                    <div class="form-body">
                                        <div class="form-group">
                                            <label class="col-md-3 control-label"> Date/Time</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control input-inline input-large" name="book_date_time" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label"> Location</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control input-inline input-large" name="book_location" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label"> Room</label>
                                            <div class="col-md-9">
                                                <input class="form-control input-inline input-large" name="book_room" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label"> Activity</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control input-inline input-large" name="book_activity" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label"> Player </label>
                                            <div class="col-md-9">
                                                <input class="form-control input-inline input-large" name="book_player" readonly />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label"> Financial </label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control input-inline input-large" name="book_finance" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group" id="all_booking_notes" style="display:none; margin-bottom:0px;">
                                            <label class="col-md-3 text-right"> Notes </label>
                                            <div class="col-md-9"> </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">No - return</button>
                                    <button type="button" class="btn green" onclick="javascript:cancel_booking();">Yes - update</button>
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
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

    <script src="{{ asset('assets/global/scripts/datatable.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/datatables/datatables.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') }}" type="text/javascript"></script>

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

        $(document).on('click', '.cancel_booking', function(){
            //alert('Cancel Booking' + $(this).attr('data-id'));

            get_booking_details($(this).attr('data-id'), $('#small_cancel'));
            $('#small_cancel').modal('show');
        });

        function get_booking_details(key, container){
            $.ajax({
                url: '{{route('ajax/get_single_booking_details')}}',
                type: "post",
                cache: false,
                data: {
                    'search_key': key,
                },
                success: function (data) {
                    var booking_notes = '';
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
                            '<div class="row margin-bottom-5"><div class="col-md-4 bg-grey-salt bg-font-grey-salt"> Booking Location </div><div class="col-md-8 bg-grey-steel bg-font-grey-steel"> ' + data.location + ' </div></div>' +
                            '<div class="row margin-bottom-5"><div class="col-md-4 bg-grey-salt bg-font-grey-salt"> Booking Room </div><div class="col-md-8 bg-grey-steel bg-font-grey-steel"> ' + data.room + ' </div></div>' +
                            '<div class="row margin-bottom-5"><div class="col-md-4 bg-grey-salt bg-font-grey-salt"> Activity </div><div class="col-md-8 bg-grey-steel bg-font-grey-steel"> ' + data.category + ' </div></div>' +
                            '<div class="row margin-bottom-5"><div class="col-md-4 bg-grey-salt bg-font-grey-salt"> Created By </div><div class="col-md-8 bg-grey-steel bg-font-grey-steel"> ' + data.byUserName + ' </div></div>' +
                            '<div class="row margin-bottom-5"><div class="col-md-4 bg-grey-salt bg-font-grey-salt"> Player </div><div class="col-md-8 bg-grey-steel bg-font-grey-steel"> ' + data.forUserName + ' </div></div>' +
                            '<div class="row margin-bottom-5"><div class="col-md-4 bg-grey-salt bg-font-grey-salt"> Finance </div><div class="col-md-8 bg-grey-steel bg-font-grey-steel"> ' + data.financialDetails + ' </div></div>' +
                            '<div class="row margin-bottom-5"><div class="col-md-4 bg-grey-salt bg-font-grey-salt"> Notes </div><div class="col-md-8 bg-grey-steel bg-font-grey-steel"> ' + booking_notes + ' </div></div>' +
                            '<input type="hidden" value="' + key + '" name="search_key_selected" />';

                    container.find('.book_details_cancel_place').html(book_details);
                }
            });
        }

        function cancel_booking(){
            App.blockUI({
                target: '#small_cancel',
                boxed: true,
                message: 'Processing...'
            });

            var search_key = $('input[name="search_key_selected"]').val();

            $.ajax({
                url: '{{route('ajax/cancel_booking')}}',
                type: "post",
                cache: false,
                data: {
                    'search_key' : search_key
                },
                success: function (data) {
                    show_notification('Booking Canceled', 'The selected booking was canceled.', 'lemon', 3500, 0);

                    $('#small_cancel').find('.book_details_cancel_place').html('');
                    $('#small_cancel').modal('hide');
                    App.unblockUI('#small_cancel');

                    setTimeout(function(){
                        location.reload();
                    },1500);
                }
            });
        }

        function modify_booking_details(key){
            $.ajax({
                url: '{{route('ajax/get_single_booking_details')}}',
                type: "post",
                cache: false,
                data: {
                    'search_key': key,
                },
                success: function (data) {
                    $('input[name="book_date_time"]').val(data.bookingDate + ', ' + data.timeStart + ' - ' + data.timeStop);
                    $('input[name="book_location"]').val(data.location);
                    $('input[name="book_room"]').val(data.room);
                    $('input[name="book_activity"]').val(data.category);
                    $('input[name="book_player"]').val(data.forUserName);
                    //get_players_list($('select[name="book_player"]'), data.forUserName, data.forUserID, key);

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

                    $('input[name="book_finance"]').val(data.financialDetails);
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

        $(document).on('click', '.modify_booking', function(){
            modify_booking_details($(this).attr('data-id'));
            $('#changeIt').modal('show');
        });

        $(document).on('click', '.details_booking', function(){
            get_booking_details($(this).attr('data-id'), $('#small_details'));
            $('#small_details').modal('show');
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