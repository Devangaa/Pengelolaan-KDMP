<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MasterSeeder extends Seeder
{
    public function run()
    {
        echo "Running Master Data Seeders...\n";

        $this->call('UserSeeder'); 
        
        echo "Master Data Seeding Completed!\n";
    }
}