<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateJurnalTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_jurnal' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_akun' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'tgl_jurnal' => [
                'type' => 'DATETIME',
            ],
            'deskripsi' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'debit' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0.00,
            ],
            'kredit' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0.00,
            ],
            'ref_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'ref_table' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id_jurnal', true);
        $this->forge->addForeignKey('id_akun', 'akun', 'id_akun', 'CASCADE', 'CASCADE');
        $this->forge->createTable('jurnal');
    }

    public function down()
    {
        $this->forge->dropTable('jurnal');
    }
}
