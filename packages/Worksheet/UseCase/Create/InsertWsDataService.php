<?php
declare(strict_types=1);
namespace packages\Worksheet\UseCase\Create;

use Log;
use Illuminate\Support\Carbon;
use packages\Worksheet\Infrastructure\Worksheet\WorksheetRepositoryInterface;

class InsertWsDataService implements InsertWsDataServiceInterface
{
    private $nowCarbon;
    private $WorksheetRepository;

    public function __construct(
        WorksheetRepositoryInterface $WorksheetRepository
    )
    {
        $nowStamp                  = Carbon::now('Asia/Tokyo');
        $this->nowCarbon           = Carbon::parse($nowStamp);
        $this->WorksheetRepository = $WorksheetRepository;
    }

    public function insertWs($atsConsumerId, $wsUrl)
    {
        $insertData = [
            'ats_consumer_id' => $atsConsumerId,
            'ws_url'          => $wsUrl,
            'created_at'      => $this->nowCarbon
        ];
        $this->WorksheetRepository->insertWorksheet($insertData);
    }
}


