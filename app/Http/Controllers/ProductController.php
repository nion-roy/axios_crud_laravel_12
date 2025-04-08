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

    public function getProducts(Request $request)
    {
        return Product::latest('id')->get();
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
            'status' => true,
            'message' => 'Product created successfully',
        ]);
    }
}
