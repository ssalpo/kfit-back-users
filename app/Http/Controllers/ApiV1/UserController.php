<?php
namespace App\Http\Controllers\ApiV1;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    public function index(): JsonResponse
    {
        $users = User::all();
        return $this->response($users);
    }

    public function me(Request $request): JsonResponse
    {
        $user = $request->user();
        return $this->response($user);
    }
}
