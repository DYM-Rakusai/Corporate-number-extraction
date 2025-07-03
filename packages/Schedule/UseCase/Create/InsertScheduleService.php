<?php
declare(strict_types=1);
namespace packages\Schedule\UseCase\Create;

use Log;
use Illuminate\Support\Carbon;
use packages\Schedule\Infrastructure\Schedule\ScheduleRepositoryInterface;

class InsertScheduleService implements InsertScheduleServiceInterface
{
    private $ScheduleRepository;

    public function __construct(
        ScheduleRepositoryInterface $ScheduleRepository
    )
    {
        $this->ScheduleRepository = $ScheduleRepository;
    }

    public function insertScheduleData($insertData)
    {
        $this->ScheduleRepository->insertSchedule($insertData);
    }
}



