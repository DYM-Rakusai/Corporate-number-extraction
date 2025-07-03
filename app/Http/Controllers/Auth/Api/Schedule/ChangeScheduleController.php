<?php

namespace App\Http\Controllers\Auth\Api\Schedule;

use Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use packages\Url\UseCase\Create\CreateUrlOtherWorksheetServiceInterface;
use packages\Schedule\UseCase\Delete\DeleteScheduleServiceInterface;
use packages\Consumer\UseCase\Read\GetCsDataServiceInterface;
use packages\Schedule\UseCase\Read\GetScheduleServiceInterface;
use packages\User\UseCase\Read\GetUserServiceInterface;
use packages\Worksheet\UseCase\Validate\IsAnswerServiceInterface;
use packages\Schedule\UseCase\Create\InsertScheduleServiceInterface;
use packages\Mail\UseCase\Send\MailSendServiceInterface;
use packages\Text\UseCase\Make\MakeTextServiceInterface;
use packages\Remind\UseCase\Register\RegisterRemindServiceInterface;
use packages\Resend\UseCase\Update\ResendUpdateServiceInterface;
use packages\Sms\UseCase\Send\SmsSendServiceInterface;
use packages\Schedule\UseCase\Update\SetScheduleServiceInterface;
use packages\Consumer\UseCase\Update\UpdateConsumerDataServiceInterface;
use packages\Remind\UseCase\Update\UpdateRemindServiceInterface;
use packages\Consumer\UseCase\Validate\ValidateConsumerDataServiceInterface;
use packages\Schedule\UseCase\Validate\ValidateScheduleServiceInterface;
use packages\Worksheet\UseCase\Update\WorksheetUpdateServiceInterface;

class ChangeScheduleController extends Controller
{
    private $atsConsumerId;
    private $CreateUrlOtherWorksheetService;
    private $DeleteScheduleService;
    private $getCsDatas;
    private $GetCsDataService;
    private $GetScheduleService;
    private $GetUserService;
    private $interviewSet;
    private $IsAnswerService;
    private $InsertScheduleService;
    private $MailSendService;
    private $MakeTextService;
    private $nowCarbon;
    private $remindSet;
    private $RegisterRemindService;
    private $ResendUpdateService;
    private $SmsSendService;
    private $SetScheduleService;
    private $user;
    private $UpdateRemindService;
    private $UpdateConsumerDataService;
    private $ValidateConsumerDataService;
    private $ValidateScheduleService;
    private $WorksheetUpdateService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        CreateUrlOtherWorksheetServiceInterface $CreateUrlOtherWorksheetService,
        DeleteScheduleServiceInterface          $DeleteScheduleService,
        GetCsDataServiceInterface               $GetCsDataService,
        GetScheduleServiceInterface             $GetScheduleService,
        GetUserServiceInterface                 $GetUserService,
        IsAnswerServiceInterface                $IsAnswerService,
        InsertScheduleServiceInterface          $InsertScheduleService,
        MailSendServiceInterface                $MailSendService,
        MakeTextServiceInterface                $MakeTextService,
        RegisterRemindServiceInterface          $RegisterRemindService,
        ResendUpdateServiceInterface            $ResendUpdateService,
        SmsSendServiceInterface                 $SmsSendService,
        SetScheduleServiceInterface             $SetScheduleService,
        UpdateConsumerDataServiceInterface      $UpdateConsumerDataService,
        UpdateRemindServiceInterface            $UpdateRemindService,
        ValidateConsumerDataServiceInterface    $ValidateConsumerDataService,
        ValidateScheduleServiceInterface        $ValidateScheduleService,
        WorksheetUpdateServiceInterface         $WorksheetUpdateService
    )
    {
        $nowStamp = Carbon::now('Asia/Tokyo');
        $this->nowCarbon = Carbon::parse($nowStamp);
        $this->middleware('auth:api');
        $this->CreateUrlOtherWorksheetService = $CreateUrlOtherWorksheetService;
        $this->DeleteScheduleService          = $DeleteScheduleService;
        $this->GetCsDataService               = $GetCsDataService;
        $this->GetScheduleService             = $GetScheduleService;
        $this->GetUserService                 = $GetUserService;
        $this->IsAnswerService                = $IsAnswerService;
        $this->InsertScheduleService          = $InsertScheduleService;
        $this->MailSendService                = $MailSendService;
        $this->MakeTextService                = $MakeTextService;
        $this->RegisterRemindService          = $RegisterRemindService;
        $this->ResendUpdateService            = $ResendUpdateService;
        $this->SmsSendService                 = $SmsSendService;
        $this->SetScheduleService             = $SetScheduleService;
        $this->UpdateConsumerDataService      = $UpdateConsumerDataService;
        $this->UpdateRemindService            = $UpdateRemindService;
        $this->ValidateConsumerDataService    = $ValidateConsumerDataService;
        $this->ValidateScheduleService        = $ValidateScheduleService;
        $this->WorksheetUpdateService         = $WorksheetUpdateService;
    }

    public function store(Request $request)
    {
        \Log::info('ChangeScheduleController');
        \Log::info($request);
        $atsConsumerId               = urldecode($request->get('id'));
        $this->atsConsumerId         = strval($atsConsumerId);
        $this->remindSet             = $request->get('remindSet', null);
        $this->interviewSet          = $request->get('interviewSet');
        $this->interviewDecisionDate = $request->get('interview_decision_date');
        $this->user                  = Auth::user();

        $this->consumerData          = $this->GetCsDataService->getConsumerData('ats_consumer_id', $this->atsConsumerId, false);

        $response = [
            'error'  => 'filled',
            'status' => '500'
        ];
        $isError = $this->setSchedule();
        if ($isError) {
            return $response;
        }
        $this->setRemind();

        if($this->interviewSet == '面接キャンセル'){
            $whereData = ['ats_consumer_id' => $this->atsConsumerId];
            $consumerData = array(
                'interview_decision_date' => null,
                'consumer_status'         => '面接キャンセル'
            );
            $this->UpdateConsumerDataService->updateCsData($whereData, $consumerData);
            \Log::info('面接キャンセル');
        }else{

            $this->setInterviewDecisionDate();

            $isAnswer = $this->IsAnswerService->checkAnswer($this->atsConsumerId);
            if ($isAnswer) {
                $this->WorksheetUpdateService->updateWorksheet(
                    ['ats_consumer_id' => $this->atsConsumerId],
                    ['ws_answers'      => json_encode([])]
                );
            }

            $csUrl = $this->CreateUrlOtherWorksheetService->makeUrl(
                'cs-status-page',
                ['hashCs' => $this->atsConsumerId],
                [
                    'consumerId' => $this->consumerData['id'],
                    'csStatus'   => 'decide'
                ]
            );

            $smsText    = $this->MakeTextService->makeSendSmsText( 'decideInterview', $csUrl);
            $mailData   = $this->MakeTextService->makeSendMailText('decideInterview', $csUrl);

            $isValidList = $this->ValidateConsumerDataService->getIsValidList([
                'tel'  => $this->consumerData['tel'],
                'mail' => $this->consumerData['mail']
            ]);

            if($isValidList['tel']) {
                $this->SmsSendService->sendSms(
                    $smsText,
                    $this->consumerData['tel'],
                    $this->atsConsumerId
                );
     
                $this->ResendUpdateService->updateResendData(['ats_consumer_id' => $this->atsConsumerId],$smsText);
            }

            if($isValidList['mail']) {
                $this->MailSendService->sendMail(
                    [$this->consumerData['mail']],
                    [],
                    $mailData['subject'],
                    $mailData['mainText'],
                    $this->atsConsumerId
                );
            }

            $cpUrl = $this->CreateUrlOtherWorksheetService->makeUrl(
                'company-status-page',
                [    'hashCs'    => $this->atsConsumerId],
                [
                    'consumerId' => $this->consumerData['id'],
                    'status'     => 'decideForCompany'
                ]
            );
              
            $mailDataForCp = $this->MakeTextService->makeSendMailText('decideForCompany', $cpUrl);
            $userMail      = $this->GetUserService->getUserValById($this->consumerData['user_id'], 'mail');

            $this->MailSendService->sendToCompanyMail(
                $mailDataForCp['subject'],
                $mailDataForCp['mainText'],
                $atsConsumerId,
                $this->consumerData['user_id'],
                [$userMail]
            );
        }

        $response['error']  = '';
        $response['status'] = '200';
        return $response;
    }

    private function setSchedule()
    {
        $isError = false;
        //現状埋めているスケジュールのIDを取得
        $scheduleDatas = $this->GetScheduleService->getScheduleDataByWhere(['ats_consumer_id' => $this->atsConsumerId]);
        if ($this->interviewSet != '面接キャンセル') {
            /*
            //選んだ日程が空いているか確認
            $isExistFreeSchedule = $this->ValidateScheduleService->existSchedule(
                $this->interviewSet,
                0,
                $this->consumerData['user_id']
            );
            if ($isExistFreeSchedule) {
                //新しい日程埋める
                $updateData = [
                    'ats_consumer_id' => $this->atsConsumerId,
                    'is_filled' => 1
                ];
                $this->SetScheduleService->updateSchedule(
                    [
                        'schedule' => $this->interviewSet,
                        'is_filled' => 0,
                        'user_id' => $this->consumerData['user_id']
                    ],
                    $updateData
                );
            */
            //枠が埋まっているか調べる
            $isExistFreeSchedule = $this->ValidateScheduleService->existSchedule(
                $this->interviewSet,
                1,
                $this->consumerData['user_id']
            );
            if (!$isExistFreeSchedule) {
                $insertData = [
                    'schedule'        => $this->interviewSet,
                    'ats_consumer_id' => $this->atsConsumerId,
                    'is_filled'       => 1,
                    'user_id'         => $this->consumerData['user_id'],
                    'created_at'      => $this->nowCarbon
                ];
                $this->InsertScheduleService->insertScheduleData($insertData);
            } else {
                \Log::info('日程空き無し');
                $isError = true;
                return $isError;
            }
        }
        //元のスケジュール空ける
        if (!$scheduleDatas->isEmpty()) {
            $updateDataToFree = [
                'ats_consumer_id' => null,
                'is_filled'       => 0
            ];
            $this->SetScheduleService->updateSchedule(
                ['id' => $scheduleDatas[0]->id],
                $updateDataToFree
            );
        }
        return $isError;
    }

    private function setRemind()
    {
        $consumerId       = $this->consumerData['id'];
        $sendTime         = new Carbon('2020-01-01 00:00:00');
        $updateRemindData = ['send_time' => $sendTime];
        
        $this->UpdateRemindService->updateRemind($this->atsConsumerId, $updateRemindData);
        if ($this->interviewSet != '面接キャンセル' && !is_null($this->remindSet) && $this->remindSet == 1) {
           
            $csRemindUrl = $this->CreateUrlOtherWorksheetService->makeUrl(
                'cs-status-page',
                ['hashCs' => $this->atsConsumerId],
                [
                    'consumerId' => $consumerId,
                    'csStatus'   => 'remind'
                ]
            );
            
            $remindSmsText    = $this->MakeTextService->makeSendSmsText( 'remind', $csRemindUrl);
            $remindMailData   = $this->MakeTextService->makeSendMailText('remind', $csRemindUrl);

            $this->RegisterRemindService->registerRemind(
                $this->atsConsumerId,
                $remindSmsText,
                $remindMailData['subject'],
                $remindMailData['mainText'],
                $csRemindUrl,
                'remind',
                $this->interviewSet);
        }
    }

    private function setInterviewDecisionDate()
    {
        $whereData      = ['ats_consumer_id' => $this->atsConsumerId];
 
        $consumerData = array(
            'interview_decision_date' => $this->interviewDecisionDate,
            'consumer_status'         => '面接日程調整済み'
        );
        $this->UpdateConsumerDataService->updateCsData($whereData, $consumerData);
        \Log::info('応募者詳細からの操作で面接決定日を更新');
    }
}





