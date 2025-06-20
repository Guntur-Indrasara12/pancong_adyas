<?php

namespace App\Models;

use CodeIgniter\Model;

class bahanbakumodel extends Model
{
    protected $table            = 'bahan_baku';
    protected $primaryKey       = 'id_bahan';
    protected $allowedFields    = ['nama_bahan', 'harga_beli', 'satuan'];

    protected $useTimestamps = false;
    protected $useSoftDeletes = false;

    public function getBahanBaku($id = false)
    {
        if ($id === false) {
            return $this->findAll();
        } else {
            return $this->getWhere(['id_bahan' => $id]);
        }
    }

    public function insertBahanBaku($data)
    {
        return $this->insert($data);
    }

    public function updateBahanBaku($data, $id)
    {
        return $this->update($id, $data);
    }

    public function deleteBahanBaku($id)
    {
        return $this->delete($id);
    }

    public function getBahanPrices(): array
    {
        $allBahan = $this->findAll();
        $prices = [];
        foreach ($allBahan as $bahan) {
            $prices[$bahan['nama_bahan']] = $bahan['harga_beli'];
        }
        return $prices;
    }
}
