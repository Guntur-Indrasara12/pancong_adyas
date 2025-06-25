<?php

namespace App\Models;

use CodeIgniter\Model;

class jurnalmodel extends Model
{
    protected $table            = 'jurnal';
    protected $primaryKey       = 'id_jurnal';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = ['id_akun', 'tgl_jurnal', 'deskripsi', 'debit', 'kredit', 'ref_id', 'ref_table'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';


    public function getJurnal($startDate, $endDate)
    {
        return $this->select('jurnal.*, akun.kode_akun, akun.nama_akun')
            ->join('akun', 'akun.id_akun = jurnal.id_akun')
            ->where('DATE(jurnal.tgl_jurnal) >=', $startDate)
            ->where('DATE(jurnal.tgl_jurnal) <=', $endDate)
            ->orderBy('jurnal.tgl_jurnal', 'ASC')
            ->orderBy('jurnal.id_jurnal', 'ASC')
            ->findAll();
    }


    public function getBukuBesar($id_akun, $startDate, $endDate)
    {
        $saldoAwalDebit = $this->selectSum('debit', 'total_debit')
            ->where('id_akun', $id_akun)
            ->where('DATE(tgl_jurnal) <', $startDate)
            ->get()->getRow()->total_debit ?? 0;

        $saldoAwalKredit = $this->selectSum('kredit', 'total_kredit')
            ->where('id_akun', $id_akun)
            ->where('DATE(tgl_jurnal) <', $startDate)
            ->get()->getRow()->total_kredit ?? 0;

        $saldoAwal = $saldoAwalDebit - $saldoAwalKredit;

        $transaksi = $this->select('jurnal.*, akun.nama_akun, akun.posisi_saldo_normal')
            ->join('akun', 'akun.id_akun = jurnal.id_akun')
            ->where('jurnal.id_akun', $id_akun)
            ->where('DATE(jurnal.tgl_jurnal) >=', $startDate)
            ->where('DATE(jurnal.tgl_jurnal) <=', $endDate)
            ->orderBy('jurnal.tgl_jurnal', 'ASC')
            ->findAll();

        return [
            'saldo_awal' => $saldoAwal,
            'transaksi'  => $transaksi,
            'info_akun'  => (new AkunModel())->find($id_akun)
        ];
    }


    public function getNeracaSaldo($endDate)
    {
        return $this->select('jurnal.id_akun, akun.kode_akun, akun.nama_akun, akun.posisi_saldo_normal, SUM(jurnal.debit) as total_debit, SUM(jurnal.kredit) as total_kredit')
            ->join('akun', 'akun.id_akun = jurnal.id_akun')
            ->where('DATE(jurnal.tgl_jurnal) <=', $endDate)
            ->groupBy('jurnal.id_akun, akun.kode_akun, akun.nama_akun, akun.posisi_saldo_normal')
            ->orderBy('akun.kode_akun', 'ASC')
            ->findAll();
    }
}
