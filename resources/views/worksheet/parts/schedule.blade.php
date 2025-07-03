<div class="schedule_confirm d-none">
      <div class="schedule_frame">
        <h2 class="schedule_title">面接希望日</h2>
        @for($index = 1; $index <= 3; $index++)
            <div class="schedule_choice_frame">
                <select name="choice_schedule_{{$index}}">
                    @if($index == 1)
                        <option value="-">第１希望日▼</option>
                    @elseif($index == 2)
                        <option value="-">第２希望日▼</option>
                    @elseif($index == 3)
                        <option value="-">第３希望日▼</option>
                    @endif
                    @foreach($schedules as $schedule)
                    <option value="{{$schedule['schedule']}}">{{$schedule['schedule']}}</option>
                    @endforeach
                </select>
            </div>
        @endfor
        <div class="switch_schedule">
            <p name="to_adjust_schedule">
                <u>
                    <span>日程が表示されない</span><br>
                    <span>合う日程がない</span>
                </u>
            </p>
        </div>
    </div>
    <div class="form_btn">
        <button name="send_schedule_btn">送信</button>
    </div>
</div>
