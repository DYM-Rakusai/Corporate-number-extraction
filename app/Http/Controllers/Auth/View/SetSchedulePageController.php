<?php

namespace App\Http\Controllers\Auth\View;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use packages\User\UseCase\Read\GetUserServiceInterface;
use packages\Schedule\UseCase\Shap\ShapScheduleServiceInterface;

class SetSchedulePageController extends Controller
{
    private $GetUserService;
    private $ShapScheduleService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        GetUserServiceInterface $GetUserService,
        ShapScheduleServiceInterface $ShapScheduleService
    ) {
        $this->middleware('auth');
        $this->GetUserService = $GetUserService;
        $this->ShapScheduleService = $ShapScheduleService;
    }

    public function index(Request $request)
    {
        $user          = Auth::user();
        $userDataArray = array();
        $userId        = $user->id;

        if ($user->authority == 'master') {
            $userId        = $request['userId'] ?? 0;
            $userDataArray = $this->GetUserService->getUserAllData();
        }
        $userName = '';

        if ($userId != 0) {
            $userName = $this->GetUserService->getUserValById($userId, 'name');
        }

        $forPageScheduleData     = $this->ShapScheduleService->scheduleForAdminPage($userId);
        $forPageScheduleDataJson = json_encode($forPageScheduleData);

        return view(
            'auth.company.calendar',
            [
                'versionParam'            => config('app.versionParam'),
                'forPageScheduleDataJson' => $forPageScheduleDataJson,
                'apiToken'                => $user->api_token,
                'userDataArray'           => $userDataArray,
                'userName'                => $userName,
                'userId'                  => $userId,
            ]
        );
    }
}
