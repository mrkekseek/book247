@extends('admin.layouts.main')

@section('pageLevelPlugins')

@endsection

@section('themeGlobalStyle')
    <link href="{{ asset('assets/global/css/components-rounded.min.css') }}" rel="stylesheet" id="style_components" type="text/css" />
    <link href="{{ asset('assets/global/css/plugins.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/jquery-notific8/jquery.notific8.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('themeLayoutStyle')
    <link href="{{ asset('assets/layouts/layout4/css/layout.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/layouts/layout4/css/themes/light.min.css') }}" rel="stylesheet" type="text/css" id="style_color" />
    <link href="{{ asset('assets/layouts/layout4/css/custom.min.css') }}" rel="stylesheet" type="text/css" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('title', 'Back-end shops - View Shop Details')
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
            <div class="col-md-6 ">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-settings font-dark"></i>
                            <span class="caption-subject font-dark sbold uppercase">Shop/Store Details</span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <form class="form-horizontal" role="form" name="store_details" id="store_details">
                            <div class="form-body">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Shop Name</label>
                                    <div class="col-md-9">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-envelope"></i>
                                            </span>
                                            <input type="text" name="shop_name" class="form-control" placeholder="Shop Name" value="{{ $shopDetails->name }}" /> </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Phone Number</label>
                                    <div class="col-md-9">
                                        <input type="text" name="shop_phone" value="{{ $shopDetails->phone }}" class="form-control input-inline input-medium" placeholder="Phone Number">
                                        <span class="help-inline"> land line </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Fax Number</label>
                                    <div class="col-md-9">
                                        <input type="text" name="shop_fax" value="{{ $shopDetails->fax }}" class="form-control input-inline input-medium" placeholder="Fax Number">
                                        <span class="help-inline"> land line </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Contact Email</label>
                                    <div class="col-md-9">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-envelope"></i>
                                            </span>
                                        <input type="email" name="shop_email" value="{{ $shopDetails->email }}" class="form-control" placeholder="Email Address"> </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Visibility</label>
                                    <div class="col-md-9">
                                        <select name="visibility" class="form-control">
                                            <option {!! $shopDetails->visibility=="pending"?"selected":"" !!} value="pending">Pending</option>
                                            <option {!! $shopDetails->visibility=="public"?"selected":"" !!} value="public">Public</option>
                                            <option {!! $shopDetails->visibility=="warehouse"?"selected":"" !!} value="warehouse">Warehouse</option>
                                            <option {!! $shopDetails->visibility=="suspended"?"selected":"" !!} value="suspended">Suspended</option>
                                        </select>
                                        <span class="help-block"> Only public visibility will be shown in calendar dropdown </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" class="btn green">Update Store Details</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6 ">
                <!-- BEGIN SAMPLE FORM PORTLET-->
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-settings font-dark"></i>
                            <span class="caption-subject font-dark sbold uppercase">Shop/Store Address</span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <form class="form-horizontal" role="form" name="store_address" id="store_address">
                            <div class="form-body">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Address Line1</label>
                                    <div class="col-md-9">
                                        <input type="text" name="shop_address1" value="{{ $shopAddress->address1 }}" class="form-control" placeholder="Enter text">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Address Line2</label>
                                    <div class="col-md-9">
                                        <input type="text" name="shop_address2" value="{{ $shopAddress->address2 }}" class="form-control" placeholder="Enter text">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">City</label>
                                    <div class="col-md-9">
                                        <input type="text" name="shop_city" value="{{ $shopAddress->city }}" class="form-control input-inline input-medium" placeholder="Enter text">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Postal Code</label>
                                    <div class="col-md-9">
                                        <input type="text" name="shop_postal_code" value="{{ $shopAddress->postal_code }}" class="form-control input-inline input-medium" placeholder="Enter text">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Region</label>
                                    <div class="col-md-9">
                                        <input type="text" name="shop_region" value="{{ $shopAddress->region }}" class="form-control input-inline input-medium" placeholder="Enter text">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Country</label>
                                    <div class="col-md-9">
                                        <input type="text" name="shop_country" value="{{ $shopAddress->countryName }}" class="form-control" readonly placeholder="Enter text">
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" class="btn green">Update Store Address</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- END SAMPLE FORM PORTLET-->
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="portlet box red border-grey-silver">
                    <div class="portlet-title bg-grey-silver bg-font-grey-silver">
                        <div class="caption">
                            <i class="fa fa-cogs"></i>Shop/Store Activities Interval Duration </div>
                        <div class="tools">
                            <a class="collapse" href="javascript:;" data-original-title="" title=""> </a>
                        </div>
                        <div class="actions">
                            <a class="btn green-jungle" data-toggle="modal" href="#addCategoryTime">
                                <i class="fa fa-plus"></i> Add Activity Booking Time </a>
                        </div>
                    </div>
                    <div class="portlet-body flip-scroll">
                        @if (sizeof($storeCategories)>0)
                            <table class="table table-bordered table-striped table-condensed flip-content">
                                <tbody>
                                <tr>
                                    <th>Available Activity to Location</th>
                                    <th>Location time interval </th>
                                </tr>
                            @foreach($storeCategories as $key=>$single)
                                <tr>
                                    <td> &nbsp;{{$single}} </td>
                                    <td> <select name="option_value" class="form-control input-inline input-medium input-sm" aria-invalid="false">
                                            <option value="-1" {!! 1==-1?'selected="selected"':'' !!}>Default</option>
                                            @for($i=5; $i<=120; $i++)
                                                <option value="{{$i}}" {!! $i==$key?'selected':'' !!}> {{$i}} minutes </option>
                                            @endfor
                                        </select>
                                        <input type="hidden" name="option_key" value="shop_finance_profile" />
                                        <a class="btn blue btn-sm update_system_option" >Update</a>
                                    </td>
                                </tr>
                            @endforeach
                                </tbody>
                            </table>
                        @else

                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="portlet box red border-grey-silver">
                    <div class="portlet-title bg-grey-silver bg-font-grey-silver">
                        <div class="caption">
                            <i class="fa fa-cogs"></i>Shop/Store System Options </div>
                        <div class="tools">
                            <a class="collapse" href="javascript:;" data-original-title="" title=""> </a>
                        </div>
                    </div>
                    <div class="portlet-body flip-scroll">
                        <table class="table table-bordered table-striped table-condensed flip-content">
                            <tbody>
                            <tr>
                                <td> &nbsp; <b>Location financial profile</b> </td>
                                <td> <select name="option_value" class="form-control input-inline input-medium input-sm" aria-invalid="false">
                                        <option value="-1" {!! $shopFinancialProfile==-1?'selected="selected"':'' !!}>Default</option>
                                        @foreach ($financialProfiles as $singleProfile)
                                            <option value="{{ $singleProfile->id }}" {!! $shopFinancialProfile==$singleProfile->id?'selected':'' !!}> {{ $singleProfile->profile_name }} </option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="option_key" value="shop_finance_profile" />
                                    <a class="btn blue btn-sm update_system_option" >Update</a> </td>
                            </tr>
                            <tr>
                                <td> &nbsp; <b>Automatic Booking marked as Show</b> </td>
                                <td> <select name="option_value" class="form-control input-inline input-medium input-sm" aria-invalid="false">
                                        <option value="-1" {!! @$system_options['automatic_bookings_mark_as_show']==-1?'selected="selected"':'' !!}>Default</option>
                                        <option value="1" {!! @$system_options['automatic_bookings_mark_as_show']==1?'selected="selected"':'' !!}>Yes</option>
                                        <option value="0" {!! @$system_options['automatic_bookings_mark_as_show']==0?'selected="selected"':'' !!}>No</option>
                                    </select>
                                    <input type="hidden" name="option_key" value="automatic_bookings_mark_as_show" />
                                    <a class="btn blue btn-sm update_system_option" >Update</a> </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="portlet box red border-grey-silver">
                    <div class="portlet-title bg-grey-silver bg-font-grey-silver">
                        <div class="caption">
                            <i class="fa fa-cogs"></i>Shop Resources </div>
                        <div class="tools">
                            <a class="collapse" href="javascript:;" data-original-title="" title=""> </a>
                        </div>
                        <div class="actions">
                            <a class="btn green-jungle" data-toggle="modal" href="#draggable">
                                <i class="fa fa-plus"></i> Add Resource </a>
                        </div>
                    </div>
                    <div class="portlet-body flip-scroll">
                        <table class="table table-bordered table-striped table-condensed flip-content">
                            <thead class="flip-content">
                            <tr>
                                <th width="5%"> No. </th>
                                <th> Name </th>
                                <th class="numeric"> Category </th>
                                <th class="numeric" style="width:190px;"> Options </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($resourceList as $key=>$resource)
                            <tr>
                                <td> {{$key+1}} </td>
                                <td> <b>{{ $resource->name }}</b> </td>
                                <td class="numeric"> {{ $resource->category->name }} </td>
                                <td class="numeric"> <a class="btn blue" href="{{ route('admin/shops/resources/view', ['id'=>$resource->id]) }}">Edit/Show</a>
                                    <button class="btn red delete_shop_resource" data-id="{{$resource->id}}">Delete</button> </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN PORTLET-->
                <div class="portlet box red border-green-sharp">
                    <div class="portlet-title bg-green-sharp bg-font-green-sharp">
                        <div class="caption">
                            <i class="fa fa-gift"></i>Opening Hours </div>
                        <div class="actions">
                            <a class="btn green-jungle" data-toggle="modal" href="#add_opening_hours_modal">
                                <i class="fa fa-plus"></i> Add Opening Hours </a>
                        </div>
                    </div>
                    <div class="portlet-body flip-scroll">
                        @if (sizeof($opening_hours)>0)
                            <table class="table table-bordered table-striped table-condensed flip-content">
                                <thead class="flip-content">
                                <tr>
                                    <th width="5%"> No. </th>
                                    <th> Week Days </th>
                                    <th class="numeric"> Time Interval </th>
                                    <th class="numeric"> Date Restrictions </th>
                                    <th class="numeric"> Type </th>
                                    <th class="numeric" style="width:130px;"> </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($opening_hours as $key=>$hours)
                                    <tr>
                                        <td> {{$key+1}} </td>
                                        <td> {{$hours['days']}} </td>
                                        <td> {{$hours['time_interval']}} </td>
                                        <td> {{$hours['date_interval']}} </td>
                                        <td class="numeric"> {{$hours['type']}} </td>
                                        <td class="numeric"> <span class="btn blue btn-sm" onclick="javascript:get_opening_hour({{$hours['id']}})">Edit</span>
                                            <span class="btn red btn-sm" onclick="javascript:delete_opening_hours('{{ $hours['id'] }}','{{ $shopDetails->id }}')">Delete</span> </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="note note-warning">
                                <h4 class="block">You have no opening hours defined for this location</h4>
                                <p> Use the "Add Opening Hours" button to define open hours for this location. After you set up the opening hours for different days of week and/or hour intervals
                                    you can also add special opening hours for specific calendar day or days interval.</p>
                            </div>
                        @endif
                    </div>
                </div>
                <!-- END PORTLET-->
            </div>
        </div>

        @if (isset($more_on_shop_location)))
        <div class="row">
            <div class="col-md-12">
                <div class="portlet box green">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-cogs"></i>Cash flow log </div>
                        <div class="tools">
                            <a class="expand" href="javascript:;" data-original-title="" title=""> </a>
                            <a class="reload" href="javascript:;" data-original-title="" title=""> </a>
                            <a class="remove" href="javascript:;" data-original-title="" title=""> </a>
                        </div>
                    </div>
                    <div class="portlet-body flip-scroll" style="display: none;">
                        <table class="table table-bordered table-striped table-condensed flip-content">
                            <thead class="flip-content">
                            <tr>
                                <th width="20%"> Code </th>
                                <th> Company </th>
                                <th class="numeric"> Price </th>
                                <th class="numeric"> Change </th>
                                <th class="numeric"> Change % </th>
                                <th class="numeric"> Open </th>
                                <th class="numeric"> High </th>
                                <th class="numeric"> Low </th>
                                <th class="numeric"> Volume </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td> AAC </td>
                                <td> AUSTRALIAN AGRICULTURAL COMPANY LIMITED. </td>
                                <td class="numeric"> &nbsp; </td>
                                <td class="numeric"> -0.01 </td>
                                <td class="numeric"> -0.36% </td>
                                <td class="numeric"> $1.39 </td>
                                <td class="numeric"> $1.39 </td>
                                <td class="numeric"> &nbsp; </td>
                                <td class="numeric"> 9,395 </td>
                            </tr>
                            <tr>
                                <td> AAD </td>
                                <td> ARDENT LEISURE GROUP </td>
                                <td class="numeric"> $1.15 </td>
                                <td class="numeric"> +0.02 </td>
                                <td class="numeric"> 1.32% </td>
                                <td class="numeric"> $1.14 </td>
                                <td class="numeric"> $1.15 </td>
                                <td class="numeric"> $1.13 </td>
                                <td class="numeric"> 56,431 </td>
                            </tr>
                            <tr>
                                <td> AGO </td>
                                <td> ATLAS IRON LIMITED </td>
                                <td class="numeric"> $3.17 </td>
                                <td class="numeric"> -0.02 </td>
                                <td class="numeric"> -0.47% </td>
                                <td class="numeric"> $3.11 </td>
                                <td class="numeric"> $3.22 </td>
                                <td class="numeric"> $3.10 </td>
                                <td class="numeric"> 5,416,303 </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="portlet box green">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-cogs"></i>Incoming Products </div>
                        <div class="tools">
                            <a class="expand" href="javascript:;" data-original-title="" title=""> </a>
                            <a class="reload" href="javascript:;" data-original-title="" title=""> </a>
                            <a class="remove" href="javascript:;" data-original-title="" title=""> </a>
                        </div>
                    </div>
                    <div class="portlet-body flip-scroll" style="display: none;">
                        <table class="table table-bordered table-striped table-condensed flip-content">
                            <thead class="flip-content">
                            <tr>
                                <th width="20%"> Code </th>
                                <th> Company </th>
                                <th class="numeric"> Price </th>
                                <th class="numeric"> Change </th>
                                <th class="numeric"> Change % </th>
                                <th class="numeric"> Open </th>
                                <th class="numeric"> High </th>
                                <th class="numeric"> Low </th>
                                <th class="numeric"> Volume </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td> AAC </td>
                                <td> AUSTRALIAN AGRICULTURAL COMPANY LIMITED. </td>
                                <td class="numeric"> &nbsp; </td>
                                <td class="numeric"> -0.01 </td>
                                <td class="numeric"> -0.36% </td>
                                <td class="numeric"> $1.39 </td>
                                <td class="numeric"> $1.39 </td>
                                <td class="numeric"> &nbsp; </td>
                                <td class="numeric"> 9,395 </td>
                            </tr>
                            <tr>
                                <td> AAD </td>
                                <td> ARDENT LEISURE GROUP </td>
                                <td class="numeric"> $1.15 </td>
                                <td class="numeric"> +0.02 </td>
                                <td class="numeric"> 1.32% </td>
                                <td class="numeric"> $1.14 </td>
                                <td class="numeric"> $1.15 </td>
                                <td class="numeric"> $1.13 </td>
                                <td class="numeric"> 56,431 </td>
                            </tr>
                            <tr>
                                <td> AGO </td>
                                <td> ATLAS IRON LIMITED </td>
                                <td class="numeric"> $3.17 </td>
                                <td class="numeric"> -0.02 </td>
                                <td class="numeric"> -0.47% </td>
                                <td class="numeric"> $3.11 </td>
                                <td class="numeric"> $3.22 </td>
                                <td class="numeric"> $3.10 </td>
                                <td class="numeric"> 5,416,303 </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="portlet box green">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-cogs"></i>Outgoing Products </div>
                        <div class="tools">
                            <a class="expand" href="javascript:;" data-original-title="" title=""> </a>
                            <a class="reload" href="javascript:;" data-original-title="" title=""> </a>
                            <a class="remove" href="javascript:;" data-original-title="" title=""> </a>
                        </div>
                    </div>
                    <div class="portlet-body flip-scroll" style="display: none;">
                        <table class="table table-bordered table-striped table-condensed flip-content">
                            <thead class="flip-content">
                            <tr>
                                <th width="20%"> Code </th>
                                <th> Company </th>
                                <th class="numeric"> Price </th>
                                <th class="numeric"> Change </th>
                                <th class="numeric"> Change % </th>
                                <th class="numeric"> Open </th>
                                <th class="numeric"> High </th>
                                <th class="numeric"> Low </th>
                                <th class="numeric"> Volume </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td> AAC </td>
                                <td> AUSTRALIAN AGRICULTURAL COMPANY LIMITED. </td>
                                <td class="numeric"> &nbsp; </td>
                                <td class="numeric"> -0.01 </td>
                                <td class="numeric"> -0.36% </td>
                                <td class="numeric"> $1.39 </td>
                                <td class="numeric"> $1.39 </td>
                                <td class="numeric"> &nbsp; </td>
                                <td class="numeric"> 9,395 </td>
                            </tr>
                            <tr>
                                <td> AAD </td>
                                <td> ARDENT LEISURE GROUP </td>
                                <td class="numeric"> $1.15 </td>
                                <td class="numeric"> +0.02 </td>
                                <td class="numeric"> 1.32% </td>
                                <td class="numeric"> $1.14 </td>
                                <td class="numeric"> $1.15 </td>
                                <td class="numeric"> $1.13 </td>
                                <td class="numeric"> 56,431 </td>
                            </tr>
                            <tr>
                                <td> AGO </td>
                                <td> ATLAS IRON LIMITED </td>
                                <td class="numeric"> $3.17 </td>
                                <td class="numeric"> -0.02 </td>
                                <td class="numeric"> -0.47% </td>
                                <td class="numeric"> $3.11 </td>
                                <td class="numeric"> $3.22 </td>
                                <td class="numeric"> $3.10 </td>
                                <td class="numeric"> 5,416,303 </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="portlet box green">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-cogs"></i>Current Inventory </div>
                        <div class="tools">
                            <a class="expand" href="javascript:;" data-original-title="" title=""> </a>
                            <a class="reload" href="javascript:;" data-original-title="" title=""> </a>
                            <a class="remove" href="javascript:;" data-original-title="" title=""> </a>
                        </div>
                    </div>
                    <div class="portlet-body flip-scroll" style="display: none;">
                        <table class="table table-bordered table-striped table-condensed flip-content">
                            <thead class="flip-content">
                            <tr>
                                <th width="20%"> Code </th>
                                <th> Company </th>
                                <th class="numeric"> Price </th>
                                <th class="numeric"> Change </th>
                                <th class="numeric"> Change % </th>
                                <th class="numeric"> Open </th>
                                <th class="numeric"> High </th>
                                <th class="numeric"> Low </th>
                                <th class="numeric"> Volume </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td> AAC </td>
                                <td> AUSTRALIAN AGRICULTURAL COMPANY LIMITED. </td>
                                <td class="numeric"> &nbsp; </td>
                                <td class="numeric"> -0.01 </td>
                                <td class="numeric"> -0.36% </td>
                                <td class="numeric"> $1.39 </td>
                                <td class="numeric"> $1.39 </td>
                                <td class="numeric"> &nbsp; </td>
                                <td class="numeric"> 9,395 </td>
                            </tr>
                            <tr>
                                <td> AAD </td>
                                <td> ARDENT LEISURE GROUP </td>
                                <td class="numeric"> $1.15 </td>
                                <td class="numeric"> +0.02 </td>
                                <td class="numeric"> 1.32% </td>
                                <td class="numeric"> $1.14 </td>
                                <td class="numeric"> $1.15 </td>
                                <td class="numeric"> $1.13 </td>
                                <td class="numeric"> 56,431 </td>
                            </tr>
                            <tr>
                                <td> AGO </td>
                                <td> ATLAS IRON LIMITED </td>
                                <td class="numeric"> $3.17 </td>
                                <td class="numeric"> -0.02 </td>
                                <td class="numeric"> -0.47% </td>
                                <td class="numeric"> $3.11 </td>
                                <td class="numeric"> $3.22 </td>
                                <td class="numeric"> $3.10 </td>
                                <td class="numeric"> 5,416,303 </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <!-- END PAGE BASE CONTENT -->

        <!-- BEGIN Add new shop resource -->
        <div class="modal fade draggable-modal" id="draggable" tabindex="-1" role="basic" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <h4 class="modal-title">Add New Shop Resource</h4>
                    </div>
                    <div class="modal-body">
                        <form action="#" id="new_resource" name="new_resource" class="form-horizontal">
                            <div class="form-body">
                                <div class="alert alert-danger display-hide">
                                    <button class="close" data-close="alert"></button> You have some form errors. Please check below. </div>
                                <div class="alert alert-success display-hide">
                                    <button class="close" data-close="alert"></button> Your form validation is successful! </div>
                                <div class="form-group  margin-top-20">
                                    <label class="control-label col-md-4">Resource Name
                                        <span class="required"> * </span>
                                    </label>
                                    <div class="col-md-7">
                                        <div class="input-icon right">
                                            <i class="fa"></i>
                                            <input type="text" class="form-control input-sm" name="resource_name" id="resource_name" /> </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4">Shop Location</label>
                                    <div class="col-md-7">
                                        <div class="input-icon right">
                                            <i class="fa"></i>
                                            <input type="text" class="form-control input-sm" id="resource_location" name="resource_location" readonly disabled value="{{ $shopDetails->name }}" /> </div>
                                            <input type="hidden" id="resource_location_id" name="resource_location_id" readonly disabled value="{{ $shopDetails->id }}" /> </div>
                                    </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4">Category</label>
                                    <div class="col-md-7">
                                        <select class="form-control input-sm" name="resource_category" id="resource_category">
                                            <option value="-1">Select Category...</option>
                                            @foreach($storeCategories as $key=>$single)
                                                <option value="{{ $key }}">{{ $single }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4">Description
                                    </label>
                                    <div class="col-md-7">
                                        <div class="input-icon right">
                                            <i class="fa"></i>
                                            <textarea class="form-control input-sm" id="resource_description" name="resource_description"></textarea> </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4">Default/Base Price</label>
                                    <div class="col-md-7">
                                        <div class="input-icon right">
                                            <i class="fa"></i>
                                            <input type="text" class="form-control input-sm" id="resource_price" name="resource_price" value="0" /> </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4">Vat Rate</label>
                                    <div class="col-md-7">
                                        <select name="resource_vat_rate" class="form-control input-large">
                                            <option>Select VAT</option>
                                            @foreach ($vatRates as $vat)
                                                <option value="{{ $vat->id }}">{{ $vat->display_name }}</option>
                                            @endforeach
                                        </select>
                                        <span class="help-block"> Invoice VAT applied on the booking price </span>
                                    </div>
                                </div>
                                <!--<div class="form-group">
                                    <label class="control-label col-md-4">Color Code</label>
                                    <div class="col-md-7">
                                        <div class="input-icon right">
                                            <i class="fa"></i>
                                            <input type="text" class="form-control input-sm" id="resource_color" name="resource_color" /> </div>
                                    </div>
                                </div>-->
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                        <button type="button" class="btn green submit_form_2" onclick="javascript: $('#new_resource').submit();">Save changes</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- END Add new shop resource -->

        <!-- BEGIN Add new shop resource -->
        <div class="modal fade draggable-modal" id="addCategoryTime" tabindex="-1" role="basic" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <h4 class="modal-title">Add an activity to this location</h4>
                    </div>
                    <div class="modal-body">
                        <form action="#" id="new_location_activity" name="new_location_activity" class="form-horizontal">
                            <div class="form-body">
                                <div class="alert alert-danger display-hide">
                                    <button class="close" data-close="alert"></button> You have some form errors. Please check below. </div>
                                <div class="alert alert-success display-hide">
                                    <button class="close" data-close="alert"></button> Your form validation is successful! </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4">Activity</label>
                                    <div class="col-md-7">
                                        <select class="form-control input-sm" name="location_resource_category" id="location_resource_category">
                                            <option>Select Activity...</option>
                                            @foreach ($resourceCategory as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4">Booking Slot Time</label>
                                    <div class="col-md-7">
                                        <select name="resource_time_slot" class="form-control input-sm">
                                            <option>Select time in minutes</option>
                                            @for($i=5; $i<=120; $i++)
                                                <option value="{{$i}}" {!! $i==$key?'selected':'' !!}> {{$i}} minutes </option>
                                            @endfor
                                        </select>
                                        <span class="help-block"> time per single reservation for this activity </span>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                        <button type="button" class="btn green submit_form_2" onclick="javascript: $('#new_location_activity').submit();">Add</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- END Add new shop resource -->

        <!-- BEGIN Delete shop resource -->
        <div class="modal fade bs-modal-sm" id="delete_resource_modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <h4 class="modal-title">Delete this resource?</h4>
                    </div>
                    <div class="modal-body"> By clicking "Yes, Delete" the resource will be removed from this location. Do you want to delete it? </div>
                    <div class="modal-footer">
                        <button type="button" class="btn dark btn-outline" data-dismiss="modal">No, Go Back</button>
                        <button type="button" class="btn green" onclick="javascript:delete_resource();">Yes, Delete</button>
                        <input type="hidden" name="res_id" value="" />
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- END Delete shop resource -->

        <!-- BEGIN Add opening hours to shop -->
        <div class="modal fade draggable-modal" id="add_opening_hours_modal" tabindex="-1" role="basic" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <form action="#" id="new_opening_hours" name="new_opening_hours" class="form-horizontal">
                            <div class="form-body">
                                <div class="alert alert-danger display-hide">
                                    <button class="close" data-close="alert"></button> You have some form errors. Please check below. </div>
                                <div class="alert alert-success display-hide">
                                    <button class="close" data-close="alert"></button> Your form validation is successful! </div>
                                <div class="form-group  margin-top-20">
                                    <label class="control-label col-md-4">Week days
                                        <span class="required"> * </span>
                                    </label>
                                    <div class="col-md-7">
                                        <div class="input-icon right">
                                            <i class="fa"></i>
                                            <select name="week_days" class="form-control input-sm input-medium" multiple="" style="height:130px;">
                                                <option value="1">Monday</option>
                                                <option value="2">Tuesday</option>
                                                <option value="3">Wednesday</option>
                                                <option value="4">Thursday</option>
                                                <option value="5">Friday</option>
                                                <option value="6">Saturday</option>
                                                <option value="0">Sunday</option>
                                            </select> </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4">Time Interval
                                        <span class="required"> * </span>
                                    </label>
                                    <div class="col-md-7">
                                        <div class="input-icon right">
                                            <i class="fa"></i>
                                            <input type="text" name="time_start" class="form-control input-xsmall input-sm input-inline" value="00:00" /> to
                                            <input type="text" name="time_stop" class="form-control input-xsmall input-sm input-inline" value="23:59" />
                                            <h6 class="help-block"> Use a valid hh:mm time value between 00:00 and 23:59 </h6>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4">Type
                                        <span class="required"> * </span>
                                    </label>
                                    <div class="col-md-7">
                                        <div class="input-icon right">
                                            <i class="fa"></i>
                                            <select class="form-control input-sm input-medium" name="hours_type" id="hours_type">
                                                <option value="open_hours">Open Hours</option>
                                                <option value="close_hours">Closed Hours</option>
                                            </select>
                                            <h6 class="help-block"> Specific Type requires the from/to dates to be entered </h6> </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4">Date Interval</label>
                                    <div class="col-md-8">
                                        <div class="input-group input-large date-picker input-daterange" data-date="10-11-2012" data-date-format="dd-mm-yyyy">
                                            <input type="text" class="form-control input-sm" name="from_date" id="from_date">
                                            <span class="input-group-addon"> to </span>
                                            <input type="text" class="form-control input-sm" name="to_date" id="to_date"> </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                        <button type="button" class="btn green submit_form_2" onclick="javascript: $('#new_opening_hours').submit();">Save changes</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- END Add opening hours to shop -->

        <!-- BEGIN Update opening hours -->
        <div class="modal fade draggable-modal" id="update_opening_hours_modal" tabindex="-1" role="basic" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <form action="#" id="update_opening_hours" name="update_opening_hours" class="form-horizontal">
                            <div class="form-body">
                                <div class="alert alert-danger display-hide">
                                    <button class="close" data-close="alert"></button> You have some form errors. Please check below. </div>
                                <div class="alert alert-success display-hide">
                                    <button class="close" data-close="alert"></button> Your form validation is successful! </div>
                                <div class="form-group  margin-top-20">
                                    <label class="control-label col-md-4">Week days
                                        <span class="required"> * </span>
                                    </label>
                                    <div class="col-md-7">
                                        <div class="input-icon right">
                                            <i class="fa"></i>
                                            <select name="update_week_days" class="form-control input-sm input-medium" multiple="" style="height:130px;">
                                                <option value="1">Monday</option>
                                                <option value="2">Tuesday</option>
                                                <option value="3">Wednesday</option>
                                                <option value="4">Thursday</option>
                                                <option value="5">Friday</option>
                                                <option value="6">Saturday</option>
                                                <option value="0">Sunday</option>
                                            </select> </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4">Time Interval
                                        <span class="required"> * </span>
                                    </label>
                                    <div class="col-md-7">
                                        <div class="input-icon right">
                                            <i class="fa"></i>
                                            <input type="text" name="update_time_start" class="form-control input-xsmall input-sm input-inline" value="" /> to
                                            <input type="text" name="update_time_stop" class="form-control input-xsmall input-sm input-inline" value="" />
                                            <h6 class="help-block"> Use a valid hh:mm time value between 00:00 and 23:59 </h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4">Type
                                        <span class="required"> * </span>
                                    </label>
                                    <div class="col-md-7">
                                        <div class="input-icon right">
                                            <i class="fa"></i>
                                            <select class="form-control input-sm input-medium" name="update_price_type">
                                                <option value="open_hours">Open Hours</option>
                                                <option value="close_hours">Closed Hours</option>
                                            </select>
                                            <h6 class="help-block"> Specific Type requires the from/to dates to be entered </h6> </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4">Date Interval</label>
                                    <div class="col-md-8">
                                        <div class="input-group input-large date-picker input-daterange" data-date="10-11-2012" data-date-format="dd-mm-yyyy" id="edit_update_daterange">
                                            <input type="text" class="form-control input-sm" name="update_from_date">
                                            <span class="input-group-addon"> to </span>
                                            <input type="text" class="form-control input-sm" name="update_to_date"> </div>
                                    </div>
                                </div>
                                <input type="hidden" value="" name="open_hour_id" />
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                        <button type="button" class="btn green submit_form_2" onclick="javascript: $('#update_opening_hours').submit();">Save changes</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- END Update opening hours -->

        <!-- BEGIN Delete opening hour -->
        <div class="modal fade bs-modal-sm" id="delete_opening_hours_modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <h4 class="modal-title">Delete this "Opening Hours" entry?</h4>
                    </div>
                    <div class="modal-body"> By clicking "Yes, Delete" the open hour attribute will be removed from this location. Do you want to delete it? </div>
                    <div class="modal-footer">
                        <button type="button" class="btn dark btn-outline" data-dismiss="modal">No, Go Back</button>
                        <button type="button" class="btn green" onclick="javascript:delete_hours();">Yes, Delete</button>
                        <input type="hidden" name="del_open_hour_id" value="" />
                        <input type="hidden" name="del_location_id" value="" />
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- END Delete opening hour -->
    </div>
@endsection

@section('pageBelowLevelPlugins')
    <script src="{{ asset('assets/global/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript"></script>
    <!--<script src="{{ asset('assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}" type="text/javascript"></script>-->
    <script src="{{ asset('assets/global/plugins/moment.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js') }}" type="text/javascript"></script>
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

            var handleValidation1 = function() {
                var form1 = $('#new_resource');
                var error1 = $('.alert-danger', form1);
                var success1 = $('.alert-success', form1);

                form1.validate({
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block help-block-error', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    ignore: "",  // validate all fields including form hidden input
                    rules: {
                        resource_name: {
                            minlength: 3,
                            required: true
                        },
                        resource_location_id: {
                            min:1,
                            required: true
                        },
                        resource_category: {
                            min:1,
                            required: true,
                        },
                        resource_color: {
                            minlength: 6,
                            maxlength: 7,
                        },
                        resource_description: {
                            minlength: 15,
                        },
                        resource_price: {
                            min:1,
                            number:true,
                            required:true
                        },
                        resource_vat_rate: {
                            minlength:1,
                            number:true,
                            required:true
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
                        add_new_resource(); // submit the form
                    }
                });
            }

            var handleValidation2 = function() {
                var form2 = $('#opening_hours');
                var error2 = $('.alert-danger', form2);
                var success2 = $('.alert-success', form2);

                form2.validate({
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block help-block-error', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    ignore: "",  // validate all fields including form hidden input
                    rules: {
                        shop_name: {
                            minlength: 3,
                            required: true
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
                        update_work_hours(); // submit the form
                    }
                });
            }

            var handleValidation3 = function() {
                var form3 = $('#store_details');
                var error3 = $('.alert-danger', form3);
                var success3 = $('.alert-success', form3);

                form3.validate({
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block help-block-error', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    ignore: "",  // validate all fields including form hidden input
                    rules: {
                        shop_name: {
                            minlength: 3,
                            required: true
                        },
                        shop_phone: {
                            minlength: 5,
                            required: true
                        },
                        shop_fax: {
                            minlength: 5,
                            required: true
                        },
                        shop_email: {
                            email: true,
                            validate_email: true,
                            required: true
                        },
                        shop_bank_acc_no: {
                            minlength: 5,
                            required: true
                        },
                        shop_registration_no: {
                            minlength: 5,
                            required: true,
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
                        update_store_details(); // submit the form
                    }
                });
            }

            var handleValidation4 = function() {
                var form4 = $('#store_address');
                var error4 = $('.alert-danger', form4);
                var success4 = $('.alert-success', form4);

                form4.validate({
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block help-block-error', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    ignore: "",  // validate all fields including form hidden input
                    rules: {
                        shop_address1: {
                            minlength: 5,
                            required: true
                        },
                        shop_city: {
                            minlength: 3,
                            required: true
                        },
                        shop_region: {
                            minlength:2,
                            required: true
                        },
                        shop_postal_code: {
                            minlength: 2,
                            required: true
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
                        update_store_address(); // submit the form
                    }
                });
            }

            var handleValidation5 = function() {
                var form5 = $('#new_opening_hours');
                var error5 = $('.alert-danger', form5);
                var success5 = $('.alert-success', form5);

                form5.validate({
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block help-block-error', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    ignore: "",  // validate all fields including form hidden input
                    rules: {
                        week_days: {
                            required: true
                        },
                        time_start: {
                            minlength: 5,
                            required: true
                        },
                        time_stop: {
                            minlength: 5,
                            required: true
                        },
                        hours_type: {
                            required: true
                        }
                    },

                    invalidHandler: function (event, validator) { //display error alert on form submit
                        success5.hide();
                        error5.show();
                        App.scrollTo(error5, -200);
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
                        success5.show();
                        error5.hide();
                        add_opening_hours(); // submit the form
                    }
                });
            }

            var handleValidation6 = function() {
                var form6 = $('#update_opening_hours');
                var error6 = $('.alert-danger', form6);
                var success6 = $('.alert-success', form6);

                form6.validate({
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block help-block-error', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    ignore: "",  // validate all fields including form hidden input
                    rules: {
                        update_week_days: {
                            required: true
                        },
                        update_time_start: {
                            minlength: 5,
                            required: true
                        },
                        update_time_stop: {
                            minlength: 5,
                            required: true
                        },
                        update_price_type: {
                            required: true
                        }
                    },

                    invalidHandler: function (event, validator) { //display error alert on form submit
                        success6.hide();
                        error6.show();
                        App.scrollTo(error6, -200);
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
                        success6.show();
                        error6.hide();
                        update_opening_hours(); // submit the form
                    }
                });
            }

            var handleValidation7 = function() {
                var form7 = $('#new_location_activity');
                var error7 = $('.alert-danger', form7);
                var success7 = $('.alert-success', form7);

                form7.validate({
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block help-block-error', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    ignore: "",  // validate all fields including form hidden input
                    rules: {
                        location_resource_category: {
                            min:1,
                            required: true
                        },
                        resource_time_slot: {
                            min:1,
                            required: true,
                        },
                    },

                    invalidHandler: function (event, validator) { //display error alert on form submit
                        success7.hide();
                        error7.show();
                        App.scrollTo(error7, -200);
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
                        success7.show();
                        error7.hide();
                        add_new_resource(); // submit the form
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
                    handleValidation6();
                    handleValidation7();
                }
            };
        }();

        var TableDatatablesManaged = function () {

            var initTable1 = function () {

                var table = $('#all_permissions');

                // begin first table
                table.dataTable({

                    // Internationalisation. For more info refer to http://datatables.net/manual/i18n
                    "language": {
                        "aria": {
                            "sortAscending": ": activate to sort column ascending",
                            "sortDescending": ": activate to sort column descending"
                        },
                        "emptyTable": "No data available in table",
                        "info": "Showing _START_ to _END_ of _TOTAL_ records",
                        "infoEmpty": "No records found",
                        "infoFiltered": "(filtered1 from _MAX_ total records)",
                        "lengthMenu": "Show _MENU_",
                        "search": "Search:",
                        "zeroRecords": "No matching records found",
                        "paginate": {
                            "previous":"Prev",
                            "next": "Next",
                            "last": "Last",
                            "first": "First"
                        }
                    },

                    // Or you can use remote translation file
                    //"language": {
                    //   url: '//cdn.datatables.net/plug-ins/3cfcc339e89/i18n/Portuguese.json'
                    //},

                    // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
                    // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js).
                    // So when dropdowns used the scrollable div should be removed.
                    //"dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r>t<'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",

                    "bStateSave": true, // save datatable state(pagination, sort, etc) in cookie.

                    "columnDefs": [ {
                        "targets": 0,
                        "orderable": false,
                        "searchable": false
                    }],

                    "lengthMenu": [
                        [5, 15, 20, -1],
                        [5, 15, 20, "All"] // change per page values here
                    ],
                    // set the initial value
                    "pageLength": 5,
                    "pagingType": "bootstrap_full_number",
                    "columnDefs": [{  // set default column settings
                        'orderable': false,
                        'targets': [0]
                    }, {
                        "searchable": false,
                        "targets": [0]
                    }],
                    "order": [
                        [1, "asc"]
                    ] // set first column as a default sort by asc
                });

                var tableWrapper = jQuery('#sample_1_wrapper');

                table.find('.group-checkable').change(function () {
                    var set = jQuery(this).attr("data-set");
                    var checked = jQuery(this).is(":checked");
                    jQuery(set).each(function () {
                        if (checked) {
                            $(this).prop("checked", true);
                            $(this).parents('tr').addClass("active");
                        } else {
                            $(this).prop("checked", false);
                            $(this).parents('tr').removeClass("active");
                        }
                    });
                    jQuery.uniform.update(set);
                });

                table.on('change', 'tbody tr .checkboxes', function () {
                    $(this).parents('tr').toggleClass("active");
                });
            }

            return {

                //main function to initiate the module
                init: function () {
                    if (!jQuery().dataTable) {
                        return;
                    }

                    initTable1();
                }

            };

        }();

        var ComponentsDateTimePickers = function () {

            var handleDatePickers = function () {

                if (jQuery().datepicker) {
                    $('.date-picker').datepicker({
                        rtl: App.isRTL(),
                        orientation: "left",
                        autoclose: true,
                        daysOfWeekHighlighted: "0",
                        weekStart:1,
                    });
                    //$('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
                }

                /* Workaround to restrict daterange past date select: http://stackoverflow.com/questions/11933173/how-to-restrict-the-selectable-date-ranges-in-bootstrap-datepicker */
            }

            var handleDateRangePickers = function () {
                if (!jQuery().daterangepicker) {
                    return;
                }

                $('#defaultrange').daterangepicker({
                            opens: (App.isRTL() ? 'left' : 'right'),
                            format: 'MM/DD/YYYY',
                            separator: ' to ',
                            startDate: moment().subtract('days', 29),
                            endDate: moment(),
                            ranges: {
                                'Today': [moment(), moment()],
                                'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
                                'Last 7 Days': [moment().subtract('days', 6), moment()],
                                'Last 30 Days': [moment().subtract('days', 29), moment()],
                                'This Month': [moment().startOf('month'), moment().endOf('month')],
                                'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
                            },
                            minDate: '01/01/2012',
                            maxDate: '12/31/2018',
                        },
                        function (start, end) {
                            $('#defaultrange input').val(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                        }
                );

                $('#defaultrange_modal').daterangepicker({
                            opens: (App.isRTL() ? 'left' : 'right'),
                            format: 'MM/DD/YYYY',
                            separator: ' to ',
                            startDate: moment().subtract('days', 29),
                            endDate: moment(),
                            minDate: '01/01/2012',
                            maxDate: '12/31/2018',
                        },
                        function (start, end) {
                            $('#defaultrange_modal input').val(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                        }
                );

                // this is very important fix when daterangepicker is used in modal. in modal when daterange picker is opened and mouse clicked anywhere bootstrap modal removes the modal-open class from the body element.
                // so the below code will fix this issue.
                $('#defaultrange_modal').on('click', function(){
                    if ($('#daterangepicker_modal').is(":visible") && $('body').hasClass("modal-open") == false) {
                        $('body').addClass("modal-open");
                    }
                });

                $('#reportrange').daterangepicker({
                            opens: (App.isRTL() ? 'left' : 'right'),
                            startDate: moment().subtract('days', 29),
                            endDate: moment(),
                            minDate: '01/01/2012',
                            maxDate: '12/31/2014',
                            dateLimit: {
                                days: 60
                            },
                            showDropdowns: true,
                            showWeekNumbers: true,
                            timePicker: false,
                            timePickerIncrement: 1,
                            timePicker12Hour: true,
                            ranges: {
                                'Today': [moment(), moment()],
                                'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
                                'Last 7 Days': [moment().subtract('days', 6), moment()],
                                'Last 30 Days': [moment().subtract('days', 29), moment()],
                                'This Month': [moment().startOf('month'), moment().endOf('month')],
                                'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
                            },
                            buttonClasses: ['btn'],
                            applyClass: 'green',
                            cancelClass: 'default',
                            format: 'MM/DD/YYYY',
                            separator: ' to ',
                            locale: {
                                applyLabel: 'Apply',
                                fromLabel: 'From',
                                toLabel: 'To',
                                customRangeLabel: 'Custom Range',
                                daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                                monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                                firstDay: 1
                            }
                        },
                        function (start, end) {
                            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                        }
                );
                //Set the initial state of the picker label
                $('#reportrange span').html(moment().subtract('days', 29).format('MMMM D, YYYY') + ' - ' + moment().format('MMMM D, YYYY'));
            }

            return {
                //main function to initiate the module
                init: function () {
                    handleDatePickers();
                    //handleDateRangePickers();
                }
            };
        }();

        $(document).ready(function(){
            ComponentsDateTimePickers.init();

            FormValidation.init();
        });

        function add_new_resource(){
            $.ajax({
                url: '{{route('admin/shops/resources/add')}}',
                type: "post",
                data: {
                    'name':         $('input[name=resource_name]').val(),
                    'location_id':  $('input[name=resource_location_id]').val(),
                    'color_code':   $('input[name=resource_color]').val(),
                    'description':  $('textarea[name=resource_description]').val(),
                    'category_id':  $('select[name=resource_category]').val(),
                    'session_price':$('input[name=resource_price]').val(),
                    'vat_id':       $('select[name=resource_vat_rate]').val(),
                },
                success: function(data){
                    if(data.success){
                        show_notification(data.title, data.message, 'lime', 3500, 0);
                        setTimeout(function(){
                            location.reload();
                        },1500);
                    }
                    else{
                        show_notification(data.title, data.errors, 'ruby', 3500, 0);
                    }
                }
            });
        }

        function update_store_details(){
            $.ajax({
                url: '{{route('admin/shops/location/store_details_update', ['id'=>$shopDetails->id])}}',
                type: "post",
                data: {
                    'name':         $('input[name=shop_name]').val(),
                    'bank_acc_no':  $('input[name=shop_bank_acc_no]').val(),
                    'phone':        $('input[name=shop_phone]').val(),
                    'fax':          $('input[name=shop_fax]').val(),
                    'email':        $('input[name=shop_email]').val(),
                    'registered_no':$('input[name=shop_registration_no]').val(),
                    'visibility':   $('select[name=visibility]').val(),
                    '_method':'patch'
                },
                success: function(data){
                    if(data.success){
                        show_notification(data.title, data.message, 'lime', 3500, 0);
                        setTimeout(function(){
                            location.reload();
                        },2500);
                    }
                    else{
                        show_notification(data.title, data.errors, 'ruby', 3500, 0);
                    }
                }
            });
        }

        function update_store_address(){
            $.ajax({
                url: '{{route('admin/shops/location/store_address_update', ['id'=>$shopDetails->id])}}',
                type: "post",
                data: {
                    'address1':     $('input[name=shop_address1]').val(),
                    'address2':     $('input[name=shop_address2]').val(),
                    'city':         $('input[name=shop_city]').val(),
                    'region':       $('input[name=shop_region]').val(),
                    'postal_code':  $('input[name=shop_postal_code]').val(),
                    '_method':'patch'
                },
                success: function(data){
                    if(data.success){
                        show_notification(data.title, data.message, 'lime', 3500, 0);
                        setTimeout(function(){
                            location.reload();
                        },2500);
                    }
                    else{
                        show_notification(data.title, data.errors, 'ruby', 3500, 0);
                    }
                }
            });
        }

        $(".update_system_option").on('click', function(){
            var key   = $(this).parent().find('input[name=option_key]').val();
            var value = $(this).parent().find('select[name=option_value]').val();

            update_shop_options(key, value);
        });

        function update_shop_options(key, value){
            $.ajax({
                url: '{{route('admin/shops/shop_system_option_update')}}',
                type: "post",
                data: {
                    'shop_id':'{{$shopDetails->id}}',
                    'key':key,
                    'value': value
                },
                success: function(data){
                    if(data.success){
                        show_notification(data.title, data.message, 'lime', 3500, 0);
                    }
                    else{
                        show_notification(data.title, data.errors, 'ruby', 3500, 0);
                    }
                }
            });
        }

        $(".delete_shop_resource").on("click", function(){
            $('input[name=res_id]').val($(this).attr('data-id'));
            $('#delete_resource_modal').modal('show');
        });

        function delete_resource(){
            $.ajax({
                url: '{{route('admin/shops/resources/delete')}}',
                type: "post",
                data: {
                    'resource_id':$('input[name=res_id]').val(),
                    'location_id':'{{$shopDetails->id}}'
                },
                success: function(data){
                    if(data.success){
                        show_notification(data.title, data.message, 'lime', 3500, 0);
                        setTimeout(function(){
                            location.reload();
                        },2500);
                    }
                    else{
                        show_notification(data.title, data.errors, 'ruby', 3500, 0);
                    }
                }
            });
        }

        /* Add opening hours to selected location */
        function add_opening_hours(){
            $.ajax({
                url: '{{route('admin/shops/add_opening_hours')}}',
                type: "post",
                data: {
                    'days':         $('select[name=week_days]').val(),
                    'time_start':   $('input[name=time_start]').val(),
                    'time_stop':    $('input[name=time_stop]').val(),
                    'date_start':   $('input[name=from_date]').val(),
                    'date_stop':    $('input[name=to_date]').val(),
                    'type':         $('select[name=hours_type]').val(),
                    'location_id':  '{{$shopDetails->id}}'
                },
                success: function(data){
                    if(data.success){
                        show_notification(data.title, data.message, 'lime', 3500, 0);
                        setTimeout(function(){
                            location.reload();
                        },1500);
                    }
                    else{
                        show_notification(data.title, data.errors, 'ruby', 3500, 0);
                    }

                    $('#add_opening_hours_modal').modal('hide');
                    $('#new_opening_hours')[0].reset();
                }
            });
        }

        /* Get opening hours details for the clicked button in a modal window */
        function get_opening_hour(id){
            var select_days = {1:'Monday', 2:'Tuesday', 3:'Wednesday', 4:'Thursday', 5:'Friday', 6:'Saturday', 0:'Sunday'};
            var select_type = {open_hours:"Open Hours", close_hours:"Closed Hours"};

            $.ajax({
                url: '{{route('admin/shops/get_opening_hours_details')}}',
                type: "post",
                data: {
                    'open_hour_id': id,
                    'location_id':  '{{$shopDetails->id}}'
                },
                success: function(data){
                    if(data.success){
                        $("#update_opening_hours_modal").find('input:text, input:hidden, input:password, input:file, select, textarea').val('');
                        $("#update_opening_hours_modal").find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');

                        var week_days_list = '';
                        $.each(select_days, function(key, val){
                            if (key==0){ return true; }

                            if ($.inArray(key, data.hour.days)!=-1){
                                week_days_list+='<option value="'+key+'" selected>'+val+'</option>';
                            }
                            else{
                                week_days_list+='<option value="'+key+'">'+val+'</option>';
                            }
                        });

                        if ($.inArray("0", data.hour.days)!=-1){
                            week_days_list+='<option value="0" selected>'+select_days[0]+'</option>';
                        }
                        else{
                            week_days_list+='<option value="0">'+select_days[0]+'</option>';
                        }

                        $('select[name=update_week_days]').html(week_days_list);

                        var price_type = '';
                        $.each(select_type, function(key, val){
                            if ( data.hour.type == key ){
                                price_type+='<option value="'+key+'" selected="selected">'+val+'</option>';
                            }
                            else{
                                price_type+='<option value="'+key+'">'+val+'</option>';
                            }
                        });
                        $('select[name=update_price_type]').html(price_type);

                        $('input[name=update_time_start]').val(data.hour.time_start);
                        $('input[name=update_time_stop]').val(data.hour.time_stop);

                        $('input[name=update_from_date]').datepicker('setDate',data.hour.date_start);
                        $('input[name=update_to_date]').datepicker('setDate',data.hour.date_stop);

                        $('input[name=open_hour_id]').val(data.hour.id);
                        $('#update_opening_hours_modal').modal('show');
                    }
                    else{
                        show_notification(data.title, data.errors, 'ruby', 3500, 0);
                    }
                }
            });
        }

        /* Update opening hours with the popup information */
        function update_opening_hours(){
            $.ajax({
                url: '{{route('admin/shops/update_opening_hours')}}',
                type: "post",
                data: {
                    'days':         $('select[name=update_week_days]').val(),
                    'time_start':   $('input[name=update_time_start]').val(),
                    'time_stop':    $('input[name=update_time_stop]').val(),
                    'date_start':   $('input[name=update_from_date]').val(),
                    'date_stop':    $('input[name=update_to_date]').val(),
                    'type':         $('select[name=update_price_type]').val(),
                    'location_id':  '{{$shopDetails->id}}',
                    'open_hour_id': $('input[name=open_hour_id]').val()
                },
                success: function(data){
                    if(data.success){
                        show_notification(data.title, data.message, 'lime', 3500, 0);
                        setTimeout(function(){
                            location.reload();
                        },1000);
                    }
                    else{
                        show_notification(data.title, data.errors, 'ruby', 3500, 0);
                    }

                    $('#update_price_modal').modal('hide');
                    $('#new_resource_price')[0].reset();
                }
            });
        }

        /* Show delete opening hours confirmation modal window */
        function delete_opening_hours(id, res_id){
            $('input[name=del_open_hour_id]').val(id);
            $('input[name=del_location_id]').val(res_id);

            $('#delete_opening_hours_modal').modal('show');
        }

        /* Delete selected opening hours from shop location */
        function delete_hours(){
            $.ajax({
                url: '{{route('admin/shops/delete_opening_hours')}}',
                type: "post",
                data: {
                    'hour_id':      $('input[name=del_open_hour_id]').val(),
                    'location_id':  $('input[name=del_location_id]').val()
                },
                success: function(data){
                    $('#delete_price_modal').modal('hide');
                    $('input[name=del_open_hour_id]').val('');
                    $('input[name=del_location_id]').val('');

                    if(data.success){
                        show_notification(data.title, data.message, 'lime', 3500, 0);
                        setTimeout(function(){
                            location.reload();
                        },750);
                    }
                    else{
                        show_notification(data.title, data.errors, 'ruby', 3500, 0);
                    }
                }
            });
        }
    </script>
@endsection