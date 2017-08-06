@extends('login_header');

@section('main_content')
<!-- BEGIN REGISTRATION -->
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light bordered" id="form_wizard_1">
                    <div class="portlet-body form">
                        <form class="form-horizontal" action="#" id="submit_form" method="POST">
                            <div class="form-wizard">
                                <div class="form-body">
                                    <ul class="nav nav-pills nav-justified steps">
                                        <li>
                                            <a href="#tab1" data-toggle="tab" class="step">
                                                <span class="number"> 1 </span>
                                                <span class="desc">
                                                    <i class="fa fa-check"></i> Location Setup </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#tab2" data-toggle="tab" class="step">
                                                <span class="number"> 2 </span>
                                                <span class="desc">
                                                    <i class="fa fa-check"></i> Sport Setup </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#tab3" data-toggle="tab" class="step active">
                                                <span class="number"> 3 </span>
                                                <span class="desc">
                                                    <i class="fa fa-check"></i> Booking behavior Setup </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#tab4" data-toggle="tab" class="step">
                                                <span class="number"> 4 </span>
                                                <span class="desc">
                                                    <i class="fa fa-check"></i>  Finish </span>
                                            </a>
                                        </li>
                                    </ul>
                                    <div id="bar" class="progress progress-striped" role="progressbar">
                                        <div class="progress-bar progress-bar-success"> </div>
                                    </div>
                                    <div class="tab-content">
                                        <div class="alert alert-danger display-none">
                                            <button class="close" data-dismiss="alert"></button> You have some form errors. Please check below. </div>
                                        <div class="alert alert-success display-none">
                                            <button class="close" data-dismiss="alert"></button> Your form validation is successful! </div>
                                        <!--STEP 1-->
                                        <div class="tab-pane tab-pane-width active" id="tab1">
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Club name
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" name="clubname" value = "{{ ! empty($shopLocation) ? $shopLocation->name : ''}}" required/ >
                                                    <span class="help-block"> Provide your Club name </span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Contact Email
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" name="email" value = "{{ ! empty($user) ? $user->username : '' }}" required />
                                                    <span class="help-block"> Provide your email address </span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Phone
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" name="phone" required />
                                                    <span class="help-block"> Provide your Phone </span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3"> Address Line1
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" name="addressline1" required />
                                                    <span class="help-block"> Provide your Address Line1</span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3"> Address Line2
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" name="addressline2" />
                                                    <span class="help-block"> Provide your Address Line2</span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3"> City
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" name="city" required />
                                                    <span class="help-block"> Provide your  City</span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3"> Region
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" name="region" required />
                                                    <span class="help-block"> Provide your  Region</span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3"> Postal Code
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" name="postalcode" required />
                                                    <span class="help-block"> Provide your  Postal Code</span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Country
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-9">
                                                    <select name="country" class="form-control" id="country">
                                                        <option value="">Select country from a list</option>
                                                        @foreach($countries as $country)
                                                             <option value="{{ $country->id }}">{{ $country->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <span class="help-block"> Provide your Country</span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3"> Currency
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-9">
                                                    <select name="currency" class="form-control" id="currency">
                                                        <option value="">Select your Currency</option>
                                                        @foreach($currencies as $currency)
                                                            @if(isset($currency->currency_code) && $currency->currency_code != '')
                                                            <option value="{{ $currency->currency_code }}">{{ $currency->currency_code }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                    <span class="help-block"> Provide your  Currency</span>
                                                </div>
                                            </div>
                                        </div>
                                        <!--END STEP 1-->
                                       <!-- STEP 2-->
                                        <div class="tab-pane tab-pane-width" id="tab2">
                                            <div class="form-group">
                                                <label class="control-label col-md-4">Activity
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-7">
                                                    <select name="sport" id="sport" class="form-control" required>
                                                        <option value="">Select activity</option>
                                                        @foreach($shopResourceCategories as $item)
                                                            <option value="{{$item->id}}"> {{$item->name}} </option>
                                                        @endforeach
                                                    </select>
                                                    <span class="help-block"> Provide your Activity</span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-4">Minimum reservation time/time slot per booking
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-7">
                                                    <select name="time" id="time" class="form-control" required>
                                                        <option value="">Select minimum reservation time</option>
                                                        @for($i = 5; $i<=180; $i=$i+5)
                                                            <option value="{{$i}}">{{$i}} min per slot</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                                <div class="col-md-1">
                                                    <div class="btn-group">
                                                        <a class="btn blue dropdown-toggle" data-toggle="dropdown" href="javascript:;" aria-expanded="false">  <i class="fa fa-info"></i></a>
                                                        <p class="dropdown-menu btn-info-paragraphe">
                                                            Tip: If you would like to see two names per booking, then we recommend to decrease the time slots in half. Example:   If you for instance operate with 60 min slots, but want see playing partner as well, then choose 30 min slots. This way each member can add his friend to the 2nd part of the booking, so they are booked in on 30 min each, together they play for 60 min. Please see the example <br> <a href="#">'click here'</a>.
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--END STEP 2-->
                                        <!--STEP 3-->
                                        <div class="tab-pane tab-pane-width" id="tab3">
                                            <div class="form-group">
                                                <div class="col-md-12">
                                                    <div class="well">
                                                        <strong>Note:</strong> the following settings will only count for non-members or "drop-in" customers. You can customize booking behavior, price for court and restrictions per membership/product you create in your "membership plan" or "products".
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-4"> Do you allow non-members to arrange reservations?":
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-7">
                                                    <select name="members" id="members" class="form-control" required>
                                                        <option value="">Please select</option>
                                                        <option value="1">Yes</option>
                                                        <option value="0">No</option>
                                                    </select>
                                                    <span class="help-block"> Select "Yes" or "No"</span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-4"> Do you only accept direct online payments for court reservations?
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-7">
                                                    <select name="pay" id="pay" class="form-control" required>
                                                        <option value="">Please select</option>
                                                        <option value="1">Yes</option>
                                                        <option value="0">No</option>
                                                    </select>
                                                    <span class="help-block"> Select "Yes" or "No"</span>
                                                </div>
                                                <div class="col-md-1">
                                                    <div class="btn-group">
                                                        <a class="btn blue dropdown-toggle" data-toggle="dropdown" href="javascript:;" aria-expanded="false">  <i class="fa fa-info"></i></a>
                                                        <p class="dropdown-menu btn-info-paragraphe">
                                                            This question regards online payment via Paypal or other online payment solutions. By saying 'No', you can still allow members to pay with 'store credit' (pre paid voucher).
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-4"> Who may see your resource availability?
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-7">
                                                    <select name="resource" id="resource" class="form-control" required>
                                                        <option value="">Please select</option>
                                                        <option value="1">Everyone</option>
                                                        <option value="0">Only profiles who are logged in</option>
                                                    </select>
                                                    <span class="help-block"> Select "Everyone" or "Only profiles who are logged in"</span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-4"> How early do you want users to be able to create bookings?
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-7">
                                                    <select name="day" id="day" class="form-control" required>
                                                        <option value="">Please select</option>
                                                        <option value="1">1 day</option>
                                                        <option value="2">2 day</option>
                                                        <option value="3">3 day</option>
                                                        <option value="4">4 day</option>
                                                        <option value="5">5 day</option>
                                                        <option value="6">6 day</option>
                                                        <option value="7">7 day</option>
                                                        <option value="8">8 day</option>
                                                        <option value="9">9 day</option>
                                                        <option value="10">10 day</option>
                                                        <option value="11">11 day</option>
                                                        <option value="12">12 day</option>
                                                        <option value="13">13 day</option>
                                                        <option value="14">14 day</option>
                                                    </select>
                                                    <span class="help-block"> Select a day</span>
                                                </div>
                                                <div class="col-md-1">
                                                    <div class="btn-group">
                                                        <a class="btn blue dropdown-toggle" data-toggle="dropdown" href="javascript:;" aria-expanded="false">  <i class="fa fa-info"></i></a>
                                                        <p class="dropdown-menu btn-info-paragraphe">
                                                            This will effect how many days ahead a user may reserve a resource (it possible to create a setting for this per membership)
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-4"> Cancellation limit?
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-7">
                                                    <select name="limit" id="limit" class="form-control" required>
                                                        <option value="">Please select</option>
                                                        <option value="1">1 hours</option>
                                                        <option value="2">2 hours</option>
                                                        <option value="3">3 hours</option>
                                                        <option value="4">4 hours</option>
                                                        <option value="5">5 hours</option>
                                                        <option value="6">6 hours</option>
                                                        <option value="7">7 hours</option>
                                                        <option value="8">8 hours</option>
                                                        <option value="9">9 hours</option>
                                                        <option value="10">10 hours</option>
                                                        <option value="11">11 hours</option>
                                                        <option value="12">12 hours</option>
                                                        <option value="13">13 hours</option>
                                                        <option value="14">14 hours</option>
                                                        <option value="15">15 hours</option>
                                                        <option value="16">16 hours</option>
                                                        <option value="17">17 hours</option>
                                                        <option value="18">18 hours</option>
                                                        <option value="19">19 hours</option>
                                                        <option value="20">20 hours</option>
                                                        <option value="21">21 hours</option>
                                                        <option value="22">22 hours</option>
                                                        <option value="23">23 hours</option>
                                                        <option value="24">24 hours</option>
                                                        <option value="25">25 hours</option>
                                                        <option value="26">26 hours</option>
                                                        <option value="27">27 hours</option>
                                                        <option value="28">28 hours</option>
                                                        <option value="29">29 hours</option>
                                                        <option value="30">30 hours</option>
                                                        <option value="31">31 hours</option>
                                                        <option value="32">32 hours</option>
                                                        <option value="33">33 hours</option>
                                                        <option value="34">34 hours</option>
                                                        <option value="35">35 hours</option>
                                                        <option value="36">36 hours</option>
                                                        <option value="37">37 hours</option>
                                                        <option value="38">38 hours</option>
                                                        <option value="39">39 hours</option>
                                                        <option value="40">40 hours</option>
                                                        <option value="41">41 hours</option>
                                                        <option value="42">42 hours</option>
                                                        <option value="43">43 hours</option>
                                                        <option value="44">44 hours</option>
                                                        <option value="45">45 hours</option>
                                                        <option value="46">46 hours</option>
                                                        <option value="47">47 hours</option>
                                                        <option value="48">48 hours</option>
                                                    </select>
                                                    <span class="help-block"> Select an hours</span>
                                                </div>
                                                <div class="col-md-1">
                                                     <div class="btn-group">
                                                        <a class="btn blue dropdown-toggle" data-toggle="dropdown" href="javascript:;" aria-expanded="false">  <i class="fa fa-info"></i></a>
                                                        <p class="dropdown-menu btn-info-paragraphe">
                                                            The user will be able to cancel "x" amount of hours before booking starting time
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--END STEP 3-->
                                        <!--STEP 4-->
                                        <div class="tab-pane clearfix" id="tab4">
                                            <div class="col-md-offset-4 col-md-4 well">
                                                <p><strong>Finish:</strong> Click submit to finish your registration! But don`t worry if you want to make changes. Every setting can be changed in "General settings".</p>
                                            </div>
                                            <div class="col-md-12">
                                                <h3 class="text-center text-success">Congrats! You can now start using Book247!</h3>
                                            </div>
                                        </div>
                                        <!--END STEP 4-->
                                    </div>
                                </div>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="form-actions">
                                    <div class="row">
                                        <div class="col-md-12 text-center">
                                            <a href="javascript:;" class="btn default button-previous">
                                                <i class="fa fa-angle-left"></i> Back </a>
                                            <a href="javascript:;" class="btn btn-outline green button-next"> Continue
                                                <i class="fa fa-angle-right"></i>
                                            </a>
                                            <a href="javascript:;" class="btn green button-submit"> Submit
                                                <i class="fa fa-check"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- END REGISTRATION -->
<!-- BEGIN COPYRIGHT -->
<div class="copyright"> 2017 © BookingSystem by SQF. Squash Fitness! </div>
<!-- END COPYRIGHT -->
<!--[if lt IE 9]>
<script src="{{ asset('assets/global/plugins/respond.min.js') }}"></script>
<script src="{{ asset('assets/global/plugins/excanvas.min.js') }}"></script>
<![endif]-->
<!-- BEGIN CORE PLUGINS -->
<script src="{{ asset('assets/global/plugins/jquery.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/js.cookie.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery.blockui.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/uniform/jquery.uniform.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="{{ asset('assets/global/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery-validation/js/additional-methods.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-wizard/jquery.bootstrap.wizard.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/backstretch/jquery.backstretch.min.js') }}" type="text/javascript"></script>
<!--script src="{{ asset('assets/pages/scripts/form-wizard.min.js') }}" type="text/javascript"></script-->
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN THEME GLOBAL SCRIPTS -->
<script src="{{ asset('assets/global/scripts/app.min.js') }}" type="text/javascript"></script>
<!-- END THEME GLOBAL SCRIPTS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="{{ asset('assets/layouts/layout4/scripts/layout.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/pages/scripts/login-4.min.js') }}" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<!-- BEGIN THEME LAYOUT SCRIPTS -->
<!-- END THEME LAYOUT SCRIPTS -->
<script type="application/javascript">
    var FormWizard = function () {

        return {
            //main function to initiate the module
            init: function () {
                if (!jQuery().bootstrapWizard) {
                    return;
                }

                function format(state) {
                    if (!state.id) return state.text; // optgroup
                    return "<img class='flag' src='../../assets/global/img/flags/" + state.id.toLowerCase() + ".png'/>&nbsp;&nbsp;" + state.text;
                }

                $("#country_list").select2({
                    placeholder: "Select",
                    allowClear: true,
                    formatResult: format,
                    width: 'auto',
                    formatSelection: format,
                    escapeMarkup: function (m) {
                        return m;
                    }
                });

                var form = $('#submit_form');
                var error = $('.alert-danger', form);
                var success = $('.alert-success', form);

                form.validate({
                    doNotHideMessage: true, //this option enables to show the error/success messages on tab switch.
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block help-block-error', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    rules: {
                        //account
                        username: {
                            minlength: 5,
                            required: true
                        },
                        password: {
                            minlength: 5,
                            required: true
                        },
                        rpassword: {
                            minlength: 5,
                            required: true,
                            equalTo: "#submit_form_password"
                        },
                        //profile
                        fullname: {
                            required: true
                        },
                        email: {
                            required: true,
                            email: true
                        },
                        phone: {
                            required: true
                        },
                        gender: {
                            required: true
                        },
                        address: {
                            required: true
                        },
                        city: {
                            required: true
                        },
                        country: {
                            required: true
                        },
                        currency: {
                            required: true
                        },
                        //payment
                        card_name: {
                            required: true
                        },
                        card_number: {
                            minlength: 16,
                            maxlength: 16,
                            required: true
                        },
                        card_cvc: {
                            digits: true,
                            required: true,
                            minlength: 3,
                            maxlength: 4
                        },
                        card_expiry_date: {
                            required: true
                        },
                        'payment[]': {
                            required: true,
                            minlength: 1
                        }
                    },

                    messages: { // custom messages for radio buttons and checkboxes
                        'payment[]': {
                            required: "Please select at least one option",
                            minlength: jQuery.validator.format("Please select at least one option")
                        }
                    },

                    errorPlacement: function (error, element) { // render error placement for each input type
                        if (element.attr("name") == "gender") { // for uniform radio buttons, insert the after the given container
                            error.insertAfter("#form_gender_error");
                        } else if (element.attr("name") == "payment[]") { // for uniform checkboxes, insert the after the given container
                            error.insertAfter("#form_payment_error");
                        } else {
                            error.insertAfter(element); // for other inputs, just perform default behavior
                        }
                    },

                    invalidHandler: function (event, validator) { //display error alert on form submit
                        success.hide();
                        error.show();
                        App.scrollTo(error, -200);
                    },

                    highlight: function (element) { // hightlight error inputs
                        $(element)
                            .closest('.form-group').removeClass('has-success').addClass('has-error'); // set error class to the control group
                    },

                    unhighlight: function (element) { // revert the change done by hightlight
                        $(element)
                            .closest('.form-group').removeClass('has-error'); // set error class to the control group
                    },

                    success: function (label) {
                        if (label.attr("for") == "gender" || label.attr("for") == "payment[]") { // for checkboxes and radio buttons, no need to show OK icon
                            label
                                .closest('.form-group').removeClass('has-error').addClass('has-success');
                            label.remove(); // remove error label here
                        } else { // display success icon for other inputs
                            label
                                .addClass('valid') // mark the current input as valid and display OK icon
                                .closest('.form-group').removeClass('has-error').addClass('has-success'); // set success class to the control group
                        }
                    },

                    submitHandler: function (form) {
                        success.show();
                        error.hide();
                        var data = $(form).serialize();
                        sendData(data);
                    }

                });

                var displayConfirm = function() {
                    $('#tab4 .form-control-static', form).each(function(){
                        var input = $('[name="'+$(this).attr("data-display")+'"]', form);
                        if (input.is(":radio")) {
                            input = $('[name="'+$(this).attr("data-display")+'"]:checked', form);
                        }
                        if (input.is(":text") || input.is("textarea")) {
                            $(this).html(input.val());
                        } else if (input.is("select")) {
                            $(this).html(input.find('option:selected').text());
                        } else if (input.is(":radio") && input.is(":checked")) {
                            $(this).html(input.attr("data-title"));
                        } else if ($(this).attr("data-display") == 'payment[]') {
                            var payment = [];
                            $('[name="payment[]"]:checked', form).each(function(){
                                payment.push($(this).attr('data-title'));
                            });
                            $(this).html(payment.join("<br>"));
                        }
                    });
                }

                var handleTitle = function(tab, navigation, index) {
                    var total = navigation.find('li').length;
                    var current = index + 1;
                    // set wizard title
                    $('.step-title', $('#form_wizard_1')).text('Step ' + (index + 1) + ' of ' + total);
                    // set done steps
                    jQuery('li', $('#form_wizard_1')).removeClass("done");
                    var li_list = navigation.find('li');
                    for (var i = 0; i < index; i++) {
                        jQuery(li_list[i]).addClass("done");
                    }

                    if (current == 1) {
                        $('#form_wizard_1').find('.button-previous').hide();
                    } else {
                        $('#form_wizard_1').find('.button-previous').show();
                    }

                    if (current >= total) {
                        $('#form_wizard_1').find('.button-next').hide();
                        $('#form_wizard_1').find('.button-submit').show();
                        displayConfirm();
                    } else {
                        $('#form_wizard_1').find('.button-next').show();
                        $('#form_wizard_1').find('.button-submit').hide();
                    }
                    App.scrollTo($('.page-title'));
                }

                // default form wizard
                $('#form_wizard_1').bootstrapWizard({
                    'nextSelector': '.button-next',
                    'previousSelector': '.button-previous',
                    onTabClick: function (tab, navigation, index, clickedIndex) {
                        return false;

                        success.hide();
                        error.hide();
                        if (form.valid() == false) {
                            return false;
                        }

                        handleTitle(tab, navigation, clickedIndex);
                    },
                    onNext: function (tab, navigation, index) {
                        success.hide();
                        error.hide();

                        if (form.valid() == false) {
                            return false;
                        }

                        handleTitle(tab, navigation, index);
                    },
                    onPrevious: function (tab, navigation, index) {
                        success.hide();
                        error.hide();

                        handleTitle(tab, navigation, index);
                    },
                    onTabShow: function (tab, navigation, index) {
                        var total = navigation.find('li').length;
                        var current = index + 1;
                        var $percent = (current / total) * 100;
                        $('#form_wizard_1').find('.progress-bar').css({
                            width: $percent + '%'
                        });
                    }
                });

                var sendData = function (data){
                    $.ajax({
                        url: '/admin/registration',
                        type: "post",
                        data: data,
                        success: function (data) {
                            if (data.success == true) {
                                window.location.href = '/admin';
                            }
                            else{
                                window.location.href = '/';
                            }
                        }
                    });
                }


                $('#form_wizard_1').find('.button-previous').hide();
                $('#form_wizard_1 .button-submit').click(function () {
                    $('#submit_form').submit();
                }).hide();

                //apply validation on select2 dropdown value change, this only needed for chosen dropdown integration.
                $('#country_list', form).change(function () {
                    form.validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input
                });
            }

        };

    }();

    jQuery(document).ready(function() {
        FormWizard.init();
    });
</script>
@stop