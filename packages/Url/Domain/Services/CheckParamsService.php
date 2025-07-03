<?php
declare(strict_types=1);
namespace packages\Url\Domain\Services;

use Log;

class CheckParamsService
{
    public function __construct()
    {
    }

    public function isValidUrlParam($checkKey, $checkVal)
    {
        $checkResult = password_verify($checkKey, $checkVal);
        return $checkResult;
    }
}
