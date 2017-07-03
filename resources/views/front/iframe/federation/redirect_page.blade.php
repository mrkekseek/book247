@extends('layouts.federation.main')

@section('themeGlobalStyle')
    <link href="{{ asset('assets/global/css/components-rounded.min.css') }}" rel="stylesheet" id="style_components" type="text/css" />
    <link href="{{ asset('assets/global/css/plugins.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .loader {
            border: 5px solid #eff3f8; /* Light grey */
            border-top: 5px solid #48525e; /* Blue */
            border-radius: 50%;
            display: inline-block;
            width: 30px;
            height: 30px;
            animation: spin 2s linear infinite;
            position: relative;
            transform: translateY(-50%);
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .status-text{
            width: 100%;
            text-align: center;
            font-size: 40px;
            padding: 40px 0;
            color: #48525e;
        }
    </style>

@endsection

@section('title', $status)
@section('pageBodyClass','page-container-bg-solid page-boxed login')

@section('pageContentBody')
    <div class="page-content-wrapper">
        <div class="page-content">
            <div class="container">
                <div class="page-content-inner">
                    <div class="status-text">{{ $text }} <div class="loader"></div> </div>
                </div>
            </div>
        </div>
    </div>
@endsection]


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
