@extends('layouts.app')
@use('Illuminate\Support\Facades\Storage')

@section('content')
<div class="container py-5">
    <div class="row g-4">
        <div class="col-md-6">
           <img src="{{ $product->image ? Storage::url($product->image) : 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=900&q=80' }}"
     class="w-100 rounded"
     style="height: 450px; object-fit: cover;"
     onerror="this.src='https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=900&q=80'">
        </div>
        <div class="col-md-6">
            <h2>{{ $product->name }}</h2>
            <p class="text-warning">★★★★★ ({{ $product->rating }})</p>
            <p>{{ $product->description }}</p>
            <h4>Rs {{ number_format($product->price) }}</h4>
            <span class="badge bg-dark">{{ $product->discount_percent }}% Discount</span>
            <div class="mt-3 d-flex gap-2">
                <form method="POST" action="{{ route('cart.store', $product->id) }}">@csrf<button class="btn btn-pink">Add to Cart</button></form>
                <form method="POST" action="{{ route('favorites.store', $product->id) }}">@csrf<button class="btn btn-outline-dark">Favorite</button></form>
            </div>
        </div>
    </div>
</div>
@endsection
