<?php

declare(strict_types=1);

namespace packages\Consumer\UseCase\Create;

use Log;
use Illuminate\Support\Carbon;
use packages\Consumer\Infrastructure\Consumer\ConsumerRepositoryInterface;
use packages\Consumer\Domain\Create\ShapInsertDataService;

class CreateConsumerDataService implements CreateConsumerDataServiceInterface
{
    private $ConsumerRepository;
    private $ShapInsertDataService;

    public function __construct(
        ConsumerRepositoryInterface $ConsumerRepository,
        ShapInsertDataService       $ShapInsertDataService
    )
    {
        $this->ShapInsertDataService = $ShapInsertDataService;
        $this->ConsumerRepository    = $ConsumerRepository;
    }

    public function insertCsData(
        $atsConsumerId,
        $csTel,
        $csMail,
        $appData,
        $csStatus
    ) {
        // entry_jobが空でなければジョブマッピング
        if (!empty($appData['entry_job'])) {
            $appData = $this->ShapInsertDataService->getConsumerJobMapping($appData);
        }

        $consumerParam = $this->ShapInsertDataService->getConsumerParams(
            $atsConsumerId,
            $csTel,
            $csMail,
            $appData,
            $csStatus
        );

        $consumerId = $this->ConsumerRepository->insertCs($consumerParam);

        // 未対応かつuser_idがnullの場合は空文字を返す
        if ($consumerParam['consumer_status'] == '未対応' && is_null($consumerParam['user_id'])) {
            return '';
        }

        return $consumerId;
    }
}




