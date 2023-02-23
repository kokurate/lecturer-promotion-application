@component('mail::message')

<h2>
    {{ $data['title'] }}
</h2>
<br>

<p>
    {{ $data['content'] }}
</p>


@component('mail::button', ['url' => $data['url']])
Reset Your Password
@endcomponent

<p style="color:red;">
    {{ $data['expire'] }}
</p>

Terima Kasih,<br>
{{ config('app.name') }}
@endcomponent
