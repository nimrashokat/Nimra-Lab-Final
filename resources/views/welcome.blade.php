@extends('layouts.app')
@use('Illuminate\Support\Facades\Storage')

@section('content')
<section class="hero d-flex align-items-center">
    <div class="container text-center">
        <h5 class="text-uppercase">Welcome to SheStitch</h5>
        <h1 class="display-4 fw-bold">Design Your Dream Dress</h1>
        <p class="mb-4">Elegant custom tailoring for every occasion.</p>
        <form class="row justify-content-center g-2" action="{{ route('shop.index') }}">
            <div class="col-md-6">
                <input type="text" class="form-control form-control-lg" name="search" placeholder="Search products and designs...">
            </div>
            <div class="col-auto">
                <button class="btn btn-pink btn-lg">Search</button>
            </div>
        </form>
        <div class="mt-4 d-flex justify-content-center gap-3">
            <a href="/login" class="btn btn-light">Login as User</a>
            <a href="/login" class="btn btn-dark">Login as Admin</a>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <h2 class="mb-4">Categories</h2>
        @php
        $categoryImages = [
            'neck-designs'    => 'https://images.unsplash.com/photo-1594938298603-c8148c4dae35?w=500&q=80',
            'sleeve-designs'  => 'https://images.unsplash.com/photo-1551488831-00ddcb6c6bd3?w=500&q=80',
            'daman-designs'   => 'https://images.unsplash.com/photo-1610030469983-98e550d6193c?w=500&q=80',
            'trouser-designs' => 'https://images.unsplash.com/photo-1509631179647-0177331693ae?w=500&q=80',
            'suit-designs'    => 'https://images.unsplash.com/photo-1585487000160-6ebcfceb0d03?w=500&q=80',
            'frock-designs'   => 'https://images.unsplash.com/photo-1518611012118-696072aa579a?w=500&q=80',
            'lehenga-designs' => 'https://images.unsplash.com/photo-1583391733956-3750e0ff4e8b?w=500&q=80',
            'maxi-designs'    => 'https://images.unsplash.com/photo-1496747611176-843222e1e57c?w=500&q=80',
            'bridal-designs'  => 'https://images.unsplash.com/photo-1519657337289-077653f724ed?w=500&q=80',
        ];
        @endphp
        <div class="row g-3">
            @foreach($categories as $category)
                @php
                    $firstProduct = $category->products()->whereNotNull('image')->first();
                    $catImg = $firstProduct
                        ? Storage::url($firstProduct->image)
                        : ($categoryImages[$category->slug] ?? 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?w=500&q=80');
                @endphp
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="{{ route('shop.category', $category->slug) }}" class="text-decoration-none text-dark">
                        <div class="card p-3 text-center h-100">
                            <img src="{{ $catImg }}"
                                 class="rounded mb-2 w-100"
                                 style="height:150px; object-fit:cover;"
                                 onerror="this.src='https://images.unsplash.com/photo-1490481651871-ab68de25d43d?w=500&q=80'">
                            <h6 class="mb-0">{{ $category->name }}</h6>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="pb-5">
    <div class="container">
        <h2 class="mb-4">Trending Designs</h2>
        <div class="row g-3">
            @foreach($products as $product)
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card h-100">
                        <img src="{{ $product->image ? Storage::url($product->image) : 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=800&q=80' }}"
                             class="card-img-top"
                             style="height:220px; object-fit:cover;"
                             onerror="this.src='https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=800&q=80'">
                        <div class="card-body">
                            <h6>{{ $product->name }}</h6>
                            <p class="mb-1">Rs {{ number_format($product->price) }}</p>
                            <p class="text-warning mb-2">★ {{ $product->rating }}</p>
                            <div class="d-flex gap-2">
                                <form method="POST" action="{{ route('cart.store', $product->id) }}">@csrf<button class="btn btn-sm btn-pink">Add to Cart</button></form>
                                <form method="POST" action="{{ route('favorites.store', $product->id) }}">@csrf<button class="btn btn-sm btn-outline-dark"><i class="fa fa-heart"></i></button></form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-3 d-flex justify-content-center">
    {{ $products->links('pagination::bootstrap-5') }}
</div>
    </div>
</section>

<section class="py-5 bg-white">
    <div class="container">
        <h2 class="mb-4">Customer Reviews</h2>
        <div class="row g-3">
            @foreach($reviews as $review)
                <div class="col-md-4">
                    <div class="card p-3 h-100">
                        <h6>{{ $review->name }}</h6>
                        <p class="text-warning">★★★★★</p>
                        <p class="mb-0">{{ $review->review_text }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
