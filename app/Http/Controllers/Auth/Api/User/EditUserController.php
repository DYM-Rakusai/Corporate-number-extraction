<?php
declare(strict_types=1);
namespace App\Http\Controllers\Auth\Api\User;

use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use packages\User\UseCase\Update\UpdateUserServiceInterface;

class EditUserController extends Controller
{
    private $UpdateUserService;

    public function __construct(
        UpdateUserServiceInterface $UpdateUserService
    )
    {
        $this->middleware('auth:api');
        $this->UpdateUserService = $UpdateUserService;
    }

    public function store(Request $request)
    {
        \Log::info($request);
        $UserId               = $request->get('id');
        $userData             = $request->get('userData');

        $whereData            = ['id' => $UserId];
        $tel                  = str_replace('-', '', $userData['tel']);
        $userData['password'] = password_hash($tel, PASSWORD_DEFAULT);
        $userData['login_id'] = $userData['mail'];

        $this->UpdateUserService->updateUserData($whereData, $userData);

        return ['status' => '200'];
    }
}