<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Aplikasi POS</title>
    <style>
        body { font-family: system-ui, sans-serif; background: #f3f4f6; margin: 0;
               display: flex; min-height: 100vh; align-items: center; justify-content: center; }
        .card { background: #fff; padding: 2rem 2.5rem; border-radius: 12px;
                box-shadow: 0 4px 16px rgba(0,0,0,.08); max-width: 480px; }
        h1 { margin-top: 0; }
        a.btn { display: inline-block; margin-top: 1rem; padding: .6rem 1.2rem;
                background: #2563eb; color: #fff; border-radius: 8px; text-decoration: none; }
    </style>
</head>
<body>
    <div class="card">
        <h1>🛒 Aplikasi POS</h1>
        <p>Aplikasi kasir sederhana untuk mengelola produk dan transaksi penjualan.</p>
        <ul>
            <li>Admin: kelola produk dan lihat transaksi</li>
            <li>Kasir: melakukan transaksi di halaman POS</li>
        </ul>
        <p>Silakan login untuk melanjutkan.</p>
        <a class="btn" href="{{ route('login') }}">Login</a>
    </div>
</body>
</html>