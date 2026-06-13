@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Favorites</h2>
    <div class="row g-3">
        @forelse($favorites as $favorite)
            <div class="col-md-4">
                <div class="card p-3">
                    <h6>{{ $favorite->product->name }}</h6>
                    <p class="mb-2">Rs {{ number_format($favorite->product->price) }}</p>
                    <form method="POST" action="{{ route('favorites.destroy', $favorite->id) }}">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-dark">Remove</button></form>
                </div>
            </div>
        @empty
            <p>No favorites yet.</p>
        @endforelse
    </div>
</div>
@endsection
