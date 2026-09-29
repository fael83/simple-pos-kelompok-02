<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')
            ->orderBy('name')
            ->paginate(10);

        return view('products.index', compact('products'));
    }

    public function create()
    {
        return 'Form tambah produk (belum dibuat)';
    }

    public function store()
    {
        return 'Produk disimpan (belum ada logika penyimpanan)';
    }

    public function edit(string $id)
    {
        return "Form edit produk #{$id} (belum dibuat)";
    }

    public function update(string $id)
    {
        return "Produk #{$id} diperbarui (belum ada logika penyimpanan)";
    }
}