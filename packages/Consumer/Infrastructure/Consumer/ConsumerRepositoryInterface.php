<?php
declare(strict_types=1);

namespace packages\Consumer\Infrastructure\Consumer;

interface ConsumerRepositoryInterface
{
    public function insertCs($insertData);
    public function isExistConsumer($checkCol, $checkVal, $whereDate = NULL, $isSentAutoMsg = NULL);
    public function getConsumerDataObj($whereCol, $whereVal);
    public function getConsumerDataByArray($csAtsIds, $getCol);
    public function getPageCsData(
        $searchData,
        $searchKeys,
        $checkflag,
        $userId
    );
    public function updateCsData($whereData, $updateData);
    public function getInEntryDateCs($startDate, $endDate, $checkItems, $checkNum);
    public function updateAppMedia($csIds, $updateDatas);
    public function getCsDataById($csId, $column);
}
