<?php
declare(strict_types=1);
namespace packages\Mail\Infrastructure\ToCompanyMailMessage;

interface ToCompanyMailMessageRepositoryInterface
{
    public function insertToCompanyMailMessage($insertData);
}
