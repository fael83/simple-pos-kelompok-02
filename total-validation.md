# Mengapa Validasi numeric pada Field total Tidak Cukup

Kalau field total dikirim dari form dan cuma divalidasi dengan aturan
numeric, tidak cukup untuk mencegah manipulasi.

Validasi numeric hanya memeriksa bahwa nilai yang dikirim berupa angka,
bukan memeriksa apakah angka itu benar sesuai perhitungan yang seharusnya.
Artinya, seseorang yang mengerti cara kerja form HTML bisa dengan mudah
mengubah value field total lewat DevTools browser, lewat request manual
(curl/Postman), atau lewat ekstensi browser, sebelum form itu
dikirim ke server. Selama angkanya tetap berupa angka (bukan teks atau
kosong), validasi numeric akan tetap meloloskannya, walau nilainya sama
sekali tidak sesuai dengan harga produk dan qty yang sebenarnya dibeli.

Contoh skenario serangan:
Seseorang membeli produk seharga Rp 500.000, tapi lewat DevTools dia ubah
input total jadi Rp 1.000. Kalau server hanya percaya field ini dan
memvalidasinya sebagai numeric, transaksi akan tersimpan dengan total
Rp 1.000, padahal barang yang dibawa senilai Rp 500.000.

Solusi yang benar, seperti yang sudah diterapkan di Langkah 4, adalah
tidak pernah mempercayai nilai total dari client sama sekali. Server
menghitung ulang total dari data yang bisa dipercaya, yaitu harga produk
yang tersimpan di database ($product->price), dikalikan qty yang
dikirim user, dijumlahkan di dalam TransactionController::store():

php
$subtotal = $product->price * $item['qty'];
$total += $subtotal;

Dengan cara ini, client cuma mengirim product_id dan qty, lalu server
sendiri yang menentukan harga dan menghitung totalnya. Client sama sekali
tidak punya kesempatan mengirim atau memanipulasi angka total secara
langsung, karena field itu tidak pernah diambil dari input form.

Kesimpulan: validasi tipe data (numeric, integer, dll) hanya
menjaga bentuk data supaya tidak error saat diproses, bukan menjaga
kebenaran nilainya. Untuk data yang menyangkut uang atau hal sensitif
lainnya, nilai akhirnya harus dihitung ulang di server dari sumber yang
bisa dipercaya (database), bukan sekadar divalidasi dari input klien.