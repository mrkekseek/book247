@extends('admin.layouts.federation.main')

@section('pageLevelPlugins')
    <link href="{{ asset('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('themeGlobalStyle')
    <link href="{{ asset('assets/global/css/components-rounded.min.css') }}" rel="stylesheet" id="style_components" type="text/css" />
    <link href="{{ asset('assets/global/css/plugins.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/jquery-notific8/jquery.notific8.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('themeLayoutStyle')
    <!-- BEGIN PAGE LEVEL STYLES -->
    <link href="{{ asset('assets/pages/css/profile.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL STYLES -->
    <link href="{{ asset('assets/layouts/layout4/css/layout.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/layouts/layout4/css/themes/light.min.css') }}" rel="stylesheet" type="text/css" id="style_color" />
    <link href="{{ asset('assets/layouts/layout4/css/custom.min.css') }}" rel="stylesheet" type="text/css" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('title', 'Back-end users - User Details')
@section('pageBodyClass','page-container-bg-solid page-header-fixed page-sidebar-closed-hide-logo')

@section('pageContentBody')
    <div class="page-content fix_padding_top_0">
        <!-- BEGIN PAGE BASE CONTENT -->
        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN PROFILE SIDEBAR -->
                <div class="profile-sidebar">
                    <!-- PORTLET MAIN -->
                    <div class="portlet light profile-sidebar-portlet bordered">
                        <!-- SIDEBAR USERPIC -->
                        <div class="profile-userpic">
                            <img src="{{ $avatar }}" class="img-responsive" alt="" />
                        </div>
                        <!-- END SIDEBAR USERPIC -->
                        <!-- SIDEBAR USER TITLE -->
                        <div class="profile-usertitle">
                            <div class="profile-usertitle-name"> {{$user->first_name.' '.$user->middle_name.' '.$user->last_name}} </div>
                            <div class="profile-usertitle-job"> {{ $user->membership_status() }} </div>
                        </div>
                        <!-- END SIDEBAR USER TITLE -->
                        <!-- SIDEBAR BUTTONS -->
                        <div class="profile-userbuttons">
                            <button type="button" class="btn btn-circle yellow-mint btn-sm member_send_message">Add Message</button>
                            <button type="button" class="btn btn-circle btn-sm member_suspend_access {{ $user->status=='active'?'red':'green-jungle' }}">{{ $user->status=='active'?'Suspend ':'Reactivate ' }} Member</button>
                        </div>
                        <!-- END SIDEBAR BUTTONS -->
                        <!-- SIDEBAR MENU -->
                        <div class="profile-usermenu">
                            <ul class="nav">
                                <li class="active">
                                    <a href="{{route("admin/front_users/view_user", $user->id)}}">
                                        <i class="icon-home"></i> Overview </a>
                                </li>
                                <li>
                                    <a href="{{route("admin/front_users/view_personal_settings", $user->id)}}">
                                        <i class="icon-settings"></i> Personal Settings </a>
                                </li>
                                <li>
                                    <a href="{{route("admin/front_users/view_account_settings", $user->id)}}">
                                        <i class="icon-settings"></i> Account Settings </a>
                                </li>
                                {{--<li>--}}
                                    {{--<a href="{{route("admin/front_users/view_bookings", $user->id)}}">--}}
                                        {{--<i class="fa fa-calendar"></i> Bookings </a>--}}
                                {{--</li>--}}
                                <li>
                                    <a href="{{route("admin/front_users/view_finance", $user->id)}}">
                                        <i class="fa fa-money"></i> Finance </a>
                                </li>
                            </ul>
                        </div>
                        <!-- END MENU -->
                    </div>
                    <!-- END PORTLET MAIN -->
                    <!-- PORTLET MAIN -->
                    {{--<div class="portlet light bordered">--}}
                        {{--<!-- STAT -->--}}
                        {{--<div class="row list-separated profile-stat">--}}
                            {{--<div class="col-md-4 col-sm-4 col-xs-6">--}}
                                {{--<div class="uppercase profile-stat-title"> {{ $countOldBookings }} </div>--}}
                                {{--<div class="uppercase profile-stat-text"> Old Bookings </div>--}}
                            {{--</div>--}}
                            {{--<div class="col-md-4 col-sm-4 col-xs-6">--}}
                                {{--<div class="uppercase profile-stat-title"> {{$countCancelledBookings}} </div>--}}
                                {{--<div class="uppercase profile-stat-text"> Cancelled Bookings </div>--}}
                            {{--</div>--}}
                            {{--<div class="col-md-4 col-sm-4 col-xs-6">--}}
                                {{--<div class="uppercase profile-stat-title"> {{$countActiveBookings}} </div>--}}
                                {{--<div class="uppercase profile-stat-text"> Active Bookings </div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<!-- END STAT -->--}}
                        {{--<div>--}}
                            {{--<h4 class="profile-desc-title">About {{$user->first_name.' '.$user->middle_name.' '.$user->last_name}}</h4>--}}
                            {{--<span class="profile-desc-text"> {{ @$user->personalDetail->about_info }} </span>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    <!-- END PORTLET MAIN -->
                </div>
                <!-- END BEGIN PROFILE SIDEBAR -->
                <!-- BEGIN PROFILE CONTENT -->
                <div class="profile-content">
                    <div class="row">
                        <div class="col-md-6">
                            <!-- BEGIN PORTLET -->
                            <div class="portlet light bordered">
                                <div class="portlet-title tabbable-line">
                                    <div class="caption caption-md">
                                        <i class="icon-globe theme-font hide"></i>
                                        <span class="caption-subject font-blue-madison bold uppercase">Registered events</span>
                                    </div>
                                    {{--<ul class="nav nav-tabs">--}}
                                        {{--<!--<li class="active"><a href="#tab_2_1" data-toggle="tab"> This Week </a></li>-->--}}
                                        {{--<li class="active">--}}
                                            {{--<a href="#tab_2_2" data-toggle="tab"> This Month </a>--}}
                                        {{--</li>--}}
                                        {{--<li>--}}
                                            {{--<a href="#tab_2_3" data-toggle="tab"> Last 3 Months </a>--}}
                                        {{--</li>--}}
                                        {{--<li>--}}
                                            {{--<a href="#tab_2_4" data-toggle="tab"> All Time </a>--}}
                                        {{--</li>--}}
                                    {{--</ul>--}}
                                </div>
                                <div class="portlet-body tab-content">
                                    @foreach ($top_stats as $stat)
                                        <div class="table-scrollable table-scrollable-borderless tab-pane {{ $stat['ord']==2?'active':'' }}" id="tab_2_{{ $stat['ord'] }}">
                                            <table class="table table-hover table-light">
                                                <thead>
                                                <tr class="uppercase">
                                                    <th> Bookings </th>
                                                    <th style="text-align:center"> NUMBER </th>
                                                    <th> MONEY </th>
                                                    <th> RATE </th>
                                                </tr>
                                                </thead>
                                                <tr>
                                                    <td>
                                                        <a href="javascript:;" class="primary-link">Calendar Products</a>
                                                    </td>
                                                    <td align="center"> {{ $stat['drop_ins']['nr'] }} </td>
                                                    <td> {{ $stat['drop_ins']['money'] }} {{ Config::get('constants.finance.currency') }} </td>
                                                    <td>
                                                        <span class="bold theme-font">{{ $stat['drop_ins']['rate']=='-'?'-':$stat['drop_ins']['rate'].'%' }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <a href="javascript:;" class="primary-link">Membership</a>
                                                    </td>
                                                    <td align="center"> {{ $stat['membership']['nr'] }} </td>
                                                    <td> {{ $stat['membership']['money'] }} {{ Config::get('constants.finance.currency') }} </td>
                                                    <td>
                                                        <span class="bold theme-font">{{ $stat['membership']['rate']=='-'?'-':$stat['membership']['rate'].'%' }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <a href="javascript:;" class="primary-link">Active</a>
                                                    </td>
                                                    <td align="center"> {{ $stat['active']['nr'] }} </td>
                                                    <td> {{ $stat['active']['money'] }} {{ Config::get('constants.finance.currency') }} </td>
                                                    <td>
                                                        <span class="bold theme-font">{{ $stat['active']['rate']=='-'?'-':$stat['active']['rate'].'%' }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <a href="javascript:;" class="primary-link">Show</a>
                                                    </td>
                                                    <td align="center"> {{ $stat['show']['nr'] }} </td>
                                                    <td> {{ $stat['show']['money'] }} {{ Config::get('constants.finance.currency') }} </td>
                                                    <td>
                                                        <span class="bold theme-font">{{ $stat['show']['rate']=='-'?'-':$stat['show']['rate'].'%' }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <a href="javascript:;" class="primary-link">No Show</a>
                                                    </td>
                                                    <td align="center"> {{ $stat['no_show']['nr'] }} </td>
                                                    <td> {{ $stat['no_show']['money'] }} {{ Config::get('constants.finance.currency') }} </td>
                                                    <td>
                                                        <span class="bold theme-font">{{ $stat['no_show']['rate']=='-'?'-':$stat['no_show']['rate'].'%' }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <a href="javascript:;" class="primary-link">Cancelled</a>
                                                    </td>
                                                    <td align="center"> {{ $stat['canceled']['nr'] }} </td>
                                                    <td> {{ $stat['canceled']['money'] }} {{ Config::get('constants.finance.currency') }} </td>
                                                    <td>
                                                        <span class="bold theme-font">{{ $stat['canceled']['rate']=='-'?'-':$stat['canceled']['rate'].'%' }}</span>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <!-- END PORTLET -->
                        </div>
                        <div class="col-md-6">
                            <!-- BEGIN PORTLET -->
                            <div class="portlet light bordered">
                                <div class="portlet-title tabbable-line">
                                    <div class="caption caption-md">
                                        <i class="icon-globe theme-font hide"></i>
                                        <span class="caption-subject font-blue-madison bold uppercase">User Actions</span>
                                    </div>
                                    {{--<ul class="nav nav-tabs">--}}
                                        {{--<li {!! sizeof($redFlagLog)==0?'class="active"':'' !!}>--}}
                                            {{--<a href="#tab_1_1" data-toggle="tab"> All Actions </a>--}}
                                        {{--</li>--}}
                                        {{--<li {!! sizeof($redFlagLog)>0?'class="active"':'' !!}>--}}
                                            {{--<a href="#tab_1_2" data-toggle="tab"> Red Flagged </a>--}}
                                        {{--</li>--}}
                                    {{--</ul>--}}
                                </div>
                                <div class="portlet-body">
                                    <!--BEGIN TABS-->
                                    <div class="tab-content">
                                        <div class="tab-pane {!! sizeof($redFlagLog)==0?' active':'' !!}" id="tab_1_1">
                                            <div class="scroller" style="height: 277px;" data-always-visible="1" data-rail-visible1="0" data-handle-color="#D7DCE2">
                                                @if (sizeof($activityLog)>0)
                                                    <ul class="feeds">
                                                    @foreach($activityLog as $logView)
                                                        <li>
                                                            <div class="col1">
                                                                <div class="cont">
                                                                    <div class="cont-col1">
                                                                        <div class="label label-sm label-success">
                                                                            <i class="fa fa-bell-o"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="cont-col2">
                                                                        <div class="desc">
                                                                            {{ $logView['logDate'] }} - <span class="font-blue-soft">{{ $logView['description'] }}</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col2" style="width:100px; margin-left:-100px;">
                                                                <div class="date"> <small style="font-size:10px;" class="font-purple-studio">{{ $logView['ip_address'] }}</small> </div>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                                @else
                                                    <div class="note note-info">
                                                        <p>There is no member activity now. Once the member starts using his account, all his activity will be placed here.</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="tab-pane {!! sizeof($redFlagLog)>0?' active':'' !!}" id="tab_1_2">
                                            <div class="scroller" style="height: 355px;" data-always-visible="1" data-rail-visible1="0" data-handle-color="#D7DCE2">
                                                @if (sizeof($redFlagLog)>0)
                                                    <ul class="feeds">
                                                        @foreach($redFlagLog as $logView)
                                                            <li>
                                                                <div class="col1">
                                                                    <div class="cont">
                                                                        <div class="cont-col1">
                                                                            <div class="label label-sm label-success">
                                                                                <i class="fa fa-bell-o"></i>
                                                                            </div>
                                                                        </div>
                                                                        <div class="cont-col2">
                                                                            <div class="desc"> {{ $logView['description'] }} - {{ $logView['action'] }} </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col2" style="width:100px; margin-left:-100px;">
                                                                    <div class="date"> {{ $logView['addedOn'] }} </div>
                                                                </div>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <div class="note note-warning">
                                                        <p> There are no red flags raised for this member. </p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <!--END TABS-->
                                </div>
                            </div>
                            <!-- END PORTLET -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <!-- BEGIN PORTLET -->
                            <div class="portlet light bordered">
                                <div class="portlet-title">
                                    <div class="caption caption-md">
                                        <i class="icon-bar-chart theme-font hide"></i>
                                        <span class="caption-subject font-green-soft bold uppercase">User Messages</span>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <div class="scroller" style="height: 282px;" data-always-visible="1" data-rail-visible1="0" data-handle-color="#D7DCE2">
                                        <div class="general-item-list">
                                            @if (sizeof($publicNote)>0)
                                                @foreach($publicNote as $note)
                                                    <div class="item">
                                                        <div class="item-head">
                                                            <div class="item-details">
                                                                <img class="item-pic" src="../assets/pages/media/users/avatar4.jpg">
                                                                <a href="" class="item-name primary-link">{{ $note['by_user'] }}</a>
                                                                <span class="item-label">{{ $note['addedOn'] }}</span>
                                                            </div>
                                                            <span class="item-status">
                                                                <span class="badge badge-empty badge-success"></span> {{ $note['status'] }}</span>
                                                        </div>
                                                        <div class="item-body"> {{ $note['note_body'] }} </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="note note-info">
                                                    <p>There is no user messages sent to this member. Once the member starts receiving messages from the system or backend users they will be shown here.</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END PORTLET -->
                        </div>
                        <div class="col-md-6">
                            <!-- BEGIN PORTLET -->
                            <div class="portlet light bordered tasks-widget">
                                <div class="portlet-title">
                                    <div class="caption caption-md">
                                        <i class="icon-bar-chart theme-font hide"></i>
                                        <span class="caption-subject font-yellow-gold bold uppercase">Internal Messages</span>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <div class="task-content">
                                        <div class="scroller" style="height: 282px;" data-always-visible="1" data-rail-visible1="0" data-handle-color="#D7DCE2">
                                            @if (sizeof($privateNote)>0)
                                                <ul class="task-list">
                                                @foreach($privateNote as $note)
                                                    <li>
                                                        <div class="task-checkbox">
                                                            <input type="hidden" value="1" name="test" />
                                                        </div>
                                                        <div class="task-title">
                                                            <span class="task-title-sp">  <span class="font-blue-dark">{{ $note['note_body'] }} by </span> {{ $note['by_user'] }} {{ $note['addedOn'] }}  </span>
                                                            @if($note['status']=='pending')
                                                            <span class="label label-sm label-danger">{{ $note['status'] }}</span>
                                                            <span class="task-bell"><i class="fa fa-bell-o"></i></span>
                                                            @elseif($note['status']=='completed')
                                                            <span class="label label-sm label-info">{{ $note['status'] }}</span>
                                                            @elseif($note['status']=='deleted')
                                                            <span class="label label-sm label-default">{{ $note['status'] }}</span>
                                                            @elseif ($note['status']=='unread')
                                                            <span class="label label-sm label-success mark_note_as_read" data-id="{{ $note['id'] }}" is-general="{{ $note['is_general'] }}" style="cursor:pointer;">{{ $note['status'] }}</span>
                                                            @endif
                                                        </div>
                                                        @if ($note['status']=='pending')
                                                        <div class="task-config">
                                                            <div class="task-config-btn btn-group">
                                                                <a class="btn btn-sm default" href="javascript:;" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                                                    <i class="fa fa-cog"></i>
                                                                    <i class="fa fa-angle-down"></i>
                                                                </a>
                                                                <ul class="dropdown-menu pull-right">
                                                                    <li>
                                                                        <a href="javascript:;" class="note_mark_complete" data-id="{{ $note['id'] }}" is-general="{{ $note['is_general'] }}">
                                                                            <i class="fa fa-check"></i> Complete </a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="javascript:;" data-id="{{ $note['id'] }}"  is-general="{{ $note['is_general'] }}" class="note_mark_cancel">
                                                                            <i class="fa fa-trash-o"></i> Cancel/Delete </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        @endif
                                                    </li>
                                                @endforeach
                                                </ul>
                                            @else
                                                <div class="note note-info">
                                                    <p>There is no user messages sent to this member. Once the member starts receiving messages from the system or backend users they will be shown here.</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END PORTLET -->
                        </div>
                    </div>
                </div>
                <!-- END PROFILE CONTENT -->
                <!-- BEGIN General Message modal window -->
                <div class="modal fade" id="general_message_box" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                <h4 class="modal-title"> Send a message to this member or about this member </h4>
                            </div>
                            <div class="modal-body form-horizontal">
                                <div class="form-body">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">
                                            Public Message<br /><small>visible by members</small><br />
                                        </label>
                                        <div class="col-md-8">
                                            <textarea type="text" class="form-control input-inline input-large input-sm" name="custom_general_message"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label"> Internal Message<br /><small>visible by employees only</small> </label>
                                        <div class="col-md-8">
                                            <textarea type="text" class="form-control input-inline input-large input-sm" name="private_general_message"></textarea><br />
                                            <input value="1" name="private_general_action" type="checkbox"><small>check if this note will require future action </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn green btn_modify_booking" onclick="javascript:send_member_general_message();">Send Message</button>
                                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Return</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <!-- END General Message modal window -->
                <!-- BEGIN Status Change modal window -->
                <div class="modal fade" id="change_member_status" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form role="form" id="form_account_change_status" action="#">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                    <h4 class="modal-title"> {{ $user->status=='active'?'Suspend ':'Reactivate ' }} member </h4>
                                </div>
                                <div class="modal-body form-horizontal">
                                    <div class="alert alert-danger display-hide">
                                        <button class="close" data-close="alert"></button> Please add a short message about your action - more than 15 characters. </div>
                                    <div class="alert alert-success display-hide">
                                        <button class="close" data-close="alert"></button> Your form validation is successful! </div>
                                    <div class="note note-info" style="margin-bottom:0px;">
                                        <p> Current status : <span style="text-transform: uppercase; font-weight:bold;">{{ $user->status }}</span> . In order to change user status you have to provide a reason / note to this action. </p>
                                        <div class="form-group" style="margin:0px -15px 0px 0px;">
                                            <label class="control-label"> Internal Message <small>visible by employees</small></label>
                                            <textarea class="form-control input-sm" name="custom_status_change_message"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" onclick="javascript: $('#form_account_change_status').submit();" class="btn green btn_modify_booking">{{ $user->status=='active'?'Suspend User':'Reactivate User' }}</button>
                                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Return</button>
                                </div>
                            </form>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <!-- END Status Change modal window -->
            </div>
        </div>
        <!-- END PAGE BASE CONTENT -->
    </div>
@endsection

@section('pageBelowLevelPlugins')
    <script src="{{ asset('assets/global/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/jquery-validation/js/additional-methods.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/jquery.sparkline.min.js') }}" type="text/javascript"></script>
@endsection

@section('pageBelowLevelScripts')
    <script src="{{ asset('assets/global/plugins/jquery-notific8/jquery.notific8.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/jquery.blockui.min.js') }}" type="text/javascript"></script>
@endsection

@section('themeBelowLayoutScripts')
    <script src="{{ asset('assets/layouts/layout4/scripts/layout.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/layouts/layout4/scripts/demo.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/layouts/global/scripts/quick-sidebar.min.js') }}" type="text/javascript"></script>
@endsection

@section('pageCustomJScripts')
    <script type="text/javascript">
        $.validator.addMethod("datePickerDate",function(value, element) {
            // put your own logic here, this is just a (crappy) example
            return value.match(/^\d\d?-\d\d?-\d\d\d\d$/);
        },"Please enter a date in the format dd/mm/yyyy.");
        $.validator.addMethod('filesize',function(value, element, param) {
            // param = size (in bytes)
            // element = element to validate (<input>)
            // value = value of the element (file name)
            return this.optional(element) || (element.files[0].size <= param);
        },"File must be JPG, GIF or PNG, less than 1MB");
        $.validator.addMethod("validate_email",function(value, element) {
            if(/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test( value )) {
                return true;
            }
            else {
                return false;
            }
        },"Please enter a valid Email.");

        var FormValidation = function () {

            var handleValidationAccChange = function() {
                var form1 = $('#form_account_change_status');
                var error1 = $('.alert-danger', form1);
                var success1 = $('.alert-success', form1);

                form1.validate({
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block help-block-error', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    ignore: "",  // validate all fields including form hidden input
                    rules: {
                        custom_status_change_message: {
                            minlength: 15,
                            required: true
                        },
                    },

                    invalidHandler: function (event, validator) { //display error alert on form submit
                        success1.hide();
                        error1.show();
                        App.scrollTo(error1, -200);
                    },

                    errorPlacement: function (error, element) { // render error placement for each input type
                        var icon = $(element).parent('.input-icon').children('i');
                        icon.removeClass('fa-check').addClass("fa-warning");
                        icon.attr("data-original-title", error.text()).tooltip({'container': 'body'});
                    },

                    highlight: function (element) { // hightlight error inputs
                        $(element)
                                .closest('.form-group').removeClass("has-success").addClass('has-error'); // set error class to the control group
                    },

                    unhighlight: function (element) { // revert the change done by hightlight

                    },

                    success: function (label, element) {
                        var icon = $(element).parent('.input-icon').children('i');
                        $(element).closest('.form-group').removeClass('has-error').addClass('has-success'); // set success class to the control group
                        icon.removeClass("fa-warning").addClass("fa-check");
                    },

                    submitHandler: function (form) {
                        success1.show();
                        error1.hide();
                        change_member_status(); // submit the form
                    }
                });
            }

            return {
                //main function to initiate the module
                init: function () {
                    handleValidationAccChange();
                }
            };
        }();

        var Profile = function() {
            var dashboardMainChart = null;
            return {
                //main function
                init: function() {
                    Profile.initMiniCharts();
                },

                initMiniCharts: function() {
                    $("#sparkline_bar").sparkline([{{ implode(',', $finance_paid_list) }}], {
                        type: 'bar',
                        width: '100',
                        barWidth: 6,
                        height: '45',
                        barColor: '#F36A5B',
                        negBarColor: '#e02222'
                    });

                    $("#sparkline_bar2").sparkline([{{ implode(',', $bookings_paid_list) }}], {
                        type: 'bar',
                        width: '100',
                        barWidth: 6,
                        height: '45',
                        barColor: '#5C9BD1',
                        negBarColor: '#e02222'
                    });
                }
            };
        }();

        $(document).ready(function(){
            FormValidation.init();

            Profile.init();
        });

        function store_account_info(){
            $.ajax({
                url: '{{route('admin/back_users/view_user/acc_info', ['id'=>$user->id])}}',
                type: "post",
                data: {
                    'accountUsername': $('input[name=accountUsername]').val(),
                    'accountEmail': $('input[name=accountEmail]').val(),
                    'accountJobTitle': $('input[name=accountJobTitle]').val(),
                    'accountProfession': $('input[name=accountProfession]').val(),
                    'accountDescription': $('textarea[name=accountDescription]').val(),
                    'employeeRole': $('select[name=employeeRole]').val(),
                    '_method': 'post',
                },
                success: function(data){
                    alert(data);
                }
            });
        }

        function store_account_personal(){
            $.ajax({
                url: '{{route('admin/back_users/view_user/personal_info', ['id'=>$user->id])}}',
                type: "post",
                data: {
                    'first_name':       $('input[name=personalFirstName]').val(),
                    'middle_name':      $('input[name=personalMiddleName]').val(),
                    'last_name':        $('input[name=personalLastName]').val(),
                    'date_of_birth':    $('input[name=personalDOB]').val(),
                    'personal_email':   $('input[name=personalEmail]').val(),
                    'mobile_number':    $('input[name=personalPhone]').val(),
                    'bank_acc_no':      $('input[name=personalBankAcc]').val(),
                    'social_sec_no':    $('input[name=personalSSN]').val(),
                    'about_info':       $('textarea[name=personalAbout]').val(),
                    'country_id':       $('select[name=personalCountry]').val(),
                    '_method': 'post',
                },
                success: function(data){
                    alert(data);
                }
            });
        }

        function update_personal_address(){
            $.ajax({
                url: '{{route('admin/back_users/view_user/personal_address', ['id'=>$user->id])}}',
                type: "post",
                data: {
                    'address1':     $('input[name=personal_addr1]').val(),
                    'address2':     $('input[name=personal_addr2]').val(),
                    'city':         $('input[name=personal_addr_city]').val(),
                    'region':       $('input[name=personal_addr_region]').val(),
                    'postal_code':  $('input[name=personal_addr_pcode]').val(),
                    'country_id':   $('select[name=personal_addr_country]').val(),
                    '_method': 'post',
                },
                success: function(data){
                    alert(data);
                }
            });
        }

        function update_passwd(){
            $.ajax({
                url: '{{route('admin/back_users/view_user/password_update', ['id'=>$user->id])}}',
                type: "post",
                data: {
                    'old_password': $('input[name=old_password]').val(),
                    'password1':    $('input[name=new_password1]').val(),
                    'password2':    $('input[name=new_password2]').val(),
                    '_method': 'post',
                },
                success: function(data){
                    alert(data);
                }
            });
        }

        $(".user_avatar_select_btn1").on("click", function(){
            App.blockUI({
                target: '#user_picture_upload1',
                boxed: true
            });
        });

        $(".user_avatar_select_btn1").on("change", function(){
            App.unblockUI('#user_picture_upload1');
        });

        $(".user_avatar_select_btn2").on("click", function(){
            App.blockUI({
                target: '#user_picture_upload2',
                boxed: true
            });
        });

        $(".user_avatar_select_btn2").on("change", function(){
            App.unblockUI('#user_picture_upload2');
        });

        /* Start general - send message */
        $(".member_send_message").on("click", function(){
            $('#general_message_box').modal('show');
        });

        function send_member_general_message(){
            if ($('input[name=private_general_action]').is(':checked')){
                var pending_action = 1;
            }
            else{
                var pending_action = 0;
            }

            $.ajax({
                url: '{{route('ajax/general_note_add_new')}}',
                type: "post",
                cache: false,
                data: {
                    'title_message':    'General Note',
                    'memberID':         '{{ $user->id }}',
                    'custom_message':   $('textarea[name="custom_general_message"]').val(),
                    'private_message':  $('textarea[name="private_general_message"]').val(),
                    'note_action':      pending_action,
                },
                success: function (data) {
                    if (data.success) {
                        show_notification(data.title, data.message, 'lemon', 3500, 0);
                        setTimeout(function(){
                            location.reload();
                        }, 1000);
                    }
                    else{
                        show_notification(data.title, data.errors, 'ruby', 3500, 0);
                    }

                    $('#general_message_box').modal('hide');
                }
            });
        }

        $(".note_mark_complete").on('click', function(){
            var noteID = $(this).attr('data-id');
            var isGeneral = $(this).attr('is-general');
            internal_note_status_change(noteID, 'complete', isGeneral);
        });

        $(".note_mark_cancel").on('click', function(){
            var noteID = $(this).attr('data-id');
            var isGeneral = $(this).attr('is-general');
            internal_note_status_change(noteID, 'cancel', isGeneral);
        });

        $(".mark_note_as_read").on('click', function(){
            var noteID = $(this).attr('data-id');
            var isGeneral = $(this).attr('is-general');
            internal_note_status_change(noteID, 'read', isGeneral);
        });

        function internal_note_status_change(noteID, status, is_general){
            $.ajax({
                url: '{{route('ajax/internal_note_status_change')}}',
                type: "post",
                cache: false,
                data: {
                    'noteID': noteID,
                    'status': status,
                    'is_general': is_general
                },
                success: function (data) {
                    if (data.success) {
                        show_notification(data.title, data.message, 'lemon', 3500, 0);
                        if (status=='read'){
                            $('span[data-id='+noteID+']').remove();
                        }
                        else{
                            setTimeout(function(){
                                location.reload();
                            }, 1000);
                        }
                    }
                    else{
                        show_notification(data.title, data.errors, 'ruby', 3500, 0);
                    }

                    $('#general_message_box').modal('hide');
                }
            });
        }
        /* Stop general - send message */

        /* Start general - suspend user access */
        $(".member_suspend_access").on("click", function(){
            $('#change_member_status').modal('show');
        });

        function change_member_status(){
            $.ajax({
                url: '{{route('ajax/front_member_change_status')}}',
                type: "post",
                cache: false,
                data: {
                    'memberID':         '{{ $user->id }}',
                    'custom_message':   $('textarea[name="custom_status_change_message"]').val(),
                },
                success: function (data) {
                    if (data.success) {
                        $('#change_member_status').modal('hide');
                        show_notification(data.title, data.message, 'lemon', 3500, 0);
                        location.reload();
                    }
                    else{
                        show_notification(data.title, data.errors, 'ruby', 3500, 0);
                    }
                }
            });
        }
        /* Stop general - suspend user access */
    </script>
@endsection