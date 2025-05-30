<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public function show($id)
    {
        $product = Product::with('reviews.user')->findOrFail($id);
        return view('products.show', compact('product'));
    }
}
