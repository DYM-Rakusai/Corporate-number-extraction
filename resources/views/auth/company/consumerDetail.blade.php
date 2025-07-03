
@extends('layouts.sidemenu')

@section('content')

<script>
</script>

<div class="m-0 mt-5 mb-5">
    <div class="">
        <div>
            <u>
                <a class=""
                    href="{{ url('/cs-list-page') }}">応募者一覧へ戻る</a>
            </u>
            <a title="編集ページへ"
                href="{{ url('/edit-cs-page') }}?id={{$atsCsId}}">
                <img class="cs-edit-logo" src="{{ asset('upload/image/pen.png') }}">
            </a>
        </div>



        @include('auth.company.consumer.csDetail',
            [
                'getCsData' => $getCsData,
                'cskeys' => $cskeys,
                'age' => $age,
                'authority' => $authority,
                'decideSchedule' => $decideSchedule,
                'answerDataHtml' => $answerDataHtml,
                'statusColors' => $statusColors,
                'freeScheduleDatas' => $freeScheduleDatas
            ]
        )
    </div>
</div>
@endsection



