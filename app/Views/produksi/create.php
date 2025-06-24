<?php echo view('partials/header') ?>
<?php echo view('partials/sidebar') ?>

<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
        </div>

        <div class="main-card mb-3 card">
            <?php if (session()->getFlashdata('error')) : ?>
                <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                    <?= session()->getFlashdata('error'); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
            <?php endif; ?>

            <div class="card-body">
                <h5 class="card-title">Input Bahan Produksi</h5>
                <br>
                <form class="needs-validation" action="<?= base_url('produksi/process'); ?>" method="post" novalidate>
                    <?= csrf_field(); ?>

                    <h6>Bahan Baku Utama</h6>
                    <hr>
                    <div class="form-row">
                        <div class="col-md-3 mb-3">
                            <label for="tepung">Tepung Terigu (Kg)</label>
                            <input type="number" step="any" class="form-control" name="tepung" id="tepung" placeholder="Contoh: 1000" value="<?= old('tepung'); ?>" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="gula">Gula (Kg)</label>
                            <input type="number" step="any" class="form-control" name="gula" id="gula" placeholder="Contoh: 175" value="<?= old('gula'); ?>" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="telur">Telur (butir)</label>
                            <input type="number" class="form-control" name="telur" id="telur" placeholder="Contoh: 3" value="<?= old('telur'); ?>" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="margarin">Margarin (gram)</label>
                            <input type="number" step="any" class="form-control" name="margarin" id="margarin" placeholder="Contoh: 100" value="<?= old('margarin'); ?>" required>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <h6>Bahan Varian (Opsional)</h6>
                        <button type="button" id="add-variant-btn" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Tambah Varian</button>
                    </div>
                    <hr>
                    <div id="variant-container">
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <h6>Bahan Topping (Opsional)</h6>
                        <button type="button" id="add-topping-btn" class="btn btn-sm btn-info"><i class="fa fa-plus"></i> Tambah Topping</button>
                    </div>
                    <hr>
                    <div id="topping-container">
                    </div>


                    <h6 class="mt-4">Informasi Produksi</h6>
                    <hr>
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="id_cabang">Diproduksi di Cabang</label>
                            <select class="form-control" name="id_cabang" id="id_cabang" required>
                                <option value="" disabled selected>Pilih Cabang...</option>
                                <?php foreach ($cabang as $row) : ?>
                                    <option value="<?= $row['id_cabang']; ?>"><?= $row['nama_cabang']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <button class="btn btn-primary mt-3" type="submit">
                        <i class="fa fa-cogs"></i> Proses Produksi
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="variant-template" style="display: none;">
    <div class="form-row align-items-end variant-row mb-3">
        <div class="col-md-6">
            <label>Nama Varian</label>
            <select class="form-control" name="variants[nama][]">
                <option value="">Pilih Varian...</option>
                <?php foreach ($pilihan_varian as $varian) : ?>
                    <option value="<?= $varian['nama_bahan']; ?>"><?= $varian['nama_bahan']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4">
            <label>Jumlah Varian (gram)</label>
            <input type="number" step="any" class="form-control" name="variants[jumlah][]" placeholder="Contoh: 250">
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-danger btn-block remove-variant-btn">Hapus</button>
        </div>
    </div>
</div>

<div id="topping-template" style="display: none;">
    <div class="form-row align-items-end topping-row mb-3">
        <div class="col-md-6">
            <label>Nama Topping</label>
            <select class="form-control" name="toppings[nama][]">
                <option value="">Pilih Topping...</option>
                <?php foreach ($pilihan_topping as $topping) : ?>
                    <option value="<?= $topping['nama_bahan']; ?>"><?= $topping['nama_bahan']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4">
            <label>Jumlah Topping (gram)</label>
            <input type="number" step="any" class="form-control" name="toppings[jumlah][]" placeholder="Contoh: 100">
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-danger btn-block remove-topping-btn">Hapus</button>
        </div>
    </div>
</div>

<?php echo view('partials/footer') ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const addVariantBtn = document.getElementById('add-variant-btn');
        const variantContainer = document.getElementById('variant-container');
        const variantTemplate = document.getElementById('variant-template');

        addVariantBtn.addEventListener('click', function() {
            const newVariantRow = variantTemplate.firstElementChild.cloneNode(true);
            variantContainer.appendChild(newVariantRow);
        });

        variantContainer.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('remove-variant-btn')) {
                e.target.closest('.variant-row').remove();
            }
        });

        const addToppingBtn = document.getElementById('add-topping-btn');
        const toppingContainer = document.getElementById('topping-container');
        const toppingTemplate = document.getElementById('topping-template');

        addToppingBtn.addEventListener('click', function() {
            const newToppingRow = toppingTemplate.firstElementChild.cloneNode(true);
            toppingContainer.appendChild(newToppingRow);
        });

        toppingContainer.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('remove-topping-btn')) {
                e.target.closest('.topping-row').remove();
            }
        });
    });
</script>