<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;

class ProductController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->productPagination(Product::query()->with('category'));
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
     */
    public function store(Request $request)
    {
        $name = $request->input("name");
        $price = floatval($request->input("price"));
        $quantity = intval($request->input("quantity"));
        $category_id = $request->input("category");

        Product::create([
            'name' => $name,
            'price' => $price,
            'quantity' => $quantity,
            'category_id' => $category_id,
        ]);

        return redirect()->back();
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
     */
    public function update(Request $request, string $id)
    {   
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'category' => 'required',
        ]);
        $data = $request->all();
        
        // Tiếp tục xử lý cập nhật vào cơ sở dữ liệu
        
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

        return response()->json(['message' => 'Product updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found.'], 404);
        }

        $product->delete();

        return redirect()->back();
    }

    public function productPagination(Builder $listProduct, $perPage = 5)
    {
        $listCategories = Category::all();
        $products = $listProduct->simplePaginate($perPage);

        $data = [
            'products' => $products,
            'categories' => $listCategories,
        ];

        try {
            $listProducts = collect($data['products']->items())->map(function($item) {
                $item->category_name = $item->category->name;
                unset($item->category);
                return $item;
            });

            $newData = [
                'products'=> $listProducts,
                'categories' => $data['categories'],
            ];

            $message = 'Get all products successfully';

            return $this->successResponse($newData, 200, $message);
        } catch (\Exception $e) {
            return $this->errorResponse(500, $e->getMessage());
        }
    }

    public function filterProduct(Request $request)
    {
        $search = $request->input('search');
        
        if (isset($search) ) {
            $products = Product::where('name', 'like', '%' . $search . '%')->with('category');
            return $this->productPagination($products);
        }
        return $this->index();
    }
}
