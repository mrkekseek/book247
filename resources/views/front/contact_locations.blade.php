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
                                <span class="caption-subject font-grey-mint bold uppercase"> Squash medlemskap </span>
                            </div>
                            <div class="actions">
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div class="pricing-content-1">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="price-column-container border-active">
                                            <div class="price-table-head bg-green">
                                                <h3 class="no-margin">Dag/helg: før kl 16:00 man-tors og hele fre-søn</h3>
                                            </div>
                                            <div class="arrow-down border-top-green"></div>
                                            <div class="price-table-pricing">
                                                <h3>
                                                    <span class="price-sign"></span>149</h3>
                                                <p>per month</p>
                                                <div class="price-ribbon">Popular</div>
                                            </div>
                                            <div class="price-table-content">
                                                <div class="row mobile-padding">
                                                    <div class="col-xs-2 text-right mobile-padding">
                                                        <i class="icon-user-follow"></i>
                                                    </div>
                                                    <div class="col-xs-9 text-left mobile-padding">20 Members</div>
                                                </div>
                                                <div class="row mobile-padding">
                                                    <div class="col-xs-2 text-right mobile-padding">
                                                        <i class="icon-drawer"></i>
                                                    </div>
                                                    <div class="col-xs-9 text-left mobile-padding">500GB Storage</div>
                                                </div>
                                                <div class="row mobile-padding">
                                                    <div class="col-xs-2 text-right mobile-padding">
                                                        <i class="icon-cloud-download"></i>
                                                    </div>
                                                    <div class="col-xs-9 text-left mobile-padding">Cloud Syncing</div>
                                                </div>
                                                <div class="row mobile-padding">
                                                    <div class="col-xs-2 text-right mobile-padding">
                                                        <i class="icon-refresh"></i>
                                                    </div>
                                                    <div class="col-xs-9 text-left mobile-padding">Daily Backups</div>
                                                </div>
                                            </div>
                                            <div class="arrow-down arrow-grey"></div>
                                            <div class="price-table-footer">
                                                <button type="button" class="btn green price-button sbold uppercase">Sign Up</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="price-column-container border-active">
                                            <div class="price-table-head bg-purple">
                                                <h3 class="no-margin">Fullt medlemskap i hele åpningstiden</h3>
                                            </div>
                                            <div class="arrow-down border-top-purple"></div>
                                            <div class="price-table-pricing">
                                                <h3>
                                                    <span class="price-sign"></span>299</h3>
                                                <p>per month</p>
                                            </div>
                                            <div class="price-table-content">
                                                <div class="row mobile-padding">
                                                    <div class="col-xs-2 text-right mobile-padding">
                                                        <i class="icon-users"></i>
                                                    </div>
                                                    <div class="col-xs-9 text-left mobile-padding">100 Members</div>
                                                </div>
                                                <div class="row mobile-padding">
                                                    <div class="col-xs-2 text-right mobile-padding">
                                                        <i class="icon-drawer"></i>
                                                    </div>
                                                    <div class="col-xs-9 text-left mobile-padding">2TB Storage</div>
                                                </div>
                                                <div class="row mobile-padding">
                                                    <div class="col-xs-2 text-right mobile-padding">
                                                        <i class="icon-cloud-download"></i>
                                                    </div>
                                                    <div class="col-xs-9 text-left mobile-padding">Cloud Syncing</div>
                                                </div>
                                                <div class="row mobile-padding">
                                                    <div class="col-xs-2 text-right mobile-padding">
                                                        <i class="icon-refresh"></i>
                                                    </div>
                                                    <div class="col-xs-9 text-left mobile-padding">Weekly Backups</div>
                                                </div>
                                            </div>
                                            <div class="arrow-down arrow-grey"></div>
                                            <div class="price-table-footer">
                                                <button type="button" class="btn grey-salsa btn-outline price-button sbold uppercase">Sign Up</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="portlet light portlet-fit ">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-share font-grey-mint"></i>
                                <span class="caption-subject font-grey-mint bold uppercase"> Fitness medlemskap </span>
                            </div>
                            <div class="actions">
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div class="pricing-content-1">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="price-column-container border-active">
                                            <div class="price-table-head bg-blue">
                                                <h3 class="no-margin">Dag/helg: før kl 16:00 man-tors og hele fre-søn</h3>
                                            </div>
                                            <div class="arrow-down border-top-blue"></div>
                                            <div class="price-table-pricing">
                                                <h3>
                                                    <span class="price-sign"></span>149</h3>
                                                <p>per month</p>
                                                <div class="price-ribbon">Popular</div>
                                            </div>
                                            <div class="price-table-content">
                                                <div class="row mobile-padding">
                                                    <div class="col-xs-2 text-right mobile-padding">
                                                        <i class="icon-user-follow"></i>
                                                    </div>
                                                    <div class="col-xs-9 text-left mobile-padding">20 Members</div>
                                                </div>
                                                <div class="row mobile-padding">
                                                    <div class="col-xs-2 text-right mobile-padding">
                                                        <i class="icon-drawer"></i>
                                                    </div>
                                                    <div class="col-xs-9 text-left mobile-padding">500GB Storage</div>
                                                </div>
                                                <div class="row mobile-padding">
                                                    <div class="col-xs-2 text-right mobile-padding">
                                                        <i class="icon-cloud-download"></i>
                                                    </div>
                                                    <div class="col-xs-9 text-left mobile-padding">Cloud Syncing</div>
                                                </div>
                                                <div class="row mobile-padding">
                                                    <div class="col-xs-2 text-right mobile-padding">
                                                        <i class="icon-refresh"></i>
                                                    </div>
                                                    <div class="col-xs-9 text-left mobile-padding">Daily Backups</div>
                                                </div>
                                            </div>
                                            <div class="arrow-down arrow-grey"></div>
                                            <div class="price-table-footer">
                                                <button type="button" class="btn blue price-button sbold uppercase">Sign Up</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="price-column-container border-active">
                                            <div class="price-table-head bg-yellow bg-font-yellow">
                                                <h3 class="no-margin">Fullt medlemskap i hele åpningstiden</h3>
                                            </div>
                                            <div class="arrow-down border-top-yellow"></div>
                                            <div class="price-table-pricing">
                                                <h3>
                                                    <span class="price-sign"></span>299</h3>
                                                <p>per month</p>
                                            </div>
                                            <div class="price-table-content">
                                                <div class="row mobile-padding">
                                                    <div class="col-xs-2 text-right mobile-padding">
                                                        <i class="icon-users"></i>
                                                    </div>
                                                    <div class="col-xs-9 text-left mobile-padding">100 Members</div>
                                                </div>
                                                <div class="row mobile-padding">
                                                    <div class="col-xs-2 text-right mobile-padding">
                                                        <i class="icon-drawer"></i>
                                                    </div>
                                                    <div class="col-xs-9 text-left mobile-padding">2TB Storage</div>
                                                </div>
                                                <div class="row mobile-padding">
                                                    <div class="col-xs-2 text-right mobile-padding">
                                                        <i class="icon-cloud-download"></i>
                                                    </div>
                                                    <div class="col-xs-9 text-left mobile-padding">Cloud Syncing</div>
                                                </div>
                                                <div class="row mobile-padding">
                                                    <div class="col-xs-2 text-right mobile-padding">
                                                        <i class="icon-refresh"></i>
                                                    </div>
                                                    <div class="col-xs-9 text-left mobile-padding">Weekly Backups</div>
                                                </div>
                                            </div>
                                            <div class="arrow-down arrow-grey"></div>
                                            <div class="price-table-footer">
                                                <button type="button" class="btn grey-salsa btn-outline price-button sbold uppercase">Sign Up</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="portlet light portlet-fit ">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-share font-grey-mint"></i>
                                <span class="caption-subject font-grey-mint bold uppercase"> Kombi medlemskap squash og fitness </span>
                            </div>
                            <div class="actions">
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div class="pricing-content-1">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="price-column-container border-active">
                                            <div class="price-table-head bg-green">
                                                <h3 class="no-margin">Dag/helg: før kl 16:00 man-tors og hele fre-søn</h3>
                                            </div>
                                            <div class="arrow-down border-top-green"></div>
                                            <div class="price-table-pricing">
                                                <h3>
                                                    <span class="price-sign"></span>249</h3>
                                                <p>per month</p>
                                                <div class="price-ribbon">Popular</div>
                                            </div>
                                            <div class="price-table-content">
                                                <div class="row mobile-padding">
                                                    <div class="col-xs-2 text-right mobile-padding">
                                                        <i class="icon-user-follow"></i>
                                                    </div>
                                                    <div class="col-xs-9 text-left mobile-padding">20 Members</div>
                                                </div>
                                                <div class="row mobile-padding">
                                                    <div class="col-xs-2 text-right mobile-padding">
                                                        <i class="icon-drawer"></i>
                                                    </div>
                                                    <div class="col-xs-9 text-left mobile-padding">500GB Storage</div>
                                                </div>
                                                <div class="row mobile-padding">
                                                    <div class="col-xs-2 text-right mobile-padding">
                                                        <i class="icon-cloud-download"></i>
                                                    </div>
                                                    <div class="col-xs-9 text-left mobile-padding">Cloud Syncing</div>
                                                </div>
                                                <div class="row mobile-padding">
                                                    <div class="col-xs-2 text-right mobile-padding">
                                                        <i class="icon-refresh"></i>
                                                    </div>
                                                    <div class="col-xs-9 text-left mobile-padding">Daily Backups</div>
                                                </div>
                                            </div>
                                            <div class="arrow-down arrow-grey"></div>
                                            <div class="price-table-footer">
                                                <button type="button" class="btn green price-button sbold uppercase">Sign Up</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="price-column-container border-active">
                                            <div class="price-table-head bg-purple">
                                                <h3 class="no-margin">Fullt medlemskap i hele åpningstiden</h3>
                                            </div>
                                            <div class="arrow-down border-top-purple"></div>
                                            <div class="price-table-pricing">
                                                <h3>
                                                    <span class="price-sign"></span>399</h3>
                                                <p>per month</p>
                                            </div>
                                            <div class="price-table-content">
                                                <div class="row mobile-padding">
                                                    <div class="col-xs-2 text-right mobile-padding">
                                                        <i class="icon-users"></i>
                                                    </div>
                                                    <div class="col-xs-9 text-left mobile-padding">100 Members</div>
                                                </div>
                                                <div class="row mobile-padding">
                                                    <div class="col-xs-2 text-right mobile-padding">
                                                        <i class="icon-drawer"></i>
                                                    </div>
                                                    <div class="col-xs-9 text-left mobile-padding">2TB Storage</div>
                                                </div>
                                                <div class="row mobile-padding">
                                                    <div class="col-xs-2 text-right mobile-padding">
                                                        <i class="icon-cloud-download"></i>
                                                    </div>
                                                    <div class="col-xs-9 text-left mobile-padding">Cloud Syncing</div>
                                                </div>
                                                <div class="row mobile-padding">
                                                    <div class="col-xs-2 text-right mobile-padding">
                                                        <i class="icon-refresh"></i>
                                                    </div>
                                                    <div class="col-xs-9 text-left mobile-padding">Weekly Backups</div>
                                                </div>
                                            </div>
                                            <div class="arrow-down arrow-grey"></div>
                                            <div class="price-table-footer">
                                                <button type="button" class="btn grey-salsa btn-outline price-button sbold uppercase">Sign Up</button>
                                            </div>
                                        </div>
                                    </div>
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