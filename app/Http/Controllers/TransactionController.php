<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TransactionController extends Controller
{
    public function create()
    {
        $products = Product::orderBy('name')->get();

        return view('pos.create', compact('products'));
    }

    public function store(StoreTransactionRequest $request)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated) {
            $transaction = Transaction::create([
                'user_id' => 1, // sementara di-hardcode
                'total' => 0,
            ]);

            $total = 0;
            $requestedStock = [];

            foreach ($validated['items'] as $index => $item) {
                $product = Product::lockForUpdate()->findOrFail($item['product_id']);

                if (!isset($requestedStock[$product->id])) {
                    $requestedStock[$product->id] = 0;
                }

                $requestedStock[$product->id] += $item['qty'];

                if ($requestedStock[$product->id] > $product->stock) {
                    throw ValidationException::withMessages([
                        "items.{$index}.qty" =>
                            "Stok produk {$product->name} tidak mencukupi. Stok tersedia: {$product->stock}.",
                    ]);
                }

                $subtotal = $product->price * $item['qty'];
                $total += $subtotal;

                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'qty' => $item['qty'],
                    'subtotal' => $subtotal,
                ]);
            }

            $transaction->update(['total' => $total]);
        });

        return redirect()
            ->route('pos.create')
            ->with('success', 'Transaksi berhasil disimpan.');
    }
}