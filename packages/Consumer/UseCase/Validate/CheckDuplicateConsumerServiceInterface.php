<?php
declare(strict_types=1);
namespace packages\Consumer\UseCase\Validate;

interface CheckDuplicateConsumerServiceInterface
{
    public function checkDuplicateCs($csTel, $csMail);
    public function checkAlreadyExist($atsConsumerId);
}
