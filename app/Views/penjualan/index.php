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
                        <div class="page-title-subheading"> Informasi lengkap mengenai transaksi penjualan.
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="main-card mb-3 card">
                    <?php if (session()->getFlashdata('success')) : ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= session()->getFlashdata('success'); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('error')) : ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= session()->getFlashdata('error'); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title mb-0">Data Penjualan</h3>
                            <a href="<?= base_url('/penjualan/create'); ?>" class="btn btn-primary">
                                Tambah Penjualan
                            </a>
                        </div>

                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="dataTables_length"></div>
                                <div class="dataTables_info"></div>
                            </div>

                            <table id="datatable" class="table text-center table-hover datatable">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Produk</th>
                                        <th class="text-center">Cabang</th>
                                        <th class="text-center">Jumlah Terjual</th>
                                        <th class="text-center">Harga Satuan</th>
                                        <th class="text-center">Total Harga</th>
                                        <th class="text-center">Tanggal Penjualan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($penjualan as $key => $row) { ?>
                                        <tr>
                                            <td><?php echo $key + 1; ?></td>
                                            <td><?php echo $row['nama_produk']; ?></td>
                                            <td><?php echo $row['nama_cabang']; ?></td>
                                            <td><?php echo $row['jumlah_terjual']; ?></td>
                                            <td>Rp <?php echo number_format($row['harga_jual_satuan'], 0, ',', '.'); ?></td>
                                            <td>Rp <?php echo number_format($row['total_harga'], 0, ',', '.'); ?></td>
                                            <td><?php echo date('d F Y H:i:s', strtotime($row['tgl_penjualan'])); ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo view('partials/footer') ?>