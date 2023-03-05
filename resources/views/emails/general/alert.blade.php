@component('mail::message')
<span style="font-size:12pt; margin-bottom:50px ;color: #204593;font-family: 'Montserrat', sans-serif;"><strong>{{strtoupper($subject)}}</strong></span><br>
@component('mail::panel') 
{!! $msg !!}
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent