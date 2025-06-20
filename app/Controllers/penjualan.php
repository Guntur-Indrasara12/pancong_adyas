<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PenjualanModel;
use App\Models\ProdukModel;
use App\Models\ProduksiModel;
use App\Models\CabangModel;

class Penjualan extends BaseController
{
    protected $penjualanModel;
    protected $produkModel;
    protected $produksiModel;
    protected $cabangModel;

    public function __construct()
    {
        helper('form');
        $this->penjualanModel = new PenjualanModel();
        $this->produkModel = new ProdukModel();
        $this->produksiModel = new ProduksiModel();
        $this->cabangModel = new CabangModel();
    }

    public function index()
    {
        $data['penjualan'] = $this->penjualanModel->getPenjualan();
        return view('penjualan/index', $data);
    }

    public function create()
    {
        $data = [
            'produk' => $this->produksiModel->getTodaysProducedProductsWithStock(),
            'cabang' => $this->cabangModel->getCabang(),
            'validation' => \Config\Services::validation()
        ];
        return view('penjualan/create', $data);
    }

    public function process()
    {
        $rules = [
            'id_produk'      => 'required|integer',
            'id_cabang'      => 'required|integer',
            'jumlah_terjual' => 'required|integer|greater_than[0]'
        ];

        if (!$this->validate($rules)) {
            session()->setFlashdata('error', $this->validator->listErrors());
            return redirect()->to('/penjualan/create')->withInput();
        }

        $id_produk = $this->request->getPost('id_produk');
        $id_cabang = $this->request->getPost('id_cabang');
        $jumlah_terjual = $this->request->getPost('jumlah_terjual');

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $produk = $this->produkModel->find($id_produk);

            if (!$produk) {
                throw new \Exception('Produk tidak ditemukan.');
            }

            if ($produk['stok'] < $jumlah_terjual) {
                throw new \Exception('Stok produk tidak mencukupi. Stok tersedia: ' . $produk['stok']);
            }

            $harga_jual_satuan = $produk['harga'];
            $total_harga = $jumlah_terjual * $harga_jual_satuan;

            $this->produkModel->decreaseStock($id_produk, $jumlah_terjual);

            $dataPenjualan = [
                'id_produk'         => $id_produk,
                'jumlah_terjual'    => $jumlah_terjual,
                'harga_jual_satuan' => $harga_jual_satuan,
                'total_harga'       => $total_harga,
                'id_cabang'         => $id_cabang,
                'tgl_penjualan'     => date('Y-m-d H:i:s')
            ];
            $this->penjualanModel->insert($dataPenjualan);

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Gagal melakukan transaksi database penjualan.');
            }

            session()->setFlashdata('success', 'Penjualan berhasil dicatat! Stok produk ' . $produk['nama_produk'] . ' berkurang.');
            return redirect()->to('/penjualan');
        } catch (\Exception $e) {
            $db->transRollback();
            session()->setFlashdata('error', $e->getMessage());
            return redirect()->to('/penjualan/create')->withInput();
        }
    }
}
