<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table            = 'transactions';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;

    protected $allowedFields    = [
        'id',
        'transaction_id',
        'user_id',
        'member_id',
        'total',
        'pay',
        'change'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'transaction_id' => 'required|max_length[20]',
        'user_id'        => 'required|is_not_unique[users.id]|max_length[36]',
        'member_id'      => 'required|is_not_unique[members.id]|max_length[36]',
        'total'          => 'required|integer|greater_than_equal_to[0]',
        'pay'            => 'required|integer|greater_than_equal_to[0]',
        'change'         => 'required|integer|greater_than_equal_to[0]',
    ];
    protected $validationMessages   = [
        'transaction_id' => [
            'required' => 'ID transaksi wajib diisi.',
            'max_length' => 'ID transaksi maksimal 20 karakter.',
        ],
        'user_id' => [
            'required' => 'ID pengguna wajib diisi.',
            'is_not_unique' => 'ID pengguna tidak valid.',
            'max_length' => 'ID pengguna maksimal 36 karakter.',
        ],
        'member_id' => [
            'required' => 'ID anggota wajib diisi.',
            'is_not_unique' => 'ID anggota tidak valid.',
            'max_length' => 'ID anggota maksimal 36 karakter.',
        ],
        'total' => [
            'required' => 'Total transaksi wajib diisi.',
            'integer' => 'Total transaksi harus berupa angka.',
            'greater_than_equal_to' => 'Total transaksi harus lebih besar atau sama dengan 0.',
        ],
        'pay' => [
            'required' => 'Jumlah pembayaran wajib diisi.',
            'integer' => 'Jumlah pembayaran harus berupa angka.',
            'greater_than_equal_to' => 'Jumlah pembayaran harus lebih besar atau sama dengan 0.',
        ],
        'change' => [
            'required' => 'Kembalian wajib diisi.',
            'integer' => 'Kembalian harus berupa angka.',
            'greater_than_equal_to' => 'Kembalian harus lebih besar atau sama dengan 0.',
        ],
    ];
}
