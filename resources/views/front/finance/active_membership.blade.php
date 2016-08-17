@extends('layouts.main')

@section('pageLevelPlugins')
    <link href="{{ asset('assets/global/plugins/jquery-notific8/jquery.notific8.min.css') }}" rel="stylesheet" type="text/css" />
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
                @if (!$membership_plan)
                    <div class="page-content-inner">
                        <div class="portlet light margin-top-10">
                            <div class="portlet-body">
                                <div class="note note-warning" style="margin-bottom:5px;">
                                    <h4 class="block">You have no active membership</h4>
                                    <p>
                                        With a membership you can do more and pay less. Select one that is fitted to your needs and start playing sports.<br /><br />
                                        Use the bottom button to view all available memberships and select the one that is right for you.
                                    </p>
                                    <div class="form">
                                        <div class="form-actions right" style="border-top:none; padding:10px 0 0;">
                                            <a href="{{ route('front/membership_types') }}" class="btn purple-seance"> Membership Types </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="row">
                        <div class="col-md-12">
                            <div class="portlet light bordered">
                                <div class="portlet-body form">
                                    <!-- BEGIN FORM-->
                                    <form action="#" id="new_membership_plan" class="form-horizontal">
                                        <div class="form-body">
                                            <div class="form-group" style="margin-bottom: 0px;">
                                                <label class="control-label col-md-4"> Active Membership </label>
                                                <div class="col-md-8">
                                                    <select name="membership_period" class="form-control input-inline input-large  inline-block">
                                                        @if ($membership_plan->id!=1)
                                                            <option> {{$membership_plan->membership_name}} </option>
                                                        @else
                                                            <option> No active Membership Plan </option>
                                                        @endif
                                                    </select>
                                                    @if ($membership_plan->id!=1 && \Carbon\Carbon::now()->gt(\Carbon\Carbon::createFromFormat('Y-m-d',$membership_plan->day_stop)))
                                                        <a href="#cancel_confirm_box" class="btn red-soft input" data-toggle="modal" style="min-width:190px;">
                                                            <i class="fa fa-pencil"></i> Cancel Current Plan</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <!-- END FORM-->
                                </div>
                            </div>
                        </div>

                        @if ($membership_plan->id!=1)
                            <div class="col-md-12">
                                <div class="portlet light bordered">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="icon-equalizer font-blue-steel"></i>
                                            <span class="caption-subject font-blue-steel bold uppercase"> Membership Plan Details </span>
                                            <span class="caption-helper">for the signed membership plan</span>
                                        </div>
                                    </div>
                                    <div class="portlet-body row">
                                        <!-- BEGIN FORM-->
                                        @if($restrictions)
                                            <div class="col-md-4">
                                                <div class="note note-info font-grey-mint" style="min-height:105px; margin:0 0 10px; padding:5px 20px 10px 10px;">
                                                    <p> Price </p>
                                                    <h4 class="block" style="margin-bottom:0px; font-size:32px;"> <b>{{ $plan_details['price'] }} NOK</b> </h4>
                                                </div>
                                            </div>
                                            @if ($plan_details['discount']!=0)
                                            <div class="col-md-4">
                                                <div class="note note-info font-grey-mint" style="min-height:105px; margin:0 0 10px; padding:5px 20px 10px 10px;">
                                                    <p> Discount </p>
                                                    <h4 class="block" style="margin-bottom:0px; font-size:32px;"> <b>{{ $plan_details['discount'] }}</b> </h4>
                                                </div>
                                            </div>
                                            @endif
                                            <div class="col-md-4">
                                                <div class="note note-info font-grey-mint" style="min-height:105px; margin:0 0 10px; padding:5px 20px 10px 10px;">
                                                    <p> Invoice Period </p>
                                                    <h4 class="block" style="margin-bottom:0px; font-size:24px;"> <b>{{ $plan_details['invoice_period'] }}</b> </h4>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="note note-info font-grey-mint" style="min-height:105px; margin:0 0 10px; padding:5px 20px 10px 10px;">
                                                    <p> Signed On </p>
                                                    <h4 class="block" style="margin-bottom:0px; font-size:32px;"> {{ $plan_details['day_start'] }} </h4>
                                                </div>
                                            </div>
                                            <div class="{{$plan_details['discount']==0?'col-md-8':'col-md-4'}}">
                                                <div class="note note-warning" style="min-height:105px; margin:0 0 10px; padding:5px 20px 10px 10px;">
                                                    <p> Current invoice period </p>
                                                    <h4 class="block" style="margin-bottom:0px; font-size:18px;"> 22 Mar 2014 - 22 June 2014 </h4>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="note note-danger bg-red-flamingo bg-font-red-flamingo color-white" style="min-height:105px; margin:0 0 10px; padding:5px 20px 10px 10px;">
                                                    <p> Invoice Status </p>
                                                    <h4 class="block" style="margin-bottom:0px; font-size:24px;"> Not Paid </h4>
                                                </div>
                                            </div>
                                        @endif
                                        <!-- END FORM-->
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (sizeof($restrictions))
                            <div class="col-md-12">
                                <div class="portlet light bordered">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="icon-equalizer font-blue-steel"></i>
                                            <span class="caption-subject font-blue-steel bold uppercase"> Active Attributes & Restrictions </span>
                                            <span class="caption-helper">for the signed membership plan</span>
                                        </div>
                                    </div>
                                    <div class="portlet-body row">
                                        <!-- BEGIN FORM-->
                                        @if($restrictions)
                                            @foreach ($restrictions as $restriction)
                                                <div class="col-md-4">
                                                    <div class="note {{ $restriction['color'] }}" style="min-height:120px; margin:0 0 15px; padding:5px 20px 10px 10px;">
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
                        @endif
                    </div>

                    @if ($membership_plan->id!=1 && \Carbon\Carbon::now()->gt(\Carbon\Carbon::createFromFormat('Y-m-d',$membership_plan->day_stop)))
                    <div class="modal fade bs-modal-sm" id="cancel_confirm_box" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                    <h4 class="modal-title">Do you want to cancel the current membership plan?</h4>
                                </div>
                                <div class="modal-body margin-top-10 margin-bottom-10"> By clicking "Cancel Membership" the member will be switched to the default membership plan (the "No Membership Plan").
                                    After the cancellation you can apply another membership plan to this user from the same page.</div>
                                <div class="modal-footer">
                                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">No, Go Back</button>
                                    <button type="button" class="btn green" onclick="javascript:cancel_membership();">Yes, Cancel</button>
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    @endif
                @endif
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

        @if (@$membership_plan && \Carbon\Carbon::now()->gt(\Carbon\Carbon::createFromFormat('Y-m-d',$membership_plan->day_stop)))
        function cancel_membership(){
            var userID = '{{$user->id}}';

            $.ajax({
                url: '{{route('admin/membership_plans/cancel_member_plan')}}',
                type: "post",
                data: {
                    'member_id':userID
                },
                success: function(data){
                    if (data.success) {
                        $('#cancel_confirm_box').modal('hide');
                        show_notification(data.title, data.message, 'lime', 3500, 0);

                        setTimeout(function(){
                            location.reload();
                        },2000);
                    }
                    else{
                        show_notification(data.title, data.errors, 'tangerine', 3500, 0);
                    }
                }
            });
        }
        @endif

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