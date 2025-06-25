<?php echo view('partials/header') ?>
<?php echo view('partials/sidebar') ?>

<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-notebook icon-gradient bg-mean-fruit"></i>
                    </div>
                    <div><?= esc($title) ?>
                        <div class="page-title-subheading">Menampilkan seluruh transaksi dalam periode waktu tertentu.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">Filter Laporan</h5>
                <form method="get" action="<?= site_url('jurnal') ?>" class="row g-3 mb-4 align-items-end">
                    <div class="col-md-4">
                        <label for="start_date" class="form-label">Tanggal Mulai</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" value="<?= esc($startDate) ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="end_date" class="form-label">Tanggal Akhir</label>
                        <input type="date" name="end_date" id="end_date" class="form-control" value="<?= esc($endDate) ?>">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                    <div class="col-md-2">
                        <a href="<?= site_url('jurnal') ?>" class="btn btn-secondary w-100">Reset</a>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Tanggal</th>
                                <th>Kode Akun</th>
                                <th>Nama Akun</th>
                                <th>Deskripsi</th>
                                <th class="text-end">Debit</th>
                                <th class="text-end">Kredit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $totalDebit = 0;
                            $totalKredit = 0;
                            ?>
                            <?php if (empty($jurnal)) : ?>
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data untuk periode ini.</td>
                                </tr>
                            <?php else : ?>
                                <?php foreach ($jurnal as $item) : ?>
                                    <?php
                                    $totalDebit += $item['debit'];
                                    $totalKredit += $item['kredit'];
                                    ?>
                                    <tr>
                                        <td><?= date('d M Y', strtotime($item['tgl_jurnal'])) ?></td>
                                        <td><?= esc($item['kode_akun']) ?></td>
                                        <td><?= esc($item['nama_akun']) ?></td>
                                        <td><?= esc($item['deskripsi']) ?></td>
                                        <td class="text-end"><?= ($item['debit'] > 0) ? 'Rp ' . number_format($item['debit'], 2, ',', '.') : '-' ?></td>
                                        <td class="text-end"><?= ($item['kredit'] > 0) ? 'Rp ' . number_format($item['kredit'], 2, ',', '.') : '-' ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                        <tfoot class="table-group-divider">
                            <tr class="fw-bold">
                                <td colspan="4" class="text-end">TOTAL</td>
                                <td class="text-end">Rp <?= number_format($totalDebit, 2, ',', '.') ?></td>
                                <td class="text-end">Rp <?= number_format($totalKredit, 2, ',', '.') ?></td>
                            </tr>
                            <?php if (abs($totalDebit - $totalKredit) > 0.01) : ?>
                                <tr class="table-danger">
                                    <td colspan="6" class="text-center fw-bold">TIDAK SEIMBANG (NOT BALANCED)</td>
                                </tr>
                            <?php else : ?>
                                <tr class="table-success">
                                    <td colspan="6" class="text-center fw-bold">SEIMBANG (BALANCED)</td>
                                </tr>
                            <?php endif; ?>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo view('partials/footer') ?>