<?php
declare(strict_types=1);
namespace packages\Resend\UseCase\Update;

interface ResendUpdateServiceInterface
{
    public function updateResendData($whereKeys, $smsText);
    public function updateResendAfterDo($atsConsumerId, $nowSendTimes);
    public function updateConfirmTime($atsConsumerId);
}



