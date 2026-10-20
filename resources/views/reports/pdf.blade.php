<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        h2 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
        .summary { margin-bottom: 20px; }
    </style>
</head>
<body>

    <h2>Laporan Ringkasan Penjualan</h2>

    <div class="summary">
        <p><strong>Total Transaksi:</strong> {{ $totalTransactions }}</p>
        <p><strong>Total Pendapatan:</strong> Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
    </div>

    <h3>50 Transaksi Terbaru</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID Transaksi</th>
                <th>Kasir</th>
                <th>Total</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $index => $trx)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $trx->id }}</td>
                <td>{{ $trx->user->name ?? '-' }}</td>
                <td>Rp {{ number_format($trx->total_price, 0, ',', '.') }}</td>
                <td>{{ $trx->created_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>