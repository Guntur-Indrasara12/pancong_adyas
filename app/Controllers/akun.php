<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AkunModel;

class akun extends BaseController
{
    protected $akunModel;

    public function __construct()
    {
        $this->akunModel = new AkunModel();
    }


    public function index()
    {
        $data = [
            'title' => 'Daftar Akun',
            'akun'  => $this->akunModel->orderBy('kode_akun', 'ASC')->findAll(),
        ];
        return view('akun/index', $data);
    }

    public function new()
    {
        $data = [
            'title'      => 'Tambah Akun Baru',
            'validation' => \Config\Services::validation()
        ];
        return view('akun/create', $data);
    }


    public function create()
    {



        $this->akunModel->save([
            'kode_akun'           => $this->request->getPost('kode_akun'),
            'nama_akun'           => $this->request->getPost('nama_akun'),
            'posisi_saldo_normal' => $this->request->getPost('posisi_saldo_normal'),
        ]);

        session()->setFlashdata('success', 'Akun baru berhasil ditambahkan.');
        return redirect()->to('/akun');
    }

    /**
     * Menampilkan form untuk mengedit akun (Update Form)
     */
    public function edit($id = null)
    {
        $akun = $this->akunModel->find($id);
        if (!$akun) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Akun tidak ditemukan.');
        }

        $data = [
            'title'      => 'Edit Akun',
            'akun'       => $akun,
            'validation' => \Config\Services::validation()
        ];
        return view('akun/edit', $data);
    }

    /**
     * Memproses data dari form edit akun (Process Update)
     */
    public function update($id = null)
    {


        // Simpan perubahan
        $this->akunModel->update($id, [
            'kode_akun'           => $this->request->getPost('kode_akun'),
            'nama_akun'           => $this->request->getPost('nama_akun'),
            'posisi_saldo_normal' => $this->request->getPost('posisi_saldo_normal'),
        ]);

        session()->setFlashdata('success', 'Data akun berhasil diperbarui.');
        return redirect()->to('/akun');
    }

    /**
     * Menghapus akun (Delete)
     */
    public function delete($id = null)
    {
        $akun = $this->akunModel->find($id);
        if (!$akun) {
            session()->setFlashdata('error', 'Akun tidak ditemukan.');
            return redirect()->to('/akun');
        }

        $this->akunModel->delete($id);
        session()->setFlashdata('success', 'Akun berhasil dihapus.');
        return redirect()->to('/akun');
    }
}
