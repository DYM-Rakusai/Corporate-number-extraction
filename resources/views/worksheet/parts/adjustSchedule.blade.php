<div class="schedule_adjust_confirm d-none">
      <div class="schedule_frame">
        <h2 class="schedule_title">面接可能日を選択</h2>
        <p>
        <span>
            面接可能な日程を、<strong>3日以上</strong>ご選択ください。<br>
            ご応募内容をもとに、面接を設定致します。<br>
            可能な日程がない場合は、備考欄に希望日時をご記入ください。<br>
        </span>
        </p>
        <div class="schedule_table_frame">
            <table frame="Void" border="1" name="schedule_table">
                <tbody>
                    @foreach($anotherSchedules as $anotherSchedule)
                    <tr>
                        <th>
                            <span>{{$anotherSchedule['year']}}</span><br>
                            <span>{{$anotherSchedule['month']}}/{{$anotherSchedule['day']}}({{$anotherSchedule['dayOfWeek']}})</span>
                        </th>
                        <td>
                            <ul>
                                <li>
                                    <select name="adjust_start_date" data-date="{{$anotherSchedule['year']}}-{{$anotherSchedule['month']}}-{{$anotherSchedule['day']}}">
                                        <option value="-">00:00</option>
                                        @foreach($startSelectTimeArray as $startIndex => $startSelectTime)
                                            <option
                                                data-start_index="{{$startIndex}}"
                                                value="{{$startSelectTime}}">{{$startSelectTime}}</option>
                                        @endforeach
                                    </select>
                                    <span>から</span>
                                </li>
                                <li>
                                    <select name="adjust_end_date">
                                        <option value="-">00:00</option>
                                        @foreach($endSelectTimeArray as $endIndex => $endSelectTime)
                                            <option
                                                data-end_index="{{$endIndex}}"
                                                value="{{$endSelectTime}}">{{$endSelectTime}}</option>
                                        @endforeach
                                    </select>
                                    <span>まで</span>
                                </li>
                            </ul>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        
        <div class="adjust_remarks">
            <p class="adjust_remarks_title">備考</p>
            <textarea name="adjust_remarks" placeholder="例：5月17日（土） 10時～18時"></textarea>
        </div>
        
        <div class="switch_schedule">
            <p name="to_schedule">
                <u>
                    <span>※候補日から選択する</span>
                </u>
            </p>
        </div>
       
    </div>
    <div class="form_btn">
        <button name="send_adjust_schedule_btn">送信</button>
    </div>
</div>