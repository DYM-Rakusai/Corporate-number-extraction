<?php
declare(strict_types=1);
namespace packages\Consumer\UseCase\Read;

interface GetCsDataServiceInterface
{
    public function getConsumerData($whereCol, $whereVal);
    public function getCsDatas($csAtsIds, $getCol);
    public function getCsPageData($searchDatas, $checkflag, $userId);
    public function getInEntryDate($startDate, $endDate, $checkItems, $checkNum);
    public function getCsDataById($csId, $column);
}

