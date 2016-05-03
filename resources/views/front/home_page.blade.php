@extends('layouts.main')

@section('globalMandatoryStyle')
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/simple-line-icons/simple-line-icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/uniform/css/uniform.default.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('pageLevelPlugins')
    <link href="{{ asset('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/pages/css/search.min.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/bootstrap-datepaginator/bootstrap-datepaginator.min.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('assets/global/plugins/jquery-notific8/jquery.notific8.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('themeGlobalStyle')
    <link href="{{ asset('assets/global/css/components-rounded.min.css') }}" rel="stylesheet" id="style_components" type="text/css" />
    <link href="{{ asset('assets/global/css/plugins.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('themeLayoutStyle')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link href="{{ asset('assets/pages/css/login_front.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('title', 'Main Page')
@section('pageBodyClass','page-container-bg-solid page-boxed login')

@section('pageContentBody')
    <div class="page-content-wrapper">
        <!-- BEGIN CONTENT BODY -->
        <!-- BEGIN PAGE CONTENT BODY -->
        <div class="page-content">
            <div class="container">
                <!-- BEGIN PAGE BREADCRUMBS -->
                <ul class="page-breadcrumb breadcrumb">
                    <li>
                        <a href="index.html">Home</a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <a href="#">Pages</a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <span>eCommerce</span>
                    </li>
                </ul>
                <!-- END PAGE BREADCRUMBS -->
                <!-- BEGIN PAGE CONTENT INNER -->
                <div class="page-content-inner">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="portlet light margin-bottom-15">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <span class="caption-subject font-green-sharp bold uppercase">Select location</span>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    @foreach($shops as $shop)
                                        @if (sizeof($shop->resources)>0)
                                        <a class="icon-btn location_btn" href="javascript:;" data-id="{{ $shop->id }}">
                                            <i class="fa fa-group hidden-xs"></i>
                                            <div> {{$shop->name}} </div>
                                            <span class="badge badge-success"> {{sizeof($shop->resources)}} </span>
                                        </a>
                                        @endif
                                    @endforeach
                                    <a class="icon-btn location_btn bg-grey-steel" href="javascript:;" data-id="-1">
                                        <i class="fa fa-calendar hidden-xs"></i>
                                        <div> All Locations </div>
                                        <span class="badge badge-success"> 14 </span>
                                    </a>
                                    <input type="hidden" name="selected_location" value="-1" />
                                </div>
                            </div>

                            <div class="portlet light margin-bottom-15">
                                <dt>Select Activity</dt>
                                <div class="portlet-body">
                                    <div class="clearfix util-btn-margin-bottom-5">
                                        @foreach($resourceCategories as $key=>$category)
                                            @if ($category['resources_count']>0)
                                                <a class="btn btn-sm btn-outline blue-steel is_resource {{ $key==2?'active':'' }}" data-id="{{$key}}" href="javascript:;"> {{$category['name']}}
                                                    <span class="glyphicon glyphicon-cog"> </span>
                                                </a>
                                            @endif
                                        @endforeach
                                        <input type="hidden" name="selected_category" value="2" />
                                    </div>
                                </div>
                            </div>

                            <div class="portlet light margin-bottom-15">
                                <dt>Select date of booking</dt>
                                <div class="portlet-body">
                                    <div id="datepaginator_sample_4"> </div>
                                </div>
                                <input type="hidden" name="selected_date" value="{{ \Carbon\Carbon::now()->format("Y-m-d") }}" />
                            </div>

                            <div class="portlet light margin-bottom-15" id="tasks-widget">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <span class="caption-subject font-green-haze bold uppercase">Select time of booking</span>
                                    </div>
                                </div>
                                <div class="portlet-body util-btn-margin-bottom-5">
                                    <div class="clearfix" id="booking_hours">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 login " style="background-color:transparent!important;">

                        @if (!Auth::check())
                            <div class="content" style="background-color:#ffffff; margin-top:0px;">
                                <!-- BEGIN LOGIN FORM -->
                                <form class="login-form portlet light " action="{{ url('/login') }}" method="post" name="user_login_form" id="user_login_form">
                                    {!! csrf_field() !!}

                                    <div class="portlet-title">
                                        <div class="caption">
                                            <span class="caption-subject font-green-haze bold uppercase">User Login</span>
                                            <span class="caption-helper">account details...</span>
                                        </div>
                                    </div>
                                    <div class="alert alert-danger display-hide">
                                        <button class="close" data-close="alert"></button>
                                        <span> Incorrect username/password combination ... </span>
                                    </div>
                                    <div class="form-group">
                                        <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                                        <label class="control-label visible-ie8 visible-ie9">Username
                                            <span class="required"> * </span>
                                        </label>
                                        <input class="form-control form-control-solid placeholder-no-fix {{ $errors->has('username') ? ' has-error' : '' }}" type="text" autocomplete="off" placeholder="Email" name="username" id="username_focus" value="{{ old('username') }}" /> </div>
                                    <div class="form-group">
                                        <label class="control-label visible-ie8 visible-ie9">Password
                                            <span class="required"> * </span>
                                        </label>
                                        <input class="form-control form-control-solid placeholder-no-fix {{ $errors->has('password') ? ' has-error' : '' }}" type="password" autocomplete="off" placeholder="Password" name="password" /> </div>
                                    <div class="form-actions">
                                        <button type="submit" class="btn red btn-block uppercase">Login</button>
                                    </div>
                                    <div class="form-actions">
                                        <div class="pull-left">
                                            <label class="rememberme check">
                                                <input type="checkbox" name="remember" value="1" />Remember me </label>
                                        </div>
                                        <div class="pull-right forget-password-block">
                                            <a href="javascript:;" id="forget-password" class="forget-password">Forgot Password?</a>
                                        </div>
                                    </div>
                                    <div class="create-account bg-white bg-font-white">
                                        <p>
                                            <a href="javascript:;" class="green-meadow btn" id="register-btn">Create an account</a>
                                        </p>
                                    </div>
                                </form>
                                <!-- END LOGIN FORM -->
                                <!-- BEGIN FORGOT PASSWORD FORM -->
                                <form class="forget-form portlet light " action="index.html" method="post" name="password_reset_form" id="password_reset_form">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <span class="caption-subject font-green-haze bold uppercase">Forget Password ?</span>
                                            <span class="caption-helper">Enter your e-mail to reset it...</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Email" name="email" /> </div>
                                    <div class="form-actions">
                                        <button type="button" id="back-btn" class="btn grey-steel">Back</button>
                                        <button type="submit" class="btn btn-primary uppercase pull-right">Submit</button>
                                    </div>
                                </form>
                                <!-- END FORGOT PASSWORD FORM -->
                                <!-- BEGIN REGISTRATION FORM -->
                                <form class="register-form portlet light " action="index.html" method="post" name="user_registration_form" id="user_registration_form">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <span class="caption-subject font-green-haze bold uppercase">Sign Up</span>
                                            <span class="caption-helper"></span>
                                        </div>
                                    </div>
                                    <p class="hint"> Enter your personal details below: </p>
                                    <div class="form-group">
                                        <label class="control-label visible-ie8 visible-ie9">First Name</label>
                                        <input class="form-control placeholder-no-fix" type="text" placeholder="First Name" name="firstname" /> </div>
                                    <div class="form-group">
                                        <label class="control-label visible-ie8 visible-ie9">Last Name</label>
                                        <input class="form-control placeholder-no-fix" type="text" placeholder="Last Name" name="lastname" /> </div>
                                    <div class="form-group">
                                        <label class="control-label visible-ie8 visible-ie9">Phone Number</label>
                                        <input class="form-control placeholder-no-fix" type="text" placeholder="Phone Number" name="phone" /> </div>

                                    <p class="hint"> Enter your account details below: </p>
                                    <div class="form-group">
                                        <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                                        <label class="control-label visible-ie8 visible-ie9">Email</label>
                                        <input class="form-control placeholder-no-fix" type="text" placeholder="Email" name="email" /> </div>
                                    <div class="form-group">
                                        <label class="control-label visible-ie8 visible-ie9">Password</label>
                                        <input class="form-control placeholder-no-fix" type="password" autocomplete="off" id="register_password" placeholder="Password" name="password" /> </div>
                                    <div class="form-group">
                                        <label class="control-label visible-ie8 visible-ie9">Re-type Your Password</label>
                                        <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="Re-type Your Password" name="rpassword" /> </div>
                                    <div class="form-group margin-top-20 margin-bottom-20">
                                        <label class="check">
                                            <input type="checkbox" name="tnc" />
                                            <span class="loginblue-font">I agree to the </span>
                                            <a href="javascript:;" class="loginblue-link">Terms of Service</a>
                                            <span class="loginblue-font">and</span>
                                            <a href="javascript:;" class="loginblue-link">Privacy Policy </a>
                                        </label>
                                        <div id="register_tnc_error"> </div>
                                    </div>
                                    <div class="form-actions">
                                        <button type="button" id="register-back-btn" class="btn grey-steel">Back</button>
                                        <button type="submit" id="register-submit-btn" class="btn red uppercase pull-right">Submit</button>
                                    </div>
                                </form>
                                <!-- END REGISTRATION FORM -->
                            </div>
                        @else
                            <!-- BEGIN PORTLET -->
                            <div class="portlet light margin-bottom-15">
                                <div class="portlet-title">
                                    <div class="caption caption-md">
                                        <i class="icon-bar-chart theme-font hide"></i>
                                        <span class="caption-subject font-blue-madison bold uppercase">Friends & Own Activity</span>
                                        <span class="caption-helper">3 new</span>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <div class="scroller" style="height: 305px;" data-always-visible="1" data-rail-visible1="0" data-handle-color="#D7DCE2">
                                        <div class="general-item-list">
                                            @foreach ($meAndFriendsBookings as $knownBooking)
                                                <div class="item">
                                                    <div class="item-head">
                                                        <div class="item-details">
                                                            <img class="item-pic" src="../assets/pages/media/users/avatar4.jpg">
                                                            <a href="" class="item-name primary-link">{{ $knownBooking['breated_by'] }}</a>
                                                            <span class="item-label">{{ $knownBooking['passed_time_since_creation'] }}</span>
                                                        </div>
                                                        <span class="item-status">
                                                            <span class="badge badge-empty badge-warning"></span> {{ $knownBooking['status'] }} </span>
                                                    </div>
                                                    <div class="item-body"> Own booking for <span class="font-blue-hoki">{{ $knownBooking['book_date_format'] }}</span> in <span class="font-purple-sharp">{{ $knownBooking['on_location'] }}</span>
                                                        for <span class="font-blue-hoki">{{ $knownBooking['categoryName'] }}</span> activity. Reserved resource -  <span class="font-purple-sharp">{{ $knownBooking['on_resource'] }}</span> room. </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END PORTLET -->
                        @endif
                            <div class="portlet light search-page search-content-1 ">
                                <div class="search-container ">
                                    <ul>
                                        <li class="search-item clearfix">
                                            <a href="javascriptt:;">
                                                <img src="{{ asset('assets/pages/img/page_general_search/01.jpg') }}">
                                            </a>
                                            <div class="search-content">
                                                <h2 class="search-title">
                                                    <a href="javascript:;">Metronic Search Results</a>
                                                </h2>
                                                <p class="search-desc"> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec efficitur pellentesque auctor. Morbi lobortis, leo in tristique scelerisque, mauris quam volutpat nunc </p>
                                            </div>
                                        </li>
                                        <li class="search-item clearfix">
                                            <a href="javascript:;">
                                                <img src="{{ asset('assets/pages/img/page_general_search/1.jpg') }}">
                                            </a>
                                            <div class="search-content">
                                                <h2 class="search-title">
                                                    <a href="javascript:;">Lorem ipsum dolor</a>
                                                </h2>
                                                <p class="search-desc"> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec efficitur pellentesque auctor. Morbi lobortis, leo in tristique scelerisque, mauris quam volutpat nunc </p>
                                            </div>
                                        </li>
                                        <li class="search-item clearfix">
                                            <a href="javascript:;">
                                                <img src="{{ asset('assets/pages/img/page_general_search/02.jpg') }}">
                                            </a>
                                            <div class="search-content">
                                                <h2 class="search-title">
                                                    <a href="javascript:;">sit amet</a>
                                                </h2>
                                                <p class="search-desc"> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec efficitur pellentesque auctor. Morbi lobortis, leo in tristique scelerisque, mauris quam volutpat nunc </p>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END PAGE CONTENT INNER -->
            </div>

            <div class="modal fade draggable-modal" id="new_booking_modal" tabindex="-1" role="basic" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body form-horizontal">
                            <div class="portlet light " style="padding-bottom:0px;margin-bottom:0px;">
                                <div class="portlet-title form-group">
                                    <div class="caption">
                                        <i class="icon-social-dribbble font-green"></i>
                                        <span class="caption-subject font-green bold uppercase">Blockquotes</span>
                                    </div>
                                </div>
                                <div class="portlet-body form">
                                    <!-- Booking first step Start -->
                                    <form action="#" id="booking-step-one" role="form" name="new_booking1">
                                        <div class="form-body" style="padding-top:0px; padding-bottom:0px;">
                                            <div class="form-group note note-info margin-bottom-10">
                                                <label>Select Activity Room</label>
                                                <select class="form-control" name="resources_rooms" id="resources_rooms"></select>
                                                <small class="help-block"> this room is free to book until 12:30 </small>
                                            </div>
                                            <div class="form-group note note-success margin-bottom-10">
                                                <b3>Booking Time</b3><br />
                                                <strong>
                                                    <span class="pre_book_time"></span> on <span class="pre_book_date"></span></strong>
                                                <input type="hidden" name="selected_time" value="" />
                                            </div>
                                            <div class="form-group note note-info margin-bottom-10">
                                                <label>Payment Method</label>
                                                <!--<div class="radio-list">
                                                    <label class="radio-inline">
                                                        <input type="radio" name="payment_method" id="payment_method" value="membership" checked> Membership Included Booking </label>
                                                </div>-->
                                                <div class="radio-list">
                                                    <label class="radio-inline">
                                                        <input type="radio" name="payment_method" id="payment_method" value="cash-card" > Pay on Location Cash/Card </label>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <!-- Booking first step End -->

                                    <!-- Booking second step Start -->
                                    <form action="#" id="booking-step-two" role="form" name="new_booking2" style="display:none;">
                                        <div class="form-body" style="padding-top:0px; padding-bottom:0px;">
                                            <div class="alert alert-danger display-hide">
                                                <button class="close" data-close="alert"></button> You have some form errors. Please check below. </div>
                                            <div class="alert alert-success display-hide">
                                                <button class="close" data-close="alert"></button> Your form validation is successful! </div>
                                            <div class="form-group note note-info margin-bottom-10">
                                                <label>Select Activity Room</label>
                                                <span id="selected_resources_rooms"></span>
                                            </div>
                                            <div class="form-group note note-success margin-bottom-10">
                                                <b3>Booking Time</b3><br />
                                                <strong>
                                                    <span class="pre_book_time"></span> on <span class="pre_book_date"></span></strong>
                                                <input type="hidden" name="selected_time" value="" />
                                            </div>
                                            <div class="form-group note note-info margin-bottom-10">
                                                <!--<div class="radio-list">
                                                    <label class="radio-inline">
                                                        <input type="radio" name="payment_method" id="payment_method" value="membership" checked> Membership Included Booking </label>
                                                </div>-->
                                                <div class="radio-list">
                                                    <label>Payment Method</label>
                                                    <span class="text-info">Pay on Location Cash/Card </span>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <!-- Booking second step End -->
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn dark btn-outline submit_form_2" data-dismiss="modal">Close</button>
                            <button type="button" class="btn green submit_form_2" onclick="booking_step_two()">Next Step</button>
                            <button type="button" class="btn dark btn-outline submit_form_3" onclick="cancel_booking()" style="display:none;">Cancel Booking</button>
                            <button type="button" class="btn green submit_form_3" onclick="confirm_booking()" style="display:none;">Confirm Booking</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
        </div>
        <!-- END PAGE CONTENT BODY -->
        <!-- END CONTENT BODY -->
    </div>
@endsection

@section('pageBelowCorePlugins')
    <script src="{{ asset('assets/global/plugins/jquery.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/js.cookie.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/jquery.blockui.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/uniform/jquery.uniform.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}" type="text/javascript"></script>
@endsection

@section('pageBelowLevelPlugins')
    <script src="{{ asset('assets/global/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/jquery-validation/js/additional-methods.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>

    <script src="{{ asset('assets/global/plugins/moment.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/bootstrap-datepaginator/bootstrap-datepaginator.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/jquery-notific8/jquery.notific8.min.js') }}" type="text/javascript"></script>
@endsection

@section('themeBelowGlobalScripts')
    <script src="{{ asset('assets/global/scripts/app.min.js') }}" type="text/javascript"></script>
@endsection

@section('pageBelowLevelScripts')
    <script src="{{ asset('assets/pages/scripts/ui-notific8.min.js') }}" type="text/javascript"></script>
@endsection

@section('themeBelowLayoutScripts')
    <script src="{{ asset('assets/layouts/layout3/scripts/layout.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/layouts/global/scripts/quick-sidebar.min.js') }}" type="text/javascript"></script>
@endsection

@section('pageCustomJScripts')
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var Login = function() {

            var handleLogin = function() {

                $('.login-form').validate({
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    rules: {
                        username: {
                            required: true
                        },
                        password: {
                            required: true
                        },
                        remember: {
                            required: false
                        }
                    },

                    messages: {
                        username: {
                            required: "Username is required."
                        },
                        password: {
                            required: "Password is required."
                        }
                    },

                    invalidHandler: function(event, validator) { //display error alert on form submit
                        $('.alert-danger', $('.login-form')).show();
                    },

                    highlight: function(element) { // hightlight error inputs
                        $(element)
                                .closest('.form-group').addClass('has-error'); // set error class to the control group
                    },

                    success: function(label) {
                        label.closest('.form-group').removeClass('has-error');
                        label.remove();
                    },

                    errorPlacement: function(error, element) {
                        error.insertAfter(element.closest('.input-icon'));
                    },

                    submitHandler: function(form) {
                        form.submit(); // form validation success, call ajax form submit
                    }
                });

                $('.login-form input').keypress(function(e) {
                    if (e.which == 13) {
                        if ($('.login-form').validate().form()) {
                            $('.login-form').submit(); //form validation success, call ajax form submit
                        }
                        return false;
                    }
                });
            }

            var handleForgetPassword = function() {
                $('.forget-form').validate({
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    ignore: "",
                    rules: {
                        email: {
                            required: true,
                            email: true
                        }
                    },

                    messages: {
                        email: {
                            required: "Email is required."
                        }
                    },

                    invalidHandler: function(event, validator) { //display error alert on form submit

                    },

                    highlight: function(element) { // hightlight error inputs
                        $(element)
                                .closest('.form-group').addClass('has-error'); // set error class to the control group
                    },

                    success: function(label) {
                        label.closest('.form-group').removeClass('has-error');
                        label.remove();
                    },

                    errorPlacement: function(error, element) {
                        /*error.insertAfter(element.closest('.input-icon'));*/
                        error.insertAfter(element.closest('.form-control'));
                    },

                    submitHandler: function(form) {
                        form.submit();
                    }
                });

                $('.forget-form input').keypress(function(e) {
                    if (e.which == 13) {
                        if ($('.forget-form').validate().form()) {
                            $('.forget-form').submit();
                        }
                        return false;
                    }
                });

                jQuery('#forget-password').click(function() {
                    jQuery('.login-form').hide();
                    jQuery('.forget-form').show();
                });

                jQuery('#back-btn').click(function() {
                    jQuery('.login-form').show();
                    jQuery('.forget-form').hide();
                });

            }

            var handleRegister = function() {

                $('.register-form').validate({
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    ignore: "",
                    rules: {

                        firstname: {
                            required: true
                        },
                        lastname: {
                            required: true
                        },
                        phone: {
                            required: true
                        },
                        email: {
                            required: true,
                            email: true
                        },

                        username: {
                            required: true
                        },
                        password: {
                            required: true
                        },
                        rpassword: {
                            equalTo: "#register_password"
                        },

                        tnc: {
                            required: true
                        }
                    },

                    messages: { // custom messages for radio buttons and checkboxes
                        tnc: {
                            required: "Please accept TNC first."
                        }
                    },

                    invalidHandler: function(event, validator) { //display error alert on form submit

                    },

                    highlight: function(element) { // hightlight error inputs
                        $(element)
                                .closest('.form-group').addClass('has-error'); // set error class to the control group
                    },

                    success: function(label) {
                        label.closest('.form-group').removeClass('has-error');
                        label.remove();
                    },

                    errorPlacement: function(error, element) {
                        if (element.attr("name") == "tnc") { // insert checkbox errors after the container
                            error.insertAfter($('#register_tnc_error'));
                        } else if (element.closest('.input-icon').size() === 1) {
                            error.insertAfter(element.closest('.input-icon'));
                        } else {
                            error.insertAfter(element);
                        }
                    },

                    submitHandler: function(form) {
                        form.submit();
                    }
                });

                $('.register-form input').keypress(function(e) {
                    if (e.which == 13) {
                        if ($('.register-form').validate().form()) {
                            $('.register-form').submit();
                        }
                        return false;
                    }
                });

                jQuery('#register-btn').click(function() {
                    jQuery('.login-form').hide();
                    jQuery('.register-form').show();
                });

                jQuery('#register-back-btn').click(function() {
                    jQuery('.login-form').show();
                    jQuery('.register-form').hide();
                });
            }

            return {
                //main function to initiate the module
                init: function() {

                    handleLogin();
                    handleForgetPassword();
                    handleRegister();

                }

            };

        }();

        jQuery(document).ready(function() {
            Login.init();
        });

        var UIDatepaginator = function () {

            return {
                //main function to initiate the module
                init: function () {
                    //sample #3
                    var options_sample3 = {
                        endDate: '{{ \Carbon\Carbon::today()->addDays(7)->format('Y-m-d') }}',
                        startDate:'{{ \Carbon\Carbon::today()->format('Y-m-d') }}',
                        selectedDate: '{{ \Carbon\Carbon::today()->format('Y-m-d') }}',
                        showOffDays:false,
                        size:'large',
                        itemWidth:45,
                        selectedDateFormat:  'Do, MMM YYYY',
                        onSelectedDateChanged: function(event, date) {
                            //alert("Selected date: " + moment(date).format("YYYY-MM-DD"));
                            $('input[name=selected_date]').val(moment(date).format("YYYY-MM-DD"));
                            get_booking_hours(moment(date).format("YYYY-MM-DD"));
                        }
                    }

                    $('#datepaginator_sample_4').datepaginator(options_sample3);

                } // end init

            };

        }();

        jQuery(document).ready(function() {
            UIDatepaginator.init();
        });

        $(document).on('click', '.is_resource', function(){
            $('.is_resource').removeClass('active');
            $(this).addClass('active');

            $('input[name=selected_category]').val($(this).attr('data-id'));
            get_booking_hours();
        });

        $(document).on('click', '.book_step', function(){
        @if (isset($user))
            $('.pre_book_time').html( $.trim($(this).html()) );
            $('input[name=selected_time]').val($(this).html());
            $('.pre_book_date').html($('.dp-selected').attr('title'));

            get_resources_for_hour( $.trim($(this).html()) );

            $('#new_booking_modal').modal('show');
        @else
            jQuery('.forget-form').hide();
            jQuery('.register-form').hide();
            jQuery('.login-form').show();

            $('#username_focus').focus();
            show_notification('Please login', 'You need to login in order to use the booking', 'lemon', 3500, 0);
        @endif
        });

        $(document).on('click', '.location_btn', function(){
            $('.location_btn').removeClass('bg-grey-steel');
            $(this).addClass('bg-grey-steel');

            $('input[name=selected_location]').val($(this).attr('data-id'));
            get_booking_hours();
        });

        function get_booking_hours(selectedDate){
            App.blockUI({
                target: '#tasks-widget',
                boxed: true,
                message: 'Processing...'
            });

            selectedDate = typeof selectedDate !== 'undefined' ? selectedDate : $('input[name=selected_date]').val();
            $('.pre_book_date').html($('.dp-selected').attr('title'));

            $.ajax({
                url: '{{route('ajax/get_booking_hours')}}',
                type: "post",
                cache: false,
                data: {
                    'location_selected':    $('input[name=selected_location]').val(),
                    'date_selected':        selectedDate,
                    'selected_category':    $('input[name=selected_category]').val(),
                },
                success: function(data){
                    time_of_booking_format_hours(data.hours);
                    place_of_booking_format_rooms(data.shopResources);
                    App.unblockUI('#tasks-widget');
                }
            });
        }

        function time_of_booking_format_hours(hours){
            var all_hours = '';
            $.each(hours, function(key, value){
                if (value.color_stripe=='red-stripe'){
                    all_hours += '<a class="btn default ' + value.color_stripe + ' btn-lg" disabled href="javascript:;"> ' + key + ' </a> ';
                }
                else {
                    all_hours += '<a class="btn default ' + value.color_stripe + ' btn-lg book_step" href="javascript:;"> ' + key + ' </a> ';
                }
            });

            $("#booking_hours").html(all_hours);
        }

        function place_of_booking_format_rooms(resources){
            var all_rooms = '';
            $.each(resources, function(key, value){
                all_rooms+='<option value="'+ value.id +'"> '+ value.name +' </option> ';
            });
            $('#resources_rooms').html(all_rooms);
        }

        function get_resources_for_hour(bookTime){
            bookDate = $('input[name=selected_date]').val();

            $.ajax({
                url: '{{route('ajax/get_resource_date_time')}}',
                type: "post",
                cache: false,
                data: {
                    'location_selected':    $('input[name=selected_location]').val(),
                    'date_selected':        bookDate,
                    'time_selected':        bookTime,
                    'selected_category':    $('input[name=selected_category]').val(),
                },
                success: function(data){
                    place_of_booking_format_rooms(data);
                }
            });
        }

        function save_booking(){
            $.ajax({
                url: '{{route('booking.store')}}',
                type: "post",
                cache: false,
                data: {
                    'selected_location':    $('input[name=selected_location]').val(),
                    'selected_activity':    $('input[name=selected_category]').val(),
                    'selected_date':        $('input[name=selected_date]').val(),
                    'selected_time':        $('input[name=selected_time]').val(),
                    'selected_resource':    $('select[name=resources_rooms]').val(),
                    'selected_payment':     $('input[name=payment_method]:radio:checked').val(),

                },
                success: function(data){
                    get_booking_hours();
                    $('#new_booking_modal').modal('hide');
                }
            });
        }

        function confirm_booking(){
            $.ajax({
                url: '{{route('booking.store')}}',
                type: "post",
                cache: false,
                data: {
                    'selected_location':    $('input[name=selected_location]').val(),
                    'selected_activity':    $('input[name=selected_category]').val(),
                    'selected_date':        $('input[name=selected_date]').val(),
                    'selected_time':        $('input[name=selected_time]').val(),
                    'selected_resource':    $('select[name=resources_rooms]').val(),
                    'selected_payment':     $('input[name=payment_method]:radio:checked').val(),

                },
                success: function(data){
                    $('#new_booking_modal').modal('hide');
                    show_notification('Booking Confirmed', 'Your booking is now confirmed. You can see it in your list of bookings.', 'lemon', 3500, 0);

                    get_booking_hours();
                    booking_step_one();
                }
            });
        }

        function cancel_booking(){
            $.ajax({
                url: '{{route('booking.store')}}',
                type: "post",
                cache: false,
                data: {
                    'selected_location':    $('input[name=selected_location]').val(),
                    'selected_activity':    $('input[name=selected_category]').val(),
                    'selected_date':        $('input[name=selected_date]').val(),
                    'selected_time':        $('input[name=selected_time]').val(),
                    'selected_resource':    $('select[name=resources_rooms]').val(),
                    'selected_payment':     $('input[name=payment_method]:radio:checked').val(),

                },
                success: function(data){
                    $('#new_booking_modal').modal('hide');
                    show_notification('Booking Canceled', 'The selected booking is canceled. You can start the booking process again and select another date/time interval.', 'lemon', 3500, 0);

                    get_booking_hours();
                    booking_step_one();
                }
            });
        }

        function booking_step_two(){
            jQuery('#booking-step-one').hide();
            jQuery('.submit_form_2').hide();
            jQuery('#booking-step-two').show();
            jQuery('.submit_form_3').show();
        }

        function booking_step_one(){
            jQuery('#booking-step-one').show();
            jQuery('.submit_form_2').show();
            jQuery('#booking-step-two').hide();
            jQuery('.submit_form_3').hide();
        }

        jQuery(document).ready(function() {
            get_booking_hours();
        });

        @if($errors->has('email') || $errors->has('password'))
        setTimeout(function() {
            show_notification('{{$errors->first('header')}}', '{{$errors->first('message_body')}}', 'ruby', 10000, false);
        }, 500);
        @endif

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