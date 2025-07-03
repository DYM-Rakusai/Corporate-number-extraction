<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>WEB選考結果</title>
    {{-- ファビコン --}}
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/csStatusPage.css') }}" rel="stylesheet">
</head>

<body class="page-template-default">
    <div class="body-content">
        <div class="page_header">
            <img class="header_logo" src="{{ asset('upload/image/headLogo.png') }}" alt="">
        </div>
        <div class="msg_frame">
            <p>
                誠に残念ながら今回はご期待に添えない結果となりました。<br>
                せっかくご応募いただいたにもかかわらず、誠に申し訳ございません。<br>
                貴殿の今後益々のご活躍をお祈り申し上げます。
            </p>
        </div>
    </div>

</body>
</html>

