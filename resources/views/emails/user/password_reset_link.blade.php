@extends('beautymail::templates.minty')

@section('content')

    @include('beautymail::templates.minty.contentStart')
    <tr>
        <td class="title">
            Good day {{$user->first_name}} {{$user->middle_name}} {{$user->last_name}}
        </td>
    </tr>
    <tr>
        <td width="100%" height="10"></td>
    </tr>
    <tr>
        <td class="paragraph">
            This is a password reset request email sent by Booking System Agent. If you did not request a password reset, ignore this email.<br /><br />
            If this request was initiated by you, click the following link to <a href="{{ route('reset_password', ['token'=>$token]) }}" target="_blank">reset your password</a>.
            The link will be available for the next 60 minutes, after that you will need to request another password reset request.
        </td>
    </tr>
    <tr>
        <td width="100%" height="10"></td>
    </tr>
    <tr>
        <td class="paragraph">
            Once the password is reset you will get a new email with the outcome of your action, then you can login to the system with your newly created password.<br />
            <b>Remember this link is active for the next 60 minutes.</b>
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