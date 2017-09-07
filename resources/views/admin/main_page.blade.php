@extends('admin.layouts.main')

@section('globalMandatoryStyle')
    <link href="//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/simple-line-icons/simple-line-icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/uniform/css/uniform.default.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('pageLevelPlugins')
    <link href="{{ asset('assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/morris/morris.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/fullcalendar/fullcalendar.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/jqvmap/jqvmap/jqvmap.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('themeGlobalStyle')
    <link href="{{ asset('assets/global/css/components-rounded.min.css') }}" rel="stylesheet" id="style_components" type="text/css" />
    <link href="{{ asset('assets/global/css/plugins.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('themeLayoutStyle')
    <link href="{{ asset('assets/layouts/layout4/css/layout.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/layouts/layout4/css/themes/light.min.css') }}" rel="stylesheet" type="text/css" id="style_color" />
    <link href="{{ asset('assets/layouts/layout4/css/custom.min.css') }}" rel="stylesheet" type="text/css" />

    <meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('title', 'Main Page')
@section('pageBodyClass','page-container-bg-solid page-header-fixed page-sidebar-closed-hide-logo')

@section('pageContentBody')
    <div class="page-content">
        <!-- BEGIN PAGE BREADCRUMB -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="index.html">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span class="active">Dashboard</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMB -->
        <!-- BEGIN PAGE BASE CONTENT -->
        @if (Auth::user()->hasRole('owner'))
            @if (sizeof($stats)>0 && $showStats==true)
                @foreach ($stats as $single)
                    <div class="m-heading-1 border-green m-bordered">
                        <h3 style="margin-bottom:0px;">{{ $single['location_name'] }}</h3>
                    </div>
                    <div class="row">
                        @foreach($single['today_occupancy'] as $key=>$val)
                        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                            <div class="dashboard-stat2 bordered">
                                <div class="display">
                                    <div class="number">
                                        <h3 class="font-green-sharp">
                                            <span data-counter="counterup" data-value="{{ $val!=0?$val:0 }}">0</span>
                                            <small class="font-green-sharp">bookings</small>
                                        </h3>
                                        <small>{{ $single['location_categories'][$key] }}</small>
                                    </div>
                                    <div class="icon">
                                        <i class="icon-pie-chart"></i>
                                    </div>
                                </div>
                                <div class="progress-info">
                                    <div class="progress">
                                        <span style="width: {{ $single['today_availability'][$key]!=0?intval(($val/$single['today_availability'][$key])*100):0 }}%;" class="progress-bar progress-bar-success green-sharp">
                                            <span class="sr-only">{{ $single['today_availability'][$key]!=0?intval(($val/$single['today_availability'][$key])*100):0 }}% occupancy</span>
                                        </span>
                                    </div>
                                    <div class="status">
                                        <div class="status-title"> Occupancy </div>
                                        <div class="status-number"> {{ $single['today_availability'][$key]!=0?intval(($val/$single['today_availability'][$key])*100):0 }}% </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                            <div class="dashboard-stat2 bordered">
                                <div class="display">
                                    <div class="number">
                                        <h3 class="font-red-haze">
                                            <span data-counter="counterup" data-value="{{ $single['members_today'] }}">0</span>
                                        </h3>
                                        <small>Signed Memberships</small>
                                    </div>
                                    <div class="icon">
                                        <i class="icon-like"></i>
                                    </div>
                                </div>
                                <div class="progress-info">
                                    <div class="progress">
                                    <span style="width: {{ $single['members_today']!=0?intval($membersToday/$single['members_today']*100):0 }}%;" class="progress-bar progress-bar-success red-haze">
                                        <span class="sr-only">{{ $single['members_today']!=0?intval($membersToday/$single['members_today']*100):0 }}% change</span>
                                    </span>
                                    </div>
                                    <div class="status">
                                        <div class="status-title"> from new registrations </div>
                                        <div class="status-number"> {{ $single['members_today']!=0?intval($membersToday/$single['members_today']*100):0 }}% </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @foreach ($single['bookings_this_month'] as $key=>$val)
                        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                            <!-- BEGIN WIDGET THUMB -->
                            <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                                <h4 class="widget-thumb-heading" style="margin-bottom:10px;">Bookings this month</h4>
                                <div class="widget-thumb-wrap">
                                    <i class="widget-thumb-icon bg-blue icon-bar-chart"></i>
                                    <div class="widget-thumb-body">
                                        <span class="widget-thumb-subtitle">{{ @$single['location_categories'][$key] }}</span>
                                        <span class="widget-thumb-body-stat" data-counter="counterup" data-value="{{$val}}">0</span>
                                    </div>
                                </div>
                            </div>
                            <!-- END WIDGET THUMB -->
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                            <!-- BEGIN WIDGET THUMB -->
                            <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                                <h4 class="widget-thumb-heading" style="margin-bottom:8px;">Bookings last month</h4>
                                <div class="widget-thumb-wrap">
                                    <i class="widget-thumb-icon bg-blue icon-bar-chart"></i>
                                    <div class="widget-thumb-body">
                                        <span class="widget-thumb-subtitle">{{ @$single['location_categories'][$key] }}</span>
                                        <span class="widget-thumb-body-stat" data-counter="counterup" data-value="{{ $single['bookings_last_month'][$key] }}">0</span>
                                    </div>
                                </div>
                            </div>
                            <!-- END WIDGET THUMB -->
                        </div>
                        @endforeach
                    </div>
                @endforeach
                @if (count($dataForMatrix))
                <div class="portlet box green" id="matrix">
                    <div class="portlet-title" style="height:1px !important; min-height:1px;">
                        <div class="caption">
                            <div class="form-inline margin-bottom-0">
                                <div class="input-group input-small date date-picker" data-date="{{\Carbon\Carbon::now()->format('d-m-Y')}}" data-date-format="dd-mm-yyyy" data-date-viewmode="years">
                                    <input type="text" id="header_date_selected" class="form-control reload_calendar_page" value="{{\Carbon\Carbon::now()->format('d-m-Y')}}" readonly="">
                                    <span class="input-group-btn">
                                        <button class="btn default" type="button">
                                            <i class="fa fa-calendar"></i>
                                        </button>
                                    </span>
                                </div>
                                <div class="input-group input-medium">
                                    <select id="header_location_selected" class="form-control reload_calendar_page" style="border-radius:4px;">
                                    @foreach($dataForMatrix['shop_locations'] as $item)
                                        <option value='{{$item['id']}}'> {{$item['name']}} </option>
                                    @endforeach
                                    </select>
                                </div>
                                <div class="input-group input-medium">
                                    <select id="header_activity_selected" class="form-control reload_calendar_page" style="border-radius:4px;">
                                    </select>
                                </div>
                                <div class="input-group input-medium">
                                    <input type="button" value="Send" class="btn blue" id="get_matrix">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div id="matrix_table" class="dataTables_wrapper">
                        
                        </div>
                    </div>
                </div>
                @endif
            @else
                <div class="m-heading-1 border-green m-bordered">
                    <h3 style="margin-bottom:0px;">No active location with booking history - non existing statistics</h3>
                </div>
            @endif

            @if (sizeof($stats)>0  && $showStats==true)
                <div class="m-heading-1 border-green m-bordered">
                    <h3 style="margin-bottom:0px;">Last 7 days statistics</h3>
                </div>

                <div class="row">
                @foreach ($stats as $key=>$single)
                    <div class="col-md-6 col-sm-6">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption font-red">
                                    <span class="caption-subject bold uppercase">{{ $single['location_name'] }}</span>
                                    <span class="caption-helper">bookings</span>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div id="dashboard_amchart_3_{{$key+1}}" class="CSSAnimationChart"></div>
                            </div>
                        </div>
                    </div>
                @endforeach

                    <div class="col-md-6 col-sm-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption font-green">
                                    <span class="caption-subject bold uppercase">Bookings By Locations</span>
                                    <span class="caption-helper">for today...</span>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div id="dashboard_amchart_4_1" class="CSSAnimationChart"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption font-green">
                                    <span class="caption-subject bold uppercase">Bookings by type</span>
                                    <span class="caption-helper">for today...</span>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div id="dashboard_amchart_4_2" class="CSSAnimationChart"></div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="m-heading-1 border-green m-bordered col-md-12">
                    <h3 style="margin-bottom:0px;">There are no last 7 days statistics</h3>
                </div>
            @endif
        @else
            <div class="m-heading-1 border-green m-bordered">
                <h3 style="margin-bottom:0px;">Welcome to backend admin for bookings</h3>
            </div>
        @endif
        <!-- END PAGE BASE CONTENT -->
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
    <script src="{{ asset('assets/global/plugins/moment.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/morris/morris.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/morris/raphael-min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/counterup/jquery.waypoints.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/counterup/jquery.counterup.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/amcharts/amcharts/amcharts.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/amcharts/amcharts/plugins/responsive/responsive.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/amcharts/amcharts/serial.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/amcharts/amcharts/pie.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/amcharts/amcharts/radar.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/amcharts/amcharts/themes/light.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/amcharts/amcharts/themes/patterns.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/amcharts/amcharts/themes/chalk.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/amcharts/ammap/ammap.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/amcharts/ammap/maps/js/worldLow.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/amcharts/amstockcharts/amstock.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/fullcalendar/fullcalendar.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/flot/jquery.flot.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/flot/jquery.flot.resize.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/flot/jquery.flot.categories.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/jquery-easypiechart/jquery.easypiechart.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/jquery.sparkline.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/jqvmap/jqvmap/jquery.vmap.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.russia.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.world.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.europe.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.germany.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.usa.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/jqvmap/jqvmap/data/jquery.vmap.sampledata.js') }}" type="text/javascript"></script>
@endsection

@section('themeBelowGlobalScripts')
    <script src="{{ asset('assets/global/scripts/app.min.js') }}" type="text/javascript"></script>
@endsection

@section('pageBelowLevelScripts')
@endsection

@section('themeBelowLayoutScripts')
    <script src="{{ asset('assets/layouts/layout4/scripts/layout.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/layouts/layout4/scripts/demo.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/layouts/global/scripts/quick-sidebar.min.js') }}" type="text/javascript"></script>
@endsection

@section('pageCustomJScripts')
    <script type="text/javascript">
        @if (Auth::user()->hasRole('owner'))
        var Dashboard = function() {
            return {
                initAmChart1: function() {
                    if (typeof(AmCharts) === 'undefined' || $('#dashboard_amchart_1').size() === 0) {
                        return;
                    }

                    var chartData = [{
                        "date": "2012-01-05",
                        "distance": 480,
                        "townName": "Miami",
                        "townName2": "Miami",
                        "townSize": 10,
                        "latitude": 25.83,
                        "duration": 501
                    }, {
                        "date": "2012-01-06",
                        "distance": 386,
                        "townName": "Tallahassee",
                        "townSize": 7,
                        "latitude": 30.46,
                        "duration": 443
                    }, {
                        "date": "2012-01-07",
                        "distance": 348,
                        "townName": "New Orleans",
                        "townSize": 10,
                        "latitude": 29.94,
                        "duration": 405
                    }, {
                        "date": "2012-01-08",
                        "distance": 238,
                        "townName": "Houston",
                        "townName2": "Houston",
                        "townSize": 16,
                        "latitude": 29.76,
                        "duration": 309
                    }, {
                        "date": "2012-01-09",
                        "distance": 218,
                        "townName": "Dalas",
                        "townSize": 17,
                        "latitude": 32.8,
                        "duration": 287
                    }, {
                        "date": "2012-01-10",
                        "distance": 349,
                        "townName": "Oklahoma City",
                        "townSize": 11,
                        "latitude": 35.49,
                        "duration": 485
                    }, {
                        "date": "2012-01-11",
                        "distance": 603,
                        "townName": "Kansas City",
                        "townSize": 10,
                        "latitude": 39.1,
                        "duration": 890
                    }, {
                        "date": "2012-01-12",
                        "distance": 534,
                        "townName": "Denver",
                        "townName2": "Denver",
                        "townSize": 18,
                        "latitude": 39.74,
                        "duration": 810
                    }, {
                        "date": "2012-01-13",
                        "townName": "Salt Lake City",
                        "townSize": 12,
                        "distance": 425,
                        "duration": 670,
                        "latitude": 40.75,
                        "alpha": 0.4
                    }, {
                        "date": "2012-01-14",
                        "latitude": 36.1,
                        "duration": 470,
                        "townName": "Las Vegas",
                        "townName2": "Las Vegas",
                        "bulletClass": "lastBullet"
                    }, {
                        "date": "2012-01-15"
                    }];
                    var chart = AmCharts.makeChart("dashboard_amchart_1", {
                        type: "serial",
                        fontSize: 12,
                        fontFamily: "Open Sans",
                        dataDateFormat: "YYYY-MM-DD",
                        dataProvider: chartData,

                        addClassNames: true,
                        startDuration: 1,
                        color: "#6c7b88",
                        marginLeft: 0,

                        categoryField: "date",
                        categoryAxis: {
                            parseDates: true,
                            minPeriod: "DD",
                            autoGridCount: false,
                            gridCount: 50,
                            gridAlpha: 0.1,
                            gridColor: "#FFFFFF",
                            axisColor: "#555555",
                            dateFormats: [{
                                period: 'DD',
                                format: 'DD'
                            }, {
                                period: 'WW',
                                format: 'MMM DD'
                            }, {
                                period: 'MM',
                                format: 'MMM'
                            }, {
                                period: 'YYYY',
                                format: 'YYYY'
                            }]
                        },

                        valueAxes: [{
                            id: "a1",
                            title: "distance",
                            gridAlpha: 0,
                            axisAlpha: 0
                        }, {
                            id: "a2",
                            position: "right",
                            gridAlpha: 0,
                            axisAlpha: 0,
                            labelsEnabled: false
                        }, {
                            id: "a3",
                            title: "duration",
                            position: "right",
                            gridAlpha: 0,
                            axisAlpha: 0,
                            inside: true,
                            duration: "mm",
                            durationUnits: {
                                DD: "d. ",
                                hh: "h ",
                                mm: "min",
                                ss: ""
                            }
                        }],
                        graphs: [{
                            id: "g1",
                            valueField: "distance",
                            title: "distance",
                            type: "column",
                            fillAlphas: 0.7,
                            valueAxis: "a1",
                            balloonText: "[[value]] miles",
                            legendValueText: "[[value]] mi",
                            legendPeriodValueText: "total: [[value.sum]] mi",
                            lineColor: "#08a3cc",
                            alphaField: "alpha",
                        }, {
                            id: "g2",
                            valueField: "latitude",
                            classNameField: "bulletClass",
                            title: "latitude/city",
                            type: "line",
                            valueAxis: "a2",
                            lineColor: "#786c56",
                            lineThickness: 1,
                            legendValueText: "[[description]]/[[value]]",
                            descriptionField: "townName",
                            bullet: "round",
                            bulletSizeField: "townSize",
                            bulletBorderColor: "#02617a",
                            bulletBorderAlpha: 1,
                            bulletBorderThickness: 2,
                            bulletColor: "#89c4f4",
                            labelText: "[[townName2]]",
                            labelPosition: "right",
                            balloonText: "latitude:[[value]]",
                            showBalloon: true,
                            animationPlayed: true,
                        }, {
                            id: "g3",
                            title: "duration",
                            valueField: "duration",
                            type: "line",
                            valueAxis: "a3",
                            lineAlpha: 0.8,
                            lineColor: "#e26a6a",
                            balloonText: "[[value]]",
                            lineThickness: 1,
                            legendValueText: "[[value]]",
                            bullet: "square",
                            bulletBorderColor: "#e26a6a",
                            bulletBorderThickness: 1,
                            bulletBorderAlpha: 0.8,
                            dashLengthField: "dashLength",
                            animationPlayed: true
                        }],

                        chartCursor: {
                            zoomable: false,
                            categoryBalloonDateFormat: "DD",
                            cursorAlpha: 0,
                            categoryBalloonColor: "#e26a6a",
                            categoryBalloonAlpha: 0.8,
                            valueBalloonsEnabled: false
                        },
                        legend: {
                            bulletType: "round",
                            equalWidths: false,
                            valueWidth: 120,
                            useGraphSettings: true,
                            color: "#6c7b88"
                        }
                    });
                },

                initAmChart2: function() {
                    if (typeof(AmCharts) === 'undefined' || $('#dashboard_amchart_2').size() === 0) {
                        return;
                    }

                    // svg path for target icon
                    var targetSVG = "M9,0C4.029,0,0,4.029,0,9s4.029,9,9,9s9-4.029,9-9S13.971,0,9,0z M9,15.93 c-3.83,0-6.93-3.1-6.93-6.93S5.17,2.07,9,2.07s6.93,3.1,6.93,6.93S12.83,15.93,9,15.93 M12.5,9c0,1.933-1.567,3.5-3.5,3.5S5.5,10.933,5.5,9S7.067,5.5,9,5.5 S12.5,7.067,12.5,9z";
                    // svg path for plane icon
                    var planeSVG = "M19.671,8.11l-2.777,2.777l-3.837-0.861c0.362-0.505,0.916-1.683,0.464-2.135c-0.518-0.517-1.979,0.278-2.305,0.604l-0.913,0.913L7.614,8.804l-2.021,2.021l2.232,1.061l-0.082,0.082l1.701,1.701l0.688-0.687l3.164,1.504L9.571,18.21H6.413l-1.137,1.138l3.6,0.948l1.83,1.83l0.947,3.598l1.137-1.137V21.43l3.725-3.725l1.504,3.164l-0.687,0.687l1.702,1.701l0.081-0.081l1.062,2.231l2.02-2.02l-0.604-2.689l0.912-0.912c0.326-0.326,1.121-1.789,0.604-2.306c-0.452-0.452-1.63,0.101-2.135,0.464l-0.861-3.838l2.777-2.777c0.947-0.947,3.599-4.862,2.62-5.839C24.533,4.512,20.618,7.163,19.671,8.11z";

                    var map = AmCharts.makeChart("dashboard_amchart_2", {
                        type: "map",
                        "theme": "light",
                        pathToImages: "../assets/global/plugins/amcharts/ammap/images/",

                        dataProvider: {
                            map: "worldLow",
                            linkToObject: "london",
                            images: [{
                                id: "london",
                                color: "#009dc7",
                                svgPath: targetSVG,
                                title: "London",
                                latitude: 51.5002,
                                longitude: -0.1262,
                                scale: 1.5,
                                zoomLevel: 2.74,
                                zoomLongitude: -20.1341,
                                zoomLatitude: 49.1712,

                                lines: [{
                                    latitudes: [51.5002, 50.4422],
                                    longitudes: [-0.1262, 30.5367]
                                }, {
                                    latitudes: [51.5002, 46.9480],
                                    longitudes: [-0.1262, 7.4481]
                                }, {
                                    latitudes: [51.5002, 59.3328],
                                    longitudes: [-0.1262, 18.0645]
                                }, {
                                    latitudes: [51.5002, 40.4167],
                                    longitudes: [-0.1262, -3.7033]
                                }, {
                                    latitudes: [51.5002, 46.0514],
                                    longitudes: [-0.1262, 14.5060]
                                }, {
                                    latitudes: [51.5002, 48.2116],
                                    longitudes: [-0.1262, 17.1547]
                                }, {
                                    latitudes: [51.5002, 44.8048],
                                    longitudes: [-0.1262, 20.4781]
                                }, {
                                    latitudes: [51.5002, 55.7558],
                                    longitudes: [-0.1262, 37.6176]
                                }, {
                                    latitudes: [51.5002, 38.7072],
                                    longitudes: [-0.1262, -9.1355]
                                }, {
                                    latitudes: [51.5002, 54.6896],
                                    longitudes: [-0.1262, 25.2799]
                                }, {
                                    latitudes: [51.5002, 64.1353],
                                    longitudes: [-0.1262, -21.8952]
                                }, {
                                    latitudes: [51.5002, 40.4300],
                                    longitudes: [-0.1262, -74.0000]
                                }],

                                images: [{
                                    label: "Flights from London",
                                    svgPath: planeSVG,
                                    left: 100,
                                    top: 45,
                                    labelShiftY: 5,
                                    color: "#d93d5e",
                                    labelColor: "#d93d5e",
                                    labelRollOverColor: "#d93d5e",
                                    labelFontSize: 20
                                }, {
                                    label: "show flights from Vilnius",
                                    left: 106,
                                    top: 70,
                                    labelColor: "#6c7b88",
                                    labelRollOverColor: "#d93d5e",
                                    labelFontSize: 11,
                                    linkToObject: "vilnius"
                                }]
                            },

                                {
                                    id: "vilnius",
                                    color: "#009dc7",
                                    svgPath: targetSVG,
                                    title: "Vilnius",
                                    latitude: 54.6896,
                                    longitude: 25.2799,
                                    scale: 1.5,
                                    zoomLevel: 4.92,
                                    zoomLongitude: 15.4492,
                                    zoomLatitude: 50.2631,

                                    lines: [{
                                        latitudes: [54.6896, 50.8371],
                                        longitudes: [25.2799, 4.3676]
                                    }, {
                                        latitudes: [54.6896, 59.9138],
                                        longitudes: [25.2799, 10.7387]
                                    }, {
                                        latitudes: [54.6896, 40.4167],
                                        longitudes: [25.2799, -3.7033]
                                    }, {
                                        latitudes: [54.6896, 50.0878],
                                        longitudes: [25.2799, 14.4205]
                                    }, {
                                        latitudes: [54.6896, 48.2116],
                                        longitudes: [25.2799, 17.1547]
                                    }, {
                                        latitudes: [54.6896, 44.8048],
                                        longitudes: [25.2799, 20.4781]
                                    }, {
                                        latitudes: [54.6896, 55.7558],
                                        longitudes: [25.2799, 37.6176]
                                    }, {
                                        latitudes: [54.6896, 37.9792],
                                        longitudes: [25.2799, 23.7166]
                                    }, {
                                        latitudes: [54.6896, 54.6896],
                                        longitudes: [25.2799, 25.2799]
                                    }, {
                                        latitudes: [54.6896, 51.5002],
                                        longitudes: [25.2799, -0.1262]
                                    }, {
                                        latitudes: [54.6896, 53.3441],
                                        longitudes: [25.2799, -6.2675]
                                    }],

                                    images: [{
                                        label: "Flights from Vilnius",
                                        svgPath: planeSVG,
                                        left: 100,
                                        top: 45,
                                        labelShiftY: 5,
                                        color: "#d93d5e",
                                        labelColor: "#d93d5e",
                                        labelRollOverColor: "#d93d5e",
                                        labelFontSize: 20
                                    }, {
                                        label: "show flights from London",
                                        left: 106,
                                        top: 70,
                                        labelColor: "#009dc7",
                                        labelRollOverColor: "#d93d5e",
                                        labelFontSize: 11,
                                        linkToObject: "london"
                                    }]
                                }, {
                                    svgPath: targetSVG,
                                    title: "Brussels",
                                    latitude: 50.8371,
                                    longitude: 4.3676
                                }, {
                                    svgPath: targetSVG,
                                    title: "Prague",
                                    latitude: 50.0878,
                                    longitude: 14.4205
                                }, {
                                    svgPath: targetSVG,
                                    title: "Athens",
                                    latitude: 37.9792,
                                    longitude: 23.7166
                                }, {
                                    svgPath: targetSVG,
                                    title: "Reykjavik",
                                    latitude: 64.1353,
                                    longitude: -21.8952
                                }, {
                                    svgPath: targetSVG,
                                    title: "Dublin",
                                    latitude: 53.3441,
                                    longitude: -6.2675
                                }, {
                                    svgPath: targetSVG,
                                    title: "Oslo",
                                    latitude: 59.9138,
                                    longitude: 10.7387
                                }, {
                                    svgPath: targetSVG,
                                    title: "Lisbon",
                                    latitude: 38.7072,
                                    longitude: -9.1355
                                }, {
                                    svgPath: targetSVG,
                                    title: "Moscow",
                                    latitude: 55.7558,
                                    longitude: 37.6176
                                }, {
                                    svgPath: targetSVG,
                                    title: "Belgrade",
                                    latitude: 44.8048,
                                    longitude: 20.4781
                                }, {
                                    svgPath: targetSVG,
                                    title: "Bratislava",
                                    latitude: 48.2116,
                                    longitude: 17.1547
                                }, {
                                    svgPath: targetSVG,
                                    title: "Ljubljana",
                                    latitude: 46.0514,
                                    longitude: 14.5060
                                }, {
                                    svgPath: targetSVG,
                                    title: "Madrid",
                                    latitude: 40.4167,
                                    longitude: -3.7033
                                }, {
                                    svgPath: targetSVG,
                                    title: "Stockholm",
                                    latitude: 59.3328,
                                    longitude: 18.0645
                                }, {
                                    svgPath: targetSVG,
                                    title: "Bern",
                                    latitude: 46.9480,
                                    longitude: 7.4481
                                }, {
                                    svgPath: targetSVG,
                                    title: "Kiev",
                                    latitude: 50.4422,
                                    longitude: 30.5367
                                }, {
                                    svgPath: targetSVG,
                                    title: "Paris",
                                    latitude: 48.8567,
                                    longitude: 2.3510
                                }, {
                                    svgPath: targetSVG,
                                    title: "New York",
                                    latitude: 40.43,
                                    longitude: -74
                                }
                            ]
                        },

                        zoomControl: {
                            buttonFillColor: "#dddddd"
                        },

                        areasSettings: {
                            unlistedAreasColor: "#15A892"
                        },

                        imagesSettings: {
                            color: "#d93d5e",
                            rollOverColor: "#d93d5e",
                            selectedColor: "#009dc7"
                        },

                        linesSettings: {
                            color: "#d93d5e",
                            alpha: 0.4
                        },


                        backgroundZoomsToTop: true,
                        linesAboveImages: true,

                        "export": {
                            "enabled": true,
                            "libs": {
                                "path": "http://www.amcharts.com/lib/3/plugins/export/libs/"
                            }
                        }
                    });
                },

                @if (sizeof($stats)>0)
                    @foreach ($stats as $key=>$single)
                    initAmChart3_{{$key+1}}: function() {
                    if (typeof(AmCharts) === 'undefined' || $('#dashboard_amchart_3_{{$key+1}}').size() === 0) {
                        return;
                    }

                    var chart = AmCharts.makeChart("dashboard_amchart_3_{{$key+1}}", {
                        "type": "serial",
                        "addClassNames": true,
                        "theme": "light",
                        "path": "../assets/global/plugins/amcharts/ammap/images/",
                        "autoMargins": false,
                        "marginLeft": 30,
                        "marginRight": 8,
                        "marginTop": 10,
                        "marginBottom": 26,
                        "balloon": {
                            "adjustBorderColor": false,
                            "horizontalPadding": 10,
                            "verticalPadding": 8,
                            "color": "#ffffff"
                        },

                        "dataProvider": [
                            @foreach ($single['last_seven_days'] as $key1=>$val1)
                            {
                                "year": '{{ \Carbon\Carbon::today()->addDays($key1-7)->format('d.m') }}',
                                "income": {{ $val1['membership'] }},
                                "expenses": {{ $val1['all'] }}
                            },
                            @endforeach
                            ],
                        "valueAxes": [{
                                "axisAlpha": 0,
                                "position": "left"
                            }],
                        "startDuration": 1,
                        "graphs": [
                            {
                            "alphaField": "alpha",
                            "balloonText": "<span style='font-size:12px;'>[[title]] for [[category]]:<br><span style='font-size:20px;'>[[value]]</span> [[additional]]</span>",
                            "fillAlphas": 1,
                            "title": "Membership",
                            "type": "column",
                            "valueField": "income",
                            "dashLengthField": "dashLengthColumn"
                            },
                            {
                            "id": "graph2",
                            "balloonText": "<span style='font-size:12px;'>[[title]] for [[category]]:<br><span style='font-size:20px;'>[[value]]</span> [[additional]]</span>",
                            "bullet": "round",
                            "lineThickness": 3,
                            "bulletSize": 7,
                            "bulletBorderAlpha": 1,
                            "bulletColor": "#FFFFFF",
                            "useLineColorForBulletBorder": true,
                            "bulletBorderThickness": 3,
                            "fillAlphas": 0,
                            "lineAlpha": 1,
                            "title": "All",
                            "valueField": "expenses"
                        }],
                        "categoryField": "year",
                        "categoryAxis": {
                            "gridPosition": "start",
                            "axisAlpha": 0,
                            "tickLength": 0
                        },
                        "export": {
                            "enabled": true
                        }
                    });
                },
                    @endforeach
                @endif

                initAmChart4_1: function() {
                    if (typeof(AmCharts) === 'undefined' || $('#dashboard_amchart_4_1').size() === 0) {
                        return;
                    }

                    var chart = AmCharts.makeChart("dashboard_amchart_4_1", {
                        "type": "pie",
                        "theme": "light",
                        "path": "../assets/global/plugins/amcharts/ammap/images/",
                        "dataProvider": [
                        @foreach($totalToday as $single)
                            {"country": "{!! $single['name'] !!}", "value":{{ $single['amount'] }} },
                        @endforeach
                        ],
                        "valueField": "value",
                        "titleField": "country",
                        "outlineAlpha": 0.4,
                        "depth3D": 0,
                        "balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
                        "angle": 0,
                        "export": {
                            "enabled": true
                        },
                        labelsEnabled: true,
                        autoMargins: false,
                        marginTop: 0,
                        marginBottom: 0,
                        marginLeft: 0,
                        marginRight: 0,
                        pullOutRadius: 0,
                        "responsive": {
                            "enabled": true,
                            "rules": [
                                // at 400px wide, we hide legend
                                {
                                    "maxWidth": 400,
                                    "overrides": {
                                        labelsEnabled: false
                                    }
                                },

                                // at 300px or less, we move value axis labels inside plot area
                                // the legend is still hidden because the above rule is still applicable
                                {
                                    "maxWidth": 300,
                                    "overrides": {
                                        "valueAxes": {
                                            "inside": true
                                        },
                                    }
                                },

                                // at 200 px we hide value axis labels altogether
                                {
                                    "maxWidth": 200,
                                    "overrides": {
                                        "valueAxes": {
                                            "labelsEnabled": false
                                        }
                                    }
                                }

                            ]
                        }
                    });
                    jQuery('.chart-input').off().on('input change', function() {
                        var property = jQuery(this).data('property');
                        var target = chart;
                        var value = Number(this.value);
                        chart.startDuration = 0;

                        if (property == 'innerRadius') {
                            value += "%";
                        }

                        target[property] = value;
                        chart.validateNow();
                    });
                },

                initAmChart4_2: function() {
                    if (typeof(AmCharts) === 'undefined' || $('#dashboard_amchart_4_2').size() === 0) {
                        return;
                    }

                    var chart = AmCharts.makeChart("dashboard_amchart_4_2", {
                        "type": "pie",
                        "theme": "light",
                        "path": "../assets/global/plugins/amcharts/ammap/images/",
                        "dataProvider": [
                        @foreach ($totalPerType as $key=>$val)
                            { "country": "{!! $key !!}", "value": {{$val}} },
                        @endforeach
                        ],
                        "valueField": "value",
                        "titleField": "country",
                        "outlineAlpha": 0.4,
                        "depth3D": 0,
                        "balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
                        "angle": 0,
                        "export": {
                            "enabled": true
                        },
                        labelsEnabled: true,
                        autoMargins: false,
                        marginTop: 0,
                        marginBottom: 0,
                        marginLeft: 0,
                        marginRight: 0,
                        pullOutRadius: 0,
                        "responsive": {
                            "enabled": true,
                            "rules": [
                                // at 400px wide, we hide legend
                                {
                                    "maxWidth": 400,
                                    "overrides": {
                                        labelsEnabled: false
                                    }
                                },

                                // at 300px or less, we move value axis labels inside plot area
                                // the legend is still hidden because the above rule is still applicable
                                {
                                    "maxWidth": 300,
                                    "overrides": {
                                        "valueAxes": {
                                            "inside": true
                                        },
                                    }
                                },

                                // at 200 px we hide value axis labels altogether
                                {
                                    "maxWidth": 200,
                                    "overrides": {
                                        "valueAxes": {
                                            "labelsEnabled": false
                                        }
                                    }
                                }

                            ]
                        }
                    });
                    jQuery('.chart-input').off().on('input change', function() {
                        var property = jQuery(this).data('property');
                        var target = chart;
                        var value = Number(this.value);
                        chart.startDuration = 0;

                        if (property == 'innerRadius') {
                            value += "%";
                        }

                        target[property] = value;
                        chart.validateNow();
                    });
                },

                initWorldMapStats: function() {
                    if ($('#mapplic').size() === 0) {
                        return;
                    }

                    $('#mapplic').mapplic({
                        source: '../assets/global/plugins/mapplic/world.json',
                        height: 265,
                        animate: false,
                        sidebar: false,
                        minimap: false,
                        locations: true,
                        deeplinking: true,
                        fullscreen: false,
                        hovertip: true,
                        zoombuttons: false,
                        clearbutton: false,
                        developer: false,
                        maxscale: 2,
                        skin: 'mapplic-dark',
                        zoom: true
                    });

                    $("#widget_sparkline_bar").sparkline([8, 7, 9, 8.5, 8, 8.2, 8, 8.5, 9, 8, 9], {
                        type: 'bar',
                        width: '100',
                        barWidth: 5,
                        height: '30',
                        barColor: '#4db3a4',
                        negBarColor: '#e02222'
                    });

                    $("#widget_sparkline_bar2").sparkline([8, 7, 9, 8.5, 8, 8.2, 8, 8.5, 9, 8, 9], {
                        type: 'bar',
                        width: '100',
                        barWidth: 5,
                        height: '30',
                        barColor: '#f36a5a',
                        negBarColor: '#e02222'
                    });

                    $("#widget_sparkline_bar3").sparkline([8, 7, 9, 8.5, 8, 8.2, 8, 8.5, 9, 8, 9], {
                        type: 'bar',
                        width: '100',
                        barWidth: 5,
                        height: '30',
                        barColor: '#5b9bd1',
                        negBarColor: '#e02222'
                    });

                    $("#widget_sparkline_bar4").sparkline([8, 7, 9, 8.5, 8, 8.2, 8, 8.5, 9, 8, 9], {
                        type: 'bar',
                        width: '100',
                        barWidth: 5,
                        height: '30',
                        barColor: '#9a7caf',
                        negBarColor: '#e02222'
                    });
                },
                
                handleDatePickers: function () {
                    if (jQuery().datepicker) {
                        $('.date-picker').datepicker({
                            rtl: App.isRTL(),
                            orientation: "left",
                            autoclose: true,
                            daysOfWeekHighlighted: "0",
                            weekStart:1,
                        });
                    }
                },
                
                initMatrix: function(){
                    jQuery('#get_matrix').click(function(){
                        getMatrix();
                    });
                    jQuery('#header_location_selected').on('change', function(){
                        getMatrixActivities();
                    });
                    getMatrixActivities(loadMatrix = true);
                    
                    function getMatrixActivities(loadMatrix){
                        blockContent('.caption');
                        jQuery('#header_activity_selected').html('');
                        $.ajax({
                            url: '{{route('ajax/get_activity_for_matrix')}}',
                            type: "post",
                            cache: false,
                            data: {
                                location: jQuery('#header_location_selected').val(),
                            },
                            success: function (data) {
                                for(var i=0; i<data.cats.length; i++){
                                    jQuery('#header_activity_selected').append("<option value='"+data.cats[i].id+"'>"+data.cats[i].name+"</option>");
                                }
                                if (loadMatrix == true){
                                    getMatrix();
                                } 
                                jQuery('.caption').unblock();
                           }
                       });
                    };
                    function getMatrix(){
                        blockContent('#matrix');
                        var data = {
                            date: jQuery('#header_date_selected').val(),
                            location: jQuery('#header_location_selected').val(),
                            activity: jQuery('#header_activity_selected').val()
                        };
                        $.ajax({
                           url: '{{route('ajax/get_resource_intervals_matrix')}}',
                           type: "post",
                           cache: false,
                           data: data,
                           success: function (data) {
                               jQuery('#matrix_table').html(data);
                               jQuery('#matrix').unblock();
                           }
                       });
                    };
                    
                    
                    function blockContent(selector){
                        var message =  "<div class='loading-message loading-message-boxed'>	<img src='/assets/global/img/loading-spinner-grey.gif' align=''><span>&nbsp;&nbsp;Processing...</span></div>";
                        $(selector).block({ 
                            message: message, 
                            overlayCSS: { 
                                backgroundColor: '#555555',
                                opacity : '0.05'
                            },
                            css: {
                                border: 'none',
                                backgroundColor: 'none'
                            }
                        });
                    }
                    
                },
                
                getMatrix: function (){
                    
                },
                
                

                init: function() {
                    this.handleDatePickers();
                    this.initAmChart1();
                    this.initAmChart2();
                @if (sizeof($stats)>0)
                    @foreach ($stats as $key=>$single)
                    this.initAmChart3_{{ $key + 1 }}();
                    @endforeach
                @endif
                    this.initAmChart4_1();
                    this.initAmChart4_2();
                    this.initMatrix();
                }
            };
        }();

        if (App.isAngularJsApp() === false) {
            jQuery(document).ready(function() {
                Dashboard.init(); // init metronic core componets
            });
        }
        @endif
    </script>
@endsection