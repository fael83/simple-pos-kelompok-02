<?php

namespace App\Http\Controllers;

class TransactionController extends Controller
{
    public function create()
    {
        $products = collect([
            (object) ['id' => 1, 'name' => 'Kopi Sachet', 'price' => 3000, 'stock' => 40],
            (object) ['id' => 2, 'name' => 'Teh Celup', 'price' => 2500, 'stock' => 25],
            (object) ['id' => 3, 'name' => 'Mie Instan', 'price' => 3500, 'stock' => 8],
            (object) ['id' => 4, 'name' => 'Air Mineral 600ml', 'price' => 4000, 'stock' => 60],
            (object) ['id' => 5, 'name' => 'Roti Tawar', 'price' => 12000, 'stock' => 15],
            (object) ['id' => 6, 'name' => 'Gula Pasir 1kg', 'price' => 15000, 'stock' => 5],
        ]);

        return view('pos.create', ['products' => $products]);
    }

    public function store()
    {
        return 'Transaksi disimpan (belum ada logika penyimpanan)';
    }

    public function index()
    {
        return 'Daftar transaksi';
    }

    public function show(string $id)
    {
        return "Detail transaksi #{$id}";
    }
}