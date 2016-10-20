@extends('admin.layouts.main')

@section('pageLevelPlugins')
    <link href="{{ asset('assets/global/plugins/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/dropzone/basic.min.css') }}" rel="stylesheet" type="text/css" />
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
                            <button type="button" class="btn btn-circle green btn-sm">Follow</button>
                            <button type="button" class="btn btn-circle red btn-sm">Message</button>
                        </div>
                        <!-- END SIDEBAR BUTTONS -->
                        <!-- SIDEBAR MENU -->
                        <div class="profile-usermenu">
                            <ul class="nav">
                                <li>
                                    <a href="{{route("admin/front_users/view_user", $user->id)}}">
                                        <i class="icon-home"></i> Overview </a>
                                </li>
                                <li class="active">
                                    <a href="{{route("admin/front_users/view_account_settings", $user->id)}}">
                                        <i class="icon-settings"></i> Account Settings </a>
                                </li>
                                <li>
                                    <a href="{{route("admin/front_users/view_bookings", $user->id)}}">
                                        <i class="fa fa-calendar"></i> Bookings </a>
                                </li>
                                <li>
                                    <a href="{{route("admin/front_users/view_finance", $user->id)}}">
                                        <i class="fa fa-money"></i> Finance </a>
                                </li>
                            </ul>
                        </div>
                        <!-- END MENU -->
                    </div>
                    <!-- END PORTLET MAIN -->
                </div>
                <!-- END BEGIN PROFILE SIDEBAR -->
                <!-- BEGIN PROFILE CONTENT -->
                <div class="profile-content">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="portlet light bordered">
                                <div class="portlet-title tabbable-line">
                                    <div class="caption caption-md">
                                        <i class="icon-globe theme-font hide"></i>
                                        <span class="caption-subject font-blue-madison bold uppercase">Profile Account</span>
                                    </div>
                                    <ul class="nav nav-tabs">
                                        <li class="active">
                                            <a href="#tab_1_1" data-toggle="tab">Personal Info</a>
                                        </li>
                                        <li>
                                            <a href="#tab_1_5" data-toggle="tab">Membership Plan</a>
                                        </li>
                                        <li>
                                            <a href="#tab_1_2" data-toggle="tab">Change Avatar</a>
                                        </li>
                                        <li>
                                            <a href="#tab_1_3" data-toggle="tab">Change Password</a>
                                        </li>
                                        <li>
                                            <a href="#tab_1_4" data-toggle="tab">Documents</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="portlet-body">
                                    <div class="tab-content">
                                        <!-- PERSONAL INFO TAB -->
                                        <div class="tab-pane active row" id="tab_1_1">
                                            <form role="form" id="form_acc_personal" action="#">
                                                <div class="alert alert-danger display-hide">
                                                    <button class="close" data-close="alert"></button> You have some form errors. Please check below. </div>
                                                <div class="alert alert-success display-hide">
                                                    <button class="close" data-close="alert"></button> Your form validation is successful! </div>
                                                <div class="form-group">
                                                    <label class="control-label">First Name</label>
                                                    <input type="text" name="personalFirstName" id="personalFirstName" placeholder="First Name" value="{{$user->first_name}}" class="form-control" /> </div>
                                                <div class="form-group">
                                                    <label class="control-label">Middle Name</label>
                                                    <input type="text" name="personalMiddleName" id="personalMiddleName" placeholder="Middle Name" value="{{$user->middle_name}}" class="form-control" /> </div>
                                                <div class="form-group">
                                                    <label class="control-label">Last Name</label>
                                                    <input type="text" name="personalLastName" id="personalLastName" placeholder="Last Name" value="{{$user->last_name}}" class="form-control" /> </div>
                                                <div class="form-group">
                                                    <label class="control-label">Gender</label>
                                                    <select name="gender" class="form-control">
                                                        <option value>Select Gender</option>
                                                        <option {!! $user->gender=='F'?'selected="selected"':'' !!} value="F"> Female </option>
                                                        <option {!! $user->gender=='M'?'selected="selected"':'' !!} value="M"> Male </option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Citizenship</label>
                                                    <select name="personalCountry" id="personalCountry" class="form-control">
                                                        @foreach ($countries as $country)
                                                            <option value="{{ $country->id }}" {!! ($country->id==$user->country_id ? ' selected="selected" ' : '') !!}>{{ $country->citizenship }}</option>
                                                        @endforeach
                                                    </select></div>
                                                <div class="form-group">
                                                    <label class="control-label">Date of Birth</label>
                                                    <div class="control-label">
                                                        <div class="input-group input-medium date date-picker" data-date="{{ @$personal->dob_format }}" data-date-format="dd-mm-yyyy" data-date-viewmode="years">
                                                            <input type="text" class="form-control" name="personalDOB" id="personalDOB" value="{{ @$personal->dob_format }}" readonly>
                                                        <span class="input-group-btn">
                                                            <button class="btn default" type="button">
                                                                <i class="fa fa-calendar"></i>
                                                            </button>
                                                        </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Personal Email</label>
                                                    <input type="text" name="personalEmail" id="personalEmail" placeholder="Personal Email Address" class="form-control" value="{{@$personal->personal_email}}" /> </div>
                                                <div class="form-group">
                                                    <label class="control-label">Mobile Phone Number</label>
                                                    <input type="text" name="personalPhone" id="personalPhone" placeholder="+1 234 567 8910 (6284)" class="form-control" value="{{@$personal->mobile_number}}" /> </div>
                                                <div class="form-group">
                                                    <label class="control-label">About</label>
                                                    <textarea class="form-control" rows="3" placeholder="About Me!!!" id="personalAbout" name="personalAbout">{{@$personal->about_info}}</textarea>
                                                </div>
                                                <div class="margiv-top-10">
                                                    <a href="javascript:;" onclick="javascript: $('#form_acc_personal').submit();" class="btn green"> Update Details </a>
                                                    <a href="javascript:;" class="btn default"> Cancel </a>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- END PERSONAL INFO TAB -->
                                        <!-- Membership Plan TAB -->
                                        <div class="tab-pane row" id="tab_1_5">
                                            <div class="col-md-12">
                                                <div class="portlet light bordered">
                                                    <div class="portlet-body form">
                                                        <!-- BEGIN FORM-->
                                                        <form action="#" id="new_membership_plan" class="form-horizontal">
                                                            <div class="form-body">
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-3"> Active Membership </label>
                                                                    <div class="col-md-8">
                                                                        @if (isset($membership_plan->membership_id))
                                                                            <input class="form-control input-inline input-large inline-block" disabled readonly name="what_is_the_plan" value="{{$membership_plan->membership_name}}" />
                                                                            @if ($membership_plan->status=='suspended')
                                                                            <a href="#unfreeze_plan_box" class="btn bg-green-jungle bg-font-green-jungle input" data-toggle="modal" style="min-width:160px;">
                                                                                <i class="fa fa-play"></i> Un-Freeze Plan</a>
                                                                            @else
                                                                            <a href="#freeze_plan_box" class="btn bg-blue-sharp bg-font-blue-sharp input" data-toggle="modal" style="min-width:160px;">
                                                                                <i class="fa fa-pause"></i> Freeze Plan</a>
                                                                            @endif
                                                                            <a href="#cancel_plan_box" class="btn red-soft input" data-toggle="modal" style="min-width:160px;">
                                                                                <i class="fa fa-eject"></i> Cancel Plan</a>
                                                                        @else
                                                                            <input class="form-control input-inline input-large inline-block" disabled readonly name="what_is_the_plan" value="No active Membership Plan" />
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                @if (isset($membership_plan->membership_id))
                                                                <div class="form-group">
                                                                    <div class="col-md-5 text-right">
                                                                        <i class="form-control-static"> Current invoice period : {{ $plan_details['invoicePeriod'] }} </i>
                                                                    </div>
                                                                    <div class="col-md-1"> &nbsp; </div>
                                                                    <div class="col-md-5">
                                                                        <i class="form-control-static"> Next invoice period : {{ $plan_details['nextInvoicePeriod'] }} </i>
                                                                    </div>
                                                                </div>
                                                                @endif

                                                                @if (sizeof($memberships)>0 && !isset($membership_plan->membership_id))
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-3 inline"> Change Plan To </label>
                                                                    <div class="col-md-8">
                                                                        <select name="membership_plans_list" class="form-control input-inline input-large  inline-block list_all_plans">
                                                                            <option value="-1"> Select membership plan </option>
                                                                            @foreach ($memberships as $membership)
                                                                                <option value="{{$membership->id}}"> {{$membership->name}} </option>
                                                                            @endforeach
                                                                        </select>
                                                                        <a class="btn green apply_new_membership_plan input" style="min-width:190px; display:none;">
                                                                            <i class="fa fa-pencil"></i> Apply New Plan </a>
                                                                    </div>
                                                                </div>
                                                                @endif
                                                            </div>
                                                        </form>
                                                        <!-- END FORM-->
                                                    </div>
                                                </div>
                                            </div>

                                            @if (isset($membership_plan->membership_id))
                                            <div class="col-md-12">
                                                <div class="portlet light bordered">
                                                    <div class="portlet-title">
                                                        <div class="caption">
                                                            <i class="icon-equalizer font-blue-steel"></i>
                                                            <span class="caption-subject font-blue-steel bold uppercase"> Membership Plan Details </span>
                                                            <span class="caption-helper">for the signed membership plan</span>
                                                        </div>
                                                        <div class="tools">
                                                            <a class="collapse" href="" data-original-title="" title=""> </a>
                                                        </div>
                                                    </div>
                                                    <div class="portlet-body row">
                                                        <!-- BEGIN FORM-->
                                                        @if($restrictions)
                                                            <div class="col-md-4">
                                                                <div class="note note-info font-grey-mint" style="min-height:110px; margin:0 0 10px; padding:5px 20px 10px 10px;">
                                                                    <p> Price </p>
                                                                    <h4 class="block" style="margin-bottom:0px; font-size:32px;"> <b>{{ $plan_details['price'] }} NOK</b> </h4>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="note note-info font-grey-mint" style="min-height:110px; margin:0 0 10px; padding:5px 20px 10px 10px;">
                                                                    <p> Discount </p>
                                                                    <h4 class="block" style="margin-bottom:0px; font-size:32px;"> <b>{{ $plan_details['discount'] }}</b> </h4>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="note note-warning font-grey-mint" style="min-height:110px; margin:0 0 10px; padding:5px 20px 10px 10px;">
                                                                    <p> Invoice Period </p>
                                                                    <h4 class="block" style="margin-bottom:0px; font-size:22px;"> <b>{{ $plan_details['invoice_period'] }}</b> </h4>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="note note-info font-grey-mint" style="min-height:110px; margin:0 0 10px; padding:5px 20px 10px 10px;">
                                                                    <p> Signed On </p>
                                                                    <h4 class="block" style="margin-bottom:0px; font-size:24px;"> {{ $plan_details['day_start'] }} </h4>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="note note-info font-purple" style="min-height:110px; margin:0 0 10px; padding:5px 20px 10px 10px;">
                                                                    <p> Signed By </p>
                                                                    @if($plan_details['signed_by_link']!='')
                                                                        <h4 class="block" style="margin-bottom:0px; font-size:24px;"> <b><a class="font-purple" href="{{ $plan_details['signed_by_link'] }}" target="_blank"> {{ $plan_details['signed_by_name'] }} </a></b> </h4>
                                                                    @else
                                                                        <h4 class="block" style="margin-bottom:0px; font-size:24px;"> <b>{{ $plan_details['signed_by_name'] }}</b> </h4>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="note note-danger bg-red-flamingo bg-font-red-flamingo color-white" style="min-height:110px; margin:0 0 10px; padding:5px 20px 10px 10px;">
                                                                    <p> Invoice Status </p>
                                                                    <h4 class="block" style="margin-bottom:0px; font-size:24px;"> Not Paid </h4>
                                                                </div>
                                                            </div>
                                                        @endif
                                                        <!-- END FORM-->
                                                    </div>
                                                </div>
                                            </div>
                                            @endif

                                            @if (sizeof($restrictions))
                                            <div class="col-md-12">
                                                <div class="portlet light bordered">
                                                    <div class="portlet-title">
                                                        <div class="caption">
                                                            <i class="icon-equalizer font-blue-steel"></i>
                                                            <span class="caption-subject font-blue-steel bold uppercase"> Active Attributes & Restrictions </span>
                                                            <span class="caption-helper">for the signed membership plan</span>
                                                        </div>
                                                        <div class="tools">
                                                            <a class="collapse" href="" data-original-title="" title=""> </a>
                                                        </div>
                                                    </div>
                                                    <div class="portlet-body row">
                                                        <!-- BEGIN FORM-->
                                                        @if($restrictions)
                                                            @foreach ($restrictions as $restriction)
                                                                <div class="col-md-4">
                                                                    <div class="note {{ $restriction['color'] }}" style="min-height:140px; margin:0 0 15px; padding:5px 20px 10px 10px;">
                                                                        <h4 class="block"> {{ $restriction['title'] }} Rule </h4>
                                                                        <p> {!! $restriction['description'] !!} </p>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @else
                                                            <div class="note note-warning" style="margin-left:15px; margin-right:15px;">
                                                                <h4 class="block">You have no attributes added to this plan</h4>
                                                                <p> Please use the "Add Membership Attributes" to customize and configure the membership plan so you create the perfect plan for your business. </p>
                                                            </div>
                                                            @endif
                                                                    <!-- END FORM-->
                                                    </div>
                                                </div>
                                            </div>
                                            @endif

                                            @if (sizeof($plannedInvoices))
                                            <div class="col-md-12">
                                                <div class="portlet light bordered">
                                                    <div class="portlet-title">
                                                        <div class="caption">
                                                            <i class="icon-equalizer font-blue-steel"></i>
                                                            <span class="caption-subject font-blue-steel bold uppercase"> Membership planned invoices </span>
                                                            <span class="caption-helper">for the signed membership plan</span>
                                                        </div>
                                                        <div class="tools">
                                                            <a class="expand" href="" data-original-title="" title=""> </a>
                                                        </div>
                                                    </div>
                                                    <div class="portlet-body row" style="display:none;">
                                                        <!-- BEGIN FORM-->
                                                        <div class="table-scrollable">
                                                            <table class="table table-striped table-bordered table-advance table-hover">
                                                                <thead>
                                                                <tr>
                                                                    <th>
                                                                        <i class="fa fa-calendar-minus-o"></i> Invoice Name </th>
                                                                    <th class="hidden-xs">
                                                                        <i class="fa fa-calendar"></i> To be issued on </th>
                                                                    <th class="hidden-xs">
                                                                        <i class="fa fa-calendar"></i> Last active day </th>
                                                                    <th>
                                                                        <i class="fa fa-dollar"></i> Price </th>
                                                                    <th class="hidden-xs">
                                                                        <i class="fa fa-asterisk"></i> Payment Status </th>
                                                                    <!--<th> Options </th>-->
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                @foreach ($plannedInvoices as $singlePlanned)
                                                                    @if (sizeof($plan_requests)>0)
                                                                        @foreach($plan_requests as $one_request)
                                                                            @if ($one_request['action_type']=='freeze' && $one_request['after_date']->between(\Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$singlePlanned['issued_date'].' 00:00:00'), \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$singlePlanned['last_active_date'].' 00:00:00')))
                                                                                <tr>
                                                                                    <td class="highlight" colspan="5">
                                                                                        @if ($one_request['processed']=='1')
                                                                                            <div class="warning"></div> <a class="font-green-seagreen"> Processed - </a>
                                                                                        @else
                                                                                            <div class="danger"></div> <a class="font-red-thunderbird"> Pending - </a>
                                                                                        @endif
                                                                                        Freeze membership between <b class="font-purple-studio">{{ $one_request['start_date']->format('d M Y') }}</b> and <b class="font-purple-studio">{{ $one_request['end_date']->format('d M Y') }}</b>. Action added by <a style="margin-left:0px;" href="{{ $one_request['added_by_link'] }}" target="_blank">{{ $one_request['added_by_name'] }}</a> on {{ $one_request['created_at'] }} (last update on {{ $one_request['updated_at'] }})
                                                                                    </td>
                                                                                </tr>
                                                                            @elseif ($one_request['action_type']=='cancel' && $one_request['start_date']->eq(\Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$singlePlanned['last_active_date'].' 00:00:00')->addDay()))
                                                                                <tr>
                                                                                    <td class="highlight" colspan="5">
                                                                                        @if ($one_request['processed']=='1')
                                                                                            <div class="warning"></div> <a class="font-green-seagreen"> Processed - </a>
                                                                                        @else
                                                                                            <div class="danger"></div> <a class="font-red-thunderbird"> Pending - </a>
                                                                                        @endif
                                                                                        Membership cancellation starting with <b class="font-purple-studio">{{ $one_request['start_date']->format('d M Y') }}</b>. Action added by <a style="margin-left:0px;" href="{{ $one_request['added_by_link'] }}" target="_blank">{{ $one_request['added_by_name'] }}</a> on {{ $one_request['created_at'] }} (last update on {{ $one_request['updated_at'] }})
                                                                                    </td>
                                                                                </tr>
                                                                            @endif
                                                                        @endforeach
                                                                    @endif

                                                                    <tr>
                                                                        <td class="highlight">
                                                                        @if ($singlePlanned['invoiceLink']!='')
                                                                            <div class="success"></div>
                                                                            <a href="{{ $singlePlanned['invoiceLink'] }}" target="_blank"> {{ $singlePlanned['item_name'] }} </a>
                                                                        @else
                                                                            <div class="success"></div>
                                                                            <span> &nbsp; &nbsp; {{ $singlePlanned['item_name'] }} </span>
                                                                        @endif
                                                                        </td>
                                                                        <td> {{ $singlePlanned['issued_date'] }} - {{ $singlePlanned['status'] }} </td>
                                                                        <td> {{ $singlePlanned['last_active_date'] }} </td>
                                                                        <td class="hidden-xs"> {{ $singlePlanned['price'] }} NOK </td>
                                                                        <td>
                                                                            @if ($singlePlanned['invoiceStatus']!='')
                                                                                <span class="label label-sm label-success"> {{$singlePlanned['invoiceStatus']}} </span>
                                                                            @endif
                                                                        </td>
                                                                        <!--<td> <a href="javascript:;" class="btn btn-sm green"> Group Invoices <i class="fa fa-plus"></i></a>
                                                                            <a href="javascript:;" class="btn btn-sm purple"> Defer <i class="fa fa-times"></i></a></td>-->
                                                                    </tr>
                                                                @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <!-- END FORM-->
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                        <!-- END Membership Plan TAB -->
                                        <!-- CHANGE AVATAR TAB -->
                                        <div class="tab-pane" id="tab_1_2">
                                            <form action="{{ route('admin/front_users/view_user/avatar_image', ['id'=>$user->id]) }}" id="user_picture_upload1" class="form-horizontal" method="post" enctype="multipart/form-data">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <div class="form-body">
                                                    <div class="alert alert-danger display-hide">
                                                        <button class="close" data-close="alert"></button> You have some form errors. Please check below. </div>
                                                    <div class="alert alert-success display-hide">
                                                        <button class="close" data-close="alert"></button> Your form validation is successful! </div>
                                                    <div class="form-group last">
                                                        <label class="control-label col-md-2">Member Avatar</label>
                                                        <div class="col-md-9">
                                                            <div class="fileinput fileinput-{{ (strlen($avatar)>10) ? 'exists':'new' }} " data-provides="fileinput">
                                                                <div class="fileinput-new thumbnail" style="width: 200px; height: 244px;">
                                                                    <img src="http://www.placehold.it/200x246/EFEFEF/AAAAAA&amp;text=no+image" alt="" /> </div>
                                                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 240px;">
                                                                    @if ( strlen($avatar)>10 )
                                                                        <img src="{{ $avatar }}" />
                                                                    @endif
                                                                </div>
                                                                <div>
                                                                    <span class="btn default btn-file">
                                                                        <span class="fileinput-new"> Select image </span>
                                                                        <span class="fileinput-exists"> Change </span>
                                                                        <input type="file" name="user_avatar" class="user_avatar_select_btn1" /> </span>
                                                                    <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                                                </div>
                                                            </div>
                                                            <div class="clearfix margin-top-10">
                                                                <div class="note note-warning margin-bottom-5">
                                                                    <p> Image preview only works in IE10+, FF3.6+, Safari6.0+, Chrome6.0+ and Opera11.1+. In older browsers the filename is shown instead. </p>
                                                                </div>
                                                            </div>
                                                            <div class="clearfix margin-top-10">
                                                                <a class="btn green" onclick="javascript: $('#user_picture_upload1').submit();" href="javascript:;"> Update avatar </a>
                                                                <a class="btn default" href="javascript:;"> Cancel </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>

                                            <div class="portlet light bordered">
                                                <div class="portlet-title">
                                                    <div class="caption">
                                                        <i class="icon-edit font-dark"></i>
                                                        <span class="caption-subject font-dark bold uppercase">Avatar Archive</span>
                                                    </div>
                                                </div>
                                                <div class="portlet-body">
                                                    <div class="row">
                                                        @foreach ($old_avatars as $old_avatar)
                                                            <div class="col-md-3" style="text-align: center;">
                                                                <img src="data:{{ $old_avatar['type'] }};base64,{{ base64_encode($old_avatar['data']) }}" style="max-width:100%; max-height:200px;" />
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- END CHANGE AVATAR TAB -->
                                        <!-- CHANGE PASSWORD TAB -->
                                        <div class="tab-pane" id="tab_1_3">
                                            <form action="#" id="form_password_update" role="form">
                                                <div class="alert alert-danger display-hide">
                                                    <button class="close" data-close="alert"></button> You have some form errors. Please check below. </div>
                                                <div class="alert alert-success display-hide">
                                                    <button class="close" data-close="alert"></button> Your form validation is successful! </div>
                                                <div class="form-group">
                                                    <label class="control-label">New Password</label>
                                                    <div class="input-icon">
                                                        <i class="fa"></i>
                                                        <input type="password" name="new_password1" id="new_password1" class="form-control" /> </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Re-type New Password</label>
                                                    <div class="input-icon">
                                                        <i class="fa"></i>
                                                        <input type="password" name="new_password2" id="new_password2" class="form-control" /> </div>
                                                </div>
                                                <div class="margin-top-10">
                                                    <a href="javascript:;" class="btn green" onClick="javascript: $('#form_password_update').submit();"> Change Password </a>
                                                    <a href="javascript:;" class="btn default"> Cancel </a>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- END CHANGE PASSWORD TAB -->
                                        <!-- DOCUMENTS TAB -->
                                        <div class="tab-pane" id="tab_1_4">
                                            <div class="portlet box blue">
                                                <div class="portlet-title">
                                                    <div class="caption">
                                                        <i class="fa fa-gift"></i>Upload Documents </div>
                                                    <div class="tools">
                                                        <a class="expand" href="javascript:;" data-original-title="" title=""> </a>
                                                    </div>
                                                </div>
                                                <div class="portlet-body" style="display: none;">
                                                    <div class="m-heading-1 border-green m-bordered">
                                                        <h3>Documents Dropzone</h3>
                                                        <p> Select the documents you want to add, documents related to this specific user, and upload them once you added all of them to the dropbox area. </p>
                                                    </div>
                                                    <form action="{{ route('admin/front_users/view_user/add_document', ['id'=>$user->id]) }}" class="dropzone dropzone-file-area" id="my-dropzone" style="width: 500px; margin-top: 50px;">
                                                        <h3 class="sbold">Drop files here or click to upload</h3>
                                                        <p> This is just a demo dropzone. Selected files are not actually uploaded. </p>
                                                    </form>
                                                </div>
                                            </div>

                                            <div class="portlet light portlet-fit bordered">
                                                <div class="portlet-title">
                                                    <div class="caption">
                                                        <i class=" icon-layers font-green"></i>
                                                        <span class="caption-subject font-green bold uppercase">Uploaded documents [page needs to be reloaded for latest files to be shown]</span>
                                                        <div class="caption-desc font-grey-cascade"> hire documents, national identification card, etc. </div>
                                                    </div>
                                                </div>
                                                <div class="portlet-body">
                                                    <div class="mt-element-list">
                                                        <div class="mt-list-container list-simple ext-1">
                                                            <ul>
                                                                @foreach ($documents as $document)
                                                                    <li class="mt-list-item">
                                                                        <div class="list-icon-container">
                                                                            <i class="icon-check"></i>
                                                                        </div>
                                                                        <div class="list-datetime"> {{ $document->created_at->format('m/d/y') }} </div>
                                                                        <div class="list-item-content">
                                                                            <h3 class="uppercase">
                                                                                <a href="{{ route('admin/front_user/get_document', [ 'id' => $user->id , 'document_name'=> $document->file_name ]) }}" target="_blank">{{ $document->file_name }}</a>
                                                                            </h3>
                                                                        </div>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- END DOCUMENTS TAB -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="changeIt" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                <h4 class="modal-title"> Membership Plan Details </h4>
                            </div>
                            <div class="modal-body form-horizontal" id="book_main_details_container">
                                <div class="form-body">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label"> Membership Name </label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control input-sm" name="membership_name" value="" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label"> Price </label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control input-small" name="membership_price" value="" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label"> Invoice Period </label>
                                        <div class="col-md-8">
                                            <input class="form-control input-small" name="membership_invoice_period" value="" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label"> One time Fee </label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control input-sm" name="membership_one_fee_name" value="" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label"> One time fee value </label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control input-small" name="membership_one_fee_value" value="" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label"> Description </label>
                                        <div class="col-md-8">
                                            <textarea class="form-control input-sm" name="membership_description" readonly></textarea>
                                            <!--<select class="form-control input-inline input-large" name="book_finance"></select>-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn green btn_no_show" data-toggle="modal" href="#changeIt"> Return </button>
                                <button type="button" class="btn green btn_modify_booking" onclick="javascript:change_membership_plan();"> Assign New Plan </button>
                                <input type="hidden" name="selected_plan_number" value="" />
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>

                <div class="modal fade bs-modal-sm" id="cancel_plan_box" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                <h4 class="modal-title">Do you want to cancel the current membership plan?</h4>
                            </div>
                            <div class="modal-body margin-top-10" style="margin-bottom:0px; padding-bottom:5px;"> Please select the first day with no plan from the drop-down list below</div>
                            <div class="modal-body margin-bottom-20" style="padding-top:5px;">
                                <select name="date_cancellation_time" id="date_cancellation_time" class="form-control input-inline input-large  inline-block list_all_plans">
                                    @foreach ($invoiceCancellation as $key=>$val)
                                        <option value="{{$key}}"> {{$val}} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn dark btn-outline" data-dismiss="modal">No, Go Back</button>
                                <button type="button" class="btn green" data-toggle="modal" href="#cancel_plan_confirm_box">Cancel Membership</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>

                <div class="modal fade bs-modal-sm" id="cancel_plan_confirm_box" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                <h4 class="modal-title">Do you want to cancel the current membership plan?</h4>
                            </div>
                            <div class="modal-body margin-top-10 margin-bottom-10"> By clicking "Cancel Membership" the member will be switched to the default membership plan (the "No Membership Plan").
                                After the cancellation you can apply another membership plan to this user from the same page.</div>
                            <div class="modal-footer">
                                <button type="button" class="btn dark btn-outline" data-dismiss="modal">No, Go Back</button>
                                <button type="button" class="btn green" onclick="javascript:cancel_membership();">Yes, Cancel</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>

                <!-- BEGIN Booking No Show modal window -->
                <div class="modal fade" id="freeze_plan_box" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" style="margin-top:45px;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                <h4 class="modal-title"> Freeze membership plan </h4>
                            </div>
                            <div class="modal-body form-horizontal" id="recurring_details_container" style="min-height:200px;">
                                <div class="note note-warning">
                                    <h4 class="block">Freezing the membership plan suspends the membership</h4>
                                    <p> Please add the date interval for which the membership plan is suspended. Once the end of the interval is reached, the membership will carry on and a new invoice will be generated.<br />
                                        <br />The membership period will be extended with the period the membership is freezed.</p>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Freeze Interval</label>
                                    <div class="col-md-8">
                                        <div class="input-group input-large date-picker input-daterange" data-date-start-date="{{\Carbon\Carbon::today()->addDays(30)->format('d-m-Y')}}" data-date-end-date="{{\Carbon\Carbon::today()->addDays(90)->format('d-m-Y')}}" data-date-format="dd-mm-yyyy">
                                            <input type="text" class="form-control input-sm" name="freeze_from_date" id="freeze_from_date" value="{{\Carbon\Carbon::today()->addDays(30)->format('d-m-Y')}}">
                                            <span class="input-group-addon"> to </span>
                                            <input type="text" class="form-control input-sm" name="freeze_to_date" id="freeze_to_date"> </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn green btn_no_show" data-toggle="modal" href="#freeze_plan_confirm_box">Freeze plan</button>
                                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Return</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <!-- END No Show modal window -->

                <!-- BEGIN Recurrent Cancel Confirm modal window show -->
                <div class="modal fade" id="freeze_plan_confirm_box" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content" style="margin-left:20px; margin-right:20px; margin-top:60px;">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                <h4 class="modal-title">Membership Freeze Confirmation?</h4>
                            </div>
                            <div class="modal-body">
                                <div class="note note-info" style="margin-bottom:0px;">
                                    <h4 class="block">Freeze membership plan</h4>
                                    <p> By clicking "Yes, Freeze" the membership plan will be suspended for the selected date interval and re-activated after the time passes. Do you want to proceed with this action? </p>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn dark btn-outline" data-dismiss="modal">No, Go Back</button>
                                <button type="button" class="btn green" onclick="javascript:freeze_membership();">Yes, Freeze</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <!-- END Recurrent Cancel Confirm modal window show -->
            </div>
            <!-- END PROFILE CONTENT -->
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
    <script src="{{ asset('assets/pages/scripts/profile.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/dropzone/dropzone.min.js') }}" type="text/javascript"></script>
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
            /* Personal Info */
            var handleValidation1 = function() {
                var form1 = $('#form_acc_personal');
                var error1 = $('.alert-danger', form1);
                var success1 = $('.alert-success', form1);

                form1.validate({
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block help-block-error', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    ignore: "",  // validate all fields including form hidden input
                    rules: {
                        personalFirstName: {
                            minlength: 3,
                            required: true
                        },
                        personalLastName: {
                            minlength: 3,
                            required: true
                        },
                        personalDOB: {
                            required: true,
                            datePickerDate:true
                        },
                        personalEmail: {
                            required: true,
                            email: true,
                            validate_email: true
                        },
                        personalPhone: {
                            required: true,
                            digits: true,
                            minlength:4,
                            maxlength:12
                        },
                        gender: {
                            required: true,
                        }
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
                        store_account_personal(); // submit the form
                    }
                });
            }

            var handleValidation2 = function() {
                // for more info visit the official plugin documentation:
                // http://docs.jquery.com/Plugins/Validation
                var form2 = $('#form_acc_info');
                var error2 = $('.alert-danger', form2);
                var success2 = $('.alert-success', form2);

                form2.validate({
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block help-block-error', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    ignore: "",  // validate all fields including form hidden input
                    rules: {
                        accountUsername: {
                            minlength: 3,
                            required: true
                        },
                        accountEmail: {
                            required: true,
                            email: true,
                            validate_email: true
                        },
                    },

                    invalidHandler: function (event, validator) { //display error alert on form submit
                        success2.hide();
                        error2.show();
                        App.scrollTo(error2, -200);
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
                        success2.show();
                        error2.hide();
                        store_account_info(); // submit the form
                    }
                });
            }

            var handleValidation3 = function() {
                var form3 = $('#form_personal_address');
                var error3 = $('.alert-danger', form3);
                var success3 = $('.alert-success', form3);

                form3.validate({
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block help-block-error', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    ignore: "",  // validate all fields including form hidden input
                    rules: {
                        personal_addr1: {
                            minlength: 5,
                            required: true
                        },
                        personal_addr_city: {
                            minlength: 3,
                            required: true
                        },
                        personal_addr_region: {
                            minlength:2,
                            required: true
                        },
                        personal_addr_pcode: {
                            minlength: 2,
                            required: true
                        },
                    },

                    invalidHandler: function (event, validator) { //display error alert on form submit
                        success3.hide();
                        error3.show();
                        App.scrollTo(error3, -200);
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
                        success3.show();
                        error3.hide();
                        update_personal_address(); // submit the form
                    }
                });
            }

            var handleValidation4 = function() {
                var form4 = $('#form_password_update');
                var error4 = $('.alert-danger', form4);
                var success4 = $('.alert-success', form4);

                form4.validate({
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block help-block-error', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    ignore: "",  // validate all fields including form hidden input
                    rules: {
                        new_password1: {
                            minlength: 8,
                            required: true,
                        },
                        new_password2: {
                            minlength: 8,
                            required: true,
                            equalTo: '#new_password1',
                        },
                    },

                    invalidHandler: function (event, validator) { //display error alert on form submit
                        success4.hide();
                        error4.show();
                        App.scrollTo(error4, -200);
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
                        success4.show();
                        error4.hide();
                        update_passwd(); // submit the form
                    }
                });
            }

            var handleValidation5 = function() {
                var form5 = $('#user_picture_upload1');
                var error5 = $('.alert-danger', form5);
                var success5 = $('.alert-success', form5);

                form5.validate({
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block help-block-error', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    ignore: "",  // validate all fields including form hidden input
                    rules: {
                        user_avatar: {
                            required: true,
                            accept: "image/*",
                            filesize: 1048576,
                        },
                    },
                    messages: {
                        user_avatar: {
                            required: "We need your avatar before submitting the form",
                            accept: "The uploaded file must be an image",
                            filesize: "File must be JPG, GIF or PNG, less than 1MB",
                        }
                    },

                    invalidHandler: function (event, validator) { //display error alert on form submit
                        success5.hide();
                        error5.show();
                        App.scrollTo(error5, -200);
                    },

                    highlight: function (element) { // hightlight error inputs
                        $(element)
                                .closest('.form-group').addClass('has-error'); // set error class to the control group
                    },

                    unhighlight: function (element) { // revert the change done by hightlight
                        $(element)
                                .closest('.form-group').removeClass('has-error'); // set error class to the control group
                    },

                    success: function (label) {
                        label
                                .closest('.form-group').removeClass('has-error'); // set success class to the control group
                    },

                    submitHandler: function (form) {
                        success5.show();
                        error5.hide();
                        form.submit();
                    }
                });
            }

            return {
                //main function to initiate the module
                init: function () {
                    handleValidation1();
                    handleValidation2();
                    handleValidation3();
                    handleValidation4();
                    handleValidation5();
                }
            };
        }();

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

        var FormDropzone = function () {
            return {
                //main function to initiate the module
                init: function () {

                    Dropzone.options.myDropzone = {
                        paramName: "user_doc", // The name that will be used to transfer the file
                        maxFilesize: 20, // MB
                        acceptedFiles: "image/jpeg,image/png,application/pdf,.psd,.doc,.docx,.xls,.xlsx,.JPG",
                        dictDefaultMessage: '',
                        dictResponseError: 'Error uploading file!',
                        init: function() {
                            this.on("sending", function(file, xhr, data) {
                                data.append("_token", '{{ csrf_token() }}');
                            });
                            this.on("addedfile", function(file) {
                                // Create the remove button
                                var removeButton = Dropzone.createElement("<a href='javascript:;'' class='btn red btn-sm btn-block'>Remove</a>");

                                // Capture the Dropzone instance as closure.
                                var _this = this;

                                // Listen to the click event
                                removeButton.addEventListener("click", function(e) {
                                    // Make sure the button click doesn't submit the form:
                                    e.preventDefault();
                                    e.stopPropagation();

                                    // Remove the file preview.
                                    _this.removeFile(file);
                                    // If you want to the delete the file on the server as well,
                                    // you can do the AJAX request here.
                                });

                                // Add the button to the file preview element.
                                file.previewElement.appendChild(removeButton);
                            });
                        }
                    }
                }
            };
        }();

        $(document).ready(function(){
            FormValidation.init();
            ComponentsDateTimePickers.init();
            FormDropzone.init();
        });

        /* Done */
        function store_account_personal(){
            $.ajax({
                url: '{{route('admin/front_users/view_user/personal_info', ['id'=>$user->id])}}',
                type: "post",
                data: {
                    'first_name':       $('input[name=personalFirstName]').val(),
                    'middle_name':      $('input[name=personalMiddleName]').val(),
                    'last_name':        $('input[name=personalLastName]').val(),
                    'gender':           $('select[name=gender]').val(),
                    'date_of_birth':    $('input[name=personalDOB]').val(),
                    'personal_email':   $('input[name=personalEmail]').val(),
                    'mobile_number':    $('input[name=personalPhone]').val(),
                    'about_info':       $('textarea[name=personalAbout]').val(),
                    'country_id':       $('select[name=personalCountry]').val(),
                },
                success: function(data){
                    if (data.success) {
                        show_notification(data.title, data.message, 'lime', 3500, 0);
                    }
                    else{
                        show_notification(data.title, data.errors, 'tangerine', 3500, 0);
                    }
                }
            });
        }

        /* Done */
        function update_passwd(){
            $.ajax({
                url: '{{route('admin/front_users/view_user/password_update', ['id'=>$user->id])}}',
                type: "post",
                data: {
                    'password1':    $('input[name=new_password1]').val(),
                    'password2':    $('input[name=new_password2]').val(),
                },
                success: function(data){
                    if (data.success) {
                        show_notification(data.title, data.message, 'lime', 3500, 0);
                        $('#form_password_update').find('.alert').css('display','none');
                        $('#form_password_update').find('i.fa').removeClass('fa-check');

                        $('#new_password1').val('');
                        $('#new_password2').val('');
                    }
                    else{
                        show_notification(data.title, data.errors, 'tangerine', 3500, 0);
                    }
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

        $('.list_all_plans').on('change', function(){
            var button = $('.apply_new_membership_plan');
            if ($(this).val()==-1 || $(this).val()=='{{$membership_plan->id}}'){
                button.hide();
            }
            else{
                button.show();
            }
        });

        $('.apply_new_membership_plan').on('click', function(){
            $.ajax({
                url: '{{route('admin/membership_plans/ajax_get_details')}}',
                type: "post",
                data: {
                    'selected_plan': $('select[name=membership_plans_list]').val(),
                },
                success: function(data){
                    if (data.success) {
                        $('input[name="membership_name"]').val(data.name);
                        $('input[name="membership_price"]').val(data.price);
                        $('input[name="membership_one_fee_name"]').val(data.one_time_fee_name);
                        $('input[name="membership_one_fee_value"]').val(data.one_time_fee_value);
                        $('input[name="membership_invoice_period"]').val(data.invoice_time);
                        $('textarea[name="membership_description"]').html(data.description);
                        $('input[name="selected_plan_number"]').val(data.plan_order_id);

                        $('#changeIt').modal('show');
                    }
                    else{
                        show_notification(data.title, data.errors, 'tangerine', 3500, 0);
                    }
                }
            });
        });

        function change_membership_plan(){
            var userID = '{{$user->id}}';

            $.ajax({
                url: '{{route('admin/membership_plans/assign_to_member')}}',
                type: "post",
                data: {
                    'selected_plan':    $('select[name=membership_plans_list]').val(),
                    'member_id':        userID
                },
                success: function(data){
                    if (data.success) {
                        $('input[name="membership_name"]').val('');
                        $('input[name="membership_price"]').val('');
                        $('input[name="membership_one_fee_name"]').val('');
                        $('input[name="membership_one_fee_value"]').val('');
                        $('input[name="membership_invoice_period"]').val('');
                        $('textarea[name="membership_description"]').html('');
                        $('input[name="selected_plan_number"]').html(-1);

                        $('#changeIt').modal('hide');
                        show_notification(data.title, data.message, 'lime', 3500, 0);

                        setTimeout(function(){
                            location.reload();
                        },2000);
                    }
                    else{
                        show_notification(data.title, data.errors, 'tangerine', 3500, 0);
                    }
                }
            });
        }

        function freeze_membership(){
            var userID = '{{$user->id}}';

            $.ajax({
                url: '{{route('admin/membership_plans/freeze_member_plan')}}',
                type: "post",
                data: {
                    'from_date': $('input[name="freeze_from_date"]').val(),
                    'to_date': $('input[name="freeze_to_date"]').val(),
                    'member_id':userID
                },
                success: function(data){
                    if (data.success) {
                        $('#freeze_plan_confirm_box').modal('hide');
                        $('#freeze_plan_box').modal('hide');
                        show_notification(data.title, data.message, 'lime', 3500, 0);

                        setTimeout(function(){
                            location.reload();
                        },2000);
                    }
                    else{
                        $('#freeze_plan_confirm_box').modal('hide');
                        show_notification(data.title, data.errors, 'tangerine', 3500, 0);
                    }
                }
            });
        }

        function cancel_membership(){
            var userID = '{{$user->id}}';

            $.ajax({
                url: '{{route('admin/membership_plans/cancel_member_plan')}}',
                type: "post",
                data: {
                    'member_id':userID,
                    'cancellation_date':$('select[name="date_cancellation_time"]').val()
                },
                success: function(data){
                    if (data.success) {
                        $('#cancel_confirm_box').modal('hide');
                        show_notification(data.title, data.message, 'lime', 3500, 0);

                        setTimeout(function(){
                            location.reload();
                        },2000);
                    }
                    else{
                        show_notification(data.title, data.errors, 'tangerine', 3500, 0);
                    }
                }
            });
        }
    </script>
@endsection