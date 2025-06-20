<?php echo view('partials/header') ?>
<?php echo view('partials/sidebar') ?>

<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="fa-solid fa-cart-shopping"></i>
                    </div>
                    <div>Data Penjualan
                        <div class="page-title-subheading"> Tambah transaksi penjualan baru.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">Tambah Penjualan</h5>
                <br>
                <form class="needs-validation" action="<?= base_url('penjualan/process'); ?>" method="post" novalidate>
                    <?= csrf_field(); ?>
                    <div class="form-row">
                        <div class="col-md-6 mb-3">
                            <label for="id_produk">Pilih Produk (Produksi Hari Ini)</label>
                            <select class="form-control" name="id_produk" id="id_produk" required>
                                <option value="">-- Pilih Produk (Produksi Hari Ini) --</option>
                                <?php if (!empty($produk)): ?>
                                    <?php foreach ($produk as $p) : ?>
                                        <option value="<?= $p['id_produk'] ?>" <?= (old('id_produk') == $p['id_produk']) ? 'selected' : '' ?>>
                                            <?= $p['nama_produk'] ?> (Stok Tersedia: <?= $p['stok'] ?> - Harga Jual: Rp <?= number_format($p['harga'], 0, ',', '.') ?>)
                                        </option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="" disabled>Tidak ada produk yang diproduksi hari ini.</option>
                                <?php endif; ?>
                            </select>
                            <?php if (session()->getFlashdata('error') && strpos(session()->getFlashdata('error'), 'id_produk') !== false) : ?>
                                <div class="invalid-feedback d-block">
                                    Pilih produk yang valid.
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="id_cabang">Pilih Cabang</label>
                            <select class="form-control" name="id_cabang" id="id_cabang" required>
                                <option value="">-- Pilih Cabang --</option>
                                <?php if (!empty($cabang)): ?>
                                    <?php foreach ($cabang as $c) : ?>
                                        <option value="<?= $c['id_cabang'] ?>" <?= (old('id_cabang') == $c['id_cabang']) ? 'selected' : '' ?>>
                                            <?= $c['nama_cabang'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="" disabled>Tidak ada cabang tersedia.</option>
                                <?php endif; ?>
                            </select>
                            <?php if (session()->getFlashdata('error') && strpos(session()->getFlashdata('error'), 'id_cabang') !== false) : ?>
                                <div class="invalid-feedback d-block">
                                    Pilih cabang yang valid.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-6 mb-3">
                            <label for="jumlah_terjual">Jumlah Terjual</label>
                            <input type="number" class="form-control" name="jumlah_terjual" id="jumlah_terjual"
                                placeholder="Jumlah Terjual" value="<?= old('jumlah_terjual'); ?>" required min="1">
                            <?php if (session()->getFlashdata('error') && strpos(session()->getFlashdata('error'), 'jumlah_terjual') !== false) : ?>
                                <div class="invalid-feedback d-block">
                                    Jumlah terjual harus diisi dan lebih dari 0.
                                </div>
                            <?php elseif (session()->getFlashdata('error') && strpos(session()->getFlashdata('error'), 'Stok produk tidak mencukupi') !== false) : ?>
                                <div class="invalid-feedback d-block">
                                    <?= session()->getFlashdata('error'); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <button class="btn btn-primary" type="submit">Catat Penjualan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php echo view('partials/footer') ?>