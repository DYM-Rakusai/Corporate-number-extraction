<?php
declare(strict_types=1);
namespace packages\User\UseCase\Create;

interface CreateUserDataServiceInterface
{
    public function insertUserData($userData);
}

