<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ApiClientDemo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:demo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Demonstrate consuming the Simple POS API as an external client';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $baseUrl = 'http://127.0.0.1:8000';

        $this->info('--- PENGUJIAN 1: Login dengan Kata Sandi Salah ---');

        // 1. Coba login dengan password yang sengaja salah
        $loginSalah = Http::acceptJson()->post("{$baseUrl}/api/v1/login", [
            'email' => 'kasir@pos.test',
            'password' => 'salah-password-banget',
        ]);

        if ($loginSalah->failed()) {
            // Menampilkan pesan gagal dari server (biasanya 401 Unauthorized)
            $this->error('Login gagal (Sesuai Harapan): '.$loginSalah->json('message'));
            $this->info('Program berhenti di langkah pertama, tidak melanjutkan ke pengambilan produk atau transaksi.');

            return self::FAILURE; 
        }

        // --- Bagian di bawah ini TIDAK AKAN DIEKSEKUSI jika login di atas gagal ---
        $token = $loginSalah->json('token');
        $this->info("Login berhasil, token: {$token}");

        $products = Http::acceptJson()
            ->withToken($token)
            ->get("{$baseUrl}/api/v1/products");

        if ($products->failed()) {
            $this->error('Token kedaluwarsa/dicabut, atau server sedang bermasalah.');

            return self::FAILURE;
        }

        $this->info('Jumlah produk: '.count($products->json('data')));

        $productId = Product::first()->id;

        $transaction = Http::acceptJson()
            ->withToken($token)
            ->post("{$baseUrl}/api/v1/transactions", [
                'items' => [
                    [
                        'product_id' => $productId,
                        'qty' => 1,
                    ],
                ],
            ]);

        match ($transaction->status()) {
            201 => $this->info(
                'Transaksi tersimpan: '.json_encode($transaction->json('data'))
            ),
            401 => $this->error('Token tidak valid, login ulang.'),
            422 => $this->error('Input ditolak: '.$transaction->json('message')),
            default => $this->error('Galat lain: '.$transaction->status()),
        };

        return self::SUCCESS;
    }
}