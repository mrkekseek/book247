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

@section('title', 'Back-end Clubs - View Club Details')
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
                            <span class="caption-subject font-dark sbold uppercase">Club Details</span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <form class="form-horizontal" role="form" name="store_details" id="store_details">
                            <div class="row">
                                <div class="form-body">
                                    <div class="col-md-12">
                                        <div class="alert alert-danger display-hide">
                                            <button class="close" data-close="alert"></button> You have some form errors. Please check below. </div>
                                        <div class="alert alert-success display-hide">
                                            <button class="close" data-close="alert"></button> Your form validation is successful! </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Club Name</label>
                                            <div class="col-md-9">
                                                    <input type="text" name="shop_name" class="form-control input-inline input-medium" placeholder="Club Name"  />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Phone Number</label>
                                            <div class="col-md-9">
                                                <input type="text" name="shop_phone"  class="form-control input-inline input-medium" placeholder="Phone Number">
                                                <span class="help-inline"> land line </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Fax Number</label>
                                            <div class="col-md-9">
                                                <input type="text" name="shop_fax" class="form-control input-inline input-medium" placeholder="Fax Number">
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
                                                    <input type="email" name="shop_email"  class="form-control" placeholder="Email Address"> </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Visibility</label>
                                            <div class="col-md-9">
                                                <select disabled name="shop_visibility" class="form-control">
                                                    <option selected value="pending">Pending</option>
                                                    <option value="public">Public</option>
                                                    <option value="warehouse">Warehouse</option>
                                                    <option value="suspended">Suspended</option>
                                                </select>
                                                <span class="help-block"> Only public visibility will be shown in calendar dropdown </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Address Line1</label>
                                            <div class="col-md-9">
                                                <input type="text" name="shop_address1"  class="form-control" placeholder="Enter text">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Address Line2</label>
                                            <div class="col-md-9">
                                                <input type="text" name="shop_address2"  class="form-control" placeholder="Enter text">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">City</label>
                                            <div class="col-md-9">
                                                <input type="text" name="shop_city"  class="form-control input-inline input-medium" placeholder="Enter text">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Postal Code</label>
                                            <div class="col-md-9">
                                                <input type="text" name="shop_postal_code"  class="form-control input-inline input-medium" placeholder="Enter text">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Region</label>
                                            <div class="col-md-9">
                                                <input type="text" name="shop_region"  class="form-control input-inline input-medium" placeholder="Enter text">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Country</label>
                                            <div class="col-md-9">
                                                <select class="form-control input-medium" name="shop_country">
                                                    <option value="">Chose country</option>
                                                    @foreach($countries as $country)
                                                        <option value="{{ $country->id }}"> {{ $country->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Activity</label>
                                        <div class="col-md-9">
                                            <select class="form-control input-md" name="location_resource_category" id="location_resource_category">
                                                <option value="">Select Activity...</option>
                                                @foreach ($resourceCategory as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Booking Slot Time</label>
                                        <div class="col-md-9">
                                            <select name="resource_time_slot" class="form-control input-md">
                                                <option value="">Select time in minutes</option>
                                                @for($i=5; $i<=120; $i++)
                                                    <option value="{{$i}}" {!! $i==$key?'selected':'' !!}> {{$i}} minutes </option>
                                                @endfor
                                            </select>
                                            <span class="help-block"> time per single reservation for this activity </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" for="#store_details" class="btn green center-block">Add store</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- END PAGE BASE CONTENT -->
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
                var form1 = $('#store_details');
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
                        shop_phone: {
                            minlength:8,
                            required: true,
                            number: true
                        },
                        shop_fax: {
                            minlength:8,
                            required: true,
                            number: true
                        },
                        shop_email: {
                            required: true,
                            email: true
                        },
                        shop_address1: {
                            required: true,
                            minlength: 6,
                        },
                        shop_city: {
                            required: true,
                            minlength: 3,
                        },
                        shop_postal_code: {
                            required: true,
                            number:true,
                        },
                        shop_region: {
                            minlength: 3,
                            required:true
                        },
                        shop_country :{
                            required:true

                        },
                        location_resource_category : {
                            required:true
                        },
                        resource_time_slot: {
                            required: true
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
                        add_new(); // submit the form
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

        function add_new(){
            $.ajax({
                url: '{{route('admin/shops/locations/add_from_page')}}',
                type: "post",
                data: {
                    'name':         $('input[name=shop_name]').val(),
                    'phone':        $('input[name=shop_phone]').val(),
                    'fax':          $('input[name=shop_fax]').val(),
                    'email':        $('input[name=shop_email]').val(),
                    'visibility':   $('select[name=shop_visibility]').val(),
                    'address1':     $('input[name=shop_address1]').val(),
                    'address2':     $('input[name=shop_address2]').val(),
                    'postal_code':  $('input[name=shop_postal_code]').val(),
                    'city':         $('input[name=shop_city]').val(),
                    'region':       $('input[name=shop_region]').val(),
                    'country_id':      $('select[name=shop_country]').val(),
                    'activity_id': $('select[name=location_resource_category]').val(),
                    'value': $('select[name=resource_time_slot]').val(),
                },
                success: function(data){
                    if(data.success){
                        show_notification(data.title, data.message, 'lime', 3500, 0);
                        setTimeout(function(){
                            window.location.href = data.redirect_link;
                        },2500);
                    }
                    else{
                        show_notification(data.title, data.errors, 'ruby', 3500, 0);
                    }
                }

            });
        }


        function update_store_details(){
            $.ajax({
                url: '{{route('admin/shops/location/store_details_update', ['id'=>1])}}',
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


    </script>
@endsection