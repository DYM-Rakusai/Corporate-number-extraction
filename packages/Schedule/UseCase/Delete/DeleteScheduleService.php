<?php
declare(strict_types=1);
namespace packages\Schedule\UseCase\Delete;

use Log;
use Illuminate\Support\Carbon;
use packages\Schedule\Infrastructure\Schedule\ScheduleRepositoryInterface;

class DeleteScheduleService implements DeleteScheduleServiceInterface
{
    private $ScheduleRepository;

    public function __construct(
        ScheduleRepositoryInterface $ScheduleRepository
    )
    {
        $this->ScheduleRepository = $ScheduleRepository;
    }

    public function deleteSchedules($deleteScheduleIds, $userId)
    {
        $this->ScheduleRepository->deleteSchedules($deleteScheduleIds, $userId);
    }

    public function pastFreeSchedule($pastPeriod)
    {
        $this->ScheduleRepository->deletePastFreeSchedule($pastPeriod);
    }

    public function deleteFreeSchedule()
    {
        $this->ScheduleRepository->deleteFreeSchedule();
    }
}



