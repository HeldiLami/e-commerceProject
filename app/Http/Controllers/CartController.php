<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $products = Product::all()->map(function ($product) {//Kalon secilin produkt dhe e kthen ne nje struktur te re array
            return [
                'id' => $product->id,
                'name' => $product->name,
                'image' => asset($product->image),
                'price_cents' => $product->price_cents,
            ];
        });

        return view('front.cart', ['products' => $products]);//array asocativ 
                                                            //Krijon variabel products 
    }
}
