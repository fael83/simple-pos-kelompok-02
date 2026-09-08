<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = [
            ['id' => 1, 'nama' => 'Kopi Susu', 'harga' => 15000],
            ['id' => 2, 'nama' => 'Teh Manis', 'harga' => 5000],
        ];

        return view('produk.index', compact('products'));
    }

    public function show($id)
    {
        return view('produk.show', compact('id'));
    }
}