@extends('vendor.dompdf.layout')

@section('content')
    {!! $html !!}
@endsection

@push("style")
    <style>
        body, html {
            font-family: "Open Sans",sans-serif,arial;
            font-size: 14px;
            color: #333;
        }
        
        .wrap {
            width: 100%;
        }

        .wrap:after {
            content: "";
            display: block;
            clear: both;
        }

        .wrap .left {
            float: left;
            text-align: left;
        }

        .wrap .right {
            float: right;
            width: 35%
        }

        .wrap .left {
            float: left;
            width: 65%;
        }

        .invoice-logo {
            display: inline-block;
            text-align: center;
        }

        ul {
            padding: 0;
            margin: 0;
            list-style: none;
        }

        .invoice .invoice-logo p {
            padding: 5px 0;
            font-size: 26px;
            line-height: 28px;
            text-align: right;
        }

        .invoice .invoice-logo p span {
            display: block;
            font-size: 14px;
        }

        hr {
            border: 0;
            border-top: 1px solid #eee;
            border-bottom: 0;
        }

        table {
            margin: 30px 0;
            border-collapse: collapse;
        }

        h3 {
            font-size: 24px;
            font-weight: 300;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        tr td {
            padding: 8px;
            line-height: 1.42857;
            vertical-align: top;
            border-top: 1px solid #e7ecf1;
        }
        
        .list-unstyled.amounts {
            text-align: right;
        }

        .well {
            border: 0;
            padding: 20px;
            box-shadow: none!important;
            min-height: 20px;
            padding: 19px;
            margin-bottom: 20px;
            background-color: #f1f4f7;
            border: 1px solid #e3e3e3;
            border-radius: 4px;
            -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
            box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
        }

        a {
            text-shadow: none;
            color: #337ab7;
            text-decoration: none;
        }

        address {
            font-style: normal;
            line-height: 1.42857;
            margin-bottom: 20px;
        }

        [hidden] {
            display: none;
        }

    </style>
@endpush