<?php

namespace App\Models;

use CodeIgniter\Model;

class produkmodel extends Model
{
    protected $table            = 'produk';
    protected $primaryKey       = 'id_produk';
    protected $allowedFields    = ['nama_produk', 'harga', 'stok'];



    protected $useTimestamps = false;
    protected $useSoftDeletes = false;


    public function getProduk($id = false)
    {
        if ($id === false) {
            return $this->findAll();
        } else {
            return $this->getWhere(['id_produk' => $id]);
        }
    }

    public function insertProduk($data)
    {
        return $this->insert($data);
    }

    public function updateProduk($data, $id)
    {
        return $this->update($id, $data);
    }

    public function deleteProduk($id)
    {
        return $this->delete($id);
    }

    public function findOrCreateByName(string $namaProduk): int
    {
        $produk = $this->where('nama_produk', $namaProduk)->first();
        if ($produk) {
            return $produk['id_produk'];
        } else {
            return $this->insert(['nama_produk' => $namaProduk, 'harga' => 2500, 'stok' => 0]);
        }
    }

    public function increaseStock(int $idProduk, int $jumlah)
    {
        $this->where('id_produk', $idProduk)->set('stok', "stok + $jumlah", false)->update();
    }

    public function decreaseStock($id_produk, $quantity)
    {
        $produk = $this->find($id_produk);

        if ($produk) {
            $newStock = $produk['stok'] - $quantity;
            if ($newStock >= 0) {
                return $this->update($id_produk, ['stok' => $newStock]);
            } else {
                return false;
            }
        }
        return false;
    }
}
