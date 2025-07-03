<?php

declare(strict_types=1);

namespace packages\User\Infrastructure\User;

use App\User;
use packages\User\Infrastructure\User\ForSearch\UserSearchQuery;

class UserRepository implements UserRepositoryInterface
{
    public function __construct()
    {
    }

    public function getUserData($userId, $colName)
    {
        $userData = User::where('id', $userId)->value($colName);
        return $userData;
    }

    public function getUserIdsByJob($entryJob)
    {
        $userIds = User::where('target_jobs', 'LIKE', '%' . $entryJob . '%')->pluck('id')->toArray();
        return $userIds;
    }

    public function getUserAllData()
    {
        $storeDataArray = User::get();
        return $storeDataArray;
    }

    public function updateUserSelectedColumn($updateData, $userId)
    {
        User::where('id', $userId)
            ->update($updateData);
    }

    public function getUserDataByVal($value, $column)
    {
        $userData = User::where($column, $value)
            ->get();
        return $userData;
    }

    public function getPageUserData($searchDatas, $searchKeys, $checkflag, $userId) 
    {
        $userQuery          = User::query();
        $UserSearchQueryObj = new UserSearchQuery($userQuery);
        $UserSearchQueryObj->searchParamsByArray($searchKeys, $searchDatas, $userId);

        if ($checkflag != true) {
            $userIdData = $UserSearchQueryObj->userQuery->get()->toArray();
            return $userIdData;
        }
        $userPageData = $UserSearchQueryObj->userQuery->paginate(30);
        
        return $userPageData;
    }

    public function getUserDataObj($whereCol, $whereVal)
    {
        $userQuery = User::query();
        $userQuery->where($whereCol, '=', $whereVal);
        $userDataObj = $userQuery->get();
        return $userDataObj;
    }

    public function updateUserData($whereData, $updateData)
    {
        if(empty($whereData)) {
            \Log::error('whereのキーが不正のためupdateしない');
            return;
        }
        $userQuery = User::query();
        foreach($whereData as $whereKey => $whereVal) {
            $userQuery->where($whereKey, '=', $whereVal);
        }
        $userQuery->update($updateData);
    }

    public function insertUser($insertData)
    {
        User::insert($insertData);
    }

     public function getUserDataWithKeyValue($valueColumn, $keyColumn)
    {
        return User::pluck($valueColumn, $keyColumn)->toArray();
    }
}
