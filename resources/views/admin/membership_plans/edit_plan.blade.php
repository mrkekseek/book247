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
            <div class="col-md-12">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-equalizer font-red-sunglo"></i>
                            <span class="caption-subject font-red-sunglo bold uppercase"> Membership plan details</span>
                            <span class="caption-helper">update details here...</span>
                        </div>
                        <div class="tools">
                            <a class="expand" href="" data-original-title="" title=""> </a>
                        </div>
                    </div>
                    <div class="portlet-body form" style="display:none;">
                        <!-- BEGIN FORM-->
                        <form action="#" class="form-horizontal">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-3"> Membership Name </label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" name="membership_name" placeholder="New plan name">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3 inline"> Membership Price </label>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control input-inline input-small" name="membership_price" placeholder="NOK">
                                            </div>
                                            <div class="col-md-5">
                                                <select name="membership_period" class="form-control input-inline input-small  inline-block">
                                                    <option value="7d">7 days</option>
                                                    <option value="14d">14 days</option>
                                                    <option value="1m">one month</option>
                                                    <option value="3m">three months</option>
                                                    <option value="6m">six months</option>
                                                    <option value="12m">12 months</option>
                                                </select>
                                                <span class="help-inline inline-block"> Invoicing Period </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3"> Administration Fee Name </label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" name="administration_fee_name" placeholder="Fee Name">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3"> Administration Fee Price </label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control input-medium" name="administration_fee_price" placeholder="Fee Price">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Plan Color</label>
                                            <div class="col-md-3">
                                                <div class="input-group color colorpicker-default" data-color="#3865a8" data-color-format="rgba">
                                                    <input type="text" class="form-control" name="membership_color" value="#3865a8" readonly>
                                                        <span class="input-group-btn">
                                                            <button class="btn default" type="button">
                                                                <i style="background-color: #3865a8;"></i>&nbsp;</button>
                                                        </span>
                                                </div>
                                                <!-- /input-group -->
                                            </div>
                                            <div class="col-md-6">
                                                <span class="help-inline  block-inline"> Color to be displayed in calendar booking </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3 inline">Short Description</label>
                                            <div class="col-md-9">
                                                <textarea name="membership_short_description" style="min-height:100px;" class="form-control"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <!--/span-->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-3 inline">Long Description</label>
                                            <div class="col-md-9">
                                                <textarea name="membership_long_description" style="height:246px;" class="form-control"></textarea>
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

            <div class="col-md-12">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-equalizer font-red-sunglo"></i>
                            <span class="caption-subject font-red-sunglo bold uppercase">Add new restrictions</span>
                            <span class="caption-helper">set variables for new restrictions...</span>
                        </div>
                        <div class="tools">
                            <a class="expand" href="" data-original-title="" title=""> </a>
                        </div>
                    </div>
                    <div class="portlet-body form tabbable-line boxless tabbable-reversed" style="display:none;">
                        <!-- BEGIN FORM-->
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#tab_0" data-toggle="tab"> Include Activity </a>
                            </li>
                            <li>
                                <a href="#tab_1" data-toggle="tab"> Booking Time Period </a>
                            </li>
                            <li>
                                <a href="#tab_2" data-toggle="tab"> Booking Time of Day </a>
                            </li>
                            <li>
                                <a href="#tab_3" data-toggle="tab"> Open Bookings </a>
                            </li>
                            <li>
                                <a href="#tab_4" data-toggle="tab"> Cancellation </a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_0">
                                <div class="portlet light bordered form-fit">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="icon-equalizer font-green-haze"></i>
                                            <span class="caption-subject font-green-haze bold uppercase">Activities</span>
                                            <span class="caption-helper">select witch ones are included in membership plan...</span>
                                        </div>
                                        <div class="actions">  </div>
                                    </div>
                                    <div class="portlet-body form">
                                        <!-- BEGIN FORM-->
                                        <form action="#" class="form-horizontal form-row-seperated">
                                            <div class="form-body">
                                                <div class="form-group">
                                                    <label class="control-label col-md-3">Select Included Activities</label>
                                                    <div class="col-md-9">
                                                        <select class="form-control input-large" multiple style="height:120px;">
                                                            <option> Tennis </option>
                                                            <option> Squash </option>
                                                            <option> Polo </option>
                                                            <option> Golf </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-actions">
                                                <div class="row">
                                                    <div class="col-md-offset-3 col-md-9">
                                                        <button type="submit" class="btn green">
                                                            <i class="fa fa-pencil"></i> Add Activities Restriction </button>
                                                        <button type="button" class="btn default">Cancel</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <!-- END FORM-->
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_1">
                                <div class="portlet light bordered form-fit">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="icon-equalizer font-green-haze"></i>
                                            <span class="caption-subject font-green-haze bold uppercase">Form Sample</span>
                                            <span class="caption-helper">some info...</span>
                                        </div>
                                        <div class="actions">  </div>
                                    </div>
                                    <div class="portlet-body form">
                                        <!-- BEGIN FORM-->
                                        <form action="#" class="form-horizontal form-row-seperated">
                                            <div class="form-body">
                                                <div class="form-group">
                                                    <label class="control-label col-md-2"> Hours Before </label>
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control form-inline input-xsmall inline-block" placeholder="hour">
                                                        <span class="help-block inline-block"> bookings available in advance with these hours </span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-2"> Hours Until </label>
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control form-inline input-xsmall inline-block" placeholder="hour">
                                                        <span class="help-block inline-block"> bookings available until these hours from now  </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-actions">
                                                <div class="row">
                                                    <div class="col-md-offset-3 col-md-9">
                                                        <button type="submit" class="btn green">
                                                            <i class="fa fa-pencil"></i> Add Day/Time Restriction </button>
                                                        <button type="button" class="btn default">Cancel</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <!-- END FORM-->
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_2">
                                <div class="portlet light bordered form-fit">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="icon-equalizer font-green-haze"></i>
                                            <span class="caption-subject font-green-haze bold uppercase">Form Sample</span>
                                            <span class="caption-helper">some info...</span>
                                        </div>
                                        <div class="actions">  </div>
                                    </div>
                                    <div class="portlet-body form">
                                        <!-- BEGIN FORM-->
                                        <form action="#" class="form-horizontal form-row-seperated">
                                            <div class="form-body">
                                                <div class="form-group">
                                                    <label class="control-label col-md-3">Day of the week</label>
                                                    <div class="col-md-9">
                                                        <select class="form-control input-large" style="height:150px;" multiple>
                                                            <option>Monday</option>
                                                            <option>Tuesday</option>
                                                            <option>Wednesday</option>
                                                            <option>Thursday</option>
                                                            <option>Friday</option>
                                                            <option>Saturday</option>
                                                            <option>Sunday</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3">Time of day</label>
                                                    <div class="col-md-9">
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control form-inline input-xsmall inline-block" placeholder="hour">
                                                            <input type="text" class="form-control form-inline input-xsmall inline-block" placeholder="minutes">
                                                            <span class="help-block inline-block"> Start Time </span>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control inline-block input-xsmall" placeholder="hour">
                                                            <input type="text" class="form-control inline-block input-xsmall" placeholder="minutes">
                                                            <span class="help-block inline-block"> End Time </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-actions">
                                                <div class="row">
                                                    <div class="col-md-offset-3 col-md-9">
                                                        <button type="submit" class="btn green">
                                                            <i class="fa fa-pencil"></i> Add Day/Time Restriction </button>
                                                        <button type="button" class="btn default">Cancel</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <!-- END FORM-->
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_3">
                                <div class="portlet light bordered form-fit">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="icon-equalizer font-green-haze"></i>
                                            <span class="caption-subject font-green-haze bold uppercase">Form Sample</span>
                                            <span class="caption-helper">some info...</span>
                                        </div>
                                        <div class="actions">  </div>
                                    </div>
                                    <div class="portlet-body form">
                                        <!-- BEGIN FORM-->
                                        <form action="#" class="form-horizontal form-row-seperated">
                                            <div class="form-body">
                                                <div class="form-group">
                                                    <label class="control-label col-md-3">Membership Open Bookings</label>
                                                    <div class="col-md-9">
                                                        <input type="text" placeholder="number of bookings" class="block-inline input-large form-control" />
                                                        <span class="help-block"> after these free bookings, the member needs to pay for any other open bookings he is doing </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-actions">
                                                <div class="row">
                                                    <div class="col-md-offset-3 col-md-9">
                                                        <button type="submit" class="btn green">
                                                            <i class="fa fa-pencil"></i> Add "Open Bookings" Restriction </button>
                                                        <button type="button" class="btn default">Cancel</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <!-- END FORM-->
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_4">
                                <div class="portlet light bordered form-fit">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="icon-equalizer font-green-haze"></i>
                                            <span class="caption-subject font-green-haze bold uppercase">Cancelation</span>
                                            <span class="caption-helper">some info...</span>
                                        </div>
                                        <div class="actions">  </div>
                                    </div>
                                    <div class="portlet-body form">
                                        <!-- BEGIN FORM-->
                                        <form action="#" class="form-horizontal form-row-seperated">
                                            <div class="form-body">
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"> Can cancel </label>
                                                    <div class="col-md-9">
                                                        <input type="text" placeholder="small" class="input-small form-control inline-block" />
                                                        <span class="help-block inline-block"> hours before </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-actions">
                                                <div class="row">
                                                    <div class="col-md-offset-3 col-md-9">
                                                        <button type="submit" class="btn green">
                                                            <i class="fa fa-pencil"></i> Add "Cancellation" restriction </button>
                                                        <button type="button" class="btn default">Cancel</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <!-- END FORM-->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END FORM-->
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-equalizer font-red-sunglo"></i>
                            <span class="caption-subject font-red-sunglo bold uppercase">Active restrictions</span>
                            <span class="caption-helper">for the selected membership plan</span>
                        </div>
                        <div class="tools">
                            <a class="collapse" href="" data-original-title="" title=""> </a>
                        </div>
                    </div>
                    <div class="portlet-body row">
                        <!-- BEGIN FORM-->
                        <div class="col-md-4">
                            <div class="note note-success">
                                <h4 class="block"> Cancellation Rule </h4>
                                <p> 72 hours before time of play </p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="note note-info">
                                <h4 class="block"> Time of Booking Rule </h4>
                                <p> Monday to Thursday - 7:00 to 23:00 </p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="note note-warning">
                                <h4 class="block"> Activities Rule </h4>
                                <p> Included : tenis, squash </p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="note note-info">
                                <h4 class="block"> Time of Booking Rule </h4>
                                <p> Friday - 7:00 to 16:00 </p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="note note-info">
                                <h4 class="block"> Time of Booking Rule </h4>
                                <p> Saturday - 9:00 to 20:00 </p>
                            </div>
                        </div>
                        <!-- END FORM-->
                    </div>
                </div>
            </div>
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

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

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
                var form1 = $('#new_cash_terminal');
                var error1 = $('.alert-danger', form1);
                var success1 = $('.alert-success', form1);

                form1.validate({
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block help-block-error', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    ignore: "",  // validate all fields including form hidden input
                    rules: {
                        terminal_name: {
                            minlength: 5,
                            required: true
                        },
                        bar_code: {
                            minlength: 5,
                            required: true
                        },
                        shop_location: {
                            minlength: 1,
                            required: true,
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
                        add_new_terminal(); // submit the form
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

        function add_new_terminal(){
            $.ajax({
                url: '{{route('admin/shops/new_cash_terminal')}}',
                type: "post",
                data: {
                    'name':         $('input[name=terminal_name]').val(),
                    'location_id':  $('select[name=shop_location]').val(),
                    'bar_code':     $('textarea[name=bar_code]').val()
                },
                success: function(data){
                    alert(data);
                }
            });
        }

        /* Start - All admin scripts */
        var UserTopAjaxSearch = function() {

            var handleDemo = function() {

                // Set the "bootstrap" theme as the default theme for all Select2
                // widgets.
                //
                // @see https://github.com/select2/select2/issues/2927
                $.fn.select2.defaults.set("theme", "bootstrap");
                $.fn.modal.Constructor.prototype.enforceFocus = function() {};

                var placeholder = "Select a State";

                $(".select2, .select2-multiple").select2({
                    placeholder: placeholder,
                    width: null
                });

                $(".select2-allow-clear").select2({
                    allowClear: true,
                    placeholder: placeholder,
                    width: null
                });

                function formatUserData(repo) {
                    if (repo.loading) return repo.text;

                    var markup = "<div class='select2-result-repository clearfix' >" +
                            "<div class='select2-result-repository__avatar'><img src='" + repo.product_image_url + "' /></div>" +
                            "<div class='select2-result-repository__meta'>" +
                            "<div class='select2-result-repository__title'>" + repo.first_name + " " + repo.middle_name + " " + repo.last_name + "</div> ";

                    if (repo.description) {
                        //markup += "<div class='select2-result-repository__description'>" + repo.description + "</div>";
                    }

                    markup += "<div class='select2-result-repository__statistics'>";
                    if (repo.email) {
                        markup += " <div class='select2-result-repository__forks'><span class='fa fa-envelope-square'></span> " + repo.email + "</div> ";
                    }
                    if (repo.phone) {
                        markup += " <div class='select2-result-repository__forks'><span class='fa fa-phone-square'></span> " + repo.phone + "</div> ";
                    }
                    markup += '<br />';

                    if (repo.city || repo.region) {
                        markup += "<div class='select2-result-repository__stargazers'><span class='fa fa-map-o'></span> " + repo.city + ", " + repo.region + "</div>";
                    }

                    markup += "</div>" +
                            "</div></div>";

                    return markup;
                }

                function formatUserDataSelection(repo) {
                    // we add product price to the form
                    //$('input[name=inventory_list_price]').val(repo.list_price);
                    //$('input[name=inventory_entry_price]').val(repo.entry_price);
                    //$('.price_currency').html(repo.currency);

                    if (repo.first_name==null && repo.first_name==null && repo.first_name==null){
                        var full_name = null;
                    }
                    else{
                        var full_name = repo.first_name + " " + repo.middle_name + " " + repo.last_name;
                        location.href = repo.user_link_details;
                    }

                    return full_name || repo.text;
                }

                $(".js-data-users-ajax").select2({
                    width: "off",
                    ajax: {
                        url: "{{ route('admin/users/ajax_get_users') }}",
                        type: "post",
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                q: params.term, // search term
                                page: params.page
                            };
                        },
                        processResults: function(data, page) {
                            // parse the results into the format expected by Select2.
                            // since we are using custom formatting functions we do not need to
                            // alter the remote JSON data
                            return {
                                results: data.items
                            };
                        },
                        cache: true
                    },
                    escapeMarkup: function(markup) {
                        return markup;
                    }, // let our custom formatter work
                    minimumInputLength: 1,
                    templateResult: formatUserData,
                    templateSelection: formatUserDataSelection
                });

                $("button[data-select2-open]").click(function() {
                    $("#" + $(this).data("select2-open")).select2("open");
                });

                $(":checkbox").on("click", function() {
                    $(this).parent().nextAll("select").prop("disabled", !this.checked);
                });

                // copy Bootstrap validation states to Select2 dropdown
                //
                // add .has-waring, .has-error, .has-succes to the Select2 dropdown
                // (was #select2-drop in Select2 v3.x, in Select2 v4 can be selected via
                // body > .select2-container) if _any_ of the opened Select2's parents
                // has one of these forementioned classes (YUCK! ;-))
                $(".select2, .select2-multiple, .select2-allow-clear, .js-data-example-ajax, .js-data-users-ajax").on("select2:open", function() {
                    if ($(this).parents("[class*='has-']").length) {
                        var classNames = $(this).parents("[class*='has-']")[0].className.split(/\s+/);

                        for (var i = 0; i < classNames.length; ++i) {
                            if (classNames[i].match("has-")) {
                                $("body > .select2-container").addClass(classNames[i]);
                            }
                        }
                    }
                });

                $(".js-btn-set-scaling-classes").on("click", function() {
                    $("#select2-multiple-input-sm, #select2-single-input-sm").next(".select2-container--bootstrap").addClass("input-sm");
                    $("#select2-multiple-input-lg, #select2-single-input-lg").next(".select2-container--bootstrap").addClass("input-lg");
                    $(this).removeClass("btn-primary btn-outline").prop("disabled", true);
                });
            }

            return {
                //main function to initiate the module
                init: function() {
                    handleDemo();
                }
            };
        }();
        jQuery(document).ready(function () {
            // initialize select2 drop downs
            UserTopAjaxSearch.init();
        });

        function booking_calendar_view_redirect(selected_date){
            var calendar_book = "{{route('bookings/location_calendar_day_view',['day'=>'##day##'])}}";
            the_link = calendar_book.replace('##day##', $('#calendar_booking_top_menu').data('datepicker').getFormattedDate('dd-mm-yyyy'));
            window.location.href = the_link;
        }

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
        /* Stop - All admin scripts */
    </script>
@endsection