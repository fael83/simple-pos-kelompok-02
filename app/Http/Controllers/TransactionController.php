<?php

namespace App\Http\Controllers;

use App\Models\Product;

class TransactionController extends Controller
{
    public function index()
    {
        return view('transactions.index');
    }


    public function create()
    {
        $products = Product::take(12)->get();
        return view('pos.create', ['products' => $products]);
    }

    public function store()
    {
        return 'Transaksi disimpan (belum ada logika penyimpanan)';
    }

    public function show(string $id)
    {
        return "Detail transaksi #{$id}";
    }
}