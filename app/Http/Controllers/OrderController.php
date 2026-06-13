<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\CartItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function create()
    {
        return view('shop.order-form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:50'],
            'last_name' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email'],
            'phone' => ['required', 'string', 'max:20'],
            'design_choice' => ['required', 'string', 'max:120'],
            'size' => ['required', 'string', 'max:20'],
            'fabric_details' => ['required', 'string', 'max:255'],
            'delivery_address' => ['required', 'string', 'max:255'],
            'special_instructions' => ['nullable', 'string'],
            'design_image' => ['nullable', 'image', 'max:4096'],
        ]);

        // Cart se total aur pehli product ki image lo
        $cartItems = CartItem::where('user_id', auth()->id())->with('product')->get();
        $total = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);

        // Agar customer ne khud image upload ki toh woh use karo
        // Warna cart ki pehli product ki image automatically set karo
        if ($request->hasFile('design_image')) {
            $validated['design_image'] = $request->file('design_image')->store('designs', 'public');
        } else {
            $firstProduct = $cartItems->first()?->product;
            if ($firstProduct && $firstProduct->image) {
                $validated['design_image'] = $firstProduct->image;
            }
        }

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'pending';
        $validated['total_amount'] = $total;

        Order::create($validated);

        return back()->with('success', 'Order placed successfully!');
    }
}
