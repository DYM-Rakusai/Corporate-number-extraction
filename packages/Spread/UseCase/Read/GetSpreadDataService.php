<?php
declare(strict_types=1);
namespace packages\Spread\UseCase\Read;

use Log;
use packages\Spread\Domain\Services\GetGoogleClientService;
use packages\Spread\Domain\Services\GetSpreadValueDataService;
use packages\Spread\Domain\Services\ShapSpreadDataService;

class GetSpreadDataService implements GetSpreadDataServiceInterface
{
    private $GetGoogleClientService;
    private $GetSpreadValueDataService;
    private $ShapSpreadDataService;

    public function __construct(
        GetGoogleClientService    $GetGoogleClientService,
        GetSpreadValueDataService $GetSpreadValueDataService,
        ShapSpreadDataService     $ShapSpreadDataService
    )
    {
        $this->GetGoogleClientService    = $GetGoogleClientService;
        $this->GetSpreadValueDataService = $GetSpreadValueDataService;
        $this->ShapSpreadDataService     = $ShapSpreadDataService;
    }

    public function getAllSpreadDataArray($sheetName, $spreadId)
    {
        $googleClientObj = $this->GetGoogleClientService->getGoogleClient();
        $allSpreadDatas  = $this->GetSpreadValueDataService->getAllSpreadData(
            $googleClientObj,
            $sheetName,
            $spreadId);
        $getSpreadDatas = $this->ShapSpreadDataService->shapSpreadData($allSpreadDatas);
        return $getSpreadDatas;
    }
}


