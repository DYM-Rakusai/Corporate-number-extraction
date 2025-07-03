<?php

declare(strict_types=1);

namespace packages\User\UseCase\Create;

use Log;
use Illuminate\Support\Carbon;
use packages\User\Infrastructure\User\UserRepositoryInterface;

class CreateUserDataService implements CreateUserDataServiceInterface
{
    private $UserRepository;

    public function __construct(
        UserRepositoryInterface $UserRepository
    )
    {
        $this->UserRepository = $UserRepository;
    }

    public function insertUserData($userData) 
    {
        $this->UserRepository->insertUser($userData);
    }
}




