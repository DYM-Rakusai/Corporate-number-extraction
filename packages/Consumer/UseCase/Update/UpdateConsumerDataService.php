<?php

declare(strict_types=1);

namespace packages\Consumer\UseCase\Update;

use Log;
use Illuminate\Support\Carbon;
use packages\Consumer\Infrastructure\Consumer\ConsumerRepositoryInterface;


class UpdateConsumerDataService implements UpdateConsumerDataServiceInterface
{
    private $ConsumerRepository;

    public function __construct(
        ConsumerRepositoryInterface $ConsumerRepository
    )
    {
        $this->ConsumerRepository = $ConsumerRepository;
    }

    public function updateCsData($whereData, $updateData)
    {
        $this->ConsumerRepository->updateCsData($whereData, $updateData);
    }

    public function updateAppMedia($csIds, $updateDatas)
    {
        $this->ConsumerRepository->updateAppMedia($csIds, $updateDatas);
    }
}






