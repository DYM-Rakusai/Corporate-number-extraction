<?php
declare(strict_types=1);

namespace packages\User\Infrastructure\User\ForSearch;

use Illuminate\Support\Carbon;

class UserSearchQuery
{
    private $nowCarbonStr;
    private $sub3Days;
    private $sub7Days;
    public  $UserQuery;

    public function __construct($userQuery)
    {
        $nowCarbon          = new Carbon('Asia/Tokyo');
        $this->nowCarbonStr = $nowCarbon->format('Y-m-d H:i');
        $this->sub3Days     = $nowCarbon->subDays(3)->format('Y-m-d 00:00:00');
        $this->sub7Days     = $nowCarbon->subDays(4)->format('Y-m-d 00:00:00');
        $this->userQuery    = $userQuery;
    }

    public function searchParamsByArray($searchKeys, $searchDatas, $userId)
    {
        if ($userId != '') {
            $searchKeys['id']  = ['comp_key' => 'match', 'db_key' => 'id'];
            $searchDatas['id'] = $userId;
        }
        foreach ($searchKeys as $searchKey => $whereData) {
            $searchDataVal = $searchDatas[$searchKey] ?? '';
            if(!empty($searchDataVal)) {
                if($whereData['comp_key'] == 'under') {
                    $this->userQuery->where($whereData['db_key'], '>=', $searchDataVal);
                } elseif($whereData['comp_key'] == 'over') {
                    $this->userQuery->where($whereData['db_key'], '<=', $searchDataVal);
                } elseif($whereData['comp_key'] == 'match') {
                    $this->userQuery->where($whereData['db_key'], '=', $searchDataVal);
                } elseif($whereData['comp_key'] == 'partial_match') {
                    $this->userQuery->where($whereData['db_key'], 'like', "%$searchDataVal%");
                } elseif($whereData['comp_key'] == 'partial_tel_match') {
                    $this->userQuery->where(function($query) use($searchDataVal) {
                        $searchDataVal = str_replace(' ', '', $searchDataVal);
                        $searchDataVal = str_replace(' ', '', $searchDataVal);
                        $where         = "replace(tel, ' ','') like " . "'%" . $searchDataVal . "%'";
                        $query->whereRaw($where);
                        $orWhere = "replace(tel, ' ','') like " . "'%" . $searchDataVal . "%'";
                        $query->orWhereRaw($orWhere);
                    });
                } elseif($whereData['comp_key'] == 'partial_name_match') {
                    $this->userQuery->where(function($query) use($searchDataVal) {
                        $searchDataVal = str_replace(' ', '', $searchDataVal);
                        $searchDataVal = str_replace(' ', '', $searchDataVal);
                        $whereName     = "replace(name, ' ','') like " . "'%" . $searchDataVal . "%'";
                        $query->whereRaw($whereName);
                        $orWhereName = "replace(name, ' ','') like " . "'%" . $searchDataVal . "%'";
                        $query->orWhereRaw($orWhereName);
                    });
                }
            }
        }
    }
}





