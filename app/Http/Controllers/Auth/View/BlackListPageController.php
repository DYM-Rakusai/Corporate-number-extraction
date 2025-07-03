<?php

namespace App\Http\Controllers\Auth\View;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use packages\BlackList\UseCase\Read\GetBlackListServiceInterface;
use Illuminate\Support\Facades\Auth;

class BlackListPageController extends Controller
{
    private $GetBlackListService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        GetBlackListServiceInterface $GetBlackListService
    )
    {
        $this->middleware('auth');
        $this->GetBlackListService = $GetBlackListService;
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $blackListPageData = $this->GetBlackListService->getBlackListData();
        return view(
            'auth.company.blackList',
            [
                'versionParam'      => config('app.versionParam'),
                'blackListPageData' => $blackListPageData,
                'apiToken'          => $user->api_token
            ]
        );
    }
}


