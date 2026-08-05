<?php

namespace App\Models;

use App\Models\BaseModel;

class TransactionDetailModel extends BaseModel
{
    protected $table            = 'transaction_details';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields    = [
        'id',
        'transaction_id',
        'product_id',
        'price',
        'quantity',
        'subtotal'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'transaction_id' => 'required|is_not_unique[transactions.id]|max_length[36]',
        'product_id' => 'required|is_not_unique[products.id]|max_length[36]',
        'price' => 'required|integer|greater_than_equal_to[0]',
        'quantity' => 'required|integer|greater_than_equal_to[0]',
        'subtotal' => 'required|integer|greater_than_equal_to[0]', 
    ];
    protected $validationMessages   = [
        'transaction_id' => [
            'required' => 'ID transaksi wajib diisi.',
            'is_not_unique' => 'ID transaksi tidak valid.',
            'max_length' => 'ID transaksi maksimal 36 karakter.',
        ],
        'product_id' => [
            'required' => 'ID produk wajib diisi.',
            'is_not_unique' => 'ID produk tidak valid.',
            'max_length' => 'ID produk maksimal 36 karakter.',
        ],
        'price' => [
            'required' => 'Harga produk wajib diisi.',
            'integer' => 'Harga produk harus berupa angka.',
            'greater_than_equal_to' => 'Harga produk harus lebih besar atau sama dengan 0.',
        ],
        'quantity' => [
            'required' => 'Jumlah produk wajib diisi.',
            'integer' => 'Jumlah produk harus berupa angka.',
            'greater_than_equal_to' => 'Jumlah produk harus lebih besar atau sama dengan 0.',
        ],
        'subtotal' => [
            'required' => 'Subtotal wajib diisi.',
            'integer' => 'Subtotal harus berupa angka.',
            'greater_than_equal_to' => 'Subtotal harus lebih besar atau sama dengan 0.',
        ],
    ];
}
