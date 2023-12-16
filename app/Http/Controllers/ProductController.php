<?php

namespace App\Http\Controllers;

use App\Models\Product;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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

        Product::create([
            'name' => $name,
            'price' => $price,
            'quantity' => $quantity,
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
        $name = $request->input("name");
        $price = floatval($request->input("price"));
        $quantity = intval($request->input("quantity"));

        $product = Product::find($id);

        $product->name = $name;
        $product->price = $price;
        $product->quantity = $quantity;

        $product->save();

        return redirect()->back();
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

    public function productPagination(Request $request) {
        $products = Product::paginate(5);
        return view('product')->with(['products' => $products]);
    }
}
