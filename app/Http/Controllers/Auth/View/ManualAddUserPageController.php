<?php

namespace App\Http\Controllers\Auth\View;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use packages\User\UseCase\Read\GetUserServiceInterface;

class ManualAddUserPageController extends Controller
{
    private $GetUserService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        GetUserServiceInterface $GetUserService
    ) {
        $this->GetUserService = $GetUserService;
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user          = Auth::user();
        $userDataArray = array();

        if ($user->authority == 'master') {
            $userDataArray = $this->GetUserService->getUserAllData();
            $userDataArray = json_decode($userDataArray);
            $userNameArray = array_column($userDataArray, 'name');
        }

        $data = [
            'versionParam' => config('app.versionParam'),
            'userAddConf'  => config('User.userAddConf'),
            'userName'     => $userNameArray,
            'apiToken'     => $user->api_token,
        ];

        return view('auth.company.user.addUserForm', $data);
    }
}
