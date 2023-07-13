@component('mail::message')

{!! $emailBody !!}<br>

{!! $footer !!}<br>


<p>If you wish to unsubscribe from our mailing list, click <a href="{{ route('admin.mailing.system.campaign.unSubscribe', ['email' => $userEmail, 'campaign' => $compaignMailId]) }}">here</a>.</p>
<img src="{{ $trackingUrl }}" width="1" height="1" />

@endcomponent
