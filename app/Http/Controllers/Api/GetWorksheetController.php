<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Worksheet\Pattern1AdjustService;
use App\Services\Worksheet\Pattern1DecideService;
use packages\Consumer\UseCase\Read\GetCsDataServiceInterface;
use packages\Worksheet\UseCase\Validate\IsAnswerServiceInterface;
use packages\Consumer\UseCase\Update\UpdateConsumerDataServiceInterface;
use packages\Url\UseCase\Validate\UrlParamServiceInterface;
use packages\Worksheet\UseCase\Update\WorksheetUpdateServiceInterface;
use packages\Consumer\UseCase\Validate\ValidateConsumerDataServiceInterface;
use packages\Remind\UseCase\Register\SetMsgRemindServiceInterface;

class GetWorksheetController extends Controller
{
    private $GetCsDataService;
    private $IsAnswerService;
    private $UrlParamService;
    private $UpdateConsumerDataService;
    private $Pattern1AdjustService;
    private $Pattern1DecideService;
    private $ValidateConsumerDataService;
    private $WorksheetUpdateService;
    private $SetMsgRemindService;

    public function __construct(
        GetCsDataServiceInterface            $GetCsDataService,
        IsAnswerServiceInterface             $IsAnswerService,
        UrlParamServiceInterface             $UrlParamService,
        UpdateConsumerDataServiceInterface   $UpdateConsumerDataService,
        Pattern1AdjustService                $Pattern1AdjustService,
        Pattern1DecideService                $Pattern1DecideService,
        WorksheetUpdateServiceInterface      $WorksheetUpdateService,
        SetMsgRemindServiceInterface         $SetMsgRemindService,
        ValidateConsumerDataServiceInterface $ValidateConsumerDataService
    )
    {
        $this->GetCsDataService            = $GetCsDataService;
        $this->IsAnswerService             = $IsAnswerService;
        $this->UrlParamService             = $UrlParamService;
        $this->UpdateConsumerDataService   = $UpdateConsumerDataService;
        $this->Pattern1AdjustService       = $Pattern1AdjustService;
        $this->Pattern1DecideService       = $Pattern1DecideService;
        $this->WorksheetUpdateService      = $WorksheetUpdateService;
        $this->ValidateConsumerDataService = $ValidateConsumerDataService;
        $this->SetMsgRemindService         = $SetMsgRemindService;
    }

    public function store(Request $request)
    {
        \Log::info('----------GetWorksheetController---------');
        \Log::info($request);
    
        if ($request->get('apiToken') !== config('app.api_token')) {
            throw new \Exception('APIトークンが不正です。');
        }
        
        $consumerId = $request->get('consumerId');
        $isExistCs  = $this->ValidateConsumerDataService->isExistConsumer('id', $consumerId);
        if (!$isExistCs) {
            \Log::info('対象の応募者がいません。');
            return;
        }
    
        $consumerData = $this->GetCsDataService->getConsumerData('id', $consumerId);
        $atsCsId      = $consumerData['ats_consumer_id'];
        $hashCs       = $request->get('hashCs');
        $isValid      = $this->UrlParamService->isValidUrl([$atsCsId => $hashCs]);

        if ($isValid === false) {
            \Log::error('URLのパラメータに不備があります。');
            return;
        }
    
        $validAnswer = $this->IsAnswerService->checkAnswer($atsCsId);

        if ($validAnswer === false) {
            \Log::info('アンケート回答済みです。');
            return;
        }

        $pattern         = $request->get('pattern'   );
        $answerData      = $request->get('answerData');
        $schedules       = $request->get('schedules'      , null);
        $adjustSchedules = $request->get('adjustSchedules', null);

        if ($pattern == 'pattern1decide') {
            $isSet = $this->Pattern1DecideService->pattern1DecideAction($schedules, $consumerData, null);
        } elseif ($pattern == 'pattern1adjust') {
            $isSet = $this->Pattern1AdjustService->pattern1AdjustAction($consumerData);
        } elseif ($pattern == 'failure') {
            $isSet = $this->SetMsgRemindService->setRemind(
                $atsCsId,
                $consumerId,
                'failure',
                null
            );
        } else {
            \Log::error('GetWorkSheetController：想定外のパターンです');
            $isSet = false;
        }

        if (!$isSet) {
            return response()->json(['status' => 'error'], 200);
        }

        // ワークシート情報を更新
        $this->WorksheetUpdateService->updateWorksheet(
            ['ats_consumer_id' => $atsCsId],
            [
                'answer_status'    => $pattern,
                'ws_answers'       => $this->encodeJson($answerData),
                'schedule_answers' => $this->encodeJson($schedules),
                'adjust_schedules' => $this->encodeJson($adjustSchedules)
            ]
        );

        // 次のステータスを取得し、応募者情報を更新
        $nextStatusList = config('Consumer.statusList.patternList');
        $csStatus = $nextStatusList[$pattern] ?? null;

        $this->UpdateConsumerDataService->updateCsData(
            ['ats_consumer_id' => $atsCsId],
            [
                'consumer_status' => $csStatus,
                'interview_way'   => $answerData['interview_way'] ?? null
            ]
        );
    }

    /**
     * データが空でなければJSONエンコードして返す
     *
     * @param mixed $data
     * @return string|null
     */
    private function encodeJson($data)
    {
        return !empty($data) ? json_encode($data) : null;
    }

}





