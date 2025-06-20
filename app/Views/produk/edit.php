<?php echo view('partials/header') ?>
<?php echo view('partials/sidebar') ?>

<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="fa-solid fa-boxes-stacked"></i>
                    </div>
                    <div>Data Produk
                        <div class="page-title-subheading"> Informasi lengkap mengenai produk yang tersedia.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">Ubah Produk</h5>
                <br>
                <form class="needs-validation" action="<?= base_url('produk/edit/process'); ?>" method="post" novalidate>
                    <?= csrf_field(); ?>
                    <input type="hidden" class="form-control" name="id_produk" id="id_produk"
                        value="<?= set_value('id_produk', $produk['id_produk'] ?? '') ?>" required>

                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="nama_produk">Nama Produk</label>
                            <input type="text" class="form-control" name="nama_produk" id="nama_produk"
                                placeholder="Nama Produk" value="<?= set_value('nama_produk', $produk['nama_produk'] ?? '') ?>" required>
                            <?php if (session()->getFlashdata('error') && strpos(session()->getFlashdata('error'), 'Nama produk harus diisi.') !== false) : ?>
                                <div class="invalid-feedback d-block">
                                    Nama produk harus diisi.
                                </div>
                            <?php elseif (session()->getFlashdata('error') && strpos(session()->getFlashdata('error'), 'Nama produk sudah ada.') !== false) : ?>
                                <div class="invalid-feedback d-block">
                                    Nama produk sudah ada.
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="harga">Harga</label>
                            <input type="number" class="form-control" name="harga" id="harga"
                                placeholder="Harga" value="<?= set_value('harga', $produk['harga'] ?? '') ?>" required min="0">
                            <?php if (session()->getFlashdata('error') && strpos(session()->getFlashdata('error'), 'Harga harus diisi.') !== false) : ?>
                                <div class="invalid-feedback d-block">
                                    Harga harus diisi.
                                </div>
                            <?php elseif (session()->getFlashdata('error') && strpos(session()->getFlashdata('error'), 'Harga harus berupa angka bulat.') !== false) : ?>
                                <div class="invalid-feedback d-block">
                                    Harga harus berupa angka bulat.
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="stok">Stok</label>
                            <input type="number" class="form-control" name="stok" id="stok"
                                placeholder="Stok" value="<?= set_value('stok', $produk['stok'] ?? '') ?>" required min="0">
                            <?php if (session()->getFlashdata('error') && strpos(session()->getFlashdata('error'), 'Stok harus diisi.') !== false) : ?>
                                <div class="invalid-feedback d-block">
                                    Stok harus diisi.
                                </div>
                            <?php elseif (session()->getFlashdata('error') && strpos(session()->getFlashdata('error'), 'Stok harus berupa angka bulat.') !== false) : ?>
                                <div class="invalid-feedback d-block">
                                    Stok harus berupa angka bulat.
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