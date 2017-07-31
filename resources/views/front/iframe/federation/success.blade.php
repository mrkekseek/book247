@extends('layouts.federation.main')

@section('themeGlobalStyle')
    <link href="{{ asset('assets/global/css/components-rounded.min.css') }}" rel="stylesheet" id="style_components" type="text/css" />
    <link href="{{ asset('assets/global/css/plugins.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/success/css/style.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('title', $status)

@section('pageBodyClass','page-container-bg-solid page-boxed login')

@section('pageContentBody')
    <div class="page-content-wrapper">
        <div class="page-content">
            <div class="success-wrap">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3 col-sm-12 col-xs-12">
                            <div class="success-box-container">
                                <div class="success-box">
                                    <div class="success-box-top-wrap">
                                        @if ($status=="Unknown")
                                            <div class="success-box-text">
                                                <span> UNKNOWN PAYMENT STATUS </span><br/>
                                                <span>You arrived on this page directly, without making any payment</span>
                                                <span>If that was the case, the page will redirect you to homepage in a few seconds.</span><br/>
                                                <span>If you are not automatically redirected, click the button bellow.</span>
                                            </div>
                                        @elseif($status=="Success")
                                            <div class="success-box-mark">
                                                <img src="{{ asset('assets/success/img/success-mark.png') }}">
                                            </div>
                                            <div class="success-box-text">
                                                <span>PAYMENT APPROVED</span><br/>
                                                <span>Thank you for your purchase.</span>
                                                <span>We received your payment and the services bought are active and can be used.</span><br/>
                                                <span>If you are not automatically redirected, click the button bellow.</span>
                                            </div>
                                        @else
                                            <div class="success-box-mark">
                                                <img src="{{ asset('assets/success/img/cancel-mark.png') }}">
                                            </div>
                                            <div class="success-box-text">
                                                <span>PAYMENT DECLINED</span><br/>
                                                <span>Your payment has been declined by your card issuer, please use a different card to complete the payment.</span><br/>
                                                <span>Click the button bellow to make a new payment, the page will automatically refresh in 1 minute.</span>
                                            </div>
                                        @endif
                                    </div>
                                    <a class="success-box-redirect" href="{{ $link }}">
                                    @if($status == 'Canceled')
                                        New payment
                                    @else
                                        Done
                                    @endif
                                    </a>
                                    <div class="success-box-powered-by">
                                        <span>Powered by:</span>
                                        <img src="{{ asset('assets/success/img/book247.png') }}">
                                        <img src="{{ asset('assets/success/img/rankedin.png') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('pageCustomJScripts')
    <script type="text/javascript">
        $(document).ready(function(){
            var duration = '{{ $status == 'Canceled' ? 60000 : 5000}}';
            var link = '{{ $link }}';
            if (link.length > 0){
                setTimeout(function(){
                    window.location.replace(link);
                },Number(duration));
            }
        });
    </script>
@endsection
