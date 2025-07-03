<?php
declare(strict_types=1);
namespace packages\Remind\UseCase\Register;

interface RegisterRemindServiceInterface
{
    public function registerRemind(
        $atsConsumerId,
        $smsText,
        $mailTitle,
        $mailText,
        $sendUrl = NULL,
        $nextStatus,
        $interviewDate = NULL);
}

