<?php
declare(strict_types=1);

namespace packages\Schedule\Infrastructure\Schedule;

interface ScheduleRepositoryInterface
{
    public function getScheduleData(
        $fromDate = null,
        $toDate   = null,
        $isFilled = null,
        $userIds  = []
    );
    public function getScheduleDataWhereInfos($whereInfos);
    public function isExsitSchedule($schedule, $isFilled, $userId);
    public function updateSchedule($whereKeys, $updateData);
    public function getDecideDate($atsCsId);
    public function insertSchedule($insertData);
    public function deleteSchedules($deleteScheduleIds, $userId);
    public function deletePastFreeSchedule($pastPeriod);
    public function deleteFreeSchedule();
    public function getSchedule($atsCsIds);
    public function getScheduleInPeriod($startDate, $endDate, $isFilled, $column, $value);
    public function getSpecificScheduleByUser($schedule, $userId);
}

