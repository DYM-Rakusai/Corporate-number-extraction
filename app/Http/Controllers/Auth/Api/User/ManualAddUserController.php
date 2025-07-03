<?php
declare(strict_types=1);
namespace App\Http\Controllers\Auth\Api\User;

use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use packages\User\UseCase\Create\CreateUserDataServiceInterface;

class ManualAddUserController extends Controller
{
    private $CreateUserDataService;
    private $nowCarbon;
    
    public function __construct(
        CreateUserDataServiceInterface $CreateUserDataService
    )
    {
        $this->CreateUserDataService = $CreateUserDataService;
        $this->nowCarbon = Carbon::now('Asia/Tokyo');
        $this->middleware('auth:api');
    }

    public function store(Request $request)
    {
        \Log::info($request);
        $manualAddData  = $request['manualAddData'];
        $tel            = str_replace('-', '', $manualAddData['tel']);
        $hashedPassword = password_hash($tel, PASSWORD_DEFAULT);
      
        $userData = [
            'name'          => $manualAddData['name'],
            'kana'          => $manualAddData['kana'] ?? '',
            'tel'           => $tel,
            'mail'          => $manualAddData['mail'],
            'login_id'      => $manualAddData['mail'],
            'password'      => $hashedPassword,
            'authority'     => 'user',
            'interview_url' => $manualAddData['interview_url'],
            'api_token'     => '$ik.E~~Q-N+7',
            'created_at'    => $this->nowCarbon
        ];

        $this->CreateUserDataService->insertUserData($userData);
        
        return response()->json(['message' => 'アカウント追加'], 200); 
    }
}





