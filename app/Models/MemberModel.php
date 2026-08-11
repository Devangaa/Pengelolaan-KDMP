<?php

namespace App\Models;

use App\Models\BaseModel;

class MemberModel extends BaseModel
{
    protected $table            = 'members';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;

    protected $allowedFields    = [
        'id',
        'nik',
        'name',
        'address',
        'phone',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'nik' => 'required|exact_length[16]|is_unique[members.nik,id,{id}]',
        'name' => 'required|min_length[3]|max_length[100]',
        'address' => 'required|min_length[10]|max_length[255]',
        'phone' => 'required|numeric|min_length[10]|max_length[15]',
    ];
    protected $validationMessages   = [
        'nik' => [
            'required' => 'NIK wajib diisi.',
            'exact_length' => 'NIK harus terdiri dari 16 digit.',
            'is_unique' => 'NIK ini sudah terdaftar di sistem.',
        ],
        'name' => [
            'required' => 'Nama wajib diisi.',
            'min_length' => 'Nama minimal 3 karakter.',
            'max_length' => 'Nama maksimal 100 karakter.',
        ],
        'address' => [
            'required' => 'Alamat wajib diisi.',
            'min_length' => 'Alamat minimal 10 karakter.',
            'max_length' => 'Alamat maksimal 255 karakter.',
        ],
        'phone' => [
            'required' => 'Nomor telepon wajib diisi.',
            'numeric' => 'Nomor telepon harus berupa angka.',
            'min_length' => 'Nomor telepon minimal 10 digit.',
            'max_length' => 'Nomor telepon maksimal 15 digit.',
        ],
    ];

    public function countActiveMembers(): int
    {
        return (int) $this->builder()
            ->where('deleted_at', null)
            ->countAllResults(false);
    }
}