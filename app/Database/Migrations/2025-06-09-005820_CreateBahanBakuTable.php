<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class BuatTabelBahanBaku extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_bahan' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'nama_bahan' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'unique' => true,
            ],
            'harga_beli' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'satuan' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
            ],
            'jenis' => [
                'type' => 'ENUM',
                'constraint' => ['utama', 'toping', 'varian'],
                'default' => 'utama',
            ],
        ]);

        $this->forge->addKey('id_bahan', true);
        $this->forge->createTable('bahan_baku');

        $data = [
            [
                'nama_bahan' => 'Tepung Terigu',
                'harga_beli' => 13000,
                'satuan' => 'Kg',
                'jenis' => 'utama'
            ],
            [
                'nama_bahan' => 'Gula',
                'harga_beli' => 10000,
                'satuan' => 'Kg',
                'jenis' => 'utama'
            ],
            [
                'nama_bahan' => 'Telur',
                'harga_beli' => 2000,
                'satuan' => 'butir',
                'jenis' => 'utama'
            ],
            [
                'nama_bahan' => 'Margarin',
                'harga_beli' => 3000,
                'satuan' => 'gram',
                'jenis' => 'utama'
            ],
        ];

        $this->db->table('bahan_baku')->insertBatch($data);
    }

    public function down()
    {
        $this->forge->dropTable('bahan_baku', true);
    }
}
