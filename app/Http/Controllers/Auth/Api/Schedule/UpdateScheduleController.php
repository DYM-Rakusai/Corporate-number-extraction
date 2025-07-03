<?php

namespace App\Http\Controllers\Auth\Api\Schedule;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use packages\Schedule\UseCase\Delete\DeleteScheduleServiceInterface;
use packages\Schedule\UseCase\Create\InsertScheduleServiceInterface;
use packages\Schedule\UseCase\Shap\ShapScheduleServiceInterface;

class UpdateScheduleController extends Controller
{
    private $DeleteScheduleService;
    private $InsertScheduleService;
    private $ShapScheduleService;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        DeleteScheduleServiceInterface $DeleteScheduleService,
        InsertScheduleServiceInterface $InsertScheduleService,
        ShapScheduleServiceInterface   $ShapScheduleService
        
    ) {
        $this->middleware('auth:api');
        $this->DeleteScheduleService = $DeleteScheduleService;
        $this->InsertScheduleService = $InsertScheduleService;
        $this->ShapScheduleService   = $ShapScheduleService;
        
    }

    public function store(Request $request)
    {
        \Log::info('---------$UpdateScheduleController---------');
        \Log::info($request);

        if ($request->get('apiToken') !== config('app.api_token')) {
            throw new \Exception('APIトークンが不正です。');
            return;
        }
        $ableScheduleData = $request->get('ableScheduleData');
        $user             = Auth::user();
        $userId           = $request['userId'] ?? '';
        
        if (empty($userId)) {
            $userId = $user->id;
            \Log::info($user);
        }
    
        [$insertDatas, $deleteScheduleIds] = $this->ShapScheduleService->getScheduleDataForUpdate($ableScheduleData, $userId);
        $this->DeleteScheduleService->deleteSchedules($deleteScheduleIds, $userId);
        $this->InsertScheduleService->insertScheduleData($insertDatas);
        return;
    }
}


