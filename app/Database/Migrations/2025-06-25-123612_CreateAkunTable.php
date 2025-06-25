<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAkunTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_akun' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'kode_akun' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'unique'     => true,
            ],
            'nama_akun' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'posisi_saldo_normal' => [
                'type'       => 'ENUM',
                'constraint' => ['Debit', 'Kredit'],
                'default'    => 'Debit',
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

        $this->forge->addKey('id_akun', true);
        $this->forge->createTable('akun');

        $data = [
            [
                'kode_akun' => '1-1100',
                'nama_akun' => 'Kas',
                'posisi_saldo_normal' => 'Debit'
            ],
            [
                'kode_akun' => '1-1200',
                'nama_akun' => 'Piutang Usaha',
                'posisi_saldo_normal' => 'Debit'
            ],
            [
                'kode_akun' => '1-1300',
                'nama_akun' => 'Persediaan Produk Jadi',
                'posisi_saldo_normal' => 'Debit'
            ],

            [
                'kode_akun' => '4-1100',
                'nama_akun' => 'Pendapatan Penjualan',
                'posisi_saldo_normal' => 'Kredit'
            ],

            [
                'kode_akun' => '5-1100',
                'nama_akun' => 'Beban Pokok Produksi',
                'posisi_saldo_normal' => 'Debit'
            ],
        ];

        $this->db->table('akun')->insertBatch($data);
    }

    public function down()
    {
        $this->forge->dropTable('akun');
    }
}
