<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProduksiModel;
use App\Models\PenjualanModel;
use App\Models\CabangModel;

class Home extends BaseController
{
    protected $produksiModel;
    protected $penjualanModel;
    protected $cabangModel;

    public function __construct()
    {
        $this->produksiModel = new ProduksiModel();
        $this->penjualanModel = new PenjualanModel();
        $this->cabangModel = new CabangModel();
    }

    public function index()
    {
        $today = date('Y-m-d');

        $allCabang = $this->cabangModel->getCabang();
        $cabangNames = array_column($allCabang, 'nama_cabang');

        $produksiSummary = $this->produksiModel->getProductionSummaryByCabang($today, $today);

        $salesSummary = $this->penjualanModel->getSalesSummaryByCabang($today, $today);

        $productionChartData = array_fill_keys($cabangNames, 0);
        foreach ($produksiSummary as $data) {
            $productionChartData[$data['nama_cabang']] = (int)$data['total_produksi_pcs'];
        }

        $salesChartData = array_fill_keys($cabangNames, 0);
        foreach ($salesSummary as $data) {
            $salesChartData[$data['nama_cabang']] = (int)$data['total_terjual_cabang'];
        }


        $totalPenjualanHariIni = $this->penjualanModel
            ->where('DATE(tgl_penjualan)', $today)
            ->selectSum('total_harga')
            ->first()['total_harga'] ?? 0;

        $totalProduksiHariIni = $this->produksiModel
            ->where('DATE(tgl_produksi)', $today)
            ->selectSum('jumlah_hasil')
            ->first()['jumlah_hasil'] ?? 0;

        $totalProdukTerjualHariIniPcs = $this->penjualanModel
            ->where('DATE(tgl_penjualan)', $today)
            ->selectSum('jumlah_terjual')
            ->first()['jumlah_terjual'] ?? 0;


        $data = [
            'page_title' => 'Dashboard Analitik',
            'page_subheading' => 'Perbandingan Produksi dan Penjualan Antar Cabang Hari Ini.',
            'chart_labels' => json_encode($cabangNames),
            'production_data' => json_encode(array_values($productionChartData)),
            'sales_data' => json_encode(array_values($salesChartData)),
            'total_penjualan_umum' => $totalPenjualanHariIni,
            'total_produksi_umum' => $totalProduksiHariIni,
            'total_produk_terjual_hari_ini' => $totalProdukTerjualHariIniPcs,
        ];

        return view('index', $data);
    }
}
