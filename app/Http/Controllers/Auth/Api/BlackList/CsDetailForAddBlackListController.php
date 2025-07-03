<?php

namespace App\Http\Controllers\Auth\Api\BlackList;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use packages\BlackList\UseCase\Create\AddBlackListServiceInterface;
use packages\Consumer\UseCase\Read\GetCsDataServiceInterface;

class CsDetailForAddBlackListController extends Controller
{
    private $AddBlackListService;
    private $GetCsDataService;

    public function __construct(
        AddBlackListServiceInterface $AddBlackListService,
        GetCsDataServiceInterface    $GetCsDataService
    )
    {
        $this->middleware('auth');
        $this->AddBlackListService = $AddBlackListService;
        $this->GetCsDataService    = $GetCsDataService;
    }

    public function store(Request $request)
    {
        if ($request->get('apiToken') !== config('app.apiToken')) {
            throw new \Exception('APIトークンが不正です。');
        }
        
        $consumerData = $this->GetCsDataService->getConsumerData(
            'ats_consumer_id', 
            $request->get('id'), 
            true
        );

        if(empty($consumerData)) {
            return;
        }

        $this->AddBlackListService->addBlackList(
            $consumerData['name'],
            $consumerData['tel' ],
            $consumerData['mail']
        );

    }
}