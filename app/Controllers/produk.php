<?php

namespace App\Controllers;

use App\Models\ProdukModel;

class Produk extends BaseController
{
    protected $helpers = [];
    protected $produkModel;

    public function __construct()
    {
        helper('form');
        $this->produkModel = new ProdukModel();
    }

    public function index()
    {
        $data['produk'] = $this->produkModel->getProduk();
        return view('produk/index', $data);
    }

    public function create()
    {
        return view('produk/create');
    }

    public function process()
    {
        $rules = [
            'nama_produk' => [
                'rules' => 'required|is_unique[produk.nama_produk]',
                'errors' => [
                    'required' => 'Nama produk harus diisi.',
                    'is_unique' => 'Nama produk sudah ada.'
                ]
            ],
            'harga' => [
                'rules' => 'required|integer',
                'errors' => [
                    'required' => 'Harga harus diisi.',
                    'integer' => 'Harga harus berupa angka bulat.'
                ]
            ],
        ];

        if (!$this->validate($rules)) {
            session()->setFlashdata('error', $this->validator->listErrors());
            return redirect()->back()->withInput();
        }

        $data = array(
            'nama_produk' => $this->request->getPost('nama_produk'),
            'harga' => $this->request->getPost('harga'),
            'stok' => 0,
        );
        $simpan = $this->produkModel->insertProduk($data);
        if ($simpan) {
            session()->setFlashdata('success', 'Berhasil Menambahkan Data Produk');
            return redirect()->to(base_url('/produk'));
        }
    }

    public function edit($id)
    {
        $data['produk'] = $this->produkModel->getProduk($id)->getRowArray();
        if (empty($data['produk'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Produk tidak ditemukan: ' . $id);
        }
        return view('produk/edit', $data);
    }

    public function edit_process()
    {
        $id = $this->request->getVar('id_produk');

        $rules = [
            'nama_produk' => [
                'rules' => 'required|is_unique[produk.nama_produk,id_produk,' . $id . ']',
                'errors' => [
                    'required' => 'Nama produk harus diisi.',
                    'is_unique' => 'Nama produk sudah ada.'
                ]
            ],
            'harga' => [
                'rules' => 'required|integer',
                'errors' => [
                    'required' => 'Harga harus diisi.',
                    'integer' => 'Harga harus berupa angka bulat.'
                ]
            ],
            'stok' => [
                'rules' => 'required|integer',
                'errors' => [
                    'required' => 'Stok harus diisi.',
                    'integer' => 'Stok harus berupa angka bulat.'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            session()->setFlashdata('error', $this->validator->listErrors());
            return redirect()->back()->withInput();
        }

        $data = array(
            'nama_produk' => $this->request->getPost('nama_produk'),
            'harga' => $this->request->getPost('harga'),
            'stok' => $this->request->getPost('stok'),
        );
        $simpan = $this->produkModel->updateProduk($data, $id);
        if ($simpan) {
            session()->setFlashdata('warning', 'Berhasil Edit Data Produk');
            return redirect()->to(base_url('/produk'));
        } else {
            session()->setFlashdata('error', 'Gagal Edit Data Produk. Tidak ada perubahan atau terjadi kesalahan.');
            return redirect()->back()->withInput();
        }
    }

    public function delete($id)
    {
        $hapus = $this->produkModel->deleteProduk($id);
        if ($hapus) {
            session()->setFlashdata('error', 'Berhasil Hapus Data Produk');
            return redirect()->to(base_url('/produk'));
        } else {
            session()->setFlashdata('error', 'Gagal Hapus Data Produk.');
            return redirect()->back();
        }
    }
}
