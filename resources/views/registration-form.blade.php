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
                                        <div class="tab-pane active" id="tab1">
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Club name
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-5">
                                                    <input type="text" class="form-control" name="clubname" required/ >
                                                    <span class="help-block"> Provide your Club name </span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Contact Email
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-5">
                                                    <input type="text" class="form-control" name="email" required />
                                                    <span class="help-block"> Provide your email address </span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Phone
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-5">
                                                    <input type="text" class="form-control" name="phone" required />
                                                    <span class="help-block"> Provide your Phone </span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3"> Fax
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-5">
                                                    <input type="text" class="form-control" name="fax" required />
                                                    <span class="help-block"> Provide your Fax </span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3"> Address Line1
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-5">
                                                    <input type="text" class="form-control" name="addressline1" required />
                                                    <span class="help-block"> Provide your Address Line1</span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3"> Address Line2
                                                </label>
                                                <div class="col-md-5">
                                                    <input type="text" class="form-control" name="addressline2" />
                                                    <span class="help-block"> Provide your Address Line2</span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3"> City
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-5">
                                                    <input type="text" class="form-control" name="city" required />
                                                    <span class="help-block"> Provide your  City</span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3"> Region
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-5">
                                                    <input type="text" class="form-control" name="region" required />
                                                    <span class="help-block"> Provide your  Region</span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3"> Postal Code
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-5">
                                                    <input type="text" class="form-control" name="postalcode" required />
                                                    <span class="help-block"> Provide your  Postal Code</span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Country
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-5">
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
                                                <div class="col-md-5">
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
                                        <div class="tab-pane" id="tab2">
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Activity
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-5">
                                                    <select name="sport" id="sport" class="form-control" required>
                                                        <option value="">Select activity</option>
                                                        <option value="1">Tennis</option>
                                                        <option value="2">Squash</option>
                                                        <option value="3">Volley</option>
                                                        <option value="4">Badminton</option>
                                                    </select>
                                                    <span class="help-block"> Provide your Activity</span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Minimum reservation time/time slot per booking
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-5">
                                                    <select name="time" id="time" class="form-control" required>
                                                        <option value="">Select minimum reservation time</option>
                                                        <option value="10">10 min per slot</option>
                                                        <option value="15">15 min per slot</option>
                                                        <option value="20">20 min per slot</option>
                                                        <option value="25">25 min per slot</option>
                                                        <option value="30">30 min per slot</option>
                                                        <option value="35">35 min per slot</option>
                                                        <option value="40">40 min per slot</option>
                                                        <option value="45">45 min per slot</option>
                                                        <option value="50">50 min per slot</option>
                                                        <option value="55">55 min per slot</option>
                                                        <option value="60">60 min per slot</option>
                                                        <option value="65">65 min per slot</option>
                                                        <option value="70">70 min per slot</option>
                                                        <option value="75">75 min per slot</option>
                                                        <option value="80">80 min per slot</option>
                                                        <option value="85">85 min per slot</option>
                                                        <option value="90">90 min per slot</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="btn-group">
                                                        <a class="btn blue dropdown-toggle" data-toggle="dropdown" href="javascript:;" aria-expanded="false">  <i class="fa fa-info"></i></a>
                                                        <p class="dropdown-menu btn-info-paragraphe">
                                                            Tip: If you would like to see two names per booking, then we recommend to decrease the time slots in half. Example:   If you for instance operate with 60 min slots, but want see playing partner as well =, then choose 30 min slots. This way each member can add his friend to the 2nd part of the booking, so they are booked in on 30 min each, together they play for 60 min. Please see the example <br> <a href="#">'click here'</a>.
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--END STEP 2-->
                                        <!--STEP 3-->
                                        <div class="tab-pane" id="tab3">
                                            <div class="form-group">
                                                <div class="col-md-offset-3 col-md-4">
                                                    <div class="well">
                                                        <strong>Note:</strong> the following settings will only count for non-members or "drop-in" customers. You can customize booking behavior, price for court and restrictions per membership/product you create in your "membership plan" or "products".
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3"> Do you allow non-members to arrange reservations?":
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-5">
                                                    <select name="members" id="members" class="form-control" required>
                                                        <option value="">Please select</option>
                                                        <option value="1">Yes</option>
                                                        <option value="2">No</option>
                                                    </select>
                                                    <span class="help-block"> Select "Yes" or "No"</span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3"> Do you only accept direct online payments for court reservations?
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-5">
                                                    <select name="pay" id="pay" class="form-control" required>
                                                        <option value="">Please select</option>
                                                        <option value="1">Yes</option>
                                                        <option value="2">No</option>
                                                    </select>
                                                    <span class="help-block"> Select "Yes" or "No"</span>
                                                </div>
                                                <div class="col-md-4">
                                                    <button class="btn btn-default popovers blue" data-container="body" data-trigger="hover" data-placement="right" data-content="This question regards online payment via Paypal or other online payment solutions. By saying 'No', you can still allow members to pay with 'store credit' (pre paid voucher)">
                                                        <i class="fa fa-info"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3"> Who may see your resource availability?
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-5">
                                                    <select name="resource" id="resource" class="form-control" required>
                                                        <option value="">Please select</option>
                                                        <option value="1">Everyone</option>
                                                        <option value="2">Only profiles who are logged in</option>
                                                    </select>
                                                    <span class="help-block"> Select "Everyone" or "Only profiles who are logged in"</span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3"> How early do you want users to be able to create bookings?
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-5">
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
                                                    <span class="help-block"> Select your day</span>
                                                </div>
                                                <div class="col-md-4">
                                                    <button class="btn btn-default popovers blue" data-container="body" data-trigger="hover" data-placement="right" data-content="This will effect how many days ahead a user may reserve a resource (it possible to create a setting for this per membership)">
                                                        <i class="fa fa-info"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3"> Cancellation limit?
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-5">
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
                                                    <span class="help-block"> Select your hours</span>
                                                </div>
                                                <div class="col-md-4">
                                                    <button class="btn btn-default popovers blue" data-container="body" data-trigger="hover" data-placement="right" data-content='The user will be able to cancel "x" amount of hours before booking starting time'>
                                                        <i class="fa fa-info"></i>
                                                    </button>
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
<script src="{{ asset('assets/pages/scripts/form-wizard.min.js') }}" type="text/javascript"></script>
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
@stop