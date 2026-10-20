# Pengukuran Memori: get() vs lazyById()

Dataset: sekitar 52.500 row pada tabel `transactions`.

| Cara baca data | Peak memory (MB) |
|---|---|
| `Transaction::lazyById()` | 70.5 MB |
| `Transaction::get()` | Error / $\ge 128$ MB (Memory Limit Exceeded) |

Kesimpulan kelompok: `Transaction::lazyById()` sangat efisien karena memproses data secara bertahap (per *chunk*) dengan penggunaan peak memory yang stabil (70.5 MB). Sebaliknya, `Transaction::get()` gagal dan mengalami *fatal error* (`Allowed memory size exhausted`) karena mencoba memuat seluruh 52.500 row objek Eloquent sekaligus ke dalam RAM.