@extends('layouts.main')

@section('globalMandatoryStyle')
    <link href="//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/simple-line-icons/simple-line-icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/uniform/css/uniform.default.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('pageLevelPlugins')
    <link href="{{ asset('assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}" rel="stylesheet" type="text/css" />

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
    <div class="page-content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <!-- Begin: life time stats -->
                    <div class="portlet light portlet-fit portlet-datatable ">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-settings font-dark"></i>
                                <span class="caption-subject font-dark sbold uppercase"> invoice #{{ $invoice->invoice_number }} / {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $invoice->created_at)->format('d M Y') }}
                                    <span class="hidden-xs">| {{ $invoice->invoice_type }} </span>
                                </span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div class="tab-content">
                                <div class="tab-pane active">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12">
                                            <div class="portlet green-meadow box">
                                                <div class="portlet-title">
                                                    <div class="caption">
                                                        <i class="fa fa-cogs"></i>Payer Information
                                                    </div>
                                                </div>
                                                <div class="portlet-body payer_payee_boxes">
                                                    <div class="row static-info">
                                                        <div class="col-md-5 name">
                                                            Name:
                                                        </div>
                                                        <div class="col-md-7 value">
                                                            {{ $member->first_name.' '.$member->middle_name.' '.$member->last_name }}
                                                        </div>
                                                    </div>
                                                    <div class="row static-info">
                                                        <div class="col-md-5 name">
                                                            Email:
                                                        </div>
                                                        <div class="col-md-7 value">
                                                            {{ @$member->email }}
                                                        </div>
                                                    </div>
                                                    <div class="row static-info">
                                                        <div class="col-md-5 name">
                                                            Country:
                                                        </div>
                                                        <div class="col-md-7 value">
                                                            {{ $country }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="portlet red-sunglo box">
                                                <div class="portlet-title">
                                                    <div class="caption">
                                                        <i class="fa fa-cogs"></i>Company Information
                                                    </div>
                                                </div>
                                                <div class="portlet-body payer_payee_boxes">
                                                    <div class="row static-info">
                                                        <div class="col-md-5 name"> Name: </div>
                                                        <div class="col-md-7 value"> {{ @$customer->company_name }} </div>
                                                    </div>
                                                    <div class="row static-info">
                                                        <div class="col-md-5 name"> Address: </div>
                                                        <div class="col-md-7 value"> {{ @$customer->address1.' '.@$customer->address2 }} </div>
                                                    </div>
                                                    <div class="row static-info">
                                                        <div class="col-md-5 name"> City: </div>
                                                        <div class="col-md-7 value"> {{ @$customer->city.", ".@$customer->region }} </div>
                                                    </div>
                                                    <div class="row static-info">
                                                        <div class="col-md-5 name"> Postal Code: </div>
                                                        <div class="col-md-7 value"> {{ @$customer->postal_code }} </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12">
                                            <div class="portlet grey-cascade box">
                                                <div class="portlet-title">
                                                    <div class="caption">
                                                        <i class="fa fa-cogs"></i>Shopping Cart
                                                    </div>
                                                </div>
                                                <div class="portlet-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-hover table-bordered table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th> Product </th>
                                                                    <th> Original Price </th>
                                                                    <th> Price </th>
                                                                    <th> Quantity </th>
                                                                    <th> Tax Amount </th>
                                                                    <th> Tax Percent </th>
                                                                    <th> Discount Amount </th>
                                                                    <th> Total </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>

                                                                @foreach($invoice_items as $item)
                                                                    <tr>
                                                                        <td>
                                                                            {{ $item->item_name }}
                                                                        </td>
                                                                        <td>
                                                                           {{ number_format($item->price, 2) }}
                                                                        </td>
                                                                        <td>
                                                                            {{ number_format($item->price - (($item->discount * $item->price) / 100), 2) }}
                                                                            
                                                                        </td>
                                                                        <td>
                                                                            {{ $item->quantity }}
                                                                        </td>
                                                                        <td>
                                                                            -
                                                                        </td>
                                                                        <td>
                                                                            -
                                                                        </td>
                                                                        <td>
                                                                            {{ number_format($item->discount, 2) }} %
                                                                        </td>
                                                                        <td>
                                                                            {{ number_format($item->total_price, 2) }}
                                                                        </td>
                                                                    </tr>
                                                                @endforeach

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6"> </div>
                                        <div class="col-md-6">
                                            <div class="well">
                                                <div class="row static-info align-reverse">
                                                    <div class="col-md-8 name"> Sub - Total: </div>
                                                    <div class="col-md-3 value"> {{ number_format($sub_total, 2) }} {{\App\Http\Controllers\AppSettings::get_setting_value_by_name('finance_currency')}} </div>
                                                </div>
                                                <div class="row static-info align-reverse">
                                                    <div class="col-md-8 name"> Discount: </div>
                                                    <div class="col-md-3 value"> {{ number_format($discount, 2) }} {{\App\Http\Controllers\AppSettings::get_setting_value_by_name('finance_currency')}}</div>
                                                </div>
                                                <div class="row static-info align-reverse">
                                                    <div class="col-md-8 name"> Grand Total: </div>
                                                    <div class="col-md-3 value"> {{ number_format($grand_total, 2) }} {{\App\Http\Controllers\AppSettings::get_setting_value_by_name('finance_currency')}} </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 text-right">
                                            @if (strlen($paypal_email)>=6)
                                            <button id="pay_with_paypal" class="btn btn-primary">Pay with paypal</button>
                                            @endif
                                            
                                            @if ( ! empty($stripe_account))
                                            <button class="btn btn-success" id="pay_with_stripe">Pay with strype</button>
                                            @endif
                                            
                                            <a href="{{ route('homepage') }}" class="btn btn-success">Return Home</a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- End: life time stats -->
                </div>
            </div>
        </div>
    </div>
    @if (strlen($paypal_email)>=6)
    <form id="paypal-form" action="{{ App::environment('production')?env('PAYPAL_LINK'):env('PAYPAL_SANDBOX') }}"  target="_blank" method="post" style="display: none;">
        <input type="hidden" name="cmd" value="_cart">
        <input type="hidden" name="business" value="{{ $paypal_email }}">
        <input type="hidden" name="return" value="{{ route('payment/paypal_success') }}">
        <input type="hidden" name="cancel_url" value="{{ route('payment/paypal_cancel') }}">
        <input type="hidden" name="notify_url" value="{{ route('payment/paypal-ipn') }}">
        <input type="hidden" name="rm" value="2">
        <input type="hidden" name="upload" value="1">

        @foreach($invoice_items as $key => $item)
            <input type="hidden" name="item_name_{{ $key+1 }}" value="{{ $item->item_name }}">
            <input type="hidden" name="amount_{{ $key+1 }}" value="{{ $item->price - (($item->discount * $item->price) / 100) }}">
            <input type="hidden" name="quantity_{{ $key+1 }}" value="{{ $item->quantity }}">
        @endforeach
        <input type="hidden" name="currency_code" value="{{\App\Http\Controllers\AppSettings::get_setting_value_by_name('finance_currency')}}">

        <input type="hidden" name="first_name" value="{{ $member->first_name }}">
        <input type="hidden" name="last_name" value="{{ $member->last_name }}">
        <input type="hidden" name="email" value="{{ $member->email }}">

        <input type="hidden" name="custom" value="{{ $custom }}">
        <input type="hidden" name="invoice" value="{{ $invoice->id }}">

    </form>
    @endif
    <!-- END PAGE CONTENT INNER -->
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

    <script src="{{ asset('assets/global/scripts/datatable.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/datatables/datatables.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/scripts/jquery.matchHeight.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/jquery-notific8/jquery.notific8.min.js') }}" type="text/javascript"></script>
@endsection

@section('themeBelowGlobalScripts')
    <script src="{{ asset('assets/global/scripts/app.min.js') }}" type="text/javascript"></script>
@endsection

@section('pageCustomJScripts')
    <script type="text/javascript">
        $(document).on('click','#pay_with_paypal',function(){
            $('#paypal-form').submit();
        });

        var options = { byRow: true, property: 'height', target: null, remove: false};
        $(function() {
            $('.payer_payee_boxes').matchHeight(options);
        });

        $(function(){
            $("#pay_with_stripe").click(function(){
                $("#pay_with_stripe").attr('disabled', 'disabled');
                var id = $(this).data("id");
                $.ajax({
                    url : "{{ route('pay_with_stripe', ['id' => $invoice->id]) }}",
                    method : "post",
                    data : {
                        '_token' : '{{ csrf_token() }}'
                    },
                    success : function(data)
                    {
                        if (data.success)
                        {
                            show_notification(data.title, data.errors, 'lime', 3500, 0);
                        }
                        else
                        {
                            show_notification(data.title, data.errors, 'lime', 3500, 0); 
                        }

                        setTimeout(function(){
                            location.href =  data.redirect;
                        }, 2000);
                    }
                })
            })
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

@section('pageBelowLevelScripts')
    <script src="{{ asset('assets/pages/scripts/ui-notific8.min.js') }}" type="text/javascript"></script>
@endsection

@section('themeBelowLayoutScripts')
    <script src="{{ asset('assets/layouts/layout3/scripts/layout.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/layouts/global/scripts/quick-sidebar.min.js') }}" type="text/javascript"></script>
@endsection
