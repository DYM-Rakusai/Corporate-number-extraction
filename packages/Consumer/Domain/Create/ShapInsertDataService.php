<?php
declare(strict_types=1);
namespace packages\Consumer\Domain\Create;

use Illuminate\Support\Carbon;
use packages\Consumer\Infrastructure\Consumer\ConsumerRepositoryInterface;
use packages\Job\Infrastructure\JobMapping\JobMappingRepositoryInterface;
use packages\Consumer\Domain\Validate\ValidateDataService;

class ShapInsertDataService
{
    private $beforeCarbon;
    private $ConsumerRepository;
    private $nowCarbon;
    private $ValidateDataService;

    public function __construct(
        ConsumerRepositoryInterface   $ConsumerRepository,
        JobMappingRepositoryInterface $JobMappingRepository,
        ValidateDataService           $ValidateDataService
    )
    {
        $this->ConsumerRepository   = $ConsumerRepository;
        $this->JobMappingRepository = $JobMappingRepository;
        $this->ValidateDataService  = $ValidateDataService;

        $this->nowCarbon    = Carbon::now('Asia/Tokyo');
        $beforeTime         = config('Consumer.consumerConf.duplicateTime');
        $this->beforeCarbon = $this->nowCarbon->copy()->subHour($beforeTime);
    }

    public function getConsumerParams(
        $atsConsumerId,
        $csTel,
        $csMail,
        $appData,
        $csStatus)
    {
        $createConsumer = [
            'ats_consumer_id' => $atsConsumerId,
            'name'            => $appData['name'],
            'kana'            => $appData['kana'] ?? NULL,
            'tel'             => $csTel ?? NULL,
            'mail'            => $appData['mail'] ?? NULL,
            'birthday'        => $appData['birthday'] ?? NULL,
            'gender'          => $appData['gender'] ?? NULL,
            'address'         => $appData['address'] ?? NULL,
            'entry_job'       => $appData['entry_job'] ?? NULL,
            'user_id'         => $appData['user_id'] ?? NULL,
            'consumer_status' => $csStatus,
            'app_date'        => $appData['app_date'] ?? NULL,
            'app_media'       => $appData['app_media'] ?? NULL,
            'created_at'      => $this->nowCarbon
        ];

        return $createConsumer;
    }

    public function getConsumerJobMapping($appData)
    {
        $keywordArray = $this->JobMappingRepository->getUserIdToKeyword();
        // \Log::Info($keywordArray);
        foreach ($keywordArray as $keyword => $data) {
            if (strpos($appData['entry_job'], $keyword) !== false) {
                $appData['user_id'] = $data['user_id'];
                break;
            }
        }

        return $appData;
    }

}



