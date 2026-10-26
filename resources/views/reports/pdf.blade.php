<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h1 { font-size: 18px; margin-bottom: 4px; }
        h3 { font-size: 14px; margin-top: 20px; margin-bottom: 6px; }
        table { width: 100%; border-collapse: collapse; margin-top: 6px; margin-bottom: 16px; }
        th, td { border: 1px solid #999; padding: 4px 8px; text-align: left; }
        th { background: #eee; }
        .right { text-align: right; }
        .center { text-align: center; }
    </style>
</head>
<body>
    <h1>Laporan Penjualan</h1>
    <p>Periode {{ $from }} s.d. {{ $to }}</p>
    <p>Total penjualan: <strong>Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</strong> dari {{ $jumlahTransaksi }} transaksi</p>

    <!-- BAGIAN BARU: TABEL PRODUK TERLARIS -->
    <h3>Top 5 Produk Terlaris</h3>
    <table>
        <thead>
            <tr>
                <th class="center" style="width: 30px;">No</th>
                <th>Nama Produk</th>
                <th class="center">Terjual</th>
                <th class="right">Total Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($topProducts as $index => $product)
            <tr>
                <td class="center">{{ $index + 1 }}</td>
                <td>{{ $product->name }}</td>
                <td class="center">{{ $product->total_qty }} pcs</td>
                <td class="right">Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="center">Belum ada data produk terjual pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <p>50 transaksi terbaru pada periode ini:</p>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tanggal</th>
                <th>Kasir</th>
                <th class="right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $t)
            <tr>
                <td>{{ $t->id }}</td>
                <td>{{ $t->created_at->format('Y-m-d H:i') }}</td>
                <td>{{ $t->user->name }}</td>
                <td class="right">Rp {{ number_format($t->total, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>