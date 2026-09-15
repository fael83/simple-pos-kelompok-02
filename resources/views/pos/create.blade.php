@extends('layouts.app')

@section('title', 'Kasir')

@section('content')
    <h1 class="text-lg font-semibold mb-4 text-slate-800">Transaksi Kasir</h1>

    <div x-data="{
        cart: [],
        lastAdded: null,
        timer: null,
        addToCart(id, name, price) {
            this.cart.push({ id, name, price: Number(price) });
            this.lastAdded = id;
            clearTimeout(this.timer);
            this.timer = setTimeout(() => {
                this.lastAdded = null;
            }, 2000);
        },
        removeFromCart(index) {
            this.cart.splice(index, 1);
        },
        subtotal() {
            return this.cart.reduce((sum, item) => sum + item.price, 0);
        }
    }">
                {{-- Grid Kartu Produk --}}
                <div class="grid grid-cols-3 gap-4">
                    @foreach ($products as $product)
                        <div class="border border-slate-200 rounded-xl p-4 cursor-pointer bg-white hover:border-slate-400 hover:shadow-sm transition-all relative"
                            :class="lastAdded === {{ $product->id }} ? 'ring-2 ring-blue-500 border-blue-500' : 'border-slate-200 hover:border-slate-400'"
                            @click="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }})">
                            
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-semibold text-slate-800">{{ $product->name }}</p>
                                    <p class="text-sm text-slate-500 mt-1">Rp {{ number_format($product->price) }}</p>
                                </div>

                                @if($product->stock < 10)
                                    <span class="bg-amber-100 text-amber-700 text-xs px-2 py-0.5 rounded-full font-medium">
                                        Stok Menipis
                                    </span>
                                @endif
                            </div>

                        </div>
                    @endforeach
                </div>

        {{-- Panel Keranjang --}}
        <div class="mt-6 border border-slate-200 rounded-xl p-4 bg-white shadow-sm">
            <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider mb-3">Keranjang</h2>

            <template x-if="cart.length === 0">
                <p class="text-sm text-slate-400 italic py-2">Keranjang masih kosong. Klik produk di atas untuk menambahkan.</p>
            </template>

            <div class="space-y-2">
                <template x-for="(item, index) in cart" :key="index">
                    <div class="flex items-center justify-between p-2.5 bg-slate-50 rounded-lg border border-slate-100 hover:bg-slate-100/80 transition-all">
                        <div class="flex items-center gap-2">
                            <span class="font-medium text-slate-700 text-sm" x-text="item.name"></span>
                            <span class="text-xs text-slate-300">•</span>
                            <span class="text-sm font-semibold text-slate-600" x-text="'Rp ' + item.price.toLocaleString('id-ID')"></span>
                        </div>
                        
                        <button @click="removeFromCart(index)" 
                            class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 border border-red-100 rounded-md transition-colors"
                            title="Hapus Item">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Hapus
                        </button>
                    </div>
                </template>
            </div>

            {{-- Subtotal --}}
            <div class="mt-4 pt-3 border-t border-slate-200 flex items-center justify-between">
                <span class="text-sm font-medium text-slate-600">Subtotal</span>
                <span class="text-lg font-bold text-slate-800" x-text="'Rp ' + subtotal().toLocaleString('id-ID')"></span>
            </div>
        </div>
    </div>
@endsection