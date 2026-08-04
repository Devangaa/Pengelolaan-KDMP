<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMembersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'       => 'VARCHAR',
                'constraint' => 36,
            ],
            'nik' => [
                'type'       => 'VARCHAR',
                'constraint' => 16,
                'unique'     => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'address' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'phone' => [
                'type'       => 'VARCHAR',
                'constraint' => 15,
                'null'       => true,
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

        $this->forge->addKey('id', true);

        $this->forge->createTable('members');
    }

    public function down()
    {
        $this->forge->dropTable('members');
    }
}
