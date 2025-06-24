<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('/login', 'Login::index');
$routes->post('/login/authenticate', 'Login::authenticate');
$routes->get('/logout', 'Login::logout');


/// cabang
$routes->get('/cabang', 'cabang::index');
$routes->get('/cabang/create', 'cabang::create');
$routes->get('/cabang/edit/(:num)', 'cabang::edit/$1');
$routes->post('/cabang/process', 'cabang::process');
$routes->post('/cabang/edit/process', 'cabang::edit_process');
$routes->get('/cabang/delete/(:num)', 'cabang::delete/$1');


/// karyawan
$routes->get('/karyawan', 'karyawan::index');
$routes->get('/karyawan/create', 'karyawan::create');
$routes->get('/karyawan/edit/(:num)', 'karyawan::edit/$1');
$routes->post('/karyawan/process', 'karyawan::process');
$routes->post('/karyawan/edit/process', 'karyawan::edit_process');
$routes->get('/karyawan/delete/(:num)', 'karyawan::delete/$1');

/// user
$routes->get('/user', 'user::index');
$routes->get('/user/create', 'user::create');
$routes->get('/user/edit/(:num)', 'user::edit/$1');
$routes->post('/user/process', 'user::process');
$routes->get('/user/edit/process/(:num)', 'user::edit_process/$1');
$routes->get('/user/delete/(:num)', 'user::delete/$1');


//produksi
$routes->get('/produksi', 'produksi::index');
$routes->get('/produksi/create', 'produksi::create');
$routes->post('/produksi/process', 'produksi::process');


//produk
$routes->get('/produk', 'Produk::index');
$routes->get('/produk/create', 'Produk::create');
$routes->get('/produk/edit/(:num)', 'Produk::edit/$1');
$routes->post('/produk/process', 'Produk::process');
$routes->post('/produk/edit/process', 'Produk::edit_process');
$routes->get('/produk/delete/(:num)', 'Produk::delete/$1');

//bahan baku
$routes->get('/bahanbaku', 'BahanBaku::index');
$routes->get('/bahanbaku/create', 'BahanBaku::create');
$routes->get('/bahanbaku/edit/(:num)', 'BahanBaku::edit/$1');
$routes->post('/bahanbaku/process', 'BahanBaku::process');
$routes->post('/bahanbaku/edit_process', 'BahanBaku::edit_process');
$routes->get('/bahanbaku/delete/(:num)', 'BahanBaku::delete/$1');


//pemjualan
$routes->get('/penjualan', 'Penjualan::index');
$routes->get('/penjualan/create', 'Penjualan::create');
$routes->post('/penjualan/process', 'Penjualan::process');


//laporan
$routes->get('/penjualan/report', 'laporan::index');
$routes->get('/laporan/penjualan/download_pdf', 'laporan::downloadPdf');
