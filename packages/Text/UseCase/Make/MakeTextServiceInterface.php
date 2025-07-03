<?php
declare(strict_types=1);
namespace packages\Text\UseCase\Make;

interface MakeTextServiceInterface
{
    public function makeSendSmsText($consumerStatus, $url);
    public function makeSendMailText($consumerStatus, $url);
}