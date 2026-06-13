@extends('layouts.app')

@section('content')
<section class="hero d-flex align-items-center">
    <div class="container text-center">
        <h5 class="text-uppercase mb-2">Welcome to SheStitch</h5>
        <h1 class="display-5 fw-bold mb-3">Design Your Dream Dress</h1>
        <p class="mb-4">Elegant, feminine online tailor experience just for you.</p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="{{ route('login') }}" class="btn btn-light btn-lg px-4">Login as User</a>
            <a href="{{ route('login') }}" class="btn btn-dark btn-lg px-4">Login as Admin</a>
        </div>
    </div>
</section>
@endsection

