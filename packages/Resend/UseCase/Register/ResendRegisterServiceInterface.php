<?php
declare(strict_types=1);
namespace packages\Resend\UseCase\Register;

interface ResendRegisterServiceInterface
{
    public function insertResendData(
        $mailText,
        $url,
        $atsCsId);
}



