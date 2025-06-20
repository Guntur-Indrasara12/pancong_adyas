<div class="app-sidebar sidebar-shadow">
    <div class="app-header__logo">
        <div class="logo-src"></div>
        <div class="header__pane ml-auto">
            <div><button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar"><span class="hamburger-box"><span class="hamburger-inner"></span></span></button></div>
        </div>
    </div>
    <div class="app-header__mobile-menu">
        <div><button type="button" class="hamburger hamburger--elastic mobile-toggle-nav"><span class="hamburger-box"><span class="hamburger-inner"></span></span></button></div>
    </div>
    <div class="app-header__menu">
        <span><button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav"><span class="btn-icon-wrapper"><i class="fa fa-ellipsis-v fa-w-6"></i></span></button></span>
    </div>
    <div class="scrollbar-sidebar">
        <div class="app-sidebar__inner">
            <ul class="vertical-nav-menu">
                <li class="app-sidebar__heading">Dashboards</li>
                <li>
                    <a href="<?php echo base_url('/'); ?>" class="<?php echo (current_url() == base_url('/')) ? 'mm-active' : ''; ?>">
                        <i class="metismenu-icon pe-7s-rocket"></i>
                        Dashboard
                    </a>
                </li>
                <li class="app-sidebar__heading">Master</li>
                <li>
                    <a href="<?php echo base_url('cabang'); ?>" class="<?php echo (strpos(current_url(), 'cabang') !== false) ? 'mm-active' : ''; ?>">
                        <i class="metismenu-icon pe-7s-map-marker"></i> Data Cabang
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url('karyawan'); ?>" class="<?php echo (strpos(current_url(), 'karyawan') !== false) ? 'mm-active' : ''; ?>">
                        <i class="metismenu-icon pe-7s-users"></i> Data Karyawan
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url('produk'); ?>" class="<?php echo (strpos(current_url(), 'kategori') !== false) ? 'mm-active' : ''; ?>">
                        <i class="metismenu-icon pe-7s-albums"></i> Data Produk
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url('bahanbaku'); ?>" class="<?php echo (strpos(current_url(), 'kategori') !== false) ? 'mm-active' : ''; ?>">
                        <i class="metismenu-icon pe-7s-albums"></i> Data Bahan Baku
                    </a>
                </li>
                <li class="app-sidebar__heading">Transaksi</li>
                <li>
                    <a href="<?php echo base_url('/produksi'); ?>" class="<?php echo (strpos(current_url(), 'bahanbaku') !== false) ? 'mm-active' : ''; ?>">
                        <i class="metismenu-icon pe-7s-box2"></i>Produksi
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url('penjualan'); ?>" class="<?php echo (strpos(current_url(), 'penjualan') !== false) ? 'mm-active' : ''; ?>">
                        <i class="metismenu-icon pe-7s-cart"></i> Penjualan
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url('laporan'); ?>" class="<?php echo (strpos(current_url(), 'laporan') !== false) ? 'mm-active' : ''; ?>">
                        <i class="metismenu-icon pe-7s-news-paper"></i> Laporan
                    </a>
                </li>
                <li class="app-sidebar__heading">User</li>
                <li>
                    <a href="<?php echo base_url('user'); ?>" class="<?php echo (strpos(current_url(), 'user') !== false) ? 'mm-active' : ''; ?>">
                        <i class="metismenu-icon pe-7s-user"></i> Data User
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>