<?php
declare(strict_types=1);
namespace packages\Remind\UseCase\Update;

interface UpdateRemindServiceInterface
{
    public function updateRemind($atsCsId, $updateData);
}

