<nav class="bg-slate-900 text-white px-4 py-3 flex items-center gap-8">
    <span class="font-semibold text-lg">Simple POS</span>
    
    <a href="{{ route('pos.create') }}" 
       class="hover:underline transition-all {{ request()->routeIs('pos.create') ? 'text-blue-400 font-bold underline underline-offset-4' : 'text-gray-300' }}">
       Kasir
    </a>
    
    <a href="{{ route('transactions.index') }}" 
       class="hover:underline transition-all {{ request()->routeIs('transactions.index') ? 'text-blue-400 font-bold underline underline-offset-4' : 'text-gray-300' }}">
       Transaksi
    </a>
</nav>