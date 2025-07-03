<?php

declare(strict_types=1);

namespace packages\Schedule\UseCase\Update;

use Log;
use Illuminate\Support\Carbon;
use packages\Schedule\Infrastructure\Schedule\ScheduleRepositoryInterface;

class SetScheduleService implements SetScheduleServiceInterface
{
    private $ScheduleRepository;
    public function __construct(
        ScheduleRepositoryInterface $ScheduleRepository
    )
    {
        $this->ScheduleRepository = $ScheduleRepository;
    }

    public function updateSchedule($whereKeys, $updateData)
    {
		$this->ScheduleRepository->updateSchedule($whereKeys, $updateData);
    }
}



