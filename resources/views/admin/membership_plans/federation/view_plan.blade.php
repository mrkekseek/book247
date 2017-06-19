@extends('admin.layouts.federation.main')

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
            @if ($membership_plan)
                <div class="col-md-12">
                    <div class="portlet light bordered">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-equalizer font-purple-studio"></i>
                                <span class="caption-subject font-purple-studio bold uppercase"> Membership plan details</span>
                                <span class="caption-helper">update details here...</span>
                            </div>
                            <div class="tools">
                                <a class="collapse" href="" data-original-title="" title=""> </a>
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
                                                    <p class="form-control-static form-control border-blue-steel"> {{$membership_plan->name}} </p>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3 inline"> Membership Price </label>
                                                <div class="col-md-9">
                                                    <p class="form-control-static form-control border-blue-steel input-small">{{$membership_plan->price[0]->price}}</p>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3 inline"> Invoicing Period </label>
                                                <div class="col-md-9">
                                                    <p class="form-control-static form-control border-blue-steel input-large">
                                                        @if ($membership_plan->plan_period==7)
                                                            once every 7 days
                                                        @elseif($membership_plan->plan_period==14)
                                                            once every 14 days
                                                        @elseif($membership_plan->plan_period==30)
                                                            one per month
                                                        @elseif($membership_plan->plan_period==90)
                                                            once every three months
                                                        @elseif($membership_plan->plan_period==180)
                                                            once every six months
                                                        @else
                                                            once per year
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3 inline"> Binding Period </label>
                                                <div class="col-md-9">
                                                @if ($membership_plan->binding_period==0)
                                                    <p class="form-control-static form-control border-blue-steel input-large">
                                                    no period
                                                    </p>
                                                @elseif ($membership_plan->binding_period==1)
                                                    <p class="form-control-static form-control border-blue-steel input-large">
                                                    1  month
                                                    </p>
                                                @else
                                                    <p class="form-control-static form-control border-blue-steel input-large">
                                                    {{$membership_plan->binding_period}}  months
                                                    </p>
                                                @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3 inline"> Sign out period </label>
                                                <div class="col-md-9">
                                                @if ($membership_plan->sign_out_period==0)
                                                    <p class="form-control-static form-control border-blue-steel input-large">
                                                    no period
                                                    </p>
                                                @elseif ($membership_plan->sign_out_period==1)
                                                    <p class="form-control-static form-control border-blue-steel input-large">
                                                    1  month
                                                    </p>
                                                @else
                                                    <p class="form-control-static form-control border-blue-steel input-large">
                                                    {{$membership_plan->sign_out_period}}  months
                                                    </p>
                                                @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3"> Administration Fee Name </label>
                                                <div class="col-md-9">
                                                    <p class="form-control-static form-control border-blue-steel">{{ $membership_plan->administration_fee_name }}</p>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3"> Administration Fee Price </label>
                                                <div class="col-md-9">
                                                    <p class="form-control-static form-control border-blue-steel input-small">{{$membership_plan->administration_fee_amount}}</p>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Plan Color</label>
                                                <div class="col-md-9">
                                                    <p class="form-control-static form-control border-blue-steel input-small" style="background-color: {{$membership_plan->plan_calendar_color}};"> &nbsp; </p>
                                                    <span class="help-inline  block-inline"> Color to be displayed in calendar booking </span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3 inline">Short Description</label>
                                                <div class="col-md-9">
                                                    <p class="form-control-static form-control border-blue-steel" style="height:auto;">{{$membership_plan->short_description}}</p>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3 inline">Long/HTML Description</label>
                                                <div class="col-md-9">
                                                    <p class="form-control-static form-control border-blue-steel" style="height:auto; min-height:90px;">{{$membership_plan->description}}</p>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3 inline"> Membership Status </label>
                                                <div class="col-md-9">
                                                    <p class="form-control-static form-control border-blue-steel">
                                                        @if ($membership_plan->status=='active')
                                                            Active - in use
                                                        @elseif($membership_plan->status=='pending')
                                                            Pending - Needs verification
                                                        @elseif($membership_plan->status=='suspended')
                                                            Suspended - active, not in use
                                                        @elseif($membership_plan->status=='deleted')
                                                            Deleted - old membership plan
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--/row-->
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
                                <i class="icon-equalizer font-blue-steel"></i>
                                <span class="caption-subject font-blue-steel bold uppercase"> Active Attributes & Restrictions </span>
                                <span class="caption-helper">for the selected membership plan</span>
                            </div>
                            <div class="tools">
                                <a class="collapse" href="" data-original-title="" title=""> </a>
                            </div>
                        </div>
                        <div class="portlet-body row">
                            <!-- BEGIN FORM-->
                            @if($restrictions)
                                @foreach ($restrictions as $restriction)
                                    <div class="col-md-4">
                                        <div class="note {{ $restriction['color'] }} membership_rules">
                                            <h4 class="block"> {{ $restriction['title'] }} Rule </h4>
                                            <p> {!! $restriction['description'] !!} </p>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="note note-warning" style="margin-left:15px; margin-right:15px;">
                                    <h4 class="block">You have no attributes added to this plan</h4>
                                    <p> Please use the "Add Membership Attributes" to customize and configure the membership plan so you create the perfect plan for your business. </p>
                                </div>
                                @endif
                                        <!-- END FORM-->
                        </div>
                    </div>
                </div>
            @else
                <div class="col-md-12">
                    <div class="portlet light ">
                        <div class="portlet-body">
                            <div class="note note-warning">
                                <h4 class="block">An error occured</h4>
                                <p> The price plan that you are searching for could not be found. Go back to the page you came here and try refreshing it, then access the link again.<br /><br /> Thank you!</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
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
    <script src="{{ asset('assets/global/scripts/jquery.matchHeight.js') }}" type="text/javascript"></script>
@endsection

@section('themeBelowLayoutScripts')
    <script src="{{ asset('assets/layouts/layout4/scripts/layout.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/layouts/layout4/scripts/demo.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/layouts/global/scripts/quick-sidebar.min.js') }}" type="text/javascript"></script>
@endsection

@section('pageCustomJScripts')
    <script type="text/javascript">
        var options = { byRow: true, property: 'height', target: null, remove: false};
        $(function() {
            $('.membership_rules').matchHeight(options);
        });
    </script>
@endsection