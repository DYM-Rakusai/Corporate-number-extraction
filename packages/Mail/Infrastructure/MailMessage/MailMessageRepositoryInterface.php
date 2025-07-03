<?php
declare(strict_types=1);
namespace packages\Mail\Infrastructure\MailMessage;

interface MailMessageRepositoryInterface
{
    public function insertMailMessage($insertData);
}
