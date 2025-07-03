<?php
declare(strict_types=1);
namespace packages\Schedule\UseCase\Update;

interface SetScheduleServiceInterface
{
    public function updateSchedule($whereKeys, $updateData);
}

