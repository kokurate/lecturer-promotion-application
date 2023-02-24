@component('mail::message')
<h3>
    {{ $data['title'] }}
</h3>

<p>
    {{ $data['open'] }}
</p>

<p>
    {{ $data['close'] }}
</p>

@component('mail::button', ['url' => $data['url']])
Kunjungi Website
@endcomponent

Terima Kasih,<br>
{{ config('app.name') }}
@endcomponent
