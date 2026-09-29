@extends('layouts.app')

@section('title', 'Produk')

@section('content')

    4

    <h1 class="text-lg font-semibold mb-4">Daftar Produk</h1>

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b">
                <th class="py-2 pr-4">Nama</th>
                <th class="py-2 pr-4">Kategori</th>
                <th class="py-2 pr-4">Harga</th>
                <th class="py-2 pr-4">Stok</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr class="border-b">
                    <td class="py-2 pr-4">{{ $product->name }}</td>
                    <td class="py-2 pr-4">{{ $product->category->name }}</td>
                    <td class="py-2 pr-4">Rp {{ number_format($product->price) }}</td>
                    <td class="py-2 pr-4">{{ $product->stock }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $products->links() }}
    </div>

@endsection