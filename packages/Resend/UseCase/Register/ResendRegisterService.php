<?php
declare(strict_types=1);
namespace packages\Resend\UseCase\Register;

use Log;
use Illuminate\Support\Carbon;
use packages\Resend\Infrastructure\Resend\ResendRepositoryInterface;

class ResendRegisterService implements ResendRegisterServiceInterface
{
    private $nowDate;
    private $nextSendTime;
    private $resendAddHour;
    private $resendAddHour2;
    private $ResendRepository;


    public function __construct(
        ResendRepositoryInterface $ResendRepository
    ) {
        $this->resendAddHour    = config('Resend.resendConf.resendAddHour');
        $this->resendAddHour2   = config('Resend.resendConf.resendAddHour2');
        $this->ResendRepository = $ResendRepository;

        // 現在時刻取得
        $nowCarbon  = Carbon::now('Asia/Tokyo');
        $resendDate = $nowCarbon->copy()->addHours($this->resendAddHour);

        // 22時以降または9時以前の場合は追加で加算
        if (22 <= $resendDate->hour || $resendDate->hour <= 9) {
            $resendDate = $resendDate->addHours($this->resendAddHour2);
        }
        $this->nextSendTime = $resendDate->format('Y-m-d H:i:00');
    }

    public function insertResendData(
        $mailText,
        $url,
        $atsCsId
    ) {
        $insertData = [
            'send_text'       => $mailText,
            'ats_consumer_id' => $atsCsId,
            'send_time'       => $this->nextSendTime,
            'confirm_time'    => null,
            'created_at'      => $nowCarbon
        ];
        $this->ResendRepository->insertResend($insertData);
    }
}


