<?php
declare(strict_types=1);
namespace packages\Url\UseCase\Create;

use Log;
use packages\Url\Domain\Services\CreateUrlService;
use packages\Url\Domain\Services\MakeShortUrlService;


class CreateUrlOtherWorksheetService implements CreateUrlOtherWorksheetServiceInterface
{
    private $CreateUrlService;
    private $MakeShortUrlService;

    public function __construct(
        CreateUrlService    $CreateUrlService,
        MakeShortUrlService $MakeShortUrlService
    )
    {
        $this->CreateUrlService    = $CreateUrlService;
        $this->MakeShortUrlService = $MakeShortUrlService;
    }

    /**
     * URLを生成し、短縮URLを返す
     *
     * @param string $pathEnd
     * @param array $hashInfos
     * @param array $normalInfos
     * @return string $shortUrl
     */
    public function makeUrl($pathEnd, $hashInfos, $normalInfos)
    {
        $makeUrl = $this->CreateUrlService->createUrl($pathEnd, $hashInfos, $normalInfos);
        if (empty($makeUrl)) {
            Log::error('URL作成失敗');
        }

        $shortUrl = $this->makeShortUrl($makeUrl);
        if (empty($shortUrl)) {
            Log::error('短縮URL作成失敗');
        }

        Log::info('shortURL: ' . $shortUrl);
        return $shortUrl;
    }

    /**
     * 短縮URLを生成
     *
     * @param string $originUrl
     * @return string
     */
    public function makeShortUrl($originUrl)
    {
        return $this->MakeShortUrlService->shorten($originUrl);
    }
}

