<?php
declare(strict_types=1);
namespace packages\Spread\Domain\Services;

use Log;
use Google_Client;
use Google_Service_Sheets;

class GetSpreadValueDataService
{
    public function __construct(
    )
    {
    }

    public function getAllSpreadData($googleClient, $sheetName, $spreadId)
    {
        #$getSheetRange = $sheetName.'!A:AZ';
        $getSheetRange  = $sheetName.'!F:K';
        $options = [
            'valueRenderOption' => 'UNFORMATTED_VALUE'
        ];
        $response     = $googleClient->spreadsheets_values->get($spreadId, $getSheetRange, $options);
        $spreadValues = $response->getValues();
        return $spreadValues;
    }
}