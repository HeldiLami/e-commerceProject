<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function index()
    {
        $stats = DB::table('order_product')
            ->join('products', 'products.id', '=', 'order_product.product_id')
            ->select(
                'products.type as category_type',
                'products.name as product_name',
                DB::raw('SUM(order_product.quantity) as sales_count')
            )
            ->groupBy('products.type', 'products.name')
            ->orderBy('products.type')
            ->orderByDesc('sales_count')
            ->get();

        return view('admin.statistics', compact('stats'));
    }
}
