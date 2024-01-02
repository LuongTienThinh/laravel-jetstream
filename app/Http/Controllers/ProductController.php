<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\UpdateProductRequest;
use App\Repositories\ProductRepository;
use App\Traits\ApiResponseTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes\Delete;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\Put;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Schema;
use Exception;

/**
 * @OA\Info(title="My First API", version="0.1")
 */
class ProductController extends Controller
{
    use ApiResponseTrait;

    public ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Request $request
     * @return JsonResponse
     */
    #[Get(
        path: '/api/product/get-list',
        operationId: "getListProducts",
        description: "Get list products by search (default: empty) for each page (default: 1).",
        summary: "Get list products",
        parameters: [
            new Parameter(
                name: "search",
                in: "query",
                required: false,
                schema: new Schema(
                    type: "string",
                )
            ),
            new Parameter(
                name: "page",
                in: "query",
                required: false,
                schema: new Schema(
                    type: "int",
                )
            )
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Success',
                content: new JsonContent(
                    properties: [
                        new Property(property: "status", type: "int", example: 200),
                        new Property(property: "message", type: "string", example: "Get list products successfully.")
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
    public function index(Request $request): JsonResponse
    {
        $search = $request->get('search');
        $page = $request->get('page');

        if (isset($search)) {
            $products = $this->productRepository->filterSearch($search);
            return $this->productPagination($products, $page);
        }
        return $this->productPagination($this->productRepository->getProductWith(), $page);
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
     * @param  UpdateProductRequest $request
     * @return JsonResponse
     */
    #[Post(
        path: '/api/product/create',
        operationId: "createProduct",
        description: "Create a product and add it into products table.",
        summary: "Create a product",
        requestBody: new RequestBody(
            required: true,
            content: new JsonContent(
                properties: [
                    new Property(property: "name", type: "string", example: "Redmi note 9"),
                    new Property(property: "price", type: "float", example: "48.500"),
                    new Property(property: "quantity", type: "int", example: "20"),
                    new Property(property: "category_id", type: "int", example: "1"),
                ]
            )
        ),
        responses: [
            new Response(
                response: 200,
                description: 'Success',
                content: new JsonContent(
                    properties: [
                        new Property(property: "message", type: "string", example: "Create a product successfully.")
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
    public function store(UpdateProductRequest $request): JsonResponse
    {
        try {
            $this->productRepository->create($request->validated());

            $message = 'Product created successfully';
            return $this->successResponse(null, 200, $message);
        } catch (Exception $e) {
            return $this->errorResponse(500, $e->getMessage());
        }
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
        path: '/api/product/edit/{id}',
        operationId: "updateProduct",
        description: "Update a product's information in products table",
        summary: "Update a product",
        requestBody: new RequestBody(
            required: true,
            content: new JsonContent(
                properties: [
                    new Property(property: "name", type: "string", example: "Redmi note 9"),
                    new Property(property: "price", type: "number", format: "float", example: 48500),
                    new Property(property: "quantity", type: "int", example: 20),
                    new Property(property: "category", type: "int", example: 1),
                ]
            )
        ),
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
                        new Property(property: "message", type: "string", example: "Update a product successfully.")
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
    public function update(UpdateProductRequest $request, string $id): JsonResponse
    {
        try {
            $result = $this->productRepository->update($request->validated(), $id);
            if ($result) {
                $message = 'Product updated successfully';
                return $this->successResponse(null, 200, $message);
            } else {
                return response()->json(['message' => 'Product not found.'], 404);
            }
        } catch (Exception $e) {
            return $this->errorResponse(500, $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string $id
     * @return JsonResponse
     */
    #[Delete(
        path: '/api/product/delete/{id}',
        operationId: "deleteProduct",
        description: "Delete a product's information in products table",
        summary: "Delete a product",
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
                        new Property(property: "message", type: "string", example: "Delete a product successfully.")
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
        try {
            $result = $this->productRepository->delete($id);
            if ($result) {
                $message = 'Product deleted successfully';
                return $this->successResponse(null, 200, $message);
            } else {
                return response()->json(['message' => 'Product not found.'], 404);
            }
        } catch (Exception $e) {
            return $this->errorResponse(500, $e->getMessage());
        }
    }

    /**
     * Paginate for the list of products
     *
     * @param  Builder  $listProduct
     * @param  int|null $page
     * @param  int|null $perPage
     * @return JsonResponse
     */
    public function productPagination(Builder $listProduct, int $page = null, int $perPage = null): JsonResponse
    {
        $page = $page ?? 1;
        $perPage = $perPage ?? 5;

        $products = $listProduct->skip(($page - 1) * $perPage)->take($perPage)->get();
        $nextProducts = $listProduct->skip($page * $perPage)->take($perPage)->get();

        $prev = $page > 1 && $products->isNotEmpty();
        $next = $nextProducts->isNotEmpty();

        try {
            $listProducts = $products->map(function($item, $index) use($page, $perPage) {
                $item->category_name = $item->category->name;
                $item->no = ($page - 1) * $perPage + 1 + $index;
                unset($item->category);
                return $item;
            });

            $newData = [
                'products'=> $listProducts,
                'prev' => $prev,
                'next' => $next
            ];

            $message = 'Get list products successfully';

            return $this->successResponse($newData, 200, $message);
        } catch (Exception $e) {
            return $this->errorResponse(500, $e->getMessage());
        }
    }
}
