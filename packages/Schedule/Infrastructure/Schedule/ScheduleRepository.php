<?php

declare(strict_types=1);

namespace packages\Schedule\Infrastructure\Schedule;

use App\Model\Schedule;

class ScheduleRepository implements ScheduleRepositoryInterface
{
    public function __construct(
    ) {
    }

    public function getScheduleData(
        $fromDate = null,
        $toDate   = null,
        $isFilled = null,
        $userIds  = []
    ) {
        $scheduleQuery = Schedule::query();
        if (!empty($fromDate)) {
            $scheduleQuery = $scheduleQuery->where('schedule', '>=', $fromDate);
        }
        if (!empty($toDate)) {
            $scheduleQuery = $scheduleQuery->where('schedule', '<=', $toDate);
        }
        if (!is_null($isFilled)) {
            $scheduleQuery = $scheduleQuery->where('is_filled', '=', $isFilled);
        }
        $scheduleQuery = $scheduleQuery->whereIn('user_id', $userIds);
        $scheduleQuery->orderBy('schedule', 'asc');
        $scheduleData = $scheduleQuery->get();
        return $scheduleData;
    }
    
    public function getScheduleDataWhereInfos($whereInfos)
    {
        $scheduleQuery = Schedule::query();
        foreach ($whereInfos as $whereCol => $whereVal) {
            $scheduleQuery->where($whereCol, '=', $whereVal);
        }
        $scheduleObjs = $scheduleQuery->get();
        return $scheduleObjs;
    }

    public function isExsitSchedule($schedule, $isFilled, $userId)
    {
        $isExsitSchedule = Schedule::where('schedule', $schedule)
            ->where('is_filled', $isFilled)
            ->where('user_id', $userId)
            ->exists();
        return $isExsitSchedule;
    }

    public function updateSchedule($whereKeys, $updateData)
    {
        if (empty($whereKeys)) {
            \Log::error('updateScheduleエラー');
            return;
        }
        $scheduleQuery = Schedule::query();
        foreach ($whereKeys as $whereCol => $whereVal) {
            $scheduleQuery->where($whereCol, '=', $whereVal);
        }
        $scheduleQuery->first();
        $scheduleQuery->update($updateData);
    }

    public function getDecideDate($atsCsId)
    {
        $schedule = Schedule::where('is_filled', '=', 1)
            ->where('ats_consumer_id', '=', $atsCsId)
            ->value('schedule');
        return $schedule;
    }

    public function getSchedule($atsCsIds)
    {
        $scheduleArray = Schedule::whereIn('ats_consumer_id', $atsCsIds)
            ->where('is_filled', '=', 1)
            ->pluck('schedule', 'ats_consumer_id')
            ->toArray();
        return $scheduleArray;
    }

    public function insertSchedule($insertData)
    {
        Schedule::insert($insertData);
    }

    public function deleteSchedules($deleteScheduleIds, $userId)
    {
        Schedule::where('is_filled', '=', 0)
            ->whereIn('id', $deleteScheduleIds)
            ->where('user_id', '=', $userId)
            ->delete();
    }

    public function deletePastFreeSchedule($pastPeriod)
    {
        Schedule::where('is_filled', '=', 0)
            ->where('schedule', '<=', $pastPeriod)
            ->delete();
    }

    public function deleteFreeSchedule()
    {
        Schedule::where('is_filled', '=', 0)
            ->delete();
    }

    public function getScheduleInPeriod($startDate, $endDate, $isFilled, $column, $value)
    {
        $scheduleQuery = Schedule::where('is_filled', '=', $isFilled)
            ->where('schedule', '>=', $startDate)
            ->where('schedule', '<=', $endDate);

        if (!empty($column)) {
            $scheduleQuery->where($column, $value);
        }

        $scheduleData = $scheduleQuery->get();
        return $scheduleData;
    }
    
    public function getSpecificScheduleByUser($schedule, $userId)
    {
        $scheduleObj = Schedule::where('schedule', '=', $schedule)
            ->where('user_id', '=', $userId)
            ->get();
        return $scheduleObj;
    }
}
