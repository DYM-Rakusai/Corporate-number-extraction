<?php

namespace App\Http\Controllers\View;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use packages\Consumer\UseCase\Read\GetCsDataServiceInterface;
use packages\Schedule\UseCase\Read\GetScheduleServiceInterface;
use packages\Worksheet\Domain\WsFormChoice\GetWsFormChoiceService;
use packages\Worksheet\UseCase\Validate\IsAnswerServiceInterface;
use packages\Resend\UseCase\Update\ResendUpdateServiceInterface;
use packages\Url\UseCase\Validate\UrlParamServiceInterface;
use packages\Consumer\UseCase\Validate\ValidateConsumerDataServiceInterface;

class WorksheetController extends Controller
{
    private $endDateAddWeeks;
    private $GetCsDataService;
    private $GetScheduleService;
    private $GetWsFormChoiceService;
    private $IsAnswerService;
    private $ResendUpdateService;
    private $UrlParamService;
    private $ValidateConsumerDataService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        GetCsDataServiceInterface            $GetCsDataService,
        GetScheduleServiceInterface          $GetScheduleService,
        GetWsFormChoiceService               $GetWsFormChoiceService,
        IsAnswerServiceInterface             $IsAnswerService,
        ResendUpdateServiceInterface         $ResendUpdateService,
        UrlParamServiceInterface             $UrlParamService,
        ValidateConsumerDataServiceInterface $ValidateConsumerDataService
    ) {
        $this->GetCsDataService            = $GetCsDataService;
        $this->GetScheduleService          = $GetScheduleService;
        $this->GetWsFormChoiceService      = $GetWsFormChoiceService;
        $this->IsAnswerService             = $IsAnswerService;
        $this->ResendUpdateService         = $ResendUpdateService;
        $this->UrlParamService             = $UrlParamService;
        $this->ValidateConsumerDataService = $ValidateConsumerDataService;

        $this->endDateAddWeeks = config('Schedule.scheduleConf.endDateAddWeeks');
        $now                   = Carbon::now('Asia/Tokyo');
        $this->startDate       = $now->copy()->addDay();
        $this->endDate         = $now->copy()->addWeeks($this->endDateAddWeeks);
    }

    public function index(Request $request)
    {
        \Log::info($request);
        $consumerId      = $request->get('consumerId');
        $isConsumerExist = $this->ValidateConsumerDataService->isExistConsumer('id', $consumerId);

        // consumerIdが存在しない場合はエラー
        if ($isConsumerExist == false) {
            \Log::error('パラメータに不備: consumerId');
            return;
        }

        $consumerData  = $this->GetCsDataService->getConsumerData('id', $consumerId);
        $atsConsumerId = $consumerData['ats_consumer_id'];
        $hashCs        = $request->get('hashCs');
        $isValidUrl    = $this->UrlParamService->isValidUrl([$atsConsumerId => $hashCs]);

        // URL認証失敗時
        if ($isValidUrl == false) {
            \Log::error('パラメータに不備: URL 認証失敗');
            return;
        }

        $hasAnswered = $this->IsAnswerService->checkAnswer($atsConsumerId);

        // アンケート回答済みの場合はフリーページ表示
        if ($hasAnswered == false) {
            \Log::info('アンケート回答済み');
            return view(
                'public.freePage',
                [
                    'pageTitle'   => config('Text.freePageTitle.worksheetTitle'),
                    'pageContent' => config('Text.freePageMessage.worksheetContent')
                ]
            );
        }

        // 回答確認時刻を更新
        $this->ResendUpdateService->updateConfirmTime($atsConsumerId);

        // フォーム選択肢取得
        $formChoices = $this->GetWsFormChoiceService->getFormChoices($consumerData);
        
        $userId      = $consumerData['user_id']      ?? null;
        $atsStoreId  = $consumerData['ats_store_id'] ?? null;

        // スケジュール取得・ソート
        $schedules = $this->GetScheduleService->getDataInSchedule(
            $this->startDate,
            $this->endDate,
            0,
            $userId,
            $atsStoreId
        )->sortBy(fn($schedule) => strtotime($schedule['schedule']));

        // その他スケジュール・時間帯配列取得
        list($anotherSchedules, $startSelectTimeArray, $endSelectTimeArray) = $this->GetScheduleService->getAnotherSchedules();

        $viewData = [
            'versionParam'         => config('app.versionParam'),
            'apiToken'             => config('app.apiToken'),
            'formChoices'          => $formChoices,
            'schedules'            => $schedules,
            'anotherSchedules'     => $anotherSchedules,
            'startSelectTimeArray' => $startSelectTimeArray,
            'endSelectTimeArray'   => $endSelectTimeArray
        ];

        return view('worksheet.worksheet', $viewData);
    }
}
