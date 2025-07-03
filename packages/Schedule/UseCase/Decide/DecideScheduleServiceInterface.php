<?php
declare(strict_types=1);
namespace packages\Schedule\UseCase\Decide;

interface DecideScheduleServiceInterface
{
    // public function decideSchedule($schedule, $atsCsId);
    public function decideSchedule($schedule, $atsCsId, $userId);
}

