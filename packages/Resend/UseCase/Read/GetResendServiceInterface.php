<?php
declare(strict_types=1);
namespace packages\Resend\UseCase\Read;


interface GetResendServiceInterface
{
    public function getResendDatas($nowDate);
}



