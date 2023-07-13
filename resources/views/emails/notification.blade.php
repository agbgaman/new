@component('mail::message')
# You Received a {{$notification['type']}} Notification

Hello GTS Dash Team,

Notification : <br>
        {{$notification['subject']}}<br>
Notification message: <br>
{!! $notification['message'] !!}  <br>
Thanks,<br>
{{ config('app.name') }}
@endcomponent
