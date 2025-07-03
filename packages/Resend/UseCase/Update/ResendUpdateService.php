<?php
declare(strict_types=1);
namespace packages\Resend\UseCase\Update;

use Log;
use packages\Resend\Infrastructure\Resend\ResendRepositoryInterface;
use Illuminate\Support\Carbon;

class ResendUpdateService implements ResendUpdateServiceInterface
{
    private $currentDateTime;
    private $nextSendTime;
    private $resendCount;
    private $resendAddHour;
    private $resendAddHour2;
    private $ResendRepository;

    public function __construct(
        ResendRepositoryInterface $ResendRepository
    )
    {
        $this->ResendRepository = $ResendRepository;
        $this->resendAddHour    = config('Resend.resendConf.resendAddHour');
        $this->resendAddHour2   = config('Resend.resendConf.resendAddHour2');
        $this->resendCount      = config('Resend.resendConf.resendCount');

        $this->currentDateTime  = Carbon::now('Asia/Tokyo');

        $resendDate = $this->currentDateTime->copy()->addHours($this->resendAddHour);

        if ($resendDate->hour >= 22 || $resendDate->hour <= 9) {
            $resendDate->addHours($this->resendAddHour2);
        }

        $this->nextSendTime = $resendDate->format('Y-m-d H:i:00');
    }

    public function updateResendData($whereKeys, $smsText)
    {
        $updateData = [
            'send_time'    => $this->nextSendTime,
            'send_text'    => $smsText,
            'send_count'   => $this->resendCount,
            'confirm_time' => null
        ];
        $this->ResendRepository->updateResend($whereKeys, $updateData);
    }

    public function updateResendAfterDo($atsConsumerId, $nowSendTimes)
    {
        if($nowSendTimes < 3) {
            $whereKeys = ['ats_consumer_id' => $atsConsumerId];
            $updateData = [
                'send_count' => $nowSendTimes + 1,
                'send_time'  => $this->nextSendTime
            ];
            $this->ResendRepository->updateResend($whereKeys, $updateData);
        }
    }

    public function updateConfirmTime($atsConsumerId)
    {
        $whereKeys = [
            'ats_consumer_id' => $atsConsumerId,
            'confirm_time'    => NULL
        ];
        $updateData = ['confirm_time' => $this->currentDateTime];
        
        $this->ResendRepository->updateResend($whereKeys, $updateData);
    }
}



