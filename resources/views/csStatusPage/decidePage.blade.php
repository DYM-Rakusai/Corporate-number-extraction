<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>面接確定</title>
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
                {{$csName}} 様<br><br>
                面接のおしらせです！<br>
                下記の日程でご予約を承っております。<br><br>

                面接日時：{{ $decideSchedule }}<br>

                @if ($interviewWay === 'face')
                    面接場所： 
                    <a href="https://www.google.com/maps/search/?api=1&query=面接会場" target="_blank" rel="noopener noreferrer">
                        面接会場
                    </a><br>
                    ※駐車場のご用意はございませんので、あらかじめご了承ください。<br>
                @else
                    面接URL：<a href="{{ $interviewUrl }}" target="_blank">Google Meet</a>からご参加お願いします。<br>
                    ※開始時間になりましたら、上記のリンクよりご参加ください。<br>
                @endif

                <br>
                面接日時のご変更やキャンセル、その他ご不明な点がございましたら、<a href="tel:08049345993">080-4934-5993</a>までご連絡頂けますと幸いです。<br>
                当日お会いできるのを楽しみにしております！
            </p>
        </div>
    </div>
</body>
</html>


