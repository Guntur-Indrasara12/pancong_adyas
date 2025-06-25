<?php echo view('partials/header') ?>
<?php echo view('partials/sidebar') ?>

<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-bookmarks icon-gradient bg-happy-itmeo"></i>
                    </div>
                    <div><?= esc($title) ?>
                        <div class="page-title-subheading">Menampilkan rincian transaksi per akun.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">Filter Laporan</h5>
                <form method="get" action="<?= site_url('/buku-besar') ?>" class="row g-3 mb-4 align-items-end">
                    <div class="col-md-4">
                        <label for="id_akun" class="form-label">Pilih Akun</label>
                        <select name="id_akun" id="id_akun" class="form-control" required>
                            <option value="">-- Pilih Akun --</option>
                            <?php foreach ($list_akun as $akun) : ?>
                                <option value="<?= $akun['id_akun'] ?>" <?= ($selected_akun == $akun['id_akun']) ? 'selected' : '' ?>>
                                    <?= esc($akun['kode_akun']) ?> - <?= esc($akun['nama_akun']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="start_date" class="form-label">Tanggal Mulai</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" value="<?= esc($startDate) ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="end_date" class="form-label">Tanggal Akhir</label>
                        <input type="date" name="end_date" id="end_date" class="form-control" value="<?= esc($endDate) ?>">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-success w-100">Tampilkan</button>
                    </div>
                </form>
            </div>
        </div>

        <?php if ($laporan) : ?>
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <i class="header-icon lnr-book icon-gradient bg-happy-itmeo"> </i>
                    Laporan untuk Akun: <strong><?= esc($laporan['info_akun']['nama_akun']) ?></strong>
                    <div class="btn-actions-pane-right">
                        <div class="text-muted">Periode: <?= date('d M Y', strtotime($startDate)) ?> s/d <?= date('d M Y', strtotime($endDate)) ?></div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Deskripsi</th>
                                    <th class="text-end">Debit</th>
                                    <th class="text-end">Kredit</th>
                                    <th class="text-end">Saldo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="4"><strong>Saldo Awal</strong></td>
                                    <td class="text-end"><strong>Rp <?= number_format($laporan['saldo_awal'], 2, ',', '.') ?></strong></td>
                                </tr>
                                <?php
                                $saldo = $laporan['saldo_awal'];
                                foreach ($laporan['transaksi'] as $trx) :
                                    if ($laporan['info_akun']['posisi_saldo_normal'] == 'Kredit') {
                                        $saldo = $saldo - $trx['debit'] + $trx['kredit'];
                                    } else {
                                        $saldo = $saldo + $trx['debit'] - $trx['kredit'];
                                    }
                                ?>
                                    <tr>
                                        <td><?= date('d M Y', strtotime($trx['tgl_jurnal'])) ?></td>
                                        <td><?= esc($trx['deskripsi']) ?></td>
                                        <td class="text-end"><?= ($trx['debit'] > 0) ? 'Rp ' . number_format($trx['debit'], 2, ',', '.') : '-' ?></td>
                                        <td class="text-end"><?= ($trx['kredit'] > 0) ? 'Rp ' . number_format($trx['kredit'], 2, ',', '.') : '-' ?></td>
                                        <td class="text-end">Rp <?= number_format($saldo, 2, ',', '.') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="table-group-divider">
                                <tr>
                                    <td colspan="4" class="text-end fw-bold">Saldo Akhir</td>
                                    <td class="text-end fw-bold">Rp <?= number_format($saldo, 2, ',', '.') ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        <?php elseif ($selected_akun) : ?>
            <div class="alert alert-warning">Tidak ada data transaksi untuk akun yang dipilih pada periode ini.</div>
        <?php else : ?>
            <p class="text-center mt-4 alert alert-info">Silakan pilih akun dan periode untuk menampilkan laporan buku besar.</p>
        <?php endif; ?>

    </div>
</div>

<?php echo view('partials/footer') ?>