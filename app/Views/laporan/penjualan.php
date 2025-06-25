<?php echo view('partials/header') ?>
<?php echo view('partials/sidebar') ?>

<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="fa-solid fa-file-invoice-dollar"></i>
                    </div>
                    <div>Laporan Penjualan & Produksi
                        <div class="page-title-subheading">Ringkasan data untuk periode yang dipilih.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">Filter Laporan</h5>
                <form action="<?= base_url('/penjualan/report') ?>" method="get" class="form-inline">
                    <div class="form-group mb-2">
                        <label for="start_date" class="mr-2">Dari Tanggal</label>
                        <input type="date" name="start_date" id="start_date" class="form-control mr-3" value="<?= esc($start_date) ?>">
                    </div>
                    <div class="form-group mb-2">
                        <label for="end_date" class="mr-2">Sampai Tanggal</label>
                        <input type="date" name="end_date" id="end_date" class="form-control mr-3" value="<?= esc($end_date) ?>">
                    </div>
                    <button type="submit" class="btn btn-primary mb-2 mr-2"><i class="fa fa-filter mr-1"></i> Terapkan Filter</button>
                    <a href="<?= base_url('/penjualan/report') ?>" class="btn btn-secondary mb-2 mr-2">
                        <i class="fa-solid fa-rotate-left mr-1"></i> Reset
                    </a>
                    <a href="<?= base_url('/laporan/penjualan/download_pdf') ?>?start_date=<?= esc($start_date) ?>&end_date=<?= esc($end_date) ?>" class="btn btn-success mb-2" target="_blank">
                        <i class="fa-solid fa-file-pdf mr-1"></i> Unduh PDF
                    </a>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="main-card mb-3 card">
                    <div class="card-header">
                        <i class="fa-solid fa-rupiah-sign mr-2"></i>
                        <strong>RINGKASAN PERIODE: <?= date('d M Y', strtotime($start_date)); ?> s/d <?= date('d M Y', strtotime($end_date)); ?></strong>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="widget-content">
                                    <div class="widget-content-wrapper">
                                        <div class="widget-content-left">
                                            <div class="widget-heading">Total Pendapatan</div>
                                            <div class="widget-subheading">Dari Penjualan</div>
                                        </div>
                                        <div class="widget-content-right">
                                            <div class="widget-numbers text-success">Rp <?= number_format($total_pendapatan, 0, ',', '.'); ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="widget-content">
                                    <div class="widget-content-wrapper">
                                        <div class="widget-content-left">
                                            <div class="widget-heading">Total Modal</div>
                                            <div class="widget-subheading">Dari Produksi</div>
                                        </div>
                                        <div class="widget-content-right">
                                            <div class="widget-numbers text-danger">Rp <?= number_format($total_modal, 0, ',', '.'); ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="widget-content">
                                    <div class="widget-content-wrapper">
                                        <div class="widget-content-left">
                                            <div class="widget-heading">Estimasi Laba</div>
                                            <div class="widget-subheading">Pendapatan - Modal</div>
                                        </div>
                                        <div class="widget-content-right">
                                            <div class="widget-numbers text-primary">Rp <?= number_format($laba_periode, 0, ',', '.'); ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <h5 class="card-title">Penjualan per Produk</h5>
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Terjual (Pcs)</th>
                                    <th>Pendapatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($sales_by_product)) : ?>
                                    <?php foreach ($sales_by_product as $item) : ?>
                                        <tr>
                                            <td><?= esc($item['nama_produk']); ?></td>
                                            <td><?= esc($item['total_jumlah']); ?></td>
                                            <td>Rp <?= number_format(esc($item['total_pendapatan']), 0, ',', '.'); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="3" class="text-center">Tidak ada data penjualan pada periode ini.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <h5 class="card-title">Penjualan per Cabang</h5>
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>Cabang</th>
                                    <th>Terjual (Pcs)</th>
                                    <th>Pendapatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($sales_by_cabang)) : ?>
                                    <?php foreach ($sales_by_cabang as $item) : ?>
                                        <tr>
                                            <td><?= esc($item['nama_cabang'] ?? 'Tidak Diketahui'); ?></td>
                                            <td><?= esc($item['total_terjual_cabang']); ?></td>
                                            <td>Rp <?= number_format(esc($item['total_pendapatan_cabang']), 0, ',', '.'); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="3" class="text-center">Tidak ada data penjualan pada periode ini.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-7">
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <h5 class="card-title">Log Produksi</h5>
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>Waktu</th>
                                    <th>Produk</th>
                                    <th>Jumlah</th>
                                    <th>Total Modal</th>
                                    <th>Cabang</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($production_log)) : ?>
                                    <?php foreach ($production_log as $item) : ?>
                                        <tr>
                                            <td><?= date('d/m/y H:i', strtotime($item['tgl_produksi'])); ?></td>
                                            <td><?= esc($item['nama_produk']); ?></td>
                                            <td><?= esc($item['jumlah_hasil']); ?> Pcs</td>
                                            <td>Rp <?= number_format(esc($item['total_modal']), 0, ',', '.'); ?></td>
                                            <td><?= esc($item['nama_cabang']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="5" class="text-center">Tidak ada data produksi pada periode ini.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <h5 class="card-title">Ringkasan Produksi (per Cabang)</h5>
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>Cabang</th>
                                    <th>Total Produksi (Pcs)</th>
                                    <th>Total Modal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($production_summary)) : ?>
                                    <?php foreach ($production_summary as $item) : ?>
                                        <tr>
                                            <td><?= esc($item['nama_cabang']); ?></td>
                                            <td><?= esc($item['total_produksi_pcs']); ?></td>
                                            <td>Rp <?= number_format(esc($item['total_modal_produksi']), 0, ',', '.'); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="3" class="text-center">Tidak ada data produksi pada periode ini.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php echo view('partials/footer') ?>