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

    public function getSalesSummaryByProduct()
    {
        return $this->select('produk.nama_produk, SUM(penjualan.jumlah_terjual) as total_jumlah, SUM(penjualan.total_harga) as total_pendapatan')
            ->join('produk', 'produk.id_produk = penjualan.id_produk')
            ->groupBy('produk.nama_produk')
            ->findAll();
    }

    public function getSalesSummaryByDate($startDate = null, $endDate = null)
    {
        $builder = $this->select('DATE(penjualan.tgl_penjualan) as tanggal, SUM(penjualan.total_harga) as total_pendapatan_harian, SUM(penjualan.jumlah_terjual) as total_terjual_harian')
            ->groupBy('DATE(penjualan.tgl_penjualan)')
            ->orderBy('tanggal', 'DESC');

        if ($startDate && $endDate) {
            $builder->where('DATE(penjualan.tgl_penjualan) >=', $startDate)
                ->where('DATE(penjualan.tgl_penjualan) <=', $endDate);
        } else {
            $builder->where('DATE(penjualan.tgl_penjualan) >=', date('Y-m-d', strtotime('-30 days')));
        }

        return $builder->findAll();
    }

    public function getSalesSummaryByCabang($startDate = null, $endDate = null)
    {
        $builder = $this->select('cabang.nama_cabang, SUM(penjualan.total_harga) as total_pendapatan_cabang, SUM(penjualan.jumlah_terjual) as total_terjual_cabang')
            ->join('cabang', 'cabang.id_cabang = penjualan.id_cabang', 'left')
            ->groupBy('cabang.nama_cabang')
            ->orderBy('cabang.nama_cabang', 'ASC');

        if ($startDate && $endDate) {
            $builder->where('DATE(penjualan.tgl_penjualan) >=', $startDate)
                ->where('DATE(penjualan.tgl_penjualan) <=', $endDate);
        } else {
            $builder->where('DATE(penjualan.tgl_penjualan) >=', date('Y-m-d', strtotime('-30 days')));
        }

        return $builder->findAll();
    }
}
