<?php
declare(strict_types=1);

namespace packages\Consumer\Infrastructure\Consumer\ForSearch;

use Illuminate\Support\Carbon;

class ConsumerSearchQuery
{
    public  $consumerQuery;
    private $nowCarbonStr;
    private $sub3Days;
    private $sub7Days;

    public function __construct($consumerQuery)
    {
        $nowCarbon = new Carbon('Asia/Tokyo');
        $this->consumerQuery = $consumerQuery;
        $this->nowCarbonStr  = $nowCarbon->format('Y-m-d H:i');
        $this->sub3Days      = $nowCarbon->subDays(3)->format('Y-m-d 00:00:00');
        $this->sub7Days      = $nowCarbon->subDays(4)->format('Y-m-d 00:00:00');
    }

    public function searchParamsByArray($searchKeys, $searchDatas, $userId)
    {
        if ($userId != '') {
            $searchKeys['user_id'] = ['comp_key' => 'match', 'db_key' => 'user_id'];
            $searchDatas['user_id'] = $userId;
        }
        foreach ($searchKeys as $searchKey => $whereData) {
            $searchDataVal = $searchDatas[$searchKey] ?? '';
            if(!empty($searchDataVal)) {
                if($searchKey == 'end_app_date') {
                    // $searchDate = new Carbon($searchDataVal);
                    // $searchDate->addDays(1);
                    // $searchDataVal = $searchDate->format('Y-m-d 00:00:00');
                    $searchDataVal = $searchDataVal.' 23:59:59';
                }
                if($whereData['comp_key'] == 'under') {
                    $this->consumerQuery->where($whereData['db_key'], '>=', $searchDataVal);
                } elseif($whereData['comp_key'] == 'over') {
                    $this->consumerQuery->where($whereData['db_key'], '<=', $searchDataVal);
                } elseif($whereData['comp_key'] == 'match') {
                    $this->consumerQuery->where($whereData['db_key'], '=', $searchDataVal);
                } elseif($whereData['comp_key'] == 'partial_match') {
                    $this->consumerQuery->where($whereData['db_key'], 'like', "%$searchDataVal%");
                } elseif($whereData['comp_key'] == 'partial_tel_match') {
                    $this->consumerQuery->where(function($query) use($searchDataVal) {
                        $searchDataVal = str_replace(' ', '', $searchDataVal);
                        $searchDataVal = str_replace(' ', '', $searchDataVal);
                        $where = "replace(tel, ' ','') like " . "'%" . $searchDataVal . "%'";
                        $query->whereRaw($where);
                        $orWhere = "replace(tel, ' ','') like " . "'%" . $searchDataVal . "%'";
                        $query->orWhereRaw($orWhere);
                    });
                } elseif($whereData['comp_key'] == 'partial_name_match') {
                    $this->consumerQuery->where(function($query) use($searchDataVal) {
                        $searchDataVal = str_replace(' ', '', $searchDataVal);
                        $searchDataVal = str_replace(' ', '', $searchDataVal);
                        // $query->orWhere('name', 'like', "%$searchDataVal%")
                        //     ->orWhere('kana', 'like', "%$searchDataVal%");
                        $whereName = "replace(name, ' ','') like " . "'%" . $searchDataVal . "%'";
                        $query->whereRaw($whereName);
                        $orWhereName = "replace(name, ' ','') like " . "'%" . $searchDataVal . "%'";
                        $query->orWhereRaw($orWhereName);
                    });
                }
            }
        }
    }

    public function searchInterviewDate($compOperater, $interviewDate)
    {
        if(!empty($interviewDate)) {
            if($compOperater == '<=') {
                // $searchDate = new Carbon($interviewDate);
                // $searchDate->addDays(1);
                // $interviewDate = $searchDate->format('Y-m-d 00:00:00');
                $interviewDate = $interviewDate.' 23:59:59';
            }
            $this->consumerQuery->whereIn(
                'ats_consumer_id', function ($query) use($compOperater, $interviewDate)  {
                $query->select('ats_consumer_id')
                    ->from('schedules')
                    ->where('schedule', $compOperater, $interviewDate);
            });
        }
    }

    public function searchInterviewSet($interviewSet)
    {
        //面接設定
        if($interviewSet == 'set') {
            $this->consumerQuery->whereIn(
                'ats_consumer_id', function ($query) {
                    $query->select('ats_consumer_id')
                        ->where('ats_consumer_id', '<>', NULL)
                        ->from('schedules');
            });
        } elseif($interviewSet == 'not_set') {
            $this->consumerQuery->whereNotIn(
                'ats_consumer_id', function ($query) {
                    $test = $query->select('ats_consumer_id')
                        ->where('ats_consumer_id', '<>', NULL)
                        ->from('schedules');
            });
        }
    }
}





