<?php
declare(strict_types=1);
namespace packages\Schedule\UseCase\Read;

use Log;
use Illuminate\Support\Carbon;
use packages\Schedule\Domain\Get\GetAnotherScheduleService;
use packages\Schedule\Infrastructure\Schedule\ScheduleRepositoryInterface;
use packages\Schedule\Domain\Shap\ShapForWorksheetService;

class GetScheduleService implements GetScheduleServiceInterface
{
    private $adjustFromDate;
    private $adjustToDate;
    private $dayList;
    private $freeFromDate;
    private $fromDate;
    private $toDate;
    private $GetAnotherScheduleService;
    private $isFetchedByAtsStoreId;
    private $isFetchedByUserId;
    private $ScheduleRepository;
    private $ShapForWorksheetService;


    public function __construct(
        GetAnotherScheduleService   $GetAnotherScheduleService,
        ShapForWorksheetService     $ShapForWorksheetService,
        ScheduleRepositoryInterface $ScheduleRepository
    )
    {
        $nowCarbon            = Carbon::now('Asia/Tokyo');
        $this->adjustFromDate = $nowCarbon->copy()->addDays(1);
        $this->adjustToDate   = $nowCarbon->copy()->addDays(16);
        $this->fromDate       = $nowCarbon->copy()->addDays(2)->format('Y-m-d 00:00:00');
        $this->freeFromDate   = $nowCarbon->format('Y-m-d H:i:s');
        $this->toDate         = $nowCarbon->copy()->addDays(5)->format('Y-m-d 23:59:59');
        
        $this->GetAnotherScheduleService = $GetAnotherScheduleService;
        $this->ScheduleRepository        = $ScheduleRepository;
        $this->ShapForWorksheetService   = $ShapForWorksheetService;

        
        $this->dayList = [
            '日',
            '月',
            '火',
            '水',
            '木',
            '金',
            '土'
        ];
        $this->isFetchedByUserId     = config('Schedule.scheduleConf.isFetchedByUserId');
        $this->isFetchedByAtsStoreId = config('Schedule.scheduleConf.isFetchedByAtsStoreId');
    }

    public function getFreeScheduleDatas($isFilled, $userId)
    {
        $scheduleDataObjs = $this->ScheduleRepository->getScheduleData(
            $this->freeFromDate,
            $this->toDate,
            $isFilled,
            [$userId]
        );
        $shapScheduleDatas = $this->ShapForWorksheetService->shapScheduleData($scheduleDataObjs);
        return $shapScheduleDatas;
    }

    public function getDecideSchedule($atsCsId, $format = 'None')
    {
        $scheduleDate = $this->ScheduleRepository->getDecideDate($atsCsId);
        $scheduleDateStr = '';
        if($scheduleDate == '') {
            return $scheduleDateStr;
        }
        if($format == 'japanese') {
            $scheduleCarbon   = new Carbon($scheduleDate);
            $scheduleDateStr .= $scheduleCarbon->format('Y/m/d');
            $scheduleDateStr .= '（' . $this->dayList[$scheduleCarbon->dayOfWeek] . '） ';
            $scheduleDateStr .= $scheduleCarbon->format('H:i');
        } else {
            $scheduleDateStr = $scheduleDate;
        }
        return $scheduleDateStr;
    }

    public function getAnotherSchedules()
    {
        $anotherSchedules = $this->GetAnotherScheduleService->getAnotherDates($this->adjustFromDate, $this->adjustToDate);
        $startSelectTimeArray = [
            '09:00', '10:00', '11:00',
            '12:00', '13:00', '14:00',
            '15:00', '16:00', 
        ];
        $endSelectTimeArray = [
            '10:00', '11:00', '12:00',
            '13:00', '14:00', '15:00',
            '16:00', '17:00', 
        ];
        return [$anotherSchedules, $startSelectTimeArray, $endSelectTimeArray];
    }

    public function getScheduleDataByWhere($whereInfos)
    {
        $scheduleData = $this->ScheduleRepository->getScheduleDataWhereInfos($whereInfos);
        return $scheduleData;
    }

    public function getScheduleDataByArray($atsCsIds)
    {
        $scheduleArray = $this->ScheduleRepository->getSchedule($atsCsIds);
        return $scheduleArray;
    }

    public function getDataInSchedule($startDate, $endDate, $isFilled, $userId, $atsStoreId)
    {
        $column = null;
        $value  = null;
        if ($this->isFetchedByUserId) {
            $column = 'user_id';
            $value  = $userId;
        } elseif ($this->isFetchedByAtsStoreId) {
            $column = 'ats_store_id';
            $value  = $atsStoreId;
        }
        $scheduleData = $this->ScheduleRepository->getScheduleInPeriod($startDate, $endDate, $isFilled, $column, $value);
        return $scheduleData;
    }

}



