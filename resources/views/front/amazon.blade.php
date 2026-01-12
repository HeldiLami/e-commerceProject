@extends('layouts.front-layout')

@section('title', 'Amazon Project')

@push('css')
    @vite(['resources/css/pages/amazon.css'])
@endpush

@section('content')
    <div class="products-grid js-products-grid">
    </div>
@endsection

@push('scripts')
    <script type="module" src="{{ asset('scripts/amazon.js') }}"></script>
@endpush