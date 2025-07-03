<?php
declare(strict_types=1);
namespace packages\Url\UseCase\Create;

interface CreateUrlOtherWorksheetServiceInterface
{
    public function makeUrl($path, $hashInfos, $normalInfos);
    public function makeShortUrl($originUrl);
}
