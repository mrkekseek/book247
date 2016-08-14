@extends('layouts.main')

@section('globalMandatoryStyle')
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/simple-line-icons/simple-line-icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/uniform/css/uniform.default.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('pageLevelPlugins')
    <link href="{{ asset('assets/pages/css/invoice.min.css') }}" rel="stylesheet" type="text/css" />
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
            <div class="container bg-white bg-font-white">
                <!-- BEGIN PAGE CONTENT INNER -->
                <div class="page-content-inner margin-top-10">
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
                            <div class="col-xs-4">
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
                        <div class="row">
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