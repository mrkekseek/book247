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
                <!-- BEGIN PAGE BREADCRUMBS -->
                <ul class="page-breadcrumb breadcrumb">
                    <li>
                        <a href="{{route('homepage')}}">Home</a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <span>Booking Archive</span>
                    </li>
                </ul>
                <!-- END PAGE BREADCRUMBS -->
                <!-- BEGIN PAGE CONTENT INNER -->
                <div class="page-content-inner">
                    <div class="row">
                        <div class="row">
                            <div class="col-md-12">
                                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                                <div class="portlet light ">
                                    <div class="portlet-body">
                                        <div class="table-scrollable">
                                            <table class="table table-striped table-bordered table-advance table-hover">
                                                <thead>
                                                <tr>
                                                    <th>
                                                        <i class="fa fa-briefcase"></i> Friend Name </th>
                                                    <th>
                                                        <i class="fa fa-user"></i> Email Address </th>
                                                    <th>
                                                        <i class="fa fa-user"></i> Phone Number </th>
                                                    <th class="hidden-xs">
                                                        <i class="fa fa-shopping-cart"></i> Preferred Gym </th>
                                                    <th class="hidden-xs">
                                                        <i class="fa fa-shopping-cart"></i> Friend Since </th>
                                                    <th> </th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td class="highlight">
                                                        <div class="success"></div>
                                                        <a href="javascript:;"> Stefan Bogdan </a>
                                                    </td>
                                                    <td class="hidden-xs"> 0744511446 </td>
                                                    <td> <a href="javascript:;">stefan.bogdan@ymail.com</a> </td>
                                                    <td> Lysake squash </td>
                                                    <td>
                                                        <a href="javascript:;" class="btn btn-sm btn-outline red-haze">
                                                            <i class="fa fa-edit"></i> Remove </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="highlight">
                                                        <div class="info"></div>
                                                        <a href="javascript:;"> Stefan Bogdan </a>
                                                    </td>
                                                    <td class="hidden-xs"> 0744511446 </td>
                                                    <td> <a href="javascript:;">stefan.bogdan@ymail.com</a> </td>
                                                    <td> Lysake squash </td>
                                                    <td>
                                                        <a href="javascript:;" class="btn btn-sm btn-outline red-haze">
                                                            <i class="fa fa-edit"></i> Remove </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="highlight">
                                                        <div class="success"></div>
                                                        <a href="javascript:;"> Stefan Bogdan </a>
                                                    </td>
                                                    <td class="hidden-xs"> 0744511446 </td>
                                                    <td> <a href="javascript:;">stefan.bogdan@ymail.com</a> </td>
                                                    <td> Lysake squash </td>
                                                    <td>
                                                        <a href="javascript:;" class="btn btn-sm btn-outline yellow-casablanca">
                                                            <i class="fa fa-edit"></i> Remove </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="highlight">
                                                        <div class="warning"></div>
                                                        <a href="javascript:;"> Stefan Bogdan </a>
                                                    </td>
                                                    <td class="hidden-xs"> 0744511446 </td>
                                                    <td> <a href="javascript:;">stefan.bogdan@ymail.com</a> </td>
                                                    <td> Lysake squash </td>
                                                    <td>
                                                        <a href="javascript:;" class="btn btn-sm btn-outline yellow-casablanca">
                                                            <i class="fa fa-edit"></i> Remove </a>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- END EXAMPLE TABLE PORTLET-->
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

        $(document).on('click', '.cancel_booking', function(){
            //alert('Cancel Booking' + $(this).attr('data-id'));

            get_booking_details($(this).attr('data-id'), $('#small_cancel'));
            $('#small_cancel').modal('show');
        });

        function cancel_booking(){
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