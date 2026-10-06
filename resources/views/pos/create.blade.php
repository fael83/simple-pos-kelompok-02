@extends('layouts.app')

@section('title', 'Kasir')

@section('content')
<h1 class="text-lg font-semibold mb-4">Transaksi Kasir</h1>

@if (session('success'))
<div class="bg-green-50 text-green-700 p-3 rounded-md mb-4">
    {{ session('success') }}
</div>
@endif

@error('items')
<div class="bg-red-50 text-red-700 p-3 rounded-md mb-4">{{ $message }}</div>
@enderror

<form method="POST" action="{{ route('transactions.store') }}" x-data="{
    cart: [],
    addToCart(id, name, price) {
        this.cart.push({ id, name, price });
    },
    subtotal() {
        return this.cart.reduce((sum, item) => sum + item.price, 0);
    }
}">
    @csrf
    <div class="grid grid-cols-3 gap-4">
        @foreach ($products as $product)
        <div class="border rounded-md p-3 cursor-pointer"
            @click="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->price }})">
            <p class="font-medium">{{ $product->name }}</p>
            <p class="text-sm text-slate-500">Rp {{ number_format($product->price) }}</p>
        </div>
        @endforeach
    </div>

    <div class="mt-4 border-t pt-3">
        <template x-for="(item, index) in cart" :key="index">
            <div>
                <p x-text="item.name + ' - Rp ' + item.price"></p>
                <input type="hidden" :name="'items[' + index + '][product_id]'" :value="item.id">
                <input type="hidden" :name="'items[' + index + '][qty]'" value="1">
            </div>
        </template>

        <p class="font-semibold mt-2">Subtotal: Rp <span x-text="subtotal()"></span></p>
        <button type="submit" class="mt-3 bg-blue-600 text-white px-4 py-2 rounded-md">Bayar</button>
    </div>
</form>
@endsection