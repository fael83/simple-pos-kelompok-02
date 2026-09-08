<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function create()
    {
        $waktu = now()->format('H:i:s');
        return "Halaman kasir dibuka pukul {$waktu}";
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

    public function riwayat()
    {
        return 'Riwayat kasir';
    }
}