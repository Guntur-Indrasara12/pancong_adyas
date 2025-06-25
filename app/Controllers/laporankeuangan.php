<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\JurnalModel;
use App\Models\AkunModel;

class LaporanKeuangan extends BaseController
{
    protected $jurnalModel;
    protected $akunModel;

    public function __construct()
    {
        $this->jurnalModel = new JurnalModel();
        $this->akunModel = new AkunModel();
    }

    /**
     * Menampilkan halaman Jurnal Umum.
     */
    public function jurnal()
    {
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-t');

        $data = [
            'title'     => 'Laporan Jurnal Umum',
            'jurnal'    => $this->jurnalModel->getJurnal($startDate, $endDate),
            'startDate' => $startDate,
            'endDate'   => $endDate,
        ];

        return view('laporan/jurnal', $data);
    }

    /**
     * Menampilkan halaman Buku Besar.
     */
    public function bukuBesar()
    {
        $id_akun = $this->request->getGet('id_akun');
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-t');

        $laporanData = null;
        if ($id_akun) {
            $laporanData = $this->jurnalModel->getBukuBesar($id_akun, $startDate, $endDate);
        }

        $data = [
            'title'     => 'Laporan Buku Besar',
            'list_akun' => $this->akunModel->orderBy('kode_akun', 'ASC')->findAll(),
            'laporan'   => $laporanData,
            'selected_akun' => $id_akun,
            'startDate' => $startDate,
            'endDate'   => $endDate,
        ];

        return view('laporan/buku_besar', $data);
    }

    /**
     * Menampilkan halaman Neraca Saldo.
     */
    public function neracaSaldo()
    {
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-d');

        $rawNeraca = $this->jurnalModel->getNeracaSaldo($endDate);
        $neracaData = [];

        foreach ($rawNeraca as $item) {
            $saldo_akhir = $item['total_debit'] - $item['total_kredit'];

            $debit_final = 0;
            $kredit_final = 0;

            if ($item['posisi_saldo_normal'] == 'Debit') {
                if ($saldo_akhir >= 0) {
                    $debit_final = $saldo_akhir;
                } else {
                    $kredit_final = abs($saldo_akhir);
                }
            } else {
                if ($saldo_akhir <= 0) {
                    $kredit_final = abs($saldo_akhir);
                } else {
                    $debit_final = $saldo_akhir;
                }
            }

            if ($debit_final > 0 || $kredit_final > 0) {
                $neracaData[] = [
                    'kode_akun' => $item['kode_akun'],
                    'nama_akun' => $item['nama_akun'],
                    'debit'     => $debit_final,
                    'kredit'    => $kredit_final,
                ];
            }
        }

        $data = [
            'title'   => 'Laporan Neraca Saldo',
            'neraca'  => $neracaData,
            'endDate' => $endDate
        ];

        return view('laporan/neraca_saldo', $data);
    }
}
