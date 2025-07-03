<?php
declare(strict_types=1);
namespace packages\Consumer\Domain\Get;

class ShapCsDataService
{

    public function __construct(
    )
    {
    }

    public function shapCsData($csDataObj)
    {
        if($csDataObj->isEmpty()) {
            return [];
        }
        $csDataArray = $csDataObj->toArray();
        $consumers   = [];
        if(!empty($csDataArray[0]['consumer'])) {
            foreach($csDataArray[0]['consumer'] as $consumer) {
                $consumers[$consumer['key']] = $consumer['val'];
            }
        }
        $shapCsData = [];
        foreach($csDataArray[0] as $colName => $colVal) {
            $shapCsData[$colName] = $colVal;
        }
        $shapCsData['consumer'] = $consumers;
        return $shapCsData;
    }
}



