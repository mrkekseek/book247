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
            @if ($membership_product)
                <div class="col-md-12">
                    <div class="portlet light bordered">
                        <div class="portlet-body form">
                            <!-- BEGIN FORM-->
                            <form action="#" id="new_membership_plan" class="form-horizontal">
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label col-md-3"> Membership Product Name </label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" name="membership_product_name" placeholder="New product name" value="{{$membership_product->name}}">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Product Color</label>
                                                <div class="col-md-3">
                                                    <div class="input-group color colorpicker-default" data-color="{{$membership_product->color_code}}" data-color-format="rgba">
                                                        <input type="text" class="form-control" name="membership_product_color" value="{{$membership_product->color_code}}" readonly>
                                                        <span class="input-group-btn">
                                                            <button class="btn default" type="button">
                                                                <i style="background-color: {{$membership_product->plan_calendar_color}}};"></i>&nbsp;</button>
                                                        </span>
                                                    </div>
                                                    <!-- /input-group -->
                                                </div>
                                                <div class="col-md-6">
                                                    <span class="help-inline  block-inline"> Color to be displayed in calendar booking </span>
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
            @else
                <div class="col-md-12">
                    <div class="portlet light ">
                        <div class="portlet-body">
                            <div class="note note-warning">
                                <h4 class="block">An error occurred</h4>
                                <p> The membership product you are searching for could not be found.
                                    Go back to the page you came here and try refreshing it, then access the link again.<br /><br />
                                    Thank you!</p>
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
                        sign_out_period: {
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
            @if ($membership_product)
                $.ajax({
                url: '{{route('admin/membership_products/update', ['id'=>$membership_product->id])}}',
                type: "post",
                data: {
                    'name':         $('input[name=membership_product_name]').val(),
                    'color_code':   $('input[name=membership_product_color]').val(),
                },
                success: function(data){
                    if(data.success){
                        show_notification(data.title, data.message, 'lime', 3500, 0);
                        setTimeout(function(){
                            location.reload();
                        },2000);
                    }
                    else{
                        show_notification(data.title, data.errors, 'ruby', 3500, 0);
                    }
                }
            });
            @endif
        }
    </script>
@endsection