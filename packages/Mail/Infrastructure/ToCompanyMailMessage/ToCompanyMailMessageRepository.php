<?php
declare(strict_types=1);
namespace packages\Mail\Infrastructure\ToCompanyMailMessage;

use App\Model\ToCompanyMailMessage;

class ToCompanyMailMessageRepository implements ToCompanyMailMessageRepositoryInterface
{

    public function __construct(
    )
    {
    }

    public function insertToCompanyMailMessage($insertData)
    {
        ToCompanyMailMessage::insert($insertData);
    }
}


