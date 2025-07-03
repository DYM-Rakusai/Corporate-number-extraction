<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use packages\Resend\UseCase\Validate\CheckResendServiceInterface;
use packages\Consumer\UseCase\Read\GetCsDataServiceInterface;
use packages\Mail\UseCase\Send\MailSendServiceInterface;
use packages\Remind\UseCase\Read\ReadRemindServiceInterface;
use packages\Resend\UseCase\Update\ResendUpdateServiceInterface;
use packages\Sms\UseCase\Send\SmsSendServiceInterface;
use packages\Consumer\UseCase\Validate\ValidateConsumerDataServiceInterface;

class Remind extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:remind {nowDate?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'リマインドを送信する';

    private $CheckResendService;
    private $GetCsDataService;
    private $MailSendService;
    private $ReadRemindService;
    private $ResendUpdateService;
    private $SmsSendService;
    private $ValidateConsumerDataService;
    
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        CheckResendServiceInterface          $CheckResendService,
        GetCsDataServiceInterface            $GetCsDataService,
        MailSendServiceInterface             $MailSendService,
        ReadRemindServiceInterface           $ReadRemindService,
        ResendUpdateServiceInterface         $ResendUpdateService,
        SmsSendServiceInterface              $SmsSendService,
        ValidateConsumerDataServiceInterface $ValidateConsumerDataService
    )
    {
        $this->CheckResendService          = $CheckResendService;
        $this->GetCsDataService            = $GetCsDataService;
        $this->MailSendService             = $MailSendService;
        $this->ReadRemindService           = $ReadRemindService;
        $this->ResendUpdateService         = $ResendUpdateService;
        $this->SmsSendService              = $SmsSendService;
        $this->ValidateConsumerDataService = $ValidateConsumerDataService;
        
        parent::__construct();
    }

    public function handle()
    {
        $nowDate      = $this->argument('nowDate');
        $nowCarbon    = new Carbon($nowDate);
        $nowCarbonStr = $nowCarbon->format('Y-m-d H:i');

        $reminds      = $this->ReadRemindService->getRemindTargetData($nowCarbonStr);
        if($reminds->isEmpty()) {
            return;
        }

        // 送信対象の取得
        $consumerIdArray = [];
        foreach ($reminds as $remind) {
            $consumerAtsIdArray[] = $remind->ats_consumer_id;
        }
        $consumerTels  = $this->GetCsDataService->getCsDatas($consumerAtsIdArray, 'tel');
        $consumerMails = $this->GetCsDataService->getCsDatas($consumerAtsIdArray, 'mail');

        foreach($reminds as $remind) {
            $consumerTel  = $consumerTels[$remind->ats_consumer_id];
            $consumerMail = $consumerMails[$remind->ats_consumer_id];
            $isValidList  = $this->ValidateConsumerDataService->getIsValidList([
                'tel'  => $consumerTel,
                'mail' => $consumerMail
            ]);
            if($isValidList['tel']) {
                
                $this->SmsSendService->sendSms(
                    $remind->send_sms_text,
                    $consumerTel,
                    $remind->ats_consumer_id
                );

            }
            if($isValidList['mail']) {
                
                $this->MailSendService->sendMail(
                    [$consumerMail],
                    [],
                    $remind->send_mail_title,
                    $remind->send_mail_text,
                    $remind->ats_consumer_id
                );
            }
        }
    }
}


