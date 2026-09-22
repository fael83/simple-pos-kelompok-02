<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $categoryIds = collect(['Makanan', 'Minuman', 'Snack', 'Lainnya'])
            ->map(fn (string $name) => Category::create(['name' => $name])->id)
            ->all();

        $products = [];

        foreach ($categoryIds as $categoryId) {
            for ($i = 0; $i < 75; $i++) {
                $products[] = [
                    'category_id' => $categoryId,
                    'name' => fake()->words(2, true),
                    'price' => fake()->numberBetween(3000, 50000),
                    'stock' => fake()->numberBetween(0, 200),
                    'is_active' => fake()->boolean(90),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        foreach (array_chunk($products, 50) as $chunk) {
            DB::table('products')->insert($chunk);
        }

        $productPrices = DB::table('products')->pluck('price', 'id');
        $productIds = $productPrices->keys()->all();

        for ($t = 0; $t < 2500; $t++) {
            DB::transaction(function () use ($user, $productIds, $productPrices) {
                $itemCount = fake()->numberBetween(1, 4);
                $total = 0;
                $details = [];

                for ($i = 0; $i < $itemCount; $i++) {
                    $productId = fake()->randomElement($productIds);
                    $qty = fake()->numberBetween(1, 3);
                    $subtotal = $productPrices[$productId] * $qty;
                    $total += $subtotal;

                    $details[] = [
                        'product_id' => $productId,
                        'qty' => $qty,
                        'subtotal' => $subtotal,
                    ];
                }

                $transactionId = DB::table('transactions')->insertGetId([
                    'user_id' => $user->id,
                    'total' => $total,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                foreach ($details as &$detail) {
                    $detail['transaction_id'] = $transactionId;
                    $detail['created_at'] = now();
                    $detail['updated_at'] = now();
                }

                DB::table('transaction_details')->insert($details);
            });
        }
    }
}