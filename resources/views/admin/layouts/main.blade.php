<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8" />
    <title>@yield('title')</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta content="@yield('description')" name="description" />
    <meta content="@yield('author')" name="author" />
    <base href="{{Config::get('constants.globalWebsite.url')}}" />
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/simple-line-icons/simple-line-icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/uniform/css/uniform.default.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
@yield('pageLevelPlugins')
    <link href="{{ asset('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN THEME GLOBAL STYLES -->
@yield('themeGlobalStyle')
    <!-- END THEME GLOBAL STYLES -->
    <!-- BEGIN THEME LAYOUT STYLES -->
@yield('themeLayoutStyle')
    <!-- END THEME LAYOUT STYLES -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" /> </head>
    <link href="{{ asset('assets/apps/css/admin_custom.css') }}" rel="stylesheet" type="text/css" />
<!-- END HEAD -->

<body class=" @yield('pageBodyClass') ">
<!-- BEGIN HEADER -->
<div class="page-header navbar navbar-fixed-top">
    <!-- BEGIN HEADER INNER -->
    @include('admin.layouts.main_head_nav')
    <!-- END HEADER INNER -->
</div>
<!-- END HEADER -->
<!-- BEGIN HEADER & CONTENT DIVIDER -->
<div class="clearfix"> </div>
<!-- END HEADER & CONTENT DIVIDER -->
<!-- BEGIN CONTAINER -->
<div class="page-container">
    <!-- BEGIN SIDEBAR -->
    <div class="page-sidebar-wrapper">
        <!-- BEGIN SIDEBAR -->
        <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
        <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
        @include('admin.layouts.main_sidebar_left')
        <!-- END SIDEBAR -->
    </div>
    <!-- END SIDEBAR -->
    <!-- BEGIN CONTENT -->
    <div class="page-content-wrapper">
        <!-- BEGIN CONTENT BODY -->
        @yield('pageContentBody')
        <!-- END CONTENT BODY -->
    </div>
    <!-- END CONTENT -->
    <!-- BEGIN QUICK SIDEBAR -->
    @if (isset($right_sidebar_on))
    @include('admin.layouts.main_quick_sidebar')
    @endif
    <!-- END QUICK SIDEBAR -->
</div>
<!-- END CONTAINER -->
<!-- BEGIN FOOTER -->
@include('admin.layouts.footer')
<!-- END FOOTER -->

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
<script src="{{ asset('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>
@yield('pageBelowLevelPlugins')
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN THEME GLOBAL SCRIPTS -->
<script src="{{ asset('assets/global/scripts/app.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/scripts/admin-scripts.js') }}" type="text/javascript"></script>
<!-- END THEME GLOBAL SCRIPTS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
@yield('pageBelowLevelScripts')
<script src="{{ asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<!-- BEGIN THEME LAYOUT SCRIPTS -->
@yield('themeBelowLayoutScripts')
<!-- END THEME LAYOUT SCRIPTS -->
<!-- BEGIN THEME LAYOUT SCRIPTS -->
@yield('pageCustomJScripts')
<script src="{{ asset('assets/global/scripts/backend_app.js') }}" type="text/javascript"></script>
<!-- END THEME LAYOUT SCRIPTS -->

<script type="text/javascript">
    /* Start - All admin scripts */
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

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
                        "<div class='select2-result-repository__avatar'><img src='" + repo.avatar_image + "' /></div>" +
                        "<div class='select2-result-repository__meta'>" +
                        "<div class='select2-result-repository__title'>" + repo.first_name + " " + repo.middle_name + " " + repo.last_name + "</div> ";

                if (repo.description) {
                    //markup += "<div class='select2-result-repository__description'>" + repo.description + "</div>";
                }

                markup += "<div class='select2-result-repository__statistics'>";
                if (repo.membership){
                    markup += " <div class='select2-result-repository__forks'><span class='fa fa-phone-square'></span> " + repo.membership + "</div> ";
                }
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
                minimumInputLength: 2,
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
        if (!$('select').hasClass('js-data-members-ajax')) {
            UserTopAjaxSearch.init();
        }
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

</body>
</html>