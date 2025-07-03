<?php
declare(strict_types=1);
namespace App\Http\Controllers\Auth\Api\Consumer;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use packages\Consumer\UseCase\Read\GetCsDataServiceInterface;
use packages\Consumer\UseCase\Update\UpdateConsumerDataServiceInterface;

class EditConsumerController extends Controller
{
    private $GetCsDataService;
    private $UpdateConsumerDataService;

    public function __construct(
        GetCsDataServiceInterface          $GetCsDataService,
        UpdateConsumerDataServiceInterface $UpdateConsumerDataService
    )
    {
        $this->middleware('auth:api');
        $this->GetCsDataService          = $GetCsDataService;
        $this->UpdateConsumerDataService = $UpdateConsumerDataService;
    }

    public function store(Request $request)
    {
        \Log::info($request);

        $whereData  = ['ats_consumer_id' => urldecode($request->get('id'))];
        $updateData =  $request->get('consumerData');
        
        $this->UpdateConsumerDataService->updateCsData(
            $whereData, 
            $updateData
        );
        
        return ['status' => '200'];
    }
}