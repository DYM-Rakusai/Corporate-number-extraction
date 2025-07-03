<?php
declare(strict_types=1);
namespace packages\Spread\Domain\Services;

use Log;

class ShapSpreadDataService
{
    public function __construct(
    )
    {
    }

    public function shapSpreadData($spreadDatasArray)
    {
        $index            = 0;
        $spreadTitleArray = [];
        $shapSpreadDatas  = [];
        foreach($spreadDatasArray as $spreadDatas) {
            if($index === 0) {
                //スプレッドシートの1行目取得
                $spreadTitleArray = $this->getTitleArray($spreadDatas);
                if(empty($spreadTitleArray)) {
                    \Log::error('スプレッドシートの1行目不備');
                    return [];
                }
            } else {
                $shapSpreadData    = $this->getRowDataArray($spreadDatas, $spreadTitleArray);
                $shapSpreadDatas[] = $shapSpreadData;
            }
            $index++;
        }
        return $shapSpreadDatas;
    }
    
    private function getTitleArray($spreadTitleDatas)
    {
        $spreadTitleArray = [];
        $columnNum        = 0;
        foreach($spreadTitleDatas as $spreadTitleData) {
            preg_match('/<(.*)>/u', $spreadTitleData, $matchSpreadData);
            if(!empty($matchSpreadData[1])) {
                $infoKey = $matchSpreadData[1];
                $spreadTitleArray[$columnNum] = $infoKey;
            }
            $columnNum++;
        }
        return $spreadTitleArray;
    }

    private function getRowDataArray($spreadRowDatas, $spreadTitleArray)
    {
        $rowDataArray = [];
        foreach($spreadTitleArray as $spreadColumnNum => $spreadColumnKey) {
            $rowDataArray[$spreadColumnKey] = $spreadRowDatas[$spreadColumnNum] ?? '';
        }
        return $rowDataArray;
    }
}


