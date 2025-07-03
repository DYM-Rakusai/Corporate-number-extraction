<?php
declare(strict_types=1);
namespace packages\Url\UseCase\Validate;

interface UrlParamServiceInterface
{
    public function isValidUrl($checkList);
}
