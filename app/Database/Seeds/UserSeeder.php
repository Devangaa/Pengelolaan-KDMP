<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Entities\User;
use App\Models\UserModel;

class UserSeeder extends Seeder
{
    public function run()
    {
        $userModel = new UserModel();

        $admin = new User([
            'name' => 'Budi Santoso',
            'email' => 'budi.santoso@gmail.com',
            'password' => 'password123',
            'role' => 'admin',
            'avatar' => null,
        ]);

        $kasir = new User([
            'name' => 'Siti Aminah',
            'email' => 'siti.aminah@gmail.com',
            'password' => 'password123',
            'role' => 'kasir',
            'avatar' => null,
        ]);

        $userModel->save($admin);
        $userModel->save($kasir);
    }
}
