<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PenjualanModel;
use Dompdf\Dompdf;
use Dompdf\Options;

class laporan extends BaseController
{
    protected $penjualanModel;

    public function __construct()
    {
        helper('form');
        $this->penjualanModel = new PenjualanModel();
    }

    public function index()
    {
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');

        $data = [
            'sales_by_product' => $this->penjualanModel->getSalesSummaryByProduct(),
            'sales_by_date' => $this->penjualanModel->getSalesSummaryByDate($startDate, $endDate),
            'sales_by_cabang' => $this->penjualanModel->getSalesSummaryByCabang($startDate, $endDate),
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];
        return view('laporan/penjualan', $data);
    }

    public function downloadPdf()
    {
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');

        $data = [
            'sales_by_product' => $this->penjualanModel->getSalesSummaryByProduct(),
            'sales_by_date' => $this->penjualanModel->getSalesSummaryByDate($startDate, $endDate),
            'sales_by_cabang' => $this->penjualanModel->getSalesSummaryByCabang($startDate, $endDate),
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);

        $html = view('laporan/penjualan_pdf', $data);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $dompdf->stream('Laporan_Penjualan_' . date('Ymd') . '.pdf', ['Attachment' => 0]);
        exit();
    }
}
