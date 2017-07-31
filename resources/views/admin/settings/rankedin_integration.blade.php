@extends('admin.layouts.main')


@section('pageLevelPlugins')
    <link href="{{ asset('assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/global/plugins/bootstrap-markdown/css/bootstrap-markdown.min.css') }}">
    <link href="{{ asset('assets/global/plugins/bootstrap-summernote/summernote.css') }}" rel="stylesheet" type="text/css" />
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

@section('title', 'Back-end users - All list')
@section('pageBodyClass','page-container-bg-solid page-header-fixed page-sidebar-closed-hide-logo')

@section('pageContentBody')
    <div class="page-content fix_padding_top_0">
        <!-- BEGIN PAGE BASE CONTENT -->
        <div class="row">
            <div class="col-md-12">
                <div>
                    <div class="portlet light bordered">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-social-dribbble"></i>
                                <span class="caption-subject bold uppercase">{!!$text_parts['title']!!}</span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <blockquote>
                                <p> <b>What is <span class="font-blue">Rankedin?</span></b><br /><br />
                                    Rankedin is a ranking app available in the PlayStore, iTunes app store and in any browser. It enables anybody, on any device,
                                    to create any kind of event that counts towards rankings. <br />
                                    RankedIn is a platform for players and sport clubs.<br />
                                    <br />
                                    Clubs can be a mixture of everything, from federations to companies, from friend groups to associations and so on.<br />
                                    <br />
                                    RankedIn offers many features that will stimulate maximum activity when it comes to your sport or your club.<br />
                                    <br />
                                    From now on you can easily find new opponents and organize any kind of sporting events without hassle. If it sounds too good to be true,
                                    try it out for yourself and see how easy, but advanced our system really is. It is 100% free of charge!<br />
                                </p>
                                <small>read more on RankedIn.com
                                    <a href="https://rankedin.com/Content/ShowNews/83?clubid=&color=" target="_blank"><cite title="Source Title"> about page </cite></a>
                                </small>
                            </blockquote>

                            <div class="note note-info">
                                <h4>Below is your very own <b>account key</b> that will be needed on RankedIn.com to make the integration of the two systems :</h4>
                                <h2 class="block text-center">{{$application_key}}</h2>
                            </div>

                            <blockquote>
                                <p> <b>How to integrate <span class="font-blue">Book247</span> with <span class="font-blue">Rankedin?</span></b><br /><br />
                                    As an organization admin you need to go to a organization page. Open settings and it's the last collapsible panel.
                                    You will see something similar to the image below<br /><br />
                                    <img src="{{\App\Http\Controllers\AppSettings::get_setting_value_by_name('globalWebsite_url')}}/assets/global/img/rankedin_integration_with_book247_panel_001.png">
                                </p>
                                <small>read more on RankedIn.com
                                    <a href="https://rankedin.com/Content/ShowNews/83?clubid=&color=" target="_blank"><cite title="Source Title"> about page </cite></a>
                                </small>
                            </blockquote>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END PAGE BASE CONTENT -->
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
    <script src="{{ asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/jquery-validation/js/additional-methods.min.js') }}" type="text/javascript"></script>
@endsection

@section('themeBelowGlobalScripts')
    <script src="{{ asset('assets/global/scripts/app.min.js') }}" type="text/javascript"></script>
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

    </script>
@endsection