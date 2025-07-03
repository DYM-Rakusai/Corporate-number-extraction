<?php
declare(strict_types=1);
namespace packages\Mail\UseCase\Send;

use Log;
use Illuminate\Support\Carbon;
use packages\Mail\Infrastructure\MailMessage\MailMessageRepositoryInterface;
use packages\Mail\Domain\SendMailService;
use packages\Mail\Infrastructure\ToCompanyMailMessage\ToCompanyMailMessageRepositoryInterface;

class MailSendService implements MailSendServiceInterface
{
    private $MailMessageRepository;
    private $mailSenderName;
    private $nowCarbon;
    private $senderMail;
    private $SendMailService;
    private $ToCompanyMailMessageRepository;

    public function __construct(
        MailMessageRepositoryInterface          $MailMessageRepository,
        SendMailService                         $SendMailService,
        ToCompanyMailMessageRepositoryInterface $ToCompanyMailMessageRepository
    )
    {
        $this->MailMessageRepository          = $MailMessageRepository;
        $this->mailSenderName                 = config('app.mail_sender_name');
        $this->nowCarbon                      = Carbon::now('Asia/Tokyo');
        $this->senderMail                     = config('app.company_mail');
        $this->SendMailService                = $SendMailService;
        $this->ToCompanyMailMessageRepository = $ToCompanyMailMessageRepository;
    }

    public function sendMail(
        $toMails,
        $ccMails,
        $mailTitle,
        $mailMessage,
        $atsConsumerId
    ) {
        $lambdaRequest = [
            'subject'      => $mailTitle,
            'from_address' => $this->senderMail,
            'message'      => $mailMessage,
            'to_address'   => $toMails,
            'cc_address'   => $ccMails,
            'sender_name'  => $this->mailSenderName
        ];
        $this->SendMailService->sendMailByAws($lambdaRequest);

        $insertData = [
            'ats_consumer_id' => $atsConsumerId,
            'message'         => $mailMessage,
            'created_at'      => $this->nowCarbon,
        ];
        $this->MailMessageRepository->insertMailMessage($insertData);
    }

    public function sendToCompanyMail(
        $mailTitle,
        $mailMessage,
        $atsConsumerId,
        $userId,
        $toMails
    ) {
        $lambdaRequest = [
            'subject'      => $mailTitle,
            'from_address' => $this->senderMail,
            'message'      => $mailMessage,
            'to_address'   => $toMails,
            'cc_address'   => [],
            'sender_name'  => $this->mailSenderName
        ];
        $this->SendMailService->sendMailByAws($lambdaRequest);

        $insertData = [
            'ats_consumer_id' => $atsConsumerId,
            'user_id'         => $userId,
            'message'         => $mailMessage,
            'created_at'      => $this->nowCarbon,
        ];
        $this->ToCompanyMailMessageRepository->insertToCompanyMailMessage($insertData);
    }
}

