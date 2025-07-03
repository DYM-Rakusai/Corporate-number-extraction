<?php
declare(strict_types=1);
namespace packages\Remind\UseCase\Register;

interface SetMsgRemindServiceInterface
{
    public function setRemind(
        $atsCsId,
        $consumerId,
        $remindStatus,
        $schedule = NULL
    );
}



