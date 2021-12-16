@component('mail::message')
# Hey, {{$user}}

<div style="text-align: center">
<h4>{{$head}}</h4>
{!! $body !!}
</div>
@component('mail::button', ['url' => $link])
{{$button}}
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
