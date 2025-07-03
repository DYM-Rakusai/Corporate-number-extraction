<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use packages\Consumer\UseCase\Read\GetCsDataServiceInterface;
use packages\Resend\UseCase\Read\GetResendServiceInterface;
use packages\Text\UseCase\Make\MakeTextServiceInterface;
use packages\Resend\UseCase\Update\ResendUpdateServiceInterface;
use packages\Sms\UseCase\Send\SmsSendServiceInterface;
use packages\Consumer\UseCase\Validate\ValidateConsumerDataServiceInterface;

class Resend extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature   = 'command:resendSms {nowDate?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'SMSを再送信する';

    private $GetCsDataService;
    private $GetResendService;
    private $MakeTextService;
    private $ResendUpdateService;
    private $SmsSendService;
    private $ValidateConsumerDataService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        GetCsDataServiceInterface            $GetCsDataService,
        GetResendServiceInterface            $GetResendService,
        MakeTextServiceInterface             $MakeTextService,
        ResendUpdateServiceInterface         $ResendUpdateService,
        SmsSendServiceInterface              $SmsSendService,
        ValidateConsumerDataServiceInterface $ValidateConsumerDataService
    )
    {
        $this->GetCsDataService            = $GetCsDataService;
        $this->ValidateConsumerDataService = $ValidateConsumerDataService;
        $this->ResendUpdateService         = $ResendUpdateService;
        $this->SmsSendService              = $SmsSendService;
        $this->GetResendService            = $GetResendService;
        $this->MakeTextService             = $MakeTextService;
        parent::__construct();
    }

    public function handle()
    {
        // リマインドが必要なものを抽出する
        $nowDate      = $this->argument('nowDate');
        $nowCarbon    = new Carbon($nowDate);
        $nowCarbonStr = $nowCarbon->format('Y-m-d H:i');

        $resends      = $this->GetResendService->getResendDatas($nowCarbonStr);
        if($resends->isEmpty()) {
            return;
        }

        foreach ($resends as $resend) {
            $consumerAtsIdArray[] = $resend->ats_consumer_id;
            $sendTextArray[$resend->ats_consumer_id]  = $resend->send_text;
            $sendCountArray[$resend->ats_consumer_id] = $resend->send_count;
        }

        $consumerTels = $this->GetCsDataService->getCsDatas($consumerAtsIdArray, 'tel');

        foreach($resends as $resend) {
            $consumerId  = $resend->ats_consumer_id;
            $consumerTel = $consumerTels[$consumerId];
            $sendCount   = $sendCountArray[$consumerId];
            $isValidList = $this->ValidateConsumerDataService->getIsValidList([
                'tel' => $consumerTel
            ]);
            if($isValidList['tel']) {

                $sendText = $sendTextArray[$consumerId];
         
                $this->SmsSendService->sendSms(
                    $sendText,
                    $consumerTel,
                    $consumerId
                );
                $this->ResendUpdateService->updateResendAfterDo(
                    $consumerId,
                    $sendCount
                );
            }
        }
    }
}


