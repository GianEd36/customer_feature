<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request; 

class HomeController extends Controller
{
   public function index(Request $request)
    {
        $query = Product::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Optionally show featured products
        $featured = Product::inRandomOrder()->take(3)->get();

        $products = $query->latest()->paginate(12);

        $categories = Product::select('category')->distinct()->pluck('category')->filter()->values();

        return view('home', compact('products', 'categories', 'featured'));
    }

}
