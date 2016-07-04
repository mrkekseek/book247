@extends('beautymail::templates.minty')

@section('content')

    @include('beautymail::templates.minty.contentStart')
    <tr>
        <td class="title">
            Good day {{$user->first_name}} {{$user->middle_name}} {{$user->last_name}},
        </td>
    </tr>
    <tr>
        <td width="100%" height="10"></td>
    </tr>
    <tr>
        <td class="paragraph">
            @if ($booking->made_by_user == $booking->made_for_user)

            @else

            @endif
        </td>
    </tr>
    <tr>
        <td width="100%" height="5"></td>
    </tr>
    <tr>
        <td class="paragraph">
            Username : {{$user->username}} <br />
            Password : {{$raw_password}}
        </td>
    </tr>
    <tr>
        <td width="100%" height="10"></td>
    </tr>
    <tr>
        <td class="paragraph">
            Your phone : <strong>{{$personal_details->mobile_number}}</strong> that is registered in the system will be used to send you alerts when you create a booking or when a booking is created on your behalf.
        </td>
    </tr>
    <tr>
        <td width="100%" height="25"></td>
    </tr>
    <tr>
        <td>
            @include('beautymail::templates.minty.button', ['text' => 'Booking System Homepage', 'link' => route('homepage')])
        </td>
    </tr>
    <tr>
        <td width="100%" height="25"></td>
    </tr>
    @include('beautymail::templates.minty.contentEnd')

@stop