<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return view('products.index');
    }

    public function getProducts()
    {
        return Product::orderByDesc('id')->get();
    }

    public function store(Request $request)
    {
        $validation = $request->validate([
            'product_name' => 'required|string|max:255|unique:products',
            'product_price' => 'required|numeric',
            'product_details' => 'nullable|string',
        ]);

        $product = new Product();
        $product->product_name =  $validation['product_name'];
        $product->product_price =  $validation['product_price'];
        $product->product_details =  $validation['product_details'];
        $product->save();

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully',
        ]);
    }



    public function show($id)
    {
        $product = Product::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $product,
            'message' => 'Product retrieve successfully.'
        ]);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validation = $request->validate([
            'product_name' => 'required|string|max:255|unique:products,product_name,' . $id,
            'product_price' => 'required|numeric',
            'product_details' => 'nullable|string',
        ]);

        $product->update($validation);

        return response()->json([
            'status' => true,
            'message' => 'Product updated successfully',
        ]);
    }



    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found.!'
            ]);
        }

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully'
        ]);
    }
}
