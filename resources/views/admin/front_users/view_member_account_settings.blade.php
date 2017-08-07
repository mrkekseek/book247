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
    <link href="{{ asset('assets/global/plugins/ladda/ladda-themeless.min.css') }}" rel="stylesheet" type="text/css" />
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
                       <div class="profile-userpic" style="background-size: cover; background-position: center center; margin: 0 auto; width: 150px; height: 150px; border-radius: 50%;background-image: url('{{ $avatar }}');"></div>
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
                                <li>
                                    <a href="{{route("admin/front_users/view_user", $user->id)}}">
                                        <i class="icon-home"></i> Overview </a>
                                </li>
                                <li>
                                    <a href="{{route("admin/front_users/view_personal_settings", $user->id)}}">
                                        <i class="icon-settings"></i> Personal Settings </a>
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
                                        <li>
                                            <a href="#tab_1_3" data-toggle="tab">Store Credit</a>
                                        </li>
                                        <li class="active">
                                            <a href="#tab_1_5" data-toggle="tab">Membership Plan</a>
                                        </li>
                                        <li>
                                            <a href="#tab_1_2" data-toggle="tab">Access Card</a>
                                        </li>
                                        <li>
                                            <a href="#tab_1_4" data-toggle="tab">Documents</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="portlet-body">
                                    <div class="tab-content">
                                        <!-- Access Card TAB -->
                                        <div class="tab-pane row" id="tab_1_2">
                                            <div class="col-md-12">
                                                <div class="portlet light bordered">
                                                    <div class="portlet-body form">
                                                        <!-- BEGIN FORM-->
                                                        <form action="#" id="update_access_card" class="form-horizontal">
                                                            <div class="form-body">
                                                                <div class="form-group" style="margin-bottom:0px;">
                                                                    <label class="control-label col-md-4"> Access Card Number </label>
                                                                    <div class="col-md-8">
                                                                        <input class="form-control input-inline inline-block input-xlarge" name="access_card_number" placeholder="insert/paste number here" value="{{ $accessCardNo }}" />
                                                                        <button class="btn uppercase green-meadow inline-block" >Update</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                        <!-- END FORM-->
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="portlet light bordered">
                                                    <div class="portlet-title">
                                                        <div class="caption">
                                                            <i class="icon-equalizer font-blue-steel"></i>
                                                            <span class="caption-subject font-blue-steel bold uppercase"> Access Card Activity </span>
                                                            <span class="caption-helper">for the selected member</span>
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

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <!-- END FORM-->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- END Access Card TAB -->
                                        <!-- Access Card TAB -->
                                        <div class="tab-pane row" id="tab_1_3">
                                            <div class="col-md-12">
                                                <div class="portlet light bordered">
                                                    <div class="portlet-body form">
                                                        <!-- BEGIN FORM-->
                                                        <form action="#" id="add_store_credit" class="form-horizontal">
                                                            <div class="form-body">
                                                                <label class="control-label col-md-4 margin-bottom-10"> Available Store Credit </label>
                                                                <div class="col-md-8 margin-bottom-10">
                                                                    <a href="#" disabled role="button" class="btn yellow"> {!! sizeof($storeCreditNotes)>0?$storeCreditNotes[0]->total_amount:0 !!} credits</a>
                                                                </div>
                                                                <div class="form-group" style="margin-bottom:0px;">
                                                                    <label class="control-label col-md-4"> Add Store Credit </label>
                                                                    <div class="col-md-8">
                                                                        <select name="store_credit_value" class="form-control input-inline input-large  inline-block list_all_plans">
                                                                            <option value="-1"> Select store credit package </option>
                                                                            @foreach($store_credit_products as $package)
                                                                                <option value="{{ $package->id }}"> {{ $package->name }} </option>
                                                                            @endforeach
                                                                        </select>
                                                                        <button class="btn uppercase green-meadow inline-block">Add to account</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                        <!-- END FORM-->
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="portlet light bordered">
                                                    <div class="portlet-title">
                                                        <div class="caption">
                                                            <i class="icon-equalizer font-blue-steel"></i>
                                                            <span class="caption-subject font-blue-steel bold uppercase"> Store Credit Activity </span>
                                                            <span class="caption-helper">for the selected member</span>
                                                        </div>
                                                        <div class="tools">
                                                            <a class="collapse" href="" data-original-title="" title=""> </a>
                                                        </div>
                                                    </div>
                                                    <div class="portlet-body row">
                                                        <!-- BEGIN FORM-->
                                                        <div class="table-scrollable">
                                                            <table class="table table-striped table-bordered table-advance table-hover">
                                                                <thead>
                                                                <tr>
                                                                    <th>
                                                                        <i class="fa fa-calendar-minus-o"></i> Issued Date & By whom </th>
                                                                    <th class="hidden-xs">
                                                                        <i class="fa fa-calendar"></i> Amount </th>
                                                                    <th>
                                                                        <i class="fa fa-dollar"></i> Overall credit </th>
                                                                    <th class="hidden-xs">
                                                                        <i class="fa fa-asterisk"></i> Status </th>
                                                                    <!--<th> Options </th>-->
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                @if (sizeof($storeCreditNotes)>0)
                                                                    @foreach($storeCreditNotes as $single)
                                                                    <tr>
                                                                        <td>
                                                                            <span class="item {!! $single->value<0?'font-red-flamingo':'font-green-jungle' !!}">
                                                                                <span aria-hidden="true" class="icon-{!! $single->value<0?'logout':'login' !!}"></span>
                                                                                {{ $single->created_at->format('d-m-Y') }} by {{ $single->full_name }}
                                                                            </span>
                                                                        </td>
                                                                        <td class="{!! $single->value<0?'font-red-flamingo':'font-green-jungle' !!}">{{ $single->value }}</td>
                                                                        <td class="font-blue-steel"> <strong>{{ $single->total_amount }}</strong> </td>
                                                                        <td>
                                                                            @if ($single->value>0)
                                                                            <span class="label label-sm label-{{ $single->status=='active'?'success':'warning' }}"> {{ $single->status }} </span>
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                    @endforeach
                                                                @endif
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <!-- END FORM-->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- END Access Card TAB -->
                                        <!-- Membership Plan TAB -->
                                        <div class="tab-pane active row" id="tab_1_5">
                                            <div class="col-md-12">
                                                <div class="portlet light bordered">
                                                    <div class="portlet-body form">
                                                        <!-- BEGIN FORM-->
                                                        <form action="#" id="new_membership_plan" class="form-horizontal">
                                                            <div class="form-body" style="padding-bottom:0px;">
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-3"> Active Membership </label>
                                                                    <div class="col-md-9">
                                                                        @if (isset($membership_plan->membership_id))
                                                                            <input class="form-control input-inline input-large inline-block margin-bottom-10" disabled readonly name="what_is_the_plan" value="{{$membership_plan->membership_name}}" />

                                                                            @if($canUpdate==true)
                                                                            <a href="#upgrade_downgrade_plan_box" class="btn bg-green-jungle bg-font-green-jungle input margin-bottom-10" onclick="javascript: ui_block($('#tab_1_5')) ;" data-toggle="modal" style="min-width:160px;">
                                                                                <i class="fa fa-play"></i> Upgrade/Downgrade Plan</a>
                                                                            @endif
                                                                            <br />
                                                                            @if ($membership_plan->status=='suspended')
                                                                            <a href="#unfreeze_plan_box" class="btn bg-green-jungle bg-font-green-jungle input margin-bottom-10" onclick="javascript: ui_block($('#tab_1_5')) ;" data-toggle="modal" style="min-width:158px;">
                                                                                <i class="fa fa-play"></i> Un-Freeze Plan</a>
                                                                            @elseif($canFreeze==true)
                                                                            <a href="#freeze_plan_box" class="btn bg-blue-sharp bg-font-blue-sharp input margin-bottom-10" data-toggle="modal" onclick="javascript: ui_block($('#tab_1_5')) ;" style="min-width:158px;">
                                                                                <i class="fa fa-pause"></i> Freeze Plan</a>
                                                                            @endif

                                                                            @if($canCancel==true)
                                                                            <a href="#cancel_plan_box" class="btn red-soft input margin-bottom-10" data-toggle="modal" onclick="javascript: ui_block($('#tab_1_5')) ;" style="min-width:158px;">
                                                                                <i class="fa fa-eject"></i> Cancel Plan</a>
                                                                            @endif
                                                                        @else
                                                                            <input class="form-control input-inline input-large inline-block margin-bottom-10" disabled readonly name="what_is_the_plan" value="No active Membership Plan" />
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                @if (isset($membership_plan->membership_id))
                                                                <div class="form-group" style="margin-bottom:0px;">
                                                                    <div class="col-md-5 border-green-jungle" style="border: 1px solid #ffffff;">
                                                                        <i class="form-control-static"> Current invoice period : {{ $plan_details['invoicePeriod'] }} </i>
                                                                    </div>
                                                                    <div class="col-md-2"></div>
                                                                    <div class="col-md-5 text-right border-purple-studio" style="border: 1px solid #ffffff;">
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
                                                                        </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-3 inline"> Signing location </label>
                                                                    <div class="col-md-8">
                                                                        <select name="locations_list" class="form-control input-inline input-large  inline-block list_all_plans">
                                                                            <option value="-1"> Select signing location  </option>
                                                                            @foreach ($locations as $location)
                                                                                <option value="{{$location->id}}"> {{$location->name}} </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-3 inline"> Membership Start Date </label>
                                                                    <div class="col-md-8">
                                                                        <div class="input-group input date date-picker" data-date-start-date="-30d" data-date="{{ \Carbon\Carbon::today()->format('d-m-Y') }}" data-date-format="dd-mm-yyyy" data-date-viewmode="years" style="display:inline-flex; margin-top:2px; margin-right:40px;">
                                                                            <input type="text" class="form-control" name="start_date" readonly style="background-color:#ffffff;" value="{{ \Carbon\Carbon::today()->format('d-m-Y') }}">
                                                                            <span class="input-group-btn">
                                                                                <button class="btn default" type="button">
                                                                                    <i class="fa fa-calendar"></i>
                                                                                </button>
                                                                            </span>
                                                                        </div>
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
                                                    <div class="portlet-body">
                                                        <!-- BEGIN membership details-->
                                                        @if($restrictions)
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="note note-info font-grey-mint membership_options" style="margin:0 0 10px; padding:5px 20px 10px 10px;">
                                                                    <p> <b>Price</b> / Discount </p>
                                                                    <h4 class="block" style="margin-bottom:0px; font-size:28px;">
                                                                        <b>{{ $plan_details['price'].' '.\App\Http\Controllers\AppSettings::get_setting_value_by_name('finance_currency') }} </b> /
                                                                        {{ $plan_details['discount'].' '.\App\Http\Controllers\AppSettings::get_setting_value_by_name('finance_currency') }}
                                                                    </h4>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="note note-info font-grey-mint membership_options" style="margin:0 0 10px; padding:5px 20px 10px 10px;">
                                                                    <p> Contract Number </p>
                                                                    <h4 class="block" style="margin-bottom:0px; font-size:28px;"> {{ $plan_details['contract_number'] }} </h4>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="note note-info font-grey-mint membership_options" style="margin:0 0 10px; padding:5px 20px 10px 10px;">
                                                                    <p> Invoice Period </p>
                                                                    <h4 class="block" style="margin-bottom:0px; font-size:22px;"> <b>{{ $plan_details['invoice_period'] }}</b> </h4>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="note note-info font-grey-mint membership_options" style="margin:0 0 10px; padding:5px 20px 10px 10px;">
                                                                    <p> Signed On </p>
                                                                    <h4 class="block" style="margin-bottom:0px; font-size:24px;"> {{ $plan_details['day_start'] }} </h4>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="note note-info font-purple membership_options" style="margin:0 0 10px; padding:5px 20px 10px 10px;">
                                                                    <p> Signed By </p>
                                                                    @if($plan_details['signed_by_link']!='')
                                                                        <h4 class="block" style="margin-bottom:0px; font-size:24px;"> <b><a class="font-purple" href="{{ $plan_details['signed_by_link'] }}" target="_blank"> {{ $plan_details['signed_by_name'] }} </a></b> </h4>
                                                                    @else
                                                                        <h4 class="block" style="margin-bottom:0px; font-size:24px;"> <b>{{ $plan_details['signed_by_name'] }}</b> </h4>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="note note-danger bg-red-flamingo bg-font-red-flamingo color-white membership_options" style="margin:0 0 10px; padding:5px 20px 10px 10px;">
                                                                    <p> Invoice Status </p>
                                                                    <h4 class="block" style="margin-bottom:0px; font-size:24px;"> Not Paid </h4>
                                                                </div>
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
                                                            <a class="expand" href="" data-original-title="" title=""> </a>
                                                        </div>
                                                    </div>
                                                    <div class="portlet-body" style="display:none;">
                                                        <!-- BEGIN restriction boxes-->
                                                        <div class="row">
                                                        @if($restrictions)
                                                            @foreach ($restrictions as $key=>$restriction)
                                                                <div class="col-md-4">
                                                                    <div class="note {{ $restriction['color'] }} membership_rules" style="margin:0 0 15px; padding:5px 20px 10px 10px;">
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
                                                        </div>
                                                        <!-- END restriction boxes-->
                                                        @if(Auth::user()->can('manage-membership-plans'))
                                                        <div class="alert alert-danger">
                                                            <strong>Warning!</strong> This action will change this member membership restrictions to the current membership restriction status.
                                                            This is an action that can't be undone.<br /> <a class="alert-link re_sync_restriction">Do you want to reset the membership restrictions to default?</a>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            @endif

                                            @if (sizeof($InvoicesActionsPlanned))
                                            <div class="col-md-12">
                                                <div class="portlet light bordered">
                                                    <div class="portlet-title">
                                                        <div class="caption">
                                                            <i class="icon-equalizer font-blue-steel"></i>
                                                            <span class="caption-subject font-blue-steel bold uppercase"> Membership planned invoices and Actions </span>
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
                                                                @foreach ($InvoicesActionsPlanned as $singlePlanned)
                                                                    @if ($singlePlanned['type']=='invoice')
                                                                    <tr>
                                                                        <td class="highlight">
                                                                        @if ($singlePlanned['object']['invoiceLink']!='')
                                                                            <div class="success"></div>
                                                                            <a href="{{ $singlePlanned['object']['invoiceLink'] }}" target="_blank"> {{ $singlePlanned['object']['item_name'] }} </a>
                                                                        @else
                                                                            <div class="success"></div>
                                                                            <span> &nbsp; &nbsp; {{ $singlePlanned['object']['item_name'] }} </span>
                                                                        @endif
                                                                        </td>
                                                                        <td> {{ $singlePlanned['object']['issued_date'] }} - {{ $singlePlanned['object']['status'] }} </td>
                                                                        <td> {{ $singlePlanned['object']['last_active_date'] }} </td>
                                                                        <td class="hidden-xs"> {{ $singlePlanned['object']['price'].' '.\App\Http\Controllers\AppSettings::get_setting_value_by_name('finance_currency') }} </td>
                                                                        <td>
                                                                            @if ($singlePlanned['object']['invoiceStatus']!='')
                                                                                <span class="label label-sm label-success"> {{$singlePlanned['object']['invoiceStatus']}} </span>
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                    @elseif ($singlePlanned['type']=='cancel')
                                                                    <tr>
                                                                        <td class="highlight" colspan="5">
                                                                            @if ($singlePlanned['object']['processed']=='1')
                                                                                <div class="warning"></div> <a class="font-green-seagreen"> Processed - </a>
                                                                            @else
                                                                                <div class="danger"></div> <a class="font-red-thunderbird"> Pending - </a>
                                                                            @endif
                                                                            Membership cancellation starting with <b class="font-purple-studio">{{ \Carbon\Carbon::instance($singlePlanned['object']['end_date'])->addDay()->format('d M Y') }}</b>.
                                                                            Action added by <a style="margin-left:0px;" href="{{ $singlePlanned['object']['added_by_link'] }}" target="_blank">{{ $singlePlanned['object']['added_by_name'] }}</a>
                                                                            on {{ $singlePlanned['object']['created_at'] }}
                                                                            @if ($singlePlanned['object']['processed']=='0')
                                                                                | <a class="label label-sm label-danger remove_pending_action" data-id="{{ $singlePlanned['object']['id'] }}"> delete </a>
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                    @elseif ($singlePlanned['type']=='freeze')
                                                                    <tr>
                                                                        <td class="highlight" colspan="5">
                                                                            @if ($singlePlanned['object']['processed']=='1' && $singlePlanned['object']['status']=='old')
                                                                                <div class="warning"></div> <a class="font-green-seagreen"> Processed - </a>
                                                                            @elseif ($singlePlanned['object']['processed']=='1')
                                                                                <div class="warning"></div> <a class="font-green-seagreen"> Waiting Activation - </a>
                                                                            @else
                                                                                <div class="danger"></div> <a class="font-red-thunderbird"> Pending - </a>
                                                                            @endif
                                                                            Freeze membership between <b class="font-purple-studio">{{ $singlePlanned['object']['start_date']->format('d M Y') }}</b> and <b class="font-purple-studio">{{ $singlePlanned['object']['end_date']->format('d M Y') }}</b>.
                                                                            Action added by <a style="margin-left:0px;" href="{{ $singlePlanned['object']['added_by_link'] }}" target="_blank">{{ $singlePlanned['object']['added_by_name'] }}</a>
                                                                            on {{ $singlePlanned['object']['created_at'] }}
                                                                            @if ($singlePlanned['object']['processed']=='0')
                                                                                | <a class="label label-sm label-danger remove_pending_action" data-id="{{ $singlePlanned['object']['id'] }}"> delete </a>
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                    @elseif ($singlePlanned['type']=='update')
                                                                    <tr>
                                                                        <td class="highlight" colspan="5">
                                                                            @if ($singlePlanned['object']['processed']=='1')
                                                                                <div class="warning"></div> <a class="font-green-seagreen"> Processed - </a>
                                                                            @else
                                                                                <div class="danger"></div> <a class="font-red-thunderbird"> Pending - </a>
                                                                            @endif
                                                                            Membership {{ $singlePlanned['object']['additional_values']->is_update===true?'upgraded':'downgraded' }}
                                                                            from <b>{{ $singlePlanned['object']['additional_values']->old_membership_plan_name }}</b> to <b>{{ $singlePlanned['object']['additional_values']->new_membership_plan_name }}</b>
                                                                            starting with <b class="font-purple-studio">{{ \Carbon\Carbon::instance($singlePlanned['object']['start_date'])->format('d M Y') }}</b>.
                                                                            Action added by <a style="margin-left:0px;" href="{{ $singlePlanned['object']['added_by_link'] }}" target="_blank">{{ $singlePlanned['object']['added_by_name'] }}</a>
                                                                            on {{ $singlePlanned['object']['created_at'] }}
                                                                            @if ($singlePlanned['object']['processed']=='0')
                                                                                | <a class="label label-sm label-danger remove_pending_action" data-id="{{ $singlePlanned['object']['id'] }}"> delete </a>
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                    @endif
                                                                @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <!-- END FORM-->
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            <div class="col-md-12">
                                                <div class="portlet light bordered">
                                                    <div class="portlet-title">
                                                        <div class="caption">
                                                            <i class="icon-equalizer font-blue-steel"></i>
                                                            <span class="caption-subject font-blue-steel bold uppercase"> Membership history </span>
                                                        </div>
                                                        <div class="tools">
                                                            <a class="expand" href="" data-original-title="" title=""> </a>
                                                        </div>
                                                    </div>
                                                    <div class="portlet-body row" style="display:none;">
                                                        <div class="table-scrollable">
                                                            <table class="table table-bordered table-hover">
                                                                <thead>
                                                                <tr>
                                                                    <th> # </th>
                                                                    <th> Membership </th>
                                                                    <th> Start date </th>
                                                                    <th> End date </th>
                                                                    <th> Number of invoices </th>
                                                                    <th> Status </th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                @if( sizeof($old_memberships)>0)
                                                                    @foreach($old_memberships as $key => $membership)
                                                                        <tr>
                                                                            <td> {{ $key + 1 }} </td>
                                                                            <td> {{ $membership['membership_name'] }}</td>
                                                                            <td> {{ $membership['start_day'] }} </td>
                                                                            <td> {{ $membership['stop_day'] }} </td>
                                                                            <td> {{ $membership['number_of_invoices'] }} </td>
                                                                            <td>
                                                                                @if ($membership['status']=='pending')
                                                                                    <span class="label label-sm label-info "> Pending </span>
                                                                                @elseif ($membership['status']=='active')
                                                                                    <span class="label label-sm label-success"> Active </span>
                                                                                @elseif($membership['status']=='suspended')
                                                                                    <span class="label label-sm label-warning"> Suspended </span>
                                                                                @elseif($membership['status']=='canceled')
                                                                                    <span class="label label-sm label-warning"> Cancelled </span>
                                                                                @elseif($membership['status']=='expired')
                                                                                    <span class="label label-sm label-danger"> Expired </span>
                                                                                @endif
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                @else
                                                                    <tr>
                                                                        <td></td>
                                                                        <td colspan="5">There are no old memberships associated with this user</td>
                                                                    </tr>
                                                                @endif

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- END Membership Plan TAB -->
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

                <!--  -->
                <div class="modal fade" id="upgrade_downgrade_plan_box" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                <h4 class="modal-title"> Membership Plan Change [Upgrade / Downgrade] </h4>
                            </div>
                            <div class="modal-body form-horizontal" id="book_main_details_container">
                                <div class="form-body">
                                    <div class="form-group">
                                        <label class="control-label col-md-4 inline"> Change Plan To </label>
                                        <div class="col-md-8">
                                            <select name="change_membership_plans_list" class="form-control input-inline input-large  inline-block list_all_plans">
                                                <option value="" selected="selected"> Select membership plan </option>
                                                @foreach ($update_memberships as $membership)
                                                    <option value="{{$membership['id']}}"> {{$membership['name']}} - {{$membership['up_or_down']}} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 inline"> Change From </label>
                                        <div class="col-md-8">
                                            <select name="membership_plans_list" class="form-control input-inline input-large  inline-block list_all_plans">
                                                <option value="0" selected> Start Today </option>
                                                <option value="1">Next Invoice Period</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn green btn_no_show" onclick="javascript: $('#tab_1_5').unblock() ;"  data-dismiss="modal"> Return </button>
                                <button type="button" class="btn btn-primary mt-ladda-btn ladda-button update_downgrade_membership" data-style="expand-right" onclick="javascript:update_assigned_membership_plan();"> <span class="ladda-label"> Apply Change </span> </button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
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
                                <button type="button" class="btn green btn_no_show" data-toggle="modal" onclick="javascript: $('#tab_1_5').unblock();" href="#changeIt"> Return </button>
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
                        <div class="modal-content ">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                <h4 class="modal-title">Do you want to cancel the current membership plan?</h4>
                            </div>
                            <div class="modal-body margin-top-10" style="margin-bottom:0px; padding-bottom:5px;"> Please select the first day with no plan, from the drop-down list below</div>
                            <div class="modal-body margin-bottom-0" style="padding-top:5px;">
                                <select name="date_cancellation_time" id="date_cancellation_time" class="form-control input-inline input-large  inline-block list_all_plans">
                                    @foreach ($invoiceCancellation as $key=>$val)
                                        <option value="{{$key}}"> {{$val}} </option>
                                    @endforeach
                                </select>
                            </div>

                            @if (Auth::user()->can('general-permission-overwrite'))
                            <div class="modal-body margin-top-0" style="padding-top:0px;">
                                <div class="note note-danger" style="margin-bottom:0px;">
                                    <div class="form-group margin-bottom-5">
                                        <span class="help-inline">Membership Cancellation Date</span>
                                        <div class="input-group input-small date date-picker" data-date-start-date="today" data-date="{{ \Carbon\Carbon::today()->format('d-m-Y') }}" data-date-format="dd-mm-yyyy" data-date-viewmode="month" style="display:inline-flex; margin-top:2px; margin-right:40px;">
                                            <input type="text" class="form-control" name="custom_cancel_date" readonly style="background-color:#ffffff;">
                                            <span class="input-group-btn">
                                                <button class="btn default" type="button">
                                                    <i class="fa fa-calendar"></i>
                                                </button>
                                            </span>
                                        </div>

                                        <div class="checkbox-list">
                                            <label class="checkbox-inline help-inline">
                                                <input type="checkbox" id="overwrite_admin_cancellation_rule" value="1" > Accept cancellation overwrite rule </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="modal-footer">
                                <button type="button" class="btn dark btn-outline" onclick="javascript: $('#tab_1_5').unblock() ;" data-dismiss="modal">No, Go Back</button>
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
                                <button type="button" class="btn dark btn-outline"  data-dismiss="modal">No, Go Back</button>
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
                                        <br />The membership period will be extended with the period the membership is frozen.</p>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4">Freeze Interval</label>
                                    <div class="col-md-7">
                                        <div class="input-group input-large date-picker input-daterange" data-date-start-date="{{\Carbon\Carbon::today()->addDays(30)->format('d-m-Y')}}" data-date-end-date="{{\Carbon\Carbon::today()->addDays(360)->format('d-m-Y')}}" data-date-format="dd-mm-yyyy">
                                            <input type="text" class="form-control input-sm" name="freeze_from_date" id="freeze_from_date" value="{{\Carbon\Carbon::today()->addDays(30)->format('d-m-Y')}}">
                                            <span class="input-group-addon"> to </span>
                                            <input type="text" class="form-control input-sm" name="freeze_to_date" id="freeze_to_date"> </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-12 text-center"> ---- OR ---- </label>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4">Freeze period start date</label>
                                    <div class="col-md-7">
                                        <select name="freeze_start_date" id="freeze_start_date" class="form-control input input-sm list_all_plans">
                                            <option value="-1"> Select Date </option>
                                        @foreach ($invoiceFreeze as $key=>$oneInv)
                                            <option value="{{ $key }}"> {{ $oneInv }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4">how many months</label>
                                    <div class="col-md-7">
                                        <select name="freeze_no_months" id="freeze_no_months" class="form-control input input-sm list_all_plans">
                                            <option value="-1"> Select Number </option>
                                            <option value="1"> One Month </option>
                                            <option value="2"> Two Months </option>
                                            <option value="3"> Three Months </option>
                                            <option value="4"> Four Months </option>
                                            <option value="5"> Five Months </option>
                                            <option value="6"> Six Months </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn green btn_no_show" data-toggle="modal" href="#freeze_plan_confirm_box">Freeze plan</button>
                                <button type="button" class="btn dark btn-outline" onclick="javascript: $('#tab_1_5').unblock() ;" data-dismiss="modal">Return</button>
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
                                <button type="button" class="btn dark btn-outline" onclick="javascript: $('#tab_1_5').unblock() ;" data-dismiss="modal">No, Go Back</button>
                                <button type="button" class="btn green" onclick="javascript:freeze_membership();">Yes, Freeze</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <!-- END Recurrent Cancel Confirm modal window show -->

                <div class="modal fade bs-modal-sm" id="cancel_planned_action_confirm_box" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                <h4 class="modal-title">Do you want to remove the selected planned action?</h4>
                            </div>
                            <div class="modal-body margin-top-10 margin-bottom-10"> By clicking "Yes, Remove" the action planned will be deleted and no action will be taken on the current membership plan.</div>
                            <div class="modal-footer">
                                <button type="button" class="btn dark btn-outline" data-dismiss="modal">No, Go Back</button>
                                <button type="button" class="btn green" onclick="javascript:remove_pending_action();">Yes, Remove</button>
                            </div>
                            <input type="hidden" name="remove_action_id" value="0" />
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
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
                                        <label class="col-md-4 control-label"> Public Message<br /><small>visible by members</small></label>
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
                                            <label class="control-label"> Internal Message <small>visible by employees</small> </label>
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
                <!-- BEGIN Reset Restrictions modal window -->
                <div class="modal fade bs-modal-sm" id="resync_confirm_box" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                <h4 class="modal-title"> Update/Sync User Memberships </h4>
                            </div>
                            <div class="modal-body margin-top-10 margin-bottom-10">
                                This is an action that can't be undone. By clicking "Yes : Re-sync", this member will receive the restriction/attribute from the membership default.
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn green" onclick="javascript:resync_attribute_restriction();">Yes : Re-sync</button>
                                <button type="button" class="btn dark btn-outline" data-dismiss="modal">No : Go Back</button>
                                <input type="hidden" name="plan-id" value="{{ $membership_plan->id }}" />
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <!-- END Reset Restrictions modal window -->
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
    <script src="{{ asset('assets/global/scripts/jquery.matchHeight.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/ladda/spin.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/ladda/ladda.min.js') }}" type="text/javascript"></script>
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

            var handleValidationAccessCardChange = function() {
                var form1 = $('#update_access_card');
                var error1 = $('.alert-danger', form1);
                var success1 = $('.alert-success', form1);

                form1.validate({
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block help-block-error', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    ignore: "",  // validate all fields including form hidden input
                    rules: {
                        access_card_number: {
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
                        access_card_number_update(); // submit the form
                    }
                });
            }

            var handleValidationStoreCredit = function() {
                var form1 = $('#add_store_credit');
                var error1 = $('.alert-danger', form1);
                var success1 = $('.alert-success', form1);

                form1.validate({
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block help-block-error', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    ignore: "",  // validate all fields including form hidden input
                    rules: {
                        store_credit_value: {
                            minlength: 1,
                            required: true,
                            number:true,
                            min:1
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
                        add_store_credit(); // submit the form
                    }
                });
            }

            return {
                //main function to initiate the module
                init: function () {
                    handleValidationAccChange();
                    handleValidationAccessCardChange();
                    handleValidationStoreCredit();
                }
            };
        }();

        var ComponentsDateTimePickers = function () {

            var handleDatePickers = function () {

                if (jQuery().datepicker) {
                    $('.date-picker').datepicker({
                        rtl: App.isRTL(),
                        orientation: "left bottom",
                        autoclose: true,
                        daysOfWeekHighlighted: "0",
                        weekStart:1,
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

        $('.list_all_plans').on('change', function(){
            var button = $('.apply_new_membership_plan');
            if ($(this).val()==-1 || $(this).val()=='{{$membership_plan->id}}'){
                button.hide();
            }
            else{
                button.show();
            }
        });

        function ui_block(selector) {
            var message =  "<div class='loading-message loading-message-boxed'>	<img src='{{ asset('assets/global/img/loading-spinner-grey.gif') }}' align=''><span>&nbsp;&nbsp;Processing...</span></div>";
            selector.block({
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

        $('.apply_new_membership_plan').on('click', function(){
            var message =  "<div class='loading-message loading-message-boxed'>	<img src='{{ asset('assets/global/img/loading-spinner-grey.gif') }}' align=''><span>&nbsp;&nbsp;Processing...</span></div>";
            $('#tab_1_5').block({
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
            $.ajax({
                url: '{{route('admin/membership_plans/ajax_get_details')}}',
                type: "post",
                data: {
                    'selected_plan': $('select[name=membership_plans_list]').val(),
                    'selected_location': $('select[name=locations_list]').val()
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

        $('.remove_pending_action').on('click', function(){
            $('input[name="remove_action_id"]').val($(this).attr('data-id'));

            $('#cancel_planned_action_confirm_box').modal('show');
        });

        $('.re_sync_restriction').on('click', function(){
            $("#resync_confirm_box").modal('show');
        });

        function remove_pending_action(){
            var userID = '{{$user->id}}';

            $.ajax({
                url: '{{route('admin/membership_plans/delete_pending_action')}}',
                type: "post",
                data: {
                    'selected_action': $('input[name=remove_action_id]').val(),
                    'member_id':       userID
                },
                success: function(data){
                    if (data.success) {
                        $('#cancel_planned_action_confirm_box').modal('hide');
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

        function change_membership_plan(){
            var userID = '{{$user->id}}';

            $.ajax({
                url: '{{route('admin/membership_plans/assign_to_member')}}',
                type: "post",
                data: {
                    'selected_location': $('select[name=locations_list]').val(),
                    'selected_plan':    $('select[name=membership_plans_list]').val(),
                    'start_date':       $('input[name="start_date"]').val(),
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
                        $('#tab_1_5').unblock();
                        show_notification(data.title, data.message, 'lime', 3500, 0);

                        setTimeout(function(){
                            location.reload();
                        },2000);
                    }
                    else{
                        show_notification(data.title, data.errors, 'tangerine', 3500, 0);
                    }
                    $('#tab_1_5').unblock();
                }
            });
        }

        function update_assigned_membership_plan(){
            var userID = '{{$user->id}}';

            var btn = document.querySelector(".update_downgrade_membership");
            var la = Ladda.create(btn);
            la.start();

            $.ajax({
                url: '{{route('admin/membership_plans/changed_active_plan')}}',
                type: "post",
                data: {
                    'selected_plan':    $('select[name=change_membership_plans_list]').val(),
                    'start_date':       $('select[name=membership_plans_list]').val(),
                    'member_id':        {{ $user->id }}
                },
                success: function(data){
                    la.stop();

                    if (data.success) {
                        $('#upgrade_downgrade_plan_box').modal('hide');
                        show_notification(data.title, data.message, 'lime', 3500, 0);

                        setTimeout(function(){
                            location.reload();
                        },3500);
                    }
                    else{
                        show_notification(data.title, data.errors, 'tangerine', 5000, 0);
                    }
                    $('#tab_1_5').unblock();
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
                    'invoice_date':$('select[name=freeze_start_date]').val(),
                    'no_of_months':$('select[name=freeze_no_months]').val(),
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
                    $('#tab_1_5').unblock();
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
                    'cancellation_date':$('select[name="date_cancellation_time"]').val(),
                    @if (Auth::user()->can('general-permission-overwrite'))
                    'is_overwrite':$('#overwrite_admin_cancellation_rule:checked').val(),
                    'custom_cancellation_date':$('input[name="custom_cancel_date"]').val()
                    @endif
                },
                success: function(data){
                    if (data.success) {
                        $('#cancel_plan_confirm_box').modal('hide');
                        $('#cancel_plan_box').modal('hide');
                        show_notification(data.title, data.message, 'lime', 3100, 0);

                        setTimeout(function(){
                            location.reload();
                        },3500);
                    }
                    else{
                        $('#cancel_plan_confirm_box').modal('hide');
                        show_notification(data.title, data.errors, 'tangerine', 3500, 0);
                    }
                    $('#tab_1_5').unblock();
                }
            });
        }

        function access_card_number_update(){
            ui_block($('#tab_1_2'));
            $.ajax({
                url: '{{route('ajax/front_member_update_access_card')}}',
                type: "post",
                cache: false,
                data: {
                    'memberID':     '{{ $user->id }}',
                    'card_value':   $('input[name="access_card_number"]').val(),
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
                    $('#tab_1_2').unblock();
                }

            });
        }

        function add_store_credit(){
            ui_block($('#tab_1_3'));
            $.ajax({
                url: '{{route('ajax/buy_store_credit')}}',
                type: "post",
                cache: false,
                data: {
                    'member_id':     '{{ $user->id }}',
                    'package_id':   $('select[name="store_credit_value"]').val(),
                    'is_bonus':0,
                    'issue_invoice':1
                },
                success: function (data) {
                    if (data.success) {
                        $('#change_member_status').modal('hide');
                        show_notification(data.title, data.message, 'lime', 3500, 0);
                        location.reload();
                    }
                    else{
                        show_notification(data.title, data.errors, 'ruby', 3500, 0);
                    }
                    $('#tab_1_3').unblock();
                }
            });
        }

        @if(Auth::user()->can('manage-membership-plans'))
        function resync_attribute_restriction(){
            $.ajax({
                url: '{{route('membership_plan-resync_member_restriction')}}',
                type: "post",
                data: {
                    'member_membership_id': '{{@$membership_plan->id}}',
                    'member_id':'{{$user->id}}'
                },
                success: function(data){
                    if(data.success){
                        show_notification('Active Memberships plans re-synced!', data.message, 'lime', 3500, 0);
                        setTimeout(function(){
                            location.reload();
                        },1000);
                    }
                    else{
                        show_notification('Error re-syncing active membership plans!', data.errors, 'ruby', 3500, 0);
                    }
                }
            });
        };
        @endif

        var options = { byRow: true, property: 'height', target: null, remove: false};
        $(function() {
            $('.membership_rules').matchHeight(options);
            $('.membership_options').matchHeight(options);
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
