<?php
declare(strict_types=1);
namespace packages\Remind\UseCase\Register;

use Log;
use Illuminate\Support\Carbon;
use packages\Url\UseCase\Create\CreateUrlOtherWorksheetServiceInterface;
use packages\Text\UseCase\Make\MakeTextServiceInterface;
use packages\Remind\UseCase\Register\RegisterRemindServiceInterface;

class SetMsgRemindService implements SetMsgRemindServiceInterface
{
    private $CreateUrlOtherWorksheetService;
    private $MakeTextService;
    private $nowDate;
    private $RegisterRemindService;

    public function __construct(
        CreateUrlOtherWorksheetServiceInterface $CreateUrlOtherWorksheetService,
        MakeTextServiceInterface                $MakeTextService,
        RegisterRemindServiceInterface          $RegisterRemindService
    )
    {
        $nowStamp = Carbon::now('Asia/Tokyo');
        $this->nowDate                        = Carbon::parse($nowStamp);
        $this->CreateUrlOtherWorksheetService = $CreateUrlOtherWorksheetService;
        $this->MakeTextService                = $MakeTextService;
        $this->RegisterRemindService          = $RegisterRemindService;
    }

    public function setRemind(
        $atsCsId,
        $consumerId,
        $remindStatus,
        $schedule = NULL
    )
    {
        $csRemindUrl = $this->CreateUrlOtherWorksheetService->makeUrl(
            'cs-status-page',
            [   'hashCs'     => $atsCsId],
            [
                'consumerId' => $consumerId,
                'csStatus'   => $remindStatus
            ]
        );
        
        $remindSmsText  = $this->MakeTextService->makeSendSmsText($remindStatus, $csRemindUrl);
        $remindMailData = $this->MakeTextService->makeSendMailText($remindStatus, $csRemindUrl);

        $this->RegisterRemindService->registerRemind(
            $atsCsId,
            $remindSmsText,
            $remindMailData['subject'],
            $remindMailData['mainText'],
            $csRemindUrl,
            $remindStatus,
            $schedule
        );
    }
}


