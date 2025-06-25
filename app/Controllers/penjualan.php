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
            'toppings' => $this->produkModel->getAvailableToppings(),
            'cabang' => $this->cabangModel->getCabang(),
            'validation' => \Config\Services::validation()
        ];
        return view('penjualan/create', $data);
    }

    public function process()
    {
        $rules = [
            'id_produk'         => 'required|integer',
            'id_cabang'         => 'required|integer',
            'jumlah_terjual'    => 'required|integer|greater_than[0]',
            'toppings.id_produk.*' => 'permit_empty|integer',
            'toppings.jumlah.*'    => 'permit_empty|integer|greater_than_equal_to[0]'
        ];

        if (!$this->validate($rules)) {
            session()->setFlashdata('error', $this->validator->listErrors());
            return redirect()->to('/penjualan/create')->withInput();
        }

        $id_cabang = $this->request->getPost('id_cabang');

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $kode_transaksi = 'INV-' . strtoupper(uniqid());
            $totalHargaTransaksi = 0;
            $namaProdukTerjual = [];

            $id_produk_utama = $this->request->getPost('id_produk');
            $jumlah_utama = $this->request->getPost('jumlah_terjual');

            $this->catatItemPenjualan($id_produk_utama, $jumlah_utama, $id_cabang, $kode_transaksi, $totalHargaTransaksi, $namaProdukTerjual, false);

            $postToppings = $this->request->getPost('toppings');
            if (!empty($postToppings['id_produk'])) {
                foreach ($postToppings['id_produk'] as $index => $id_topping) {
                    $jumlah_topping = $postToppings['jumlah'][$index] ?? 0;
                    if (!empty($id_topping) && $jumlah_topping > 0) {
                        $this->catatItemPenjualan($id_topping, $jumlah_topping, $id_cabang, $kode_transaksi, $totalHargaTransaksi, $namaProdukTerjual, true);
                    }
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Gagal melakukan transaksi database penjualan.');
            }

            $listProdukStr = implode(', ', $namaProdukTerjual);
            session()->setFlashdata('success', 'Penjualan berhasil dicatat! Produk terjual: ' . $listProdukStr . '. Total: Rp ' . number_format($totalHargaTransaksi, 0, ',', '.'));
            return redirect()->to('/penjualan');
        } catch (\Exception $e) {
            $db->transRollback();
            session()->setFlashdata('error', $e->getMessage());
            return redirect()->to('/penjualan/create')->withInput();
        }
    }

    private function catatItemPenjualan(int $id_produk, int $jumlah_terjual, int $id_cabang, string $kode_transaksi, float &$totalHargaTransaksi, array &$namaProdukTerjual, bool $isTopping = false)
    {
        $produk = $this->produkModel->find($id_produk);

        if (!$produk) {
            throw new \Exception("Produk dengan ID {$id_produk} tidak ditemukan.");
        }

        $stokToDecrease = $isTopping ? ($jumlah_terjual * 50) : $jumlah_terjual;

        if ($produk['stok'] < $stokToDecrease) {
            throw new \Exception("Stok produk '{$produk['nama_produk']}' tidak mencukupi. Stok tersedia: {$produk['stok']}, dibutuhkan: {$stokToDecrease}");
        }

        $harga_jual_satuan = $produk['harga'];
        $total_harga_item = $jumlah_terjual * $harga_jual_satuan;

        $this->produkModel->decreaseStock($id_produk, $stokToDecrease);

        $dataPenjualan = [
            'kode_transaksi'    => $kode_transaksi,
            'id_produk'         => $id_produk,
            'jumlah_terjual'    => $jumlah_terjual,
            'harga_jual_satuan' => $harga_jual_satuan,
            'total_harga'       => $total_harga_item,
            'id_cabang'         => $id_cabang,
            'tgl_penjualan'     => date('Y-m-d H:i:s')
        ];
        $this->penjualanModel->insert($dataPenjualan);

        $totalHargaTransaksi += $total_harga_item;
        $namaProdukTerjual[] = "{$produk['nama_produk']} (x{$jumlah_terjual})";
    }
}
