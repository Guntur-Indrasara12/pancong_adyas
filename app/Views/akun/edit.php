<?php echo view('partials/header') ?>
<?php echo view('partials/sidebar') ?>

<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-note icon-gradient bg-warm-flame"></i>
                    </div>
                    <div><?= esc($title) ?>
                        <div class="page-title-subheading">Perbarui data akun yang sudah ada.</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">Form Edit Akun</h5>
                <form action="<?= site_url('akun/update/' . $akun['id_akun']) ?>" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" value="POST"> <!-- Meskipun route resource bisa pakai PUT, form HTML hanya support GET/POST -->

                    <div class="form-group">
                        <label for="kode_akun">Kode Akun</label>
                        <input type="text" class="form-control <?= ($validation->hasError('kode_akun')) ? 'is-invalid' : '' ?>" id="kode_akun" name="kode_akun" value="<?= old('kode_akun', esc($akun['kode_akun'])) ?>">
                        <?php if ($validation->hasError('kode_akun')) : ?>
                            <div class="invalid-feedback"><?= $validation->getError('kode_akun') ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="nama_akun">Nama Akun</label>
                        <input type="text" class="form-control <?= ($validation->hasError('nama_akun')) ? 'is-invalid' : '' ?>" id="nama_akun" name="nama_akun" value="<?= old('nama_akun', esc($akun['nama_akun'])) ?>">
                        <?php if ($validation->hasError('nama_akun')) : ?>
                            <div class="invalid-feedback"><?= $validation->getError('nama_akun') ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="posisi_saldo_normal">Posisi Saldo Normal</label>
                        <select class="form-control <?= ($validation->hasError('posisi_saldo_normal')) ? 'is-invalid' : '' ?>" id="posisi_saldo_normal" name="posisi_saldo_normal">
                            <option value="">-- Pilih Posisi --</option>
                            <option value="Debit" <?= (old('posisi_saldo_normal', $akun['posisi_saldo_normal']) == 'Debit') ? 'selected' : '' ?>>Debit</option>
                            <option value="Kredit" <?= (old('posisi_saldo_normal', $akun['posisi_saldo_normal']) == 'Kredit') ? 'selected' : '' ?>>Kredit</option>
                        </select>
                        <?php if ($validation->hasError('posisi_saldo_normal')) : ?>
                            <div class="invalid-feedback"><?= $validation->getError('posisi_saldo_normal') ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="<?= site_url('akun') ?>" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php echo view('partials/footer') ?>