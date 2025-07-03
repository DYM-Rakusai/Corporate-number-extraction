<?php
declare(strict_types=1);
namespace packages\Remind\Infrastructure\Remind;

interface RemindRepositoryInterface
{
    public function insertRemind($insertData);
    public function getRemindByTime($date);
    public function updateRemind($atsCsId, $updateData);
}
