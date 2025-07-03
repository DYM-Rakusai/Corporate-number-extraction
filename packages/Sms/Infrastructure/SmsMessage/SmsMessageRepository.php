<?php

declare(strict_types=1);

namespace packages\Sms\Infrastructure\SmsMessage;

use App\Model\SmsMessage;

class SmsMessageRepository implements SmsMessageRepositoryInterface
{
    public function __construct(
    )
    {
    }

    public function insertSmsMessage($insertData)
    {
        SmsMessage::insert($insertData);
    }
}

