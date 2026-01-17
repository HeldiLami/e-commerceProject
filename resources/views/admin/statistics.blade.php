<x-layouts.admin-layout>
    <x-slot:title>Admin • Statistics</x-slot>

    @vite(['resources/css/pages/admin/statistics.css'])
    
    <div class="page-header">
        <h1>Statistics</h1>
        <h2 class="subtitle">Përmbledhje e shitjeve sipas kategorisë dhe produktit.</h2>
    </div>

    <div class="stats-card">
        <table class="stats-table">
            <thead>
                <tr>
                    <th>Kategoria e produktit</th>
                    <th>Emri i produktit</th>
                    <th>Numri i shitjeve</th>
                </tr>
            </thead>

            <tbody>
                @forelse($stats as $row)
                    <tr>
                        <td><span class="pill">{{ $prettyType($row->category_type) }}</span></td>
                        <td>{{ $row->product_name }}</td>
                        <td class="num">{{ $row->sales_count }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="text-align:center; padding: 16px;">
                            Nuk ka të dhëna për momentin.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.admin-layout>
