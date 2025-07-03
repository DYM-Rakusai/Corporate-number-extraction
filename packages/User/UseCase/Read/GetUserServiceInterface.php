<?php
declare(strict_types=1);
namespace packages\User\UseCase\Read;

interface GetUserServiceInterface
{
    public function getUserValById($userId, $colName);
    public function getUserIdsByJob($jobName);
    public function getUserAllData();
    public function getUserDataByVal($columnVal, $column);
    public function getUserPageData($searchDatas, $checkflag, $userId);
    public function getUserData($whereCol, $whereVal);
    public function getUserDataWithKeyValue($valueColumn, $keyColumn);
}
