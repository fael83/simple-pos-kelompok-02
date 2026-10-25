<nav class="bg-slate-900 text-white px-4 py-3 flex gap-4 items-center">
    <span class="font-semibold">Simple POS</span>
    <a href="{{ route('pos.create') }}" class="hover:underline">Kasir</a>
    <a href="{{ route('transactions.index') }}" class="hover:underline">Transaksi</a>
    @if (auth()->user()?->isAdmin())
        <a href="{{ route('products.index') }}" class="hover:underline">Produk</a>
        <a href="{{ route('categories.index') }}" class="hover:underline">Kategori</a>
        <a href="{{ route('reports.index') }}" class="hover:underline">Laporan</a>
        <a href="{{ route('import.create') }}" class="hover:underline">Import</a>
    @endif
    <span class="ml-auto text-sm">{{ auth()->user()?->name }} ({{ auth()->user()?->role }})</span>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="hover:underline">Keluar</button>
    </form>
</nav>