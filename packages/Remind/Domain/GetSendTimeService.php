<?php
declare(strict_types=1);
namespace packages\Remind\Domain;

use Log;
use Illuminate\Support\Carbon;


class GetSendTimeService
{
    private $resendAddHour;
    private $resendAddHour2;

    public function __construct(
    )
    {
        $this->resendAddHour  = config('Resend.resendConf.resendAddHour');
        $this->resendAddHour2 = config('Resend.resendConf.resendAddHour2');
    }

    public function getNextSendTime($nextStatus, $interviewDate = NULL)
    {
        $nextSendTimeStr = '';
        if($nextStatus == 'failure') {
            $nowStamp     = Carbon::now('Asia/Tokyo');
            $nowCarbon    = Carbon::parse($nowStamp);
            $nextSendTime = $nowCarbon->addHours($this->resendAddHour);

            if(22 <= $nextSendTime->hour || $nextSendTime->hour <= 9) {
                $nextSendTime = $nextSendTime->addHours($this->resendAddHour2);
            }
        } elseif($nextStatus == 'remind') {
            if(!empty($interviewDate)) {
                $interviewDateCarbon = new Carbon($interviewDate);
                $nextSendTime        = $interviewDateCarbon->subHours(24);
            }
        }
        if(!empty($nextSendTime)) {
            $nextSendTimeStr = $nextSendTime->format('Y-m-d H:i:00');
        }
        return $nextSendTimeStr;
    }
}




