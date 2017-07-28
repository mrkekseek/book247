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
                                        <div class="success-box-mark">
                                            <img src="{{ asset('assets/success/img/success-mark.png') }}">
                                        </div>
                                        <div class="success-box-text">
                                            <span>PAYMENT APPROVED</span><br/>
                                            <span>Thank you for purchasing a plan.</span>
                                            <span>Rankedin has received your payment.</span><br/>
                                            <span>If you are not automatically redirected, click the button bellow.</span>
                                        </div>
                                    </div>
                                    <a class="success-box-redirect" href="{{ $link }}">Done</a>
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
            var link = '{{ $link }}';
            if (link.length > 0){
                setTimeout(function(){
                    window.location.replace(link);
                },5000)
            }
        });
    </script>
@endsection
