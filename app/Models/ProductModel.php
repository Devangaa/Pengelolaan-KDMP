<?php

namespace App\Models;

use App\Models\BaseModel;

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
        'description',
        'image',
        'barcode',
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
        'description' => 'permit_empty|max_length[255]',
        'image' => 'permit_empty|is_image[image]|max_size[image,1024]|ext_in[image,png,jpg,jpeg,gif,webp]',
        'barcode' => 'permit_empty|max_length[50]|is_unique[products.barcode]',
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
        'description' => [
            'max_length' => 'Deskripsi maksimal 255 karakter.',
        ],
        'image' => [
            'is_image' => 'File harus berupa gambar.',
            'max_size' => 'Ukuran gambar maksimal 1MB.',
            'ext_in' => 'Format gambar harus berupa PNG, JPG, JPEG, GIF, atau WEBP.',
        ],
        'barcode' => [
            'max_length' => 'Barcode maksimal 50 karakter.',
            'is_unique' => 'Barcode sudah digunakan oleh produk lain.',
        ],
    ];

    public function getFilteredProducts($categoryId = null, $search = null, $sort = 'popular')
    {
        $this->select('products.*, product_categories.name as category_name, COALESCE(SUM(transaction_details.quantity), 0) as total_sold')
            ->join('product_categories', 'products.category_id = product_categories.id', 'left')
            ->join('transaction_details', 'transaction_details.product_id = products.id', 'left')
            ->groupBy('products.id');

        if ($categoryId) {
            $this->where('products.category_id', $categoryId);
        }

        if ($search) {
            $this->like('products.name', $search);
        }

        $this->orderBy('(CASE WHEN products.stock > 0 THEN 0 ELSE 1 END)', 'ASC');

        switch ($sort) {
            case 'name_asc':   $this->orderBy('products.name', 'ASC'); break;
            case 'name_desc':  $this->orderBy('products.name', 'DESC'); break;
            case 'price_asc':  $this->orderBy('products.sell_price', 'ASC'); break;
            case 'price_desc': $this->orderBy('products.sell_price', 'DESC'); break;
            case 'newest':     $this->orderBy('products.id', 'DESC'); break;
            case 'popular':
            default:           $this->orderBy('total_sold', 'DESC'); break;
        }

        return $this; 
    }

    public function getPopularProducts($limit = 8)
    {
        return $this->getFilteredProducts(null, null, 'popular')->findAll($limit);
    }

    public function getLowStockProducts(int $limit = 10, int $threshold = 5)
    {
        return $this->builder()
            ->select('products.id, products.name as nama_produk, product_categories.name as kategori, products.stock as stok')
            ->join('product_categories', 'product_categories.id = products.category_id', 'left')
            ->where('products.deleted_at', null)
            ->where('products.stock <', $threshold)
            ->orderBy('products.stock', 'ASC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    public function getByBarcode(string $barcode)
    {
        // Gunakan builder baru untuk menghindari state contamination
        return $this->builder()
            ->where('barcode', $barcode)
            ->where('deleted_at', null)
            ->get()
            ->getFirstRow('array');
    }
}
