@extends('layouts.app')
@use('Illuminate\Support\Facades\Storage')

@section('content')
<div class="container py-5">
    <div class="d-flex flex-column flex-md-row justify-content-between gap-2 mb-4">
        <h2>All Products</h2>
        <form class="d-flex gap-2">
            <input name="search" class="form-control" placeholder="Search...">
            <select name="sort" class="form-select">
                <option value="">Sort</option>
                <option value="price_low">Price low to high</option>
                <option value="price_high">Price high to low</option>
            </select>
            <button class="btn btn-pink">Apply</button>
        </form>
    </div>
    <div class="row g-3">
        @foreach($products as $product)
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card h-100">
                    <img src="{{ $product->image ? Storage::url($product->image) : 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=700&q=80' }}"
     class="card-img-top" style="height:210px; object-fit:cover;"
     onerror="this.src='https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=700&q=80'">
                    <div class="card-body">
                        <h6>{{ $product->name }}</h6>
                        <span class="badge badge-discount text-white mb-2">{{ $product->discount_percent }}% OFF</span>
                        <p>Rs {{ number_format($product->price) }}</p>
                        <a href="{{ route('shop.show', $product->slug) }}" class="btn btn-sm btn-outline-dark">Details</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="mt-4">{{ $products->links() }}</div>
</div>
@endsection
