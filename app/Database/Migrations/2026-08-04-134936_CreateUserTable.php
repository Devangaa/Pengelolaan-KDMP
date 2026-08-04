<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        // 1. Mendefinisikan Struktur Kolom Tabel
        $this->forge->addField([
            'id' => [
                'type'       => 'VARCHAR',
                'constraint' => 36,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'unique'     => true,
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'role' => [
                'type'       => 'ENUM',
                'constraint' => ['admin', 'kasir'],
                'default'    => 'kasir',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        // 2. Menentukan Primary Key
        $this->forge->addKey('id', true);

        // 3. Menjalankan Perintah Eksekusi Buat Tabel
        $this->forge->createTable('users');
    }

    public function down()
    {
        // Menghapus tabel jika dilakukan rollback
        $this->forge->dropTable('users');
    }
}