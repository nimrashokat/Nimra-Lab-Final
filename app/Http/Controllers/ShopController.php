<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with('category')
            ->when($request->filled('search'), fn($q) => $q->where('name', 'like', '%' . $request->search . '%'))
            ->when($request->filled('sort') && $request->sort === 'price_low', fn($q) => $q->orderBy('price'))
            ->when($request->filled('sort') && $request->sort === 'price_high', fn($q) => $q->orderByDesc('price'))
            ->latest()
            ->paginate(12);

        return view('shop.index', compact('products'));
    }

    public function category(string $slug)
    {
        $category = Category::whereSlug($slug)->firstOrFail();
        $products = $category->products()->latest()->paginate(12);

        return view('shop.category', compact('category', 'products'));
    }

    public function show(Product $product)
    {
        return view('shop.show', compact('product'));
    }
}
