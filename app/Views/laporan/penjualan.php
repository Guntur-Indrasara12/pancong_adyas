<?php echo view('partials/header') ?>
<?php echo view('partials/sidebar') ?>

<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="fa-solid fa-chart-line"></i>
                    </div>
                    <div>Laporan Penjualan
                        <div class="page-title-subheading"> Ringkasan dan analisis data penjualan.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <h5 class="card-title">Filter Laporan</h5>
                        <form action="<?= base_url('/penjualan/report'); ?>" method="get" class="form-inline mb-3">
                            <label for="start_date" class="mr-2">Dari Tanggal:</label>
                            <input type="date" class="form-control mr-3" id="start_date" name="start_date" value="<?= esc($start_date ?? date('Y-m-d', strtotime('-30 days'))); ?>">
                            <label for="end_date" class="mr-2">Sampai Tanggal:</label>
                            <input type="date" class="form-control mr-3" id="end_date" name="end_date" value="<?= esc($end_date ?? date('Y-m-d')); ?>">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="<?= base_url('/penjualan/report'); ?>" class="btn btn-secondary ml-2">Reset Filter</a>

                            <?php
                            $pdf_url = base_url('/laporan/penjualan/download_pdf');
                            if (!empty($start_date) || !empty($end_date)) {
                                $pdf_url .= '?';
                                if (!empty($start_date)) {
                                    $pdf_url .= 'start_date=' . esc($start_date) . '&';
                                }
                                if (!empty($end_date)) {
                                    $pdf_url .= 'end_date=' . esc($end_date);
                                }
                                $pdf_url = rtrim($pdf_url, '&');
                            }
                            ?>
                            <a href="<?= $pdf_url; ?>" class="btn btn-success ml-3" target="_blank">
                                <i class="fa-solid fa-file-pdf mr-1"></i> Unduh PDF
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <h5 class="card-title">Ringkasan Penjualan Berdasarkan Produk</h5>
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Total Terjual (Pcs)</th>
                                    <th>Total Pendapatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($sales_by_product)): ?>
                                    <?php foreach ($sales_by_product as $item): ?>
                                        <tr>
                                            <td><?= esc($item['nama_produk']); ?></td>
                                            <td><?= esc($item['total_jumlah']); ?></td>
                                            <td>Rp <?= number_format(esc($item['total_pendapatan']), 0, ',', '.'); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="text-center">Tidak ada data penjualan produk.</td>
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
                        <h5 class="card-title">Ringkasan Penjualan Berdasarkan Cabang</h5>
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>Cabang</th>
                                    <th>Total Terjual (Pcs)</th>
                                    <th>Total Pendapatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($sales_by_cabang)): ?>
                                    <?php foreach ($sales_by_cabang as $item): ?>
                                        <tr>
                                            <td><?= esc($item['nama_cabang'] ?? 'Tidak Diketahui'); ?></td>
                                            <td><?= esc($item['total_terjual_cabang']); ?></td>
                                            <td>Rp <?= number_format(esc($item['total_pendapatan_cabang']), 0, ',', '.'); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="text-center">Tidak ada data penjualan per cabang.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <h5 class="card-title">Ringkasan Penjualan Per Tanggal</h5>
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Total Terjual (Pcs)</th>
                                    <th>Total Pendapatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($sales_by_date)): ?>
                                    <?php foreach ($sales_by_date as $item): ?>
                                        <tr>
                                            <td><?= esc(date('d F Y', strtotime($item['tanggal']))); ?></td>
                                            <td><?= esc($item['total_terjual_harian']); ?></td>
                                            <td>Rp <?= number_format(esc($item['total_pendapatan_harian']), 0, ',', '.'); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="text-center">Tidak ada data penjualan untuk rentang tanggal ini.</td>
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