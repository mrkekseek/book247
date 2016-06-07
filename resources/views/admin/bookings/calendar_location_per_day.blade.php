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
                        <div class="form-group col-md-4" style="margin-bottom:0px;">
                            <label class="control-label col-md-3">Select Date</label>
                            <div class="col-md-9">
                                <div class="input-group input-medium date date-picker" data-date="12-02-2012" data-date-format="dd-mm-yyyy" data-date-viewmode="years">
                                    <input type="text" class="form-control" readonly>
                                        <span class="input-group-btn">
                                            <button class="btn default" type="button">
                                                <i class="fa fa-calendar"></i>
                                            </button>
                                        </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-8" style="margin-bottom:0px;">
                            <label class="control-label col-md-2">Select Location</label>
                            <div class="col-md-4">
                                <div class="input-group input-medium">
                                    <select class="form-control" style="border-radius:4px;"><option>Select Location</option></select>
                                </div>
                            </div>
                            <label class="control-label col-md-2">Select Activity</label>
                            <div class="col-md-4">
                                <div class="input-group input-medium">
                                    <select class="form-control" style="border-radius:4px;"><option>Select Activity</option></select>
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
                        <div class="tools">
                            <a href="javascript:;" class="collapse"> </a>
                            <a href="#portlet-config" data-toggle="modal" class="config"> </a>
                            <a href="javascript:;" class="reload"> </a>
                            <a href="javascript:;" class="remove"> </a>
                        </div>
                    </div>
                    <div class="portlet-body flip-scroll">
                        <table class="table table-striped table-bordered table-hover">
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
                                <td >{{ $key }}</td>
                                @foreach ($resources as $resource)
                                    <td class="{{ isset($location_bookings[$key][$resource['id']]['color_stripe'])?$location_bookings[$key][$resource['id']]['color_stripe']:$hour['color_stripe'] }}" style="padding:4px 8px;">
                                        @if ( isset($location_bookings[$key][$resource['id']]) )
                                        <a class="font-white" href="">{{ @$location_bookings[$key][$resource['id']]['player_name'] }}</a>
                                        <div class="actions" search-key="{{ $location_bookings[$key][$resource['id']]['search_key'] }}" style="float:right;">
                                            @if ($location_bookings[$key][$resource['id']]['button_show'] == 'is_disabled')
                                                <a class="btn btn-circle btn-icon-only {{ $button_color['is_disabled'] }} border-white" style="height:30px; width:30px; padding:4px 3px 0 0; margin-right:1px; cursor:default;" href="javascript:;"
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
                                                <a style="height:30px; width:30px; padding-top:0px; margin-right:1px; font-size:20px; line-height:26px; cursor:default;" href="javascript:;" class="btn btn-circle btn-icon-only {{ $button_color['is_disabled'] }} border-white "> $ </a>
                                            @else
                                                <a style="height:30px; width:30px; padding-top:0px; margin-right:1px; font-size:20px; line-height:26px;" href="javascript:;"
                                                   data-key="{{ $location_bookings[$key][$resource['id']]['search_key'] }}"
                                                   class="btn btn-circle btn-icon-only {{ $button_color[$location_bookings[$key][$resource['id']]['button_finance']] }} border-white invoice_cash_card"
                                                   data-toggle="confirmation" data-original-title="How would you pay?"
                                                   data-btn-ok-label="CASH" data-btn-ok-icon=""
                                                   data-btn-cancel-label="CARD" data-btn-cancel-icon=""> $ </a>
                                            @endif


                                            <a style="height:30px; width:30px; padding-top:4px; margin-right:4px;" href="javascript:;" class="btn btn-circle btn-icon-only btn-default border-white open_more_options">
                                                <i class="icon-speech"></i>
                                            </a>
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
        </div>

        <!-- BEGIN More Options modal window show -->
        <div class="modal fade" id="more_options_bookings_show" tabindex="-1" role="dialog" aria-hidden="true">
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
        <!-- END More Options modal window show -->

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

        $('[data-toggle=confirmation]').confirmation({ container: 'body', btnOkClass: 'btn btn-sm blue', btnCancelClass: 'btn purple btn-sm', copyAttributes: 'data-key'});

        $('.open_more_options').on('click', function(){
            $('#more_options_bookings_show').modal('show');
        });

        $('.check_as_in').on('click', function(){
            var book_key = $(this).parent().attr('search-key');
console.log(book_key);
            if ($(this).hasClass('{{ $button_color['is_show'] }}')){
                $(this).removeClass('{{ $button_color['is_show'] }}');
                $(this).addClass('{{ $button_color['show_btn_active'] }}');
            }
            else{
                $(this).addClass('{{ $button_color['is_show'] }}');
                $(this).removeClass('{{ $button_color['show_btn_active'] }}');
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
            alink.find('a[data-key="' + $(this).attr('data-key') + '"]').addClass('bg-purple-seance');
            alink.find('a[data-key="' + $(this).attr('data-key') + '"]').addClass('bg-font-purple-seance');

            mark_invoice_as_paid($(this).attr('data-key'), 'cash');
            console.log("Cash for : " + $(this).attr('data-key'));
        });

        $('[data-toggle=confirmation]').on('canceled.bs.confirmation', function () {
            var alink = $('div[search-key="' + $(this).attr('data-key') + '"]');
            alink.find('a[data-key="' + $(this).attr('data-key') + '"]').addClass('bg-font-blue-steel');
            alink.find('a[data-key="' + $(this).attr('data-key') + '"]').addClass('bg-blue-steel');

            mark_invoice_as_paid($(this).attr('data-key'), 'card');
            console.log(" Card for :" + $(this).attr('data-key'));
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