@extends('admin.layouts.main')

@section('pageLevelPlugins')
    <link href="{{ asset('assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/fancybox/source/jquery.fancybox.css') }}" rel="stylesheet" type="text/css" />
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
                <div class="portlet">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-shopping-cart"></i>{!!$text_parts['table_head_text']!!} </div>
                        <div class="actions btn-set">
                            <button type="button" name="back" class="btn btn-secondary-outline">
                                <i class="fa fa-angle-left"></i> Back</button>
                            <button class="btn btn-success">
                                <i class="fa fa-check"></i> Save</button>
                            <button class="btn btn-success">
                                <i class="fa fa-check-circle"></i> Save & Continue Edit</button>
                            <div class="btn-group">
                                <a class="btn btn-success dropdown-toggle" href="javascript:;" data-toggle="dropdown">
                                    <i class="fa fa-share"></i> More
                                    <i class="fa fa-angle-down"></i>
                                </a>
                                <div class="dropdown-menu pull-right">
                                    <li>
                                        <a data-toggle="modal" href="#new_inventory_box"> New Inventory </a>
                                    </li>
                                    <li>
                                        <a data-toggle="modal" href="#transfer_inventory_box"> Make a Transfer </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;"> Delete </a>
                                    </li>
                                    <li class="dropdown-divider"> </li>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="tabbable-bordered">
                            <ul class="nav nav-tabs">
                                <li class="active">
                                    <a href="#tab_general" data-toggle="tab"> General </a>
                                </li>
                                <li>
                                    <a href="#tab_meta" data-toggle="tab"> Meta </a>
                                </li>
                                <li>
                                    <a href="#tab_images" data-toggle="tab"> Images </a>
                                </li>
                                <li>
                                    <a href="#tab_inventory" data-toggle="tab"> Inventory Activity
                                        <span class="badge badge-success"> 3 </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#tab_stock" data-toggle="tab"> Current Stock
                                        <span class="badge badge-success"> 3 </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#tab_history" data-toggle="tab"> Sale History
                                        <span class="badge badge-success"> 13 </span>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_general">
                                    <form role="form" action="" name="product_details_info" id="product_details_info" class="form-horizontal form-row-seperated">
                                        <div class="form-body">
                                            <div class="alert alert-danger display-hide">
                                                <button class="close" data-close="alert"></button> You have some form errors. Please check below. </div>
                                            <div class="alert alert-success display-hide">
                                                <button class="close" data-close="alert"></button> Your form validation is successful! </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Name:
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-10">
                                                    <input type="text" class="form-control" name="product_name" placeholder="Product Name" value="{{ $product_details->name }}"> </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Alternate Name:</label>
                                                <div class="col-md-10">
                                                    <input type="text" class="form-control" name="product_alternate_name" placeholder="" value="{{ $product_details->alternate_name }}"> </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Description:</label>
                                                <div class="col-md-10">
                                                    <textarea class="form-control" name="product_description">{{ $product_details->description }}</textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Categories:
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-10">
                                                    <select class="table-group-action-input form-control input-large" name="product_category">
                                                        <option value="">Select Category...</option>
                                                        @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}" {!! $product_details->category_id == $category->id ? ' selected="selected" ' : '' !!}}>{{ $category->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Available Date:</label>
                                                <div class="col-md-10">
                                                    <div class="input-group input-large date-picker input-daterange" data-date="11-10-2012" data-date-format="dd-mm-yyyy">
                                                        <input type="text" class="form-control" name="product_available_from" value="{{ @$product_availability->available_from }}">
                                                        <span class="input-group-addon"> to </span>
                                                        <input type="text" class="form-control" name="product_available_to" value="{{ @$product_availability->available_to }}"></div>
                                                    <span class="help-block"><button class="btn btn-success" id="update_availability_now">
                                                            <i class="fa fa-check"></i> Update Availability</button></span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Cost Price:</label>
                                                <div class="col-md-10">
                                                    <span class="btn default disabled" > {{ isset($entry_price)?$entry_price->entry_price." - since ".@$entry_price->added_on:'-' }} </span>
                                                    <span class="help-inline"> ex. VAT </span></div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">List Price:
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-10">
                                                    <input type="text" class="form-control input-medium input-inline" name="product_price" placeholder="Product Price" value="{{ @$price->list_price }}">
                                                    <span class="help-inline"> ex. VAT </span></div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Currency:
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-10">
                                                    <select class="table-group-action-input form-control input-small" name="product_currency">
                                                        <option value="">Select...</option>
                                                        @foreach ($currencies as $currency)
                                                            <option value="{{ $currency->id }}" {!! $currency->id==@$price->currency->id ? ' selected="selected" ' : '' !!}}>{{ $currency->currency_code }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">VAT Rate:
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-10">
                                                    <select class="table-group-action-input form-control input-medium" name="product_vat_rate">
                                                        <option value="">Select...</option>
                                                        @foreach ($vat_rates as $vatRate)
                                                        <option value="{{$vatRate->id}}" {!!$vatRate->id==$product_details->vat_rate_id ? ' selected="selected" ' : ''!!}>{{ $vatRate->display_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Brand:
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-10">
                                                    <input type="text" class="form-control" name="product_brand" placeholder="Product Brand" value="{{ $product_details->brand }}"> </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Manufacturer:
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-10">
                                                    <input type="text" class="form-control" name="product_manufacturer" placeholder="Manufacturer" value="{{ $product_details->manufacturer }}"> </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">URL:
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-10">
                                                    <input type="text" class="form-control" name="product_url" placeholder="Product URL" value="{{ $product_details->url }}"> </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Bar Code:</label>
                                                <div class="col-md-10">
                                                    <input type="text" class="form-control" name="product_bar_code" placeholder="Bar Code" value="{{ $product_details->barcode }}"> </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Status:
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-10">
                                                    <select class="table-group-action-input form-control input-medium" name="product_status">
                                                        <option value="">Select...</option>
                                                        <option value="1" {!! $product_details->status==1 ? ' selected="selected" ' : '' !!}>Published</option>
                                                        <option value="0" {!! $product_details->status==0 ? ' selected="selected" ' : '' !!}>Not Published</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-offset-2 col-md-10">
                                                    <div class="actions btn-set">
                                                        <button class="btn btn-secondary-outline" name="back" type="button">
                                                            <i class="fa fa-angle-left"></i> Back</button>
                                                        <button class="btn btn-success">
                                                            <i class="fa fa-check"></i> Save</button>
                                                        <button class="btn btn-success">
                                                            <i class="fa fa-check-circle"></i> Save &amp; Continue Edit</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane" id="tab_meta">
                                    <form role="form" action="" name="product_details_meta" id="product_details_meta" class="form-horizontal form-row-seperated">
                                        <div class="form-body">
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Meta Title:</label>
                                                <div class="col-md-10">
                                                    <input type="text" class="form-control maxlength-handler" name="product[meta_title]" maxlength="100" placeholder="">
                                                    <span class="help-block"> max 100 chars </span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Meta Keywords:</label>
                                                <div class="col-md-10">
                                                    <textarea class="form-control maxlength-handler" rows="8" name="product[meta_keywords]" maxlength="1000"></textarea>
                                                    <span class="help-block"> max 1000 chars </span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Meta Description:</label>
                                                <div class="col-md-10">
                                                    <textarea class="form-control maxlength-handler" rows="8" name="product[meta_description]" maxlength="255"></textarea>
                                                    <span class="help-block"> max 255 chars </span>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane" id="tab_images">
                                    <div class="alert alert-success margin-bottom-10">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                                        <i class="fa fa-warning fa-lg"></i> Image type and information need to be specified. </div>
                                    <div id="tab_images_uploader_container" class="text-align-reverse margin-bottom-10">
                                        <a id="tab_images_uploader_pickfiles" href="javascript:;" class="btn btn-success">
                                            <i class="fa fa-plus"></i> Select Files </a>
                                        <a id="tab_images_uploader_uploadfiles" href="javascript:;" class="btn btn-primary">
                                            <i class="fa fa-share"></i> Upload Files </a>
                                    </div>
                                    <div class="row">
                                        <div id="tab_images_uploader_filelist" class="col-md-6 col-sm-12"> </div>
                                    </div>
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                        <tr role="row" class="heading">
                                            <th width="8%"> Image </th>
                                            <th width="25%"> Label </th>
                                            <th width="8%"> Sort Order </th>
                                            <th width="10%"> Base Image </th>
                                            <th width="10%"> Small Image </th>
                                            <th width="10%"> Thumbnail </th>
                                            <th width="10%"> </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if ($product_images)
                                            @foreach ($product_images as $product_image)
                                                <tr>
                                                    <td>
                                                        <a href="" class="fancybox-button" data-rel="fancybox-button">
                                                            <img class="img-responsive" src="{{ asset("uploads/".$product_image->file_location) }}" alt=""> </a>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="product[images][{{ $product_image->id }}][label]" value="{{ $product_image->label }}"> </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="product[images][{{ $product_image->id }}][sort_order]" value="{{ $product_image->sort_order }}"> </td>
                                                    <td>
                                                        <label>
                                                            <input type="radio" name="product[images][{{ $product_image->id }}][image_type]" value="1" {{ $product_image->image_size==1?' checked ':'' }}> </label>
                                                    </td>
                                                    <td>
                                                        <label>
                                                            <input type="radio" name="product[images][{{ $product_image->id }}][image_type]" value="2" {{ $product_image->image_size==2?' checked ':'' }}> </label>
                                                    </td>
                                                    <td>
                                                        <label>
                                                            <input type="radio" name="product[images][{{ $product_image->id }}][image_type]" value="3" {{ $product_image->image_size==3?' checked ':'' }}> </label>
                                                    </td>
                                                    <td>
                                                        <a href="javascript:;" class="btn btn-default btn-sm">
                                                            <i class="fa fa-save"></i> Update </a>
                                                        <a href="javascript:;" class="btn btn-default btn-sm">
                                                            <i class="fa fa-times"></i> Remove </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane" id="tab_inventory">
                                    <div class="table-container">
                                        <table class="table table-striped table-bordered table-hover" id="datatable_inventory">
                                            <thead>
                                            <tr role="row" class="heading">
                                                <th width="5%"> Inventory&nbsp;ID&nbsp;# </th>
                                                <th width="10%"> Inventory&nbsp;Date</th>
                                                <th width="10%"> Amount - at least </th>
                                                <th width="15%"> List&nbsp;Price - at least </th>
                                                <th width="15%"> Shop&nbsp;Location </th>
                                                <th width="15%"> Employee </th>
                                                <th width="5%"> Actions </th>
                                            </tr>
                                            <tr role="row" class="filter">
                                                <td>
                                                    <input type="text" class="form-control form-filter input-sm" name="t_inventory_id_no"> </td>
                                                <td>
                                                    <div class="input-group date date-picker margin-bottom-5" data-date-format="dd-mm-yyyy">
                                                        <input type="text" class="form-control form-filter input-sm" readonly name="t_inventory_date_from" placeholder="From">
                                                            <span class="input-group-btn">
                                                                <button class="btn btn-sm default" type="button">
                                                                    <i class="fa fa-calendar"></i>
                                                                </button>
                                                            </span>
                                                    </div>
                                                    <div class="input-group date date-picker" data-date-format="dd-mm-yyyy">
                                                        <input type="text" class="form-control form-filter input-sm" readonly name="t_inventory_date_to" placeholder="To">
                                                            <span class="input-group-btn">
                                                                <button class="btn btn-sm default" type="button">
                                                                    <i class="fa fa-calendar"></i>
                                                                </button>
                                                            </span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control form-filter input-sm" name="t_inventory_amount"> </td>
                                                <td>
                                                    <input type="text" class="form-control form-filter input-sm" name="t_inventory_list_price"> </td>
                                                <td>
                                                    <select name="t_inventory_location" class="form-control form-filter input-sm">
                                                        <option value="">Select...</option>
                                                    @foreach ($shops as $shop)
                                                        <option value="{!! $shop->id !!}">{{ $shop->name }}</option>
                                                    @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <select name="t_inventory_employee" class="form-control form-filter input-sm">
                                                        <option value="">Select...</option>
                                                        @foreach ($users as $user)
                                                            <option value="{!! $user->id !!}">{{ $user->first_name.' '.$user->middle_name.' '.$user->last_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <div class="margin-bottom-5">
                                                        <button class="btn btn-sm btn-success filter-submit margin-bottom">
                                                            <i class="fa fa-search"></i> Search</button>
                                                    </div>
                                                    <button class="btn btn-sm btn-danger filter-cancel">
                                                        <i class="fa fa-times"></i> Reset</button>
                                                </td>
                                            </tr>
                                            </thead>
                                            <tbody> </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab_stock">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <!-- BEGIN PORTLET-->
                                            <div class="portlet box blue">
                                                <div class="portlet-title">
                                                    <div class="caption">
                                                        <i class="fa fa-gift"></i>Current Stock </div>
                                                    <div class="tools">
                                                        <a class="collapse" href="javascript:;" data-original-title="" title=""> </a>
                                                        <a class="reload" href="javascript:;" data-original-title="" title=""> </a>
                                                    </div>
                                                </div>
                                                <div class="portlet-body form" style="display: block;">
                                                    <!-- BEGIN FORM-->
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <!-- BEGIN EXAMPLE TABLE PORTLET-->
                                                            <div class="portlet light bordered">

                                                                <div class="portlet-body">
                                                                    <table class="table table-striped table-bordered table-hover table-checkable order-column" id="sample_1">
                                                                        <thead>
                                                                        <tr>
                                                                            <th> No. </th>
                                                                            <th> Shop Location </th>
                                                                            <th> Product Name </th>
                                                                            <th> Quantity </th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        @foreach ($stocks as $stock)
                                                                            <tr class="odd gradeX">
                                                                                <td></td>
                                                                                <td class="text-right"> {{ $stock["location_name"] }} </td>
                                                                                <td class="text-center">
                                                                                    <a href="{{ $product_details->id }}"> {{ $product_details->name }} </a>
                                                                                </td>
                                                                                <td class="text-center"> {{ $stock["quantity"] }} </td>
                                                                            </tr>
                                                                        @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                            <!-- END EXAMPLE TABLE PORTLET-->
                                                        </div>
                                                    </div>
                                                    <!-- END FORM-->
                                                </div>
                                            </div>
                                            <!-- END PORTLET-->
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab_history">
                                    <div class="table-container">
                                        <table class="table table-striped table-bordered table-hover" id="datatable_history">
                                            <thead>
                                            <tr role="row" class="heading">
                                                <th width="25%"> Datetime </th>
                                                <th width="55%"> Description </th>
                                                <th width="10%"> Notification </th>
                                                <th width="10%"> Actions </th>
                                            </tr>
                                            <tr role="row" class="filter">
                                                <td>
                                                    <div class="input-group date datetime-picker margin-bottom-5" data-date-format="dd/mm/yyyy hh:ii">
                                                        <input type="text" class="form-control form-filter input-sm" readonly name="product_history_date_from" placeholder="From">
                                                            <span class="input-group-btn">
                                                                <button class="btn btn-sm default date-set" type="button">
                                                                    <i class="fa fa-calendar"></i>
                                                                </button>
                                                            </span>
                                                    </div>
                                                    <div class="input-group date datetime-picker" data-date-format="dd/mm/yyyy hh:ii">
                                                        <input type="text" class="form-control form-filter input-sm" readonly name="product_history_date_to" placeholder="To">
                                                            <span class="input-group-btn">
                                                                <button class="btn btn-sm default date-set" type="button">
                                                                    <i class="fa fa-calendar"></i>
                                                                </button>
                                                            </span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control form-filter input-sm" name="product_history_desc" placeholder="To" /> </td>
                                                <td>
                                                    <select name="product_history_notification" class="form-control form-filter input-sm">
                                                        <option value="">Select...</option>
                                                        <option value="pending">Pending</option>
                                                        <option value="notified">Notified</option>
                                                        <option value="failed">Failed</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <div class="margin-bottom-5">
                                                        <button class="btn btn-sm btn-default filter-submit margin-bottom">
                                                            <i class="fa fa-search"></i> Search</button>
                                                    </div>
                                                    <button class="btn btn-sm btn-danger-outline filter-cancel">
                                                        <i class="fa fa-times"></i> Reset</button>
                                                </td>
                                            </tr>
                                            </thead>
                                            <tbody> </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade draggable-modal" id="new_inventory_box" tabindex="-1" role="basic" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                            <h4 class="modal-title">Add new inventory</h4>
                        </div>
                        <div class="modal-body">
                            <form action="#" id="new_inventory" name="new_inventory" class="form-horizontal">
                                <div class="form-body">
                                    <div class="alert alert-danger display-hide">
                                        <button class="close" data-close="alert"></button> You have some form errors. Please check below. </div>
                                    <div class="alert alert-success display-hide">
                                        <button class="close" data-close="alert"></button> Your form validation is successful! </div>
                                    <div class="form-group  margin-top-20">
                                        <label class="control-label col-md-4">Product Name
                                            <span class="required"> * </span>
                                        </label>
                                        <div class="col-md-7">
                                            <div class="input-icon right">
                                                <i class="fa"></i>
                                                <input type="text" class="form-control input-xs" value="{{ $product_details->name }}" readonly disabled /> </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Quantity</label>
                                        <div class="col-md-7">
                                            <div class="input-icon right">
                                                <i class="fa"></i>
                                                <input type="text" class="form-control input-small" id="inventory_quantity" name="inventory_quantity" /> </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Cost Price</label>
                                        <div class="col-md-7">
                                            <div class="input-icon right">
                                                <i class="fa"></i>
                                                <input type="text" class="form-control input-small input-inline" id="inventory_product_price" name="inventory_product_price" value="{{ @$entry_price->entry_price }}" />
                                                <span class="help-inline"> ex. VAT </span></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">List Price</label>
                                        <div class="col-md-7">
                                            <div class="input-icon right">
                                                <i class="fa"></i>
                                                <input class="form-control input-small input-inline" value="{{ @$price->list_price }}" readonly disabled />
                                                <span class="help-inline"> ex. VAT </span></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Shop Location
                                            <span class="required"> * </span>
                                        </label>
                                        <div class="col-md-7">
                                            <select class="form-control input-sm" name="inventory_shop_location" id="inventory_shop_location">
                                                <option value="-1">Select...</option>
                                            @foreach($shops as $shop)
                                                <option value="{{$shop->id}}">{{$shop->name}}</option>
                                            @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                            <button type="button" class="btn green submit_form_2" onClick="javascript: $('#new_inventory').submit();">Add inventory</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>

            <div class="modal fade draggable-modal" id="transfer_inventory_box" tabindex="-1" role="basic" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                            <h4 class="modal-title">Transfer Inventory</h4>
                        </div>
                        <div class="modal-body">
                            <form action="#" id="new_product_transfer" name="new_product_transfer" class="form-horizontal">
                                <div class="form-body">
                                    <div class="alert alert-danger display-hide">
                                        <button class="close" data-close="alert"></button> You have some form errors. Please check below. </div>
                                    <div class="alert alert-success display-hide">
                                        <button class="close" data-close="alert"></button> Your form validation is successful! </div>
                                    <div class="form-group  margin-top-20">
                                        <label class="control-label col-md-4">Product Name
                                            <span class="required"> * </span>
                                        </label>
                                        <div class="col-md-7">
                                            <div class="input-icon right">
                                                <i class="fa"></i>
                                                <input type="text" class="form-control input-xs" value="{{ $product_details->name }}" readonly disabled /> </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Quantity</label>
                                        <div class="col-md-7">
                                            <div class="input-icon right">
                                                <i class="fa"></i>
                                                <input type="text" class="form-control input-small" id="inventory_transfer_quantity" name="inventory_transfer_quantity" /> </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Cost Price</label>
                                        <div class="col-md-7">
                                            <div class="input-icon right">
                                                <i class="fa"></i>
                                                <input type="text" class="form-control input-small input-inline" value="{{ @$entry_price->entry_price }}" readonly disabled />
                                                <span class="help-inline"> ex. VAT </span></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">List Price</label>
                                        <div class="col-md-7">
                                            <div class="input-icon right">
                                                <i class="fa"></i>
                                                <input class="form-control input-small input-inline" value="{{ @$price->list_price }}" readonly disabled />
                                                <span class="help-inline"> ex. VAT </span></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">From Location
                                            <span class="required"> * </span>
                                        </label>
                                        <div class="col-md-7">
                                            <select class="form-control input-sm" name="transfer_from_location" id="transfer_from_location">
                                                <option value="-1">Select...</option>
                                                @foreach($shops as $shop)
                                                    <option value="{{$shop->id}}">{{$shop->name}} - {{ @$stocks[$shop->id]["quantity"] }} in stock</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">To Location
                                            <span class="required"> * </span>
                                        </label>
                                        <div class="col-md-7">
                                            <select class="form-control input-sm" name="transfer_to_location" id="transfer_to_location">
                                                <option>Select...</option>
                                                @foreach($shops as $shop)
                                                    <option value="{{$shop->id}}">{{$shop->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                            <button type="button" class="btn green submit_form_2" onClick="javascript: $('#new_product_transfer').submit();">Make transfer</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
        </div>
        <!-- END PAGE BASE CONTENT -->
    </div>
@endsection

@section('pageBelowLevelPlugins')
    <script src="{{ asset('assets/global/scripts/datatable.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/datatables/datatables.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript"></script>

    <script src="{{ asset('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/fancybox/source/jquery.fancybox.pack.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/plupload/js/plupload.full.min.js') }}" type="text/javascript"></script>
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

        var EcommerceProductsEdit = function () {

            var handleImages = function() {

                // see http://www.plupload.com/
                var uploader = new plupload.Uploader({
                    runtimes : 'html5,flash,silverlight,html4',

                    browse_button : document.getElementById('tab_images_uploader_pickfiles'), // you can pass in id...
                    container: document.getElementById('tab_images_uploader_container'), // ... or DOM Element itself

                    url : "{{ route('admin/shops/products/add_image', ['id'=>$product_details->id]) }}",

                    filters : {
                        max_file_size : '10mb',
                        mime_types: [
                            {title : "Image files", extensions : "jpg,gif,png"},
                        ]
                    },

                    // Flash settings
                    flash_swf_url : '{{ asset('assets/plugins/plupload/js/Moxie.swf') }}',

                    // Silverlight settings
                    silverlight_xap_url : '{{ asset('assets/plugins/plupload/js/Moxie.xap') }}',

                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },

                    init: {
                        PostInit: function() {
                            $('#tab_images_uploader_filelist').html("");

                            $('#tab_images_uploader_uploadfiles').click(function() {
                                uploader.start();
                                return false;
                            });

                            $('#tab_images_uploader_filelist').on('click', '.added-files .remove', function(){
                                uploader.removeFile($(this).parent('.added-files').attr("id"));
                                $(this).parent('.added-files').remove();
                            });
                        },

                        FilesAdded: function(up, files) {
                            plupload.each(files, function(file) {
                                $('#tab_images_uploader_filelist').append('<div class="alert alert-warning added-files" id="uploaded_file_' + file.id + '">' + file.name + '(' + plupload.formatSize(file.size) + ') <span class="status label label-info"></span>&nbsp;<a href="javascript:;" style="margin-top:-5px" class="remove pull-right btn btn-sm red"><i class="fa fa-times"></i> remove</a></div>');
                            });
                        },

                        UploadProgress: function(up, file) {
                            $('#uploaded_file_' + file.id + ' > .status').html(file.percent + '%');
                        },

                        FileUploaded: function(up, file, response) {
                            var response = $.parseJSON(response.response);

                            if (response.result && response.result == 'OK') {
                                var id = response.id; // uploaded file's unique name. Here you can collect uploaded file names and submit an jax request to your server side script to process the uploaded files and update the images tabke

                                $('#uploaded_file_' + file.id + ' > .status').removeClass("label-info").addClass("label-success").html('<i class="fa fa-check"></i> Done'); // set successfull upload
                            } else {
                                $('#uploaded_file_' + file.id + ' > .status').removeClass("label-info").addClass("label-danger").html('<i class="fa fa-warning"></i> Failed'); // set failed upload
                                App.alert({type: 'danger', message: 'One of uploads failed. Please retry.', closeInSeconds: 10, icon: 'warning'});
                            }
                        },

                        Error: function(up, err) {
                            App.alert({type: 'danger', message: err.message, closeInSeconds: 10, icon: 'warning'});
                        }
                    }
                });

                uploader.init();

            }

            var handleReviews = function () {

                var grid = new Datatable();

                grid.init({
                    src: $("#datatable_inventory"),
                    onSuccess: function (grid) {
                        // execute some code after table records loaded
                    },
                    onError: function (grid) {
                        // execute some code on network or other general error
                    },
                    loadingMessage: 'Loading...',
                    dataTable: { // here you can define a typical datatable settings from http://datatables.net/usage/options

                        // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
                        // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/scripts/datatable.js).
                        // So when dropdowns used the scrollable div should be removed.
                        //"dom": "<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'>>",

                        "lengthMenu": [
                            [10, 20, 50, 100, 150, -1],
                            [10, 20, 50, 100, 150, "All"] // change per page values here
                        ],
                        "pageLength": 10, // default record count per page
                        "ajax": {
                            "url": "{{ route('admin/shops/products/get_inventory', ['id'=>$product_details->id]) }}", // ajax source
                        },
                        "columnDefs": [{ // define columns sorting options(by default all columns are sortable extept the first checkbox column)
                            'orderable': true,
                            'targets': [0]
                        }],
                        "order": [
                            [0, "asc"]
                        ] // set first column as a default sort by asc
                    }
                });
            }

            var handleHistory = function () {

                var grid = new Datatable();

                grid.init({
                    src: $("#datatable_history"),
                    onSuccess: function (grid) {
                        // execute some code after table records loaded
                    },
                    onError: function (grid) {
                        // execute some code on network or other general error
                    },
                    loadingMessage: 'Loading...',
                    dataTable: { // here you can define a typical datatable settings from http://datatables.net/usage/options
                        "lengthMenu": [
                            [10, 20, 50, 100, 150, -1],
                            [10, 20, 50, 100, 150, "All"] // change per page values here
                        ],
                        "pageLength": 10, // default record count per page
                        "ajax": {
                            "url": "{{ route('admin/shops/products/get_history') }}", // ajax source
                        },
                        "columnDefs": [{ // define columns sorting options(by default all columns are sortable extept the first checkbox column)
                            'orderable': true,
                            'targets': [0]
                        }],
                        "order": [
                            [0, "asc"]
                        ] // set first column as a default sort by asc
                    }
                });
            }

            var initComponents = function () {
                //init datepickers
                $('.date-picker').datepicker({
                    rtl: App.isRTL(),
                    autoclose: true
                });

                //init datetimepickers
                $(".datetime-picker").datetimepicker({
                    isRTL: App.isRTL(),
                    autoclose: true,
                    todayBtn: true,
                    pickerPosition: (App.isRTL() ? "bottom-right" : "bottom-left"),
                    minuteStep: 10
                });

                //init maxlength handler
                $('.maxlength-handler').maxlength({
                    limitReachedClass: "label label-danger",
                    alwaysShow: true,
                    threshold: 5
                });
            }

            return {

                //main function to initiate the module
                init: function () {
                    initComponents();

                    handleImages();
                    handleReviews();
                    handleHistory();
                }

            };

        }();

        jQuery(document).ready(function() {
            EcommerceProductsEdit.init();
        });

        jQuery.validator.addMethod(
                "datePickerDate",
                function(value, element) {
                    // put your own logic here, this is just a (crappy) example
                    return value.match(/^\d\d?-\d\d?-\d\d\d\d$/);
                },
                "Please enter a date in the format dd/mm/yyyy."
        );

        jQuery.validator.addMethod(
                "notEqual",
                function(value, element, param) {
                    return this.optional(element) || value != $(param).val();
                },
                "Please specify a different (non-default) value"
        );

        var FormValidation = function () {

            var handleValidation1 = function() {
                var form1 = $('#product_details_info');
                var error1 = $('.alert-danger', form1);
                var success1 = $('.alert-success', form1);

                form1.validate({
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block help-block-error', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    ignore: "",  // validate all fields including form hidden input
                    rules: {
                        product_name: {
                            minlength: 3,
                            required: true
                        },
                        product_alternate_name: {
                            minlength: 3,
                        },
                        product_description: {
                            minlength: 10,
                        },
                        product_category: {
                            number: true,
                            required: true
                        },
                        product_available_from: {
                            datePickerDate: true
                        },
                        product_available_to: {
                            datePickerDate: true
                        },
                        product_price: {
                            minlength: 1,
                            number: true,
                            required: true
                        },
                        product_currency: {
                            number: true,
                            required: true
                        },
                        product_vat_rate: {
                            number: true,
                            required: true
                        },
                        product_brand: {
                            minlength: 3,
                            required: true
                        },
                        product_manufacturer: {
                            minlength: 3,
                            required: true
                        },
                        product_url: {
                            minlength: 3,
                            required: true
                        },
                        product_barcode: {
                            minlength: 5,
                            required: false
                        },
                        product_status: {
                            number: true,
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
                        update_product_details(); // submit the form
                    }
                });
            }

            var handleValidation2 = function() {
                var form2 = $('#new_inventory');
                var error2 = $('.alert-danger', form2);
                var success2 = $('.alert-success', form2);

                form2.validate({
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block help-block-error', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    ignore: "",  // validate all fields including form hidden input
                    rules: {
                        inventory_quantity: {
                            minlength: 1,
                            number: true,
                            required: true
                        },
                        inventory_product_price: {
                            minlength: 1,
                            number: true,
                            required: true
                        },
                        inventory_shop_location: {
                            number: true,
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
                        add_to_inventory(); // submit the form
                    }
                });
            }

            var handleValidation3 = function() {
                var form3 = $('#new_product_transfer');
                var error3 = $('.alert-danger', form3);
                var success3 = $('.alert-success', form3);

                form3.validate({
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block help-block-error', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    ignore: "",  // validate all fields including form hidden input
                    rules: {
                        inventory_transfer_quantity: {
                            minlength: 1,
                            number: true,
                            required: true
                        },
                        transfer_from_location: {
                            number: true,
                            required: true,
                            notEqual: '#transfer_to_location'
                        },
                        transfer_to_location: {
                            number: true,
                            required: true,
                            notEqual: '#transfer_from_location'
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
                        transfer_inventory(); // submit the form
                    }
                });
            }

            return {
                //main function to initiate the module
                init: function () {
                    handleValidation1();
                    handleValidation2();
                    handleValidation3();
                }

            };

        }();

        $(document).ready(function(){
            FormValidation.init();
        });

        var TableDatatablesManaged = function () {

            var initTable1 = function () {

                var table = $('#sample_1');

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

            var initTable2 = function () {

                var table = $('#sample_2');

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

                    // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
                    // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js).
                    // So when dropdowns used the scrollable div should be removed.
                    //"dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r>t<'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",

                    "bStateSave": true, // save datatable state(pagination, sort, etc) in cookie.
                    "pagingType": "bootstrap_extended",

                    "lengthMenu": [
                        [5, 15, 20, -1],
                        [5, 15, 20, "All"] // change per page values here
                    ],
                    // set the initial value
                    "pageLength": 5,
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

                var tableWrapper = jQuery('#sample_2_wrapper');

                table.find('.group-checkable').change(function () {
                    var set = jQuery(this).attr("data-set");
                    var checked = jQuery(this).is(":checked");
                    jQuery(set).each(function () {
                        if (checked) {
                            $(this).prop("checked", true);
                        } else {
                            $(this).prop("checked", false);
                        }
                    });
                    jQuery.uniform.update(set);
                });
            }

            var initTable3 = function () {

                var table = $('#sample_3');

                // begin: third table
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

                    // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
                    // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js).
                    // So when dropdowns used the scrollable div should be removed.
                    //"dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r>t<'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",

                    "bStateSave": true, // save datatable state(pagination, sort, etc) in cookie.

                    "lengthMenu": [
                        [6, 15, 20, -1],
                        [6, 15, 20, "All"] // change per page values here
                    ],
                    // set the initial value
                    "pageLength": 6,
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

                var tableWrapper = jQuery('#sample_3_wrapper');

                table.find('.group-checkable').change(function () {
                    var set = jQuery(this).attr("data-set");
                    var checked = jQuery(this).is(":checked");
                    jQuery(set).each(function () {
                        if (checked) {
                            $(this).prop("checked", true);
                        } else {
                            $(this).prop("checked", false);
                        }
                    });
                    jQuery.uniform.update(set);
                });
            }

            return {

                //main function to initiate the module
                init: function () {
                    if (!jQuery().dataTable) {
                        return;
                    }

                    initTable1();
                    initTable2();
                    initTable3();
                }

            };

        }();

        if (App.isAngularJsApp() === false) {
            jQuery(document).ready(function() {
                TableDatatablesManaged.init();
            });
        }

        $('#update_availability_now').on('click', function( event ){
            event.preventDefault();
            var from_date = $('input[name=product_available_from]').val();
            var to_date = $('input[name=product_available_to]').val();

            if (from_date!='' || to_date!='') {
                update_availability(from_date, to_date);
            }
        });

        function update_availability(from_date, to_date){
            $.ajax({
                url: '{{ route('admin/shops/products/update_availability', ['id' => $product_details->id]) }}',
                type: "post",
                data: {
                    'available_from':   from_date,
                    'available_to':     to_date,
                    '_method':          'put',
                },
                success: function(data){
                    alert(data);
                }
            });
        }

        function update_product_details(){
            $.ajax({
                url: '{{route('admin/shops/products/update', array('id'=>$product_details->id))}}',
                type: "post",
                data: {
                    'name':             $('input[name=product_name]').val(),
                    'alternate_name':   $('input[name=product_alternate_name]').val(),
                    'category_id':      $('select[name=product_category]').val(),
                    'available_from':   $('input[name=product_available_from]').val(),
                    'available_to':     $('input[name=product_available_to]').val(),
                    'list_price':       $('input[name=product_price]').val(),
                    'brand':            $('input[name=product_brand]').val(),
                    'manufacturer':     $('input[name=product_manufacturer]').val(),
                    'url':              $('input[name=product_url]').val(),
                    'barcode':          $('input[name=product_bar_code]').val(),
                    'vat_rate_id':      $('select[name=product_vat_rate]').val(),
                    'country_id':       $('select[name=product_currency]').val(),
                    'status':           $('select[name=product_status]').val(),
                    'description':      $('textarea[name=product_description]').val(),
                    '_method':          'put'
                },
                success: function(data){
                    alert(data);
                }
            });
        }

        function add_to_inventory(){
            $.ajax({
                url: '{{route('admin/shops/products/add_to_inventory')}}',
                type: "post",
                data: {
                    'product_id':    "{{$product_details->id}}",
                    'quantity':      $('input[name=inventory_quantity]').val(),
                    'entry_price':   $('input[name=inventory_product_price]').val(),
                    'location_id':   $('select[name=inventory_shop_location]').val(),
                },
                success: function(data){
                    alert(data);
                }
            });
        }

        function transfer_inventory(){
            $.ajax({
                url: '{{route('admin/shops/products/transfer_inventory', array('id'=>$product_details->id))}}',
                type: "post",
                data: {
                    'quantity':      $('input[name=inventory_transfer_quantity]').val(),
                    'old_location_id': $('select[name=transfer_from_location]').val(),
                    'new_location_id':   $('select[name=transfer_to_location]').val(),
                },
                success: function(data){
                    alert(data);
                }
            });
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