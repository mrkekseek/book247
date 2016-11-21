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
            <div class="col-md-12 ">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-settings font-dark"></i>
                            <span class="caption-subject font-dark sbold uppercase">Resource Details</span> </div>
                        <div class="tools">
                            <a class="collapse" href="javascript:;" data-original-title="" title=""> </a>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <form class="form-horizontal" role="form" name="resource_details" id="resource_details">
                            <div class="form-body">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Shop/Location Name</label>
                                    <div class="col-md-9">
                                        <input type="text" readonly disabled value="{{$resourceDetails->shop_location->name}}" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Resource Name</label>
                                    <div class="col-md-9">
                                        <input type="text" name="resource_name" value="{{$resourceDetails->name}}" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Description</label>
                                    <div class="col-md-9">
                                        <textarea name="resource_description" class="form-control">{{$resourceDetails->description}}</textarea>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-3 control-label">Category</label>
                                    <div class="col-md-9">
                                        <select name="resource_category" class="form-control input-large">
                                            <option>Select Category</option>
                                            @foreach($resourceCategory as $categ)
                                            <option value="{{$categ->id}}" {!! $categ->id==$resourceDetails->category_id?'selected':'' !!}>{{ $categ->name }}</option>
                                            @endforeach
                                        </select>
                                        <span class="help-block"> The category represents the activity that describes the resource </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Default Price</label>
                                    <div class="col-md-9">
                                        <input type="text" name="resource_price" value="{{$resourceDetails->session_price}}" class="form-control input-small">
                                        <span class="help-block"> If there are no price settings defined this will be used for paid bookings </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Applied VAT</label>
                                    <div class="col-md-9">
                                        <select name="resource_vat_rate" class="form-control input-large">
                                            <option>Select VAT</option>
                                            @foreach ($vatRates as $vat)
                                            <option value="{{ $vat->id }}" {!! $vat->id==$resourceDetails->vat_id?'selected':'' !!}>{{ $vat->display_name }}</option>
                                            @endforeach
                                        </select>
                                        <span class="help-block"> Invoice VAT applied on the booking price </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" class="btn green">Update Resource Details</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="portlet box red border-grey-silver">
                    <div class="portlet-title bg-grey-silver bg-font-grey-silver">
                        <div class="caption">
                            <i class="fa fa-cogs"></i> Price settings </div>
                        <div class="tools">
                            <a class="collapse" href="javascript:;" data-original-title="" title=""> </a>
                        </div>
                        <div class="actions">
                            <a class="btn green-jungle" data-toggle="modal" href="#add_price_modal">
                                <i class="fa fa-plus"></i> Add new price setting </a>
                        </div>
                    </div>
                    <div class="portlet-body flip-scroll">
                    @if (sizeof($prices)>0)
                        <table class="table table-bordered table-striped table-condensed flip-content">
                            <thead class="flip-content">
                            <tr>
                                <th width="5%"> No. </th>
                                <th> Week Days </th>
                                <th class="numeric"> Time Interval </th>
                                <th class="numeric"> Date Restrictions </th>
                                <th class="numeric"> Price </th>
                                <th class="numeric"> Type </th>
                                <th class="numeric" style="width:130px;"> </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($prices as $key=>$price)
                                <tr>
                                    <td> {{$key+1}} </td>
                                    <td> <b> {{$price['days']}} </b> </td>
                                    <td> <b> {{$price['time_interval']}} </b> </td>
                                    <td> <b> {{$price['date_interval']}} </b> </td>
                                    <td> <b> {{$price['price']}} </b> </td>
                                    <td class="numeric"> {{$price['type']}} </td>
                                    <td class="numeric"> <span class="btn blue btn-sm" onclick="javascript:get_resource_price({{$price['id']}})">Edit</span>
                                        <span class="btn red btn-sm" onclick="javascript:delete_resource_price('{{ $price['id'] }}','{{ $resourceDetails->id }}')">Delete</span> </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        @if (sizeof($availablePrices))
                            @foreach($availablePrices as $onePrice)
                                <button class="btn blue-madison copy_prices_from" style="margin-bottom:5px;" data-id="{{$onePrice['id']}}">Copy {{$onePrice['name']}} prices</button>
                            @endforeach
                        @else
                            <div class="note note-warning">
                                <h4 class="block">You have no prices defined for this location</h4>
                                <p> Use the "Add new price setting" button to define prices for this resource in the selected location. After you set up the prices for different days of week and/or hour intervals
                                    you will have the option to duplicate the prices to the locations with the price settings added.</p>
                            </div>
                        @endif
                    @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- END PAGE BASE CONTENT -->

        <!-- BEGIN Add price to resource -->
        <div class="modal fade draggable-modal" id="add_price_modal" tabindex="-1" role="basic" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <form action="#" id="new_resource_price" name="new_resource_price" class="form-horizontal">
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
                                            <select class="form-control input-sm input-medium" name="price_type" id="price_type">
                                                <option value="general">General</option>
                                                <option value="specific">Specific</option>
                                            </select>
                                            <h6 class="help-block"> Specific Type requires the from/to dates to be entered </h6> </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4">Price
                                        <span class="required"> * </span>
                                    </label>
                                    <div class="col-md-7">
                                        <div class="input-icon right">
                                            <i class="fa"></i>
                                            <input type="text" class="form-control input-sm input-medium" name="price" id="price" /> </div>
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
                        <button type="button" class="btn green submit_form_2" onclick="javascript: $('#new_resource_price').submit();">Save changes</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- END Add price to resource -->

        <!-- BEGIN Update price for resource -->
        <div class="modal fade draggable-modal" id="update_price_modal" tabindex="-1" role="basic" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <form action="#" id="update_resource_price" name="update_resource_price" class="form-horizontal">
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
                                                <option value="general">General</option>
                                                <option value="specific">Specific</option>
                                            </select>
                                            <h6 class="help-block"> Specific Type requires the from/to dates to be entered </h6> </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4">Price
                                        <span class="required"> * </span>
                                    </label>
                                    <div class="col-md-7">
                                        <div class="input-icon right">
                                            <i class="fa"></i>
                                            <input type="text" class="form-control input-sm input-medium" name="update_price" /> </div>
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
                                <input type="hidden" value="" name="price_id" />
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                        <button type="button" class="btn green submit_form_2" onclick="javascript: $('#update_resource_price').submit();">Save changes</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- END Update price for resource -->

        <!-- BEGIN Delete price for resource -->
        <div class="modal fade bs-modal-sm" id="delete_price_modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <h4 class="modal-title">Delete this price?</h4>
                    </div>
                    <div class="modal-body"> By clicking "Delete Price" the price attribute will be removed from this resource. Do you want to delete it? </div>
                    <div class="modal-footer">
                        <button type="button" class="btn dark btn-outline" data-dismiss="modal">No, Go Back</button>
                        <button type="button" class="btn green" onclick="javascript:delete_price();">Yes, Delete</button>
                        <input type="hidden" name="res_price_id" value="" />
                        <input type="hidden" name="res_id" value="" />
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- END Delete price for resource -->
    </div>
@endsection

@section('pageBelowLevelPlugins')
    <script src="{{ asset('assets/global/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript"></script>
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
                var form1 = $('#resource_details');
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
                        resource_category: {
                            minlength:1,
                            required: true,
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
                        update_resource(); // submit the form
                    }
                });
            }

            var handleValidation2 = function() {
                var form2 = $('#new_resource_price');
                var error2 = $('.alert-danger', form2);
                var success2 = $('.alert-success', form2);

                form2.validate({
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
                        price_type: {
                            required: true
                        },
                        price: {
                            minlength: 1,
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
                        add_resource_price(); // submit the form
                    }
                });
            }

            var handleValidation3 = function() {
                var form3 = $('#update_resource_price');
                var error3 = $('.alert-danger', form3);
                var success3 = $('.alert-success', form3);

                form3.validate({
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
                        },
                        update_price: {
                            minlength: 1,
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
                        update_resource_price(); // submit the form
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

        function update_resource(){
            $.ajax({
                url: '{{route('admin/shops/resource/update')}}',
                type: "post",
                data: {
                    'name':         $('input[name=resource_name]').val(),
                    'description':  $('textarea[name=resource_description]').val(),
                    'category_id':  $('select[name=resource_category]').val(),
                    'session_price':$('input[name=resource_price]').val(),
                    'vat_rate':     $('select[name=resource_vat_rate]').val(),
                    'resource_id':  '{{$resourceDetails->id}}'
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

        function add_resource_price(){
            $.ajax({
                url: '{{route('admin/shops/resource/add_price')}}',
                type: "post",
                data: {
                    'days':         $('select[name=week_days]').val(),
                    'time_start':   $('input[name=time_start]').val(),
                    'time_stop':    $('input[name=time_stop]').val(),
                    'date_start':   $('input[name=from_date]').val(),
                    'date_stop':    $('input[name=to_date]').val(),
                    'type':         $('select[name=price_type]').val(),
                    'price':        $('input[name=price]').val(),
                    'resource_id':  '{{$resourceDetails->id}}'
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

                    $('#add_price_modal').modal('hide');
                    $('#new_resource_price')[0].reset();
                }
            });
        }

        function get_resource_price(id){
            var select_days = {1:'Monday', 2:'Tuesday', 3:'Wednesday', 4:'Thursday', 5:'Friday', 6:'Saturday', 0:'Sunday'};
            var select_type = {general:"General", specific:"Specific"};

            $.ajax({
                url: '{{route('admin/shops/resource/get_resource_price_details')}}',
                type: "post",
                data: {
                    'price_id': id,
                    'resource_id':  '{{$resourceDetails->id}}'
                },
                success: function(data){
                    if(data.success){
                        $("#update_price_modal").find('input:text, input:hidden, input:password, input:file, select, textarea').val('');
                        $("#update_price_modal").find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');

                        var week_days_list = '';
                        $.each(select_days, function(key, val){
                            if (key==0){ return true; }

                            if ($.inArray(key, data.price.days)!=-1){
                                week_days_list+='<option value="'+key+'" selected>'+val+'</option>';
                            }
                            else{
                                week_days_list+='<option value="'+key+'">'+val+'</option>';
                            }
                        });

                        if ($.inArray("0", data.price.days)!=-1){
                            week_days_list+='<option value="0" selected>'+select_days[0]+'</option>';
                        }
                        else{
                            week_days_list+='<option value="0">'+select_days[0]+'</option>';
                        }

                        $('select[name=update_week_days]').html(week_days_list);

                        var price_type = '';
                        $.each(select_type, function(key, val){
                            if ( data.price.type == key ){
                                price_type+='<option value="'+key+'" selected="selected">'+val+'</option>';
                            }
                            else{
                                price_type+='<option value="'+key+'">'+val+'</option>';
                            }
                        });
                        $('select[name=update_price_type]').html(price_type);

                        $('input[name=update_time_start]').val(data.price.time_start);
                        $('input[name=update_time_stop]').val(data.price.time_stop);
                        $('input[name=update_price]').val(data.price.price);

                        $('input[name=update_from_date]').datepicker('setDate',data.price.date_start);
                        $('input[name=update_to_date]').datepicker('setDate',data.price.date_stop);

                        $('input[name=price_id]').val(data.price.id);

                        $('#update_price_modal').modal('show');
                    }
                    else{
                        show_notification(data.title, data.errors, 'ruby', 3500, 0);
                    }
                }
            });
        }

        function update_resource_price(){
            $.ajax({
                url: '{{route('admin/shops/resource/update_resource_price')}}',
                type: "post",
                data: {
                    'days':         $('select[name=update_week_days]').val(),
                    'time_start':   $('input[name=update_time_start]').val(),
                    'time_stop':    $('input[name=update_time_stop]').val(),
                    'date_start':   $('input[name=update_from_date]').val(),
                    'date_stop':    $('input[name=update_to_date]').val(),
                    'type':         $('select[name=update_price_type]').val(),
                    'price':        $('input[name=update_price]').val(),
                    'resource_id':  '{{$resourceDetails->id}}',
                    'price_id':     $('input[name=price_id]').val()
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

        function delete_resource_price(id, res_id){
            $('input[name=res_price_id]').val(id);
            $('input[name=res_id]').val(res_id);

            $('#delete_price_modal').modal('show');
        }

        function delete_price(){
            $.ajax({
                url: '{{route('admin/shops/resource/delete_resource_price')}}',
                type: "post",
                data: {
                    'resource_id':  $('input[name=res_id]').val(),
                    'price_id':     $('input[name=res_price_id]').val()
                },
                success: function(data){
                    $('#delete_price_modal').modal('hide');
                    $('input[name=res_price_id]').val('');
                    $('input[name=res_id]').val('');

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

        $(".copy_prices_from").on('click', function(){
            $.ajax({
                url: '{{route('admin/shops/resource/copy_resource_prices')}}',
                type: "post",
                data: {
                    'from_resource_id': $(this).attr('data-id'),
                    'to_resource_id':   "{{$resourceDetails->id}}",
                    'location_id':      "{{$resourceDetails->location_id}}"
                },
                success: function(data){
                    $('#delete_price_modal').modal('hide');
                    $('input[name=res_price_id]').val('');
                    $('input[name=res_id]').val('');

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
        });
    </script>
@endsection