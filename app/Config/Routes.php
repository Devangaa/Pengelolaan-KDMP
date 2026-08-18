<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->group('', ['filter' => 'guest'], static function ($routes) {
    $routes->get('/', 'GuestController::index');
    $routes->get('/produk', 'GuestController::products');
    $routes->get('/produk/saring', 'GuestController::filterProducts');
    $routes->get('/tentang-kami', 'GuestController::about');
    
    $routes->get('login', 'AuthController::login');
    $routes->post('login', 'AuthController::loginProcess', ['filter' => 'csrf']);
    $routes->get('/lupa-kata-sandi', 'AuthController::forgotPassword');
});

$routes->group('', ['filter' => 'role'], static function ($routes) {
    $routes->get('dasbor', 'DashboardController::index');
    $routes->post('logout', 'AuthController::logout', ['filter' => 'csrf']);
});

$routes->group('', ['filter' => 'role:kasir'], static function ($routes) {
    $routes->get('katalog', 'Cashier\ProductCatalogController::index');
    $routes->get('katalog/saring', 'Cashier\ProductCatalogController::filterProducts');

    $routes->get('transaksi', 'Cashier\TransactionController::index');
    $routes->get('transaksi/(:segment)', 'Cashier\TransactionController::detail/$1');
    $routes->get('transaksi/(:segment)/nota', 'Cashier\TransactionController::downloadNota/$1');   

    $routes->get('pos', 'Cashier\PosController::index');
    $routes->post('pos/mulai-shift', 'Cashier\PosController::startShift', ['filter' => 'csrf']);
    $routes->post('pos/tutup-shift', 'Cashier\PosController::closeShift', ['filter' => 'csrf']);
    $routes->post('pos/checkout', 'Cashier\PosController::checkout', ['filter' => 'csrf']);
    $routes->post('pos/cari-barcode', 'Cashier\PosController::searchByBarcode');

    $routes->get('rekap_shift', 'Cashier\ShiftReportController::index');
});