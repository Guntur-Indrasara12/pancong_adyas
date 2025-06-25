<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PenjualanModel;
use App\Models\ProduksiModel;
use Dompdf\Dompdf;
use Dompdf\Options;

class Laporan extends BaseController
{
    protected $penjualanModel;
    protected $produksiModel;

    public function __construct()
    {
        helper('form');
        $this->penjualanModel = new PenjualanModel();
        $this->produksiModel = new ProduksiModel();
    }

    public function index()
    {
        // Ambil tanggal dari filter, jika tidak ada, gunakan tanggal hari ini
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-d');
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-d');

        // Ambil data berdasarkan rentang tanggal
        $sales_by_product = $this->penjualanModel->getSalesSummaryByProduct($startDate, $endDate);
        $sales_by_cabang = $this->penjualanModel->getSalesSummaryByCabang($startDate, $endDate);
        $production_log = $this->produksiModel->getProductionHistory($startDate, $endDate);
        $production_summary = $this->produksiModel->getProductionSummaryByCabang($startDate, $endDate);

        // Hitung total pendapatan dan modal untuk periode yang dipilih
        $total_pendapatan = $this->penjualanModel->getTotalRevenueForPeriod($startDate, $endDate);
        $total_modal = $this->produksiModel->getTotalModalForPeriod($startDate, $endDate);
        $laba_periode = $total_pendapatan - $total_modal;

        $data = [
            'title' => 'Laporan Harian & Periodik',
            'start_date' => $startDate,
            'end_date' => $endDate,
            'sales_by_product' => $sales_by_product,
            'sales_by_cabang' => $sales_by_cabang,
            'production_log' => $production_log,
            'production_summary' => $production_summary,
            'total_pendapatan' => $total_pendapatan,
            'total_modal' => $total_modal,
            'laba_periode' => $laba_periode,
        ];

        return view('laporan/penjualan', $data);
    }

    public function downloadPdf()
    {
        // Ambil tanggal dari parameter GET, default ke hari ini jika tidak ada
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-d');
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-d');

        $total_pendapatan = $this->penjualanModel->getTotalRevenueForPeriod($startDate, $endDate);
        $total_modal = $this->produksiModel->getTotalModalForPeriod($startDate, $endDate);
        $laba_periode = $total_pendapatan - $total_modal;

        $data = [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'sales_by_product' => $this->penjualanModel->getSalesSummaryByProduct($startDate, $endDate),
            'sales_by_cabang' => $this->penjualanModel->getSalesSummaryByCabang($startDate, $endDate),
            'production_log' => $this->produksiModel->getProductionHistory($startDate, $endDate),
            'production_summary' => $this->produksiModel->getProductionSummaryByCabang($startDate, $endDate),
            'total_pendapatan' => $total_pendapatan,
            'total_modal' => $total_modal,
            'laba_periode' => $laba_periode,
        ];

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Helvetica'); // Set font default

        $dompdf = new Dompdf($options);
        $html = view('laporan/penjualan_pdf', $data);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Nama file dinamis berdasarkan periode
        $filename = 'Laporan_Periode_' . $startDate . '_sd_' . $endDate . '.pdf';
        $dompdf->stream($filename, ['Attachment' => 0]); // Tampilkan di browser
        exit();
    }
}
