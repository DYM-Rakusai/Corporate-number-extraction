<?php

namespace packages\BlackList\Domain\ShapData;

class ShapBlackListIdsDataService
{
    public function __construct()
    {
        
    }

    public function shapBlackListIds($blackListIdDatas)
    {
        $blackListIds = [];
        foreach($blackListIdDatas as $blackListIdData) {
            $blackListIds[] = $blackListIdData['id'];
        }
        return $blackListIds;
    }
}