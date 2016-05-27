@extends('admin.layouts.main')

@section('pageLevelPlugins')

@endsection

@section('themeGlobalStyle')
    <link href="{{ asset('assets/global/css/components-rounded.min.css') }}" rel="stylesheet" id="style_components" type="text/css" />
    <link href="{{ asset('assets/global/css/plugins.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/jquery-notific8/jquery.notific8.min.css') }}" rel="stylesheet" type="text/css" />
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
                                    <label class="col-md-3 control-label">Bank Account</label>
                                    <div class="col-md-9">
                                        <input type="text" name="shop_bank_acc_no" value="{{ $shopDetails->bank_acc_no }}" placeholder="Bank Account" class="form-control">
                                        <span class="help-block"> Bank Account Number </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Registered No.</label>
                                    <div class="col-md-9">
                                        <input type="text" name="shop_registration_no" value="{{ $shopDetails->registered_no }}" placeholder="Shop Registration Number" class="form-control">
                                        <span class="help-block"> National Registration Number </span>
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
                                        <span class="help-inline"> Inline help. </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Postal Code</label>
                                    <div class="col-md-9">
                                        <input type="text" name="shop_postal_code" value="{{ $shopAddress->postal_code }}" class="form-control input-inline input-medium" placeholder="Enter text">
                                        <span class="help-inline"> Inline help. </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Region</label>
                                    <div class="col-md-9">
                                        <input type="text" name="shop_region" value="{{ $shopAddress->region }}" class="form-control input-inline input-medium" placeholder="Enter text">
                                        <span class="help-inline"> Inline help. </span>
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
                <div class="portlet box green">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-cogs"></i>Shop Resources </div>
                        <div class="tools">
                            <a class="expand" href="javascript:;" data-original-title="" title=""> </a>
                        </div>
                        <div class="actions">
                            <a class="btn btn-circle btn-default" data-toggle="modal" href="#draggable">
                                <i class="fa fa-plus"></i> Add Resource </a>
                        </div>
                    </div>
                    <div class="portlet-body flip-scroll" style="display:none;">
                        <table class="table table-bordered table-striped table-condensed flip-content">
                            <thead class="flip-content">
                            <tr>
                                <th width="5%"> Code </th>
                                <th> Name </th>
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
                            @foreach ($resourceList as $resource)
                            <tr>
                                <td> AAC </td>
                                <td> <b>{{ $resource->name }}</b> </td>
                                <td class="numeric"> &nbsp; </td>
                                <td class="numeric"> -0.01 </td>
                                <td class="numeric"> -0.36% </td>
                                <td class="numeric"> $1.39 </td>
                                <td class="numeric"> $1.39 </td>
                                <td class="numeric"> &nbsp; </td>
                                <td class="numeric"> 9,395 </td>
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
                <div class="portlet box red">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-gift"></i>Opening Hours </div>
                        <div class="tools">
                            <a href="javascript:;" class="expand"> </a>
                            <a href="javascript:;" class="reload"> </a>
                            <a href="javascript:;" class="remove"> </a>
                        </div>
                    </div>
                    <div class="portlet-body form" style="display:none;">
                        <!-- BEGIN FORM-->
                        <form action="" class="form-horizontal form-bordered" name="opening_hours" id="opening_hours">
                            <div class="form-body form">
                                @foreach ($weekDays as $key=>$day)
                                <div class="form-group">
                                    <div class="control-label col-md-1">
                                        {{$day}} Open
                                    </div>
                                    <div class="col-md-2">
                                        <div class="input-group">
                                            <input type="text" class="form-control timepicker timepicker-24" name="open_{{$key}}" value="{{@$opening_hours[$key]["open_at"]}}">
                                                <span class="input-group-btn">
                                                    <button class="btn default" type="button">
                                                        <i class="fa fa-clock-o"></i>
                                                    </button>
                                                </span>
                                        </div>
                                    </div>
                                    <label class="control-label col-md-1">Close</label>
                                    <div class="col-md-2">
                                        <div class="input-group">
                                            <input type="text" class="form-control timepicker timepicker-24" name="close_{{$key}}" value="{{@$opening_hours[$key]["close_at"]}}">
                                                <span class="input-group-btn">
                                                    <button class="btn default" type="button">
                                                        <i class="fa fa-clock-o"></i>
                                                    </button>
                                                </span>
                                        </div>
                                    </div>

                                    <div class="control-label col-md-1">
                                        Break From
                                    </div>
                                    <div class="col-md-2">
                                        <div class="input-group">
                                            <input type="text" class="form-control timepicker timepicker-24"  value="{{@$opening_hours[$key]["break_from"]}}" name="break_from_{{$key}}">
                                                <span class="input-group-btn">
                                                    <button class="btn default" type="button">
                                                        <i class="fa fa-clock-o"></i>
                                                    </button>
                                                </span>
                                        </div>
                                    </div>
                                    <label class="control-label col-md-1">To</label>
                                    <div class="col-md-2">
                                        <div class="input-group">
                                            <input type="text" class="form-control timepicker timepicker-24"  value="{{@$opening_hours[$key]["break_to"]}}" name="break_to_{{$key}}">
                                                <span class="input-group-btn">
                                                    <button class="btn default" type="button">
                                                        <i class="fa fa-clock-o"></i>
                                                    </button>
                                                </span>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-10 col-md-2">
                                        <button class="btn green" type="submit">Update</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <!-- END FORM-->
                    </div>
                </div>
                <!-- END PORTLET-->
            </div>
        </div>

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
        <!-- END PAGE BASE CONTENT -->

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
                                            @foreach ($resourceCategory as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4">Color Code</label>
                                    <div class="col-md-7">
                                        <div class="input-icon right">
                                            <i class="fa"></i>
                                            <input type="text" class="form-control input-sm" id="resource_color" name="resource_color" /> </div>
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
    </div>
@endsection

@section('pageBelowLevelPlugins')
    <script src="{{ asset('assets/global/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}" type="text/javascript"></script>
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

            var handleTimePickers = function () {

                if (jQuery().timepicker) {
                    $('.timepicker-default').timepicker({
                        autoclose: true,
                        showSeconds: true,
                        minuteStep: 1
                    });

                    $('.timepicker-no-seconds').timepicker({
                        autoclose: true,
                        minuteStep: 5
                    });

                    $('.timepicker-24').timepicker({
                        autoclose: true,
                        minuteStep: 5,
                        showSeconds: false,
                        showMeridian: false
                    });

                    // handle input group button click
                    $('.timepicker').parent('.input-group').on('click', '.input-group-btn', function(e){
                        e.preventDefault();
                        $(this).parent('.input-group').find('.timepicker').timepicker('showWidget');
                    });
                }
            }

            return {
                //main function to initiate the module
                init: function () {
                    handleTimePickers();
                }
            };

        }();

        if (App.isAngularJsApp() === false) {
            jQuery(document).ready(function() {
                ComponentsDateTimePickers.init();
            });
        }

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

            return {
                //main function to initiate the module
                init: function () {
                    handleValidation1();
                    handleValidation2();
                    handleValidation3();
                    handleValidation4();
                }

            };

        }();

        $(document).ready(function(){
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
                },
                success: function(data){
                    alert(data);
                }
            });
        }

        function update_work_hours(){
            var str = $( "#opening_hours" ).serializeArray();

            $.ajax({
                url: '{{route('admin/shops/location/opening_hours_update', ['id'=>$shopDetails->id])}}',
                type: "post",
                data: {
                    opening_hours : str,
                },
                success: function(data){
                    alert(data);
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
                    '_method':'patch'
                },
                success: function(data){
                    alert(data);
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
                    alert(data);
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