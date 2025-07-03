<?php
declare(strict_types=1);
namespace packages\Mail\Infrastructure\MailMessage;

use App\Model\MailMessage;

class MailMessageRepository implements MailMessageRepositoryInterface
{

    public function __construct(
    )
    {
    }

    /**
     * メールで送る文章を保存する
     * 
     * @param array $insertData
     */
    public function insertMailMessage($insertData)
    {
        MailMessage::insert($insertData);
    }
}