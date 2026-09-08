<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CashierController extends Controller
{
    public function create()
    {
        return view('kasir.create');
    }

    public function store(Request $request)
    {
        return 'Transaksi Berhasil Disimpan';
    }
}