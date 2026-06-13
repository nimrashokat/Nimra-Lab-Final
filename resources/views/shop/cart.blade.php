@extends('layouts.app')
@use('Illuminate\Support\Facades\Storage')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Your Cart</h2>
    @forelse($items as $item)
        <div class="card p-3 mb-3">
            <div class="d-flex align-items-center gap-3">
                {{-- Product Image --}}
                <img src="{{ $item->product->image ? Storage::url($item->product->image) : 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?w=80&h=80&fit=crop' }}"
                     style="width:80px; height:80px; object-fit:cover; border-radius:10px;"
                     onerror="this.src='https://images.unsplash.com/photo-1490481651871-ab68de25d43d?w=80&h=80&fit=crop'">

                {{-- Product Info --}}
                <div class="flex-grow-1">
                    <h6 class="mb-1">{{ $item->product->name }}</h6>
                    <small class="text-muted">Quantity: {{ $item->quantity }}</small>
                </div>

                {{-- Price + Remove --}}
                <div class="d-flex align-items-center gap-3">
                    <span class="fw-bold">Rs {{ number_format($item->product->price * $item->quantity) }}</span>
                    <form method="POST" action="{{ route('cart.destroy', $item->id) }}">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Remove</button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-info">Your cart is empty. Start adding your favorite designs.</div>
    @endforelse

    @if($items->count() > 0)
        {{-- Total --}}
        <div class="card p-3 mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Total Amount</h5>
                <h5 class="mb-0 text-pink">Rs {{ number_format($items->sum(fn($i) => $i->product->price * $i->quantity)) }}</h5>
            </div>
        </div>

        {{-- Place Order Button --}}
        <div class="d-flex gap-2 mt-3">
            <a href="{{ route('shop.index') }}" class="btn btn-outline-dark">Continue Shopping</a>
            <a href="{{ route('order.create') }}" class="btn btn-pink btn-lg px-5">
                <i class="fa fa-shopping-bag me-2"></i> Place Order
            </a>
        </div>
    @endif
</div>

<style>
.text-pink { color: #e99ab3; }
.btn-pink { background: #e99ab3; color: #fff; border: none; }
.btn-pink:hover { background: #d985a0; color: #fff; }
</style>
@endsection
