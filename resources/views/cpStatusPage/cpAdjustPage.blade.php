<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport"   content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>応募者日程確認</title>
    {{-- ファビコン --}}
    <!-- Styles -->
    <link  href="{{ asset('css/app.css')                 }}" rel="stylesheet">
    <link  href="{{ asset('css/cpStatusPage.css')        }}" rel="stylesheet">
    <link  href="{{ asset('css/custom/cpStatusPage.css') }}" rel="stylesheet">
</head>

@inject('ShapDateService', 'App\Services\ShapDate\ShapDateService')
<body class="page-template-default">
    <div class="body-content">
        <div class="page_header">
            <img class="header_logo" src="{{ asset('upload/image/headLogo.png') }}" alt="">
        </div>

        <div class="msg_frame">
            <p>
                応募者がアンケートに回答しました。<br>
                回答情報をご確認後、面接日程のご調整をお願い致します。<br>
                <br>
                <span>名前　　　　　 : {{$csName}}  </span><br>
                <span>フリガナ　　　 : {{$nameKana}}</span><br>
                <span>電話番号　　　 : <a href="tel:{{$csTel}}">{{$csTel}}</a></span><br>
                <span>メールアドレス : {{$csMail}}  </span><br>
                <br>
                <div class="cs_answer">
                    <span>アンケート回答内容</span><br>
                    {!! $getAnswerData !!}
                </div>
                <br>
                <span style="color: red;">※{{$csName}} 様の面接希望日でご調整が難しい場合、お電話で日程調整後、日時設定して頂けますと幸いです。</span><br>
                <br>
                <div class="interview_set_frame">

                    <h5 class="text-danger"><b>面接設定</b></h5>

                    <div class="radio-group">
                        <label>面接担当者</label>
                        @foreach ($userData as $id => $name)
                            <label class="radio-item">
                                <input type="radio" name="interview_user" value="{{ $id }}">
                                {{ $name }}
                            </label>
                        @endforeach
                    </div>
        
                    <!-- <div class="radio-group">
                        <input type="radio" name="interview_setting_method" id="phone_call" value="phone_call">
                        <label for ="phone_call">電話対応</label>
                    </div>
                    <div class="radio-group">
                        <input type="radio" name="interview_setting_method" id="schedule_date" value="schedule_date">
                        <label for ="schedule_date">日時設定</label>
                    </div> -->
                      
                    <div class="interview_date_set">

                        <div class="interview_date_frame">
                            <b class="form_title">面接の日付</b><br>
                            <input type="date" name="interview_date" id="calendar">
                        </div>
                        <div class="interview_time_frame">
                            <b class="form_title">開始時刻</b><br>
                            <select name="interview_time">
                                <option value="-">開始時刻</option>
                                @for($indexTime = 9; $indexTime <= 19; $indexTime++)
                                    @for($minute = 0; $minute <= 30; $minute += 30)
                                        <option value="{{ $ShapDateService->shapTime($indexTime, $minute) }}">
                                            {{ $ShapDateService->shapTime($indexTime, $minute) }}
                                        </option>
                                        @if($indexTime == 19 && $minute == 0)
                                            @break
                                        @endif
                                    @endfor
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div class="btn_frame">
                        <button name="interview_set_btn">上記で回答</button>
                    </div>
                </div>
            </p>
        </div>
    </div>
    <script type="text/javascript">
        const dymline     = {};
        dymline['root'  ] = "{{ url('/api') }}";
        dymline['domain'] = "{{ url(''    ) }}";
    </script>
    <script src="{{ asset('js/app.js') }}{{ $versionParam }}" defer></script>
    <script src="{{ asset('js/auth/adminCommon.js') }}{{ $versionParam }}" defer></script>
    <script src="{{ asset('js/auth/companyStatus/companyStatus.js') }}{{ $versionParam }}" defer></script>
</html>


