<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $items = CartItem::with('product')->where('user_id', $request->user()->id)->get();
        return view('shop.cart', compact('items'));
    }

    public function store(Request $request, Product $product)
    {
        CartItem::updateOrCreate(
            ['user_id' => $request->user()->id, 'product_id' => $product->id],
            ['quantity' => 1]
        );
        return back()->with('success', 'Added to cart');
    }

    public function destroy(CartItem $cartItem)
    {
        $cartItem->delete();
        return back()->with('success', 'Removed from cart');
    }
}
