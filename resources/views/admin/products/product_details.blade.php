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
                <form class="form-horizontal form-row-seperated" action="#">
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
                                            <a href="javascript:;"> New Inventory </a>
                                        </li>
                                        <li>
                                            <a href="javascript:;"> Duplicate </a>
                                        </li>
                                        <li>
                                            <a href="javascript:;"> Delete </a>
                                        </li>
                                        <li class="dropdown-divider"> </li>
                                        <li>
                                            <a href="javascript:;"> Print </a>
                                        </li>
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
                                        <a href="#tab_reviews" data-toggle="tab"> Reviews
                                            <span class="badge badge-success"> 3 </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#tab_history" data-toggle="tab"> History </a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_general">
                                        <div class="form-body">
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
                                                <label class="col-md-2 control-label">Description:
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-10">
                                                    <textarea class="form-control" name="product_description">{{ $product_details->description }}</textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Categories:
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-10">
                                                    <div class="form-control height-auto">
                                                        <div class="scroller" style="height:275px;" data-always-visible="1">
                                                            <ul class="list-unstyled">
                                                            @foreach ($categories as $category)
                                                                <li>
                                                                    <label>
                                                                        <input type="checkbox" name="product_categories[]" value="{{ $category->id }}">{{ $category->name }}</label>
                                                                </li>
                                                            @endforeach
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <span class="help-block"> select one or more categories </span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Available Date:</label>
                                                <div class="col-md-10">
                                                    <div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="mm/dd/yyyy">
                                                        <input type="text" class="form-control" name="product_available_from">
                                                        <span class="input-group-addon"> to </span>
                                                        <input type="text" class="form-control" name="product_available_to"></div>
                                                    <span class="help-block"><button class="btn btn-success" id="update_availability_now">
                                                            <i class="fa fa-check"></i> Update Availability</button></span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Price:
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-10">
                                                    <input type="text" class="form-control input-medium" name="product_price" placeholder="Product Price" value=""> </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Currency:
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-10">
                                                    <select class="table-group-action-input form-control input-small" name="product_currency">
                                                        <option value="">Select...</option>
                                                        @foreach ($currencies as $currency)
                                                            <option value="{{$currency->id}}" >{{ $currency->currency_code }}</option>
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
                                                    <input type="text" class="form-control" name="product_barcode" placeholder="Bar Code" value="{{ $product_details->barcode }}"> </div>
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
                                    </div>
                                    <div class="tab-pane" id="tab_meta">
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
                                            <tr>
                                                <td>
                                                    <a href="../assets/pages/media/works/img1.jpg" class="fancybox-button" data-rel="fancybox-button">
                                                        <img class="img-responsive" src="{{ asset('assets/pages/media/works/img1.jpg') }}" alt=""> </a>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="product[images][1][label]" value="Thumbnail image"> </td>
                                                <td>
                                                    <input type="text" class="form-control" name="product[images][1][sort_order]" value="1"> </td>
                                                <td>
                                                    <label>
                                                        <input type="radio" name="product[images][1][image_type]" value="1"> </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input type="radio" name="product[images][1][image_type]" value="2"> </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input type="radio" name="product[images][1][image_type]" value="3" checked> </label>
                                                </td>
                                                <td>
                                                    <a href="javascript:;" class="btn btn-default btn-sm">
                                                        <i class="fa fa-times"></i> Remove </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <a href="../assets/pages/media/works/img2.jpg" class="fancybox-button" data-rel="fancybox-button">
                                                        <img class="img-responsive" src="{{ asset('assets/pages/media/works/img2.jpg') }}" alt=""> </a>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="product[images][2][label]" value="Product image #1"> </td>
                                                <td>
                                                    <input type="text" class="form-control" name="product[images][2][sort_order]" value="1"> </td>
                                                <td>
                                                    <label>
                                                        <input type="radio" name="product[images][2][image_type]" value="1"> </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input type="radio" name="product[images][2][image_type]" value="2" checked> </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input type="radio" name="product[images][2][image_type]" value="3"> </label>
                                                </td>
                                                <td>
                                                    <a href="javascript:;" class="btn btn-default btn-sm">
                                                        <i class="fa fa-times"></i> Remove </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <a href="../assets/pages/media/works/img3.jpg" class="fancybox-button" data-rel="fancybox-button">
                                                        <img class="img-responsive" src="{{ asset('assets/pages/media/works/img3.jpg') }}" alt=""> </a>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="product[images][3][label]" value="Product image #2"> </td>
                                                <td>
                                                    <input type="text" class="form-control" name="product[images][3][sort_order]" value="1"> </td>
                                                <td>
                                                    <label>
                                                        <input type="radio" name="product[images][3][image_type]" value="1" checked> </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input type="radio" name="product[images][3][image_type]" value="2"> </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input type="radio" name="product[images][3][image_type]" value="3"> </label>
                                                </td>
                                                <td>
                                                    <a href="javascript:;" class="btn btn-default btn-sm">
                                                        <i class="fa fa-times"></i> Remove </a>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="tab-pane" id="tab_reviews">
                                        <div class="table-container">
                                            <table class="table table-striped table-bordered table-hover" id="datatable_reviews">
                                                <thead>
                                                <tr role="row" class="heading">
                                                    <th width="5%"> Review&nbsp;# </th>
                                                    <th width="10%"> Review&nbsp;Date </th>
                                                    <th width="10%"> Customer </th>
                                                    <th width="20%"> Review&nbsp;Content </th>
                                                    <th width="10%"> Status </th>
                                                    <th width="10%"> Actions </th>
                                                </tr>
                                                <tr role="row" class="filter">
                                                    <td>
                                                        <input type="text" class="form-control form-filter input-sm" name="product_review_no"> </td>
                                                    <td>
                                                        <div class="input-group date date-picker margin-bottom-5" data-date-format="dd/mm/yyyy">
                                                            <input type="text" class="form-control form-filter input-sm" readonly name="product_review_date_from" placeholder="From">
                                                                            <span class="input-group-btn">
                                                                                <button class="btn btn-sm default" type="button">
                                                                                    <i class="fa fa-calendar"></i>
                                                                                </button>
                                                                            </span>
                                                        </div>
                                                        <div class="input-group date date-picker" data-date-format="dd/mm/yyyy">
                                                            <input type="text" class="form-control form-filter input-sm" readonly name="product_review_date_to" placeholder="To">
                                                                            <span class="input-group-btn">
                                                                                <button class="btn btn-sm default" type="button">
                                                                                    <i class="fa fa-calendar"></i>
                                                                                </button>
                                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control form-filter input-sm" name="product_review_customer"> </td>
                                                    <td>
                                                        <input type="text" class="form-control form-filter input-sm" name="product_review_content"> </td>
                                                    <td>
                                                        <select name="product_review_status" class="form-control form-filter input-sm">
                                                            <option value="">Select...</option>
                                                            <option value="pending">Pending</option>
                                                            <option value="approved">Approved</option>
                                                            <option value="rejected">Rejected</option>
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
                </form>
            </div>
        </div>
        <!-- END PAGE BASE CONTENT -->
    </div>
@endsection

@section('pageQuickSidebar')
    <a href="javascript:;" class="page-quick-sidebar-toggler">
        <i class="icon-login"></i>
    </a>
    <div class="page-quick-sidebar-wrapper" data-close-on-body-click="false">
        <div class="page-quick-sidebar">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="javascript:;" data-target="#quick_sidebar_tab_1" data-toggle="tab"> Users
                        <span class="badge badge-danger">2</span>
                    </a>
                </li>
                <li>
                    <a href="javascript:;" data-target="#quick_sidebar_tab_2" data-toggle="tab"> Alerts
                        <span class="badge badge-success">7</span>
                    </a>
                </li>
                <li class="dropdown">
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"> More
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu pull-right">
                        <li>
                            <a href="javascript:;" data-target="#quick_sidebar_tab_3" data-toggle="tab">
                                <i class="icon-bell"></i> Alerts </a>
                        </li>
                        <li>
                            <a href="javascript:;" data-target="#quick_sidebar_tab_3" data-toggle="tab">
                                <i class="icon-info"></i> Notifications </a>
                        </li>
                        <li>
                            <a href="javascript:;" data-target="#quick_sidebar_tab_3" data-toggle="tab">
                                <i class="icon-speech"></i> Activities </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="javascript:;" data-target="#quick_sidebar_tab_3" data-toggle="tab">
                                <i class="icon-settings"></i> Settings </a>
                        </li>
                    </ul>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active page-quick-sidebar-chat" id="quick_sidebar_tab_1">
                    <div class="page-quick-sidebar-chat-users" data-rail-color="#ddd" data-wrapper-class="page-quick-sidebar-list">
                        <h3 class="list-heading">Staff</h3>
                        <ul class="media-list list-items">
                            <li class="media">
                                <div class="media-status">
                                    <span class="badge badge-success">8</span>
                                </div>
                                <img class="media-object" src="{{ asset('assets/layouts/layout/img/avatar3.jpg') }}" alt="...">
                                <div class="media-body">
                                    <h4 class="media-heading">Bob Nilson</h4>
                                    <div class="media-heading-sub"> Project Manager </div>
                                </div>
                            </li>
                            <li class="media">
                                <img class="media-object" src="{{ asset('assets/layouts/layout/img/avatar1.jpg') }}" alt="...">
                                <div class="media-body">
                                    <h4 class="media-heading">Nick Larson</h4>
                                    <div class="media-heading-sub"> Art Director </div>
                                </div>
                            </li>
                            <li class="media">
                                <div class="media-status">
                                    <span class="badge badge-danger">3</span>
                                </div>
                                <img class="media-object" src="{{ asset('assets/layouts/layout/img/avatar4.jpg') }}" alt="...">
                                <div class="media-body">
                                    <h4 class="media-heading">Deon Hubert</h4>
                                    <div class="media-heading-sub"> CTO </div>
                                </div>
                            </li>
                            <li class="media">
                                <img class="media-object" src="{{ asset('assets/layouts/layout/img/avatar2.jpg') }}" alt="...">
                                <div class="media-body">
                                    <h4 class="media-heading">Ella Wong</h4>
                                    <div class="media-heading-sub"> CEO </div>
                                </div>
                            </li>
                        </ul>
                        <h3 class="list-heading">Customers</h3>
                        <ul class="media-list list-items">
                            <li class="media">
                                <div class="media-status">
                                    <span class="badge badge-warning">2</span>
                                </div>
                                <img class="media-object" src="{{ asset('assets/layouts/layout/img/avatar6.jpg') }}" alt="...">
                                <div class="media-body">
                                    <h4 class="media-heading">Lara Kunis</h4>
                                    <div class="media-heading-sub"> CEO, Loop Inc </div>
                                    <div class="media-heading-small"> Last seen 03:10 AM </div>
                                </div>
                            </li>
                            <li class="media">
                                <div class="media-status">
                                    <span class="label label-sm label-success">new</span>
                                </div>
                                <img class="media-object" src="{{ asset('assets/layouts/layout/img/avatar7.jpg') }}" alt="...">
                                <div class="media-body">
                                    <h4 class="media-heading">Ernie Kyllonen</h4>
                                    <div class="media-heading-sub"> Project Manager,
                                        <br> SmartBizz PTL </div>
                                </div>
                            </li>
                            <li class="media">
                                <img class="media-object" src="{{ asset('assets/layouts/layout/img/avatar8.jpg') }}" alt="...">
                                <div class="media-body">
                                    <h4 class="media-heading">Lisa Stone</h4>
                                    <div class="media-heading-sub"> CTO, Keort Inc </div>
                                    <div class="media-heading-small"> Last seen 13:10 PM </div>
                                </div>
                            </li>
                            <li class="media">
                                <div class="media-status">
                                    <span class="badge badge-success">7</span>
                                </div>
                                <img class="media-object" src="{{ asset('assets/layouts/layout/img/avatar9.jpg') }}" alt="...">
                                <div class="media-body">
                                    <h4 class="media-heading">Deon Portalatin</h4>
                                    <div class="media-heading-sub"> CFO, H&D LTD </div>
                                </div>
                            </li>
                            <li class="media">
                                <img class="media-object" src="{{ asset('assets/layouts/layout/img/avatar10.jpg') }}" alt="...">
                                <div class="media-body">
                                    <h4 class="media-heading">Irina Savikova</h4>
                                    <div class="media-heading-sub"> CEO, Tizda Motors Inc </div>
                                </div>
                            </li>
                            <li class="media">
                                <div class="media-status">
                                    <span class="badge badge-danger">4</span>
                                </div>
                                <img class="media-object" src="{{ asset('assets/layouts/layout/img/avatar11.jpg') }}" alt="...">
                                <div class="media-body">
                                    <h4 class="media-heading">Maria Gomez</h4>
                                    <div class="media-heading-sub"> Manager, Infomatic Inc </div>
                                    <div class="media-heading-small"> Last seen 03:10 AM </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="page-quick-sidebar-item">
                        <div class="page-quick-sidebar-chat-user">
                            <div class="page-quick-sidebar-nav">
                                <a href="javascript:;" class="page-quick-sidebar-back-to-list">
                                    <i class="icon-arrow-left"></i>Back</a>
                            </div>
                            <div class="page-quick-sidebar-chat-user-messages">
                                <div class="post out">
                                    <img class="avatar" alt="" src="{{ asset('assets/layouts/layout/img/avatar3.jpg') }}" />
                                    <div class="message">
                                        <span class="arrow"></span>
                                        <a href="javascript:;" class="name">Bob Nilson</a>
                                        <span class="datetime">20:15</span>
                                        <span class="body"> When could you send me the report ? </span>
                                    </div>
                                </div>
                                <div class="post in">
                                    <img class="avatar" alt="" src="{{ asset('assets/layouts/layout/img/avatar2.jpg') }}" />
                                    <div class="message">
                                        <span class="arrow"></span>
                                        <a href="javascript:;" class="name">Ella Wong</a>
                                        <span class="datetime">20:15</span>
                                        <span class="body"> Its almost done. I will be sending it shortly </span>
                                    </div>
                                </div>
                                <div class="post out">
                                    <img class="avatar" alt="" src="{{ asset('assets/layouts/layout/img/avatar3.jpg') }}" />
                                    <div class="message">
                                        <span class="arrow"></span>
                                        <a href="javascript:;" class="name">Bob Nilson</a>
                                        <span class="datetime">20:15</span>
                                        <span class="body"> Alright. Thanks! :) </span>
                                    </div>
                                </div>
                                <div class="post in">
                                    <img class="avatar" alt="" src="{{ asset('assets/layouts/layout/img/avatar2.jpg') }}" />
                                    <div class="message">
                                        <span class="arrow"></span>
                                        <a href="javascript:;" class="name">Ella Wong</a>
                                        <span class="datetime">20:16</span>
                                        <span class="body"> You are most welcome. Sorry for the delay. </span>
                                    </div>
                                </div>
                                <div class="post out">
                                    <img class="avatar" alt="" src="{{ asset('assets/layouts/layout/img/avatar3.jpg') }}" />
                                    <div class="message">
                                        <span class="arrow"></span>
                                        <a href="javascript:;" class="name">Bob Nilson</a>
                                        <span class="datetime">20:17</span>
                                        <span class="body"> No probs. Just take your time :) </span>
                                    </div>
                                </div>
                                <div class="post in">
                                    <img class="avatar" alt="" src="{{ asset('assets/layouts/layout/img/avatar2.jpg') }}" />
                                    <div class="message">
                                        <span class="arrow"></span>
                                        <a href="javascript:;" class="name">Ella Wong</a>
                                        <span class="datetime">20:40</span>
                                        <span class="body"> Alright. I just emailed it to you. </span>
                                    </div>
                                </div>
                                <div class="post out">
                                    <img class="avatar" alt="" src="{{ asset('assets/layouts/layout/img/avatar3.jpg') }}" />
                                    <div class="message">
                                        <span class="arrow"></span>
                                        <a href="javascript:;" class="name">Bob Nilson</a>
                                        <span class="datetime">20:17</span>
                                        <span class="body"> Great! Thanks. Will check it right away. </span>
                                    </div>
                                </div>
                                <div class="post in">
                                    <img class="avatar" alt="" src="{{ asset('assets/layouts/layout/img/avatar2.jpg') }}" />
                                    <div class="message">
                                        <span class="arrow"></span>
                                        <a href="javascript:;" class="name">Ella Wong</a>
                                        <span class="datetime">20:40</span>
                                        <span class="body"> Please let me know if you have any comment. </span>
                                    </div>
                                </div>
                                <div class="post out">
                                    <img class="avatar" alt="" src="{{ asset('assets/layouts/layout/img/avatar3.jpg') }}" />
                                    <div class="message">
                                        <span class="arrow"></span>
                                        <a href="javascript:;" class="name">Bob Nilson</a>
                                        <span class="datetime">20:17</span>
                                        <span class="body"> Sure. I will check and buzz you if anything needs to be corrected. </span>
                                    </div>
                                </div>
                            </div>
                            <div class="page-quick-sidebar-chat-user-form">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Type a message here...">
                                    <div class="input-group-btn">
                                        <button type="button" class="btn green">
                                            <i class="icon-paper-clip"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane page-quick-sidebar-alerts" id="quick_sidebar_tab_2">
                    <div class="page-quick-sidebar-alerts-list">
                        <h3 class="list-heading">General</h3>
                        <ul class="feeds list-items">
                            <li>
                                <div class="col1">
                                    <div class="cont">
                                        <div class="cont-col1">
                                            <div class="label label-sm label-info">
                                                <i class="fa fa-check"></i>
                                            </div>
                                        </div>
                                        <div class="cont-col2">
                                            <div class="desc"> You have 4 pending tasks.
                                                        <span class="label label-sm label-warning "> Take action
                                                            <i class="fa fa-share"></i>
                                                        </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col2">
                                    <div class="date"> Just now </div>
                                </div>
                            </li>
                            <li>
                                <a href="javascript:;">
                                    <div class="col1">
                                        <div class="cont">
                                            <div class="cont-col1">
                                                <div class="label label-sm label-success">
                                                    <i class="fa fa-bar-chart-o"></i>
                                                </div>
                                            </div>
                                            <div class="cont-col2">
                                                <div class="desc"> Finance Report for year 2013 has been released. </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col2">
                                        <div class="date"> 20 mins </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <div class="col1">
                                    <div class="cont">
                                        <div class="cont-col1">
                                            <div class="label label-sm label-danger">
                                                <i class="fa fa-user"></i>
                                            </div>
                                        </div>
                                        <div class="cont-col2">
                                            <div class="desc"> You have 5 pending membership that requires a quick review. </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col2">
                                    <div class="date"> 24 mins </div>
                                </div>
                            </li>
                            <li>
                                <div class="col1">
                                    <div class="cont">
                                        <div class="cont-col1">
                                            <div class="label label-sm label-info">
                                                <i class="fa fa-shopping-cart"></i>
                                            </div>
                                        </div>
                                        <div class="cont-col2">
                                            <div class="desc"> New order received with
                                                <span class="label label-sm label-success"> Reference Number: DR23923 </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col2">
                                    <div class="date"> 30 mins </div>
                                </div>
                            </li>
                            <li>
                                <div class="col1">
                                    <div class="cont">
                                        <div class="cont-col1">
                                            <div class="label label-sm label-success">
                                                <i class="fa fa-user"></i>
                                            </div>
                                        </div>
                                        <div class="cont-col2">
                                            <div class="desc"> You have 5 pending membership that requires a quick review. </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col2">
                                    <div class="date"> 24 mins </div>
                                </div>
                            </li>
                            <li>
                                <div class="col1">
                                    <div class="cont">
                                        <div class="cont-col1">
                                            <div class="label label-sm label-info">
                                                <i class="fa fa-bell-o"></i>
                                            </div>
                                        </div>
                                        <div class="cont-col2">
                                            <div class="desc"> Web server hardware needs to be upgraded.
                                                <span class="label label-sm label-warning"> Overdue </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col2">
                                    <div class="date"> 2 hours </div>
                                </div>
                            </li>
                            <li>
                                <a href="javascript:;">
                                    <div class="col1">
                                        <div class="cont">
                                            <div class="cont-col1">
                                                <div class="label label-sm label-default">
                                                    <i class="fa fa-briefcase"></i>
                                                </div>
                                            </div>
                                            <div class="cont-col2">
                                                <div class="desc"> IPO Report for year 2013 has been released. </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col2">
                                        <div class="date"> 20 mins </div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                        <h3 class="list-heading">System</h3>
                        <ul class="feeds list-items">
                            <li>
                                <div class="col1">
                                    <div class="cont">
                                        <div class="cont-col1">
                                            <div class="label label-sm label-info">
                                                <i class="fa fa-check"></i>
                                            </div>
                                        </div>
                                        <div class="cont-col2">
                                            <div class="desc"> You have 4 pending tasks.
                                                        <span class="label label-sm label-warning "> Take action
                                                            <i class="fa fa-share"></i>
                                                        </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col2">
                                    <div class="date"> Just now </div>
                                </div>
                            </li>
                            <li>
                                <a href="javascript:;">
                                    <div class="col1">
                                        <div class="cont">
                                            <div class="cont-col1">
                                                <div class="label label-sm label-danger">
                                                    <i class="fa fa-bar-chart-o"></i>
                                                </div>
                                            </div>
                                            <div class="cont-col2">
                                                <div class="desc"> Finance Report for year 2013 has been released. </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col2">
                                        <div class="date"> 20 mins </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <div class="col1">
                                    <div class="cont">
                                        <div class="cont-col1">
                                            <div class="label label-sm label-default">
                                                <i class="fa fa-user"></i>
                                            </div>
                                        </div>
                                        <div class="cont-col2">
                                            <div class="desc"> You have 5 pending membership that requires a quick review. </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col2">
                                    <div class="date"> 24 mins </div>
                                </div>
                            </li>
                            <li>
                                <div class="col1">
                                    <div class="cont">
                                        <div class="cont-col1">
                                            <div class="label label-sm label-info">
                                                <i class="fa fa-shopping-cart"></i>
                                            </div>
                                        </div>
                                        <div class="cont-col2">
                                            <div class="desc"> New order received with
                                                <span class="label label-sm label-success"> Reference Number: DR23923 </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col2">
                                    <div class="date"> 30 mins </div>
                                </div>
                            </li>
                            <li>
                                <div class="col1">
                                    <div class="cont">
                                        <div class="cont-col1">
                                            <div class="label label-sm label-success">
                                                <i class="fa fa-user"></i>
                                            </div>
                                        </div>
                                        <div class="cont-col2">
                                            <div class="desc"> You have 5 pending membership that requires a quick review. </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col2">
                                    <div class="date"> 24 mins </div>
                                </div>
                            </li>
                            <li>
                                <div class="col1">
                                    <div class="cont">
                                        <div class="cont-col1">
                                            <div class="label label-sm label-warning">
                                                <i class="fa fa-bell-o"></i>
                                            </div>
                                        </div>
                                        <div class="cont-col2">
                                            <div class="desc"> Web server hardware needs to be upgraded.
                                                <span class="label label-sm label-default "> Overdue </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col2">
                                    <div class="date"> 2 hours </div>
                                </div>
                            </li>
                            <li>
                                <a href="javascript:;">
                                    <div class="col1">
                                        <div class="cont">
                                            <div class="cont-col1">
                                                <div class="label label-sm label-info">
                                                    <i class="fa fa-briefcase"></i>
                                                </div>
                                            </div>
                                            <div class="cont-col2">
                                                <div class="desc"> IPO Report for year 2013 has been released. </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col2">
                                        <div class="date"> 20 mins </div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="tab-pane page-quick-sidebar-settings" id="quick_sidebar_tab_3">
                    <div class="page-quick-sidebar-settings-list">
                        <h3 class="list-heading">General Settings</h3>
                        <ul class="list-items borderless">
                            <li> Enable Notifications
                                <input type="checkbox" class="make-switch" checked data-size="small" data-on-color="success" data-on-text="ON" data-off-color="default" data-off-text="OFF"> </li>
                            <li> Allow Tracking
                                <input type="checkbox" class="make-switch" data-size="small" data-on-color="info" data-on-text="ON" data-off-color="default" data-off-text="OFF"> </li>
                            <li> Log Errors
                                <input type="checkbox" class="make-switch" checked data-size="small" data-on-color="danger" data-on-text="ON" data-off-color="default" data-off-text="OFF"> </li>
                            <li> Auto Sumbit Issues
                                <input type="checkbox" class="make-switch" data-size="small" data-on-color="warning" data-on-text="ON" data-off-color="default" data-off-text="OFF"> </li>
                            <li> Enable SMS Alerts
                                <input type="checkbox" class="make-switch" checked data-size="small" data-on-color="success" data-on-text="ON" data-off-color="default" data-off-text="OFF"> </li>
                        </ul>
                        <h3 class="list-heading">System Settings</h3>
                        <ul class="list-items borderless">
                            <li> Security Level
                                <select class="form-control input-inline input-sm input-small">
                                    <option value="1">Normal</option>
                                    <option value="2" selected>Medium</option>
                                    <option value="e">High</option>
                                </select>
                            </li>
                            <li> Failed Email Attempts
                                <input class="form-control input-inline input-sm input-small" value="5" /> </li>
                            <li> Secondary SMTP Port
                                <input class="form-control input-inline input-sm input-small" value="3560" /> </li>
                            <li> Notify On System Error
                                <input type="checkbox" class="make-switch" checked data-size="small" data-on-color="danger" data-on-text="ON" data-off-color="default" data-off-text="OFF"> </li>
                            <li> Notify On SMTP Error
                                <input type="checkbox" class="make-switch" checked data-size="small" data-on-color="warning" data-on-text="ON" data-off-color="default" data-off-text="OFF"> </li>
                        </ul>
                        <div class="inner-content">
                            <button class="btn btn-success">
                                <i class="icon-settings"></i> Save Changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pageFooterInner')
    <div class="page-footer-inner"> 2014 &copy; Metronic by keenthemes.
        <a href="http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes" title="Purchase Metronic just for 27$ and get lifetime updates for free" target="_blank">Purchase Metronic!</a>
    </div>
    <div class="scroll-to-top">
        <i class="icon-arrow-up"></i>
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

                    url : "{{ asset('assets/plugins/plupload/examples/upload.php') }}",

                    filters : {
                        max_file_size : '10mb',
                        mime_types: [
                            {title : "Image files", extensions : "jpg,gif,png"},
                            {title : "Zip files", extensions : "zip"}
                        ]
                    },

                    // Flash settings
                    flash_swf_url : '{{ asset('assets/plugins/plupload/js/Moxie.swf') }}',

                    // Silverlight settings
                    silverlight_xap_url : '{{ asset('assets/plugins/plupload/js/Moxie.xap') }}',

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
                    src: $("#datatable_reviews"),
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
                            "url": "{{ route('admin/shops/products/get_inventory') }}", // ajax source
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

        function add_new_product(){
            $.ajax({
                url: '{{route('admin/shops/products/add')}}',
                type: "post",
                data: {
                    'name':             $('input[name=product_name]').val(),
                    'alternate_name':   $('input[name=product_alternate_name]').val(),
                    'category_id':      $('select[name=product_category]').val(),
                    'brand':            $('input[name=product_brand]').val(),
                    'manufacturer':     $('input[name=product_manufacturer]').val(),
                    'barcode':          $('input[name=product_bar_code]').val(),
                    'vat_rate_id':      $('select[name=product_vat_rate]').val(),
                    'description':      $('textarea[name=product_description]').val(),
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