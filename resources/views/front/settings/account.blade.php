@extends('layouts.main')

@section('pageLevelPlugins')
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
            <div class="container">
                <!-- BEGIN PAGE CONTENT INNER -->
                <div class="page-content-inner">
                    <div class="col-md-6">
                        <div class="portlet light ">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="icon-share font-dark"></i>
                                    <span class="caption-subject font-dark bold uppercase">Bookings Settings</span>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="margin-top-10 margin-bottom-10 clearfix">
                                    <table class="table table-bordered table-striped">
                                        <tbody><tr>
                                            <td> Preferred Location </td>
                                            <td>
                                                <div style="padding:5px;" class="pulsate-regular">
                                                    <select class="form-control">
                                                        <option> Select Location </option>
                                                    </select>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Preferred Activity
                                            </td>
                                            <td>
                                                <div style="padding:5px;" class="pulsate-regular">
                                                    <select class="form-control">
                                                        <option> Select Activity </option>
                                                    </select>
                                                </div>
                                            </td>
                                        </tr>
                                        </tbody></table>
                                </div>
                                <span class="label label-danger"> NOTE! </span>
                                <span> Pulsate is supported in Latest Firefox, Chrome, Opera, Safari and Internet Explorer 9 and Internet Explorer 10 only. </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="portlet light ">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="icon-share font-dark"></i>
                                    <span class="caption-subject font-dark bold uppercase">Pulsate</span>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <h4>Pulsate any page elements.</h4>
                                <div class="margin-top-10 margin-bottom-10 clearfix">
                                    <table class="table table-bordered table-striped">
                                        <tbody><tr>
                                            <td> Repeating Pulsate </td>
                                            <td>
                                                <div style="padding: 5px; -moz-outline-radius: 0px; outline: 2px solid rgba(191, 28, 86, 0.6); box-shadow: 0px 0px 5px rgba(191, 28, 86, 0.6);"> Repeating Pulsate </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <button  class="btn green">Pulsate Once</button>
                                            </td>
                                            <td>
                                                <div style="padding:5px;"> Pulsate me </div>
                                            </td>
                                        </tr>
                                        </tbody></table>
                                </div>
                                <span class="label label-danger"> NOTE! </span>
                                <span> Pulsate is supported in Latest Firefox, Chrome, Opera, Safari and Internet Explorer 9 and Internet Explorer 10 only. </span>
                            </div>
                        </div>
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
    <script src="{{ asset('assets/global/plugins/jquery.pulsate.min.js') }}" type="text/javascript"></script>
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

        var UIGeneral = function () {

            var handlePulsate = function () {
                if (!jQuery().pulsate) {
                    return;
                }

                if (App.isIE8() == true) {
                    return; // pulsate plugin does not support IE8 and below
                }

                if (jQuery().pulsate) {
                    jQuery('.pulsate-regular').pulsate({
                        color: "#bf1c56"
                    });

                    $('#pulsate-once-target2').pulsate({
                        color: "#399bc3",
                        repeat: false
                    });

                    $('#pulsate-crazy-target1').pulsate({
                        color: "#fdbe41",
                        reach: 50,
                        repeat: 10,
                        speed: 100,
                        glow: true
                    });
                }
            }

            return {
                //main function to initiate the module
                init: function () {
                    handlePulsate();
                }

            };

        }();

        jQuery(document).ready(function() {
            UIGeneral.init();
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