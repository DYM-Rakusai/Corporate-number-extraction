<?php
declare(strict_types=1);
namespace packages\Worksheet\UseCase\Create;

interface InsertWsDataServiceInterface
{
    public function insertWs($atsConsumerId, $wsUrl);
}
