@extends('login_header');

@section('main_content')
<!-- BEGIN LOGO -->
<div class="logo">
    <a href="index.html">
        <img src="{{ asset('assets/pages/img/logo-big.png') }}" alt="" /> </a>
</div>
<!-- END LOGO -->
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
                                            <h3 class="block">Location</h3>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Club name
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-4">
                                                    <input type="text" class="form-control" name="clubname" required/ >
                                                    <span class="help-block"> Provide your Club name </span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Contact Email
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-4">
                                                    <input type="text" class="form-control" name="email" required />
                                                    <span class="help-block"> Provide your email address </span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Phone
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-4">
                                                    <input type="text" class="form-control" name="phone" required />
                                                    <span class="help-block"> Provide your Phone </span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3"> Fax
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-4">
                                                    <input type="text" class="form-control" name="fax" required />
                                                    <span class="help-block"> Provide your Fax </span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3"> Address Line1
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-4">
                                                    <input type="text" class="form-control" name="addressline1" required />
                                                    <span class="help-block"> Provide your Address Line1</span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3"> Address Line2
                                                </label>
                                                <div class="col-md-4">
                                                    <input type="text" class="form-control" name="addressline2" />
                                                    <span class="help-block"> Provide your Address Line2</span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3"> City
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-4">
                                                    <input type="text" class="form-control" name="city" required />
                                                    <span class="help-block"> Provide your  City</span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3"> Region
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-4">
                                                    <input type="text" class="form-control" name="region" required />
                                                    <span class="help-block"> Provide your  Region</span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3"> Postal Code
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-4">
                                                    <input type="text" class="form-control" name="postalcode" required />
                                                    <span class="help-block"> Provide your  Postal Code</span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Country
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-4">
                                                    <select name="country" id="country" class="form-control">
                                                        <option value="">Select your Country</option>
                                                        <option value="AF">Afghanistan</option>
                                                        <option value="AL">Albania</option>
                                                        <option value="DZ">Algeria</option>
                                                    </select>
                                                    <span class="help-block"> Provide your Country</span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3"> Currency
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-4">
                                                    <select name="currency" id="currency" class="form-control" required>
                                                       <option value="">Select your Currency</option>
                                                        <option value="EUR">Euro</option>
                                                        <option value="AOA">Kwanza</option>
                                                        <option value="USD">US dollar</option>
                                                    </select>
                                                    <span class="help-block"> Provide your  Currency</span>
                                                </div>
                                            </div>
                                        </div>
                                        <!--END STEP 1-->
                                        <!--STEP 2-->
                                        <div class="tab-pane" id="tab2">
                                            <h3 class="block">Information about sport</h3>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Activity
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-4">
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
                                                <div class="col-md-4">
                                                    <select name="time" id="time" class="form-control" required>
                                                        <option value="">Select minimum reservation time</option>
                                                        <option value="1">30 min per slot</option>
                                                        <option value="2">60 min per slot</option>
                                                    </select>
                                                    <br>
                                                    <span> Tip: If you would like to see two names per booking, then we recommend to decrease the time slots in half. Example: <strong>  If you for instance operate with 60 min slots, but want see playing partner as well =, then choose 30 min slots. This way each member can add his friend to the 2nd part of the booking, so they are booked in on 30 min each, together they play for 60 min. Please see the example "click here"</strong>.</span>
                                                </div>
                                            </div>
                                        </div>
                                        <!--END STEP 2-->
                                        <!--STEP 3-->
                                        <div class="tab-pane" id="tab3">
                                            <h3 class="block">Booking behavior for drop-in customers</h3>
                                            <div class="form-group">
                                                <div class="col-md-offset-3 col-md-4">
                                                    <div class="well">
                                                        <strong>Note:</strong> the following settings will only count for non-members or "drop-in" customers. You can customize booking behavior, price for court and restrictions per membership/product you create in your "membership plan" or "products".
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3"> Do you allow to book drop-ins by non-members?
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-4">
                                                    <select name="members" id="members" class="form-control" required>
                                                        <option value="">Please select</option>
                                                        <option value="1">Yes</option>
                                                        <option value="2">No</option>
                                                    </select>
                                                    <span class="help-block"> Provide your "Yes" or "No"</span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3"> May customers pay for reservations online?
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-4">
                                                    <select name="pay" id="pay" class="form-control" required>
                                                        <option value="">Please select</option>
                                                        <option value="1">Yes</option>
                                                        <option value="2">No</option>
                                                    </select>
                                                    <span class="help-block"> Provide your "Yes" or "No"</span>
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
                                                <div class="col-md-4">
                                                    <select name="resource" id="resource" class="form-control" required>
                                                        <option value="">Please select</option>
                                                        <option value="1">Everyone</option>
                                                        <option value="2">Only profiles who are logged in</option>
                                                    </select>
                                                    <span class="help-block"> Provide your "Everyone" or "Only profiles who are logged in"</span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3"> How early do you want users to be able to create bookings?
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-4">
                                                    <select name="day" id="day" class="form-control" required>
                                                        <option value="">Please select</option>
                                                        <option value="1">Monday</option>
                                                        <option value="2">Tuesday</option>
                                                        <option value="3">Wednesday</option>
                                                        <option value="4">Thursday</option>
                                                        <option value="5">Friday</option>
                                                        <option value="6">Saturday</option>
                                                        <option value="7">Sunday</option>
                                                    </select>
                                                    <span class="help-block"> Provide your day</span>
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
                                                <div class="col-md-4">
                                                    <select name="limit" id="limit" class="form-control" required>
                                                        <option value="">Please select</option>
                                                        <option value="1">1 hours</option>
                                                        <option value="2">2 hours</option>
                                                        <option value="3">3 hours</option>
                                                        <option value="4">4 hours</option>
                                                        <option value="5">5 hours</option>
                                                        <option value="6">6 hours</option>
                                                    </select>
                                                    <span class="help-block"> Provide your hours</span>
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
                                            <h3 class="block">Finish</h3>
                                            <div class="col-md-offset-4 col-md-4 well">
                                                <p><strong>Finish:</strong> <a href="#" class="text-info">"Click here"</a> to finish your registration! But don`t worry if you want to make changes. Every setting can be changed in "General settings".</p>
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
                                        <div class="col-md-offset-4 col-md-8">
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
<div class="copyright"> 2014 &copy; Metronic - Admin Dashboard Template. </div>
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