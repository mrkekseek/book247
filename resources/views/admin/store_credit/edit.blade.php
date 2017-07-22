@extends('admin.layouts.main')

@section('pageLevelPlugins')
    <link href="{{ asset('assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css') }}" rel="stylesheet" type="text/css" />
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
                <h1>{!! $text_parts['title'] !!}
                    <small>{!! $text_parts['subtitle'] !!}</small>
                </h1>
            </div>
            <!-- END PAGE TITLE -->
        </div>
        <!-- END PAGE HEAD-->
        <!-- BEGIN PAGE BASE CONTENT -->
        <div class="row">
        @if ($product)
            <div class="col-md-12">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption">
                             <i class="icon-equalizer {{ $product->status == 'active' ? 'font-green-jungle' : 'font-red-thunderbird' }}"></i>
                            <span class="caption-subject {{ $product->status == 'active' ? 'font-green-jungle' : 'font-red-thunderbird' }} bold uppercase"> Store Credit Product details</span>
                            <span class="caption-helper {{ $product->status == 'active' ? 'font-green-jungle' : 'font-red-thunderbird' }}">
                                @if ($product->status == 'active')
                                    - active and in use
                                @else
                                    - status "{{ $product->status }}" - switch to "active" when you want this to be available
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="portlet-body form" >
                        <!-- BEGIN FORM-->
                        <form action="#" id="new_store_credit_product" class="form-horizontal">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-danger display-hide">
                                             You have some errors in the form. Please check below. 
                                          </div>
                                          <div class="alert alert-success display-hide">
                                             Information is valid, please wait!
                                          </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-3"> Name </label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" name="name" placeholder="Name" value="{{ $product->name }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-3"> Description </label>
                                            <div class="col-md-9">
                                                <textarea type="text" class="form-control" name="description" placeholder="Description">{{ $product->description }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Cost of store credit</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" name="store_credit_value" placeholder="Cost of store credit" value="{{ $product->store_credit_value }}" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-3">The cost of the package without a discount</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" name="store_credit_price" placeholder="The cost of the package without a discount" value="{{ $product->store_credit_price }}" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Fixed price discount</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" name="store_credit_discount_fixed" placeholder="Fixed price discount" value="{{ $product->store_credit_discount_fixed }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Discount from price in %</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" name="store_credit_discount_percentage" placeholder="Discount from price in % " value="{{ $product->store_credit_discount_percentage }}" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Date the package begins to operate</label>
                                            <div class="col-md-9">
                                
                                                <div class="input-group date date-picker from" data-date-autoclose="true" data-date-orientation="bottom right" data-date="" data-date-format="yyyy-mm-dd"  data-date-start-view="days">
                                                    <input type="text" class="form-control" value="{{ $product->valid_from }}" readonly="readonly" name="valid_from"   placeholder="Date the package begins to operate" >
                                                    <span class="input-group-btn">
                                                        <button class="btn default" type="button">
                                                            <i class="fa fa-calendar"></i>
                                                        </button>
                                                    </span>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-3">How many days is active</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" name="validity_days" placeholder="How many days is active" value="{{ $product->validity_days }}" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-3">The date the package expires</label>
                                            <div class="col-md-9">
                        
                                                <div class="input-group date date-picker to" data-date-autoclose="true" data-date="" data-date-format="yyyy-mm-dd"  data-date-start-view="days" data-date-orientation="bottom right">
                                                    <input type="text" class="form-control" value="{{ $product->valid_to }}" readonly="readonly" name="valid_to"  placeholder="The date the package expires" >
                                                    <span class="input-group-btn">
                                                        <button class="btn default" type="button">
                                                            <i class="fa fa-calendar"></i>
                                                        </button>
                                                    </span>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Count of packages that a user can purchase</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" name="packages_per_user" placeholder="Count of packages that a user can purchase" value="{{ $product->packages_per_user }}" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Status</label>
                                            <div class="col-md-9">
                                                <select class="form-control" name="status">
                                                    @foreach($status as $s)
                                                        <option @if($s == $product->status) selected="selected" @endif>{{ $s }}</option>
                                                    @endforeach
                                                </select>
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
                                                <button type="submit" class="btn green">Save Product</button>
                                                <a href="{{ route('admin.store_credit_products.index') }}"  class="btn default">Cancel</a>
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
        @endif  
    </div>
@endsection

@section('pageBelowLevelPlugins')
    <script src="{{ asset('assets/global/plugins/moment.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js') }}" type="text/javascript"></script>

    <script src="{{ asset('assets/global/scripts/datatable.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/datatables/datatables.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js') }}" type="text/javascript"></script>
@endsection


@section('pageBelowLevelScripts')
    <script src="{{ asset('assets/global/plugins/jquery-notific8/jquery.notific8.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/scripts/jquery.matchHeight.js') }}" type="text/javascript"></script>
    {{--get match height--}}
@endsection

@section('themeBelowLayoutScripts')
    <script src="{{ asset('assets/layouts/layout4/scripts/layout.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/layouts/layout4/scripts/demo.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/layouts/global/scripts/quick-sidebar.min.js') }}" type="text/javascript"></script>
@endsection

@section('pageCustomJScripts')
    <script type="text/javascript">


        var FormValidation = function () {

            var handleValidation = function() {
                var form = $('#new_store_credit_product');
                var error = $('.alert-danger', form);
                var success = $('.alert-success', form);

                form.validate({
                    errorElement: 'span',
                    errorClass: 'help-block help-block-error',
                    focusInvalid: false,
                    ignore: "",
                    rules: {
                        name : {
                            required : true,
                            maxlength : 150
                        },
                        description : {
                            required : true,
                            maxlength : 255
                        },
                        store_credit_value : {
                            required : true,
                            number : true
                        },
                        store_credit_price : {
                            required : true,
                            number: true
                        },
                        store_credit_discount_fixed : {
                            number : true  
                        },
                        store_credit_discount_percentage : {
                            number : true
                        },
                        validity_days : {
                            digits : true
                        },
                        packages_per_user : {
                            number : true
                        },
                        status : {
                            required : true
                        }
                    },

                    invalidHandler: function (event, validator) {
                        success.hide();
                        error.show();
                    },

                    errorPlacement: function (error, element) {
                        var icon = $(element).parent('.input-icon').children('i');
                        icon.removeClass('fa-check').addClass("fa-warning");
                        icon.attr("data-original-title", error.text()).tooltip({'container': 'body'});
                    },

                    highlight: function (element) {
                        $(element)
                                .closest('.form-group').removeClass("has-success").addClass('has-error');
                    },

                    unhighlight: function (element) {

                    },

                    success: function (label, element) {
                        var icon = $(element).parent('.input-icon').children('i');
                        $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
                        icon.removeClass("fa-warning").addClass("fa-check");
                    },

                    submitHandler: function (form) {
                        success.show();
                        error.hide();
                        update_store_credit_details();
                    }
                });
            }

            return {
                init: function () {
                    handleValidation();
                }
            };
        }();

        $(document).ready(function(){
            FormValidation.init();
            $("[data-date]").datepicker({});
        });

        function update_store_credit_details(){
            $.ajax({
                url: '{{ route('admin.store_credit_products.update', ['id' => $product->id ]) }}',
                type: "patch",
                data: {
                    "name" : $("input[name='name']").val(),
                    "description" : $("textarea[name='description']").val(),
                    "store_credit_value" : $("input[name='store_credit_value']").val(),
                    "store_credit_price" : $("input[name='store_credit_price']").val(),
                    "store_credit_discount_fixed" : $("input[name='store_credit_discount_fixed']").val(),
                    "store_credit_discount_percentage" : $("input[name='store_credit_discount_percentage']").val(),
                    "validity_days" : $("input[name='validity_days']").val(),
                    "valid_from" : $("input[name='valid_from']").val(),
                    "valid_to" : $("input[name='valid_to']").val(),
                    "packages_per_user" : $("input[name='packages_per_user']").val(),
                    "status" : $("select[name='status']").val()
                },
                success: function(data){
                    if(data.success){
                        show_notification(data.title, data.message, 'lime', 3500, 0);
                        setTimeout(function(){
                            window.location.reload();
                        }, 2000);
                    }
                    else{
                        for(var i in data.errors)
                        {
                            show_notification(data.title, data.errors[i], 'ruby', 3500, 0);    
                        }
                    }
                }
            });
        }

    </script>
@endsection