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
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="dashboard-stat blue">
                    <div class="visual">
                        <i class="fa fa-comments"></i>
                    </div>
                    <div class="details">
                        <div class="number">
                            <span data-counter="counterup" data-value="1349">1349</span>
                        </div>
                        <div class="desc"> New players this week </div>
                    </div>
                    <a class="more" href="javascript:;"> View more
                        <i class="m-icon-swapright m-icon-white"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="dashboard-stat red">
                    <div class="visual">
                        <i class="fa fa-bar-chart-o"></i>
                    </div>
                    <div class="details">
                        <div class="number">
                            <span data-counter="counterup" data-value="4500">4500</span>$ </div>
                        <div class="desc"> Income this week </div>
                    </div>
                    <a class="more" href="javascript:;"> View more
                        <i class="m-icon-swapright m-icon-white"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="dashboard-stat green">
                    <div class="visual">
                        <i class="fa fa-shopping-cart"></i>
                    </div>
                    <div class="details">
                        <div class="number">
                            <span data-counter="counterup" data-value="49">49</span>
                        </div>
                        <div class="desc"> Upcomming Tournaments </div>
                    </div>
                    <a class="more" href="javascript:;"> View more
                        <i class="m-icon-swapright m-icon-white"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="dashboard-stat purple">
                    <div class="visual">
                        <i class="fa fa-globe"></i>
                    </div>
                    <div class="details">
                        <div class="number"> +
                            <span data-counter="counterup" data-value="890"></span> </div>
                        <div class="desc"> Registered players </div>
                    </div>
                    <a class="more" href="javascript:;"> View more
                        <i class="m-icon-swapright m-icon-white"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-sm-6">
                <!-- BEGIN PORTLET-->
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-bar-chart font-green"></i>
                            <span class="caption-subject font-green bold uppercase">Squash Players</span>
                            <span class="caption-helper">by gender and age</span>
                        </div>
                        <div class="actions">
                            <div class="btn-group btn-group-devided" data-toggle="buttons">
                                <label class="btn red btn-outline btn-circle btn-sm active">
                                    <input type="radio" name="options" class="toggle" id="option1">Male</label>
                                <label class="btn red btn-outline btn-circle btn-sm">
                                    <input type="radio" name="options" class="toggle" id="option2">Female</label>
                            </div>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div id="site_statistics_loading">
                            <img src="../assets/global/img/loading.gif" alt="loading" /> </div>
                        <div id="site_statistics_content" class="display-none">
                            <div id="site_statistics" class="chart" style="height:370px;"> </div>
                        </div>
                    </div>
                </div>
                <!-- END PORTLET-->
            </div>
            <div class="col-md-6 col-sm-6">
                <!-- BEGIN INTERACTIVE CHART PORTLET-->
                <div class="portlet light portlet-fit bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-settings font-dark"></i>
                            <span class="caption-subject font-dark sbold uppercase">Members growth</span>
                            <span class="caption-helper">last 30 days</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div id="chart_2" class="chart" style="height:360px;"> </div>
                    </div>
                </div>
                <!-- END INTERACTIVE CHART PORTLET-->
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-sm-6">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-red">
                            <span class="caption-subject bold uppercase">Number of club members</span>
                            <span class="caption-helper">last 5 years...</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div id="dashboard_amchart_3_1" class="CSSAnimationChart"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6">
                <div class="portlet light portlet-fit bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class=" icon-layers font-red"></i>
                            <span class="caption-subject font-red bold uppercase">Overall bookings</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <h4>Percentage by type</h4>
                        <div id="pie_chart_9" class="chart" style="height:447px;"> </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="portlet light portlet-fit bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class=" icon-layers font-blue"></i>
                            <span class="caption-subject font-blue bold uppercase">Total players</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <h4>Federation Members vs non-Federation Members</h4>
                        <div id="pie_chart_8" class="chart"> </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="portlet light portlet-fit bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class=" icon-layers font-blue"></i>
                            <span class="caption-subject font-blue bold uppercase">Total courts [entire country]</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <h4>Percentage of courts per geographic area.</h4>
                        <div id="pie_chart_8_1" class="chart"> </div>
                    </div>
                </div>
            </div>
        </div>
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

    <script src="../assets/global/plugins/flot/jquery.flot.min.js" type="text/javascript"></script>
    <script src="../assets/global/plugins/flot/jquery.flot.resize.min.js" type="text/javascript"></script>
    <script src="../assets/global/plugins/flot/jquery.flot.categories.min.js" type="text/javascript"></script>
    <script src="../assets/global/plugins/flot/jquery.flot.pie.min.js" type="text/javascript"></script>
    <script src="../assets/global/plugins/flot/jquery.flot.stack.min.js" type="text/javascript"></script>
    <script src="../assets/global/plugins/flot/jquery.flot.crosshair.min.js" type="text/javascript"></script>
    <script src="../assets/global/plugins/flot/jquery.flot.axislabels.js" type="text/javascript"></script>
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
        var Dashboard = function() {

            return {

                initJQVMAP: function() {
                    if (!jQuery().vectorMap) {
                        return;
                    }

                    var showMap = function(name) {
                        jQuery('.vmaps').hide();
                        jQuery('#vmap_' + name).show();
                    }

                    var setMap = function(name) {
                        var data = {
                            map: 'world_en',
                            backgroundColor: null,
                            borderColor: '#333333',
                            borderOpacity: 0.5,
                            borderWidth: 1,
                            color: '#c6c6c6',
                            enableZoom: true,
                            hoverColor: '#c9dfaf',
                            hoverOpacity: null,
                            values: sample_data,
                            normalizeFunction: 'linear',
                            scaleColors: ['#b6da93', '#909cae'],
                            selectedColor: '#c9dfaf',
                            selectedRegion: null,
                            showTooltip: true,
                            onLabelShow: function(event, label, code) {

                            },
                            onRegionOver: function(event, code) {
                                if (code == 'ca') {
                                    event.preventDefault();
                                }
                            },
                            onRegionClick: function(element, code, region) {
                                var message = 'You clicked "' + region + '" which has the code: ' + code.toUpperCase();
                                alert(message);
                            }
                        };

                        data.map = name + '_en';
                        var map = jQuery('#vmap_' + name);
                        if (!map) {
                            return;
                        }
                        map.width(map.parent().parent().width());
                        map.show();
                        map.vectorMap(data);
                        map.hide();
                    }

                    setMap("world");
                    setMap("usa");
                    setMap("europe");
                    setMap("russia");
                    setMap("germany");
                    showMap("world");

                    jQuery('#regional_stat_world').click(function() {
                        showMap("world");
                    });

                    jQuery('#regional_stat_usa').click(function() {
                        showMap("usa");
                    });

                    jQuery('#regional_stat_europe').click(function() {
                        showMap("europe");
                    });
                    jQuery('#regional_stat_russia').click(function() {
                        showMap("russia");
                    });
                    jQuery('#regional_stat_germany').click(function() {
                        showMap("germany");
                    });

                    $('#region_statistics_loading').hide();
                    $('#region_statistics_content').show();

                    App.addResizeHandler(function() {
                        jQuery('.vmaps').each(function() {
                            var map = jQuery(this);
                            map.width(map.parent().width());
                        });
                    });
                },

                initCalendar: function() {
                    if (!jQuery().fullCalendar) {
                        return;
                    }

                    var date = new Date();
                    var d = date.getDate();
                    var m = date.getMonth();
                    var y = date.getFullYear();

                    var h = {};

                    if ($('#calendar').width() <= 400) {
                        $('#calendar').addClass("mobile");
                        h = {
                            left: 'title, prev, next',
                            center: '',
                            right: 'today,month,agendaWeek,agendaDay'
                        };
                    } else {
                        $('#calendar').removeClass("mobile");
                        if (App.isRTL()) {
                            h = {
                                right: 'title',
                                center: '',
                                left: 'prev,next,today,month,agendaWeek,agendaDay'
                            };
                        } else {
                            h = {
                                left: 'title',
                                center: '',
                                right: 'prev,next,today,month,agendaWeek,agendaDay'
                            };
                        }
                    }



                    $('#calendar').fullCalendar('destroy'); // destroy the calendar
                    $('#calendar').fullCalendar({ //re-initialize the calendar
                        disableDragging: false,
                        header: h,
                        editable: true,
                        events: [{
                            title: 'All Day',
                            start: new Date(y, m, 1),
                            backgroundColor: App.getBrandColor('yellow')
                        }, {
                            title: 'Long Event',
                            start: new Date(y, m, d - 5),
                            end: new Date(y, m, d - 2),
                            backgroundColor: App.getBrandColor('blue')
                        }, {
                            title: 'Repeating Event',
                            start: new Date(y, m, d - 3, 16, 0),
                            allDay: false,
                            backgroundColor: App.getBrandColor('red')
                        }, {
                            title: 'Repeating Event',
                            start: new Date(y, m, d + 6, 16, 0),
                            allDay: false,
                            backgroundColor: App.getBrandColor('green')
                        }, {
                            title: 'Meeting',
                            start: new Date(y, m, d + 9, 10, 30),
                            allDay: false
                        }, {
                            title: 'Lunch',
                            start: new Date(y, m, d, 14, 0),
                            end: new Date(y, m, d, 14, 0),
                            backgroundColor: App.getBrandColor('grey'),
                            allDay: false
                        }, {
                            title: 'Birthday',
                            start: new Date(y, m, d + 1, 19, 0),
                            end: new Date(y, m, d + 1, 22, 30),
                            backgroundColor: App.getBrandColor('purple'),
                            allDay: false
                        }, {
                            title: 'Click for Google',
                            start: new Date(y, m, 28),
                            end: new Date(y, m, 29),
                            backgroundColor: App.getBrandColor('yellow'),
                            url: 'http://google.com/'
                        }]
                    });
                },

                initCharts: function() {
                    if (!jQuery.plot) {
                        return;
                    }

                    function showChartTooltip(x, y, xValue, yValue) {
                        $('<div id="tooltip" class="chart-tooltip">' + yValue + '<\/div>').css({
                            position: 'absolute',
                            display: 'none',
                            top: y - 40,
                            left: x - 40,
                            border: '0px solid #ccc',
                            padding: '2px 6px',
                            'background-color': '#fff'
                        }).appendTo("body").fadeIn(200);
                    }

                    var data = [];
                    var totalPoints = 250;

                    // random data generator for plot charts

                    function getRandomData() {
                        if (data.length > 0) data = data.slice(1);
                        // do a random walk
                        while (data.length < totalPoints) {
                            var prev = data.length > 0 ? data[data.length - 1] : 50;
                            var y = prev + Math.random() * 10 - 5;
                            if (y < 0) y = 0;
                            if (y > 100) y = 100;
                            data.push(y);
                        }
                        // zip the generated y values with the x values
                        var res = [];
                        for (var i = 0; i < data.length; ++i) res.push([i, data[i]])
                        return res;
                    }

                    function randValue() {
                        return (Math.floor(Math.random() * (1 + 50 - 20))) + 10;
                    }

                    var visitors = [
                        ['under 14',1500],
                        ['14-18',   2500],
                        ['18-24',   2700],
                        ['24-30',   2950],
                        ['30-40',   2750],
                        ['40-50',   2350],
                        ['50-60',   1500],
                        ['60-70',   1300],
                        ['over 70', 1600]
                    ];


                    if ($('#site_statistics').size() != 0) {

                        $('#site_statistics_loading').hide();
                        $('#site_statistics_content').show();

                        var plot_statistics = $.plot($("#site_statistics"), [{
                                data: visitors,
                                lines: {
                                    fill: 0.6,
                                    lineWidth: 0
                                },
                                color: ['#f89f9f']
                            }, {
                                data: visitors,
                                points: {
                                    show: true,
                                    fill: true,
                                    radius: 5,
                                    fillColor: "#f89f9f",
                                    lineWidth: 3
                                },
                                color: '#fff',
                                shadowSize: 0
                            }],

                            {
                                xaxis: {
                                    tickLength: 0,
                                    tickDecimals: 0,
                                    mode: "categories",
                                    min: 0,
                                    font: {
                                        lineHeight: 14,
                                        style: "normal",
                                        variant: "small-caps",
                                        color: "#6F7B8A"
                                    }
                                },
                                yaxis: {
                                    ticks: 5,
                                    tickDecimals: 0,
                                    tickColor: "#eee",
                                    font: {
                                        lineHeight: 14,
                                        style: "normal",
                                        variant: "small-caps",
                                        color: "#6F7B8A"
                                    }
                                },
                                grid: {
                                    hoverable: true,
                                    clickable: true,
                                    tickColor: "#eee",
                                    borderColor: "#eee",
                                    borderWidth: 1
                                }
                            });

                        var previousPoint = null;
                        $("#site_statistics").bind("plothover", function(event, pos, item) {
                            $("#x").text(pos.x.toFixed(2));
                            $("#y").text(pos.y.toFixed(2));
                            if (item) {
                                if (previousPoint != item.dataIndex) {
                                    previousPoint = item.dataIndex;

                                    $("#tooltip").remove();
                                    var x = item.datapoint[0].toFixed(2),
                                        y = item.datapoint[1].toFixed(2);

                                    showChartTooltip(item.pageX, item.pageY, item.datapoint[0], item.datapoint[1] + ' visits');
                                }
                            } else {
                                $("#tooltip").remove();
                                previousPoint = null;
                            }
                        });
                    }


                    if ($('#site_activities').size() != 0) {
                        //site activities
                        var previousPoint2 = null;
                        $('#site_activities_loading').hide();
                        $('#site_activities_content').show();

                        var data1 = [
                            ['DEC', 300],
                            ['JAN', 600],
                            ['FEB', 1100],
                            ['MAR', 1200],
                            ['APR', 860],
                            ['MAY', 1200],
                            ['JUN', 1450],
                            ['JUL', 1800],
                            ['AUG', 1200],
                            ['SEP', 600]
                        ];


                        var plot_statistics = $.plot($("#site_activities"),

                            [{
                                data: data1,
                                lines: {
                                    fill: 0.2,
                                    lineWidth: 0,
                                },
                                color: ['#BAD9F5']
                            }, {
                                data: data1,
                                points: {
                                    show: true,
                                    fill: true,
                                    radius: 4,
                                    fillColor: "#9ACAE6",
                                    lineWidth: 2
                                },
                                color: '#9ACAE6',
                                shadowSize: 1
                            }, {
                                data: data1,
                                lines: {
                                    show: true,
                                    fill: false,
                                    lineWidth: 3
                                },
                                color: '#9ACAE6',
                                shadowSize: 0
                            }],

                            {

                                xaxis: {
                                    tickLength: 0,
                                    tickDecimals: 0,
                                    mode: "categories",
                                    min: 0,
                                    font: {
                                        lineHeight: 18,
                                        style: "normal",
                                        variant: "small-caps",
                                        color: "#6F7B8A"
                                    }
                                },
                                yaxis: {
                                    ticks: 5,
                                    tickDecimals: 0,
                                    tickColor: "#eee",
                                    font: {
                                        lineHeight: 14,
                                        style: "normal",
                                        variant: "small-caps",
                                        color: "#6F7B8A"
                                    }
                                },
                                grid: {
                                    hoverable: true,
                                    clickable: true,
                                    tickColor: "#eee",
                                    borderColor: "#eee",
                                    borderWidth: 1
                                }
                            });

                        $("#site_activities").bind("plothover", function(event, pos, item) {
                            $("#x").text(pos.x.toFixed(2));
                            $("#y").text(pos.y.toFixed(2));
                            if (item) {
                                if (previousPoint2 != item.dataIndex) {
                                    previousPoint2 = item.dataIndex;
                                    $("#tooltip").remove();
                                    var x = item.datapoint[0].toFixed(2),
                                        y = item.datapoint[1].toFixed(2);
                                    showChartTooltip(item.pageX, item.pageY, item.datapoint[0], item.datapoint[1] + 'M$');
                                }
                            }
                        });

                        $('#site_activities').bind("mouseleave", function() {
                            $("#tooltip").remove();
                        });
                    }
                },

                initEasyPieCharts: function() {
                    if (!jQuery().easyPieChart) {
                        return;
                    }

                    $('.easy-pie-chart .number.transactions').easyPieChart({
                        animate: 1000,
                        size: 75,
                        lineWidth: 3,
                        barColor: App.getBrandColor('yellow')
                    });

                    $('.easy-pie-chart .number.visits').easyPieChart({
                        animate: 1000,
                        size: 75,
                        lineWidth: 3,
                        barColor: App.getBrandColor('green')
                    });

                    $('.easy-pie-chart .number.bounce').easyPieChart({
                        animate: 1000,
                        size: 75,
                        lineWidth: 3,
                        barColor: App.getBrandColor('red')
                    });

                    $('.easy-pie-chart-reload').click(function() {
                        $('.easy-pie-chart .number').each(function() {
                            var newValue = Math.floor(100 * Math.random());
                            $(this).data('easyPieChart').update(newValue);
                            $('span', this).text(newValue);
                        });
                    });
                },

                initSparklineCharts: function() {
                    if (!jQuery().sparkline) {
                        return;
                    }
                    $("#sparkline_bar").sparkline([8, 9, 10, 11, 10, 10, 12, 10, 10, 11, 9, 12, 11, 10, 9, 11, 13, 13, 12], {
                        type: 'bar',
                        width: '100',
                        barWidth: 5,
                        height: '55',
                        barColor: '#35aa47',
                        negBarColor: '#e02222'
                    });

                    $("#sparkline_bar2").sparkline([9, 11, 12, 13, 12, 13, 10, 14, 13, 11, 11, 12, 11, 11, 10, 12, 11, 10], {
                        type: 'bar',
                        width: '100',
                        barWidth: 5,
                        height: '55',
                        barColor: '#ffb848',
                        negBarColor: '#e02222'
                    });

                    $("#sparkline_bar5").sparkline([8, 9, 10, 11, 10, 10, 12, 10, 10, 11, 9, 12, 11, 10, 9, 11, 13, 13, 12], {
                        type: 'bar',
                        width: '100',
                        barWidth: 5,
                        height: '55',
                        barColor: '#35aa47',
                        negBarColor: '#e02222'
                    });

                    $("#sparkline_bar6").sparkline([9, 11, 12, 13, 12, 13, 10, 14, 13, 11, 11, 12, 11, 11, 10, 12, 11, 10], {
                        type: 'bar',
                        width: '100',
                        barWidth: 5,
                        height: '55',
                        barColor: '#ffb848',
                        negBarColor: '#e02222'
                    });

                    $("#sparkline_line").sparkline([9, 10, 9, 10, 10, 11, 12, 10, 10, 11, 11, 12, 11, 10, 12, 11, 10, 12], {
                        type: 'line',
                        width: '100',
                        height: '55',
                        lineColor: '#ffb848'
                    });
                },

                initMorisCharts: function() {
                    if (Morris.EventEmitter && $('#sales_statistics').size() > 0) {
                        // Use Morris.Area instead of Morris.Line
                        dashboardMainChart = Morris.Area({
                            element: 'sales_statistics',
                            padding: 0,
                            behaveLikeLine: false,
                            gridEnabled: false,
                            gridLineColor: false,
                            axes: false,
                            fillOpacity: 1,
                            data: [{
                                period: '2011 Q1',
                                sales: 1400,
                                profit: 400
                            }, {
                                period: '2011 Q2',
                                sales: 1100,
                                profit: 600
                            }, {
                                period: '2011 Q3',
                                sales: 1600,
                                profit: 500
                            }, {
                                period: '2011 Q4',
                                sales: 1200,
                                profit: 400
                            }, {
                                period: '2012 Q1',
                                sales: 1550,
                                profit: 800
                            }],
                            lineColors: ['#399a8c', '#92e9dc'],
                            xkey: 'period',
                            ykeys: ['sales', 'profit'],
                            labels: ['Sales', 'Profit'],
                            pointSize: 0,
                            lineWidth: 0,
                            hideHover: 'auto',
                            resize: true
                        });

                    }
                },

                initChat: function() {
                    var cont = $('#chats');
                    var list = $('.chats', cont);
                    var form = $('.chat-form', cont);
                    var input = $('input', form);
                    var btn = $('.btn', form);

                    var handleClick = function(e) {
                        e.preventDefault();

                        var text = input.val();
                        if (text.length == 0) {
                            return;
                        }

                        var time = new Date();
                        var time_str = (time.getHours() + ':' + time.getMinutes());
                        var tpl = '';
                        tpl += '<li class="out">';
                        tpl += '<img class="avatar" alt="" src="' + Layout.getLayoutImgPath() + 'avatar1.jpg"/>';
                        tpl += '<div class="message">';
                        tpl += '<span class="arrow"></span>';
                        tpl += '<a href="#" class="name">Bob Nilson</a>&nbsp;';
                        tpl += '<span class="datetime">at ' + time_str + '</span>';
                        tpl += '<span class="body">';
                        tpl += text;
                        tpl += '</span>';
                        tpl += '</div>';
                        tpl += '</li>';

                        var msg = list.append(tpl);
                        input.val("");

                        var getLastPostPos = function() {
                            var height = 0;
                            cont.find("li.out, li.in").each(function() {
                                height = height + $(this).outerHeight();
                            });

                            return height;
                        }

                        cont.find('.scroller').slimScroll({
                            scrollTo: getLastPostPos()
                        });
                    }

                    $('body').on('click', '.message .name', function(e) {
                        e.preventDefault(); // prevent click event

                        var name = $(this).text(); // get clicked user's full name
                        input.val('@' + name + ':'); // set it into the input field
                        App.scrollTo(input); // scroll to input if needed
                    });

                    btn.click(handleClick);

                    input.keypress(function(e) {
                        if (e.which == 13) {
                            handleClick(e);
                            return false; //<---- Add this line
                        }
                    });
                },

                initDashboardDaterange: function() {
                    if (!jQuery().daterangepicker) {
                        return;
                    }

                    $('#dashboard-report-range').daterangepicker({
                        "ranges": {
                            'Today': [moment(), moment()],
                            'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
                            'Last 7 Days': [moment().subtract('days', 6), moment()],
                            'Last 30 Days': [moment().subtract('days', 29), moment()],
                            'This Month': [moment().startOf('month'), moment().endOf('month')],
                            'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
                        },
                        "locale": {
                            "format": "MM/DD/YYYY",
                            "separator": " - ",
                            "applyLabel": "Apply",
                            "cancelLabel": "Cancel",
                            "fromLabel": "From",
                            "toLabel": "To",
                            "customRangeLabel": "Custom",
                            "daysOfWeek": [
                                "Su",
                                "Mo",
                                "Tu",
                                "We",
                                "Th",
                                "Fr",
                                "Sa"
                            ],
                            "monthNames": [
                                "January",
                                "February",
                                "March",
                                "April",
                                "May",
                                "June",
                                "July",
                                "August",
                                "September",
                                "October",
                                "November",
                                "December"
                            ],
                            "firstDay": 1
                        },
                        //"startDate": "11/08/2015",
                        //"endDate": "11/14/2015",
                        opens: (App.isRTL() ? 'right' : 'left'),
                    }, function(start, end, label) {
                        $('#dashboard-report-range span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                    });

                    $('#dashboard-report-range span').html(moment().subtract('days', 29).format('MMMM D, YYYY') + ' - ' + moment().format('MMMM D, YYYY'));
                    $('#dashboard-report-range').show();
                },

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

                initAmChart3_1: function() {
                    if (typeof(AmCharts) === 'undefined' || $('#dashboard_amchart_3_1').size() === 0) {
                        return;
                    }

                    var chart = AmCharts.makeChart("dashboard_amchart_3_1", {
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

                        "dataProvider": [{
                            "year": 2002,
                            "income": 23,
                            "expenses": 32
                        }, {
                            "year": 2013,
                            "income": 26,
                            "expenses": 40
                        }, {
                            "year": 2014,
                            "income": 30,
                            "expenses": 44
                        }, {
                            "year": 2015,
                            "income": 34,
                            "expenses": 44
                        }, {
                            "year": 2016,
                            "income": 34,
                            "expenses": 49,
                        }, {
                            "year": 2017,
                            "income": 35,
                            "expenses": 53,
                            "dashLengthColumn": 5,
                            "alpha": 0.2,
                            "additional": "(projection)"
                        }],
                        "valueAxes": [{
                            "axisAlpha": 0,
                            "position": "left"
                        }],
                        "startDuration": 1,
                        "graphs": [{
                            "alphaField": "alpha",
                            "balloonText": "<span style='font-size:12px;'>[[title]] in [[category]]:<br><span style='font-size:20px;'>[[value]]</span> [[additional]]</span>",
                            "fillAlphas": 1,
                            "title": "Squash clubs",
                            "type": "column",
                            "valueField": "income",
                            "dashLengthField": "dashLengthColumn"
                        }, {
                            "id": "graph2",
                            "balloonText": "<span style='font-size:12px;'>[[title]] in [[category]]:<br><span style='font-size:20px;'>[[value]]</span> [[additional]]</span>",
                            "bullet": "round",
                            "lineThickness": 3,
                            "bulletSize": 7,
                            "bulletBorderAlpha": 1,
                            "bulletColor": "#FFFFFF",
                            "useLineColorForBulletBorder": true,
                            "bulletBorderThickness": 3,
                            "fillAlphas": 0,
                            "lineAlpha": 1,
                            "title": "Squash rooms",
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

                initAmChart3_2: function() {
                    if (typeof(AmCharts) === 'undefined' || $('#dashboard_amchart_3_2').size() === 0) {
                        return;
                    }

                    var chart = AmCharts.makeChart("dashboard_amchart_3_2", {
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

                        "dataProvider": [{
                            "year": 2009,
                            "income": 23.5,
                            "expenses": 21.1
                        }, {
                            "year": 2010,
                            "income": 26.2,
                            "expenses": 30.5
                        }, {
                            "year": 2011,
                            "income": 30.1,
                            "expenses": 34.9
                        }, {
                            "year": 2012,
                            "income": 29.5,
                            "expenses": 31.1
                        }, {
                            "year": 2013,
                            "income": 30.6,
                            "expenses": 28.2,
                        }, {
                            "year": 2014,
                            "income": 34.1,
                            "expenses": 32.9,
                            "dashLengthColumn": 5,
                            "alpha": 0.2,
                            "additional": "(projection)"
                        }],
                        "valueAxes": [{
                            "axisAlpha": 0,
                            "position": "left"
                        }],
                        "startDuration": 1,
                        "graphs": [{
                            "alphaField": "alpha",
                            "balloonText": "<span style='font-size:12px;'>[[title]] in [[category]]:<br><span style='font-size:20px;'>[[value]]</span> [[additional]]</span>",
                            "fillAlphas": 1,
                            "title": "Income",
                            "type": "column",
                            "valueField": "income",
                            "dashLengthField": "dashLengthColumn"
                        }, {
                            "id": "graph2",
                            "balloonText": "<span style='font-size:12px;'>[[title]] in [[category]]:<br><span style='font-size:20px;'>[[value]]</span> [[additional]]</span>",
                            "bullet": "round",
                            "lineThickness": 3,
                            "bulletSize": 7,
                            "bulletBorderAlpha": 1,
                            "bulletColor": "#FFFFFF",
                            "useLineColorForBulletBorder": true,
                            "bulletBorderThickness": 3,
                            "fillAlphas": 0,
                            "lineAlpha": 1,
                            "title": "Expenses",
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

                initAmChart4_1: function() {
                    if (typeof(AmCharts) === 'undefined' || $('#dashboard_amchart_4_1').size() === 0) {
                        return;
                    }

                    var chart = AmCharts.makeChart("dashboard_amchart_4_1", {
                        "type": "pie",
                        "theme": "light",
                        "path": "../assets/global/plugins/amcharts/ammap/images/",
                        "dataProvider": [{
                            "country": "Bærum Squash",
                            "value": 60
                        }, {
                            "country": "Drammen Squash",
                            "value": 51
                        }, {
                            "country": "Lysaker Squash",
                            "value": 65
                        }, {
                            "country": "Sagene Squash",
                            "value": 39
                        }, {
                            "country": "Sentrum Squash",
                            "value": 29
                        },{
                            "country": " Vulkan Squash",
                            "value": 39
                        },{
                            "country": "Økern Squash",
                            "value": 29
                        }, {
                            "country": "Skippern Squash",
                            "value": 10
                        }],
                        "valueField": "value",
                        "titleField": "country",
                        "outlineAlpha": 0.4,
                        "depth3D": 15,
                        "balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
                        "angle": 30,
                        "export": {
                            "enabled": true
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
                        "dataProvider": [{
                            "country": "Dag Helg Fitness",
                            "value": 19
                        },{
                            "country": "FItness",
                            "value": 19
                        },{
                            "country": "Recurring",
                            "value": 19
                        },{
                            "country": "Fultid Squash",
                            "value": 19
                        }, {
                            "country": "Drop-In",
                            "value": 32
                        }],
                        "valueField": "value",
                        "titleField": "country",
                        "outlineAlpha": 0.4,
                        "depth3D": 15,
                        "balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
                        "angle": 30,
                        "export": {
                            "enabled": true
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

                init: function() {

                    this.initJQVMAP();
                    this.initCalendar();
                    this.initCharts();
                    this.initEasyPieCharts();
                    this.initSparklineCharts();
                    this.initChat();
                    this.initDashboardDaterange();
                    this.initMorisCharts();

                    this.initAmChart1();
                    this.initAmChart2();
                    this.initAmChart3_1();
                    this.initAmChart3_2();
                    this.initAmChart4_1();
                    this.initAmChart4_2();

                    this.initWorldMapStats();
                }
            };

        }();

        if (App.isAngularJsApp() === false) {
            jQuery(document).ready(function() {
                Dashboard.init(); // init metronic core componets
            });
        }

        var ChartsFlotcharts = function() {

            return {
                //main function to initiate the module

                init: function() {

                    App.addResizeHandler(function() {
                        Charts.initPieCharts();
                    });

                },

                initCharts: function() {

                    if (!jQuery.plot) {
                        return;
                    }

                    var data = [];
                    var totalPoints = 250;

                    // random data generator for plot charts

                    function getRandomData() {
                        if (data.length > 0) data = data.slice(1);
                        // do a random walk
                        while (data.length < totalPoints) {
                            var prev = data.length > 0 ? data[data.length - 1] : 50;
                            var y = prev + Math.random() * 10 - 5;
                            if (y < 0) y = 0;
                            if (y > 100) y = 100;
                            data.push(y);
                        }
                        // zip the generated y values with the x values
                        var res = [];
                        for (var i = 0; i < data.length; ++i) {
                            res.push([i, data[i]]);
                        }

                        return res;
                    }

                    //Basic Chart

                    function chart1() {
                        if ($('#chart_1').size() != 1) {
                            return;
                        }

                        var d1 = [];
                        for (var i = 0; i < Math.PI * 2; i += 0.25)
                            d1.push([i, Math.sin(i)]);

                        var d2 = [];
                        for (var i = 0; i < Math.PI * 2; i += 0.25)
                            d2.push([i, Math.cos(i)]);

                        var d3 = [];
                        for (var i = 0; i < Math.PI * 2; i += 0.1)
                            d3.push([i, Math.tan(i)]);

                        $.plot($("#chart_1"), [{
                            label: "sin(x)",
                            data: d1,
                            lines: {
                                lineWidth: 1,
                            },
                            shadowSize: 0
                        }, {
                            label: "cos(x)",
                            data: d2,
                            lines: {
                                lineWidth: 1,
                            },
                            shadowSize: 0
                        }, {
                            label: "tan(x)",
                            data: d3,
                            lines: {
                                lineWidth: 1,
                            },
                            shadowSize: 0
                        }], {
                            series: {
                                lines: {
                                    show: true,
                                },
                                points: {
                                    show: true,
                                    fill: true,
                                    radius: 3,
                                    lineWidth: 1
                                }
                            },
                            xaxis: {
                                tickColor: "#eee",
                                ticks: [0, [Math.PI / 2, "\u03c0/2"],
                                    [Math.PI, "\u03c0"],
                                    [Math.PI * 3 / 2, "3\u03c0/2"],
                                    [Math.PI * 2, "2\u03c0"]
                                ]
                            },
                            yaxis: {
                                tickColor: "#eee",
                                ticks: 10,
                                min: -2,
                                max: 2
                            },
                            grid: {
                                borderColor: "#eee",
                                borderWidth: 1
                            }
                        });

                    }

                    //Interactive Chart

                    function chart2() {
                        if ($('#chart_2').size() != 1) {
                            return;
                        }

                        function randValue() {
                            return (Math.floor(Math.random() * (1 + 40 - 20))) + 20;
                        }
                        var visitors = [
                            [1, 345],
                            [2, 352],
                            [3, 355],
                            [4, 304],
                            [5, 328],
                            [6, 390],
                            [7, 388],
                            [8, 320],
                            [9, 460],
                            [10, 471],
                            [11, 420],
                            [12, 430],
                            [13, 450],
                            [14, 431],
                            [15, 440],
                            [16, 470],
                            [17, 485],
                            [18, 520],
                            [19, 550],
                            [20, 580],
                            [21, 621],
                            [22, 645],
                            [23, 699],
                            [24, 670],
                            [25, 680],
                            [26, 750],
                            [27, 810],
                            [28, 820],
                            [29, 900],
                            [30, 951]
                        ];
                        var pageviews = [
                            [1, 245],
                            [2, 252],
                            [3, 295],
                            [4, 284],
                            [5, 298],
                            [6, 303],
                            [7, 308],
                            [8, 308],
                            [9, 321],
                            [10, 340],
                            [11, 340],
                            [12, 340],
                            [13, 350],
                            [14, 331],
                            [15, 360],
                            [16, 370],
                            [17, 385],
                            [18, 410],
                            [19, 450],
                            [20, 452],
                            [21, 475],
                            [22, 475],
                            [23, 475],
                            [24, 452],
                            [25, 438],
                            [26, 550],
                            [27, 591],
                            [28, 600],
                            [29, 600],
                            [30, 621]
                        ];

                        var plot = $.plot($("#chart_2"), [{
                            data: pageviews,
                            label: "Paying members",
                            lines: {
                                lineWidth: 1,
                            },
                            shadowSize: 0

                        }, {
                            data: visitors,
                            label: "Non paying members",
                            lines: {
                                lineWidth: 1,
                            },
                            shadowSize: 0
                        }], {
                            series: {
                                lines: {
                                    show: true,
                                    lineWidth: 2,
                                    fill: true,
                                    fillColor: {
                                        colors: [{
                                            opacity: 0.05
                                        }, {
                                            opacity: 0.01
                                        }]
                                    }
                                },
                                points: {
                                    show: true,
                                    radius: 3,
                                    lineWidth: 1
                                },
                                shadowSize: 2
                            },
                            grid: {
                                hoverable: true,
                                clickable: true,
                                tickColor: "#eee",
                                borderColor: "#eee",
                                borderWidth: 1
                            },
                            colors: ["#d12610", "#37b7f3", "#52e136"],
                            xaxis: {
                                ticks: 11,
                                tickDecimals: 0,
                                tickColor: "#eee",
                            },
                            yaxis: {
                                ticks: 11,
                                tickDecimals: 0,
                                tickColor: "#eee",
                            }
                        });


                        function showTooltip(x, y, contents) {
                            $('<div id="tooltip">' + contents + '</div>').css({
                                position: 'absolute',
                                display: 'none',
                                top: y + 5,
                                left: x + 15,
                                border: '1px solid #333',
                                padding: '4px',
                                color: '#fff',
                                'border-radius': '3px',
                                'background-color': '#333',
                                opacity: 0.80
                            }).appendTo("body").fadeIn(200);
                        }

                        var previousPoint = null;
                        $("#chart_2").bind("plothover", function(event, pos, item) {
                            $("#x").text(pos.x.toFixed(2));
                            $("#y").text(pos.y.toFixed(2));

                            if (item) {
                                if (previousPoint != item.dataIndex) {
                                    previousPoint = item.dataIndex;

                                    $("#tooltip").remove();
                                    var x = item.datapoint[0].toFixed(2),
                                        y = item.datapoint[1].toFixed(2);

                                    showTooltip(item.pageX, item.pageY, item.series.label + " of " + x + " = " + y);
                                }
                            } else {
                                $("#tooltip").remove();
                                previousPoint = null;
                            }
                        });
                    }

                    //Tracking Curves

                    function chart3() {
                        if ($('#chart_3').size() != 1) {
                            return;
                        }
                        //tracking curves:

                        var sin = [],
                            cos = [];
                        for (var i = 0; i < 14; i += 0.1) {
                            sin.push([i, Math.sin(i)]);
                            cos.push([i, Math.cos(i)]);
                        }

                        plot = $.plot($("#chart_3"), [{
                            data: sin,
                            label: "sin(x) = -0.00",
                            lines: {
                                lineWidth: 1,
                            },
                            shadowSize: 0
                        }, {
                            data: cos,
                            label: "cos(x) = -0.00",
                            lines: {
                                lineWidth: 1,
                            },
                            shadowSize: 0
                        }], {
                            series: {
                                lines: {
                                    show: true
                                }
                            },
                            crosshair: {
                                mode: "x"
                            },
                            grid: {
                                hoverable: true,
                                autoHighlight: false,
                                tickColor: "#eee",
                                borderColor: "#eee",
                                borderWidth: 1
                            },
                            yaxis: {
                                min: -1.2,
                                max: 1.2
                            }
                        });

                        var legends = $("#chart_3 .legendLabel");
                        legends.each(function() {
                            // fix the widths so they don't jump around
                            $(this).css('width', $(this).width());
                        });

                        var updateLegendTimeout = null;
                        var latestPosition = null;

                        function updateLegend() {
                            updateLegendTimeout = null;

                            var pos = latestPosition;

                            var axes = plot.getAxes();
                            if (pos.x < axes.xaxis.min || pos.x > axes.xaxis.max || pos.y < axes.yaxis.min || pos.y > axes.yaxis.max) return;

                            var i, j, dataset = plot.getData();
                            for (i = 0; i < dataset.length; ++i) {
                                var series = dataset[i];

                                // find the nearest points, x-wise
                                for (j = 0; j < series.data.length; ++j)
                                    if (series.data[j][0] > pos.x) break;

                                // now interpolate
                                var y, p1 = series.data[j - 1],
                                    p2 = series.data[j];

                                if (p1 == null) y = p2[1];
                                else if (p2 == null) y = p1[1];
                                else y = p1[1] + (p2[1] - p1[1]) * (pos.x - p1[0]) / (p2[0] - p1[0]);

                                legends.eq(i).text(series.label.replace(/=.*/, "= " + y.toFixed(2)));
                            }
                        }

                        $("#chart_3").bind("plothover", function(event, pos, item) {
                            latestPosition = pos;
                            if (!updateLegendTimeout) updateLegendTimeout = setTimeout(updateLegend, 50);
                        });
                    }

                    //Dynamic Chart

                    function chart4() {
                        if ($('#chart_4').size() != 1) {
                            return;
                        }
                        //server load
                        var options = {
                            series: {
                                shadowSize: 1
                            },
                            lines: {
                                show: true,
                                lineWidth: 0.5,
                                fill: true,
                                fillColor: {
                                    colors: [{
                                        opacity: 0.1
                                    }, {
                                        opacity: 1
                                    }]
                                }
                            },
                            yaxis: {
                                min: 0,
                                max: 100,
                                tickColor: "#eee",
                                tickFormatter: function(v) {
                                    return v + "%";
                                }
                            },
                            xaxis: {
                                show: false,
                            },
                            colors: ["#6ef146"],
                            grid: {
                                tickColor: "#eee",
                                borderWidth: 0,
                            }
                        };

                        var updateInterval = 30;
                        var plot = $.plot($("#chart_4"), [getRandomData()], options);

                        function update() {
                            plot.setData([getRandomData()]);
                            plot.draw();
                            setTimeout(update, updateInterval);
                        }
                        update();
                    }

                    //bars with controls

                    function chart5() {
                        if ($('#chart_5').size() != 1) {
                            return;
                        }
                        var d1 = [];
                        for (var i = 0; i <= 10; i += 1)
                            d1.push([i, parseInt(Math.random() * 30)]);

                        var d2 = [];
                        for (var i = 0; i <= 10; i += 1)
                            d2.push([i, parseInt(Math.random() * 30)]);

                        var d3 = [];
                        for (var i = 0; i <= 10; i += 1)
                            d3.push([i, parseInt(Math.random() * 30)]);

                        var stack = 0,
                            bars = true,
                            lines = false,
                            steps = false;

                        function plotWithOptions() {
                            $.plot($("#chart_5"),

                                [{
                                    label: "sales",
                                    data: d1,
                                    lines: {
                                        lineWidth: 1,
                                    },
                                    shadowSize: 0
                                }, {
                                    label: "tax",
                                    data: d2,
                                    lines: {
                                        lineWidth: 1,
                                    },
                                    shadowSize: 0
                                }, {
                                    label: "profit",
                                    data: d3,
                                    lines: {
                                        lineWidth: 1,
                                    },
                                    shadowSize: 0
                                }]

                                , {
                                    series: {
                                        stack: stack,
                                        lines: {
                                            show: lines,
                                            fill: true,
                                            steps: steps,
                                            lineWidth: 0, // in pixels
                                        },
                                        bars: {
                                            show: bars,
                                            barWidth: 0.5,
                                            lineWidth: 0, // in pixels
                                            shadowSize: 0,
                                            align: 'center'
                                        }
                                    },
                                    grid: {
                                        tickColor: "#eee",
                                        borderColor: "#eee",
                                        borderWidth: 1
                                    }
                                }
                            );
                        }

                        $(".stackControls input").click(function(e) {
                            e.preventDefault();
                            stack = $(this).val() == "With stacking" ? true : null;
                            plotWithOptions();
                        });

                        $(".graphControls input").click(function(e) {
                            e.preventDefault();
                            bars = $(this).val().indexOf("Bars") != -1;
                            lines = $(this).val().indexOf("Lines") != -1;
                            steps = $(this).val().indexOf("steps") != -1;
                            plotWithOptions();
                        });

                        plotWithOptions();
                    }

                    //graph
                    chart1();
                    chart2();
                    chart3();
                    chart4();
                    chart5();

                },

                initBarCharts: function() {

                    // bar chart:
                    var data = GenerateSeries(0);

                    function GenerateSeries(added) {
                        var data = [];
                        var start = 100 + added;
                        var end = 200 + added;

                        for (i = 1; i <= 20; i++) {
                            var d = Math.floor(Math.random() * (end - start + 1) + start);
                            data.push([i, d]);
                            start++;
                            end++;
                        }

                        return data;
                    }

                    var options = {
                        series: {
                            bars: {
                                show: true
                            }
                        },
                        bars: {
                            barWidth: 0.8,
                            lineWidth: 0, // in pixels
                            shadowSize: 0,
                            align: 'left'
                        },

                        grid: {
                            tickColor: "#eee",
                            borderColor: "#eee",
                            borderWidth: 1
                        }
                    };

                    if ($('#chart_1_1').size() !== 0) {
                        $.plot($("#chart_1_1"), [{
                            data: data,
                            lines: {
                                lineWidth: 1,
                            },
                            shadowSize: 0
                        }], options);
                    }

                    // horizontal bar chart:

                    var data1 = [
                        [10, 10],
                        [20, 20],
                        [30, 30],
                        [40, 40],
                        [50, 50]
                    ];

                    var options = {
                        series: {
                            bars: {
                                show: true
                            }
                        },
                        bars: {
                            horizontal: true,
                            barWidth: 6,
                            lineWidth: 0, // in pixels
                            shadowSize: 0,
                            align: 'left'
                        },
                        grid: {
                            tickColor: "#eee",
                            borderColor: "#eee",
                            borderWidth: 1
                        }
                    };

                    if ($('#chart_1_2').size() !== 0) {
                        $.plot($("#chart_1_2"), [data1], options);
                    }
                },

                initPieCharts: function() {

                    var data = [];
                    var series = Math.floor(Math.random() * 10) + 1;
                    series = series < 5 ? 5 : series;

                    for (var i = 0; i < series; i++) {
                        data[i] = {
                            label: "Series" + (i + 1),
                            data: Math.floor(Math.random() * 100) + 1
                        };
                    }

                    var data1 = [{ label: "Federation M.", data:61}, {label: "non-Federation", data: 39}];
                    var data2 = [{ label: 'Nord-Noreg', data:33},
                        {label: 'Midt-Noreg', data:14},
                        {label: 'Vestlandet', data:25},
                        {label: 'Sørlandet or Agder', data:15},
                        {label: 'Østlandet/Austlandet', data:14}];
                    var data3 = [{
                        label: "Drop-In's",
                        data: 18
                    }, {
                        label: "Full Day",
                        data: 20
                    }, {
                        label: "Half Day",
                        data: 35
                    }, {
                        label: "Morning",
                        data: 12
                    }, {
                        label: "Others",
                        data: 15
                    }];

                    // DEFAULT
                    if ($('#pie_chart').size() !== 0) {
                        $.plot($("#pie_chart"), data, {
                            series: {
                                pie: {
                                    show: true
                                }
                            }
                        });
                    }

                    // GRAPH 1
                    if ($('#pie_chart_1').size() !== 0) {
                        $.plot($("#pie_chart_1"), data, {
                            series: {
                                pie: {
                                    show: true
                                }
                            },
                            legend: {
                                show: false
                            }
                        });
                    }

                    // GRAPH 2
                    if ($('#pie_chart_2').size() !== 0) {
                        $.plot($("#pie_chart_2"), data, {
                            series: {
                                pie: {
                                    show: true,
                                    radius: 1,
                                    label: {
                                        show: true,
                                        radius: 1,
                                        formatter: function(label, series) {
                                            return '<div style="font-size:8pt;text-align:center;padding:2px;color:white;">' + label + '<br/>' + Math.round(series.percent) + '%</div>';
                                        },
                                        background: {
                                            opacity: 0.8
                                        }
                                    }
                                }
                            },
                            legend: {
                                show: false
                            }
                        });
                    }

                    // GRAPH 3
                    if ($('#pie_chart_3').size() !== 0) {
                        $.plot($("#pie_chart_3"), data, {
                            series: {
                                pie: {
                                    show: true,
                                    radius: 1,
                                    label: {
                                        show: true,
                                        radius: 3 / 4,
                                        formatter: function(label, series) {
                                            return '<div style="font-size:8pt;text-align:center;padding:2px;color:white;">' + label + '<br/>' + Math.round(series.percent) + '%</div>';
                                        },
                                        background: {
                                            opacity: 0.5
                                        }
                                    }
                                }
                            },
                            legend: {
                                show: false
                            }
                        });
                    }

                    // GRAPH 4
                    if ($('#pie_chart_4').size() !== 0) {
                        $.plot($("#pie_chart_4"), data, {
                            series: {
                                pie: {
                                    show: true,
                                    radius: 1,
                                    label: {
                                        show: true,
                                        radius: 3 / 4,
                                        formatter: function(label, series) {
                                            return '<div style="font-size:8pt;text-align:center;padding:2px;color:white;">' + label + '<br/>' + Math.round(series.percent) + '%</div>';
                                        },
                                        background: {
                                            opacity: 0.5,
                                            color: '#000'
                                        }
                                    }
                                }
                            },
                            legend: {
                                show: false
                            }
                        });
                    }

                    // GRAPH 5
                    if ($('#pie_chart_5').size() !== 0) {
                        $.plot($("#pie_chart_5"), data, {
                            series: {
                                pie: {
                                    show: true,
                                    radius: 3 / 4,
                                    label: {
                                        show: true,
                                        radius: 3 / 4,
                                        formatter: function(label, series) {
                                            return '<div style="font-size:8pt;text-align:center;padding:2px;color:white;">' + label + '<br/>' + Math.round(series.percent) + '%</div>';
                                        },
                                        background: {
                                            opacity: 0.5,
                                            color: '#000'
                                        }
                                    }
                                }
                            },
                            legend: {
                                show: false
                            }
                        });
                    }

                    // GRAPH 6
                    if ($('#pie_chart_6').size() !== 0) {
                        $.plot($("#pie_chart_6"), data, {
                            series: {
                                pie: {
                                    show: true,
                                    radius: 1,
                                    label: {
                                        show: true,
                                        radius: 2 / 3,
                                        formatter: function(label, series) {
                                            return '<div style="font-size:8pt;text-align:center;padding:2px;color:white;">' + label + '<br/>' + Math.round(series.percent) + '%</div>';
                                        },
                                        threshold: 0.1
                                    }
                                }
                            },
                            legend: {
                                show: false
                            }
                        });
                    }

                    // GRAPH 7
                    if ($('#pie_chart_7').size() !== 0) {
                        $.plot($("#pie_chart_7"), data, {
                            series: {
                                pie: {
                                    show: true,
                                    combine: {
                                        color: '#999',
                                        threshold: 0.1
                                    }
                                }
                            },
                            legend: {
                                show: false
                            }
                        });
                    }

                    // GRAPH 8
                    if ($('#pie_chart_8').size() !== 0) {
                        $.plot($("#pie_chart_8"), data1, {
                            series: {
                                pie: {
                                    show: true,
                                    radius: 280,
                                    label: {
                                        show: true,
                                        formatter: function(label, series) {
                                            return '<div style="font-size:8pt;text-align:center;padding:2px;color:white;">' + label + '<br/>' + Math.round(series.percent) + '%</div>';
                                        },
                                        threshold: 0.1
                                    }
                                }
                            },
                            legend: {
                                show: false
                            }
                        });
                    }

                    // GRAPH 8_1
                    if ($('#pie_chart_8_1').size() !== 0) {
                        $.plot($("#pie_chart_8_1"), data2, {
                            series: {
                                pie: {
                                    show: true,
                                    radius: 280,
                                    label: {
                                        show: true,
                                        formatter: function(label, series) {
                                            return '<div style="font-size:8pt;text-align:center;padding:2px;color:white;">' + label + '<br/>' + Math.round(series.percent) + '%</div>';
                                        },
                                        threshold: 0.1
                                    }
                                }
                            },
                            legend: {
                                show: false
                            }
                        });
                    }

                    // GRAPH 9
                    if ($('#pie_chart_9').size() !== 0) {
                        $.plot($("#pie_chart_9"), data3, {
                            series: {
                                pie: {
                                    show: true,
                                    radius: 1,
                                    tilt: 0.5,
                                    label: {
                                        show: true,
                                        radius: 1,
                                        formatter: function(label, series) {
                                            return '<div style="font-size:8pt;text-align:center;padding:2px;color:white;">' + label + '<br/>' + Math.round(series.percent) + '%</div>';
                                        },
                                        background: {
                                            opacity: 0.8
                                        }
                                    },
                                    combine: {
                                        color: '#999',
                                        threshold: 0.1
                                    }
                                }
                            },
                            legend: {
                                show: false
                            }
                        });
                    }

                    // DONUT
                    if ($('#donut').size() !== 0) {
                        $.plot($("#donut"), data, {
                            series: {
                                pie: {
                                    innerRadius: 0.5,
                                    show: true
                                }
                            }
                        });
                    }

                    // INTERACTIVE
                    if ($('#interactive').size() !== 0) {
                        $.plot($("#interactive"), data, {
                            series: {
                                pie: {
                                    show: true
                                }
                            },
                            grid: {
                                hoverable: true,
                                clickable: true
                            }
                        });
                        $("#interactive").bind("plothover", pieHover);
                        $("#interactive").bind("plotclick", pieClick);
                    }

                    function pieHover(event, pos, obj) {
                        if (!obj)
                            return;
                        percent = parseFloat(obj.series.percent).toFixed(2);
                        $("#hover").html('<span style="font-weight: bold; color: ' + obj.series.color + '">' + obj.series.label + ' (' + percent + '%)</span>');
                    }

                    function pieClick(event, pos, obj) {
                        if (!obj)
                            return;
                        percent = parseFloat(obj.series.percent).toFixed(2);
                        alert('' + obj.series.label + ': ' + percent + '%');
                    }

                },

                initAxisLabelsPlugin: function() {
                    var d1 = [];

                    for (var i = 0; i < Math.PI * 2; i += 0.25)
                        d1.push([i, Math.sin(i)]);

                    var d2 = [];
                    for (var i = 0; i < Math.PI * 2; i += 0.25)
                        d2.push([i, Math.cos(i)]);

                    var d3 = [];
                    for (var i = 0; i < Math.PI * 2; i += 0.1)
                        d3.push([i, Math.tan(i)]);

                    var options = {
                        axisLabels: {
                            show: true
                        },
                        xaxes: [{
                            axisLabel: 'hor label',
                            tickColor: "#eee",
                        }],
                        yaxes: [{
                            position: 'left',
                            axisLabel: 'ver label',
                            tickColor: "#eee",
                        }, {
                            position: 'right',
                            axisLabel: 'bleem'
                        }],

                        grid: {
                            borderColor: "#eee",
                            borderWidth: 1
                        }
                    };

                    $.plot($("#chart_1_1"),
                        [{
                            label: "sin(x)",
                            data: d1,
                            lines: {
                                lineWidth: 1,
                            },
                            shadowSize: 0
                        }, {
                            label: "cos(x)",
                            data: d2,
                            lines: {
                                lineWidth: 1,
                            },
                            shadowSize: 0
                        }, {
                            label: "tan(x)",
                            data: d3,
                            lines: {
                                lineWidth: 1,
                            },
                            shadowSize: 0
                        }],
                        options
                    );
                }
            };
        }();

        jQuery(document).ready(function() {
            ChartsFlotcharts.init();
            ChartsFlotcharts.initCharts();
            ChartsFlotcharts.initPieCharts();
            ChartsFlotcharts.initBarCharts();
            ChartsFlotcharts.initAxisLabelsPlugin();
        });
    </script>
@endsection