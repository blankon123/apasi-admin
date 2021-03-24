<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta property="og:type" content="website" />
    <meta name="og:site_name" content="[APASI]">
    <meta property="og:image"
        content="https://images.unsplash.com/photo-1550399105-c4db5fb85c18?ixid=MXwxMjA3fDB8MHxzZWFyY2h8M3x8Ym9va3N8ZW58MHx8MHw%3D&ixlib=rb-1.2.1&w=1000&q=80" />
    <meta property="og:image:width" content="850">
    <meta property="og:image:height" content="450">
    <meta property="og:image:type" content="image/png" />
    <meta property="og:url" content="https://apasi.bpskalteng.web.id/" />
    <meta property="og:title" content="📚[APASI] Kalteng" />
    <meta property="og:description" content="Aplikasi Pembantu Diseminasi BPS Provinsi Kalimantan Tengah">
    <title>APASI | Aplikasi Pembantu Diseminasi</title>
    <link rel="stylesheet" href="{{mix('css/app.css')}}">
    <link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@5.9.55/css/materialdesignicons.min.css" rel="stylesheet" />
</head>

<body>
    <div id="app">
        <App></App>
    </div>
</body>

<script src="{{mix('js/app.js')}}"></script>

</html>