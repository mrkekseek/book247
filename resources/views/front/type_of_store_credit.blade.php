@extends('layouts.main')

@section('pageLevelPlugins')
    <link href="{{ asset('assets/global/plugins/jquery-notific8/jquery.notific8.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/pages/css/pricing.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('themeGlobalStyle')
    <link href="{{ asset('assets/global/css/components-rounded.min.css') }}" rel="stylesheet" id="style_components" type="text/css" />
    <link href="{{ asset('assets/global/css/plugins.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('themeLayoutStyle')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('title', 'Main Page')
@section('pageBodyClass','page-container-bg-solid page-boxed')

@section('pageContentBody')
    <div class="page-content-wrapper">
        <!-- BEGIN CONTENT BODY -->
        <!-- BEGIN PAGE CONTENT BODY -->
        <div class="page-content">
            <div class="container">
                <!-- BEGIN PAGE CONTENT INNER -->
                <div class="page-content-inner">

                   
                    <div class="portlet light portlet-fit ">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-share font-grey-mint"></i>
                                <span class="caption-subject font-grey-mint bold uppercase"> Store Credits </span>
                            </div>
                            <div class="actions">
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div class="pricing-content-1">
                                <div class="row">
                                    @foreach($store_credit_purchases as $p)
                                    <div class="col-md-6 form-group">
                                        <div class="price-column-container border-active">
                                            <div class="price-table-head" style="background-color: #48a838;">
                                                <h3 class="no-margin">{{ $p->name }}</h3>
                                            </div>
                                            <div class="arrow-down" style="border-top-color: #48a838;"></div>
                                            <div class="price-table-pricing">
                                                <h3>
                                                    <span class="price-sign"></span>{{ number_format($p->store_credit_price, 2) }}
                                                </h3>
                                                <!-- <p>per month</p>-->
                                                <div class="price-ribbon">{{ $p->status }}</div>
                                            </div>
                                            <div class="price-table-content">
                                                <div class="row mobile-padding">
                                                    <div class="col-xs-11 col-xs-offset-1 text-left mobile-padding">
                                                        <p>{{ $p->description }}</p>
                                                    </div>
                                                </div>
                                                <div class="row mobile-padding">
                                                    <div class="col-xs-6 col-xs-offset-1 text-left mobile-padding">
                                                        <span class="col-xs-2 text-left">
                                                            <i class="icon-calculator"></i>
                                                        </span>
                                                        <span class="col-xs-10">
                                                            Cost of store credit
                                                        </span>
                                                    </div>
                                                    <div class="col-xs-5 text-left mobile-padding">{{ $p->store_credit_value }}</div>
                                                </div>
                                                <div class="row mobile-padding">
                                                    <div class="col-xs-6 col-xs-offset-1 text-left mobile-padding">
                                                        <span class="col-xs-2 text-left">
                                                            <i class="icon-calculator"></i>
                                                        </span>
                                                        <span class="col-xs-10">
                                                            The cost of the package without a discount
                                                        </span>
                                                    </div>
                                                    <div class="col-xs-5 text-left mobile-padding">{{ $p->store_credit_price }}</div>
                                                </div>
                                                <div class="row mobile-padding">
                                                    <div class="col-xs-6 col-xs-offset-1 text-left mobile-padding">
                                                        <span class="col-xs-2 text-left">
                                                            <i class="icon-calculator"></i>
                                                        </span>
                                                        <span class="col-xs-10">
                                                            Fixed price discount
                                                        </span>
                                                    </div>
                                                    <div class="col-xs-5 text-left mobile-padding">{{ $p->store_credit_discount_fixed }}</div>
                                                </div>
                                                <div class="row mobile-padding">
                                                    <div class="col-xs-6 col-xs-offset-1 text-left mobile-padding">
                                                        <span class="col-xs-2 text-left">
                                                            <i class="icon-calculator"></i>
                                                        </span>
                                                        <span class="col-xs-10">
                                                            Date the package begins to operate
                                                        </span>
                                                    </div>
                                                    <div class="col-xs-5 text-left mobile-padding">{{ $p->valid_from }}</div>
                                                </div>
                                                <div class="row mobile-padding">
                                                    <div class="col-xs-6 col-xs-offset-1 text-left mobile-padding">
                                                        <span class="col-xs-2 text-left">
                                                            <i class="icon-calculator"></i>
                                                        </span>
                                                        <span class="col-xs-10">
                                                            The date the package expires
                                                        </span>
                                                    </div>
                                                    <div class="col-xs-5 text-left mobile-padding">{{ $p->valid_to }}</div>
                                                </div>


                                            </div>
                                            <div class="arrow-down arrow-grey"></div>
                                            <div class="price-table-footer">
                                                <a href="{{ url('/') }}" type="button" class="btn price-button sbold uppercase" style="background-color: #f29407; color: #fff;">Sign Up</a>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                    @if ( ! count($store_credit_purchases))
                                        <div class="col-md-6 form-group">
                                            <h3>Store Credit Purchases not yes added</h3>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                   
                    
                </div>
                <!-- END PAGE CONTENT INNER -->
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