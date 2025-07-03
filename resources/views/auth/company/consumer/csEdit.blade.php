<div>
    @inject('ShapDateService', 'App\Services\ShapDate\ShapDateService')
    <table class="cs_detail">
        <tbody>
            @foreach($cskeys as $csKey => $csName)
            <tr>
                <th>
                    {{ $csName }}
                </th>
                <td>
                    @if(array_key_exists($csKey, $csEditConf))
                        @if($csEditConf[$csKey] == 'inputText')
                        <input
                            name="{{$csKey}}"
                            type="text"
                            value="{{$getCsData[$csKey] ?? ''}}">
                        @elseif($csEditConf[$csKey] == 'inputDate')
                        <input
                            name="{{$csKey}}"
                            type="date"
                            value="{{$ShapDateService->shapDate($getCsData[$csKey] ?? '', 'Y-m-d')}}">

                        @elseif($csEditConf[$csKey] == 'selectStatus')
                            <select name="{{$csKey}}">
                                @foreach($statusList as $statusData)
                                    @if(!empty($getCsData[$csKey]) && $statusData == $getCsData[$csKey])
                                        <option value="{{$statusData}}" selected>{{$statusData}}</option>
                                    @else
                                        <option value="{{$statusData}}">{{$statusData}}</option>
                                    @endif
                                @endforeach
                            </select>

                        @elseif($csEditConf[$csKey] == 'textArea')
                            <textarea name="{{$csKey}}">{{$getCsData[$csKey] ?? ''}}</textarea>
                        @elseif($csEditConf[$csKey] == 'interviewDate')
                            {{$decideSchedule}}
                        @endif
                    @else
                        @if($csKey == 'app_date' && !empty($getCsData[$csKey]))
                            {{ $ShapDateService->shapDate($getCsData[$csKey] ?? '', 'Y年m月d日 H:i') }}
                        @elseif($csKey == 'birthday')
                            {{$getCsData[$csKey]}}
                            @if($age != '')
                                ({{ $age}}歳)
                            @endif
                        @else
                            {{ $getCsData[$csKey] ?? '-' }}
                        @endif
                    @endif
                </td>
            </tr>
            @endforeach
            @if ($authority == 'master')
            <tr>
                <th>面接担当者</th>
                <td>
                    <div class="cs_store">
                    {{ $userName }}
                    </div>
                </td>
            </tr>
            @endif
            <tr>
                <th>回答情報</th>
                <td>
                    <div class="cs_answer">
                    {!! $answerDataHtml !!}
                    </div>
                </td>
            </tr>

        </tbody>
    </table>

    <div class="btn_area">
        <button name="update_cs_data">応募者情報更新</button>
    </div>
</div>

