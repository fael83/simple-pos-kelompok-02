b905314 hamdirafi increment 7: autentikasi dan otorisasi rbac
3b56366 Ailsasahda13 Merge pull request #43 from fael83/add-manager-role
706257b Ailsa Sahda tambah peran manager dan sesuaikan route transactions
86d0ff4 Ailsasahda13 Merge pull request #42 from fael83/custom-forbidden-message
3f59794 Ailsa Sahda ubah pesan 403 middleware role agar menampilkan peran yang dibutuhkan
83859ea meryymarta Merge pull request #41 from fael83/login-logout
78ba65c meryymarta push ulang login-logout
b6eea63 Faell Merge pull request #40 from fael83/role-middleware
2529795 fael83 perbaiki error
be15e12 Faell Merge pull request #39 from fael83/role-middleware
91a0018 fael83 lindungi route produk dan kategori dengan role admin
7cd6aee fael83 tambah middleware role untuk pembatasan halaman admin
e5a8cca meryymarta Merge pull request #38 from fael83/login-logout
9db0c47 meryymarta tambah tombol keluar dan menu admin di navigasi
d39baf1 meryymarta Merge pull request #37 from fael83/demo-users
ecab2af meryymarta tambah akun demo admin dan kasir lewat seeder
c6fff12 Ahmad Rafi Hamdi Merge pull request #36 from fael83/user-model
c087b5b hamdirafi perbarui model User dengan role, is_active, dan hash kata sandi
7dc9e82 Ailsasahda13 Merge pull request #35 from fael83/user-role-migration
e4cb425 Ailsa Sahda tambah kolom role dan is_active pada users
62beda3 fael83 increment 6: validasi form request dan total di server simple pos
c6795f7 Faell Merge pull request #34 from fael83/explain-total-manipulation
a415512 fael83 tambah penjelasan kenapa validasi numeric saja tidak cukup mencegah manipulasi total
fd77f12 Ailsasahda13 Merge pull request #33 from fael83/product-edit-validation
34552d8 Ailsa Sahda selesaikan edit dan update produk
611f3d4 meryymarta tambah validasi stok produk transaksi
3d039c7 Faell Merge pull request #32 from fael83/merge-cart-qty
66c056f fael83 gabungkan qty per produk di keranjang kasir
55954ef hamdirafi Merge branch 'transaction-form-validation' into main
4767027 hamdirafi tambah validasi dan total di server untuk transaksi
3ec7917 hamdirafi tambah validasi dan total di server untuk transaksi
a73f00a Ahmad Rafi Hamdi Merge pull request #31 from fael83/transaction-form-validation
32c2f68 hamdirafi tambah validasi dan total di server untuk transaksi
b83ef8f meryymarta Merge pull request #30 from fael83/product-form-validation
fc99b1e meryymarta tambah validasi dan form tambah produk
ffe6eae Ailsasahda13 Merge pull request #29 from fael83/fillable-models
e020a24 Ailsa Sahda tambah fillable pada product, transaction, dan transaction detail
3674b15 Ailsa Sahda Merge origin/main into main
004ad13 Ailsa Sahda increment 5: relationship eloquent, eager loading, dan ↪ pagination simple pos
43bb51f Ailsa Sahda increment 5: relationship eloquent, eager loading, dan ↪ pagination simple pos
37c8619 Ailsasahda13 Merge pull request #28 from fael83/product-pagination
4cbd0c8 Ailsasahda13 Merge branch 'main' into product-pagination
72dc82a Ailsa Sahda Add pagination to POS product list
60806da Faell Merge pull request #27 from fael83/item-count-per-transaction
dd17513 fael83 tampilkan jumlah item per transaksi
8e5578a meryymarta Merge pull request #26 from fael83/product-details-relation
78d8504 meryymarta tambah relationship product ke transaction detail
f666ca8 Ailsasahda13 Merge pull request #25 from fael83/product-transactions
edd53de Ailsa Sahda Add product transactions relationship
9bc066b Ahmad Rafi Hamdi Merge pull request #24 from fael83/cashier-name
ec292c9 hamdirafi tampilkan nama kasir pada halaman transactions dengan eager loading user
2a860df Faell Merge pull request #23 from fael83/transaction-history
2e45463 fael83 perbaiki n+1 dengan eager loading
19de4a9 fael83 tambah riwayat transaksi dengan pagination
150154a meryymarta Merge pull request #22 from fael83/product-listing
df1810d meryymarta tambah listing produk dengan eager loading dan pagination
bd26813 Ahmad Rafi Hamdi Merge pull request #21 from fael83/transaction-models
07ff78a hamdirafi tambah model transaction dan transaction detail beserta relationship-nya
8fe5737 Ailsasahda13 Merge pull request #20 from fael83/category-product-relationships
84984b0 Ailsa Sahda tambah relationship category dan product
4deb5f9 fael83 increment 4: skema, seeder, dan index simple pos
dfa9510 Faell Merge pull request #19 from fael83/show-all-in-stock-products
c068e91 fael83 tampilkan seluruh produk dengan stock di atas 0
0c30dee meryymarta Merge pull request #18 from fael83/add-is-active-to-products
31d8eb4 meryymarta tambah is_active dan update seeder produk
91473ac meryymarta Merge pull request #17 from fael83/add-sku-to-products
5a609ce meryymarta tambah kolom sku pada tabel products
7b69dd2 Ailsasahda13 Merge pull request #16 from fael83/index-transaction-details
6bfcbac Ailsa Sahda tambah index transaction details product id
725b0ac Faell Merge pull request #15 from fael83/product-model
41be850 fael83 ganti data hardcode dengan model product
79276e1 Ahmad Rafi Hamdi Merge pull request #14 from fael83/seeder-and-index
3e24382 hamdirafi tambah seeder skala nyata dan index foreign key
9d8c434 meryymarta Merge branch 'main' of https://github.com/fael83/simple-pos-kelompok-02
4039c29 meryymarta tambah migrasi transactions dan transaction_details
46c495f meryymarta Merge pull request #13 from fael83/transactions-migration
9d349e7 meryymarta tambah migrasi transactions dan transaction_details
c1e8a29 Ailsasahda13 Merge pull request #12 from fael83/categories-products-migration
bfc3bde Ailsa Sahda tambah migrasi categories dan products
31cac8d Ahmad Rafi Hamdi Merge pull request #11 from fael83/transaction-history-view
4635b20 hamdirafi tambah view riwayat transaksi dan section title
98e45f1 meryymarta Merge pull request #10 from fael83/active-nav-link
b587a4e meryymarta rapikan jarak menu navigasi dan tambah penanda link aktif
8d2a630 Faell Merge pull request #9 from fael83/add-highlight
3f2ff41 fael83 hapus timer di highlgiht
1342d94 fael83 tambah highlight warna biru ketika produk di klik
8638f09 Ahmad Rafi Hamdi Merge pull request #8 from fael83/low-stock-badge
3c72c29 hamdirafi tambah badge stok menipis pada kartu produk
afecb9c Ailsasahda13 Merge pull request #7 from fael83/remove-cart-item
3195d5e Ailsa Sahda tambah fitur hapus item di  keranjang
2bd78ea Ailsasahda13 Merge pull request #6 from fael83/alpine-cart
93fe2fa Ailsa Sahda tambah keranjang dinamis dengan alpine
82fd062 Faell Merge pull request #5 from fael83/install-alpine
e1fead8 fael83 install alpine.js
f60f54f Faell Merge pull request #4 from fael83/cashier-page
7e8e814 hamdirafi tambah halaman kasir dengan tailwind
b42d7e5 meryymarta Merge pull request #3 from fael83/nav-component
40eda84 meryymarta tambah component nav
04a0795 Faell Merge pull request #2 from fael83/base-layout
e62a4ed fael83 tambah layout dasar aplikasi
8b9b6ad Faell Merge pull request #1 from fael83/simplify-controller
4b19111 meryymarta sederhanakan TransactionController untuk pertemuan 3
790a902 Faell Initial commit
