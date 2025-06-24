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
                <h5 class="card-title">Tambah Bahan Baku</h5>
                <br>
                <form class="needs-validation" action="<?= base_url('bahanbaku/process'); ?>" method="post" novalidate>
                    <?= csrf_field(); ?>
                    <div class="form-row">
                        <div class="col-md-6 mb-3">
                            <label for="nama_bahan">Nama Bahan</label>
                            <input type="text" class="form-control" name="nama_bahan" id="nama_bahan" placeholder="Nama Bahan" value="<?= old('nama_bahan'); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="harga_beli">Harga Beli</label>
                            <input type="number" class="form-control" name="harga_beli" id="harga_beli" placeholder="Harga Beli" value="<?= old('harga_beli'); ?>" required min="0">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-6 mb-3">
                            <label for="satuan">Satuan</label>
                            <input type="text" class="form-control" name="satuan" id="satuan" placeholder="Contoh: Kg, Liter, Buah" value="<?= old('satuan'); ?>" required maxlength="50">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="jenis">Jenis Bahan</label>
                            <select class="form-control" name="jenis" id="jenis" required>
                                <option value="">Pilih Jenis</option>
                                <option value="utama" <?= old('jenis') == 'utama' ? 'selected' : ''; ?>>Utama</option>
                                <option value="toping" <?= old('jenis') == 'toping' ? 'selected' : ''; ?>>Toping</option>
                                <option value="varian" <?= old('jenis') == 'varian' ? 'selected' : ''; ?>>Variant</option>
                            </select>
                        </div>
                    </div>

                    <?php if (session()->getFlashdata('error')) : ?>
                        <div class="alert alert-danger">
                            <?= session()->getFlashdata('error'); ?>
                        </div>
                    <?php endif; ?>

                    <button class="btn btn-primary" type="submit">Simpan</button>
                    <a href="<?= base_url('bahanbaku'); ?>" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php echo view('partials/footer') ?>