<div>
    @inject('ShapDate', 'App\Services\ShapDate\ShapDateService')
    
    <div class="page-nation-top">
        <button type="button" class="download-button-top" name="download-button" value="Excelダウンロード">Excelダウンロード</button>
        {{ $csPageData->appends($urlParam)->links() }}
    </div>
    
    <table class="cs_list">
        <thead>
            <th>応募日時/ステータス</th>
            <th>詳細情報</th>
        </thead>
        <tbody>
            @foreach($csPageData as $cnt => $csData)

                @php
                $entryDate = $ShapDate->shapDate(
                    $csData->app_date ?? '', 'Y年m月d日 H:i'
                )
                @endphp
                <td>
                    {{$entryDate}}<br>
                    <span
                        class="status_name"
                        style="
                            background-color: {{$statusColors[$csData->consumer_status] ?? '#ff0000'}};
                            border: 2px solid {{$statusColors[$csData->consumer_status] ?? '#ff0000'}};"
                        >{{$csData->consumer_status}}
                    </span>

                    <div class="memo-area">
                        <textarea class="textarea_{{$cnt}} d-none">{{$csData->memo}}</textarea>
                        <input type="text" class="memo_{{$cnt}}" disabled="disabled" value="{{$csData->memo}}"></input>
                        <button type="button" class="edit-btn_{{$cnt}}" name="edit-btn">メモ</button>
                        <button type="button" class="keep-btn_{{$cnt}} d-none" name="keep-btn">保存</button>
                    </div>
                    
                </td>
                <td>
                    @php
                        if (!empty($csData->birthday)) {
                            try {
                                $birthday = \Carbon\Carbon::parse($csData->birthday);
                                $formattedBirthday = $birthday->format('Y年n月j日');
                                $age = $birthday->age;
                                $birthdayDisplay = "{$formattedBirthday} ({$age}歳)";
                            } catch (\Exception $e) {
                                $birthdayDisplay = "{$csData->birthday}";
                            }
                        } else {
                            $birthdayDisplay = "-";
                        }
                        $interviewDate = $ShapDate->shapDate(
                            $csData['scheduleData'][0]->schedule ?? '', 'Y年m月d日 H:i'
                        );
                    @endphp
                    <div class="cs_detail">
                        <u>
                            <a href="{{ url('/cs-detail-page') }}?id={{$csData->ats_consumer_id}}" target="_blank" class="csDetail_{{$cnt}}">編集</a>
                        </u><br>
                        氏名　　　：　{{$csData->name}}<br>
                        生年月日　：　{{$birthdayDisplay }}<br>
                        メール　　：　{{$csData->mail}}<br>
                        電話　　　：　{{$csData->tel}}</a><br>
                        求人情報　：　{{$csData->app_media ?? '-'}} / {{$csData->entry_job}}</a><br>
                        面接日　　：　{{$interviewDate}}<br>
                        @if ($userId == '')
                        担当者　　：　{{$csData['userData'][0]->name ?? NULL}}
                        @endif
                        <br>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="page-nation-bottom">
        {{ $csPageData->links() }}
    </div>
</div>
