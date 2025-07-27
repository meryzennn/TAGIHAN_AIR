<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Auth::index');
$routes->get('/login', 'Auth::index');
$routes->post('/auth/login', 'Auth::login');
$routes->get('/logout', 'Auth::logout');

$routes->get('/register', 'Auth::registerForm');
$routes->post('/register/save', 'Auth::register');

$routes->group('', ['filter' => 'auth'], function($routes) {
    // Dashboard
    $routes->get('/dashboard/admin', 'Dashboard::admin');
    $routes->get('/user/dashboard', 'User::index');


    // adminCRUD
    $routes->get('/pelanggan', 'Pelanggan::index');
    $routes->get('/pelanggan/create', 'Pelanggan::create');
    $routes->post('/pelanggan/store', 'Pelanggan::store');
    $routes->get('/pelanggan/edit/(:num)', 'Pelanggan::edit/$1');
    $routes->post('/pelanggan/update/(:num)', 'Pelanggan::update/$1');
    $routes->get('/pelanggan/delete/(:num)', 'Pelanggan::delete/$1');
    $routes->get('/pelanggan/export', 'Pelanggan::export');
    // Penggunaan Air
    $routes->get('/penggunaan-air', 'PenggunaanAir::index');
    $routes->get('/penggunaan-air/create', 'PenggunaanAir::create');
    $routes->post('/penggunaan-air/store', 'PenggunaanAir::store');
    $routes->get('penggunaan-air/delete/(:num)', 'PenggunaanAir::delete/$1');
    $routes->get('penggunaan-air/edit/(:num)', 'PenggunaanAir::edit/$1');
    $routes->post('penggunaan-air/update/(:num)', 'PenggunaanAir::update/$1');
    $routes->get('/penggunaan-air/export', 'PenggunaanAir::export');

    // Tagihan
    $routes->get('/tagihan', 'Tagihan::index');
    $routes->get('tagihan/generate', 'Tagihan::generate');
    $routes->get('/tagihan/export', 'Tagihan::export');

    // Laporan
    $routes->get('/admin/laporan', 'Laporan::index');
    $routes->get('/admin/laporan/export/excel', 'Laporan::exportExcel');
    $routes->get('/admin/laporan/pdf', 'Laporan::exportPDF');
    // Tarif Air
    $routes->get('/tarif', 'Tarif::index');
    $routes->post('/tarif/update', 'Tarif::update');

    // User
    $routes->get('payment/index/(:num)', 'Payment::index/$1');
    $routes->get('payment/finish', 'Payment::finish');
    $routes->get('payment/(:num)', 'Payment::index/$1');
    $routes->get('struk/(:num)', 'Struk::cetak/$1');


    







});

