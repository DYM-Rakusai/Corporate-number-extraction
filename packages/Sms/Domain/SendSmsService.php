<?php
declare(strict_types=1);
namespace packages\Sms\Domain;

use Log;
use packages\Lambda\UseCase\Request\CommonRequestServiceInterface;

class SendSmsService
{
    private $CommonRequestService;

    public function __construct(
        CommonRequestServiceInterface $CommonRequestService
    )
    {
        $this->CommonRequestService = $CommonRequestService;
    }

    public function awsSmsInvoke($message, $csTel, $fromName)
    {
        $convertCsTel = $this->convertToSmsNumber($csTel);
        $lambdaRequest = array(
            "tel"         => $convertCsTel,
            "password"    => "Xt5LU3rWBm2kLfsW-",
            "companyName" => $fromName,
            "message"     => $message
        );
        $this->CommonRequestService->lambdaCommonRequest(
            'common-sms',
            $lambdaRequest
        );
    }

    private function convertToSmsNumber($tel)
    {
        if (preg_match('/^\+\d{1,15}$/', $tel)) {
            return $tel;
        }
        $tel = str_replace('-', '', $tel);
        $tel = preg_replace('/^0(\d)/', '+81$1', $tel);
        if (!preg_match('/^\+\d{1,15}$/', $tel)) {
            return '';
        }
        return $tel;
    }
}