<?php
declare(strict_types=1);
namespace packages\Consumer\UseCase\Update;

interface UpdateConsumerDataServiceInterface
{
    public function updateCsData($whereData, $updateData);
    public function updateAppMedia($csIds, $updateDatas);
}

