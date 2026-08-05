<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\ProductCategoryModel;
use App\Models\ProductModel;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create('id_ID');
        $productModel = new ProductModel();

        $categories = $productModel->getProductCategories();

        $data = [];

        for ($i = 0; $i < 10; $i++) {
            

            $data[] = [
                'name'        => $faker->word(),
                'category_id' => $faker->randomElement($categories),
                'buy_price'    => $faker->numberBetween(1000, 50000),
                'sell_price'   => $faker->numberBetween(50000, 100000),
                'stock'        => $faker->numberBetween(1, 100),
                'unit'         => $faker->randomElement(['pcs', 'kg', 'liter']),
                'created_at'   => $faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d H:i:s'),
                'updated_at'   => $faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d H:i:s'),
            ];
        }

        foreach ($data as $product) {
            $productModel->save($product);
        }
    }
}
