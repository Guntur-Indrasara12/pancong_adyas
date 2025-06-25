<?php echo view('partials/header') ?>
<?php echo view('partials/sidebar') ?>

<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-server icon-gradient bg-plum-plate"></i>
                    </div>
                    <div>Daftar Akun (Chart of Accounts)
                        <div class="page-title-subheading">Kelola semua akun yang digunakan dalam sistem akuntansi.</div>
                    </div>
                </div>
                <div class="page-title-actions">
                    <a href="<?= site_url('akun/new') ?>" class="btn-shadow btn btn-primary">
                        <span class="btn-icon-wrapper pr-2 opacity-7">
                            <i class="fa fa-plus fa-w-20"></i>
                        </span>
                        Tambah Akun
                    </a>
                </div>
            </div>
        </div>

        <div class="main-card mb-3 card">
            <div class="card-body">
                <!-- Tampilkan Flashdata -->
                <?php if (session()->getFlashdata('success')) : ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('success'); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('error'); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                <?php endif; ?>

                <table class="table table-hover table-bordered" id="table-akun">
                    <thead>
                        <tr>
                            <th>Kode Akun</th>
                            <th>Nama Akun</th>
                            <th>Posisi Saldo Normal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($akun)) : ?>
                            <?php foreach ($akun as $row) : ?>
                                <tr>
                                    <td><?= esc($row['kode_akun']) ?></td>
                                    <td><?= esc($row['nama_akun']) ?></td>
                                    <td><?= esc($row['posisi_saldo_normal']) ?></td>
                                    <td>
                                        <a href="<?= site_url('akun/edit/' . $row['id_akun']) ?>" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="<?= site_url('akun/delete/' . $row['id_akun']) ?>" method="post" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini?');">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="4" class="text-center">Belum ada data akun.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php echo view('partials/footer') ?>