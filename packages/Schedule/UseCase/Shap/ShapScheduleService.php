<?php
declare(strict_types=1);
namespace packages\Schedule\UseCase\Shap;

use Illuminate\Support\Carbon;
use packages\Schedule\Domain\Shap\ForAdminPageService;
use packages\Schedule\Domain\Shap\CutScheduleService;
use packages\Schedule\Infrastructure\Schedule\ScheduleRepositoryInterface;
use packages\Schedule\Domain\Shap\ShapForUpdateScheduleService;

class ShapScheduleService implements ShapScheduleServiceInterface
{
    private $fromDate;
    private $ForAdminPageService;
    private $CutScheduleService;
    private $shapForUpdateScheduleService;
    private $ScheduleRepository;

    public function __construct(
        ForAdminPageService          $ForAdminPageService,
        CutScheduleService           $CutScheduleService,
        shapForUpdateScheduleService $shapForUpdateScheduleService,
        ScheduleRepositoryInterface  $ScheduleRepository
    )
    {
        $nowStamp                           = Carbon::now('Asia/Tokyo');
        $this->fromDate                     = $nowStamp->format('Y-m-d 00:00:00');
        $this->ForAdminPageService          = $ForAdminPageService;
        $this->CutScheduleService           = $CutScheduleService;
        $this->shapForUpdateScheduleService = $shapForUpdateScheduleService;
        $this->ScheduleRepository           = $ScheduleRepository;
    }

    public function getScheduleDataForUpdate($ableSchedules, $userId)
    {
        $nowScheduleDatas = $this->ScheduleRepository->getScheduleData($this->fromDate, null, null, [$userId]);
        $scheduleCounts   = $this->shapForUpdateScheduleService->scheduleCounts($nowScheduleDatas);
        $cutSchedules     = $this->CutScheduleService->cutScheduleData($ableSchedules);
        //$addSchedulesは削除するスケジュールを成形するための配列
        [$insertDatas, $addSchedules] = $this->shapForUpdateScheduleService->shapScheduleForAdd(
            $cutSchedules,
            $scheduleCounts,
            $userId
        );
        $nowFreeScheduleDatas = $this->ScheduleRepository->getScheduleData($this->fromDate, null, 0, [$userId]);
        $deleteScheduleIds    = $this->shapForUpdateScheduleService->getDeleteIds($nowFreeScheduleDatas, $addSchedules);
        return [$insertDatas, $deleteScheduleIds];
    }


    public function scheduleForAdminPage($userId)
    {
        $nowScheduleDatas = $this->ScheduleRepository->getScheduleData($this->fromDate, null, null, [$userId]);
        $shapForAdminPage = $this->ForAdminPageService->shapForAdminPage($nowScheduleDatas);
        return $shapForAdminPage;
    }
}




