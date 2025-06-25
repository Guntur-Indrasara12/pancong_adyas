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
    // protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
    protected $afterInsert    = ['createPenjualanJurnal'];

    public function getPenjualan()
    {
        return $this->select('penjualan.*, produk.nama_produk, cabang.nama_cabang')
            ->join('produk', 'produk.id_produk = penjualan.id_produk')
            ->join('cabang', 'cabang.id_cabang = penjualan.id_cabang', 'left')
            ->orderBy('tgl_penjualan', 'DESC')
            ->findAll();
    }

    // public function getSalesSummaryByProduct()
    // {
    //     return $this->select('produk.nama_produk, SUM(penjualan.jumlah_terjual) as total_jumlah, SUM(penjualan.total_harga) as total_pendapatan')
    //         ->join('produk', 'produk.id_produk = penjualan.id_produk')
    //         ->groupBy('produk.nama_produk')
    //         ->findAll();
    // }

    public function getSalesSummaryByProduct($startDate, $endDate)
    {
        return $this->select('produk.nama_produk, SUM(penjualan.jumlah_terjual) as total_jumlah, SUM(penjualan.total_harga) as total_pendapatan')
            ->join('produk', 'produk.id_produk = penjualan.id_produk')
            ->where('DATE(penjualan.tgl_penjualan) >=', $startDate)
            ->where('DATE(penjualan.tgl_penjualan) <=', $endDate)
            ->groupBy('produk.nama_produk')
            ->orderBy('total_pendapatan', 'DESC')
            ->findAll();
    }

    public function getSalesSummaryByCabang($startDate, $endDate)
    {
        $builder = $this->select('cabang.nama_cabang, SUM(penjualan.total_harga) as total_pendapatan_cabang, SUM(penjualan.jumlah_terjual) as total_terjual_cabang')
            ->join('cabang', 'cabang.id_cabang = penjualan.id_cabang', 'left')
            ->groupBy('cabang.nama_cabang')
            ->orderBy('total_pendapatan_cabang', 'DESC');

        if ($startDate && $endDate) {
            $builder->where('DATE(penjualan.tgl_penjualan) >=', $startDate)
                ->where('DATE(penjualan.tgl_penjualan) <=', $endDate);
        }

        return $builder->findAll();
    }

    public function getTotalRevenueForPeriod($startDate, $endDate)
    {
        $result = $this->selectSum('total_harga', 'total_revenue')
            ->where('DATE(tgl_penjualan) >=', $startDate)
            ->where('DATE(tgl_penjualan) <=', $endDate)
            ->get()
            ->getRow();

        return $result ? (float)$result->total_revenue : 0;
    }

    public function getTotalRevenueForDate($date)
    {
        return $this->getTotalRevenueForPeriod($date, $date);
    }

    protected function createPenjualanJurnal(array $data)
    {
        if (!isset($data['id']) || !isset($data['data'])) {
            return $data;
        }

        $penjualanData = $data['data'];
        $id_penjualan = $data['id'];

        $jurnalModel = new \App\Models\JurnalModel();
        $akunModel = new \App\Models\AkunModel();

        $akunKas = $akunModel->where('kode_akun', '1-1100')->first();
        $akunPendapatan = $akunModel->where('kode_akun', '4-1100')->first();

        if ($akunKas && $akunPendapatan) {
            $totalHarga = $penjualanData['total_harga'];
            $tglJurnal = $penjualanData['tgl_penjualan'] ?? date('Y-m-d H:i:s');

            $jurnalEntries = [
                [
                    'id_akun'    => $akunKas['id_akun'],
                    'tgl_jurnal' => $tglJurnal,
                    'debit'      => $totalHarga,
                    'kredit'     => 0,
                    'deskripsi'  => 'Pendapatan dari penjualan ID: ' . $id_penjualan,
                    'ref_id'     => $id_penjualan,
                    'ref_table'  => 'penjualan'
                ],
                [
                    'id_akun'    => $akunPendapatan['id_akun'],
                    'tgl_jurnal' => $tglJurnal,
                    'debit'      => 0,
                    'kredit'     => $totalHarga,
                    'deskripsi'  => 'Pendapatan dari penjualan ID: ' . $id_penjualan,
                    'ref_id'     => $id_penjualan,
                    'ref_table'  => 'penjualan'
                ]
            ];

            $jurnalModel->insertBatch($jurnalEntries);
        }

        return $data;
    }
}
