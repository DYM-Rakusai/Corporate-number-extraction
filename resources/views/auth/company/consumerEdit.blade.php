
@extends('layouts.sidemenu')

@section('content')

<script>
</script>

<div class="m-0 mt-5">
    <div class="">
        <div>
            <u>
                <a class=""
                    href="{{ url('/cs-detail-page') }}?id={{$atsCsId}}">この応募者の詳細ページへ戻る</a>
            </u>
        </div>


        @include('auth.company.consumer.csEdit',
            [
                'getCsData' => $getCsData,
                'cskeys' => $cskeys,
                'authority' => $authority,
                'decideSchedule' => $decideSchedule,
                'answerDataHtml' => $answerDataHtml,
                'csEditConf' => $csEditConf,
                'statusList' => $statusList,
                'userName' => $userName,
                'age' => $age,
            ]
        )


    </div>
</div>
@endsection



