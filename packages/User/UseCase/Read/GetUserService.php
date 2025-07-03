<?php
declare(strict_types=1);
namespace packages\User\UseCase\Read;

use Log;
use packages\User\Domain\Get\ShapUserDataService;
use packages\User\Infrastructure\User\UserRepositoryInterface;

class GetUserService implements GetUserServiceInterface
{
    private $UserRepository;
    public function __construct(
        ShapUserDataService     $ShapUserDataService,
        UserRepositoryInterface $UserRepository
    ) {
        $this->UserRepository      = $UserRepository;
        $this->ShapUserDataService = $ShapUserDataService;
    }

    public function getUserValById($userId, $colName)
    {
        $userData = $this->UserRepository->getUserData($userId, $colName);
        return $userData;
    }

    public function getUserIdsByJob($jobName)
    {
        $userIds = $this->UserRepository->getUserIdsByJob($jobName);
        return $userIds;
    }

    public function getUserAllData()
    {
        $userDataArray = $this->UserRepository->getUserAllData();
        return $userDataArray;
    }

    public function getUserDataByVal($name, $column)
    {
        $userData = $this->UserRepository->getUserDataByVal($name, $column);
        return $userData;
    }

    public function getUserPageData($searchDatas, $checkflag, $userId)
    {
        $searchKeys = [
            'user_name' => [
                'db_key'   => 'name',
                'comp_key' => 'partial_name_match'
            ],//部分一致
            'user_tel'  => [
                'db_key'   => 'tel',
                'comp_key' => 'partial_tel_match'
            ], //部分一致
            'user_mail' => [
                'db_key'   => 'mail',
                'comp_key' => 'partial_match'
            ], 
            'user_interview_url' => [
                'db_key'   => 'interview_url',
                'comp_key' => 'partial_match'
            ]
        ];
        $userPageData = $this->UserRepository->getPageUserData(
            $searchDatas,
            $searchKeys,
            $checkflag,
            $userId
        );
        return $userPageData;
    }

    public function getUserData($whereCol, $whereVal)
    {
        $userDataObj  = $this->UserRepository->getUserDataObj($whereCol, $whereVal);
        $shapUserData = $this->ShapUserDataService->shapUserData($userDataObj);
        return $shapUserData;
    }

    public function getUserDataWithKeyValue($valueColumn, $keyColumn){
        return $this->UserRepository->getUserDataWithKeyValue($valueColumn, $keyColumn);
    }
}
