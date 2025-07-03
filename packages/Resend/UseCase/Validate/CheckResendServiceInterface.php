<?php
declare(strict_types=1);
namespace packages\Resend\UseCase\Validate;

interface CheckResendServiceInterface
{
    public function isExistResendData($atsCsId);
}



