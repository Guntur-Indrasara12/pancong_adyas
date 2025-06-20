<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\bahanbakumodel;
use App\Models\produksimodel;
use App\Models\cabangmodel;
use App\Models\produkmodel;

class produksi extends BaseController
{
    protected $produksiModel;
    protected $cabangModel;
    protected $bahanBakuModel;
    protected $produkModel;


    public function __construct()
    {
        $this->produksiModel = new produksimodel();
        $this->cabangModel = new cabangmodel();
        $this->bahanBakuModel = new bahanbakumodel();
        $this->produkModel = new produkmodel();
    }


    public function index()
    {
        $data = [
            'riwayat' => $this->produksiModel->getTodaysProduction(),
            'tanggal_hari_ini' => date('d F Y')
        ];
        return view('produksi/index', $data);
    }

    public function create()
    {
        $allBahan = $this->bahanBakuModel->findAll();
        $bahanUtama = ['Tepung Terigu', 'Gula', 'Telur', 'Margarin'];
        $pilihanVarian = array_filter($allBahan, function ($bahan) use ($bahanUtama) {
            return !in_array($bahan['nama_bahan'], $bahanUtama);
        });

        $data = [
            'cabang' => $this->cabangModel->getCabang(),
            'pilihan_varian' => $pilihanVarian,
            'validation' => \Config\Services::validation()
        ];
        return view('produksi/create', $data);
    }


    public function process()
    {
        $rules = [
            'tepung'    => 'required|numeric',
            'gula'      => 'required|numeric',
            'telur'     => 'required|numeric',
            'margarin'  => 'required|numeric',
            'id_cabang' => 'required|integer',
            'variants.*.nama' => 'permit_empty|alpha_numeric_space',
            'variants.*.jumlah' => 'permit_empty|numeric'
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/produksi/create')->withInput();
        }

        $postData = $this->request->getPost();

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $resepDasar = ['Tepung Terigu' => 1];
            $hasilPerResep = 25;
            $varianPerGram = 25;

            $daftarHargaBahan = $this->bahanBakuModel->getBahanPrices();

            $bahanUtamaDigunakan = [
                'Tepung Terigu' => $postData['tepung'],
                'Gula' => $postData['gula'],
                'Telur' => $postData['telur'],
                'Margarin' => $postData['margarin'],
            ];
            $totalModalProduksi = 0;
            foreach ($bahanUtamaDigunakan as $nama => $jumlah) {
                if (!isset($daftarHargaBahan[$nama])) throw new \Exception("Master bahan '$nama' tidak ditemukan.");

                $totalModalProduksi += $daftarHargaBahan[$nama] * $jumlah;
            }

            $variants = $postData['variants'] ?? [];
            $listProdukVarian = [];
            $totalJumlahVarianPcs = 0;

            if (!empty($variants['nama'])) {
                foreach ($variants['nama'] as $index => $namaVarian) {
                    $jumlahVarian = $variants['jumlah'][$index] ?? 0;
                    if (!empty($namaVarian) && $jumlahVarian > 0) {
                        if (!isset($daftarHargaBahan[$namaVarian])) throw new \Exception("Master bahan varian '$namaVarian' tidak ditemukan.");

                        $totalModalProduksi += ($daftarHargaBahan[$namaVarian] / 1000) * $jumlahVarian;

                        $jumlahPcs = floor($jumlahVarian / $varianPerGram);
                        $totalJumlahVarianPcs += $jumlahPcs;

                        $listProdukVarian[] = ['nama' => 'Pancung ' . ucfirst(strtolower($namaVarian)), 'jumlah' => $jumlahPcs];
                    }
                }
            }

            $faktorPengali = ($postData['tepung']) / $resepDasar['Tepung Terigu'];
            $totalOutput = $faktorPengali * $hasilPerResep;

            if ($totalJumlahVarianPcs > $totalOutput) {
                throw new \Exception("Total hasil varian (" . $totalJumlahVarianPcs . " pcs) tidak boleh melebihi total output (" . $totalOutput . " pcs).");
            }

            $produkOriginal = ['nama' => 'Pancung original', 'jumlah' => $totalOutput - $totalJumlahVarianPcs];

            if ($produkOriginal['jumlah'] > 0) {
                $idOriginal = $this->produkModel->findOrCreateByName($produkOriginal['nama']);
                $this->produkModel->increaseStock($idOriginal, $produkOriginal['jumlah']);
                $this->produksiModel->insert([
                    'id_produk'     => $idOriginal,
                    'jumlah_hasil'  => $produkOriginal['jumlah'],
                    'total_modal'   => $totalModalProduksi,
                    'id_cabang' => $postData['id_cabang'],
                    'tgl_produksi'  => date('Y-m-d H:i:s')
                ]);
            }

            foreach ($listProdukVarian as $varian) {
                if ($varian['jumlah'] > 0) {
                    $idVarian = $this->produkModel->findOrCreateByName($varian['nama']);
                    $this->produkModel->increaseStock($idVarian, $varian['jumlah']);
                    $this->produksiModel->insert([
                        'id_produk'     => $idVarian,
                        'jumlah_hasil'  => $varian['jumlah'],
                        'total_modal'   => 0,
                        'id_cabang' => $postData['id_cabang'],
                        'tgl_produksi'  => date('Y-m-d H:i:s')
                    ]);
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Gagal melakukan transaksi database.');
            }

            session()->setFlashdata('success', "Produksi berhasil dengan total modal Rp " . number_format($totalModalProduksi, 0, ',', '.'));
            return redirect()->to('/produksi');
        } catch (\Exception $e) {
            session()->setFlashdata('error', $e->getMessage());
            return redirect()->to('/produksi/create')->withInput();
        }
    }
}
