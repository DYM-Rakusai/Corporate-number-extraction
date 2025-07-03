<?php
declare(strict_types=1);
namespace packages\Remind\UseCase\Register;

use Log;
use Illuminate\Support\Carbon;
use packages\Remind\Domain\GetSendTimeService;
use packages\Remind\Infrastructure\Remind\RemindRepositoryInterface;

class RegisterRemindService implements RegisterRemindServiceInterface
{
    private $GetSendTimeService;
    private $nowDate;
    private $RemindRepository;

    public function __construct(
        GetSendTimeService        $GetSendTimeService,
        RemindRepositoryInterface $RemindRepository
    )
    {
        $nowStamp                 = Carbon::now('Asia/Tokyo');
        $this->nowDate            = Carbon::parse($nowStamp);
        $this->GetSendTimeService = $GetSendTimeService;
        $this->RemindRepository   = $RemindRepository;
    }

    public function registerRemind(
        $atsConsumerId,
        $smsText,
        $mailTitle,
        $mailText,
        $sendUrl = NULL,
        $nextStatus,
        $interviewDate = NULL)
    {
        $nextSendTime = $this->GetSendTimeService->getNextSendTime($nextStatus, $interviewDate);
        $insertData = [
            'ats_consumer_id' => $atsConsumerId,
            'send_sms_text'   => $smsText,
            'send_mail_title' => $mailTitle,
            'send_mail_text'  => $mailText,
            'url'             => $sendUrl,
            'send_time'       => $nextSendTime,
            'next_status'     => $nextStatus,
            'created_at'      => $this->nowDate
        ];
        $this->RemindRepository->insertRemind($insertData);
    }
}


