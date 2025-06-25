<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan</title>
    <style>
        @page {
            margin: 25mm 20mm;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10pt;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 18pt;
            color: #000;
        }

        .header h3 {
            margin: 5px 0;
            font-size: 12pt;
            font-weight: normal;
        }

        .period-info {
            text-align: center;
            font-size: 11pt;
            margin-bottom: 25px;
        }

        .summary-container {
            width: 100%;
            margin-bottom: 25px;
            border-spacing: 10px;
            border-collapse: separate;
        }

        .summary-box {
            border: 1px solid #ccc;
            padding: 15px;
            text-align: center;
            width: 30%;
            border-radius: 8px;
        }

        .summary-box .title {
            font-size: 10pt;
            font-weight: bold;
            color: #555;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .summary-box .value {
            font-size: 14pt;
            font-weight: bold;
        }

        .text-success {
            color: #28a745;
        }

        .text-danger {
            color: #dc3545;
        }

        .text-primary {
            color: #007bff;
        }

        h2.section-title {
            font-size: 14pt;
            color: #333;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
            margin-top: 25px;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
            font-size: 10pt;
        }

        td {
            font-size: 9.5pt;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .no-data {
            text-align: center;
            font-style: italic;
            color: #777;
            padding: 20px;
        }

        .footer {
            position: fixed;
            bottom: -20mm;
            left: 0;
            right: 0;
            height: 20mm;
            text-align: center;
            font-size: 8pt;
            color: #888;
        }

        .footer .page-number:after {
            content: counter(page);
        }
    </style>
</head>

<body>
    <div class="footer">
        Laporan dihasilkan pada <?= date('d F Y H:i:s'); ?> | Halaman <span class="page-number"></span>
    </div>

    <div class="header">
        <h1>Laporan Keuangan & Operasional</h1>
        <h3>Toko Roti Pancong Adyas</h3>
    </div>

    <div class="period-info">
        <strong>Periode Laporan:</strong>
        <?= date('d F Y', strtotime($start_date)); ?> &mdash; <?= date('d F Y', strtotime($end_date)); ?>
    </div>

    <table class="summary-container">
        <tr>
            <td class="summary-box">
                <div class="title">Total Pendapatan</div>
                <div class="value text-success">Rp <?= number_format($total_pendapatan, 0, ',', '.'); ?></div>
            </td>
            <td class="summary-box">
                <div class="title">Total Modal</div>
                <div class="value text-danger">Rp <?= number_format($total_modal, 0, ',', '.'); ?></div>
            </td>
            <td class="summary-box">
                <div class="title">Estimasi Laba</div>
                <div class="value text-primary">Rp <?= number_format($laba_periode, 0, ',', '.'); ?></div>
            </td>
        </tr>
    </table>


    <h2 class="section-title">Ringkasan Penjualan</h2>

    <strong>Berdasarkan Produk</strong>
    <table>
        <thead>
            <tr>
                <th>Produk</th>
                <th class="text-center">Terjual (Pcs)</th>
                <th class="text-right">Total Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($sales_by_product)): ?>
                <?php foreach ($sales_by_product as $item): ?>
                    <tr>
                        <td><?= esc($item['nama_produk']); ?></td>
                        <td class="text-center"><?= esc($item['total_jumlah']); ?></td>
                        <td class="text-right">Rp <?= number_format(esc($item['total_pendapatan']), 0, ',', '.'); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" class="no-data">Tidak ada data penjualan produk.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <strong>Berdasarkan Cabang</strong>
    <table>
        <thead>
            <tr>
                <th>Cabang</th>
                <th class="text-center">Terjual (Pcs)</th>
                <th class="text-right">Total Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($sales_by_cabang)): ?>
                <?php foreach ($sales_by_cabang as $item): ?>
                    <tr>
                        <td><?= esc($item['nama_cabang'] ?? 'Tidak Diketahui'); ?></td>
                        <td class="text-center"><?= esc($item['total_terjual_cabang']); ?></td>
                        <td class="text-right">Rp <?= number_format(esc($item['total_pendapatan_cabang']), 0, ',', '.'); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" class="no-data">Tidak ada data penjualan per cabang.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <h2 class="section-title">Ringkasan Produksi</h2>

    <strong>Berdasarkan Cabang</strong>
    <table>
        <thead>
            <tr>
                <th>Cabang</th>
                <th class="text-center">Total Produksi (Pcs)</th>
                <th class="text-right">Total Modal</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($production_summary)): ?>
                <?php foreach ($production_summary as $item): ?>
                    <tr>
                        <td><?= esc($item['nama_cabang']); ?></td>
                        <td class="text-center"><?= esc($item['total_produksi_pcs']); ?></td>
                        <td class="text-right">Rp <?= number_format(esc($item['total_modal_produksi']), 0, ',', '.'); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" class="no-data">Tidak ada ringkasan produksi.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <strong>Log Produksi Rinci</strong>
    <table>
        <thead>
            <tr>
                <th>Waktu Produksi</th>
                <th>Produk</th>
                <th class="text-center">Jumlah</th>
                <th class="text-right">Modal</th>
                <th>Cabang</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($production_log)): ?>
                <?php foreach ($production_log as $item): ?>
                    <tr>
                        <td><?= date('d M Y, H:i', strtotime($item['tgl_produksi'])); ?></td>
                        <td><?= esc($item['nama_produk']); ?></td>
                        <td class="text-center"><?= esc($item['jumlah_hasil']); ?> Pcs</td>
                        <td class="text-right">Rp <?= number_format(esc($item['total_modal']), 0, ',', '.'); ?></td>
                        <td><?= esc($item['nama_cabang']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="no-data">Tidak ada data log produksi.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>

</html>