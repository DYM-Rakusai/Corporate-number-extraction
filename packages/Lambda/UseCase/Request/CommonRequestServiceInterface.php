<?php
declare(strict_types=1);
namespace packages\Lambda\UseCase\Request;

interface CommonRequestServiceInterface
{
    public function lambdaCommonRequest($requestMethod, $requestParam);
}
