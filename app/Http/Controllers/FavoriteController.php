<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        $favorites = Favorite::with('product')->where('user_id', $request->user()->id)->get();
        return view('shop.favorites', compact('favorites'));
    }

    public function store(Request $request, Product $product)
    {
        Favorite::firstOrCreate([
            'user_id' => $request->user()->id,
            'product_id' => $product->id,
        ]);
        return back()->with('success', 'Added to favorites');
    }

    public function destroy(Favorite $favorite)
    {
        $favorite->delete();
        return back()->with('success', 'Removed from favorites');
    }
}
