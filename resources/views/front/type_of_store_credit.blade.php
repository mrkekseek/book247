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
                                                    <span class="price-sign"></span>{{ $p->store_credit_value }} {{ \App\Http\Controllers\AppSettings::get_setting_value_by_name('finance_currency') }}
                                                </h3>
                                                <!-- <p>per month</p>-->
                                                <div class="price-ribbon"> Credit Value </div>
                                            </div>
                                            <div class="price-table-content">
                                                <div class="row mobile-padding">
                                                    <div class="col-xs-11 col-xs-offset-1 text-left mobile-padding is_pack_description">
                                                        <p>{{ $p->description }}</p>
                                                    </div>
                                                </div>
                                                <div class="row mobile-padding">
                                                    <div class="col-xs-7 col-xs-offset-1 text-left mobile-padding">
                                                        <span class="col-xs-2 text-left">
                                                            <i class="icon-calculator"></i>
                                                        </span>
                                                        <span class="col-xs-10">
                                                            Price without a discount
                                                        </span>
                                                    </div>
                                                    <div class="col-xs-4 text-left mobile-padding">{{ $p->store_credit_price }} {{ \App\Http\Controllers\AppSettings::get_setting_value_by_name('finance_currency') }}</div>
                                                </div>

                                                @if ($p->store_credit_discount_fixed==0 && $p->store_credit_discount_percentage!=0)
                                                    <div class="row mobile-padding">
                                                        <div class="col-xs-7 col-xs-offset-1 text-left mobile-padding">
                                                            <span class="col-xs-2 text-left">
                                                                <i class="icon-calculator"></i>
                                                            </span>
                                                            <span class="col-xs-10">
                                                                Discount
                                                            </span>
                                                        </div>
                                                        <div class="col-xs-4 text-left mobile-padding">{{ $p->store_credit_discount_percentage }} %</div>
                                                    </div>
                                                @elseif ($p->store_credit_discount_fixed!=0)
                                                    <div class="row mobile-padding">
                                                        <div class="col-xs-7 col-xs-offset-1 text-left mobile-padding">
                                                        <span class="col-xs-2 text-left">
                                                            <i class="icon-calculator"></i>
                                                        </span>
                                                            <span class="col-xs-10">
                                                            Discount
                                                        </span>
                                                        </div>
                                                        <div class="col-xs-4 text-left mobile-padding">{{ $p->store_credit_discount_fixed }} {{ \App\Http\Controllers\AppSettings::get_setting_value_by_name('finance_currency') }}</div>
                                                    </div>
                                                @endif
                                                <div class="row mobile-padding">
                                                    <div class="col-xs-7 col-xs-offset-1 text-left mobile-padding">
                                                        <span class="col-xs-2 text-left">
                                                            <i class="icon-calculator"></i>
                                                        </span>
                                                        <span class="col-xs-10">
                                                            Paying Price
                                                        </span>
                                                    </div>
                                                    <div class="col-xs-4 text-left mobile-padding">{{ $p->final_price }} {{ \App\Http\Controllers\AppSettings::get_setting_value_by_name('finance_currency') }}</div>
                                                </div>

                                                <div class="row mobile-padding">
                                                    <div class="col-xs-7 col-xs-offset-1 text-left mobile-padding">
                                                        <span class="col-xs-2 text-left">
                                                            <i class="icon-calculator"></i>
                                                        </span>
                                                        <span class="col-xs-10">
                                                            Available until
                                                        </span>
                                                    </div>
                                                    <div class="col-xs-4 text-left mobile-padding">
                                                        @if($p->valid_to != '0000-00-00')
                                                            {{ $p->valid_to }}
                                                        @else
                                                            never expires
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="arrow-down arrow-grey"></div>
                                            <div class="price-table-footer">
                                                <a href="javascript:;" type="button" class="btn price-button sbold uppercase buy-store-credit" data-id="{{ $p->id }}" data-target="#buy-store-credit" data-toggle="modal" style="background-color: #f29407; color: #fff;" >Buy {{ $p->name }}</a>
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

    <div class="modal fade" id="buy-store-credit" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="store-credit-title"></h4>
          </div>
          <div class="modal-body" id="store-credit-content">

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Return</button>
            <button type="button" class="btn btn-primary btn-buy" data-id="0">Buy</button>
          </div>
        </div>
      </div>
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
        var store_credits = {!! json_encode($store_credit_purchases ? $store_credit_purchases->toArray() : []) !!};
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var options = { byRow: true, property: 'height', target: null, remove: false};

        $(function() {
            $('.price-table-content').matchHeight(options);
            $('.is_pack_description').matchHeight(options);
        });

        $(document).ready(function(){
            $(".buy-store-credit").click(function(){
                var store = {};
                for(var i in store_credits)
                {
                    if (store_credits[i].id == $(this).data("id"))
                    {
                        store = store_credits[i];
                    }
                }

                if (store)
                {
                    $('#store-credit-title').html(store.name);
                    var content = "";
                    content += "<ul>";

                    content += "<li>Price without a discount : " + store.store_credit_value + "</li>";
                    content += "<li>Paying Price : " + store.store_credit_discount_fixed + "</li>";
                    content += "<li>Available until : " + store.valid_to + "</li>";

                    content += "</ul>";
                    $('#store-credit-content').html(content);
                    $('.btn-buy').data("id", store.id);
                }
            });

            $(".btn-buy").click(function(){
                $.ajax({
                    url : '{{ route("front/buy_store_credit") }}',
                    method : 'post',
                    data : {
                        store_credits_id : $(this).data("id")
                    },
                    success : function(data)
                    {
                        if (data.redirect)
                        {
                            window.location.href = data.redirect;
                        }
                    }
                });
            });


        })

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
