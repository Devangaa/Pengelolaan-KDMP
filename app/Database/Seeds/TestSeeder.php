<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TestSeeder extends Seeder
{
    public function run()
    {
        echo "Running Test Data Seeders...\n";

        $this->call('MemberSeeder');
        $this->call('ProductSeeder');
        $this->call('TransactionSeeder'); 

        echo "Test Data Seeding Completed!\n";
    }
}