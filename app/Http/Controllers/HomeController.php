<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->where('is_active', true);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->latest()->paginate(8);
        $categories = Category::all();
        $reviews = Review::latest()->take(6)->get();

        return view('welcome', compact('products', 'categories', 'reviews'));
    }

    public function sizeChart()
    {
        return view('shop.size-chart');
    }
}
