<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepositoryEloquent;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    use ApiResponseTrait;

    public UserRepositoryEloquent $userRepositoryEloquent;

    public function __construct(UserRepositoryEloquent $userRepositoryEloquent)
    {
        $this->userRepositoryEloquent = $userRepositoryEloquent;
    }
    public function update(Request $request): JsonResponse
    {
        try {
            $userId = Auth::user()->id;

            $this->userRepositoryEloquent->update($request->all(), $userId);

            return $this->successResponse(null, 200, 'Update user success.');
        } catch (Exception $e) {
            return $this->errorResponse(500, $e->getMessage());
        }
    }
}
