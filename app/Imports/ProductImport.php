<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProductImport implements ToModel, WithHeadingRow, WithChunkReading, ShouldQueue
{
    public function model(array $row)
    {
        // Validasi kolom dasar per baris
        if (!isset($row['name'], $row['category_id'], $row['price'])) {
            return null;
        }

        return new Product([
            'name'        => $row['name'],
            'category_id' => $row['category_id'],
            'price'       => $row['price'],
        ]);
    }

    public function chunkSize(): int
    {
        return 1000; // Membaca file per 1.000 baris agar memori tetap efisien
    }
}