<?php

namespace App\Http\Controllers\Auth\View;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use packages\Job\UseCase\Read\GetJobKeywordServiceInterface;
use packages\User\UseCase\Read\GetUserServiceInterface;

class ManualAddCsPageController extends Controller
{
    private $GetJobKeywordService;
    private $GetUserService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        GetJobKeywordServiceInterface $GetJobKeywordService,
        GetUserServiceInterface       $GetUserService
    ) {
        $this->GetJobKeywordService = $GetJobKeywordService;
        $this->GetUserService       = $GetUserService;
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user          = Auth::user();
        $userDataArray = array();
        if ($user->authority == 'master') {
            #$userDataArray = $this->GetUserService->getUserAllData();
            #$userDataArray = json_decode($userDataArray);
            #$userNameArray = array_column($userDataArray, 'name');
            $jobDataArray = $this->GetJobKeywordService->getAllJobKeyword();
        }else{
            #$userNameArray = [$user->name];
            $jobDataArray = $this->GetJobKeywordService->getJobMappingByUserId($user->id);
        }
        $data = [
            'versionParam' => config('app.versionParam'),
            'apiToken'     => $user->api_token,
            'csAddConf'    => config('Consumer.csAddConf'),
            #'userName' => $userNameArray,
            #'job' => $jobDataArray,
        ];

        /*
        $data['csAddConf']['userName'] = [
            'formType' => 'select',
            'formTitle' => '面接担当者',
            'choiceConf' => $userNameArray,
            'isNeed' => 1,
            'remarks' => ''
        ];*/

        return view( 'auth.company.manualAddCs', $data);
    }
}
