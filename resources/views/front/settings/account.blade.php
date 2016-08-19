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
                                    <span class="caption-subject font-dark bold uppercase">Booking Settings</span>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="margin-top-10 margin-bottom-10 clearfix">
                                    <table class="table table-bordered table-striped">
                                        <tbody><tr>
                                            <td> Preferred Location </td>
                                            <td>
                                                <div style="padding:5px;">
                                                    <select class="form-control" name="settings_preferred_location">
                                                        <option> Select Location </option>
                                                    @foreach ($locations as $location)
                                                        <option {!! @$settings['settings_preferred_location']==$location->id?' selected="selected" ':'' !!} value="{{$location->id}}">{{ $location->name }}</option>
                                                    @endforeach
                                                    </select>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Preferred Activity
                                            </td>
                                            <td>
                                                <div style="padding:5px;">
                                                    <select class="form-control" name="settings_preferred_activity">
                                                        <option> Select Activity </option>
                                                    @foreach ($activities as $activity)
                                                        <option {!! @$settings['settings_preferred_activity']==$activity->id?' selected="selected" ':'' !!} value="{{$activity->id}}">{{ $activity->name }}</option>
                                                    @endforeach
                                                    </select>
                                                </div>
                                            </td>
                                        </tr>
                                        </tbody></table>
                                    <div class="form-actions right">
                                        <button class="btn green" type="submit" onclick="javascript:update_booking_settings()"> Update </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="portlet light ">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="icon-share font-dark"></i>
                                    <span class="caption-subject font-dark bold uppercase">Account Settings</span>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="margin-top-10 margin-bottom-10 clearfix">
                                    <table class="table table-bordered table-striped">
                                        <tbody><tr>
                                            <td> Username </td>
                                            <td>
                                                <input type="text" name="account_username" value="{{$user->username}}" class="form-control input-inline input-large">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Registration Email
                                            </td>
                                            <td>
                                                <input type="text" name="account_email" value="{{$user->email}}" class="form-control input-inline input-large">
                                            </td>
                                        </tr>
                                        </tbody></table>
                                    <div class="form-actions right">
                                        <button class="btn green" type="submit"> Update </button>
                                    </div>
                                </div>
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

        function update_booking_settings(){
            var data = {settings_preferred_location:$('select[name="settings_preferred_location"]').val(), settings_preferred_activity:$('select[name="settings_preferred_activity"]').val()};
            send_settings(data);
        }

        function update_account_settings(){
            var data = { account_username:$('select[name="account_username"]').val(), account_email:$('select[name="account_email"]').val()};
            send_settings(data);
        }

        function send_settings(information){
            $.ajax({
                url: '{{route('ajax/update_general_settings')}}',
                type: "post",
                cache: false,
                data: {
                    'general_settings': information
                },
                success: function (data) {
                    if (data.success) {
                        show_notification(data.title, data.message, 'lime', 3500, 0);
                    }
                    else{
                        show_notification(data.title, data.errors, 'tangerine', 3500, 0);
                    }
                }
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