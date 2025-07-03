<?php
declare(strict_types=1);
namespace packages\Schedule\UseCase\Read;

interface GetScheduleServiceInterface
{
    public function getFreeScheduleDatas($isFilled, $userId);
    public function getDecideSchedule($atsCsId, $format = 'None');
    public function getAnotherSchedules();
    public function getScheduleDataByWhere($whereInfos);
    public function getScheduleDataByArray($atsCsIds);
    public function getDataInSchedule($startDate, $endDate, $isFilled, $userId, $atsStoreId);
 
}

