<?php
declare(strict_types=1);
namespace packages\Schedule\Domain\Shap;

use Illuminate\Support\Carbon;

class ShapForUpdateScheduleService
{
    private $nowCarbon;

    public function __construct(
    )
    {
        $nowStamp        = Carbon::now('Asia/Tokyo');
        $this->nowCarbon = Carbon::parse($nowStamp);
    }

    public function scheduleCounts($dbScheduleDatas)
    {
        $scheduleCounts = [];
        foreach($dbScheduleDatas as $dbScheduleData) {
            $schedule       = $dbScheduleData->schedule;
            $scheduleCounts = $this->countSchedule($scheduleCounts, $schedule, 1);
        }
        return $scheduleCounts;
    }

    public function shapScheduleForAdd(
        $cutSchedules,
        $scheduleCounts,
        $userId)
    {
        $shapCounts   = [];
        $insertDatas  = [];
        $addSchedules = [];
        foreach($cutSchedules as $cutSchedule) {
            $scheduleCount = $scheduleCounts[$cutSchedule] ?? 0;
            $shapCount     = $shapCounts[$cutSchedule] ?? 0;
            $checkCount    = $scheduleCount + $shapCount;
            if($checkCount == 0) {
                $shapCounts    = $this->countSchedule($shapCounts, $cutSchedule, 1);
                $insertData    = $this->insertData($cutSchedule, $userId);
                $insertDatas[] = $insertData;
            }
            if(!in_array($cutSchedule, $addSchedules, true)) {
                $addSchedules[] = $cutSchedule;
            }
        }
        return [$insertDatas, $addSchedules];
    }


    public function getDeleteIds($dbScheduleDatas, $addSchedules)
    {
        $deleteIds = [];
        foreach($dbScheduleDatas as $dbScheduleData) {
            if($dbScheduleData->is_filled == 0 && !in_array($dbScheduleData->schedule, $addSchedules, true)) {
                $deleteIds[] = $dbScheduleData->id;
            }
        }
        return $deleteIds;
    }

    private function countSchedule($countsData, $keySchedule, $num)
    {
        if(array_key_exists($keySchedule, $countsData)) {
            $countsData[$keySchedule] = $countsData[$keySchedule] + $num;
        } else {
            $countsData[$keySchedule] = $num;
        }
        return $countsData;
    }

    private function insertData($schedule, $userId)
    {
        $insertData = [
            'schedule'        => $schedule,
            'ats_consumer_id' => NULL,
            'is_filled'       => 0,
            'user_id'         => $userId,
            'created_at'      => $this->nowCarbon
        ];
        return $insertData;
    }
}




