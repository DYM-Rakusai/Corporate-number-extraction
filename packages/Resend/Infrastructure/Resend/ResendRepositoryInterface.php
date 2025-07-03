<?php
declare(strict_types=1);
namespace packages\Resend\Infrastructure\Resend;

interface ResendRepositoryInterface
{
    public function insertResend($insertData);
    public function updateResend($whereKeys, $updateData);
    public function getResendData($whereDate);
    public function checkExistResendData($atsCsId);
}
