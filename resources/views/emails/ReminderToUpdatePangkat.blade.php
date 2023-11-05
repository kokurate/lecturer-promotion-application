@component('mail::message')
<h3>
    {{ $data['title'] }}
</h3>

<p>
    {{ $data['sub_title'] }}
</p>

<p>
    {{ $data['latest'] }}
</p
>
<p>{{ $data['nama'] }}</p>
<p>{{ $data['pangkat'] }}</p>
<p>{{ $data['jabatan'] }}</p>
<p>{{ $data['golongan'] }}</p>

@component('mail::button', ['url' => $data['url']])
Login
@endcomponent

Terima Kasih,<br>
{{ config('app.name') }}
@endcomponent
