<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(12);
        $categories = Category::all();
        return view('admin.products.index', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:120'],
            'price' => ['required', 'numeric'],
            'rating' => ['nullable', 'numeric', 'between:1,5'],
            'discount_percent' => ['nullable', 'integer', 'min:0', 'max:90'],
            'sizes' => ['nullable', 'array'],
            'image' => ['nullable', 'image', 'max:4096'],
        ]);

        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(5);
        $validated['description'] = $request->description;
        $validated['sizes'] = $request->sizes ?? ['S', 'M', 'L', 'XL'];
        $validated['rating'] = $request->rating ?? 0;
        $validated['is_active'] = true;

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($validated);

        return back()->with('success', 'Product created');
    }

    public function update(Request $request, Product $product)
    {
        $product->update($request->only([
            'category_id', 'name', 'price', 'rating', 'description', 'discount_percent'
        ]));
        return back()->with('success', 'Product updated');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('success', 'Product deleted');
    }
}
