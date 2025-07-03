<?php
declare(strict_types=1);
namespace packages\User\UseCase\Update;

interface UpdateUserServiceInterface
{
    public function updateUserSelectedColumn($data, $columnName, $userId);
}
