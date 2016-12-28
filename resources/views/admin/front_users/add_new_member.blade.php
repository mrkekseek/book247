@extends('admin.layouts.main')

@section('pageLevelPlugins')
    <link href="{{ asset('assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
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

@section('title', 'Back-end users - All list')
@section('pageBodyClass','page-container-bg-solid page-header-fixed page-sidebar-closed-hide-logo')

@section('pageContentBody')
    <div class="page-content">
        <!-- BEGIN PAGE BASE CONTENT -->
        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                <div class="portlet light bordered">
                    <div class="portlet-body">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="icon-settings font-dark"></i>
                                    <span class="caption-subject font-dark sbold uppercase">Register new client</span>
                                </div>
                            </div>
                            <div class="portlet-body form">
                                <form class="register-form portlet light " method="post" name="user_registration_form" id="user_registration_form">
                                    <div class="alert alert-danger display-hide">
                                        <button class="close" data-close="alert"></button> You have some errors in the form. Please check below. </div>
                                    <div class="alert alert-success display-hide">
                                        <button class="close" data-close="alert"></button> Information is valid, please wait! </div>

                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">Enter personal details - mandatory</h3>
                                        </div>
                                        <div class="panel-body">
                                            <div class="form-group">
                                                <label class="control-label visible-ie8 visible-ie9">First Name</label>
                                                <input class="form-control placeholder-no-fix" type="text" placeholder="First Name" name="firstname" /> </div>
                                            <div class="form-group">
                                                <label class="control-label visible-ie8 visible-ie9">Middle Name</label>
                                                <input class="form-control placeholder-no-fix" type="text" placeholder="Middle Name" name="middlename" /> </div>
                                            <div class="form-group">
                                                <label class="control-label visible-ie8 visible-ie9">Last Name</label>
                                                <input class="form-control placeholder-no-fix" type="text" placeholder="Last Name" name="lastname" /> </div>
                                            <div class="form-group">
                                                <label class="control-label visible-ie8 visible-ie9">Phone Number</label>
                                                <input class="form-control placeholder-no-fix" type="text" placeholder="Phone Number" name="phone" /> </div>
                                            <div class="form-group">
                                                <label class="control-label visible-ie8 visible-ie9">Gender</label>
                                                <select class="form-control" name="member_gender">
                                                    <option value="" selected="selected">Select Gender</option>
                                                    <option value="F">Female</option>
                                                    <option value="M">Male</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel panel-info">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">Personal details - optional</h3>
                                        </div>
                                        <div class="panel-body">
                                            <div class="form-group">
                                                <span class="help-inline">Date of Birth</span>
                                                <div class="input-group input-medium date date-picker" data-date="{{ \Carbon\Carbon::today()->format('d-m-Y') }}" data-date-format="dd-mm-yyyy" data-date-viewmode="years" style="display:inline-flex; margin-top:2px; margin-right:40px;">
                                                    <input type="text" class="form-control" name="date_of_birth" readonly style="background-color:#ffffff;">
                                                        <span class="input-group-btn">
                                                            <button class="btn default" type="button">
                                                                <i class="fa fa-calendar"></i>
                                                            </button>
                                                        </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">Enter account details below</h3>
                                        </div>
                                        <div class="panel-body">
                                            <div class="form-group">
                                                <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                                                <label class="control-label visible-ie8 visible-ie9">Email</label>
                                                <input class="form-control placeholder-no-fix" type="text" placeholder="Email" name="reg_email" /> </div>
                                            <div class="form-group">
                                                <label class="control-label visible-ie8 visible-ie9">Password</label>
                                                <input class="form-control placeholder-no-fix" type="password" autocomplete="off" id="register_password" placeholder="Password" name="password" /> </div>
                                            <div class="form-group">
                                                <label class="control-label visible-ie8 visible-ie9">Re-type Your Password</label>
                                                <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="Re-type Your Password" name="rpassword" /> </div>
                                            <div class="form-group">
                                                <label class="control-label visible-ie8 visible-ie9">Signing Location</label>
                                                <select class="form-control" name="member_sign_location">
                                                    <option value="" selected="selected">Select Signing Location</option>
                                                    @foreach($shop_locations as $location)
                                                    <option value="{{ $location->id }}"> {{ $location->name }} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    @if (sizeof($memberships)>0)
                                        <div class="panel panel-info">
                                            <div class="panel-heading">
                                                <h3 class="panel-title">Select membership plan - optional</h3>
                                            </div>
                                            <div class="panel-body">
                                                <div class="form-group">
                                                    <span class="help-inline">Membership Start Date</span>
                                                    <div class="input-group input-medium date date-picker" data-date="{{ \Carbon\Carbon::today()->format('d-m-Y') }}" data-date-format="dd-mm-yyyy" data-date-viewmode="years" style="display:inline-flex; margin-top:2px; margin-right:40px;">
                                                        <input type="text" class="form-control" name="start_date" readonly style="background-color:#ffffff;">
                                                        <span class="input-group-btn">
                                                            <button class="btn default" type="button">
                                                                <i class="fa fa-calendar"></i>
                                                            </button>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <span class="help-inline">Membership Type</span>
                                                    <select class="form-control input-group input-inline input-medium" name="membership_plan">
                                                        <option value="1" selected="selected">No membership plan</option>
                                                        @foreach($memberships as $plan)
                                                        <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="panel panel-info">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">Member Address - optional</h3>
                                        </div>
                                        <div class="panel-body">
                                            <div class="form-group">
                                                <label class="control-label">Address Line1</label>
                                                <input type="text" name="personal_addr1" id="personal_addr1" placeholder="Address1" value="{{ @$personalAddress->address1 }}" class="form-control" /> </div>
                                            <div class="form-group">
                                                <label class="control-label">Address Line2</label>
                                                <input type="text" name="personal_addr2" id="personal_addr2" placeholder="Address2" value="{{ @$personalAddress->address2 }}" class="form-control" /> </div>
                                            <div class="form-group">
                                                <label class="control-label">City</label>
                                                <input type="text" name="personal_addr_city" id="personal_addr_city" placeholder="City" value="{{ @$personalAddress->city }}" class="form-control" /> </div>
                                            <div class="form-group">
                                                <label class="control-label">Region</label>
                                                <input type="text" name="personal_addr_region" id="personal_addr_region" placeholder="Region" value="{{ @$personalAddress->region }}" class="form-control" /> </div>
                                            <div class="form-group">
                                                <label class="control-label">Postal Code</label>
                                                <input type="text" name="personal_addr_pcode" id="personal_addr_pcode" placeholder="Postal Code" value="{{ @$personalAddress->postal_code }}" class="form-control" /> </div>
                                            <div class="form-group">
                                                <label class="control-label">Country</label>
                                                <select class="form-control" name="personal_addr_country" id="personal_addr_country">
                                                    @foreach ($countries as $country)
                                                        <option value="{{ $country->id }}" {!! @$personalAddress->country_id==$country->id?'selected':'' !!}>{{ $country->name }}</option>
                                                    @endforeach
                                                </select></div>
                                        </div>
                                    </div>

                                    <div class="form-actions">
                                        <button type="submit" class="btn red uppercase pull-right">Register New</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END EXAMPLE TABLE PORTLET-->
            </div>
        </div>
        <!-- END PAGE BASE CONTENT -->
    </div>
@endsection

@section('pageBelowLevelPlugins')
    <script src="{{ asset('assets/global/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/jquery-validation/js/additional-methods.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/bootstrap-confirmation-2-2/bootstrap-confirmation.min.js') }}" type="text/javascript"></script>
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
            // validation using icons
            var handleValidation2 = function() {
                var form2 = $('#user_registration_form');
                var error2 = $('.alert-danger', form2);
                var success2 = $('.alert-success', form2);

                form2.validate({
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block help-block-error', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    ignore: "",  // validate all fields including form hidden input
                    rules: {
                        firstname: {
                            minlength: 2,
                            maxlength: 150,
                            required: true
                        },
                        lastname: {
                            minlength: 2,
                            maxlength: 150,
                            required: true
                        },
                        phone: {
                            number: true,
                            required: true,
                            remote: {
                                url: "{{ route('ajax/check_phone_for_member_registration') }}",
                                type: "post",
                                data: {
                                    phone: function() {
                                        return $( "input[name='phone']" ).val();
                                    }
                                }
                            }
                        },
                        reg_email: {
                            email: true,
                            validate_email: true,
                            required: true,
                            remote: {
                                url: "{{ route('ajax/check_email_for_member_registration') }}",
                                type: "post",
                                data: {
                                    email: function() {
                                        return $( "input[name='reg_email']" ).val();
                                    }
                                }
                            }
                        },
                        password: {
                            minlength: 5,
                            maxlength: 150,
                        },
                        rpassword: {
                            minlength: 5,
                            maxlength: 150,
                            equalTo:"#register_password"
                        },
                        member_gender: {
                            required:true,
                            minlength: 1,
                        },
                        member_sign_location: {
                            required:true,
                            minlength: 1,
                        },
                        date_of_birth: {
                            //required:false,
                            //datePickerDate: true,
                        },

                        personal_addr1: {
                            minlength: 5,
                        },
                        personal_addr_city: {
                            minlength: 3,
                        },
                        personal_addr_region: {
                            minlength:2,
                        },
                        personal_addr_pcode: {
                            minlength: 2,
                        },
                    },

                    messages: { // custom messages for radio buttons and checkboxes
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
                        register_member(); // submit the form
                    }
                });
            }

            return {
                //main function to initiate the module
                init: function () {

                    handleValidation2();

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

            return {
                //main function to initiate the module
                init: function () {
                    handleDatePickers();
                }
            };

        }();

        function register_member(){
            $.ajax({
                url: '{{route('ajax/register_new_member')}}',
                type: "post",
                cache: false,
                data: {
                    'first_name': $('input[name="firstname"]').val(),
                    'middle_name': $('input[name="middlename"]').val(),
                    'last_name': $('input[name="lastname"]').val(),
                    'phone_number': $('input[name="phone"]').val(),
                    'gender': $('select[name="member_gender"]').val(),
                    'dob': $('input[name="date_of_birth"]').val(),

                    'email': $('input[name="reg_email"]').val(),
                    'password': $('input[name="password"]').val(),
                    'rpassword': $('input[name="rpassword"]').val(),

                    'membership_plan': $('select[name="membership_plan"]').val(),
                    'start_date': $('input[name="start_date"]').val(),

                    'address1':     $('input[name=personal_addr1]').val(),
                    'address2':     $('input[name=personal_addr2]').val(),
                    'city':         $('input[name=personal_addr_city]').val(),
                    'region':       $('input[name=personal_addr_region]').val(),
                    'postal_code':  $('input[name=personal_addr_pcode]').val(),
                    'adr_country_id':   $('select[name=personal_addr_country]').val(),
                },
                success: function (data) {
                    if (data.success) {
                        show_notification('New user registered', 'The details entered were correct so the user is now registered.', 'lime', 3500, 0);
                        setTimeout(function(){
                            window.location.reload(true);
                        },2500);
                    }
                    else{
                        show_notification('User registration ERROR', 'Something went wrong with the registration. Try changing the email/phone number or try reloading the page', 'tangerine', 3500, 0);
                    }
                }
            });
        }

        $(document).ready(function(){
            FormValidation.init();

            ComponentsDateTimePickers.init();
        });
    </script>
@endsection