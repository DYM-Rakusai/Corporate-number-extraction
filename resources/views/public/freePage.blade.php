<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{$pageTitle}}</title>
    {{-- ファビコン --}}
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/freePage.css') }}" rel="stylesheet">
</head>

<body class="page-template-default">
    <div class="body-content">
        <div class="msg_frame">
            <p>
                {!! $pageContent !!}
            </p>
        </div>
    </div>

</body>
</html>

