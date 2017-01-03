@extends('beautymail::templates.minty')

@section('content')

    @include('beautymail::templates.minty.contentStart')
    <tr>
        <td class="title">
            Welcome {{$user->first_name}} {{$user->middle_name}} {{$user->last_name}}
        </td>
    </tr>
    <tr>
        <td width="100%" height="10"></td>
    </tr>
    <tr>
        <td class="paragraph">
            SQF/Book247 is the official booking system for Drammen & Økern. Within approximately 30 days time Book 247 will be used for all SquashFitness locations.
            Please log in and "add your friends" (playing partners). By doing this you and your playing partners can book on behalf of each other and according to the booking rules.
            This will make the booking procedure faster for you, your partners and our receptionists.
        </td>
    </tr>
    <tr>
        <td class="paragraph">
            You registered to the Booking System platform with the following credentials, credentials that you can use to <a href="{{route('homepage')}}" target="_blank">login here</a> :
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
            Your phone : <strong>{{$personal_details->mobile_number}}</strong> that is registered in the system can be used to send you alerts when you create a booking or when a booking is created on your behalf.
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