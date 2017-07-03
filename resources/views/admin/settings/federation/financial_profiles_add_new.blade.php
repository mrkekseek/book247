@extends('admin.layouts.federation.main')

@section('pageLevelPlugins')
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
    <div class="page-content fix_padding_top_0">
        <!-- BEGIN PAGE BASE CONTENT -->
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-equalizer font-red-sunglo"></i>
                            <span class="caption-subject font-red-sunglo bold uppercase">Create new financial profile</span>
                            <span class="caption-helper">add profile details...</span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <!-- BEGIN FORM-->
                        <form action="#" id="new_financial_profile" class="form-horizontal">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-3"> Profile Name </label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control input-xlarge" name="profile_name">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3"> Company Name </label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control input-xlarge" name="company_name">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3 inline"> Company Registration No. </label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control inline-block input-xlarge input-inline" name="registration_name">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3 inline"> Bank Name </label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control inline-block input-xlarge input-inline" name="bank_name">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3 inline"> Bank Acc No. </label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control inline-block input-xlarge input-inline" name="bank_acc_no">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3 inline"> Address Line 1 </label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control inline-block input-inline input-xlarge" name="address1">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3 inline"> Address Line 2 </label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control inline-block input-inline input-xlarge" name="address2">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3 inline"> Postal Code </label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control inline-block input-small input-inline" name="postal_code">
                                        </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3 inline"> City </label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control inline-block input-xlarge input-inline" name="city">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3 inline"> Region </label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control inline-block input-large input-inline" name="region">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3 inline"> Country </label>
                                            <div class="col-md-9">
                                                <select class="form-control input-large" name="country" id="country">
                                                    @foreach ($countries as $country)
                                                        <option value="{{ $country->id }}" {!! @$personalAddress->country_id==$country->id?'selected':'' !!}>{{ $country->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <!--/span-->
                                </div>
                                <!--/row-->
                            </div>
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-offset-3 col-md-9">
                                                <button type="submit" class="btn green">Create Financial Profile</button>
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
        </div>
        <!-- END PAGE BASE CONTENT -->
    </div>
@endsection

@section('pageBelowLevelPlugins')
    <script src="{{ asset('assets/global/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript"></script>
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
        var FormValidation = function () {
            var handleValidation1 = function() {
                var form1 = $('#new_financial_profile');
                var error1 = $('.alert-danger', form1);
                var success1 = $('.alert-success', form1);

                form1.validate({
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block help-block-error', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    ignore: "",  // validate all fields including form hidden input
                    rules: {
                        profile_name: {
                            minlength: 5,
                            required: true
                        },
                        company_name: {
                            minlength: 3,
                            required: true
                        },
                        registration_name: {
                            required: true,
                            minlength: 5,
                        },
                        bank_name: {
                            required: true,
                            minlength: 5,
                        },
                        bank_acc_no: {
                            required: true,
                            minlength: 3,
                        },
                        address1: {
                            minlength: 5,
                            required: true
                        },
                        address2: {
                            minlength: 5,
                            required: false
                        },
                        postal_code: {
                            minlength: 3,
                            required: true
                        },
                        city: {
                            minlength: 2,
                            required: true
                        },
                        region: {
                            minlength: 2,
                            required: true
                        },
                        country: {
                            minlength: 1,
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
                        add_new_financial_profile(); // submit the form
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

        function add_new_financial_profile(){
            $.ajax({
                url: '{{route('admin/settings_financial_profiles/create')}}',
                type: "post",
                data: {
                    'profile_name':         $('input[name=profile_name]').val(),
                    'company_name':         $('input[name=company_name]').val(),
                    'bank_name':            $('input[name=bank_name]').val(),
                    'bank_account':         $('input[name=bank_acc_no]').val(),
                    'organisation_number':  $('input[name=registration_name]').val(),
                    'address1':             $('input[name=address1]').val(),
                    'address2':             $('input[name=address2]').val(),
                    'city':                 $('input[name=city]').val(),
                    'postal_code':          $('input[name=postal_code]').val(),
                    'region':               $('input[name=region]').val(),
                    'country':              $('select[name=country]').val()
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
        }
    </script>
@endsection