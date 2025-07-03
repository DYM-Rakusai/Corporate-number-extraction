
@extends('layouts.sidemenu')

@section('content')

<link href="{{ asset('plugin/lib/main.css') }}" rel="stylesheet">
<script src="{{ asset('plugin/lib/main.js') }}" defer></script>
<script src="{{ asset('js/jquery-3.6.0.min.js') }}" defer></script>
<script>
    const calendarEvenets = <?php echo $forPageScheduleDataJson; ?>;
</script>

<div class="m-0 mt-5 mb-5">
    <div class="">
        <div>
            @if($userName != '')
                <h5 class="user_name">{{$userName}}</h5>
            @endif
        </div>

        @if(!empty($userDataArray))
            <div class="dropdown dropdown_store">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    カレンダー選択
                </button>

                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    @foreach($userDataArray as $userData)
                        @if ($userData->authority != 'master')
                            <a class="dropdown-item" href="{{ url('/set-schedule-page') }}?userId={{$userData->id}}">{{$userData->name}}</a>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif
        @if($userId != 0)
            <div>
                <span class="calendar_description">「スケジュール更新」ボタンを押さないと、修正が反映されないのでご注意ください。</span>
            </div>
            <div id="calendar">
            </div>
                
            <div class="btn_frame">
                <button name="update_schedule">スケジュール更新</button>
            </div>
        @endif
    </div>
    @include('auth.company.calendar.scheduleDetail', [])
</div>


@endsection
