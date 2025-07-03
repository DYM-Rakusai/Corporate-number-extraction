<?php
namespace App\Services\Worksheet;

use packages\Url\UseCase\Create\CreateUrlOtherWorksheetServiceInterface;
use packages\User\UseCase\Read\GetUserServiceInterface;
use packages\Text\UseCase\Make\MakeTextServiceInterface;
use packages\Mail\UseCase\Send\MailSendServiceInterface;

class Pattern1AdjustService
{
    private $CreateUrlOtherWorksheetService;
    private $GetUserService;
    private $MakeTextService;
    private $MailSendService;
    
    public function __construct(
        GetUserServiceInterface                 $GetUserService,
        CreateUrlOtherWorksheetServiceInterface $CreateUrlOtherWorksheetService,
        MakeTextServiceInterface                $MakeTextService,
        MailSendServiceInterface                $MailSendService
    )
    {
        $this->GetUserService                 = $GetUserService;
        $this->CreateUrlOtherWorksheetService = $CreateUrlOtherWorksheetService;
        $this->MakeTextService                = $MakeTextService;
        $this->MailSendService                = $MailSendService;
    }

    public function pattern1AdjustAction($consumerData)
    {
        try{
            $atsConsumerId = $consumerData['ats_consumer_id'];
            $userId        = $consumerData['user_id'] ?? '';

            $cpUrl = $this->CreateUrlOtherWorksheetService->makeUrl(
                'company-status-page',
                ['hashCs' => $atsConsumerId],
                [
                    'consumerId' => $consumerData['id'],
                    'status'     => 'adjustForCompany'
                ]
            );
            
            $mailDataForCp = $this->MakeTextService->makeSendMailText('adjustForCompany', $cpUrl);
            $userMail      = $this->GetUserService->getUserValById($userId, 'mail');

            $this->MailSendService->sendToCompanyMail(
                $mailDataForCp['subject' ],
                $mailDataForCp['mainText'],
                $atsConsumerId,
                $userId,
                [$userMail]
            );

            return True;
        } catch (\Exception $e) {
            Log::error("PatternAdjust 失敗: " . $e->getMessage());
            return False;
        }
    }
}





