<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>APASI | Aplikasi Pembantu Diseminasi</title>

    <link rel="stylesheet" href="{{asset('css/app.css')}}">
</head>

<body>
    <div id="app">
        <App></App>
    </div>
</body>

<script src="{{asset('js/app.js')}}"></script>

</html>