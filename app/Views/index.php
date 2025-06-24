<?php echo view('partials/header') ?>
<?php echo view('partials/sidebar') ?>

<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="fa-solid fa-chart-line icon-gradient bg-mean-fruit"></i>
                    </div>
                    <div><?= esc($page_title); ?>
                        <div class="page-title-subheading"><?= esc($page_subheading); ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 col-xl-4">
                <div class="card mb-3 widget-content bg-midnight-bloom">
                    <div class="widget-content-wrapper text-white">
                        <div class="widget-content-left">
                            <div class="widget-heading">Total Penjualan Hari Ini (Rp)</div>
                            <div class="widget-subheading">Total pendapatan dari transaksi hari ini</div>
                        </div>
                        <div class="widget-content-right">
                            <div class="widget-numbers text-white"><span>Rp <?= number_format($total_penjualan_umum, 0, ',', '.'); ?></span></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-4">
                <div class="card mb-3 widget-content bg-arielle-smile">
                    <div class="widget-content-wrapper text-white">
                        <div class="widget-content-left">
                            <div class="widget-heading">Total Produksi Hari Ini (Pcs)</div>
                            <div class="widget-subheading">Jumlah total produk yang dihasilkan hari ini</div>
                        </div>
                        <div class="widget-content-right">
                            <div class="widget-numbers text-white"><span><?= number_format($total_produksi_umum, 0, ',', '.'); ?></span></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-4">
                <div class="card mb-3 widget-content bg-grow-early">
                    <div class="widget-content-wrapper text-white">
                        <div class="widget-content-left">
                            <div class="widget-heading">Produk Terjual Hari Ini (Pcs)</div>
                            <div class="widget-subheading">Jumlah produk yang terjual hari ini</div>
                        </div>
                        <div class="widget-content-right">
                            <div class="widget-numbers text-white"><span><?= number_format($total_produk_terjual_hari_ini, 0, ',', '.'); ?></span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3 card">
                    <div class="card-header-tab card-header-tab-animation card-header">
                        <div class="card-header-title">
                            <i class="header-icon lnr-apartment icon-gradient bg-love-kiss"> </i>
                            Perbandingan Produksi per Cabang (Pcs) Hari Ini
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="production-chart-tab">
                                <canvas id="productionChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3 card">
                    <div class="card-header-tab card-header">
                        <div class="card-header-title">
                            <i class="header-icon lnr-rocket icon-gradient bg-tempting-azure"> </i>
                            Perbandingan Penjualan per Cabang (Pcs) Hari Ini
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="sales-chart-tab">
                                <canvas id="salesChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <?php echo view('partials/footer') ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var productionCtx = document.getElementById('productionChart').getContext('2d');
        var salesCtx = document.getElementById('salesChart').getContext('2d');

        var labels = <?= $chart_labels ?>;
        var productionData = <?= $production_data ?>;
        var salesData = <?= $sales_data ?>;

        function generateColors(numColors) {
            var colors = [];
            for (var i = 0; i < numColors; i++) {
                var hue = (i * 137.508) % 360;
                colors.push('hsl(' + hue + ', 70%, 60%)');
            }
            return colors;
        }

        var productionColors = generateColors(labels.length);
        var salesColors = generateColors(labels.length);

        Chart.register(ChartDataLabels);

        new Chart(productionCtx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Total Produksi (Pcs)',
                    data: productionData,
                    backgroundColor: productionColors,
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'right',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed !== null) {
                                    const total = context.dataset.data.reduce((sum, val) => sum + val, 0);
                                    const percentage = (context.parsed / total * 100).toFixed(2) + '%';
                                    label += new Intl.NumberFormat('id-ID').format(context.parsed) + ' Pcs (' + percentage + ')';
                                }
                                return label;
                            }
                        }
                    },
                    datalabels: {
                        display: false
                    }
                }
            }
        });

        new Chart(salesCtx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Total Penjualan (Pcs)',
                    data: salesData,
                    backgroundColor: salesColors,
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'right',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed !== null) {
                                    const total = context.dataset.data.reduce((sum, val) => sum + val, 0);
                                    const percentage = (context.parsed / total * 100).toFixed(2) + '%';
                                    label += new Intl.NumberFormat('id-ID').format(context.parsed) + ' Pcs (' + percentage + ')';
                                }
                                return label;
                            }
                        }
                    },
                    datalabels: {
                        display: false
                    }
                }
            }
        });
    });
</script>