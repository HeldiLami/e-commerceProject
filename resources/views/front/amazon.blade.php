<x-layouts.front-layout title="Amazon Project">
    <x-slot name="css">
        @vite(['resources/css/pages/amazon.css'])
    </x-slot>

    <div class="products-grid js-products-grid">
    </div>

    <x-slot name="scripts">
        @vite(['resources/js/amazon.js'])
    </x-slot>
</x-layouts.front-layout>
