<?php

namespace App\Models;

use CodeIgniter\Model;

class PenjualanModel extends Model
{
    protected $table            = 'penjualan';
    protected $primaryKey       = 'id_penjualan';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_produk', 'jumlah_terjual', 'harga_jual_satuan', 'total_harga', 'id_cabang', 'tgl_penjualan'];

    protected bool $allowEmptyInserts = false;

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getPenjualan()
    {
        return $this->select('penjualan.*, produk.nama_produk, cabang.nama_cabang')
            ->join('produk', 'produk.id_produk = penjualan.id_produk')
            ->join('cabang', 'cabang.id_cabang = penjualan.id_cabang', 'left')
            ->orderBy('tgl_penjualan', 'DESC')
            ->findAll();
    }
}
