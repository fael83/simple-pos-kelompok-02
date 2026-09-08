<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Transaksi Kasir</title>
</head>
<body>
    <h1>Form Transaksi Kasir</h1>
    <form method="POST" action="/kasir">
        @csrf
        <div>
            <label for="produk">Nama Produk:</label><br>
            <input type="text" id="produk" name="produk" required>
        </div>
        <br>
        <div>
            <label for="qty">Jumlah:</label><br>
            <input type="number" id="qty" name="qty" min="1" required>
        </div>
        <br>
        <button type="submit">Bayar</button>
    </form>
</body>
</html>