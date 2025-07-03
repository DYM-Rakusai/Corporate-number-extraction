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
                    @if($csKey == 'consumer_status')
                        <span
                        class="status_name"
                        style="
                            background-color: {{$statusColors[$getCsData[$csKey]] ?? '#ff0000'}};
                            border: 2px solid {{$statusColors[$getCsData[$csKey]] ?? '#ff0000'}};"
                        >{{ $getCsData[$csKey] ?? '-' }}</span>
                    @elseif($csKey == 'app_date' && !empty($getCsData[$csKey]))
                        {{ $ShapDateService->shapDate($getCsData[$csKey] ?? '', 'Y年m月d日 H:i') }}
                    @elseif(( $csKey == 'interview_decision_date' ) &&
                            !empty($getCsData[$csKey]))
                        {{ $ShapDateService->shapDate($getCsData[$csKey] ?? '', 'Y年m月d日') }}
                    @elseif(($csKey == 'memo' ||
                            $csKey == 'address') && 
                            !empty($getCsData[$csKey]))
                        {!! nl2br(e($getCsData[$csKey])) !!}
                    @elseif($csKey == 'interview_date')
                        @include(
                            'auth.company.consumer.schedule.csEditSchedule',
                            [
                                'decideSchedule' => $decideSchedule,
                                'freeScheduleDatas' => $freeScheduleDatas
                            ]
                        )
                    @elseif($csKey == 'entry_job')
                        {{ $getCsData[$csKey] ?? '-' }}</a>
                    @elseif($csKey == 'birthday')
                        {{$getCsData[$csKey]}}
                        @if($age != '')
                            ({{ $age}}歳)
                        @endif
                    @else
                        {{ $getCsData[$csKey] ?? '-' }}
                    @endif
                </td>
            </tr>
            @endforeach
            <tr>
                <th>回答情報</th>
                <td>
                    <div class="cs_answer">
                    {!! $answerDataHtml !!}
                    </div>
                </td>
            </tr>
            @if($authority == 'master')
            <tr>
                <th>担当者</th>
                <td>
                    <div class="cs_store">
                    {{ $userName }}
                    </div>
                </td>
            </tr>
            @endif
        </tbody>
    </table>
</div>
