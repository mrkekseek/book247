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
                                <th width="22%"> Bærum Squash & Fitness </th>
                                <th width="22%"> Lysaker Squash </th>
                                <th width="22%"> Sagene Squashsenter </th>
                                <th> Sagene Squashsenter </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <th> 07:00 </th>
                                <td class="bg-grey-salt bg-font-grey-salt"> &nbsp; </td>
                                <td class="bg-grey-salt bg-font-grey-salt"> &nbsp; </td>
                                <td class="bg-grey-salt bg-font-grey-salt"> &nbsp; </td>
                                <td class="bg-grey-salt bg-font-grey-salt"> &nbsp; </td>
                            </tr>
                            <tr>
                                <th> 07:30 </th>
                                <td class="bg-grey-salt bg-font-grey-salt"> &nbsp; </td>
                                <td class="bg-yellow-gold bg-font-yellow-gold"> <small><a href="" class="font-white">John Snow</a><br /></small> </td>
                                <td class="bg-grey-salt bg-font-grey-salt"> &nbsp; </td>
                                <td class="bg-grey-salt bg-font-grey-salt"> &nbsp; </td>
                            </tr>
                            <tr>
                                <th> 08:00 </th>
                                <td class="bg-grey-salt bg-font-grey-salt"> &nbsp; </td>
                                <td class="bg-grey-salt bg-font-grey-salt"> &nbsp; </td>
                                <td class="bg-grey-salt bg-font-grey-salt"> &nbsp; </td>
                                <td class="bg-grey-salt bg-font-grey-salt"> &nbsp; </td>
                            </tr>
                            <tr>
                                <th> 08:30 </th>
                                <td class="bg-grey-salt bg-font-grey-salt"> &nbsp; </td>
                                <td class="bg-grey-salt bg-font-grey-salt"> &nbsp; </td>
                                <td class="bg-grey-salt bg-font-grey-salt"> &nbsp; </td>
                                <td class="bg-grey-salt bg-font-grey-salt"> &nbsp; </td>
                            </tr>
                            <tr>
                                <th> 09:00 </th>
                                <td class="bg-grey-salt bg-font-grey-salt"> &nbsp; </td>
                                <td class="bg-grey-salt bg-font-grey-salt"> &nbsp; </td>
                                <td class="bg-grey-salt bg-font-grey-salt"> &nbsp; </td>
                                <td class="bg-grey-salt bg-font-grey-salt"> &nbsp; </td>
                            </tr>
                            <tr>
                                <th> 09:30 </th>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                            </tr>
                            <tr>
                                <th> 10:00 </th>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                            </tr>
                            <tr>
                                <th> 10:30 </th>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                            </tr>
                            <tr>
                                <th> 11:00 </th>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                            </tr>
                            <tr>
                                <th> 11:30 </th>
                                <td class="bg-purple-seance bg-font-purple-seance"> <small><a href="" class="font-white">John Snow</a><br /></small> </td>
                                <td class="bg-purple-seance bg-font-purple-seance"> <small><a href="" class="font-white">John Snow</a><br /></small> </td>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                            </tr>
                            <tr>
                                <th> 12:00 </th>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                                <td class="bg-purple-seance bg-font-purple-seance"> <small><a href="" class="font-white">John Snow</a><br /></small> </td>
                                <td> &nbsp; </td>
                            </tr>
                            <tr>
                                <th> 12:30 </th>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                                <td class="bg-yellow-gold bg-font-yellow-gold"> <small><a href="" class="font-white">John Snow</a><br /></small> </td>
                                <td> &nbsp; </td>
                            </tr>
                            <tr>
                                <th> 13:00 </th>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                                <td class="bg-yellow-gold bg-font-yellow-gold"> <small><a href="" class="font-white">John Snow</a><br /></small> </td>
                                <td> &nbsp; </td>
                            </tr>
                            <tr>
                                <th> 13:30 </th>
                                <td> &nbsp; </td>
                                <td class="bg-purple-seance bg-font-purple-seance"> <small><a href="" class="font-white">John Snow</a><br /></small> </td>
                                <td class="bg-yellow-gold bg-font-yellow-gold"> <small><a href="" class="font-white">John Snow</a><br /></small> </td>
                                <td> &nbsp; </td>
                            </tr>
                            <tr>
                                <th> 14:00 </th>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                                <td class="bg-yellow-gold bg-font-yellow-gold"> <small><a href="" class="font-white">John Snow</a><br /></small> </td>
                                <td> &nbsp; </td>
                            </tr>
                            <tr>
                                <th> 14:30 </th>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                            </tr>
                            <tr>
                                <th> 15:00 </th>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                            </tr>
                            <tr>
                                <th> 15:30 </th>
                                <td> &nbsp; </td>
                                <td class="bg-purple-seance bg-font-purple-seance"> <small><a href="" class="font-white">John Snow</a><br /></small> </td>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                            </tr>
                            <tr>
                                <th> 16:00 </th>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                            </tr>
                            <tr>
                                <th> 16:30 </th>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                            </tr>
                            <tr>
                                <th> 17:00 </th>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                            </tr>
                            <tr>
                                <th> 17:30 </th>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                            </tr>
                            <tr>
                                <th> 18:00 </th>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                            </tr>
                            <tr>
                                <th> 18:30 </th>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                            </tr>
                            <tr>
                                <th> 19:00 </th>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                            </tr>
                            <tr>
                                <th> 19:30 </th>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                            </tr>
                            <tr>
                                <th> 20:00 </th>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                            </tr>
                            <tr>
                                <th> 20:30 </th>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                            </tr>
                            <tr>
                                <th> 21:00 </th>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                            </tr>
                            <tr>
                                <th> 21:30 </th>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                            </tr>
                            <tr>
                                <th> 22:00 </th>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                            </tr>
                            <tr>
                                <th> 22:30 </th>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                                <td> &nbsp; </td>
                            </tr>
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