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
                                <span class="caption-subject font-grey-mint bold uppercase"> Squash plans </span>
                            </div>
                            <div class="actions">
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div class="pricing-content-1">
                                <div class="row">
                                    @foreach($plans as $p)
                                    <div class="col-md-6 form-group">
                                        <div class="price-column-container border-active">
                                            <div class="price-table-head" style="background-color: {{ $p->plan_calendar_color }}">
                                                <h3 class="no-margin">{{ $p->name }}</h3>
                                            </div>
                                            <div class="arrow-down" style="border-top-color: {{ $p->plan_calendar_color }}"></div>
                                            <div class="price-table-pricing">
                                                <h3>
                                                    <span class="price-sign"></span>{{ $p->price->price }}
                                                </h3>
                                                <!-- <p>per month</p>-->
                                                <!-- <div class="price-ribbon">{{ $p->status }}</div> -->
                                            </div>
                                            <div class="price-table-content">
                                                <div class="row mobile-padding">
                                                    <div class="col-xs-11 col-xs-offset-1 text-left mobile-padding">
                                                        <p>{{ $p->short_description }}</p>
                                                    </div>
                                                </div>
                                                <div class="row mobile-padding">
                                                    <div class="col-xs-6 col-xs-offset-1 text-left mobile-padding">
                                                        <span class="col-xs-2 text-left">
                                                            <i class="icon-calendar"></i>
                                                        </span>
                                                        <span class="col-xs-10">
                                                            Plan period
                                                        </span>
                                                    </div>
                                                    <div class="col-xs-5 text-left mobile-padding">
                                                        @if ($p->plan_period==7 || $p->plan_period==14)
                                                            {{ $p->plan_period }} Days
                                                        @elseif( in_array($p->plan_period,[30, 90, 180]))
                                                            {{ $p->plan_period/30 }} Month{{$p->plan_period==30?'':'s'}}
                                                        @elseif( in_array($p->plan_period, 360) )
                                                            one year
                                                        @else
                                                            lifetime
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="row mobile-padding">
                                                    <div class="col-xs-6 col-xs-offset-1 text-left mobile-padding">
                                                        <span class="col-xs-2 text-left">
                                                            <i class="icon-calendar"></i>
                                                        </span>
                                                        <span class="col-xs-10">
                                                            Binding Period
                                                        </span>
                                                    </div>
                                                     <div class="col-xs-5 text-left mobile-padding">{{ $p->binding_period }} Month{{$p->binding_period==1?'':'s'}}</div>
                                                </div>
                                                 <div class="row mobile-padding">
                                                    <div class="col-xs-6 col-xs-offset-1 text-left mobile-padding">
                                                        <span class="col-xs-2 text-left">
                                                            <i class="icon-calendar"></i>
                                                        </span>
                                                        <span class="col-xs-10">
                                                            SignOut period
                                                        </span>
                                                    </div>
                                                    <div class="col-xs-5 text-left mobile-padding">{{ $p->sign_out_period }} Month{{$p->sign_out_period==1?'':'s'}}</div>
                                                </div>




                                            </div>
                                            <div class="arrow-down arrow-grey"></div>
                                            <div class="price-table-footer">
                                                <a href="javascript:void(0);" type="button" class="btn price-button sbold uppercase btn-signup" data-id="{{ $p->id }}" style="background-color: {{ $p->plan_calendar_color }}; color: #fff">Sign Up</a>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
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
    <!-- BEGIN MODAL -->
    <div class="modal fade" id="signup_membership" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="membership_name"></h4>
          </div>
          <div class="modal-body">
            <div class="row">
                <div class="col-md-12 form-group">
                    <ul>
                        <li>Plan period : <span id="membership_plan_period"></span></li>
                        <li>Binding Period : <span id="membership_binding_period"></span></li>
                        <li>SignOut period : <span id="membership_signout_period"></span></li>
                    </ul>
                    <textarea readonly name="" id="membership_desc" cols="30" rows="10" class="form-control"></textarea>
                </div>
                <div class="col-md-12">
                    <label for="terms_and_condition">I agree with the terms and conditions</label>
                    <input type="checkbox" name="terms_and_condition" />
                </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="btn-pay" data-id="0" disabled="disabled">Sign up and pay</button>
          </div>
        </div>
      </div>
    </div>
    <!-- END MODAL -->
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
    <script src="{{ asset('assets/global/scripts/jquery.matchHeight.js') }}" type="text/javascript"></script>
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
        var memberships_plans = {!! json_encode($plans ? $plans->toArray() : []) !!};
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var options = { byRow: true, property: 'height', target: null, remove: false};
        $(function() {
            $('.price-table-content').matchHeight(options);
        });

        $(document).ready(function(){
            $(".btn-signup").click(function(){
                var plan = false;
                for(var i in memberships_plans)
                {
                    plan = (memberships_plans[i].id == $(this).data("id")) ? memberships_plans[i] : plan;
                }

                if (plan)
                {
                    var plan_period = "";

                    if (plan.plan_period == 7 || plan.plan_period == 14)
                    {
                        plan_period = plan.plan_period + ' Days';
                    }
                    else if([30, 90, 180].indexOf(plan.plan_period) + 1)
                    {
                        plan_period = (plan.plan_period / 30) + 'Month' + (plan.plan_period == 30 ? '' : 's');
                    }
                    else if(plan.plan_period(360))
                    {
                        plan_period = 'one year';
                    }
                    else
                    {
                        plan_period = 'lifetime';
                    }

                    $("#membership_name").text(plan.name);
                    $("#membership_plan_period").text(plan_period);
                    $("#membership_binding_period").text(plan.binding_period + " Month" + (plan.binding_period != 1 ? "s" : ""));
                    $("#membership_signout_period").text(plan.sign_out_period + " Month" + (plan.binding_period != 1 ? "s" : ""));
                    $("#membership_desc").val(plan.short_description);
                    $("#btn-pay").data("id", plan.id);
                    $("#signup_membership").modal("show");
                }
            });

            $("input[name=terms_and_condition]").change(function(){
                if ( ! $(this).prop("checked"))
                {
                    $("#btn-pay").attr("disabled", "disabled");
                }
                else
                {
                    $("#btn-pay").removeAttr("disabled");
                }
            })

            $("#btn-pay").click(function() {
                var plan_id = $(this).data("id"),
                    terms_and_condition = $("input[name=terms_and_condition]").prop("checked");

                if ( ! terms_and_condition)
                {
                    show_notification("Singup membership", "Please check terms and condition", "tangerine", 3500, 0);
                    return false;
                }

                $.ajax({
                    method : "post",
                    url : "{{ route("front/singup_membership_plan") }}",
                    data : {
                        "membership_id" : plan_id,
                        "terms_and_condition" : terms_and_condition
                    },
                    success : function(response){

                        if (response.success)
                        {
                            $('#signup_membership').modal('hide');
                            show_notification(response.title, response.errors, 'lime', 3500, 0);

                            setTimeout(function(){
                                window.location.href = response.redirect;
                            }, 1500);
                        }
                        else
                        {
                            show_notification(response.title, response.errors, 'tangerine', 3500, 0);
                        }
                    }
                });

            });
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
