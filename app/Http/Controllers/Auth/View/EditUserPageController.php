<?php

namespace App\Http\Controllers\Auth\View;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use packages\User\UseCase\Read\GetUserServiceInterface;

class EditUserPageController extends Controller
{
    private $GetUserService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        GetUserServiceInterface $GetUserService
    )
    {
        $this->middleware('auth');
        $this->GetUserService = $GetUserService;
    }

    public function index(Request $request)
    {
        $user     = Auth::user();
        $userId   = $request->get('id');
        $userData = $this->GetUserService->getUserData('id', $userId, true);
        \Log::info($userData);

        if (empty($userData)) {
            return view('auth.404');
        }
        
        $data = [
            'versionParam' => config('app.versionParam'),
            'userEditConf' => config('User.userEditConf'),
            'userkeys'     => config('User.userDataTitles'),
            'apiToken'     => $user->api_token,
            'userData'     => $userData
        ];
        return view('auth.company.user.userEdit', $data);
    }
}



