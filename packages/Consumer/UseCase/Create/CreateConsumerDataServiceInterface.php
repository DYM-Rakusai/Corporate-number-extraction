<?php
declare(strict_types=1);
namespace packages\Consumer\UseCase\Create;

interface CreateConsumerDataServiceInterface
{
    public function insertCsData(
        $atsConsumerId,
        $csTel,
        $csMail,
        $appData,
        $csStatus
    );
}

