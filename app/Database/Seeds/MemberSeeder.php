<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\MemberModel;

class MemberSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create('id_ID');
        $memberModel = new MemberModel();

        $data = [];

        for ($i = 0; $i < 10; $i++) {
            $data[] = [
                'nik'        => $faker->unique()->numerify('3509############'),
                'name'       => $faker->name(),
                'address'    => $faker->address(),
                'phone'      => $faker->phoneNumber(),
            ];


            $memberModel->save($data);
        }
    }
}