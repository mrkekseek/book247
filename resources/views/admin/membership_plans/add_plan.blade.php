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
        <!-- BEGIN PAGE BREADCRUMB -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="index.html">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span class="active">Form Stuff</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMB -->
        <!-- BEGIN PAGE BASE CONTENT -->
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-equalizer font-red-sunglo"></i>
                            <span class="caption-subject font-red-sunglo bold uppercase">Create new membership plan</span>
                            <span class="caption-helper">add basic details...</span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <!-- BEGIN FORM-->
                        <form action="#" id="new_membership_plan" class="form-horizontal">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-3"> Membership Name </label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" name="membership_name" placeholder="New plan name">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3 inline"> Membership Price </label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control inline-block input-small input-inline" name="membership_price">
                                                <span class="help-inline inline-block"> Per Invoice </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3 inline"> Invoicing Period </label>
                                            <div class="col-md-9">
                                                <select name="membership_period" class="form-control input-inline input inline-block">
                                                    <option value="7">once every 7 days</option>
                                                    <option value="14">once every 14 days</option>
                                                    <option value="30">one per month</option>
                                                    <option value="90">once every three months</option>
                                                    <option value="180">once every six months</option>
                                                    <option value="360">once per year</option>
                                                </select>
                                                <span class="help-inline inline-block">  </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3 inline"> Binding Period </label>
                                            <div class="col-md-9">
                                                <select name="binding_period" class="form-control input-small input-inline input inline-block">
                                                    @for ($i=1; $i<25;$i++)
                                                        <option value="{{ $i }}">{{ $i }}</option>
                                                    @endfor
                                                </select>
                                                <span class="help-inline inline-block"> months </span>
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
                                                <input type="text" class="form-control input-small inline-block" name="administration_fee_price">
                                                <span class="help-inline inline-block"> NOK </span><br />
                                                <span class="help-inline"> One time payment at the start of the membership period </span>
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
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label inline col-md-3">Long/HTML Description</label>
                                            <div class="col-md-9">
                                                <textarea name="membership_long_description" style="height:110px;" class="form-control"></textarea>
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
                            required: true,
                            number: true
                        },
                        binding_period: {
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
                            minlength: 10,
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
                        add_new_membership(); // submit the form
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

        function add_new_membership(){
            $.ajax({
                url: '{{route('admin.membership_plan.store')}}',
                type: "post",
                data: {
                    'name':                         $('input[name=membership_name]').val(),
                    'price':                        $('input[name=membership_price]').val(),
                    'plan_period':                  $('select[name=membership_period]').val(),
                    'binding_period':               $('select[name=binding_period]').val(),
                    'administration_fee_name':      $('input[name=administration_fee_name]').val(),
                    'administration_fee_amount':    $('input[name=administration_fee_price]').val(),
                    'plan_calendar_color':          $('input[name=membership_color]').val(),
                    'membership_short_description': $('textarea[name=membership_short_description]').val(),
                    'membership_long_description':  $('textarea[name=membership_long_description]').val(),
                },
                success: function(data){
                    if(data.success){
                        show_notification(data.title, data.message, 'lime', 3500, 0);
                        setTimeout(function(){
                            window.location.href = data.redirect_link;
                        },2000);
                    }
                    else{
                        show_notification(data.title, data.errors, 'ruby', 3500, 0);
                    }
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