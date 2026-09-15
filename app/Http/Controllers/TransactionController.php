<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Models\Product;
use App\Models\ShopSetting;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();
        $from = $request->date('from');
        $to = $request->date('to');

        // An explicit custom range always wins (the "Rentang khusus" advanced path — mirrors
        // ReportController::resolveRange()). Otherwise year+month resolves to that calendar
        // month, year alone to that whole year. Unlike /reports, no default period is forced
        // here when none of from/to/year/month are given — /transactions stays a browse-
        // everything list, unfiltered, matching today's behavior.
        if (! $from && ! $to) {
            $year = $request->integer('year') ?: null;
            $month = $request->integer('month') ?: null;

            if ($year && $month) {
                $from = Carbon::create($year, $month, 1)->startOfMonth();
                $to = $from->copy()->endOfMonth();
            } elseif ($year) {
                $from = Carbon::create($year, 1, 1)->startOfYear();
                $to = Carbon::create($year, 12, 31)->endOfYear();
            }
        }

        $status = in_array($request->string('status')->value(), ['selesai', 'dibatalkan'], true)
            ? $request->string('status')->value()
            : null;
        $q = trim((string) $request->string('q'));

        $sort = in_array($request->string('sort')->value(), ['invoice_no', 'created_at', 'total'], true)
            ? $request->string('sort')->value()
            : 'created_at';
        $direction = $request->string('direction')->value() === 'asc' ? 'asc' : 'desc';

        $transactions = Transaction::with('user')
            ->when(! $user->isAdmin(), fn ($qr) => $qr->where('user_id', $user->id))
            ->when($from, fn ($qr) => $qr->whereDate('created_at', '>=', $from))
            ->when($to, fn ($qr) => $qr->whereDate('created_at', '<=', $to))
            ->when($status, fn ($qr) => $qr->where('status', $status))
            ->when($q !== '', fn ($qr) => $qr->where(
                fn ($w) => $w->where('invoice_no', 'like', "%{$q}%")
                    ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$q}%"))
            ))
            ->orderBy($sort, $direction)
            ->paginate(15)
            ->withQueryString();

        // Pre-fills the Bulan/Tahun quick-filter selects — reflects whatever the request
        // actually asked for, else the current month/year as a ready-to-go starting point
        // (this does NOT filter anything by itself; only submitting "Tampilkan" does).
        $selectedYear = $request->filled('year') ? (int) $request->input('year') : now()->year;
        $selectedMonth = $request->filled('month')
            ? (int) $request->input('month')
            : ($request->filled('year') ? null : now()->month);
        $availableYears = range(now()->year, now()->year - 2);

        return view('transactions.index', compact(
            'transactions', 'from', 'to', 'status', 'q', 'sort', 'direction',
            'selectedYear', 'selectedMonth', 'availableYears'
        ));
    }

    public function create(Request $request): View
    {
        $products = Product::active()
            ->where('stock', '>', 0)
            ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%' . $request->string('q') . '%'))
            ->orderBy('name')
            ->paginate(24)
            ->withQueryString();

        return view('pos.create', compact('products'));
    }

    /**
     * Cari produk persis lewat SKU (scan barcode) untuk langsung masuk keranjang
     * tanpa reload halaman — kelayakan sama dengan grid POS (aktif & stok tersedia).
     */
    public function lookup(Request $request): JsonResponse
    {
        $sku = trim((string) $request->query('sku'));

        $product = Product::active()->where('stock', '>', 0)->where('sku', $sku)->first();

        if (! $product) {
            return response()->json(['message' => 'Produk tidak ditemukan.'], 404);
        }

        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'sku' => $product->sku,
            'price' => $product->price,
            'stock' => $product->stock,
        ]);
    }

    public function store(StoreTransactionRequest $request): RedirectResponse
    {
        $items = $request->validated()['items'];
        $amountPaidInput = $request->filled('amount_paid') ? (int) $request->input('amount_paid') : null;
        $discountInput = $request->filled('discount') ? (int) $request->input('discount') : 0;
        $taxPercent = ShopSetting::current()->tax_percent;

        try {
            $transaction = DB::transaction(function () use ($items, $amountPaidInput, $discountInput, $taxPercent) {
                $itemsSubtotal = 0;
                $transaction = Transaction::create([
                    'user_id' => Auth::id(),
                    // 6-digit suffix (not 3) — cuts same-second collision odds under
                    // concurrent checkouts by ~1000x. invoice_no is still DB-unique-constrained
                    // as the actual guarantee; see the QueryException handling below.
                    'invoice_no' => 'INV-' . now()->format('Ymd-His') . '-' . random_int(100000, 999999),
                    'total' => 0,
                ]);

                foreach ($items as $item) {
                    $product = Product::lockForUpdate()->findOrFail($item['product_id']);

                    if ($product->stock < $item['qty']) {
                        throw new \RuntimeException("Stok {$product->name} tidak mencukupi.");
                    }

                    $subtotal = $product->price * $item['qty'];
                    $itemsSubtotal += $subtotal;

                    TransactionDetail::create([
                        'transaction_id' => $transaction->id,
                        'product_id' => $product->id,
                        'qty' => $item['qty'],
                        'price' => $product->price,
                        'subtotal' => $subtotal,
                    ]);

                    $product->decrement('stock', $item['qty']);
                }

                // Discount can never exceed the subtotal (no negative pre-tax amount);
                // tax is always server-computed off the shop's tax_percent, never from
                // client input.
                $discount = min($discountInput, $itemsSubtotal);
                $tax = (int) round(($itemsSubtotal - $discount) * $taxPercent / 100);
                $grandTotal = $itemsSubtotal - $discount + $tax;

                $amountPaid = $amountPaidInput ?? $grandTotal;

                if ($amountPaid < $grandTotal) {
                    throw new \RuntimeException('Uang diterima kurang dari total transaksi.');
                }

                $transaction->update([
                    'total' => $grandTotal,
                    'discount' => $discount,
                    'tax' => $tax,
                    'payment_method' => 'tunai',
                    'amount_paid' => $amountPaid,
                    'change_due' => $amountPaid - $grandTotal,
                ]);

                return $transaction;
            });
        } catch (\Illuminate\Database\QueryException $e) {
            // Catches a same-second invoice_no collision (rare — see the widened random
            // suffix above) or any other DB-level constraint failure. QueryException is a
            // RuntimeException subtype, so this must be caught before the block below —
            // without it, a raw SQL error message would leak to the cashier.
            return back()->with('error', 'Gagal menyimpan transaksi, silakan coba lagi.');
        } catch (\RuntimeException $e) {
            // Insufficient stock or insufficient cash surfaces as a flash error
            // on the POS page instead of an unhandled 500.
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('transactions.show', $transaction)
            ->with('status', 'Transaksi berhasil disimpan.');
    }

    public function show(Transaction $transaction): View
    {
        $transaction->load('details.product', 'user', 'voidedBy');

        return view('transactions.show', compact('transaction'));
    }

    /**
     * Batalkan transaksi (admin-only) — stok dikembalikan, transaksi tetap tampil di
     * riwayat (ditandai) tapi dikecualikan dari total laporan/dashboard. Tidak pernah
     * dihapus, sama seperti produk/pengguna — hanya status yang berubah.
     */
    public function void(Request $request, Transaction $transaction): RedirectResponse
    {
        $request->validate([
            'reason' => ['required', 'string', 'max:255'],
        ]);

        if ($transaction->isVoided()) {
            return back()->with('error', 'Transaksi ini sudah dibatalkan sebelumnya.');
        }

        DB::transaction(function () use ($transaction, $request) {
            $transaction->load('details');

            foreach ($transaction->details as $detail) {
                Product::lockForUpdate()->find($detail->product_id)?->increment('stock', $detail->qty);
            }

            $transaction->update([
                'status' => 'dibatalkan',
                'voided_by' => Auth::id(),
                'voided_at' => now(),
                'void_reason' => $request->string('reason'),
            ]);
        });

        return redirect()->route('transactions.show', $transaction)
            ->with('status', 'Transaksi dibatalkan, stok dikembalikan.');
    }

    /**
     * Unduh struk transaksi sebagai PDF (studi kasus DomPDF — minggu 9).
     */
    public function pdf(Transaction $transaction): Response
    {
        $transaction->load('details.product', 'user');

        $pdf = Pdf::loadView('transactions.pdf', compact('transaction'))
            ->setPaper([0, 0, 226.77, 600], 'portrait'); // ~80mm thermal receipt width

        return $pdf->download("struk-{$transaction->invoice_no}.pdf");
    }
}
