@extends('vendor.dompdf.layout')

@section('content')
    {!! $html !!}
@endsection

@push("style")
    <style>
        body, html {
            font-family: arial, sans-serif;
            font-size: 14px;
            color: #333;
        }
        
        .wrap {
            display: table;
            width: 100%;
        }
          
        .wrap .left, .wrap .right {
            display: table-cell;
            width: 50%;
        }

        .invoice-logo {
            display: inline-block;
            text-align: center;
        }
    </style>
@endpush