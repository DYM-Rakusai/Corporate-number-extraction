<?php
declare(strict_types=1);
namespace App\Http\Controllers\Auth\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use packages\Url\UseCase\Create\CreateUrlOtherWorksheetServiceInterface;
use packages\Schedule\UseCase\Decide\DecideScheduleServiceInterface;
use packages\Consumer\UseCase\Read\GetCsDataServiceInterface;
use packages\User\UseCase\Read\GetUserServiceInterface;
use packages\Mail\UseCase\Send\MailSendServiceInterface;
use packages\Text\UseCase\Make\MakeTextServiceInterface;
use packages\Resend\UseCase\Update\ResendUpdateServiceInterface;
use packages\Remind\UseCase\Register\SetMsgRemindServiceInterface;
use packages\Sms\UseCase\Send\SmsSendServiceInterface;
use packages\Consumer\UseCase\Update\UpdateConsumerDataServiceInterface;
use packages\Consumer\UseCase\Validate\ValidateConsumerDataServiceInterface;
use packages\Worksheet\UseCase\Update\WorksheetUpdateServiceInterface;

class GetCompanyAnswerController extends Controller
{
    private $CreateUrlOtherWorksheetService;
    private $DecideScheduleService;
    private $GetCsDataService;
    private $GetUserService;
    private $MailSendService;
    private $MakeTextService;
    private $nowCarbonStr;
    private $ResendUpdateService;
    private $SetMsgRemindService;
    private $SmsSendService;
    private $UpdateConsumerDataService;
    private $ValidateConsumerDataService;
    private $WorksheetUpdateService;

    public function __construct(
        CreateUrlOtherWorksheetServiceInterface $CreateUrlOtherWorksheetService,
        DecideScheduleServiceInterface          $DecideScheduleService,
        GetCsDataServiceInterface               $GetCsDataService,
        GetUserServiceInterface                 $GetUserService,
        MailSendServiceInterface                $MailSendService,
        MakeTextServiceInterface                $MakeTextService,
        ResendUpdateServiceInterface            $ResendUpdateService,
        SetMsgRemindServiceInterface            $SetMsgRemindService,
        SmsSendServiceInterface                 $SmsSendService,
        UpdateConsumerDataServiceInterface      $UpdateConsumerDataService,
        ValidateConsumerDataServiceInterface    $ValidateConsumerDataService,
        WorksheetUpdateServiceInterface         $WorksheetUpdateService
    )
    {
        $this->middleware(['auth']);
        $this->nowCarbonStr                   = Carbon::now('Asia/Tokyo')->format('Y-m-d H:i');
        $this->CreateUrlOtherWorksheetService = $CreateUrlOtherWorksheetService;
        $this->DecideScheduleService          = $DecideScheduleService;
        $this->GetCsDataService               = $GetCsDataService;
        $this->GetUserService                 = $GetUserService;
        $this->MailSendService                = $MailSendService;
        $this->MakeTextService                = $MakeTextService;
        $this->ResendUpdateService            = $ResendUpdateService;
        $this->SetMsgRemindService            = $SetMsgRemindService;
        $this->SmsSendService                 = $SmsSendService;
        $this->UpdateConsumerDataService      = $UpdateConsumerDataService;
        $this->ValidateConsumerDataService    = $ValidateConsumerDataService;
        $this->WorksheetUpdateService         = $WorksheetUpdateService;
    }

    public function store(Request $request)
    {
        \Log::info('----------GetCompanyAnswerController---------');
        \Log::info($request);

        $pattern      = $request->interviewSettingMethod;
        $consumerId   = $request->consumerId;
        $consumerData = $this->GetCsDataService->getConsumerData('id', $consumerId);

        $atsConsumerId = $consumerData['ats_consumer_id'];
        $csTel         = $consumerData['tel'];
        $csMail        = $consumerData['mail'];

        // 会社回答内容をワークシートに保存
        $this->WorksheetUpdateService->updateWorksheet(
            ['ats_consumer_id'     => $atsConsumerId],
            ['company_answer_json' => json_encode($request->all())]
        );

        $nextStatusList = config('Consumer.statusList.patternList');
        $this->UpdateConsumerDataService->updateCsData(
            ['ats_consumer_id' => $atsConsumerId],
            ['consumer_status' => $nextStatusList[$pattern] ?? null]
        );

        if ($pattern == 'schedule_date') {
            $userId        = $consumerData['user_id'] ?? '';
            $interviewDate = $request->interviewDate;
            $interviewTime = $request->interviewTime;
            $schedule      = $interviewDate . ' ' . $interviewTime . ':00';

            $isSet = $this->DecideScheduleService->decideSchedule(
                $schedule,
                $atsConsumerId,
                $userId
            );

            if ($isSet === false) {
                \Log::error('この日程はすでに埋まっています。');
                return response()->json(['status' => 'notDecide']);
            }

            $csUrl = $this->CreateUrlOtherWorksheetService->makeUrl(
                'cs-status-page',
                ['hashCs' => $atsConsumerId],
                [
                    'consumerId' => $consumerId,
                    'csStatus'   => 'decide'
                ]
            );

            $smsText     = $this->MakeTextService->makeSendSmsText('decideInterview', $csUrl);
            $mailData    = $this->MakeTextService->makeSendMailText('decideInterview', $csUrl);
            $isValidList = $this->ValidateConsumerDataService->getIsValidList([
                'tel'  => $csTel,
                'mail' => $csMail
            ]);

            if ($isValidList['tel']) {
                $this->SmsSendService->sendSms($smsText, $csTel, $atsConsumerId);
                $this->ResendUpdateService->updateResendData(
                    ['ats_consumer_id' => $atsConsumerId],
                    $smsText
                );
            }

            if ($isValidList['mail']) {
                $this->MailSendService->sendMail(
                    [$csMail],
                    [],
                    $mailData['subject'],
                    $mailData['mainText'],
                    $atsConsumerId
                );
            }

            $this->SetMsgRemindService->setRemind(
                $atsConsumerId,
                $consumerId,
                'remind',
                $schedule
            );

            $cpUrl = $this->CreateUrlOtherWorksheetService->makeUrl(
                'company-status-page',
                ['hashCs' => $atsConsumerId],
                [
                    'consumerId' => $consumerId,
                    'status'     => 'decideForCompany'
                ]
            );

            $mailDataForCp = $this->MakeTextService->makeSendMailText('decideForCompany', $cpUrl);
            $userMail      = $this->GetUserService->getUserValById($userId, 'mail');

            $this->MailSendService->sendToCompanyMail(
                $mailDataForCp['subject'],
                $mailDataForCp['mainText'],
                $atsConsumerId,
                $userId,
                [$userMail]
            );

            $this->UpdateConsumerDataService->updateCsData(
                ['ats_consumer_id'         => $atsConsumerId],
                ['interview_decision_date' => $this->nowCarbonStr]
            );
        } else {
            \Log::error('GetCompanyAnswerController エラー');
        }

        return response()->json(['status' => 'success']);
    }
}

