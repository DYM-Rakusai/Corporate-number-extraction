<?php
declare(strict_types=1);
namespace packages\Schedule\UseCase\Validate;

interface ValidateScheduleServiceInterface
{
    public function existSchedule($schedule, $isFilled, $userId);
}

