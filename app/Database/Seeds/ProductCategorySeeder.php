<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\ProductCategoryModel;

class ProductCategorySeeder extends Seeder
{
    public function run()
    {
        $productCategoryModel = new ProductCategoryModel();

        $categories = [
            ['name' => 'Pakaian'],
            ['name' => 'Makanan'],
            ['name' => 'Minuman'],
        ];

        foreach ($categories as $category) {
            $productCategoryModel->save($category);
        }
    }
}
