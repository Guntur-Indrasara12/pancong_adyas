<?php

namespace App\Models;

use CodeIgniter\Model;

class produksimodel extends Model
{
    protected $table            = 'log_produksi';
    protected $primaryKey       = 'id_log';
    protected $allowedFields    = ['id_produk', 'jumlah_hasil', 'total_modal', 'id_cabang', 'tgl_produksi'];

    public function getProductionHistory()
    {
        return $this->db->table('log_produksi')
            ->select('log_produksi.*, produk.nama_produk, cabang.nama_cabang')
            ->join('produk', 'produk.id_produk = log_produksi.id_produk')
            ->join('cabang', 'cabang.id_cabang = log_produksi.id_cabang')
            ->orderBy('log_produksi.tgl_produksi', 'DESC')
            ->get()->getResultArray();
    }

    public function getTodaysProduction()
    {
        $today = date('Y-m-d');
        return $this->db->table('log_produksi')
            ->select('log_produksi.*, produk.nama_produk, cabang.nama_cabang')
            ->join('produk', 'produk.id_produk = log_produksi.id_produk')
            ->join('cabang', 'cabang.id_cabang = log_produksi.id_cabang')
            ->where("DATE(log_produksi.tgl_produksi)", $today)
            ->orderBy('log_produksi.tgl_produksi', 'ASC')
            ->get()->getResultArray();
    }

    public function getTodaysProducedProductsWithStock()
    {
        $today = date('Y-m-d');
        return $this->select('produk.id_produk, produk.nama_produk, produk.harga, produk.stok')
            ->join('produk', 'produk.id_produk = log_produksi.id_produk')
            ->where('DATE(log_produksi.tgl_produksi)', $today)
            ->groupBy('produk.id_produk, produk.nama_produk, produk.harga, produk.stok')
            ->findAll();
    }

    public function getProductionSummaryByCabang($startDate = null, $endDate = null)
    {
        $today = date('Y-m-d');
        $builder = $this->select('cabang.nama_cabang, SUM(log_produksi.jumlah_hasil) as total_produksi_pcs, SUM(log_produksi.total_modal) as total_modal_produksi')
            ->join('cabang', 'cabang.id_cabang = log_produksi.id_cabang')
            ->where('DATE(log_produksi.tgl_produksi)', $today)
            ->groupBy('cabang.nama_cabang')
            ->orderBy('cabang.nama_cabang', 'ASC');

        return $builder->findAll();
    }
}
