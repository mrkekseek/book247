@extends('admin.layouts.main')

@section('pageLevelPlugins')
    <link href="{{ asset('assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('themeGlobalStyle')
    <link href="{{ asset('assets/global/css/components-rounded.min.css') }}" rel="stylesheet" id="style_components" type="text/css" />
    <link href="{{ asset('assets/global/css/plugins.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/jquery-notific8/jquery.notific8.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/pages/css/invoice.min.css') }}" rel="stylesheet" type="text/css" />
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
        <!-- BEGIN PAGE BASE CONTENT -->
        <div class="row">
            <div class="col-md-12">
                <!-- Begin: life time stats -->
                <div class="portlet light portlet-fit portlet-datatable bordered">
                    <div class="portlet-body">
                        <div class="invoice">
                            <div class="row invoice-logo">
                                <div class="col-xs-6 invoice-logo-space">
                                    <img src="{{ asset('assets/global/img/sqf-logo.png') }}" class="img-responsive" alt="" /> </div>
                                <div class="col-xs-6">
                                    <p> #{{ $invoice->invoice_number }} / {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $invoice->created_at)->format('d M Y') }}
                                        <span class="muted"> {{ $invoice->invoice_type }} </span>
                                    </p>
                                </div>
                            </div>
                            <hr/>
                            <div class="row">
                                <div class="col-xs-4">
                                    <h3>Client:</h3>
                                    <ul class="list-unstyled">
                                        <li> {{ $member['full_name'] }} </li>
                                        <li> {{ $member['email_address'] }} </li>
                                        <li> {{ $member['country'] }} </li>
                                    </ul>
                                </div>
                                <div class="col-xs-4  hidden-print">
                                </div>
                                <div class="col-xs-4 invoice-payment">
                                    <h3>Payment Details:</h3>
                                    <ul class="list-unstyled">
                                        <li>
                                            <strong>V.A.T Reg #:</strong> 542554(DEMO)78 </li>
                                        <li>
                                            <strong>Account Name:</strong> FoodMaster Ltd </li>
                                        <li>
                                            <strong>SWIFT code:</strong> 45454DEMO545DEMO </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="row" style="min-height:300px;">
                                <div class="col-xs-12">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                        <tr>
                                            <th> # </th>
                                            <th> Item </th>
                                            <th class="hidden-xs"> Quantity </th>
                                            <th class="hidden-xs"> Unit Cost </th>
                                            <th class="hidden-xs"> Discount </th>
                                            <th class="hidden-xs"> VAT </th>
                                            <th> Total </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($invoice_items as $key=>$item)
                                            <tr>
                                                <td> {{$key+1}} </td>
                                                <td> {{ $item->item_name }} </td>
                                                <td class="hidden-xs"> {{ $item->quantity }} </td>
                                                <td class="hidden-xs"> {{ $item->price }} </td>
                                                <td class="hidden-xs"> {{ $item->discount }}% </td>
                                                <td class="hidden-xs"> {{ $item->vat }}% </td>
                                                <td> {{ $item->total_price }} </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-4">
                                    <div class="well">
                                        <address>
                                            <strong>Rud Squash AS</strong>
                                            <br/> Postbox 60
                                            <br/> N-1309 Rud
                                            <br/>
                                            <abbr title="Phone">P:</abbr> (234) 145-1810 </address>
                                        <address>
                                            <strong>{{ $member['full_name'] }}</strong>
                                            <br/>
                                            <a href="mailto:{{$member['email_address']}}"> {{ $member['email_address'] }} </a>
                                        </address>
                                    </div>
                                </div>
                                <div class="col-xs-8 invoice-block">
                                    <ul class="list-unstyled amounts">
                                        <li>
                                            <strong>Sub - Total amount:</strong> {{$sub_total}} </li>
                                        <li>
                                            <strong>Discount:</strong> {{$discount}} </li>
                                        @foreach($vat as $key_vat=>$item_vat)
                                        <li>
                                            <strong>VAT {{$key_vat}}%:</strong> {{$item_vat==0?'----':$item_vat}} </li>
                                        @endforeach
                                        <li>
                                            <strong>Grand Total:</strong> {{$grand_total}} </li>
                                    </ul>
                                    <br/>
                                    <a class="btn btn-lg blue hidden-print margin-bottom-5" onclick="javascript:window.print();"> Print
                                        <i class="fa fa-print"></i>
                                    </a>
                                    @if ($invoice->status=='pending')
                                    <a class="btn btn-lg green hidden-print margin-bottom-5"> Make Payment
                                        <i class="fa fa-check"></i>
                                    </a>
                                    @elseif ($invoice->status=='processing')
                                        <a class="btn btn-lg green hidden-print margin-bottom-5"> Payment is processing
                                            <i class="fa fa-check"></i>
                                        </a>
                                    @elseif ($invoice->status=='cancelled')
                                        <a class="btn btn-lg green hidden-print margin-bottom-5"> Invoice Cancelled
                                            <i class="fa fa-check"></i>
                                        </a>
                                    @elseif ($invoice->status=='declined')
                                        <a class="btn btn-lg green hidden-print margin-bottom-5"> Payment is declined
                                            <i class="fa fa-check"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End: life time stats -->
            </div>
        </div>
        <!-- END PAGE BASE CONTENT -->
    </div>
@endsection

@section('pageBelowLevelPlugins')
    <script src="{{ asset('assets/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
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

        var FormValidation = function () {

            var handleValidation1 = function() {
                var form1 = $('#all_order_lines');
                var error1 = $('.alert-danger', form1);
                var success1 = $('.alert-success', form1);

                form1.validate({
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block help-block-error', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    ignore: "",  // validate all fields including form hidden input
                    rules: {
                        shop_name: {
                            minlength: 3,
                            required: true
                        },
                        shop_email: {
                            email: true,
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
                        form.submit(); // submit the form
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