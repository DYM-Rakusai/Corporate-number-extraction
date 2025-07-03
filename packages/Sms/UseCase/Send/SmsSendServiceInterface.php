<?php
declare(strict_types=1);
namespace packages\Sms\UseCase\Send;

interface SmsSendServiceInterface
{
    public function sendSms($smsText, $csTel, $atsConsumerId);
}



