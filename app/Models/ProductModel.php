<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends BaseModel
{
    protected $table            = 'products';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;

    protected $allowedFields    = [
        'id',
        'name',
        'category_id',
        'buy_price',
        'sell_price',
        'stock',
        'unit',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'name' => 'required|max_length[100]',
        'category_id' => 'required|is_not_unique[product_categories.id]',
        'buy_price' => 'required|integer|greater_than_equal_to[0]',
        'sell_price' => 'required|integer|greater_than_equal_to[0]',
        'stock' => 'required|integer|greater_than_equal_to[0]',
        'unit' => 'required|max_length[20]',
    ];
    protected $validationMessages   = [
        'name' => [
            'required' => 'Nama produk wajib diisi.',
            'max_length' => 'Nama produk maksimal 100 karakter.',
        ],
        'category_id' => [
            'required' => 'Kategori produk wajib diisi.',
            'is_not_unique' => 'Kategori produk tidak valid.',
        ],
        'buy_price' => [
            'required' => 'Harga beli wajib diisi.',
            'integer' => 'Harga beli harus berupa angka.',
            'greater_than_equal_to' => 'Harga beli harus lebih besar atau sama dengan 0.',
        ],
        'sell_price' => [
            'required' => 'Harga jual wajib diisi.',
            'integer' => 'Harga jual harus berupa angka.',
            'greater_than_equal_to' => 'Harga jual harus lebih besar atau sama dengan 0.',
        ],
        'stock' => [
            'required' => 'Stok wajib diisi.',
            'integer' => 'Stok harus berupa angka.',
            'greater_than_equal_to' => 'Stok harus lebih besar atau sama dengan 0.',
        ],
        'unit' => [
            'required' => 'Satuan wajib diisi.',
            'max_length' => 'Satuan maksimal 20 karakter.',
        ],
    ];

    public function getProductsWithCategory($id = null)
    {
        $builder = $this->select('products.*, product_categories.name as category_name')
                        ->join('product_categories', 'products.category_id = product_categories.id', 'left');

        if ($id !== null) {
            return $builder->where('products.id', $id)->first();
        }

        return $builder->findAll();
    }
}
