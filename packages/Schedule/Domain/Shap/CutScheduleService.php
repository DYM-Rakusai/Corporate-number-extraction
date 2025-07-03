<?php
declare(strict_types=1);
namespace packages\Schedule\Domain\Shap;

use Illuminate\Support\Carbon;

class CutScheduleService
{

    public function __construct(
    )
    {
    }

    public function cutScheduleData($ableSchedules)
    {
        $cutDatas = [];
        if(empty($ableSchedules)) {
            return $cutDatas;
        }
        //['2022/11/09_13:00~14:00', '2022/11/10_13:30~16:30', '2022/11/11_15:00~17:00']
        foreach($ableSchedules as $ableSchedule) {
            // $ableSchedule;
            $ableScheduleDatas = explode('_', $ableSchedule);
            $ableScheduleTimes = explode('~', $ableScheduleDatas[1]);
            $ableScheduleDay   = str_replace('/', '-', $ableScheduleDatas[0]);
            $startDate         = $ableScheduleDay . ' ' . $ableScheduleTimes[0];
            $endDate           = $ableScheduleDay . ' ' . $ableScheduleTimes[1];
            $startCarbon       = new Carbon($startDate);
            $endCarbon         = new Carbon($endDate);
            for($index = 1; $index < 30; $index++) {
                $cutSchedule = $startCarbon->format('Y-m-d H:i:00');
                if(!in_array($cutSchedule, $cutDatas, true)) {
                    $cutDatas[] = $cutSchedule;
                }
                $startCarbon->addMinutes(30);
                if($endCarbon->lte($startCarbon)) {
                    break;
                }
            }
        }
        return $cutDatas;
    }
}




