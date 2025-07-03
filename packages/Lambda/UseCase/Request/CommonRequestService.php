<?php
declare(strict_types=1);
namespace packages\Lambda\UseCase\Request;

use Log;
use packages\Lambda\Domain\Services\LambdaRequestService;

class CommonRequestService implements CommonRequestServiceInterface
{
    private $LambdaRequestService;

    public function __construct(
        LambdaRequestService $LambdaRequestService
    )
    {
        $this->LambdaRequestService = $LambdaRequestService;
    }

    public function lambdaCommonRequest($requestMethod, $requestParam)
    {
        $this->LambdaRequestService->lambdaRequest($requestMethod, $requestParam);
    }
}