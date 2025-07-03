<?php

declare(strict_types=1);

namespace packages\Schedule\UseCase\Validate;

use Log;
use Illuminate\Support\Carbon;
use packages\Schedule\Infrastructure\Schedule\ScheduleRepositoryInterface;

class ValidateScheduleService implements ValidateScheduleServiceInterface
{
    private $ScheduleRepository;

    public function __construct(
        ScheduleRepositoryInterface $ScheduleRepository
    )
    {
        $this->ScheduleRepository = $ScheduleRepository;
    }

    public function existSchedule($schedule, $isFilled, $userId)
    {
        $isExistSchedule = $this->ScheduleRepository->isExsitSchedule(
            $schedule,
            $isFilled,
            $userId
        );
        return $isExistSchedule;
    }
}



