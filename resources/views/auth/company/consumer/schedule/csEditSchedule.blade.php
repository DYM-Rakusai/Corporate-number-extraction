<div>
    <div>
        <span>{{$decideSchedule}}</span>
    </div>
    <div class="interview_change_frame">
        <button name="interview_change_btn">面接の設定・変更・キャンセル</button>
    </div>
    <div class="set_title_frame d-none">
        <span class="set_title">面接の設定・変更・キャンセル</span>
    </div>
    <div class="interview_div d-none">
        <div class="interview_set_frame">
            <span class="interview_set_title">選択する</span>
            <br>
            <select name="interview_set">
                <option value="">-</option>
                <option value="面接キャンセル">面接キャンセル</option>
                <option value="日程設定">日程設定</option>
                <!--
                @foreach($freeScheduleDatas as $freeScheduleData)
                    @if(date("Y-m-d H:i:s") < $freeScheduleData['scheduleDate'])
                        <option value="{{$freeScheduleData['scheduleDate']}}">{{$freeScheduleData['scheduleStr']}}</option>
                    @endif
                @endforeach
                -->
            </select>
        </div>

        <div class="interview_date_set d-none">
            <div class="interview_date_frame">
                <b class="form_title">面接の日付</b><br>
                <input type="date" name="interview_date" id="calendar">
            </div>
            <br>
            <div class="interview_time_frame">
                <b class="form_title">開始時刻</b>
                <br>
                <select name="interview_time">
                    <option value="-">面接時刻</option>
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
            <br>
        </div>

        <div class="remind_set_frame d-none">
            <span class="interview_set_title">リマインドの設定</span><br>
            <label>
                <input type="radio" name="remind_set" value="1">
                <span>面接の前日にリマインドをする</span>
            </label><br>
            <label>
                <input type="radio" name="remind_set" value="0">
                <span>面接の前日にリマインドをしない</span>
            </label>
        </div>

        <!--
        <div class="interview_way_set_frame d-none">
            <span class="interview_way_title">面接形式の設定</span><br>
            <label>
                <input type="radio" name="interview_way_set" value="tel">
                <span>電話面談</span>
            </label><br>
            <label>
                <input type="radio" name="interview_way_set" value="web">
                <span>WEB面談</span>
            </label>
        </div>
        -->

        <div class="interview_set_btn_frame d-none">
            <button name="interview_set_btn">上記の内容を反映</button>
        </div>
    </div>
</div>