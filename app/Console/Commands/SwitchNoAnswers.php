<?php

namespace App\Console\Commands;

use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use packages\Url\UseCase\Create\CreateUrlOtherWorksheetServiceInterface;
use packages\Lambda\UseCase\Request\CommonRequestServiceInterface;
use packages\Consumer\UseCase\Read\GetCsDataServiceInterface;
use packages\Consumer\UseCase\Update\UpdateConsumerDataServiceInterface;
use packages\Text\UseCase\Make\MakeTextServiceInterface;
use packages\Worksheet\UseCase\Read\GetWsDataServiceInterface;
use packages\Remind\UseCase\Register\RegisterRemindServiceInterface;

class SwitchNoAnswers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:switchNoAnswer {days=2} {nowDate?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '未回答の応募者対応';

    private $CommonRequestService;
    private $CreateUrlOtherWorksheetService;
    private $GetWsDataService;
    private $GetCsDataService;
    private $MakeTextService;
    private $RegisterRemindService;
    private $UpdateConsumerDataService;

    public function __construct(
        CommonRequestServiceInterface               $CommonRequestService,
        CreateUrlOtherWorksheetServiceInterface     $CreateUrlOtherWorksheetService,
        GetCsDataServiceInterface                   $GetCsDataService,
        GetWsDataServiceInterface                   $GetWsDataService,
        MakeTextServiceInterface                    $MakeTextService,
        RegisterRemindServiceInterface              $RegisterRemindService,
        UpdateConsumerDataServiceInterface          $UpdateConsumerDataService
    ) {
        $this->CommonRequestService               = $CommonRequestService;
        $this->CreateUrlOtherWorksheetService     = $CreateUrlOtherWorksheetService;
        $this->GetCsDataService                   = $GetCsDataService;
        $this->GetWsDataService                   = $GetWsDataService;
        $this->MakeTextService                    = $MakeTextService;
        $this->RegisterRemindService              = $RegisterRemindService;
        $this->UpdateConsumerDataService          = $UpdateConsumerDataService;
        parent::__construct();
    }

    public function handle()
    {
        $days            = (int)      $this->argument('days');
        $nowCarbon       = new Carbon($this->argument('nowDate'));
        $pastTime        = $nowCarbon->subHours($days * 24)->format('Y-m-d H:i');

        $consumerIdArray = $this->GetWsDataService->getNoAnswerCsIds($pastTime);

        if (empty($consumerIdArray)) {
            return;
        }
        
        $nextStatusList = config('Consumer.statusList.patternList');
        $csStatus = 'failure';
        
        foreach ($consumerIdArray as $atsCsId) {
            $consumerData  = $this->GetCsDataService->getConsumerData ('ats_consumer_id', $atsCsId);
            $worksheetData = $this->GetWsDataService->getWsAnswerDatas([$atsCsId]);
            $consumerId    = $consumerData['id'];
    
            $csStatusPageUrl = $this->CreateUrlOtherWorksheetService->makeUrl(
                'cs-status-page',
                ['hashCs' => $atsCsId],
                [
                    'consumerId' =>  $consumerId,
                    'csStatus'   =>  $csStatus
                ]
            );

            $smsText  = $this->MakeTextService->makeSendSmsText($csStatus , $csStatusPageUrl);
            $mailData = $this->MakeTextService->makeSendMailText($csStatus, $csStatusPageUrl);

            $this->RegisterRemindService->registerRemind(
                $atsCsId,
                $smsText,
                $mailData['subject'],
                $mailData['mainText'],
                $csStatusPageUrl,
                $csStatus,
                NULL
            );

            $this->UpdateConsumerDataService->updateCsData(
                ['ats_consumer_id' => $atsCsId ],
                ['consumer_status' => $nextStatusList[$csStatus]]
            );
        }
    }
}
