<?php

namespace App\Controllers;

use App\Models\BahanBakuModel;

class BahanBaku extends BaseController
{
    protected $helpers = [];
    protected $bahanBakuModel;

    public function __construct()
    {
        helper('form');
        $this->bahanBakuModel = new BahanBakuModel();
    }

    public function index()
    {
        $data['bahan_baku'] = $this->bahanBakuModel->getBahanBaku();
        return view('bahanbaku/index', $data);
    }

    public function create()
    {
        return view('bahanbaku/create');
    }

    public function process()
    {
        $rules = [
            'nama_bahan' => [
                'rules' => 'required|is_unique[bahan_baku.nama_bahan]',
                'errors' => [
                    'required' => 'Nama bahan harus diisi.',
                    'is_unique' => 'Nama bahan sudah ada.'
                ]
            ],
            'harga_beli' => [
                'rules' => 'required|integer',
                'errors' => [
                    'required' => 'Harga beli harus diisi.',
                    'integer' => 'Harga beli harus berupa angka bulat.'
                ]
            ],
            'satuan' => [
                'rules' => 'required|max_length[50]',
                'errors' => [
                    'required' => 'Satuan harus diisi.',
                    'max_length' => 'Satuan terlalu panjang (maksimal 50 karakter).'
                ]
            ],
            'jenis' => [
                'rules' => 'required|in_list[utama,toping,varian]',
                'errors' => [
                    'required' => 'Jenis bahan harus dipilih.',
                    'in_list' => 'Jenis bahan tidak valid.'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            session()->setFlashdata('error', $this->validator->listErrors());
            return redirect()->back()->withInput();
        }

        $data = [
            'nama_bahan' => $this->request->getPost('nama_bahan'),
            'harga_beli' => $this->request->getPost('harga_beli'),
            'satuan' => $this->request->getPost('satuan'),
            'jenis' => $this->request->getPost('jenis'),
        ];
        $simpan = $this->bahanBakuModel->insertBahanBaku($data);
        if ($simpan) {
            session()->setFlashdata('success', 'Berhasil Menambahkan Data Bahan Baku');
            return redirect()->to(base_url('/bahanbaku'));
        }
    }

    public function edit($id)
    {
        $data['bahan_baku'] = $this->bahanBakuModel->getBahanBaku($id)->getRowArray();
        if (empty($data['bahan_baku'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Bahan baku tidak ditemukan: ' . $id);
        }
        return view('bahanbaku/edit', $data);
    }

    public function edit_process()
    {
        $id = $this->request->getVar('id_bahan');

        $rules = [
            'nama_bahan' => [
                'rules' => 'required|is_unique[bahan_baku.nama_bahan,id_bahan,' . $id . ']',
                'errors' => [
                    'required' => 'Nama bahan harus diisi.',
                    'is_unique' => 'Nama bahan sudah ada.'
                ]
            ],
            'harga_beli' => [
                'rules' => 'required|integer',
                'errors' => [
                    'required' => 'Harga beli harus diisi.',
                    'integer' => 'Harga beli harus berupa angka bulat.'
                ]
            ],
            'satuan' => [
                'rules' => 'required|max_length[50]',
                'errors' => [
                    'required' => 'Satuan harus diisi.',
                    'max_length' => 'Satuan terlalu panjang (maksimal 50 karakter).'
                ]
            ],
            'jenis' => [
                'rules' => 'required|in_list[utama,toping,varian]',
                'errors' => [
                    'required' => 'Jenis bahan harus dipilih.',
                    'in_list' => 'Jenis bahan tidak valid.'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            session()->setFlashdata('error', $this->validator->listErrors());
            return redirect()->back()->withInput();
        }

        $data = [
            'nama_bahan' => $this->request->getPost('nama_bahan'),
            'harga_beli' => $this->request->getPost('harga_beli'),
            'satuan' => $this->request->getPost('satuan'),
            'jenis' => $this->request->getPost('jenis'),
        ];
        $simpan = $this->bahanBakuModel->updateBahanBaku($data, $id);
        if ($simpan) {
            session()->setFlashdata('warning', 'Berhasil Edit Data Bahan Baku');
            return redirect()->to(base_url('/bahanbaku'));
        } else {
            session()->setFlashdata('error', 'Gagal Edit Data Bahan Baku. Tidak ada perubahan atau terjadi kesalahan.');
            return redirect()->back()->withInput();
        }
    }

    public function delete($id)
    {
        $hapus = $this->bahanBakuModel->deleteBahanBaku($id);
        if ($hapus) {
            session()->setFlashdata('error', 'Berhasil Hapus Data Bahan Baku');
            return redirect()->to(base_url('/bahanbaku'));
        } else {
            session()->setFlashdata('error', 'Gagal Hapus Data Bahan Baku.');
            return redirect()->back();
        }
    }
}
