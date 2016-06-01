@extends('admin.layouts.main')

@section('pageLevelPlugins')
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
                <div class="note note-success">
                    <p> Please try to re-size your browser window in order to see the tables in responsive mode. </p>
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
                                        <div class="actions" style="float:right;">
                                            <a style="height:26px; width:26px; padding-top:4px;" href="javascript:;" class="btn btn-circle btn-icon-only btn-default">
                                                <i class="icon-cloud-upload"></i>
                                            </a>
                                            <a style="height:26px; width:26px; padding-top:4px;" href="javascript:;" class="btn btn-circle btn-icon-only btn-default">
                                                <i class="icon-wrench"></i>
                                            </a>
                                            <a style="height:26px; width:26px; padding-top:4px;" href="javascript:;" class="btn btn-circle btn-icon-only btn-default">
                                                <i class="icon-trash"></i>
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

        <!-- END PAGE BASE CONTENT -->
    </div>
@endsection

@section('pageBelowLevelPlugins')
    <script src="{{ asset('assets/global/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript"></script>
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