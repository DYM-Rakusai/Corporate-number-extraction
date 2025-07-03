<?php
declare(strict_types=1);
namespace packages\Url\UseCase\Validate;

use Log;
use packages\Url\Domain\Services\CheckParamsService;

class UrlParamService implements UrlParamServiceInterface
{
	private $CheckParamsService;

    public function __construct(
    	CheckParamsService $CheckParamsService
    )
    {
    	$this->CheckParamsService = $CheckParamsService;
    }

    // $checkList : $hashKey => $hashVal
    public function isValidUrl($checkList)
    {
        $isValid = true;
    	foreach($checkList as $hashKey => $hashVal) {
            $hashKeyStr = strval($hashKey);
    		$isValid = $this->CheckParamsService->isValidUrlParam($hashKeyStr, $hashVal);
            if($isValid === false) {
                return $isValid;
            }
    	}
        return $isValid;
    }
}


