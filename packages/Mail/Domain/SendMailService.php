<?php
declare(strict_types=1);
namespace packages\Mail\Domain;

use Log;
use packages\Lambda\UseCase\Request\CommonRequestServiceInterface;

class SendMailService
{
    private $CommonRequestService;

    public function __construct(
        CommonRequestServiceInterface $CommonRequestService
    )
    {
        $this->CommonRequestService = $CommonRequestService;
    }

    public function sendMailByAws($lambdaRequest)
    {
        $this->CommonRequestService->lambdaCommonRequest(
            'common-mail_v2',
            $lambdaRequest
        );
    }

}

