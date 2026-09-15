<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->string('q'));

        $sort = in_array($request->string('sort')->value(), ['name', 'products_count'], true)
            ? $request->string('sort')->value()
            : 'name';
        $direction = $request->string('direction')->value() === 'desc' ? 'desc' : 'asc';

        $categories = Category::withCount('products')
            ->when($q !== '', fn ($qr) => $qr->where('name', 'like', "%{$q}%"))
            ->orderBy($sort, $direction)
            ->paginate(10)
            ->withQueryString();

        return view('categories.index', compact('categories', 'q', 'sort', 'direction'));
    }

    /**
     * Bulk delete — skips any category that still has products, matching the
     * existing single-delete guard, and deletes the rest.
     */
    public function bulkDelete(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:categories,id'],
        ]);

        $categories = Category::withCount('products')->whereIn('id', $validated['ids'])->get();
        $deletable = $categories->where('products_count', 0);
        $skipped = $categories->count() - $deletable->count();

        Category::whereIn('id', $deletable->pluck('id'))->delete();

        $message = "{$deletable->count()} kategori berhasil dihapus.";
        if ($skipped > 0) {
            $message .= " {$skipped} kategori dilewati karena masih memiliki produk.";
        }

        return back()->with($deletable->count() > 0 ? 'status' : 'error', $message);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        Category::create($data);

        return back()->with('status', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $category->update($data);

        return back()->with('status', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->products()->exists()) {
            return back()->with('error', 'Kategori masih memiliki produk, tidak dapat dihapus.');
        }

        $category->delete();

        return back()->with('status', 'Kategori berhasil dihapus.');
    }
}
