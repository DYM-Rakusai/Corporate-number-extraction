<?php
declare(strict_types=1);
namespace packages\Consumer\UseCase\Validate;

interface ValidateConsumerDataServiceInterface
{
    public function getIsValidList($checkList);
    public function isExistConsumer($whereCol, $whereVal);
}
