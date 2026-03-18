@extends('layouts.catalog')

@section('content')
<div class="container">
    <div class="row g-4">
        @foreach($products as $product)
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card border-0 h-100 shadow-hover">
                <div class="position-relative overflow-hidden bg-light rounded-3" style="aspect-ratio: 1/1;">
                    @if($product->images->count() > 0)
                        <img src="{{ asset('storage/' . $product->images->first()->path) }}" 
                             class="w-100 h-100" style="object-fit: contain; padding: 15px;">
                    @endif
                </div>
                <div class="card-body px-0 pt-3">
                    <p class="text-muted small mb-1 text-uppercase">{{ $product->team->name }}</p>
                    <h6 class="fw-bold mb-2">{{ $product->name }}</h6>
                    <p class="fw-bold fs-5">{{ number_format($product->cost, 2) }} €</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<style>
    .shadow-hover { transition: 0.3s ease; cursor: pointer; }
    .shadow-hover:hover { transform: translateY(-8px); }
</style>
@endsection