@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2>Order #{{ $order->id }}</h2>
    <div class="card p-4">
        <p><strong>Name:</strong> {{ $order->first_name }} {{ $order->last_name }}</p>
        <p><strong>Email:</strong> {{ $order->email }}</p>
        <p><strong>Phone:</strong> {{ $order->phone }}</p>
        <p><strong>Design:</strong> {{ $order->design_choice }}</p>
        <p><strong>Size:</strong> {{ $order->size }}</p>
        <p><strong>Fabric:</strong> {{ $order->fabric_details }}</p>
        <p><strong>Address:</strong> {{ $order->delivery_address }}</p>
        <p><strong>Status:</strong> {{ $order->status }}</p>
    </div>
</div>
@endsection
