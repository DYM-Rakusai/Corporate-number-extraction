<?php
declare(strict_types=1);
namespace packages\Sms\UseCase\Send;

use Log;
use Illuminate\Support\Carbon;
use packages\Sms\Domain\SendSmsService;
use packages\Sms\Infrastructure\SmsMessage\SmsMessageRepositoryInterface;

class SmsSendService implements SmsSendServiceInterface
{
    private $nowCarbon;
    private $SendSmsService;
    private $SmsMessageRepository;

    public function __construct(
        SendSmsService                $SendSmsService,
        SmsMessageRepositoryInterface $SmsMessageRepository
    ) {
        $this->SendSmsService       = $SendSmsService;
        $this->SmsMessageRepository = $SmsMessageRepository;
        $this->nowCarbon            = Carbon::now('Asia/Tokyo');
    }

    public function sendSms(
        $smsText,
        $csTel,
        $atsConsumerId
    ) {
        $fromName = config('app.company_sms_name');
        $this->SendSmsService->awsSmsInvoke($smsText, $csTel, $fromName);

        $insertData = [
            'ats_consumer_id' => $atsConsumerId,
            'message'         => $smsText,
            'created_at'      => $this->nowCarbon
        ];
        $this->SmsMessageRepository->insertSmsMessage($insertData);
    }
}


