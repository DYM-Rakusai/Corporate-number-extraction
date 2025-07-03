<?php
declare(strict_types=1);
namespace packages\Schedule\Domain\Shap;

use Illuminate\Support\Carbon;

class ShapForWorksheetService
{

    public function __construct(
    )
    {
    }

    public function shapScheduleData($scheduleObjs)
    {
        $scheduleDatas  = [];
        $checkSchedules = [];
        foreach($scheduleObjs as $scheduleObj) {
            if (in_array($scheduleObj->schedule, $checkSchedules, true)) {
                continue;
            }
            $checkSchedules[] = $scheduleObj->schedule;
            $carbonObj        = new Carbon($scheduleObj->schedule);
            $scheduleStr      = $carbonObj->format('Y-m-d H:i');
            $scheduleData     = [
                'scheduleDate' => $scheduleObj->schedule,
                'scheduleStr'  => $scheduleStr
            ];
            $scheduleDatas[] = $scheduleData;
        }
        return $scheduleDatas;
    }
}




