<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>応募アンケート</title>
    {{-- ファビコン --}}
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/worksheet.css') }}{{ $versionParam }}" rel="stylesheet">
    <link href="{{ asset('css/custom/worksheet.css') }}{{ $versionParam }}" rel="stylesheet">
{{-- javascript記述 --}}
<!-- Global site tag (gtag.js) - Google Analytics -->

<script async src="https://www.googletagmanager.com/gtag/js?id=UA-178393362-1"></script>
<script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script>


<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'UA-178393362-1');
</script>
</head>

<body class="page-template-default page page-id-7">
    <header>
    </header>
    <div class="body-content">
        <div class="form_header">
            <img class="header_logo" src="{{ asset('upload/image/headLogo.png') }}" alt="">
            <div class="header_text">
                <p class="open_text">
                    ご応募いただきましてありがとうございます。<br>
                    下記のアンケートにご回答お願いいたします。<br>
                    <span class="highlight">
                        ※下記アンケートはWEB選考も兼ねています。<br>
                    </span>              
                </p>

                <p class="thanks_text d-none">
                    ご回答ありがとうございました。
                </p>
            </div>
        </div>
        <div class="form_content">
            <div class="form_frame">
                @include('worksheet.parts.choiceParts',
                    ['formChoices' => $formChoices
                    ])
                
                @include('worksheet.parts.confirm',
                    ['formChoices' => $formChoices])

                @include('worksheet.parts.schedule', 
                    ['schedules' => $schedules])

                @include('worksheet.parts.adjustSchedule',
                    [
                        'anotherSchedules'     => $anotherSchedules,
                        'startSelectTimeArray' => $startSelectTimeArray,
                        'endSelectTimeArray'   => $endSelectTimeArray
                    ])
                <div class="thanks_frame d-none">
                    <p>
                        ご回答内容を確認後、弊社採用担当よりご連絡差し上げます。<br>
                        今しばらくお待ちください。<br>
                    </p>
                </div>
            </div>
        </div>

        <div class="form_footer">
        </div>

    </div>
    <input type="hidden" name="apiToken" value="{{$apiToken}}">
    <script
    src="https://code.jquery.com/jquery-3.4.1.js"
    integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
    crossorigin="anonymous"></script>
    <script src="{{ asset('js/worksheet.js') }}{{ $versionParam }}" defer></script>
    <script src="{{ asset('js/custom/worksheet.js') }}{{ $versionParam }}" defer></script>
    <script>
        const dymline = {};
        dymline['root'] = "{{ url('/') }}";
    </script>
</body>
</html>

