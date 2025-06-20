<?php echo view('partials/header') ?>
<?php echo view('partials/sidebar') ?>

<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="fa-solid fa-flask-vial"></i>
                    </div>
                    <div>Data Bahan Baku
                        <div class="page-title-subheading"> Informasi lengkap mengenai bahan baku yang tersedia.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">Ubah Bahan Baku</h5>
                <br>
                <form class="needs-validation" action="<?= base_url('bahanbaku/edit/process'); ?>" method="post" novalidate>
                    <?= csrf_field(); ?>
                    <input type="hidden" class="form-control" name="id_bahan" id="id_bahan"
                        value="<?= set_value('id_bahan', $bahan_baku['id_bahan'] ?? '') ?>" required>

                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="nama_bahan">Nama Bahan</label>
                            <input type="text" class="form-control" name="nama_bahan" id="nama_bahan"
                                placeholder="Nama Bahan" value="<?= set_value('nama_bahan', $bahan_baku['nama_bahan'] ?? '') ?>" required>
                            <?php if (session()->getFlashdata('error') && strpos(session()->getFlashdata('error'), 'Nama bahan harus diisi.') !== false) : ?>
                                <div class="invalid-feedback d-block">
                                    Nama bahan harus diisi.
                                </div>
                            <?php elseif (session()->getFlashdata('error') && strpos(session()->getFlashdata('error'), 'Nama bahan sudah ada.') !== false) : ?>
                                <div class="invalid-feedback d-block">
                                    Nama bahan sudah ada.
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="harga_beli">Harga Beli</label>
                            <input type="number" class="form-control" name="harga_beli" id="harga_beli"
                                placeholder="Harga Beli" value="<?= set_value('harga_beli', $bahan_baku['harga_beli'] ?? '') ?>" required min="0">
                            <?php if (session()->getFlashdata('error') && strpos(session()->getFlashdata('error'), 'Harga beli harus diisi.') !== false) : ?>
                                <div class="invalid-feedback d-block">
                                    Harga beli harus diisi.
                                </div>
                            <?php elseif (session()->getFlashdata('error') && strpos(session()->getFlashdata('error'), 'Harga beli harus berupa angka bulat.') !== false) : ?>
                                <div class="invalid-feedback d-block">
                                    Harga beli harus berupa angka bulat.
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="satuan">Satuan</label>
                            <input type="text" class="form-control" name="satuan" id="satuan"
                                placeholder="Contoh: Kg, Liter, Buah" value="<?= set_value('satuan', $bahan_baku['satuan'] ?? '') ?>" required maxlength="50">
                            <?php if (session()->getFlashdata('error') && strpos(session()->getFlashdata('error'), 'Satuan harus diisi.') !== false) : ?>
                                <div class="invalid-feedback d-block">
                                    Satuan harus diisi.
                                </div>
                            <?php elseif (session()->getFlashdata('error') && strpos(session()->getFlashdata('error'), 'Satuan terlalu panjang') !== false) : ?>
                                <div class="invalid-feedback d-block">
                                    Satuan terlalu panjang (maksimal 50 karakter).
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <button class="btn btn-primary" type="submit">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php echo view('partials/footer') ?>