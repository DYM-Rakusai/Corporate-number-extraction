<?php
declare(strict_types=1);
namespace packages\Text\UseCase\Make;

use Log;
use packages\Text\Domain\Services\ShapTextService;

class MakeTextService implements MakeTextServiceInterface
{
    private $ShapTextService;

    public function __construct(
        ShapTextService $ShapTextService
    )
    {
        $this->ShapTextService = $ShapTextService;
    }

    public function makeSendSmsText($consumerStatus, $url)
    {
        return $this->ShapTextService->shapSmsSendText($consumerStatus, $url);
    }

    public function makeSendMailText($consumerStatus, $url)
    {
        $sendText      = $this->ShapTextService->shapMailSendText($consumerStatus, $url);
        $mailTitle     = config('Text.sendMessageMailTitle.' . $consumerStatus, '');
        $mailTextArray = [
            'mainText' => $sendText,
            'subject'  => $mailTitle
        ];
        return $mailTextArray;
    }
}