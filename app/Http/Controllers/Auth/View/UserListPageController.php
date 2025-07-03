<?php

namespace App\Http\Controllers\Auth\View;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use packages\User\UseCase\Read\GetUserServiceInterface;

class UserListPageController extends Controller
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
        $user   = Auth::user();
        $userId = '';
        
        if ($user->authority != 'master') {
            $userId = $user->id;
        }

        $userPageData = $this->GetUserService->getUserPageData($request, true, $userId);
        $urlParam     = $request->all();
        unset($urlParam['page']);

        return view(
            'auth.company.userList',
            [
                'versionParam' => config('app.versionParam'),
                'apiToken'     => $user->api_token,
                'userPageData' => $userPageData,
                'urlParam'     => $urlParam,
                'userId'       => $userId,
                'authority'    => $user->authority
            ]
        );
    }
}


