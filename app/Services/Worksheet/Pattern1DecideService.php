<?php
namespace App\Services\Worksheet;

use Illuminate\Support\Carbon;
use packages\Url\UseCase\Create\CreateUrlOtherWorksheetServiceInterface;
use packages\Schedule\UseCase\Read\GetScheduleServiceInterface;
use packages\User\UseCase\Read\GetUserServiceInterface;
use packages\Mail\UseCase\Send\MailSendServiceInterface;
use packages\Text\UseCase\Make\MakeTextServiceInterface;
use packages\Resend\UseCase\Update\ResendUpdateServiceInterface;
use packages\Remind\UseCase\Register\SetMsgRemindServiceInterface;
use packages\Schedule\UseCase\Update\SetScheduleServiceInterface;
use packages\Sms\UseCase\Send\SmsSendServiceInterface;
use packages\Consumer\UseCase\Update\UpdateConsumerDataServiceInterface;
use packages\Consumer\UseCase\Validate\ValidateConsumerDataServiceInterface;

class Pattern1DecideService
{
    private $CreateUrlOtherWorksheetService;
    private $GetScheduleService;
    private $GetUserService;
    private $MailSendService;
    private $MakeTextService;
    private $nowCarbonStr;
    private $ResendUpdateService;
    private $SetMsgRemindService;
    private $SetScheduleService;
    private $SmsSendService;
    private $UpdateConsumerDataService;
    private $ValidateConsumerDataService;

    public function __construct(
        CreateUrlOtherWorksheetServiceInterface $CreateUrlOtherWorksheetService,
        GetScheduleServiceInterface             $GetScheduleService,
        GetUserServiceInterface                 $GetUserService,
        MailSendServiceInterface                $MailSendService,
        MakeTextServiceInterface                $MakeTextService,
        ResendUpdateServiceInterface            $ResendUpdateService,
        SetMsgRemindServiceInterface            $SetMsgRemindService,
        SetScheduleServiceInterface             $SetScheduleService,
        SmsSendServiceInterface                 $SmsSendService,
        UpdateConsumerDataServiceInterface      $UpdateConsumerDataService,
        ValidateConsumerDataServiceInterface    $ValidateConsumerDataService
    )
    {
        $this->CreateUrlOtherWorksheetService = $CreateUrlOtherWorksheetService;
        $this->GetScheduleService             = $GetScheduleService;
        $this->GetUserService                 = $GetUserService;
        $this->MailSendService                = $MailSendService;
        $this->MakeTextService                = $MakeTextService;
        $this->ResendUpdateService            = $ResendUpdateService;
        $this->SetMsgRemindService            = $SetMsgRemindService;
        $this->SetScheduleService             = $SetScheduleService;
        $this->SmsSendService                 = $SmsSendService;
        $this->UpdateConsumerDataService      = $UpdateConsumerDataService;
        $this->ValidateConsumerDataService    = $ValidateConsumerDataService;
        $this->nowCarbonStr                   = Carbon::now('Asia/Tokyo')->format('Y-m-d H:i');
    }

    public function pattern1DecideAction($schedules, $consumerData, $userId=null)
    {
        $atsCsId        = $consumerData['ats_consumer_id'];
        $userId         = $consumerData['user_id'] ?? '';
        $isSet          = false;
        $dicedeSchedule = '';

        foreach ($schedules as $schedule) {
            $scheduleObj = $this->GetScheduleService->getScheduleDataByWhere([
                'schedule'  => $schedule,
                'user_id'   => $consumerData['user_id'],
                'is_filled' => 0
            ]);

            if (!$scheduleObj->isEmpty()) {
                $updateData = [
                    'ats_consumer_id' => $atsCsId,
                    'user_id'         => $consumerData['user_id'],
                    'is_filled'       => 1
                ];

                $this->SetScheduleService->updateSchedule([
                    'schedule'  => $schedule,
                    'user_id'   => $consumerData['user_id'],
                    'is_filled' => 0
                ], $updateData);

                $isSet          = true;
                $dicedeSchedule = $schedule;
                $userId         = $scheduleObj[0]->user_id;
                break;
            }
        }
        if ($isSet === false || empty($dicedeSchedule)) {
            \Log::error('面接設定エラー: 面接日：' . $dicedeSchedule);
            return $isSet;
        }

        $this->sendInterviewDecisionNotice($consumerData, $dicedeSchedule, $userId);
        
        return True;
    }

    public function sendInterviewDecisionNotice($consumerData, $dicedeSchedule, $userId)
    {
        $csTel      = $consumerData['tel'];
        $csMail     = $consumerData['mail'];
        $atsCsId    = $consumerData['ats_consumer_id'];
        $consumerId = $consumerData['id'];

        $isValidList = $this->ValidateConsumerDataService->getIsValidList([
            'tel'  => $csTel ,
            'mail' => $csMail
        ]);

        $csUrl = $this->CreateUrlOtherWorksheetService->makeUrl(
            'cs-status-page',
            [   'hashCs'     => $atsCsId  ],
            [
                'consumerId' => $consumerId,
                'csStatus'   => 'decide'
            ]
        );

        if ($isValidList['tel']) {
            $smsText = $this->MakeTextService->makeSendSmsText('decideInterview', $csUrl);
            $this->SmsSendService->sendSms($smsText, $csTel, $atsCsId);
            $this->ResendUpdateService->updateResendData(['ats_consumer_id' => $atsCsId], $smsText);
        }

        if ($isValidList['mail']) {
            $mailData = $this->MakeTextService->makeSendMailText('decideInterview', $csUrl);
            $this->MailSendService->sendMail(
                [$csMail],
                [],
                $mailData['subject'],
                $mailData['mainText'],
                $atsCsId
            );
        }

        $this->SetMsgRemindService->setRemind($atsCsId, $consumerId, 'remind', $dicedeSchedule);

        $cpUrl = $this->CreateUrlOtherWorksheetService->makeUrl(
            'company-status-page',
            [   'hashCs'     => $atsCsId],
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
            $atsCsId,
            $userId,
            $userMail
        );

        $whereData    = ['ats_consumer_id' => $atsCsId];
        $consumerData = array('interview_decision_date' => $this->nowCarbonStr);
        $this->UpdateConsumerDataService->updateCsData($whereData, $consumerData);
        
    }
}
