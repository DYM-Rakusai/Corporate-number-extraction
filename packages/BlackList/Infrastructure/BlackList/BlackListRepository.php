<?php

declare(strict_types=1);

namespace packages\BlackList\Infrastructure\BlackList;

use App\Model\BlackList;

class BlackListRepository implements BlackListRepositoryInterface
{
    public function __construct(
    )
    {
    }

    public function isExistBlackList($csTel, $csMail, $isValidList)
    {
        $BlackListQuery = BlackList::query();
        if($isValidList['tel'] && $isValidList['mail']) {
            $BlackListQuery = $BlackListQuery->where('tel', '=', $csTel);
            $BlackListQuery = $BlackListQuery->orWhere('mail', '=', $csMail);
        } else if($isValidList['tel']) {
            $BlackListQuery = $BlackListQuery->where('tel', '=', $csTel);
        } else if($isValidList['mail']) {
            $BlackListQuery = $BlackListQuery->where('mail', '=', $csMail);
        } else {
            return false;
        }
        $isExist = $BlackListQuery->exists();
        return $isExist;
    }

    public function getBlackList()
    {
        $blackListPageData = BlackList::paginate(30);
        return $blackListPageData;
    }

    public function insertBlackList($insertData)
    {
        BlackList::insert($insertData);
    }

    public function deleteBlackList($blackListId)
    {
        BlackList::where('id', '=', $blackListId)->delete();
    }

    public function deleteBlackListForCsDetail($blackListIds)
    {
        BlackList::whereIn('id', $blackListIds)->delete();
    }

    public function getBlackListCsData($csTel, $csMail, $isValidList)
    {
        $BlackListQuery = BlackList::query();
        if ($isValidList['tel'] && $isValidList['mail']) {
            $blackListCsData = $BlackListQuery->where('tel', '=', $csTel)
                                                  ->orWhere('mail', '=', $csMail)
                                                  ->get('id');
        } elseif($isValidList['tel']) {
            $blackListCsData = $BlackListQuery->where('tel', '=', $csTel)
                                                  ->get('id');
        } elseif($isValidList['mail']) {
            $blackListCsData = $BlackListQuery->where('mail', '=', $csMail)
                                                  ->get('id');
        }
        
        return $blackListCsData;
    }
}
