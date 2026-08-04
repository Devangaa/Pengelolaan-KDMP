<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductCategoryModel extends BaseModel
{
    protected $table            = 'product_categories';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields    = [
        'id',
        'name',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'name' => 'required|max_length[100]|is_unique[product_categories.name,id,{id}]',
    ];
    protected $validationMessages   = [
        'name' => [
            'required' => 'Nama kategori wajib diisi.',
            'max_length' => 'Nama kategori maksimal 100 karakter.',
            'is_unique' => 'Nama kategori ini sudah terdaftar di sistem.',
        ],
    ];
}
