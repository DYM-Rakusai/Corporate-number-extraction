<?php

namespace App\Http\Controllers\Auth\View;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use packages\Consumer\UseCase\Read\GetCsDataServiceInterface;

class CsListPageController extends Controller
{
    private $GetCsDataService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        GetCsDataServiceInterface $GetCsDataService
    )
    {
        $this->middleware('auth');
        $this->GetCsDataService = $GetCsDataService;
    }

    public function index(Request $request)
    {
        $user   = Auth::user();
        $userId = '';
        if ($user->authority != 'master') {
            $userId = $user->id;
        }
        $csPageData = $this->GetCsDataService->getCsPageData($request, true, $userId);
        $urlParam   = $request->all();
        unset($urlParam['page']);

        return view(
            'auth.company.consumerList',
            [
                'versionParam' => config('app.versionParam'),
                'apiToken'     => $user->api_token,
                'csPageData'   => $csPageData,
                'statusColors' => config('Consumer.statusList.statusColors'),
                'statusList'   => config('Consumer.statusList.statusList'),
                'urlParam'     => $urlParam,
                'userId'       => $userId,
            ]
        );
    }
}


