@component('mail::layout')

@slot ('header')
@component('mail::header', ['url' => config('app.url')])
|📚APASI| <br>
Aplikasi Pembantu Diseminasi

@endcomponent
@endslot
{{$maildata['judul']}}

{{$maildata['konten']}}

{{-- Subcopy --}}
@slot('subcopy')
@component('mail::subcopy')
<!-- subcopy -->
{{$maildata['subcopy']}}
@endcomponent
@endslot

{{-- Footer --}}
@slot ('footer')
@component('mail::footer')
Aplikai Pembantu Diseminasi - DLS BPS Provinsi Kalimantan Tengah<br>
APASI © 2021
@endcomponent
@endslot
@endcomponent