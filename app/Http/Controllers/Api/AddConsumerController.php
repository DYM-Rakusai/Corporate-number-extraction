<?php

namespace App\Http\Controllers\Api;

use DateTime;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use packages\Consumer\UseCase\Validate\CheckDuplicateConsumerServiceInterface;
use packages\Consumer\UseCase\Create\CreateConsumerDataServiceInterface;
use packages\Url\UseCase\Create\CreateUrlOtherWorksheetServiceInterface;
use packages\Worksheet\UseCase\Create\InsertWsDataServiceInterface;
use packages\Mail\UseCase\Send\MailSendServiceInterface;
use packages\Text\UseCase\Make\MakeTextServiceInterface;
use packages\Resend\UseCase\Register\ResendRegisterServiceInterface;
use packages\Sms\UseCase\Send\SmsSendServiceInterface;
use packages\Consumer\UseCase\Update\UpdateConsumerDataServiceInterface;
use packages\Consumer\UseCase\Validate\ValidateConsumerDataServiceInterface;

class AddConsumerController extends Controller
{
    private $CheckDuplicateConsumerService;
    private $CreateConsumerDataService;
    private $CreateUrlOtherWorksheetService;
    private $InsertWsDataService;
    private $MailSendService;
    private $MakeTextService;
    private $ResendRegisterService;
    private $SmsSendService;
    private $UpdateConsumerDataService;
    private $ValidateConsumerDataService;    

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        CheckDuplicateConsumerServiceInterface  $CheckDuplicateConsumerService,
        CreateConsumerDataServiceInterface      $CreateConsumerDataService,
        CreateUrlOtherWorksheetServiceInterface $CreateUrlOtherWorksheetService,
        InsertWsDataServiceInterface            $InsertWsDataService,
        MailSendServiceInterface                $MailSendService,
        MakeTextServiceInterface                $MakeTextService,
        ResendRegisterServiceInterface          $ResendRegisterService,
        SmsSendServiceInterface                 $SmsSendService,
        UpdateConsumerDataServiceInterface      $UpdateConsumerDataService,
        ValidateConsumerDataServiceInterface    $ValidateConsumerDataService
    )
    {
        $this->CheckDuplicateConsumerService  = $CheckDuplicateConsumerService;
        $this->CreateConsumerDataService      = $CreateConsumerDataService;
        $this->CreateUrlOtherWorksheetService = $CreateUrlOtherWorksheetService;
        $this->InsertWsDataService            = $InsertWsDataService;
        $this->MailSendService                = $MailSendService;
        $this->MakeTextService                = $MakeTextService;
        $this->ResendRegisterService          = $ResendRegisterService;
        $this->SmsSendService                 = $SmsSendService;
        $this->UpdateConsumerDataService      = $UpdateConsumerDataService;
        $this->ValidateConsumerDataService    = $ValidateConsumerDataService;
    }

    public function store(Request $request)
    {
        \Log::info($request);
        if ($request->get('apiToken') !== config('app.api_token')) {
            throw new \Exception('APIトークンが不正です。');
        }

        $applicantInfo  = $request->get('applicants');
        $atsConsumerId  = $applicantInfo['ats_consumer_id'];
        $isAlreadyExist = $this->CheckDuplicateConsumerService->checkAlreadyExist($atsConsumerId);

        if ($isAlreadyExist) {
            \Log::error('同一ATS IDの応募者を取り込もうとしている。');
            return;
        }

        // 電話番号・メールアドレスの整形
        $csTel  = !empty($applicantInfo['tel'])  ? str_replace('-', '', $applicantInfo['tel']) : '-';
        $csMail = !empty($applicantInfo['mail']) ? $applicantInfo['mail']                      : '-';

        // 有効性チェック
        $isValidList = $this->ValidateConsumerDataService->getIsValidList([
            'tel'  => $csTel,
            'mail' => $csMail
        ]);
        if (!in_array(true, $isValidList, true)) {
            \Log::error('電話番号も、メールアドレスも不正です。');
            return;
        }

        // 応募者の重複チェック
        $isDuplicate = $this->CheckDuplicateConsumerService->checkDuplicateCs($csTel, $csMail);
        $csStatus    = $isDuplicate ? '重複応募'   : '未対応';
        $messageType = $isDuplicate ? 'duplicate' : 'new';

        // 応募者データ登録
        $consumerId = $this->CreateConsumerDataService->insertCsData(
            $atsConsumerId,
            $csTel,
            $csMail,
            $applicantInfo,
            $csStatus
        );
        if ($consumerId == '') {
            \Log::error('マッピングエラー');
            return;
        }

        // ワークシートURL生成・登録（未対応の場合のみ）
        $wsUrl = '';
        if ($csStatus == '未対応') {
            $csStatus = '自動対応中';
            $wsUrl    = $this->CreateUrlOtherWorksheetService->makeUrl(
                'worksheet',
                ['hashCs'     => $atsConsumerId],
                ['consumerId' => $consumerId   ]
            );
            $this->InsertWsDataService->insertWs($atsConsumerId, $wsUrl);
        }

        // 電話番号が有効かつメッセージタイプが指定されている場合、SMS送信
        if ($isValidList['tel'] && !empty($messageType)) {
            $smsText = $this->MakeTextService->makeSendsmsText($messageType, $wsUrl);
            $this->SmsSendService->sendSms($smsText, $csTel, $atsConsumerId);

            // 新規応募かつURLがある場合、再送用データ登録
            if ($messageType == 'new' && !empty($wsUrl)) {
                $this->ResendRegisterService->insertResendData($smsText, $wsUrl, $atsConsumerId);
            }
        }

        // メールアドレスが有効かつメッセージタイプが指定されている場合、メール送信
        if ($isValidList['mail'] && !empty($messageType)) {
            $mailData = $this->MakeTextService->makeSendMailText($messageType, $wsUrl);
            $this->MailSendService->sendMail(
                [$csMail],
                [],
                $mailData['subject'],
                $mailData['mainText'],
                $atsConsumerId
            );
        }

        // 応募者ステータス更新
        $this->UpdateConsumerDataService->updateCsData(
            ['ats_consumer_id' => $atsConsumerId],
            ['consumer_status' => $csStatus     ]
        );

        \Log::info('アンケート送信完了');
    }
}



