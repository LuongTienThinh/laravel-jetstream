<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepositoryEloquent;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Put;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;

class UserController extends Controller
{
    use ApiResponseTrait;

    /**
     * The user service variable
     *
     * @var UserRepositoryEloquent
     */
    protected UserRepositoryEloquent $userRepositoryEloquent;

    /**
     * Constructor function for UserController
     *
     * @param UserRepositoryEloquent $userRepositoryEloquent
     */
    public function __construct(UserRepositoryEloquent $userRepositoryEloquent)
    {
        $this->userRepositoryEloquent = $userRepositoryEloquent;
    }

    /**
     * Update user info
     *
     * @param  Request $request
     * @return JsonResponse
     */
    #[Put(
        path: '/api/user/address/update',
        operationId: "updateUser",
        description: "Update a user's information in users table",
        summary: "Update a user",
        requestBody: new RequestBody(
            required: true,
            content: new JsonContent(
                properties: [
                    new Property(property: "name", type: "string", example: "John Smith"),
                ]
            )
        ),
        tags: ['users'],
        responses: [
            new Response(
                response: 200,
                description: 'Success',
                content: new JsonContent(
                    properties: [
                        new Property(property: "message", type: "string", example: "Update a user success.")
                    ]
                )
            ),
            new Response(
                response: 500,
                description: 'Error',
                content: new JsonContent(
                    properties: [
                        new Property(property: "message", type: "string", example: "Internal server error.")
                    ]
                )
            ),
        ]
    )]
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
