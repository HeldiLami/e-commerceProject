<x-layouts.admin-layout>
    <x-slot:title>Admin • Statistics</x-slot>

    @vite(['resources/css/admin/statistics.css'])

    @php
        // $stats vjen nga controller
        // I grupojmë sipas "category_type" (products.type)
        $grouped = $stats->groupBy('category_type');

        // Funksion i vogël për ta bërë label më të bukur:
        // "products/clothing" -> "Clothing"
        $prettyType = function ($type) {
            if (!$type) return 'Uncategorized';
            $last = last(explode('/', $type));
            return ucfirst(str_replace(['-', '_'], ' ', $last));
        };
    @endphp

    <div class="page-header">
        <h1>Statistics</h1>
        <h2 class="subtitle">Përmbledhje e shitjeve sipas kategorisë dhe produktit.</h2>
    </div>

    <div class="stats-card">
        @forelse($grouped as $type => $rows)
            {{-- Heading Category --}}
            <table class="stats-table" style="margin-bottom: 18px;">
                <thead>
                    <tr>
                        <th>Kategoria e produktit</th>
                        <th>Emri i produktit</th>
                        <th>Numri i shitjeve</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($rows as $row)
                        <tr>
                            <td><span class="pill">{{ $prettyType($row->category_type) }}</span></td>
                            <td>{{ $row->product_name }}</td>
                            <td class="num">{{ $row->sales_count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @empty
            <div style="padding: 16px; text-align:center;">
                Nuk ka të dhëna për momentin.
            </div>
        @endforelse
    </div>
</x-layouts.admin-layout>