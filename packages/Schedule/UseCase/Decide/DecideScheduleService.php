<?php
declare(strict_types=1);
namespace packages\Schedule\UseCase\Decide;

use Log;
use Illuminate\Support\Carbon;
use packages\Schedule\Infrastructure\Schedule\ScheduleRepositoryInterface;


class DecideScheduleService implements DecideScheduleServiceInterface
{
    private $nowTimeData;
    private $ScheduleRepository;

    public function __construct(
        ScheduleRepositoryInterface $ScheduleRepository
    )
    {
        $this->ScheduleRepository = $ScheduleRepository;
        $nowCarbon                = Carbon::now('Asia/Tokyo');
        $this->nowTimeData        = $nowCarbon->format('Y-m-d H:i');
    }

    public function decideSchedule($schedule, $atsCsId, $userId)
    {
        // $schedule, $atsCsId, $userIdこれでスケジュール取得
        $scheduleObj = $this->ScheduleRepository->getSpecificScheduleByUser(
            $schedule,
            $userId
        );
        $isSet = false;
        if($scheduleObj->isEmpty()) {
            //insert
            $this->ScheduleRepository->insertSchedule(
                [
                    'schedule'        => $schedule,
                    'ats_consumer_id' => $atsCsId,
                    'is_filled'       => 1,
                    'user_id'         => $userId
                ]
            );
            $isSet = true;
        }
        /*else {
            if($scheduleObj[0]->is_filled == 0) {
                //update
                $this->ScheduleRepository->updateSchedule(
                    [ 'id' => $scheduleObj[0]->id ],
                    [
                        'ats_consumer_id' => $atsCsId,
                        'is_filled' => 1
                    ]
                );
                $isSet = true;
            }
        }*/
        //埋まっている場合は、そのままFalse返す


        return $isSet;
    }
}



