<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20mm;
            font-size: 10pt;
        }

        h1,
        h2,
        h3 {
            text-align: center;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .filter-info {
            text-align: center;
            margin-bottom: 20px;
            font-style: italic;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Laporan Penjualan</h1>
        <h3>Toko Roti Pancong Adyas</h3>
    </div>

    <div class="filter-info">
        <?php
        $filter_text = 'Semua data';
        if (!empty($start_date) && !empty($end_date)) {
            $filter_text = 'Periode: ' . date('d F Y', strtotime($start_date)) . ' s/d ' . date('d F Y', strtotime($end_date));
        } elseif (!empty($start_date)) {
            $filter_text = 'Dari Tanggal: ' . date('d F Y', strtotime($start_date));
        } elseif (!empty($end_date)) {
            $filter_text = 'Sampai Tanggal: ' . date('d F Y', strtotime($end_date));
        }
        echo $filter_text;
        ?>
    </div>

    <h2>Ringkasan Penjualan Berdasarkan Produk</h2>
    <table>
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

    <h2>Ringkasan Penjualan Berdasarkan Cabang</h2>
    <table>
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

    <h2>Ringkasan Penjualan Per Tanggal</h2>
    <table>
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
</body>

</html>