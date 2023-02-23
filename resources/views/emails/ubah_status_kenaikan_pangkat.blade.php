@component('mail::message')
<h4>
    {{ $data['title'] }}
</h4>
<p>
    {{  $data['p1']  }}
</p>
<br>
<p>
    {{  $data['p2']  }}
</p>
<br>
<p style="color:red">
    {{ $data['credentials'] }}
</p>


@component('mail::button', ['url' => $data['url']])
Login
@endcomponent

Terima Kasih,<br>
{{ config('app.name') }}
@endcomponent
