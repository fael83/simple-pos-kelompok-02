<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Produk</title>
</head>
<body>
    <h1>Daftar Produk</h1>
    <ul>
        @foreach ($products as $item)
            <li>
                <a href="/produk/{{ $item['id'] }}">
                    {{ $item['nama'] }} - Rp{{ number_format($item['harga'], 0, ',', '.') }}
                </a>
            </li>
        @endforeach
    </ul>
</body>
</html>