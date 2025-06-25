<?php echo view('partials/header') ?>
<?php echo view('partials/sidebar') ?>

<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-graph1 icon-gradient bg-ripe-malin"></i>
                    </div>
                    <div><?= esc($title) ?>
                        <div class="page-title-subheading">Menampilkan saldo akhir setiap akun untuk memeriksa keseimbangan.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">Filter Laporan</h5>
                <!-- Form Action diperbaiki agar sesuai dengan routes -->
                <form method="get" action="<?= site_url('neraca-saldo') ?>" class="row g-3 mb-4 align-items-end">
                    <div class="col-md-5">
                        <label for="end_date" class="form-label">Per Tanggal</label>
                        <input type="date" name="end_date" id="end_date" class="form-control" value="<?= esc($endDate) ?>">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-info w-100">Tampilkan</button>
                    </div>
                    <div class="col-md-3">
                        <a href="<?= site_url('neraca-saldo') ?>" class="btn btn-secondary w-100">Reset</a>
                    </div>
                </form>

                <h5 class="text-center">Per Tanggal: <?= date('d F Y', strtotime($endDate)) ?></h5>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Kode Akun</th>
                                <th>Nama Akun</th>
                                <th class="text-end">Debit (Rp)</th>
                                <th class="text-end">Kredit (Rp)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $totalDebit = 0;
                            $totalKredit = 0;
                            ?>
                            <?php if (empty($neraca)) : ?>
                                <tr>
                                    <td colspan="4" class="text-center">Tidak ada data transaksi hingga tanggal ini.</td>
                                </tr>
                            <?php else : ?>
                                <?php foreach ($neraca as $item) : ?>
                                    <?php
                                    $totalDebit += $item['debit'];
                                    $totalKredit += $item['kredit'];
                                    ?>
                                    <tr>
                                        <td><?= esc($item['kode_akun']) ?></td>
                                        <td><?= esc($item['nama_akun']) ?></td>
                                        <td class="text-end"><?= number_format($item['debit'], 2, ',', '.') ?></td>
                                        <td class="text-end"><?= number_format($item['kredit'], 2, ',', '.') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                        <tfoot class="table-group-divider">
                            <tr class="fw-bold">
                                <td colspan="2" class="text-end">TOTAL</td>
                                <td class="text-end">Rp <?= number_format($totalDebit, 2, ',', '.') ?></td>
                                <td class="text-end">Rp <?= number_format($totalKredit, 2, ',', '.') ?></td>
                            </tr>
                            <?php if (abs($totalDebit - $totalKredit) > 0.01) : ?>
                                <tr class="table-danger">
                                    <td colspan="4" class="text-center fw-bold">TIDAK SEIMBANG (NOT BALANCED)</td>
                                </tr>
                            <?php else : ?>
                                <tr class="table-success">
                                    <td colspan="4" class="text-center fw-bold">SEIMBANG (BALANCED)</td>
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