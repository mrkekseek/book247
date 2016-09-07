@extends('admin.layouts.main')

@section('pageLevelPlugins')
    <link href="{{ asset('assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/bootstrap-colorpicker/css/colorpicker.css') }}" rel="stylesheet" type="text/css" />
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

@section('title', 'Back-end users - All User Roles')
@section('pageBodyClass','page-container-bg-solid page-header-fixed page-sidebar-closed-hide-logo')

@section('pageContentBody')
    <div class="page-content">
        <!-- BEGIN PAGE HEAD-->
        <div class="page-head">
            <!-- BEGIN PAGE TITLE -->
            <div class="page-title">
                <h1>{!!$text_parts['title']!!}
                    <small>{!!$text_parts['subtitle']!!}</small>
                </h1>
            </div>
            <!-- END PAGE TITLE -->
        </div>
        <!-- END PAGE HEAD-->
        <!-- BEGIN PAGE BASE CONTENT -->
        <div class="row">
        @if ($membership_plan)
            <div class="col-md-12">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-equalizer {{ $membership_plan->status == 'active' ? 'font-green-jungle' : 'font-red-thunderbird' }}"></i>
                            <span class="caption-subject {{ $membership_plan->status == 'active' ? 'font-green-jungle' : 'font-red-thunderbird' }} bold uppercase"> Membership plan details</span>
                            <span class="caption-helper {{ $membership_plan->status == 'active' ? 'font-green-jungle' : 'font-red-thunderbird' }}">
                                @if ($membership_plan->status == 'active')
                                    - active and in use
                                @else
                                    - status "{{$membership_plan->status}}" - switch to "active" when you want this plan to be available
                                @endif
                            </span>
                        </div>
                        <div class="tools">
                            <a class="expand" href="" data-original-title="" title=""> </a>
                        </div>
                    </div>
                    <div class="portlet-body form" style="display:none;">
                        <!-- BEGIN FORM-->
                        <form action="#" id="new_membership_plan" class="form-horizontal">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-3"> Membership Name </label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" name="membership_name" placeholder="New plan name" value="{{$membership_plan->name}}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3 inline"> Membership Price </label>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control input-inline input-small" name="membership_price" placeholder="NOK" value="{{$membership_plan->price[0]->price}}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3 inline"> Invoicing Period </label>
                                            <div class="col-md-9">
                                                <select name="membership_period" class="form-control input-inline input inline-block">
                                                    <option {!!$membership_plan->plan_period==7?'selected="selected"':''!!} value="7">once every 7 days</option>
                                                    <option {!!$membership_plan->plan_period==14?'selected="selected"':''!!} value="14">once every 14 days</option>
                                                    <option {!!$membership_plan->plan_period==30?'selected="selected"':''!!} value="30">one per month</option>
                                                    <option {!!$membership_plan->plan_period==90?'selected="selected"':''!!} value="90">once every three months</option>
                                                    <option {!!$membership_plan->plan_period==180?'selected="selected"':''!!} value="180">once every six months</option>
                                                    <option {!!$membership_plan->plan_period==360?'selected="selected"':''!!} value="360">once per year</option>
                                                </select>
                                                <span class="help-inline inline-block"> </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3 inline"> Binding Period </label>
                                            <div class="col-md-9">
                                                <select name="binding_period" class="form-control input-inline input inline-block">
                                                    @for ($i=1; $i<25;$i++)
                                                        <option {!!$membership_plan->binding_period==$i?'selected="selected"':''!!} value="{{ $i }}">{{ $i }} {!!$membership_plan->binding_period==$i?' - Current Selection':''!!}</option>
                                                    @endfor
                                                </select>
                                                <span class="help-inline inline-block"> months </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3"> Administration Fee Name </label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" name="administration_fee_name" placeholder="Fee Name" value="{{$membership_plan->administration_fee_name}}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3"> Administration Fee Price </label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control input-medium" name="administration_fee_price" placeholder="Fee Price" value="{{$membership_plan->administration_fee_amount}}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Plan Color</label>
                                            <div class="col-md-3">
                                                <div class="input-group color colorpicker-default" data-color="{{$membership_plan->plan_calendar_color}}" data-color-format="rgba">
                                                    <input type="text" class="form-control" name="membership_color" value="{{$membership_plan->plan_calendar_color}}" readonly>
                                                        <span class="input-group-btn">
                                                            <button class="btn default" type="button">
                                                                <i style="background-color: {{$membership_plan->plan_calendar_color}}};"></i>&nbsp;</button>
                                                        </span>
                                                </div>
                                                <!-- /input-group -->
                                            </div>
                                            <div class="col-md-6">
                                                <span class="help-inline  block-inline"> Color to be displayed in calendar booking </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3 inline">Short Description</label>
                                            <div class="col-md-9">
                                                <textarea name="membership_short_description" style="min-height:100px;" class="form-control">{{$membership_plan->short_description}}</textarea>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3 inline">Long/HTML Description</label>
                                            <div class="col-md-9">
                                                <textarea name="membership_long_description" style="height:100px;" class="form-control">{{$membership_plan->description}}</textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3 inline"> Membership Status </label>
                                            <div class="col-md-7">
                                                <select name="membership_status" class="form-control input-inline input-small  inline-block">
                                                    <option {!!$membership_plan->status=='active'?'selected="selected"':''!!} value="active"> Active </option>
                                                    <option {!!$membership_plan->status=='pending'?'selected="selected"':''!!} value="pending"> Pending </option>
                                                    <option {!!$membership_plan->status=='suspended'?'selected="selected"':''!!} value="suspended"> Suspended </option>
                                                    <option {!!$membership_plan->status=='deleted'?'selected="selected"':''!!} value="deleted"> Deleted </option>
                                                </select>
                                                <span class="help-inline inline-block"> Active status will make it live </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--/row-->
                            </div>
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-offset-3 col-md-9">
                                                <button type="submit" class="btn green">Save Plan</button>
                                                <button type="button" class="btn default">Cancel</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6"> </div>
                                </div>
                            </div>
                        </form>
                        <!-- END FORM-->
                    </div>
                </div>
            </div>

            @if ($membership_plan->status != 'active')
            <div class="col-md-12">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-equalizer font-purple-studio"></i>
                            <span class="caption-subject font-purple-studio bold uppercase"> Add Membership Attributes </span>
                            <span class="caption-helper">set the membership properties like activities included and much more</span>
                        </div>
                        <div class="tools">
                            <a class="collapse" href="" data-original-title="" title=""> </a>
                        </div>
                    </div>
                    <div class="portlet-body form tabbable-line boxless tabbable-reversed">
                        <!-- BEGIN FORM-->
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#tab_0" data-toggle="tab"> Include Activity </a>
                            </li>
                            <li>
                                <a href="#tab_1" data-toggle="tab"> Booking Time Period </a>
                            </li>
                            <li>
                                <a href="#tab_2" data-toggle="tab"> Booking Time of Day </a>
                            </li>
                            <li>
                                <a href="#tab_3" data-toggle="tab"> Open Bookings </a>
                            </li>
                            <li>
                                <a href="#tab_4" data-toggle="tab"> Cancellation </a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_0">
                                <div class="portlet light bordered form-fit">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="icon-equalizer font-green-haze"></i>
                                            <span class="caption-subject font-green-haze bold uppercase">Activities</span>
                                            <span class="caption-helper">select witch ones are included in membership plan...</span>
                                        </div>
                                        <div class="actions">  </div>
                                    </div>
                                    <div class="portlet-body form">
                                        <!-- BEGIN FORM-->
                                        <form action="#" class="form-horizontal form-row-seperated">
                                            <div class="form-body">
                                                <div class="form-group">
                                                    <label class="control-label col-md-3">Select Included Activities</label>
                                                    <div class="col-md-9">
                                                        <select class="form-control input-large" name="plan_included_activity" multiple style="height:120px;">
                                                            @foreach ($activities as $activity)
                                                                <option value="{{$activity->id}}"> {{$activity->name}} </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-actions">
                                                <div class="row">
                                                    <div class="col-md-offset-3 col-md-9">
                                                        <button type="submit" class="btn green add_included_activity">
                                                            <i class="fa fa-pencil"></i> Add Activities </button>
                                                        <button type="button" class="btn default">Cancel</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <!-- END FORM-->
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_1">
                                <div class="portlet light bordered form-fit">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="icon-equalizer font-green-haze"></i>
                                            <span class="caption-subject font-green-haze bold uppercase">Form Sample</span>
                                            <span class="caption-helper">some info...</span>
                                        </div>
                                        <div class="actions">  </div>
                                    </div>
                                    <div class="portlet-body form">
                                        <!-- BEGIN FORM-->
                                        <form action="#" class="form-horizontal form-row-seperated">
                                            <div class="form-body" style="padding-left:15px; padding-right:15px; border-bottom:none;">
                                                <div class="form-group" style="border-bottom:none;">
                                                    <span class="help-block inline-block"> Player cannot book more than </span>
                                                    <select name="hours_until_booking" class="form-control form-inline input-small inline-block">
                                                        <option value="1">1 hour</option>
                                                        <option value="2">2 hours</option>
                                                        <option value="3">3 hours</option>
                                                        <option value="4">4 hours</option>
                                                        <option value="5">5 hours</option>
                                                        <option value="6">6 hours</option>
                                                        <option value="9">9 hours</option>
                                                        <option value="12">12 hours</option>
                                                        <option value="24">1 day</option>
                                                        <option value="48">2 days</option>
                                                        <option value="72">3 days</option>
                                                        <option value="96">4 days</option>
                                                        <option value="120">5 days</option>
                                                        <option value="144">6 days</option>
                                                        <option value="168">7 days</option>
                                                        <option value="336">14 days</option>
                                                    </select>
                                                    <span class="help-block inline-block"> up front </span>
                                                </div>
                                            </div>
                                            <div class="form-actions">
                                                <div class="row">
                                                    <div class="col-md-offset-3 col-md-9">
                                                        <button type="submit" class="btn green add_booking_allowed_interval">
                                                            <i class="fa fa-pencil"></i> Add Booking Allowed Interval </button>
                                                        <button type="button" class="btn default">Cancel</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <!-- END FORM-->
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_2">
                                <div class="portlet light bordered form-fit">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="icon-equalizer font-green-haze"></i>
                                            <span class="caption-subject font-green-haze bold uppercase">Form Sample</span>
                                            <span class="caption-helper">some info...</span>
                                        </div>
                                        <div class="actions">  </div>
                                    </div>
                                    <div class="portlet-body form">
                                        <!-- BEGIN FORM-->
                                        <form action="#" class="form-horizontal form-row-seperated">
                                            <div class="form-body">
                                                <div class="form-group">
                                                    <label class="control-label col-md-3">Day of the week</label>
                                                    <div class="col-md-9">
                                                        <select name="booking_day_selection" class="form-control input-large booking_day_selection" style="height:150px;" multiple>
                                                            <option value="1">Monday</option>
                                                            <option value="2">Tuesday</option>
                                                            <option value="3">Wednesday</option>
                                                            <option value="4">Thursday</option>
                                                            <option value="5">Friday</option>
                                                            <option value="6">Saturday</option>
                                                            <option value="0">Sunday</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3">Time of day</label>
                                                    <div class="col-md-9">
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control form-inline input-xsmall inline-block" placeholder="hour" name="booking_hour_start">
                                                            <input type="text" class="form-control form-inline input-xsmall inline-block" placeholder="minutes" name="booking_minute_start">
                                                            <span class="help-block inline-block"> Start Time </span>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control inline-block input-xsmall" placeholder="hour" name="booking_hour_stop">
                                                            <input type="text" class="form-control inline-block input-xsmall" placeholder="minutes" name="booking_minute_stop">
                                                            <span class="help-block inline-block"> End Time </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group" style="border-bottom:none;">
                                                    <label class="col-md-3 control-label"> Use Special Permission </label>
                                                    <div class="col-md-9">
                                                        <div class="radio-list">
                                                            <label class="radio-inline">
                                                                <input type="radio" name="useSpecialPermissions" value="1" checked> Yes </label>
                                                            <label class="radio-inline">
                                                                <input type="radio" name="useSpecialPermissions" value="0" checked> No </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--<div class="special_permission_option form-group" style="border-bottom:none; padding-top:0; display:none;">
                                                    <label class="col-md-3 control-label"> Book Current Day </label>
                                                    <div class="col-md-9">
                                                        <div class="radio-list">
                                                            <label class="radio-inline">
                                                                <input type="radio" name="specialCurrentDay" value="1" checked> Yes </label>
                                                            <label class="radio-inline">
                                                                <input type="radio" name="specialCurrentDay" value="0" checked> No </label>
                                                        </div>
                                                    </div>
                                                </div>-->
                                                <input type="hidden" name="specialCurrentDay" value="1" />
                                                <div class=" special_permission_option form-group" style="border-bottom:none; padding-top:0; display:none;">
                                                    <label class="col-md-3 control-label"> Book Next Day/Days </label>
                                                    <div class="col-md-9">
                                                        <select class="form-control input-large" name="specialDaysAhead">
                                                            <option value="-1">Select Number Of Days</option>
                                                            <option value="1">1 Day Ahead</option>
                                                            <option value="2">2 Days Ahead</option>
                                                            <option value="3">3 Days Ahead</option>
                                                            <option value="4">4 Days Ahead</option>
                                                            <option value="5">5 Days Ahead</option>
                                                            <option value="6">6 Days Ahead</option>
                                                            <option value="7">7 Days Ahead</option>
                                                            <option value="8">8 Days Ahead</option>
                                                            <option value="9">9 Days Ahead</option>
                                                            <option value="10">10 Days Ahead</option>
                                                            <option value="11">11 Days Ahead</option>
                                                            <option value="12">12 Days Ahead</option>
                                                            <option value="13">13 Days Ahead</option>
                                                            <option value="14">14 Days Ahead</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-actions">
                                                <div class="row">
                                                    <div class="col-md-offset-3 col-md-9">
                                                        <button type="submit" class="btn green add_booking_time_of_day">
                                                            <i class="fa fa-pencil"></i> Add Day/Time Values </button>
                                                        <button type="button" class="btn default">Cancel</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <!-- END FORM-->
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_3">
                                <div class="portlet light bordered form-fit">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="icon-equalizer font-green-haze"></i>
                                            <span class="caption-subject font-green-haze bold uppercase">Form Sample</span>
                                            <span class="caption-helper">some info...</span>
                                        </div>
                                        <div class="actions">  </div>
                                    </div>
                                    <div class="portlet-body form">
                                        <!-- BEGIN FORM-->
                                        <form action="#" class="form-horizontal form-row-seperated">
                                            <div class="form-body">
                                                <div class="form-group">
                                                    <label class="control-label col-md-3">Membership Open Bookings</label>
                                                    <div class="col-md-9">
                                                        <input type="text" placeholder="number of bookings" class="block-inline input-large form-control" name="nr_of_open_bookings" />
                                                        <span class="help-block"> after these free bookings, the member needs to pay for any other open bookings he is doing </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-actions">
                                                <div class="row">
                                                    <div class="col-md-offset-3 col-md-9">
                                                        <button type="submit" class="btn green add_nr_open_bookings">
                                                            <i class="fa fa-pencil"></i> Add "Open Bookings" Limit </button>
                                                        <button type="button" class="btn default">Cancel</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <!-- END FORM-->
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_4">
                                <div class="portlet light bordered form-fit">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="icon-equalizer font-green-haze"></i>
                                            <span class="caption-subject font-green-haze bold uppercase">Cancellation</span>
                                            <span class="caption-helper">some info...</span>
                                        </div>
                                        <div class="actions">  </div>
                                    </div>
                                    <div class="portlet-body form">
                                        <!-- BEGIN FORM-->
                                        <form action="#" class="form-horizontal form-row-seperated">
                                            <div class="form-body">
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"> Can cancel </label>
                                                    <div class="col-md-9">
                                                        <input type="text" placeholder="" class="input-small form-control inline-block" name="nr_of_hours_before_cancellation" />
                                                        <span class="help-block inline-block"> hours before </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-actions">
                                                <div class="row">
                                                    <div class="col-md-offset-3 col-md-9">
                                                        <button type="submit" class="btn green add_cancellation_hours">
                                                            <i class="fa fa-pencil"></i> Add "Cancellation" Limits </button>
                                                        <button type="button" class="btn default">Cancel</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <!-- END FORM-->
                                    </div>
                                </div>
                            </div>
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
                            <span class="caption-subject font-blue-steel bold uppercase"> Active Attributes & Restrictions </span>
                            <span class="caption-helper">for the selected membership plan</span>
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
                                    <div class="note {{ $restriction['color'] }}" style="min-height:145px;">
                                        @if ($membership_plan->status != 'active')
                                        <button class="close remove_restriction" data-id="{{$restriction['id']}}" type="button"></button>
                                        @endif

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

            <div class="modal fade bs-modal-sm" id="cancel_confirm_box" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                            <h4 class="modal-title"> Remove the selected attribute/restriction </h4>
                        </div>
                        <div class="modal-body margin-top-10 margin-bottom-10"> Do you want to remove the selected attribute/restriction from the current plan? By clicking "Yes - Remove" all the newly signed membership plans will not have this rule/restriction.</div>
                        <div class="modal-footer">
                            <button type="button" class="btn green" onclick="javascript:remove_attribute_restriction();">Yes - Remove</button>
                            <button type="button" class="btn dark btn-outline" data-dismiss="modal">No - Go Back</button>
                            <input type="hidden" name="attribute-id" value="-1" />
                            <input type="hidden" name="plan-id" value="{{ $membership_plan->id }}" />
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
        @else
            <div class="col-md-12">
                <div class="portlet light ">
                    <div class="portlet-body">
                        <div class="note note-warning">
                            <h4 class="block">An error occured</h4>
                            <p> The price plan that you are searching for could not be found. Go back to the page you came here and try refreshing it, then access the link again.<br /><br /> Thank you!</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        </div>
        <!-- END PAGE BASE CONTENT -->
    </div>
@endsection

@section('pageBelowLevelPlugins')
    <script src="{{ asset('assets/global/scripts/datatable.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/datatables/datatables.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js') }}" type="text/javascript"></script>
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

        var ComponentsColorPickers = function() {
            var handleColorPicker = function () {
                if (!jQuery().colorpicker) {
                    return;
                }
                $('.colorpicker-default').colorpicker({
                    format: 'hex'
                });
                $('.colorpicker-rgba').colorpicker();
            }

            return {
                //main function to initiate the module
                init: function() {
                    handleColorPicker();
                }
            };
        }();

        var FormValidation = function () {
            var handleValidation1 = function() {
                var form1 = $('#new_membership_plan');
                var error1 = $('.alert-danger', form1);
                var success1 = $('.alert-success', form1);

                form1.validate({
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block help-block-error', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    ignore: "",  // validate all fields including form hidden input
                    rules: {
                        membership_name: {
                            minlength: 5,
                            required: true
                        },
                        membership_price: {
                            number: true,
                            minlength: 1,
                            required: true
                        },
                        membership_period: {
                            required: true
                        },
                        binding_period: {
                            required: true,
                            number:true
                        },
                        administration_fee_name: {
                            minlength: 5,
                            required: true
                        },
                        administration_fee_price: {
                            number: true,
                            minlength: 1,
                            required: true
                        },
                        membership_color: {
                            minlength: 7,
                            required: true
                        },
                        membership_short_description: {
                            minlength: 20,
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
                        update_membership_details(); // submit the form
                    }
                });
            }

            return {
                //main function to initiate the module
                init: function () {
                    handleValidation1();
                }

            };
        }();

        $(document).ready(function(){
            FormValidation.init();

            ComponentsColorPickers.init();
        });

        function update_membership_details(){
            @if ($membership_plan)
                $.ajax({
                    url: '{{route('admin.membership_plan.update', ['id'=>$membership_plan->id])}}',
                    type: "post",
                    data: {
                        'name':                         $('input[name=membership_name]').val(),
                        'price':                        $('input[name=membership_price]').val(),
                        'plan_period':                  $('select[name=membership_period]').val(),
                        'binding_period':               $('select[name=binding_period]').val(),
                        'status':                       $('select[name=membership_status]').val(),
                        'administration_fee_name':      $('input[name=administration_fee_name]').val(),
                        'administration_fee_amount':    $('input[name=administration_fee_price]').val(),
                        'plan_calendar_color':          $('input[name=membership_color]').val(),
                        'membership_short_description': $('textarea[name=membership_short_description]').val(),
                        'membership_long_description':  $('textarea[name=membership_long_description]').val(),
                        '_method':'patch'
                    },
                    success: function(data){
                        if(data.success){
                            show_notification('Membership Plan Details Updated', data.message, 'lime', 3500, 0);
                            setTimeout(function(){
                                location.reload();
                            },2000);
                        }
                        else{
                            show_notification('Error updating membership details', data.errors, 'ruby', 3500, 0);
                        }
                    }
                });
            @endif
        }

        $('.add_included_activity').on('click', function(event){
            event.preventDefault();

            $.ajax({
                url: '{{route('membership_plan-add_restriction')}}',
                type: "post",
                data: {
                    'type' : 'included_activity',
                    'activities': serealizeSelects($('select[name="plan_included_activity"]')),
                    'membership_id': '{{@$membership_plan->id}}'
                },
                success: function(data){
                    if(data.success){
                        show_notification('Included Activities Added', data.message, 'lime', 3500, 0);
                        setTimeout(function(){
                            location.reload();
                        },2000);
                    }
                    else{
                        show_notification('Error adding activities', data.errors, 'ruby', 3500, 0);
                    }
                }
            });
        });

        $('.add_booking_allowed_interval').on('click', function(event){
            event.preventDefault();

            $.ajax({
                url: '{{route('membership_plan-add_restriction')}}',
                type: "post",
                data: {
                    'type' : 'booking_time_interval',
                    'min_val': 0,
                    'max_val': $('select[name="hours_until_booking"]').val(),
                    'membership_id': '{{@$membership_plan->id}}'
                },
                success: function(data){
                    if(data.success){
                        show_notification('Booking Time Period Added', data.message, 'lime', 3500, 0);
                        setTimeout(function(){
                            location.reload();
                        },2000);
                    }
                    else{
                        show_notification('Error adding time period', data.errors, 'ruby', 3500, 0);
                    }
                }
            });
        });

        $('.add_booking_time_of_day').on('click', function(event){
            event.preventDefault();

            var special_current_day = '';
            var special_days_ahead = '';
            if ($('input[name="useSpecialPermissions"]').val()==1){
                special_current_day = $('input[name="specialCurrentDay"]').val();
                special_days_ahead  = $('select[name="specialDaysAhead"]').val();
            }

            $.ajax({
                url: '{{route('membership_plan-add_restriction')}}',
                type: "post",
                data: {
                    'type' : 'time_of_day',
                    'day_selection': serealizeSelects($('select[name="booking_day_selection"]')),
                    'hour_start': $('input[name="booking_hour_start"]').val(),
                    'hour_stop': $('input[name="booking_hour_stop"]').val(),
                    'minute_start': $('input[name="booking_minute_start"]').val(),
                    'minute_stop': $('input[name="booking_minute_stop"]').val(),
                    'membership_id': '{{@$membership_plan->id}}',
                    'special_current_day':special_current_day,
                    'special_days_ahead':special_days_ahead
                },
                success: function(data){
                    if(data.success){
                        show_notification('Booking Time of Day added', data.message, 'lime', 3500, 0);
                        setTimeout(function(){
                            location.reload();
                        },2000);
                    }
                    else{
                        show_notification('Error adding booking time of day', data.errors, 'ruby', 3500, 0);
                    }
                }
            });
        });

        $('.add_nr_open_bookings').on('click', function(event){
            event.preventDefault();

            $.ajax({
                url: '{{route('membership_plan-add_restriction')}}',
                type: "post",
                data: {
                    'type' : 'open_bookings',
                    'open_bookings': $('input[name="nr_of_open_bookings"]').val(),
                    'membership_id': '{{@$membership_plan->id}}'
                },
                success: function(data){
                    if(data.success){
                        show_notification('Nr. of open bookings added', data.message, 'lime', 3500, 0);
                        setTimeout(function(){
                            location.reload();
                        },2000);
                    }
                    else{
                        show_notification('Error adding nr. of open bookings', data.errors, 'ruby', 3500, 0);
                    }
                }
            });
        });

        $('.add_cancellation_hours').on('click', function(event){
            event.preventDefault();

            $.ajax({
                url: '{{route('membership_plan-add_restriction')}}',
                type: "post",
                data: {
                    'type' : 'cancellation',
                    'cancellation_before_hours': $('input[name="nr_of_hours_before_cancellation"]').val(),
                    'membership_id': '{{@$membership_plan->id}}'
                },
                success: function(data){
                    if(data.success){
                        show_notification('Cancellation hours restriction added', data.message, 'lime', 3500, 0);
                        setTimeout(function(){
                            location.reload();
                        },2000);
                    }
                    else{
                        show_notification('Error adding cancellation restriction', data.errors, 'ruby', 3500, 0);
                    }
                }
            });
        });

        $('.remove_restriction').on('click', function(){
            $('input[name="attribute-id"]').val($(this).attr('data-id'));
            $("#cancel_confirm_box").modal('show');
        });

        $('input[name="useSpecialPermissions"]').on('change', function(){
            if ($(this).val() == 1){
                $('.special_permission_option').show();
            }
            else{
                $('.special_permission_option').hide();
            }
        });

        function remove_attribute_restriction(){
            $.ajax({
                url: '{{route('membership_plan-remove_restriction')}}',
                type: "post",
                data: {
                    'restriction_id':$('input[name="attribute-id"]').val(),
                    'membership_id': '{{@$membership_plan->id}}'
                },
                success: function(data){
                    if(data.success){
                        show_notification('Cancellation hours restriction added', data.message, 'lime', 3500, 0);
                        setTimeout(function(){
                            location.reload();
                        },1000);
                    }
                    else{
                        show_notification('Error adding cancellation restriction', data.errors, 'ruby', 3500, 0);
                    }
                }
            });
        };

        /**
         * Convert select to array with values
         */
        function serealizeSelects (select)
        {
            var array = [];
            var list = '';
            select.each(function(){
                array.push($(this).val())
                list = list + $(this).val() + ",";
                console.log($(this).val());
            });
            return array;
        }

        /* Start - All admin scripts */
        var UserTopAjaxSearch = function() {

            var handleDemo = function() {

                // Set the "bootstrap" theme as the default theme for all Select2
                // widgets.
                //
                // @see https://github.com/select2/select2/issues/2927
                $.fn.select2.defaults.set("theme", "bootstrap");
                $.fn.modal.Constructor.prototype.enforceFocus = function() {};

                var placeholder = "Select a State";

                $(".select2, .select2-multiple").select2({
                    placeholder: placeholder,
                    width: null
                });

                $(".select2-allow-clear").select2({
                    allowClear: true,
                    placeholder: placeholder,
                    width: null
                });

                function formatUserData(repo) {
                    if (repo.loading) return repo.text;

                    var markup = "<div class='select2-result-repository clearfix' >" +
                            "<div class='select2-result-repository__avatar'><img src='" + repo.avatar_image + "' /></div>" +
                            "<div class='select2-result-repository__meta'>" +
                            "<div class='select2-result-repository__title'>" + repo.first_name + " " + repo.middle_name + " " + repo.last_name + "</div> ";

                    if (repo.description) {
                        //markup += "<div class='select2-result-repository__description'>" + repo.description + "</div>";
                    }

                    markup += "<div class='select2-result-repository__statistics'>";
                    if (repo.email) {
                        markup += " <div class='select2-result-repository__forks'><span class='fa fa-envelope-square'></span> " + repo.email + "</div> ";
                    }
                    if (repo.phone) {
                        markup += " <div class='select2-result-repository__forks'><span class='fa fa-phone-square'></span> " + repo.phone + "</div> ";
                    }
                    markup += '<br />';

                    if (repo.city || repo.region) {
                        markup += "<div class='select2-result-repository__stargazers'><span class='fa fa-map-o'></span> " + repo.city + ", " + repo.region + "</div>";
                    }

                    markup += "</div>" +
                            "</div></div>";

                    return markup;
                }

                function formatUserDataSelection(repo) {
                    // we add product price to the form
                    //$('input[name=inventory_list_price]').val(repo.list_price);
                    //$('input[name=inventory_entry_price]').val(repo.entry_price);
                    //$('.price_currency').html(repo.currency);

                    if (repo.first_name==null && repo.first_name==null && repo.first_name==null){
                        var full_name = null;
                    }
                    else{
                        var full_name = repo.first_name + " " + repo.middle_name + " " + repo.last_name;
                        location.href = repo.user_link_details;
                    }

                    return full_name || repo.text;
                }

                $(".js-data-users-ajax").select2({
                    width: "off",
                    ajax: {
                        url: "{{ route('admin/users/ajax_get_users') }}",
                        type: "post",
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                q: params.term, // search term
                                page: params.page
                            };
                        },
                        processResults: function(data, page) {
                            // parse the results into the format expected by Select2.
                            // since we are using custom formatting functions we do not need to
                            // alter the remote JSON data
                            return {
                                results: data.items
                            };
                        },
                        cache: true
                    },
                    escapeMarkup: function(markup) {
                        return markup;
                    }, // let our custom formatter work
                    minimumInputLength: 1,
                    templateResult: formatUserData,
                    templateSelection: formatUserDataSelection
                });

                $("button[data-select2-open]").click(function() {
                    $("#" + $(this).data("select2-open")).select2("open");
                });

                $(":checkbox").on("click", function() {
                    $(this).parent().nextAll("select").prop("disabled", !this.checked);
                });

                // copy Bootstrap validation states to Select2 dropdown
                //
                // add .has-waring, .has-error, .has-succes to the Select2 dropdown
                // (was #select2-drop in Select2 v3.x, in Select2 v4 can be selected via
                // body > .select2-container) if _any_ of the opened Select2's parents
                // has one of these forementioned classes (YUCK! ;-))
                $(".select2, .select2-multiple, .select2-allow-clear, .js-data-example-ajax, .js-data-users-ajax").on("select2:open", function() {
                    if ($(this).parents("[class*='has-']").length) {
                        var classNames = $(this).parents("[class*='has-']")[0].className.split(/\s+/);

                        for (var i = 0; i < classNames.length; ++i) {
                            if (classNames[i].match("has-")) {
                                $("body > .select2-container").addClass(classNames[i]);
                            }
                        }
                    }
                });

                $(".js-btn-set-scaling-classes").on("click", function() {
                    $("#select2-multiple-input-sm, #select2-single-input-sm").next(".select2-container--bootstrap").addClass("input-sm");
                    $("#select2-multiple-input-lg, #select2-single-input-lg").next(".select2-container--bootstrap").addClass("input-lg");
                    $(this).removeClass("btn-primary btn-outline").prop("disabled", true);
                });
            }

            return {
                //main function to initiate the module
                init: function() {
                    handleDemo();
                }
            };
        }();
        jQuery(document).ready(function () {
            // initialize select2 drop downs
            UserTopAjaxSearch.init();
        });

        function booking_calendar_view_redirect(selected_date){
            var calendar_book = "{{route('bookings/location_calendar_day_view',['day'=>'##day##'])}}";
            the_link = calendar_book.replace('##day##', $('#calendar_booking_top_menu').data('datepicker').getFormattedDate('dd-mm-yyyy'));
            window.location.href = the_link;
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
        /* Stop - All admin scripts */
    </script>
@endsection