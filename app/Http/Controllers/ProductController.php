<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\StockAdjustment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $lowStock = $request->boolean('low_stock');
        $q = trim((string) $request->string('q'));
        $categoryId = $request->integer('category_id') ?: null;

        $sort = in_array($request->string('sort')->value(), ['name', 'sku', 'price', 'stock'], true)
            ? $request->string('sort')->value()
            : 'name';
        $direction = $request->string('direction')->value() === 'desc' ? 'desc' : 'asc';

        $products = Product::with('category')
            ->when($lowStock, fn ($qr) => $qr->where('stock', '<', 10))
            ->when($q !== '', fn ($qr) => $qr->where(
                fn ($w) => $w->where('name', 'like', "%{$q}%")->orWhere('sku', 'like', "%{$q}%")
            ))
            ->when($categoryId, fn ($qr) => $qr->where('category_id', $categoryId))
            ->orderBy($sort, $direction)
            ->paginate(10)
            ->withQueryString();

        // Drill-down from /categories's "Jumlah Produk" link — resolved to a name here so the
        // filter banner can say *which* category, not just that a filter is active.
        $categoryName = $categoryId ? Category::find($categoryId)?->name : null;

        return view('products.index', compact('products', 'lowStock', 'q', 'sort', 'direction', 'categoryId', 'categoryName'));
    }

    /**
     * Bulk activate/deactivate — checkbox selection in the table, admin-only.
     */
    public function bulkStatus(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:products,id'],
            'is_active' => ['required', 'boolean'],
        ]);

        Product::whereIn('id', $validated['ids'])->update(['is_active' => $validated['is_active']]);

        return back()->with('status', 'Status produk terpilih berhasil diperbarui.');
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('products.create', compact('categories'));
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        } else {
            unset($data['image']);
        }

        Product::create($data);

        return redirect()->route('products.index')->with('status', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product): View
    {
        $categories = Category::orderBy('name')->get();

        return view('products.edit', compact('product', 'categories'));
    }

    public function update(StoreProductRequest $request, Product $product): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        } else {
            unset($data['image']);
        }

        // Stock can only change through adjustStock() below, so every change is
        // logged with who/why — never accept it silently from the general edit form,
        // even if a crafted request includes it.
        unset($data['stock']);

        $product->update($data);

        return redirect()->route('products.index')->with('status', 'Produk berhasil diperbarui.');
    }

    /**
     * Audited stock correction (restock, shrinkage, recount) — the only path allowed
     * to change an existing product's stock; logs who/why/before→after.
     */
    public function adjustStock(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'delta' => ['required', 'integer', 'not_in:0'],
            'reason' => ['required', 'string', 'max:255'],
        ], [
            'delta.not_in' => 'Jumlah penyesuaian tidak boleh 0.',
        ]);

        try {
            DB::transaction(function () use ($product, $validated) {
                $locked = Product::lockForUpdate()->findOrFail($product->id);
                $newStock = $locked->stock + $validated['delta'];

                if ($newStock < 0) {
                    throw new \RuntimeException('Stok tidak boleh menjadi negatif.');
                }

                $locked->update(['stock' => $newStock]);

                StockAdjustment::create([
                    'product_id' => $locked->id,
                    'user_id' => Auth::id(),
                    'delta' => $validated['delta'],
                    'stock_after' => $newStock,
                    'reason' => $validated['reason'],
                ]);
            });
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('status', 'Stok produk disesuaikan.');
    }

    /**
     * Produk tidak pernah dihapus (riwayat transaksi merujuk padanya) — hanya
     * dinonaktifkan/diaktifkan kembali, sehingga tetap muncul di laporan lama
     * tapi hilang dari daftar kasir.
     */
    public function toggleStatus(Product $product): RedirectResponse
    {
        $product->update(['is_active' => ! $product->is_active]);

        return back()->with('status', $product->is_active
            ? 'Produk diaktifkan kembali.'
            : 'Produk dinonaktifkan.');
    }
}
