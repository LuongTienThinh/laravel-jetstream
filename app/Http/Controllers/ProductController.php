<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

use Illuminate\Database\Eloquent\Builder;
use OpenApi\Annotations as OA;
use \Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateProductRequest;
use App\Traits\ApiResponseTrait;

class ProductController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     * 
     * @param Request $request
     * @return JsonResponse
     */

    #[OA\Get(
        path: '/api/users',
        responses: [
            new OA\Response(response: 200, description: 'AOK'),
            new OA\Response(response: 401, description: 'Not allowed'),
        ]
    )]
    public function index(Request $request): JsonResponse
    {
        $search = $request->search;
        $page = $request->page;

        // dd($page, $search);

        if (isset($search)) {
            $products = $this->filterProduct($search);
            return $this->productPagination($products, $page);
        }

        return $this->productPagination(Product::with('category'), $page);
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
    public function store(UpdateProductRequest $request): JsonResponse
    {

        try {
            Product::create($request->validated());

            $message = 'Product created successfully';

            return $this->successResponse(null, 200, $message);
        } catch (\Exception $e) {
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
    public function update(Request $request, string $id): JsonResponse
    {   
        try {
            $name = $request->input('name');
            $price = floatval($request->input('price'));
            $quantity = intval($request->input('quantity'));
            $category = intval($request->input('category'));
    
            $product = Product::findOrFail($id);
            $product->name = $name;
            $product->price = $price;
            $product->quantity = $quantity;
            $product->category_id = $category;

            $product->save();

            $message = 'Product updated successfully';

            return $this->successResponse(null, 200, $message);
        } catch (\Exception $e) {
            return $this->errorResponse(500, $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param  string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found.'], 404);
        }

        try {
            $product->delete();
            $message = 'Product deleted successfully';

            return $this->successResponse(null, 200, $message);
        } catch (\Exception $e) {
            return $this->errorResponse(500, $e->getMessage());
        }
    }

    /**
     * Paginate for the list of products
     * 
     * @param Builder $listProduct
     * @param int     $page
     * @param int     $perPage
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

            $message = 'Get all products successfully';

            return $this->successResponse($newData, 200, $message);
        } catch (\Exception $e) {
            return $this->errorResponse(500, $e->getMessage());
        }
    }

    /**
     * Filter list of products with condition
     * 
     * @param  Request $request
     * @return Builder
     */
    public function filterProduct(string $search): Builder
    {
        return Product::where('name', 'like', '%' . $search . '%')->with('category');
    }
}
