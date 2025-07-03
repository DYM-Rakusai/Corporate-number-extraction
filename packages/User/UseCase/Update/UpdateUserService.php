<?php
declare(strict_types=1);
namespace packages\User\UseCase\Update;

use Log;
use packages\User\Infrastructure\User\UserRepositoryInterface;

class UpdateUserService implements UpdateUserServiceInterface
{
    private $UserRepository;
    public function __construct(
        UserRepositoryInterface $UserRepository
    ) {
        $this->UserRepository = $UserRepository;
    }
    public function updateUserSelectedColumn($data, $columnName, $userId)
    {
        Log::info($data);
        $updateData = [
            'target_jobs' => $data
        ];
        $this->UserRepository->updateUserSelectedColumn($updateData, $userId);
    }

    public function updateUserData($whereData, $userData)
    {
        $this->UserRepository->updateUserData($whereData, $userData);
    }
}
