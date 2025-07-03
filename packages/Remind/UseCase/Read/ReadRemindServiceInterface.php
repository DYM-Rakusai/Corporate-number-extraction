<?php
declare(strict_types=1);
namespace packages\Remind\UseCase\Read;

interface ReadRemindServiceInterface
{
    public function getRemindTargetData($nowDate);
}




