<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\User;

class UserModel extends BaseModel
{
    protected $table            = 'users';
    protected $useSoftDeletes   = true;   
    protected $protectFields    = true;

    protected $allowedFields    = [
        'id',
        'name',
        'email',
        'password',
        'role',
    ];

    protected $returnType = User::class;

    // Pengaturan Timestamps
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Rules Validasi Bawaan (Memudahkan Controller)
    protected $validationRules = [
        'name'     => 'required|min_length[3]|max_length[100]',
        'email'    => 'required|valid_email|is_unique[users.email,id,{id}]',
        'password' => 'required|min_length[6]',
        'role'     => 'required|in_list[admin,kasir]',
    ];
    protected $validationMessages = [
        'email' => [
            'required'    => 'Alamat email wajib diisi.',
            'is_unique' => 'Email ini sudah terdaftar di sistem.',
            'valid_email' => 'Format email tidak valid.',
        ],
        'name' => [
            'required'    => 'Nama wajib diisi.',
            'min_length'  => 'Nama minimal 3 karakter.',
            'max_length'  => 'Nama maksimal 100 karakter.',
        ],
        'password' => [
            'required'    => 'Password wajib diisi.',
            'min_length'  => 'Password minimal 6 karakter.',
        ],
        'role' => [
            'required'    => 'Role wajib diisi.',
            'in_list'     => 'Role harus berupa "admin" atau "kasir".',
        ],
    ];
}