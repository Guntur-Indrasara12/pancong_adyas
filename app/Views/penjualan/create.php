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

                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="alert alert-danger">
                        <?= session()->getFlashdata('error'); ?>
                    </div>
                <?php endif; ?>

                <form class="needs-validation" action="<?= base_url('penjualan/process'); ?>" method="post" novalidate>
                    <?= csrf_field(); ?>

                    <h6>Produk Utama</h6>
                    <hr>
                    <div class="form-row">
                        <div class="col-md-6 mb-3">
                            <label for="id_produk">Pilih Produk (Produksi Hari Ini)</label>
                            <select class="form-control" name="id_produk" id="id_produk" required>
                                <option value="">-- Pilih Produk --</option>
                                <?php if (!empty($produk)): ?>
                                    <?php foreach ($produk as $p) : ?>
                                        <?php if (strpos(strtolower($p['nama_produk']), 'topping') === false) : ?>
                                            <option value="<?= $p['id_produk'] ?>" <?= (old('id_produk') == $p['id_produk']) ? 'selected' : '' ?>>
                                                <?= $p['nama_produk'] ?> (Stok: <?= $p['stok'] ?> - Harga: Rp <?= number_format($p['harga'], 0, ',', '.') ?>)
                                            </option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="" disabled>Tidak ada produk utama yang diproduksi hari ini.</option>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="jumlah_terjual">Jumlah Terjual</label>
                            <input type="number" class="form-control" name="jumlah_terjual" id="jumlah_terjual" placeholder="Jumlah Terjual" value="<?= old('jumlah_terjual'); ?>" required min="1">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <h6>Pilih Topping (Opsional)</h6>
                        <button type="button" id="add-topping-btn" class="btn btn-sm btn-info"><i class="fa fa-plus"></i> Tambah Topping</button>
                    </div>
                    <hr>
                    <div id="topping-container">
                    </div>

                    <h6 class="mt-4">Informasi Cabang</h6>
                    <hr>
                    <div class="form-row">
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
                        </div>
                    </div>

                    <button class="btn btn-primary mt-3" type="submit">Catat Penjualan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="topping-template" style="display: none;">
    <div class="form-row align-items-end topping-row mb-3">
        <div class="col-md-10">
            <label>Nama Topping</label>
            <select class="form-control" name="toppings[id_produk][]">
                <option value="">Pilih Topping...</option>
                <?php if (!empty($toppings)): ?>
                    <?php foreach ($toppings as $topping) : ?>
                        <option value="<?= $topping['id_produk']; ?>">
                            <?= $topping['nama_produk']; ?> (Stok: <?= $topping['stok']; ?> - Harga: Rp <?= number_format($topping['harga'], 0, ',', '.') ?>)
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            <input type="hidden" name="toppings[jumlah][]" value="1">
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-danger btn-block remove-topping-btn">Hapus</button>
        </div>
    </div>
</div>
<?php echo view('partials/footer') ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const addToppingBtn = document.getElementById('add-topping-btn');
        const toppingContainer = document.getElementById('topping-container');
        const toppingTemplate = document.getElementById('topping-template');

        addToppingBtn.addEventListener('click', function() {
            const newToppingRow = toppingTemplate.firstElementChild.cloneNode(true);
            toppingContainer.appendChild(newToppingRow);
        });

        toppingContainer.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('remove-topping-btn')) {
                e.target.closest('.topping-row').remove();
            }
        });
    });
</script>