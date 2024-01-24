<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Traits\ApiResponseTrait;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;
use OpenApi\Attributes\Delete;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Put;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Schema;

class CategoryController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    #[Get(
        path: '/api/category',
        operationId: "getListCategories",
        description: "Get list categories.",
        summary: "Get list categories",
        tags: ['categories'],
        responses: [
            new Response(
                response: 200,
                description: 'Success',
                content: new JsonContent(
                    properties: [
                        new Property(property: "status", type: "int", example: 200),
                        new Property(property: "message", type: "string", example: "Get list categories success.")
                    ]
                )
            ),
            new Response(
                response: 500,
                description: 'Error',
                content: new JsonContent(
                    properties: [
                        new Property(property: "status", type: "int", example: 500),
                        new Property(property: "message", type: "string", example: "Internal server error.")
                    ]
                )
            ),
        ]
    )]
    public function index(): JsonResponse
    {
        try {
            $message = 'Get list categories success.';
            $categories = Category::all();
            return $this->successResponse($categories, 200, $message);
        } catch (Exception $e) {
            return $this->errorResponse(500, $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return JsonResponse
     */
    #[Post(
        path: '/api/category/create',
        operationId: "createCategory",
        description: "Create a category and add it into categories table.",
        summary: "Create a category",
        requestBody: new RequestBody(
            required: true,
            content: new JsonContent(
                properties: [
                    new Property(property: "name", type: "string", example: "Screen"),
                ]
            )
        ),
        tags: ['categories'],
        responses: [
            new Response(
                response: 200,
                description: 'Success',
                content: new JsonContent(
                    properties: [
                        new Property(property: "message", type: "string", example: "Create a category successfully.")
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
    public function store(Request $request): JsonResponse
    {
        $name = $request->input("name");

        Category::query()->create([
            'name' => $name,
        ]);

        return response()->json(['message' => 'Success.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  string  $id
     * @return JsonResponse
     */
    #[Put(
        path: '/api/category/edit/{id}',
        operationId: "updateCategory",
        description: "Update a category's information in categories table",
        summary: "Update a category",
        requestBody: new RequestBody(
            required: true,
            content: new JsonContent(
                properties: [
                    new Property(property: "name", type: "string", example: "Screen"),
                ]
            )
        ),
        tags: ['categories'],
        parameters: [
            new Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new Schema(
                    type: "string"
                )
            )
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Success',
                content: new JsonContent(
                    properties: [
                        new Property(property: "message", type: "string", example: "Update a category success.")
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
    public function update(Request $request, string $id): JsonResponse
    {
        $name = $request->input("name");

        $category = Category::query()->find($id);

        $category->name = $name;

        $category->save();

        return response()->json(['message' => 'Success.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string $id
     * @return JsonResponse
     */
    #[Delete(
        path: '/api/category/delete/{id}',
        operationId: "deleteCategory",
        description: "Delete a category's information in categories table",
        summary: "Delete a category",
        tags: ['categories'],
        parameters: [
            new Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new Schema(
                    type: "string"
                )
            )
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Success',
                content: new JsonContent(
                    properties: [
                        new Property(property: "message", type: "string", example: "Delete a category success.")
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
    public function destroy(string $id): JsonResponse
    {
        $category = Category::query()->find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found.'], 404);
        }

        $category->delete();

        return response()->json(['message' => 'Success.']);
    }

    /**
     * Show view category
     *
     * @return View|Application|Factory|string|null
     */
    public function viewCategory(): View|Application|Factory|string|null
    {
        return view('category');
    }
}
