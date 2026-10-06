@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')

    <div class="flex justify-center">
        <div class="w-full max-w-2xl">

            <div class="bg-white p-6 rounded-lg shadow-md">

                <h1 class="text-lg font-semibold mb-6">Edit Produk</h1>

                <form method="POST"
                    action="{{ route('products.update', $product->id) }}">

                    @csrf
                    @method('PUT')

                    <label class="block mb-4">
                        <span class="text-sm font-medium">Nama</span>

                        <input
                            type="text"
                            name="name"
                            value="{{ old('name', $product->name) }}"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md"
                        >

                        @error('name')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </label>

                    <label class="block mb-4">
                        <span class="text-sm font-medium">Kategori</span>

                        <select
                            name="category_id"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md"
                        >
                            @foreach ($categories as $category)
                                <option
                                    value="{{ $category->id }}"
                                    @selected(old('category_id', $product->category_id) == $category->id)
                                >
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('category_id')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </label>

                    <label class="block mb-4">
                        <span class="text-sm font-medium">Harga</span>

                        <input
                            type="number"
                            name="price"
                            value="{{ old('price', $product->price) }}"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md"
                        >

                        @error('price')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </label>

                    <label class="block mb-6">
                        <span class="text-sm font-medium">Stok</span>

                        <input
                            type="number"
                            name="stock"
                            value="{{ old('stock', $product->stock) }}"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md"
                        >

                        @error('stock')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </label>

                    <div class="flex justify-end">
                        <button
                            type="submit"
                            class="bg-blue-600 text-white px-5 py-2 rounded-md"
                        >
                            Update
                        </button>
                    </div>

                </form>

            </div>

        </div>
    </div>

@endsection