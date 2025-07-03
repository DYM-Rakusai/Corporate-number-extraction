<?php

namespace App\Http\Controllers\Auth\View;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use packages\Job\UseCase\Read\GetJobKeywordServiceInterface;
use packages\User\UseCase\Read\GetUserServiceInterface;

class JobMappingPageController extends Controller
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
        $this->middleware('auth');
        $this->GetJobKeywordService = $GetJobKeywordService;
        $this->GetUserService       = $GetUserService;
    }

    public function index(Request $request)
    {
        $user          = Auth::user();
        $userId        = $user->id;
        $userDataArray = array();

        if ($user->authority == 'master') {
            $userId        = $request['userId'] ?? 0;
            $userDataArray = $this->GetUserService->getUserAllData();
        }

        $targetKeyword = '';
        $userName      = '';

        if ($userId != 0) {
            $userName      = $this->GetUserService->getUserValById($userId, 'name');
            $targetKeyword = $this->GetJobKeywordService->getJobMappingByUserId($userId);
            if (empty($targetKeyword)) {
                $targetKeyword = '';
            }
        }

        $data = [
            'versionParam'   => config('app.versionParam'),
            'apiToken'       => $user->api_token,
            'userDataArray'  => $userDataArray,
            'targetKeywords' => $targetKeyword,
            'userName'       => $userName,
            'userId'         => $userId,
        ];

        return view('auth.company.jobMapping', $data);
    }
}
