<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport"   content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>面接確定</title>
    {{-- ファビコン --}}
    <!-- Styles -->
    <link href="{{ asset('css/app.css')          }}" rel="stylesheet">
    <link href="{{ asset('css/cpStatusPage.css') }}" rel="stylesheet">
</head>

<body class="page-template-default">
    <div class="body-content">
        <div class="page_header">
            <img class="header_logo" src="{{ asset('upload/image/headLogo.png') }}" alt="">
        </div>

        <div class="msg_frame">
            <p>
                下記日程で面接が確定しました。<br>
                ご確認のほどよろしくお願い致します。<br><br>

                担当者　：{{ $userName }}<br><br>
                
                面接日時：{{ $decideSchedule }}～<br>

                @if ($interviewWay === 'web')
                    面接方法：オンライン（Google Meet）<br><br>
                    面接URL：<a href="{{ $interviewUrl }}" target="_blank">Google Meet</a> よりご参加ください。<br>
                    ※開始時間になりましたら、上記リンクをクリックしてご参加ください。<br>
                @else
                    面接方法：対面<br>
                @endif
                <br>
               
                応募者<br>
                <span>名前　　　　　：{{ $csName }}</span><br>
                <span>フリガナ　　　：{{ $nameKana }}</span><br>
                <span>電話番号　　　：<a href="tel:{{ $csTel }}">{{ $csTel }}</a></span><br>
                <span>メールアドレス：{{ $csMail }}</span><br><br>

                <div class="cs_answer">
                    <span>アンケート回答内容</span><br>
                    {!! $getAnswerData !!}
                </div>
            </p>
        </div>
    </div>
</body>
</html>


