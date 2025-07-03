<?php
declare(strict_types=1);
namespace packages\Url\Domain\Services;

use Log;

class CreateUrlService
{
    public function __construct()
    {

    }

    /**
     * パラメータ付きのURLを生成する
     * 
     * @param string $pathEnd
     * @param array $hashInfos
     * @param array $normalInfos
     * 
     * @return string $createUrl
     */
    public function createUrl($pathEnd, $hashInfos, $normalInfos)
    {
        $urlParam = '';
        foreach($hashInfos as $hashKey => $beforeHashVal) {
            $afterHashVal = password_hash($beforeHashVal, PASSWORD_DEFAULT);
            $urlParam .= $urlParam == '' ? '?' : '&';
            $urlParam .= $hashKey . '=' . $afterHashVal;
        }
        foreach($normalInfos as $normalKey => $normalVal) {
            $urlParam .= $urlParam == '' ? '?' : '&';
            $urlParam .= $normalKey . '=' . $normalVal;
        }
        $createUrl = url("/" . $pathEnd . $urlParam);
        return $createUrl;
    }
}