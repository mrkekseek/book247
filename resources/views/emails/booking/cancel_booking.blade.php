@extends('beautymail::templates.minty')

@section('content')

    @include('beautymail::templates.minty.contentStart')
    <tr>
        <td class="title">
            Good day {{$player->first_name}} {{$player->middle_name}} {{$player->last_name}},
        </td>
    </tr>
    <tr>
        <td width="100%" height="10"></td>
    </tr>
    <tr>
        <td class="paragraph">
            @if (!isset($booking['bookingDate']))
                The following bookings were canceled :
            @else
                Your booking for {{$booking['bookingDate']}} - {{$booking['timeStart']}} to {{$booking['timeStop']}} was canceled.<br /><br />
                Below you can check the full booking summary :
            @endif
        </td>
    </tr>
    <tr>
        <td width="100%" height="5"></td>
    </tr>
    <tr>
        <td class="paragraph">
            @if (!isset($booking['bookingDate']))
                @foreach($booking as $var)
                    - {{$var['bookingDate']}}, from {{$var['timeStart']}} to {{$var['timeStop']}}, location {{$var['location']}} - {{$var['room']}}; <br />
                @endforeach
            @else
                Booking Date : {{$booking['bookingDate']}} <br />
                Time of booking : {{$booking['timeStart']}} - {{$booking['timeStop']}} <br />
                Booking Location : {{$booking['location']}} - {{$booking['room']}} <br />
                Activity : {{$booking['category']}} <br />
                Player : {{$booking['forUserName']}} <br />
            @endif
        </td>
    </tr>
    <tr>
        <td width="100%" height="10"></td>
    </tr>
    <tr>
        <td class="paragraph">
        You can view this information in your account by accessing your <strong>Bookings > Booking Archive</strong> menu.
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