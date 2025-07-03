<?php

namespace App\Http\Controllers\Auth\Api\BlackList;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use packages\BlackList\UseCase\Delete\DeleteBlackListServiceInterface;
use packages\BlackList\UseCase\Read\GetBlackListServiceInterface;
use packages\Consumer\UseCase\Read\GetCsDataServiceInterface;
use packages\BlackList\Domain\ShapData\ShapBlackListIdsDataService;
use packages\Consumer\UseCase\Validate\ValidateConsumerDataServiceInterface;

class CsDetailForDeleteBlackListController extends Controller
{
    private $DeleteBlackListService;
    private $GetBlackListService;
    private $GetCsDataService;
    private $ShapBlackListIdsDataService;
    private $ValidateConsumerDataService;

    public function __construct(
        DeleteBlackListServiceInterface      $DeleteBlackListService,
        GetBlackListServiceInterface         $GetBlackListService,
        GetCsDataServiceInterface            $GetCsDataService,
        ShapBlackListIdsDataService          $ShapBlackListIdsDataService,
        ValidateConsumerDataServiceInterface $ValidateConsumerDataService
    )
    {
        $this->middleware('auth');
        $this->DeleteBlackListService      = $DeleteBlackListService;
        $this->GetBlackListService         = $GetBlackListService;
        $this->GetCsDataService            = $GetCsDataService;
        $this->ShapBlackListIdsDataService = $ShapBlackListIdsDataService;
        $this->ValidateConsumerDataService = $ValidateConsumerDataService;
    }

    public function store(Request $request)
    {
        if ($request->get('apiToken') !== config('app.apiToken')) {
            throw new \Exception('APIトークンが不正です。');
        }
        
        $consumerData = $this->GetCsDataService->getConsumerData(
            'ats_consumer_id'  , 
            $request->get('id'), 
            true
        );
        
        if(empty($consumerData)) {
            return;
        }

        $isValidList = $this->ValidateConsumerDataService->getIsValidList([
            'tel'  => $consumerData['tel' ],
            'mail' => $consumerData['mail']
        ]);

        $blackListCsDatas = $this->GetBlackListService->getBlackListCsData(
            $consumerData['tel' ],
            $consumerData['mail'],
            $isValidList
        );

        $blackListIds = $this->ShapBlackListIdsDataService->shapBlackListIds($blackListCsDatas);
        
        $this->DeleteBlackListService->deleteBlackListForCsDetail($blackListIds);
    }
}