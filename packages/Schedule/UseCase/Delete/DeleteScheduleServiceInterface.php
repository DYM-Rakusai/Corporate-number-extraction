<?php
declare(strict_types=1);
namespace packages\Schedule\UseCase\Delete;

interface DeleteScheduleServiceInterface
{
    public function deleteSchedules($deleteScheduleIds, $userId);
    public function pastFreeSchedule($pastPeriod);
    public function deleteFreeSchedule();
}

