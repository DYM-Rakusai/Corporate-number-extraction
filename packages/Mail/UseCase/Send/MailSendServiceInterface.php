<?php
declare(strict_types=1);
namespace packages\Mail\UseCase\Send;

interface MailSendServiceInterface
{
    public function sendMail($toMails,$ccMails,$mailTitle,$mailMessage,$atsConsumerId);
    public function sendToCompanyMail($mailTitle, $mailMessage, $atsConsumerId, $userid, $toMails);
}

