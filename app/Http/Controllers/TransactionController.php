<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function index(){
    $transactions = Transaction::with(['details.product', 'user'])
        ->latest()
        ->paginate(15);
    return view('transactions.index', compact('transactions'));
}


    public function create()
    {
        $products = Product::where('stock', '>', 0)->get();
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