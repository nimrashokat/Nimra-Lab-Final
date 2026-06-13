@extends('layouts.app')
@use('Illuminate\Support\Facades\Storage')
@section('content')
<div class="container py-5">
    <h2 class="mb-4">{{ $category->name }}</h2>
    <div class="row g-3">
        @forelse($products as $product)
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card h-100">
                    <img src="{{ $product->image ? Storage::url($product->image) : 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=700&q=80' }}"
     class="card-img-top" style="height:200px; object-fit:cover;"
     onerror="this.src='https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=700&q=80'">
                    <div class="card-body">
                        <h6>{{ $product->name }}</h6>
                        <p class="mb-1">Rs {{ number_format($product->price) }}</p>
                        <p class="text-warning">★ {{ $product->rating }}</p>
                        <div class="d-flex gap-2">
                            <form method="POST" action="{{ route('cart.store', $product->id) }}">@csrf<button class="btn btn-sm btn-pink">Add to Cart</button></form>
                            <form method="POST" action="{{ route('favorites.store', $product->id) }}">@csrf<button class="btn btn-sm btn-outline-dark">❤</button></form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p>No products found in this category.</p>
        @endforelse
    </div>
</div>
@endsection
