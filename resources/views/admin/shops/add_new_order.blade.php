@extends('admin.layouts.main')

@section('pageLevelPlugins')
    <link href="{{ asset('assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('themeGlobalStyle')
    <link href="{{ asset('assets/global/css/components-rounded.min.css') }}" rel="stylesheet" id="style_components" type="text/css" />
    <link href="{{ asset('assets/global/css/plugins.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/jquery-notific8/jquery.notific8.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/pages/css/invoice.min.css') }}" rel="stylesheet" type="text/css" />
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
                <!-- Begin: life time stats -->
                <div class="portlet light portlet-fit portlet-datatable bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-settings font-dark"></i>
                            <span class="caption-subject font-dark sbold uppercase"> Order #{{@$order_number}}
                                <span class="hidden-xs">| {{@$order_date}} </span>
                            </span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="tabbable-line">
                            <ul class="nav nav-tabs nav-tabs-lg">
                                <li class="active">
                                    <a href="#tab_1" data-toggle="tab"> Details </a>
                                </li>
                                <li>
                                    <a href="#tab_2" data-toggle="tab"> Invoice </a>
                                </li>
                                <li>
                                    <a href="#tab_3" data-toggle="tab"> Order Notes
                                        <span class="badge badge-success">4</span></a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_1">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12">
                                            <div class="portlet yellow-crusta box">
                                                <div class="portlet-title">
                                                    <div class="caption">
                                                        <i class="fa fa-cogs"></i>Order Details </div>
                                                    <div class="actions">
                                                        <a href="javascript:;" class="btn btn-default btn-sm">
                                                            <i class="fa fa-pencil"></i> Edit </a>
                                                    </div>
                                                </div>
                                                <div class="portlet-body">
                                                    <div class="row static-info">
                                                        <div class="col-md-5 name"> Order #: </div>
                                                        <div class="col-md-7 value" data-title="order_det-no"> - </div>
                                                    </div>
                                                    <div class="row static-info">
                                                        <div class="col-md-5 name"> Order Date & Time: </div>
                                                        <div class="col-md-7 value" data-title="order_det-date_time"> - </div>
                                                    </div>
                                                    <div class="row static-info">
                                                        <div class="col-md-5 name"> Order Status: </div>
                                                        <div class="col-md-7 value" data-title="order_det-status">
                                                            <span class="label label-success"> - </span>
                                                        </div>
                                                    </div>
                                                    <div class="row static-info">
                                                        <div class="col-md-5 name"> Grand Total: </div>
                                                        <div class="col-md-7 value" data-title="order_det-total_price"> - </div>
                                                    </div>
                                                    <div class="row static-info">
                                                        <div class="col-md-5 name"> Payment Information: </div>
                                                        <div class="col-md-7 value" data-title="order_det-payment_method"> - </div>
                                                    </div>
                                                    <input type="hidden" name="order_number_id" value="{{$orderID}}" id="order_number_id" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="portlet blue-hoki box">
                                                <div class="portlet-title">
                                                    <div class="caption">
                                                        <i class="fa fa-cogs"></i>Customer Information </div>
                                                    <div class="actions">
                                                        <a data-toggle="modal" href="#customer_search_box" class="btn btn-default btn-sm">
                                                            <i class="fa fa-pencil"></i> Search Registered Customer </a>
                                                    </div>
                                                </div>
                                                <div class="portlet-body">
                                                    <div class="row static-info">
                                                        <div class="col-md-5 name"> Customer Name: </div>
                                                        <div class="col-md-7 value" data-title="customer_info-name"> - </div>
                                                    </div>
                                                    <div class="row static-info">
                                                        <div class="col-md-5 name"> Email: </div>
                                                        <div class="col-md-7 value" data-title="customer_info-email"> - </div>
                                                    </div>
                                                    <div class="row static-info">
                                                        <div class="col-md-5 name"> City: </div>
                                                        <div class="col-md-7 value" data-title="customer_info-city"> - </div>
                                                    </div>
                                                    <div class="row static-info">
                                                        <div class="col-md-5 name"> State: </div>
                                                        <div class="col-md-7 value" data-title="customer_info-state"> - </div>
                                                    </div>
                                                    <div class="row static-info">
                                                        <div class="col-md-5 name"> Phone Number: </div>
                                                        <div class="col-md-7 value" data-title="customer_info-phone_no"> - </div>
                                                    </div>
                                                    <input type="hidden" value="-1" name="buyerID" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12">
                                            <div class="portlet green-meadow box">
                                                <div class="portlet-title">
                                                    <div class="caption">
                                                        <i class="fa fa-cogs"></i>Billing Address </div>
                                                    <div class="tools">
                                                        <a class="expand" href="javascript:;" data-original-title="" title=""> </a>
                                                    </div>
                                                    <div class="actions">
                                                        <a href="javascript:;" class="btn btn-default btn-sm">
                                                            <i class="fa fa-pencil"></i> Edit </a>
                                                    </div>
                                                </div>
                                                <div class="portlet-body"  style="display:none;">
                                                    <div class="row static-info">
                                                        <div class="col-md-12 value" data-title="order_bill_address">
                                                            <br>
                                                            <br>
                                                            <br>
                                                            <br>
                                                            <br>
                                                            <br>
                                                            <br>
                                                        </div>
                                                        <input type="hidden" name="buyerBillAddress" value="-1" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="portlet red-sunglo box">
                                                <div class="portlet-title">
                                                    <div class="caption">
                                                        <i class="fa fa-cogs"></i>Shipping Address </div>
                                                    <div class="tools">
                                                        <a class="expand" href="javascript:;" data-original-title="" title=""> </a>
                                                    </div>
                                                    <div class="actions">
                                                        <a href="javascript:;" class="btn btn-default btn-sm">
                                                            <i class="fa fa-pencil"></i> Edit </a>
                                                    </div>
                                                </div>
                                                <div class="portlet-body" style="display:none;">
                                                    <div class="row static-info">
                                                        <div class="col-md-12 value" data-title="order_ship_address">
                                                            <br>
                                                            <br>
                                                            <br>
                                                            <br>
                                                            <br>
                                                            <br>
                                                            <br>
                                                        </div>
                                                        <input type="hidden" name="buyerShipAddress" value="-1" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12">
                                            <div class="portlet grey-cascade box">
                                                <div class="portlet-title">
                                                    <div class="caption">
                                                        <i class="fa fa-cogs"></i>Shopping Cart </div>
                                                    <div class="actions">
                                                        <a data-toggle="modal" href="#product_search_box" class="btn btn-default btn-sm">
                                                            <i class="fa fa-pencil"></i> Edit </a>
                                                    </div>
                                                </div>
                                                <div class="portlet-body">
                                                    <div class="table-responsive">
                                                        <form name="all_order_lines" id="all_order_lines" action="{{route('admin/shops/save_new_order')}}" method="post">
                                                            <table class="table table-hover table-bordered table-striped" id="order_line_items">
                                                                <thead>
                                                                <tr>
                                                                    <th> Product </th>
                                                                    <th> Item Status </th>
                                                                    <th> Cost Price </th>
                                                                    <th> Sell Price </th>
                                                                    <th> Quantity </th>
                                                                    <th> VAT Percentage </th>
                                                                    <th> VAT Amount </th>
                                                                    <th> Discount Amount </th>
                                                                    <th> Total </th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>

                                                                </tbody>
                                                            </table>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6"> </div>
                                        <div class="col-md-6">
                                            <div class="well">
                                                <div class="row static-info align-reverse">
                                                    <div class="col-md-8 name"> Sub Total: </div>
                                                    <div class="col-md-3 value"> <span class="all_sub_total">0.00</span> <span class="currency_code help-inline"></span> </div>
                                                </div>
                                                <div class="row static-info align-reverse">
                                                    <div class="col-md-8 name"> Shipping Cost: </div>
                                                    <div class="col-md-3 value"> <span class="all_shipping_cost">0.00</span> <span class="currency_code help-inline"></span></div>
                                                </div>
                                                <div class="row static-info align-reverse">
                                                    <div class="col-md-8 name"> Total Discount: </div>
                                                    <div class="col-md-3 value"> <span class="all_total_discount">0.00</span> <span class="currency_code help-inline"></span></div>
                                                </div>
                                                <div class="row static-info align-reverse">
                                                    <div class="col-md-8 name"> Total Paid: </div>
                                                    <div class="col-md-3 value"> <span class="all_total_paid">0.00</span> <span class="currency_code help-inline"></span></div>
                                                </div>
                                            </div>
                                            <div class="form">
                                                <div class="form-actions right">
                                                    <button class="btn default" type="button">Cancel Order</button>
                                                    <button class="btn green" type="submit" onclick="$('#all_order_lines').submit()">Save Order</button>
                                                    <button class="btn blue" type="submit" onclick="$('#all_order_lines').submit()">Pay Order</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab_2">
                                    <div class="invoice">
                                        <div class="row invoice-logo">
                                            <div class="col-xs-6 invoice-logo-space">
                                                <img src="../assets/pages/media/invoice/walmart.png" class="img-responsive" alt="" /> </div>
                                            <div class="col-xs-6">
                                                <p> #5652256 / 28 Feb 2013
                                                    <span class="muted"> Consectetuer adipiscing elit </span>
                                                </p>
                                            </div>
                                        </div>
                                        <hr/>
                                        <div class="row">
                                            <div class="col-xs-4">
                                                <h3>Client:</h3>
                                                <ul class="list-unstyled">
                                                    <li> John Doe </li>
                                                    <li> Mr Nilson Otto </li>
                                                    <li> FoodMaster Ltd </li>
                                                    <li> Madrid </li>
                                                    <li> Spain </li>
                                                    <li> 1982 OOP </li>
                                                </ul>
                                            </div>
                                            <div class="col-xs-4">
                                                <h3>About:</h3>
                                                <ul class="list-unstyled">
                                                    <li> Drem psum dolor sit amet </li>
                                                    <li> Laoreet dolore magna </li>
                                                    <li> Consectetuer adipiscing elit </li>
                                                    <li> Magna aliquam tincidunt erat volutpat </li>
                                                    <li> Olor sit amet adipiscing eli </li>
                                                    <li> Laoreet dolore magna </li>
                                                </ul>
                                            </div>
                                            <div class="col-xs-4 invoice-payment">
                                                <h3>Payment Details:</h3>
                                                <ul class="list-unstyled">
                                                    <li>
                                                        <strong>V.A.T Reg #:</strong> 542554(DEMO)78 </li>
                                                    <li>
                                                        <strong>Account Name:</strong> FoodMaster Ltd </li>
                                                    <li>
                                                        <strong>SWIFT code:</strong> 45454DEMO545DEMO </li>
                                                    <li>
                                                        <strong>V.A.T Reg #:</strong> 542554(DEMO)78 </li>
                                                    <li>
                                                        <strong>Account Name:</strong> FoodMaster Ltd </li>
                                                    <li>
                                                        <strong>SWIFT code:</strong> 45454DEMO545DEMO </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <table class="table table-striped table-hover">
                                                    <thead>
                                                    <tr>
                                                        <th> # </th>
                                                        <th> Item </th>
                                                        <th class="hidden-xs"> Description </th>
                                                        <th class="hidden-xs"> Quantity </th>
                                                        <th class="hidden-xs"> Unit Cost </th>
                                                        <th> Total </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td> 1 </td>
                                                        <td> Hardware </td>
                                                        <td class="hidden-xs"> Server hardware purchase </td>
                                                        <td class="hidden-xs"> 32 </td>
                                                        <td class="hidden-xs"> $75 </td>
                                                        <td> $2152 </td>
                                                    </tr>
                                                    <tr>
                                                        <td> 2 </td>
                                                        <td> Furniture </td>
                                                        <td class="hidden-xs"> Office furniture purchase </td>
                                                        <td class="hidden-xs"> 15 </td>
                                                        <td class="hidden-xs"> $169 </td>
                                                        <td> $4169 </td>
                                                    </tr>
                                                    <tr>
                                                        <td> 3 </td>
                                                        <td> Foods </td>
                                                        <td class="hidden-xs"> Company Anual Dinner Catering </td>
                                                        <td class="hidden-xs"> 69 </td>
                                                        <td class="hidden-xs"> $49 </td>
                                                        <td> $1260 </td>
                                                    </tr>
                                                    <tr>
                                                        <td> 3 </td>
                                                        <td> Software </td>
                                                        <td class="hidden-xs"> Payment for Jan 2013 </td>
                                                        <td class="hidden-xs"> 149 </td>
                                                        <td class="hidden-xs"> $12 </td>
                                                        <td> $866 </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-4">
                                                <div class="well">
                                                    <address>
                                                        <strong>Loop, Inc.</strong>
                                                        <br/> 795 Park Ave, Suite 120
                                                        <br/> San Francisco, CA 94107
                                                        <br/>
                                                        <abbr title="Phone">P:</abbr> (234) 145-1810 </address>
                                                    <address>
                                                        <strong>Full Name</strong>
                                                        <br/>
                                                        <a href="mailto:#"> first.last@email.com </a>
                                                    </address>
                                                </div>
                                            </div>
                                            <div class="col-xs-8 invoice-block">
                                                <ul class="list-unstyled amounts">
                                                    <li>
                                                        <strong>Sub - Total amount:</strong> $9265 </li>
                                                    <li>
                                                        <strong>Discount:</strong> 12.9% </li>
                                                    <li>
                                                        <strong>VAT:</strong> ----- </li>
                                                    <li>
                                                        <strong>Grand Total:</strong> $12489 </li>
                                                </ul>
                                                <br/>
                                                <a class="btn btn-lg blue hidden-print margin-bottom-5" onclick="javascript:window.print();"> Print
                                                    <i class="fa fa-print"></i>
                                                </a>
                                                <a class="btn btn-lg green hidden-print margin-bottom-5"> Submit Your Invoice
                                                    <i class="fa fa-check"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab_3">
                                    <div class="table-container">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End: life time stats -->
            </div>

            <div class="modal fade draggable-modal" id="product_search_box" tabindex="-1" role="basic" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                            <h4 class="modal-title">Search Products</h4>
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
                                                <select id="inventory_product" name="inventory_product" class="form-control js-data-example-ajax">
                                                    <option value="" selected="selected">Select...</option>
                                                </select> </div>
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
                                                <input type="text" class="btn-group form-control input-small" id="inventory_entry_price" name="inventory_entry_price" value="0" readonly disabled /> <span class="price_currency help-inline"></span> </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">List Price</label>
                                        <div class="col-md-7">
                                            <div class="input-icon right">
                                                <i class="fa"></i>
                                                <input type="text" class="btn-group form-control input-small" value="0" name="inventory_list_price" readonly disabled /> <span class="price_currency help-inline"></span> </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                            <button type="button" class="btn green submit_form_2" onclick="add_update_order_line_items(-1,-1,-1)">Add inventory</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>

            <div class="modal fade draggable-modal" id="customer_search_box" tabindex="-1" role="basic" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                            <h4 class="modal-title">Search Customers</h4>
                        </div>
                        <div class="modal-body">
                            <form action="#" id="new_inventory" name="new_inventory" class="form-horizontal">
                                <div class="form-body">
                                    <div class="alert alert-danger display-hide">
                                        <button class="close" data-close="alert"></button> You have some form errors. Please check below. </div>
                                    <div class="alert alert-success display-hide">
                                        <button class="close" data-close="alert"></button> Your form validation is successful! </div>
                                    <div class="form-group  margin-top-20">
                                        <label class="control-label col-md-4">Customer Name
                                            <span class="required"> * </span>
                                        </label>
                                        <div class="col-md-7">
                                            <div class="input-icon right">
                                                <i class="fa"></i>
                                                <select id="find_customer_name" name="find_customer_name" class="form-control js-data-users-ajax">
                                                    <option value="" selected="selected">Select...</option>
                                                </select> </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                            <button type="button" class="btn green submit_form_2" onclick="javascript: get_customer_information();">Select Customer</button>
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
    <script src="{{ asset('assets/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
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

        var FormValidation = function () {

            var handleValidation1 = function() {
                var form1 = $('#new_shop');
                var error1 = $('.alert-danger', form1);
                var success1 = $('.alert-success', form1);

                form1.validate({
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block help-block-error', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    ignore: "",  // validate all fields including form hidden input
                    rules: {
                        shop_name: {
                            minlength: 3,
                            required: true
                        },
                        shop_email: {
                            email: true,
                            required: true
                        },
                        shop_reg_no: {
                            minlength: 5,
                            required: true,
                        },
                        shop_bank_acc: {
                            minlength: 5,
                            required: true
                        },
                        shop_phone: {
                            minlength: 5,
                            required: true
                        },
                        shop_fax: {
                            minlength: 5,
                            required: true,
                        },
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
                        add_new_shop(); // submit the form
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
        });

        var ComponentsSelect2 = function() {

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

                // @see https://select2.github.io/examples.html#data-ajax
                function formatRepo(repo) {
                    if (repo.loading) return repo.text;

                    var markup = "<div class='select2-result-repository clearfix'>" +
                            "<div class='select2-result-repository__avatar'><img src='" + repo.product_image_url + "' /></div>" +
                            "<div class='select2-result-repository__meta'>" +
                            "<div class='select2-result-repository__title'>" + repo.product_name + "</div>";

                    if (repo.description) {
                        markup += "<div class='select2-result-repository__description'>" + repo.description + "</div>";
                    }

                    markup += "<div class='select2-result-repository__statistics'>" +
                            "<div class='select2-result-repository__forks'><span class='glyphicon glyphicon-flash'></span> " + repo.category + "</div>" +
                            "<div class='select2-result-repository__stargazers'><span class='glyphicon glyphicon-star'></span> " + repo.manufacturer + "</div>" +
                            "</div>" +
                            "</div></div>";

                    return markup;
                }

                function formatRepoSelection(repo) {
                    // we add product price to the form
                    $('input[name=inventory_list_price]').val(repo.list_price);
                    $('input[name=inventory_entry_price]').val(repo.entry_price);
                    $('.price_currency').html(repo.currency);

                    return repo.full_name || repo.text;
                }

                $(".js-data-example-ajax").select2({
                    width: "off",
                    ajax: {
                        url: "{{ route('admin/shops/products/ajax_get') }}",
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
                    templateResult: formatRepo,
                    templateSelection: formatRepoSelection
                });

                // @see https://select2.github.io/examples.html#data-ajax
                function formatUserData(repo) {
                    if (repo.loading) return repo.text;

                    var markup = "<div class='select2-result-repository clearfix'>" +
                            "<div class='select2-result-repository__avatar'><img src='" + repo.product_image_url + "' /></div>" +
                            "<div class='select2-result-repository__meta'>" +
                            "<div class='select2-result-repository__title'>" + repo.first_name + " " + repo.middle_name + " " + repo.last_name + "</div>";

                    if (repo.description) {
                        markup += "<div class='select2-result-repository__description'>" + repo.description + "</div>";
                    }

                    markup += "<div class='select2-result-repository__statistics'>" +
                            "<div class='select2-result-repository__forks'><span class='glyphicon glyphicon-flash'></span> " + repo.email + "</div>";

                    markup += "<div class='select2-result-repository__stargazers'><span class='glyphicon glyphicon-star'></span> " + repo.city + ", " + repo.region + "</div>" +
                            "</div>" +
                            "</div></div>";

                    return markup;
                }

                function formatUserDataSelection(repo) {
                    // we add product price to the form
                    $('input[name=inventory_list_price]').val(repo.list_price);
                    $('input[name=inventory_entry_price]').val(repo.entry_price);
                    $('.price_currency').html(repo.currency);

                    if (repo.first_name==null && repo.first_name==null && repo.first_name==null){
                        var full_name = null;
                    }
                    else{
                        var full_name = repo.first_name + " " + repo.middle_name + " " + repo.last_name;
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

        if (App.isAngularJsApp() === false) {
            jQuery(document).ready(function() {
                ComponentsSelect2.init();
            });
        }

        function add_new_order(){
            $.ajax({
                url: '{{route('admin/shops/locations/add')}}',
                type: "post",
                data: {
                    'name':         $('input[name=shop_name]').val(),
                    'bank_acc_no':  $('input[name=shop_bank_acc]').val(),
                    'phone':        $('input[name=shop_phone]').val(),
                    'fax':          $('input[name=shop_fax]').val(),
                    'email':        $('input[name=shop_email]').val(),
                    'registered_no':$('input[name=shop_reg_no]').val(),

                    'address1':     $('input[name=shop_address1]').val(),
                    'address2':     $('input[name=shop_address2]').val(),
                    'city':         $('input[name=shop_city]').val(),
                    'region':       $('input[name=shop_region]').val(),
                    'postal_code':  $('input[name=shop_postal_code]').val(),
                    'country_id':   $('select[name=shop_country]').val(),
                },
                success: function(data){
                    alert(data);
                }
            });
        }

        function get_order_details(orderID){
            // here we make the load items button
            $.ajax({
                url: '{{ route('admin/shops/orders/ajax_get_details') }}',
                type: "post",
                data: {
                    'id':orderID,
                },
                success: function(data){
                    format_order_details_data(data);
                }
            });
        }

        function format_order_details_data(order_details){
            //alert(order_details.order_no);
            $("div").find("[data-title='order_det-no']").html(order_details.order_no);
            $("div").find("[data-title='order_det-date_time']").html(order_details.order_date_time);
            $("div").find("[data-title='order_det-status']").html(order_details.order_status);
            $("div").find("[data-title='order_det-total_price']").html(order_details.order_total_price);
            $("div").find("[data-title='order_det-payment_method']").html(order_details.order_payment_info);
        }

        function get_customer_information(customer_id){
            // here we make the load items button
            if ($('select[name=find_customer_name]').val()!=""){
                customer_id = $('select[name=find_customer_name]').val();
            }

            $.ajax({
                url: '{{ route('admin/users/ajax_get_info') }}',
                type: "post",
                data: {
                    'id': customer_id,
                },
                success: function(data){
                    format_customer_information_data(data);
                }
            });
        }

        function format_customer_information_data(order_details){
            //alert(order_details.order_no);
            $("div").find("[data-title='customer_info-name']").html(order_details.full_name);
            $("div").find("[data-title='customer_info-email']").html(order_details.email);
            $("div").find("[data-title='customer_info-city']").html(order_details.city);
            $("div").find("[data-title='customer_info-state']").html(order_details.state);
            $("div").find("[data-title='customer_info-phone_no']").html(order_details.phone_number);

            $('#customer_search_box').modal('hide');
        }

        function get_customer_bill_address(memberID){
            // here we make the load items button
            var billAddress = $('input[name=buyerBillAddress]').val();

            $.ajax({
                url: '{{ route('admin/users/ajax_get_bill_address') }}',
                type: "post",
                data: {
                    'addressID': billAddress,
                    'memberID': memberID,
                },
                success: function(data){
                    format_customer_address_data(data, 'order_bill_address');
                }
            });
        }

        function get_customer_ship_address(memberID){
            // here we make the load items button
            var shipAddress = $('input[name=buyerBillAddress]').val();

            $.ajax({
                url: '{{ route('admin/users/ajax_get_ship_address') }}',
                type: "post",
                data: {
                    'id': shipAddress,
                    'memberID': memberID,
                },
                success: function(data){
                    format_customer_address_data(data, 'order_ship_address');
                }
            });
        }

        function format_customer_address_data(bill_address, field){
            //alert(order_details.order_no);
            $("div").find("[data-title='" + field + "']").html(bill_address.full_address);
        }

        function add_update_order_line_items(lineID, sell_price, discount_amount){
            var productID = $('select[name=inventory_product]').val();
            var quantity  = $('input[name=inventory_quantity]').val();
            var orderID = $('input[name=order_number_id]').val();

            if (lineID!=-1){
                var quantity    = $('input[name=quantity_'+ lineID +']').val();
                var sell_price  = $('input[name=sell_price_'+ lineID +']').val();
                var discount_amount = $('input[name=discount_amount_'+ lineID +']').val();
            }

            $.ajax({
                url: '{{route('admin/shops/orders/add_update_line_items')}}',
                type: "post",
                data: {
                    'productID':        productID,
                    'quantity':         quantity,
                    'sell_price':       sell_price,
                    'discount_amount':  discount_amount,
                    'orderID':          orderID,
                    'lineID':           lineID,
                },
                success: function (orderLine) {
                    //get_order_line_items();
                    var new_line = '<tr data-info="'+orderLine.item_line+'">'+
                            '<td><a href="'+orderLine.product_link+'">' + orderLine.product_name + '</a></td>'+
                            '<td><span class="label label-sm label-success">'+orderLine.inventory_status+'</span></td>'+
                            '<td> '+orderLine.cost_price+'<span class="help-inline">'+orderLine.currency+'</span> </td>'+
                            '<td> <input type="text" class="form-control input-inline input-xsmall lineSellPrice" value="'+orderLine.sell_price+'" name="sell_price_'+orderLine.item_line+'"><span class="help-inline">'+orderLine.currency+'</span> </td>'+
                            '<td> <input type="text" class="form-control input-xsmall lineQuantity" name="quantity_'+orderLine.item_line+'" value="'+orderLine.quantity+'"> </td>'+
                            '<td> '+orderLine.vat+'% </td>'+
                            '<td> <span class="vat_amount">'+orderLine.vat_value+'</span> <span class="help-inline">'+orderLine.currency+'</span> </td>'+
                            '<td> <input type="text" class="form-control input-xsmall input-inline lineDiscount" name="discount_'+orderLine.item_line+'" value="'+orderLine.discount_value+'"><span class="help-inline">%</span> </td>'+
                            '<td> <span class="total_amount">'+orderLine.total_amount+'</span><span class="help-inline">'+orderLine.currency+'</span> </td>'+
                            '<input type="hidden" name="orderLineSellPrice_'+orderLine.item_line+'" value="'+ orderLine.sell_price +'" />'+
                            '<input type="hidden" name="orderLineQuantity_'+orderLine.item_line+'" value="'+ orderLine.quantity +'" />'+
                            '<input type="hidden" name="orderLineVAT_'+orderLine.item_line+'" value="'+ orderLine.vat +'" />'+
                            '<input type="hidden" name="orderLineDiscount_'+orderLine.item_line+'" value="0" />'+
                            '</tr>';

                    if (orderLine.orderID){
                        $('input[name=order_number_id]').val(orderLine.orderID);
                        get_order_details(orderLine.orderID);
                    }

                    $("#order_line_items tbody").append(new_line);

                    $('input[name=inventory_quantity]').val('');
                    $('input[name=inventory_entry_price]').val('');
                    $('input[name=inventory_list_price]').val('');
                    //$('#select2-inventory_product-container').attr('title', 'Select...');
                    //$('#select2-inventory_product-container').html('Select...');

                    $('span.currency_code').html(orderLine.currency);

                    recalculate_all_lines();
                }
            });

            $('#product_search_box').modal('hide');
        }

        function get_order_line_items(){
            $.ajax({
                url: '{{route('admin/shops/orders/ajax_get_line_items',['id' => $orderID])}}',
                type: "post",
                data: {
                    'orderID': $('input[name=order_number_id]').val(),
                },
                success: function (data) {
                    format_order_line_items(data);
                }
            });
        }

        function format_order_line_items(data){
            $("#order_line_items tbody").html('');

            $.each(data, function(key, value){
                $("#order_line_items tbody").append(value);
            });
        }

        $(document).ready(function(){
            var buyerID = $("input[name=buyerID]").val();
            var orderID = $('input[name=order_number_id]').val();

            get_order_details(orderID);

            get_customer_information(buyerID);
            get_customer_bill_address(buyerID);
            get_customer_ship_address(buyerID)

        });

        $('body').on('keyup','input.lineSellPrice',function(){
            var topContainer = $(this).closest('tr');
            var lineNo = topContainer.attr('data-info');
            $('input[name=orderLineSellPrice_'+lineNo+']').val($(this).val());

            recalculate_line(lineNo);
        });

        $('body').on('keyup','input.lineQuantity',function(){
            var topContainer = $(this).closest('tr');
            var lineNo = topContainer.attr('data-info');
            $('input[name=orderLineQuantity_'+lineNo+']').val($(this).val());

            recalculate_line(lineNo);
        });

        $('body').on('keyup','input.lineDiscount',function(){
            var topContainer = $(this).closest('tr');
            var lineNo = topContainer.attr('data-info');
            $('input[name=orderLineDiscount_'+lineNo+']').val($(this).val());

            recalculate_line(lineNo);
        });

        function recalculate_line(lineNo){
            var container = $('tr[data-info='+lineNo+']');
            var price =     $('input[name=orderLineSellPrice_'+lineNo+']').val()*100;
            var vat =       $('input[name=orderLineVAT_'+lineNo+']').val();
            var quantity =  $('input[name=orderLineQuantity_'+lineNo+']').val();
            var discount =  $('input[name=orderLineDiscount_'+lineNo+']').val();

            var vatAmount = (price*quantity)*(vat/100);
            var totalAmount = (price*quantity) + vatAmount;
            totalAmount = totalAmount - (totalAmount*(discount/100));

            totalAmount = Math.round(totalAmount);
            vatAmount = Math.round(vatAmount);

            container.find('span.vat_amount').html(vatAmount/100);
            container.find('span.total_amount').html(totalAmount/100);

            recalculate_all_lines();
        }

        function recalculate_all_lines(){
            var allVatAmount = 0;
            var allSubTotal = 0;
            var allDiscount = 0;

            $('#order_line_items > tbody tr').each(function() {
                $this = $(this);

                var lineNo = $this.attr('data-info');
                var price =     $('input[name=orderLineSellPrice_'+lineNo+']').val()*100;
                var vat =       $('input[name=orderLineVAT_'+lineNo+']').val();
                var quantity =  $('input[name=orderLineQuantity_'+lineNo+']').val();
                var discount =  $('input[name=orderLineDiscount_'+lineNo+']').val();

                var vatAmount = (price*quantity)*(vat/100);
                var totalAmount = (price*quantity) + vatAmount;
                var discount = totalAmount*(discount/100);

                allSubTotal+= Math.round(totalAmount);
                allVatAmount+= Math.round(vatAmount);
                allDiscount+=discount;
            });

            var totalPaid = allSubTotal-allDiscount;
            allSubTotal = parseFloat(Math.round(allSubTotal)/100).toFixed(2);
            allDiscount = parseFloat(Math.round(allDiscount)/100).toFixed(2);
            totalPaid = parseFloat(Math.round(totalPaid)/100).toFixed(2);


            $(".all_sub_total").html(allSubTotal);
            $(".all_total_discount").html(allDiscount);
            $(".all_total_paid").html(totalPaid);
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