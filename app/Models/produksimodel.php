<?php

namespace App\Models;

use CodeIgniter\Model;

class produksimodel extends Model
{
    protected $table            = 'log_produksi';
    protected $primaryKey       = 'id_log';
    protected $allowedFields    = ['id_produk', 'jumlah_hasil', 'total_modal', 'id_cabang', 'tgl_produksi'];

    // public function getProductionHistory()
    // {
    //     return $this->db->table('log_produksi')
    //         ->select('log_produksi.*, produk.nama_produk, cabang.nama_cabang')
    //         ->join('produk', 'produk.id_produk = log_produksi.id_produk')
    //         ->join('cabang', 'cabang.id_cabang = log_produksi.id_cabang')
    //         ->orderBy('log_produksi.tgl_produksi', 'DESC')
    //         ->get()->getResultArray();
    // }

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

    // public function getTodaysProducedProductsWithStock()
    // {
    //     $today = date('Y-m-d');
    //     return $this->select('produk.id_produk, produk.nama_produk, produk.harga, produk.stok')
    //         ->join('produk', 'produk.id_produk = log_produksi.id_produk')
    //         ->where('DATE(log_produksi.tgl_produksi)', $today)
    //         ->groupBy('produk.id_produk, produk.nama_produk, produk.harga, produk.stok')
    //         ->findAll();
    // }

    public function getProductionHistory($startDate, $endDate)
    {
        return $this->db->table('log_produksi')
            ->select('log_produksi.*, produk.nama_produk, cabang.nama_cabang')
            ->join('produk', 'produk.id_produk = log_produksi.id_produk')
            ->join('cabang', 'cabang.id_cabang = log_produksi.id_cabang', 'left')
            ->where('DATE(log_produksi.tgl_produksi) >=', $startDate)
            ->where('DATE(log_produksi.tgl_produksi) <=', $endDate)
            ->orderBy('log_produksi.tgl_produksi', 'DESC')
            ->get()->getResultArray();
    }

    public function getProductionSummaryByCabang($startDate, $endDate)
    {
        $builder = $this->select('cabang.nama_cabang, SUM(log_produksi.jumlah_hasil) as total_produksi_pcs, SUM(log_produksi.total_modal) as total_modal_produksi')
            ->join('cabang', 'cabang.id_cabang = log_produksi.id_cabang', 'left')
            ->where('DATE(log_produksi.tgl_produksi) >=', $startDate)
            ->where('DATE(log_produksi.tgl_produksi) <=', $endDate)
            ->groupBy('cabang.nama_cabang')
            ->orderBy('total_modal_produksi', 'DESC');

        return $builder->findAll();
    }

    public function getTotalModalForPeriod($startDate, $endDate)
    {
        $result = $this->selectSum('total_modal', 'total_cost')
            ->where('DATE(tgl_produksi) >=', $startDate)
            ->where('DATE(tgl_produksi) <=', $endDate)
            ->get()
            ->getRow();

        return $result ? (float)$result->total_cost : 0;
    }

    public function getTotalModalForDate($date)
    {
        return $this->getTotalModalForPeriod($date, $date);
    }
}
