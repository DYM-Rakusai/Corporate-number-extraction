<?php
declare(strict_types=1);
namespace packages\User\Infrastructure\User;

interface UserRepositoryInterface
{
    public function getUserData($userId, $colName);
    public function getUserIdsByJob($entryJob);
    public function getUserAllData();
    public function updateUserSelectedColumn($updateData, $userId);
    public function getUserDataByVal($value, $column);
    public function getPageUserData($searchDatas, $searchKeys, $checkflag, $userId);
    public function getUserDataObj($whereCol, $whereVal);
    public function updateUserData($whereData, $userData);
    public function insertUser($insertData);
    public function getUserDataWithKeyValue($valueColumn, $keyColumn);
}
