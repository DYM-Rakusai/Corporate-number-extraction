<?php
declare(strict_types=1);
namespace packages\Sms\Infrastructure\SmsMessage;

interface SmsMessageRepositoryInterface
{
    public function insertSmsMessage($insertData);
}


